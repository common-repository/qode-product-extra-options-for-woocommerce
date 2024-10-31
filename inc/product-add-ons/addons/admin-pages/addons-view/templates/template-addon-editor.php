<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Addon Editor Template
 *
 * @var int $block_id Block ID.
 * @var array $block The block.
 */

$addon_id      = isset( $_REQUEST['addon_id'] ) ? sanitize_key( $_REQUEST['addon_id'] ) : 'new'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$addon_type    = isset( $_REQUEST['addon_type'] ) ? sanitize_key( $_REQUEST['addon_type'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$template_file = QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/addons/admin-pages/addon-types/template-' . $addon_type . '.php';

$delete_action = array(
	'title'        => _x( 'Delete', 'Add-on editor panel (option action)', 'qode-product-extra-options-for-woocommerce' ),
	'action'       => 'delete',
	'icon'         => 'trash',
	'url'          => 'javascript: void(0)',
	'confirm_data' => array(
		'title'               => esc_html__( 'Confirm delete', 'qode-product-extra-options-for-woocommerce' ),
		'message'             => esc_html__( 'Are you sure you want to delete this option?', 'qode-product-extra-options-for-woocommerce' ),
		'confirm-button'      => esc_html__( 'Yes, delete', 'qode-product-extra-options-for-woocommerce' ),
		'confirm-button-type' => 'delete',
	),
);

if ( qode_product_extra_options_for_woocommerce_is_addon_type_available( $addon_type ) && ( file_exists( $template_file ) || 'new' === $addon_id ) ) : ?>
	<?php
	$addons_type = Qode_Product_Extra_Options_For_WooCommerce_Main()->get_addon_types();
	$addon_name  = '';
	$addon_title = '';

	foreach ( $addons_type as $addon ) {
		if ( isset( $addon['slug'] ) && $addon_type === $addon['slug'] ) {
			$addon_name  = $addon['label'] ?? '';
			$addon_title = $addon['name'] ?? '';
		}
	}
	?>
	<div id="qodef-addon-overlay" class="qodef-addon-overlay-wrapper">
		<div id="qodef-addon-editor" class="qodef-addon-type-<?php echo esc_html( $addon_type ); ?>" data-addon-type="<?php echo esc_html( $addon_type ); ?>">

			<span href="#" id="qodef-close-popup">
				<?php qode_product_extra_options_for_woocommerce_render_svg_icon( 'close', 'qodef-icon qodef-icon-close' ); ?>
			</span>

			<?php
			if ( '' !== $addon_type ) :

				if ( qode_product_extra_options_for_woocommerce_is_installed( 'qpeofw-premium' ) && qode_product_extra_options_for_woocommerce_premium_is_plugin_activated() ) {
					$addon = qode_product_extra_options_for_woocommerce_instance_class(
						'Qode_Product_Extra_Options_For_WooCommerce_Premium_Addon',
						array(
							'id'   => $addon_id,
							'type' => $addon_type,
						)
					);
				} else {
					$addon = qode_product_extra_options_for_woocommerce_instance_class(
						'Qode_Product_Extra_Options_For_WooCommerce_Addon',
						array(
							'id'   => $addon_id,
							'type' => $addon_type,
						)
					);
				}
				?>

				<form action="admin.php?page=<?php echo esc_attr( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ); ?>" method="post" id="addon">
					<button type="submit" class="submit button-primary" style="display: none;"></button>

					<?php if ( 'new' === $addon_id ) : ?>
						<a class="qodef-btn qodef-btn-solid qodef-back-to-type-choice" href="admin.php?page=<?php echo esc_attr( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ); ?>&block_id=<?php echo esc_attr( $block_id ); ?>&action=edit&addon_id=new">
							<?php
								echo esc_html__( 'back to the type choice', 'qode-product-extra-options-for-woocommerce' );
							?>
						</a>
					<?php endif; ?>

					<div id="qodef-addon-editor-type" class="qodef-addon-editor-type-<?php echo esc_html( $addon_type ); ?>">

						<h2><?php echo esc_html( ucfirst( str_replace( 'html', 'HTML', str_replace( '_', ' ', $addon_title ) ) ) ); ?></h2>

						<?php if ( strpos( $addon_type, 'html' ) === false ) : ?>

							<?php
							qode_product_extra_options_for_woocommerce_template_part(
								'product-add-ons',
								'addons/admin-pages/addons-view/templates/template',
								'addon-tabs',
								array(
									'addon_id'   => $addon_id,
									'addon_type' => $addon_type,
								)
							);
							?>

						<?php endif; ?>

						<div id="qodef-addon-container">
							<!-- OPTION SETTINGS -->
							<div id="qodef-options-list-tab">

								<?php
								// Count of labels.
								$options_total = is_array( $addon->options ) && isset( array_values( $addon->options )[1] ) ? count( array_values( $addon->options )[1] ) : 1;
								if ( 'html-heading' === $addon_type || 'html-separator' === $addon_type || 'html-text' === $addon_type ) :
									qode_product_extra_options_for_woocommerce_template_part(
										'product-add-ons',
										'addons/admin-pages/addon-types/template',
										$addon_type,
										array(
											'addon'      => $addon,
											'addon_type' => $addon_type,
										)
									);
								else :
									?>

									<!-- Option field -->
									<div class="qodef-field-holder col-md-12 col-lg-12">
										<div class="qodef-field-section">
											<div class="qodef-field-desc">
												<h3 class="qodef-title qodef-field-title">
													<?php
														echo esc_html__( 'Title', 'qode-product-extra-options-for-woocommerce' );
													?>
												</h3>
												<p class="qodef-description qodef-field-description">
													<?php
														esc_html_e( 'Title to show before the options', 'qode-product-extra-options-for-woocommerce' );
													?>
												</p>
											</div>
											<div class="qodef-field-content">
												<div class="qodef-field-wrapper">
													<div class="qodef-additional-options">
														<input type="text" name="addon_title" id="addon-title" class="qodef-field qodef-input" value="<?php echo esc_attr( $addon->get_setting( 'title', '', false ) ); ?>">
														<?php
															// Hook to include additional options after addon title input option.
															do_action( 'qode_product_extra_options_for_woocommerce_admin_action_after_addon_title_input', $addon );
														?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- End option field -->

									<?php
										// Hook to include additional options after addon title option.
										do_action( 'qode_product_extra_options_for_woocommerce_admin_action_after_addon_title', $addon );
									?>

									<!-- Option field -->
									<div class="qodef-field-holder col-md-12 col-lg-12">
										<div class="qodef-field-section">
											<div class="qodef-field-desc">
												<h3 class="qodef-title qodef-field-title">
													<?php
													echo esc_html__( 'Description', 'qode-product-extra-options-for-woocommerce' );
													?>
												</h3>
												<p class="qodef-description qodef-field-description">
													<?php
													echo esc_html__( 'Description to show before the options', 'qode-product-extra-options-for-woocommerce' );
													?>
												</p>
											</div>
											<div class="qodef-field-content">
												<div class="qodef-field-wrapper">
													<textarea type="text" name="addon_description" id="addon-description" class="form-control qodef-field"><?php echo esc_attr( $addon->get_setting( 'description', '', false ) ); ?></textarea>
												</div>
											</div>
										</div>
									</div>
									<!-- End option field -->

									<div id="qodef-addon-options">
										<?php
										for ( $x = 0; $x < $options_total; $x++ ) :
											$addon_label = $addon->get_option( 'label', $x, '', false );
											if ( 'product' === $addon_type ) {
												$product_id = $addon->get_option( 'product', $x, '', false ) ? $addon->get_option( 'product', $x, '', false ) : '';
												if ( $product_id > 0 ) {
													$product = wc_get_product( $product_id );
													if ( $product instanceof WC_Product ) {
														$addon_label = $product->get_name();
													}
												}
											}
											?>
											<div class="qodef-option-item <?php echo 1 === $options_total ? 'qodef-open' : ''; ?>" data-index="<?php echo esc_attr( $x ); ?>">
												<div class="qodef-actions">
													<?php
													$actions = array(
														'move'      => array(
															'title'  => esc_html__( 'Move', 'qode-product-extra-options-for-woocommerce' ),
															'action' => 'move',
															'icon'   => 'drag',
															'url'    => 'javascript:void(0)',
														),
														'delete' => $delete_action,
													);
													qode_product_extra_options_for_woocommerce_get_action_buttons( $actions, true );
													?>
												</div>
												<div class="qodef-title">
													<?php qode_product_extra_options_for_woocommerce_render_svg_icon( 'chevron-down', 'qodef-icon' ); ?>
													<div class="qodef-addon-name">
														<h3 class="qodef-name">
															<?php echo esc_html( mb_strtoupper( $addon_name ) ) . ' - <span class="qodef-addon-label-text">' . esc_html( substr( $addon_label, 0, 60 ) ) . '</span>'; ?>
														</h3>
														<div class="qodef-additional-options">
															<div class="qodef-selected-by-default">
																<?php
																if (
																	in_array(
																		$addon_type,
																		array(
																			'checkbox',
																			'color',
																			'label',
																			'product',
																			'radio',
																			'select',
																		),
																		true
																	)
																) :
																	?>
																	<!-- Option field -->
																	<div class="qodef-checkbox-group-holder">
																		<div class="qodef-inline">
																			<?php
																			$is_default = $addon->get_option( 'default', $x, 'no', false ) === 'yes';
																			if ( 'new' === $addon_id && 'radio' === $addon_type ) {
																				$is_default = 'yes';
																			}
																			qode_product_extra_options_for_woocommerce_get_field(
																				array(
																					'id'    => 'option-default-' . $x,
																					'name'  => 'options[default][' . $x . ']',
																					'type'  => 'checkbox',
																					'class' => 'qodef-field selected-by-default-chbx checkbox',
																					'value' => $is_default,
																				),
																				true,
																				false
																			);
																			?>
																			<label for="option-default-<?php echo esc_attr( $x ); ?>" class="selected-by-default-chbx">
																				<span class="qodef-label-view"></span>
																				<span class="qodef-label-text"><?php esc_html_e( 'Selected by default', 'qode-product-extra-options-for-woocommerce' ); ?></span>
																			</label>
																		</div>
																	</div>
																	<!-- End option field -->
																<?php endif; ?>
															</div>
															<div class="enabled">
																<?php
																$enabled = $addon->get_option( 'addon_enabled', $x, 'yes', false );
																qode_product_extra_options_for_woocommerce_get_field(
																	array(
																		'id'      => 'addon-option-enabled-' . $x,
																		'name'    => 'options[addon_enabled][' . $x . ']',
																		'type'    => 'yesno-radio',
																		'value'   => $enabled,
																	),
																	true
																);
																?>
															</div>
														</div>
													</div>
												</div>
												<?php
												qode_product_extra_options_for_woocommerce_template_part(
													'product-add-ons',
													'addons/admin-pages/addon-types/template',
													$addon_type,
													array(
														'addon'      => $addon,
														'addon_type' => $addon_type,
														'x'          => $x,
													)
												);
												?>
											</div>
										<?php endfor; ?>
									</div>

									<div id="qodef-add-new-option" class="qodef-btn qodef-btn-solid">
										<?php
											echo esc_html( qode_product_extra_options_for_woocommerce_get_string_by_addon_type( 'add_new', $addon_type ) ) . ' ' . esc_html( mb_strtolower( $addon_name ) );
										?>
									</div>

									<!-- NEW OPTION TEMPLATE -->
									<!-- Add new option template for adding with JS - wp.template -->
									<script type="text/html" id="tmpl-qodef-new-option-template">
										<div class="qodef-option-item qodef-open" data-index={{{data.option_index}}}>
											<div class="qodef-actions">
												<?php
												$actions = array(
													'move' => array(
														'title'  => esc_html__( 'Move', 'qode-product-extra-options-for-woocommerce' ),
														'action' => 'move',
														'icon'   => 'drag',
														'url'    => 'javascript:void(0)',
													),
													'delete' => $delete_action,
												);
												qode_product_extra_options_for_woocommerce_get_action_buttons( $actions, true );

												$addon_label = $addon->get_option( 'label', '{{{data.option_index}}}', '', false );
												?>
											</div>
											<div class="qodef-title">
												<?php qode_product_extra_options_for_woocommerce_render_svg_icon( 'chevron-down', 'qodef-icon' ); ?>
												<div class="qodef-addon-name">
													<h3 class="qodef-name">
														<?php echo esc_html( mb_strtoupper( $addon_name ) ) . ' - <span class="qodef-addon-label-text">' . esc_html( substr( $addon_label, 0, 60 ) ) . '</span>'; ?>
													</h3>
													<div class="qodef-additional-options">
														<div class="qodef-selected-by-default">
															<?php
															if (
															in_array(
																$addon_type,
																array(
																	'checkbox',
																	'color',
																	'label',
																	'product',
																	'radio',
																	'select',
																),
																true
															)
															) :
																?>
																<!-- Option field -->
																<div class="qodef-checkbox-group-holder">
																	<div class="qodef-inline">
																		<?php
																		$is_default = $addon->get_option( 'default', '{{{data.option_index}}}', 'no', false ) === 'yes';

																		if ( 'new' === $addon_id && 'radio' === $addon_type ) {
																			if ( ! isset( $_SESSION['qodef_run_once_check'] ) ) {
																				$_SESSION['qodef_run_once_check'] = 1;
																			} else {
																				// check only first check box when adding new.
																				$is_default = 'yes';
																			}
																		}
																		qode_product_extra_options_for_woocommerce_get_field(
																			array(
																				'id'    => 'option-default-{{{data.option_index}}}',
																				'name'  => 'options[default][{{{data.option_index}}}]',
																				'type'  => 'checkbox',
																				'class' => 'qodef-field selected-by-default-chbx checkbox',
																				'value' => $is_default,
																			),
																			true,
																			false
																		);
																		?>
																		<label for="option-default-<?php echo esc_attr( '{{{data.option_index}}}' ); ?>" class="selected-by-default-chbx">
																			<span class="qodef-label-view"></span>
																			<span class="qodef-label-text"><?php esc_html_e( 'Selected by default', 'qode-product-extra-options-for-woocommerce' ); ?></span>
																		</label>
																	</div>
																</div>
																<!-- End option field -->
															<?php endif; ?>
														</div>
														<div class="enabled">
															<?php
															$enabled = $addon->get_option( 'addon_enabled', '{{{data.option_index}}}', 'yes', false );
															qode_product_extra_options_for_woocommerce_get_field(
																array(
																	'id'      => 'addon-option-enabled-{{{data.option_index}}}',
																	'name'    => 'options[addon_enabled][{{{data.option_index}}}]',
																	'type'    => 'yesno-radio',
																	'value'   => $enabled,
																),
																true
															);
															?>
														</div>
													</div>
												</div>
											</div>
											<?php
											$new_option = true;
											qode_product_extra_options_for_woocommerce_template_part(
												'product-add-ons',
												'addons/admin-pages/addon-types/template',
												$addon_type,
												array(
													'addon' => $addon,
													'addon_type' => $addon_type,
													'x' => '{{{data.option_index}}}',
												)
											);
											?>
										</div>
									</script>
									<!-- NEW OPTION TEMPLATE -->

								<?php endif; ?>
							</div>

							<?php
							// Different setting are displayed depending on premium version.
							qode_product_extra_options_for_woocommerce_template_part(
								'product-add-ons',
								'addons/admin-pages/addons-view/templates/template',
								'addon-display-settings',
								array(
									'addon'      => $addon,
									'addon_id'   => $addon_id,
									'addon_type' => $addon_type,
									'block_id'   => $block_id,
									'block'      => $block,
								)
							);
							qode_product_extra_options_for_woocommerce_template_part(
								'product-add-ons',
								'addons/admin-pages/addons-view/templates/template',
								'addon-conditional-logic',
								array(
									'addon'      => $addon,
									'addon_id'   => $addon_id,
									'addon_type' => $addon_type,
									'block_id'   => $block_id,
									'block'      => $block,
								)
							);
							// Different setting are displayed depending on premium version.
							qode_product_extra_options_for_woocommerce_template_part(
								'product-add-ons',
								'addons/admin-pages/addons-view/templates/template',
								'addon-advanced-settings',
								array(
									'addon'      => $addon,
									'addon_id'   => $addon_id,
									'addon_type' => $addon_type,
									'block_id'   => $block_id,
									'block'      => $block,
								)
							);
							?>
						</div><!-- #options-container -->
					</div><!-- #options-editor-radio -->
					<input type="hidden" name="qode_product_extra_options_for_woocommerce_action" value="save-addon">
					<input type="hidden" name="addon_id" value="<?php echo esc_attr( $addon_id ); ?>">
					<input type="hidden" name="addon_type" value="<?php echo esc_attr( $addon_type ); ?>">
					<input type="hidden" name="block_id" value="<?php echo esc_attr( $block_id ); ?>">
					<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'qode_product_extra_options_for_woocommerce_admin' ) ); ?>">
					<?php
						$block_name = ! empty( $block->get_name() ) ? $block->get_name() : ( isset( $_REQUEST['block_name'] ) ? sanitize_key( $_REQUEST['block_name'] ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.EscapeOutput.OutputNotEscaped;
					?>
					<input type="hidden" name="block_name" value="<?php echo esc_attr( $block_name ); ?>">
					<?php
						$block_priority = ! empty( $block->get_priority() ) ? $block->get_priority() : ( isset( $_REQUEST['block_priority'] ) ? sanitize_key( $_REQUEST['block_priority'] ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.EscapeOutput.OutputNotEscaped;
					?>
					<input type="hidden" name="block_priority" value="<?php echo esc_attr( $block_priority ); ?>">
					<?php
						$block_rule_show_in = ! empty( $block->get_rule( 'show_in', '' ) ) ? $block->get_rule( 'show_in' ) : ( isset( $_REQUEST['block_rule_show_in'] ) ? sanitize_key( $_REQUEST['block_rule_show_in'] ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
					<input type="hidden" name="block_rule_show_in" value="<?php echo esc_attr( $block_rule_show_in ); ?>">
					<?php
						$block_rule_show_in_products = ! empty( $block->get_rule( 'show_in_products', '' ) ) ? esc_attr( wp_json_encode( $block->get_rule( 'show_in_products' ) ) ) : ( isset( $_REQUEST['block_rule_show_in_products'] ) ? esc_attr( wp_json_encode( sanitize_text_field( wp_unslash( $_REQUEST['block_rule_show_in_products'] ) ) ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
					?>
					<input type="hidden" name="block_rule_show_in_products" value="<?php echo esc_attr( $block_rule_show_in_products ); ?>">
					<?php
						$block_rule_show_in_categories = ! empty( $block->get_rule( 'show_in_categories', '' ) ) ? esc_attr( wp_json_encode( $block->get_rule( 'show_in_categories' ) ) ) : ( isset( $_REQUEST['block_rule_show_in_categories'] ) ? esc_attr( wp_json_encode( sanitize_text_field( wp_unslash( $_REQUEST['block_rule_show_in_categories'] ) ) ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
					?>
					<input type="hidden" name="block_rule_show_in_categories" value="<?php echo esc_attr( $block_rule_show_in_categories ); ?>">
					<?php
						$block_rule_exclude_products = ! empty( $block->get_rule( 'exclude_products', '' ) ) ? $block->get_rule( 'exclude_products' ) : ( isset( $_REQUEST['block_rule_exclude_products'] ) ? sanitize_key( $_REQUEST['block_rule_exclude_products'] ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
					<input type="hidden" name="block_rule_exclude_products" value="<?php echo esc_attr( $block_rule_exclude_products ); ?>">
					<?php
						$block_rule_exclude_products_products = ! empty( $block->get_rule( 'exclude_products_products', '' ) ) ? esc_attr( wp_json_encode( $block->get_rule( 'exclude_products_products' ) ) ) : ( isset( $_REQUEST['block_rule_exclude_products_products'] ) ? esc_attr( wp_json_encode( sanitize_text_field( wp_unslash( $_REQUEST['block_rule_exclude_products_products'] ) ) ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
					?>
					<input type="hidden" name="block_rule_exclude_products_products" value="<?php echo esc_attr( $block_rule_exclude_products_products ); ?>">
					<?php
						$block_rule_exclude_products_categories = ! empty( $block->get_rule( 'exclude_products_categories', '' ) ) ? esc_attr( wp_json_encode( $block->get_rule( 'exclude_products_categories' ) ) ) : ( isset( $_REQUEST['block_rule_exclude_products_categories'] ) ? esc_attr( wp_json_encode( sanitize_text_field( wp_unslash( $_REQUEST['block_rule_exclude_products_categories'] ) ) ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
					?>
					<input type="hidden" name="block_rule_exclude_products_categories" value="<?php echo esc_attr( $block_rule_exclude_products_categories ); ?>">
					<?php
						$block_rule_show_to = ! empty( $block->get_rule( 'show_to', '' ) ) ? $block->get_rule( 'show_to' ) : ( isset( $_REQUEST['block_rule_show_to'] ) && ! empty( $_REQUEST['block_rule_show_to'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['block_rule_show_to'] ) ) : 'all' ); // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
					<input type="hidden" name="block_rule_show_to" value="<?php echo esc_attr( $block_rule_show_to ); ?>">
					<?php
						$block_rule_show_to_user_roles = ! empty( $block->get_rule( 'show_to_user_roles', '' ) ) ? esc_attr( wp_json_encode( $block->get_rule( 'show_to_user_roles' ) ) ) : ( isset( $_REQUEST['block_rule_show_to_user_roles'] ) ? esc_attr( wp_json_encode( sanitize_text_field( wp_unslash( $_REQUEST['block_rule_show_to_user_roles'] ) ) ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
					?>
					<input type="hidden" name="block_rule_show_to_user_roles" value="<?php echo esc_attr( $block_rule_show_to_user_roles ); ?>">

					<div id="qodef-addon-editor-buttons">
						<button type="submit" class="qodef-btn qodef-btn-solid qodef-button-save submit button-primary">
							<?php
							echo esc_html__( 'Save', 'qode-product-extra-options-for-woocommerce' );
							?>
						</button>
						<button type="reset" class="qodef-btn qodef-btn-outlined qodef-button-cancel cancel button-secondary">
							<?php
								echo esc_html__( 'Cancel', 'qode-product-extra-options-for-woocommerce' );
							?>
						</button>
					</div>
				</form>

			<?php elseif ( 'new' === $addon_id ) : ?>

				<div id="qodef-types">
					<h3>
						<?php
						echo esc_html__( 'Add HTML element', 'qode-product-extra-options-for-woocommerce' );
						?>
					</h3>
					<div class="qodef-types-list">
						<?php foreach ( Qode_Product_Extra_Options_For_WooCommerce_Main()->get_html_types() as $key => $html_type ) : ?>

							<?php
							$html_url = add_query_arg(
								array(
									'page'               => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
									'block_id'           => $block_id,
									'action'             => 'edit',
									'addon_id'           => 'new',
									'addon_type'         => $html_type['slug'],
									'block_name'         => isset( $_REQUEST['block_name'] ) && ! empty( $_REQUEST['block_name'] ) ? sanitize_key( $_REQUEST['block_name'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
									'block_priority'     => isset( $_REQUEST['block_priority'] ) && ! empty( $_REQUEST['block_priority'] ) ? sanitize_key( $_REQUEST['block_priority'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification.Recommended
									'block_rule_show_in' => isset( $_REQUEST['block_rule_show_in'] ) && ! empty( $_REQUEST['block_rule_show_in'] ) ? sanitize_key( $_REQUEST['block_rule_show_in'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
									'block_rule_show_in_products' => isset( $_REQUEST['block_rule_show_in_products'] ) && ! empty( $_REQUEST['block_rule_show_in_products'] ) ? sanitize_key( $_REQUEST['block_rule_show_in_products'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
									'block_rule_show_in_categories' => isset( $_REQUEST['block_rule_show_in_categories'] ) && ! empty( $_REQUEST['block_rule_show_in_categories'] ) ? sanitize_key( $_REQUEST['block_rule_show_in_categories'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
									'block_rule_exclude_products' => isset( $_REQUEST['block_rule_exclude_products'] ) && ! empty( $_REQUEST['block_rule_exclude_products'] ) ? sanitize_key( $_REQUEST['block_rule_exclude_products'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
									'block_rule_exclude_products_products' => isset( $_REQUEST['block_rule_exclude_products_products'] ) && ! empty( $_REQUEST['block_rule_exclude_products_products'] ) ? sanitize_key( $_REQUEST['block_rule_exclude_products_products'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
									'block_rule_exclude_products_categories' => isset( $_REQUEST['block_rule_exclude_products_categories'] ) && ! empty( $_REQUEST['block_rule_exclude_products_categories'] ) ? sanitize_key( $_REQUEST['block_rule_exclude_products_categories'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
									'block_rule_show_to' => isset( $_REQUEST['block_rule_show_to'] ) && ! empty( $_REQUEST['block_rule_show_to'] ) ? sanitize_key( $_REQUEST['block_rule_show_to'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
									'block_rule_show_to_user_roles' => isset( $_REQUEST['block_rule_show_to_user_roles'] ) && ! empty( $_REQUEST['block_rule_show_to_user_roles'] ) ? sanitize_key( $_REQUEST['block_rule_show_to_user_roles'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
								),
								admin_url( '/admin.php' )
							);
							?>


							<a class="qodef-type" href="<?php echo esc_attr( $html_url ); ?>">
								<?php qode_product_extra_options_for_woocommerce_render_svg_icon( $html_type['slug'], 'qodef-icon qodef-icon-' . esc_attr( $html_type['slug'] ) ); ?>
								<span class="qodef-type-heading qodef-h4"><?php echo esc_html( $html_type['name'] ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
					<h3>
						<?php
							echo esc_html__( 'Add option for the user', 'qode-product-extra-options-for-woocommerce' );
						?>
					</h3>
					<div class="qodef-types-list">
						<?php
						$available_addon_types = Qode_Product_Extra_Options_For_WooCommerce_Main()->get_available_addon_types();
						foreach ( $addons_type as $key => $addon_type ) :
							if ( str_starts_with( $addon_type['slug'], 'html' ) ) {
								continue;
							}
							$class = 'qodef-disabled';
							$url   = '#';
							if ( in_array( $addon_type['slug'], $available_addon_types, true ) ) {
								$class = 'qodef-enabled';

								$url = add_query_arg(
									array(
										'page'           => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
										'block_id'       => $block_id,
										'action'         => 'edit',
										'addon_id'       => 'new',
										'addon_type'     => $addon_type['slug'],
										'block_name'     => isset( $_REQUEST['block_name'] ) && ! empty( $_REQUEST['block_name'] ) ? sanitize_key( $_REQUEST['block_name'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
										'block_priority' => isset( $_REQUEST['block_priority'] ) && ! empty( $_REQUEST['block_priority'] ) ? sanitize_key( $_REQUEST['block_priority'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
										'block_rule_show_in' => isset( $_REQUEST['block_rule_show_in'] ) && ! empty( $_REQUEST['block_rule_show_in'] ) ? sanitize_key( $_REQUEST['block_rule_show_in'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
										'block_rule_show_in_products' => isset( $_REQUEST['block_rule_show_in_products'] ) && ! empty( $_REQUEST['block_rule_show_in_products'] ) ? sanitize_key( $_REQUEST['block_rule_show_in_products'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
										'block_rule_show_in_categories' => isset( $_REQUEST['block_rule_show_in_categories'] ) && ! empty( $_REQUEST['block_rule_show_in_categories'] ) ? sanitize_key( $_REQUEST['block_rule_show_in_categories'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
										'block_rule_exclude_products' => isset( $_REQUEST['block_rule_exclude_products'] ) && ! empty( $_REQUEST['block_rule_exclude_products'] ) ? sanitize_key( $_REQUEST['block_rule_exclude_products'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
										'block_rule_exclude_products_products' => isset( $_REQUEST['block_rule_exclude_products_products'] ) && ! empty( $_REQUEST['block_rule_exclude_products_products'] ) ? sanitize_key( $_REQUEST['block_rule_exclude_products_products'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
										'block_rule_exclude_products_categories' => isset( $_REQUEST['block_rule_exclude_products_categories'] ) && ! empty( $_REQUEST['block_rule_exclude_products_categories'] ) ? sanitize_key( $_REQUEST['block_rule_exclude_products_categories'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
										'block_rule_show_to' => isset( $_REQUEST['block_rule_show_to'] ) && ! empty( $_REQUEST['block_rule_show_to'] ) ? sanitize_key( $_REQUEST['block_rule_show_to'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
										'block_rule_show_to_user_roles' => isset( $_REQUEST['block_rule_show_to_user_roles'] ) && ! empty( $_REQUEST['block_rule_show_to_user_roles'] ) ? sanitize_key( $_REQUEST['block_rule_show_to_user_roles'] ) : '', // phpcs:ignore WordPress.Security.NonceVerification
									),
									admin_url( '/admin.php' )
								);

							}
							?>
							<a class="qodef-type <?php echo esc_attr( $class ); ?>" href="<?php echo esc_attr( $url ); ?>" <?php echo 'qodef-disabled' === $class ? 'onclick="return false;"' : ''; ?>>
								<?php qode_product_extra_options_for_woocommerce_render_svg_icon( $addon_type['slug'], 'qodef-icon qodef-icon-' . esc_attr( $addon_type['slug'] ) ); ?>
								<?php qode_product_extra_options_for_woocommerce_render_svg_icon( 'lock', 'qodef-icon qodef-icon-lock qodef-premium-badge' ); ?>
								<span class="qodef-type-heading qodef-h4"><?php echo esc_html( $addon_type['name'] ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
