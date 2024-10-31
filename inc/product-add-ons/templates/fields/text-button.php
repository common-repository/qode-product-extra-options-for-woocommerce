<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the text-button field
 *
 * @var array $field The field.
 */

list ( $field_id, $class, $name, $value, $std, $buttons, $custom_attributes, $data ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'class', 'name', 'value', 'std', 'buttons', 'custom_attributes', 'data' );

$backward_compatibility = false;
if ( ! isset( $buttons ) ) {
	$backward_compatibility = true;
	$button_data            = array();

	if ( isset( $field['button-class'] ) ) {
		$button_data['class'] = $field['button-class'];
	}
	if ( isset( $field['button-name'] ) ) {
		$button_data['name'] = $field['button-name'];
	}
	if ( isset( $field['data'] ) ) {
		$button_data['data'] = $field['data'];
	}

	$buttons = array( $button_data );
}
$class = isset( $class ) ? $class : 'qodef-field qodef-input';
?>
<input type="text"
		id="<?php echo esc_attr( $field_id ); ?>"
		name="<?php echo esc_attr( $name ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		value="<?php echo esc_attr( $value ); ?>"

	<?php if ( isset( $std ) ) : ?>
		data-std="<?php echo esc_attr( $std ); ?>"
	<?php endif; ?>

	<?php qode_product_extra_options_for_woocommerce_html_attributes_to_string( $custom_attributes, true ); ?>
	<?php
	if ( ! $backward_compatibility ) {
		qode_product_extra_options_for_woocommerce_html_data_to_string( $data, true );
	}
	?>
/>
<?php
if ( isset( $buttons ) ) {
	$button_field = array(
		'type'    => 'buttons',
		'buttons' => $buttons,
	);
	qode_product_extra_options_for_woocommerce_get_field( $button_field, true );
}
?>
