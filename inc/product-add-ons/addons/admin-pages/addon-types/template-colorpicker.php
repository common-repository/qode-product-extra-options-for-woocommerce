<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Colorpicker Template
 *
 * @var object $addon
 * @var string $addon_type
 * @var int    $x
 */

$colorpicker_show = $addon->get_option( 'colorpicker_show', $x, 'default_color', false );

?>

<div class="qodef-fields">

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
				<?php
					echo esc_html__( 'In picker show', 'qode-product-extra-options-for-woocommerce' );
				?>
				</h3>
				<p class="qodef-description qodef-field-description">
				<?php
					echo esc_html__( 'Display default color or placeholder text', 'qode-product-extra-options-for-woocommerce' );
				?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'      => 'option-colorpicker-show-' . $x,
						'name'    => 'options[colorpicker_show][' . $x . ']',
						'type'    => 'select',
						'value'   => $colorpicker_show,
						'options' => array(
							'default_color' => esc_html__( 'A default color', 'qode-product-extra-options-for-woocommerce' ),
							'placeholder'   => esc_html__( 'A placeholder text', 'qode-product-extra-options-for-woocommerce' ),
						),
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
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[colorpicker_show][' . $x . ']":"default_color"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Default color', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Choose default color', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'            => 'option-colorpicker-' . $x,
						'name'          => 'options[colorpicker][]',
						'type'          => 'colorpicker',
						'alpha_enabled' => false,
						'default'       => '#',
						'value'         => $addon->get_option( 'colorpicker', $x, '#ffffff', false ),
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
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[colorpicker_show][' . $x . ']":"placeholder"' ); ?>}">
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
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-tooltip-' . $x,
						'name'  => 'options[placeholder][]',
						'type'  => 'text',
						'value' => $addon->get_option( 'placeholder', $x, '', false ),
					),
					true,
					false
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<?php
	qode_product_extra_options_for_woocommerce_template_part(
		'product-add-ons',
		'addons/admin-pages/addons-view/templates/template',
		'option-common-fields',
		array(
			'x'          => $x,
			'addon_type' => $addon_type,
			'addon'      => $addon,
		)
	);
	?>

</div>
