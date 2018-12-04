<?php
/**
 * NiceFramework by NiceThemes.
 *
 * This file contains functionality that helps manage the current PHP
 * execution context.
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2017 NiceThemes
 * @since     2.0.6
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Load Execution Context functions file.
 *
 * Most functions in this file are wrappers for the PHP File Manager library.
 *
 * @see \NiceThemes\Execution_Context\
 */
require dirname( __FILE__ ) . '/functions.php';

/**
 * Begin using the component.
 *
 * Use an alias to access the Execution Context namespace when needed.
 */
use NiceThemes\Execution_Context as context;

if ( ! function_exists( 'nice_doing_ajax' ) ) :
/**
 * Check if we're in an AJAX context.
 *
 * @since  2.0
 *
 * @return bool
 */
function nice_doing_ajax() {
	/**
	 * @hook nice_doing_ajax
	 *
	 * Hook in here to modify our internal AJAX flag.
	 *
	 * @since 2.0.6
	 */
	return apply_filters( 'nice_doing_ajax', context\doing_ajax() );
}
endif;

if ( ! function_exists( 'nice_is_mixed_content' ) ) :
/**
 * Useful if some libraries need to be loaded in both admin and public sides.
 *
 * @since 2.0.9
 */
function nice_is_mixed_content() {
	/**
	 * @hook nice_is_mixed_content
	 *
	 * Hook in here to change default mixed content value.
	 */
	return apply_filters( 'nice_is_mixed_content', false );
}
endif;

if ( ! function_exists( 'nice_development_mode' ) ) :
/**
 * Check if development mode is active.
 *
 * @since 2.0
 */
function nice_development_mode() {
	/**
	 * @hook nice_development_mode
	 *
	 * Development mode is defined here. Hook in from theme files to set it
	 * from option values.
	 */
	return apply_filters( 'nice_development_mode', context\development_mode() );
}
endif;

if ( ! function_exists( 'nice_debug' ) ) :
/**
 * Check if we're in debug context.
 *
 * @since  2.0
 *
 * @return bool
 */
function nice_debug() {
	/**
	 * @hook nice_debug
	 *
	 * Hook in here to modify our internal debugging mode.
	 *
	 * @since 2.0.6
	 */
	return apply_filters( 'nice_debug', context\debug() );
}
endif;
