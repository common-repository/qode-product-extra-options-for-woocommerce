<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the title field
 *
 * @var array $field The field.
 */

list ( $field_id, $class, $name, $desc, $std, $custom_attributes, $data ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'class', 'name', 'desc', 'std', 'custom_attributes', 'data' );

$class = isset( $class ) ? $class : 'title';
?>
<h3 id="<?php echo esc_attr( $field_id ); ?>"
		class="<?php echo esc_attr( $class ); ?>"

	<?php qode_product_extra_options_for_woocommerce_html_attributes_to_string( $custom_attributes, true ); ?>
	<?php qode_product_extra_options_for_woocommerce_html_data_to_string( $data, true ); ?>
>
	<?php echo wp_kses_post( $desc ); ?>
</h3>
