<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Color Template
 *
 * @var object $addon
 * @var string $addon_type
 * @var int    $x
 */
?>

<div class="qodef-fields">
	<?php
	qode_product_extra_options_for_woocommerce_template_part(
		'product-add-ons',
		'addons/admin-pages/addons-view/templates/template',
		'option-common-fields',
		array(
			'x'          => $x,
			'addon_type' => $addon_type,
			'addon'      => $addon,
		)
	);
	?>
</div>
