<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Block Rules Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Blocks $block
 */

$block = $params[0];

$show_in                 = $block->get_rule( 'show_in' );
$show_show_in_products   = ( 'categories' !== $show_in && 'all' !== $show_in && '' !== $show_in ) || ( isset( $_REQUEST['block_rule_show_in'] ) && 'products' === sanitize_text_field( wp_unslash( $_REQUEST['block_rule_show_in'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
$show_show_in_categories = 'categories' === $show_in;

$show_exclude_products            = 'all' === $show_in || 'products' === $show_in || 'categories' === $show_in;
$show_exclude_products_products   = $block->get_rule( 'exclude_products' ) === 'yes';
$show_exclude_products_categories = $block->get_rule( 'exclude_products' ) === 'yes';
?>

<div id="qodef-block-rules" class="qodef-field-holder-full-width">

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12" id="qode_product_extra_options_for_woocommerce_block_rule_show_in">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
						echo esc_html__( 'Show This Block of Options In', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
						echo esc_html__( 'Choose to show these options in all products or only specific products or categories', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php

				$block_rule_show_in = 'all';

				$block_rule_show_in_check = $block->get_rule( 'show_in' );
				if ( ! empty( $block_rule_show_in_check ) ) {
					$block_rule_show_in = $block->get_rule( 'show_in' );
				} elseif ( isset( $_REQUEST['block_rule_show_in'] ) && ! empty( $_REQUEST['block_rule_show_in'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$block_rule_show_in = sanitize_text_field( wp_unslash( $_REQUEST['block_rule_show_in'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
				}

				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'      => 'qodef-qode-product-extra-options-for-woocommerce-block-rule-show-in',
						'name'    => 'qode_product_extra_options_for_woocommerce_block_rule_show_in',
						'type'    => 'select',
						'value'   => $block_rule_show_in,
						'options' => array(
							'all'      => esc_html__( 'All products', 'qode-product-extra-options-for-woocommerce' ),
							'products' => esc_html__( 'Specific products or categories', 'qode-product-extra-options-for-woocommerce' ),
						),
						'default' => 'all',
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" id="qode_product_extra_options_for_woocommerce_block_rule_show_in_products" data-show="{<?php echo esc_attr( '"qode_product_extra_options_for_woocommerce_block_rule_show_in":"products"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
						echo esc_html__( 'Show in Products', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
						echo esc_html__( 'Choose the products you wish to show this block for', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="field block-option">
				<?php
				$show_in_products = $block->get_rule( 'show_in_products' );

				if ( empty( $show_in_products ) && isset( $_REQUEST['block_rule_show_in_products'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$show_in_products = is_string( $_REQUEST['block_rule_show_in_products'] ) ? // phpcs:ignore WordPress.Security.NonceVerification
						preg_match( '~[0-9]+~', sanitize_key( $_REQUEST['block_rule_show_in_products'] ) ) ? // phpcs:ignore WordPress.Security.NonceVerification
							explode( ',', stripslashes( str_replace( array( '[', ']', '"', "\'" ), '', sanitize_key( $_REQUEST['block_rule_show_in_products'] ) ) ) ) : '' // phpcs:ignore WordPress.Security.NonceVerification
						: sanitize_key( $_REQUEST['block_rule_show_in_products'] ); // phpcs:ignore WordPress.Security.NonceVerification
				}

				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'       => 'qodef-qode-product-extra-options-for-woocommerce-block-rule-show-in-products',
						'name'     => 'qode_product_extra_options_for_woocommerce_block_rule_show_in_products',
						'type'     => 'select',
						'multiple' => true,
						'value'    => $show_in_products,
						'options'  => qode_product_extra_options_for_woocommerce_get_cpt_items( 'product', array( 'numberposts' => '-1' ) ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" id="qode_product_extra_options_for_woocommerce_block_rule_show_in_categories" data-show="{<?php echo esc_attr( '"qode_product_extra_options_for_woocommerce_block_rule_show_in":"products"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
						echo esc_html__( 'Show in Categories', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
						echo esc_html__( 'Choose product categories to show this block for', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="field block-option">
				<?php
				$show_in_categories = $block->get_rule( 'show_in_categories' );

				if ( empty( $show_in_categories ) && isset( $_REQUEST['block_rule_show_in_categories'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$show_in_categories = is_string( $_REQUEST['block_rule_show_in_categories'] ) ? // phpcs:ignore WordPress.Security.NonceVerification
						preg_match( '~[0-9]+~', sanitize_key( $_REQUEST['block_rule_show_in_categories'] ) ) ? // phpcs:ignore WordPress.Security.NonceVerification
							explode( ',', stripslashes( str_replace( array( '[', ']', '"', "\'" ), '', sanitize_key( $_REQUEST['block_rule_show_in_categories'] ) ) ) ) : '' // phpcs:ignore WordPress.Security.NonceVerification
						: sanitize_key( $_REQUEST['block_rule_show_in_categories'] ); // phpcs:ignore WordPress.Security.NonceVerification
				}

				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'       => 'qodef-qode-product-extra-options-for-woocommerce-block-rule-show-in-categories',
						'name'     => 'qode_product_extra_options_for_woocommerce_block_rule_show_in_categories',
						'type'     => 'select',
						'multiple' => true,
						'value'    => $show_in_categories,
						'options'  => qode_product_extra_options_for_woocommerce_get_cpt_taxonomy_items( 'product_cat', false ),
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<?php
	// Hook to include additional options after show in categories option.
	do_action( 'qode_product_extra_options_for_woocommerce_action_block_rules_after_show_in_categories', $block );
	?>
</div>
