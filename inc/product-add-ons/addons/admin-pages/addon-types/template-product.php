<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Product Template
 *
 * @var object $addon
 * @var string $addon_type
 * @var int    $x
 */

$product_name = '';
$product_id   = $addon->get_option( 'product', $x, '', false ) ? $addon->get_option( 'product', $x, '', false ) : '';

?>

<div class="qodef-fields">
	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Choose product', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Choose one of the products', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content qodef-addon-product-selection">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'      => 'option-product-' . $x,
						'name'    => 'options[product][]',
						'type'    => 'select',
						'options' => qode_product_extra_options_for_woocommerce_get_cpt_items( 'product', array( 'numberposts' => '-1' ) ),
						'value'   => $product_id,
					),
					true
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
