<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_add_style_options' ) ) {
	/**
	 * Function that add product options for this module
	 */
	function qode_product_extra_options_for_woocommerce_add_style_options() {

		$qode_product_extra_options_for_woocommerce_framework = qode_product_extra_options_for_woocommerce_framework_get_framework_root();

		$page = $qode_product_extra_options_for_woocommerce_framework->add_options_page(
			array(
				'scope'       => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_OPTIONS_NAME,
				'type'        => 'admin',
				'slug'        => 'style_options',
				'icon'        => 'fa fa-indent',
				'title'       => esc_html__( 'Block Styles', 'qode-product-extra-options-for-woocommerce' ),
				'description' => esc_html__( 'Configure style options to customize the add-ons you have created', 'qode-product-extra-options-for-woocommerce' ),
			)
		);

		if ( $page ) {

			$general_section = $page->add_section_element(
				array(
					'name' => 'qode_product_extra_options_for_woocommerce_product_options_page_form_general_section',
				)
			);

			$general_section->add_field_element(
				array(
					'field_type'    => 'select',
					'name'          => 'qode_product_extra_options_for_woocommerce_block_heading',
					'title'         => esc_html__( 'Block Heading', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Choose which heading to use for the titles in the block of options', 'qode-product-extra-options-for-woocommerce' ),
					'options'       => qode_product_extra_options_for_woocommerce_get_select_type_options_pool( 'title_tag' ),
					'default_value' => 'h6',
				)
			);

			$general_section->add_field_element(
				array(
					'field_type'    => 'color',
					'name'          => 'qode_product_extra_options_for_woocommerce_block_background_color',
					'title'         => esc_html__( 'Block Background', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Choose the background color for all block options', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => '#fff',
				)
			);

			$general_section->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qode_product_extra_options_for_woocommerce_block_content_padding',
					'title'       => esc_html__( 'Block Content Padding', 'qode-product-extra-options-for-woocommerce' ),
					'description' => esc_html__( 'Set padding that will be applied for block content in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'qode-product-extra-options-for-woocommerce' ),
				)
			);

			do_action( 'qode_product_extra_options_for_woocommerce_action_framework_after_block_content_padding_style_options_map', $page, $general_section );

			$general_section->add_field_element(
				array(
					'field_type'    => 'yesno',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_options_display_toggle_opened',
					'title'         => esc_html__( 'Toggle Opened by Default', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Enable to show the toggle opened by default', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => 'no',
					'dependency'    => array(
						'show' => array(
							'qode_product_extra_options_for_woocommerce_product_options_display_in_toggle' => array(
								'values'        => 'yes',
								'default_value' => 'no',
							),
						),
					),
				)
			);

			do_action( 'qode_product_extra_options_for_woocommerce_action_framework_after_product_options_page_toggle_section_options_map', $page, $general_section );

			$required_section = $page->add_section_element(
				array(
					'name'  => 'qode_product_extra_options_for_woocommerce_product_options_page_required_section',
					'title' => esc_html__( 'Required Product Options', 'qode-product-extra-options-for-woocommerce' ),
				)
			);

			$required_section->add_field_element(
				array(
					'field_type'    => 'color',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_options_required_option_color',
					'title'         => esc_html__( 'Required Option Color', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Set the color for the required Option message', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => '#ff0000',
				)
			);

			$required_section->add_field_element(
				array(
					'field_type'    => 'text',
					'name'          => 'qode_product_extra_options_for_woocommerce_product_options_required_option_text',
					'title'         => esc_html__( 'Required Option Text', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Input text for the required Option', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => esc_html__( 'This is required option', 'qode-product-extra-options-for-woocommerce' ),
				)
			);

			$form_style_section = $page->add_section_element(
				array(
					'name'  => 'qode_product_extra_options_for_woocommerce_product_options_page_form_style_section',
					'title' => esc_html__( 'Form Style', 'qode-product-extra-options-for-woocommerce' ),
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'    => 'radio',
					'name'          => 'qode_product_extra_options_for_woocommerce_form_style',
					'title'         => esc_html__( 'Enable Predefined Style', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Inherit form style from the theme or set predefined styling', 'qode-product-extra-options-for-woocommerce' ),
					'options'       => array(
						''                 => esc_html__( 'Theme Style', 'qode-product-extra-options-for-woocommerce' ),
						'predefined-style' => esc_html__( 'Predefined Style', 'qode-product-extra-options-for-woocommerce' ),
					),
					'default_value' => 'predefined-style',
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'    => 'radio',
					'name'          => 'qode_product_extra_options_for_woocommerce_select_style',
					'title'         => esc_html__( 'Select Style', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Choose the style for the select dropdown input fields (This applies only to select input fields where select2 script has been initialized)', 'qode-product-extra-options-for-woocommerce' ),
					'options'       => array(
						'plugin-fields' => esc_html__( 'Only select fields from the plugin', 'qode-product-extra-options-for-woocommerce' ),
						'all-fields'    => esc_html__( 'All select fields', 'qode-product-extra-options-for-woocommerce' ),
					),
					'default_value' => 'plugin-fields',
					'dependency'    => array(
						'show' => array(
							'qode_product_extra_options_for_woocommerce_form_style' => array(
								'values'        => 'predefined-style',
								'default_value' => '',
							),
						),
					),
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'    => 'radio',
					'name'          => 'qode_product_extra_options_for_woocommerce_checkbox_style',
					'title'         => esc_html__( 'Checkbox Style', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Choose the style for the checkbox', 'qode-product-extra-options-for-woocommerce' ),
					'options'       => array(
						'square'  => esc_html__( 'Square', 'qode-product-extra-options-for-woocommerce' ),
						'rounded' => esc_html__( 'Rounded', 'qode-product-extra-options-for-woocommerce' ),
					),
					'default_value' => 'square',
					'dependency'    => array(
						'show' => array(
							'qode_product_extra_options_for_woocommerce_form_style' => array(
								'values'        => 'predefined-style',
								'default_value' => '',
							),
						),
					),
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'  => 'color',
					'name'        => 'qode_product_extra_options_for_woocommerce_main_color',
					'title'       => esc_html__( 'Main Color', 'qode-product-extra-options-for-woocommerce' ),
					'description' => esc_html__( 'Set the main color for selected options', 'qode-product-extra-options-for-woocommerce' ),
					'dependency'  => array(
						'show' => array(
							'qode_product_extra_options_for_woocommerce_form_style' => array(
								'values'        => 'predefined-style',
								'default_value' => '',
							),
						),
					),
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'  => 'color',
					'name'        => 'qode_product_extra_options_for_woocommerce_borders_color',
					'title'       => esc_html__( 'Form Border Color', 'qode-product-extra-options-for-woocommerce' ),
					'description' => esc_html__( 'Set the color for form borders', 'qode-product-extra-options-for-woocommerce' ),
					'dependency'  => array(
						'show' => array(
							'qode_product_extra_options_for_woocommerce_form_style' => array(
								'values'        => 'predefined-style',
								'default_value' => '',
							),
						),
					),
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'  => 'color',
					'name'        => 'qode_product_extra_options_for_woocommerce_radio_buttons_color',
					'title'       => esc_html__( 'Radio Button Color', 'qode-product-extra-options-for-woocommerce' ),
					'description' => esc_html__( 'Set the color for radio buttons', 'qode-product-extra-options-for-woocommerce' ),
					'dependency'  => array(
						'show' => array(
							'qode_product_extra_options_for_woocommerce_form_style' => array(
								'values'        => 'predefined-style',
								'default_value' => '',
							),
						),
					),
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'  => 'color',
					'name'        => 'qode_product_extra_options_for_woocommerce_radio_buttons_color_inactive',
					'title'       => esc_html__( 'Radio Buttons Color Inactive', 'qode-product-extra-options-for-woocommerce' ),
					'description' => esc_html__( 'Set the color of the radio buttons when unchecked', 'qode-product-extra-options-for-woocommerce' ),
					'dependency'  => array(
						'show' => array(
							'qode_product_extra_options_for_woocommerce_form_style' => array(
								'values'        => 'predefined-style',
								'default_value' => '',
							),
						),
					),
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'    => 'number',
					'name'          => 'qode_product_extra_options_for_woocommerce_label_font_size',
					'title'         => esc_html__( 'Label Font Size', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Set the label font size in pixels', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => '13',
					'dependency'    => array(
						'show' => array(
							'qode_product_extra_options_for_woocommerce_form_style' => array(
								'values'        => 'predefined-style',
								'default_value' => '',
							),
						),
					),
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'    => 'number',
					'name'          => 'qode_product_extra_options_for_woocommerce_description_font_size',
					'title'         => esc_html__( 'Description Font Size', 'qode-product-extra-options-for-woocommerce' ),
					'description'   => esc_html__( 'Set the description font size in pixels', 'qode-product-extra-options-for-woocommerce' ),
					'default_value' => '13',
					'dependency'    => array(
						'show' => array(
							'qode_product_extra_options_for_woocommerce_form_style' => array(
								'values'        => 'predefined-style',
								'default_value' => '',
							),
						),
					),
				)
			);

			$form_style_section->add_field_element(
				array(
					'field_type'  => 'text',
					'name'        => 'qode_product_extra_options_for_woocommerce_form_style_custom_css_class',
					'title'       => esc_html__( 'Form Style Custom CSS Class', 'qode-product-extra-options-for-woocommerce' ),
					'description' => esc_html__( 'Set a custom css class for the form style holder if you want to be able to use it and apply your own styling with the css selector that you have defined', 'qode-product-extra-options-for-woocommerce' ),
				)
			);

			do_action( 'qode_product_extra_options_for_woocommerce_action_framework_after_style_options_map', $page );
		}
	}

	add_action( 'qode_product_extra_options_for_woocommerce_action_default_options_init', 'qode_product_extra_options_for_woocommerce_add_style_options' );
}
