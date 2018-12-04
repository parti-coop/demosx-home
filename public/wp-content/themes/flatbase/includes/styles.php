<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file includes functions to manage styles for the theme.
 *
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     1.0.3
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_theme_async_styles' ) ) :
add_filter( 'nice_async_styles', 'nice_theme_async_styles', 10, 2 );
/**
 * List of styles that can be loaded asynchronously.
 *
 * @since 2.0
 *
 * @param array $scripts
 * @param array $context
 *
 * @return array
 */
function nice_theme_async_styles( $scripts = array(), $context = array() ) {
	// Return early if asynchronous styles are not setup.
	if ( ! nice_load_async_styles() ) {
		return array();
	}

	$scripts = array_merge( $scripts, array(
			'nice-fancybox-styles',
			'nice-font-awesome-source',
		)
	);

	if ( class_exists( 'bbPress' ) ) {
		$scripts = array_merge( $scripts, array(
			'nice-bbpress-styles'
			)
		);
	}

	/**
	 * @hook nice_theme_async_styles
	 *
	 * Hook here to modify the list os asynchronous styles.
	 *
	 * @since 2.0
	 *
	 * Hooked here:
	 * @see nice_web_fonts_async_styles()
	 */
	return apply_filters( 'nice_theme_async_styles', $scripts, $context );
}
endif;

if ( ! function_exists( 'nice_styles' ) ) :
add_action( 'nice_register_styles', 'nice_styles', 0 ); // Use zero as priority to make sure these files are loaded first.
/**
 * Load CSS files.
 *
 * @uses wp_register_style()
 * @uses wp_enqueue_style()
 *
 * @since 1.0.0
 */
function nice_styles() {
	/**
	 * Load main styles.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_default_styles', true ) ) {
		wp_register_style( 'nice-styles', get_stylesheet_uri() );
		wp_enqueue_style( 'nice-styles' );
	}

	/**
	 * Load Fancybox styles.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_fancybox_styles', true ) ) {
		wp_register_style( 'nice-fancybox-styles', get_template_directory_uri() . '/includes/assets/css/jquery.fancybox.css' );
		wp_enqueue_style( 'nice-fancybox-styles' );
	}

	/**
	 * Load bbPress styles.
	 *
	 * @since 1.0.0
	 */
	if ( class_exists( 'bbPress' ) ) {
		wp_register_style( 'nice-bbpress-styles', nice_get_file_uri( 'bbpress/bbpress.css' ) );
		wp_enqueue_style( 'nice-bbpress-styles' );
	}

	/**
	 * Load FontAwesome.
	 *
	 * @since 1.0.0
	 *
	 */
	if ( apply_filters( 'nice_font_awesome_styles', true ) ) {
		// Load source.
		wp_register_style( 'nice-font-awesome-source', get_template_directory_uri() . '/includes/assets/css/font-awesome.min.css' );
		wp_enqueue_style( 'nice-font-awesome-source' );

	}

	/**
	 * Load Font Awesome for IE7.
	 *
	 * @uses WP_Styles::add_data()
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_font_awesome_ie7_styles', true ) ) {
		global $wp_styles;

		wp_enqueue_style( 'nice-font-awesome-ie7-styles', get_template_directory_uri() . '/includes/assets/css/font-awesome-ie7.min.css', array( 'nice-style' ) );
		$wp_styles->add_data( 'nice-font-awesome-ie7-styles', 'conditional', 'IE 7' );
	}

}
endif;
