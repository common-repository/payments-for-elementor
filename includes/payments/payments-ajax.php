<?php

class EMEP_PaymentAjax {

	public function __construct() {
		add_action( 'wp_ajax_emep_ajax_get_payment_intent', array( $this, 'create_payment_intent' ) );
		add_action( 'wp_ajax_nopriv_emep_ajax_get_payment_intent', array( $this, 'create_payment_intent' ) );

		add_action( 'wp_ajax_emep_process_payment', array( $this, 'add_payment' ) );
		add_action( 'wp_ajax_nopriv_emep_process_payment', array( $this, 'add_payment' ) );

		add_action( 'wp_ajax_emep_issue_refund', array( $this, 'issue_refund' ) );
	}

	/**
	 * Creates a PaymentIntent in Stripe
	 *
	 * @return void
	 */
	public function create_payment_intent() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'emep_ajax_get_payment_intent' ) ) {
			die( 'Failed' );
		}

		emep_autoload();

		$post_id = (int) $_POST['post_id'];
		$post = get_post( $post_id );

		// See your keys here: https://dashboard.stripe.com/account/apikeys
		\Stripe\Stripe::setApiKey( emep_get_stripe_secret_key() );

		$name = sanitize_text_field( ! empty( $_POST['name'] ) ? $_POST['name'] : '' );
		$email = sanitize_email( ! empty( $_POST['email'] ) ? $_POST['email'] : '' );
		$user_id = is_numeric( $_POST['user_id'] ) ? (int) $_POST['user_id'] : false;

		$cutomer = false;
		$customer_id = null;

		// First, check if there's already a user
		$user = empty( $user_id ) ? get_user_by( 'email', $email ) : get_user_by( 'ID', $user_id );

		// WP_User exists
		if ( ! empty( $user ) && is_a( $user, 'WP_User' ) ) {
			$user_id = $user->ID;
			// User is set up in Stripe, so grab the Stripe customer ID
			$customer_id = sanitize_text_field( get_user_meta( $user_id, 'emep_stripe_customer_id', true ) );
		} else if ( ! empty( $email ) ) { // WP_User does not exist, so create it
			$name_parts = explode( ' ', $name );
			$user_id = wp_insert_user( apply_filters( 'emep_insert_user_args', array(
				'user_login' => $email,
				'user_email' => $email,
				'first_name' => $name_parts[0],
				'last_name' => $name_parts[1]
			) ) );
		}

		// Create customer record in Stripe if needed
		// This will happen if the user was just created, or an existing user has not yet been set up in Stripe
		if ( empty( $customer_id ) ) {
			try {

				$customer = \Stripe\Customer::create( apply_filters( 'emep_stripe_create_customer_args', array(
					'email' => $email,
					'name' => $name,
					'metadata' => array(
						'wordpress_user_id' => $user_id
					)
				) ) );
				$customer_id = $customer->id;

				// We have a WP_User by this point, so store the Stripe customer ID as user meta
				update_user_meta( $user_id, 'emep_stripe_customer_id', $customer_id );

			} catch (Exception $e) {}
		}

		// Create payment intent
		$response = wp_remote_post( 'https://elegantmodules.com/wp-json/emapi/v1/intent/create', array(
			'body' => array(
				'account_id' => esc_html( emep_get_option( 'emep_stripe_account_id' ) ),
				'test_mode' => esc_html( emep_get_option( 'emep_test_mode' ) ),
				'amount' => sanitize_text_field( $_POST['amount'] ),
				'currency' => esc_html( strtolower( emep_get_currency() ) ),
				'description' => sanitize_text_field( $_POST['description'] ),
				'email' => esc_html( $email ),
				'customer_id' => esc_html( $customer_id ),
				'descriptor' => esc_html( emep_get_option( 'emep_stripe_statement_descriptor' ) ),
				'post_id' => (int) $post_id,
				'post_title' => esc_html( $post->post_title ),
				'name' => esc_html( $name ),
				'user_id' => esc_html( $user_id ),
				'license_key' => esc_html( emep_get_option( 'emep_license_key' ) )
			)
		) );

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		$intent_secret = ! empty( $body['data']['intent_client_secret'] ) ? sanitize_text_field( $body['data']['intent_client_secret'] ) : false;

		if ( false === $intent_secret ) {
			wp_send_json_error( array(
				'message' => __( 'Payment intent could not be created.', 'payments-for-elementor' )
			) );
		}

		do_action( 'emep_create_payment_intent', $intent );

		wp_send_json_success( array(
			'intent_client_secret' => $intent_secret,
			'user_id' => $user_id
		) );
	}

	/**
	 * Create a new payment
	 */
	public function add_payment() {
		
		// Security check
		if ( empty( $_POST['emep_process_payment_nonce'] ) || ! wp_verify_nonce( $_POST['emep_process_payment_nonce'], 'emep_process_payment' ) ) {
			die( 'Failed' );
		}

		emep_autoload();
		\Stripe\Stripe::setApiKey( emep_get_stripe_secret_key() );

		$bad_intent = false;

		$name = sanitize_text_field( $_POST['emep_payment_name'] );
		$email = sanitize_text_field( $_POST['emep_payment_email'] );
		$intent_id = sanitize_text_field( $_POST['emep_payment_intent_id'] );
		$user_id = sanitize_text_field( $_POST['emep_payment_user_id'] );
		$amount = sanitize_text_field( $_POST['emep_payment_amount'] );
		$submitted_from = (int) sanitize_text_field( $_POST['emep_payment_post_id'] );
		$description = sanitize_text_field( $_POST['emep_payment_description'] );

		// Confirm the payment intent
		try {
			$intent = \Stripe\PaymentIntent::retrieve( $intent_id );
			if ( ! isset( $intent->id ) || empty( $intent->metadata->processed_by ) || 'elegant_modules_elementor_payments' != $intent->metadata->processed_by ) {
				$bad_intent = true;
			}
		} catch (Exception $e) {
			// Error - payment intent could not be validated
			$bad_intent = true;
		}

		// Bad intent, so exit by redirecting to the same page
		if ( true === $bad_intent ) {
			wp_redirect( esc_url( get_permalink( $submitted_from ) ) );
			exit;
		}

		do_action( 'emep_pre_insert_payment' );
		$time = time();
		$payment_slug = sprintf( 'payment-%s-%s-%s-%s', date( 'Y', $time ), date( 'm', $time ), date( 'd', $time ), $time );

		// Insert the payment into the database
		$post_id = wp_insert_post( apply_filters( 'emep_insert_payment_args', array(
			'post_title' => '',
			'post_name' => $payment_slug,
			'post_type' => 'emep_payment',
			'post_status' => 'emep-completed',
			'comment_status' => 'closed',
			'ping_status' => 'closed',
		) ), false );

		// Set the title using the payment ID
		// $order_title = esc_html__( "Payment #{$post_id}", 'payments-for-elementor' );
		wp_update_post( array(
			'ID' => $post_id,
			'post_title' => $post_id
		), false );

		// Add payment meta
		if ( false != $post_id ) {
			update_post_meta( $post_id, 'payment_status', 'complete' );
			update_post_meta( $post_id, 'customer_user_id', $user_id );
			update_post_meta( $post_id, 'payment_intent_id', $intent_id );
			update_post_meta( $post_id, 'payment_amount', $amount );
			update_post_meta( $post_id, 'payment_balance', $amount );
			update_post_meta( $post_id, 'submitted_from', $submitted_from );
			update_post_meta( $post_id, 'payment_description', $description );
			if ( emep_is_test_mode() ) {
				update_post_meta( $post_id, 'test_purchase', true );
			}
		}

		// Add the payment ID to the meta data in Stripe
		\Stripe\PaymentIntent::update( $intent_id, array(
			'metadata' => array(
				'payment_id' => $post_id
			)
		) );

		do_action( 'emep_insert_payment', $post_id );

		// Redirect or return response
		wp_redirect( add_query_arg(  array(
			'emep-payment-submitted' => 'success',
			'payment_id' => $post_id
		), esc_url( ! empty( $_POST['emep_process_payment_redirect'] ) ? $_POST['emep_process_payment_redirect'] : get_permalink( $submitted_from ) ) ) );
		exit;
	}

	/**
	 * Issue a refund
	 *
	 * @return void
	 */
	public function issue_refund() {
		
		// Security check on nonce and permissions
		if ( ! current_user_can( 'manage_options' ) || ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'emep_issue_refund' ) ) {
			die('Failed');
		}

		$payment_id = (int) $_GET['payment_id'];
		$post = get_post( $payment_id );
		$intent_id = get_post_meta( $post->ID, 'payment_intent_id', true );

		// Cannot be refunded
		if ( empty( $post ) || empty( $intent_id ) || 'emep_payment' !== $post->post_type || 'emep-refunded' === $post->post_status ) {
			die('Failed');
		}

		$amount = ! empty( $_GET['amount'] ) ? (float) $_GET['amount'] : 0;

		$refund_args = array(
			'payment_intent' => esc_html( $intent_id ),
			'amount' => $amount,
		);

		if ( ! empty( $_GET['reason'] ) ) {
			$refund_args['reason'] = sanitize_text_field( $_GET['reason'] );
		}

		do_action( 'emep_pre_issue_refund', $post );

		// Send refund request
		$refund_args = apply_filters( 'emep_stripe_create_refund_args', $refund_args );

		// Required elements not to be overridden with the filter
		$refund_args['account_id'] = esc_html( emep_get_option( '	emep_stripe_account_id' ) );
		$refund_args['test_mode'] = esc_html( emep_get_option( 'emep_test_mode' ) );
		$refund_args['token'] = esc_html( emep_get_stripe_secret_key() );

		$response = wp_remote_post( 'https://elegantmodules.com/wp-json/emapi/v1/intent/refund', array(
			'body' => $refund_args
		) );

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		$refund = ! empty( $body['data']['refund'] ) ? $body['data']['refund'] : false;

		if ( empty( $refund ) || ! isset( $refund['id'] ) ) {
			wp_die( __( 'The refund could not be processed. Please visit your Stripe dashboard.', 'payments-for-elementor' ) );
		}

		// Set post status to refunded on full refund
		if ( $amount >= (float) get_post_meta( $post->ID, 'payment_balance', true ) ) {
			wp_update_post( array(
				'ID' => $post->ID,
				'post_status' => 'emep-refunded'
			) );
		}

		$refunds = get_post_meta( $post->ID, 'payment_refunds', true );
		$refunds = is_array( $refunds ) ? $refunds : array();
		$refunds[] = array(
			'id' => sanitize_text_field( $refund['id'] ),
			'refund_amount' => sanitize_text_field( $amount ),
			'time' => (int) $refund['created'],
			'reason' => sanitize_text_field( $_GET['reason'] ),
			'balance_transaction' => (int) $refund['balance_transaction']
		);

		$balance = (float) get_post_meta( $post->ID, 'payment_balance', true );
		$balance -= $amount;
		update_post_meta( $post->ID, 'payment_balance', $balance );
		update_post_meta( $post->ID, 'payment_refunds', $refunds );
		do_action( 'emep_issue_refund', $post, $refund );

		// Send back to payment page
		wp_redirect( add_query_arg( array(
			'post' => $post->ID,
			'action' => 'edit'
		), admin_url( 'post.php' ) ) );
		exit;
	}

}

new EMEP_PaymentAjax;