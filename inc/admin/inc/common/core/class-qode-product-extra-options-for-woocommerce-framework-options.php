<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}
class Qode_Product_Extra_Options_For_WooCommerce_Framework_Options {
	private static $instance;
	private $child_elements;
	private $options;
	private $options_by_type;

	public function __construct() {
		$this->child_elements  = array();
		$this->options         = array();
		$this->options_by_type = array();
	}

	/**
	 * Instance of module class
	 *
	 * @return Qode_Product_Extra_Options_For_WooCommerce_Framework_Options
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_child_elements() {
		return $this->child_elements;
	}

	public function get_child_element( $key ) {
		return $this->child_elements[ $key ];
	}

	public function set_child_element( Qode_Product_Extra_Options_For_WooCommerce_Framework_Page $page ) {
		$key                          = $page->get_slug();
		$this->child_elements[ $key ] = $page;
	}

	public function child_exists( $key ) {
		return array_key_exists( $key, $this->child_elements );
	}

	public function get_options() {
		return $this->options;
	}

	public function set_options( $options ) {
		$this->options = $options;
	}

	public function get_option( $key ) {
		if ( isset( $this->options[ $key ] ) ) {
			return $this->options[ $key ];
		}

		return false;
	}

	public function set_option( $key, $value, $field_type = '' ) {
		$this->options[ $key ] = $value;
		$this->set_option_by_type( $field_type, $key );
	}

	public function get_options_by_type( $field_type ) {
		if ( array_key_exists( $field_type, $this->options_by_type ) ) {
			return $this->options_by_type[ $field_type ];
		}

		return false;
	}

	public function set_option_by_type( $field_type, $key ) {
		$this->options_by_type[ $field_type ][] = $key;
	}

	public function get_option_value( $key ) {

		if ( array_key_exists( $key, $this->options ) ) {
			return $this->options[ $key ];
		}

		return false;
	}

	public function get_child_elements_by_scope( $scope ) {
		$children = array();
		if ( is_array( $this->get_child_elements() ) && count( $this->get_child_elements() ) ) {
			foreach ( $this->get_child_elements() as $child ) {
				if ( is_array( $child->get_scope() ) && in_array( $scope, $child->get_scope(), true ) ) {
					$children[] = $child;
				} elseif ( $child->get_scope() !== '' && $child->get_scope() === $scope ) {
					$children[] = $child;
				}
			}
		}

		return $children;
	}

	public function add_option_page( Qode_Product_Extra_Options_For_WooCommerce_Framework_Page $page ) {

		if ( $page->get_slug() !== null ) {
			$this->set_child_element( $page );

			return $page;
		}

		return false;
	}

	public function enqueue_dashboard_framework_scripts( $exclude_scripts = array() ) {
		// 3rd party plugins styles.
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
		wp_enqueue_style( 'select2', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_URL_PATH . '/inc/common/assets/plugins/select2/select2.min.css', array(), '4.0.13' );

		// Hook to include additional scripts before dashboard scripts.
		do_action( 'qode_product_extra_options_for_woocommerce_action_framework_before_dashboard_scripts' );

		// Main dashboard css styles.
		if ( ! isset( $exclude_scripts['main_style'] ) ) {
			// phpcs:ignore WordPress.WP.EnqueuedResourceParameters
			wp_enqueue_style( 'qode-product-extra-options-for-woocommerce-framework-style', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_URL_PATH . '/inc/common/assets/css/common.min.css' );
		}

		// Enqueue 3rd party plugins scripts.
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'select2', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_URL_PATH . '/inc/common/assets/plugins/select2/select2.full.min.js', array(), '4.0.13', true );
		wp_enqueue_script( 'perfect-scrollbar', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_URL_PATH . '/inc/common/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js', array(), '1.5.3', true );

		// Register 3rd party plugins scripts.
		wp_register_script( 'wp-color-picker-alpha', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_URL_PATH . '/inc/common/assets/plugins/wp-color-picker-alpha/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), '3.0.3', true );

		// Compatibility with WP 5.5 => wpColorPickerL10n are not loaded by WP core anymore, but we need them for the custom wp-color-picker-alpha.js.
		global $wp_version;
		if ( version_compare( $wp_version, '5.5', '>=' ) ) {
			wp_localize_script(
				'wp-color-picker-alpha',
				'wpColorPickerL10n',
				array(
					'clear'            => esc_html__( 'Clear', 'qode-product-extra-options-for-woocommerce' ),
					'clearAriaLabel'   => esc_html__( 'Clear color', 'qode-product-extra-options-for-woocommerce' ),
					'defaultString'    => esc_html__( 'Default', 'qode-product-extra-options-for-woocommerce' ),
					'defaultAriaLabel' => esc_html__( 'Select default color', 'qode-product-extra-options-for-woocommerce' ),
					'pick'             => esc_html__( 'Select Color', 'qode-product-extra-options-for-woocommerce' ),
					'defaultLabel'     => esc_html__( 'Color value', 'qode-product-extra-options-for-woocommerce' ),
				)
			);
		}

		// Main dashboard js scripts.
		// phpcs:ignore WordPress.WP.EnqueuedResourceParameters
		wp_enqueue_script( 'qode-product-extra-options-for-woocommerce-framework-script', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_URL_PATH . '/inc/common/assets/js/common.min.js', array( 'jquery' ), false, true );

		// Hook to include additional scripts after dashboard scripts.
		do_action( 'qode_product_extra_options_for_woocommerce_action_framework_after_dashboard_scripts' );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_framework_options' ) ) {
	/**
	 * Function that initialize main framework options object
	 */
	function qode_product_extra_options_for_woocommerce_framework_options() {
		return Qode_Product_Extra_Options_For_WooCommerce_Framework_Options::get_instance();
	}
}
