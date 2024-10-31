<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Colorpicker Template
 *
 * @var object $addon
 * @var int    $x
 * @var string $setting_hide_images
 * @var string $required_message
 * @var array  $settings
 * @var string $image_replacement
 * @var string $option_description
 * @var string $option_image
 * @var string $default_price
 * @var string $default_sale_price
 * @var string $price
 * @var string $price_method
 * @var string $price_sale
 * @var string $price_type
 * @var string $currency
 */

// Settings configuration.
extract( $settings ); // @codingStandardsIgnoreLine

$hide_options_prices = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_hide_option_prices', $hide_option_prices, $addon );

$hide_option_images  = wc_string_to_bool( $hide_option_images );
$hide_option_label   = wc_string_to_bool( $hide_option_label );
$hide_option_prices  = wc_string_to_bool( $hide_option_prices );
$hide_product_prices = wc_string_to_bool( $hide_product_prices );

$image_replacement = array( 'data-replace-image' => $addon->get_image_replacement( $addon, $x ) );

// Option configuration.
$required         = $addon->get_option( 'required', $x, 'no', false ) === 'yes';
$checked          = $addon->get_option( 'default', $x, 'no', false ) === 'yes';
$colorpicker_show = $addon->get_option( 'colorpicker_show', $x, 'default_color' );
$colorpicker      = $addon->get_option( 'colorpicker', $x, '#ffffff' );
if ( 'placeholder' === $colorpicker_show ) {
	$colorpicker = '';
}
$placeholder   = $addon->get_option( 'placeholder', $x );
$default_color = 'default_color' === $colorpicker_show ? wp_kses_post( $colorpicker ) : '';

$colorpickerstyle = apply_filters( 'qode_product_extra_options_for_woocommerce_product_addons_filter_color_picker_input', 'text' );

$holder_classes = array();

$holder_classes[] = 'qpeofw-option';
$holder_classes[] = ! empty( $selection_type ) ? 'qpeofw-selection--' . $selection_type : '';
$holder_classes[] = $checked ? 'qpeofw-selected' : '';
?>

<div id="qpeofw-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?> <?php qode_product_extra_options_for_woocommerce_inline_attrs( $image_replacement ); ?>>

	<div class="qpeofw-label <?php echo wp_kses_post( ! empty( $addon_image_position ) ? 'qpeofw-position-' . $addon_image_position : '' ); ?>">

		<div class="option-container">

			<!-- ABOVE / LEFT IMAGE -->
			<?php
			if ( 'above' === $addon_options_images_position || 'left' === $addon_options_images_position ) {
				wc_get_template(
					'option-image.php',
					apply_filters(
						'qode_product_extra_options_for_woocommerce_filter_addon_image_option_args',
						array(
							'addon'                => $addon,
							'x'                    => $x,
							// Addon options.
							'option_image'         => $option_image,
							'hide_option_images'   => $hide_option_images,
							'addon_image_position' => $addon_image_position,
							'images_height_style'  => isset( $images_height_style ) ? $images_height_style : '',
						)
					),
					'',
					QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates/front/'
				);
			}
			?>

			<!-- LABEL -->
			<?php
			$label_text  = $addon->get_option( 'label', $x );
			$label_price = $addon->get_option_price_html( $x, $currency, $product );

			if ( ( ! $hide_option_label || ! $hide_option_prices ) && ( ! empty( $label_text ) || ! empty( $label_price ) ) ) {
				?>
			<label class="qpeofw-addon-label" for="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">
				<?php
				if ( ! $hide_option_label ) {
					echo esc_html( $label_text );
				}
				?>
				<?php
				if ( $required && ! $hide_option_label && ! empty( $label_text ) ) :
					echo '<span class="qpeofw-required">*</span>';
				endif;
				?>

				<?php
				if ( ! $hide_option_prices ) {
					echo wp_kses_post( $label_price );
				}
				?>
			</label>
			<?php } ?>

			<!-- UNDER / RIGHT IMAGE -->
			<?php
			if ( 'under' === $addon_options_images_position || 'right' === $addon_options_images_position ) {
				wc_get_template(
					'option-image.php',
					apply_filters(
						'qode_product_extra_options_for_woocommerce_filter_addon_image_option_args',
						array(
							'addon'                => $addon,
							'x'                    => $x,
							// Addon options.
							'option_image'         => $option_image,
							'hide_option_images'   => $hide_option_images,
							'addon_image_position' => $addon_image_position,
							'images_height_style'  => isset( $images_height_style ) ? $images_height_style : '',
						)
					),
					'',
					QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates/front/'
				);
			}
			?>
		</div>
		<div class="qpeofw-colorpicker-container">
			<!-- Colorpicker -->
			<input type="<?php echo esc_attr( $colorpickerstyle ); ?>"
				class="wp-color-picker qpeofw-option-value"
				id="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
				name="qpeofw[][<?php echo esc_attr( $addon->id . '-' . $x ); ?>]"
				data-alpha-enabled="true"
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
				?>
				data-price-type="<?php echo esc_attr( $price_type ); ?>"
				data-price-method="<?php echo esc_attr( $price_method ); ?>"
				data-addon-id="<?php echo esc_attr( $addon->id ); ?>"
				data-addon-colorpicker-show="<?php echo esc_attr( $colorpicker_show ); ?>"
				<?php if ( ! empty( $default_color ) ) : ?>
				data-default-color="<?php echo wp_kses_post( $default_color ); ?>"
				<?php endif; ?>
				data-addon-placeholder="<?php echo esc_attr( $placeholder ); ?>"
			<?php
			if ( $addon->get_option( 'required', $x, 'no', false ) === 'yes' ) :
				echo 'required';
			endif;
			?>
			/>
		</div>

	</div>

	<!-- TOOLTIP -->
	<?php if ( 'yes' === qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_show_tooltips' ) && '' !== $addon->get_option( 'tooltip', $x ) ) : ?>
		<span class="qpeofw-tooltip qpeofw-position-<?php echo esc_attr( qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_tooltip_position' ) ); ?>">
			<span class="qpeofw-tooltip-text"><?php echo wp_kses_post( $addon->get_option( 'tooltip', $x ) ); ?></span>
		</span>
	<?php endif; ?>

	<!-- DESCRIPTION -->
	<?php if ( '' !== $option_description ) : ?>
		<p class="qpeofw-option-description"><?php echo wp_kses_post( $option_description ); ?></p>
	<?php endif; ?>
	<!-- Sold individually -->
	<?php if ( 'yes' === $sell_individually ) : ?>
		<input type="hidden" name="qpeofw_sell_individually[<?php echo esc_attr( $addon->id . '-' . $x ); ?>]" value="yes">
	<?php endif; ?>
</div>
