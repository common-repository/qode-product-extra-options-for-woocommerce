<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( qode_product_extra_options_for_woocommerce_is_installed( 'wpml' ) ) {
	include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/compatibility/class-qode-product-extra-options-for-woocommerce-wpml-compatibility.php';
}

foreach ( glob( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/dashboard/*/*.php' ) as $option ) {
	include_once $option;
}

include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/helper.php';
include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/class-qode-product-extra-options-for-woocommerce-frontend-module.php';
include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/addons/include.php';
include_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/blocks/include.php';
