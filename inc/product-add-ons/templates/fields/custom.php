<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Template for displaying the custom field
 *
 * @var array $field The field.
 */

if ( isset( $field['action'] ) ) {
	do_action( $field['action'], $field );
}
