<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the number field
 *
 * @var array $field The field.
 */

list ( $field_id, $class, $name, $std, $value, $min, $max, $step, $custom_attributes, $data ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'class', 'name', 'std', 'value', 'min', 'max', 'step', 'custom_attributes', 'data' );

$class = isset( $class ) ? $class : 'qodef-field qodef-input';

?>
<input type="number" id="<?php echo esc_attr( $field_id ); ?>"
		name="<?php echo esc_attr( $name ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
	<?php if ( isset( $min ) ) : ?>
		min="<?php echo esc_attr( $min ); ?>"
	<?php endif; ?>
	<?php if ( isset( $max ) ) : ?>
		max="<?php echo esc_attr( $max ); ?>"
	<?php endif; ?>
	<?php if ( isset( $step ) ) : ?>
		step="<?php echo esc_attr( $step ); ?>"
	<?php endif; ?>
	<?php if ( isset( $std ) ) : ?>
		data-std="<?php echo esc_attr( $std ); ?>"
	<?php endif; ?>
	<?php qode_product_extra_options_for_woocommerce_html_attributes_to_string( $custom_attributes, true ); ?>
	<?php qode_product_extra_options_for_woocommerce_html_data_to_string( $data, true ); ?>
/>
