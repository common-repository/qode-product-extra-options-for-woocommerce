<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_Blocks' ) ) {

	/**
	 * Block class.
	 *
	 *  The class manage all the Block behaviors.
	 */
	class Qode_Product_Extra_Options_For_WooCommerce_Blocks {

		/**
		 *  ID
		 *
		 * @var int
		 */
		public $id = 0;

		/**
		 *  Settings
		 *
		 * @var array
		 */
		public $settings = array();

		/**
		 *  User ID
		 *
		 * @var int
		 */
		public $user_id;

		/**
		 *  Vendor ID
		 *
		 * @var int
		 */
		public $vendor_id;

		/**
		 *  Visibility
		 *
		 * @var boolean
		 */
		public $visibility = 1;

		/**
		 *  Name
		 *
		 * @var string
		 */
		public $name = '';

		/**
		 *  Priority
		 *
		 * @var int
		 */
		public $priority;

		/**
		 * Rules
		 *
		 * @var array
		 */
		public $rules = array();

		/**
		 *  Constructor
		 *
		 * @param array $args The args to instantiate the class.
		 */
		public function __construct( $args ) {
			global $wpdb;

			/**
			 * $id -> The block id.
			 */
			extract( $args ); // @codingStandardsIgnoreLine

			if ( $id > 0 ) {

				$blocks_name = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_BLOCKS;
				$row         = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE id=%d', $blocks_name, $id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

				if ( isset( $row ) && $row->id === $id ) {

					$this->id         = $row->id;
					$this->user_id    = $row->user_id;
					$this->vendor_id  = $row->vendor_id;
					$this->priority   = $row->priority;
					$this->visibility = $row->visibility;
					$this->name       = $row->name ?? '';
					$this->settings   = @unserialize( $row->settings ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_unserialize, WordPress.PHP.NoSilencedErrors.Discouraged

					// Settings.
					$this->rules = $this->settings['rules'] ?? array();
				}
			}
		}

		/**
		 * Return id of the current block.
		 *
		 * @return string
		 */
		public function get_id() {
			return $this->id ?? 0;
		}

		/**
		 * Get Setting
		 *
		 * @param string $option Option.
		 * @param string $default Default.
		 *
		 * @return mixed|string
		 */
		public function get_setting( $option, $default = '' ) {
			return isset( $this->settings[ $option ] ) ? $this->settings[ $option ] : $default;
		}

		/**
		 * Get Rule
		 *
		 * @param string $name Name.
		 * @param string $default Default.
		 *
		 * @return mixed|string
		 */
		public function get_rule( $name, $default = '' ) {
			return isset( $this->rules[ $name ] ) ? $this->rules[ $name ] : $default;
		}

		/**
		 * Return name of the current block.
		 *
		 * @return string
		 */
		public function get_name() {
			return $this->name ?? '';
		}

		/**
		 * Return user_id of the current block.
		 *
		 * @return string
		 */
		public function get_user_id() {
			return $this->user_id ?? 0;
		}

		/**
		 * Return visibility of the current block.
		 *
		 * @return string
		 */
		public function get_visibility() {
			return $this->visibility ?? 0;
		}

		/**
		 * Return priority of the current block.
		 *
		 * @return string
		 */
		public function get_priority() {
			return $this->priority ?? 0;
		}

		/**
		 * Return vendor_id of the current block.
		 *
		 * @return string
		 */
		public function get_vendor_id() {
			return 0;
		}
	}
}
