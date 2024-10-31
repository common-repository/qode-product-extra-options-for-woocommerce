<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_Main' ) ) {

	/**
	 * Qode_Product_Extra_Options_For_WooCommerce_Main Class
	 */
	class Qode_Product_Extra_Options_For_WooCommerce_Main {

		/**
		 * Single instance of the class
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_Main
		 */
		public static $instance;

		/**
		 * Front object
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_Front
		 */
		public $front;

		/**
		 * Cart object
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_Cart
		 */
		public $cart;

		/**
		 * DB object
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_Db
		 */
		public $db;
		/**
		 * WPML object
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_WPML
		 */
		public $wpml;

		// TODO: probably to remove.
		/**
		 * Check if Multi Vendor is installed
		 *
		 * @var boolean
		 * @since 1.0.0
		 */
		public static $is_vendor_installed;

		/**
		 * Check if WPML is installed
		 *
		 * @var boolean
		 * @since 1.0.0
		 */
		public static $is_wpml_installed;

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		public $version = QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_VERSION;

		/**
		 * Returns single instance of the class
		 *
		 * @return Qode_Product_Extra_Options_For_WooCommerce_Main
		 */
		public static function get_instance() {
			$self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

			return ! is_null( $self::$instance ) ? $self::$instance : $self::$instance = new $self();
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			global $sitepress;
			self::$is_wpml_installed = ! empty( $sitepress );
			// TODO: vendors check.
			self::$is_vendor_installed = function_exists( 'QODE_Vendors' );

			if ( self::$is_wpml_installed ) {
				// TODO: WPML compatibility.
				$this->wpml = Qode_Product_Extra_Options_For_WooCommerce_WPML::get_instance();
			}

			// Front.
			$is_ajax_request = defined( 'DOING_AJAX' ) && DOING_AJAX;

			if ( ! is_admin() || $is_ajax_request ) {
				$this->front = Qode_Product_Extra_Options_For_WooCommerce_Front();
				$this->cart  = Qode_Product_Extra_Options_For_WooCommerce_Cart();
			}

			// Common.
			$this->db = Qode_Product_Extra_Options_For_WooCommerce_Db();

			// HPOS Compatibility.
			add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );

			// Plugin options.
			add_filter( 'woocommerce_order_item_get_formatted_meta_data', array( $this, 'qode_product_extra_options_for_woocommerce_product_addons_maybe_hide_options_on_email' ), 10, 2 );
		}

		/**
		 * Get HTML types
		 *
		 * @return array
		 */
		public function get_html_types() {

			$html_types = array(
				array(
					'slug' => 'html-heading',
					'name' => esc_html__( 'Heading', 'qode-product-extra-options-for-woocommerce' ),
				),
				array(
					'slug' => 'html-separator',
					'name' => esc_html__( 'Separator', 'qode-product-extra-options-for-woocommerce' ),
				),
				array(
					'slug' => 'html-text',
					'name' => esc_html__( 'HTML Text', 'qode-product-extra-options-for-woocommerce' ),
				),
			);

			return $html_types;
		}

		/**
		 * Get addon types
		 *
		 * @return array
		 */
		public function get_addon_types() {
			$addon_types = array(
				'checkbox'       => array(
					'slug'  => 'checkbox',
					'name'  => esc_html__( 'Checkbox', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Checkbox', 'qode-product-extra-options-for-woocommerce' ),
				),
				'radio'          => array(
					'slug'  => 'radio',
					'name'  => esc_html__( 'Radio', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Radio button', 'qode-product-extra-options-for-woocommerce' ),
				),
				'text'           => array(
					'slug'  => 'text',
					'name'  => esc_html__( 'Input text', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Input field', 'qode-product-extra-options-for-woocommerce' ),
				),
				'textarea'       => array(
					'slug'  => 'textarea',
					'name'  => esc_html__( 'Textarea', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Textarea', 'qode-product-extra-options-for-woocommerce' ),
				),
				'color'          => array(
					'slug'  => 'color',
					'name'  => esc_html__( 'Color swatch', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Color swatch', 'qode-product-extra-options-for-woocommerce' ),
				),
				'number'         => array(
					'slug'  => 'number',
					'name'  => esc_html__( 'Number', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Number', 'qode-product-extra-options-for-woocommerce' ),
				),
				'select'         => array(
					'slug'  => 'select',
					'name'  => esc_html__( 'Select', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Select item', 'qode-product-extra-options-for-woocommerce' ),
				),
				'label'          => array(
					'slug'  => 'label',
					'name'  => esc_html__( 'Label or image', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Label or image', 'qode-product-extra-options-for-woocommerce' ),
				),
				'product'        => array(
					'slug'  => 'product',
					'name'  => esc_html__( 'Product', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Product', 'qode-product-extra-options-for-woocommerce' ),
				),
				'date'           => array(
					'slug'  => 'date',
					'name'  => esc_html__( 'Date', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Date', 'qode-product-extra-options-for-woocommerce' ),
				),
				'file'           => array(
					'slug'  => 'file',
					'name'  => esc_html__( 'File upload', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'File uploader', 'qode-product-extra-options-for-woocommerce' ),
				),
				'colorpicker'    => array(
					'slug'  => 'colorpicker',
					'name'  => esc_html__( 'Color picker', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'Color picker', 'qode-product-extra-options-for-woocommerce' ),
				),
				'html-heading'   => array(
					'slug'  => 'html-heading',
					'name'  => esc_html__( 'HTML Heading', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'HTML Heading', 'qode-product-extra-options-for-woocommerce' ),
				),
				'html-text'      => array(
					'slug'  => 'html-text',
					'name'  => esc_html__( 'HTML Text', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'HTML Text', 'qode-product-extra-options-for-woocommerce' ),
				),
				'html-separator' => array(
					'slug'  => 'html-separator',
					'name'  => esc_html__( 'HTML Separator', 'qode-product-extra-options-for-woocommerce' ),
					'label' => esc_html__( 'HTML Separator', 'qode-product-extra-options-for-woocommerce' ),
				),
			);
			return $addon_types;
		}

		/**
		 * Get add-on label by slug
		 *
		 * @param string $slug The slug of the add-on.
		 *
		 * @return string
		 */
		public function get_addon_label_by_slug( $slug ) {

			if ( empty( $slug ) ) {
				return '';
			}

			$label       = '';
			$addon_types = $this->get_addon_types();

			if ( isset( $addon_types[ $slug ] ) && isset( $addon_types[ $slug ]['label'] ) ) {
				$label = $addon_types[ $slug ]['label'];
			}

			return $label;
		}

		/**
		 * Get add-on name by slug
		 *
		 * @param string $slug The slug of the add-on.
		 *
		 * @return string
		 */
		public function get_addon_name_by_slug( $slug ) {

			if ( empty( $slug ) ) {
				return '';
			}

			$name        = '';
			$addon_types = $this->get_addon_types();

			if ( isset( $addon_types[ $slug ] ) && isset( $addon_types[ $slug ]['name'] ) ) {
				$name = $addon_types[ $slug ]['name'];
			}

			return $name;
		}

		/**
		 * Get available addon types
		 *
		 * @return array
		 */
		public function get_available_addon_types() {

			$available_addon_types = array(
				'checkbox',
				'radio',
				'text',
				'select',
			);

			return apply_filters( 'qode_product_extra_options_for_woocommerce_product_addons_filter_available_addon_types', $available_addon_types );
		}

		/**
		 * Hide options on email depending on plugin option.
		 *
		 * @param array  $meta Meta value of email.
		 * @param object $order_item The order item.
		 *
		 * @return mixed
		 * @throws Exception The exception.
		 */
		public function qode_product_extra_options_for_woocommerce_product_addons_maybe_hide_options_on_email( $meta, $order_item ) {

			if ( current_user_can( 'edit_theme_options' ) && check_admin_referer( 'qode_product_extra_options_for_woocommerce_framework_ajax_save_nonce', 'qode_product_extra_options_for_woocommerce_framework_ajax_save_nonce' ) ) {

				$is_resend = isset( $_POST['wc_order_action'] ) ? 'send_order_details' === wc_clean( wp_unslash( $_POST['wc_order_action'] ) ) : false; //phpcs:ignore

				if ( ! $is_resend && ( is_admin() || is_wc_endpoint_url() ) ) {
					return $meta;
				}

				$labels    = array();
				$item_id   = $order_item->get_id();
				$meta_data = wc_get_order_item_meta( $item_id, '_qode_product_extra_options_for_woocommerce_meta_data', true );
				if ( $meta_data && is_array( $meta_data ) ) {
					foreach ( $meta_data as $index => $option ) {
						foreach ( $option as $key => $value ) {
							if ( $key && '' !== $value ) {
								$values = self::get_instance()->split_addon_and_option_ids( $key, $value );

								$addon_id  = $values['addon_id'];
								$option_id = $values['option_id'];

								$label    = qode_product_extra_options_for_woocommerce_get_option_label( $addon_id, $option_id );
								$labels[] = $label;
							}
						}
					}
				}

				foreach ( $meta as $meta_id => $meta_value ) {
					foreach ( $labels as $label ) {
						if ( $label === $meta_value->key ) {
							unset( $meta[ $meta_id ] );
						}
					}
				}
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_hide_options_in_order_email_meta', $meta, $order_item );
		}

		/**
		 * Calculate the price with the tax included if necessary.
		 *
		 * @param int $price The price added.
		 *
		 * @param null $product
		 *
		 * @return float|int|mixed
		 */
		public function calculate_price_depending_on_tax( $price = 0, $product = null ) {

			$price = qode_product_extra_options_for_woocommerce_calculate_price_depending_on_tax( $price, $product );

			return $price;
		}

		/**
		 * Split addon_id and option_id depending on key and value. (Example: 24-0 - addon_id => 24, option_id => 0 )
		 *
		 * @param string $key The key.
		 * @param string $value The value.
		 *
		 * @return array
		 */
		public function split_addon_and_option_ids( $key, $value ) {

			$values = array();

			if ( ! is_array( $value ) ) {
				$value = stripslashes( $value );
			}
			$explode = explode( '-', $key );
			if ( isset( $explode[1] ) ) {
				$addon_id  = $explode[0];
				$option_id = $explode[1];
			} else {
				$addon_id  = $key;
				$option_id = $value;
			}

			$values['addon_id']  = $addon_id;
			$values['option_id'] = $option_id;

			return $values;
		}

		/**
		 * Declare support for WooCommerce features.
		 */
		public function declare_wc_features_support() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_REL_PATH, true );
			}
		}

		/**
		 * Get Current MultiVendor
		 *
		 * @return null|QODE_Vendor
		 */
		// TODO: check if it is going to be removed.
		public static function get_current_multivendor() {
			if ( self::$is_vendor_installed && is_user_logged_in() ) {
				$vendor = qode_get_vendor( 'current', 'user' );
				if ( $vendor->is_valid() ) {
					return $vendor;
				}
			}
			return null;
		}

		/**
		 * Get MultiVendor by ID
		 *
		 * @param int    $id ID.
		 * @param string $obj Obj.
		 * @return null|QODE_Vendor
		 */
		// TODO: check if it is going to be removed.
		public static function get_multivendor_by_id( $id, $obj = 'vendor' ) {
			if ( self::$is_vendor_installed ) {
				$vendor = qode_get_vendor( $id, $obj );
				if ( $vendor->is_valid() ) {
					return $vendor;
				}
			}
			return null;
		}

		/**
		 * Is Plugin Enabled for Vendors
		 *
		 * @return bool
		 */
		// TODO: check if it is going to be removed.
		public function is_plugin_enabled_for_vendors() {
			return get_option( 'qode_wpv_vendors_option_advanced_product_options_management' ) === 'yes';
		}
	}
}

/**
 * Unique access to instance of Qode_Product_Extra_Options_For_WooCommerce_Main class
 *
 * @return Qode_Product_Extra_Options_For_WooCommerce_Main
 * @since 1.0.0
 */
function Qode_Product_Extra_Options_For_WooCommerce_Main() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return Qode_Product_Extra_Options_For_WooCommerce_Main::get_instance();
}
