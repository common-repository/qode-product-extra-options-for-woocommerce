<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Image Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
 * @var int $x
 * @var string $option_image
 * @var string $addon_image_position
 * @var string $images_height_style
 */

$attachment_id = wp_get_attachment_image( $option_image );
if ( is_numeric( $option_image ) ) {
	$image_url = wp_get_attachment_image_url( $option_image, 'full' );
} else {
	$image_url = $option_image;
}

$option_image_alt = empty( $attachment_id ) ? '' : get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

if ( ! empty( $addon_image_position ) && ! empty( $option_image ) ) : ?>
<div class="qpeofw-image-container" for="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">
	<span class="qpeofw-image">
		<img src="<?php echo esc_url( $image_url ); ?>" <?php qode_product_extra_options_for_woocommerce_inline_style( $images_height_style ); ?> alt="<?php echo esc_attr( $option_image_alt ); ?>">
	</span>
</div>

<?php endif; ?>
