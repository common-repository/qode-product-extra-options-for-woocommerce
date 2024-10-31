<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * File Template
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
$required       = $addon->get_option( 'required', $x, 'no', false ) === 'yes';
$allow_multiple = $addon->get_option( 'multiupload', $x, 'no', false ) === 'yes';
$max_multiple   = $addon->get_option( 'multiupload_max', $x, '', false );
$allow_multiple = wc_string_to_bool( $allow_multiple );
$upload_string  = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_upload_file_text_to_show' );
$link_to_show   = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_upload_file_link_to_show' );

$holder_classes = array();

$holder_classes[] = 'qpeofw-option';
$holder_classes[] = $allow_multiple ? 'allow-multiple' : '';
?>

<div id="qpeofw-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?>
	data-option-id="<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
		<?php
		if ( '' !== $max_multiple && $max_multiple > 0 ) {
			?>
			data-max-multiple="<?php echo esc_attr( $max_multiple ); ?>"
			<?php
		}
		?>
>

	<div class="qpeofw-file-input <?php echo wp_kses_post( ! empty( $addon_image_position ) ? 'qpeofw-position-' . $addon_image_position : '' ); ?>">
		<div class="qpeofw-file-input-container">
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
				<label class="qpeofw-addon-label">
					<!-- LABEL -->
					<?php
					if ( ! $hide_option_label ) {
						echo wp_kses_post( $addon->get_option( 'label', $x ) );
					}
					?>

					<!-- PRICE -->
					<?php
					if ( ! $hide_option_prices ) {
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
			<div class="qpeofw-file-container">
				<!-- INPUT -->
				<input type="hidden"
					id="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
					class="option qpeofw-option-value upload-parent"
					value=""
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
				>

				<input id="qpeofw-file-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" type="file" class="qpeofw-file"
					<?php
					if ( $allow_multiple ) :
						echo 'multiple';
					endif;
					?>
				>

				<div class="qpeofw-ajax-uploader">
					<div class="qpeofw-uploaded-file" style="display: none;">
					</div>
					<div class="qpeofw-ajax-uploader-container">
						<?php echo '<span class="qpeofw-upload-text">' . wp_kses_post( $upload_string ) . '</span>'; ?>
						<?php if ( 'text' === $link_to_show ) : ?>
							<span class="qpeofw-upload-button">
								<?php
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo strtoupper( esc_html__( 'upload', 'qode-product-extra-options-for-woocommerce' ) );
									qode_product_extra_options_for_woocommerce_render_svg_icon( 'upload' );
								?>
							</span>
						<?php else : ?>
							&nbsp;<a class="qpeofw-upload-link"><?php echo esc_html__( 'upload', 'qode-product-extra-options-for-woocommerce' ); ?></a>
						<?php endif; ?>
					</div>

				</div>
			</div>
		</div>
	</div>

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
