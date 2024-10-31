<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Addon Conditional Logic Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
 * @var int $addon_id
 * @var string $addon_type
 * @var Qode_Product_Extra_Options_For_WooCommerce_Blocks $block
 * @var int $block_id
 */

$enable_rules                 = $addon->get_setting( 'enable_rules', 'no', false );
$enable_rules_variations      = $addon->get_setting( 'enable_rules_variations', 'no', false );
$conditional_logic_display    = $addon->get_setting( 'conditional_logic_display', 'show', false );
$conditional_rules_variations = $addon->get_setting( 'conditional_rule_variations', array() );
$conditional_set_conditions   = $addon->get_setting( 'conditional_set_conditions', '0' );
$conditional_logic_display_if = $addon->get_setting( 'conditional_logic_display_if', 'all', false );
$conditional_rule_addon       = (array) $addon->get_setting( 'conditional_rule_addon' );
$conditional_rule_addon_is    = $addon->get_setting( 'conditional_rule_addon_is' );

$conditional_array = array( 'empty' => esc_html__( 'Select an add-on', 'qode-product-extra-options-for-woocommerce' ) );

$show_in                      = $block->get_rule( 'show_in', 'all' );
$selected_products            = array();
$selected_categories          = array();
$original_selected_products   = ! empty( $block->get_rule( 'show_in_products' ) ) ? (array) $block->get_rule( 'show_in_products' ) : array();
$original_selected_categories = ! empty( $block->get_rule( 'show_in_categories' ) ) ? (array) $block->get_rule( 'show_in_categories' ) : array();
$has_categories               = 0;

$conditional_addons       = Qode_Product_Extra_Options_For_WooCommerce_Main()->db->get_addons_by_block_id( $block_id );
$total_conditional_addons = count( $conditional_addons );
if ( $total_conditional_addons > 0 ) {

	foreach ( $conditional_addons as $key => $conditional_addon ) {
		/**
		 * Addon class.
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $conditional_addon
		 */

		// Return HTML Type Add-ons.
		if ( str_starts_with( $conditional_addon->get_type(), 'html' ) ) {
			continue;
		}

		$current_addon_id = $conditional_addon->get_id();

		if ( $conditional_addon->get_id() !== $addon_id ) {
			$addon_title = ! empty( $conditional_addon->get_title() ) ? $conditional_addon->get_title() : esc_html__( 'Empty title', 'qode-product-extra-options-for-woocommerce' );
			if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_id_in_conditional_addon_title', false ) ) {
				$addon_title = '#' . $conditional_addon->get_id() . ' - ' . $addon_title;
			}

			if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_add_parent_addon_conditional_logic', false ) ) {
				$conditional_array[ $current_addon_id ]['options'][ $conditional_addon->id ] = ' - ' . $addon_title;
			}

			$conditional_array[ $current_addon_id ]['label'] = htmlentities( $addon_title );

			$options_total = is_array( $conditional_addon->options ) && isset( array_values( $conditional_addon->options )[0] ) ? count( array_values( $conditional_addon->options )[0] ) : 1;

			for ( $x = 0; $x < $options_total; $x++ ) {
				if ( isset( $conditional_addon->options['label'][ $x ] ) ) {

					$option_name = $conditional_addon->options['label'][ $x ];
					if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_reduce_conditional_option_name', true ) && strlen( $option_name ) > 25 ) {
						$option_name = mb_substr( $option_name, 0, 22 ) . '...';
					}
					$conditional_array[ $current_addon_id ]['options'][ $conditional_addon->id . '-' . $x ] = ' - ' . $option_name;
				}
			}
		}
	}
}


/** Include particular variations to the select box dropdown of the conditional logic */

if ( 'products' === $show_in && ! empty( $original_selected_products ) ) {
	$selected_products = $original_selected_products;
	foreach ( $selected_products as $index => $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product instanceof WC_Product_Variable ) {
			$variation_ids     = $product->get_children();
			$selected_products = array_merge( $selected_products, $variation_ids );
		}
		$selected_products[ $index ] = $product_id;
	}
}


if ( 'products' === $show_in && ! empty( $original_selected_categories ) ) {
	foreach ( $original_selected_categories as $index => $category_id ) {
		$category = get_term( $category_id, 'product_cat' );

		$product_ids_cat = get_posts(
			array(
				'post_type'   => 'product',
				'numberposts' => - 1,
				'post_status' => 'publish',
				'fields'      => 'ids',
				'tax_query'   => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'id',
						'terms'    => $category_id,
						'operator' => 'IN',
					),
				),
			)
		);
		foreach ( $product_ids_cat as $index_inner => $product_id ) {
			$product = wc_get_product( $product_id );
			if ( $product instanceof WC_Product_Variable ) {
				$variation_ids     = $product->get_children();
				$selected_products = array_merge( $selected_products, $variation_ids );
			}
		}
	}
}

$selected_products = array_unique( $selected_products );

?>

<div id="qodef-conditional-logic-tab" style="display: none;">
	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php echo esc_html__( 'Adjust Conditions to Show or Hide This Set of Options', 'qode-product-extra-options-for-woocommerce' ); ?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php echo esc_html__( 'Enable to set rules to hide or show the Options', 'qode-product-extra-options-for-woocommerce' ); ?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'addon-enable-rules',
						'name'  => 'addon_enable_rules',
						'type'  => 'yesno-radio',
						'value' => $enable_rules,
					),
					true,
					false
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" id="qode_product_extra_options_for_woocommerce_block_rule_show_in_products" data-show="{<?php echo esc_attr( '"addon_enable_rules":"yes"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php echo esc_html__( 'Show/Hide on Specific Variations', 'qode-product-extra-options-for-woocommerce' ); ?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php echo esc_html__( 'Enable to set rules to hide or show the Options for specific product variations', 'qode-product-extra-options-for-woocommerce' ); ?>
				</p>
			</div>
			<div class="qodef-field-content">
				<?php
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'    => 'addon-enable-rules-variations',
						'name'  => 'addon_enable_rules_variations',
						'type'  => 'yesno-radio',
						'value' => $enable_rules_variations,
					),
					true
				);
				?>
			</div>
		</div>
	</div>
	<!-- End option field -->

	<!-- Option field -->
	<div class="qodef-field-holder col-md-12 col-lg-12 qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"addon_enable_rules":"yes"' ); ?>}">
		<div class="qodef-field-section">
			<div class="qodef-field-desc">
				<h3 class="qodef-title qodef-field-title">
					<?php
					echo esc_html__( 'Display Rules', 'qode-product-extra-options-for-woocommerce' );
					?>
				</h3>
				<p class="qodef-description qodef-field-description">
					<?php
					echo esc_html__( 'Set conditions for rules to be displayed', 'qode-product-extra-options-for-woocommerce' );
					?>
				</p>
			</div>
			<div class="qodef-field-content">
				<div class="qodef-field-wrapper">
					<div class="qodef-additional-options">
						<div class="qodef-enabled-by-addon-enable-rules">
							<div class="qodef-variations-container">
								<div class="qodef-conditional-display-rules">
									<div class="qodef-field-wrapper qodef-filed-type--select qodef-display-logic">
										<?php
										qode_product_extra_options_for_woocommerce_get_field(
											array(
												'id'      => 'addon-conditional-logic-display',
												'name'    => 'addon_conditional_logic_display',
												'type'    => 'select',
												'value'   => $conditional_logic_display,
												'options' => array(
													'show' => esc_html__( 'Show', 'qode-product-extra-options-for-woocommerce' ),
													'hide' => esc_html__( 'Hide', 'qode-product-extra-options-for-woocommerce' ),
												),
											),
											true
										);
										?>
									</div>

									<div class="qodef-field-wrapper qodef-filed-type--multiselect qodef-product-variation qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"addon_enable_rules_variations":"yes"' ); ?>}">
										<small>
											<?php
											echo esc_html__( 'variable product specific variation(s)', 'qode-product-extra-options-for-woocommerce' );
											?>
										</small>
										<?php
										qode_product_extra_options_for_woocommerce_get_field(
											array(
												'id'       => 'addon-conditional-logic-variations',
												'name'     => 'addon_conditional_rule_variations',
												'type'     => 'select',
												'multiple' => true,
												'value'    => $conditional_rules_variations,
												'options'  => qode_product_extra_options_for_woocommerce_get_products_variations( array( 'numberposts' => '-1' ) ),
											),
											true
										);
										?>
									</div>

									<div class="qodef-field-wrapper qodef-filed-type--checkbox qodef-set-conditions qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"addon_enable_rules_variations":"yes"' ); ?>}">
										<div class="qodef-set-conditions-inner">
											<?php
											$set_conditions = '1' === $conditional_set_conditions ? 'yes' : 'no';

											qode_product_extra_options_for_woocommerce_get_field(
												array(
													'id'   => 'enabled-variations-set-conditions',
													'name' => 'addon_conditional_set_conditions',
													'type' => 'checkbox',
													'value' => $set_conditions,
												),
												true
											);
											?>

											<label for="enabled-variations-set-conditions" class="enabled-variations-set-conditions"><?php echo esc_html__( 'Set conditions', 'qode-product-extra-options-for-woocommerce' ); ?></label>
										</div>
									</div>

									<div class="qodef-field-wrapper qodef-filed-type--select qodef-only-if">
										<span class="variations-only-if"><?php echo esc_html__( 'only if', 'qode-product-extra-options-for-woocommerce' ); ?></span>
										<?php
										qode_product_extra_options_for_woocommerce_get_field(
											array(
												'id'      => 'addon-conditional-logic-display-if',
												'name'    => 'addon_conditional_logic_display_if',
												'type'    => 'select',
												'value'   => $conditional_logic_display_if,
												'options' => array(
													'all' => esc_html__( 'All of these rules', 'qode-product-extra-options-for-woocommerce' ),
													'any' => esc_html__( 'Any of these rules', 'qode-product-extra-options-for-woocommerce' ),
												),
											),
											true
										);
										?>
										<span><?php echo esc_html__( 'match', 'qode-product-extra-options-for-woocommerce' ); ?>:</span>
									</div>
								</div>

								<div class="qodef-display-rules" data-addon-options="<?php echo esc_attr( wp_json_encode( $conditional_array ) ); ?>">
									<div class="qodef-display-rules-container-inner">
										<?php
										$conditional_rules_count = count( $conditional_rule_addon );
										for ( $y = 0; $y < $conditional_rules_count; $y++ ) :
											$conditional_rule = isset( $conditional_rule_addon[ $y ] ) ? $conditional_rule_addon[ $y ] : '';
											?>
											<div class="qodef-rule">
												<div class="qodef-field-wrapper">
													<?php
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'          => 'addon-conditional-rule-addon',
															'name'        => 'addon_conditional_rule_addon[]',
															'type'        => 'select',
															'placeholder' => esc_html__( 'Select an add-on', 'qode-product-extra-options-for-woocommerce' ),
															'value'       => $conditional_rule,
															'options'     => $conditional_array,
														),
														true
													);
													?>

													<a class="qodef-delete-rule" href="javascript: void(0)" rel="noopener noreferrer"><?php qode_product_extra_options_for_woocommerce_render_svg_icon( 'trash' ); ?></a>

												</div>

												<span class="qodef-is-selection"><?php echo esc_html__( 'is', 'qode-product-extra-options-for-woocommerce' ); ?></span>

												<div class="qodef-field-wrapper">
													<?php
													// TODO: set the correct options depending on Add-on type selected.
													qode_product_extra_options_for_woocommerce_get_field(
														array(
															'id'          => 'addon-conditional-rule-addon-is',
															'name'        => 'addon_conditional_rule_addon_is[]',
															'type'        => 'select',
															'placeholder' => esc_html__( 'Select an option', 'qode-product-extra-options-for-woocommerce' ),
															'value'       => isset( $conditional_rule_addon_is[ $y ] ) ? $conditional_rule_addon_is[ $y ] : '',
															'options'     => array(
																''             => '',
																'selected'     => esc_html__( 'Selected', 'qode-product-extra-options-for-woocommerce' ),
																'not-selected' => esc_html__( 'Not selected', 'qode-product-extra-options-for-woocommerce' ),
																'empty'        => esc_html__( 'Empty', 'qode-product-extra-options-for-woocommerce' ),
																'not-empty'    => esc_html__( 'Not empty', 'qode-product-extra-options-for-woocommerce' ),
															),
														),
														true
													);
													?>
												</div>
											</div>
										<?php endfor; ?>
									</div>

									<div id="qodef-add-conditional-rule">
										<a class="qodef-btn qodef-btn-solid qodef-add-conditional-rule-button" href="javascript: void(0)"><?php echo esc_html__( 'Add rule', 'qode-product-extra-options-for-woocommerce' ); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End option field -->
</div>
