<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Container for Blocks
 *
 * @param Qode_Product_Extra_Options_For_WooCommerce_Frontend_Module $instance
 * @param WC_Product $product
 */

$form_style            = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_form_style' );
$form_style_custom_css = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_form_style_custom_css_class' );

$holder_classes   = array();
$holder_classes[] = 'qpeofw-container';
$holder_classes[] = $form_style ? 'qpeofw-form-style--' . esc_html( $form_style ) : '';
$holder_classes[] = $form_style_custom_css ? esc_html( $form_style_custom_css ) : '';

if ( $product instanceof WC_Product_Variable && empty( $product->get_default_attributes() ) ) {
	$product_price = 0;
} else {
	$product_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_product_price', qode_product_extra_options_for_woocommerce_get_display_price( $product ), $product );
}

do_action( 'qode_product_extra_options_for_woocommerce_product_addons_action_before_main_container' );

// Creating nonce and adding to request - needed for initial print of blocks.
$nonce                               = wp_create_nonce( 'qodef-blocks-cart-nonce' );
$_REQUEST['qodef-blocks-cart-nonce'] = sanitize_text_field( $nonce );
?>

	<?php
		// Needed nonce for use in woocommerce cart.
		wp_nonce_field( 'qodef_blocks_cart', 'qodef-blocks-cart-nonce' );
	?>
	<!-- #qpeofw-container START -->
	<div id="qpeofw-container" <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?> data-product-price="<?php echo esc_attr( $product_price ); ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
		<?php
			$instance->print_blocks();
		?>
	</div>
	<!-- #qpeofw-container END -->

<?php
do_action( 'qode_product_extra_options_for_woocommerce_product_addons_action_after_main_container' );
