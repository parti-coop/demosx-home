<?php
/**
 * NiceFramework by NiceThemes.
 *
 * Functions related to versions.
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2015 NiceThemes
 * @since     1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! function_exists( 'nice_framework_version_init' ) ) :
add_action( 'init', 'nice_framework_version_init', 10 );
/**
 * nice_framework_version_init()
 *
 * Init version. If the framework has been updated,
 * update nice_framework_option to current version
 * stored in $version.
 *
 * @since 1.0.0
 *
 */
function nice_framework_version_init() {
	$version = NICE_FRAMEWORK_VERSION;

	if ( get_option( 'nice_framework_version' ) !== $version ) {
		update_option( 'nice_framework_version', $version );
	}
}
endif;
