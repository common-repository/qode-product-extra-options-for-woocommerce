<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the datepicker field
 *
 * @var array $field The field.
 */

wp_enqueue_script( 'jquery-ui-datepicker' );

list ( $field_id, $class, $name, $value, $data, $date_format, $custom_attributes ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'class', 'name', 'value', 'data', 'date_format', 'custom_attributes' );

if ( ! isset( $date_format ) && empty( $date_format ) ) {
	$date_format = 'yy-mm-dd';
}

$class = ! empty( $class ) ? $class : 'qodef-field qodef-input qodef-datepicker';
?>
<input type="text"
		name="<?php echo esc_attr( $name ); ?>"
		data-date-format="<?php echo esc_attr( $date_format ); ?>"
		id="<?php echo esc_attr( $field_id ); ?>"
		value="<?php echo esc_attr( $value ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		autocomplete="off" readonly
	<?php qode_product_extra_options_for_woocommerce_html_attributes_to_string( $custom_attributes, true ); ?>
	<?php qode_product_extra_options_for_woocommerce_html_data_to_string( $data, true ); ?>
/>
