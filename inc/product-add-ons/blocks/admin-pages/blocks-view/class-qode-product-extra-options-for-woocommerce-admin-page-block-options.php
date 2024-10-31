<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_Admin_Page_Block_Options' ) ) {
	class Qode_Product_Extra_Options_For_WooCommerce_Admin_Page_Block_Options {
		private static $instance;

		public function __construct() {

			// Add custom page content inside Global options.
			add_action( 'qode_product_extra_options_for_woocommerce_action_framework_custom_page_content', array( $this, 'render' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'set_additional_scripts' ), 50 );
		}

		/**
		 * Instance of module class
		 *
		 * @return Qode_Product_Extra_Options_For_WooCommerce_Admin_Page_Block_Options
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function render( $page_slug ) {

			if ( 'blocks-editor' === $page_slug ) {

				$table = new Qode_Product_Extra_Options_For_WooCommerce_All_Blocks_Table();

				echo '<div id="qodef-page" class="wrap qodef-options-admin qodef-admin-page-v4 qodef-page-v4-qode-product-extra-options-for-woocommerce qodef-blocks">';

				if ( isset( $_REQUEST['block_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					// edit block.
					if ( isset( $_GET['action'] ) && isset( $_GET['page'] ) && QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME === $_GET['page'] && 'edit' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
						$block_id = isset( $_REQUEST['block_id'] ) ? sanitize_key( $_REQUEST['block_id'] ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

						if ( ! empty( $block_id ) ) {
							qode_product_extra_options_for_woocommerce_template_part( 'product-add-ons', 'blocks/admin-pages/blocks-view/templates/template', 'block-editor', array( 'block_id' => $block_id ) );
						}
					}
				} else {
					$this->display_header();
					if ( ! empty( $table ) ) {
						$table->prepare_items();
						$table->views();
						$table->display_table();
					} else {
						esc_html_e( 'Unfortunately there is no any blocks at this moment. Please add some', 'qode-product-extra-options-for-woocommerce' );
					}
				}
				echo '</div>';
				echo '<div class="qodef-admin-spinner"><div class="qodef-inner"><div class="qodef-pulse"></div></div></div>';
			}
		}

		public function display_header() {

			$params['banner_enabled'] = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_options_upgrade_banner', true );

			qode_product_extra_options_for_woocommerce_framework_template_part( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH, 'inc/common', 'modules/admin/templates/content-banner', '', $params );

			echo '<div class="qodef-admin-page-heading-wrapper">';
			echo '<div class="qodef-admin-page-add-block-wrapper"><h1 class="wp-heading-inline">' . esc_html__( 'Blocks Editor', 'qode-product-extra-options-for-woocommerce' ) . '</h1>';
			echo '<a href="admin.php?page=' . esc_attr( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ) . '&block_id=new&action=edit"  class="qodef-btn qodef-btn-solid qodef-page-title-action">' . esc_html__( 'Add New', 'qode-product-extra-options-for-woocommerce' ) . '</a></div>';
			echo '<hr class="wp-header-end">';
			echo '</div>';
		}

		public function set_additional_scripts( $hook ) {

			if ( strpos( $hook, QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_MENU_NAME ) !== false ) {
				// phpcs:ignore WordPress.WP.EnqueuedResourceParameters
				wp_enqueue_style( 'qode-product-extra-options-for-woocommerce-addons-admin', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_URL_PATH . '/product-add-ons/admin-styles/assets/css/dashboards.min.css' );

				// phpcs:ignore WordPress.WP.EnqueuedResourceParameters
				wp_enqueue_script( 'qode-product-extra-options-for-woocommerce-addons-admin', QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_URL_PATH . '/product-add-ons/admin-styles/assets/js/dashboard.js', array( 'jquery' ), false, true );
			}
		}
	}

	Qode_Product_Extra_Options_For_WooCommerce_Admin_Page_Block_Options::get_instance();
}
