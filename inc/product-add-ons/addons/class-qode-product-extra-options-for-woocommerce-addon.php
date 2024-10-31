<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_Addon' ) ) {

	/**
	 *  Addon class.
	 *  The class manage all the Addon behaviors.
	 */
	class Qode_Product_Extra_Options_For_WooCommerce_Addon {

		/**
		 *  ID
		 *
		 *  @var int
		 */
		public $id = 0;

		/**
		 *  Settings
		 *
		 *  @var array
		 */
		public $settings = array();

		/**
		 *  Options
		 *
		 *  @var array
		 */
		public $options = array();

		/**
		 *  Title
		 *
		 * @var string
		 */
		public $title = '';

		/**
		 *  Priority
		 *
		 *  @var int
		 */
		public $priority = 0;

		/**
		 *  Visibility
		 *
		 *  @var array
		 */
		public $visibility = 1;

		/**
		 *  Type
		 *
		 *  @var string
		 */
		public $type = 0;

		/**
		 *  Constructor
		 *
		 * @param array $args The args to instantiate the class.
		 */
		public function __construct( $args ) {
			global $wpdb;

			/**
			 * $id -> The add-on id.
			 * $type -> The add-on type. Used for new add-ons (via $_REQUEST)
			 */
			extract( $args ); // @codingStandardsIgnoreLine

			$this->type = $type ?? '';

			if ( is_numeric( $id ) && $id > 0 ) {

				$addons_table = $wpdb->prefix . QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADD_ONS;
				$row          = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM %i WHERE id=%d', $addons_table, $id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

				if ( isset( $row ) && $row->id === (string) $id ) {

					$this->id         = $row->id;
					$this->settings   = maybe_unserialize( $row->settings );
					$this->options    = maybe_unserialize( $row->options );
					$this->priority   = $row->priority;
					$this->visibility = $row->visibility;

					// Settings.
					$this->type  = isset( $this->settings['type'] ) ? $this->settings['type'] : 'html-text';
					$this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : '';

				}
			}
		}

		/**
		 * Return id of the current add-on.
		 *
		 * @return string
		 */
		public function get_id() {
			return $this->id ?? 0;
		}

		/**
		 * Return title of the current add-on.
		 *
		 * @return string
		 */
		public function get_title() {
			return $this->title ?? '';
		}

		/**
		 * Return type of the current add-on.
		 *
		 * @return string
		 */
		public function get_type() {
			return $this->type ?? '';
		}

		/**
		 * Return priority of the current add-on.
		 *
		 * @return string
		 */
		public function get_priority() {
			return $this->priority ?? 0;
		}

		/**
		 * Return visibility of the current add-on.
		 *
		 * @return string
		 */
		public function get_visibility() {
			return $this->visibility ?? 0;
		}

		/**
		 * Set ID of the add-on.
		 *
		 * @param int $id The id of the add-on.
		 *
		 * @return void
		 */
		public function set_id( $id ) {
			$this->id = $id;
		}

		/**
		 * Set type of the add-on.
		 *
		 * @param string $type The type of the add-on: checkbox, radio, text, color ...
		 *
		 * @return void
		 */
		public function set_type( $type ) {
			$this->type = $type;
		}

		/**
		 *  Get formatted settings with default values.
		 */
		public function get_formatted_settings() {
			$label_padding_defaults = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_label_and_images_image_position' );

			$formatted_settings = array();

			$default_settings = array(
				// General.
				'addon_type'                    => array(
					'id'        => 'type',
					'default'   => '',
					'translate' => false,
				),
				// Display options.
				'addon_title'                   => array(
					'id'        => 'title',
					'default'   => '',
					'translate' => true,
				),
				'addon_description'             => array(
					'id'        => 'description',
					'default'   => '',
					'translate' => true,
				),
				'addon_required'                => array(
					'id'        => 'required',
					'default'   => 'no',
					'translate' => false,
				),
				'show_image'                    => array(
					'default'   => 'no',
					'translate' => false,
				),
				'addon_image'                   => array(
					'id'        => 'image',
					'default'   => '',
					'translate' => false,
				),
				'addon_image_replacement'       => array(
					'id'        => 'image_replacement',
					'default'   => 'no',
					'translate' => false,
				),
				'addon_options_images_position' => array(
					'id'        => 'options_images_position',
					'default'   => 'above',
					'translate' => false,
				),
				'show_as_toggle'                => array(
					'default'   => 'no',
					'translate' => false,
				),
				'hide_option_images'            => array(
					'id'        => 'hide_options_images',
					'default'   => 'no',
					'translate' => false,
				),
				'hide_option_label'             => array(
					'id'        => 'hide_options_label',
					'default'   => 'no',
					'translate' => false,
				),
				'hide_option_prices'            => array(
					'id'        => 'hide_options_prices',
					'default'   => 'no',
					'translate' => false,
				),
				'hide_product_prices'           => array(
					'id'        => 'hide_products_prices',
					'default'   => 'no',
					'translate' => false,
				),
				'show_add_to_cart'              => array(
					'default'   => 'no',
					'translate' => false,
				),
				'show_sku'                      => array(
					'default'   => 'no',
					'translate' => false,
				),
				'show_stock'                    => array(
					'default'   => 'no',
					'translate' => false,
				),
				'show_quantity'                 => array(
					'default'   => 'no',
					'translate' => false,
				),
				'options_display_type'          => array(
					'default'   => 'grid',
					'translate' => false,
				),
				'show_in_a_grid'                => array(
					'default'   => 'no',
					'translate' => false,
				),
				'options_per_row'               => array(
					'default'   => 1,
					'translate' => false,
				),
				'options_grid_gap'              => array(
					'default'   => 12,
					'translate' => false,
				),
				'options_per_row_1512'          => array(
					'default'   => 1,
					'translate' => false,
				),
				'options_per_row_1368'          => array(
					'default'   => 1,
					'translate' => false,
				),
				'options_per_row_1200'          => array(
					'default'   => 1,
					'translate' => false,
				),
				'options_per_row_1024'          => array(
					'default'   => 1,
					'translate' => false,
				),
				'options_per_row_880'           => array(
					'default'   => 1,
					'translate' => false,
				),
				'options_per_row_680'           => array(
					'default'   => 1,
					'translate' => false,
				),
				'select_width'                  => array(
					'default'   => 75,
					'translate' => false,
				),
				// Style settings.
				'image_position'                => array(
					'default'   => '',
					'translate' => false,
				),
				'label_content_align'           => array(
					'default'   => 'left',
					'translate' => false,
				),
				'image_equal_height'            => array(
					'default'   => 'no',
					'translate' => false,
				),
				'images_height'                 => array(
					'default'   => 100,
					'translate' => false,
				),
				'label_style'                   => array(
					'default'   => 'default',
					'translate' => false,
				),
				'color_swathes_style'           => array(
					'default'   => 'default',
					'translate' => false,
				),
				'color_swathes_size'            => array(
					'default'   => 0,
					'translate' => false,
				),
				'label_position'                => array(
					'default'   => 'default',
					'translate' => false,
				),
				'label_padding'                 => array(
					'default'   => array(
						'dimensions' => $label_padding_defaults,
					),
					'translate' => false,
				),
				'description_position'          => array(
					'default'   => 'default',
					'translate' => false,
				),
				'product_out_of_stock'          => array(
					'default'   => 'hide',
					'translate' => false,
				),
				// Conditional logic.
				'enable_rules'                  => array(
					'default'   => 'no',
					'translate' => false,
				),
				'enable_rules_variations'       => array(
					'default'   => 'no',
					'translate' => false,
				),
				'conditional_logic_display'     => array(
					'default'   => 'show',
					'translate' => false,
				),
				'conditional_rule_variations'   => array(
					'default'   => array(),
					'translate' => false,
				),
				'conditional_set_conditions'    => array(
					'default'   => '0',
					'translate' => false,
				),
				'conditional_logic_display_if'  => array(
					'default'   => 'all',
					'translate' => false,
				),
				'conditional_rule_addon'        => array(
					'default'   => array(),
					'translate' => false,
				),
				'conditional_rule_addon_is'     => array(
					'default'   => array(),
					'translate' => false,
				),
				// Advanced options.
				'first_options_selected'        => array(
					'default'   => 'no',
					'translate' => false,
				),
				'first_free_options'            => array(
					'default'   => 0,
					'translate' => false,
				),
				'selection_type'                => array(
					'default'   => 'single',
					'translate' => false,
				),
				'enable_min_max'                => array(
					'default'   => 'no',
					'translate' => false,
				),
				'min_max_rule'                  => array(
					'default'   => array( 'min' ),
					'translate' => false,
				),
				'min_max_value'                 => array(
					'default'   => array( 0 ),
					'translate' => false,
				),
				'sell_individually'             => array(
					'default'   => 'no',
					'translate' => false,
				),
				'enable_min_max_numbers'        => array(
					'default'   => 'no',
					'translate' => false,
				),
				'numbers_min'                   => array(
					'default'   => '',
					'translate' => false,
				),
				'numbers_max'                   => array(
					'default'   => '',
					'translate' => false,
				),
				// HTML elements.
				'text_content'                  => array(
					'default'   => '',
					'translate' => true,
				),
				'text_class'                    => array(
					'default'   => '',
					'translate' => false,
				),
				'heading_text'                  => array(
					'default'   => '',
					'translate' => true,
				),
				'heading_type'                  => array(
					'default'   => 'h1',
					'translate' => false,
				),
				'heading_color'                 => array(
					'default'   => '#ee2852',
					'translate' => false,
				),
				'heading_class'                 => array(
					'default'   => '',
					'translate' => false,
				),
				'separator_style'               => array(
					'default'   => 'solid_border',
					'translate' => false,
				),
				'separator_class'               => array(
					'default'   => '',
					'translate' => false,
				),
				'separator_width'               => array(
					'default'   => 100,
					'translate' => false,
				),
				'separator_size'                => array(
					'default'   => 2,
					'translate' => false,
				),
				'separator_color'               => array(
					'default'   => '#ee2852',
					'translate' => false,
				),
				// Rules.
				'conditional_logic'             => array(
					'default'   => array(),
					'translate' => false,
				),
			);

			foreach ( $default_settings as $setting_id => $setting_value ) {
				$default   = $setting_value['default'];
				$translate = $setting_value['translate'];
				$addon_id  = $setting_id;

				if ( isset( $setting_value['id'] ) ) {
					$addon_id = $setting_value['id'];
				}

				$formatted_settings[ $setting_id ] = $this->get_setting( $addon_id, $default, $translate );
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_formatted_settings', $formatted_settings );
		}

		/**
		 *  Get Setting
		 *
		 * @param string  $option    Option name.
		 * @param string  $default   Default value.
		 * @param boolean $translate Translate the setting or not.
		 *
		 * @return string
		 */
		public function get_setting( $option, $default = '', $translate = true ) {

			$value = isset( $this->settings[ $option ] ) && ! empty( $this->settings[ $option ] ) ? $this->settings[ $option ] : $default;
			if ( is_string( $value ) && Qode_Product_Extra_Options_For_WooCommerce_Main::$is_wpml_installed && $translate ) {
				$value = Qode_Product_Extra_Options_For_WooCommerce_WPML::string_translate( $value );
			}
			/**
			 *
			 * Get setting of a specific option of an add-on..
			 *
			 * @param string $value      the value
			 * @param string  $option    the option
			 * @param string  $default   the default value
			 */
			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_addon_' . $option . '_settings', $value, $option, $default );
		}

		/**
		 *  Get Option
		 *
		 * @param string $option Option name.
		 * @param int    $index Option index.
		 * @param string $default Default value.
		 * @param bool   $translate Translate the option or not.
		 *
		 * @return string
		 */
		public function get_option( $option, $index, $default = '', $translate = true ) {
			$index = $index ? $index : 0;
			if ( is_array( $this->options )
				&& isset( $this->options[ $option ] )
				&& is_array( $this->options[ $option ] )
				&& isset( $this->options[ $option ][ $index ] ) ) {
				if ( Qode_Product_Extra_Options_For_WooCommerce_Main::$is_wpml_installed && $translate ) {
					return Qode_Product_Extra_Options_For_WooCommerce_WPML::string_translate( $this->options[ $option ][ $index ] );
				}
				$option_to_return = $this->options[ $option ][ $index ];
				return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_option', $option_to_return, $option );
			}
			return $default;
		}

		/**
		 *  Get Option Price HTML
		 *
		 * @param int $index Option index.
		 * @param bool $currency
		 * @param int|null $product
		 *
		 * @return string
		 */
		public function get_option_price_html( $index, $currency = false, $product = null ) {

			if ( isset( $_REQUEST['qodef-blocks-cart-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['qodef-blocks-cart-nonce'] ) ), 'qodef-blocks-cart-nonce' ) ) {
				$html_price    = '';
				$product_price = Qode_Product_Extra_Options_For_WooCommerce_Front()->current_product_price;

				$product   = qode_product_extra_options_for_woocommerce_get_global_product();
				$variation = qode_product_extra_options_for_woocommerce_get_global_variation();

				if ( $variation instanceof WC_Product_Variation ) {
					$_variation_id = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_original_product_id', $variation->get_id() );
				}

				$blocks_product_price = floatval( $_POST['price'] ?? ( $variation ? $variation->get_price() : $product->get_price() ) );
				$blocks_product_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_blocks_product_price', $blocks_product_price, $product, $variation );
				$blocks_product_price = $blocks_product_price + ( ( $blocks_product_price / 100 ) * qode_product_extra_options_for_woocommerce_get_tax_rate() );

				$product_price = $blocks_product_price;

				$price_method = $this->get_option( 'price_method', $index, 'free', false );
				$price_type   = $this->get_option( 'price_type', $index, 'fixed', false );

				$option_price      = $this->get_price( $index, true, $product );
				$option_price_sale = $this->get_sale_price( $index, true, $product );
				$option_price      = floatval( str_replace( ',', '.', $option_price ) );
				$option_price_sale = '' !== $option_price_sale ? floatval( str_replace( ',', '.', $option_price_sale ) ) : '';

				if ( 'free' !== $price_method ) {
					if ( 'percentage' === $price_type ) {
						$option_percentage      = $option_price;
						$option_percentage_sale = $option_price_sale;
						$option_price           = ( $product_price / 100 ) * $option_percentage;
						$option_price_sale      = $option_percentage && $option_percentage_sale > 0 ? ( $product_price / 100 ) * $option_percentage_sale : '';
					} elseif ( 'multiplied' === $price_type ) {
						$option_price      = $this->get_price( $index );
						$option_price_sale = '';
					}

					$sign       = '+';
					$sign_class = 'positive';
					if ( $this->get_option( 'price_method', $index, 'free', false ) === 'decrease' ) {
						$sign              = '-';
						$sign_class        = 'negative';
						$option_price_sale = '';
					}

					$sign = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_price_sign', $sign );

					if ( '' !== $option_price ) {

						$option_price      = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_option_price', $option_price );
						$option_price_sale = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_option_price_sale', $option_price_sale );

						if ( '' !== $option_price_sale && floatval( $option_price_sale ) >= 0 ) {
							$html_price = '<small class="qpeofw-option-price"><span class="brackets">(</span><span class="sign ' . $sign_class . '">' . $sign . '</span><del>' . wc_price( $option_price, array( 'currency' => $currency ) ) . '</del> ' . wc_price( $option_price_sale, array( 'currency' => $currency ) ) . '<span class="brackets">)</span></small>';
						} else {
							$html_price = '<small class="qpeofw-option-price"><span class="brackets">(</span><span class="sign ' . $sign_class . '">' . $sign . '</span>' . wc_price( $option_price, array( 'currency' => $currency ) ) . '<span class="brackets">)</span></small>';
						}
					}
				}

				$html_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_option_price_html', $html_price );
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_option_' . $this->id . '_' . $index . '_price_html', $html_price );
		}

		/**
		 *  Get default add-on price.
		 *
		 * @param int     $index Option index.
		 * @return float
		 */
		public function get_default_price( $index ) {
			return $this->get_option( 'price', $index );
		}

		/**
		 *  Get default add-on sale price.
		 *
		 * @param int     $index Option index.
		 * @return float
		 */
		public function get_default_sale_price( $index ) {
			return $this->get_option( 'sale_price', $index );
		}

		/**
		 *  Get add-on price.
		 *
		 * @param int $index Option index.
		 * @param boolean $calculate_taxes Calculate the taxes of the prices.
		 * @param int|null $product
		 *
		 * @return float
		 */
		public function get_price( $index, $calculate_taxes = true, $product = null ) {

			$price        = $this->get_option( 'price', $index );
			$price_method = $this->get_option( 'price_method', $index, 'free', false );
			$price_type   = $this->get_option( 'price_type', $index, 'fixed', false );
			if ( $calculate_taxes ) {
				$price = Qode_Product_Extra_Options_For_WooCommerce_Main::get_instance()->calculate_price_depending_on_tax( $price, $product );
			}

			// TODO: for currency switching.
			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_addon_price', trim( $price ), false, $price_method, $price_type, $index );
		}

		/**
		 *  Get add-on sale price.
		 *
		 * @param int $index Option index.
		 * @param boolean $calculate_taxes Calculate the taxes of the prices.
		 * @param int|null $product
		 *
		 * @return float
		 */
		public function get_sale_price( $index, $calculate_taxes = true, $product = null ) {

			$sale_price   = $this->get_option( 'price_sale', $index );
			$price_method = $this->get_option( 'price_method', $index, 'free', false );
			$price_type   = $this->get_option( 'price_type', $index, 'fixed', false );
			if ( $calculate_taxes ) {
				$sale_price = Qode_Product_Extra_Options_For_WooCommerce_Main::get_instance()->calculate_price_depending_on_tax( $sale_price, $product );
			}

			// TODO: for currency switching.
			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_addon_sale_price', trim( $sale_price ), false, $price_method, $price_type, $index );
		}

		/**
		 * Get the image replacement of the current add-on option.
		 *
		 * @param Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
		 * @param int $x
		 *
		 * @return mixed|string
		 */
		public function get_image_replacement( $addon, $x ) {

			$image_replacement       = '';
			$addon_image_replacement = $addon->get_setting( 'image_replacement', 'no', false );
			$addon_image             = $addon->get_setting( 'image', '', false );
			$option_image            = $addon->get_option( 'image', $x, '', false );

			if ( 'addon' === $addon_image_replacement ) {
				$image_replacement = wp_get_attachment_image_src( $addon_image, 'full' );
			} elseif ( ! empty( $option_image ) && 'options' === $addon_image_replacement ) {
				$image_replacement = wp_get_attachment_image_src( $option_image, 'full' );
			}

			if ( ! empty( $image_replacement[0] ) ) {
				// get only src.
				$image_replacement = $image_replacement[0];
			}

			return $image_replacement;
		}

		/**
		 * Get the add-on image position of the current index
		 *
		 * @param $x
		 *
		 * @return string
		 */
		public function get_image_position( $x ) {

			$image_position      = '';
			$setting_hide_images = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_single_options_hide_images' );

			if ( wc_string_to_bool( $setting_hide_images ) ) {
				return $image_position;
			}

			$show_image   = $this->get_option( 'show_image', $x, 'no', false );
			$option_image = $this->get_option( 'image', $x, '', false );

			$hide_options_images    = $this->get_setting( 'hide_options_images', 'no', false );
			$option_images_position = $this->get_setting( 'options_images_position', 'above', false );

			if ( wc_string_to_bool( $show_image ) && ! wc_string_to_bool( $hide_options_images ) && ! empty( $option_image ) ) {
				$image_position = $option_images_position;
			}

			return $image_position;
		}

		/**
		 * Get the array of options of the Configuration tab - extended in premium version
		 *
		 * @return array
		 */
		public function get_options_configuration_array() {

			$options = array();

			if ( ! empty( $this ) ) {
				$selection_type = $this->get_setting( 'selection_type', 'single', false );
				$required       = $this->get_setting( 'required', 'no', false );

				$options = array(
					'addon-selection-type' => array(
						// translators: Add-on edit > Option configuration.
						'title'       => esc_html__( 'Selection type', 'qode-product-extra-options-for-woocommerce' ),
						'field'       => array(
							array(
								'name'    => 'addon_selection_type',
								'class'   => 'qodef-select2 qodef-field',
								'type'    => 'select',
								'value'   => $selection_type,
								'options' => array(
									'single'   => qode_product_extra_options_for_woocommerce_get_string_by_addon_type( 'single_option', $this->type ),
									'multiple' => qode_product_extra_options_for_woocommerce_get_string_by_addon_type( 'multiple_options', $this->type ),
								),
							),
						),
						// translators: %1$s is the string "select", "fill", etc. depending on add-on type. %2$s is the string "option" depending on add-on type.
						'description' => qode_product_extra_options_for_woocommerce_get_string_by_addon_type( 'selection_description', $this->type ),
					),
					'addon-required'       => array(
						// translators: Add-on edit > Option configuration.
						'title'       => esc_html__( 'Force User to Select an Option', 'qode-product-extra-options-for-woocommerce' ),
						'field'       => array(
							array(
								'name'    => 'addon_required',
								'type'    => 'yesno-radio',
								'default' => 'no',
								'value'   => $required,
							),
						),
						// translators: Add-on edit > Option configuration.
						'description' => esc_html__( 'Enable to force the user to select an option of the select to proceed with the purchase', 'qode-product-extra-options-for-woocommerce' ),
					),
				);
			}

			return $this->get_options_by_addon_type( $options, $this->type, 'configuration' );
		}

		/**
		 * Get the array of options of the Display & Style tab - extended in premium version
		 *
		 * @return array
		 */
		public function get_options_display_style_array() {
			return array();
		}

		/**
		 * Get the array of options for time in date addon - extended in premium version $index is REQUIRED
		 *
		 * @return array
		 */
		public function create_availability_time_array( $index ) {
			return array();
		}

		/**
		 * Get correct options by addon type.
		 *
		 * @param array $options The options array.
		 * @param string $addon_type The addon type.
		 * @param string $option_tab String to differenciate the different options. 'configuration' | 'style'
		 *
		 * @return array
		 */
		public function get_options_by_addon_type( $options, $addon_type, $option_tab ) {

			$needed_options = array();
			$options_type   = qode_product_extra_options_for_woocommerce_get_configuration_options_by_type( $addon_type, $option_tab );

			foreach ( $options_type as $option ) {
				if ( array_key_exists( $option, $options ) ) {
					$needed_options[ $option ] = $options[ $option ];
				}
			}

			return $needed_options;
		}
	}
}
