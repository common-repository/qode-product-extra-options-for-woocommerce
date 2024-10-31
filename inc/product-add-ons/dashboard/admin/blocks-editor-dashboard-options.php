<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_add_blocks_editor_dashboard_options' ) ) {
	/**
	 * Function that add general options for this module
	 */
	function qode_product_extra_options_for_woocommerce_add_blocks_editor_dashboard_options() {
		$qode_framework = qode_product_extra_options_for_woocommerce_framework_get_framework_root();

		$qode_framework->add_options_page(
			array(
				'scope'       => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_OPTIONS_NAME,
				'type'        => 'admin',
				'layout'      => 'custom',
				'slug'        => 'blocks-editor',
				'title'       => esc_html__( 'Blocks Editor', 'qode-product-extra-options-for-woocommerce' ),
				'description' => esc_html__( 'Block Items', 'qode-product-extra-options-for-woocommerce' ),
			)
		);
	}

	add_action( 'qode_product_extra_options_for_woocommerce_action_default_options_init', 'qode_product_extra_options_for_woocommerce_add_blocks_editor_dashboard_options', 80 );
}
