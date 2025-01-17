<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_VERSION', '1.0.1' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DB_VERSION', '1.0' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_LOCALIZE_SLUG', 'qode-product-extra-options-for-woocommerce' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ABS_PATH', __DIR__ );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_REL_PATH', dirname( plugin_basename( __FILE__ ) ) );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_URL_PATH', plugin_dir_url( __FILE__ ) );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSETS_PATH', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ABS_PATH . '/assets' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSETS_URL_PATH', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_URL_PATH . 'assets' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ABS_PATH . '/inc' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_URL_PATH', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_URL_PATH . 'inc' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/admin' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_URL_PATH', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_URL_PATH . '/admin' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_ASSETS_PATH', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH . '/core-pages/assets' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_ASSETS_URL_PATH', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_URL_PATH . '/core-pages/assets' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_WPML_CONTEXT', 'Qode Product Extra Options For WooCommerce' );

define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_GENERAL_MENU_NAME', 'qode_woocommerce_general_menu' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME', 'qode_product_extra_options_for_woocommerce_menu' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_OPTIONS_NAME', 'qode_product_extra_options_for_woocommerce_options' );
define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MARKET_URL', 'https://qodeinteractive.com/products/plugins/qode-product-extra-options-for-woocommerce/' );

$wp_upload_dir = wp_upload_dir();

defined( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DOCUMENT_SAVE_DIR' ) || define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DOCUMENT_SAVE_DIR', $wp_upload_dir['basedir'] );
defined( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DOCUMENT_SAVE_URL' ) || define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DOCUMENT_SAVE_URL', $wp_upload_dir['baseurl'] );

/* database constants tables and tables backups - keep them short because of table name length limit */
const QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS              = 'qode_qpeofw_blocks';
const QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSOCIATIONS        = 'qode_qpeofw_blocks_assoc';
const QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS             = 'qode_qpeofw_addons';
const QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS_BACKUP       = 'qode_qpeofw_blocks_backup';
const QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSOCIATIONS_BACKUP = 'qode_qpeofw_blocks_assoc_backup';
const QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS_BACKUP      = 'qode_qpeofw_addons_backup';
