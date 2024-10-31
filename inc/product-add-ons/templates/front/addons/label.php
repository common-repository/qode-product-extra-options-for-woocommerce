<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Label Template
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

// Image replacement.
$image_replacement = array( 'data-replace-image' => $addon->get_image_replacement( $addon, $x ) );

// Option configuration.
// The label of the add-on.
$addon_label = $addon->get_option( 'label', $x );

$required = $addon->get_option( 'required', $x, 'no', false ) === 'yes';
$checked  = $addon->get_option( 'default', $x, 'no', false ) === 'yes';

$style_images_position      = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_label_and_images_image_position' );
$style_images_equal_height  = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_label_and_images_equal_height' );
$style_images_height        = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_label_and_images_height' );
$style_label_position       = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_label_and_images_label_position' );
$style_description_position = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_label_and_images_description_position' );

// Individual style options.
$images_position            = '';
$images_height_style        = array();
$label_position_style       = '';
$label_padding_style        = '';
$label_content_align_style  = '';
$description_position_style = '';

// Images position.
if ( 'default' !== $addon_options_images_position ) {
	$images_position = $addon_options_images_position;
} else {
	$images_position = $style_images_position;
}

// Label content alignment.
$label_content_align_style = $label_content_align;

// Force Equal Image Heights.
if ( 'yes' === $image_equal_height ) {
	$images_height_style[] = 'height: ' . $images_height . 'px';
} else {
	if ( 'yes' === $style_images_equal_height ) {
		$images_height_style[] = 'height: ' . $style_images_height . 'px';
	}
}

// Label position.
if ( 'default' !== $label_position ) {
	$label_position_style = $label_position;
} else {
	$label_position_style = $style_label_position;
}

// Description position.
if ( 'default' !== $description_position ) {
	$description_position_style = $description_position;
} else {
	$description_position_style = $style_description_position;
}

// Label padding.
$label_padding_dim   = $label_padding['dimensions'];
$label_padding_style = array();

if ( ! empty( $label_padding_dim['top'] ) ) {
	$label_padding_style[] = 'padding-top: ' . $label_padding_dim['top'] . 'px';
}

if ( ! empty( $label_padding_dim['right'] ) ) {
	$label_padding_style[] = 'padding-right: ' . $label_padding_dim['right'] . 'px';
}

if ( ! empty( $label_padding_dim['bottom'] ) ) {
	$label_padding_style[] = 'padding-bottom: ' . $label_padding_dim['bottom'] . 'px';
}

if ( ! empty( $label_padding_dim['left'] ) ) {
	$label_padding_style[] = 'padding-left: ' . $label_padding_dim['left'] . 'px';
}

$description_html = '' !== $option_description ? '<span class="qpeofw-label-description">' . wp_kses_post( $option_description ) . '</span>' : '';

if ( $hide_option_label && $hide_option_prices ) {
	$label_price_html = '';
} else {
	$label_price_html  = '<label class="qpeofw-label-price" for="qpeofw-' . esc_attr( $addon->id . '-' . $x ) . '">';
	$label_price_html .= ! $hide_option_label ? '<span class="qpeofw-label-text" >' . $addon_label . '</span>' : '';
	$label_price_html .= $required ? ' <span class="qpeofw-required">*</span>' : '';
	if ( 'inside' === $description_position_style ) :
		$label_price_html .= wp_kses_post( $description_html );
	endif;
	$label_price_html .= ! $hide_option_prices ? ' ' . $addon->get_option_price_html( $x, $currency, $product ) : '';
	$label_price_html .= '</label>';
}

$holder_classes = array();

$holder_classes[] = 'qpeofw-option';
$holder_classes[] = $hide_option_label ? 'qpeofw-option-label--no' : 'qpeofw-option-label--yes';
$holder_classes[] = ! empty( $selection_type ) ? 'qpeofw-selection--' . $selection_type : '';
$holder_classes[] = $checked ? 'qpeofw-selected' : '';

$label_style_global = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_label_and_images_style' );

if ( 'default' !== $label_style ) {
	// addon option.
	$holder_classes[] = 'qpeofw-label-style--' . $label_style;
} else {
	$holder_classes[] = 'qpeofw-label-style--' . $label_style_global;
}
?>

<div id="qpeofw-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?> <?php qode_product_extra_options_for_woocommerce_inline_attrs( $image_replacement ); ?>>

	<!-- INPUT -->
	<input type="checkbox"
		id="qpeofw-<?php echo esc_attr( $addon->id . '-' . $x ); ?>"
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

	<?php
		$label_container_classes = array();

		$label_container_classes[] = 'qpeofw-label-container';
		$label_container_classes[] = 'qpeofw-image-position--' . esc_attr( $images_position );
		$label_container_classes[] = 'qpeofw-label-position--' . esc_attr( $label_position_style );
		$label_container_classes[] = 'qpeofw-label-description-position--' . esc_attr( $description_position_style );
		$label_container_classes[] = 'qpeofw-content-align--' . esc_attr( $label_content_align_style );
	?>
	<div <?php qode_product_extra_options_for_woocommerce_class_attribute( $label_container_classes ); ?>>

		<label class="qpeofw-label <?php echo wp_kses_post( ! empty( $addon_image_position ) ? 'qpeofw-position--' . $addon_image_position : '' ); ?>" for="qpeofw-<?php echo esc_attr( $addon->id . '-' . $x ); ?>">

			<?php if ( 'outside' === $label_position_style && 'under' === $images_position ) : ?>
				<?php echo wp_kses_post( $label_price_html ); ?>
			<?php endif; ?>

			<div class="qpeofw-label-container-display" <?php qode_product_extra_options_for_woocommerce_inline_style( $label_padding_style ); ?>>
				<?php
				if ( 'above' === $images_position || 'left' === $images_position ) {
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

				<?php if ( 'inside' === $label_position_style && 'under' === $images_position ) : ?>
					<?php echo wp_kses_post( $label_price_html ); ?>
				<?php endif; ?>

				<?php if ( ( 'inside' === $label_position_style && 'under' !== $images_position ) || 'inside' === $description_position_style ) : ?>
					<div class="qpeofw-label-position-inner-inside">
				<?php endif; ?>

					<?php if ( 'inside' === $label_position_style && 'under' !== $images_position ) : ?>
						<?php echo wp_kses_post( $label_price_html ); ?>
					<?php endif; ?>

				<?php if ( ( 'inside' === $label_position_style && 'under' !== $images_position ) || 'inside' === $description_position_style ) : ?>
					</div>
				<?php endif; ?>

				<?php
				if ( 'under' === $images_position || 'right' === $images_position ) {
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
			<?php if ( ( 'outside' === $label_position_style && 'under' !== $images_position ) || 'outside' === $description_position_style ) : ?>
				<div class="qpeofw-label-position-inner-outside">
			<?php endif; ?>

				<?php if ( 'outside' === $label_position_style && 'under' !== $images_position ) : ?>
					<?php echo wp_kses_post( $label_price_html ); ?>
				<?php endif; ?>

				<?php if ( 'outside' === $description_position_style ) : ?>
					<?php echo wp_kses_post( $description_html ); ?>
				<?php endif; ?>

			<?php if ( ( 'outside' === $label_position_style && 'under' !== $images_position ) || 'outside' === $description_position_style ) : ?>
				</div>
			<?php endif; ?>

			<!-- TOOLTIP -->
			<?php if ( $addon->get_option( 'tooltip', $x ) !== '' ) : ?>
				<span class="qpeofw-tooltip qpeofw-position-<?php echo esc_attr( qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_tooltip_position' ) ); ?>">
					<span class="qpeofw-tooltip-text"><?php echo esc_html( $addon->get_option( 'tooltip', $x ) ); ?></span>
				</span>
			<?php endif; ?>
		</label>

	</div>

	<!-- Sold individually -->
	<?php if ( 'yes' === $sell_individually ) : ?>
		<input type="hidden" name="qpeofw_sell_individually[<?php echo esc_attr( $addon->id . '-' . $x ); ?>]" value="yes">
	<?php endif; ?>
</div>
