<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Text Template
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

$image_replacement = $addon->get_image_replacement( $addon, $x );

// Option configuration.
$required      = $addon->get_option( 'required', $x, 'no', false ) === 'yes';
$remove_spaces = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_remove_spaces', false );
$option_label  = wp_kses_post( $addon->get_option( 'label', $x ) ) . ( $required ? ' <span class="qpeofw-required">*</span>' : '' );
?>

<div id="qpeofw-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" class="qpeofw-option">

	<div class="qpeofw-label <?php echo wp_kses_post( ! empty( $addon_image_position ) ? 'qpeofw-position-' . $addon_image_position : '' ); ?>">

		<div class="qpeofw-option-container">

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

			<?php
			$label_price = $addon->get_option_price_html( $x, $currency, $product );

			if ( ( ! $hide_option_label || ! $hide_option_prices ) && ( ! empty( $option_label ) || ! empty( $label_price ) ) ) {
				?>
				<label class="qpeofw-addon-label" for="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">

					<!-- LABEL -->
					<?php
					if ( ! $hide_option_label ) {
						echo wp_kses_post( $option_label );
					}
					?>

					<!-- PRICE -->
					<?php
					if ( $hide_option_prices ) {
						echo wp_kses_post( $label_price );
					}
					?>

				</label>
				<?php
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
		</div>

		<div class="qpeofw-input-text-container">
			<!-- INPUT -->
			<input type="text"
					id="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
					class="qpeofw-option-value"
					name="qpeofw[][<?php echo esc_attr( $addon->id . '-' . $x ); ?>]"
					value=""
				<?php if ( $addon->get_option( 'characters_limit', $x ) === 'yes' ) : ?>
					minlength="<?php echo esc_attr( $addon->get_option( 'characters_limit_min', $x ) ); ?>"
					maxlength="<?php echo esc_attr( $addon->get_option( 'characters_limit_max', $x ) ); ?>"
				<?php endif; ?>
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
				<?php echo 'characters' === $price_type && $remove_spaces ? 'data-remove-spaces=\'yes\'' : ''; ?>
				<?php
				if ( $required ) :
					echo 'required';
				endif;
				?>
					placeholder="<?php echo esc_attr( $addon->get_option( 'placeholder', $x ) ); ?>"
			>
		</div>
	</div>


	<?php if ( $addon->get_option( 'characters_limit', $x ) === 'yes' ) : ?>
		<?php
			$min_length = $addon->get_option( 'characters_limit_min', $x );
			$max_length = $addon->get_option( 'characters_limit_max', $x );
		if ( ! empty( $min_length ) && ! empty( $max_length ) ) :
			/* Translators: %1$d is $min_length for input, %2$d is $max_length for input */
				$length_error_message = sprintf( __( 'You need to enter between %1$d and %2$d characters.', 'qode-product-extra-options-for-woocommerce' ), $min_length, $max_length );
			elseif ( ! empty( $min_length ) ) :
				/* Translators: %1$d is $min_length for input */
				$length_error_message = sprintf( __( 'You need to enter at least %1$d characters.', 'qode-product-extra-options-for-woocommerce' ), $min_length );
			elseif ( ! empty( $max_length ) ) :
				/* Translators: %1$d is $max_length for input */
				$length_error_message = sprintf( __( 'You need to enter less than %1$d characters.', 'qode-product-extra-options-for-woocommerce' ), $max_length );
			endif;
			?>
		<small class="qpeofw-length-error-message" style="display: none;"><?php echo esc_html( $length_error_message ); ?></small>
	<?php endif; ?>

	<!-- TOOLTIP -->
	<?php if ( $addon->get_option( 'tooltip', $x ) !== '' ) : ?>
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
