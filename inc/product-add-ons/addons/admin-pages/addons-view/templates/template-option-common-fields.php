<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Option Template
 *
 * @var int $x
 * @var string $addon_type
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
 */

$price_type         = $addon->get_option( 'price_type', $x, 'fixed', false );
$option_color_type  = $addon->get_option( 'color_type', $x, 'color', false );
$gradient_rendering = $addon->get_option( 'gradient_rendering', $x, '', false );
$color_b            = $addon->get_option( 'color_b', $x, '', false );
?>

<?php if ( 'product' !== $addon_type ) : ?>

	<div class="qodef-option-common-fields">

		<?php
		// Start COLOR addon type.
		if ( 'color' === $addon_type ) :
			?>
			<!-- Option field -->
			<div class="qodef-field-holder col-md-12 col-lg-12">
				<div class="qodef-field-section">
					<div class="qodef-field-desc">
						<h3 class="qodef-title qodef-field-title">
							<?php
							echo esc_html__( 'Show as', 'qode-product-extra-options-for-woocommerce' );
							?>
						</h3>
						<p class="qodef-description qodef-field-description">
							<?php
							echo esc_html__( 'Choose swatch type', 'qode-product-extra-options-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="qodef-field-content">
						<?php
						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'      => 'option-color-type-' . $x,
								'name'    => 'options[color_type][' . $x . ']',
								'type'    => 'select',
								'value'   => $option_color_type,
								'options' => array(
									'color' => esc_html__( 'Color swatch', 'qode-product-extra-options-for-woocommerce' ),
									'image' => esc_html__( 'Image swatch', 'qode-product-extra-options-for-woocommerce' ),
								),
							),
							true
						);
						?>
					</div>
				</div>
			</div>
			<!-- End option field -->

			<!-- Option field -->
			<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[color_type][' . $x . ']":"color"' ); ?>}">
				<div class="qodef-field-section">
					<div class="qodef-field-desc">
						<h3 class="qodef-title qodef-field-title">
						<?php
							echo esc_html__( 'Color', 'qode-product-extra-options-for-woocommerce' );
						?>
						</h3>
						<p class="qodef-description qodef-field-description">
						<?php
							echo esc_html__( 'For gradient color set both color values, and for solid color set just first color', 'qode-product-extra-options-for-woocommerce' );
						?>
						</p>
					</div>
					<div class="qodef-field-content">
						<div class="qodef-field-wrapper">
							<div class="qodef-additional-options">
								<div class="qodef-color-a">
									<?php
									qode_product_extra_options_for_woocommerce_get_field(
										array(
											'id'    => 'option-color-' . $x,
											'name'  => 'options[color][' . $x . ']',
											'type'  => 'colorpicker',
											'value' => $addon->get_option( 'color', $x, '#ee2852', false ),
										),
										true
									);
									?>
								</div>
								<div class="qodef-color-b">
									<?php
									qode_product_extra_options_for_woocommerce_get_field(
										array(
											'id'    => 'option-color-b-' . $x,
											'name'  => 'options[color_b][' . $x . ']',
											'type'  => 'colorpicker',
											'value' => $color_b,
										),
										true
									);
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End option field -->

			<!-- Option field -->
			<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[color_type][' . $x . ']":"color"' ); ?>}">
				<div class="qodef-field-section">
					<div class="qodef-field-desc">
						<h3 class="qodef-title qodef-field-title">
							<?php
							echo esc_html__( 'Gradient rendering', 'qode-product-extra-options-for-woocommerce' );
							?>
						</h3>
						<p class="qodef-description qodef-field-description">
							<?php
							echo esc_html__( 'For gradient rendering option to work set both color values', 'qode-product-extra-options-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="qodef-field-content">
						<?php
						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'      => 'option-gradient-rendering-' . $x,
								'name'    => 'options[gradient_rendering][' . $x . ']',
								'type'    => 'select',
								'value'   => $gradient_rendering,
								'options' => array(
									''       => esc_html__( 'Default', 'qode-product-extra-options-for-woocommerce' ),
									'smooth' => esc_html__( 'Smooth', 'qode-product-extra-options-for-woocommerce' ),
								),
							),
							true
						);
						?>
					</div>
				</div>
			</div>
			<!-- End option field -->

			<!-- Option field -->
			<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[color_type][' . $x . ']":"image"' ); ?>}">
				<div class="qodef-field-section">
					<div class="qodef-field-desc">
						<h3 class="qodef-title qodef-field-title">
						<?php
							echo esc_html__( 'Image', 'qode-product-extra-options-for-woocommerce' );
						?>
						</h3>
						<p class="qodef-description qodef-field-description">
							<?php
							echo esc_html__( 'Upload an image for the image swatch', 'qode-product-extra-options-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="qodef-field-content">
						<?php
						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'    => 'option-color-image-' . $x,
								'name'  => 'options[color_image][]',
								'type'  => 'media',
								'value' => $addon->get_option( 'color_image', $x, '', false ),
							),
							true
						);
						?>
					</div>
				</div>
			</div>
			<!-- End option field -->
			<?php
		endif;
		// End COLOR addon type.
		?>

		<!-- Option field -->
		<div class="qodef-field-holder col-md-12 col-lg-12">
			<div class="qodef-field-section">
				<div class="qodef-field-desc">
					<h3 class="qodef-title qodef-field-title">
						<?php
						echo esc_html__( 'Label', 'qode-product-extra-options-for-woocommerce' );
						?>
					</h3>
					<p class="qodef-description qodef-field-description">
						<?php
						echo esc_html__( 'Enter label text', 'qode-product-extra-options-for-woocommerce' );
						?>
					</p>
				</div>
				<div class="qodef-field-content">
					<div class="qodef-field-wrapper">
						<div class="qodef-additional-options">
							<input type="text" name="options[label][]" id="qode_product_extra_options_for_woocommerce_option-label-<?php echo esc_attr( $x ); ?>" value="<?php echo esc_html( $addon->get_option( 'label', $x, '', false ) ); ?>" class="qodef-field qodef-input">
							<?php
							// Hook to include additional options after addon label input option.
							do_action( 'qode_product_extra_options_for_woocommerce_admin_action_after_addon_label_input', $addon, $x );
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End option field -->

		<?php
		// Hook to include additional options after addon label option.
			do_action( 'qode_product_extra_options_for_woocommerce_admin_action_after_addon_label', $addon, $x );
		?>

		<?php
		if ( 'text' === $addon_type || 'textarea' === $addon_type ) :
			// Start TEXT/TEXTAREA addon type.
			?>
			<!-- Option field -->
			<div class="qodef-field-holder col-md-12 col-lg-12">
				<div class="qodef-field-section">
					<div class="qodef-field-desc">
						<h3 class="qodef-title qodef-field-title">
						<?php
							echo esc_html__( 'Placeholder', 'qode-product-extra-options-for-woocommerce' );
						?>
						</h3>
						<p class="qodef-description qodef-field-description">
							<?php
							echo esc_html__( 'Enter placeholder text', 'qode-product-extra-options-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="qodef-field-content">
						<input type="text" name="options[placeholder][]" id="option-placeholder-<?php echo esc_attr( $x ); ?>" class="qodef-field qodef-input" value="<?php echo esc_html( $addon->get_option( 'placeholder', $x, '', false ) ); ?>">
					</div>
				</div>
			</div>
			<!-- End option field -->
			<?php
			// End TEXT/TEXTAREA addon type.
		endif;
		?>

		<?php if ( 'select' !== $addon_type ) : ?>
			<!-- Option field -->
			<div class="qodef-field-holder col-md-12 col-lg-12">
				<div class="qodef-field-section">
					<div class="qodef-field-desc">
						<h3 class="qodef-title qodef-field-title">
							<?php
							echo esc_html__( 'Tooltip', 'qode-product-extra-options-for-woocommerce' );
							?>
						</h3>
						<p class="qodef-description qodef-field-description">
							<?php
							echo esc_html__( 'Enter tooltip text', 'qode-product-extra-options-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="qodef-field-content">
						<input type="text" name="options[tooltip][]" id="option-tooltip-<?php echo esc_attr( $x ); ?>" class="qodef-field qodef-input" value="<?php echo esc_html( $addon->get_option( 'tooltip', $x, '', false ) ); ?>">
					</div>
				</div>
			</div>
			<!-- End option field -->
		<?php endif; ?>

		<!-- Option field -->
		<div class="qodef-field-holder col-md-12 col-lg-12">
			<div class="qodef-field-section">
				<div class="qodef-field-desc">
					<h3 class="qodef-title qodef-field-title">
						<?php
						echo esc_html__( 'Description', 'qode-product-extra-options-for-woocommerce' );
						?>
					</h3>
					<p class="qodef-description qodef-field-description">
						<?php
						echo esc_html__( 'Enter description text', 'qode-product-extra-options-for-woocommerce' );
						?>
					</p>
				</div>
				<div class="qodef-field-content">
					<input type="text" name="options[description][]" id="option-description-<?php echo esc_attr( $x ); ?>" class="qodef-field qodef-input" value="<?php echo esc_html( $addon->get_option( 'description', $x, '', false ) ); ?>">
				</div>
			</div>
		</div>
		<!-- End option field -->

	</div>

	<?php
		// Hook to include additional options before add image option.
		do_action( 'qode_product_extra_options_for_woocommerce_product_addons_admin_action_options_before_add_image', $addon, $x, $addon_type );
	?>

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12" id="qode_product_extra_options_for_woocommerce_show_image">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Add Image', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					printf(
							// translators: part of text is wrapped with strong HTML tags.
						esc_html__( 'Enable to upload an image for the Option. You can use this image to replace the default product image', 'qode-product-extra-options-for-woocommerce' ),
						'<strong>',
						'</strong>'
					);
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'       => 'qode-product-extra-options-for-woocommerce-option-show-image-' . $x,
						'name'     => 'options[show_image][' . $x . ']',
						'type'     => 'yesno-radio',
						'options'  => qode_product_extra_options_for_woocommerce_get_select_type_options_pool( 'no_yes', false ),
						'value'    => $addon->get_option( 'show_image', $x, 'no', false ),
						'store_as' => 'id',
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" id="qode_product_extra_options_for_woocommerce_show_image_<?php echo esc_attr( $x ); ?>" data-show="{<?php echo esc_attr( '"options[show_image][' . $x . ']":"yes"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Image', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Upload an image for the option', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'       => 'option-image-' . $x,
						'name'     => 'options[image][]',
						'type'     => 'media',
						'value'    => $addon->get_option( 'image', $x, '', false ),
						'store_as' => 'id',
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

<?php else : ?>

	<!-- Option field -->
	<?php
	$product_label       = $addon->get_option( 'label', $x, '', false );
	$product_addon_label = $product_label ?? '';
	?>
	<input type="hidden" name="options[label][]" class="qode_product_extra_options_for_woocommerce_option-label qodef-field qodef-input" value="<?php echo esc_html( $product_addon_label ); ?>">
	<!-- End option field -->

<?php endif; ?>

<!-- Option field -->
<div class="qodef-field-holder col-md-12 col-lg-12">
	<div class="qodef-field-section">
		<div class="qodef-field-desc">
			<h3 class="qodef-title qodef-field-title">
				<?php
				echo esc_html__( 'Price', 'qode-product-extra-options-for-woocommerce' );
				?>
			</h3>
			<p class="qodef-description qodef-field-description">
				<?php
				echo esc_html__( 'Choose price behavior', 'qode-product-extra-options-for-woocommerce' );
				?>
			</p>
		</div>
		<div class="qodef-field-content">
			<?php
			$option_price_method = $addon->get_option( 'price_method', $x, 'free', false );
			$price_methods       = array(
				'free'     => esc_html__( 'Product price doesn\'t change - free option', 'qode-product-extra-options-for-woocommerce' ),
				'increase' => esc_html__( 'Product price increase - set option price', 'qode-product-extra-options-for-woocommerce' ),
				'decrease' => esc_html__( 'Product price decrease - set discount price', 'qode-product-extra-options-for-woocommerce' ),
			);
			if ( 'number' === $addon_type ) {
				$price_methods = array(
					'free'            => esc_html__( 'Product price doesn\'t change - free option', 'qode-product-extra-options-for-woocommerce' ),
					'increase'        => esc_html__( 'Product price increase - set option price', 'qode-product-extra-options-for-woocommerce' ),
					'decrease'        => esc_html__( 'Product price decrease - set discount price', 'qode-product-extra-options-for-woocommerce' ),
					'value_x_product' => esc_html__( 'Value multiplied by product price', 'qode-product-extra-options-for-woocommerce' ),
				);
			}
			if ( 'product' === $addon_type ) {
				$option_price_method = $addon->get_option( 'price_method', $x, 'product', false );
				$price_methods       = array(
					'free'     => esc_html__( 'Product price doesn\'t change - free option', 'qode-product-extra-options-for-woocommerce' ),
					'increase' => esc_html__( 'Product price increase - set option price', 'qode-product-extra-options-for-woocommerce' ),
					'decrease' => esc_html__( 'Product price decrease - set discount price', 'qode-product-extra-options-for-woocommerce' ),
					'product'  => esc_html__( 'Use price of linked product', 'qode-product-extra-options-for-woocommerce' ),
					'discount' => esc_html__( 'Discount price of linked product', 'qode-product-extra-options-for-woocommerce' ),
				);
			}

			$price_methods = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_price_methods', $price_methods, $addon );
			qode_product_extra_options_for_woocommerce_get_field(
				array(
					'id'      => 'option-price-method-' . $x,
					'name'    => 'options[price_method][' . $x . ']',
					'type'    => 'select',
					'value'   => $option_price_method,
					'options' => $price_methods,
				),
				true
			);
			?>
		</div>
	</div>
</div>
<!-- End option field -->

<!-- Option field -->
<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" id="qode_product_extra_options_for_woocommerce_option_cost_<?php echo esc_attr( $x ); ?>" data-hide="{<?php echo esc_attr( '"options[price_method][' . $x . ']":"free,product"' ); ?>}">
	<div class="qodef-field-section">
		<div class="qodef-field-desc">
			<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Option cost', 'qode-product-extra-options-for-woocommerce' );
				?>
			</h3>
			<p class="qodef-description qodef-field-description">
				<?php
				echo esc_html__( 'Set option cost depending on price behavior option (REGULAR or DISCOUNT price)', 'qode-product-extra-options-for-woocommerce' );
				?>
			</p>
		</div>
		<div class="qodef-field-content">
			<div class="qodef-field-wrapper">
				<div class="qodef-additional-options">
					<div class="qodef-increase-or-decrease">
						<div class="qodef-field-wrapper qodef-filed-type--text">
							<small class="option-price-method">
							<?php
								echo esc_html__( 'regular or discount price', 'qode-product-extra-options-for-woocommerce' );
							?>
							</small>
							<input type="text" name="options[price][]" id="option-price" value="<?php echo esc_html( $addon->get_option( 'price', $x, '', false ) ); ?>" class="qodef-field qodef-input">
						</div>

						<div class="qodef-field-wrapper qodef-filed-type--text qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[price_method][' . $x . ']":"increase"' ); ?>}">
							<small>
							<?php
								echo esc_html__( 'sale price', 'qode-product-extra-options-for-woocommerce' );
							?>
							</small>
							<input type="text" name="options[price_sale][]" id="option-price-sale" value="<?php echo esc_html( $addon->get_option( 'price_sale', $x ) ); ?>" class="qodef-field qodef-input">
						</div>


						<?php
						$price_options = array(
							// general add-on option.
							'fixed'      => esc_html__( 'Fixed amount', 'qode-product-extra-options-for-woocommerce' ),
							'percentage' => esc_html__( 'Percentage', 'qode-product-extra-options-for-woocommerce' ),
						);
						if ( 'number' === $addon_type ) {
							// general add-on option.
							$price_options['multiplied'] = esc_html__( 'Price multiplied by value', 'qode-product-extra-options-for-woocommerce' );
						}
						if ( 'text' === $addon_type || 'textarea' === $addon_type ) {
							// general add-on option.
							$price_options['characters'] = esc_html__( 'Price multiplied by string length', 'qode-product-extra-options-for-woocommerce' );
						}

						$price_options = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_price_options', $price_options, $addon );

						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'      => 'option-price-type-' . $x,
								'name'    => 'options[price_type][]',
								'type'    => 'select',
								'value'   => $addon->get_option( 'price_type', $x, 'fixed', false ),
								'options' => $price_options,
							),
							true
						);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End option field -->

<?php if ( 'select' !== $addon_type && 'date' !== $addon_type && 'radio' !== $addon_type ) : ?>

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Required', 'qode-product-extra-options-for-woocommerce' )
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Enable to make this option mandatory for users', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'      => 'option-required-' . $x,
						'name'    => 'options[required][' . $x . ']',
						'type'    => 'yesno-radio',
						'options' => qode_product_extra_options_for_woocommerce_get_select_type_options_pool( 'no_yes', false ),
						'value'   => $addon->get_option( 'required', $x, 'no', false ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

<?php endif; ?>

<?php
if ( 'text' === $addon_type || 'textarea' === $addon_type ) :
	// Start TEXT/TEXTAREA addon type - bottom part.
	?>
	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Limit Input', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Limit input characters to certain value', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'      => 'option-characters-limit',
						'name'    => 'options[characters_limit][' . $x . ']',
						'type'    => 'select',
						'options' => qode_product_extra_options_for_woocommerce_get_select_type_options_pool( 'no_yes', false ),
						'value'   => $addon->get_option( 'characters_limit', $x, 'no', false ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[characters_limit][' . $x . ']":"yes"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Number of Characters', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Set min and max value of characters for the input', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<div class="qodef-field-wrapper">
					<div class="qodef-additional-options">
						<div class="qodef-increase-or-decrease">
							<div class="qodef-field-wrapper qodef-filed-type--text">
								<small><?php echo esc_html__( 'MIN', 'qode-product-extra-options-for-woocommerce' ); ?></small>
								<input type="text" name="options[characters_limit_min][]" id="option-characters-limit-min" value="<?php echo esc_attr( $addon->get_option( 'characters_limit_min', $x, '', false ) ); ?>" class="qodef-field qodef-input">
							</div>
							<div class="qodef-field-wrapper qodef-filed-type--text">
								<small><?php echo esc_html__( 'MAX', 'qode-product-extra-options-for-woocommerce' ); ?></small>
								<input type="text" name="options[characters_limit_max][]" id="option-characters-limit-max" value="<?php echo esc_attr( $addon->get_option( 'characters_limit_max', $x, '', false ) ); ?>" class="qodef-field qodef-input">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End option field -->
	<?php
	// End TEXT/TEXTAREA addon type - bottom part.
endif;
?>

<?php if ( 'file' === $addon_type ) : ?>

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Multiple upload', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Enable to allow upload multiple files in the uploader', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-multiupload-' . $x,
						'name'  => 'options[multiupload][' . $x . ']',
						'type'  => 'yesno-radio',
						'value' => $addon->get_option( 'multiupload', $x, 'no', false ),
					),
					true,
					false
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[multiupload][' . $x . ']":"yes"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Users can upload a max of', 'qode-product-extra-options-for-woocommerce' )
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Set the max number of files a user can upload or leave empty if the user can upload files without any limits', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-multiupload-max-' . $x,
						'name'  => 'options[multiupload_max][' . $x . ']',
						'type'  => 'number',
						'min'   => 0,
						'value' => $addon->get_option( 'multiupload_max', $x, '', false ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->
<?php endif; ?>
