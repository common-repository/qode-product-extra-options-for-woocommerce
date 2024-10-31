<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the textarea-editor field
 *
 * @var array $field The field.
 */

list ( $field_id, $class, $name, $value, $custom_attributes, $data ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'class', 'name', 'value', 'custom_attributes', 'data' );

// Handle deprecated param 'classes' (since 3.5): use 'class' instead.
if ( isset( $field['classes'] ) && ! isset( $class ) ) {
	$class = $field['classes'];
}

if ( ! function_exists( 'wp_editor' ) ) {
	$field['type'] = 'textarea';
	qode_product_extra_options_for_woocommerce_get_field( $field, true, false );

	return;
}

$class = isset( $class ) ? $class : '';

$editor_args = wp_parse_args(
	$field,
	array(
		// Choose if you want to use wpautop.
		'wpautop'       => true,
		// Choose if showing media button(s).
		'media_buttons' => true,
		// Set the textarea name to something different, square brackets [] can be used here.
		'textarea_name' => $name,
		// Set the number of rows.
		'textarea_rows' => 20,
		'tabindex'      => '',
		// Add extra class(es) to the editor textarea.
		'editor_class'  => 'qode-product-extra-options-for-woocommerce-textarea-editor',
		// Output the minimal editor config used in Press This.
		'teeny'         => false,
		// Replace the default fullscreen with DFW (needs specific DOM elements and css).
		'dfw'           => false,
		// Load TinyMCE, can be used to pass settings directly to TinyMCE using an array().
		'tinymce'       => true,
		// Load Quicktags, can be used to pass settings directly to Quicktags using an array().
		'quicktags'     => true,
	)
);
?>
<div class="editor <?php echo esc_attr( $class ); ?>"
	<?php qode_product_extra_options_for_woocommerce_html_attributes_to_string( $custom_attributes, true ); ?>
	<?php qode_product_extra_options_for_woocommerce_html_data_to_string( $data, true ); ?>
><?php wp_editor( stripslashes( html_entity_decode( $value ) ), strtolower( $field_id ), $editor_args ); ?></div>
