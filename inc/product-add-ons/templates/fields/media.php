<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the Media field
 *
 * @var array $field The field.
 */

list ( $field_id, $class, $name, $value, $multiple, $default, $custom_attributes, $data ) = qode_product_extra_options_for_woocommerce_extract( $field, 'id', 'class', 'name', 'value', 'multiple', 'default', 'custom_attributes', 'data' );

$classes = array(
	'qodef-image-uploader',
	$class,
);

$classes = implode( ' ', array_filter( $classes ) );
?>
<?php
$has_image = ! empty( $value );

$image_holder_classes   = array();
$image_holder_classes[] = 'qodef-image-thumb';

if ( ! $has_image ) {
	$image_holder_classes[] = 'qodef-hide';
}
?>
<div class="<?php echo esc_attr( $classes ); ?>" data-file="no" data-multiple="<?php echo esc_attr( $multiple ); ?>">
	<div <?php qode_product_extra_options_for_woocommerce_class_attribute( $image_holder_classes ); ?>>
		<?php if ( 'yes' === $multiple ) { ?>
			<ul class="clearfix">
				<?php
				if ( '' !== $value ) {
					$images_array = explode( ',', $value );
					foreach ( $images_array as $image_id ) :
						$image_src = wp_get_attachment_image_src( $image_id, 'thumbnail', false );
						echo '<li ><img src="' . esc_url( $image_src[0] ) . '" alt="' . esc_attr__( 'Image Thumbnail', 'qode-product-extra-options-for-woocommerce' ) . '" /></li>';
					endforeach;
				}
				?>
			</ul>
			<?php
		} else {
			if ( '' !== $value ) {
				$image     = wp_get_attachment_image_src( $value, 'thumbnail', false );
				$image_src = ! empty( $image ) ? $image[0] : $value;
				?>
				<img class="qodef-single-image" src="<?php echo esc_url( $image_src ); ?>" alt="<?php esc_attr_e( 'Image Thumbnail', 'qode-product-extra-options-for-woocommerce' ); ?>"/>
				<?php
			}
		}
		?>
	</div>
	<div class="qodef-image-meta-fields qodef-hide">
		<input type="hidden" class="qodef-field qodef-image-upload-id" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"/>
	</div>
	<a class="qodef-image-upload-btn" href="javascript:void(0)" data-frame-title="<?php esc_attr_e( 'Select Image', 'qode-product-extra-options-for-woocommerce' ); ?>" data-frame-button-text="<?php esc_attr_e( 'Select Image', 'qode-product-extra-options-for-woocommerce' ); ?>"><?php esc_html_e( 'Upload', 'qode-product-extra-options-for-woocommerce' ); ?></a>
	<a href="javascript: void(0)" class="qodef-image-remove-btn qodef-hide"><?php esc_html_e( 'Remove', 'qode-product-extra-options-for-woocommerce' ); ?></a>
</div>
