<?php
/*
Plugin Name: QODE Product Extra Options for WooCommerce
Description: QODE Product Extra Options for WooCommerce elevates the eCommerce experience by providing your shoppers with selectable advanced product options.
Author: Qode Interactive
Author URI: https://qodeinteractive.com/
Plugin URI: https://qodeinteractive.com/qode-product-extra-options-for-woocommerce/
Version: 1.0.1
Requires at least: 6.3
Requires PHP: 7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: qode-product-extra-options-for-woocommerce
*/

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce' ) ) {
	class Qode_Product_Extra_Options_For_WooCommerce {
		private static $instance;

		/**
		 * The version option.
		 */
		const DB_VERSION_OPTION = 'qode_product_extra_options_for_woocommerce_db_version_option';

		public function __construct() {
			// Set the main plugins constants.
			define( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_PLUGIN_BASE_FILE', plugin_basename( __FILE__ ) );

			// Include required files.
			require_once __DIR__ . '/constants.php';
			require_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ABS_PATH . '/helpers/helper.php';

			// Include framework file.
			require_once QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH . '/class-qode-product-extra-options-for-woocommerce-framework.php';

			// Check if WooCommerce is installed.
			if ( function_exists( 'WC' ) ) {

				// Create DB Tables.
				add_action( 'init', array( $this, 'check_version' ), 5 );

				// Make plugin available for translation.
				// permission 15 is set in order to be after the plugin initialization.
				add_action( 'plugins_loaded', array( $this, 'load_plugin_text_domain' ), 15 );

				add_action( 'admin_init', array( $this, 'manage_actions' ) );

				// Add plugin's body classes.
				add_filter( 'body_class', array( $this, 'add_body_classes' ) );

				// Set plugins predefined styles.
				add_filter( 'qode_product_extra_options_for_woocommerce_filter_predefined_style', array( $this, 'is_predefined_style_enabled' ) );
				add_filter( 'qode_product_extra_options_for_woocommerce_premium_filter_predefined_style', array( $this, 'is_predefined_style_enabled' ) );

				// HPOS Compatibility.
				add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );

				// Enqueue plugin's assets.
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
				// permission 12 is set in order to have the highest priority.
				add_action( 'wp_enqueue_scripts', array( $this, 'add_inline_style' ), 12 );
				add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ) );

				// Ajax update product price.
				add_action( 'wp_ajax_update_totals_with_suffix', array( $this, 'update_totals_with_suffix' ) );
				add_action( 'wp_ajax_nopriv_update_totals_with_suffix', array( $this, 'update_totals_with_suffix' ) );

				// Ajax update default product price.
				add_action( 'wp_ajax_get_default_variation_price', array( $this, 'get_default_variation_price' ) );
				add_action( 'wp_ajax_nopriv_get_default_variation_price', array( $this, 'get_default_variation_price' ) );

				// Ajax live print.
				add_action( 'wp_ajax_live_print_blocks', array( $this, 'live_print_blocks' ) );
				add_action( 'wp_ajax_nopriv_live_print_blocks', array( $this, 'live_print_blocks' ) );

				// Attach uploads to email.
				add_filter( 'woocommerce_email_attachments', array( $this, 'attach_uploads_to_emails' ), 10, 2 );

				// Include plugin's modules.
				$this->include_modules();
			}
		}

		/**
		 * Instance of module class
		 *
		 * @return Qode_Product_Extra_Options_For_WooCommerce
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function load_plugin_text_domain() {
			// Make plugin available for translation.
			load_plugin_textdomain( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_LOCALIZE_SLUG, false, QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_REL_PATH . '/languages' );
		}

		public function add_body_classes( $classes ) {
			$classes[] = 'qode-product-extra-options-for-woocommerce--' . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_VERSION;

			if ( wp_is_mobile() ) {
				$classes[] = 'qode-product-extra-options-for-woocommerce--touch';
			} else {
				$classes[] = 'qode-product-extra-options-for-woocommerce--no-touch';
			}

			$form_style = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_form_style' );
			$classes[]  = $form_style ? 'qode-product-extra-options-for-woocommerce--' . esc_html( $form_style ) : '';

			$select_style = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_select_style' );
			$classes[]    = $select_style ? 'qode-product-extra-options-for-woocommerce-select2-style--' . esc_html( $select_style ) : '';

			return $classes;
		}

		public function manage_actions() {
			// Actions.
			$nonce  = ( ! function_exists( 'wp_verify_nonce' ) || isset( $_REQUEST['nonce'] ) ) && ( wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'qode_product_extra_options_for_woocommerce_action' ) || wp_verify_nonce( sanitize_key( $_REQUEST['nonce'] ), 'qode_product_extra_options_for_woocommerce_admin' ) );
			$action = sanitize_key( $_REQUEST['qode_product_extra_options_for_woocommerce_action'] ?? '' );

			$save_block_button = isset( $_REQUEST['save-block-button'] ) ? 1 : 0;

			if ( $action && $nonce && current_user_can( 'edit_theme_options' ) ) {
				$block_id = sanitize_key( $_REQUEST['block_id'] ?? '' );
				$addon_id = sanitize_key( $_REQUEST['addon_id'] ?? '' );
				if ( 'save-block' === $action && $save_block_button ) {
					$this->save_block( isset( $_REQUEST ) ? map_deep( wp_unslash( $_REQUEST ), 'sanitize_text_field' ) : array() );
				} elseif ( 'duplicate-block' === $action ) {
					$this->duplicate_block( $block_id );
				} elseif ( 'remove-block' === $action ) {
					$this->remove_block( $block_id );
				} elseif ( 'save-addon' === $action ) {
					$this->save_addon( isset( $_REQUEST ) ? map_deep( wp_unslash( $_REQUEST ), 'sanitize_text_field' ) : array() );
				} elseif ( 'duplicate-addon' === $action ) {
					$this->duplicate_addon( $block_id, $addon_id );
				} elseif ( 'remove-addon' === $action ) {
					$this->remove_addon( $block_id, $addon_id );
				} elseif ( 'control_debug_options' === $action ) {
					$this->control_debug_options();
				}
			}
		}

		/**
		 * Save Block
		 *
		 * @param array $request Request array.
		 * @return mixed
		 */
		public function save_block( $request ) {
			global $wpdb;

			$block_id = isset( $request['block_id'] ) ? sanitize_text_field( wp_unslash( $request['block_id'] ) ) : '';

			if ( isset( $request['block_id'] ) ) {

				$show_in             = isset( $request['qode_product_extra_options_for_woocommerce_block_rule_show_in'] ) ? sanitize_text_field( wp_unslash( $request['qode_product_extra_options_for_woocommerce_block_rule_show_in'] ) ) : 'all';
				$excluded_categories = isset( $request['qode_product_extra_options_for_woocommerce_block_rule_exclude_products_categories'] ) ? array_map( 'sanitize_key', $request['qode_product_extra_options_for_woocommerce_block_rule_exclude_products_categories'] ) : '';
				$show_to             = isset( $request['qode_product_extra_options_for_woocommerce_block_rule_show_to'] ) ? sanitize_text_field( wp_unslash( $request['qode_product_extra_options_for_woocommerce_block_rule_show_to'] ) ) : 'all';
				$show_to_user_roles  = isset( $request['qode_product_extra_options_for_woocommerce_block_rule_show_to_user_roles'] ) ? array_map( 'sanitize_key', $request['qode_product_extra_options_for_woocommerce_block_rule_show_to_user_roles'] ) : '';
				// TODO extend if we release membership plugin.
				$show_to_membership = isset( $request['qode_product_extra_options_for_woocommerce_block_rule_show_to_membership'] ) ? array_map( 'sanitize_key', $request['qode_product_extra_options_for_woocommerce_block_rule_show_to_membership'] ) : '';

				if ( 'products' === $show_in ) {
					$excluded_categories = '';
				}
				if ( 'user_roles' !== $show_to ) {
					$show_to_user_roles = '';
				}
				if ( 'membership' !== $show_to ) {
					$show_to_membership = '';
				}

				$rules = array(
					'show_in'                     => $show_in,
					'show_in_products'            => isset( $request['qode_product_extra_options_for_woocommerce_block_rule_show_in_products'] ) ? array_map( 'sanitize_key', $request['qode_product_extra_options_for_woocommerce_block_rule_show_in_products'] ) : '',
					'show_in_categories'          => isset( $request['qode_product_extra_options_for_woocommerce_block_rule_show_in_categories'] ) ? array_map( 'sanitize_key', $request['qode_product_extra_options_for_woocommerce_block_rule_show_in_categories'] ) : '',
					'exclude_products'            => isset( $request['qode_product_extra_options_for_woocommerce_block_rule_exclude_products'] ) ? sanitize_text_field( wp_unslash( $request['qode_product_extra_options_for_woocommerce_block_rule_exclude_products'] ) ) : '',
					'exclude_products_products'   => isset( $request['qode_product_extra_options_for_woocommerce_block_rule_exclude_products_products'] ) ? array_map( 'sanitize_key', $request['qode_product_extra_options_for_woocommerce_block_rule_exclude_products_products'] ) : '',
					'exclude_products_categories' => $excluded_categories,
					'show_to'                     => $show_to,
					'show_to_user_roles'          => $show_to_user_roles,
					'show_to_membership'          => $show_to_membership,
				);

				$settings = array(
					'name'     => isset( $request['block_name'] ) ? sanitize_text_field( wp_unslash( $request['block_name'] ) ) : '',
					'priority' => isset( $request['block_priority'] ) ? sanitize_text_field( wp_unslash( $request['block_priority'] ) ) : 1,
					'rules'    => $rules,
				);

				$data = array(
					'settings'            => serialize( $settings ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					'priority'            => isset( $request['block_priority'] ) ? sanitize_text_field( wp_unslash( $request['block_priority'] ) ) : 1,
					'visibility'          => isset( $request['block_visibility'] ) ? sanitize_text_field( wp_unslash( $request['block_visibility'] ) ) : 1,
					'name'                => isset( $request['block_name'] ) ? sanitize_text_field( wp_unslash( $request['block_name'] ) ) : '',
					'product_association' => isset( $request['qode_product_extra_options_for_woocommerce_block_rule_show_in'] ) ? sanitize_text_field( wp_unslash( $request['qode_product_extra_options_for_woocommerce_block_rule_show_in'] ) ) : 'all',
					'exclude_products'    => isset( $request['qode_product_extra_options_for_woocommerce_block_rule_exclude_products'] ) ? wc_string_to_bool( $request['qode_product_extra_options_for_woocommerce_block_rule_exclude_products'] ) : 0,
					'user_association'    => isset( $request['qode_product_extra_options_for_woocommerce_block_rule_show_to'] ) ? sanitize_text_field( wp_unslash( $request['qode_product_extra_options_for_woocommerce_block_rule_show_to'] ) ) : 'all',
					// TODO: Change if exclude specific user is added to the plugin.
					'exclude_users'       => 0,
				);

				// Already sanitized - $show_in_products and $exclude_products.
				$show_in_products = $rules['show_in_products'] ?? array();
				$exclude_products = $rules['exclude_products_products'] ?? array();

				if ( is_array( $show_in_products ) ) {
					// If it is a variable product, add all available variation ids to the array.
					foreach ( $show_in_products as $product_id ) {
						$product = wc_get_product( $product_id );
						if ( $product instanceof WC_Product_Variable ) {
							$variations     = $product->get_available_variations();
							$variations_ids = wp_list_pluck( $variations, 'variation_id' );

							if ( ! empty( $variations_ids ) ) {
								$show_in_products = array_merge( $show_in_products, $variations_ids );
							}
						}
					}
				}

				if ( is_array( $exclude_products ) ) {
					// If it is a variable product, add all available variation ids to the array.
					foreach ( $exclude_products as $product_id ) {
						$product = wc_get_product( $product_id );
						if ( $product instanceof WC_Product_Variable ) {
							$variations     = $product->get_available_variations();
							$variations_ids = wp_list_pluck( $variations, 'variation_id' );

							if ( ! empty( $variations_ids ) ) {
								$exclude_products = array_merge( $exclude_products, $variations_ids );
							}
						}
					}
				}

				$show_in_categories = $rules['show_in_categories'] ?? array();
				$exclude_categories = $rules['exclude_products_categories'] ?? array();
				$user_roles         = $rules['show_to_user_roles'] ?? array();
				$memberships        = isset( $rules['show_to_membership'] ) && ! empty( $rules['show_to_membership'] ) ? (array) $rules['show_to_membership'] : array();

				$assoc_objects = array(
					'product'           => $show_in_products,
					'category'          => $show_in_categories,
					'excluded_product'  => $exclude_products,
					'excluded_category' => $exclude_categories,
					'user_role'         => $user_roles,
					'membership'        => $memberships,
				);

				if ( isset( $request['block_user_id'] ) && $request['block_user_id'] > 0 ) {
					$data['user_id'] = sanitize_text_field( $request['block_user_id'] );
				}

				// TODO: probably to remove.
				/** Multi Vendor integration. */
				$vendor_id = '';

				// migration.
				if ( isset( $request['block_vendor_id'] ) ) {
					$vendor_id = sanitize_text_field( $request['block_vendor_id'] );
					// v2.
				} elseif ( isset( $request['vendor_id'] ) ) {
					$vendor_id = sanitize_text_field( $request['vendor_id'] );
				}
				$data['vendor_id'] = $vendor_id;

				$table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;

				if ( 'new' === $request['block_id'] ) {

					if ( ! isset( $request['block_priority'] ) || 0 === $request['block_priority'] ) {
						$new_priority = 0;
						// Get max priority value.
						$max_priority = $wpdb->get_var( $wpdb->prepare( 'SELECT MAX(priority) FROM %i', $table ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						// Get number of blocks.
						$res_priority = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i', $table ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						$total_blocks = $wpdb->num_rows;
						// New priority value.
						if ( $max_priority > 0 && $total_blocks > 0 ) {
							$new_priority = $max_priority > $total_blocks ? $max_priority : $total_blocks;
						}
						$data['priority'] = $new_priority + 1;
					}

					$wpdb->insert( $table, $data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$block_id = $wpdb->insert_id;

				} elseif ( $request['block_id'] > 0 ) {
					$block_id = sanitize_text_field( wp_unslash( $request['block_id'] ) );
					$wpdb->update( $table, $data, array( 'id' => $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				}

				if ( is_numeric( $block_id ) ) {
					$this->set_associations( $block_id, $assoc_objects );
				}

				if ( isset( $request['add_options_after_save'] ) ) {
					wp_safe_redirect(
						add_query_arg(
							array(
								'page'     => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
								'block_id' => $block_id,
								'addon_id' => 'new',
							),
							admin_url( '/admin.php' )
						)
					);
				} elseif ( isset( $request['qode_product_extra_options_for_woocommerce_action'] ) && 'save-block' === $request['qode_product_extra_options_for_woocommerce_action'] ) {
					wp_safe_redirect(
						add_query_arg(
							array(
								'page'     => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
								'block_id' => $block_id,
								'action'   => 'edit',
							),
							admin_url( '/admin.php' )
						)
					);
				} else {
					return $block_id;
				}
			}
		}

		/**
		 * Remove Block
		 *
		 * @param int $block_id Block ID.
		 * @return void
		 */
		public function remove_block( $block_id ) {
			global $wpdb;

			if ( $block_id > 0 ) {
				$blocks_table       = $wpdb->prefix . Qode_Product_Extra_Options_For_WooCommerce_Db()::QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS_DB_CONST;
				$addons_table       = $wpdb->prefix . Qode_Product_Extra_Options_For_WooCommerce_Db()::QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DB_CONST;
				$associations_table = $wpdb->prefix . Qode_Product_Extra_Options_For_WooCommerce_Db()::QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS_ASSOCIATIONS_DB_CONST;

				$wpdb->delete( $blocks_table, array( 'id' => $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->delete( $addons_table, array( 'block_id' => $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->delete( $associations_table, array( 'rule_id' => $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

				wp_safe_redirect(
					add_query_arg(
						array(
							'page' => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
						),
						admin_url( '/admin.php' )
					)
				);
			}
		}

		/**
		 * Duplicate Block
		 *
		 * @param int $block_id Block ID.
		 * @return void
		 */
		public function duplicate_block( $block_id ) {
			global $wpdb;

			if ( $block_id > 0 ) {

				$blocks_table       = $wpdb->prefix . Qode_Product_Extra_Options_For_WooCommerce_Db()::QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS_DB_CONST;
				$addons_table       = $wpdb->prefix . Qode_Product_Extra_Options_For_WooCommerce_Db()::QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DB_CONST;
				$associations_table = $wpdb->prefix . Qode_Product_Extra_Options_For_WooCommerce_Db()::QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS_ASSOCIATIONS_DB_CONST;

				$queried_block_row  = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE id=%d', $blocks_table, $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$queried_addons_row = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i WHERE block_id=%d', $addons_table, $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$queried_assoc_row  = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i WHERE rule_id=%d', $associations_table, $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

				if ( isset( $queried_block_row ) && $queried_block_row->id === $block_id ) {

					$block_data = array(
						'vendor_id'  => $queried_block_row->vendor_id,
						'settings'   => $queried_block_row->settings,
						'priority'   => $queried_block_row->priority,
						'visibility' => $queried_block_row->visibility,
					);

					if ( isset( $queried_block_row->name ) &&
						isset( $queried_block_row->product_association ) &&
						isset( $queried_block_row->exclude_products ) &&
						isset( $queried_block_row->user_association ) &&
						isset( $queried_block_row->exclude_users )
					) {
						$block_data['name']                = $queried_block_row->name;
						$block_data['product_association'] = $queried_block_row->product_association;
						$block_data['exclude_products']    = $queried_block_row->exclude_products;
						$block_data['user_association']    = $queried_block_row->user_association;
						$block_data['exclude_users']       = $queried_block_row->exclude_users;
					}

					$wpdb->insert( $blocks_table, $block_data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$block_id = $wpdb->insert_id;

					foreach ( $queried_assoc_row as $assoc_row ) {
						$assoc_data = array(
							'rule_id' => $block_id,
							'object'  => $assoc_row->object,
							'type'    => $assoc_row->type,
						);

						$wpdb->insert( $associations_table, $assoc_data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					}

					$settings_addons_old = array();
					$addons_new_ids      = array();

					foreach ( $queried_addons_row as $addons_row ) {
						$addons_data = array(
							'block_id'   => $block_id,
							'settings'   => $addons_row->settings,
							'options'    => $addons_row->options,
							'priority'   => $addons_row->priority,
							'visibility' => $addons_row->visibility,
						);

						$wpdb->insert( $addons_table, $addons_data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
						$addon_id = $wpdb->insert_id;

						// Sync conditional logics with new data.
						if ( $addon_id ) {
							$settings = maybe_unserialize( $addons_data['settings'] );
							// Save setting default addon.
							$settings_addons_old[ $addons_row->id ] = $settings;
							// Create an array pair default_addon => clone addon.
							$addons_new_ids[ $addons_row->id ] = $addon_id;

						}
					}

					if ( ! empty( $addons_new_ids ) ) {

						foreach ( $addons_new_ids as $old_id => $new_id ) {

							$conditional_rule_addon_old = $settings_addons_old[ $old_id ]['conditional_rule_addon'];

							if ( is_array( $conditional_rule_addon_old ) ) {

								$conditional_rule_addon_new = array();

								foreach ( $conditional_rule_addon_old as $id ) {

									if ( ! empty( $id ) ) {

										$split_addon = explode( '-', $id );

										if ( $split_addon ) {
											// Prevent change variations.
											if ( 'v' !== $split_addon[0] ) {
												// change new addon_id.
												$split_addon[0]               = $addons_new_ids[ $split_addon[0] ] ?? '';
												$new_value                    = implode( '-', $split_addon );
												$conditional_rule_addon_new[] = $new_value;
											} else {
												$conditional_rule_addon_new[] = $id;
											}
											// Simple addon only switch the value.
										} else {
											$conditional_rule_addon_new[] = $settings_addons_old[ $id ];
										}
									}
								}
								if ( ! empty( $conditional_rule_addon_new ) ) {

									$settings_addons_old[ $old_id ]['conditional_rule_addon'] = $conditional_rule_addon_new;
									$update_settings_values                                   = serialize( $settings_addons_old[ $old_id ] ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
									$wpdb->update( $addons_table, array( 'settings' => $update_settings_values ), array( 'id' => $new_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
								}
							}
						}
					}

					wp_safe_redirect(
						add_query_arg(
							array(
								'page' => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
							),
							admin_url( '/admin.php' )
						)
					);
				}
			}
		}

		/**
		 * Save Addon
		 *
		 * @param array  $request Request array.
		 * @param string $method String to know that it comes from migration method.
		 * @return mixed
		 */
		public function save_addon( $request, $method = '' ) {
			global $wpdb;

			if ( isset( $request['block_id'] ) && 'new' === $request['block_id'] ) {
				$temp_request['block_id']                               = 'new';
				$temp_request['block_name']                             = isset( $_REQUEST['block_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['block_name'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$temp_request['block_priority']                         = isset( $_REQUEST['block_priority'] ) ? intval( $_REQUEST['block_priority'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$temp_request['block_rule_show_in']                     = isset( $_REQUEST['block_rule_show_in'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['block_rule_show_in'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$temp_request['block_rule_show_in_products']            = isset( $_REQUEST['block_rule_show_in_products'] ) ? array_map( 'sanitize_key', $_REQUEST['block_rule_show_in_products'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$temp_request['block_rule_show_in_categories']          = isset( $_REQUEST['block_rule_show_in_categories'] ) ? array_map( 'sanitize_key', $_REQUEST['block_rule_show_in_categories'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$temp_request['block_rule_exclude_products']            = isset( $_REQUEST['block_rule_exclude_products'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['block_rule_exclude_products'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$temp_request['block_rule_exclude_products_products']   = isset( $_REQUEST['block_rule_exclude_products_products'] ) ? array_map( 'sanitize_key', $_REQUEST['block_rule_exclude_products_products'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$temp_request['block_rule_exclude_products_categories'] = isset( $_REQUEST['block_rule_exclude_products_categories'] ) ? array_map( 'sanitize_key', $_REQUEST['block_rule_exclude_products_categories'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$temp_request['block_rule_show_to']                     = isset( $_REQUEST['block_rule_show_to'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['block_rule_show_to'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
				$temp_request['block_rule_show_to_user_roles']          = isset( $_REQUEST['block_rule_show_to_user_roles'] ) ? array_map( 'sanitize_key', $_REQUEST['block_rule_show_to_user_roles'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

				$request['block_id'] = $this->save_block( $temp_request );
			}

			if ( isset( $request['addon_id'] ) && isset( $request['block_id'] ) && $request['block_id'] > 0 ) {

				$conditional_logic = array();

				$settings = array(

					// General.
					'type'                         => sanitize_text_field( wp_unslash( $request['addon_type'] ) ) ?? '',

					// Display options.
					'title'                        => isset( $request['addon_title'] ) ? wp_unslash( str_replace( '"', '&quot;', $request['addon_title'] ) ) : '',
					'title_in_cart'                => isset( $request['addon_title_in_cart'] ) ? wp_unslash( str_replace( '"', '&quot;', $request['addon_title_in_cart'] ) ) : '',
					'title_in_cart_opt'            => isset( $request['addon_title_in_cart_opt'] ) ? wp_unslash( str_replace( '"', '&quot;', $request['addon_title_in_cart_opt'] ) ) : '',
					'description'                  => isset( $request['addon_description'] ) ? wp_unslash( $request['addon_description'] ) : '',
					'required'                     => isset( $request['addon_required'] ) ? sanitize_text_field( wp_unslash( $request['addon_required'] ) ) : '',
					'show_image'                   => isset( $request['addon_show_image'] ) ? sanitize_text_field( wp_unslash( $request['addon_show_image'] ) ) : '',
					'image'                        => isset( $request['addon_image'] ) ? sanitize_text_field( wp_unslash( $request['addon_image'] ) ) : '',
					'image_replacement'            => isset( $request['addon_image_replacement'] ) ? sanitize_text_field( wp_unslash( $request['addon_image_replacement'] ) ) : '',
					'options_images_position'      => isset( $request['addon_options_images_position'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_images_position'] ) ) : '',
					'show_as_toggle'               => isset( $request['addon_show_as_toggle'] ) ? sanitize_text_field( wp_unslash( $request['addon_show_as_toggle'] ) ) : '',
					'hide_options_images'          => isset( $request['addon_hide_options_images'] ) ? sanitize_text_field( wp_unslash( $request['addon_hide_options_images'] ) ) : '',
					'hide_options_label'           => isset( $request['addon_hide_options_label'] ) ? sanitize_text_field( wp_unslash( $request['addon_hide_options_label'] ) ) : '',
					'hide_options_prices'          => isset( $request['addon_hide_options_prices'] ) ? sanitize_text_field( wp_unslash( $request['addon_hide_options_prices'] ) ) : '',
					'hide_products_prices'         => isset( $request['addon_hide_products_prices'] ) ? sanitize_text_field( wp_unslash( $request['addon_hide_products_prices'] ) ) : '',
					'show_add_to_cart'             => isset( $request['addon_show_add_to_cart'] ) ? sanitize_text_field( wp_unslash( $request['addon_show_add_to_cart'] ) ) : '',
					'show_sku'                     => isset( $request['addon_show_sku'] ) ? sanitize_text_field( wp_unslash( $request['addon_show_sku'] ) ) : '',
					'show_stock'                   => isset( $request['addon_show_stock'] ) ? sanitize_text_field( wp_unslash( $request['addon_show_stock'] ) ) : '',
					'show_quantity'                => isset( $request['addon_show_quantity'] ) ? sanitize_text_field( wp_unslash( $request['addon_show_quantity'] ) ) : '',
					'options_display_type'         => isset( $request['addon_options_display_type'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_display_type'] ) ) : '',
					'show_in_a_grid'               => isset( $request['addon_show_in_a_grid'] ) ? sanitize_text_field( wp_unslash( $request['addon_show_in_a_grid'] ) ) : '',
					'options_grid_gap'             => isset( $request['addon_options_grid_gap'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_grid_gap'] ) ) : '',
					'options_per_row'              => isset( $request['addon_options_per_row'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_per_row'] ) ) : '',
					'options_per_row_1512'         => isset( $request['addon_options_per_row_1512'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_per_row_1512'] ) ) : '',
					'options_per_row_1368'         => isset( $request['addon_options_per_row_1368'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_per_row_1368'] ) ) : '',
					'options_per_row_1200'         => isset( $request['addon_options_per_row_1200'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_per_row_1200'] ) ) : '',
					'options_per_row_1024'         => isset( $request['addon_options_per_row_1024'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_per_row_1024'] ) ) : '',
					'options_per_row_880'          => isset( $request['addon_options_per_row_880'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_per_row_880'] ) ) : '',
					'options_per_row_680'          => isset( $request['addon_options_per_row_680'] ) ? sanitize_text_field( wp_unslash( $request['addon_options_per_row_680'] ) ) : '',
					'select_width'                 => isset( $request['addon_select_width'] ) ? sanitize_text_field( wp_unslash( $request['addon_select_width'] ) ) : '',

					// Style settings.
					'label_style'                  => isset( $request['addon_label_style'] ) ? sanitize_text_field( wp_unslash( $request['addon_label_style'] ) ) : '',
					'color_swathes_style'          => isset( $request['addon_color_swatches_style'] ) ? sanitize_text_field( wp_unslash( $request['addon_color_swatches_style'] ) ) : '',
					'color_swathes_size'           => isset( $request['addon_color_swatches_size'] ) ? sanitize_text_field( wp_unslash( $request['addon_color_swatches_size'] ) ) : '',
					'image_position'               => isset( $request['addon_image_position'] ) ? sanitize_text_field( wp_unslash( $request['addon_image_position'] ) ) : '',
					'label_content_align'          => isset( $request['addon_label_content_align'] ) ? sanitize_text_field( wp_unslash( $request['addon_label_content_align'] ) ) : '',
					'image_equal_height'           => isset( $request['addon_image_equal_height'] ) ? sanitize_text_field( wp_unslash( $request['addon_image_equal_height'] ) ) : '',
					'images_height'                => isset( $request['addon_images_height'] ) ? sanitize_text_field( wp_unslash( $request['addon_images_height'] ) ) : '',
					'label_position'               => isset( $request['addon_label_position'] ) ? sanitize_text_field( wp_unslash( $request['addon_label_position'] ) ) : '',
					'label_padding'                => isset( $request['addon_label_padding'] ) ? map_deep( wp_unslash( $request['addon_label_padding'] ), 'sanitize_text_field' ) : '',
					'description_position'         => isset( $request['addon_description_position'] ) ? sanitize_text_field( wp_unslash( $request['addon_description_position'] ) ) : '',
					'product_out_of_stock'         => isset( $request['addon_product_out_of_stock'] ) ? sanitize_text_field( wp_unslash( $request['addon_product_out_of_stock'] ) ) : '',

					// Conditional logic.
					'enable_rules'                 => isset( $request['addon_enable_rules'] ) ? sanitize_text_field( wp_unslash( $request['addon_enable_rules'] ) ) : '',
					'enable_rules_variations'      => isset( $request['addon_enable_rules_variations'] ) && isset( $request['addon_conditional_rule_variations'] ) ? sanitize_text_field( wp_unslash( $request['addon_enable_rules_variations'] ) ) : '',
					'conditional_logic_display'    => isset( $request['addon_conditional_logic_display'] ) ? sanitize_text_field( wp_unslash( $request['addon_conditional_logic_display'] ) ) : '',
					'conditional_rule_variations'  => isset( $request['addon_conditional_rule_variations'] ) ? array_map( 'sanitize_key', $request['addon_conditional_rule_variations'] ) : '',
					'conditional_set_conditions'   => isset( $request['addon_conditional_set_conditions'] ) ? sanitize_text_field( wp_unslash( $request['addon_conditional_set_conditions'] ) ) : '',
					'conditional_logic_display_if' => isset( $request['addon_conditional_logic_display_if'] ) ? sanitize_text_field( wp_unslash( $request['addon_conditional_logic_display_if'] ) ) : '',
					'conditional_rule_addon'       => isset( $request['addon_conditional_rule_addon'] ) ? array_map( 'sanitize_key', $request['addon_conditional_rule_addon'] ) : '',
					'conditional_rule_addon_is'    => isset( $request['addon_conditional_rule_addon_is'] ) ? array_map( 'sanitize_key', $request['addon_conditional_rule_addon_is'] ) : '',

					// Advanced options.
					'first_options_selected'       => isset( $request['addon_first_options_selected'] ) ? sanitize_text_field( wp_unslash( $request['addon_first_options_selected'] ) ) : '',
					'first_free_options'           => isset( $request['addon_first_free_options'] ) ? sanitize_text_field( wp_unslash( $request['addon_first_free_options'] ) ) : '',
					'selection_type'               => isset( $request['addon_selection_type'] ) ? sanitize_text_field( wp_unslash( $request['addon_selection_type'] ) ) : '',
					'enable_min_max'               => isset( $request['addon_enable_min_max'] ) ? sanitize_text_field( wp_unslash( $request['addon_enable_min_max'] ) ) : '',
					'min_max_rule'                 => isset( $request['addon_min_max_rule'] ) ? array_map( 'sanitize_key', $request['addon_min_max_rule'] ) : '',
					'min_max_value'                => isset( $request['addon_min_max_value'] ) ? array_map( 'sanitize_key', $request['addon_min_max_value'] ) : '',
					// Sell individually addon.
					'sell_individually'            => isset( $request['addon_sell_individually'] ) && 'yes' === sanitize_text_field( wp_unslash( $request['addon_sell_individually'] ) ) ? 'yes' : 'no',

					'enable_min_max_numbers'       => isset( $request['addon_enable_min_max_numbers'] ) ? sanitize_text_field( wp_unslash( $request['addon_enable_min_max_numbers'] ) ) : '',
					'numbers_min'                  => isset( $request['addon_number_min'] ) ? sanitize_text_field( wp_unslash( $request['addon_number_min'] ) ) : '',
					'numbers_max'                  => isset( $request['addon_number_max'] ) ? sanitize_text_field( wp_unslash( $request['addon_number_max'] ) ) : '',

					// HTML elements.
					'text_content'                 => isset( $request['option_text_content'] ) ? str_replace( '"', '&quot;', $request['option_text_content'] ) : '',
					'text_class'                   => isset( $request['option_text_class'] ) ? esc_html( $request['option_text_class'] ) : '',
					'heading_text'                 => isset( $request['option_heading_text'] ) ? str_replace( '"', '&quot;', $request['option_heading_text'] ) : '',
					'heading_class'                => isset( $request['option_heading_class'] ) ? esc_html( $request['option_heading_class'] ) : '',
					'heading_type'                 => isset( $request['option_heading_type'] ) ? sanitize_text_field( wp_unslash( $request['option_heading_type'] ) ) : '',
					'heading_color'                => isset( $request['option_heading_color'] ) ? sanitize_text_field( wp_unslash( $request['option_heading_color'] ) ) : '',
					'separator_style'              => isset( $request['option_separator_style'] ) ? sanitize_text_field( $request['option_separator_style'] ) : '',
					'separator_class'              => isset( $request['option_separator_class'] ) ? esc_html( $request['option_separator_class'] ) : '',
					'separator_width'              => isset( $request['option_separator_width'] ) ? sanitize_text_field( $request['option_separator_width'] ) : '',
					'separator_size'               => isset( $request['option_separator_size'] ) ? sanitize_text_field( $request['option_separator_size'] ) : '',
					'separator_color'              => isset( $request['option_separator_color'] ) ? sanitize_text_field( $request['option_separator_color'] ) : '',

					// Rules.
					'conditional_logic'            => $conditional_logic,
				);

				$request = $this->stripslashes_recursive( $request );

				$request  = $this->save_addon_enable_value_formatted( $request );
				$settings = $this->save_formatted_settings( $settings );

				$settings = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_save_addon_settings', $settings, $request );

				$data = array(
					'block_id'   => sanitize_text_field( wp_unslash( $request['block_id'] ) ),
					'settings'   => serialize( $settings ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					'options'    => serialize( stripslashes_deep( $request['options'] ?? '' ) ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
					'visibility' => 1,
				);

				// addon_priority from migration ( it should keep the same order ).
				if ( isset( $request['addon_priority'] ) ) {
					$data['priority'] = sanitize_text_field( $request['addon_priority'] );
				}

				$table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS;

				if ( 'new' === $request['addon_id'] || 'migration' === $method ) {
					if ( 'migration' === $method ) {
						$addon_id = sanitize_text_field( $request['addon_id'] );
						if ( $request['addon_id'] > 0 ) {
							$data['id'] = $addon_id;
							$wpdb->insert( $table, $data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
						}
					} else {
						$wpdb->insert( $table, $data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
						$addon_id = $wpdb->insert_id;

						// New priority value.
						$priority_data = array( 'priority' => $addon_id );
						$wpdb->update( $table, $priority_data, array( 'id' => $addon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
					}
				} elseif ( $request['addon_id'] > 0 ) {
					$addon_id = sanitize_text_field( $request['addon_id'] );
					$wpdb->update( $table, $data, array( 'id' => $addon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
				}

				if ( qode_product_extra_options_for_woocommerce_is_installed( 'wpml' ) ) {
					Qode_Product_Extra_Options_For_WooCommerce_WPML::register_option_type( $settings['title'], $settings['description'], $data['options'], $settings['text_content'], $settings['heading_text'] );
				}

				if ( isset( $request['qode_product_extra_options_for_woocommerce_action'] ) && 'save-addon' === sanitize_text_field( wp_unslash( $request['qode_product_extra_options_for_woocommerce_action'] ) ) ) {

					wp_safe_redirect(
						add_query_arg(
							array(
								'page'     => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
								'block_id' => sanitize_text_field( wp_unslash( $request['block_id'] ) ),
								'action'   => 'edit',
							),
							admin_url( '/admin.php' )
						)
					);

				} else {
					return $addon_id;
				}
			}

			return false;
		}

		/**
		 *
		 * Recursive stripslashes for entire array ($variable)
		 *
		 * @param array|string $variable
		 * @return mixed|string
		 */
		private function stripslashes_recursive( $variable ) {
			if ( is_string( $variable ) ) {
				return stripslashes( $variable );
			} elseif ( is_array( $variable ) ) {
				foreach ( $variable as $i => $value ) {
					$variable[ $i ] = $this->stripslashes_recursive( $value );
				}
			}

			return $variable;
		}

		/**
		 * Duplicate Addon
		 *
		 * @param int $block_id Block ID.
		 * @param int $addon_id Addon ID.
		 * @return void
		 */
		public function duplicate_addon( $block_id, $addon_id ) {
			global $wpdb;

			if ( $addon_id > 0 ) {
				$addons_table = $wpdb->prefix . Qode_Product_Extra_Options_For_WooCommerce_Db()::QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DB_CONST;

				$row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE id=%d', $addons_table, $addon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

				$settings = maybe_unserialize( $row->settings );
				if ( isset( $settings['title'] ) ) {
					$settings['title'] = $settings['title'] . ' - ' . _x( 'Copy', 'String added to the add-on title when it is duplicated', 'qode-product-extra-options-for-woocommerce' );
				}

				$settings = serialize( $settings ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize

				$data = array(
					'block_id'   => $row->block_id,
					'settings'   => $settings,
					'options'    => $row->options,
					// Raise it slightly so there are no same values.
					'priority'   => $row->priority + 0.01,
					'visibility' => $row->visibility,
				);

				$wpdb->insert( $addons_table, $data ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
				$addon_id = $wpdb->insert_id;

				wp_safe_redirect(
					add_query_arg(
						array(
							'page'     => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
							'block_id' => $block_id,
							'action'   => 'edit',
						),
						admin_url( '/admin.php' )
					)
				);

			}
		}

		/**
		 * Save addon attributes formatted.
		 *
		 * @param array $request The array of the request.
		 *
		 * @return mixed
		 */
		public function save_addon_enable_value_formatted( $request ) {

			$excluded_addon_types = array(
				'html-heading',
				'html-separator',
				'html-text',
			);

			if ( ! in_array( $request['addon_type'], $excluded_addon_types, true ) ) {
				$options      = &$request['options'];
				$addons_count = isset( $options['label'] ) ? count( $options['label'] ) : 0;

				for ( $i = 0; $i < $addons_count; $i++ ) {
					$options['label'][ $i ]         = isset( $options['label'][ $i ] ) && ! empty( $options['label'][ $i ] ) ? stripslashes( $options['label'][ $i ] ) : '';
					$options['description'][ $i ]   = isset( $options['description'][ $i ] ) && ! empty( $options['description'][ $i ] ) ? stripslashes( $options['description'][ $i ] ) : '';
					$options['addon_enabled'][ $i ] = isset( $options['addon_enabled'][ $i ] ) && 'yes' === $options['addon_enabled'][ $i ] ? 'yes' : 'no';
					$options['show_image'][ $i ]    = isset( $options['show_image'][ $i ] ) && 'yes' === $options['show_image'][ $i ] ? 'yes' : 'no';
					$options['default'][ $i ]       = isset( $options['default'][ $i ] ) && 1 === intval( $options['default'][ $i ] ) ? 'yes' : 'no';
					$options['label_in_cart'][ $i ] = isset( $options['label_in_cart'][ $i ] ) && 1 === intval( $options['label_in_cart'][ $i ] ) ? 'yes' : 'no';
					$options['price'][ $i ]         = isset( $options['price'][ $i ] ) ? trim( $options['price'][ $i ] ) : '';
					$options['price_sale'][ $i ]    = isset( $options['price_sale'][ $i ] ) ? trim( $options['price_sale'][ $i ] ) : '';
				}
			}
			return $request;
		}

		/**
		 * Save settings with right values.
		 *
		 * @param array $settings The array of settings.
		 *
		 * @return mixed
		 */
		public function save_formatted_settings( $settings ) {

			$settings['title_in_cart'] = isset( $settings['title_in_cart'] ) && wc_string_to_bool( $settings['title_in_cart'] ) ? $settings['title_in_cart'] : 'no';

			return $settings;
		}

		/**
		 * Remove Addon
		 *
		 * @param int $block_id Block ID.
		 * @param int $addon_id Addon ID.
		 * @return void
		 */
		public function remove_addon( $block_id, $addon_id ) {
			global $wpdb;

			if ( $addon_id > 0 ) {

				$addons_table = $wpdb->prefix . Qode_Product_Extra_Options_For_WooCommerce_Db()::QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DB_CONST;

				$wpdb->delete( $addons_table, array( 'id' => $addon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

				wp_safe_redirect(
					add_query_arg(
						array(
							'page'     => QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME,
							'block_id' => $block_id,
							'action'   => 'edit',
						),
						admin_url( '/admin.php' )
					)
				);
			}
		}

		/**
		 * Restart db options / Remove columns/ Remove tables
		 *
		 * @return void
		 */
		public function control_debug_options() {
			global $wpdb;

			$option = isset( $_REQUEST['option'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['option'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$blocks_table_name       = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;
			$blocks_assoc_table_name = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSOCIATIONS;
			$addons_table_name       = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS;

			$blocks_backup_table_name       = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS_BACKUP;
			$blocks_assoc_backup_table_name = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSOCIATIONS_BACKUP;
			$addons_backup_table_name       = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS_BACKUP;

			switch ( $option ) {
				case 'create_tables':
					$this->create_tables();
					break;
				case 'clear_tables':
					$wpdb->query( $wpdb->prepare( 'DELETE FROM %i', $blocks_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'DELETE FROM %i', $blocks_assoc_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'DELETE FROM %i', $addons_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					break;
				case 'create_backup_tables':
					// blocks.
					$wpdb->query( $wpdb->prepare( 'CREATE TABLE IF NOT EXISTS %i LIKE %i', $blocks_backup_table_name, $blocks_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'INSERT INTO %i SELECT * FROM %i', $blocks_backup_table_name, $blocks_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

					// blocks assoc.
					$wpdb->query( $wpdb->prepare( 'CREATE TABLE IF NOT EXISTS %i LIKE %i', $blocks_assoc_backup_table_name, $blocks_assoc_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'INSERT INTO %i SELECT * FROM %i', $blocks_assoc_backup_table_name, $blocks_assoc_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

					// addons.
					$wpdb->query( $wpdb->prepare( 'CREATE TABLE IF NOT EXISTS %i LIKE %i', $addons_backup_table_name, $addons_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'INSERT INTO %i SELECT * FROM %i', $addons_backup_table_name, $addons_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					break;
				case 'create_new_backup_tables':
					$wpdb->query( $wpdb->prepare( 'DELETE FROM %i', $blocks_backup_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'DELETE FROM %i', $blocks_assoc_backup_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'DELETE FROM %i', $addons_backup_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

					$wpdb->query( $wpdb->prepare( 'INSERT INTO %i SELECT * FROM %i', $blocks_backup_table_name, $blocks_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'INSERT INTO %i SELECT * FROM %i', $blocks_assoc_backup_table_name, $blocks_assoc_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'INSERT INTO %i SELECT * FROM %i', $addons_backup_table_name, $addons_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					break;
				case 'restore_tables':
					$wpdb->query( $wpdb->prepare( 'INSERT INTO %i SELECT * FROM %i', $blocks_table_name, $blocks_backup_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'INSERT INTO %i SELECT * FROM %i', $blocks_assoc_table_name, $blocks_assoc_backup_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->query( $wpdb->prepare( 'INSERT INTO %i SELECT * FROM %i', $addons_table_name, $addons_backup_table_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					break;
			}

			wp_safe_redirect( admin_url( '/admin.php?page=' . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ) );
		}

		/**
		 * Insert or update in the database the associations.
		 *
		 * @param $block_id
		 * @param $associations_obj
		 * @return void
		 */
		public function set_associations( $block_id, $associations_obj ) {
			global $wpdb;

			$associations_table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSOCIATIONS;

			$wpdb->delete( $associations_table, array( 'rule_id' => $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			foreach ( $associations_obj as $object_type => $object_array ) {
				if ( ! empty( $object_array ) && is_array( $object_array ) ) {
					foreach ( $object_array as $obj_item ) {
						if ( ! empty( $obj_item ) ) {
							$association_data = array(
								'rule_id' => $block_id,
								'object'  => $obj_item,
								'type'    => $object_type,
							);
							$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
								$associations_table,
								$association_data
							);
						}
					}
				}
			}
		}

		public function enqueue_assets() {
			// Enqueue CSS styles.
			wp_enqueue_style( 'qode-product-extra-options-for-woocommerce-main', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSETS_URL_PATH . '/css/main.min.css', array(), QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_VERSION );

			// Enqueue JS scripts.
			wp_enqueue_script( 'qode-product-extra-options-for-woocommerce-main', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSETS_URL_PATH . '/js/main.js', array( 'jquery', 'jquery-ui-progressbar', 'wc-add-to-cart-variation' ), QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_VERSION, true );

			// Enqueue predefined CSS styles and JS scripts.
			if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_predefined_style', false ) ) {
				// Include plugins predefined Google Fonts.
				$this->include_google_fonts();

				// Enqueue plugin's 3rd party script (select2 is registered inside WooCommerce plugin).
				wp_enqueue_script( 'select2' );

				// Enqueue plugin's 3rd party style (select2 is registered inside WooCommerce plugin).
				wp_enqueue_style( 'select2' );

				// Enqueue predefined style.
				wp_enqueue_style( 'qode-product-extra-options-for-woocommerce-main-predefined', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSETS_URL_PATH . '/css/main-predefined.min.css', array(), QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_VERSION );

				add_filter( 'qode_product_extra_options_for_woocommerce_filter_inline_style_handle', array( $this, 'include_inline_style_handle' ) );

				// Register 3rd party plugins scripts.
				wp_register_script( 'wp-color-picker-alpha', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_URL_PATH . '/inc/common/assets/plugins/wp-color-picker-alpha/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), '3.0.3', true );
			}
		}

		public function add_inline_style() {
			$style = esc_html( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_add_inline_style', '' ) );

			if ( ! empty( $style ) ) {
				wp_add_inline_style( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_inline_style_handle', 'qode-product-extra-options-for-woocommerce-main' ), $style );
			}
		}

		public function include_inline_style_handle() {
			return 'qode-product-extra-options-for-woocommerce-main-predefined';
		}

		public function is_predefined_style_enabled() {
			return 'predefined-style' === qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_form_style' );
		}

		public function localize_scripts() {
			$global = apply_filters(
				'qode_product_extra_options_for_woocommerce_localize_main_plugin_script',
				array(
					// default CSS styles from WordPress.
					'adminBarHeight' => is_admin_bar_showing() ? ( wp_is_mobile() ? 46 : 32 ) : 0,
					'restUrl'        => esc_url_raw( rest_url() ),
					'restNonce'      => wp_create_nonce( 'wp_rest' ),
				)
			);

			wp_localize_script(
				'qode-product-extra-options-for-woocommerce-main',
				'qpeofwGlobal',
				$global
			);

			// frontend.
			$front_localize = apply_filters(
				'qode_product_extra_options_for_woocommerce_filter_localize_frontend_plugin_script',
				array(
					'dom'                            => array(
						'single_add_to_cart_button' => '.single_add_to_cart_button',
					),
					'i18n'                           => array(
						// translators: Label printed in the add-on type Date, when activating the timepicker.
						'datepickerSetTime'          => esc_html__( 'Set time', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Label printed in the add-on type Date, when activating the timepicker.
						'datepickerSaveButton'       => esc_html__( 'Save', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Label printed if minimum value is 1.
						'selectAnOption'             => esc_html__( 'Please, populate an option', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Label printed if minimum value is more than 1. %d is the number of options.
						'selectAtLeast'              => esc_html__( 'Please, populate at least %d options', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Label printed for exact selection value. %d is the number of options.
						'selectOptions'              => esc_html__( 'Please, populate %d options', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Error when the user select more than allowed options ( min/max feature ).
						'maxOptionsSelectedMessage'  => esc_html__( 'More options than allowed have been populate', 'qode-product-extra-options-for-woocommerce' ),
						'uploadPercentageDoneString' => _x( 'done', 'Percentage done when uploading a file on an add-on type File.', 'qode-product-extra-options-for-woocommerce' ),
					),
					'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
					'addons_nonce'                   => wp_create_nonce( 'addons-nonce' ),
					'upload_allowed_file_types'      => qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_upload_allowed_file_types' ),
					'upload_max_file_size'           => qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_upload_max_file_size' ),
					'total_price_box_option'         => qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_single_options_total_price_box' ),
					'replace_product_price'          => qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_single_replace_product_price' ),
					'woocommerce_currency'           => esc_attr( get_woocommerce_currency() ),
					'total_thousand_sep'             => get_option( 'woocommerce_price_thousand_sep', ',' ),
					'decimal_sep'                    => get_option( 'woocommerce_price_decimal_sep', '.' ),
					'priceSuffix'                    => wc_tax_enabled() ? get_option( 'woocommerce_price_display_suffix', '' ) : '',
					'replace_image_path'             => $this->get_product_gallery_image_path(),
					'replace_product_price_class'    => $this->get_product_price_class(),
					'hide_button_required'           => qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_single_hide_add_to_cart_button_if_required' ),
					'messages'                       => array(
						// translators: Message error when total of add-ons type numbers does not exceeds the minimum set in the configuration.
						'minErrorMessage'         => esc_html__( 'The sum of the numbers is below the minimum. The minimum value is:', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Message error when total of add-ons type numbers exceeds the maximum set in the configuration.
						'maxErrorMessage'         => esc_html__( 'The sum of the numbers exceeded the maximum. The maximum value is:', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Message giving the error after checking minimum and maximum quantity when adding to cart.
						'checkMinMaxErrorMessage' => esc_html__( 'Please, populate an option', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Text to show when an option is required.
						'requiredMessage'         => qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_options_required_option_text' ),
						// translators: Text to show for maximum files allowed on add-on type Upload.
						'maxFilesAllowed'         => esc_html__( 'Maximum uploaded files allowed. The maximum number of files allowed is: ', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Message giving the error when the file upload is not a supported extension.
						'noSupportedExtension'    => esc_html__( 'Error - not supported extension!', 'qode-product-extra-options-for-woocommerce' ),
						// translators: Message giving the error when the file has a high size, 1 file size, 2 allowed size, .
						'maxFileSize'             => esc_html__( 'Error - file size for %1$s - max %2$d MB allowed!', 'qode-product-extra-options-for-woocommerce' ),

					),
					'wc_blocks'                      => array(
						'has_cart_block' => has_block( 'woocommerce/cart' ),
					),
					'productQuantitySelector'        => apply_filters(
						'qode_product_extra_options_for_woocommerce_filter_product_quantity_selector',
						'form.cart .quantity input.qty:not(.qpeofw-product-qty)'
					),
					'enableGetDefaultVariationPrice' => apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_default_variation_price_calculation', true ),
					'currentLanguage'                => '',
					'conditionalDisplayEffect'       => apply_filters( 'qode_product_extra_options_for_woocommerce_filter_conditional_display_effect', 'fade' ),
				)
			);

			wp_localize_script(
				'qode-product-extra-options-for-woocommerce-main',
				'qpeofwFrontend',
				$front_localize
			);
		}

		/**
		 * Declare support for WooCommerce features.
		 */
		public function declare_wc_features_support() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}

		/**
		 * Update totals with suffix
		 *
		 * Update totals when there are suffix configured
		 */
		public function update_totals_with_suffix() {
			check_ajax_referer( 'addons-nonce', 'security' );
			$values = array(
				'price_html'           => '',
				'options_price_suffix' => '',
				'order_price_suffix'   => '',
			);
			if ( isset( $_POST['data']['product_id'] ) ) {
				$product_id = absint( $_POST['data']['product_id'] );
				$product    = wc_get_product( $product_id );
				if ( $product instanceof WC_Product ) {
					if ( $product instanceof WC_Product_Variable && empty( $product->get_default_attributes() ) ) {
						$price = 0;
					} else {
						// TODO: for currency switching.
						$price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_convert_price', wc_get_price_to_display( $product ), true );
					}
					// TODO: for currency switching.
					$totals_price_args = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_totals_price_args', array() );
					$display_product   = wc_price( $price, $totals_price_args ) . $product->get_price_suffix();

					$values['price_html'] = $display_product;

					// Options price.
					if ( isset( $_POST['data']['options_price'] ) ) {

						$options_price         = floatval( sanitize_text_field( wp_unslash( $_POST['data']['options_price'] ) ) );
						$options_default_price = isset( $_POST['data']['options_default_price'] ) ? floatval( sanitize_text_field( wp_unslash( $_POST['data']['options_default_price'] ) ) ) : '';

						$options_price_suffix           = wc_price( $options_price ) . $product->get_price_suffix( $options_default_price );
						$values['options_price_suffix'] = $options_price_suffix;
					}

					// Order price.
					if ( isset( $_POST['data']['total_order_price'] ) ) {

						$order_price_suffix    = floatval( sanitize_text_field( wp_unslash( $_POST['data']['total_order_price'] ) ) );
						$options_default_price = floatval( sanitize_text_field( wp_unslash( $_POST['data']['options_default_price'] ) ) );

						$order_price_suffix           = wc_price( $order_price_suffix ) . $product->get_price_suffix( $product->get_price() + $options_default_price );
						$values['order_price_suffix'] = $order_price_suffix;

					}
				}
			}
			wp_send_json( $values );
		}

		/**
		 * Get the default product price when variation is reset.
		 */
		public function get_default_variation_price() {
			check_ajax_referer( 'addons-nonce', 'security' );
			$values = array( 'price_html' => '' );

			if ( isset( $_POST['product_id'] ) ) {
				$product_id = absint( $_POST['product_id'] );
				$product    = wc_get_product( $product_id );
				if ( $product instanceof WC_Product ) {
					$product_price      = $product->get_price();
					$product_price_html = $product->get_price_html();

					$values['price_html']    = $product_price_html;
					$values['current_price'] = $product_price;

				}
			}
			wp_send_json( $values );
		}

		/**
		 * Live print blocks
		 */
		public function live_print_blocks() {
			check_ajax_referer( 'addons-nonce', 'security' );

			// Simple, grouped and external products.
			add_filter( 'woocommerce_product_get_price', array( $this, 'custom_price' ), 99, 1 );
			add_filter( 'woocommerce_product_get_regular_price', array( $this, 'custom_price' ), 99, 1 );
			// Variations.
			add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'custom_price' ), 99, 1 );
			add_filter( 'woocommerce_product_variation_get_price', array( $this, 'custom_price' ), 99, 1 );
			// Variable (price range).
			add_filter( 'woocommerce_variation_prices_price', array( $this, 'custom_variable_price' ), 99, 1 );
			add_filter( 'woocommerce_variation_prices_regular_price', array( $this, 'custom_variable_price' ), 99, 1 );

			global $woocommerce, $product, $variation;
			$woocommerce  = WC();
			$product_id   = 0;
			$variation_id = 0;
			$variation    = false;
			$addons       = sanitize_text_field( $_POST['addons'] ) ?? array(); // phpcs:ignore
			foreach ( $addons as $key => $input ) {
				if ( 'qpeofw_product_id' === $input['name'] ) {
					$product_id = $input['value'];
				}
				if ( 'variation_id' === $input['name'] ) {
					$variation_id = $input['value'];
					if ( $variation_id > 0 ) {
						$variation = new WC_Product_Variation( $variation_id );
					}
				}
			}

			$product = wc_get_product( $product_id );

			ob_start();
			Qode_Product_Extra_Options_For_WooCommerce_Frontend_Module::get_instance()->print_blocks();
			$html = ob_get_clean();

			// Format the add-ons structure from serialized array.
			$formatted_addons = qode_product_extra_options_for_woocommerce_format_addons( $addons );
			$addons           = array_merge( $formatted_addons['qpeofw_options']['addons'] ?? array(), $formatted_addons['qpeofw_options']['individual'] ?? array() );
			$quantities       = $formatted_addons['qpeofw_qty_options'] ?? array();

			wp_send_json(
				array(
					'html'       => $html,
					'addons'     => $addons,
					'quantities' => $quantities ?? array(),
				)
			);
		}

		/**
		 * Custom price
		 *
		 * @param string $price Price.
		 *
		 * @return float
		 */
		public function custom_price( $price ) {
			return (float) $price;
		}

		/**
		 * Custom variable price
		 *
		 * @param string $price Price.
		 *
		 * @return float
		 */
		public function custom_variable_price( $price ) {
			return (float) $price;
		}

		/**
		 * Attach files uploaded to the emails.
		 *
		 * @param array    $attachments The attachments.
		 * @param WC_Order $object The order object.
		 *
		 * @return array
		 * @throws Exception
		 */
		public function attach_uploads_to_emails( $attachments, $object ) {

			$attach_uploads = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_attach_file_to_email' );

			if ( 'yes' !== $attach_uploads || ! $object instanceof WC_Order ) {
				return $attachments;
			}

			$items = $object->get_items();

			foreach ( $items as $item_id => $item ) {

				$meta_data = wc_get_order_item_meta( $item_id, '_qode_product_extra_options_for_woocommerce_meta_data', true );

				if ( $meta_data && is_array( $meta_data ) ) {
					foreach ( $meta_data as $index => $option ) {
						foreach ( $option as $key => $value ) {
							if ( $key && '' !== $value ) {
								$values = Qode_Product_Extra_Options_For_WooCommerce_Main::get_instance()->split_addon_and_option_ids( $key, $value );

								$addon_id  = $values['addon_id'];
								$option_id = $values['option_id'];

								$info       = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id );
								$addon_type = $info['addon_type'];

								if ( 'file' === $addon_type ) {
									if ( is_array( $value ) ) {
										foreach ( $value as $attachment ) {
											$attachments[] = $attachment;
										}
									}
								}
							}
						}
					}
				}
			}

			return $attachments;
		}

		/**
		 * Get the product price classes to modify the price.
		 *
		 * @return string
		 */
		private function get_product_price_class() {

			$product_class = '.product .entry-summary .price,
            div.elementor.product .elementor-widget-woocommerce-product-price .price:first';

			// Is using WC Blocks.
			if ( qode_product_extra_options_for_woocommerce_woo_is_using_block_template_in_single_product() ) {
				$product_class = '.woocommerce.product .wp-block-woocommerce-product-price .amount';
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_replace_product_price_class', esc_attr( $product_class ) );
		}

		/**
		 * Get the main product image classes on the product page to replace the image.
		 *
		 * @return string
		 */
		private function get_product_gallery_image_path() {

			$image_class = '.woocommerce-product-gallery .woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image:first-child img.zoomImg,
            .woocommerce-product-gallery .woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image:first-child source,
            .owl-carousel .woocommerce-main-image,
            .qodef-woo-single-image .woocommerce-product-gallery__image .wp-post-image,
            .qodef-woo-single-image .woocommerce-product-gallery__image--placeholder .wp-post-image,
            .dt-sc-product-image-gallery-container .wp-post-image';

			// Is using WC Blocks.
			if ( qode_product_extra_options_for_woocommerce_woo_is_using_block_template_in_single_product() ) {
				$image_class = '.wp-block-woocommerce-product-image-gallery .woocommerce-product-gallery__wrapper .wp-post-image';
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_additional_replace_image_path', esc_attr( $image_class ) );
		}

		public function include_modules() {
			// Hook to include additional element before modules inclusion.
			do_action( 'qode_product_extra_options_for_woocommerce_action_before_include_modules' );

			foreach ( glob( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/*/include.php' ) as $module ) {
				include_once $module;
			}

			// Hook to include additional element after modules inclusion.
			do_action( 'qode_product_extra_options_for_woocommerce_action_after_include_modules' );
		}

		public function include_google_fonts() {
			$font_subset_array = array(
				'latin-ext',
			);

			$font_weight_array = array(
				'300',
				'400',
				'500',
				'600',
				'700',
			);

			$default_font_family = array(
				'DM Sans',
			);

			$font_weight_str = implode( ',', $font_weight_array );
			$font_subset_str = implode( ',', $font_subset_array );

			if ( ! empty( $default_font_family ) ) {
				$modified_default_font_family = array();

				foreach ( $default_font_family as $font ) {
					$modified_default_font_family[] = $font . ':' . $font_weight_str;
				}

				$default_font_string = implode( '|', $modified_default_font_family );

				$fonts_full_list_args = array(
					'family'  => rawurlencode( $default_font_string ),
					'subset'  => rawurlencode( $font_subset_str ),
					'display' => 'swap',
				);

				$google_fonts_url = add_query_arg( $fonts_full_list_args, 'https://fonts.googleapis.com/css' );

				wp_enqueue_style( 'qode-product-extra-options-for-woocommerce-google-fonts', esc_url_raw( $google_fonts_url ), array(), '1.0.0' );
			}
		}

		/**
		 * Check the plugin version and run the updater if required.
		 * This check is done on all requests and runs if the versions do not match.
		 */
		public function check_version() {
			$current_db_version = get_option( self::DB_VERSION_OPTION, null );

			// creating tables and more if needed.
			if ( $current_db_version ) {
				$this->install();

				update_option( self::DB_VERSION_OPTION, QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DB_VERSION );
			}

			// Update for future versions.
			if ( $this->needs_db_update() ) {
				update_option( self::DB_VERSION_OPTION, QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DB_VERSION );
			}
		}

		/**
		 * Start the migration
		 */
		public function install() {
			// can set transient for updating and than remove it in later versions.
			// Create tables and backup tables.
			$this->create_tables();

			do_action( 'qode_product_extra_options_for_woocommerce_action_migrated' );
		}

		/**
		 * The DB needs to be updated?
		 *
		 * @return bool
		 */
		public function needs_db_update() {
			$current_db_version = get_option( self::DB_VERSION_OPTION, null );

			if ( is_null( $current_db_version ) ) {
				return true;
			} elseif ( version_compare( $current_db_version, QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DB_VERSION, '<' ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * DB Create
		 *
		 * @return void
		 */
		public function create_tables() {
			global $wpdb;

			if ( ! function_exists( 'dbDelta' ) ) {
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			}

			$wpdb->hide_errors();
			$wpdb->suppress_errors( true );
			$wpdb->show_errors( false );

			$charset_collate = $wpdb->get_charset_collate();

			$sql_blocks = "CREATE TABLE {$wpdb->prefix}" . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS . "(
						id					INT(3) NOT NULL AUTO_INCREMENT,
						user_id				BIGINT(20),
						vendor_id			BIGINT(20),
						settings			LONGTEXT,
						priority			DECIMAL(9,5),
						visibility			INT(1),
						creation_date		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
						last_update			TIMESTAMP,
						name                varchar(255) NOT NULL,
                        product_association varchar(255),
                        exclude_products    tinyint(1) NOT NULL,
				        user_association    varchar(255),
				        exclude_users       tinyint(1) NOT NULL,
						PRIMARY KEY (id)
					) $charset_collate;";

			$sql_addons = "CREATE TABLE {$wpdb->prefix}" . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS . "(
						id					INT(4) NOT NULL AUTO_INCREMENT,
						block_id			INT(3),
						settings			LONGTEXT,
						options				LONGTEXT,
						priority			DECIMAL(9,5),
						visibility			INT(1),
						creation_date		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
						last_update			TIMESTAMP,
						PRIMARY KEY (id)
					) $charset_collate;";

			$sql_associations = "CREATE TABLE {$wpdb->prefix}" . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSOCIATIONS . "(
						rule_id bigint(20) NOT NULL,
                        object 	varchar(255) NOT NULL,
                        type 	varchar(50) NOT NULL,
                        KEY 	`type` (`type`),
                        KEY 	`object` (`object`)
					) $charset_collate;";

			dbDelta( $sql_blocks );
			dbDelta( $sql_addons );
			dbDelta( $sql_associations );
		}
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_init_plugin' ) ) {
	/**
	 * Function that init plugin activation
	 */
	function qode_product_extra_options_for_woocommerce_init_plugin() {
		Qode_Product_Extra_Options_For_WooCommerce::get_instance();
	}

	add_action( 'plugins_loaded', 'qode_product_extra_options_for_woocommerce_init_plugin' );
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_activation_trigger' ) ) {
	/**
	 * Function that trigger hooks on plugin activation
	 */
	function qode_product_extra_options_for_woocommerce_activation_trigger() {
		// Hook to add additional code on plugin activation.
		do_action( 'qode_product_extra_options_for_woocommerce_action_on_activation' );
	}

	register_activation_hook( __FILE__, 'qode_product_extra_options_for_woocommerce_activation_trigger' );
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_deactivation_trigger' ) ) {
	/**
	 * Function that trigger hooks on plugin deactivation
	 */
	function qode_product_extra_options_for_woocommerce_deactivation_trigger() {
		// Hook to add additional code on plugin deactivation.
		do_action( 'qode_product_extra_options_for_woocommerce_action_on_deactivation' );
	}

	register_deactivation_hook( __FILE__, 'qode_product_extra_options_for_woocommerce_deactivation_trigger' );
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_check_requirements' ) ) {
	/**
	 * Function that check plugin requirements
	 */
	function qode_product_extra_options_for_woocommerce_check_requirements() {
		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'qode_product_extra_options_for_woocommerce_admin_notice_content' );
		}
	}

	add_action( 'plugins_loaded', 'qode_product_extra_options_for_woocommerce_check_requirements' );
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_admin_notice_content' ) ) {
	/**
	 * Function that display the error message if the requirements are not met
	 */
	function qode_product_extra_options_for_woocommerce_admin_notice_content() {
		printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html__( 'WooCommerce plugin is required for QODE Product Extra Options for WooCommerce plugin to work properly. Please install/activate it first.', 'qode-product-extra-options-for-woocommerce' ) );
	}
}
