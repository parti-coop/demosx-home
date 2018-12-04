<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the theme's setup.
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

if ( ! function_exists( 'nice_theme_textdomain' ) ) :
add_filter( 'nice_theme_textdomain', 'nice_theme_textdomain' );
/**
 * Setup the theme textdomain.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_theme_textdomain() {
	return 'nicethemes';
}
endif;

if ( ! function_exists( 'nice_theme_slug' ) ) :
add_filter( '_nice_theme_name', 'nice_theme_name' );
/**
 * Setup the theme name.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_theme_name() {
	return 'Flatbase';
}
endif;

if ( ! function_exists( 'nice_theme_slug' ) ) :
add_filter( '_nice_theme_slug', 'nice_theme_slug' );
/**
 * Setup the theme slug.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_theme_slug() {
	return 'flatbase';
}
endif;

if ( ! function_exists( 'nice_theme_admin_menu_label' ) ) :
add_filter( 'nice_admin_menu_label', 'nice_theme_admin_menu_label' );
add_filter( 'nice_admin_bar_menu_label', 'nice_theme_admin_menu_label' );
/**
 * Set the Admin Menu label
 *
 * @since 1.0.0
 *
 * @return string
 */
function nice_theme_admin_menu_label() {
	return esc_html__( 'Flatbase', 'nicethemes' );
}
endif;

