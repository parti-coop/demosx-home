<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file includes functions to interact with the TGMPA class
 * https://github.com/TGMPA/TGM-Plugin-Activation
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

if ( ! function_exists( 'nice_theme_register_plugins' ) ) :
add_filter( 'nice_theme_plugins', 'nice_theme_register_plugins' );
/**
 * Define the required and recommended plugins for the theme.
 *
 * @since 1.1.1
 *
 * @return array
 */
function nice_theme_register_plugins() {
	$system_status = nice_admin_system_status();

	$plugins = array(
		array(
			'name'         => __( 'bbPress', 'nicethemes' ),
			'slug'         => 'bbpress',
			'description'  => __( 'bbPress is forum software with a twist from the creators of WordPress.', 'nicethemes' ),
			'external_url' => 'https://wordpress.org/plugins/bbpress/',
			'author_name'  => 'bbPress Community',
			'author_url'   => 'https://bbpress.org/',
			'required'     => false,
			'menu_order'   => 45,
			'image_url'    => NICE_TPL_DIR . '/includes/plugin-integrations/bbpress/screenshot.jpg',
		)
	);

	return $plugins;
}
endif;
