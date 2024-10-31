<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

interface Qode_Product_Extra_Options_For_WooCommerce_Framework_Tree_Interface {
	public function has_children();

	public function get_children();

	public function get_child( $key );

	public function add_child( Qode_Product_Extra_Options_For_WooCommerce_Framework_Child_Interface $field );
}
