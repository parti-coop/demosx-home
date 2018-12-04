<?php
/**
 * NiceFramework by NiceThemes.
 *
 * This file contains constants related to the base configuration of the
 * framework. All constants included here can be declared in an earlier point
 * of execution, such as the parent or Child Theme initialization.
 *
 * @package Nice_Framework
 * @since   2.0.6
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'NICE_FRAMEWORK_VERSION' ) ) {
	/**
	 * Define current framework version.
	 *
	 * @since 1.0.0
	 */
	define( 'NICE_FRAMEWORK_VERSION', '2.0.9.1' );
}

if ( ! defined( 'NICE_PREFIX' ) ) {
	/**
	 * Define main framework prefix.
	 *
	 * @since 1.0.0
	 */
	define( 'NICE_PREFIX', 'nice' );
}

if ( ! defined( 'NICE_THEME_ABSPATH' ) ) {
	/**
	 * Define absolute path to theme internally.
	 *
	 * @since 2.0.6
	 */
	define( 'NICE_THEME_ABSPATH', get_template_directory() );
}

if ( ! defined( 'NICE_FRAMEWORK_ABSPATH' ) ) {
	/**
	 * Define absolute path to framework internally.
	 *
	 * @since 2.0.6
	 */
	define( 'NICE_FRAMEWORK_ABSPATH', NICE_THEME_ABSPATH . '/' . basename( __DIR__ ) );
}

if ( ! defined( 'NICE_THEME_REALPATH' ) ) {
	/**
	 * Define theme realpath to help handling symlinked directories.
	 *
	 * @since 2.0.6
	 */
	define( 'NICE_THEME_REALPATH', realpath( NICE_THEME_ABSPATH ) );
}

if ( ! defined( 'NICE_FRAMEWORK_REALPATH' ) ) {
	/**
	 * Define framework realpath to help handling symlinked directories.
	 *
	 * @since 2.0.6
	 */
	define( 'NICE_FRAMEWORK_REALPATH', realpath( NICE_FRAMEWORK_ABSPATH ) );
}

if ( ! defined( 'NICE_FRAMEWORK_URI' ) ) {
	/**
	 * Define framework URI.
	 *
	 * @since 2.0.6
	 */
	define( 'NICE_FRAMEWORK_URI', get_template_directory_uri() . '/' . basename( __DIR__ ) );
}

if ( ! defined( 'NICE_TPL_DIR' ) ) {
	/**
	 * Define template directory URI.
	 *
	 * @since 1.0.0
	 */
	define( 'NICE_TPL_DIR', get_template_directory_uri() );
}

if ( ! defined( 'NICE_TPL_PATH' ) ) {
	/**
	 * Define absolute path to template directory.
	 *
	 * @since 1.0.0
	 *
	 * @note To be deprecated. Use NICE_THEME_ABSPATH instead.
	 */
	define( 'NICE_TPL_PATH', NICE_THEME_ABSPATH );
}

if ( ! defined( 'NICE_LOADER_METHOD' ) ) {
	/**
	 * Define default loader method.
	 *
	 * @since 2.0.6
	 */
	define( 'NICE_LOADER_METHOD', 'standard' );
}

if ( ! defined( 'NICE_ADMIN_TEMPLATES_PATH' ) ) {
	/**
	 * Define absolute path for templates in the admin-facing side of the theme.
	 *
	 * @since 2.0
	 */
	define( 'NICE_ADMIN_TEMPLATES_PATH', NICE_FRAMEWORK_ABSPATH . '/admin/templates/' );
}

if ( ! defined( 'NICE_UPDATES_URL' ) ) {
	/**
	 * Set URL to check for automatic updates.
	 *
	 * @hook nice_remote_setting_updates_url
	 *
	 * Hook here if you want to use a different updates URL.
	 *
	 * Hook origin:
	 * @see nice_get_remote_setting()
	 *
	 * @since 2.0
	 */
	define( 'NICE_UPDATES_URL', nice_get_remote_setting( 'updates_url', 'http://updates.nicethemes.com' ) );
}

if ( ! defined( 'NICE_AWS_S3_ENDPOINT' ) ) {
	/**
	 * Set endpoint for AWS.
	 *
	 * @hook nice_remote_setting_aws_s3_endpoint
	 *
	 * Hook here if you want to use a different AWS S3 endpoint.
	 *
	 * Hook origin:
	 * @see nice_get_remote_setting()
	 *
	 * @since 2.0
	 */
	define( 'NICE_AWS_S3_ENDPOINT', nice_get_remote_setting( 'aws_s3_endpoint', 's3-us-west-2.amazonaws.com' ) );
}

if ( ! defined( 'NICE_AWS_S3_BUCKET_UPDATES' ) ) {
	/**
	 * Set bucket location to check and fetch plugin updates.
	 *
	 * @hook nice_remote_setting_aws_s3_bucket_updates
	 *
	 * Hook here if you want to use a different AWS S3 bucket for the updates.
	 *
	 * Hook origin:
	 * @see nice_get_remote_setting()
	 *
	 * @since 2.0
	 */
	define( 'NICE_AWS_S3_BUCKET_UPDATES', nice_get_remote_setting( 'aws_s3_bucket_updates', 'nicethemes-updates' ) );
}

if ( ! defined( 'NICE_AWS_S3_BUCKET_DEMOS' ) ) {
	/**
	 * Set bucket location to check and fetch demo sites.
	 *
	 * @hook nice_remote_setting_aws_s3_bucket_demos
	 *
	 * Hook here if you want to use a different AWS S3 bucket for the demo packs.
	 *
	 * Hook origin:
	 * @see nice_get_remote_setting()
	 *
	 * @since 2.0
	 */
	define( 'NICE_AWS_S3_BUCKET_DEMOS', nice_get_remote_setting( 'aws_s3_bucket_demos', 'nicethemes-demos' ) );
}
