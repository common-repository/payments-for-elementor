<?php

class EMEP_Webhooks {

	public function __construct() {
		//
	}

	public static function get_webhook_url() {
		return add_query_arg( 'emep-webhook', 'stripe', admin_url( 'admin-ajax.php' ) );
	}
}

new EMEP_Webhooks;