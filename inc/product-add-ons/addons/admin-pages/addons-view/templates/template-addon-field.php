<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Addon Advanced Options Template
 *
 * @var Qode_Product_Extra_Options_For_WooCommerce_Addon $addon
 * @var int $addon_id
 * @var string $addon_type
 * @var string $config_id
 * @var array $config_options
 */
?>

<!-- Option field -->
<?php
$field_holder_classes   = array();
$field_holder_classes[] = 'qodef-field-holder col-md-12 col-lg-12';
$field_holder_classes[] = $config_id . '-container';

if ( ! empty( $config_options['enabled-by'] ) ) {
	$field_holder_classes[] = 'qodef-dependency-holder qodef-hide-dependency-holder';
}

if ( ! empty( $config_options['field-wrap-class'] ) ) {
	$field_holder_classes[] = $config_options['field-wrap-class'];
}

$data_show = '';

if ( ! empty( $config_options['enabled-by'] ) ) {
	$data_show = 'data-show="{' . esc_attr( $config_options['enabled-by'] ) . '}"';
}

$data_relation = '';
if ( ! empty( $config_options['data-relation'] ) ) {
	$data_relation = 'data-relation="' . esc_attr( $config_options['data-relation'] ) . '"';
}
?>
<div <?php qode_product_extra_options_for_woocommerce_class_attribute( $field_holder_classes ); ?> <?php echo wp_kses_post( $data_show ); ?> <?php echo wp_kses_post( $data_relation ); ?> >
	<div class="qodef-field-section <?php echo esc_html( $config_options['div-class'] ); ?>">
		<div class="qodef-field-desc">
			<h3 class="qodef-title qodef-field-title">
				<?php echo esc_html( $config_options['title'] ); ?>
			</h3>
			<p class="qodef-description qodef-field-description">
				<?php echo wp_kses_post( $config_options['description'] ); ?>
			</p>
		</div>
		<?php
		$fields = $config_options['field'];
		foreach ( $fields as $field ) {
			$custom_message = $field['custom_message'] ?? '';

			$custom_message_text = '';
			if ( ! empty( $custom_message ) ) {
				$custom_message_text = 'custom-message';
			}
			?>
			<div class="qodef-field-content <?php echo esc_html( $field['div-class'] ); ?> <?php echo esc_attr( $custom_message_text ); ?>">
				<?php
				if ( ! empty( $field['title'] ) ) {
					?>
					<small><?php echo esc_html( $field['title'] ); ?></small>
					<?php
				}
				qode_product_extra_options_for_woocommerce_get_field(
					array(
						'id'             => $config_id,
						'name'           => $field['name'],
						'class'          => $field['class'],
						'type'           => $field['type'],
						'min'            => $field['min'],
						'max'            => $field['max'],
						'step'           => $field['step'],
						'value'          => $field['value'],
						'default'        => $field['default'],
						'options'        => $field['options'],
						'units'          => $field['units'],
						'custom_message' => $custom_message,
					),
					true
				);
			?>
			</div>
			<?php
		}
		?>
	</div>
</div>
<!-- End option field -->
