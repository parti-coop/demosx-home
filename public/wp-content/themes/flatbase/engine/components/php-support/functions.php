<?php
/**
 * NiceFramework by NiceThemes.
 *
 * This file provides functions for easier interaction with the
 * Nice_PHP_Support class.
 *
 * @see Nice_PHP_Support
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2017 NiceThemes
 * @since     2.0.8
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Obtain an instance of Nice_PHP_Support
 *
 * @since 2.0.8
 *
 * @return Nice_PHP_Support
 */
function nice_php_support_instance() {
	static $instance = null;

	if ( is_null( $instance ) ) {
		$instance = new Nice_PHP_Support;
	}

	return $instance;
}

/**
 * Check if theme activation should be rolled back.
 *
 * @since 2.0.8
 *
 * @return bool
 */
function nice_php_support_rollback_activation() {
	$instance = nice_php_support_instance();

	return $instance->rollback_activation();
}
