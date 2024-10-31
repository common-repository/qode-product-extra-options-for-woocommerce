<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * HTML Separator Template
 *
 * @var object $addon
 * @var array  $settings
 */

// Settings configuration.
extract( $settings ); // @codingStandardsIgnoreLine

$separator_styles = array();

if ( 'empty_space' === $separator_style ) {
	$separator_styles[] = 'height: ' . $separator_size . 'px';
} else {
	$separator_styles[] = 'width: ' . $separator_width . '%';
	$separator_styles[] = 'border-bottom-width: ' . $separator_size . 'px';

	if ( ! empty( $separator_color ) ) {
		$separator_styles[] = 'border-color: ' . $separator_color;
	}
}

$holder_classes   = array();
$holder_classes[] = 'qpeofw-html-separator';
$holder_classes[] = 'qpeofw-' . str_replace( '_', '-', $separator_style );

if ( ! empty( $separator_class ) ) {
	$holder_classes[] = $separator_class;
}

?>

<div <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?> <?php qode_product_extra_options_for_woocommerce_inline_style( $separator_styles ); ?>></div>
