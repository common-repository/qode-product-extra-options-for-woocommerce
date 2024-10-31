<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_Db' ) ) {

	/**
	 * Qode_Product_Extra_Options_For_WooCommerce_Db Class
	 */
	class Qode_Product_Extra_Options_For_WooCommerce_Db {

		/* inherited constants */
		const QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS_DB_CONST              = QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;
		const QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_DB_CONST                     = QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS;
		const QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS_ASSOCIATIONS_DB_CONST = QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSOCIATIONS;

		/**
		 * Single instance of the class
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_Db
		 */
		protected static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return Qode_Product_Extra_Options_For_WooCommerce_Db
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
		}

		/**
		 * Get Addons by Block ID Function
		 *
		 * @param int $block_id Block ID.
		 * @param bool $visible
		 *
		 * @return array
		 */
		public function get_addons_by_block_id( $block_id, $visible = false ) {
			global $wpdb;

			if ( 'new' === $block_id ) {
				return array();
			}

			$conditions  = array();
			$query_where = array();
			$addons      = array();

			if ( $block_id ) {
				$conditions['block_id'] = $block_id;
			}
			if ( $visible ) {
				$conditions['visibility'] = 1;
			}

			// Create the WHERE condition.
			if ( ! empty( $conditions ) ) {

				foreach ( $conditions as $column => $value ) {
					$query_where[] = $column . '=' . $value;
				}

				$query_where = ' WHERE ' . implode( ' AND ', $query_where );
			} else {
				$query_where = '';
			}

			$blocks_table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS;

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM %1s %1s ORDER BY priority ASC', $blocks_table, $query_where ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQLPlaceholders, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder

			foreach ( $results as $key => $addon ) {
				if ( qode_product_extra_options_for_woocommerce_is_installed( 'qpeofw-premium' ) && qode_product_extra_options_for_woocommerce_premium_is_plugin_activated() ) {
					$addons[] = qode_product_extra_options_for_woocommerce_instance_class(
						'Qode_Product_Extra_Options_For_WooCommerce_Premium_Addon',
						array(
							'id' => $addon->id,
						)
					);
				} else {
					$addons[] = qode_product_extra_options_for_woocommerce_instance_class(
						'Qode_Product_Extra_Options_For_WooCommerce_Addon',
						array(
							'id' => $addon->id,
						)
					);
				}
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_product_addons_filter_addons_by_block_id', $addons, $block_id );
		}

		/**
		 * Get Addons by Block ID Function
		 *
		 * @param WC_Product $product The product.
		 * @param null $variation
		 * @param bool $visible
		 *
		 * @param array $query_args
		 *
		 * @return array
		 */
		public function qode_product_extra_options_for_woocommerce_get_blocks_by_product( $product = null, $variation = null, $visible = false, $query_args = array() ) {
			global $wpdb;

			$blocks = array();

			if ( is_numeric( $product ) ) {
				$product = wc_get_product( $product );
			}

			if ( ! $product instanceof WC_Product ) {
				return $blocks;
			}

			if ( is_numeric( $variation ) ) {
				$variation = wc_get_product( $variation );
			}

			// Get only visible blocks.
			if ( 'yes' === $visible ) {
				$visible = '(1)';
			} elseif ( 'no' === $visible ) {
				$visible = '(0)';
			} elseif ( ! $visible ) {
				$visible = '(0, 1)';
			}

			$query_search = '';
			$query_limit  = '';
			$query_offset = '';
			$order_by     = 'priority,id';

			// Limit.
			if ( isset( $query_args['limit'] ) ) {
				$query_limit = 'LIMIT ' . absint( $query_args['limit'] );
			}

			// Offset.
			if ( isset( $query_args['offset'] ) ) {
				$query_offset = 'OFFSET ' . absint( $query_args['offset'] );
			}

			// Search box.
			if ( isset( $query_args['s'] ) ) {
				if ( ! empty( $query_args['s'] ) ) {
					$query_search = "( name LIKE '%" . esc_sql( $query_args['s'] ) . "%' ) 
                AND ";
				}
			}

			$product_id = $product->get_id();

			if ( $variation instanceof WC_Product_Variation ) {
				$product_id = $variation->get_id();
			}

			if ( empty( $product_cats ) ) {
				$product_cats = array( '0' );
			}

			$product_cats          = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_original_category_ids', $product->get_category_ids(), $product, apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_product_id', $product_id ) );
			$product_cats_imploded = implode( ', ', $product_cats );

			$is_logged_in = is_user_logged_in();
			$user_id      = get_current_user_id();
			$user         = get_user_by( 'id', $user_id );
			$user_roles   = $user instanceof WP_User ? $user->roles : '';

			if ( empty( $user_roles ) ) {
				$user_roles = array( '0' );
			}

			$user_roles_imploded = implode( ',', $user_roles );

			$blocks_table       = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;
			$associations_table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ASSOCIATIONS;

			$logged_in_association = $is_logged_in ? 'logged_users' : 'guest_users';

			// Default.
			if ( empty( $membership_plans ) ) {
				$membership_plans = array( '0' );
			}

			// TODO: members plugin.
			if ( defined( 'QODE_WCMBS_PREMIUM' ) ) {
				$member           = QODE_WCMBS_Members()->get_member( $user_id );
				$membership_plans = $member->get_membership_plans( array( 'return' => 'id' ) );
			}

			$membership_plans_imploded = implode( ',', $membership_plans );

			// All.
			$vendor_ids = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_blocks_by_product_set_vendor', array( 0 ), $product );
			$vendor_ids = ! ! $vendor_ids ? ( '(' . implode( ',', $vendor_ids ) . ')' ) : '(0)';

			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
			$query = $wpdb->prepare(
				"SELECT blocks.id
            FROM %i as blocks
            WHERE
                (
                    visibility IN %1s
                )
                AND
                %1s
                (
                    id IN (
                            SELECT id FROM %i
                            WHERE vendor_id IN %1s
                        )
                )
                AND
                (
                    (
                        id IN (
                            SELECT id FROM %i
                            WHERE product_association = 'all'
                        )
                        OR
                        id IN (
                            SELECT DISTINCT(id) FROM %i as i1
                            JOIN %i as a1 on a1.rule_id = i1.id
                            WHERE product_association = 'products' 
                                AND ( type = 'product' AND object = %d ) OR ( type = 'category' AND object IN ( %s )
                        )
                    )
                    AND 
                    (
                        id IN (
                            SELECT id FROM %i
                            WHERE user_association IN ( 'all', %s )
                        )
                            OR 
                        id IN (
                            SELECT DISTINCT(id) FROM %i as i2
                            JOIN %i as a2 on a2.rule_id = i2.id
                            WHERE user_association = 'user_roles'
                                AND ( type = 'user_role' AND object IN ( %s ) )
                        )
                            OR 
                        id IN (
                            SELECT DISTINCT(id) FROM %i as i3
                            JOIN %i as a3 on a3.rule_id = i3.id
                            WHERE user_association = 'membership'
                                AND ( type = 'membership' AND object IN ( %s ) )
                        )
                    )
                )
                AND
                ( 
                    id NOT IN (
                        SELECT DISTINCT(rule_id)
                        FROM %i as i4
                        JOIN %i as a4 on a4.rule_id = i4.id
                        WHERE exclude_products = 1 AND type = 'excluded_product' AND object = %d
                    )
                    AND 
                    id NOT IN (
                        SELECT DISTINCT(rule_id)
                        FROM %i as i5
                        JOIN %i as a5 on a5.rule_id = i5.id
                        WHERE exclude_products = 1 AND type = 'excluded_category' AND object IN ( %s )
                        )
                    )
                    AND 
                    id NOT IN (
                        SELECT DISTINCT(rule_id)
                        FROM %i as i6
                        JOIN %i as a6 on a6.rule_id = i6.id
                        WHERE exclude_users = 1 AND type = 'excluded_user_role' AND object IN ( %s )
                    )
                )
            ORDER BY %s
            %1s
            %1s
            ",
				array(
					$blocks_table,
					$visible,
					$query_search,
					$blocks_table,
					$vendor_ids,
					$blocks_table,
					$blocks_table,
					$associations_table,
					$product_id,
					$product_cats_imploded,
					$blocks_table,
					$logged_in_association,
					$blocks_table,
					$associations_table,
					$user_roles_imploded,
					$blocks_table,
					$associations_table,
					$membership_plans_imploded,
					$blocks_table,
					$associations_table,
					$product_id,
					$blocks_table,
					$associations_table,
					$product_cats_imploded,
					$blocks_table,
					$associations_table,
					$user_roles_imploded,
					$order_by,
					$query_limit,
					$query_offset,
				)
			);

			$blocks = $wpdb->get_col( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_blocks_by_product', $blocks, $product, $variation, $visible );
		}
	}
}

/**
 * Unique access to instance of Qode_Product_Extra_Options_For_WooCommerce_Db class
 *
 * @return Qode_Product_Extra_Options_For_WooCommerce_Db
 */
function Qode_Product_Extra_Options_For_WooCommerce_Db() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return Qode_Product_Extra_Options_For_WooCommerce_Db::get_instance();
}
