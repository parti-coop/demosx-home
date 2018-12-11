<?php
/**
 * Demosx by NiceThemes.
 *
 * This file contains functions to manage widget registration.
 *
 * @package   Demosx
 * @author    Parti Coop <contact@parti.xyz>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'demosx_register_widgets' ) ) :
add_action( 'widgets_init', 'demosx_register_widgets' );
/**
 * Register loaded widgets.
 *
 * @since 2.0
 */
function demosx_register_widgets() {
	// Register article categories widgets.
	register_widget( 'Demosx_ArticleCategories' );
}
endif;
