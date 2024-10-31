<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the onoff field
 *
 * @var array $field The field.
 */

list ( $field_id, $class, $name, $std, $value, $custom_attributes, $data, $desc_inline ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'class', 'name', 'std', 'value', 'custom_attributes', 'data', 'desc-inline' );

$class_print = '';

if ( ! empty( $class ) ) {
	$class_print = $class;
}
?>

<div class="qodef-yesno qodef-field <?php echo esc_attr( $class_print ); ?>" data-option-name="<?php echo esc_attr( $field_id ); ?>" data-option-type="yesno" <?php qode_product_extra_options_for_woocommerce_html_attributes_to_string( $custom_attributes, true ); ?>>
	<?php qode_product_extra_options_for_woocommerce_html_data_to_string( $data, true ); ?>

	<input type="radio" id="<?php echo esc_attr( $field_id ); ?>-yes" name="<?php echo esc_attr( $field_id ); ?>" value="yes" <?php echo 'yes' === esc_attr( $value ) ? 'checked' : ''; ?>/>
	<label for="<?php echo esc_attr( $field_id ); ?>-yes">
		<?php esc_html_e( 'Yes', 'qode-product-extra-options-for-woocommerce' ); ?>
	</label>
	<input type="radio" id="<?php echo esc_attr( $field_id ); ?>-no" name="<?php echo esc_attr( $field_id ); ?>" value="no" <?php echo 'no' === esc_attr( $value ) ? 'checked' : ''; ?>/>
	<label for="<?php echo esc_attr( $field_id ); ?>-no">
		<?php esc_html_e( 'No', 'qode-product-extra-options-for-woocommerce' ); ?>
	</label>
</div>

<?php if ( isset( $desc_inline ) ) : ?>
	<p class='qodef-description qodef-field-description'><?php echo wp_kses_post( $desc_inline ); ?></p>
<?php endif; ?>
