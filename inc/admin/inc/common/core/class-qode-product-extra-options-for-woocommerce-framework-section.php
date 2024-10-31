<?php
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}
abstract class Qode_Product_Extra_Options_For_WooCommerce_Framework_Section implements Qode_Product_Extra_Options_For_WooCommerce_Framework_Tree_Interface, Qode_Product_Extra_Options_For_WooCommerce_Framework_Child_Interface {
	private $scope;
	private $type;
	private $name;
	private $layout;
	private $title;
	private $description;
	private $dependency;
	private $icon;
	private $children;
	private $args;

	public function __construct( $params ) {
		$this->scope       = isset( $params['scope'] ) ? $params['scope'] : '';
		$this->type        = isset( $params['type'] ) ? $params['type'] : '';
		$this->name        = isset( $params['name'] ) ? $params['name'] : '';
		$this->layout      = isset( $params['layout'] ) ? $params['layout'] : 'normal';
		$this->title       = isset( $params['title'] ) ? $params['title'] : '';
		$this->description = isset( $params['description'] ) ? $params['description'] : '';
		$this->dependency  = isset( $params['dependency'] ) ? $params['dependency'] : array();
		$this->icon        = isset( $params['icon'] ) ? $params ['icon'] : '';
		$this->children    = isset( $params['children'] ) ? $params['children'] : array();
		$this->args        = isset( $params['args'] ) ? $params['args'] : array();
	}

	public function get_scope() {
		return $this->scope;
	}

	public function get_type() {
		return $this->type;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_layout() {
		return $this->layout;
	}

	public function get_title() {
		return $this->title;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_icon() {
		return $this->icon;
	}

	public function get_children() {
		return $this->children;
	}

	public function has_children() {
		return count( $this->children ) > 0;
	}

	public function get_child( $key ) {
		return $this->children[ $key ];
	}

	public function add_child( Qode_Product_Extra_Options_For_WooCommerce_Framework_Child_Interface $field ) {
		$key                    = $field->get_name();
		$this->children[ $key ] = $field;
	}

	public function get_args() {
		return $this->args;
	}

	abstract public function add_row_element( $params );

	abstract public function add_section_element( $params );

	public function add_repeater_element( $params ) {
		if ( isset( $params['name'] ) && ! empty( $params['name'] ) ) {
			$field = new Qode_Product_Extra_Options_For_WooCommerce_Framework_Field_Repeater( $params );
			$this->add_child( $field );

			return $field;
		}

		return false;
	}

	public function add_field_element( $params ) {

		if ( isset( $params['name'] ) && ! empty( $params['name'] ) ) {
			$field = new Qode_Product_Extra_Options_For_WooCommerce_Framework_Field_Mapper( $params );
			$this->add_child( $field );

			return $field;
		}

		return false;
	}

	public function render() {
		$dependency_data = array();
		$class           = array();

		$params['this_object'] = $this;
		$class[]               = 'qodef-section-' . $this->get_layout();
		$class[]               = 'qodef-section-name-' . $this->get_name();

		if ( ! empty( $this->dependency ) ) {
			$class[] = 'qodef-dependency-holder';

			$repeater = false;
			$show     = array_key_exists( 'show', $this->dependency ) ? qode_product_extra_options_for_woocommerce_framework_return_dependency_options_array( $this->scope, $this->type, $this->dependency['show'], true, $repeater ) : array();
			$hide     = array_key_exists( 'hide', $this->dependency ) ? qode_product_extra_options_for_woocommerce_framework_return_dependency_options_array( $this->scope, $this->type, $this->dependency['hide'], false, $repeater ) : array();
			$relation = array_key_exists( 'relation', $this->dependency ) ? $this->dependency['relation'] : 'and';

			$class[]         = qode_product_extra_options_for_woocommerce_framework_return_dependency_classes( $show, $hide );
			$dependency_data = qode_product_extra_options_for_woocommerce_framework_return_dependency_data( $show, $hide, $relation );
		}

		$args = $this->get_args();
		if ( isset( $args ) && count( $args ) && isset( $args['custom_class'] ) ) {
			$class[] = $args['custom_class'];
		}

		$params['class']           = implode( ' ', $class );
		$params['dependency_data'] = $dependency_data;

		qode_product_extra_options_for_woocommerce_framework_template_part( QODE_PRODUCT_EXTRA_OPTIONS_FOR_WOOCOMMERCE_ADMIN_PATH, 'inc/common', 'templates/section', $this->get_layout(), $params );
	}
}