<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Select Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
 * @var string $setting_hide_images
 * @var string $required_message
 * @var array  $settings
 * @var string $addon_image_position
 * @var int    $options_total
 * @var string $options_width_select_css
 * @var string $currency
 * @var WC_Product $product
*/

extract( $settings ); // @codingStandardsIgnoreLine

$hide_option_images = wc_string_to_bool( $hide_option_images );
$is_required        = 'yes' === $addon_required ? 'required' : '';

$holder_classes   = array();
$holder_classes[] = 'qpeofw-option';

$select_classes   = array();
$select_classes[] = 'qpeofw-option-value';
$select_classes[] = ! empty( $addon_image_replacement ) ? 'qpeofw-image-replacement--' . $addon_image_replacement : '';

$product = qode_product_extra_options_for_woocommerce_get_global_product();
?>
<div id="qpeofw-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?>>
	<div class="qpeofw-label <?php echo wp_kses_post( ! empty( $addon_image_position ) ? 'qpeofw-position-' . $addon_image_position : '' ); ?>">
		<?php
		if ( ! $hide_options_images && ( 'above' === $addon_image_position || 'left' === $addon_image_position || 'right' === $addon_image_position ) ) {
			echo '<div class="qpeofw-option-image"></div>';
		}
		?>
		<div class="qpeofw-select-content">
			<select id="qpeofw-<?php echo esc_attr( $addon->id ); ?>"
				name="qpeofw[][<?php echo esc_attr( $addon->id ); ?>]"
				<?php qode_product_extra_options_for_woocommerce_class_attribute( $select_classes ); ?>
				data-addon-id="<?php echo esc_attr( $addon->id ); ?>"
				style="<?php echo esc_attr( $options_width_select_css ); ?>"
				<?php echo esc_attr( $is_required ); ?>
			>
			<option value="default"><?php echo esc_html( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_select_option_label', esc_html__( 'Select an option', 'qode-product-extra-options-for-woocommerce' ) ) ); ?></option>
			<?php
			for ( $x = 0; $x < $options_total; $x++ ) {
				if ( file_exists( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates/front/addons/select-option.php' ) ) {
					$enabled = $addon->get_option( 'addon_enabled', $x, 'yes', false );

					if ( 'yes' === $enabled ) {

						$option_show_image = $addon->get_option( 'show_image', $x, false, false );
						$option_image      = $option_show_image ? $addon->get_option( 'image', $x ) : '';

						// TODO: improve price calculation.
						$price_method       = $addon->get_option( 'price_method', $x, 'free', false );
						$price_type         = $addon->get_option( 'price_type', $x, 'fixed', false );
						$default_price      = $addon->get_default_price( $x );
						$default_sale_price = $addon->get_default_sale_price( $x );
						$price              = $addon->get_price( $x, true, $product );
						$price_sale         = $addon->get_sale_price( $x, $product );
						$price              = floatval( str_replace( ',', '.', $price ) );
						$price_sale         = '' !== $price_sale ? floatval( str_replace( ',', '.', $price_sale ) ) : '';

						// TODO: improve price calculation.
						if ( 'free' === $price_method ) {
							$default_price      = '0';
							$default_sale_price = '0';
							$price              = '0';
							$price_sale         = '0';
						} elseif ( 'decrease' === $price_method ) {
							$default_price      = $default_price > 0 ? - $default_price : 0;
							$default_sale_price = '0';
							$price              = $price > 0 ? - $price : 0;
							$price_sale         = '0';
						} elseif ( 'product' === $price_method ) {
							$default_price      = $default_price > 0 ? $default_price : 0;
							$default_sale_price = '0';
							$price              = $price > 0 ? $price : 0;
							$price_sale         = '0';
						} else {
							$default_price      = $default_price > 0 ? $default_price : '0';
							$default_sale_price = $default_sale_price >= 0 ? $default_sale_price : 'undefined';

							$price      = $price > 0 ? $price : '0';
							$price_sale = $price_sale >= 0 ? $price_sale : 'undefined';
						}

						wc_get_template(
							'select-option.php',
							apply_filters(
								'qode_product_extra_options_for_woocommerce_filter_addon_select_option_args',
								array(
									'addon'               => $addon,
									'x'                   => $x,
									'setting_hide_images' => $setting_hide_images,
									'required_message'    => $required_message,
									'settings'            => $settings,
									// Addon options.
									'option_image'        => is_ssl() ? str_replace( 'http://', 'https://', $option_image ) : $option_image,
									'default_price'       => $default_price,
									'default_sale_price'  => $default_sale_price,
									'price'               => $price,
									'price_method'        => $price_method,
									'price_sale'          => $price_sale,
									'price_type'          => $price_type,
									'currency'            => $currency,
									'product'             => $product,
								),
								$addon
							),
							'',
							QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates/front/addons/'
						);
					}
				}
			}
			?>
			</select>
			<?php
			if ( ! $hide_options_images && 'under' === $addon_image_position ) {
				echo '<div class="qpeofw-option-image"></div>';
			}
			?>
			<!--option description added with js if exists-->
			<p class="qpeofw-option-description"></p>
		</div>
	</div>
</div>
