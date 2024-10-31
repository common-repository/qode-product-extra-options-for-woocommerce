<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Select option Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
 * @var int    $x
 * @var string $setting_hide_images
 * @var string $required_message
 * @var array  $settings
 * @var string $option_image
 * @var string $default_price
 * @var string $default_sale_price
 * @var string $price
 * @var string $price_method
 * @var string $price_sale
 * @var string $price_type
 * @var string $currency
 * @var WC_Product $product
 */

extract( $settings ); // @codingStandardsIgnoreLine

$hide_options_prices = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_hide_option_prices', $hide_option_prices, $addon );
$hide_options_prices = wc_string_to_bool( $hide_options_prices );

$image_replacement = '';
if ( 'addon' === $addon_image_replacement ) {
	$image_replacement = $addon_image;
	$image_replacement = wp_get_attachment_image_src( $addon_image, 'full' );
	// get only src.
	$image_replacement = $image_replacement[0];
} elseif ( ! empty( $option_image ) && 'options' === $addon_image_replacement ) {
	$image_replacement = $option_image;
	$image_replacement = wp_get_attachment_image_src( $option_image, 'full' );
	// get only src.
	$image_replacement = $image_replacement[0];
}

$image_replacement = is_ssl() ? str_replace( 'http://', 'https://', $image_replacement ) : $image_replacement;

$selected           = $addon->get_option( 'default', $x, 'no' ) === 'yes' ? 'selected="selected"' : '';
$option_description = $addon->get_option( 'description', $x );

$option_disabled = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_select_option_disabled', false, $addon, $x );

?>

<option value="<?php echo esc_attr( $x ); ?>" <?php echo esc_attr( $selected ); ?>
		data-default-price="<?php echo esc_attr( $default_price ); ?>"
		<?php
		if ( $default_price > 0 ) {
			?>
			data-default-sale-price="<?php echo esc_attr( $default_sale_price ); ?>"
			<?php
		}
		?>
		data-price="<?php echo esc_attr( $price ); ?>"
	<?php
	if ( $price > 0 ) {
		?>
		data-price-sale="<?php echo esc_attr( $price_sale ); ?>"
		<?php
	}

	$data_image = wp_get_attachment_image_src( $option_image, 'full', false );
	?>
		data-price-type="<?php echo esc_attr( $price_type ); ?>"
		data-price-method="<?php echo esc_attr( $price_method ); ?>"
		data-first-free-enabled="<?php echo esc_attr( $first_options_selected ); ?>"
		data-first-free-options="<?php echo esc_attr( $first_free_options ); ?>"
		data-addon-id="<?php echo esc_attr( $addon->id ); ?>"
		data-image="<?php echo esc_url( $data_image ? esc_url( $data_image[0] ) : '' ); ?>"
		data-replace-image="<?php echo esc_attr( $image_replacement ); ?>"
		data-description="<?php echo wp_kses_post( $option_description ); ?>"
		<?php
		if ( $option_disabled ) :
			echo 'disabled';
		endif;
		?>
>
	<?php echo wp_kses_post( $addon->get_option( 'label', $x ) ); ?>
	<?php
	$option_price_html = '';
	if ( ! $hide_options_prices ) {
		$option_price_html = $addon->get_option_price_html( $x, $currency, $product );
	}
	?>
	<?php echo wp_kses_post( $option_price_html ); ?>
</option>
