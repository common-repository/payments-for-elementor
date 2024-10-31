<?php

require_once 'Helpers.php';
include_once 'admin/licenses.php';
include_once 'admin/settings.php';
include_once 'admin/webhooks.php';
include_once 'payments/post-type.php';
include_once 'payments/payments-ajax.php';

$module_files = glob( __DIR__ . '/widgets/*/Widget.php' );

// Load custom Elementor widgets
foreach ( (array) $module_files as $module_file ) {
	require_once $module_file;
}
