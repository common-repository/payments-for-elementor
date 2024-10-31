<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EMEP_Licenses {

	static $updater_url = 'https://elegantmodules.com';

	/**
	 * Sends a request to activate a license
	 *
	 * @return void
	 */
	public static function activate_license( $license ) {

		$args = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( 'Payments for Elementor' ),
			'url'        => home_url()
		);

		$response = wp_remote_post( self::$updater_url, array( 
			'timeout' => 15, 
			'sslverify' => false, 
			'body' => $args 
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			// Bad response

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred activating your license. Please try again.', 'payments-for-elementor' );
			}

		} else {

			// Good response

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.', 'payments-for-elementor' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled' :
					case 'revoked' :

						$message = __( 'Your license key has been disabled.', 'payments-for-elementor' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.', 'payments-for-elementor' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.', 'payments-for-elementor' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key.', 'payments-for-elementor' ) );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.', 'payments-for-elementor' );
						break;

					default :

						$message = __( 'An error occurred, please try again.', 'payments-for-elementor' );
						break;
				}

			}

		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			wp_die( $message );
		}

		update_option( 'emep_license_key', sanitize_text_field( $license ) );

		// $license_data->license will be either "valid" or "invalid"
		update_option( 'emep_license_status', $license_data->license );
	}


	/**
	 * Sends a request to deactivate a license
	 *
	 * @return void
	 */
	public static function deactivate_license( $license ) {

		$args = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( 'Payments for Elementor' ),
			'url'        => home_url()
		);

		$response = wp_remote_post( self::$updater_url, array( 
			'timeout' => 15, 
			'sslverify' => false, 
			'body' => $args 
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			// Bad response

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred deactivating your license. Please try again.', 'payments-for-elementor' );
			}

		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			wp_die( $message );
		}

		delete_option( 'emep_license_key' );
		delete_option( 'emep_license_status' );
	}
}
