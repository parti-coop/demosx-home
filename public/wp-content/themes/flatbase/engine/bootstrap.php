<?php
/****
  _ __  _  ___  ___
 | '_ \| |/ __|/ _ \
 | | | | | (__   __/ themes.com
 |_| |_|_|\___|\___|

 * Text Domain: nice-framework

 ****/

/**
 * Load component to check for PHP version support.
 *
 * @since 2.0.6
 */
require dirname( __FILE__ ) . '/components/php-support/component.php';

// Prevent activating the theme if running an unsupported PHP version.
if ( nice_php_support_rollback_activation() ) {
	return;
}

/**
 * Load component for PHP file management.
 *
 * @since 2.0.6
 */
require dirname( __FILE__ ) . '/components/php-file-manager/component.php';

/**
 * Load component for PHP execution context management.
 *
 * @since 2.0.6
 */
require dirname( __FILE__ ) . '/components/execution-context/component.php';

/**
 * Load component for remote settings management.
 *
 * @since 2.0.6
 */
require dirname( __FILE__ ) . '/components/remote-settings/component.php';

/**
 * Load a file for development purposes if we have one.
 *
 * This is useful for developers and users that want to test things
 * without breaking the rest of the codebase.
 *
 * @since 2.0
 */
if ( file_exists( $nice_dev_file = nice_get_theme_file_path( 'develop.php' ) ) ) {
	require $nice_dev_file;
}

/**
 * Load a file inside the framework for development purposes if we have one.
 *
 * @since 2.0.8
 */
if ( file_exists( $nice_fw_dev_file = dirname( __FILE__ ) . '/develop.php' ) ) {
	require $nice_fw_dev_file;
}

/**
 * Load a file to adjust early framework setup from themes.
 *
 * This is useful to maintain backwards compatibility for older themes when new
 * features are not fully implemented there yet.
 *
 * @since 2.0.5
 */
if ( file_exists( $nice_setup_file = nice_get_theme_file_path( 'config.php' ) ) ) {
	require $nice_setup_file;
}

/**
 * Load framework configuration file. All constants should be defined here.
 *
 * @since 2.0.6
 */
require dirname( __FILE__ ) . '/config.php';

/**
 * Define framework path to load files.
 *
 * @since 2.0.6
 */
$nice_framework_path = nice_dir_path( __FILE__ );

/**
 * Theme stuff.
 *
 * Automatically load PHP files from the theme.
 */
nice_loader( 'includes/' );
nice_loader( 'includes/custom-post-types/' );

/**
 * Framework classes.
 *
 * Automatically load needed framework classes.
 *
 * The advanced loader only needs to register the folders where classes are,
 * since single class files are not loaded right away, but when needed.
 */
if ( 'advanced' === nice_loader_method() ) {
	nice_loader( $nice_framework_path . 'admin/classes/' );
} else {
	nice_loader( $nice_framework_path . 'admin/classes/class-admin-page.php' );
	nice_loader( $nice_framework_path . 'admin/classes/class-admin-page-template-handler.php' );
	nice_loader( $nice_framework_path . 'admin/classes/class-admin-system-status.php' );
	nice_loader( $nice_framework_path . 'admin/classes/class-admin-system-status-report.php' );
	nice_loader( $nice_framework_path . 'admin/classes/class-theme-demo-pack.php' );
}

/**
 * Framework stuff.
 *
 * Automatically load PHP files from the framework.
 */
nice_loader( $nice_framework_path . 'admin/' );
nice_loader( $nice_framework_path . 'theming/' );

/**
 * Libraries.
 *
 * Load third party libraries.
 */
if ( is_admin() ) {
	nice_loader( $nice_framework_path . 'admin/lib/tgmpa/' );
}
