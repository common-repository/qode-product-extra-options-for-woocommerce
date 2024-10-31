<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * WPML compatibility.
 */

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_WPML_Compatibility' ) ) {
	/**
	 * Compatibility Class
	 *
	 * @class   Qode_Product_Extra_Options_For_WooCommerce_WPML_Compatibility
	 */
	class Qode_Product_Extra_Options_For_WooCommerce_WPML_Compatibility {

		/**
		 * Single instance of the class
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_WPML_Compatibility
		 */
		protected static $instance;

		/**
		 * The default WPML language.
		 *
		 * @var string
		 */
		public $wpml_default_lang;


		/**
		 * Returns single instance of the class
		 *
		 * @return Qode_Product_Extra_Options_For_WooCommerce_WPML_Compatibility
		 */
		public static function get_instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * Qode_Product_Extra_Options_For_WooCommerce_WPML_Compatibility constructor
		 */
		private function __construct() {
			$this->wpml_default_lang = apply_filters( 'wpml_default_language', null );

			add_filter( 'qode_product_extra_options_for_woocommerce_filter_get_original_product_id', array( $this, 'get_parent_id' ) );
			add_filter( 'qode_product_extra_options_for_woocommerce_filter_get_original_category_ids', array( $this, 'get_original_category_ids' ), 10, 3 );
			add_filter( 'qode_product_extra_options_for_woocommerce_filter_addon_product_id', array( $this, 'get_translated_product_id' ) );
			add_filter(
				'qode_product_extra_options_for_woocommerce_filter_conditional_rule_variation',
				array(
					$this,
					'display_variation_based_current_language',
				)
			);
			add_filter( 'qode_product_extra_options_for_woocommerce_filter_localize_frontend_plugin_script', array( $this, 'set_current_wpml_language' ) );
		}

		/**
		 * Retrieve the WPML parent product id
		 *
		 * @param int $id ID.
		 *
		 * @return int
		 */
		public function get_parent_id( $id ) {
			/**
			 * WPML Post Translations
			 *
			 * @var $qode_wpml_post_translations
			 */
			$qode_wpml_post_translations = qode_product_extra_options_for_woocommerce_get_global_wpml_post_translations();

			$parent_id = ! ! $qode_wpml_post_translations ? $qode_wpml_post_translations->get_original_element( $id ) : false;

			if ( $parent_id ) {
				$id = $parent_id;
			}

			return $id;
		}

		/**
		 * Retrieve the WPML parent category ids
		 *
		 * @param array $categories Categories.
		 * @param WC_Product $product Product.
		 * @param int $product_id Parent product id.
		 *
		 * @return array
		 */
		public function get_original_category_ids( $categories, $product, $product_id ) {

			if ( $product_id !== $product->get_id() ) {
				$original_categories = array();
				$default_language    = apply_filters( 'wpml_default_language', null );
				foreach ( $categories as $id ) {
					$original_categories[] = apply_filters( 'wpml_object_id', $id, 'product_cat', true, $default_language );
				}
				if ( ! empty( $original_categories ) ) {
					$categories = $original_categories;
				}
			}

			return $categories;
		}

		/**
		 * Retrieve product id in current language
		 *
		 * @param int $product_id Product id.
		 *
		 * @return int
		 */
		public function get_translated_product_id( $product_id ) {

			if ( $product_id ) {
				$my_current_lang = apply_filters( 'wpml_current_language', null );
				$my_default_lang = apply_filters( 'wpml_default_language', null );
				if ( $my_current_lang !== $my_default_lang ) {
					$product_id = apply_filters( 'wpml_object_id', $product_id, 'post' );
				}
			}

			return $product_id;
		}

		/**
		 * Filter the current WPML Language
		 *
		 * @param array $args The args passed to the JS
		 *
		 * @return array mixed
		 */
		public function set_current_wpml_language( $args ) {

			global $sitepress;

			$args['currentLanguage'] = isset( $sitepress ) ? $sitepress->get_current_language() : $args['currentLanguage'];

			return $args;
		}

		/**
		 * Retrieve current variation translation product
		 *
		 * @param array $conditional_rule_addon conditional rules.
		 *
		 * @return array
		 */
		public function display_variation_based_current_language( $conditional_rule_addon ) {

			$my_current_lang = apply_filters( 'wpml_current_language', null );
			$my_default_lang = apply_filters( 'wpml_default_language', null );

			if ( $my_current_lang !== $my_default_lang ) {
				if ( ! empty( $conditional_rule_addon ) ) {
					$new_conditional_rule_array = array();
					foreach ( $conditional_rule_addon as $variation_id ) {
						$variation_id                 = apply_filters( 'wpml_object_id', $variation_id, 'post' );
						$new_conditional_rule_array[] = $variation_id;
					}
					$conditional_rule_addon = $new_conditional_rule_array;
				}
			}

			return $conditional_rule_addon;
		}
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_init_wpml_compatibility' ) ) {
	/**
	 * Init main color and label variations backend module instance.
	 */
	function qode_product_extra_options_for_woocommerce_init_wpml_compatibility() {
		Qode_Product_Extra_Options_For_WooCommerce_Frontend_Module::get_instance();
	}

	add_action( 'init', 'qode_product_extra_options_for_woocommerce_init_wpml_compatibility', 10 );
}
