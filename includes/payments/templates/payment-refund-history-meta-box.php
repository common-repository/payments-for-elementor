<?php

$date_format = get_option( 'date_format' );
// $time_format = get_option( 'time_format' );
$count = 0;

?>

<table class="form-table">
	<thead>
		<tr>
			<th>Date</th>
			<th>Amount Refunded</th>
			<th>Reason</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $refunds as $refund ) : ?>
			<tr valign="top" <?php echo 0 === $count || 0 === $count % 2 ? 'class="alternate"' : ''; ?>>
				<td><?php esc_html_e( date_i18n( "{$date_format}", $refund['time'] ) ); ?></td>
				<td><?php echo emep_price( $refund['refund_amount'] ); ?></td>
				<td><?php esc_html_e( $refund['reason'] ); ?></td>
			</tr>
			<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>