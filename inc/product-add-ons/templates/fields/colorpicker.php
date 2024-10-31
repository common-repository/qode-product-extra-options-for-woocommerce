<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the colorpicker field
 *
 * @var array $field The field.
 */

wp_enqueue_style( 'wp-color-picker' );

wp_enqueue_script( 'wp-color-picker' );
wp_enqueue_script( 'wp-color-picker-alpha' );

list ( $field_id, $name, $class, $value ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'name', 'class', 'value' );

$class = ! empty( $class ) ? $class : 'qodef-field qodef-color-field';

?>
<input type="text" data-alpha-enabled="true" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $field_id ); ?>" value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $class ); ?>"/>
