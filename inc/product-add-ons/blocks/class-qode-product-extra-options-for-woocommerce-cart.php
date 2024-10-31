<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! class_exists( 'Qode_Product_Extra_Options_For_WooCommerce_Cart' ) ) {

	/**
	 *  Qode_Product_Extra_Options_For_WooCommerce_Cart Class
	 */
	class Qode_Product_Extra_Options_For_WooCommerce_Cart {

		/**
		 * Single instance of the class
		 *
		 * @var Qode_Product_Extra_Options_For_WooCommerce_Cart
		 */
		public static $instance;

		/**
		 * Returns single instance of the class
		 *
		 * @return Qode_Product_Extra_Options_For_WooCommerce_Cart
		 */
		public static function get_instance() {
			return ! is_null( self::$instance ) ? self::$instance : self::$instance = new self();
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			// Loop add to cart button.
			$shop_loop_button_label = qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_list_button_label' );

			if ( ! empty( $shop_loop_button_label ) && 'select-options-button' === $shop_loop_button_label ) {
				add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'add_to_cart_url' ), 50, 1 );
				add_action( 'woocommerce_product_add_to_cart_text', array( $this, 'add_to_cart_text' ), 10, 1 );
				add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 50, 2 );
				add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'replace_ajax_add_to_cart_button' ), 55, 3 );
			}

			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_addons_validation' ), 50, 3 );

			// Add options to cart item.
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 25, 2 );
			add_filter( 'woocommerce_order_again_cart_item_data', array( $this, 'add_cart_item_data_order_again' ), 25, 2 );

			// Display custom product thumbnail in cart.
			if ( 'yes' === qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_show_image_in_cart' ) ) {
				add_filter( 'woocommerce_order_item_thumbnail', array( $this, 'order_item_thumbnail' ), 10, 2 );
				add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'cart_item_thumbnail' ), 10, 2 );
			}

			// Add to cart the total price of the item with the addons.
			add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 20, 1 );

			// Display options in cart and checkout page.
			add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 25, 2 );

			// Load cart data per page load.
			add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 10, 2 );

			// Add order item meta.
			add_action( 'woocommerce_new_order_item', array( $this, 'add_order_item_meta' ), 10, 2 );

			// QODE Product Bundles For WooCommerce.
			add_filter( 'qode_product_bundles_for_woocommerce_premium_sum_of_bundled_items_price', array( $this, 'qpbfw_woocommerce_cart_item_price' ), 10, 5 );

			add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'woocommerce_order_formatted_line_subtotal' ), 20, 3 );
		}

		/**
		 * Add to cart validation
		 *
		 * @param bool $passed Passed.
		 * @param int  $product_id Product ID.
		 *
		 * @return false|mixed
		 */
		public function add_to_cart_validation( $passed, $product_id ) {
			// Disable add_to_cart_button class on shop and product archive pages when ajax add to cart is DISABLED.
			$is_product_archive = is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy();

			if ( ( $is_product_archive || wp_doing_ajax() ) && ! isset( $_REQUEST['qpeofw_is_single'] ) && qode_product_extra_options_for_woocommerce_product_has_blocks( $product_id ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return false;
			}

			return $passed;
		}

		/**
		 * Replace Ajax Add to cart in shop loop
		 *
		 * @param string $button
		 * @param object  $product Product Object.
		 *
		 * @return false|mixed
		 */
		public function replace_ajax_add_to_cart_button( $button, $product ) {
			$product_id = $product->get_id();
			if ( qode_product_extra_options_for_woocommerce_product_has_blocks( $product_id ) ) {
				$button = '<a href="' . get_permalink( $product->get_id() ) . '" class="button product_type_variable add_to_cart_button qodef--with-icon" data-product_id="' . $product->get_id() . '" rel="nofollow"><span>' . qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_list_select_options_label' ) . '</span></a>';
			}

			return $button;
		}

		/**
		 * Add to cart validation for addons
		 *
		 * @param bool $passed Passed.
		 * @param int $product_id Product ID.
		 * @param $quantity
		 *
		 * @return false|mixed
		 * @throws Exception
		 */
		public function add_to_cart_addons_validation( $passed, $product_id, $quantity ) {
			if ( $passed ) {
				try {
					if ( isset( $_REQUEST['qodef-blocks-cart-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['qodef-blocks-cart-nonce'] ) ), 'qodef_blocks_cart' ) ) {

						$addons = isset( $_POST['qpeofw'] ) ? sanitize_text_field( wp_unslash( $_POST['qpeofw'] ) ) : array();

						if ( is_array( $addons ) && ! empty( $addons ) ) {
							foreach ( $addons as $index => $option ) {
								foreach ( $option as $addon_option => $value ) {

									$values = Qode_Product_Extra_Options_For_WooCommerce_Main::get_instance()->split_addon_and_option_ids( $addon_option, $value );

									$addon_id  = $values['addon_id'];
									$option_id = $values['option_id'];

									$info = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id, false );

									if ( 'product' === $info['addon_type'] ) {
										$product_id = $info['product_id'];
										$product    = wc_get_product( $product_id );
										$quantity   = isset( $_POST['qpeofw_product_qty'][ $addon_option ] ) ? sanitize_text_field( wp_unslash( $_POST['qpeofw_product_qty'][ $addon_option ] ) ) : $quantity;

										if ( $product ) {
											if ( ! $product->is_in_stock() ) {
												/* translators: %s: product name */
												$message = sprintf( _x( 'You cannot add &quot;%s&quot; to the cart because the product is out of stock.', 'Error message when an add-on type Product is out of stock', 'qode-product-extra-options-for-woocommerce' ), $product->get_name() );

												$message = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_cart_addon_product_out_of_stock_message', $message, $product );
												throw new Exception( $message );
											}
											if ( ! $product->has_enough_stock( $quantity ) ) {
												$stock_quantity = $product->get_stock_quantity();

												/* translators: 1: product name 2: quantity in stock */
												$message = sprintf( _x( 'You cannot add that amount of &quot;%1$s&quot; to the cart because there is not enough stock (%2$s remaining).', 'When add-on type Product is added to cart with more amount than allowed', 'qode-product-extra-options-for-woocommerce' ), $product->get_name(), wc_format_stock_quantity_for_display( $stock_quantity, $product ) );

												$message = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_cart_addon_product_not_enough_stock_message', $message, $product, $stock_quantity );

												throw new Exception( $message );
											}
											if ( $product->managing_stock() ) {

												$products_qty_in_cart = $this->get_cart_item_quantities();

												if ( isset( $products_qty_in_cart[ $addon_option ] ) && ! $product->has_enough_stock( $products_qty_in_cart[ $addon_option ] + $quantity ) ) {
													$stock_quantity         = $product->get_stock_quantity();
													$stock_quantity_in_cart = $products_qty_in_cart[ $addon_option ];

													$message = sprintf(
														'<a href="%s" class="button wc-forward">%s</a> %s',
														wc_get_cart_url(),
														_x( 'View cart', 'Redirect to cart page', 'qode-product-extra-options-for-woocommerce' ),
														/* translators: 1: quantity in stock 2: current quantity */
														sprintf( _x( 'You cannot add that amount to the cart &mdash; we have %1$s in stock and you already have %2$s in your cart.', 'If sum of already add-ons type Products added to cart and the current stock selected are higher than expected', 'qode-product-extra-options-for-woocommerce' ), wc_format_stock_quantity_for_display( $stock_quantity, $product ), wc_format_stock_quantity_for_display( $stock_quantity_in_cart, $product ) )
													);

													$message = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_cart_addon_product_not_enough_stock_already_in_cart_message', $message, $product, $stock_quantity, $stock_quantity_in_cart );

													throw new Exception( $message );
												}
											}
										}
									}
								}
							}
						}
					}
				} catch ( Exception $e ) {
					if ( $e->getMessage() ) {
						wc_add_notice( $e->getMessage(), 'error' );
					}
					$passed = false;
				}
			}

			return $passed;
		}

		/**
		 * Filter cart item from session.
		 *
		 * @param array $cart_item Cart item.
		 * @param array $values Add-ons options.
		 *
		 * @return mixed
		 */
		public function get_cart_item_from_session( $cart_item, $values ) {

			if ( ! empty( $values['qpeofw_options'] ) ) {

				$cart_item['qpeofw_options'] = $values['qpeofw_options'];
				$cart_item                   = $this->add_cart_item( $cart_item );

				// TODO: probably to remove this is from some subscription plugin.
				if ( isset( $cart_item['ywsbs-subscription-info'] ) ) {
					$cart_item['ywsbs-subscription-info']['recurring_price'] = $cart_item['data']->get_price();
				}
			}

			return $cart_item;
		}

		/**
		 * Set the data for the cart item in cart object.
		 *
		 * @param array $cart_item_data Cart item data.
		 * @param int   $product_id Product ID.
		 * @param array $post_data Post data.
		 *
		 * @return mixed
		 */
		public function add_cart_item_data( $cart_item_data, $product_id, $post_data = null ) {

			if ( isset( $_REQUEST['qodef-blocks-cart-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['qodef-blocks-cart-nonce'] ) ), 'qodef_blocks_cart' ) ) {
				if ( is_null( $post_data ) ) {
					$post_data = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
				}

				// Check if the item data should be added based on filters.
				if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_add_item_data_check', true ) && ( isset( $cart_item_data['qode_cart_bundled_by'] ) || ( isset( $post_data['qpeofw_product_id'] ) && intval( $product_id ) !== intval( $post_data['qpeofw_product_id'] ) ) ) ) {
					return $cart_item_data;
				}
				$data = array();

				if ( isset( $post_data['qpeofw'] ) && is_array( $post_data['qpeofw'] ) ) {

					$product_image = 0;

					if ( isset( $post_data['qpeofw_product_img'] ) && ! empty( $post_data['qpeofw_product_img'] ) ) {
						$image_url = $post_data['qpeofw_product_img'];
						if ( ! preg_match( '~^(?:f|ht)tps?://~i', $post_data['qpeofw_product_img'] ) ) {
							$image_url = 'http:' . $post_data['qpeofw_product_img'];
						}
						$product_image = attachment_url_to_postid( $image_url );
					}

					$cart_item_data['qpeofw_product_img'] = $product_image;

					foreach ( $post_data['qpeofw'] as $index => $option ) {
						foreach ( $option as $key => $value ) {
							// Check if need to bypass the addons if it will be sell as individual addons.
							if ( isset( $post_data['qpeofw_sell_individually'][ $key ] ) ) {
								if ( ! empty( $value ) ) {
									$cart_item_data['qpeofw_product_has_individual_addons'] = true;
								}
								continue;
							}
							$cart_item_data['qpeofw_options'][ $index ][ $key ] = $value;
							$data[ $key ]                                       = $value;
						}
					}
				}

				if ( isset( $post_data['qpeofw_product_qty'] ) && is_array( $post_data['qpeofw_product_qty'] ) ) {
					foreach ( $post_data['qpeofw_product_qty'] as $key => $value ) {
						if ( isset( $data[ $key ] ) ) {
							$cart_item_data['qpeofw_qty_options'][ $key ] = $value;
						}
					}
				}
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_add_cart_item_data', $cart_item_data, $product_id, $post_data );
		}

		/**
		 * Set the data for the cart item in cart object (Order again).
		 *
		 * @param array $cart_item_data Cart item data.
		 * @param WC_Order_Item $item The item object.
		 *
		 * @return mixed
		 * @throws Exception
		 */
		public function add_cart_item_data_order_again( $cart_item_data, $item ) {

			$item_id       = $item->get_id();
			$meta_data     = wc_get_order_item_meta( $item_id, '_qode_product_extra_options_for_woocommerce_meta_data', true );
			$product_image = wc_get_order_item_meta( $item_id, '_qode_product_extra_options_for_woocommerce_product_img', true );

			if ( ! empty( $meta_data ) ) {
				$cart_item_data['qpeofw_options'] = $meta_data;
			}
			if ( ! empty( $product_image ) ) {
				$cart_item_data['qpeofw_product_img'] = $product_image;
			}

			return $cart_item_data;
		}

		/**
		 * Filter Item before add to cart.
		 *
		 * @param array $cart_item Cart item.
		 *
		 * @return mixed
		 */
		public function add_cart_item( $cart_item ) {
			// Avoid sum addons price of child products of Composite Products.
			// TODO probably to remove since it is another plugin - Composite Products - Plugin.
			if ( isset( $cart_item['qode_product_extra_options_for_woocommerce_wcp_child_component_data'] ) ) {
				return $cart_item;
			}

			// Avoid sum addons price of child products of QODE Product Bundles For WooCommerce.
			if ( isset( $cart_item['qode_cart_bundled_by'] ) ) {
				return $cart_item;
			}

			if ( isset( $cart_item['qpeofw_sold_individually'] ) ) {
				return $cart_item;
			}

			$qpeofw_price = qode_product_extra_options_for_woocommerce_woo_get_prop( $cart_item['data'], 'qpeofw_price' );

			if ( ! empty( $cart_item['qpeofw_options'] ) && ! $qpeofw_price ) {
				$total_options_price          = 0;
				$first_free_options_count     = 0;
				$sell_individually_product_id = false;
				$product_id                   = isset( $cart_item['variation_id'] ) && ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'];

				// Individually product.
				if ( isset( $cart_item['qpeofw_individual_addons'] ) && isset( $cart_item['qpeofw_product_id'] ) && ! empty( $cart_item['qpeofw_product_id'] ) ) {
					$sell_individually_product_id = $product_id;
					$product_id                   = $cart_item['qpeofw_product_id'];
				}

				$_product              = wc_get_product( $product_id );
				$_individually_product = wc_get_product( $sell_individually_product_id );

				// WooCommerce Measurement Price Calculator (compatibility).
				if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
					$product_price = $cart_item['pricing_item_meta_data']['_price'];
				} else {
					if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_product_price_excluding_tax', true ) && ! wc_prices_include_tax() && 'incl' === get_option( 'woocommerce_tax_display_cart' ) ) {
						// Calculate the add-ons taxes on cart depending on real product price (without taxes).
						$product_price = wc_get_price_excluding_tax( $_product );
					} else {
						$product_price = qode_product_extra_options_for_woocommerce_woo_get_display_price( $_product );
					}
				}

				$product_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_product_price_on_cart', $product_price, $_product, $cart_item, false );

				$addon_id_check = '';

				foreach ( $cart_item['qpeofw_options'] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						if ( $key && '' !== $value ) {

							$values = Qode_Product_Extra_Options_For_WooCommerce_Main::get_instance()->split_addon_and_option_ids( $key, $value );

							$addon_id  = $values['addon_id'];
							$option_id = $values['option_id'];

							if ( $addon_id !== $addon_id_check ) {
								$first_free_options_count = 0;
								$addon_id_check           = $addon_id;
							}

							$info                   = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id );
							$addon_type             = $info['addon_type'] ?? '';
							$first_options_selected = $info['addon_first_options_selected'] ?? '';
							$first_options_qty      = intval( $info['addon_first_free_options'] ) ?? 0;
							$price_method           = $info['price_method'] ?? '';
							$sell_individually      = $info['sell_individually'] ?? '';

							$is_empty_select = 'select' === $addon_type && 'default' === $option_id;

							if ( $is_empty_select ) {
								continue;
							}

							$calculate_taxes = false;

							if ( wc_string_to_bool( $sell_individually ) && ( $_individually_product instanceof WC_Product && 'zero-rate' === $_individually_product->get_tax_class() ) ) {
								$calculate_taxes = true;
							}

							$addon_prices = $this->calculate_addon_prices_on_cart( $addon_id, $option_id, $key, $value, $cart_item, $product_price, $calculate_taxes );

							$option_price     = 0;
							$addon_price      = abs( floatval( $addon_prices['price'] ) );
							$addon_sale_price = abs( floatval( $addon_prices['price_sale'] ) );

							// First X free options check.
							if ( 'yes' === $first_options_selected && 0 < $first_options_qty && $first_free_options_count < $first_options_qty ) {
								$first_free_options_count++;
							} else {
								if ( 0 !== $addon_price || 0 !== $addon_sale_price ) {
									if ( $addon_sale_price ) {
										$option_price = $addon_sale_price;
									} else {
										$option_price = $addon_price;
									}
								}
							}
							if ( 'decrease' === $price_method ) {
									$total_options_price -= floatval( $option_price );
							} else {
									$total_options_price += floatval( $option_price );
							}
						}
					}
				}

				$cart_item_price = is_numeric( $cart_item['data']->get_price() ) ? ( $cart_item['data']->get_price() ) : 0;
				// TODO: for currency switching.
				$total_item_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_total_item_price', $cart_item_price + $total_options_price );

				$cart_item['data']->set_price( $total_item_price );
				$cart_item['qpeofw_item_price']          = $cart_item_price;
				$cart_item['qpeofw_total_options_price'] = $total_options_price;

				$cart_item['data']->add_meta_data( 'qpeofw_price', '' );

			}

			return $cart_item;
		}

		/**
		 * Change the product image with the addon one (if selected).
		 *
		 * @param string $_product_img Product image.
		 * @param array  $cart_item Cart item.
		 *
		 * @return mixed|string
		 */
		public function cart_item_thumbnail( $_product_img, $cart_item ) {
			if ( ! empty( $cart_item['qpeofw_product_img'] ) ) {
				$_product_img = wp_get_attachment_image( $cart_item['qpeofw_product_img'] );
			}

			return $_product_img;
		}

		/**
		 * Change product image in order if replaced by add-ons
		 *
		 * @param string                $image The image.
		 * @param WC_Order_Item_Product $item The item object.
		 * @return string
		 */
		public function order_item_thumbnail( $image, $item ) {
			$qpeofw_image = $item->get_meta( '_qode_product_extra_options_for_woocommerce_product_img' );

			if ( ! empty( $qpeofw_image ) ) {
				$image = wp_get_attachment_image( $qpeofw_image );
			}

			return $image;
		}

		/**
		 * Update cart items info.
		 *
		 * @param array $cart_data Cart data.
		 * @param array $cart_item Cart item.
		 *
		 * @return mixed
		 */
		public function get_item_data( $cart_data, $cart_item ) {

			// Avoid show addons of child products of Composite Products.
			// TODO: probably to remove - Composite Products - Plugin.
			if ( isset( $cart_item['qode_product_extra_options_for_woocommerce_wcp_child_component_data'] ) ) {
				return $cart_data;
			}

			$grouped_in_cart = ! apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_options_grouped_in_cart', true );

			$product_parent_id = qode_product_extra_options_for_woocommerce_woo_get_base_product_id( $cart_item['data'] );

			if ( isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] > 0 ) {
				$base_product = new WC_Product_Variation( $cart_item['variation_id'] );
			} else {
				$base_product = wc_get_product( $product_parent_id );
			}

			if ( is_object( $base_product ) && ! empty( $cart_item['qpeofw_options'] ) &&
				isset( $cart_item['qpeofw_product_has_individual_addons'] ) && 1 === intval( $cart_item['qpeofw_product_has_individual_addons'] ) &&
				! isset( $cart_item['deposit'] )
			) {
				if ( 'qode_bundle_product' === $base_product->get_type() && false === $base_product->get_bundle_product_has_fixed_price() && method_exists( 'Qode_Product_Bundles_For_WooCommerce_Premium_Bundle_Product', 'woocommerce_bundled_item_subtotal' ) ) {
					$subtotal = $base_product->get_price();
					$price    = ( new Qode_Product_Bundles_For_WooCommerce_Premium_Bundle_Product() )->woocommerce_bundled_item_subtotal( $subtotal, $cart_item );
				} else {
					$price = qode_product_extra_options_for_woocommerce_woo_get_display_price( $base_product );
				}

				$price_html = wc_price( $price );

				// Base price is set to 0 in this case.
				$cart_data[] = array(
					'name'  => _x( 'Base price', 'Label shown on Plugin Bundle products when is added to cart.', 'qode-product-extra-options-for-woocommerce' ),
					'value' => $price_html,
				);
			}

			if ( ! empty( $cart_item['qpeofw_options'] ) ) {
				// $total_options_price = 0; phpcs:ignore Squiz.PHP.CommentedOutCode.Found.
				$cart_data_array          = array();
				$first_free_options_count = 0;
				$product_id               = isset( $cart_item['variation_id'] ) && ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'];
				$product_id               = isset( $cart_item['qpeofw_individual_addons'] ) && isset( $cart_item['qpeofw_product_id'] ) && ! empty( $cart_item['qpeofw_product_id'] ) ? $cart_item['qpeofw_product_id'] : $product_id;
				$_product                 = wc_get_product( $product_id );

				// WooCommerce Measurement Price Calculator (compatibility).
				if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
					$product_price = $cart_item['pricing_item_meta_data']['_price'];
				} else {
					$product_price = qode_product_extra_options_for_woocommerce_woo_get_display_price( $_product );
				}

				$product_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_product_price_on_cart', $product_price, $_product, $cart_item, false );

				$addon_id_check = '';

				foreach ( $cart_item['qpeofw_options'] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						if ( $key && '' !== $value ) {

							if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_option_on_cart', false, $option ) ) {
								continue;
							}

							$values = Qode_Product_Extra_Options_For_WooCommerce_Main::get_instance()->split_addon_and_option_ids( $key, $value );

							$addon_id  = $values['addon_id'];
							$option_id = $values['option_id'];

							if ( $addon_id !== $addon_id_check ) {
								$first_free_options_count = 0;
								$addon_id_check           = $addon_id;
							}

							$addon_data_name = $this->get_addon_data_name( $addon_id, $option_id, $grouped_in_cart );
							$addon_value     = $this->get_addon_value_on_cart( $addon_id, $option_id, $key, $value, $cart_item, $grouped_in_cart );
							$addon_prices    = $this->calculate_addon_prices_on_cart( $addon_id, $option_id, $key, $value, $cart_item, $product_price );

							$option_price     = 0;
							$addon_price      = abs( floatval( $addon_prices['price'] ) );
							$addon_sale_price = abs( floatval( $addon_prices['price_sale'] ) );
							$sign             = $addon_prices['sign'];

							$info                         = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id );
							$addon_type                   = isset( $info['addon_type'] ) ? $info['addon_type'] : '';
							$addon_first_options_selected = isset( $info['addon_first_options_selected'] ) ? $info['addon_first_options_selected'] : '';

							$is_empty_select = 'select' === $addon_type && 'default' === $option_id;

							// First X free options check.
							if ( 'yes' === $addon_first_options_selected && $first_free_options_count < $addon_first_options_selected ) {
								$first_free_options_count++;
							} else {
								if ( 0 !== $addon_price || 0 !== $addon_sale_price ) {
									if ( $addon_sale_price ) {
										$option_price = $addon_sale_price;
									} else {
										$option_price = $addon_price;
									}
								}
							}

							$option_price = '' !== $option_price ? $option_price : 0;
							$option_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_prices_on_cart', $option_price );

							if ( empty( $addon_value ) ) {
								$addon_value = '<span>' . $addon_value . '</span>';
							}

							$addon_value = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_value_on_cart', $addon_value, $key );

							if ( 'yes' === qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_show_options_in_cart' ) ) {
								if ( ! $is_empty_select ) {
									if ( ! isset( $cart_data_array[ $addon_data_name ] ) ) {
										$cart_data_array[ $addon_data_name ] = '';
									}

									$cart_data_array[ $addon_data_name ] .= '<div>' . $this->get_addon_display_on_cart( $addon_value, $sign, $option_price, $addon_price, $addon_sale_price ) . '</div>';
								}
							}

							if ( $grouped_in_cart ) {
								if ( ! $is_empty_select ) {
									$cart_data[] = array(
										'name'    => $addon_data_name,
										'display' => empty( $option_price ) ? $addon_value : '<div>' . $this->get_addon_display_on_cart( $addon_value, $sign, $option_price, $addon_price, $addon_sale_price ) . '</div>',
									);
								}
							}
						}
					}
				}
				if ( ! $grouped_in_cart ) {
					foreach ( $cart_data_array as $key => $value ) {
						$key = rtrim( $key, ':' );
						if ( '' === $key ) {
							$key = _x( 'Option', 'Show it in the cart page if the add-on has not a label set', 'qode-product-extra-options-for-woocommerce' );
						}
						$cart_data[] = array(
							'name'    => $key,
							'display' => stripslashes( $value ),
						);
					}
				}
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_cart_data', $cart_data, $cart_item );
		}

		/**
		 * Format add-on data to display on the cart and checkout
		 *
		 * @param mixed $value Add-on value
		 * @param string $sign Add-on price sign
		 * @param mixed $price Add-on final price
		 * @param mixed $regular_price Add-on regular price
		 * @param mixed $sale_price Add-on sale price
		 * @return string Formatted add-on data
		 */
		public function get_addon_display_on_cart( $value, $sign, $price, $regular_price, $sale_price ) {
			$display = $value . ( '' !== $price && floatval( 0 ) !== floatval( $price ) ? ' (' . $sign . wc_price( $price ) . ')' : '' );
			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_display_on_cart', $display, $value, $sign, $price, $regular_price, $sale_price );
		}

		/**
		 * Add order item meta
		 *
		 * @param int $item_id Item ID.
		 * @param array $cart_item Cart item.
		 *
		 * @throws Exception
		 */
		public function add_order_item_meta( $item_id, $cart_item ) {

			if ( is_object( $cart_item ) && property_exists( $cart_item, 'legacy_values' ) ) {
				$cart_item = $cart_item->legacy_values;
			}

			$addon_id_check = '';

			$quantity = $cart_item['quantity'] ?? 1;

			// TODO: probably refactor Composite Products - Plugin.
			if ( isset( $cart_item['qpeofw_options'] ) && ! isset( $cart_item['qode_product_extra_options_for_woocommerce_wcp_child_component_data'] ) ) {

				$grouped_in_cart = ! apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_options_grouped_in_cart', true );

				foreach ( $cart_item['qpeofw_options'] as $index => $option ) {
					foreach ( $option as $key => $value ) {
						if ( $key && '' !== $value ) {

							if ( apply_filters( 'qode_product_extra_options_for_woocommerce_filter_option_on_order', false, $option ) ) {
								continue;
							}

							$values = Qode_Product_Extra_Options_For_WooCommerce_Main::get_instance()->split_addon_and_option_ids( $key, $value );

							$addon_id  = $values['addon_id'];
							$option_id = $values['option_id'];

							if ( $addon_id !== $addon_id_check ) {
								$first_free_options_count = 0;
								$addon_id_check           = $addon_id;
							}

							// Check Product price.
							$_product = wc_get_product( $cart_item['product_id'] );
							// WooCommerce Measurement Price Calculator (compatibility).
							if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
								$product_price = $cart_item['pricing_item_meta_data']['_price'];
							} else {
								$product_price = floatval( $_product->get_price() );
							}

							$addon_name   = $this->get_addon_data_name( $addon_id, $option_id, $grouped_in_cart );
							$addon_value  = $this->get_addon_value_on_cart( $addon_id, $option_id, $key, $value, $cart_item, $grouped_in_cart );
							$addon_prices = $this->calculate_addon_prices_on_cart( $addon_id, $option_id, $key, $value, $cart_item, $product_price );

							$option_price     = 0;
							$addon_price      = abs( floatval( $addon_prices['price'] ) );
							$addon_sale_price = abs( floatval( $addon_prices['price_sale'] ) );
							$sign             = $addon_prices['sign'];

							$info                         = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id );
							$addon_type                   = isset( $info['addon_type'] ) ? $info['addon_type'] : '';
							$addon_first_options_selected = isset( $info['addon_first_options_selected'] ) ? $info['addon_first_options_selected'] : '';

							$is_empty_select = 'select' === $addon_type && 'default' === $option_id;

							if ( $is_empty_select ) {
								continue;
							}

							if ( 'product' === $addon_type ) {

								if ( ! isset( $cart_item['qpeofw_qty_options'][ $key ] ) ) {
									$cart_item['qpeofw_qty_options'][ $key ] = $quantity;
								}

								$option_product_info = explode( '-', $value );
								$option_product_id   = $option_product_info[1];
								$option_product_qty  = isset( $cart_item['qpeofw_qty_options'][ $key ] ) ? $cart_item['qpeofw_qty_options'][ $key ] : 1;
								$option_product      = wc_get_product( $option_product_id );
								if ( $option_product && $option_product instanceof WC_Product ) {
									// Stock.
									if ( $option_product->get_manage_stock() ) {
										$stock_qty = $option_product->get_stock_quantity() - $option_product_qty;
										wc_update_product_stock( $option_product, $stock_qty, 'set' );
										wc_delete_product_transients( $option_product );
									}

									if ( isset( $cart_item['qpeofw_qty_options'] ) ) {
										wc_add_order_item_meta( $item_id, '_qpeofw_product_addon_qty', $cart_item['qpeofw_qty_options'] );
									}
								}
							}

							// First X free options check.
							if ( 'yes' === $addon_first_options_selected && $first_free_options_count < $addon_first_options_selected ) {
								$first_free_options_count++;
							} else {
								if ( 0 !== $addon_price || 0 !== $addon_sale_price ) {
									if ( $addon_sale_price ) {
										$option_price = $addon_sale_price;
									} else {
										$option_price = $addon_price;
									}
								}
							}

							if ( '' === $addon_name ) {
								$addon_name = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_order_item_meta_name_default', _x( 'Option', 'Show it in the cart page if the add-on has not a label set', 'qode-product-extra-options-for-woocommerce' ), $index, $item_id, $cart_item );
							}

							$addon_value  = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_value_as_order_item', $addon_value, $key );
							$option_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_prices_as_order_item', $option_price );

							$display_value = $this->get_addon_display_on_cart( $addon_value, $sign, $option_price, $addon_price, $addon_sale_price );
							$display_value = html_entity_decode( stripslashes( $display_value ) );

							wc_add_order_item_meta( $item_id, $addon_name, $display_value );
						}
					}
				}
				wc_add_order_item_meta( $item_id, '_qode_product_extra_options_for_woocommerce_meta_data', $cart_item['qpeofw_options'] );
				if ( ! empty( $cart_item['qpeofw_product_img'] ) ) {
					wc_add_order_item_meta( $item_id, '_qode_product_extra_options_for_woocommerce_product_img', $cart_item['qpeofw_product_img'] );
				}
			}
		}

		/**
		 * Add to cart URL
		 *
		 * @param string $url URL.
		 *
		 * @return false|string|WP_Error
		 */
		public function add_to_cart_url( string $url = '' ) {

			if ( is_product() ) {
				return $url;
			}

			$product    = qode_product_extra_options_for_woocommerce_get_global_product();
			$product_id = qode_product_extra_options_for_woocommerce_woo_get_base_product_id( $product );

			if ( qode_product_extra_options_for_woocommerce_product_has_blocks( $product_id ) ) {
				return get_permalink( $product_id );
			}

			return $url;
		}

		/**
		 * Add to cart text
		 *
		 * @param string $text Text.
		 *
		 * @return false|mixed|string|void
		 */
		public function add_to_cart_text( $text = '' ) {
			$post    = qode_product_extra_options_for_woocommerce_get_global_post();
			$product = qode_product_extra_options_for_woocommerce_get_global_product();

			if ( is_object( $product ) && ! is_single( $post ) && ! is_product() ) {
				$_product_id = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_original_product_id', $product->get_id() );
				if ( qode_product_extra_options_for_woocommerce_product_has_blocks( $_product_id ) ) {
					return qode_product_extra_options_for_woocommerce_get_option_value( 'admin', 'qode_product_extra_options_for_woocommerce_product_list_select_options_label' );
				}
			}

			return $text;
		}

		/**
		 * Filter price in cart for items included in a bundle (support for QODE Product Bundles For WooCommerce).
		 *
		 * @param $sum_price
		 * @param float $price Cart item price.
		 * @param $quantity
		 * @param float $bundled_items_price Bundle items price.
		 * @param array $cart_item Cart item.
		 *
		 * @return string
		 */
		public function qpbfw_woocommerce_cart_item_price( $sum_price, $price, $quantity, $bundled_items_price, $cart_item ) {

			if ( ! empty( $cart_item ) && isset( $cart_item['qpeofw_options'] ) ) {
				$types_total_price = $this->get_total_add_ons_price( $cart_item );

				if ( isset( $cart_item['qpeofw_sold_individually'] ) && $cart_item['qpeofw_sold_individually'] ) {
					$bundled_items_price = 0;
				}

				$sum_price = ( $price + $types_total_price ) * $quantity;
			}

			return $sum_price;
		}

		/**
		 * Get total price for add-ons
		 *
		 * @param array $cart_item Cart item.
		 *
		 * @return int
		 */
		public function get_total_add_ons_price( $cart_item ) {
			$type_list         = $this->get_cart_add_ons_options( $cart_item );
			$types_total_price = $this->get_total_by_add_ons_list( $type_list, $cart_item );

			return $types_total_price;
		}

		/**
		 * Filter cart item and add add-ons options
		 *
		 * @param array  $cart_item Cart item.
		 * @param string $type Option type.
		 *
		 * @return array
		 */
		public function get_cart_add_ons_options( $cart_item, $type = 'all' ) {

			$cart_item_filtered = array();

			if ( isset( $cart_item['qpeofw_options'] ) ) {

				if ( isset( $cart_item['qpeofw_sold_individually'] ) ) {
					if ( $cart_item['qpeofw_sold_individually'] ) {
						$type = 'sold_individually';
					} else {
						$type = 'simple';
					}
				}
				foreach ( $cart_item['qpeofw_options'] as $key => $single_type_option ) {

					if ( 'all' === $type ) {
						$cart_item_filtered [ $key ] = $single_type_option;
					} elseif ( 'sold_individually' === $type && isset( $single_type_option['sold_individually'] ) && $single_type_option['sold_individually'] ) {
						$cart_item_filtered [ $key ] = $single_type_option;
					} elseif ( 'simple' === $type && ( ! isset( $single_type_option['sold_individually'] ) || ( isset( $single_type_option['sold_individually'] ) && ! $single_type_option['sold_individually'] ) ) ) {
						$cart_item_filtered[ $key ] = $single_type_option;
					}
				}
			}

			return $cart_item_filtered;
		}

		/**
		 * Get total price for add-ons list
		 *
		 * @param array $type_list Type list.
		 * @param array $cart_item The cart item.
		 *
		 * @return int
		 */
		private function get_total_by_add_ons_list( $type_list, $cart_item ) {
			$option_price = 0;
			$total_price  = 0;

			// WooCommerce Measurement Price Calculator (compatibility).
			if ( isset( $cart_item['pricing_item_meta_data']['_price'] ) ) {
				$product_price = $cart_item['pricing_item_meta_data']['_price'];
			}
			foreach ( $type_list as $list ) {
				foreach ( $list as $key => $value ) {
					if ( $key && '' !== $value ) {
						$values = Qode_Product_Extra_Options_For_WooCommerce_Main::get_instance()->split_addon_and_option_ids( $key, $value );

						$addon_id  = $values['addon_id'];
						$option_id = $values['option_id'];

						$info = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id );

						// TODO: use the price calculation method > calculate_addon_prices_on_cart used by products bundle plugins.
						if ( 'percentage' === $info['price_type'] ) {
							$option_percentage      = floatval( $info['price'] );
							$option_percentage_sale = floatval( $info['price_sale'] );
							$option_price           = ( $product_price / 100 ) * $option_percentage;
							$option_price_sale      = ( $product_price / 100 ) * $option_percentage_sale;
						} elseif ( 'multiplied' === $info['price_type'] ) {
							$option_price      = $info['price'] * $value;
							$option_price_sale = $info['price_sale'] * $value;
						} elseif ( 'characters' === $info['price_type'] ) {
							$remove_spaces     = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_remove_spaces', false );
							$value             = $remove_spaces ? str_replace( ' ', '', $value ) : $value;
							$value_length      = function_exists( 'mb_strlen' ) ? mb_strlen( $value ) : strlen( $value );
							$option_price      = floatval( $info['price'] ) * $value_length;
							$option_price_sale = floatval( $info['price_sale'] ) * $value_length;
						} else {
							$option_price      = $info['price'];
							$option_price_sale = $info['price_sale'];
						}

						if ( 'number' === $info['addon_type'] ) {
							if ( 'value_x_product' === $info['price_method'] ) {
								$option_price = $value * $product_price;
							} else {
								if ( 'multiplied' === $info['price_type'] ) {
									$option_price = $value * $info['price'];
								}
							}
						}

						$option_price = $option_price_sale > 0 ? $option_price_sale : $option_price;

						if ( in_array( $info['addon_type'], array( 'product' ), true ) ) {
							$option_product_info = explode( '-', $value );
							$option_product_id   = $option_product_info[1];
							$option_product      = wc_get_product( $option_product_id );

							// Product prices.
							$product_price = $option_product instanceof WC_Product ? $option_product->get_price() : 0;
							if ( 'product' === $info['price_method'] ) {
								$option_price = $product_price;
							} elseif ( 'discount' === $info['price_method'] ) {
								$option_discount_value = floatval( $info['price'] );
								if ( 'percentage' === $info['price_type'] ) {
									$option_price = $product_price - ( ( $product_price / 100 ) * $option_discount_value );
								} else {
									$option_price = $product_price - $option_discount_value;
								}
							}
						}
						$option_price = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_prices_on_bundle_cart_item', $option_price );
					}
				}
				$total_price += (float) $option_price;
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_total_by_add_ons_list', $total_price, $type_list, $cart_item );
		}

		/**
		 * Return Order item subtotal
		 *
		 * @param string $product_sub_total Product subtotal.
		 * @param array  $item Order Item data.
		 * @param object $order WC Order object.
		 *
		 * @return string
		 */
		public function woocommerce_order_formatted_line_subtotal( $product_sub_total, $item, $order ) {

			if ( isset( $item['item_meta']['_qode_product_extra_options_for_woocommerce_meta_data'] ) && isset( $item['item_meta']['_bundled_items'][0] ) ) {

				$type_list         = maybe_unserialize( $item['item_meta']['_qode_product_extra_options_for_woocommerce_meta_data'] );
				$types_total_price = $this->get_total_by_add_ons_list( $type_list, $item );

				$tax_display = $order->tax_display_cart;

				if ( 'excl' === $tax_display ) {
					$ex_tax_label      = $order->prices_include_tax ? 1 : 0;
					$product_sub_total = wc_price(
						$order->get_line_subtotal( $item ) + $types_total_price,
						array(
							'ex_tax_label' => $ex_tax_label,
							'currency'     => $order->get_order_currency(),
						)
					);
				} else {
					$product_sub_total = wc_price( $order->get_line_subtotal( $item, true ) + $types_total_price, array( 'currency' => $order->get_order_currency() ) );
				}
			}

			return $product_sub_total;
		}

		/**
		 * Get addon cart items quantities - merged so we can do accurate stock checks on items across multiple lines.
		 *
		 * @return array
		 */
		public function get_cart_item_quantities() {
			$quantities = array();

			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {

				if ( isset( $values['qpeofw_qty_options'] ) ) {
					foreach ( $values['qpeofw_qty_options'] as $key => $quantity ) {
						$quantities[ $key ] = isset( $quantities[ $key ] ) ? $quantities[ $key ] + $quantity : $quantity;
					}
				}
			}
			return $quantities;
		}

		/**
		 * Calculate the add-on price individually on cart
		 *
		 * @param int $addon_id The add-on id.
		 * @param int $option_id The option id.
		 * @param int $key The key.
		 * @param string $value The value
		 * @param array $cart_item The cart item.
		 * @param float $product_price The product price.
		 * @param bool $calculate_taxes
		 *
		 * @return array
		 */
		public function calculate_addon_prices_on_cart( $addon_id, $option_id, $key, $value, $cart_item, $product_price, $calculate_taxes = true ) {

			$info = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id, $calculate_taxes );

			// Free, increase, decrease, product, discount (product), value_x_product (number).
			$price_method = isset( $info['price_method'] ) ? $info['price_method'] : '';
			// fixed, percentage, multiplied(number), characters(text, textarea).
			$price_type = isset( $info['price_type'] ) ? $info['price_type'] : '';
			$price      = isset( $info['price'] ) ? $info['price'] : '';
			$price_sale = isset( $info['price_sale'] ) ? $info['price_sale'] : '';

			$option_product_qty = isset( $cart_item['qpeofw_qty_options'][ $key ] ) ? $cart_item['qpeofw_qty_options'][ $key ] : 1;

			$addon_price      = 0;
			$addon_price_sale = 0;

			switch ( $price_method ) {
				case 'increase':
				case 'decrease':
					$addon_price      = $this->get_addon_price( $price, $price_method, $price_type, $product_price, $value );
					$addon_price_sale = $this->get_addon_price( $price_sale, $price_method, $price_type, $product_price, $value );
					break;
				case 'product':
				case 'discount':
					$option_product_info = explode( '-', $value );
					$option_product_id   = isset( $option_product_info[1] ) ? $option_product_info[1] : '';
					$option_product      = wc_get_product( $option_product_id );
					if ( $option_product instanceof WC_Product ) {

						$product_price_addon = $calculate_taxes ? wc_get_price_to_display( $option_product ) : $option_product->get_price();
						// Use price of linked product.
						if ( 'product' === $price_method ) {
							$addon_price = $product_price_addon;
						}
						// Discount price of linked product.
						if ( 'discount' === $price_method ) {
							$option_discount_value = floatval( $price );
							if ( 'percentage' === $price_type ) {
								$addon_price = $product_price_addon - ( ( $product_price_addon / 100 ) * $option_discount_value );
							} else {
								$addon_price = $product_price_addon - $option_discount_value;
							}
						}
						break;
					}
					// intentionally fall through.
					// Value multiplied by product price.
				case 'value_x_product':
					if ( is_numeric( $value ) ) {
						$addon_price = $value * $product_price;
					}
					break;
				default:
					break;
			}

			$addon_price      = ! empty( (float) $addon_price ) ? (float) $addon_price * $option_product_qty : 0;
			$addon_price_sale = ! empty( (float) $addon_price_sale ) ? (float) $addon_price_sale * $option_product_qty : 0;

			return array(
				'price'      => apply_filters( 'qode_product_extra_options_for_woocommerce_filter_addon_price', $addon_price, $addon_id, $option_id, $key, $value, $cart_item, $product_price ),
				'price_sale' => $addon_price_sale,
				'sign'       => apply_filters( 'qode_product_extra_options_for_woocommerce_filter_price_sign', 'decrease' === $price_method ? '-' : '+' ),
			);
		}

		/**
		 * Get the add-on price for 'increase' and 'decrease' price methods.
		 *
		 * @param float $price The price.
		 * @param string $price_method The price method.
		 * @param string $price_type The price type.
		 * @param float $product_price The product price.
		 * @param string $value The value.
		 *
		 * @return float
		 */
		public function get_addon_price( &$price, $price_method, $price_type, $product_price, $value ) {

			if ( ! is_numeric( $price ) || ! is_numeric( $product_price ) ) {
				return $price;
			}

			if ( $price > 0 ) {
				if ( 'fixed' === $price_type ) {
					if ( 'decrease' === $price_method ) {
						$price = - $price;
					}
				} elseif ( 'percentage' === $price_type ) {
					$price = ( $product_price * $price ) / 100;
					if ( 'decrease' === $price_method ) {
						$price = - $price;
					}
				} elseif ( 'characters' === $price_type ) {
					$remove_spaces        = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_remove_spaces', false );
					$value                = $remove_spaces ? str_replace( ' ', '', $value ) : $value;
					$number_of_characters = function_exists( 'mb_strlen' ) ? mb_strlen( $value ) : strlen( $value );

					$price = $price * $number_of_characters;

				} elseif ( 'multiplied' === $price_type ) {
					$price = $price * $value;
				}
			}

			return $price;
		}

		/**
		 * Get the add-on value on cart.
		 *
		 * @param int $addon_id The add-on id.
		 * @param int $option_id The option id.
		 * @param int $key The key.
		 * @param array $cart_item The cart item.
		 * @param $original_value
		 * @param bool $grouped_in_cart
		 *
		 * @return mixed|string
		 */
		public function get_addon_value_on_cart( $addon_id, $option_id, $key, $original_value, $cart_item, $grouped_in_cart = false ) {

			$info              = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id );
			$addon_title       = $info['addon_label'] ?? '';
			$title_in_cart     = $info['title_in_cart'] ?? '';
			$title_in_cart_opt = $info['title_in_cart_opt'] ?? '';
			// checkbox, radio, select, product, color, colorpicker, file, number, date, label, text, textarea.
			$addon_type        = $info['addon_type'] ?? '';
			$label             = $info['label'] ?? '';
			$label_in_cart     = $info['label_in_cart'] ?? '';
			$label_in_cart_opt = $info['label_in_cart_opt'] ?? '';
			$color             = $info['color'] ?? '';
			$color_b           = $info['color_b'] ?? '';

			if ( ! empty( $color_b ) ) {
				$color = $color . ' - ' . $color_b;
			}

			$is_empty_title = false;

			if ( ! wc_string_to_bool( $label_in_cart ) && ! empty( $label_in_cart_opt ) ) {
				$label = $label_in_cart_opt;
			}

			$value = '';

			if ( ( empty( $addon_title ) && wc_string_to_bool( $title_in_cart ) ) || ( empty( $addon_title ) && ! wc_string_to_bool( $title_in_cart ) && empty( $title_in_cart_opt ) ) ) {
				$is_empty_title = true;
			}

			if ( 'product' === $addon_type ) {

				$qode_product_extra_options_for_woocommerce_product_info = explode( '-', $original_value );
				$qode_product_extra_options_for_woocommerce_product_id   = isset( $qode_product_extra_options_for_woocommerce_product_info[1] ) ? $qode_product_extra_options_for_woocommerce_product_info[1] : '';
				$qode_product_extra_options_for_woocommerce_product_qty  = isset( $cart_item['qpeofw_qty_options'][ $key ] ) ? $cart_item['qpeofw_qty_options'][ $key ] : 1;

				$option_product = wc_get_product( $qode_product_extra_options_for_woocommerce_product_id );
				if ( $option_product instanceof WC_Product ) {
					$value = apply_filters(
						'qpeofw_filter_product_name_in_cart',
						$qode_product_extra_options_for_woocommerce_product_qty . ' x ' . $option_product->get_name(),
						$option_product,
						$qode_product_extra_options_for_woocommerce_product_qty,
						$cart_item
					);
				}
			} elseif ( in_array( $addon_type, array( 'text', 'textarea', 'number', 'date', 'colorpicker' ), true ) ) {
				if ( ! $grouped_in_cart && ! $is_empty_title ) {
					$label = ! empty( $label ) ? $label . ': ' : '';
					$value = $label . $original_value;
				} else {
					$value = $original_value;
				}
			} elseif ( 'file' === $addon_type ) {
				$files      = $original_value;
				$file_links = '';
				if ( is_array( $files ) ) {
					foreach ( $files as $file_id => $file_url ) {
						$file_url   = urldecode( $file_url );
						$file_split = explode( '/', $file_url );
						// translators: Label shown on cart for add-on type Upload.
						$file_name = apply_filters( 'qode_product_extra_options_for_woocommerce_filter_show_attached_file_name', true ) ? end( $file_split ) : esc_html__( 'Attached file', 'qode-product-extra-options-for-woocommerce' );

						$file_links .= '<br><a href="' . $file_url . '" target="_blank">' . $file_name . '</a>';
					}
					$value = $label . ': ' . $file_links;
					if ( empty( $label ) ) {
						$value = $file_links;
					}
				}
			} elseif ( in_array( $addon_type, array( 'select', 'radio', 'label', 'color', 'checkbox' ), true ) ) {
				$value = $label;
			}

			return apply_filters( 'qode_product_extra_options_for_woocommerce_filter_get_addon_value_on_cart', $value, $addon_id, $option_id, $key, $original_value, $cart_item );
		}

		/**
		 * Get the add-on data name.
		 *
		 * @param int $addon_id The add-on id.
		 * @param int $option_id The option id.
		 * @param bool $grouped_in_cart
		 *
		 * @return mixed
		 */
		public function get_addon_data_name( $addon_id, $option_id, $grouped_in_cart = false ) {
			$info = qode_product_extra_options_for_woocommerce_get_option_info( $addon_id, $option_id );

			$addon_title             = $info['addon_label'] ?? '';
			$addon_title_in_cart     = $info['title_in_cart'] ?? '';
			$addon_title_in_cart_opt = $info['title_in_cart_opt'] ?? '';

			$addon_label             = $info['label'] ?? '';
			$addon_label_in_cart     = $info['label_in_cart'] ?? '';
			$addon_label_in_cart_opt = $info['label_in_cart_opt'] ?? '';

			if ( ! wc_string_to_bool( $addon_title_in_cart ) && ! empty( $addon_title_in_cart_opt ) ) {
				$addon_title = $addon_title_in_cart_opt;
			}
			if ( ! wc_string_to_bool( $addon_label_in_cart ) && ! empty( $addon_label_in_cart_opt ) ) {
				$addon_label = $addon_label_in_cart_opt;
			}

			if ( $grouped_in_cart || empty( $addon_title ) ) {
				$addon_title = $addon_label;
			}

			return $addon_title;
		}
	}
}

/**
 * Unique access to instance of Qode_Product_Extra_Options_For_WooCommerce_Cart class
 *
 * @return Qode_Product_Extra_Options_For_WooCommerce_Cart
 */
function Qode_Product_Extra_Options_For_WooCommerce_Cart() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return Qode_Product_Extra_Options_For_WooCommerce_Cart::get_instance();
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_cart_init' ) ) {
	/**
	 * Init product add-ons cart module instance.
	 */
	function qode_product_extra_options_for_woocommerce_cart_init() {
		Qode_Product_Extra_Options_For_WooCommerce_Cart::get_instance();
	}

	add_action( 'init', 'qode_product_extra_options_for_woocommerce_cart_init', 20 );
}
