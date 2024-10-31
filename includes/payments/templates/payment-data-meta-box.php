<?php

$customer_link = '';
$user_id = get_post_meta( $post->ID, 'customer_user_id', true );
if ( ! empty( $user_id ) ) {
	$first_name = get_user_meta( $user_id, 'first_name', true );
	$last_name = get_user_meta( $user_id, 'last_name', true );
	$profile_url = get_edit_user_link( $user_id );
	$name = ( empty( $first_name ) && empty( $last_name ) ) ? __( 'Unknown Name', 'emep-divi-stripe-payments' ) : $first_name . ' ' . $last_name;
	$customer_link = sprintf( '<a href="%s">%s</a>', esc_url( $profile_url ), $name );
}

$submitted_from = get_post_meta( $post->ID, 'submitted_from', true );

$statuses = EMEP_PostType::get_payment_statuses();

?>

<table class="form-table">
	<tbody>
		<tr valign="top">
			<td scope="row"><strong><?php esc_html_e( 'Status', 'emep-divi-stripe-payments' ); ?></strong></td>
			<td>
				<?php esc_html_e( $statuses[$post->post_status]['label'] ); ?>
				<?php if ( 'emep-completed' === $post->post_status && ! empty( get_post_meta( $post->ID, 'payment_refunds', true ) ) ) : ?>
					<?php esc_html_e( '(Partially Refunded)', 'emep-divi-stripe-payments' ); ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr valign="top" class="alternate">
			<td scope="row"><strong><?php esc_html_e( 'Payment ID', 'emep-divi-stripe-payments' ); ?></strong></td>
			<td><?php echo $post->ID; ?></td>
		</tr>
		<tr valign="top">
			<td scope="row"><strong><?php esc_html_e( 'Stripe PaymentIntent ID', 'emep-divi-stripe-payments' ); ?></strong></td>
			<td><?php esc_html_e( get_post_meta( $post->ID, 'payment_intent_id', true ) ); ?></td>
		</tr>
		<tr valign="top" class="alternate">
			<td scope="row"><strong><?php esc_html_e( 'Payment Description', 'emep-divi-stripe-payments' ); ?></strong></td>
			<td><?php esc_html_e( get_post_meta( $post->ID, 'payment_description', true ) );  ?></td>
		</tr>
		<tr valign="top">
			<td scope="row"><strong><?php esc_html_e( 'Payment Amount', 'emep-divi-stripe-payments' ); ?></strong></td>
			<td>
				<?php echo emep_price( get_post_meta( $post->ID, 'payment_amount', true ) ); ?>
				<?php if ( (float) get_post_meta( $post->ID, 'payment_balance', true ) < (float) get_post_meta( $post->ID, 'payment_amount', true ) ) : ?>
					<?php printf( '(%s %s)', __( 'Balance:', 'emep-divi-stripe-payments' ), emep_price( get_post_meta( $post->ID, 'payment_balance', true ) ) ); ?>
				<?php endif; ?>	
			</td>
		</tr>
		<tr valign="top" class="alternate">
			<td scope="row"><strong><?php esc_html_e( 'Customer', 'emep-divi-stripe-payments' ); ?></strong></td>
			<td><?php echo $customer_link; ?></td>
		</tr>
		<tr valign="top">
			<td scope="row"><strong><?php esc_html_e( 'Page', 'emep-divi-stripe-payments' ); ?></strong></td>
			<td><?php printf( '<a href="%s">%s</a>', get_permalink( $submitted_from ), get_the_title( $submitted_from ) ); ?></td>
		</tr>
		<tr valign="top" class="alternate">
			<td scope="row"><strong><?php esc_html_e( 'Date', 'emep-divi-stripe-payments' ); ?></strong></td>
			<td><?php echo get_the_date( '', $post ); ?></td>
		</tr>
		<tr valign="top">
			<td scope="row"><strong><?php esc_html_e( 'Payment Mode', 'emep-divi-stripe-payments' ); ?></strong></td>
			<td><?php echo true == get_post_meta( $post->ID, 'test_purchase', true ) ? esc_html__( 'Test', 'emep-divi-stripe-payments' ) : esc_html__( 'Live', 'emep-divi-stripe-payments' ); ?></td>
		</tr>
	</tbody>
</table>