<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file includes functions to interact with the NiceFramework
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

if ( ! function_exists( 'nice_theme_admin_menu_label' ) ) :
add_filter( 'nice_admin_menu_label', 'nice_theme_admin_menu_label' );
add_filter( 'nice_admin_bar_menu_label', 'nice_theme_admin_menu_label' );
/**
 * Set the Admin Menu label
 *
 * @since 2.0
 *
 * @return string
 */
function nice_theme_admin_menu_label() {
	return esc_html__( 'Flatbase', 'nicethemes' );
}
endif;

if ( ! function_exists( 'nice_admin_show_nice_admin_bar_menu' ) ) :
add_filter( 'show_nice_admin_bar_menu', 'nice_admin_show_nice_admin_bar_menu' );
/**
 * Display (or not) the Admin Menu Bar
 *
 * @since 1.0.0
 *
 * @return string
 */
function nice_admin_show_nice_admin_bar_menu() {

	return nice_get_option( '_admin_bar_menu' );

}
endif;