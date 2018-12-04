<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file handles the setup of admin functionality.
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

if ( ! function_exists( 'nice_theme_admin_templates_dir' ) ):
add_filter( 'nice_admin_templates_dir', 'nice_theme_admin_templates_dir' );
/**
 * Modify the default folder for admin templates.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_theme_admin_templates_dir() {
	return nice_dir_path( __FILE__ ) . 'templates/';
}
endif;
