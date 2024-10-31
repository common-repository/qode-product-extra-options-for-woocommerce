<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Block Editor Template
 *
 * @var $block_id
 */

$block = qode_product_extra_options_for_woocommerce_instance_class(
	'Qode_Product_Extra_Options_For_WooCommerce_Blocks',
	array(
		'id' => $block_id,
	)
);

$nonce = wp_create_nonce( 'qode_product_extra_options_for_woocommerce_action' );

?>
<!-- block editor template start -->
<div id="qodef-panel-block" class="qodef-panel-block-wrapper" data-block-id="
<?php
	echo esc_attr( $block_id ?? '' );
?>
">

	<?php
	qode_product_extra_options_for_woocommerce_framework_template_part( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH . '/inc', 'admin-pages', 'templates/header-with-support', '', array( 'menu_title' => esc_html__( 'Product Extra Options for WooCommerce - Edit Block', 'qode-product-extra-options-for-woocommerce' ) ) );

	$params['banner_enabled'] = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_options_upgrade_banner', true );

	qode_product_extra_options_for_woocommerce_framework_template_part( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH, 'inc/common', 'modules/admin/templates/content-banner', '', $params );
	?>

	<div class="qodef-block-list-actions">
		<a href="admin.php?page=<?php echo esc_attr( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ); ?>" class="qodef-btn qodef-btn-solid qodef-back-to-block-list"><?php echo esc_html__( 'BACK TO BLOCK EDITOR LIST', 'qode-product-extra-options-for-woocommerce' ); ?></a>
		<a href="#qodef-addons-tabs" id="qodef-scroll-to-addons-options" class="qodef-btn qodef-btn-outlined qodef-selected">
			<?php
			echo esc_html__( 'Scroll To Options', 'qode-product-extra-options-for-woocommerce' );
			?>
		</a>
	</div>

	<form action="admin.php?page=<?php echo esc_attr( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ); ?>&block_id=<?php echo esc_attr( $block_id ); ?>&action=edit" method="post" id="block">
		<div class="row">
			<input type="hidden" name="nonce" value="<?php echo esc_attr( $nonce ); ?>">

			<!-- Option field -->
			<div class="qodef-field-holder col-md-12 col-lg-12">
				<div class="qodef-field-section">
					<div class="qodef-field-desc">
						<h3 class="qodef-title qodef-field-title">
							<?php
							echo esc_html__( 'Block Name', 'qode-product-extra-options-for-woocommerce' );
							?>
						</h3>
						<p class="qodef-description qodef-field-description">
							<?php
								echo esc_html__( 'Input a name for this Block of Options', 'qode-product-extra-options-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="qodef-field-content">
						<?php

						$block_name = '';

						if ( ! empty( $block->get_name() ) ) {
							$block_name = $block->get_name();
						} elseif ( isset( $_REQUEST['block_name'] ) && ! empty( $_REQUEST['block_name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
							$block_name = sanitize_key( $_REQUEST['block_name'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
						}

						$block_name_field = array(
							'id'    => 'block-name',
							'type'  => 'text',
							'name'  => 'block_name',
							'value' => $block_name,
						);
						qode_product_extra_options_for_woocommerce_get_field( $block_name_field, true )
						?>
					</div>
				</div>
			</div>
			<!-- End option field -->

			<!-- Option field -->
			<div class="qodef-field-holder col-md-12 col-lg-12">
				<div class="qodef-field-section">
					<div class="qodef-field-desc">
						<h3 class="qodef-title qodef-field-title">
							<?php
								echo esc_html__( 'Block Priority Level', 'qode-product-extra-options-for-woocommerce' );
							?>
						</h3>
						<p class="qodef-description qodef-field-description">
							<?php
								echo esc_html__( 'Set the priority level assigned to this block. This will allow you to arrange the order of different Blocks with Options. 1 is the highest priority level', 'qode-product-extra-options-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="qodef-field-content">
						<?php

						$block_priority = 1;

						if ( ! empty( $block->get_priority() ) ) {
							$block_priority = $block->get_priority();
						} elseif ( isset( $_REQUEST['block_priority'] ) && ! empty( $_REQUEST['block_priority'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
							$block_priority = sanitize_key( $_REQUEST['block_priority'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
						}

						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'    => 'block-priority',
								'type'  => 'number',
								'name'  => 'block_priority',
								'value' => esc_attr( round( $block_priority ) ),
								'min'   => 0,
								'max'   => 9999,
							),
							true
						)
						?>
					</div>
				</div>
			</div>
			<!-- End option field -->

			<!-- BLOCK RULES -->
			<?php
			qode_product_extra_options_for_woocommerce_template_part( 'product-add-ons', 'blocks/admin-pages/blocks-view/templates/template', 'block-rules', array( $block ) );
			?>

			<div id="qodef-addons-tabs" class="qodef-field-holder-full-width">
				<div class="qodef-field-holder col-md-12 col-lg-12">
					<div class="qodef-addons-tabs-wrapper-inner">
						<h2 class="qodef-title qodef-field-title">
							<?php
								echo esc_html__( 'Options', 'qode-product-extra-options-for-woocommerce' );
							?>
						</h2>
						<?php echo '<p class="qodef-addon-update-message">' . esc_html__( 'Addons updated', 'qode-product-extra-options-for-woocommerce' ) . '</p>'; ?>
						<?php wp_nonce_field( 'qodef-addon-update-nonce', 'qodef-addon-update-nonce' ); ?>
					</div>
				</div>
			</div>

			<div id="qodef-addons-tab" class="qodef-field-holder-full-width">
				<div class="qodef-field-holder-full-width">
					<div id="qodef-block-addons">
						<div id="qodef-block-addons-container">
							<ul id="qodef-sortable-addons-list">
								<?php
								$addons       = Qode_Product_Extra_Options_For_WooCommerce_Main()->db->get_addons_by_block_id( $block_id );
								$total_addons = count( $addons );

								if ( $total_addons > 0 ) :
									$addons = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_block_addons_admin', $addons );

									foreach ( $addons as $key => $addon ) :
										/**
										 * Addon class.
										 *
										 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
										 */

										if ( qode_product_extra_options_for_woocommerce_is_addon_type_available( $addon->get_type() ) ) :
											// Count of labels.
											$total_options = is_array( $addon->options ) && isset( array_values( $addon->options )[1] ) ? count( array_values( $addon->options )[1] ) : 0;
											?>
											<li id="qodef-addon-<?php echo esc_attr( $addon->get_id() ); ?>"
												data-id="<?php echo esc_attr( $addon->get_id() ); ?>" class="qodef-addon-element"
												data-priority="<?php echo esc_attr( ! empty( floatval( $addon->get_priority() ) ) ? floatval( $addon->get_priority() ) : floatval( $addon->get_id() ) ); ?>">
												<span class="qodef-field-holder col-md-12 col-lg-12">
													<span class="qodef-field-section">
														<span class="qodef-field-desc qodef-addon-editor-left">
															<span class="qodef-addon-icon qodef-icon-<?php echo esc_attr( $addon->get_type() ); ?>">
																<?php qode_product_extra_options_for_woocommerce_render_svg_icon( $addon->get_type() ); ?>
															</span>
															<span class="qodef-title qodef-field-title qodef-addon-name">
																<?php
																$option_url = add_query_arg(
																	array(
																		'page'       => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
																		'block_id'   => $block->get_id(),
																		'action'     => 'edit',
																		'addon_id'   => $addon->get_id(),
																		'addon_type' => $addon->get_type(),
																		'nonce'      => $nonce,
																	),
																	admin_url( '/admin.php' )
																);
																?>
																<a href="<?php echo esc_url( $option_url ); ?>">
																	<?php
																	$addon_title = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_title_on_editor', $addon->get_setting( 'title' ) ? $addon->get_setting( 'title' ) . ' - ' : '', $addon );

																	echo esc_html( $addon_title );
																	echo esc_html( Qode_Product_Extra_Options_For_WooCommerce_Main()->get_addon_name_by_slug( $addon->get_type() ) );

																	if ( strpos( $addon->get_type(), 'html' ) === false ) {
																		?>
																		<span class="qodef-addon-options">
																		<?php
																			echo ' (' . esc_html( $total_options ) . ' ';
																			echo 1 === $total_options ? esc_html_x( 'option', 'singular option on Add-on title. Ex: Date (1 option)', 'qode-product-extra-options-for-woocommerce' ) : esc_html_x( 'options', 'several options on Add-on title. Ex: Date (2 options)', 'qode-product-extra-options-for-woocommerce' );
																			echo ')';
																		?>
																		</span>
																		<?php
																	}
																	do_action( 'qode_product_extra_options_for_woocommerce_admin_action_after_block_title', $addon );
																	?>
																</a>
															</span>
														</span>

														<span class="qodef-field-content qodef-addon-editor-right">
															<span class="qodef-addon-onoff">
																<?php
																qode_product_extra_options_for_woocommerce_get_field(
																	array(
																		'id'          => 'qodef-active-addon-' . $addon->get_id(),
																		'type'        => 'onoff',
																		'value'       => '1' === $addon->get_visibility() ? 'yes' : 'no',
																	),
																	true
																);
																?>
															</span>
															<span class="qodef-addon-actions"">
																<?php
																$actions = array(
																	'edit'      => array(
																		'title'  => esc_html__( 'Edit', 'qode-product-extra-options-for-woocommerce' ),
																		'action' => 'edit',
																		'url'    => add_query_arg(
																			array(
																				'page'       => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
																				'block_id'   => $block->get_id(),
																				'action'     => 'edit',
																				'addon_id'   => $addon->get_id(),
																				'addon_type' => $addon->get_type(),
																				'nonce'      => $nonce,
																			),
																			admin_url( 'admin.php' )
																		),
																	),
																	'duplicate' => array(
																		'title'  => esc_html__( 'Duplicate', 'qode-product-extra-options-for-woocommerce' ),
																		'action' => 'duplicate',
																		'icon'   => 'clone',
																		'url'    => add_query_arg(
																			array(
																				'page'                          => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
																				'qode_product_extra_options_for_woocommerce_action' => 'duplicate-addon',
																				'block_id'                      => $block->get_id(),
																				'addon_id'                      => $addon->get_id(),
																				'nonce'                         => $nonce,
																			),
																			admin_url( 'admin.php' )
																		),
																	),
																	'move'      => array(
																		'title'  => esc_html__( 'Move', 'qode-product-extra-options-for-woocommerce' ),
																		'action' => 'move',
																		'icon'   => 'drag',
																		'url'    => 'javascript:void(0)',
																	),
																	'delete' => array(
																		'title'  => esc_html__( 'Delete', 'qode-product-extra-options-for-woocommerce' ),
																		'action' => 'delete',
																		'icon'   => 'trash',
																		'url'    => add_query_arg(
																			array(
																				'page'                          => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
																				'qode_product_extra_options_for_woocommerce_action' => 'remove-addon',
																				'block_id'                      => $block->get_id(),
																				'addon_id'                      => $addon->get_id(),
																				'nonce'                         => $nonce,
																			),
																			admin_url( 'admin.php' )
																		),
																		'confirm_data' => array(
																			'title'               => esc_html__( 'Confirm delete', 'qode-product-extra-options-for-woocommerce' ),
																			'message'             => esc_html__( 'Are you sure you want to delete this add-on?', 'qode-product-extra-options-for-woocommerce' ),
																			'confirm-button'      => esc_html__( 'Yes, delete', 'qode-product-extra-options-for-woocommerce' ),
																			'confirm-button-type' => 'delete',
																		),
																	),
																);

																qode_product_extra_options_for_woocommerce_get_action_buttons( $actions, true );
																?>
															</span>
														</span>
													</span>
												</span>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</ul>
							<div id="qodef-add-option" class="qodef-field-holder col-md-12 col-lg-12">
									<p class="start-new-option">
										<?php
										if ( 0 === $block->get_id() ) {
											echo esc_html__( 'Save Block Changes first then start adding Options to this Block!', 'qode-product-extra-options-for-woocommerce' );
										} elseif ( ! $total_addons > 0 && 0 !== $block->get_id() ) {
											echo esc_html__( 'Start adding your Options to this Block!', 'qode-product-extra-options-for-woocommerce' );
										}
										?>
									</p>
								<?php
									$submit_button_disabled = ( 0 === $block->get_id() ) ? 'disabled=disabled' : '';
								?>
									<input type="submit" name="add_options_after_save" class="qodef-btn qodef-btn-solid qodef-add-new-addon-button" <?php echo esc_attr( $submit_button_disabled ); ?> value="<?php echo esc_html__( 'Add option', 'qode-product-extra-options-for-woocommerce' ); ?>">
							</div>
						</div>
					</div>
				</div>
			</div>

			<input type="hidden" name="qode_product_extra_options_for_woocommerce_action" value="save-block">
			<input type="hidden" name="id" value="<?php echo esc_attr( $block_id ); ?>">
			<!-- QODE WooCommerce Multi Vendor Integration -->
			<?php
			// TODO premium version.
			// manage_option capability prevent to assign rules to specific vendor if the admin create the rules.
			if ( function_exists( 'qode_get_vendor' ) && ! current_user_can( 'manage_options' ) ) {
				$vendor = qode_get_vendor( 'current', 'user' );
				if ( $vendor->is_valid() ) {
					$vendor_id = $vendor->get_id();
					printf( '<input type="hidden" name="vendor_id" value="%1$s">', esc_attr( $vendor_id ) );
				}
			}
			?>
			<div id="qodef-save-button">
				<button name="save-block-button" class="qodef-btn qodef-btn-solid qodef-save-button"><?php echo esc_html__( 'Save Changes', 'qode-product-extra-options-for-woocommerce' ); ?></button>
			</div>
		</div>
	</form>

</div>
<!-- block editor template end -->
<?php
if ( isset( $_REQUEST['addon_id'] ) || isset( $_REQUEST['add_options_after_save'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	qode_product_extra_options_for_woocommerce_template_part(
		'product-add-ons',
		'addons/admin-pages/addons-view/templates/template',
		'addon-editor',
		array(
			'block_id' => $block_id,
			'block'    => $block,
		)
	);
}
?>
