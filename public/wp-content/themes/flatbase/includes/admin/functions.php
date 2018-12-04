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
 * @since     1.1.1
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
 * @since 1.1.1
 *
 * @return string
 */
function nice_theme_admin_menu_label() {
	return esc_html__( 'Flatbase', 'nicethemes' );
}
endif;
