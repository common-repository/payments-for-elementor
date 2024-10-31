<?php do_action( 'emep_before_payment_form', $settings ); ?>
<form action="<?php echo add_query_arg( 'action', 'emep_process_payment', admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="emep-payment-form" class="emep-payment-form">
	<?php do_action( 'emep_payment_form_open', $settings ); ?>
	<?php if ( ! empty( $settings['enable_name'] ) && 'on' === $settings['enable_name'] ) : ?>
		<?php
			$name = '';
			if ( is_user_logged_in() ) {
				$first_name = get_user_meta( get_current_user_id(), 'first_name', true );
				$last_name = get_user_meta( get_current_user_id(), 'last_name', true );
				$name = "{$first_name} {$last_name}";
			}
		?>
		<div class="form-row">
			<label for="emep_payment_name"><?php esc_html_e( 'Your Name', 'emep-divi-stripe-payments' ); ?></label>
			<p><input type="text" class="emep-payment-form-input" id="emep_payment_name" name="emep_payment_name" value="<?php esc_attr_e( $name ); ?>" placeholder="Enter your name" required></p>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $settings['enable_email'] ) && 'on' === $settings['enable_email'] ) : ?>
		<?php
			$email = is_user_logged_in() ? wp_get_current_user()->user_email : '';
		?>
		<div class="form-row">
			<label for="emep_payment_email"><?php esc_html_e( 'Your Email', 'emep-divi-stripe-payments' ); ?></label>
			<p><input type="email" class="emep-payment-form-input" id="emep_payment_email" name="emep_payment_email" value="<?php esc_attr_e( $email ); ?>" placeholder="Enter your email address" required></p>
		</div>
	<?php endif; ?>
	<?php do_action( 'emep_before_payment_form_cc_elements', $settings ); ?>
	<div class="form-row">
		<label for="emep-card-element"><?php esc_html_e( 'Credit or Debit Card', 'emep-divi-stripe-payments' ); ?></label>
		<div id="emep-card-element"></div>
		<div id="emep-card-errors" role="alert"></div>
	</div>
	<?php do_action( 'emep_after_payment_form_cc_elements', $settings ); ?>
	<?php do_action( 'emep_before_payment_form_button', $settings ); ?>
	<input type="hidden" id="emep_payment_post_id" name="emep_payment_post_id" value="<?php esc_attr_e( $post->ID ); ?>">
	<input type="hidden" id="emep_payment_description" name="emep_payment_description" value="<?php esc_attr_e( ! empty( $settings['description'] ) ? $settings['description'] : '' ); ?>">
	<input type="hidden" id="emep_payment_amount" name="emep_payment_amount" value="<?php esc_attr_e( $settings['amount'] ); ?>">
	<input type="hidden" id="emep_payment_user_id" name="emep_payment_user_id" value="<?php echo get_current_user_id(); ?>">
	<input type="hidden" id="emep_payment_intent_id" name="emep_payment_intent_id" value="">
	<input type="hidden" name="emep_process_payment_redirect" value="<?php esc_attr_e( ! empty( $settings['redirect'] ) ? $settings['redirect'] : '' ); ?>">
	<input type="hidden" name="emep_process_payment_nonce" value="<?php echo wp_create_nonce( 'emep_process_payment' ); ?>">
	<button id="emep_payment_form_submitt_button" class="<?php esc_html_e( apply_filters( 'emep_payment_form_button_classes', 'button submit-payment' ) ); ?>">
		<?php esc_html_e( ! empty( $settings['button_text'] ) ? $settings['button_text'] : __( 'Submit Payment', 'emep-divi-stripe-payments' ) ); ?>
		<div id="emep_payment_form_spinner" class="payment-form-spinner"></div>
	</button>
	<?php do_action( 'emep_payment_form_close', $settings ); ?>
</form>
<?php do_action( 'emep_after_payment_form', $settings ); ?>