<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Addon tabs from Editor Template
 *
 * @var int $addon_id The add-on id.
 * @var string $addon_type The add-on type.
 */

$addon_tabs = qode_product_extra_options_for_woocommerce_get_addon_tabs( $addon_id, $addon_type );
?>

<div id="qodef-addon-tabs">
<?php
foreach ( $addon_tabs as $tab_id => $addon_tab ) {
	?>
		<a href="#" id="<?php echo esc_html( $addon_tab['id'] ); ?>" class="<?php echo esc_html( $addon_tab['class'] ); ?>"><?php echo esc_html( $addon_tab['label'] ); ?></a>
	<?php
}
?>
</div>
