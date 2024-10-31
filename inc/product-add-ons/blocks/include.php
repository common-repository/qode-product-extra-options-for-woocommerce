<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/blocks/class-qode-product-extra-options-for-woocommerce-wpml.php';
include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/blocks/class-qode-product-extra-options-for-woocommerce-main.php';
include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/blocks/class-qode-product-extra-options-for-woocommerce-blocks.php';
include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/blocks/class-qode-product-extra-options-for-woocommerce-db.php';
include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/blocks/class-qode-product-extra-options-for-woocommerce-front.php';
include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/blocks/class-qode-product-extra-options-for-woocommerce-cart.php';
include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/blocks/class-qode-product-extra-options-for-woocommerce-sold-individually-product.php';
Qode_Product_Extra_Options_For_WooCommerce_Sold_Individually_Product();

require_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ABS_PATH . '/helpers/helper-woocommerce.php';

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_add_blocks_admin_pages' ) ) {
	/**
	 * Function that include additional sub-page item into general page list
	 */
	function qode_product_extra_options_for_woocommerce_add_blocks_admin_pages() {
		foreach ( glob( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/blocks/admin-pages/*/include.php' ) as $module ) {
			include_once $module;
		}
	}

	// after_setup_theme is set in order to include sub-page because of the default framework hook with priority 5.
	add_action( 'after_setup_theme', 'qode_product_extra_options_for_woocommerce_add_blocks_admin_pages' );
}
