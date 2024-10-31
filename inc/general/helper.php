<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_set_css_variables_from_options_styles' ) ) {
	/**
	 * Function that generates module inline styles
	 *
	 * @param string $style
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_set_css_variables_from_options_styles( $style ) {
		$main_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_main_color' );

		if ( ! empty( $main_color ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-main-color' => $main_color ) );
		}

		$required_option_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_options_required_option_color' );

		if ( ! empty( $required_option_color ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-required-option-color' => $required_option_color ) );
		}

		$form_border_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_borders_color' );

		if ( ! empty( $form_border_color ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-form-border-color' => $form_border_color ) );
		}

		$radio_buttons_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_radio_buttons_color' );

		if ( ! empty( $radio_buttons_color ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-radio-buttons-color' => $radio_buttons_color ) );
		}

		$radio_buttons_color_inactive = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_radio_buttons_color_inactive' );

		if ( ! empty( $radio_buttons_color_inactive ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-radio-buttons-color-inactive' => $radio_buttons_color_inactive ) );
		}

		$label_font_size = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_label_font_size' );

		if ( ! empty( $label_font_size ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-label-font-size' => $label_font_size . 'px' ) );
		}

		$description_font_size = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_description_font_size' );

		if ( ! empty( $description_font_size ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-description-font-size' => $description_font_size . 'px' ) );
		}

		$checkbox_style = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_checkbox_style' );

		if ( ! empty( $checkbox_style ) && 'rounded' === $checkbox_style ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-checkbox-radius-style' => '50%' ) );
		}

		$color_swatches_size = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_color_swatches_size' );

		if ( ! empty( $color_swatches_size ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-color-swatch-size' => $color_swatches_size . 'px' ) );
		}

		$color_swatches_style = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_color_swatches_style' );

		if ( ! empty( $color_swathes_style ) && ( 'rounded' === $color_swathes_style || 'circle' === $color_swathes_style ) ) {
			$background_styles[] = '--qpeofw-color-swatch-style: 50%;';
		}

		$upload_file_background_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_upload_file_background_color' );

		if ( ! empty( $upload_file_background_color ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-upload-file-background-color' => $upload_file_background_color ) );
		}

		$upload_file_border_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_upload_file_border_color' );

		if ( ! empty( $upload_file_border_color ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( ':root', array( '--qpeofw-upload-file-border-color' => $upload_file_border_color ) );
		}

		return $style;
	}

	add_filter( 'qode_product_extra_options_for_woocommerce_filter_add_inline_style', 'qode_product_extra_options_for_woocommerce_set_css_variables_from_options_styles' );
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_set_general_styles' ) ) {
	/**
	 * Function that generates module inline styles
	 *
	 * @param string $style
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_set_general_styles( $style ) {
		$styles = array();

		$block_background_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_block_background_color' );
		$block_padding          = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_block_content_padding' );

		if ( ! empty( $block_background_color ) ) {
			$styles['background-color'] = $block_background_color;
		}

		if ( ! empty( $block_padding ) ) {
			$styles['padding'] = $block_padding;
		}

		if ( ! empty( $styles ) ) {

			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( '.qpeofw-block', $styles );
		}

		$price_box_style = array();

		$price_box_text_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_price_box_text_color' );
		if ( ! empty( $price_box_text_color ) ) {
			$price_box_style['color'] = $price_box_text_color . ' !important';
		}

		$price_box_background_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_price_box_background_color' );
		if ( ! empty( $price_box_background_color ) ) {
			$price_box_style['background-color'] = $price_box_background_color;
		}

		if ( ! empty( $price_box_style ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( '#qpeofw-total-price-table', $price_box_style );
		}

		$tooltip_position = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_tooltip_position' );

		$tooltip_style        = array();
		$tooltip_border_style = array();

		$tooltip_text_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_tooltip_text_color' );
		if ( ! empty( $tooltip_text_color ) ) {
			$tooltip_style['color'] = $tooltip_text_color;
		}

		$tooltip_background_color = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_tooltip_background_color' );
		if ( ! empty( $tooltip_background_color ) ) {
			$tooltip_style['background-color'] = $tooltip_background_color;

			switch ( $tooltip_position ) {
				case 'top':
					$tooltip_border_style['border-top-color'] = $tooltip_background_color;
					break;
				case 'bottom':
					$tooltip_border_style['border-bottom-color'] = $tooltip_background_color;
					break;
			}
		}

		if ( ! empty( $tooltip_style ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style( '.qpeofw-block .qpeofw-addon .qpeofw-option .qpeofw-tooltip .qpeofw-tooltip-text', $tooltip_style );
		}

		if ( ! empty( $tooltip_border_style ) ) {
			$style .= qode_product_extra_options_for_woocommerce_dynamic_style(
				array(
					'.qpeofw-block .qpeofw-addon .qpeofw-option .qpeofw-tooltip .qpeofw-tooltip-text:after',
					'.qpeofw-block .qpeofw-addon .qpeofw-option .qpeofw-tooltip.qpeofw-position-bottom .qpeofw-tooltip-text:after',
				),
				$tooltip_border_style
			);
		}

		return $style;
	}

	add_filter( 'qode_product_extra_options_for_woocommerce_filter_add_inline_style', 'qode_product_extra_options_for_woocommerce_set_general_styles' );
}
