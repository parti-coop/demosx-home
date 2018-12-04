<?php
/**
 * NiceFramework by NiceThemes.
 *
 * This file includes functions to manage styles for the framework and admin section.
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2015 NiceThemes
 * @since     2.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_admin_register_styles' ) ) :
add_action( 'admin_enqueue_scripts', 'nice_admin_register_styles' );
/**
 * Register CSS files through the `nice_admin_register_styles` action hook.
 *
 * Style registrations should be hooked here, so we can make sure everything loads at the same runtime.
 *
 * @uses admin_enqueue_scripts()
 *
 * @since 2.0
 */
function nice_admin_register_styles( $hook ) {
	do_action( 'nice_admin_register_styles', $hook );
}
endif;

if ( ! function_exists( 'nice_admin_styles' ) ) :
add_action( 'nice_admin_register_styles', 'nice_admin_styles' );
/**
 * nice_admin_styles()
 *
 * Include all the css for the admin section
 *
 * @since 1.0.0
 * @updated 2.0
 *
 */

function nice_admin_styles( $hook ) {

	if ( nice_admin_is_framework_page() || ( 'post.php' === $hook || 'post-new.php' === $hook || 'page-new.php' === $hook || 'page.php' === $hook ) ) {

		global $wp_styles;

		wp_enqueue_style  ( 'wp-color-picker' );
		wp_enqueue_style  ( 'nice-framework-admin',    nice_get_file_uri( 'engine/admin/assets/css/nice-framework-admin.css' ) );
		wp_register_style ( 'nice-framework-fancybox', nice_get_file_uri( 'engine/admin/assets/css/jquery.fancybox.css' ) );
		wp_register_style ( 'nice-datepicker',         nice_get_file_uri( 'engine/admin/assets/css/datepicker.css' ) );
		wp_enqueue_style  ( 'nice-framework-fancybox' );

		add_action       ( 'admin_head', 'nice_admin_head' );
		wp_enqueue_style ( 'nice-datepicker' );
		wp_enqueue_style ( 'thickbox' );

		wp_register_style ( 'nice-budicon',         nice_get_file_uri( 'engine/admin/assets/css/budicon.css' ) );
		wp_enqueue_style  ( 'nice-budicon' );

		wp_enqueue_style  ( 'nice-budicon-ie7',     nice_get_file_uri( 'engine/admin/assets/css/budicon-ie7.css' ) );
		$wp_styles->add_data( 'nice-budicon-ie7', 'conditional', 'IE 7' );

		/**
		 * @hook nice_admin_inline_styles
		 *
		 * Hook here to add inline styles for the admin pages.
		 *
		 * @since 1.0.0
		 */
		$admin_inline_styles = apply_filters( 'nice_admin_inline_styles', '' );

		if ( ! empty( $admin_inline_styles ) ) {
			wp_add_inline_style( 'nice-framework-admin', esc_html( $admin_inline_styles ) );
		}

	}

	wp_register_style( 'nice-admin-menu', nice_get_file_uri( 'engine/admin/assets/css/admin-menu.css' ) );
	wp_enqueue_style ( 'nice-admin-menu' );

	wp_register_style( 'nice-admin-font', nice_get_file_uri( 'engine/admin/assets/css/niceadmin-font.css' ) );
	wp_enqueue_style ( 'nice-admin-font' );

}

endif;

if ( ! function_exists( 'nice_metaboxes_styles' ) ) :
add_action( 'nice_admin_register_styles', 'nice_metaboxes_styles' );
/**
 * nice_metaboxes_styles()
 *
 * Include all the css for the metaboxes section
 *
 * @since 1.0.0
 * @updated 2.0
 *
 */

function nice_metaboxes_styles( $hook ) {

	if ( 'post.php' === $hook || 'post-new.php' === $hook || 'page-new.php' === $hook || 'page.php' === $hook ) {

		// @todo: make sure jQuery UI doesn't break these styles.
		wp_register_style( 'nice-admin-metaboxes', nice_get_file_uri( 'engine/admin/assets/css/metaboxes.css' ), false, NICE_FRAMEWORK_VERSION );
		wp_enqueue_style( 'nice-admin-metaboxes' );

	}

}

endif;

if ( ! function_exists( 'nice_admin_body_class' ) ) :
add_filter( 'admin_body_class', 'nice_admin_body_class' );
/**
 * nice_admin_body_class()
 *
 * Add some custom admin body classes for framework information.
 *
 * @since  2.0
 *
 * @param  string $classes Unfiltered classes.
 *
 * @return string
 */
function nice_admin_body_class( $classes = '' ) {
	global $hook_suffix;

	if ( is_admin_niceframework() || ( 'post.php' === $hook_suffix || 'post-new.php' === $hook_suffix || 'page-new.php' === $hook_suffix || 'page.php' === $hook_suffix ) ) {
		$framework_version = str_replace( '.', '-', NICE_FRAMEWORK_VERSION );

		$classes .= " nice-framework nice-framework-{$framework_version}";
	}

	if ( nice_admin_is_framework_page() ) {
		$classes .= ' nice-framework-page';
	}

	return $classes;
}
endif;
