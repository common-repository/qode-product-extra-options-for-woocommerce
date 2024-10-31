<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

class Qode_Product_Extra_Options_For_WooCommerce_Framework_Field_Textareahtml extends Qode_Product_Extra_Options_For_WooCommerce_Framework_Field_Type {

	public function render_field() {
		?>
		<textarea class="form-control qodef-field qodef--field-html" <?php qode_product_extra_options_for_woocommerce_inline_attrs( $this->data_attrs ); ?> name="<?php echo esc_attr( $this->name ); ?>" rows="10"
		<?php
		if ( isset( $this->args['readonly'] ) ) {
			echo ' readonly';
		}
		?>
		><?php echo wp_kses_post( $this->params['value'] ); ?></textarea>
		<?php
	}
}
