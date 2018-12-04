<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functionality that deals with performance-related issues.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_setup_development_mode' ) ) :
add_filter( 'nice_development_mode', 'nice_setup_development_mode' );
/**
 * Setup development mode.
 *
 * @since 2.0
 */
function nice_setup_development_mode() {
	return nice_bool_option( '_development_mode' );
}
endif;

if ( ! function_exists( 'nice_setup_on_demand_js' ) ) :
add_filter( 'nice_on_demand_js', 'nice_setup_on_demand_js' );
/**
 * Setup JS on demand.
 *
 * @since 2.0
 */
function nice_setup_on_demand_js() {
	return nice_bool_option( '_load_js_on_demand' );
}
endif;

if ( ! function_exists( 'nice_setup_minified_files' ) ) :
add_filter( 'nice_use_minified_files', 'nice_setup_minified_files' );
/**
 * Setup usage of minified scripts.
 *
 * @since 2.0
 */
function nice_setup_minified_files() {
	return nice_bool_option( '_load_minified_js' );
}
endif;

if ( ! function_exists( 'nice_setup_lazyload' ) ) :
add_filter( 'nice_lazyload_images', 'nice_setup_lazyload' );
/**
 * Setup LazyLoad usage.
 *
 * @since 2.0
 */
function nice_setup_lazyload() {
	return nice_bool_option( '_lazyload_images' );
}
endif;

if ( ! function_exists( 'nice_setup_async_styles' ) ) :
add_filter( 'nice_load_async_styles', 'nice_setup_async_styles' );
/**
 * Setup asynchronous CSS.
 *
 * @since 2.0
 */
function nice_setup_async_styles() {
	return nice_bool_option( '_async_styles' );
}
endif;

if ( ! function_exists( 'nice_autoptimize_async_styles' ) ) :
add_filter( 'autoptimize_html_after_minify', 'nice_autoptimize_async_styles' );
/**
 * Make Autoptimize work with asynchronous styles.
 *
 * We're working under the asumption that no one would want to use
 * `media="none"` in a link tag if they're not planning to load the style
 * asynchronously. If that is not the case for someone, we'll have to provide
 * a snippet to remove this functionality.
 *
 * @param string $html
 *
 * @return mixed|string
 */
function nice_autoptimize_async_styles( $html = '' ) {
	$html = str_replace( '<link type="text/css" media="none"', '<link type="text/css" media="none" onload="media=\'all\'"', $html );

	return $html;
}
endif;
