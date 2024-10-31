<?php

if ( ! function_exists( 'emep_get_settings_page_url' ) ) :
	/**
	 * Gets the admin URL for the Elementor Payments settings page
	 *
	 * @return string
	 */
	function emep_get_settings_page_url() {
		return admin_url( 'edit.php?post_type=emep_payment&page=emep-settings' );
	}
endif;

if ( ! function_exists( 'emep_is_ssl' ) ) :
	/**
	 * Whether the current website is using SSL
	 *
	 * @return boolean
	 */
	function emep_is_ssl() {
		return false === strpos( get_home_url(), 'https', 0 ) ? false : true;
	}
endif;

if ( ! function_exists( 'emep_elementor_is_edit_mode' ) ) :
	/**
	 * Whether the Elementor previewer is loaded
	 *
	 * @return void
	 */
	function emep_elementor_is_edit_mode() {
		return \Elementor\Plugin::instance()->editor->is_edit_mode();
	}
endif;

if ( ! function_exists( 'emep_get_option' ) ) :
	/**
	 * Function to fetch an option value
	 *
	 * @return void
	 */
	function emep_get_option( $key, $default = false ) {
		return get_option( $key, $default );
	}
endif;

if ( ! function_exists( 'emep_is_test_mode' ) ) :
	/**
	 * Whether test mode is enabled.
	 *
	 * @return boolean
	 */
	function emep_is_test_mode() {
		return ! emep_is_ssl() || emep_get_option( 'emep_test_mode' );
	}
endif;

if ( ! function_exists( 'emep_is_partially_refunded' ) ) :
	/**
	 * Whether the payment is partially refunded.
	 * To be partially refunded, there should be partial refunds, and the balance should be > 0
	 *
	 * @param object 	$post 	Payment post
	 *
	 * @return boolean
	 */
	function emep_is_partially_refunded( $payment ) {
		if ( empty( $payment->post_type ) || 'emep_payment' !== $payment->post_type ) {
			return;
		}
		return 'emep-completed' === $payment->post_status && ! empty( get_post_meta( $payment->ID, 'payment_refunds', true ) ) && (float) get_post_meta( $payment->ID, 'payment_balance', true ) > 0;
	}
endif;

if ( ! function_exists( 'emep_get_stripe_key' ) ) :
	/**
	 * Retrieve a Stripe key setting.
	 *
	 * @param  string 	$key  	"pub" or "secret"
	 * @param  string 	$mode 	"live" or "secret"
	 *
	 * @return string
	 */
	function emep_get_stripe_key( $key = 'pub', $mode = 'live' ) {
		return emep_get_option( "emep_stripe_{$mode}_{$key}_key" );
	}
endif;

if ( ! function_exists( 'emep_get_stripe_pub_key' ) ) :
	/**
	 * Retrieve the Stripe publishable key setting.
	 *
	 * @return string 	Test publishable key when in test mode, otherwise live publishable key
	 */
	function emep_get_stripe_pub_key() {
		$mode = emep_is_test_mode() ? 'test' : 'live';
		return emep_get_option( "emep_stripe_{$mode}_pub_key" );
	}
endif;

if ( ! function_exists( 'emep_get_stripe_secret_key' ) ) :
	/**
	 * Retrieve the Stripe secret key setting.
	 *
	 * @return string 	Test secret key when in test mode, otherwise live secret key
	 */
	function emep_get_stripe_secret_key() {
		$mode = emep_is_test_mode() ? 'test' : 'live';
		return emep_get_option( "emep_stripe_{$mode}_secret_key" );
	}
endif;

if ( ! function_exists( 'emep_get_currency' ) ) :
	/**
	 * Get the saved currency code
	 *
	 * @return string
	 */
	function emep_get_currency() {
		return emep_get_option( 'emep_currency' );
	}
endif;


if ( ! function_exists( 'emep_get_currencies' ) ) :
	/**
	 * Get the saved currency code
	 *
	 * @return string
	 */
	function emep_get_currencies() {
		return array_unique(
			apply_filters(
				'emep_currencies',
				array(
					'USD' => __( 'US Dollars', 'emep-divi-stripe-payments' ),
					'EUR' => __( 'Euros', 'emep-divi-stripe-payments' ),
					'GBP' => __( 'Pound Sterling', 'emep-divi-stripe-payments' ),
					'AUD' => __( 'Australian Dollars', 'emep-divi-stripe-payments' ),
					'BRL' => __( 'Brazilian Real ', 'emep-divi-stripe-payments' ),
					'CAD' => __( 'Canadian Dollars', 'emep-divi-stripe-payments' ),
					'CZK' => __( 'Czech Koruna', 'emep-divi-stripe-payments' ),
					'DKK' => __( 'Danish Krone', 'emep-divi-stripe-payments' ),
					'HKD' => __( 'Hong Kong Dollar', 'emep-divi-stripe-payments' ),
					'HUF' => __( 'Hungarian Forint', 'emep-divi-stripe-payments' ),
					'ILS' => __( 'Israeli Shekel', 'emep-divi-stripe-payments' ),
					'JPY' => __( 'Japanese Yen', 'emep-divi-stripe-payments' ),
					'MYR' => __( 'Malaysian Ringgits', 'emep-divi-stripe-payments' ),
					'MXN' => __( 'Mexican Peso', 'emep-divi-stripe-payments' ),
					'NZD' => __( 'New Zealand Dollar', 'emep-divi-stripe-payments' ),
					'NOK' => __( 'Norwegian Krone', 'emep-divi-stripe-payments' ),
					'PHP' => __( 'Philippine Pesos', 'emep-divi-stripe-payments' ),
					'PLN' => __( 'Polish Zloty', 'emep-divi-stripe-payments' ),
					'SGD' => __( 'Singapore Dollar', 'emep-divi-stripe-payments' ),
					'SEK' => __( 'Swedish Krona', 'emep-divi-stripe-payments' ),
					'CHF' => __( 'Swiss Franc', 'emep-divi-stripe-payments' ),
					'TWD' => __( 'Taiwan New Dollars', 'emep-divi-stripe-payments' ),
					'THB' => __( 'Thai Baht', 'emep-divi-stripe-payments' ),
					'INR' => __( 'Indian Rupee', 'emep-divi-stripe-payments' ),
					'TRY' => __( 'Turkish Lira', 'emep-divi-stripe-payments' ),
					'RIA' => __( 'Iranian Rial', 'emep-divi-stripe-payments' ),
					'RUB' => __( 'Russian Rubles', 'emep-divi-stripe-payments' ),
					'AOA' => __( 'Angolan Kwanza', 'emep-divi-stripe-payments' ),
				)
			)
		);
	}
endif;


if ( ! function_exists( 'emep_get_currency_symbol' ) ) :
	/**
	 * Get Currency symbol.
	 *
	 * Currency Symbols and mames should follow the Unicode CLDR recommendation (http://cldr.unicode.org/translation/currency-names)
	 *
	 * @param string $currency Currency. (default: '').
	 * @return string
	 */
	function emep_get_currency_symbol( $currency = '' ) {
		
		if ( ! $currency ) {
			$currency = emep_get_currency();
		}

		$symbols = apply_filters( 'emep_currency_symbols', array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => 'KZT',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRU' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => 'N&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#x434;&#x438;&#x43d;.',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STN' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VES' => 'Bs.S',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK',
		) );

		$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

		return apply_filters( 'emep_currency_symbol', $currency_symbol, $currency );
	}
endif;

if ( ! function_exists( 'emep_get_price_format' ) ) :
	/**
	 * Get the price format depending on the currency position.
	 *
	 * @return string
	 */
	function emep_get_price_format() {
		$currency_pos = emep_get_option( 'emep_currency_position' );
		$format       = '%1$s%2$s';

		switch ( $currency_pos ) {
			case 'left':
				$format = '%1$s%2$s';
				break;
			case 'right':
				$format = '%2$s%1$s';
				break;
			case 'left_space':
				$format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space':
				$format = '%2$s&nbsp;%1$s';
				break;
		}

		return apply_filters( 'emep_price_format', $format, $currency_pos );
	}
endif;

if ( ! function_exists( 'emep_price' ) ) :
	/**
	 * Format the price with a currency symbol.
	 *
	 * @param  float $price Raw price.
	 * @param  array $args  Arguments to format a price {
	 *     Array of arguments.
	 *     Defaults to empty array.
	 *
	 *     @type string $currency           Currency code.
	 *     @type string $decimal_separator  Decimal separator.
	 *     @type string $thousand_separator Thousand separator.
	 *     @type string $decimals           Number of decimals.
	 *     @type string $price_format       Price format depending on the currency position.
	 * }
	 * @return string
	 */
	function emep_price( $price, $args = array() ) {
		$args = apply_filters(
			'emep_price_args',
			wp_parse_args(
				$args,
				array(
					'currency' => emep_get_option( 'emep_currency' ),
					'decimal_separator' => emep_get_option( 'emep_decimal_sep' ),
					'thousand_separator' => emep_get_option( 'emep_thousand_sep' ),
					'decimals' => (int) emep_get_option( 'emep_decimals' ),
					'price_format' => emep_get_price_format()
				)
			)
		);

		$unformatted_price = $price;
		$negative = $price < 0;
		$price = apply_filters( 'emep_raw_price', floatval( $negative ? $price * -1 : $price ) );
		$price = apply_filters( 'emep_formatted_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] );

		if ( apply_filters( 'emep_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
			$price = preg_replace( '/' . preg_quote( emep_get_option( 'emep_decimal_sep' ), '/' ) . '0++$/', '', $price );
		}

		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '<span class="emep-currency-symbol">' . emep_get_currency_symbol( $args['currency'] ) . '</span>', $price );
		$return = '<span class="emep-amount amount">' . $formatted_price . '</span>';

		/**
		 * Filters the string of price markup.
		 *
		 * @param string $return            Price HTML markup.
		 * @param string $price             Formatted price.
		 * @param array  $args              Pass on the args.
		 * @param float  $unformatted_price Price as float to allow plugins custom formatting. Since 3.2.0.
		 */
		return apply_filters( 'emep_price', $return, $price, $args, $unformatted_price );
	}
endif;
