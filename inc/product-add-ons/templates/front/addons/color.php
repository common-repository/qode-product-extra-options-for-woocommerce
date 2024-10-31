<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Color Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
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

$required           = $addon->get_option( 'required', $x, 'no', false ) === 'yes';
$checked            = $addon->get_option( 'default', $x, 'no', false ) === 'yes';
$color_type         = $addon->get_option( 'color_type', $x, 'color' );
$gradient_rendering = $addon->get_option( 'gradient_rendering', $x, '', false );
$color_a            = $addon->get_option( 'color', $x, '', false );
$color_b            = $addon->get_option( 'color_b', $x, '', false );

$background_styles = array();

if ( in_array( $color_type, array( 'color', 'single', 'double' ), true ) ) {
	if ( ! empty( $color_a ) && ! empty( $color_b ) ) {
		if ( ! empty( $gradient_rendering ) && 'smooth' === $gradient_rendering ) {
			$background_styles[] = 'background: linear-gradient(0deg, ' . esc_attr( $color_a ) . ' 0%,' . esc_attr( $color_b ) . ' 100%)';
		} else {
			$background_styles[] = 'background: linear-gradient(135deg, ' . esc_attr( $color_a ) . ' 50%,' . esc_attr( $color_b ) . ' 50%)';
		}
	} elseif ( ! empty( $color_a ) ) {
		$background_styles[] = 'background: ' . $color_a;
	} elseif ( ! empty( $color_b ) ) {
		$background_styles[] = 'background: ' . $color_b;
	}
}

$color_swathes_size = intval( $color_swathes_size );

if ( $color_swathes_size ) {
	$background_styles[] = '--qpeofw-color-swatch-size: ' . $color_swathes_size . 'px;';
}

if ( ! empty( $color_swathes_style ) && ( 'rounded' === $color_swathes_style || 'circle' === $color_swathes_style || 'circle-inline' === $color_swathes_style ) ) {
	$background_styles[] = '--qpeofw-color-swatch-style: 50%;';
}

$holder_classes = array();

$holder_classes[] = 'qpeofw-option qpeofw-color-swatch';
$holder_classes[] = ! empty( $selection_type ) ? 'qpeofw-selection--' . $selection_type : '';
$holder_classes[] = $checked ? 'qpeofw-selected' : '';
$holder_classes[] = $hide_option_label ? 'qpeofw-option-label--no' : 'qpeofw-option-label--yes';
$holder_classes[] = $hide_option_prices ? 'qpeofw-option-prices--no' : 'qpeofw-option-prices--yes';

if ( $color_type ) {
	$holder_classes[] = 'qpeofw-swatch-type--' . $color_type;
}

$color_swatch_style_global = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_color_swatches_style' );

if ( 'default' !== $color_swathes_style ) {
	// addon option.
	$holder_classes[] = 'qpeofw-swatch-style--' . $color_swathes_style;
} else {
	$holder_classes[] = 'qpeofw-swatch-style--' . $color_swatch_style_global;
}

?>
<div <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?> <?php qode_product_extra_options_for_woocommerce_inline_attrs( $image_replacement ); ?>>

	<input type="checkbox"
		id="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
		class="qpeofw-standard-checkbox qpeofw-option-value"
		name="qpeofw[][<?php echo esc_attr( $addon->id . '-' . $x ); ?>]"
		value="<?php echo esc_attr( $addon->get_option( 'label', $x ) ); ?>"
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
		data-first-free-enabled="<?php echo esc_attr( $first_options_selected ); ?>"
		data-first-free-options="<?php echo esc_attr( $first_free_options ); ?>"
		data-addon-id="<?php echo esc_attr( $addon->id ); ?>"
		<?php
		if ( $required ) :
			echo 'required';
		endif;
		?>
		<?php
		if ( $checked ) :
			echo 'checked="checked"';
		endif;
		?>
		style="display: none;">

	<!-- LABEL -->
	<label class="qpeofw-label <?php echo wp_kses_post( ! empty( $addon_image_position ) ? 'qpeofw-position-' . $addon_image_position : '' ); ?>" for="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">

		<span class="qpeofw-color-container">
			<span class="qpeofw-color-container-inner">
				<span class="qpeofw-color" <?php qode_product_extra_options_for_woocommerce_inline_style( $background_styles ); ?>>
					<?php
					if ( 'image' === $color_type ) {
						$image_src = wp_get_attachment_image_src( $addon->get_option( 'color_image', $x ), 'full' );

						if ( ! empty( $image_src[0] ) ) {
							echo '<img src="' . esc_url( $image_src[0] ) . '">';
						}
					}
					?>
				</span>
			</span>

			<span class="qpeofw-option-container">
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

				<small class="qpeofw-option-label">
					<?php
					if ( ! $hide_option_label ) {
						echo wp_kses_post( $addon->get_option( 'label', $x ) );
					}
					?>
				</small>
				<?php
				if ( ! $hide_option_prices ) {
					echo wp_kses_post( $addon->get_option_price_html( $x, $currency, $product ) );
				}
				?>

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
			</span>

			<!-- DESCRIPTION -->
			<?php if ( '' !== $option_description ) : ?>
				<span class="qpeofw-option-description"><?php echo wp_kses_post( $option_description ); ?></span>
			<?php endif; ?>
		</span>

		<!-- TOOLTIP -->
		<?php if ( 'yes' === qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_show_tooltips' ) && '' !== $addon->get_option( 'tooltip', $x ) ) : ?>
			<span class="qpeofw-tooltip qpeofw-position-<?php echo esc_attr( qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_tooltip_position' ) ); ?>">
			<span class="qpeofw-tooltip-text"><?php echo wp_kses_post( $addon->get_option( 'tooltip', $x ) ); ?></span>
		</span>
		<?php endif; ?>

	</label>
	<!-- Sold individually -->
	<?php if ( 'yes' === $sell_individually ) : ?>
		<input type="hidden" name="qpeofw_sell_individually[<?php echo esc_attr( $addon->id . '-' . $x ); ?>]" value="yes">
	<?php endif; ?>
</div>
