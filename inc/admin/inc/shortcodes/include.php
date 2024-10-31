<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

require_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH . '/inc/shortcodes/class-qode-product-extra-options-for-woocommerce-framework-shortcodes.php';
require_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH . '/inc/shortcodes/class-qode-product-extra-options-for-woocommerce-framework-shortcode.php';

foreach ( glob( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH . '/inc/shortcodes/translators/*/*-translator.php' ) as $translator ) {
	require_once $translator;
}
