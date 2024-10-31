<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Price Table Template
 *
 * @var WC_Product $product
 * @var WC_Product_Variation $variation
 * @var string $total_price_box
 * @var float $blocks_product_price
 */
$suffix          = '';
$suffix_callback = '';

$product_price_label = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_table_product_price_label', esc_html__( 'Product price', 'qode-product-extra-options-for-woocommerce' ) );
$total_options_label = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_table_total_options_label', esc_html__( 'Total options', 'qode-product-extra-options-for-woocommerce' ) );
$order_total_label   = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_table_order_total_label', esc_html__( 'Order total', 'qode-product-extra-options-for-woocommerce' ) );

$price_display_suffix = get_option( 'woocommerce_price_display_suffix', '' );
$price_suffix         = ' <small>' . $price_display_suffix . '</small>';

if ( $price_display_suffix ) {
	if ( strpos( $price_display_suffix, '{price_including_tax}' ) !== false ) {
		$suffix          = '{price_including_tax}';
		$suffix_callback = 'wc_get_price_including_tax';
	} elseif ( strpos( $price_display_suffix, '{price_excluding_tax}' ) !== false ) {
		$suffix          = '{price_excluding_tax}';
		$suffix_callback = 'wc_get_price_excluding_tax';
	}
	if ( $suffix_callback ) {
		$price_callback       = $suffix_callback( $product );
		$price_callback       = wc_price( $price_callback );
		$price_display_suffix = str_replace(
			$suffix,
			$price_callback,
			$price_display_suffix
		);
		$price_suffix         = $price_display_suffix;
	}
}

?>

<div id="qpeofw-total-price-table">
	<table class="<?php echo esc_attr( $total_price_box ); ?>">
		<?php if ( $blocks_product_price > 0 ) : ?>
			<tr class="qpeofw-product-price" style="<?php echo esc_attr( 'only_final' === $total_price_box ? 'display: none;' : '' ); ?>">
				<th><?php echo esc_html( $product_price_label ); ?>:</th>
				<td id="qpeofw-total-product-price"><?php echo wp_kses_post( wc_price( $blocks_product_price ) ); ?><?php echo wc_tax_enabled() ? wp_kses_post( $price_suffix ) : ''; ?></td>
			</tr>
		<?php endif; ?>
		<tr class="qpeofw-total-options" style="<?php echo esc_attr( 'all' !== $total_price_box ? 'display: none;' : '' ); ?>">
			<th><?php echo esc_html( $total_options_label ); ?>:</th>
			<td id="qpeofw-total-options-price"></td>
		</tr>
		<?php if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_table_hide_total_order', true ) ) { ?>
			<tr class="qpeofw-total-order">
				<th><?php echo esc_html( $order_total_label ); ?>:</th>
				<td id="qpeofw-total-order-price"></td>
			</tr>
		<?php } ?>
	</table>
</div>
