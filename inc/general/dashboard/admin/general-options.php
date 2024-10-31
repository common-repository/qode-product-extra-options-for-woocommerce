<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_add_general_options' ) ) {
	/**
	 * Function that add general options for this module
	 *
	 * @param $page
	 */
	function qode_product_extra_options_for_woocommerce_add_general_options( $page ) {

		if ( $page ) {

			$welcome_section = $page->add_section_element(
				array(
					'layout'      => 'welcome',
					'name'        => 'qode_product_extra_options_for_woocommerce_global_plugins_options_welcome_section',
					'title'       => esc_html__( 'Welcome to QODE Product Extra Options for WooCommerce', 'qode-product-extra-options-for-woocommerce' ),
					'description' => esc_html__( 'It\'s time to set up the Product Extra Options feature on your website', 'qode-product-extra-options-for-woocommerce' ),
					'icon'        => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSETS_URL_PATH . '/img/icon.png',
				)
			);

			$page->add_field_element(
				array(
					'field_type'    => 'radio',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_list_button_label',
					'title'         => esc_html__( 'Choose a Button in WooCommerce Loops', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Choose the button to display on WooCommerce archive pages', 'qode-product-extra-options-for-woocommerce' ),
					'options'       => array(
						'select-options-button' => esc_html__( '"Select options" button', 'qode-product-extra-options-for-woocommerce' ),
						'add-to-cart-button'    => esc_html__( '"Add To Cart" button', 'qode-product-extra-options-for-woocommerce' ),
					),
					'default_value' => 'select-options-button',
				)
			);

			$page->add_field_element(
				array(
					'field_type'    => 'text',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_list_select_options_label',
					'title'         => esc_html__( 'Label for "Select options" button', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Enter the text for the "Select options" button', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => esc_html__( 'Select options', 'qode-product-extra-options-for-woocommerce' ),
					'dependency'    => array(
						'hide' => array(
							'qode_product_extra_options_for_woocommerce_product_list_button_label' => array(
								'values'        => 'add-to-cart-button',
								'default_value' => 'select-options-button',
							),
						),
					),
				)
			);

			$page->add_field_element(
				array(
					'field_type'    => 'radio',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_single_options_position',
					'title'         => esc_html__( 'Option Positioning on Single Product Pages', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Choose the position for the options blocks on product single pages', 'qode-product-extra-options-for-woocommerce' ),
					'options'       => array(
						'before-add-to-cart' => esc_html__( 'Before "Add to Cart"', 'qode-product-extra-options-for-woocommerce' ),
						'after-add-to-cart'  => esc_html__( 'After "Add To Cart"', 'qode-product-extra-options-for-woocommerce' ),
					),
					'default_value' => 'before-add-to-cart',
				)
			);

			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_single_options_hide_titles_and_images',
					'title'         => esc_html__( 'Hide Titles and Images of Option Groups', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Enable to hide titles and images set in for the individual Options you added to your Blocks', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => 'no',
				)
			);

			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_single_options_hide_images',
					'title'         => esc_html__( 'Hide Images From the Single Options', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Enable to hide images uploaded for individual Options', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => 'no',
				)
			);

			do_action( 'qode_product_extra_options_for_woocommerce_action_framework_after_hide_titles_and_images_option_map', $page );

			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_single_replace_product_price',
					'title'         => esc_html__( 'Change Single Product Base Price With Calculated Total', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Enable to replace the product base price (below the title) with the newly calculated total, depending on the selected options', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => 'yes',
				)
			);

			$page->add_field_element(
				array(
					'field_type'    => 'select',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_single_options_total_price_box',
					'title'         => esc_html__( 'Total Price Box', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Set which info you\'d like to show in the Total Price box', 'qode-product-extra-options-for-woocommerce' ),
					'options'       => array(
						'all'          => esc_html__( 'Show product price and total options', 'qode-product-extra-options-for-woocommerce' ),
						'hide_options' => esc_html__( 'Show the final total but hide options total only if the value is 0', 'qode-product-extra-options-for-woocommerce' ),
						'only_final'   => esc_html__( 'Show only the final total', 'qode-product-extra-options-for-woocommerce' ),
						'hide_all'     => esc_html__( 'Hide price box on the product page', 'qode-product-extra-options-for-woocommerce' ),
					),
					'default_value' => 'all',
				)
			);

			$page->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_single_hide_add_to_cart_button_if_required',
					'title'         => esc_html__( 'Hide "Add to Cart" Until the Required Options Are Selected', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Enable to hide the "Add to Cart" button until the user selects the required options', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => 'no',
				)
			);

			do_action( 'qode_product_extra_options_for_woocommerce_action_framework_after_general_options_map', $page );
		}
	}

	add_action( 'qode_product_extra_options_for_woocommerce_action_default_options_init', 'qode_product_extra_options_for_woocommerce_add_general_options' );
}
