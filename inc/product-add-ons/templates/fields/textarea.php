<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the textarea field
 *
 * @var array $field The field.
 */

list ( $field_id, $class, $name, $value, $readonly, $rows, $custom_attributes, $data ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'class', 'name', 'value', 'readonly', 'rows', 'custom_attributes', 'data' );

$class = isset( $class ) ? $class : 'form-control qodef-field';
$rows  = isset( $rows ) ? $rows : 5;
?>
	<textarea id="<?php echo esc_attr( $field_id ); ?>" class="<?php echo esc_attr( $class ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="<?php echo esc_attr( $rows ); ?>"
			<?php
			if ( isset( $readonly ) ) {
				echo ' readonly';
			}
			?>
	><?php echo esc_html( $value ); ?></textarea>
