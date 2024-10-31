<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_woo_is_using_block_template_in' ) ) {
	/**
	 * Check if it is using the block template in a specific template page?
	 * Requires WooCommerce 7.9 or higher and WordPress 5.9 or higher.
	 *
	 * @param string $template_name The template to check.
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_woo_is_using_block_template_in( $template_name ) {
		static $use_blocks = array();
		if ( ! isset( $use_blocks[ $template_name ] ) ) {
			// The blockified templates are available by default since WooCommerce 7.9.
			$use_blocks[ $template_name ] = function_exists( 'WC' ) && version_compare( WC()->version, '7.9.0', '>=' );

			/**
			 * WooCommerce 7.9 includes blockified templates for the following templates,
			 * so, if the template retrieved by the query is not found and it's in this list,
			 * we can assume it's blockified.
			 */
			$blokified_templates = array( 'archive-product', 'product-search-results', 'single-product', 'taxonomy-product_attribute', 'taxonomy-product_cat', 'taxonomy-product_tag' );

			$use_blocks[ $template_name ] = $use_blocks[ $template_name ] && function_exists( 'wp_is_block_theme' ) && wp_is_block_theme();

			if ( $use_blocks[ $template_name ] ) {
				$templates = get_block_templates( array( 'slug__in' => array( $template_name ) ) );

				$is_block_template = function ( $content ) use ( $template_name ) {
					switch ( $template_name ) {
						case 'cart':
							return has_block( 'woocommerce/cart', $content );
						case 'checkout':
							return has_block( 'woocommerce/checkout', $content );
						default:
							return ! has_block( 'woocommerce/legacy-template', $content );
					}
				};

				if ( isset( $templates[0] ) ) {
					$content = $templates[0]->content;
					if ( ! $is_block_template( $content ) ) {
						$use_blocks[ $template_name ] = false;
					} elseif ( has_block( 'core/pattern', $content ) ) {
						// Search also in patterns (only one depth).
						$blocks = parse_blocks( $content );
						foreach ( $blocks as $block ) {
							$name = $block['blockName'];
							if ( 'core/pattern' === $name ) {
								$registry = WP_Block_Patterns_Registry::get_instance();
								$slug     = $block['attrs']['slug'] ?? '';

								if ( $registry->is_registered( $slug ) ) {
									$pattern = $registry->get_registered( $slug );
									if ( ! $is_block_template( $pattern['content'] ) ) {
										$use_blocks[ $template_name ] = false;
										break;
									}
								}
							}
						}
					}
				} elseif ( ! in_array( $template_name, $blokified_templates, true ) ) {
					$use_blocks[ $template_name ] = false;
				}
			}
		}

		return $use_blocks[ $template_name ];
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_woo_is_using_block_template_in_single_product' ) ) {
	/**
	 * Is using the block template in Single Product page?
	 *
	 * @return bool
	 */
	function qode_product_extra_options_for_woocommerce_woo_is_using_block_template_in_single_product() {
		return qode_product_extra_options_for_woocommerce_woo_is_using_block_template_in( 'single-product' );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_woo_get_display_price' ) ) {
	/**
	 * Get the display price.
	 *
	 * @param WC_Product $product The product.
	 * @param string     $price   The price.
	 * @param int        $qty     The quantity.
	 *
	 * @return string The price to display
	 */
	function qode_product_extra_options_for_woocommerce_woo_get_display_price( $product, $price = '', $qty = 1 ) {
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

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_woo_get_base_product_id' ) ) {
	/**
	 * Retrieve the parent product ID for WC_Product_Variation instances
	 * or the product ID in the other cases.
	 *
	 * @param WC_Product $product The product.
	 *
	 * @return int
	 */
	function qode_product_extra_options_for_woocommerce_woo_get_base_product_id( $product ) {

		return $product instanceof WC_Data && $product->is_type( 'variation' ) ? qode_product_extra_options_for_woocommerce_woo_get_prop( $product, 'parent_id' ) : qode_product_extra_options_for_woocommerce_woo_get_prop( $product, 'id' );
	}
}

if ( ! function_exists( 'qode_product_extra_options_for_woocommerce_woo_get_prop' ) ) {
	/**
	 * Retrieve a property.
	 *
	 * @param object $object  The object.
	 * @param string $key     The Meta Key.
	 * @param bool   $single  Return first found meta with key, or all.
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @return mixed|null The related value or null (if the $object is not a valid object).
	 */
	function qode_product_extra_options_for_woocommerce_woo_get_prop( $object, $key, $single = true, $context = 'view' ) {

		$is_wc_data = $object instanceof WC_Data;

		if ( $is_wc_data ) {
			$prop_map = $object->get_data_keys();
			$key      = ( array_key_exists( $key, $prop_map ) ) ? $prop_map[ $key ] : $key;
			$getter   = false;
			if ( method_exists( $object, "get{$key}" ) ) {
				$getter = "get{$key}";
			} elseif ( method_exists( $object, "get_{$key}" ) ) {
				$getter = "get_{$key}";
			}

			if ( $getter ) {
				return $object->$getter( $context );
			} else {
				return $object->get_meta( $key, $single );
			}
		} else {
			return null;
		}
	}
}
