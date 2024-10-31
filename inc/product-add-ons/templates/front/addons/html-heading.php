<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * HTML Heading Template
 *
 * @var object $addon
 * @var array  $settings
 */

// Settings configuration.
extract( $settings ); // @codingStandardsIgnoreLine

$holder_classes   = array();
$holder_classes[] = 'qpeofw-html-heading';

if ( ! empty( $heading_class ) ) {
	$holder_classes[] = $heading_class;
}

$heading_styles = array();
if ( ! empty( $heading_color ) ) {
	$heading_styles[] = 'color: ' . $heading_color;
}
?>

<<?php qode_product_extra_options_for_woocommerce_escape_title_tag( $heading_type ); ?> <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?> <?php qode_product_extra_options_for_woocommerce_inline_style( $heading_styles ); ?>>
	<?php echo wp_kses_post( $heading_text ); ?>
</<?php qode_product_extra_options_for_woocommerce_escape_title_tag( $heading_type ); ?>>
