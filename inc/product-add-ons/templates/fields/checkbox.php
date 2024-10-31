<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the checkbox field
 *
 * @var array $field The field.
 */

list ( $field_id, $name, $class, $std, $value, $data, $custom_attributes, $desc_inline ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'name', 'class', 'std', 'value', 'data', 'custom_attributes', 'desc-inline' );

$class_print = '';

if ( ! empty( $class ) ) {
	$class_print = $class;
}
?>
<input type="checkbox" id="<?php echo esc_attr( $field_id ); ?>"
		name="<?php echo esc_attr( $name ); ?>" value="1"
		class="<?php echo esc_attr( $class_print ); ?>"
	<?php if ( isset( $std ) ) : ?>
		data-std="<?php echo esc_attr( $std ); ?>"
	<?php endif; ?>
	<?php checked( true, qode_product_extra_options_for_woocommerce_is_addon_is_true( $value ) ); ?>
	<?php qode_product_extra_options_for_woocommerce_html_attributes_to_string( $custom_attributes, true ); ?>
	<?php qode_product_extra_options_for_woocommerce_html_data_to_string( $data, true ); ?>
/>
<?php if ( isset( $desc_inline ) ) : ?>
	<span class='description inline'><?php echo wp_kses_post( $desc_inline ); ?></span>
<?php endif; ?>
