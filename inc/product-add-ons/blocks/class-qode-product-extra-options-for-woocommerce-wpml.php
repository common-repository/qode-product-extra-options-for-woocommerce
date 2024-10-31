<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_WPML' ) ) {

	/**
	 * WPML Class
	 */
	class Qode_Product_Extra_Options_For_WooCommerce_WPML {
		/**
		 * Single instance of the class
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_WPML
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return Qode_Product_Extra_Options_For_WooCommerce_WPML
		 */
		public static function get_instance() {
			$self = __CLASS__;

			return ! is_null( $self::$instance ) ? $self::$instance : $self::$instance = new $self();
		}

		/**
		 * Constructor
		 */
		private function __construct() {
			// Qode_Product_Extra_Options_For_WooCommerce_WPML Constructor.
		}

		/**
		 * Register string
		 *
		 * @param string $string String.
		 * @param string $name Name.
		 */
		public static function register_string( $string, $name = '' ) {
			if ( ! $name ) {
				$name = sanitize_title( $string );
			}

			$name_slug = substr( '[' . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_LOCALIZE_SLUG . ']' . $name, 0, 150 );
			qode_product_extra_options_for_woocommerce_wpml_register_string( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_WPML_CONTEXT, $name_slug, $string );
		}

		/**
		 * String translate
		 *
		 * @param string $label Label.
		 * @param string $name Name.
		 *
		 * @return string
		 */
		public static function string_translate( $label, $name = '' ) {
			if ( is_string( $label ) ) {
				if ( ! $name ) {
					$name = sanitize_title( $label );
				}

				$name_slug        = substr( '[' . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_LOCALIZE_SLUG . ']' . $name, 0, 150 );
				$current_language = isset( $_REQUEST['current_language'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['current_language'] ) ) : apply_filters( 'wpml_current_language', null ); // phpcs:ignore WordPress.Security.NonceVerification

				return apply_filters( 'wpml_translate_single_string', $label, QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_WPML_CONTEXT, $name_slug, $current_language );
			}

			return $label;
		}

		/**
		 * Register Option Type
		 *
		 * @param string $title Title.
		 * @param string $description Description.
		 * @param string $options Options.
		 * @param string $html_text Options.
		 * @param string $html_heading_text Options.
		 */
		public static function register_option_type( $title, $description, $options, $html_text, $html_heading_text ) {

			self::register_string( $title );
			self::register_string( $description );
			self::register_string( $html_text );
			self::register_string( $html_heading_text );

			if ( isset( $options ) ) {

				$options = maybe_unserialize( $options );

				if ( ! is_array( $options ) || ! ( isset( $options['label'] ) ) || count( $options['label'] ) <= 0 ) {
					return;
				}

				$options['label']       = isset( $options['label'] ) ? array_map( 'stripslashes', $options['label'] ) : array();
				$options['description'] = isset( $options['description'] ) ? array_map( 'stripslashes', $options['description'] ) : array();
				$options['placeholder'] = isset( $options['placeholder'] ) ? array_map( 'stripslashes', $options['placeholder'] ) : array();
				$options['tooltip']     = isset( $options['tooltip'] ) ? array_map( 'stripslashes', $options['tooltip'] ) : array();

				$options_count = count( $options['label'] );
				for ( $i = 0; $i < $options_count; $i++ ) {
					if ( isset( $options['label'][ $i ] ) ) {
						self::register_string( $options['label'][ $i ] );
					}
					if ( isset( $options['description'][ $i ] ) ) {
						self::register_string( $options['description'][ $i ] );
					}
					if ( isset( $options['placeholder'][ $i ] ) ) {
						self::register_string( $options['placeholder'][ $i ] );
					}
					if ( isset( $options['tooltip'][ $i ] ) ) {
						self::register_string( $options['tooltip'][ $i ] );
					}
				}
			}
		}
	}
}
