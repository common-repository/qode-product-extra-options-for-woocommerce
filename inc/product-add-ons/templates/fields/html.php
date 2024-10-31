<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the html field
 *
 * @var array $field The field.
 */

list ( $html ) = qode_product_extra_options_for_woocommerce_extract( $field, 'html' );

$html = ! ! $html ? $html : '';

echo qode_product_extra_options_for_woocommerce_framework_wp_kses_html( 'html', $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
