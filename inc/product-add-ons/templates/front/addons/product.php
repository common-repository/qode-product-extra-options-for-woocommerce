<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Product Template
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

$hide_option_prices  = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_hide_option_prices', $hide_option_prices, $addon );
$hide_option_images  = wc_string_to_bool( $hide_option_images );
$hide_option_label   = wc_string_to_bool( $hide_option_label );
$hide_option_prices  = wc_string_to_bool( $hide_option_prices );
$hide_product_prices = wc_string_to_bool( $hide_product_prices );
$image_replacement   = $addon->get_image_replacement( $addon, $x );

// Option configuration.
$show_variation_att = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_attributes_on_variations', true );

$price_type = '';

$product_id = $addon->get_option( 'product', $x );
$product_id = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_product_id', $product_id );
$_product   = wc_get_product( $product_id );

if ( $_product instanceof WC_Product ) {

	$price_type = '';
	$parent_id  = '';

	$_product_name = $_product->get_title();
	$instock       = $_product->is_in_stock();

	$show_product_description = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_product_description', false );
	$product_description      = $_product->get_short_description();

	if ( 'hide' === $product_out_of_stock && ! $instock ) {
		return;
	}

	$price_method = $addon->get_option( 'price_method', $x, 'free', false );
	if ( 'product' !== $price_method ) {
		$price_type = $addon->get_option( 'price_type', $x, 'fixed', false );
	}
	$selected = $addon->get_option( 'default', $x, 'no' ) === 'yes';
	$checked  = $addon->get_option( 'default', $x, 'no' ) === 'yes' ? 'checked="checked"' : '';
	$required = $addon->get_option( 'required', $x, 'no', false ) === 'yes';

	if ( $_product instanceof WC_Product_Variation ) {
		$variation = new WC_Product_Variation( $product_id );
		if ( $show_variation_att ) {
			$var_attributes = implode( ' / ', $variation->get_variation_attributes() );
			$_product_name  = $_product_name . ' - ' . urldecode( $var_attributes );
		}
		$parent_id = $variation->get_parent_id();
	}

	$_product_price           = wc_get_price_to_display( $_product );
	$show_empty_product_image = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_empty_product_image', true );
	$_product_image           = $_product->get_image( 'woocommerce_thumbnail', array(), $show_empty_product_image );

	$option_price      = ! empty( $price_sale ) && 'undefined' !== $price_sale ? $price_sale : $price;
	$option_price_html = '';
	if ( 'product' === $price_method ) {
		$price_sale        = '';
		$option_price      = $_product_price;
		$option_price_html = ! $hide_product_prices ? '<small class="qpeofw-option-price">' . wc_price( $option_price ) . '</small>' : '';

	} elseif ( 'discount' === $price_method ) {
		$option_price          = $_product_price;
		$option_discount_value = $addon->get_price( $x );
		$price_sale            = $option_price - $option_discount_value;
		if ( 'percentage' === $price_type ) {
			$price_sale = $option_price - ( ( $option_price / 100 ) * $option_discount_value );
		}

		$option_price_html = ! $hide_product_prices ?
			'<small class="qpeofw-option-price"><del>' . wc_price( $option_price ) . '</del> ' . wc_price( $price_sale ) . '</small>' : '';
	} else {
		$option_price_html = $addon->get_option_price_html( $x, $currency, $product );
	}

	$holder_classes = array();

	$holder_classes[] = 'qpeofw-option';
	$holder_classes[] = ! empty( $selection_type ) ? 'qpeofw-selection--' . $selection_type : '';
	$holder_classes[] = $selected ? 'qpeofw-selected' : '';
	$holder_classes[] = ! $instock ? 'qpeofw-out-of-stock' : '';
	?>

	<div id="qpeofw-option-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" <?php qode_product_extra_options_for_woocommerce_class_attribute( $holder_classes ); ?>
		data-replace-image="<?php echo esc_attr( $image_replacement ); ?>"
		data-product-id="<?php echo esc_attr( $_product->get_id() ); ?>"
	>

		<?php
		if ( 'left' === $addon_options_images_position ) {
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

		<input type="checkbox"
				id="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>"
				class="qpeofw-standard-checkbox qpeofw-option-value"
				name="qpeofw[][<?php echo esc_attr( $addon->id . '-' . $x ); ?>]"
				value="<?php echo 'product-' . esc_attr( $_product->get_id() ) . '-1'; ?>"
				data-default-price="<?php echo esc_attr( $default_price ); ?>"
				<?php
				if ( $default_price > 0 ) {
					?>
					data-default-sale-price="<?php echo esc_attr( $default_sale_price ); ?>"
					<?php
				}
				?>
				data-price="<?php echo esc_attr( $option_price ); ?>"
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
				echo esc_html( 'required' );
			endif;
			?>
			<?php
			if ( ! $instock ) :
				echo esc_html( 'disabled="disabled"' );
			endif;
			?>
			<?php echo esc_attr( $checked ); ?>
				style="display: none;">

		<?php
		$checkbox_style         = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_checkbox_style' );
		$product_holder_class   = array();
		$product_holder_class[] = 'qpeofw-product-container';
		$product_holder_class[] = ! $instock ? ' disabled' : '';
		$product_holder_class[] = $checkbox_style ? 'qpeofw-checkbox-style--' . $checkbox_style : '';
		?>
		<div for="qpeofw-<?php echo esc_attr( $addon->id ); ?>-<?php echo esc_attr( $x ); ?>" <?php qode_product_extra_options_for_woocommerce_class_attribute( $product_holder_class ); ?>>
			<div class="qpeofw-product-image">
				<?php echo wp_kses_post( $_product_image ); ?>
			</div>
			<div class="qpeofw-product-info">
				<!-- PRODUCT NAME -->
				<span class="qpeofw-product-name"><?php echo wp_kses_post( $_product_name ); ?></span>
				<?php
				if ( 'yes' === $show_sku && $_product->get_sku() !== '' ) {
					echo '<div><small>' . esc_html__( 'SKU:', 'qode-product-extra-options-for-woocommerce' ) . ' ' . esc_html( $_product->get_sku() ) . '</small></div>'; }
				?>
				<?php
				do_action( 'qode_product_extra_options_for_woocommerce_action_after_addon_product_name', $_product, $product_id, $addon );
				?>

				<!-- PRICE -->
				<?php
				if ( ! $hide_option_prices ) {
					echo wp_kses_post( $option_price_html );
				}
				?>

				<?php
				do_action( 'qode_product_extra_options_for_woocommerce_action_after_addon_product_price', $_product, $product_id, $addon );
				?>

				<?php
				if ( $show_product_description && '' !== $product_description ) {
					echo '<p class="qpeofw-addon-description">' . stripslashes( $product_description ) . '</p>'; // phpcs:ignore
				}
				?>
				<!-- STOCK -->
				<?php
				$stock_class  = '';
				$stock_style  = '';
				$stock_status = '';
				if ( $instock ) {
					$stock_class = 'in-stock';
					$stock_style = 'margin-bottom: 10px';
					if ( $_product->get_manage_stock() ) {
						$stock_status = $_product->get_stock_quantity() . ' ' . esc_html__( 'in stock', 'qode-product-extra-options-for-woocommerce' );
					} else {
						$stock_status = esc_html__( 'In stock', 'qode-product-extra-options-for-woocommerce' );
					}
				} else {
					$stock_class  = 'qpeofw-out-of-stock';
					$stock_status = esc_html__( 'Out of stock', 'qode-product-extra-options-for-woocommerce' );
				}
				$stock_qty = $_product->get_manage_stock() ? $_product->get_stock_quantity() : false;
				if ( 'yes' === $show_stock ) {
					echo '<div style="' . esc_attr( $stock_style ) . '"><small class="stock ' . esc_attr( $stock_class ) . '">' . esc_html( $stock_status ) . '</small></div>';
				}
				?>

				<?php if ( $_product->get_stock_status() === 'instock' ) : ?>

					<div class="qpeofw-option-add-to-cart">
						<?php

						$input_name = 'qpeofw_product_qty[' . esc_attr( $addon->id . '-' . $x ) . ']';

						if ( 'yes' === $show_quantity ) {

							$default_qty = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_default_product_qty', 1, $_product );

							$input_class_quantity = array( 'input-text', 'qty', 'text', 'qpeofw-product-qty' );
							$max_value            = $_product->get_stock_quantity();

							woocommerce_quantity_input(
								array(
									'input_id'    => $input_name,
									/**
									 *
									 * Filter the array with the CSS clases for the quantity input in add-on type Products.
									 *
									 * @param array      $input_class_quantity CSS classes
									 * @param WC_Product $_product             WooCommerce product
									 *
									 * @return array
									 */
									'classes'     => apply_filters( 'qode_product_extra_options_for_woocommerce_filter_product_input_class_quantity', $input_class_quantity, $_product ),
									'input_name'  => $input_name,
									'min_value'   => apply_filters( 'qode_product_extra_options_for_woocommerce_filter_product_quantity_input_min', 1, $_product ),
									'max_value'   => apply_filters( 'qode_product_extra_options_for_woocommerce_filter_product_quantity_input_max', $max_value, $_product ),
									'input_value' => $default_qty,
								)
							);
						}
						?>
						<?php if ( 'yes' === $show_add_to_cart ) : ?>
							<a href="?add-to-cart=<?php echo esc_attr( $_product->get_id() ); ?>&quantity=1" class="button add_to_cart_button">
								<?php echo esc_html__( 'Add to cart', 'qode-product-extra-options-for-woocommerce' ); ?>
							</a>
						<?php endif; ?>

						<?php
						// TODO: premium version check this is for remove probably since it is from request quote plugin.
						if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_addon_product_add_to_quote', false ) ) :
							if ( function_exists( 'qode_render_button' ) ) {
								qode_render_button( $product_id );
							}
						endif;
						?>
					</div>

				<?php endif; ?>
			</div>

			<?php
			if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_addon_product_link', false ) ) {
				$link_target = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_addon_product_link_target', '' );
				echo '<a class="button view-product" target="' . esc_attr( $link_target ) . '" href="' . esc_url( get_permalink( $product_id ) ) . '">' . esc_html__( 'View product', 'qode-product-extra-options-for-woocommerce' ) . '</a>';
			}
			?>
		</div>

		<?php
		if ( 'right' === $addon_options_images_position ) {
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

		<?php if ( $addon->get_option( 'tooltip', $x ) !== '' ) : ?>
			<span class="qpeofw-tooltip">
				<span><?php echo esc_attr( $addon->get_option( 'tooltip', $x ) ); ?></span>
			</span>
		<?php endif; ?>

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
	<?php
}
