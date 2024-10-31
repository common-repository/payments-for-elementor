<?php

class EMEP_PostType {

	public function __construct() {
		add_action( 'init', array( $this, 'register_payment_post_type' ) );
		add_action( 'init', array( $this, 'register_post_status' ), 9 );
		add_action( 'admin_footer', array( $this, 'set_status' ) );
		add_action( 'admin_footer-edit.php', array( $this, 'rudr_status_into_inline_edit' ) );		 
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'manage_emep_payment_posts_columns', array( $this, 'admin_list_table' ) );
		add_action( 'manage_emep_payment_posts_custom_column', array( $this, 'populate_list_table_columns' ), 10, 2 );
		add_filter( 'manage_edit-emep_payment_sortable_columns', array( $this, 'set_sortable_columns' ) );
		add_action( 'pre_get_posts', array( $this, 'sort_payments' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_filter( 'display_post_states', array( $this, 'add_post_states' ), 10, 2 );
	}

	public function register_payment_post_type() {

		$labels = array(
			'name'                  => _x( 'Payments', 'Post type general name', 'emep-divi-stripe-payments' ),
			'singular_name'         => _x( 'Payment', 'Post type singular name', 'emep-divi-stripe-payments' ),
			'menu_name'             => _x( 'Payments', 'Admin Menu text', 'emep-divi-stripe-payments' ),
			'name_admin_bar'        => _x( 'Payment', 'Add New on Toolbar', 'emep-divi-stripe-payments' ),
			'add_new'               => __( 'Add New', 'emep-divi-stripe-payments' ),
			'add_new_item'          => __( 'Add New Payment', 'emep-divi-stripe-payments' ),
			'new_item'              => __( 'New Payment', 'emep-divi-stripe-payments' ),
			'edit_item'             => __( 'Edit Payment', 'emep-divi-stripe-payments' ),
			'view_item'             => __( 'View Payment', 'emep-divi-stripe-payments' ),
			'all_items'             => __( 'All Payments', 'emep-divi-stripe-payments' ),
			'search_items'          => __( 'Search Payments', 'emep-divi-stripe-payments' ),
			'parent_item_colon'     => __( 'Parent Payments:', 'emep-divi-stripe-payments' ),
			'not_found'             => __( 'No payments found.', 'emep-divi-stripe-payments' ),
			'not_found_in_trash'    => __( 'No payments found in Trash.', 'emep-divi-stripe-payments' ),
			'featured_image'        => _x( 'Payment Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'emep-divi-stripe-payments' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'emep-divi-stripe-payments' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'emep-divi-stripe-payments' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'emep-divi-stripe-payments' ),
			'archives'              => _x( 'Payment archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'emep-divi-stripe-payments' ),
			'insert_into_item'      => _x( 'Insert into payment', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'emep-divi-stripe-payments' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this payment', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'emep-divi-stripe-payments' ),
			'filter_items_list'     => _x( 'Filter payments list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'emep-divi-stripe-payments' ),
			'items_list_navigation' => _x( 'Payments list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'emep-divi-stripe-payments' ),
			'items_list'            => _x( 'Payments list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'emep-divi-stripe-payments' ),
		);
		 
		$args = array(
			'labels'              => $labels,
			'show_in_rest'		  => false,
			'menu_icon'			  => 'dashicons-cart',
			'menu_position' 	  => 50,
			'public'              => false,
			'show_ui'             => true,
			'capabilities'        => array(
				'create_posts' => 'do_not_allow'
			),
			'map_meta_cap' 		  => true,
			'map_meta_cap'        => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_in_menu'        => current_user_can( 'manage_options' ),
			'hierarchical'        => false,
			'show_in_nav_menus'   => false,
			'rewrite'             => false,
			'query_var'           => false,
			'supports'            => array( 'title' ),
			'has_archive'         => false,
		);

		register_post_type( 'emep_payment', $args );
	}

	public static function get_payment_statuses() {
		return apply_filters(
			'emep_register_payment_post_statuses',
			array(
				'emep-completed'  => array(
					'label'                     => _x( 'Completed', 'Order status', 'emep-divi-stripe-payments' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: number of orders */
					'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'emep-divi-stripe-payments' ),
				),
				'emep-refunded'   => array(
					'label'                     => _x( 'Refunded', 'Order status', 'emep-divi-stripe-payments' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: number of orders */
					'label_count'               => _n_noop( 'Refunded <span class="count">(%s)</span>', 'Refunded <span class="count">(%s)</span>', 'emep-divi-stripe-payments' ),
				),
			)
		);
	}

	/**
	 * Register our custom post statuses, used for order status.
	 */
	public function register_post_status() {
		$statuses = self::get_payment_statuses();
		foreach ( $statuses as $status => $values ) {
			register_post_status( $status, $values );
		}
	}

	public function set_status() {

		global $post;
	
		if ( ! isset( $post->post_type ) || 'emep_payment' !== $post->post_type ) {
			return;
		}

		$label = '';
		$options = '';
	
		foreach ( self::get_payment_statuses() as $status => $values ) {
			$options .= "<option value='{$status}'";
			if ( $status === $post->post_status ) {
				$options .= ' selected=\"selected\"';
				$label = $values['label'];
			}
			$options .= ">{$values['label']}</option>";
		}

		$publish_button_text = ! empty( $label ) ? __( 'Update', 'emep-divi-stripe-payments' ) : __( 'Create Payment', 'emep-divi-stripe-payments' );

		?>
		<script>
			(function($){
				$(document).ready(function(){
					$('select#post_status').html( "<?php echo $options; ?>" );
					$('#post-status-display').html( "<?php echo $label; ?>");
					<?php if ( ! empty( $label ) ) : ?>
						$("#publish").val("<?php _e( 'Update', 'emep-divi-stripe-payments' ); ?>");
						$("#publish").attr("name","save");
						$("#original_publish").val("<?php _e( 'Update', 'emep-divi-stripe-payments' ); ?>");
					<?php endif; ?>
				});
			})(jQuery);
		</script>
	<?php }

	public function rudr_status_into_inline_edit() {

		global $post;
		
		if ( empty( $post ) || empty( $_GET['post_type'] ) || 'emep_payment' !== $_GET['post_type'] ) {
			return;
		}

		$options = '';
		
		foreach ( self::get_payment_statuses() as $status => $values ) {
			$options .= "<option value='{$status}'";
			if ( $status === $post->post_status ) {
				$options .= ' selected=\"selected\"';
			}
			$options .= ">{$values['label']}</option>";
		}

		?>

		<script>
			jQuery(document).ready(function($) {
				$('select[name=\"_status\"]').html("<?php echo $options; ?>");
			});
		</script>

		<?php
	}

	/**
	 * Add meta box for payment data
	 */
	public function add_meta_boxes() {
		global $post;
		
		add_meta_box( 
			'emep_payment_meta_mb',
			__( 'Payment Data', 'emep-divi-stripe-payments' ),
			array( $this, 'payment_data_mb' ),
			'emep_payment',
			'normal'
		);
		
		if ( 'emep-refunded' !== $post->post_status ) {
			add_meta_box( 
				'emep_payment_refund_mb',
				__( 'Refund', 'emep-divi-stripe-payments' ),
				array( $this, 'payment_refund_mb' ),
				'emep_payment',
				'side'
			);
		}

		if ( ! empty( get_post_meta( $post->ID, 'payment_refunds', true ) ) ) {
			add_meta_box( 
				'emep_payment_refund_history_mb',
				__( 'Refund History', 'emep-divi-stripe-payments' ),
				array( $this, 'payment_refund_history_mb' ),
				'emep_payment',
				'normal'
			);
		}
	}

	/**
	 * Render the "Payment Data" meta box
	 */
	public function payment_data_mb( $post ) {
		include_once 'templates/payment-data-meta-box.php';
	}

	/**
	 * Render the "Refund" meta box
	 */
	public function payment_refund_mb( $post ) {
		include_once 'templates/payment-refund-meta-box.php';
	}

	/**
	 * Renders the Refund History meta box
	 */
	public function payment_refund_history_mb( $post ) {
		$refunds = get_post_meta( $post->ID, 'payment_refunds', true );
		include_once 'templates/payment-refund-history-meta-box.php';
	}

	/**
	 * Modify the list table columns
	 *
	 * @param  array 	$cols 	Columns
	 *
	 * @return array
	 */
	public function admin_list_table( $cols ) {
		unset( $cols['comments'] );
		unset( $cols['title'] );
		unset( $cols['date'] );
		$cols['title'] = __( 'Payment ID', 'emep-divi-stripe-payments' );
		$cols['amount'] = __( 'Amount', 'emep-divi-stripe-payments' );
		$cols['customer'] = __( 'Customer', 'emep-divi-stripe-payments' );
		$cols['status'] = __( 'Status', 'emep-divi-stripe-payments' );
		$cols['payment_date'] = __( 'Date', 'emep-divi-stripe-payments' );
		return $cols;
	}

	/**
	 * Populate the list table column data
	 *
	 * @param  string 	$column  	Column key
	 * @param  int 		$post_id 	ID of the post row
	 *
	 * @return void
	 */
	public function populate_list_table_columns( $column, $post_id ) {

		$statuses = self::get_payment_statuses();
		$post = get_post( $post_id );
		
		switch ( $column ) {
			case 'payment_date':
				echo get_the_date( '', $post_id );
				break;

			case 'amount':
				echo emep_price( get_post_meta( $post_id, 'payment_amount', true ) );
				break;

			case 'status':
				echo $statuses[$post->post_status]['label'];
				break;

			case 'customer':
				$user_id = get_post_meta( $post_id, 'customer_user_id', true );
				if ( ! empty( $user_id ) ) {
					$first_name = get_user_meta( $user_id, 'first_name', true );
					$last_name = get_user_meta( $user_id, 'last_name', true );
					$profile_url = get_edit_user_link( $user_id );
					$name = ( empty( $first_name ) && empty( $last_name ) ) ? __( 'Unknown Name', 'emep-divi-stripe-payments' ) : $first_name . ' ' . $last_name;
					printf( '<a href="%s">%s</a>', esc_url( $profile_url ), $name );
				}
				break;
			
			default:
				break;
		}
	}

	public function set_sortable_columns( $cols ) {
		$cols['amount'] = 'amount';
		return $cols;
	}

	public function sort_payments( $query ) {
		
		if( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( 'amount' === $query->get( 'orderby') ) {
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', 'payment_amount' );
			$query->set( 'meta_type', 'numeric' );
		}
	}

	public function admin_scripts( $hook ) {
		global $post;
		if ( 'post.php' === $hook && 'emep_payment' === $post->post_type ) {
			wp_enqueue_script( 'edit-payment', plugin_dir_url( __FILE__ ) . '/templates/js/edit-payment.js', array( 'jquery' ), '', true );
			wp_localize_script( 'edit-payment', 'emepPaymentRefund', array(
				'confirmMessage' => esc_html__( 'Are you sure you want to issue a refund of this payment?', 'emep-divi-stripe-payments' )
			) );
		}
	}

	/**
	 * Displays post states (in the admin list table, next to the title)
	 *
	 * @param  array 	$states 	Post display states
	 * @param  object 	$post  		Current post
	 *
	 * @return array
	 */
	public function add_post_states( $states, $post ) {
		if ( 'emep_payment' === $post->post_type && emep_is_partially_refunded( $post ) ) {
			$states['partially_refunded'] = esc_html__( 'Partially Refunded', 'emep-divi-stripe-payments' );
		}
		return $states;
	}
}

new EMEP_PostType;
