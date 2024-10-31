<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_Frontend_Module' ) ) {
	class Qode_Product_Extra_Options_For_WooCommerce_Frontend_Module {
		private static $instance;

		public $single_product_attributes = array();

		public function __construct() {
			add_action( 'admin_footer', array( $this, 'add_date_rule_template_js' ) );

			// 5 is set to be same permission as Gutenberg plugin have.
			add_action( 'admin_head', array( $this, 'enqueue_framework_scripts' ), 5 );
			add_action( 'admin_head', array( $this, 'localize_admin_scripts' ) );
			add_filter( 'admin_body_class', array( $this, 'add_admin_body_classes' ) );

			// Refund order.
			add_action( 'woocommerce_order_refunded', array( $this, 'manage_refunded_product_type_addons' ), 10, 2 );
			add_action( 'woocommerce_restore_order_stock', array( $this, 'restore_addons_type_product_stock' ) );
			add_action( 'woocommerce_reduce_order_stock', array( $this, 'reduce_addons_type_product_stock' ) );

			$this->include_templates();

			// Update visibility for blocks in block list (enable/disable).
			add_action( 'wp_ajax_qode_product_extra_options_for_woocommerce_action_enable_disable_block', array( $this, 'enable_disable_block' ) );
			add_action( 'wp_ajax_nopriv_qode_product_extra_options_for_woocommerce_action_enable_disable_block', array( $this, 'enable_disable_block' ) );

			// Update block priority in block list.
			add_action( 'wp_ajax_qode_product_extra_options_for_woocommerce_action_sortable_blocks', array( $this, 'sortable_blocks' ) );
			add_action( 'wp_ajax_nopriv_qode_product_extra_options_for_woocommerce_action_sortable_blocks', array( $this, 'sortable_blocks' ) );

			// Update visibility for addons in addons list (enable/disable).
			add_action( 'wp_ajax_qode_product_extra_options_for_woocommerce_action_enable_disable_addon', array( $this, 'enable_disable_addon' ) );
			add_action( 'wp_ajax_nopriv_qode_product_extra_options_for_woocommerce_action_enable_disable_addon', array( $this, 'enable_disable_addon' ) );

			// Update addon order in addon list.
			add_action( 'wp_ajax_qode_product_extra_options_for_woocommerce_action_sortable_addons', array( $this, 'sortable_addons' ) );
			add_action( 'wp_ajax_nopriv_qode_product_extra_options_for_woocommerce_action_sortable_addons', array( $this, 'sortable_addons' ) );

			// Display custom product thumbnail in cart.
			if ( 'yes' === qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_show_image_in_cart' ) ) {
				add_filter( 'woocommerce_order_item_thumbnail', array( $this, 'order_item_thumbnail' ), 10, 2 );
				add_filter( 'woocommerce_admin_order_item_thumbnail', array( $this, 'admin_order_item_thumbnail' ), 10, 3 );
			}

			// Shortcodes.
			add_shortcode( 'qode_product_extra_options_for_woocommerce_show_options', array( $this, 'qode_product_extra_options_for_woocommerce_show_options_shortcode' ) );
			add_action( 'qode_product_extra_options_for_woocommerce_action_show_options_shortcode', array( $this, 'print_template_container' ) );

			add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hide_order_item_meta' ) );
		}

		/**
		 * Returns single instance of the class
		 *
		 * @return Qode_Product_Extra_Options_For_WooCommerce_Frontend_Module
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function enqueue_framework_scripts() {

			// check if page is blocks page.
			if ( isset( $_GET['page'] ) && strpos( sanitize_text_field( wp_unslash( $_GET['page'] ) ), QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ) !== false ) { // phpcs:ignore WordPress.Security.NonceVerification
				$scripts = new Qode_Product_Extra_Options_For_WooCommerce_Framework_Options();

				$scripts->enqueue_dashboard_framework_scripts();
			}
		}

		public function add_admin_body_classes( $classes ) {
			if ( isset( $_GET['page'] ) && strpos( sanitize_text_field( wp_unslash( $_GET['page'] ) ), QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ) !== false ) { // phpcs:ignore WordPress.Security.NonceVerification
				$classes = $classes . ' qodef-framework-admin';
				$classes = $classes . ' qodef-framework-admin-options';
			}

			return $classes;
		}

		/**
		 * Show Options Shortcode
		 *
		 * @return false|string
		 */
		public function qode_product_extra_options_for_woocommerce_show_options_shortcode() {
			ob_start();
			if ( is_product() ) {
				do_action( 'qode_product_extra_options_for_woocommerce_action_show_options_shortcode' );
			} else {
				echo '<strong>' . esc_html__( 'This is not a product page!', 'qode-product-extra-options-for-woocommerce' ) . '</strong>';
			}

			return ob_get_clean();
		}

		/**
		 * Add new date rule template for adding with JS - wp.template
		 *
		 * @return void
		 */
		public function add_date_rule_template_js() {
			?>
			<script type="text/html" id="tmpl-qodef-date-rule-template">
				<div class="qodef-rule">
					<div class="qodef-field-what qodef-field-wrapper">
						<?php
						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'      => 'date-rule-what-{{data.addon_id}}-{{data.option_id}}',
								'name'    => 'options[date_rule_what][{{data.addon_id}}][{{data.option_id}}]',
								'type'    => 'select',
								'value'   => 'days',
								'options' => array(
									'days'     => esc_html__( 'Days', 'qode-product-extra-options-for-woocommerce' ),
									'daysweek' => esc_html__( 'Days of the week', 'qode-product-extra-options-for-woocommerce' ),
									'months'   => esc_html__( 'Months', 'qode-product-extra-options-for-woocommerce' ),
									'years'    => esc_html__( 'Years', 'qode-product-extra-options-for-woocommerce' ),
								),
							),
							true,
							false
						);
						?>

						<a class="qodef-delete-rule" href="javascript: void(0)" rel="noopener noreferrer"><?php qode_product_extra_options_for_woocommerce_render_svg_icon( 'trash' ); ?></a>

					</div>

					<div class="qodef-field-days qodef-field-wrapper qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_rule_what][{{data.addon_id}}][{{data.option_id}}]":"days"' ); ?>}">
						<small>
							<?php
							echo esc_html__( 'specific day', 'qode-product-extra-options-for-woocommerce' );
							?>
						</small>
						<?php
						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'          => 'date-rule-value-days-{{data.addon_id}}-{{data.option_id}}',
								'name'        => 'options[date_rule_value_days][{{data.addon_id}}][{{data.option_id}}]',
								'type'        => 'datepicker',
								'value'       => '',
								'date_format' => 'yy-mm-dd',
							),
							true,
							false
						);
						?>
					</div>
					<div class="qodef-field-daysweek qodef-field-wrapper qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_rule_what][{{data.addon_id}}][{{data.option_id}}]":"daysweek"' ); ?>}">
						<small>
							<?php
							echo esc_html__( 'day(s) in week', 'qode-product-extra-options-for-woocommerce' );
							?>
						</small>
						<?php
						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'       => 'date-rule-value-daysweek-{{data.addon_id}}-{{data.option_id}}',
								'name'     => 'options[date_rule_value_daysweek][{{data.addon_id}}][{{data.option_id}}]',
								'type'     => 'select',
								'multiple' => true,
								'options'  => array(
									'1' => esc_html__( 'Monday', 'qode-product-extra-options-for-woocommerce' ),
									'2' => esc_html__( 'Tuesday', 'qode-product-extra-options-for-woocommerce' ),
									'3' => esc_html__( 'Wednesday', 'qode-product-extra-options-for-woocommerce' ),
									'4' => esc_html__( 'Thursday', 'qode-product-extra-options-for-woocommerce' ),
									'5' => esc_html__( 'Friday', 'qode-product-extra-options-for-woocommerce' ),
									'6' => esc_html__( 'Saturday', 'qode-product-extra-options-for-woocommerce' ),
									'0' => esc_html__( 'Sunday', 'qode-product-extra-options-for-woocommerce' ),
								),
								'value'    => '',
							),
							true,
							false
						);
						?>
					</div>

					<div class="qodef-field-months qodef-field-wrapper qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_rule_what][{{data.addon_id}}][{{data.option_id}}]":"months"' ); ?>}">
						<small>
							<?php
							echo esc_html__( 'month(s)', 'qode-product-extra-options-for-woocommerce' );
							?>
						</small>
						<?php
						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'       => 'date-rule-value-months-{{data.addon_id}}-{{data.option_id}}',
								'name'     => 'options[date_rule_value_months][{{data.addon_id}}][{{data.option_id}}]',
								'type'     => 'select',
								'multiple' => true,
								'options'  => qode_product_extra_options_for_woocommerce_get_select_type_options_pool( 'months', false ),
								'value'    => '',
							),
							true,
							false
						);
						?>
					</div>

					<div class="qodef-field-years qodef-field-wrapper qodef-dependency-holder qodef-hide-dependency-holder" data-show="{<?php echo esc_attr( '"options[date_rule_what][{{data.addon_id}}][{{data.option_id}}]":"years"' ); ?>}">
						<small>
							<?php
							echo esc_html__( 'year(s)', 'qode-product-extra-options-for-woocommerce' );
							?>
						</small>
						<?php
						$years = array();
						$datey = gmdate( 'Y' );
						for ( $yy = $datey; $yy < $datey + 10; $yy++ ) {
							$years[ $yy ] = $yy;
						}
						qode_product_extra_options_for_woocommerce_get_field(
							array(
								'id'       => 'date-rule-value-years{{data.addon_id}}-{{data.option_id}}',
								'name'     => 'options[date_rule_value_years][{{data.addon_id}}][{{data.option_id}}]',
								'type'     => 'select',
								'multiple' => true,
								'options'  => $years,
								'value'    => '',
							),
							true,
							false
						);
						?>
					</div>
				</div>
			</script>
			<?php
		}

		public function localize_admin_scripts() {
			$global = apply_filters(
				'qode_product_extra_options_for_woocommerce_filter_localize_admin_plugin_script',
				array(
					'i18n' => array(
						'selectOption'                => esc_html__( 'Select an add-on', 'qode-product-extra-options-for-woocommerce' ),
						'discountLabel'               => esc_html__( 'Discount', 'qode-product-extra-options-for-woocommerce' ),
						'optionCostLabel'             => esc_html__( 'Option cost', 'qode-product-extra-options-for-woocommerce' ),
						'blockNameRequired'           => esc_html__( 'Block name is required', 'qode-product-extra-options-for-woocommerce' ),
						'actionButtonConfirmFallback' => esc_html__( 'Are you sure you want to delete?', 'qode-product-extra-options-for-woocommerce' ),
					),
				)
			);

			wp_localize_script(
				'qode-product-extra-options-for-woocommerce-framework-script',
				'qpeofwAdminGlobal',
				$global
			);
		}

		/**
		 * Update block visibility with ajax
		 *
		 * @return void
		 */
		public function enable_disable_block() {
			check_ajax_referer( 'qodef-block-update-nonce', 'nonce' );

			global $wpdb;
			$block_id  = isset( $_POST['block_id'] ) ? floatval( $_POST['block_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$block_vis = isset( $_POST['block_vis'] ) ? floatval( $_POST['block_vis'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

			// Update db table.
			$table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;
			$data  = array( 'visibility' => $block_vis );
			$wpdb->update( $table, $data, array( 'id' => $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			wp_die();
		}

		/**
		 * Sort blocks list
		 *
		 * @return void
		 */
		public function sortable_blocks() {
			check_ajax_referer( 'qodef-block-update-nonce', 'nonce' );

			global $wpdb;

			$item_id    = isset( $_POST['item_id'] ) ? intval( $_POST['item_id'] ) : '';
			$moved_item = isset( $_POST['moved_item'] ) ? intval( $_POST['moved_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$prev_item  = isset( $_POST['prev_item'] ) ? intval( $_POST['prev_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$next_item  = isset( $_POST['next_item'] ) > 0 ? intval( $_POST['next_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

			$priority = 0;

			// $prev_item || $next_item to zero means that they doesn't exists or has already priority zero.
			if ( 0 == $prev_item && $next_item > 0 ) {
				// Get the value if higher than zero. If not, get zero.
				$priority = max( $next_item - 1, 0 );
			} elseif ( 0 == $next_item && $prev_item > 0 ) {
				$priority = $prev_item + 1;
			} elseif ( $prev_item > 0 && $next_item > 0 ) {
				$gap = $next_item - $prev_item;
				$med = floatval( $gap / 2 );
				// Get the value if below 1. If not, get 1.
				$med = min( $med, 1 );

				$priority = $prev_item + $med;
			}

			// Update db table.
			$table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;
			$data  = array( 'priority' => $priority );
			$wpdb->update( $table, $data, array( 'id' => $item_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

			$data_json = array(
				'itemID'       => $item_id,
				'itemPriority' => $priority,
			);

			wp_send_json_success( $data_json );
		}

		/**
		 * Update addon visibility with ajax
		 *
		 * @return void
		 */
		public function enable_disable_addon() {
			check_ajax_referer( 'qodef-addon-update-nonce', 'nonce' );

			global $wpdb;
			$addon_id  = isset( $_POST['addon_id'] ) ? intval( $_POST['addon_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$addon_vis = isset( $_POST['addon_vis'] ) ? intval( $_POST['addon_vis'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

			// Update db table.
			$table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS;
			$data  = array( 'visibility' => $addon_vis );
			$wpdb->update( $table, $data, array( 'id' => $addon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			wp_die();
		}

		/**
		 * Sort addons list
		 *
		 * @return void
		 */
		public function sortable_addons() {
			check_ajax_referer( 'qodef-addon-update-nonce', 'nonce' );

			global $wpdb;
			$moved_item = isset( $_POST['moved_item'] ) ? floatval( $_POST['moved_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$prev_item  = isset( $_POST['prev_item'] ) ? floatval( $_POST['prev_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$next_item  = isset( $_POST['next_item'] ) ? floatval( $_POST['next_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

			$priority = 0;

			// $prev_item || $next_item to zero means that they doesn't exists or has already priority zero.
			if ( 0 == $prev_item && $next_item > 0 ) {
				// Get the value if higher than zero. If not, get zero.
				$priority = max( $next_item - 1, 0 );
			} elseif ( 0 == $next_item && $prev_item > 0 ) {
				$priority = $prev_item + 1;
			} elseif ( $prev_item > 0 && $next_item > 0 ) {
				$gap = $next_item - $prev_item;
				$med = floatval( $gap / 2 );
				// Get the value if below 1. If not, get 1.
				$med = min( $med, 1 );

				$priority = $prev_item + $med;
			}

			// Update db table.
			$table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS;
			$data  = array( 'priority' => $priority );
			$wpdb->update( $table, $data, array( 'id' => $moved_item ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

			echo esc_attr( $moved_item . '-' . $priority );

			wp_die();
		}

		/**
		 * Manage the re-stock on the product type addons refund
		 *
		 * @param int $order_id order ID.
		 * @param int $refund_id refund ID.
		 *
		 * @return void
		 * @throws Exception
		 */
		public function manage_refunded_product_type_addons( $order_id, $refund_id ) {

			$refund_order = wc_get_order( $refund_id );
			$order        = wc_get_order( $order_id );

			$refunded_items = $refund_order->get_items();

			if ( empty( $refunded_items ) ) {
				$refunded_items = $order->get_items();
			}

			foreach ( $refunded_items as $item_id => $item ) {

				$main_item_id = $item->get_meta( '_refunded_item_id', true );
				$item_id      = ! empty( $main_item_id ) ? $main_item_id : $item_id;

				$meta_data     = wc_get_order_item_meta( $item_id, '_qode_product_extra_options_for_woocommerce_meta_data', true );
				$quantity_data = wc_get_order_item_meta( $item_id, '_qpeofw_product_addon_qty', true );

				if ( $meta_data && is_array( $meta_data ) ) {
					foreach ( $meta_data as $index => $option ) {
						foreach ( $option as $key => $value ) {
							if ( $key && '' !== $value ) {
								if ( is_string( $value ) ) {
									$value   = stripslashes( $value );
									$explode = explode( '-', $value );

									if ( isset( $explode[0] ) && 'product' === $explode[0] ) {
										$product_id   = $explode[1];
										$product      = wc_get_product( $product_id );
										$product_name = $product instanceof WC_Product ? $product->get_title() : '';
										$quantity     = $quantity_data[ $key ];
										wc_update_product_stock( $product_id, $quantity, 'increase' );
										// translators: (ADMIN) Message when restock the Product quantity due to order cancelled.
										$order->add_order_note( __( 'Stock levels increased for add-ons type product:', 'qode-product-extra-options-for-woocommerce' ) . ' ' . $product_name );
									}
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Manage the re-stock on the product type addons when order is cancelled
		 *
		 * @param WC_Order $order Order.
		 *
		 * @return void
		 * @throws Exception
		 */
		public function restore_addons_type_product_stock( $order ) {

			if ( $order && $order instanceof WC_Order ) {
				$items = $order->get_items();
				foreach ( $items as $item_id => $item ) {
					$meta_data = wc_get_order_item_meta( $item_id, '_qode_product_extra_options_for_woocommerce_meta_data', true );
					if ( $meta_data && is_array( $meta_data ) ) {
						foreach ( $meta_data as $index => $option ) {
							foreach ( $option as $key => $value ) {
								if ( $key && '' !== $value ) {
									if ( is_string( $value ) ) {
										$value   = stripslashes( $value );
										$explode = explode( '-', $value );

										if ( isset( $explode[0] ) && 'product' === $explode[0] ) {
											$quantity_data = wc_get_order_item_meta( $item_id, '_qpeofw_product_addon_qty', true );
											$product_id    = $explode[1];
											$product       = wc_get_product( $product_id );
											$product_name  = $product instanceof WC_Product ? $product->get_title() : '';
											$quantity      = $quantity_data[ $key ];
											$stock         = wc_update_product_stock( $product_id, $quantity, 'increase' );
											// translators: (ADMIN) Message when restock the Product quantity due to order cancelled.
											$order->add_order_note( __( 'Stock levels increased for add-ons type product:', 'qode-product-extra-options-for-woocommerce' ) . ' ' . $product_name );
										}
									}
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Manage the reduce on the product type addons when order is completed
		 *
		 * @param WC_Order $order Order.
		 *
		 * @return void
		 * @throws Exception
		 */
		public function reduce_addons_type_product_stock( $order ) {
			if ( $order && $order instanceof WC_Order ) {
				$items = $order->get_items();
				foreach ( $items as $item_id => $item ) {
					$meta_data = wc_get_order_item_meta( $item_id, '_qode_product_extra_options_for_woocommerce_meta_data', true );
					if ( $meta_data && is_array( $meta_data ) ) {
						foreach ( $meta_data as $index => $option ) {
							foreach ( $option as $key => $value ) {
								if ( $key && '' !== $value ) {
									if ( is_string( $value ) ) {
										$value   = stripslashes( $value );
										$explode = explode( '-', $value );

										if ( isset( $explode[0] ) && 'product' === $explode[0] ) {
											$quantity_data = wc_get_order_item_meta( $item_id, '_qpeofw_product_addon_qty', true );
											$product_id    = $explode[1];
											$quantity      = $quantity_data[ $key ];
											$stock         = wc_update_product_stock( $product_id, $quantity, 'decrease' );
											// translators: (ADMIN) Message added to order notes when add-on type Product has stock.
											$order->add_order_note( __( 'Stock levels reduced for addons type product:', 'qode-product-extra-options-for-woocommerce' ) . ' ' . $product_id );
										}
									}
								}
							}
						}
					}
				}
			}
		}

		/**
		 * Hide order item metas
		 *
		 * @param array $hidden_meta The hidden item metas.
		 *
		 * @return mixed
		 */
		public function hide_order_item_meta( $hidden_meta ) {
			$hidden_meta[] = '_qode_product_extra_options_for_woocommerce_product_img';

			return $hidden_meta;
		}

		/**
		 * Change product image in dashboard if replaced by add-ons
		 *
		 * @param string                $image The image.
		 * @param int                   $item_id The item id.
		 * @param WC_Order_Item_Product $item The item object.
		 * @return string
		 */
		public function admin_order_item_thumbnail( $image, $item_id, $item ) {
			return $this->order_item_thumbnail( $image, $item );
		}

		/**
		 * Change product image in order if replaced by add-ons
		 *
		 * @param string                $image The image.
		 * @param WC_Order_Item_Product $item The item object.
		 * @return string
		 */
		public function order_item_thumbnail( $image, $item ) {
			if ( $item instanceof WC_Order_Item_Product ) {
				$qpeofw_image = $item->get_meta( '_qode_product_extra_options_for_woocommerce_product_img' );

				if ( ! empty( $qpeofw_image ) ) {
					$image = wp_get_attachment_image( $qpeofw_image );
				}
			}

			return $image;
		}

		public function include_templates() {
			$blocks_single_product_position = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_single_options_position' );

			// Print Options.
			if ( 'after-add-to-cart' === $blocks_single_product_position ) {
				add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'print_template_container' ) );
				// Default (before-add-to-cart).
			} else {
				add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'print_template_container' ) );
			}

			// TODO: add printing when gift cards plugin is finished.
			add_action( 'qode_gift_cards_template_before_add_to_cart_button', array( $this, 'print_template_container' ) );
		}

		/**
		 * Print template-container
		 */
		public function print_template_container() {
			$product = qode_product_extra_options_for_woocommerce_get_global_product();

			$not_allowed_product_types = array( 'grouped' );

			if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_allowed_product_types', true, $not_allowed_product_types ) && in_array( $product->get_type(), $not_allowed_product_types, true ) ) {
				return;
			}

			wc_get_template(
				'/template-container.php',
				array(
					'instance' => $this,
					'product'  => $product,
				),
				'templates/template',
				QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates'
			);
		}

		/**
		 * Print blocks
		 */
		public function print_blocks() {
			if ( isset( $_REQUEST['qodef-blocks-cart-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['qodef-blocks-cart-nonce'] ) ), 'qodef-blocks-cart-nonce' ) ) {
				$product   = qode_product_extra_options_for_woocommerce_get_global_product();
				$variation = qode_product_extra_options_for_woocommerce_get_global_variation();

				$currency = isset( $_POST['currency'] ) ? sanitize_text_field( wp_unslash( $_POST['currency'] ) ) : false;

				if ( $product ) {

					$show_total_price_box = false;

					// TODO filter for wpml compatibility.
					$_product_id      = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_original_product_id', $product->get_id() );
					$product_cats_ids = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
					$_variation_id    = '';

					if ( $variation instanceof WC_Product_Variation ) {
						$_variation_id = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_original_product_id', $variation->get_id() );
					}

					$blocks_product_price = floatval( $_POST['price'] ?? ( $variation ? $variation->get_price() : $product->get_price() ) );
					$blocks_product_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_blocks_product_price', $blocks_product_price, $product, $variation );
					$blocks_product_price = $blocks_product_price + ( ( $blocks_product_price / 100 ) * qode_product_extra_options_for_woocommerce_get_tax_rate() );

					$this->current_product_price = $blocks_product_price;

					// Style options.
					$style_addon_titles = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_block_heading' );

					$total_price_box = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_single_options_total_price_box' );

					$blocks = Qode_Product_Extra_Options_For_WooCommerce_Db()->qode_product_extra_options_for_woocommerce_get_blocks_by_product( $_product_id, $_variation_id, 'yes' );

					echo '<input type="hidden" id="qpeofw_product_id" name="qpeofw_product_id" value="' . esc_attr( $product->get_id() ) . '">';
					echo '<input type="hidden" id="qpeofw_product_img" name="qpeofw_product_img" value="">';
					echo '<input type="hidden" id="qpeofw_is_single" name="qpeofw_is_single" value="1">';

					/**
					 * Action before printing all the blocks
					 */
					do_action( 'qode_product_extra_options_for_woocommerce_before_blocks' );

					foreach ( $blocks as $key => $block_id ) {

						if ( ! apply_filters( 'qode_product_extra_options_for_woocommerce_filter_before_print_block', true, $block_id ) ) {
							continue;
						}

						/**
						 *  Block class.
						 *
						 * @var Qode_Product_Extra_Options_For_WooCommerce_Blocks $block
						 */
						$block = qode_product_extra_options_for_woocommerce_instance_class(
							'Qode_Product_Extra_Options_For_WooCommerce_Blocks',
							array(
								'id' => $block_id,
							)
						);

						// Vendor.
						$addons       = Qode_Product_Extra_Options_For_WooCommerce_Main()->db->get_addons_by_block_id( $block_id, true );
						$total_addons = count( $addons );
						if ( $total_addons > 0 ) {

							$show_total_price_box = true;

							wc_get_template(
								'/front/block.php',
								array(
									'block'              => $block,
									'addons'             => $addons,
									'style_addon_titles' => $style_addon_titles,
									'currency'           => $currency,
								),
								'',
								QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates'
							);
						}
					}

					/**
					 * Action after printing all the blocks
					 */
					do_action( 'qode_product_extra_options_for_woocommerce_action_after_blocks' );

					$show_total_price_box = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_total_price_box', $show_total_price_box, $product, $variation );

					// TODO: create print_addons_price_table() function.
					if ( 'hide_all' !== $total_price_box && $show_total_price_box ) :
						wc_get_template(
							'/front/addons-price-table.php',
							array(
								'product'              => $product,
								'variation'            => $variation,
								'total_price_box'      => $total_price_box,
								'blocks_product_price' => $blocks_product_price,
							),
							'',
							QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates'
						);

					endif;
				}
			}
		} // end print_blocks().
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_init_frontend_module' ) ) {
	/**
	 * Init main color and label variations backend module instance.
	 */
	function qode_product_extra_options_for_woocommerce_init_frontend_module() {
		Qode_Product_Extra_Options_For_WooCommerce_Frontend_Module::get_instance();
	}

	add_action( 'init', 'qode_product_extra_options_for_woocommerce_init_frontend_module', 15 );
}
