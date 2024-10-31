<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_add_cart_and_order_options' ) ) {
	/**
	 * Function that add product options for this module
	 */
	function qode_product_extra_options_for_woocommerce_add_cart_and_order_options() {

		$qode_product_extra_options_for_woocommerce_framework = qode_product_extra_options_for_woocommerce_framework_get_framework_root();

		$page = $qode_product_extra_options_for_woocommerce_framework->add_options_page(
			array(
				'scope'       => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_OPTIONS_NAME,
				'type'        => 'admin',
				'slug'        => 'cart_and_order_options',
				'icon'        => 'fa fa-indent',
				'title'       => esc_html__( 'Cart and Order', 'qode-product-extra-options-for-woocommerce' ),
				'description' => esc_html__( 'Set the cart options of the Product Add-ons plugin on the Cart and Checkout pages', 'qode-product-extra-options-for-woocommerce' ),
			)
		);

		if ( $page ) {

			do_action( 'qode_product_extra_options_for_woocommerce_action_framework_before_cart_and_order_options_map', $page );

			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qode_product_extra_options_for_woocommerce_show_options_in_cart',
					'title'         => esc_html__( 'Show Options in the Cart Page', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Enable to show the details of the options in the cart page', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => 'yes',
				)
			);

			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qode_product_extra_options_for_woocommerce_show_image_in_cart',
					'title'         => esc_html__( 'Show the Replacement Image in Cart', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Enable to replace product image with the Option image in the cart', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => 'no',
				)
			);

			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qode_product_extra_options_for_woocommerce_hide_options_in_order_email',
					'title'         => esc_html__( 'Hide Options in the Order Email', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Enable to hide free options in the order email', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => 'no',
				)
			);

			do_action( 'qode_product_extra_options_for_woocommerce_action_framework_after_cart_and_order_options_map', $page );
		}
	}

	add_action( 'qode_product_extra_options_for_woocommerce_action_default_options_init', 'qode_product_extra_options_for_woocommerce_add_cart_and_order_options' );
}
