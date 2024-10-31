<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_Front' ) ) {

	/**
	 *  Front class.
	 *  The class manage all the frontend behaviors.
	 */
	class Qode_Product_Extra_Options_For_WooCommerce_Front {

		/**
		 * Single instance of the class
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_Front
		 */
		protected static $instance;

		/**
		 * Current product price
		 *
		 * @var float
		 */
		public $current_product_price = 0;

		/**
		 * Returns single instance of the class
		 *
		 * @return Qode_Product_Extra_Options_For_WooCommerce_Front
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
		}
	}

}

/**
 * Unique access to instance of Qode_Product_Extra_Options_For_WooCommerce_Front class
 *
 * @return Qode_Product_Extra_Options_For_WooCommerce_Front
 */
function Qode_Product_Extra_Options_For_WooCommerce_Front() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return Qode_Product_Extra_Options_For_WooCommerce_Front::get_instance();
}
