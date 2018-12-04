<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Functions related to the Theme Updater.
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Load dependencies.
 */
if ( ! class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	nice_loader( 'engine/admin/lib/edd-theme-updater/' );
}

if ( ! class_exists( 'Nice_Theme_Updater'  )  ) {
	nice_loader( 'engine/admin/classes/class-nice-theme-updater.php' );
}

if ( ! class_exists( 'Nice_Theme_Updater_Admin'  )  ) {
	nice_loader( 'engine/admin/classes/class-nice-theme-updater-admin.php' );
}

if ( ! function_exists( 'nice_theme_updater_admin' ) ) :
/**
 * nice_theme_updater_admin()
 *
 * Obtain the instance of the Nice_Theme_Updater_Admin class.
 *
 * @since 2.0
 *
 * @return Nice_Theme_Updater_Admin
 */

function nice_theme_updater_admin() {
	return Nice_Theme_Updater_Admin::obtain();
}
endif;

if ( ! function_exists( 'nice_theme_updater' ) ) :
/**
 * nice_theme_updater()
 *
 * Obtain the instance of the Nice_Theme_Updater class.
 *
 * @since 2.0
 *
 * @param array $args
 * @param array $strings
 *
 * @return Nice_Theme_Updater
 */
function nice_theme_updater( $args = array(), $strings = array() ) {
	return Nice_Theme_Updater::obtain( $args, $strings );
}
endif;
