<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * HTML Text Template
 *
 * @var object $addon
 * @var array  $settings
 */

// Settings configuration.
extract( $settings ); // @codingStandardsIgnoreLine

$holder_classes   = array();
$holder_classes[] = 'qpeofw-html-text';

if ( ! empty( $text_class ) ) {
	$holder_classes[] = $text_class;
}

?>

<div <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?>>
	<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo qode_product_extra_options_for_woocommerce_framework_wp_kses_html( 'html', stripslashes( html_entity_decode( $text_content ) ) );
	?>
</div>
