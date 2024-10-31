<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}
?>
<div class="qodef-meta-box">
	<div class="qodef-meta-box-holder">
		<?php $metabox['args']['box']->render(); ?>
		<?php
		wp_nonce_field(
			'qode_product_extra_options_for_woocommerce_framework_meta_box_' . $metabox['args']['box']->get_slug() . '_save',
			'qode_product_extra_options_for_woocommerce_framework_meta_box_' . $metabox['args']['box']->get_slug() . '_save'
		);
		?>
	</div>
</div>
