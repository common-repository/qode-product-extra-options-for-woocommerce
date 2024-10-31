<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Checkbox Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
 * @var int    $x
 * @var string $setting_hide_images
 * @var string $required_message
 * @var array  $settings
 * @var string $image_replacement
 * @var string $option_description
 * @var string $addon_image_position
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
$show_in_a_grid      = wc_string_to_bool( $show_in_a_grid );

$hide_option_images  = wc_string_to_bool( $hide_option_images );
$hide_option_label   = wc_string_to_bool( $hide_option_label );
$hide_option_prices  = wc_string_to_bool( $hide_option_prices );
$hide_product_prices = wc_string_to_bool( $hide_product_prices );

$image_replacement = array( 'data-replace-image' => $addon->get_image_replacement( $addon, $x ) );

// Option configuration.
$required = $addon->get_option( 'required', $x, 'no', false ) === 'yes';
$checked  = $addon->get_option( 'default', $x, 'no', false ) === 'yes';

$holder_classes = array();

$holder_classes[] = 'qpeofw-option';
$holder_classes[] = ! empty( $selection_type ) ? 'qpeofw-selection--' . $selection_type : '';
$holder_classes[] = $checked ? 'qpeofw-selected' : '';
?>

<div id="qpeofw-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?> <?php qode_product_extra_options_for_woocommerce_inline_attrs( $image_replacement ); ?>>

	<div class="qpeofw-label <?php echo wp_kses_post( ! empty( $addon_image_position ) ? 'qpeofw-position-' . $addon_image_position : '' ); ?>">

		<div class="qpeofw-option-container">

			<!-- ABOVE IMAGE -->
			<?php
			if ( 'above' === $addon_options_images_position ) {
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

			<?php
			$checkbox_style = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_checkbox_style' );
			$holder_class   = $checkbox_style ? 'qpeofw-checkbox-style--' . $checkbox_style : '';
			?>
			<div class="qpeofw-checkbox-button-container <?php echo esc_attr( $holder_class ); ?>">
				<span class="qpeofw-checkbox-button <?php echo esc_attr( $checked ? 'checked' : '' ); ?>">
					<!-- INPUT -->
					<input type="checkbox"
						id="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
						class="qpeofw-option-value"
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
					>
				</span>

				<!-- LABEL -->
				<label class="qpeofw-addon-label" for="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">
					<!-- LEFT / RIGHT IMAGE -->
					<?php
					if ( 'left' === $addon_options_images_position || 'right' === $addon_options_images_position ) {
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
					<span class="qpeofw-addon-label-text">
						<?php
						if ( ! $hide_option_label ) {
							echo esc_html( $addon->get_option( 'label', $x ) );
						}
						?>
						<?php
						if ( $required ) :
							echo '<span class="qpeofw-required">*</span>';
						endif;
						?>

						<!-- PRICE -->
						<?php
						if ( ! $hide_option_prices ) {
							echo wp_kses_post( $addon->get_option_price_html( $x, $currency, $product ) );
						}
						?>
					</span>
				</label>
			</div>

			<!-- UNDER IMAGE -->
			<?php
			if ( 'under' === $addon_options_images_position ) {
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
	</div>
	<!-- DESCRIPTION -->
	<?php if ( '' !== $option_description ) : ?>
		<p class="qpeofw-option-description"><?php echo wp_kses_post( $option_description ); ?></p>
	<?php endif; ?>

	<!-- TOOLTIP -->
	<?php if ( 'yes' === qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_show_tooltips' ) && '' !== $addon->get_option( 'tooltip', $x ) ) : ?>
		<span class="qpeofw-tooltip qpeofw-position-<?php echo esc_attr( qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_tooltip_position' ) ); ?>">
			<span class="qpeofw-tooltip-text"><?php echo wp_kses_post( $addon->get_option( 'tooltip', $x ) ); ?></span>
		</span>
	<?php endif; ?>
	<!-- Sold individually -->
	<?php if ( 'yes' === $sell_individually ) : ?>
		<input type="hidden" name="qpeofw_sell_individually[<?php echo esc_attr( $addon->id . '-' . $x ); ?>]" value="yes">
	<?php endif; ?>
</div>
