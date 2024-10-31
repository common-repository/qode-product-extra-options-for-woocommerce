<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Block Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Blocks $block
 * @var array $addons
 * @var int $x
 * @var string $style_addon_titles
 * @var string $currency
 */

$block_classes       = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_block_classes', 'qpeofw-block', $block );
$required_message    = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_options_required_option_text' );
$setting_hide_images = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_single_options_hide_images' );
$hide_title_images   = wc_string_to_bool( qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_single_options_hide_titles_and_images' ) );

$html_types = array( 'html-heading', 'html-separator', 'html-text' );

$product = qode_product_extra_options_for_woocommerce_get_global_product();
?>

<div id="qpeofw-block--<?php echo esc_attr( $block->id ); ?>" class="<?php echo esc_attr( $block_classes ); ?>">

	<?php
	foreach ( $addons as $key => $addon ) :
		/**
		 * Addon class.
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
		 */
		if ( qode_product_extra_options_for_woocommerce_is_addon_type_available( $addon->type ) ) :
			$settings = $addon->get_formatted_settings();
			extract( $settings ); // @codingStandardsIgnoreLine

			$toggle_addon   = 'no' !== $show_as_toggle ? 'qpeofw--toggle' : '';
			$toggle_status  = 'qpeofw--toggle-closed';
			$toggle_default = 'qpeofw--default-closed';

			if ( 'no' !== $show_as_toggle ) {

				switch ( $show_as_toggle ) {
					case 'no-toggle':
						$toggle_addon = '';
						break;
					case 'open':
						$toggle_addon   = 'qpeofw--toggle';
						$toggle_status  = 'qpeofw--toggle-open';
						$toggle_default = 'qpeofw--default-open';
						break;
					case 'closed':
						$toggle_addon   = 'qpeofw--toggle';
						$toggle_status  = 'qpeofw--toggle-closed';
						$toggle_default = 'qpeofw--default-closed';
						break;
				}
				// General option.
			} elseif ( 'yes' === qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_options_display_in_toggle' ) ) {
				$toggle_addon   = 'qpeofw--toggle';
				$toggle_status  = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_options_display_toggle_opened' ) === 'yes' ? 'qpeofw--toggle-open' : 'qpeofw--toggle-closed';
				$toggle_default = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_options_display_toggle_opened' ) === 'yes' ? 'qpeofw--default-open' : 'qpeofw--default-closed';
			}

			if ( 'toggle' === $toggle_addon && '' === $addon_title ) {
				$addon_title = esc_html__( 'No title', 'qode-product-extra-options-for-woocommerce' );
			}

			// Advanced settings.
			$min_max_values = array(
				'min' => '',
				'max' => '',
				'exa' => '',
			);

			if ( 'yes' === $enable_min_max && is_array( $min_max_rule ) ) {
				$min_max_rule_count = count( $min_max_rule );
				for ( $y = 0; $y < $min_max_rule_count; $y++ ) {
					$min_max_values[ $min_max_rule[ $y ] ] = $min_max_value[ $y ];
				}
			}

			$is_numbers_min_max_enabled = wc_string_to_bool( $enable_min_max_numbers );
			$hide_options_images        = wc_string_to_bool( $hide_option_images );
			$show_in_a_grid             = wc_string_to_bool( $show_in_a_grid );

			$required_addon = false;

			if ( 'yes' === apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addons_settings_required', $addon_required, $addon ) || ( 'select' === $addon_type && 'yes' === $addon_required ) || ( 'yes' === $enable_min_max && ( ! empty( $min_max_values['min'] ) || ! empty( $min_max_values['exa'] ) ) ) ) {
				$required_addon = true;
			}

			// Conditional logic.
			$enable_rules            = wc_string_to_bool( $enable_rules );
			$conditional_logic_class = '';
			if ( $enable_rules ) {

				$conditional_rule_addon    = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_conditional_rule_addon', (array) $conditional_rule_addon );
				$conditional_logic_rules   = ! empty( $conditional_rule_addon );
				$conditional_rule_addon_is = ! empty( $conditional_rule_addon ) ? (array) $conditional_rule_addon_is : array();

				// Variations.
				$apply_variation_rule        = wc_string_to_bool( $enable_rules_variations );
				$conditional_logic_variation = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_conditional_rule_variation', (array) $addon->get_setting( 'conditional_rule_variations' ) );
				$variations_logic            = $apply_variation_rule && ! empty( $conditional_logic_variation );
				if ( $apply_variation_rule ) {
					if ( ! $conditional_set_conditions ) {
						$conditional_rule_addon = false;
					}
				}

				// If conditions or variations, apply the conditional logic.
				if ( $conditional_logic_rules || $variations_logic ) {
					$conditional_logic_class = 'conditional_logic';
				} else {
					$enable_rules = false;
				}
			}

			$addon_classes = apply_filters(
				'qode_product_extra_options_for_woocommerce_filter_addon_classes',
				'qpeofw-addon qpeofw-addon-type-' . esc_attr( $addon_type ) . ' ' . esc_attr( $toggle_addon ) . ' ' . esc_attr( $toggle_default ) . ' ' . esc_attr( $toggle_status ) . ' ' .
				esc_attr( $conditional_logic_class ) . ' ' . esc_attr( 'yes' === $sell_individually ? 'sell_individually' : '' ) . ' ' .
				esc_attr( $is_numbers_min_max_enabled ? 'numbers-check' : '' ) . ' ' . esc_attr( '' === $addon_title && ( ! in_array( $addon_type, $html_types, true ) ) ? 'qpeofw--empty-title' : '' ),
				$addon
			);
			?>

			<div id="qpeofw-addon-<?php echo esc_attr( $addon->id ); ?>"
				class="<?php echo esc_attr( $addon_classes ); ?>"
				data-min="<?php echo esc_attr( $min_max_values['min'] ); ?>"
				data-max="<?php echo esc_attr( $min_max_values['max'] ); ?>"
				data-exa="<?php echo esc_attr( $min_max_values['exa'] ); ?>"
				data-addon-type="<?php echo esc_attr( $addon->type ); ?>"
				<?php
				if ( $is_numbers_min_max_enabled && '' !== $numbers_min ) {
					?>
					data-numbers-min="<?php echo esc_attr( $numbers_min ); ?>"
					<?php
				}
				if ( $is_numbers_min_max_enabled && '' !== $numbers_max ) {
					?>
					data-numbers-max="<?php echo esc_attr( $numbers_max ); ?>"
				<?php } ?>
				<?php if ( $enable_rules ) : ?>
					data-addon_id="<?php echo esc_attr( $addon->id ); ?>"
					data-conditional_logic_display="<?php echo esc_attr( $conditional_logic_display ); ?>"
					data-conditional_logic_display_if="<?php echo esc_attr( $conditional_logic_display_if ); ?>"
					data-conditional_rule_addon="<?php echo esc_attr( $conditional_rule_addon ? implode( '|', $conditional_rule_addon ) : '' ); ?>"
					data-conditional_rule_addon_is="<?php echo esc_attr( implode( '|', $conditional_rule_addon_is ) ); ?>"
					data-conditional_rule_variations="<?php echo esc_attr( $variations_logic ? implode( '|', $conditional_logic_variation ) : '' ); ?>"
					style="display: none;"
				<?php endif; ?>
				>

				<div class="qpeofw-addon-header">
					<?php
					if ( ! $hide_title_images && 'yes' === $show_image && '' !== $addon_image ) {
						?>
						<div class="qpeofw-title-image">
							<img src="<?php echo esc_url( wp_get_attachment_image_url( $addon_image, 'full' ) ); ?>">
						</div>
						<?php
					}
					?>
					<?php if ( ! $hide_title_images && ! in_array( $addon_type, $html_types, true ) ) { ?>
						<?php if ( ! empty( $addon_title ) ) { ?>
							<<?php qode_product_extra_options_for_woocommerce_escape_title_tag( $style_addon_titles ); ?> class="qpeofw-addon-title <?php echo esc_attr( $toggle_status ); ?>">
								<span class="qpeofw-addon-title-text"><?php echo wp_kses_post( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_display_title', $addon_title ) ); ?></span>
								<?php
								if ( $required_addon ) :
									echo '<span class="qpeofw-required">*</span>';
								endif;
								?>
							</<?php qode_product_extra_options_for_woocommerce_escape_title_tag( $style_addon_titles ); ?>>
							<?php
							if ( 'yes' === qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_options_display_in_toggle' ) || 'no' !== $show_as_toggle ) {
								qode_product_extra_options_for_woocommerce_render_svg_icon( 'chevron-down' );
							}
						}
						?>
					<?php } ?>
				</div>

				<?php
				if ( in_array( $addon_type, $html_types, true ) ) {
					wc_get_template(
						'/front/addons/' . $addon_type . '.php',
						apply_filters(
							'qode_product_extra_options_for_woocommerce_filter_addon_html_args',
							array(
								'addon'    => $addon,
								'settings' => $settings,
							),
							$addon
						),
						'',
						QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates'
					);
				} else {
					$options_width_select_css = 'width: ' . ( $select_width ) . '%';
					$per_row                  = 'qpeofw-col-num--' . esc_attr( $options_per_row );
					if ( $show_in_a_grid ) {
						$per_row_1512 = 'qpeofw-col-num--1512--' . esc_attr( $options_per_row_1512 );
						$per_row_1368 = 'qpeofw-col-num--1368--' . esc_attr( $options_per_row_1368 );
						$per_row_1200 = 'qpeofw-col-num--1200--' . esc_attr( $options_per_row_1200 );
						$per_row_1024 = 'qpeofw-col-num--1024--' . esc_attr( $options_per_row_1024 );
						$per_row_880  = 'qpeofw-col-num--880--' . esc_attr( $options_per_row_880 );
						$per_row_680  = 'qpeofw-col-num--680--' . esc_attr( $options_per_row_680 );
					}
					$options_total = is_array( $addon->options ) && isset( array_values( $addon->options )[0] ) ? count( array_values( $addon->options )[0] ) : 1;

					$options_width_select_classes   = array();
					$options_width_select_classes[] = 'qpeofw-options';
					$options_width_select_classes[] = 'qpeofw-grid';
					$options_width_select_classes[] = $per_row;
					$options_width_select_classes[] = $show_in_a_grid ? ' qpeofw-adjust-grid' : '';

					if ( 'select' === $addon->type ) {
						echo '<div class="qpeofw-options-container ' . esc_attr( $toggle_default ) . '">';
						if ( '' !== $addon_description ) {
							echo '<p class="qpeofw-addon-description">' . stripslashes( $addon_description ) . '</p>'; // phpcs:ignore
						}
						echo '<div ' . qode_product_extra_options_for_woocommerce_get_class_attribute( $options_width_select_classes ) . ' ' . qode_product_extra_options_for_woocommerce_get_inline_style( $options_width_select_css ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						for ( $x = 0; $x < $options_total; $x++ ) {
							$addon_image_position = $addon->get_image_position( $x );
						}

						wc_get_template(
							'/front/addons/select.php',
							apply_filters(
								'qode_product_extra_options_for_woocommerce_filter_addon_select_args',
								array(
									'addon'                => $addon,
									'x'                    => $x,
									'setting_hide_images'  => $setting_hide_images,
									'hide_options_images'  => $hide_options_images,
									'required_message'     => $required_message,
									'settings'             => $settings,
									'addon_image_position' => $addon_image_position,
									'options_total'        => $options_total,
									'options_width_select_css' => $options_width_select_css,
									'currency'             => $currency,
									'product'              => $product,
								),
								$addon
							),
							'',
							QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates'
						);
					} else {
						$grid_styles = qode_product_extra_options_for_woocommerce_get_addon_grid_rules( $addon );

						echo '<div class="qpeofw-options-container ' . esc_attr( $toggle_default ) . '">';
						if ( '' !== $addon_description ) {
							echo '<p class="qpeofw-addon-description">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

							preg_match_all( '/' . get_shortcode_regex() . '/', $addon_description, $description_matches, PREG_SET_ORDER );

							if ( empty( $description_matches ) ) {
								// No shortcodes in the description.
								echo esc_html( $addon_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							} else {
								// Shortcodes in the description.
								echo do_shortcode( $addon_description );
							}

							echo '</p>';
						}

						$options_classes   = array();
						$options_classes[] = 'qpeofw-options';
						$display_type      = esc_attr( $options_display_type );

						// Options Display Type is "grid".
						if ( 'flex' !== $display_type ) {
							$options_classes[] = 'qpeofw-grid';
							$options_classes[] = $per_row;
							$options_classes[] = $show_in_a_grid ? ' qpeofw-adjust-grid' : '';
							if ( $show_in_a_grid ) {
								$options_classes[] = 'qpeofw-adjust-grid';
								$options_classes[] = $per_row_1512;
								$options_classes[] = $per_row_1368;
								$options_classes[] = $per_row_1200;
								$options_classes[] = $per_row_1024;
								$options_classes[] = $per_row_880;
								$options_classes[] = $per_row_680;
							}
						} else {
							$options_classes[] = 'qpeofw-flex';
						}

						echo '<div ' . qode_product_extra_options_for_woocommerce_get_class_attribute( $options_classes ) . ' ' . qode_product_extra_options_for_woocommerce_get_inline_style( $grid_styles ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						for ( $x = 0; $x < $options_total; $x++ ) {
							if ( file_exists( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates/front/addons/' . $addon->type . '.php' ) ) {

								$enabled = $addon->get_option( 'addon_enabled', $x, 'yes', false );

								if ( wc_string_to_bool( $enabled ) ) {

									$option_show_image  = $addon->get_option( 'show_image', $x, false );
									$option_image       = $option_show_image ? $addon->get_option( 'image', $x ) : '';
									$option_description = $addon->get_option( 'description', $x );

									// TODO: improve price calculation.
									$price_method       = $addon->get_option( 'price_method', $x, 'free', false );
									$price_type         = $addon->get_option( 'price_type', $x, 'fixed', false );
									$price              = $addon->get_price( $x, true, $product );
									$price_sale         = $addon->get_sale_price( $x );
									$default_price      = floatval( str_replace( ',', '.', $addon->get_default_price( $x ) ) );
									$default_sale_price = $addon->get_default_sale_price( $x );
									$default_sale_price = '' !== $default_sale_price ? floatval( str_replace( ',', '.', $default_sale_price ) ) : '';
									$price              = floatval( str_replace( ',', '.', $price ) );
									$price_sale         = '' !== $price_sale ? floatval( str_replace( ',', '.', $price_sale ) ) : '';

									// TODO: improve price calculation.
									if ( 'free' === $price_method ) {
										$default_price      = '0';
										$default_sale_price = '0';
										$price              = '0';
										$price_sale         = '0';
									} elseif ( 'decrease' === $price_method ) {
										$default_price      = $default_price > 0 ? - $default_price : 0;
										$default_sale_price = '0';
										$price              = $price > 0 ? - $price : 0;
										$price_sale         = '0';
									} elseif ( 'product' === $price_method ) {
										$default_price      = $default_price > 0 ? $default_price : 0;
										$default_sale_price = '0';
										$price              = $price > 0 ? $price : 0;
										$price_sale         = '0';
									} else {
										$default_price      = $default_price > 0 ? $default_price : '0';
										$default_sale_price = $default_sale_price >= 0 ? $default_sale_price : 'undefined';
										$price              = $price > 0 ? $price : '0';
										$price_sale         = $price_sale >= 0 ? $price_sale : 'undefined';
									}

									$addon_image_position = $addon->get_image_position( $x );

									wc_get_template(
										'/front/addons/' . $addon_type . '.php',
										apply_filters(
											'qode_product_extra_options_for_woocommerce_filter_addon_arg',
											array(
												'addon'    => $addon,
												'x'        => $x,
												'setting_hide_images' => $setting_hide_images,
												'required_message' => $required_message,
												'settings' => $settings,
												// Addon options.
												'option_description' => $option_description,
												'addon_image_position' => $addon_image_position,
												'option_image' => is_ssl() ? str_replace( 'http://', 'https://', $option_image ) : $option_image,
												'default_price' => $default_price,
												'default_sale_price' => $default_sale_price,
												'price'    => $price,
												'price_method' => $price_method,
												'price_sale' => $price_sale,
												'price_type' => $price_type,
												'currency' => $currency,
												'product'  => $product,
											),
											$addon
										),
										'',
										QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates'
									);
								}
							}
						}
					}

					if ( ( 'select' === $addon->type || 'radio' === $addon->type ) && 'yes' === $sell_individually ) {
						echo '<input type = "hidden" name = "qpeofw_sell_individually[' . esc_attr( $addon->id ) . ']" value = "yes" >';
					}
					?>
					</div>
					<?php
					if ( 'yes' === $addon_required || 'yes' === $enable_min_max ) :
						?>
						<div class="qpeofw-min-error" style="display: none;">
							<span class="qpeofw-min-error-message"></span>
						</div>
						<?php
					endif;
					?>
				</div>
					<?php
				}
				?>

			</div>

		<?php endif; ?>

<?php endforeach; ?>

</div>
