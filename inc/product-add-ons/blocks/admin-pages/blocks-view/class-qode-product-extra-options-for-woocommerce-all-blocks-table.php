<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_All_Blocks_Table' ) ) {
	class Qode_Product_Extra_Options_For_WooCommerce_All_Blocks_Table extends WP_List_Table {

		public function __construct() {
			parent::__construct(
				array(
					'singular' => 'qode-product-extra-options-for-woocommerce-block',
					'plural'   => 'qode-product-extra-options-for-woocommerce-blocks',
					'ajax'     => false,
				)
			);
		}

		public function extra_tablenav( $which ) {
			if ( 'top' === $which ) {
				echo '<p class="qodef-block-update-message">' . esc_html__( 'Blocks updated', 'qode-product-extra-options-for-woocommerce' ) . '</p>';
				wp_nonce_field( 'qodef-block-update-nonce', 'qodef-block-update-nonce' );
			}
		}

		/**
		 * Message to be displayed when there are no items
		 *
		 * @since 3.1.0
		 */
		public function no_items() {
			esc_html_e( 'No blocks found. Click on ADD NEW and create your first block', 'qode-product-extra-options-for-woocommerce' );
		}

		/**
		 * Default columns print method
		 *
		 * @param array  $item       Associative array of element to print.
		 * @param string $column_name Name of the column to print.
		 *
		 * @return string
		 */
		public function column_default( $item, $column_name ) {
			if ( isset( $item[ $column_name ] ) ) {
				return esc_html( $item[ $column_name ] );
			} else {
				return print_r( $item, true ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}
		}

		public function single_row( $item ) {
			$data_attr = isset( $item['id'] ) ? $item['id'] : '';
			echo '<tr data-id="' . esc_attr( $data_attr ) . '">';
			$this->single_row_columns( $item );
			echo '</tr>';
		}

		/**
		 * Prints column for active
		 *
		 * @param array $item Item to use to print record.
		 *
		 * @return string
		 */
		public function column_priority( $item ) {
			$return_html = '';

			if ( isset( $item['priority'] ) ) {

				$return_html = intval( $item['priority'] );
			}

			return $return_html;
		}

		/**
		 * Prints column for wishlist name
		 *
		 * @param array $item Item to use to print record.
		 *
		 * @return string
		 */
		public function column_table_title( $item ) {
			$settings = maybe_unserialize( $item['settings'] );

			return sprintf( '<a href="?page=%s&block_id=%s&action=%s">%s</a>', isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '', sanitize_text_field( wp_unslash( $item['id'] ) ), 'edit', sanitize_text_field( wp_unslash( $settings['name'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		/**
		 * Prints column for show in
		 *
		 * @param array $item Item to use to print record.
		 *
		 * @return string
		 */
		public function column_show_in( $item ) {
			$settings    = maybe_unserialize( $item['settings'] );
			$return_html = '';

			if ( isset( $settings['rules']['show_in'] ) ) {

				if ( 'products' === $settings['rules']['show_in'] ) {
					$return_html = sprintf(
						'<span><strong>%1$s</strong></span>',
						esc_html__( 'Products:', 'qode-product-extra-options-for-woocommerce' )
					);

					// products.
					if ( isset( $settings['rules']['show_in_products'] ) && is_array( $settings['rules']['show_in_products'] ) ) {
						foreach ( $settings['rules']['show_in_products'] as $product_id ) {
							$return_html .= ' <a itemprop="url" target="_blank" href="' . get_the_permalink( $product_id ) . '">';
							$return_html .= get_the_title( $product_id );
							$return_html .= '</a>';

							if ( next( $settings['rules']['show_in_products'] ) ) {
								// Add comma for all elements instead of last.
								$return_html .= ',';
							}
						}
					}

					// categories.
					if ( isset( $settings['rules']['show_in_categories'] ) && is_array( $settings['rules']['show_in_categories'] ) ) {
						$return_html .= '<br>';
						$return_html .= sprintf(
							'<span><strong>%1$s</strong></span>',
							esc_html__( 'Categories:', 'qode-product-extra-options-for-woocommerce' )
						);

						foreach ( $settings['rules']['show_in_categories'] as $category_id ) {
							$return_html .= ' <a itemprop="url" target="_blank" href="' . get_term_link( get_term( $category_id )->slug, 'product_cat' ) . '">';
							$return_html .= get_term( $category_id )->name;
							$return_html .= '</a>';

							if ( next( $settings['rules']['show_in_categories'] ) ) {
								// Add comma for all elements instead of last.
								$return_html .= ',';
							}
						}
					}
				} else {
					$return_html = sprintf(
						'<span><strong>%1$s</strong></span>',
						esc_html__( 'All products', 'qode-product-extra-options-for-woocommerce' )
					);
				}
			}

			return $return_html;
		}

		/**
		 * Prints column for active
		 *
		 * @param array $item Item to use to print record.
		 *
		 * @return string
		 */
		public function column_active( $item ) {
			$return_html = '';

			if ( isset( $item['visibility'] ) ) {

				$active      = '1' === $item['visibility'] ? 1 : 0;
				$checked_yes = '';
				$checked_no  = '';

				if ( $active ) {
					$checked_yes = 'checked';
				} else {
					$checked_no = 'checked';
				}

				$return_html  = '<div class="qodef-yesno qodef-field" data-id="' . $item['id'] . '" data-option-name="qodef_block_id_' . $item['id'] . '_active_yes_no" data-option-type="radiogroup">';
				$return_html .= '<input class="qodef-field" type="radio" id="qodef_block_id_' . $item['id'] . '_yes_no-yes" name="qodef_block_id_' . $item['id'] . '_yes_no" value="yes" ' . esc_attr( $checked_yes ) . '>';
				$return_html .= '<label for="qodef_block_id_' . $item['id'] . '_yes_no-yes">Yes</label>';
				$return_html .= '<input class="qodef-field" type="radio" id="qodef_block_id_' . $item['id'] . '_yes_no-no" name="qodef_block_id_' . $item['id'] . '_yes_no" value="no" ' . esc_attr( $checked_no ) . '>';
				$return_html .= '<label for="qodef_block_id_' . $item['id'] . '_yes_no-no">No</label>';
				$return_html .= '</div>';
			}

			return $return_html;
		}

		/**
		 * Prints column for actions
		 *
		 * @param array $item Item to use to print record.
		 *
		 * @return string
		 */
		public function column_actions( $item ) {

			$delete_block_url = add_query_arg(
				array(
					'qode_product_extra_options_for_woocommerce_action' => 'remove-block',
					'block_id' => $item['id'],
				),
				wp_nonce_url( admin_url( sprintf( 'admin.php?page=%s', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ) ), 'qode_product_extra_options_for_woocommerce_action', 'nonce' )
			);

			$duplicate_block_url = add_query_arg(
				array(
					'qode_product_extra_options_for_woocommerce_action' => 'duplicate-block',
					'block_id' => $item['id'],
				),
				wp_nonce_url( admin_url( sprintf( 'admin.php?page=%s', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ) ), 'qode_product_extra_options_for_woocommerce_action', 'nonce' )
			);

			$return_html = '<div class="qodef-block-actions">';

			// edit.
			$return_html .= sprintf( '<a class="qodef-action-edit" href="?page=%s&block_id=%s&action=%s">' . qode_product_extra_options_for_woocommerce_get_svg_icon( 'edit', 'qodef-icon qodef-icon-edit' ) . '</a>', isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '', sanitize_text_field( wp_unslash( $item['id'] ) ), 'edit' ); // phpcs:ignore WordPress.Security.NonceVerification

			// duplicate.
			$return_html .= sprintf(
				'<a class="qodef-action-duplicate" href="%s">' . qode_product_extra_options_for_woocommerce_get_svg_icon( 'clone', 'qodef-icon qodef-icon-clone' ) . '</a>',
				esc_url( $duplicate_block_url )
			);

			// move.
			$return_html .= sprintf( '<a class="qodef-action-move" href="#">%s</a>', qode_product_extra_options_for_woocommerce_get_svg_icon( 'drag', 'qodef-icon qodef-icon-drag' ) );

			// delete.
			$return_html .= sprintf(
				'<a class="qodef-action-delete qodef-action-button--require-confirmation" href="%s" data-message="' . esc_html__( 'Are you sure you want to delete this block?', 'qode-product-extra-options-for-woocommerce' ) . '">%s</a>',
				esc_url( $delete_block_url ),
				qode_product_extra_options_for_woocommerce_get_svg_icon( 'trash', 'qodef-icon qodef-icon-trash' )
			);

			$return_html .= '</div>';

			return $return_html;
		}

		/**
		 * Returns columns available in table
		 *
		 * @return array Array of columns of the table
		 */
		public function get_columns() {

			$columns = array(
				'table_title' => esc_attr__( 'Block Name', 'qode-product-extra-options-for-woocommerce' ),
				'priority'    => esc_attr__( 'Priority', 'qode-product-extra-options-for-woocommerce' ),
				'show_in'     => esc_attr__( 'Show in', 'qode-product-extra-options-for-woocommerce' ),
				'active'      => esc_attr__( 'Active', 'qode-product-extra-options-for-woocommerce' ),
				'actions'     => esc_attr__( 'Actions', 'qode-product-extra-options-for-woocommerce' ),
			);

			return $columns;
		}

		/**
		 * Returns views for wishlist page
		 *
		 * @return array
		 */
		public function get_views() {
			$search_query = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification
			$privacy      = isset( $_GET['privacy'] ) ? sanitize_text_field( wp_unslash( $_GET['privacy'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification

			$views = array(
				'all'     => sprintf(
					'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
					esc_url( add_query_arg( 'privacy', 'all' ) ),
					( ! $privacy || 'all' === $privacy ) ? 'current' : '',
					esc_html__( 'All', 'qode-product-extra-options-for-woocommerce' ),
					count( $this->get_table_data() )
				),
				'public'  => sprintf(
					'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
					esc_url( add_query_arg( 'privacy', 'public' ) ),
					'public' === $privacy ? 'current' : '',
					esc_html__( 'Public', 'qode-product-extra-options-for-woocommerce' ),
					count(
						$this->get_items(
							array(
								'search_query' => $search_query,
								'visibility'   => 'public',
							)
						)
					)
				),
				'private' => sprintf(
					'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
					esc_url( add_query_arg( 'privacy', 'private' ) ),
					'private' === $privacy ? 'current' : '',
					esc_html__( 'Private', 'qode-product-extra-options-for-woocommerce' ),
					count(
						$this->get_items(
							array(
								'search_query' => $search_query,
								'visibility'   => 'private',
							)
						)
					)
				),
			);

			return $views;
		}

		/**
		 * Displays the search box.
		 *
		 * @param string $text     The 'submit' button label.
		 * @param string $input_id ID attribute value for the search input field.
		 */
		public function search_box( $text, $input_id ) {
			?>
			<div class="submit">
				<?php parent::search_box( $text, $input_id ); ?>
			</div>
			<?php
		}

		/**
		 * Return all tables
		 *
		 * @param array $atts - list of search attributes
		 *
		 * @return array|null|object
		 */
		public function get_items( $atts = array() ) {
			$result = array();

			global $wpdb;
			$addons_table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;

			$all_tables    = array();
			$all_tables[0] = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i', $addons_table ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

			if ( ! empty( $all_tables ) ) {

				foreach ( $all_tables as $key => $table_values ) {
					foreach ( $table_values as $table_name_key => $table_name_value ) {
						$current_table = $all_tables[ $key ];

						// Skip if value is not array, because only items are array type.
						if ( ! is_array( $table_name_value ) ) {
							continue;
						}

						$table_items    = qode_product_extra_options_for_woocommerce_get_cleaned_block_items( $table_name_value );
						$table_id       = $table_values[ $table_name_key ]['id'] ?? '';
						$table_title    = $table_values[ $table_name_key ]['name'] ?? '';
						$table_priority = $table_values[ $table_name_key ]['priority'] ?? '';
						$table_privacy  = $table_values[ $table_name_key ]['visibility'] && '1' === $table_values[ $table_name_key ]['visibility'] ? 'public' : 'private';

						$user_name = esc_html__( 'guest', 'qode-product-extra-options-for-woocommerce' );
						if ( isset( $current_table['user'] ) && is_numeric( $current_table['user'] ) ) {
							$user = get_user_by( 'id', (int) $current_table['user'] );

							if ( ! empty( $user ) ) {
								$user_name = $user->user_login;
							} else {
								$user_name = '/';
							}
						}

						// Check search attributes.
						if ( ! empty( $atts ) ) {

							if ( isset( $atts['visibility'] ) && ! empty( $atts['visibility'] ) && 'all' !== $atts['visibility'] && $table_privacy !== $atts['visibility'] ) {
								continue;
							}

							// Check the search query string and set items that contain that query string.
							$search_skip_flag = false;
							if ( isset( $atts['search_query'] ) && ! empty( $atts['search_query'] ) ) {
								$search_query       = strtolower( $atts['search_query'] );
								$search_table_title = strtolower( $table_title );

								// Check Wishlist table name and author name.
								if ( strpos( $search_table_title, $search_query ) === false ) {
									$search_skip_flag = true;
								}
							}

							if ( $search_skip_flag ) {
								continue;
							}
						}

						$result[] = array(
							'ID'           => $table_id,
							'table_title'  => $table_title,
							'privacy'      => $table_privacy,
							'priority'     => $table_priority,
							'items_count'  => ! empty( $table_items ) ? count( array_keys( $table_items ) ) : 0,
							'date_created' => $current_table[ $table_name_key ]['date_created'] ?? '/',
						);
					}
				}
			}

			if ( ! empty( $result ) ) {
				// Sort result lists.
				if ( isset( $atts['orderby'] ) && isset( $atts['order'] ) ) {
					$column_name = $atts['orderby'];
					switch ( $atts['orderby'] ) {
						case 'name':
							$column_name = 'table_title';
							break;
						case 'date':
							$column_name = 'date_created';
							break;
					}

					$order_column = array_column( $result, $column_name ); //phpcs:ignore array_column
					$order        = 'desc' === $atts['order'] ? SORT_DESC : SORT_ASC;

					array_multisort( $order_column, $order, $result );
				}

				$column_name  = 'priority';
				$order_column = array_column( $result, $column_name ); //phpcs:ignore array_column
				$order        = SORT_REGULAR;

				array_multisort( $order_column, $order, $result );

				// Reduce number of items for pagination.
				if ( isset( $atts['limit'] ) && isset( $atts['offset'] ) ) {
					$result = array_slice( $result, (int) $atts['offset'], (int) $atts['limit'] );
				}
			}

			return $result;
		}

		/**
		 * Prepare items for table
		 *
		 * @return void
		 */
		public function prepare_items() {
			global $wpdb;

			$search_query = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$privacy      = isset( $_REQUEST['privacy'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['privacy'] ) ) : 'all'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$orderby      = ! empty( $_REQUEST['orderby'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'name'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$order        = ! empty( $_REQUEST['order'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'asc'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			// Set columns headers.
			$columns               = $this->get_columns();
			$hidden                = array();
			$primary               = 'table_name';
			$this->_column_headers = array( $columns, $hidden, $primary );

			// Set pagination atts.
			$per_page     = 20;
			$current_page = $this->get_pagenum();
			$total_items  = count(
				$this->get_items(
					array(
						'search_query' => $search_query,
					)
				)
			);

			// delete.
			if ( isset( $_GET['action'] ) && isset( $_GET['page'] ) && QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME === $_GET['page'] && 'delete' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$block_id     = isset( $_GET['block_id'] ) ? intval( $_GET['block_id'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$addons_table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;

				if ( 'delete' === $this->current_action() && isset( $block_id ) ) {
					$wpdb->delete( $addons_table, array( 'id' => $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

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

			// bulk action.
			if ( isset( $_GET['action'] ) && QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME === $_GET['page'] && 'delete_all' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$addons_table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;

				$ids = isset( $_REQUEST['item'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['item'] ) ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				if ( is_array( $ids ) ) {
					$ids = implode( ',', $ids );
				}

				if ( ! empty( $ids ) ) {
					$wpdb->query( $wpdb->prepare( 'DELETE FROM %i WHERE id IN %d', $addons_table, $ids ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

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

			// Set table data.
			$this->items = $this->get_table_data( $privacy );

			// sets pagination args.
			$this->set_pagination_args(
				array(
					'total_items' => $total_items,
					'per_page'    => $per_page,
					'total_pages' => ceil( $total_items / $per_page ),
				)
			);
		}

		// Get table data.
		public function get_table_data( $privacy = 'all' ) {
			global $wpdb;

			$table = sprintf( '%s%s', $wpdb->prefix, QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS );

			if ( ! empty( $privacy ) && 'all' !== $privacy ) {
				if ( 'public' === $privacy ) {
					// active item.
					$privacy = 1;
					return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i WHERE visibility=%d', $table, $privacy ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				} elseif ( 'private' === $privacy ) {
					// inactive item.
					$privacy = 0;
					return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i WHERE visibility=%d', $table, $privacy ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				}
			} else {
				return $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM %i', $table ), ARRAY_A ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			}
		}

		/**
		 * Required function for displaying form with datas
		 */
		public function display_table() {
			echo '<form id="qpeofw-blocks-table" method="get">';
			echo '<input type="hidden" name="page" value="' . esc_attr( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ) . '" />';
			parent::display();
			echo '</form>';
		}
	}
}
