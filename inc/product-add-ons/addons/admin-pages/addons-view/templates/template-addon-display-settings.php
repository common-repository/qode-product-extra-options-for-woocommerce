<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Addon Dsiaply Options Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
 * @var int $addon_id
 * @var string $addon_type
 * @var Qode_Product_Extra_Options_For_WooCommerce_Blocks $block
 * @var int $block_id
 */
?>

<div id="qodef-display-settings-tab" style="display: none;">

	<?php
	// Overridden when premium plugin is active.
	$options_configuration = $addon->get_options_display_style_array();
	$default_options       = qode_product_extra_options_for_woocommerce_get_default_configuration_options();

	foreach ( $options_configuration as $config_id => $config_options ) {

		$config_options = array_merge( $default_options['parent'], $config_options );

		foreach ( $config_options as $config_option_id => &$config_option_values ) {
			if ( 'field' === $config_option_id ) {
				foreach ( $config_option_values as &$field_values ) {
					$field_values = array_merge( $default_options['field'], $field_values );
				}
			}
		}

		qode_product_extra_options_for_woocommerce_template_part(
			'product-add-ons',
			'addons/admin-pages/addons-view/templates/template',
			'addon-field',
			array(
				'addon'          => $addon,
				'addon_id'       => $addon_id,
				'addon_type'     => $addon_type,
				'config_id'      => $config_id,
				'config_options' => $config_options,
			)
		);
	}
	?>
</div>
