jQuery(document).ready(function($) {
	const refundButton = document.getElementById('emep_payment_issue_refund');
	refundButton.addEventListener('click', async function(e){
		e.preventDefault();
		let confirmed = confirm( emepPaymentRefund.confirmMessage );
		if ( false === confirmed ) {
			return;
		}

		async function setHref() {
			let reason = document.getElementById('emep_issue_refund_reason').value;
			let amount = document.getElementById('emep_issue_refund_amount').value;
			let href = refundButton.href;
			href += `&reason=${reason}&amount=${amount}`;
			refundButton.href = href;
		}
		await setHref();
		window.location = refundButton.href;
	});
});