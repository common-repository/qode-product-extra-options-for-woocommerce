<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the action-button component
 *
 * @var array $component The component.
 */

list ( $component_id, $class, $the_title, $attributes, $data, $button_action, $icon, $icon_class, $url, $confirm_data ) = qode_product_extra_options_for_woocommerce_extract( $component, 'id', 'class', 'title', 'attributes', 'data', 'action', 'icon', 'icon_class', 'url', 'confirm_data' );

$button_action = isset( $button_action ) ? $button_action : '';
$icon          = isset( $icon ) ? $icon : $button_action;
$icon_class    = isset( $icon_class ) ? $icon_class : "qodef-icon qodef-icon-{$icon}";
$url           = isset( $url ) ? $url : '#';
$class         = isset( $class ) ? $class : '';
$the_title     = isset( $the_title ) ? $the_title : '';
$confirm_data  = isset( $confirm_data ) ? $confirm_data : array();

$classes = array( 'qodef-action-button', "qodef-action-button--{$button_action}-action", $class );

$link_classes = array( 'qodef-action-button--link' );
$link_data    = array();
if ( isset( $confirm_data['title'], $confirm_data['message'] ) && '#' !== $url ) {
	$link_classes[] = 'qodef-action-button--require-confirmation';
	$link_data      = $confirm_data;
}

if ( $the_title ) {
	$link_classes[] = 'qodef-action-button--tips';
}

$class      = implode( ' ', $classes );
$link_class = implode( ' ', array_filter( $link_classes ) );
?>
<span
		id="<?php echo esc_attr( $component_id ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
	<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo qode_product_extra_options_for_woocommerce_html_attributes_to_string( $attributes );
	?>
	<?php
		qode_product_extra_options_for_woocommerce_html_data_to_string( $data, true );
	?>
	>
		<a class="<?php echo esc_attr( $link_class ); ?>"
				aria-label="<?php echo esc_attr( $the_title ); ?>"
				href="
				<?php
				if ( 'javascript:void(0)' === $url ) {
					echo esc_attr( $url );
				} else {
					echo esc_url( $url );
				}
				?>
"
			<?php if ( $the_title ) : ?>
				data-tip="<?php echo esc_attr( $the_title ); ?>"
			<?php endif; ?>

			<?php qode_product_extra_options_for_woocommerce_html_data_to_string( $link_data, true ); ?>
		>
			<?php if ( $icon ) : ?>
				<?php qode_product_extra_options_for_woocommerce_render_svg_icon( $icon, $icon_class ); ?>
			<?php endif; ?>
		</a>
</span>
