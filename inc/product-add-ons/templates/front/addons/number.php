<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Number Template
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
$default_value = '';
$minimum_value = '';
$maximum_value = '';
$required      = $addon->get_option( 'required', $x, 'no', false ) === 'yes';
$number_limit  = $addon->get_option( 'number_limit', $x );

if ( 'yes' === $number_limit ) {
	$minimum_value = $addon->get_option( 'number_limit_min', $x );
	$maximum_value = $addon->get_option( 'number_limit_max', $x );
}

$show_number_option = $addon->get_option( 'show_number_option', $x, 'default', false );
if ( 'default' === $show_number_option ) {
	$default_value = $addon->get_option( 'default_number', $x, '', false );
}

$default_value = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_default_addon_number', $default_value, $addon );
$step_value    = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_default_addon_number_step', '', $addon, $x );

$allow_decimals = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_allow_decimals_number', false );
$form_style     = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_form_style' );

?>

<div id="qpeofw-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
		class="qpeofw-option qpeofw-quantity-addon"
		data-replace-image="<?php echo esc_attr( $image_replacement ); ?>">

	<div class="qpeofw-label <?php echo wp_kses_post( ! empty( $addon_image_position ) ? 'qpeofw-position-' . $addon_image_position : '' ); ?>" for="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">

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

			<!-- LABEL -->
			<label class="qpeofw-addon-label" for="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>">
				<?php
				if ( ! $hide_option_label ) :
					echo esc_html( $addon->get_option( 'label', $x ) );
				endif;

				if ( $required ) :
					echo '<span class="qpeofw-required">*</span>';
				endif;
				?>

				<!-- PRICE -->
				<?php
				if ( ! $hide_option_prices && 'value_x_product' !== $price_method ) {
					echo wp_kses_post( $addon->get_option_price_html( $x, $currency, $product ) );
				}
				?>
			</label>

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
		<div class="qpeofw-input-number-holder">
			<!-- INPUT -->
			<?php if ( $form_style && qode_product_extra_options_for_woocommerce_is_installed( 'qpeofw-premium' ) && qode_product_extra_options_for_woocommerce_premium_is_plugin_activated() ) : ?>
				<span class="qpeofw-quantity-minus"></span>
			<?php endif; ?>
			<input type="number"
					id="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
					class="qpeofw-option-value"
					name="qpeofw[][<?php echo esc_attr( $addon->id . '-' . $x ); ?>]"
					placeholder="0"
				<?php if ( 'yes' === $number_limit ) { ?>
					min="<?php echo esc_attr( $minimum_value ); ?>"
					max="<?php echo esc_attr( $maximum_value ); ?>"
				<?php } else { ?>
					min="0"
				<?php } ?>
					step="1"
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
				if ( $addon->get_option( 'required', $x, 'no', false ) === 'yes' ) :
					echo 'required';
				endif;
				?>
				<?php if ( '' !== $default_value ) : ?>
					value="<?php echo esc_attr( $default_value ); ?>"
				<?php endif ?>
				<?php if ( '' !== $step_value ) : ?>
					step="<?php echo esc_attr( $step_value ); ?>"
				<?php endif ?>
			>
			<?php if ( $form_style && qode_product_extra_options_for_woocommerce_is_installed( 'qpeofw-premium' ) && qode_product_extra_options_for_woocommerce_premium_is_plugin_activated() ) : ?>
				<span class="qpeofw-quantity-plus"></span>
			<?php endif; ?>
		</div>
	</div>

	<!-- TOOLTIP -->
	<?php if ( $addon->get_option( 'tooltip', $x ) !== '' ) : ?>
		<span class="qpeofw-tooltip qpeofw-position-<?php echo esc_attr( qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_tooltip_position' ) ); ?>">
			<span class="qpeofw-tooltip-text"><?php echo esc_attr( $addon->get_option( 'tooltip', $x ) ); ?></span>
		</span>
	<?php endif; ?>

	<!-- DESCRIPTION -->
	<?php if ( '' !== $option_description ) : ?>
		<p class="qpeofw-option-description">
			<?php echo wp_kses_post( $option_description ); ?>
		</p>
	<?php endif; ?>
	<!-- Sold individually -->
	<?php if ( 'yes' === $sell_individually ) : ?>
		<input type="hidden" name="qpeofw_sell_individually[<?php echo esc_attr( $addon->id . '-' . $x ); ?>]" value="yes">
	<?php endif; ?>
</div>
