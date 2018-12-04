<?php
/**
 * Flatbase by NiceThemes.
 *
 * The template for displaying the main sidebar of this theme.
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

/**
 * @hook nice_sidebar
 *
 * The Sidebar containing the main widget area.
 *
 * @since 2.0
 *
 * Hooked here:
 * @see nice_sidebar_do() - Print sidebars.
 */
do_action( 'nice_sidebar' );
