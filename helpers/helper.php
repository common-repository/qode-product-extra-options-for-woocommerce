<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_is_installed' ) ) {
	/**
	 * Function check is some plugin is installed
	 *
	 * @param string $plugin name
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_is_installed( $plugin ) {
		switch ( $plugin ) :
			case 'qpeofw-premium':
				return class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_Premium' );
			case 'wpbakery':
				return class_exists( 'WPBakeryVisualComposerAbstract' );
			case 'elementor':
				return defined( 'ELEMENTOR_VERSION' );
			case 'woocommerce':
				return class_exists( 'WooCommerce' );
			case 'wpml':
				return defined( 'ICL_SITEPRESS_VERSION' );
			case 'multi-currency-switcher':
				// TODO: probably to remove multy currency switcher.
				return defined( 'QODE_WCMCS_INIT' );
			case 'multi-vendor':
				// TODO: probably to remove multi vendor.
				return defined( 'QODE_WPV_PREMIUM' );
			case 'composite':
				// TODO: probably to remove composite comatibility.
				return defined( 'QODE_WCP_PREMIUM' );
			default:
				return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_is_plugin_installed', false, $plugin );

		endswitch;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_execute_template_with_params' ) ) {
	/**
	 * Loads module template part.
	 *
	 * @param string $template path to template that is going to be included
	 * @param array $params params that are passed to template
	 *
	 * @return string - template html
	 */
	function qode_product_extra_options_for_woocommerce_execute_template_with_params( $template, $params ) {
		if ( ! empty( $template ) && file_exists( $template ) ) {
			// Extract params so they could be used in template.
			if ( is_array( $params ) && count( $params ) ) {
				// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
				extract( $params, EXTR_SKIP );
			}

			ob_start();
			include $template;
			$html = ob_get_clean();

			return $html;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_sanitize_module_template_part' ) ) {
	/**
	 * Sanitize module template part.
	 *
	 * @param string $template temp path to file that is being loaded
	 *
	 * @return string - string with template path
	 */
	function qode_product_extra_options_for_woocommerce_sanitize_module_template_part( $template ) {
		$available_characters = '/[^A-Za-z0-9\_\-\/]/';

		if ( ! empty( $template ) && is_scalar( $template ) ) {
			$template = preg_replace( $available_characters, '', $template );
		} else {
			$template = '';
		}

		return $template;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_template_with_slug' ) ) {
	/**
	 * Loads module template part.
	 *
	 * @param string $temp temp path to file that is being loaded
	 * @param string $slug slug that should be checked if exists
	 *
	 * @return string - string with template path
	 */
	function qode_product_extra_options_for_woocommerce_get_template_with_slug( $temp, $slug ) {
		$template = '';

		if ( ! empty( $temp ) ) {
			$slug = qode_product_extra_options_for_woocommerce_sanitize_module_template_part( $slug );

			if ( ! empty( $slug ) ) {
				$template = "$temp-$slug.php";

				if ( ! file_exists( $template ) ) {
					$template = $temp . '.php';
				}
			} else {
				$template = $temp . '.php';
			}
		}

		return $template;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_template_part' ) ) {
	/**
	 * Loads module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 *
	 * @return string - string containing html of template
	 */
	function qode_product_extra_options_for_woocommerce_get_template_part( $module, $template, $slug = '', $params = array() ) {
		$module   = qode_product_extra_options_for_woocommerce_sanitize_module_template_part( $module );
		$template = qode_product_extra_options_for_woocommerce_sanitize_module_template_part( $template );

		$temp = QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/' . $module . '/' . $template;

		$template = qode_product_extra_options_for_woocommerce_get_template_with_slug( $temp, $slug );

		return qode_product_extra_options_for_woocommerce_execute_template_with_params( $template, $params );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_template_part' ) ) {
	/**
	 * Echo module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 */
	function qode_product_extra_options_for_woocommerce_template_part( $module, $template, $slug = '', $params = array() ) {
		$module_template_part = qode_product_extra_options_for_woocommerce_get_template_part( $module, $template, $slug, $params );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo qode_product_extra_options_for_woocommerce_framework_wp_kses_html( 'html', $module_template_part );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_addon_tabs' ) ) {
	/**
	 * Get add-ons tabs.
	 *
	 * @param int    $addon_id The add-on id.
	 * @param string $addon_type The add-on type.
	 *
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_addon_tabs( $addon_id, $addon_type ) {

		$tabs = array(
			'populate'          => array(
				'id'    => 'qodef-options-list',
				'class' => 'qodef-selected',
				'label' => esc_html__( 'OPTION SETTINGS', 'qode-product-extra-options-for-woocommerce' ),
			),
			'advanced'          => array(
				'id'    => 'qodef-advanced-settings',
				'class' => '',
				'label' => esc_html__( 'Option Configuration', 'qode-product-extra-options-for-woocommerce' ),
			),
			'conditional-logic' => array(
				'id'    => 'qodef-conditional-logic',
				'class' => '',
				'label' => esc_html__( 'Conditional Logic', 'qode-product-extra-options-for-woocommerce' ),
			),
		);

		// TODO: premium version is set but check if maybe this is bug since it is disabled for premium version.
		if ( ! defined( 'QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_PREMIUM_VERSION' ) && 'radio' === $addon_type ) {
			unset( $tabs['advanced'] );
		}

		return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_addon_tabs', $tabs, $addon_id, $addon_type );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_calculate_price_depending_on_tax' ) ) {
	/**
	 * Calculate the price with the tax included if necessary.
	 *
	 * @param int $price The price added.
	 *
	 * @param null $product
	 *
	 * @return float|int|mixed
	 */
	function qode_product_extra_options_for_woocommerce_calculate_price_depending_on_tax( $price = 0, $product = null ) {

		if ( ! wc_tax_enabled() ) {
			return $price;
		}

		if ( 0 !== $price && '' !== $price ) {

			if ( get_option( 'woocommerce_calc_taxes', 'no' ) === 'yes' ) {
				// Calculate the addons tax based on the product.
				$wc_tax_rates      = $product ? WC_Tax::get_rates( $product->get_tax_class() ) : WC_Tax::get_rates();
				$wc_tax_rate       = reset( $wc_tax_rates )['rate'] ?? 0;
				$price_include_tax = get_option( 'woocommerce_prices_include_tax' );
				if ( is_cart() || is_checkout() ) {
					$tax_display_cart = get_option( 'woocommerce_tax_display_cart' );

					if ( 'no' === $price_include_tax && 'incl' === $tax_display_cart ) {
						$price += floatval( $price ) * floatval( $wc_tax_rate / 100 );
					}
					if ( 'yes' === $price_include_tax && 'excl' === $tax_display_cart ) {
						$price = $wc_tax_rate > 0 ? ( 100 * $price ) / ( 100 + $wc_tax_rate ) : $price;
					}
				} else {
					$tax_display_shop = get_option( 'woocommerce_tax_display_shop' );
					if ( 'no' === $price_include_tax && 'incl' === $tax_display_shop ) {
						$price += floatval( $price ) * floatval( $wc_tax_rate / 100 );
					}

					if ( 'yes' === $price_include_tax && 'excl' === $tax_display_shop ) {
						$price = $wc_tax_rate > 0 ? ( 100 * $price ) / ( 100 + $wc_tax_rate ) : $price;
					}

					if ( 'yes' === $price_include_tax && 'incl' === $tax_display_shop && $product instanceof WC_Product ) {
						$price = $wc_tax_rate > 0 ? $price : wc_get_price_excluding_tax( $product, array( 'price' => $price ) );
					}
				}
			}
		}

		return $price;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_option_value' ) ) {
	/**
	 * Function that returns option value using framework function but providing its own scope
	 *
	 * @param string $type option type
	 * @param string $name name of option
	 * @param string $default_value option default value
	 * @param int $post_id id of
	 *
	 * @return string value of option
	 */
	function qode_product_extra_options_for_woocommerce_get_option_value( $type, $name, $default_value = '', $post_id = null ) {
		$scope = QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_OPTIONS_NAME;

		return qode_product_extra_options_for_woocommerce_framework_get_option_value( $scope, $type, $name, $default_value, $post_id );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_post_value_through_levels' ) ) {
	/**
	 * Function that returns meta value if exists, otherwise global value using framework function but providing its own scope
	 *
	 * @param string $name name of option
	 * @param int $post_id id of
	 *
	 * @return string|array value of option
	 */
	function qode_product_extra_options_for_woocommerce_get_post_value_through_levels( $name, $post_id = null ) {
		$scope = QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_OPTIONS_NAME;

		return qode_product_extra_options_for_woocommerce_framework_get_post_value_through_levels( $scope, $name, $post_id );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_string_ends_with' ) ) {
	/**
	 * Checks if $haystack ends with $needle and returns proper bool value
	 *
	 * @param string $haystack - to check
	 * @param string $needle - on end to match
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_string_ends_with( $haystack, $needle ) {
		if ( '' !== $haystack && '' !== $needle ) {
			return ( substr( $haystack, - strlen( $needle ), strlen( $needle ) ) == $needle );
		}

		return false;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_string_ends_with_typography_units' ) ) {
	/**
	 * Checks if $haystack ends with predefined needles and returns proper bool value
	 *
	 * @param string $haystack - to check
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_string_ends_with_typography_units( $haystack ) {
		$result  = false;
		$needles = array( 'px', 'em', 'rem', 'vh', 'vw' );

		if ( '' !== $haystack ) {
			foreach ( $needles as $needle ) {
				if ( qode_product_extra_options_for_woocommerce_string_ends_with( $haystack, $needle ) ) {
					$result = true;
				}
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_string_by_addon_type' ) ) {
	/**
	 * Return a string depending on add-on type.
	 *
	 * @param string $key The key of the array of values.
	 * @param string $addon_type The add-on type.
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_string_by_addon_type( $key, $addon_type ) {
		$str_values = array(
			'checkbox' => array(
				'add_new' => _x( 'Add a new', 'Add-on editor panel > Add a new + add-on name (fem)', 'qode-product-extra-options-for-woocommerce' ),
			),
			'text'     => array(
				'single_option'                => esc_html__( 'Single - Users can fill ONE of the available fields', 'qode-product-extra-options-for-woocommerce' ),
				'multiple_options'             => esc_html__( 'Multiple - Users can fill MULTIPLE fields', 'qode-product-extra-options-for-woocommerce' ),
				'selection_description'        => esc_html__( 'Choose if users can fill one or multiple fields.', 'qode-product-extra-options-for-woocommerce' ),
				'first_options'                => esc_html__( 'Set the first fields selected as free', 'qode-product-extra-options-for-woocommerce' ),
				'first_options_description'    => sprintf(
					// Translators: %1$s number of free items, and pay from %2$s item.
					esc_html__( 'Enable to set a specific number of fields as free. %1$s For example, the first three "pizza toppings" are free, included in the product price. %2$s Users will pay from the fourth topping on.', 'qode-product-extra-options-for-woocommerce' ),
					'<br>',
					'<br>'
				),
				'select_free'                  => esc_html__( 'Users can fill for free', 'qode-product-extra-options-for-woocommerce' ),
				'can_select_for_free'          => esc_html__( 'Set how many fields users can fill for free.', 'qode-product-extra-options-for-woocommerce' ),
				'force_select'                 => esc_html__( 'Force user to fill fields of this block', 'qode-product-extra-options-for-woocommerce' ),
				'force_select_description'     => esc_html__( 'Enable to force users to fill fields to proceed with the purchase.', 'qode-product-extra-options-for-woocommerce' ),
				'proceed_purchase'             => esc_html__( 'To proceed with the purchase, users have to fill', 'qode-product-extra-options-for-woocommerce' ),
				'proceed_purchase_description' => esc_html__( 'Set how many fields need to be filled in order to add a product to cart.', 'qode-product-extra-options-for-woocommerce' ),
				'can_select_max'               => esc_html__( 'Users can fill a max of', 'qode-product-extra-options-for-woocommerce' ),
				'can_select_max_description'   => sprintf(
					// Translators: %s number of fields user can fill.
					esc_html__( 'Optional: set the max number of fields fillable by users in this block. %s Leave empty if users can fill all fields without any limits.', 'qode-product-extra-options-for-woocommerce' ),
					'<br>'
				),
				'options'                      => esc_html__( 'fields', 'qode-product-extra-options-for-woocommerce' ),
			),
			'default'  => array(
				'single_option'                => esc_html__( 'Single - Users can select ONE of the available options', 'qode-product-extra-options-for-woocommerce' ),
				'multiple_options'             => esc_html__( 'Multiple - Users can select MULTIPLE options', 'qode-product-extra-options-for-woocommerce' ),
				'selection_description'        => esc_html__( 'Choose if users can select one or multiple options.', 'qode-product-extra-options-for-woocommerce' ),
				'first_options'                => esc_html__( 'Set the First Options Selected as Free', 'qode-product-extra-options-for-woocommerce' ),
				'first_options_description'    => esc_html__( 'Enable to set a specific number of Options as free. For example, the first three "pizza toppings" are free, included in the product price. Users will pay from the fourth topping on', 'qode-product-extra-options-for-woocommerce' ),
				'select_free'                  => esc_html__( 'Users Can Select for Free', 'qode-product-extra-options-for-woocommerce' ),
				'can_select_for_free'          => esc_html__( 'Set how many Options users can select for free', 'qode-product-extra-options-for-woocommerce' ),
				'force_select'                 => esc_html__( 'Force User to Select Options of This Block', 'qode-product-extra-options-for-woocommerce' ),
				'force_select_description'     => esc_html__( 'Enable to force users to select Options to proceed with the purchase', 'qode-product-extra-options-for-woocommerce' ),
				'proceed_purchase'             => esc_html__( 'To Proceed With the Purchase, Users Have to Select', 'qode-product-extra-options-for-woocommerce' ),
				'proceed_purchase_description' => esc_html__( 'Set how many Options need to be selected in order to add a product to cart', 'qode-product-extra-options-for-woocommerce' ),
				'can_select_max'               => esc_html__( 'Users can select a max of', 'qode-product-extra-options-for-woocommerce' ),
				'can_select_max_description'   => esc_html__( 'Optional: set the max number of options selectable by users in this block. Leave empty if users can select all options without any limits.', 'qode-product-extra-options-for-woocommerce' ),
				'min_max_all'                  => esc_html__( 'Set a min/max value among all options', 'qode-product-extra-options-for-woocommerce' ),
				'min_max_all_description'      => esc_html__( 'Enable to force users to enter values that are within a specific range when all options are added together.', 'qode-product-extra-options-for-woocommerce' ),
				'min_max_number'               => esc_html__( 'Sum of options between', 'qode-product-extra-options-for-woocommerce' ),
				'options'                      => esc_html__( 'options', 'qode-product-extra-options-for-woocommerce' ),
				'add_new'                      => _x( 'Add a new', 'Add-on editor panel > Add a new + add-on name (masc)', 'qode-product-extra-options-for-woocommerce' ),
			),
		);

		return $str_values[ $addon_type ][ $key ] ?? $str_values['default'][ $key ];
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_create_time_range' ) ) {

	/**
	 * Create a time range
	 *
	 * @param mixed $start start time, e.g., 7:30am or 7:30
	 * @param mixed $end   end time, e.g., 8:30pm or 20:30
	 * @param string $interval_type time interval type => hour, minutes, seconds.
	 * @param string $interval time intervals, 1 hour, 1 mins, 1 secs, etc.
	 * @param string $format time format, e.g., 12 or 24\
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_create_time_range( $start, $end, $interval_type = 'hours', $interval = '30 mins', $format = '12' ) {

		$start_time         = strtotime( $start );
		$end_time           = strtotime( $end );
		$return_time_format = ( '12' == $format ) ? 'g:i a' : 'G:i';
		if ( 'seconds' === $interval_type ) {
			$return_time_format = ( '12' == $format ) ? 'g:i:s a' : 'G:i:s';
		}

		$current  = time();
		$add_time = strtotime( '+' . $interval, $current );
		$diff     = $add_time - $current;

		$times = array();

		while ( $start_time + $diff <= $end_time ) {
			$times[]     = gmdate( $return_time_format, $start_time );
			$start_time += $diff;
		}
		$times[] = gmdate( $return_time_format, $start_time );

		return $times;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_configuration_options_by_type' ) ) {
	/**
	 * Get the options of each add-on type.
	 *
	 * @param string $addon_type The add-on type
	 * @param string $option_tab The add-on tab
	 *
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_configuration_options_by_type( $addon_type = '', $option_tab = '' ) {
		$options = array();

		if ( $addon_type ) {
			if ( 'configuration' === $option_tab ) {
				switch ( $addon_type ) {
					case 'checkbox':
					case 'text':
					case 'textarea':
					case 'color':
					case 'label':
					case 'colorpicker':
						array_push(
							$options,
							'addon-selection-type',
							'addon-first-options-selected',
							'addon-first-free-options',
							'addon-enable-min-max',
							'addon-min-exa-rules',
							'addon-max-rule',
							'addon-sell-individually'
						);
						break;
					case 'radio':
					case 'date':
						$options[] = 'addon-sell-individually';
						break;
					case 'select':
						array_push(
							$options,
							'addon-required',
							'addon-sell-individually'
						);
						break;
					case 'product':
					case 'file':
						array_push(
							$options,
							'addon-selection-type',
							'addon-enable-min-max',
							'addon-min-exa-rules',
							'addon-max-rule',
							'addon-sell-individually'
						);
						break;
					case 'number':
						array_push(
							$options,
							'addon-selection-type',
							'addon-first-options-selected',
							'addon-first-free-options',
							'addon-enable-min-max',
							'addon-min-exa-rules',
							'addon-max-rule',
							'addon-enable-min-max-all',
							'min-max-number',
							'addon-sell-individually'
						);
				}
			} elseif ( 'style' === $option_tab ) {

				switch ( $addon_type ) {

					case 'checkbox':
					case 'color':
					case 'radio':
					case 'number':
						array_push(
							$options,
							'addon-show-image',
							'addon-image',
							'addon-image-replacement',
							'addon-hide-options-images',
							'addon-options-images-position',
							'addon-show-as-toggle',
							'addon-hide-options-label',
							'addon-hide-options-prices',
							'addon-options-grid-gap',
							'addon-options-per-row',
							'addon-show-in-a-grid',
							'addon-options-per-row-1512',
							'addon-options-per-row-1368',
							'addon-options-per-row-1200',
							'addon-options-per-row-1024',
							'addon-options-per-row-880',
							'addon-options-per-row-680'
						);
						break;
					case 'product':
						array_push(
							$options,
							'addon-show-image',
							'addon-image',
							'addon-show-as-toggle',
							'addon-hide-products-prices',
							'addon-show-sku',
							'addon-show-stock',
							'addon-show-add-to-cart',
							'addon-show-quantity',
							'addon-product-out-of-stock',
							'addon-options-grid-gap',
							'addon-options-per-row',
							'addon-show-in-a-grid',
							'addon-options-per-row-1512',
							'addon-options-per-row-1368',
							'addon-options-per-row-1200',
							'addon-options-per-row-1024',
							'addon-options-per-row-880',
							'addon-options-per-row-680'
						);
						break;
					case 'select':
						array_push(
							$options,
							'addon-show-image',
							'addon-image',
							'addon-image-replacement',
							'addon-hide-options-images',
							'addon-options-images-position',
							'addon-show-as-toggle',
							'addon-hide-options-label',
							'addon-hide-options-prices',
							'addon-select-width'
						);
						break;
					case 'label':
						array_push(
							$options,
							'addon-label-style'
						);
						// intentionally fall trough.
					case 'label':
						array_push(
							$options,
							'addon-show-image',
							'addon-image',
							'addon-image-replacement',
							'addon-hide-options-images',
							'addon-image-equal-height',
							'addon-images-height',
							'addon-options-images-position',
							'addon-show-as-toggle',
							'addon-hide-options-label',
							'addon-hide-options-prices',
							'addon-options-grid-gap',
							'addon-options-per-row',
							'addon-show-in-a-grid',
							'addon-options-per-row-1512',
							'addon-options-per-row-1368',
							'addon-options-per-row-1200',
							'addon-options-per-row-1024',
							'addon-options-per-row-880',
							'addon-options-per-row-680',
							'addon-label-content-align',
							'addon-label-position',
							'addon-description-position',
							'addon-label-padding'
						);
						break;
				}

				switch ( $addon_type ) {
					case 'color':
						array_push(
							$options,
							'addon-color-swatches-style',
							'addon-color-swatches-size'
						);
						// intentionally fall trough.
						// All fields display type option.
					case 'checkbox':
					case 'radio':
					case 'text':
					case 'textarea':
					case 'color':
					case 'number':
					case 'select':
					case 'label':
					case 'product':
					case 'date':
					case 'file':
					case 'colorpicker':
						array_push(
							$options,
							'addon-options-display-type'
						);
						break;
					case 'date':
					case 'text':
					case 'textarea':
					case 'file':
					case 'colorpicker':
						array_push(
							$options,
							'addon-show-image',
							'addon-image',
							'addon-hide-options-images',
							'addon-options-images-position',
							'addon-show-as-toggle',
							'addon-hide-options-label',
							'addon-hide-options-prices',
							'addon-options-grid-gap',
							'addon-options-per-row',
							'addon-show-in-a-grid',
							'addon-options-per-row-1512',
							'addon-options-per-row-1368',
							'addon-options-per-row-1200',
							'addon-options-per-row-1024',
							'addon-options-per-row-880',
							'addon-options-per-row-680'
						);
						break;
				}
			}
		}

		return $options;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_default_configuration_options' ) ) {
	/**
	 * Get the default options for Option configuration tab.
	 *
	 * @return array[]
	 */
	function qode_product_extra_options_for_woocommerce_get_default_configuration_options() {

		$options = array(
			'parent' => array(
				'enabled-by'       => '',
				'data-relation'    => '',
				'title'            => '',
				'field-wrap-class' => '',
				'div-class'        => '',
				'field'            => array(
					array(),
				),
				'description'      => '',
			),
			'field'  => array(
				'title'     => '',
				'div-class' => '',
				'name'      => '',
				'class'     => '',
				'type'      => '',
				'min'       => '',
				'max'       => '',
				'step'      => '',
				'value'     => '',
				'default'   => '',
				'options'   => array(),
				'units'     => '',
			),
		);

		return $options;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_addon_grid_rules' ) ) {
	/**
	 * Return add-on grid rules.
	 *
	 * @param  $addon
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_addon_grid_rules( $addon ) {

		$grid_styles = '';
		$grid_gap    = $addon->get_setting( 'options_grid_gap', 12, false );

		if ( $grid_gap ) {
			$grid_styles .= '
                --qpeofw-gap: ' . $grid_gap . 'px;';
		}

		return $grid_styles;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_render_svg_icon' ) ) {
	/**
	 * Function that print svg html icon
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 */
	function qode_product_extra_options_for_woocommerce_render_svg_icon( $name, $class_name = '' ) {
		$svg_template_part = qode_product_extra_options_for_woocommerce_get_svg_icon( $name, $class_name );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo qode_product_extra_options_for_woocommerce_framework_wp_kses_html( 'html', $svg_template_part );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_svg_icon' ) ) {
	/**
	 * Returns svg html
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_svg_icon( $name, $class_name = '' ) {
		$html  = '';
		$class = isset( $class_name ) && ! empty( $class_name ) ? $class_name : '';

		switch ( $name ) {
			case 'expand':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="92px" height="92px" viewBox="0 0 92 92" enable-background="new 0 0 92 92" xml:space="preserve"><path d="M90,6l0,20c0,2.2-1.8,4-4,4l0,0c-2.2,0-4-1.8-4-4V15.7L58.8,38.9c-0.8,0.8-1.8,1.2-2.8,1.2c-1,0-2-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7L76.3,10H66c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4h20c1.1,0,2.1,0.4,2.8,1.2C89.6,3.9,90,4.9,90,6z M86,62c-2.2,0-4,1.8-4,4v10.3L59.2,53.7c-1.6-1.6-4.2-1.6-5.8,0c-1.6,1.6-1.6,4.1-0.1,5.7L75.9,82H65.6c0,0,0,0,0,0c-2.2,0-4,1.8-4,4s1.8,4,4,4l20,0l0,0c1.1,0,2.3-0.4,3-1.2c0.8-0.8,1.4-1.8,1.4-2.8V66C90,63.8,88.2,62,86,62zM32.8,53.5L10,76.3V66c0-2.2-1.8-4-4-4h0c-2.2,0-4,1.8-4,4l0,20c0,1.1,0.4,2.1,1.2,2.8C4,89.6,5,90,6.1,90h20c2.2,0,4-1.8,4-4c0-2.2-1.8-4-4-4H15.7l22.8-22.8c1.6-1.6,1.5-4.1,0-5.7C37,51.9,34.4,51.9,32.8,53.5z M15.7,10.4l10.3,0h0c2.2,0,4-1.8,4-4s-1.8-4-4-4l-20,0h0c-1.1,0-2.1,0.4-2.8,1.2C2.4,4.3,2,5.3,2,6.4l0,20c0,2.2,1.8,4,4,4c2.2,0,4-1.8,4-4V16l23.1,23.1c0.8,0.8,1.8,1.2,2.8,1.2c1,0,2-0.4,2.8-1.2c1.6-1.6,1.6-4.1,0-5.7L15.7,10.4z"/></svg>';
				break;
			case 'trash':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="14.593" height="16.426" viewBox="0 0 14.593 16.426"><path fill="currentColor" d="M2.504 16.426a1.464 1.464 0 0 1-1.073-.448 1.465 1.465 0 0 1-.448-1.073V2.965h-.984V1.743h4.233V.001h6.126v1.742h4.233v1.222h-.983v11.94a1.465 1.465 0 0 1-.447 1.075 1.465 1.465 0 0 1-1.074.447Zm9.883-13.462H2.205v11.94a.286.286 0 0 0 .087.215.294.294 0 0 0 .212.084h9.584a.286.286 0 0 0 .206-.094.286.286 0 0 0 .094-.206ZM4.853 13.183h1.222v-8.22H4.853Zm3.664 0h1.222v-8.22H8.517ZM2.205 2.961Z"/></svg>';
				break;
			case 'search':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M18.869 19.162l-5.943-6.484c1.339-1.401 2.075-3.233 2.075-5.178 0-2.003-0.78-3.887-2.197-5.303s-3.3-2.197-5.303-2.197-3.887 0.78-5.303 2.197-2.197 3.3-2.197 5.303 0.78 3.887 2.197 5.303 3.3 2.197 5.303 2.197c1.726 0 3.362-0.579 4.688-1.645l5.943 6.483c0.099 0.108 0.233 0.162 0.369 0.162 0.121 0 0.242-0.043 0.338-0.131 0.204-0.187 0.217-0.503 0.031-0.706zM1 7.5c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5-2.916 6.5-6.5 6.5-6.5-2.916-6.5-6.5z"></path></svg>';
				break;
			case 'spinner':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>';
				break;
			case 'chevron-down':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="9" height="6" viewBox="0 0 9 6" fill="currentColor"><path d="M9,1.331a.367.367,0,0,0-.106-.264L7.967.11A.34.34,0,0,0,7.473.1L7.465.11,4.5,3.189,1.535.11A.34.34,0,0,0,1.04.1L1.032.11l-.927.956a.382.382,0,0,0,0,.525l0,0,4.143,4.3a.342.342,0,0,0,.5.006l.006-.006,4.143-4.3A.367.367,0,0,0,9,1.331Z" transform="translate(0 0.001)"/></svg>';
				break;
			case 'upload':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 14 16"><path d="M7.706-13.706a1,1,0,0,0-1.416,0l-4,4a1,1,0,0,0,0,1.416,1,1,0,0,0,1.416,0L6-10.584V-4A1,1,0,0,0,7-3,1,1,0,0,0,8-4v-6.584l2.294,2.294a1,1,0,0,0,1.416,0,1,1,0,0,0,0-1.416l-4-4ZM2-3A1,1,0,0,0,1-4,1,1,0,0,0,0-3v2A3,3,0,0,0,3,2h8a3,3,0,0,0,3-3V-3a1,1,0,0,0-1-1,1,1,0,0,0-1,1v2a1,1,0,0,1-1,1H3A1,1,0,0,1,2-1Z" transform="translate(0 13.999)" fill="currentColor"/></svg>';
				break;
			case 'edit':
				$html = '<svg class="' . esc_attr( $class ) . '" width="13.997" height="14.001" viewBox="0 0 13.997 14.001"><path fill="currentColor" d="M1.556 12.445h1.108l7.6-7.6-1.108-1.108-7.6 7.6ZM0 14.001v-3.306L10.267.448a1.747 1.747 0 0 1 .515-.331 1.536 1.536 0 0 1 .593-.116 1.609 1.609 0 0 1 .6.117 1.366 1.366 0 0 1 .506.35l1.069 1.088a1.271 1.271 0 0 1 .34.506 1.684 1.684 0 0 1 .11.583 1.658 1.658 0 0 1-.107.593 1.457 1.457 0 0 1-.34.515L3.306 14.001ZM12.444 2.645l-1.089-1.089ZM9.7 4.301l-.544-.564 1.108 1.108Z"/></svg>';
				break;
			case 'clone':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="15" height="17" viewBox="0 0 15 17"><g fill="#fff" stroke="currentColor" stroke-width="1.4" transform="translate(0 4)"><rect width="10" height="13" stroke="none" rx="2"/><rect width="8.6" height="11.6" x=".7" y=".7" fill="none" rx="1.3"/></g><g fill="#fff" stroke="currentColor" stroke-width="1.4" transform="translate(5)"><rect width="10" height="13" stroke="none" rx="2"/><rect width="8.6" height="11.6" x=".7" y=".7" fill="none" rx="1.3"/></g></svg>';
				break;
			case 'link':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></svg>';
				break;
			case 'drag':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="16.425" height="16.425" viewBox="0 0 16.425 16.425"><path fill="currentColor" d="m8.212 16.425-3.249-3.249.774-.774 1.94 1.94v-5.6h-5.59l1.886 1.887-.779.773L0 8.208l3.2-3.2.774.774-1.9 1.9h5.595v-5.6L5.783 3.968l-.774-.774L8.203 0l3.194 3.194-.774.774-1.886-1.886v5.6h5.591l-1.886-1.887.779-.773 3.194 3.194-3.194 3.194-.774-.774 1.886-1.886H8.738v5.591l1.936-1.94.779.779Z"/></svg>';
				break;
			case 'close':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>';
				break;
			case 'lock':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><path d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"/></svg>';
				break;
			case 'database':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><path d="M448 80v48c0 44.2-100.3 80-224 80S0 172.2 0 128V80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6V288c0 44.2-100.3 80-224 80S0 332.2 0 288V186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6V432c0 44.2-100.3 80-224 80S0 476.2 0 432V346.1z"/></svg>';
				break;
			case 'html-heading':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-heading"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-heading)"><path d="M54.07 24a.9.9 0 0 1 .66.27.9.9 0 0 1 .27.66v4.68a.948.948 0 0 1-.27.675.874.874 0 0 1-.66.285.9.9 0 0 1-.66-.27.934.934 0 0 1-.27-.69v-3.75H40.93v26.25l2.82.03a.8.8 0 0 1 .66.24.934.934 0 0 1 .27.69.9.9 0 0 1-.27.66.9.9 0 0 1-.66.27h-7.5a.9.9 0 0 1-.66-.27.9.9 0 0 1-.27-.66.948.948 0 0 1 .27-.675.874.874 0 0 1 .66-.285h2.82V25.86H26.89l-.03 3.75a.847.847 0 0 1-.24.675.909.909 0 0 1-.69.285.874.874 0 0 1-.66-.285.948.948 0 0 1-.27-.675v-4.68a.9.9 0 0 1 .27-.66.9.9 0 0 1 .66-.27Z"/></g></svg>';
				break;
			case 'html-separator':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-separator"><path d="M0 0h80v80H0z"/></clipPath></defs><g fill="none" stroke="currentColor" clip-path="url(#clip-separator)"><g stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(19 23)"><rect width="42" height="12" stroke="none" rx="2"/><rect width="40.6" height="10.6" x=".7" y=".7" rx="1.3"/></g><g stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(19 45)"><rect width="42" height="12" stroke="none" rx="2"/><rect width="40.6" height="10.6" x=".7" y=".7" rx="1.3"/></g><path stroke-dasharray="4" d="M19.5 40h41"/></g></svg>';
				break;
			case 'html-text':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="currentColor"><defs><clipPath id="clip-text"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-text)"><path d="M24.134 50.2h.158v.3h-4.488v-.3h.158a.623.623 0 0 0 .442-.18 1.934 1.934 0 0 0 .332-.42 1.776 1.776 0 0 0 .205-.525 2.436 2.436 0 0 0 .063-.525V31.819h-3.665a1.8 1.8 0 0 0-.885.21 2.109 2.109 0 0 0-.632.525 2.235 2.235 0 0 0-.379.69 2.29 2.29 0 0 0-.126.735H15V30.71h14.191v3.268h-.315a2.29 2.29 0 0 0-.126-.735 2.235 2.235 0 0 0-.379-.69 2.109 2.109 0 0 0-.632-.525 1.8 1.8 0 0 0-.885-.21h-3.731v16.733a2.435 2.435 0 0 0 .063.525 1.776 1.776 0 0 0 .205.525 2.257 2.257 0 0 0 .316.42.57.57 0 0 0 .427.179Zm5.91-5.757a7.1 7.1 0 0 0 .537 1.994 5.731 5.731 0 0 0 1.095 1.664 5.089 5.089 0 0 0 1.659 1.154 5.565 5.565 0 0 0 2.276.435 6.026 6.026 0 0 0 1.47-.195 6.156 6.156 0 0 0 1.438-.555 5.061 5.061 0 0 0 1.185-.885 2.739 2.739 0 0 0 .707-1.185h.284v2.249a.963.963 0 0 0-.427.12q-.237.12-.553.3a12.362 12.362 0 0 1-1.707.765 8.24 8.24 0 0 1-2.845.4 9.226 9.226 0 0 1-2.7-.39 6.46 6.46 0 0 1-2.26-1.2 5.807 5.807 0 0 1-1.564-2.069 7.069 7.069 0 0 1-.585-3 6.835 6.835 0 0 1 .4-2.249 6.1 6.1 0 0 1 1.2-2.054 5.99 5.99 0 0 1 2.07-1.484 7.348 7.348 0 0 1 3.034-.57 6.954 6.954 0 0 1 2.765.51 5.3 5.3 0 0 1 1.96 1.409 5.871 5.871 0 0 1 1.154 2.129 9.121 9.121 0 0 1 .379 2.7Zm4.583-5.757a4.088 4.088 0 0 0-1.912.42 4.6 4.6 0 0 0-1.375 1.079 4.94 4.94 0 0 0-.885 1.5 6.978 6.978 0 0 0-.411 1.679h9.008a5.659 5.659 0 0 0-.332-1.709 5.04 5.04 0 0 0-.838-1.5 4.008 4.008 0 0 0-1.359-1.064 4.239 4.239 0 0 0-1.896-.405ZM51.757 50.2q.6 0 .6-.42a1.75 1.75 0 0 0-.379-.66l-3.16-4.137-3.287 4.228a3.264 3.264 0 0 0-.221.33.732.732 0 0 0-.126.36q0 .3.348.3v.3H42.56v-.3a1.708 1.708 0 0 0 .948-.345 3.43 3.43 0 0 0 .853-.825l3.824-4.918-3.54-4.558q-.158-.18-.363-.42a3.279 3.279 0 0 0-.474-.45 3.235 3.235 0 0 0-.537-.345 1.107 1.107 0 0 0-.49-.135v-.3h4.615v.3a.785.785 0 0 0-.4.12.476.476 0 0 0-.205.45.631.631 0 0 0 .19.42l2.56 3.358 2.435-3.057a1.742 1.742 0 0 0 .316-.48 1 1 0 0 0 .063-.33.543.543 0 0 0-.111-.33.386.386 0 0 0-.332-.15v-.3h3.034v.3a1.3 1.3 0 0 0-.948.465q-.442.465-.79.885l-3.066 3.868 4.234 5.427a4.5 4.5 0 0 0 .98.945 1.848 1.848 0 0 0 1.043.4v.3h-4.642Zm9.7-11.274v7.226q0 .78.032 1.409a4.483 4.483 0 0 0 .158 1.049 1.366 1.366 0 0 0 .379.645 1.009 1.009 0 0 0 .7.225 1.733 1.733 0 0 0 1.248-.6 2.742 2.742 0 0 0 .743-1.439h.284v2.189a.662.662 0 0 0-.474.18 4.148 4.148 0 0 1-.711.45 3.065 3.065 0 0 1-1.375.24 3.18 3.18 0 0 1-1.77-.42 2.409 2.409 0 0 1-.918-1.11 4.676 4.676 0 0 1-.332-1.529q-.047-.84-.047-1.709v-6.807h-1.77v-1.019h1.772v-2.669a3.022 3.022 0 0 0-.142-.945q-.142-.435-.932-.465v-.3l3.161-.96v5.337h3.54v1.019Z"/></g></svg>';
				break;
			case 'checkbox':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-checkbox"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-checkbox)"><g stroke="currentColor"><g fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(17.977 26)"><rect width="12" height="12" stroke="none" rx="2"/><rect width="10.6" height="10.6" x=".7" y=".7" rx="1.3"/></g><g fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(17.977 42)"><rect width="12" height="12" stroke="none" rx="2"/><rect width="10.6" height="10.6" x=".7" y=".7" rx="1.3"/></g><path stroke-width=".4" d="m23.015 49.963-2.205-2.212a.433.433 0 0 1-.007-.616.448.448 0 0 1 .623 0l1.9 1.9 2.986-2.992a.427.427 0 0 1 .616 0 .433.433 0 0 1 .007.616l-3.3 3.3a.439.439 0 0 1-.62.004Z"/><path stroke-width=".4" d="m23.015 33.963-2.205-2.212a.433.433 0 0 1-.007-.616.448.448 0 0 1 .623 0l1.9 1.9 2.986-2.992a.427.427 0 0 1 .616 0 .433.433 0 0 1 .007.616l-3.3 3.3a.439.439 0 0 1-.62.004Z"/><path fill="none" stroke-linecap="round" stroke-width="1.4" d="M35.477 32h26.547"/><path fill="none" stroke-linecap="round" stroke-width="1.4" d="M35.477 48h26.547"/></g></g></svg>';
				break;
			case 'radio':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-radio"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-radio)"><g transform="translate(-282 -338)"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(299.977 364)"><rect width="12" height="12" stroke="none" rx="6"/><rect width="10.6" height="10.6" x=".7" y=".7" rx="5.3"/></g><rect width="6" height="6" rx="3" transform="translate(302.977 367)"/><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(299.977 380)"><rect width="12" height="12" stroke="none" rx="6"/><rect width="10.6" height="10.6" x=".7" y=".7" rx="5.3"/></g><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.4" d="M317.477 370h26.547"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.4" d="M317.477 386h26.547"/></g></g></svg>';
				break;
			case 'text':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-input-text"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-input-text)"><g><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(11 30)"><rect width="58" height="20" stroke="none" rx="2"/><rect width="56.6" height="18.6" x=".7" y=".7" rx="1.3"/></g><text font-family="DM Sans" font-size="10" transform="translate(17 43.5)"><tspan x="0" y="0">Text</tspan></text></g></g></svg>';
				break;
			case 'textarea':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-textarea"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-textarea)"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(11 23)"><rect width="58" height="34" stroke="none" rx="2"/><rect width="56.6" height="32.6" x=".7" y=".7" rx="1.3"/></g><text font-family="DM Sans" font-size="10" transform="translate(16 36)"><tspan x="0" y="0">Text</tspan></text><path fill="none" stroke="currentColor" stroke-linecap="round" d="m60.5 53.5 5-5"/><path fill="none" stroke="currentColor" stroke-linecap="round" d="m63.5 53.5 2-2"/></g></svg>';
				break;
			case 'color':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-color-swatch"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-color-swatch)"><path fill="none" stroke="currentColor" stroke-width="1.4" d="M36.566 23.334a16.91 16.91 0 0 0-13.222 13.175C20.887 48.928 32.09 58.185 40.53 56.877a4.192 4.192 0 0 0 2.822-6.09 4.493 4.493 0 0 1 4.047-6.534h5.293a4.345 4.345 0 0 0 4.307-4.337 17.013 17.013 0 0 0-20.433-16.582Zm-7.192 20.919a2.123 2.123 0 0 1-2.125-2.125 2.123 2.123 0 0 1 2.125-2.126 2.123 2.123 0 0 1 2.125 2.126 2.123 2.123 0 0 1-2.125 2.125Zm2.125-8.501a2.123 2.123 0 0 1-2.125-2.125 2.123 2.123 0 0 1 2.125-2.125 2.123 2.123 0 0 1 2.125 2.125 2.123 2.123 0 0 1-2.125 2.125Zm8.5-4.25a2.123 2.123 0 0 1-2.125-2.125 2.123 2.123 0 0 1 2.125-2.125 2.123 2.123 0 0 1 2.125 2.125 2.123 2.123 0 0 1-2.125 2.125Zm8.5 4.25a2.123 2.123 0 0 1-2.125-2.125 2.123 2.123 0 0 1 2.125-2.125 2.123 2.123 0 0 1 2.125 2.125 2.123 2.123 0 0 1-2.125 2.125Z"/></g></svg>';
				break;
			case 'number':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-number"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-number)"><g><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(20 30)"><rect width="40" height="20" stroke="none" rx="2"/><rect width="38.6" height="18.6" x=".7" y=".7" rx="1.3"/></g><text font-family="DM Sans" font-size="10" transform="translate(27 43.5)"><tspan x="0" y="0">0</tspan></text><path fill="rgba(0,0,0,0)" stroke="currentColor" d="M45.5 30.5v19"/></g><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="m49.188 37.402 2.993-2.993 2.993 2.993"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="m55.174 41.964-2.993 2.993-2.993-2.993"/></g></svg>';
				break;
			case 'select':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-select"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-select)"><g><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(11 30)"><rect width="58" height="20" stroke="none" rx="2"/><rect width="56.6" height="18.6" x=".7" y=".7" rx="1.3"/></g><path d="M19.385 43.5v-6.31H17.3v-.69h5.02v.69h-2.095v6.31Zm5.238.12a2.39 2.39 0 0 1-1.255-.329 2.3 2.3 0 0 1-.86-.922 2.935 2.935 0 0 1-.308-1.386 2.959 2.959 0 0 1 .3-1.39 2.269 2.269 0 0 1 .861-.919 2.45 2.45 0 0 1 1.278-.329 2.293 2.293 0 0 1 1.261.329 2.2 2.2 0 0 1 .792.865 2.529 2.529 0 0 1 .273 1.171v.211q0 .111-.01.251h-4.13v-.65h3.313a1.47 1.47 0 0 0-.452-1.081 1.52 1.52 0 0 0-1.063-.386 1.672 1.672 0 0 0-.794.194 1.5 1.5 0 0 0-.59.568 1.8 1.8 0 0 0-.224.936v.28a2.16 2.16 0 0 0 .227 1.041 1.551 1.551 0 0 0 .594.626 1.562 1.562 0 0 0 .786.209 1.515 1.515 0 0 0 .878-.233 1.339 1.339 0 0 0 .5-.641h.83a2.162 2.162 0 0 1-.43.812 2.124 2.124 0 0 1-.744.565 2.419 2.419 0 0 1-1.033.208Zm2.742-.12 1.71-2.52-1.71-2.515h.912l1.368 2.06 1.377-2.06h.9l-1.707 2.515 1.71 2.52h-.9l-1.38-2.072L28.28 43.5Zm7.435 0a1.988 1.988 0 0 1-.78-.14 1 1 0 0 1-.505-.475 1.968 1.968 0 0 1-.175-.9v-2.8h-.875v-.71h.875l.11-1.22h.73v1.22h1.467v.71H34.18v2.8a.825.825 0 0 0 .19.635 1.036 1.036 0 0 0 .67.165h.542v.715Z"/></g><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="m60.001 38.503-2.993 2.993-2.993-2.993"/></g></svg>';
				break;
			case 'label':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-Label-or-image"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-Label-or-image)"><g><g fill="none" stroke-linecap="round"><path d="M13 37h23a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H13a2 2 0 0 1-2-2V39a2 2 0 0 1 2-2Z"/><path fill="currentColor" d="M13 38.4a.6.6 0 0 0-.6.6v12c0 .33.27.6.6.6h23a.6.6 0 0 0 .6-.6V39a.6.6 0 0 0-.6-.6H13m0-1.4h23a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H13a2 2 0 0 1-2-2V39a2 2 0 0 1 2-2Z"/></g><path d="M17.682 47.752v-5.048H16.01v-.552h4.016v.552h-1.672v5.048Zm4.192.1a1.912 1.912 0 0 1-1-.263 1.839 1.839 0 0 1-.694-.737 2.348 2.348 0 0 1-.25-1.109 2.367 2.367 0 0 1 .244-1.117 1.815 1.815 0 0 1 .689-.735 1.96 1.96 0 0 1 1.022-.263 1.834 1.834 0 0 1 1 .263 1.758 1.758 0 0 1 .634.692 2.023 2.023 0 0 1 .22.937v.169q0 .089-.008.2h-3.297v-.52h2.65a1.176 1.176 0 0 0-.362-.865 1.216 1.216 0 0 0-.85-.309 1.337 1.337 0 0 0-.635.155 1.2 1.2 0 0 0-.472.454 1.437 1.437 0 0 0-.179.749v.224a1.728 1.728 0 0 0 .182.833 1.241 1.241 0 0 0 .475.5 1.25 1.25 0 0 0 .631.17 1.212 1.212 0 0 0 .7-.187 1.072 1.072 0 0 0 .4-.513h.66a1.729 1.729 0 0 1-.341.65 1.7 1.7 0 0 1-.6.452 1.935 1.935 0 0 1-.819.17Zm2.192-.1 1.368-2.016-1.368-2.012h.73l1.094 1.648 1.1-1.648h.722l-1.366 2.012 1.368 2.016h-.722l-1.1-1.658-1.094 1.658Zm5.948 0a1.59 1.59 0 0 1-.624-.112.8.8 0 0 1-.4-.38 1.574 1.574 0 0 1-.14-.724v-2.244h-.7v-.568h.7l.084-.972h.584v.976h1.174v.568h-1.174v2.244a.66.66 0 0 0 .152.508.828.828 0 0 0 .536.132h.434v.572Z"/></g><g><g><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(42 28)"><rect width="27" height="24" stroke="none" rx="2"/><rect width="25.6" height="22.6" x=".7" y=".7" rx="1.3"/></g></g><g fill="none" stroke="currentColor" transform="translate(45 31)"><circle cx="3" cy="3" r="3" stroke="none"/><circle cx="3" cy="3" r="2.5"/></g><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M43.344 46.23H56.18l5.655-6.81 5.794 6.81"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="m58.274 42.824-2.965-2.955-6.606 6.143"/></g><path fill="none" stroke="currentColor" stroke-dasharray="2" d="M24.681 37.091s2.373-9.869 16.293-6.139"/></g></svg>';
				break;
			case 'product':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-product"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-product)"><g transform="translate(-425.393 -351.182)"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" transform="translate(445.393 367.182)"><rect width="41" height="49" stroke="none" rx="2"/><rect width="39.6" height="47.6" x=".7" y=".7" rx="1.3"/></g><rect width="27" height="1" rx=".5" transform="translate(452.393 403.182)"/><rect width="17" height="1" rx=".5" transform="translate(452.393 408.182)"/></g><path fill="none" stroke="currentColor" stroke-width="1.4" d="M35.938 23a.813.813 0 0 1 .718.549 3.76 3.76 0 0 0 1.436 2.026 4.116 4.116 0 0 0 2.407.8 4.116 4.116 0 0 0 2.407-.8 3.985 3.985 0 0 0 1.478-2.027.732.732 0 0 1 .676-.549h.549a3.971 3.971 0 0 1 2.576.929l5.364 4.435a1.39 1.39 0 0 1 .465.971 1.256 1.256 0 0 1-.338.971l-2.365 2.7a1.316 1.316 0 0 1-.887.465 1.337 1.337 0 0 1-.971-.3l-2.2-1.816v10.559a2.73 2.73 0 0 1-.8 1.9 2.73 2.73 0 0 1-1.9.8h-8.108a2.73 2.73 0 0 1-1.9-.8 2.73 2.73 0 0 1-.8-1.9v-10.55l-2.153 1.812a1.417 1.417 0 0 1-.971.3 1.35 1.35 0 0 1-.929-.465l-2.37-2.703a1.256 1.256 0 0 1-.338-.971 1.33 1.33 0 0 1 .508-.972l5.322-4.435A3.971 3.971 0 0 1 35.392 23Z"/></g></svg>';
				break;
			case 'date':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><defs><clipPath id="clip-date"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-date)"><g fill="none" stroke="currentColor" stroke-linecap="round"><g stroke-linejoin="round" stroke-width="1.4" transform="translate(19.5 23.75)"><rect width="41" height="35" stroke="none" rx="2"/><rect width="39.6" height="33.6" x=".7" y=".7" rx="1.3"/></g><g stroke-linejoin="round" transform="translate(38.5 36.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(38.5 42.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(38.5 48.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(32.5 36.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(32.5 42.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(32.5 48.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(26.5 42.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(26.5 48.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(44.5 36.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(44.5 42.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(50.5 36.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><g stroke-linejoin="round" transform="translate(50.5 42.75)"><rect width="4" height="4" stroke="none" rx="1"/><rect width="3" height="3" x=".5" y=".5" rx=".5"/></g><path d="M20 32.25h39"/><path stroke-width="1.4" d="M31 27.25v-6"/><path stroke-width="1.4" d="M50 27.25v-6"/></g></g></svg>';
				break;
			case 'file':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="currentColor"><defs><clipPath id="clip-file-upload"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-file-upload)"><path d="M40 58.5a7.7 7.7 0 0 1-3.143-.65 8.116 8.116 0 0 1-2.573-1.78 8.5 8.5 0 0 1-1.739-2.647 8.225 8.225 0 0 1-.641-3.234v-22.2a6.5 6.5 0 0 1 .492-2.52 6.424 6.424 0 0 1 1.352-2.06 6.558 6.558 0 0 1 2-1.391 5.867 5.867 0 0 1 2.441-.515 5.909 5.909 0 0 1 2.465.512 6.45 6.45 0 0 1 1.993 1.391 6.624 6.624 0 0 1 1.352 2.06 6.4 6.4 0 0 1 .5 2.52v20.342a4.613 4.613 0 0 1-.351 1.8 4.681 4.681 0 0 1-.966 1.472 4.536 4.536 0 0 1-1.431.994 4.269 4.269 0 0 1-1.747.361 4.27 4.27 0 0 1-1.75-.363 4.536 4.536 0 0 1-1.431-.994 4.681 4.681 0 0 1-.969-1.472 4.613 4.613 0 0 1-.351-1.8v-11.09a.9.9 0 0 1 .263-.65.85.85 0 0 1 .632-.271.85.85 0 0 1 .632.271.9.9 0 0 1 .263.65v11.092a2.721 2.721 0 0 0 .79 1.969 2.571 2.571 0 0 0 1.914.813 2.551 2.551 0 0 0 1.9-.813 2.721 2.721 0 0 0 .79-1.969V27.986a4.613 4.613 0 0 0-.351-1.8 4.76 4.76 0 0 0-.957-1.472 4.408 4.408 0 0 0-1.431-.994 4.331 4.331 0 0 0-1.756-.361 4.269 4.269 0 0 0-1.747.361 4.434 4.434 0 0 0-1.422.994 4.892 4.892 0 0 0-.966 1.472 4.515 4.515 0 0 0-.36 1.8v22.2a6.337 6.337 0 0 0 .5 2.511 6.767 6.767 0 0 0 1.351 2.06 6.226 6.226 0 0 0 2 1.391 6.018 6.018 0 0 0 2.452.509 5.957 5.957 0 0 0 2.441-.506 6.356 6.356 0 0 0 2-1.391 6.558 6.558 0 0 0 1.352-2.06 6.436 6.436 0 0 0 .492-2.511V37.236a.9.9 0 0 1 .263-.65.85.85 0 0 1 .632-.271.87.87 0 0 1 .65.271.9.9 0 0 1 .263.65v12.953a8.225 8.225 0 0 1-.639 3.234 8.5 8.5 0 0 1-1.738 2.647 8.116 8.116 0 0 1-2.573 1.78A7.7 7.7 0 0 1 40 58.5Z"/></g></svg>';
				break;
			case 'colorpicker':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="currentColor"><defs><clipPath id="clip-color-picker"><path d="M0 0h80v80H0z"/></clipPath></defs><g clip-path="url(#clip-color-picker)"><path d="M54 30.424a4.423 4.423 0 0 1-.336 1.708 4.353 4.353 0 0 1-.952 1.428l-3.892 3.892a2.712 2.712 0 0 1 .252 1.176 2.57 2.57 0 0 1-.77 1.89 2.537 2.537 0 0 1-1.862.77 2.834 2.834 0 0 1-1.176-.252l-10.22 10.192a.838.838 0 0 1-.616.252h-2.156L30 53.748a.838.838 0 0 1-.612.252.838.838 0 0 1-.616-.252l-2.52-2.52a.838.838 0 0 1-.252-.616.838.838 0 0 1 .252-.612l2.268-2.272v-2.156a.838.838 0 0 1 .252-.616l10.192-10.22a2.834 2.834 0 0 1-.252-1.176 2.514 2.514 0 0 1 .756-1.848 2.7 2.7 0 0 1 1.9-.756 2.647 2.647 0 0 1 1.148.252l3.92-3.92a4.353 4.353 0 0 1 1.428-.952A4.423 4.423 0 0 1 49.576 26a4.234 4.234 0 0 1 2.212.6 4.586 4.586 0 0 1 1.61 1.61A4.234 4.234 0 0 1 54 30.424ZM30.284 45.936v2.156a.856.856 0 0 1-.28.616l-1.9 1.9 1.284 1.292 1.904-1.9a.856.856 0 0 1 .616-.28h2.156l9.856-9.86-3.78-3.78Zm16.772-6.692a.828.828 0 0 0 .28-.644.8.8 0 0 0-.28-.616l-5.04-5.04a.8.8 0 0 0-.616-.28.828.828 0 0 0-.644.28.885.885 0 0 0-.28.644 1.026 1.026 0 0 0 .28.644l5.012 5.012a1.026 1.026 0 0 0 .644.28.885.885 0 0 0 .644-.28Zm4.424-6.916a2.592 2.592 0 0 0 .784-1.9 2.583 2.583 0 0 0-.8-1.89 2.583 2.583 0 0 0-1.89-.8 2.592 2.592 0 0 0-1.9.784l-3.78 3.808 3.78 3.78Z"/></g></svg>';
				break;
			default:
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><path d="M256 0c53 0 96 43 96 96v3.6c0 15.7-12.7 28.4-28.4 28.4H188.4c-15.7 0-28.4-12.7-28.4-28.4V96c0-53 43-96 96-96zM41.4 105.4c12.5-12.5 32.8-12.5 45.3 0l64 64c.7 .7 1.3 1.4 1.9 2.1c14.2-7.3 30.4-11.4 47.5-11.4H312c17.1 0 33.2 4.1 47.5 11.4c.6-.7 1.2-1.4 1.9-2.1l64-64c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-64 64c-.7 .7-1.4 1.3-2.1 1.9c6.2 12 10.1 25.3 11.1 39.5H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H416c0 24.6-5.5 47.8-15.4 68.6c2.2 1.3 4.2 2.9 6 4.8l64 64c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0l-63.1-63.1c-24.5 21.8-55.8 36.2-90.3 39.6V240c0-8.8-7.2-16-16-16s-16 7.2-16 16V479.2c-34.5-3.4-65.8-17.8-90.3-39.6L86.6 502.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l64-64c1.9-1.9 3.9-3.4 6-4.8C101.5 367.8 96 344.6 96 320H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H96.3c1.1-14.1 5-27.5 11.1-39.5c-.7-.6-1.4-1.2-2.1-1.9l-64-64c-12.5-12.5-12.5-32.8 0-45.3z"/></svg>';
		}

		return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_svg_icon', $html, $name, $class_name );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_class_attribute' ) ) {
	/**
	 * Function that echoes class attribute
	 *
	 * @param string|array $value - value of class attribute
	 *
	 * @see qode_product_extra_options_for_woocommerce_get_class_attribute()
	 */
	function qode_product_extra_options_for_woocommerce_class_attribute( $value ) {
		echo wp_kses_post( qode_product_extra_options_for_woocommerce_get_class_attribute( $value ) );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_class_attribute' ) ) {
	/**
	 * Function that returns generated class attribute
	 *
	 * @param string|array $value - value of class attribute
	 *
	 * @return string generated class attribute
	 *
	 * @see qode_product_extra_options_for_woocommerce_get_inline_attr()
	 */
	function qode_product_extra_options_for_woocommerce_get_class_attribute( $value ) {
		return qode_product_extra_options_for_woocommerce_get_inline_attr( $value, 'class', ' ' );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_id_attribute' ) ) {
	/**
	 * Function that echoes id attribute
	 *
	 * @param string|array $value - value of id attribute
	 *
	 * @see qode_product_extra_options_for_woocommerce_get_id_attribute()
	 */
	function qode_product_extra_options_for_woocommerce_id_attribute( $value ) {
		echo wp_kses_post( qode_product_extra_options_for_woocommerce_get_id_attribute( $value ) );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_id_attribute' ) ) {
	/**
	 * Function that returns generated id attribute
	 *
	 * @param string|array $value - value of id attribute
	 *
	 * @return string generated id attribute
	 *
	 * @see qode_product_extra_options_for_woocommerce_get_inline_attr()
	 */
	function qode_product_extra_options_for_woocommerce_get_id_attribute( $value ) {
		return qode_product_extra_options_for_woocommerce_get_inline_attr( $value, 'id', ' ' );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_inline_style' ) ) {
	/**
	 * Function that echoes generated style attribute
	 *
	 * @param string|array $value - attribute value
	 *
	 * @see qode_product_extra_options_for_woocommerce_get_inline_style()
	 */
	function qode_product_extra_options_for_woocommerce_inline_style( $value ) {
		$inline_style_part = qode_product_extra_options_for_woocommerce_get_inline_style( $value );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo qode_product_extra_options_for_woocommerce_framework_wp_kses_html( 'attributes', $inline_style_part );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_inline_style' ) ) {
	/**
	 * Function that generates style attribute and returns generated string
	 *
	 * @param string|array $value - value of style attribute
	 *
	 * @return string generated style attribute
	 *
	 * @see qode_product_extra_options_for_woocommerce_get_inline_style()
	 */
	function qode_product_extra_options_for_woocommerce_get_inline_style( $value ) {
		return qode_product_extra_options_for_woocommerce_get_inline_attr( $value, 'style', ';' );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_inline_attrs' ) ) {
	/**
	 * Echo multiple inline attributes
	 *
	 * @param array $attrs
	 * @param bool $allow_zero_values
	 */
	function qode_product_extra_options_for_woocommerce_inline_attrs( $attrs, $allow_zero_values = false ) {
		$inline_attrs_part = qode_product_extra_options_for_woocommerce_get_inline_attrs( $attrs, $allow_zero_values );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo qode_product_extra_options_for_woocommerce_framework_wp_kses_html( 'attributes', $inline_attrs_part );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_inline_attrs' ) ) {
	/**
	 * Generate multiple inline attributes
	 *
	 * @param array $attrs
	 * @param bool $allow_zero_values
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_inline_attrs( $attrs, $allow_zero_values = false ) {
		$output = '';
		if ( is_array( $attrs ) && count( $attrs ) ) {
			if ( $allow_zero_values ) {
				foreach ( $attrs as $attr => $value ) {
					$output .= ' ' . qode_product_extra_options_for_woocommerce_get_inline_attr( $value, $attr, '', true );
				}
			} else {
				foreach ( $attrs as $attr => $value ) {
					$output .= ' ' . qode_product_extra_options_for_woocommerce_get_inline_attr( $value, $attr );
				}
			}
		}

		$output = ltrim( $output );

		return $output;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_inline_attr' ) ) {
	/**
	 * Function that generates html attribute
	 *
	 * @param string|array $value value of html attribute
	 * @param string $attr - name of html attribute to generate
	 * @param string $glue - glue with which to implode $attr. Used only when $attr is arrayed
	 * @param bool $allow_zero_values - allow data to have zero value
	 *
	 * @return string generated html attribute
	 */
	function qode_product_extra_options_for_woocommerce_get_inline_attr( $value, $attr, $glue = '', $allow_zero_values = false ) {
		if ( $allow_zero_values ) {
			if ( '' !== $value ) {

				if ( is_array( $value ) && count( $value ) ) {
					$properties = implode( $glue, $value );
				} else {
					$properties = $value;
				}

				return $attr . '="' . esc_attr( $properties ) . '"';
			}
		} else {
			if ( ! empty( $value ) ) {

				if ( is_array( $value ) && count( $value ) ) {
					$properties = implode( $glue, $value );
				} elseif ( '' !== $value ) {
					$properties = $value;
				} else {
					return '';
				}

				return $attr . '="' . esc_attr( $properties ) . '"';
			}
		}

		return '';
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_string_ends_with' ) ) {
	/**
	 * Checks if $haystack ends with $needle and returns proper bool value
	 *
	 * @param string $haystack - to check
	 * @param string $needle - on end to match
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_string_ends_with( $haystack, $needle ) {
		if ( '' !== $haystack && '' !== $needle ) {
			return ( substr( $haystack, - strlen( $needle ), strlen( $needle ) ) === $needle );
		}

		return false;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_string_ends_with_allowed_units' ) ) {
	/**
	 * Checks if $haystack ends with predefined needles and returns proper bool value
	 *
	 * @param string $haystack - to check
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_string_ends_with_allowed_units( $haystack ) {
		$result  = false;
		$needles = array( 'px', '%', 'em', 'rem', 'vh', 'vw', ')' );

		if ( '' !== $haystack ) {
			foreach ( $needles as $needle ) {
				if ( qode_product_extra_options_for_woocommerce_string_ends_with( $haystack, $needle ) ) {
					$result = true;
				}
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_dynamic_style' ) ) {
	/**
	 * Outputs css based on passed selectors and properties
	 *
	 * @param array|string $selector
	 * @param array $properties
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_dynamic_style( $selector, $properties ) {
		$output = '';
		// check if selector and rules are valid data.
		if ( ! empty( $selector ) && ( is_array( $properties ) && count( $properties ) ) ) {

			if ( is_array( $selector ) && count( $selector ) ) {
				$output .= implode( ', ', $selector );
			} else {
				$output .= $selector;
			}

			$output .= ' { ';
			foreach ( $properties as $prop => $value ) {
				if ( '' !== $prop ) {

					if ( 'font-family' === $prop ) {
						$output .= $prop . ': "' . esc_attr( $value ) . '";';
					} else {
						$output .= $prop . ': ' . esc_attr( $value ) . ';';
					}
				}
			}

			$output .= '}';
		}

		return $output;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_dynamic_style_responsive' ) ) {
	/**
	 * Outputs css based on passed selectors and properties
	 *
	 * @param array|string $selector
	 * @param array $properties
	 * @param string $min_width
	 * @param string $max_width
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_dynamic_style_responsive( $selector, $properties, $min_width = '', $max_width = '' ) {
		$output = '';
		// check if min width or max width is set.
		if ( ! empty( $min_width ) || ! empty( $max_width ) ) {
			$output .= '@media only screen';

			if ( ! empty( $min_width ) ) {
				$output .= ' and (min-width: ' . $min_width . 'px)';
			}

			if ( ! empty( $max_width ) ) {
				$output .= ' and (max-width: ' . $max_width . 'px)';
			}

			$output .= ' { ';

			$output .= qode_product_extra_options_for_woocommerce_dynamic_style( $selector, $properties );

			$output .= '}';
		}

		return $output;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_pages' ) ) {
	/**
	 * Returns array of pages item
	 *
	 * @param bool $enable_default - add first element empty for default value
	 *
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_pages( $enable_default = false ) {
		$options = array();

		$pages = get_all_page_ids();
		if ( ! empty( $pages ) ) {

			if ( $enable_default ) {
				$options[''] = esc_html__( 'Default', 'qode-product-extra-options-for-woocommerce' );
			}

			foreach ( $pages as $page_id ) {
				$options[ $page_id ] = get_the_title( $page_id );
			}
		}

		return $options;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_attachment_id_from_url' ) ) {
	/**
	 * Function that retrieves attachment id for passed attachment url
	 *
	 * @param string $attachment_url
	 *
	 * @return null|string
	 */
	function qode_product_extra_options_for_woocommerce_get_attachment_id_from_url( $attachment_url ) {
		global $wpdb;
		$attachment_id = '';

		if ( '' !== $attachment_url ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $attachment_url ) );

			// Additional check for undefined reason when guid is not image src.
			if ( empty( $attachment_id ) ) {
				$modified_url = substr( $attachment_url, strrpos( $attachment_url, '/' ) + 1 );

				// get attachment id.
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_wp_attached_file' AND meta_value LIKE %s", '%' . $modified_url . '%' ) );
			}
		}

		return $attachment_id;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_resize_image' ) ) {
	/**
	 * Function that generates custom thumbnail for given attachment
	 *
	 * @param int|string $attachment - attachment id or url of image to resize
	 * @param int $width desired - height of custom thumbnail
	 * @param int $height desired - width of custom thumbnail
	 * @param bool $crop - whether to crop image or not
	 *
	 * @return array returns array containing img_url, width and height
	 *
	 * @see qode_product_extra_options_for_woocommerce_get_attachment_id_from_url()
	 * @see get_attached_file()
	 * @see wp_get_attachment_url()
	 * @see wp_get_image_editor()
	 */
	function qode_product_extra_options_for_woocommerce_resize_image( $attachment, $width = null, $height = null, $crop = true ) {
		$return_array = array();

		if ( ! empty( $attachment ) ) {
			if ( is_int( $attachment ) ) {
				$attachment_id = $attachment;
			} else {
				$attachment_id = qode_product_extra_options_for_woocommerce_get_attachment_id_from_url( $attachment );
			}

			if ( ! empty( $attachment_id ) && ( isset( $width ) && isset( $height ) ) ) {

				// get file path of the attachment.
				$img_path = get_attached_file( $attachment_id );

				// get attachment url.
				$img_url = wp_get_attachment_url( $attachment_id );

				// break down img path to array, so we can use its components in building thumbnail path.
				$img_path_array = pathinfo( $img_path );

				// build thumbnail path.
				$new_img_path = $img_path_array['dirname'] . '/' . $img_path_array['filename'] . '-' . $width . 'x' . $height . '.' . $img_path_array['extension'];

				// build thumbnail url.
				$new_img_url = str_replace( $img_path_array['filename'], $img_path_array['filename'] . '-' . $width . 'x' . $height, $img_url );

				// check if thumbnail exists by its path.
				if ( ! file_exists( $new_img_path ) ) {
					// get image manipulation object.
					$image_object = wp_get_image_editor( $img_path );

					if ( ! is_wp_error( $image_object ) ) {
						// resize image and save it new to path.
						$image_object->resize( $width, $height, $crop );
						$image_object->save( $new_img_path );

						// get sizes of newly created thumbnail.
						// we don't use $width and $height because those might differ from end result based on $crop parameter.
						$image_sizes = $image_object->get_size();

						$width  = $image_sizes['width'];
						$height = $image_sizes['height'];
					}
				}

				// generate data to be returned.
				$return_array = array(
					'img_url'    => $new_img_url,
					'img_width'  => $width,
					'img_height' => $height,
				);

				// attachment wasn't found in gallery, but it is not empty.
			} elseif ( '' !== $attachment && ( isset( $width ) && isset( $height ) ) ) {
				// generate data to be returned.
				$return_array = array(
					'img_url'    => $attachment,
					'img_width'  => $width,
					'img_height' => $height,
				);
			}
		}

		return $return_array;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_generate_thumbnail' ) ) {
	/**
	 * Generates thumbnail img tag. It calls qode_product_extra_options_for_woocommerce_resize_image function for resizing image
	 *
	 * @param int|string $attachment - attachment id or url to generate thumbnail from
	 * @param int $width - width of thumbnail
	 * @param int $height - height of thumbnail
	 * @param bool $crop - whether to crop thumbnail or not
	 *
	 * @return string generated img tag
	 *
	 * @see qode_product_extra_options_for_woocommerce_resize_image()
	 * @see qode_product_extra_options_for_woocommerce_get_attachment_id_from_url()
	 */
	function qode_product_extra_options_for_woocommerce_generate_thumbnail( $attachment, $width = null, $height = null, $crop = true ) {
		if ( ! empty( $attachment ) ) {
			if ( is_int( $attachment ) ) {
				$attachment_id = $attachment;
			} else {
				$attachment_id = qode_product_extra_options_for_woocommerce_get_attachment_id_from_url( $attachment );
			}
			$img_info = qode_product_extra_options_for_woocommerce_resize_image( $attachment_id, $width, $height, $crop );
			$img_alt  = ! empty( $attachment_id ) ? get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) : '';

			if ( is_array( $img_info ) && count( $img_info ) ) {
				$url            = esc_url( $img_info['img_url'] );
				$attr           = array();
				$attr['alt']    = esc_attr( $img_alt );
				$attr['width']  = esc_attr( $img_info['img_width'] );
				$attr['height'] = esc_attr( $img_info['img_height'] );

				return qode_product_extra_options_for_woocommerce_get_image_html_from_src( $url, $attr );
			}
		}

		return '';
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_image_html_from_src' ) ) {
	/**
	 * Function that returns image tag from url and it's attributes.
	 *
	 * @param string $url
	 * @param array $attr
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_image_html_from_src( $url, $attr = array() ) {
		$html = '';

		if ( ! empty( $url ) ) {
			$html .= '<img src="' . esc_url( $url ) . '"';

			if ( ! empty( $attr ) ) {
				foreach ( $attr as $name => $value ) {
					$html .= ' ' . $name . '="' . $value . '"';
				}
			}

			$html .= ' />';
		}

		return $html;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_ajax_status' ) ) {
	/**
	 * Function that return status from ajax functions
	 *
	 * @param string $status - success or error
	 * @param string $message - ajax message value
	 * @param string|array $data - returned value
	 * @param string $redirect - url address
	 *
	 * @return false|string
	 */
	function qode_product_extra_options_for_woocommerce_get_ajax_status( $status, $message, $data = null, $redirect = '' ) {
		$response = array(
			'status'   => esc_attr( $status ),
			'message'  => esc_html( $message ),
			'data'     => $data,
			'redirect' => ! empty( $redirect ) ? esc_url( $redirect ) : '',
		);

		$output = wp_json_encode( $response );

		exit( $output ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_select_type_options_pool' ) ) {
	/**
	 * Function that returns array with pool of options for select fields in framework
	 *
	 * @param string $type           - type of select field
	 * @param bool   $enable_default - add first element empty for default value
	 * @param array  $exclude        - array of items to exclude
	 * @param array  $include        - array of items to include
	 *
	 * @return array escaped output
	 */
	function qode_product_extra_options_for_woocommerce_get_select_type_options_pool( $type, $enable_default = true, $exclude = array(), $include = array() ) {
		$options = array();
		if ( $enable_default ) {
			$options[''] = esc_html__( 'Default', 'qode-product-extra-options-for-woocommerce' );
		}
		switch ( $type ) {
			case 'title_tag':
				$options['h1'] = 'H1';
				$options['h2'] = 'H2';
				$options['h3'] = 'H3';
				$options['h4'] = 'H4';
				$options['h5'] = 'H5';
				$options['h6'] = 'H6';
				$options['p']  = 'P';
				break;
			case 'link_target':
				$options['_self']  = esc_html__( 'Same Window', 'qode-product-extra-options-for-woocommerce' );
				$options['_blank'] = esc_html__( 'New Window', 'qode-product-extra-options-for-woocommerce' );
				break;
			case 'border_style':
				$options['solid']  = esc_html__( 'Solid', 'qode-product-extra-options-for-woocommerce' );
				$options['dashed'] = esc_html__( 'Dashed', 'qode-product-extra-options-for-woocommerce' );
				$options['dotted'] = esc_html__( 'Dotted', 'qode-product-extra-options-for-woocommerce' );
				break;
			case 'font_weight':
				$options['100'] = esc_html__( 'Thin (100)', 'qode-product-extra-options-for-woocommerce' );
				$options['200'] = esc_html__( 'Extra Light (200)', 'qode-product-extra-options-for-woocommerce' );
				$options['300'] = esc_html__( 'Light (300)', 'qode-product-extra-options-for-woocommerce' );
				$options['400'] = esc_html__( 'Normal (400)', 'qode-product-extra-options-for-woocommerce' );
				$options['500'] = esc_html__( 'Medium (500)', 'qode-product-extra-options-for-woocommerce' );
				$options['600'] = esc_html__( 'Semi Bold (600)', 'qode-product-extra-options-for-woocommerce' );
				$options['700'] = esc_html__( 'Bold (700)', 'qode-product-extra-options-for-woocommerce' );
				$options['800'] = esc_html__( 'Extra Bold (800)', 'qode-product-extra-options-for-woocommerce' );
				$options['900'] = esc_html__( 'Black (900)', 'qode-product-extra-options-for-woocommerce' );
				break;
			case 'font_style':
				$options['normal']  = esc_html__( 'Normal', 'qode-product-extra-options-for-woocommerce' );
				$options['italic']  = esc_html__( 'Italic', 'qode-product-extra-options-for-woocommerce' );
				$options['oblique'] = esc_html__( 'Oblique', 'qode-product-extra-options-for-woocommerce' );
				$options['initial'] = esc_html__( 'Initial', 'qode-product-extra-options-for-woocommerce' );
				$options['inherit'] = esc_html__( 'Inherit', 'qode-product-extra-options-for-woocommerce' );
				break;
			case 'text_transform':
				$options['none']       = esc_html__( 'None', 'qode-product-extra-options-for-woocommerce' );
				$options['capitalize'] = esc_html__( 'Capitalize', 'qode-product-extra-options-for-woocommerce' );
				$options['uppercase']  = esc_html__( 'Uppercase', 'qode-product-extra-options-for-woocommerce' );
				$options['lowercase']  = esc_html__( 'Lowercase', 'qode-product-extra-options-for-woocommerce' );
				$options['initial']    = esc_html__( 'Initial', 'qode-product-extra-options-for-woocommerce' );
				$options['inherit']    = esc_html__( 'Inherit', 'qode-product-extra-options-for-woocommerce' );
				break;
			case 'text_decoration':
				$options['none']         = esc_html__( 'None', 'qode-product-extra-options-for-woocommerce' );
				$options['underline']    = esc_html__( 'Underline', 'qode-product-extra-options-for-woocommerce' );
				$options['overline']     = esc_html__( 'Overline', 'qode-product-extra-options-for-woocommerce' );
				$options['line-through'] = esc_html__( 'Line-Through', 'qode-product-extra-options-for-woocommerce' );
				$options['initial']      = esc_html__( 'Initial', 'qode-product-extra-options-for-woocommerce' );
				$options['inherit']      = esc_html__( 'Inherit', 'qode-product-extra-options-for-woocommerce' );
				break;
			case 'yes_no':
				$options['yes'] = esc_html__( 'Yes', 'qode-product-extra-options-for-woocommerce' );
				$options['no']  = esc_html__( 'No', 'qode-product-extra-options-for-woocommerce' );
				break;
			case 'no_yes':
				$options['no']  = esc_html__( 'No', 'qode-product-extra-options-for-woocommerce' );
				$options['yes'] = esc_html__( 'Yes', 'qode-product-extra-options-for-woocommerce' );
				break;
			case 'months':
				$options['1']  = esc_html__( 'January', 'qode-product-extra-options-for-woocommerce' );
				$options['2']  = esc_html__( 'February', 'qode-product-extra-options-for-woocommerce' );
				$options['3']  = esc_html__( 'March', 'qode-product-extra-options-for-woocommerce' );
				$options['4']  = esc_html__( 'April', 'qode-product-extra-options-for-woocommerce' );
				$options['5']  = esc_html__( 'May', 'qode-product-extra-options-for-woocommerce' );
				$options['6']  = esc_html__( 'June', 'qode-product-extra-options-for-woocommerce' );
				$options['7']  = esc_html__( 'July', 'qode-product-extra-options-for-woocommerce' );
				$options['8']  = esc_html__( 'August', 'qode-product-extra-options-for-woocommerce' );
				$options['9']  = esc_html__( 'September', 'qode-product-extra-options-for-woocommerce' );
				$options['10'] = esc_html__( 'October', 'qode-product-extra-options-for-woocommerce' );
				$options['11'] = esc_html__( 'November', 'qode-product-extra-options-for-woocommerce' );
				$options['12'] = esc_html__( 'December', 'qode-product-extra-options-for-woocommerce' );
				break;
		}

		if ( ! empty( $exclude ) ) {
			foreach ( $exclude as $e ) {
				if ( array_key_exists( $e, $options ) ) {
					unset( $options[ $e ] );
				}
			}
		}

		if ( ! empty( $include ) ) {
			foreach ( $include as $key => $value ) {
				if ( ! array_key_exists( $key, $options ) ) {
					$options[ $key ] = $value;
				}
			}
		}

		return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_select_type_option', $options, $type, $enable_default, $exclude );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_escape_title_tag' ) ) {
	/**
	 * Function that output escape title tag variable for modules
	 *
	 * @param string $title_tag
	 */
	function qode_product_extra_options_for_woocommerce_escape_title_tag( $title_tag ) {
		echo esc_html( qode_product_extra_options_for_woocommerce_get_escape_title_tag( $title_tag ) );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_escape_title_tag' ) ) {
	/**
	 * Function that return escape title tag variable for modules
	 *
	 * @param string $title_tag
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_escape_title_tag( $title_tag ) {
		$allowed_tags = array(
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'p',
			'span',
			'ul',
			'ol',
		);

		$escaped_title_tag = '';
		$title_tag         = strtolower( sanitize_key( $title_tag ) );

		if ( in_array( $title_tag, $allowed_tags, true ) ) {
			$escaped_title_tag = $title_tag;
		}

		return $escaped_title_tag;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_button_classes' ) ) {
	/**
	 * Function that return theme and plugin classes for button elements
	 *
	 * @param array $additional_classes
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_button_classes( $additional_classes = array() ) {
		$classes = array(
			'button',
		);

		if ( function_exists( 'wc_wp_theme_get_element_class_name' ) ) {
			$classes[] = wc_wp_theme_get_element_class_name( 'button' );
		}

		return implode( ' ', array_merge( $classes, $additional_classes ) );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_call_shortcode' ) ) {
	/**
	 * Function that call/render shortcode
	 *
	 * @param      $base - shortcode base
	 * @param      $params - shortcode parameters
	 * @param null $content - shortcode content
	 *
	 * @return mixed|string
	 */
	function qode_product_extra_options_for_woocommerce_call_shortcode( $base, $params = array(), $content = null ) {
		global $shortcode_tags;

		if ( ! isset( $shortcode_tags[ $base ] ) ) {
			return false;
		}

		if ( is_array( $shortcode_tags[ $base ] ) ) {
			$shortcode = $shortcode_tags[ $base ];

			return call_user_func(
				array(
					$shortcode[0],
					$shortcode[1],
				),
				$params,
				$content,
				$base
			);
		}

		return call_user_func( $shortcode_tags[ $base ], $params, $content, $base );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_cpt_items' ) ) {
	/**
	 * Returns array of custom post items
	 *
	 * @param string $cpt_slug
	 * @param array $args
	 * @param bool $enable_default - add first element empty for default value
	 *
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_cpt_items( $cpt_slug, $args = array(), $enable_default = false ) {
		$options    = array();
		$query_args = array(
			'post_status'    => 'publish',
			'post_type'      => $cpt_slug,
			'posts_per_page' => '-1',
			'fields'         => 'ids',
		);

		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( ! empty( $value ) ) {
					$query_args[ $key ] = $value;
				}
			}
		}

		$cpt_items = new WP_Query( $query_args );

		if ( $cpt_items->have_posts() ) {

			if ( $enable_default ) {
				$options[''] = esc_html__( 'Default', 'qode-product-extra-options-for-woocommerce' );
			}

			foreach ( $cpt_items->posts as $id ) :
				$options[ $id ] = get_the_title( $id );
			endforeach;
		}

		wp_reset_postdata();

		return $options;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_products_variations' ) ) {
	/**
	 * Returns array of custom post items
	 *
	 * @param array $args
	 * @param bool $enable_default - add first element empty for default value
	 *
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_products_variations( $args = array(), $enable_default = false ) {
		$options    = array();
		$query_args = array(
			'post_status'    => 'publish',
			'post_type'      => 'product',
			'posts_per_page' => '-1',
			'fields'         => 'ids',
		);

		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( ! empty( $value ) ) {
					$query_args[ $key ] = $value;
				}
			}
		}

		$product_items = new WP_Query( $query_args );

		if ( $product_items->have_posts() ) {

			if ( $enable_default ) {
				$options[''] = esc_html__( 'Default', 'qode-product-extra-options-for-woocommerce' );
			}

			foreach ( $product_items->posts as $id ) :

				$product = wc_get_product( $id );

				if ( $product->is_type( 'variable' ) ) {
					$available_variations = $product->get_available_variations();

					foreach ( $available_variations as $variation ) {
						// get product variations by id's.
						$options[ $variation['variation_id'] ] = get_the_title( $variation['variation_id'] );
					}
				}
			endforeach;
		}

		wp_reset_postdata();

		return $options;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_cpt_taxonomy_name_by_ids' ) ) {
	/**
	 * Function that return custom post type taxonomies name
	 *
	 * @param string|array $taxonomy_ids - taxonomy id's or id
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_cpt_taxonomy_name_by_ids( $taxonomy_ids ) {
		if ( ! empty( $taxonomy_ids ) ) {
			$ids   = strpos( $taxonomy_ids, ',' ) !== false ? explode( ',', $taxonomy_ids ) : array( $taxonomy_ids );
			$names = array();

			foreach ( $ids as $id ) {
				$term = get_term( $id );

				if ( ! empty( $term ) ) {
					$names[] = $term->name;
				}
			}

			return implode( ', ', $names );
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_cpt_taxonomy_items' ) ) {
	/**
	 * Function that return custom post type taxonomy items
	 *
	 * @param string $taxonomy_slug - taxonomy slug
	 * @param bool $enable_default - add first element empty for default value
	 * @param bool $set_slug_as_key
	 *
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_cpt_taxonomy_items( $taxonomy_slug, $enable_default = true, $set_slug_as_key = false ) {
		$items = array();

		if ( ! empty( $taxonomy_slug ) ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy_slug,
					'hide_empty' => false,
				)
			);

			if ( is_array( $terms ) && ! empty( $terms ) ) {

				if ( $enable_default ) {
					$items[''] = esc_html__( 'Default', 'qode-product-extra-options-for-woocommerce' );
				}

				foreach ( $terms as $term ) {
					$key = $set_slug_as_key ? $term->slug : $term->term_id;

					$items[ $key ] = $term->name;
				}
			}
		}

		return $items;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_product_has_blocks' ) ) {
	/**
	 * Product has blocks
	 *
	 * @param int $product_id Product ID.
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_product_has_blocks( $product_id, $variation_id = null ) {

		if ( ! $product_id ) {
			return false;
		}

		$product = wc_get_product( $product_id );

		if ( $product instanceof WC_Product ) {
			$blocks = Qode_Product_Extra_Options_For_WooCommerce_Db()->qode_product_extra_options_for_woocommerce_get_blocks_by_product( $product, $variation_id, 'yes' );

			if ( ! empty( $blocks ) && count( $blocks ) > 0 ) {
				return true;
			}
		}
		return false;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_option_info' ) ) {
	/**
	 * Get Option Info
	 *
	 * @param int     $addon_id Addon ID.
	 * @param int     $option_id Option ID.
	 * @param boolean $calculate_taxes Boolean to calculate taxes on prices.
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id, $calculate_taxes = true ) {

		$info = array();

		if ( $addon_id > 0 ) {

			if ( qode_product_extra_options_for_woocommerce_is_installed( 'qpeofw-premium' ) && qode_product_extra_options_for_woocommerce_premium_is_plugin_activated() ) {
				$addon = qode_product_extra_options_for_woocommerce_instance_class(
					'Qode_Product_Extra_Options_For_WooCommerce_Premium_Addon',
					array(
						'id' => $addon_id,
					)
				);
			} else {
				$addon = qode_product_extra_options_for_woocommerce_instance_class(
					'Qode_Product_Extra_Options_For_WooCommerce_Addon',
					array(
						'id' => $addon_id,
					)
				);
			}

			// Option.
			$info['color']              = $addon->get_option( 'color', $option_id );
			$info['color_b']            = $addon->get_option( 'color_b', $option_id, '', false );
			$info['gradient_rendering'] = $addon->get_option( 'gradient_rendering', $option_id, '', false );
			$info['label']              = $addon->get_option( 'label', $option_id );
			$info['label_in_cart']      = $addon->get_option( 'label_in_cart', $option_id );
			$info['label_in_cart_opt']  = $addon->get_option( 'label_in_cart_opt', $option_id );
			$info['tooltip']            = $addon->get_option( 'tooltip', $option_id );
			$info['price_method']       = $addon->get_option( 'price_method', $option_id, 'free', false );
			$info['price_type']         = $addon->get_option( 'price_type', $option_id, 'fixed', false );
			$info['price']              = $addon->get_price( $option_id, $calculate_taxes );
			$info['price_sale']         = $addon->get_sale_price( $option_id, $calculate_taxes );

			// Addon settings.
			$info['addon_label']       = $addon->get_setting( 'title', '' );
			$info['title_in_cart']     = $addon->get_setting( 'title_in_cart', 'yes', false );
			$info['title_in_cart_opt'] = $addon->get_setting( 'title_in_cart_opt', '' );
			$info['addon_type']        = $addon->get_setting( 'type', '' );
			$info['sell_individually'] = $addon->get_setting( 'sell_individually', 'no', false );

			if ( 'product' === $info['addon_type'] ) {
				$info['product_id'] = $addon->get_option( 'product', $option_id );
			}

			// Addon advanced.
			$info['addon_first_options_selected'] = $addon->get_setting( 'first_options_selected' );
			$info['addon_first_free_options']     = $addon->get_setting( 'first_free_options' );

		}
		return $info;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_display_price' ) ) {
	/**
	 * Get the display price.
	 *
	 * @param WC_Product $product The product.
	 * @param string     $price   The price.
	 * @param int        $qty     The quantity.
	 *
	 * @return string The price to display
	 */
	function qode_product_extra_options_for_woocommerce_get_display_price( $product, $price = '', $qty = 1 ) {
		$price = wc_get_price_to_display(
			$product,
			array(
				'qty'   => $qty,
				'price' => $price,
			)
		);

		return $price;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_option_label' ) ) {
	/**
	 * Get Option Label
	 *
	 * @param int $addon_id Addon ID.
	 * @param int $option_id Option ID.
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_option_label( $addon_id, $option_id ) {

		$label = '';
		$info  = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id );

		if ( ! empty( $info ) && is_array( $info ) ) {
			if ( in_array(
				$info['addon_type'],
				array(
					'checkbox',
					'radio',
					'color',
					'select',
					'label',
					'file',
					'product',
				),
				true
			) ) {
				$label = isset( $info['addon_label'] ) && ! empty( $info['addon_label'] ) ? $info['addon_label'] : _x( 'Option', 'Show it in the cart page if the add-on has not a label set', 'qode-product-extra-options-for-woocommerce' );
			} else {
				$label = isset( $info['label'] ) && ! empty( $info['label'] ) ? $info['label'] : _x( 'Option', 'Show it in the cart page if the add-on has not a label set', 'qode-product-extra-options-for-woocommerce' );
			}
		}

		return $label;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_option_price' ) ) {
	/**
	 * Get Option Price
	 *
	 * @param int $product_id Product ID.
	 * @param int $addon_id Addon ID.
	 * @param int $option_id Option ID.
	 * @param int $quantity Option Quantity.
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_option_price( $product_id, $addon_id, $option_id, $quantity = 0 ) {
		$info              = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id );
		$option_price      = '';
		$option_price_sale = '';
		if ( 'percentage' === $info['price_type'] ) {
			$_product = wc_get_product( $product_id );

			// WooCommerce Measurement Price Calculator (compatibility).
			if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
				$product_price = $cart_item['pricing_item_meta_data']['_price'];
			} else {
				$product_price = ( $_product instanceof WC_Product ) ? floatval( $_product->get_price() ) : 0;
			}
			// end WooCommerce Measurement Price Calculator (compatibility).
			$option_percentage      = floatval( $info['price'] );
			$option_percentage_sale = floatval( $info['price_sale'] );
			$option_price           = ( $product_price / 100 ) * $option_percentage;
			$option_price_sale      = ( $product_price / 100 ) * $option_percentage_sale;
		} elseif ( 'multiplied' === $info['price_type'] ) {
			$option_price      = $info['price'] * $quantity;
			$option_price_sale = $info['price'] * $quantity;
		} else {
			$option_price      = $info['price'];
			$option_price_sale = $info['price_sale'];
		}

		return array(
			'price'      => $option_price,
			'price_sale' => $option_price_sale,
		);
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_tax_rate' ) ) {
	/**
	 * Get WooCommerce Tax Rate
	 *
	 * @return bool|int|mixed
	 */
	function qode_product_extra_options_for_woocommerce_get_tax_rate() {
		$wc_tax_rate = false;

		if ( get_option( 'woocommerce_calc_taxes', 'no' ) === 'yes' ) {

			$wc_tax_rates = WC_Tax::get_rates();

			if ( is_cart() || is_checkout() ) {
				$wc_tax_rate = false;

				if ( get_option( 'woocommerce_prices_include_tax' ) === 'no' && get_option( 'woocommerce_tax_display_cart' ) === 'incl' ) {
					$wc_tax_rate = is_array( $wc_tax_rates ) ? reset( $wc_tax_rates )['rate'] : 0;
				}
				if ( get_option( 'woocommerce_prices_include_tax' ) === 'yes' && get_option( 'woocommerce_tax_display_cart' ) === 'excl' ) {
					$wc_tax_rate = - is_array( $wc_tax_rates ) ? reset( $wc_tax_rates )['rate'] : 0;
				}
			} else {
				if ( get_option( 'woocommerce_prices_include_tax' ) === 'no' && get_option( 'woocommerce_tax_display_shop' ) === 'incl' ) {
					$wc_tax_rate = is_array( $wc_tax_rates ) ? reset( $wc_tax_rates )['rate'] : 0;
				}
				if ( get_option( 'woocommerce_prices_include_tax' ) === 'yes' && get_option( 'woocommerce_tax_display_shop' ) === 'excl' ) {
					$wc_tax_rate = - is_array( $wc_tax_rates ) ? reset( $wc_tax_rates )['rate'] : 0;
				}
			}
		}

		return $wc_tax_rate;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_wpml_register_string' ) ) {
	/**
	 * Register a string in wpml translation.
	 *
	 * @param string $context The context name.
	 * @param string $name    The name.
	 * @param string $value   The value to translate.
	 */
	function qode_product_extra_options_for_woocommerce_wpml_register_string( $context, $name, $value ) {
		do_action( 'wpml_register_single_string', $context, $name, $value );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_global_post' ) ) {
	/**
	 * Function that return global WordPress post object
	 *
	 * @return object
	 */
	function qode_product_extra_options_for_woocommerce_get_global_post() {
		global $post;

		return $post;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_global_product' ) ) {
	/**
	 * Function that return global WooCommerce object
	 *
	 * @return object
	 */
	function qode_product_extra_options_for_woocommerce_get_global_product() {
		global $product;

		return $product;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_global_variation' ) ) {
	/**
	 * Function that return global WooCommerce variation
	 *
	 * @return object
	 */
	function qode_product_extra_options_for_woocommerce_get_global_variation() {
		global $variation;

		return $variation;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_global_wpml_post_translations' ) ) {
	/**
	 * Function that return global $wpml_post_translations
	 *
	 * @return object
	 */
	function qode_product_extra_options_for_woocommerce_get_global_wpml_post_translations() {
		global $wpml_post_translations;

		return $wpml_post_translations;
	}
}
