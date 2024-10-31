<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the buttons field
 *
 * @var array $field The field.
 */

list ( $buttons ) = qode_product_extra_options_for_woocommerce_extract( $field, 'buttons' );
?>
<?php if ( ! empty( $buttons ) && is_array( $buttons ) ) : ?>
	<?php foreach ( $buttons as $button ) : ?>
		<?php
		$button_default_args = array(
			'name'  => '',
			'class' => '',
			'data'  => array(),
		);
		$button              = wp_parse_args( $button, $button_default_args );
		list ( $button_class, $button_name, $button_data ) = qode_product_extra_options_for_woocommerce_extract( $button, 'class', 'name', 'data' );
		?>
		<input type="button" class="<?php echo esc_attr( $button_class ); ?> button button-secondary"
				value="<?php echo esc_attr( $button_name ); ?>" <?php qode_product_extra_options_for_woocommerce_html_data_to_string( $button_data, true ); ?>/>
	<?php endforeach; ?>
<?php endif; ?>
