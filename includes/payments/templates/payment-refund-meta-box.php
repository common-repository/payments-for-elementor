<?php

$intent_id = get_post_meta( $post->ID, 'payment_intent_id', true );

?>

<p>
	<label for="emep_issue_refund_reason"><?php esc_html_e( 'Reason for Refund', 'emep-divi-stripe-payments' ); ?></label>
	<select id="emep_issue_refund_reason">
		<option value=""></option>
		<option value="requested_by_customer"><?php esc_html_e( 'Requested by Customer', 'emep-divi-stripe-payments' ); ?></option>
		<option value="duplicate"><?php esc_html_e( 'Duplicate', 'emep-divi-stripe-payments' ); ?></option>
		<option value="fraudulent"><?php esc_html_e( 'Fraudulent', 'emep-divi-stripe-payments' ); ?></option>
	</select>
	<br>
	<span class="description"><?php _e( 'Optional reason for the refund. If you believe the charge to be fraudulent, specifying "Fraudulent" as the reason will add the customer\'s associated card and email to your <a href="https://stripe.com/docs/radar/lists" target="_blank" rel="noopener noreferrer">block lists</a>, and will also help Stripe improve their fraud detection algorithms.', 'emep-divi-stripe-payments' ); ?></span>
</p>

<p>
	<label for="emep_issue_refund_amount"><?php esc_html_e( 'Refund Amount', 'emep-divi-stripe-payments' ); ?></label>
	<input type="text" id="emep_issue_refund_amount" value="<?php esc_attr_e( number_format( (float) get_post_meta( $post->ID, 'payment_balance', true ), 2 ) ); ?>" class="medium-text" placeholder="10.50">
	<br>
	<span class="description"><?php esc_html_e( 'Enter an amount for a partial refund. Leaving this blank will issue a full refund.', 'emep-divi-stripe-payments' ); ?></span>
</p>

<p>
	<a href="<?php echo add_query_arg( array(
		'action' => 'emep_issue_refund',
		'payment_id' => $post->ID
	), wp_nonce_url( admin_url( 'admin-ajax.php' ), 'emep_issue_refund' ) ); ?>" class="button button-secondary" id="emep_payment_issue_refund"><?php esc_html_e( 'Issue Refund', 'emep-divi-stripe-payments' ); ?></a>
</p>

<div class="notice notice-warning inline">
	<p>
		<?php esc_html_e( 'Issuing a refund will refund the customer\'s money in Stripe.', 'emep-divi-stripe-payments' ); ?>
	</p>
</div>