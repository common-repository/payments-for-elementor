<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EMEPStripeElementsForm extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'payment_form';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Payment Form', 'payments-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-credit-card';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'payment_details',
			array(
				'label' => __( 'Payment Details', 'payments-for-elementor' )
			)
		);

		$this->add_control(
			'amount',
			array(
				'label' => __( 'Amount', 'payments-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Amount to collect on this form', 'payments-for-elementor' ),
				'default' => '',
			)
		);

		$this->add_control(
			'description',
			array(
				'label' => __( 'Payment Description', 'payments-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'description' => __( 'An arbitrary description attached to the purchase. Often useful for displaying to users.', 'payments-for-elementor' ),
				'default' => '',
			)
		);

		$this->add_control(
			'redirect',
			array(
				'label' => __( 'Redirect URL', 'payments-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'URL of where to send customers after submitting payment.', 'payments-for-elementor' ),
				'default' => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'payment_form',
			array(
				'label' => __( 'Payment Form', 'payments-for-elementor' )
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label' => __( 'Button Text', 'payments-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Payment button text.', 'payments-for-elementor' ),
				'default' => __( 'Submit Payment', 'payments-for-elementor' ),
			)
		);

		$this->add_control(
			'enable_email',
			array(
				'label' => __( 'Enable Email', 'payments-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'Adds an email input field to the payment form for the customer to add their email address.', 'payments-for-elementor' ),
				'default' => 'on',
				'return_value' => 'on',
				'label_on' => __( 'Yes', 'payments-for-elementor' ),
				'label_off' => __( 'No', 'payments-for-elementor' )
			)
		);

		$this->add_control(
			'enable_name',
			array(
				'label' => __( 'Enable Name', 'payments-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'Adds a name input field to the payment form for the customer to add their name.', 'payments-for-elementor' ),
				'default' => 'on',
				'return_value' => 'on',
				'label_on' => __( 'Yes', 'payments-for-elementor' ),
				'label_off' => __( 'No', 'payments-for-elementor' )
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {

		if ( emep_elementor_is_edit_mode() ) {
			// return;
		}

		$pub_key = emep_get_stripe_pub_key();

		// We need a publishable key for the correct mode, else Stripe Elements won't work
		if ( empty( $pub_key ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				wp_enqueue_style( 'payments-for-elementor-styles' );
				?>
				<div class="payments-for-elementor-admin-notice">
					<h4 class="payments-for-elementor-admin-notice--title"><?php esc_html_e( 'Payments for Elementor - Administrator Notice', 'payments-for-elementor' ); ?></h4>
					<?php esc_html_e( 'You need to connect to your Stripe account to use Payments for Elementor.', 'payments-for-elementor' ); ?>
					<a href="<?php echo emep_get_settings_page_url(); ?>"><?php esc_html_e( 'Complete connection', 'payments-for-elementor' ); ?></a>
				</div>
				<?php
			}
			return;
		}

		global $post;

		$settings = $this->get_settings_for_display();

		$is_test_mode = emep_is_test_mode();
		$amount = ! empty( $settings['amount'] ) ? $settings['amount'] : 0;

		// $this->add_inline_editing_attributes( 'title', 'none' );
		// $this->add_inline_editing_attributes( 'description', 'basic' );
		// $this->add_inline_editing_attributes( 'content', 'advanced' );

		// Load our scripts and styles
		wp_enqueue_script( 'stripejs' );
		wp_enqueue_script( 'payments-for-elementor-scripts' );
		wp_enqueue_style( 'payments-for-elementor-styles' );
		wp_localize_script( 'payments-for-elementor-scripts', 'emepStripe', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'emep_ajax_get_payment_intent' ),
			'testMode' => $is_test_mode,
			'amount' => number_format( $amount, 2 ),
			'pubKey' => $pub_key,
			'description' => esc_html__( $settings['description'] ),
			'post_id' => $post->ID,
			'user_id' => get_current_user_id()
		) );

		include 'templates/form.php';
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>		
		<div class="emep-payment-form-preview">
			<p>{{{settings.description}}} - {{{settings.amount}}}</p>
			<div class="form-row">
				<label for="emep_payment_name">Name</label>
				<p><input type="text" class="emep-payment-form-input" /></p>
			</div>
			<div class="form-row">
				<label for="emep_payment_email">Email</label>
				<p><input type="email" class="emep-payment-form-input" /></p>
			</div>
			<div class="form-row">Credit card</div>
			<button class="button submit-payment">{{{settings.button_text}}}</button>
		</div>
		<?php
	}
}