<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage custom fields for post types.
 *
 * @see nice_fields()
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2016 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! function_exists( 'nice_fields' ) ) :
add_action( 'admin_head', 'nice_fields' );
/**
 * Load array with custom fields depending on post type,
 * then save the array into WP options.
 *
 * @since 1.0.0
 */
function nice_fields() {
	// Return early if we're using the latest functionality available to get custom fields.
	if ( version_compare( NICE_FRAMEWORK_VERSION, '2.0.6', '>=' ) ) {
		return;
	}

	$nice_fields = array();
	$post_type   = get_post_type();

	// nice_custom_fields hook = see inside /includes/post-types/ for the custom fields of each post type
	$nice_fields = apply_filters( 'nice_custom_fields', $nice_fields, $post_type );

	if ( get_option( 'nice_custom_fields' ) !== $nice_fields ) {
		update_option( 'nice_custom_fields', $nice_fields );
	}
}
endif;

/**
 * Disable usage of WP option to obtain the list of custom fields.
 *
 * @since 2.0
 */
add_filter( 'nice_custom_fields_use_wp_option', '__return_false' );