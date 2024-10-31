<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * HTML Text Template
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
					echo esc_html__( 'Text Content', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Input your textual content (allows all HTML that is permitted in post content)', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'option-text-content',
						'name'  => 'option_text_content',
						'type'  => 'textarea-editor',
						'value' => $addon->get_setting( 'text_content' ),
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
					echo esc_html__( 'Text Content Class', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( ' A comma-separated list of CSS classes for the content holder', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<input type="text" name="option_text_class" id="option-text-class" class="qodef-field qodef-input" value="<?php echo esc_attr( $addon->get_setting( 'text_class' ) ); ?>">
			</div>
		</div>
	</div>
	<!-- End option field -->

</div>
