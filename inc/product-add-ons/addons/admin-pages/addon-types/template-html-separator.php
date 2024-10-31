<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * HTML Separator Template
 *
 * @var object $addon
 * @var string $addon_type
 */
?>

<div class="qodef-fields">

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Separator Style', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Choose the separator style', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'      => 'option-separator-style',
						'name'    => 'option_separator_style',
						'type'    => 'select',
						'value'   => $addon->get_setting( 'separator_style' ),
						'options' => array(
							'solid_border'  => esc_html__( 'Solid Border', 'qode-product-extra-options-for-woocommerce' ),
							'double_border' => esc_html__( 'Double Border', 'qode-product-extra-options-for-woocommerce' ),
							'dotted_border' => esc_html__( 'Dotted Border', 'qode-product-extra-options-for-woocommerce' ),
							'dashed_border' => esc_html__( 'Dashed Border', 'qode-product-extra-options-for-woocommerce' ),
							'empty_space'   => esc_html__( 'Empty Space', 'qode-product-extra-options-for-woocommerce' ),
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
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Separator Class', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( ' A comma-separated list of CSS classes for the separator holder', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<input type="text" name="option_separator_class" id="option-separator-class" class="qodef-field qodef-input" value="<?php echo esc_attr( $addon->get_setting( 'heading_class' ) ); ?>">
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Width', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Set the width value for the separator (value is in % units)', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-separator-width',
						'name'  => 'option_separator_width',
						'type'  => 'number',
						'min'   => 1,
						'max'   => 100,
						'step'  => 1,
						'value' => $addon->get_setting( 'separator_width', 100 ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Height', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Set the height value for the separator (value is in px units)', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-separator-size',
						'name'  => 'option_separator_size',
						'type'  => 'number',
						'min'   => 0,
						'value' => $addon->get_setting( 'separator_size', 2 ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-show-dependency-holder" data-hide="{<?php echo esc_attr( '"option_separator_style":"empty_space"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'Border Color', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Set the color for the separator border', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-separator-color',
						'name'  => 'option_separator_color',
						'type'  => 'colorpicker',
						'value' => $addon->get_setting( 'separator_color', '#ee2852' ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

</div>
