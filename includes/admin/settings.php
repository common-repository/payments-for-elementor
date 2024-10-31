<?php

class EMEP_Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_submenu_pages' ) );
		add_action( 'admin_post_emep_settings', array( $this, 'save_settings' ) );
		add_action( 'admin_init', array( $this, 'process_connect' ) );
	}

	public function add_submenu_pages() {
		add_submenu_page(
			'edit.php?post_type=emep_payment', 
			__( 'Payments for Elementor Settings' ), 
			__( 'Settings', 'payments-for-elementor' ), 
			'manage_options', 
			'emep-settings', 
			array( $this, 'render_settings_page' )
		);
	}

	public function render_settings_page() {

		$return_url = urlencode( add_query_arg( array(
			'connect_nonce' => wp_create_nonce( 'emep_stripe_connect_nonce' ),
			'action' => 'emep_stripe_connect'
		), emep_get_settings_page_url() ) );

		$stripe_connect_url = add_query_arg( array(
			'return' => $return_url,
		), 'https://elegantmodules.com/wp-json/emapi/v1/connect' );

		include_once 'templates/settings-page.php';
	}

	/**
	 * Complete connection when returned from Stripe Connect authorization
	 *
	 * @return void
	 */
	public function process_connect() {

		if ( empty( $_GET['action'] ) || 'emep_stripe_connect' !== $_GET['action'] ) {
			return;
		}
		
		if ( empty( $_GET['connect_nonce'] ) || ! wp_verify_nonce( $_GET['connect_nonce'], 'emep_stripe_connect_nonce' ) ) {
			die('Failed');
		}

		$account_id = ! empty( $_GET['account_id'] ) ? sanitize_text_field( $_GET['account_id'] ) : '';
		$live_pub = ! empty( $_GET['live_pub'] ) ? sanitize_text_field( $_GET['live_pub'] ) : '';
		$test_pub = ! empty( $_GET['test_pub'] ) ? sanitize_text_field( $_GET['test_pub'] ) : '';
		$live_secret = ! empty( $_GET['live_secret'] ) ? sanitize_text_field( $_GET['live_secret'] ) : '';
		$test_secret = ! empty( $_GET['test_secret'] ) ? sanitize_text_field( $_GET['test_secret'] ) : '';
		$live_webhook_id = ! empty( $_GET['live_webhook_id'] ) ? sanitize_text_field( $_GET['live_webhook_id'] ) : '';
		$test_webhook_id = ! empty( $_GET['test_webhook_id'] ) ? sanitize_text_field( $_GET['test_webhook_id'] ) : '';

		update_option( 'emep_stripe_account_id', $account_id );
		update_option( 'emep_stripe_connect_status', 'connected' );
		update_option( 'emep_stripe_live_pub_key', $live_pub );
		update_option( 'emep_stripe_live_secret_key', $live_secret );
		update_option( 'emep_stripe_test_pub_key', $test_pub );
		update_option( 'emep_stripe_test_secret_key', $test_secret );

		wp_redirect( emep_get_settings_page_url() );
		exit;
	}

	/**
	 * Save settings
	 *
	 * @return void
	 */
	public function save_settings() {

		// Permission check
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'emep_settings' ) ) {
			die('Permission denied');
		}

		$test_mode = emep_is_ssl() ? (bool) $_POST['emep_test_mode'] : true;
		$stripe_statement_descriptor = ! empty( $_POST['emep_stripe_statement_descriptor'] ) ? sanitize_text_field( $_POST['emep_stripe_statement_descriptor'] ) : '';
		$stripe_live_pub_key = ! empty( $_POST['emep_stripe_live_pub_key'] ) ? sanitize_text_field( $_POST['emep_stripe_live_pub_key'] ) : '';
		$stripe_live_secret_key = ! empty( $_POST['emep_stripe_live_secret_key'] ) ? sanitize_text_field( $_POST['emep_stripe_live_secret_key'] ) : '';
		$stripe_test_pub_key = ! empty( $_POST['emep_stripe_test_pub_key'] ) ? sanitize_text_field( $_POST['emep_stripe_test_pub_key'] ) : '';
		$stripe_test_secret_key = ! empty( $_POST['emep_stripe_test_secret_key'] ) ? sanitize_text_field( $_POST['emep_stripe_test_secret_key'] ) : '';
		$currency = ! empty( $_POST['emep_currency'] ) ? sanitize_text_field( $_POST['emep_currency'] ) : '';
		$currency_position = ! empty( $_POST['emep_currency_position'] ) ? sanitize_text_field( $_POST['emep_currency_position'] ) : '';
		$thousand_sep = ! empty( $_POST['emep_thousand_sep'] ) ? sanitize_text_field( $_POST['emep_thousand_sep'] ) : '';
		$decimal_sep = ! empty( $_POST['emep_decimal_sep'] ) ? sanitize_text_field( $_POST['emep_decimal_sep'] ) : '';
		$decimals = ! empty( $_POST['emep_decimals'] ) ? sanitize_text_field( $_POST['emep_decimals'] ) : '';

		update_option( 'emep_test_mode', $test_mode );
		update_option( 'emep_stripe_statement_descriptor', $stripe_statement_descriptor );
		update_option( 'emep_stripe_live_pub_key', $stripe_live_pub_key );
		update_option( 'emep_stripe_live_secret_key', $stripe_live_secret_key );
		update_option( 'emep_stripe_test_pub_key', $stripe_test_pub_key );
		update_option( 'emep_stripe_test_secret_key', $stripe_test_secret_key );
		update_option( 'emep_currency', $currency );
		update_option( 'emep_currency_position', $currency_position );
		update_option( 'emep_thousand_sep', $thousand_sep );
		update_option( 'emep_decimal_sep', $decimal_sep );
		update_option( 'emep_decimals', $decimals );

		$current_license_status = emep_get_option( 'emep_license_status' );

		if ( ! empty( $_POST['emep_license_key'] ) && ( empty( $current_license_status ) || 'valid' !== $current_license_status ) ) {
			// License key provided, but no valid status --> connect
			EMEP_Licenses::activate_license( sanitize_text_field( $_POST['emep_license_key'] ) );
		} else if ( empty( $_POST['emep_license_key'] ) && ! empty( $current_license_status ) ) {
			// License removed, and existing status --> disconnect
			EMEP_Licenses::deactivate_license( sanitize_text_field( $_POST['emep_license_key'] ) );
		} else if ( empty( $_POST['emep_license_key'] ) ) {
			// Delete these just to be safe
			delete_option( 'emep_license_key' );
			delete_option( 'emep_license_status' );
		}

		do_action( 'emep_save_options' );

		wp_redirect( emep_get_settings_page_url() . '&settings-updated' );
		exit;
	}
}

new EMEP_Settings;