<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Addon Advanced Options Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
 * @var int $addon_id
 * @var string $addon_type
 * @var Qode_Product_Extra_Options_For_WooCommerce_Blocks $block
 * @var int $block_id
 */

// Used for rules in premium functionality.
$min_max_rule  = (array) $addon->get_setting( 'min_max_rule', 'min', false );
$min_max_value = (array) $addon->get_setting( 'min_max_value', 0, false );

$addon_type = isset( $_GET['addon_type'] ) ? sanitize_key( $_GET['addon_type'] ) : $addon->type; //phpcs:ignore

?>

<div id="qodef-advanced-settings-tab" style="display: none;">
	<?php
	$options_configuration = $addon->get_options_configuration_array();
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

		if ( 'addon-min-exa-rules' === $config_id ) {

			// Hook to include min exa rules for premium functionality.
			do_action( 'qode_product_extra_options_for_woocommerce_admin_action_addon_min_exa_rules', $addon_type, $min_max_rule, $min_max_value );

			continue;
		} elseif ( 'addon-max-rule' === $config_id ) {

			// Hook to include max rule for premium functionality.
			do_action( 'qode_product_extra_options_for_woocommerce_admin_action_addon_max_rule', $addon_type, $min_max_rule, $min_max_value );

			continue;
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
