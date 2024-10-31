<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_cleaned_block_items' ) ) {
	/**
	 * Function that return clean block items list, remove unnecessary items from the list
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_cleaned_block_items( $items ) {

		if ( ! empty( $items ) ) {
			foreach ( $items as $item_key => $item_value ) {

				if ( isset( $item_value['settings'] ) ) {
					unset( $items[ $item_key ]['settings'] );
				}

				if ( isset( $item_value['date_created'] ) ) {
					unset( $items[ $item_key ]['date_created'] );
				}

				if ( isset( $item_value['visibility'] ) ) {
					unset( $items[ $item_key ]['visibility'] );
				}

				if ( in_array( $item_key, array( 'table_title', 'date_created', 'visibility' ), true ) ) {
					unset( $items[ $item_key ] );
				}
			}
		}

		return $items;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_instance_class' ) ) {
	function qode_product_extra_options_for_woocommerce_instance_class( $class, $args = array() ) {
		$class_name = $class;

		if ( class_exists( $class_name ) ) {
			return new $class_name( $args );
		}
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_field' ) ) {
	/**
	 * Retrieve a field.
	 *
	 * @param array $field          The field.
	 * @param false $echo           Set to true to print the field directly; false otherwise.
	 * @param bool  $show_container Set to true to show the container; false otherwise.
	 *
	 * @return false|string
	 */
	function qode_product_extra_options_for_woocommerce_get_field( $field, $echo = false, $show_container = true ) {
		if ( empty( $field['type'] ) ) {
			return '';
		}

		if ( ! isset( $field['value'] ) ) {
			$field['value'] = '';
		}

		if ( ! isset( $field['name'] ) ) {
			$field['name'] = '';
		}

		if ( ! isset( $field['custom_attributes'] ) ) {
			$field['custom_attributes'] = array();
		}

		if ( is_array( $field['custom_attributes'] ) ) {
			/**
			 * Convert custom_attributes to string to prevent issues in plugins using them as string in their templates.
			 */
			// TODO: remove after checking plugins using custom_attributes as "string" in custom fields templates and as "array" in custom fields options.
			$field['custom_attributes'] = qode_product_extra_options_for_woocommerce_html_attributes_to_string( $field['custom_attributes'] );
		}

		if ( ! isset( $field['default'] ) && isset( $field['std'] ) ) {
			$field['default'] = $field['std'];
		}

		$field_template = qode_product_extra_options_for_woocommerce_get_field_template_path( $field );

		if ( ! isset( $field['id'] ) ) {
			static $field_number = 1;

			$field['id'] = "qodef-field--{$field_number}";
			$field_number++;
		}

		if ( $field_template ) {
			if ( ! $echo ) {
				ob_start();
			}

			if ( $show_container ) {
				echo '<div class="qodef-field-wrapper qodef-filed-type--' . esc_attr( $field['type'] ) . '">';
			}

			do_action( 'qode_product_extra_options_for_woocommerce_get_field_before', $field );
			do_action( 'qode_product_extra_options_for_woocommerce_get_field_' . $field['type'] . '_before', $field );

			include $field_template;

			do_action( 'qode_product_extra_options_for_woocommerce_get_field_after', $field );
			do_action( 'qode_product_extra_options_for_woocommerce_get_field_' . $field['type'] . '_after', $field );

			if ( $show_container ) {
				echo '</div>';
			}

			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		return '';
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_add_kses_global_attributes' ) ) {

	/**
	 * Add global attributes to a tag in the allowed HTML list.
	 *
	 * @param array $attributes An array of attributes.
	 *
	 * @return array The array of attributes with global attributes added.
	 */
	function qode_product_extra_options_for_woocommerce_add_kses_global_attributes( $attributes ) {
		$global_attributes = array(
			'aria-describedby' => true,
			'aria-details'     => true,
			'aria-label'       => true,
			'aria-labelledby'  => true,
			'aria-hidden'      => true,
			'class'            => true,
			'id'               => true,
			'style'            => true,
			'title'            => true,
			'role'             => true,
			'data-*'           => true,
		);

		if ( true === $attributes ) {
			$attributes = array();
		}

		if ( is_array( $attributes ) ) {
			return array_merge( $attributes, $global_attributes );
		}

		return $attributes;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_html_attributes_to_string' ) ) {
	/**
	 * Transform attributes array to HTML attributes string.
	 * If using a string, the attributes will be escaped.
	 * Prefer using arrays.
	 *
	 * @param array|string $attributes The attributes.
	 * @param bool         $echo       Set to true to print it directly; false otherwise.
	 *
	 * @return string
	 * @since 3.7.0
	 * @since 3.8.0 Escaping attributes when using strings; allow value-less attributes by setting value to null.
	 */
	function qode_product_extra_options_for_woocommerce_html_attributes_to_string( $attributes = array(), $echo = false ) {
		$output_attributes = '';

		if ( ! ! $attributes ) {
			if ( is_string( $attributes ) ) {
				$parsed_attrs = wp_kses_hair( $attributes, wp_allowed_protocols() );
				$attributes   = array();
				foreach ( $parsed_attrs as $attr ) {
					$attributes[ $attr['name'] ] = 'n' === $attr['vless'] ? $attr['value'] : null;
				}
			}

			if ( is_array( $attributes ) ) {
				$output_attributes = array();
				foreach ( $attributes as $key => $value ) {
					if ( ! is_null( $value ) ) {
						$output_attributes[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
					} else {
						$output_attributes[] = esc_attr( $key );
					}
				}
				$output_attributes = implode( ' ', $output_attributes );
			}
		}

		if ( $echo ) {
			echo esc_html( $output_attributes );
		}

		return $output_attributes;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_array_insert_after' ) ) {
	/**
	 * Insert a value or key/value pair after a specific key in an array.  If key doesn't exist, value is appended
	 * to the end of the array.
	 *
	 * @param array $array
	 * @param string $key
	 * @param array $new
	 *
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_array_insert_after( array $array, $key, array $new ) {
		$keys  = array_keys( $array );
		$index = array_search( $key, $keys, true );
		$pos   = false === $index ? count( $array ) : $index + 1;

		return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_addons_by_cart_item' ) ) {
	/**
	 * Get the add-ons by cart item
	 *
	 * @param string $cart_item_key The cart item key.
	 * @param boolean $include_individual Boolean to indicate if individual add-ons should be included.
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_addons_by_cart_item( $cart_item_key, $include_individual = false ) {
		$addons            = array();
		$individual_addons = array();

		if ( $cart_item_key ) {
			$cart_item  = WC()->cart->get_cart_item( $cart_item_key );
			$addons_arr = $cart_item['qpeofw_options'] ?? array();

			foreach ( $addons_arr as $current_addon ) {
				foreach ( $current_addon as $id => $value ) {
					$addons[] = array( $id => $value );
				}
			}

			if ( $include_individual ) {
				$individual_addons = qode_product_extra_options_for_woocommerce_get_individually_addons( $cart_item_key );
			}

			$addons = array_merge( $addons, $individual_addons );

		}

		return $addons;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_individually_addons' ) ) {
	/**
	 * Get the individual add-ons of the cart item.
	 *
	 * @param string $cart_item_key The cart item key.
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_individually_addons( $cart_item_key ) {
		$indiv_addons = array();

		if ( $cart_item_key ) {
			$cart_items = WC()->cart->get_cart();
			foreach ( $cart_items as $cart_item ) {
				if ( isset( $cart_item['qpeofw_individual_addons'] ) &&
					( $cart_item['qpeofw_addons_parent_key'] ?? '' ) === $cart_item_key ) {

					$addons_arr = $cart_item['qpeofw_options'] ?? array();

					foreach ( $addons_arr as $current_addon ) {
						foreach ( $current_addon as $id => $value ) {
							$indiv_addons[] = array( $id => $value );
						}
					}
				}
			}
		}

		return $indiv_addons;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_value_from_serialized_form' ) ) {
	/**
	 * Get a value from the serialized array of the form cart.
	 *
	 * @param string $name The key to search.
	 * @param array $serialized The serialized form.
	 * @return mixed|string
	 */
	function qode_product_extra_options_for_woocommerce_get_value_from_serialized_form( $name, $serialized ) {
		$value = '';

		foreach ( $serialized as $serialize_item ) {
			$serialize_item_name  = $serialize_item['name'] ?? '';
			$serialize_item_value = $serialize_item['value'] ?? '';
			if ( $serialize_item_name === $name && $serialize_item_value ) {
				$value = $serialize_item_value;
			}
		}

		return $value;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_format_addons' ) ) {
	/**
	 * Create a new array with the add-ons formatted.
	 *
	 * @param array $addons
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_format_addons( $addons ) {

		$new_addons = array(
			'qpeofw_options'     => array(
				'addons'     => array(),
				'individual' => array(),
			),
			'qpeofw_qty_options' => array(),
		);

		// array which contain ID(addonID-optionID) of individual add-ons.
		$individual_array = array();

		foreach ( $addons as $addon_id => $addon ) {

			$separator = 'qpeofw_sell_individually';
			$name      = $addon['name'] ?? '';
			$value     = $addon['value'] ?? '';

			if ( str_starts_with( $name, $separator ) ) {
				$id = substr( $name, strlen( $separator ) );
				$id = str_replace( array( '[', ']' ), '', $id );

				if ( $id ) {
					$individual_array[] = $id;
				}
			}
		}

		foreach ( $addons as $addon_id => $addon ) {

			$separator     = 'qpeofw[]';
			$qty_separator = 'qpeofw_product_qty';

			$name  = $addon['name'] ?? '';
			$value = $addon['value'] ?? '';

			if ( str_starts_with( $name, $separator ) && ! strlen( $value ) == 0 ) {
				$id = substr( $name, strlen( $separator ) );
				$id = str_replace( array( '[', ']' ), '', $id );

				if ( ! in_array( $id, $individual_array, true ) ) {
					array_push( $new_addons['qpeofw_options']['addons'], array( $id => $value ) );
				} else {
					array_push( $new_addons['qpeofw_options']['individual'], array( $id => $value ) );
				}
			}
			if ( str_starts_with( $name, $qty_separator ) ) {
				$id = substr( $name, strlen( $qty_separator ) );
				$id = str_replace( array( '[', ']' ), '', $id );

				$new_addons['qpeofw_qty_options'][ $id ] = $value;
			}
		}

		return $new_addons;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_attributes_selected' ) ) {
	/**
	 * Get variation attributes selected from the serialized array of the form cart.
	 *
	 * @param array $serialized The serialized form.
	 * @param WC_Product_Variation $variation The variation selected.
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_get_attributes_selected( $serialized, $variation = null ) {
		$new_attrs = array();

		$attributes = $variation->get_variation_attributes();
		foreach ( $attributes as $attribute_name => $attribute_val ) {
			foreach ( $serialized as $serialize_item ) {
				$serialize_item_name = $serialize_item['name'] ?? '';
				$serialize_item_val  = $serialize_item['value'] ?? '';
				if ( $serialize_item_name === $attribute_name ) {
					$new_attrs[ $serialize_item_name ] = $serialize_item_val;
				}
			}
		}

		return $new_attrs;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_field_template_path' ) ) {
	/**
	 * Retrieve the field template path.
	 *
	 * @param array $field The field.
	 *
	 * @return false|string
	 */
	function qode_product_extra_options_for_woocommerce_get_field_template_path( $field ) {
		if ( empty( $field['type'] ) ) {
			return false;
		}

		$field_template = QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH . '/product-add-ons/templates/fields/' . sanitize_title( $field['type'] ) . '.php';

		$field_template = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_field_template_path', $field_template, $field );

		return file_exists( $field_template ) ? $field_template : false;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_extract' ) ) {
	/**
	 * Extract array variables
	 * Usage example:
	 * ```
	 * list ( $type, $class, $value ) = qode_product_extra_options_for_woocommerce_extract( $field, 'type', 'class', 'value' );
	 * ```
	 *
	 * @param array  $array   The array.
	 * @param string ...$keys The keys.
	 *
	 * @return array
	 */
	function qode_product_extra_options_for_woocommerce_extract( $array, ...$keys ) {
		return array_map(
			function ( $key ) use ( $array ) {
				return isset( $array[ $key ] ) ? $array[ $key ] : null;
			},
			$keys
		);
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_html_data_to_string' ) ) {
	/**
	 * Transform data array to HTML data.
	 *
	 * @param array $data The array of data.
	 * @param false $echo Set to true to print it directly; false otherwise.
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_html_data_to_string( $data = array(), $echo = false ) {
		$output_data = '';

		if ( ! ! $data ) {
			if ( is_array( $data ) ) {
				foreach ( $data as $key => $value ) {
					$data_attribute = "data-{$key}";
					$data_value     = ! is_array( $value ) ? $value : implode( ',', $value );

					$output_data .= ' ' . esc_attr( $data_attribute ) . '="' . esc_attr( $data_value ) . '"';
				}
				$output_data .= ' ';
			}
		}

		if ( $echo ) {
			echo esc_html( $output_data );
		}

		return $output_data;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_is_addon_type_available' ) ) {
	/**
	 * Is addon type available
	 *
	 * @param string $addon_type Addon type.
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_is_addon_type_available( $addon_type ) {
		if ( '' === $addon_type || substr( $addon_type, 0, 5 ) === 'html-' || in_array( $addon_type, Qode_Product_Extra_Options_For_WooCommerce_Main()->get_available_addon_types(), true ) ) {
			return true;
		}
		return false;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_is_addon_is_true' ) ) {
	/**
	 * Is something true?
	 *
	 * @param string|bool|int $value The value to check for.
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_is_addon_is_true( $value ) {
		return true === $value || 1 === $value || '1' === $value || 'yes' === $value || 'true' === $value;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_action_buttons' ) ) {
	/**
	 * Retrieve action buttons.
	 *
	 * @param array $actions The actions.
	 * @param bool  $echo    Set to true to print the field directly; false otherwise.
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_action_buttons( $actions, $echo = true ) {
		$actions_html = '';

		foreach ( $actions as $action ) {
			$action['type'] = 'action-button';

			$actions_html .= qode_product_extra_options_for_woocommerce_get_component( $action, $echo );
		}

		return $actions_html;
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_component' ) ) {
	/**
	 * Retrieve a component.
	 *
	 * @param array $component The component.
	 * @param bool  $echo      Set to true to print the component directly; false otherwise.
	 *
	 * @return false|string
	 */
	function qode_product_extra_options_for_woocommerce_get_component( $component, $echo = true ) {
		if ( ! empty( $component['type'] ) ) {
			$type     = sanitize_title( $component['type'] );
			$defaults = array(
				'id'         => '',
				'class'      => '',
				'attributes' => array(),
				'data'       => array(),
			);

			$component = wp_parse_args( $component, $defaults );

			$component_template = '/product-add-ons/templates/components/' . $type . '.php';

			if ( ! $echo ) {
				ob_start();
			}

			qode_product_extra_options_for_woocommerce_include_template( $component_template, compact( 'component' ) );

			if ( ! $echo ) {
				return ob_get_clean();
			}
		}

		return '';
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_include_template' ) ) {
	/**
	 * Include a FW template
	 *
	 * @param string $template The template.
	 * @param array  $args     Arguments.
	 */
	function qode_product_extra_options_for_woocommerce_include_template( $template, $args = array() ) {
		$_template_path = trailingslashit( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_INC_PATH ) . $template;

		if ( file_exists( $_template_path ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
			include $_template_path;
		}
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_get_post_formatted_name' ) ) {
	/**
	 * Get the formatted name for posts/products
	 *
	 * @param int|WP_Post|WC_Product|WC_Product_Variation|WC_Order $post The post ID, the post, the product, or the order.
	 * @param array                                                $args Arguments.
	 *
	 * @return string
	 */
	function qode_product_extra_options_for_woocommerce_get_post_formatted_name( $post, $args = array() ) {
		$defaults  = array(
			'show-id'   => false,
			'post-type' => false,
		);
		$args      = wp_parse_args( $args, $defaults );
		$post_type = $args['post-type'];
		$show_id   = $args['show-id'];

		if ( is_a( $post, 'WP_Post' ) ) {
			$post_id = $post->ID;
		} elseif ( class_exists( 'WC_Product' ) && is_a( $post, 'WC_Product' ) ) {
			$post_id = $post->get_id();
			if ( false === $post_type ) {
				$post_type = is_a( $post, 'WC_Product_Variation' ) ? 'product_variation' : 'product';
			}
		} elseif ( class_exists( 'WC_Order' ) && is_a( $post, 'WC_Order' ) ) {
			$post_id = $post->get_id();
		} else {
			$post_id = absint( $post );
		}

		if ( ! $post_type ) {
			$post_type = get_post_type( $post_id );
		}

		$name = null;

		switch ( $post_type ) {
			case 'product':
			case 'product_variation':
				$product = class_exists( 'WC_Product' ) && is_a( $post, 'WC_Product' ) ? $post : false;
				if ( ! $product && function_exists( 'wc_get_product' ) ) {
					$product = wc_get_product( $post );
				}
				if ( $product ) {
					$name = $product->get_formatted_name();

					if ( ! $show_id ) {

						if ( $product->get_sku() ) {
							$identifier = $product->get_sku();
						} else {
							$identifier = '#' . $product->get_id();
						}

						// Use normal replacing instead of regex since the identifier could be also the product SKU.
						$name = str_replace( "({$identifier})", '', $name );
					}
				}
				break;
			case 'shop_order':
				$date_format = sprintf( '%s %s', wc_date_format(), wc_time_format() );
				$order       = class_exists( 'WC_Order' ) && is_a( $post, 'WC_Order' ) ? $post : false;
				if ( ! $order && function_exists( 'wc_get_order' ) ) {
					$order = wc_get_order( $post );
				}
				if ( $order ) {
					$buyer = '';
					if ( $order->get_billing_first_name() || $order->get_billing_last_name() ) {
						$buyer = trim( sprintf( '%s %s', $order->get_billing_first_name(), $order->get_billing_last_name() ) );
					} elseif ( $order->get_billing_company() ) {
						$buyer = trim( $order->get_billing_company() );
					} elseif ( $order->get_customer_id() ) {
						$user  = get_user_by( 'id', $order->get_customer_id() );
						$buyer = ucwords( $user->display_name );
					}

					$order_number = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_order_number', '#' . $order->get_id(), $order->get_id() );
					$name         = sprintf(
						'%s %s - %s',
						$order_number,
						esc_html( $buyer ),
						esc_html( $order->get_date_created()->format( $date_format ) )
					);
				}
				break;
		}

		if ( is_null( $name ) ) {
			$name = get_the_title( $post_id );
			if ( $show_id ) {
				$name .= " (#{$post_id})";
			}
		}

		return $name;
	}
}
