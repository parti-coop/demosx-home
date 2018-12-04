<?php
/**
 * Flatbase by NiceThemes.
 *
 * Load integrations and dependencies.
 *
 * @see nice_load_theming()
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

if ( ! function_exists( 'nice_load_theming' ) ) :
add_action( 'nice_load', 'nice_load_theming' );
/**
 * Load theming functions.
 *
 * @since 1.0.0.
 */
function nice_load_theming() {
	nice_loader( get_template_directory() . '/includes/theming/' );
}
endif;

if ( ! function_exists( 'nice_load_widgets' ) ) :
/**
 * Load Theme Widgets.
 *
 * @since 1.0.0
 */
add_action( 'nice_load', 'nice_load_widgets' );
function nice_load_widgets() {
	// Include the widgets inside the widgets folder.
	nice_loader( 'includes/widgets/' );
}
endif;

if ( ! function_exists( 'nice_load_post_types' ) ) :
add_action( 'nice_load', 'nice_load_post_types' );
/**
 * Load custom integration for supported post types.
 *
 * @since 2.0
 */
function nice_load_post_types() {
	nice_loader( 'includes/custom-post-types/' );
	nice_loader( 'includes/custom-post-types/post/' );
	nice_loader( 'includes/custom-post-types/page/' );
	nice_loader( 'includes/custom-post-types/article/' );
	nice_loader( 'includes/custom-post-types/infobox/' );
	//nice_loader( 'includes/post-types/faq/' );
}
endif;

if ( ! function_exists( 'nice_load_template_functions' ) ) :
add_action( 'nice_load', 'nice_load_template_functions' );
/**
 * Load custom integration for template functions.
 *
 * @since 1.0.0
 */
function nice_load_template_functions() {
	if ( is_admin() ) {
		return;
	}

	nice_loader( 'includes/template-functions/' );
}
endif;

if ( ! function_exists( 'nice_load_homepage' ) ) :
add_action( 'nice_load', 'nice_load_homepage' );
/**
 * Load custom integration for the homepage template.
 *
 * @since 1.0.0
 */
function nice_load_homepage() {

	nice_loader( 'includes/template-functions/home/' );
}
endif;

if ( ! function_exists( 'nice_load_admin_functions' ) ) :
add_action( 'nice_load', 'nice_load_admin_functions' );
/**
 * Load admin functions in `admin/` folder.
 *
 * @since 1.0.0
 */
function nice_load_admin_functions() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	nice_loader( 'includes/admin/' );
}
endif;

// Load integrations and dependencies here, allowing child themes and plugins to hook in.
do_action( 'nice_load' );
