<?php
/**
 * Flatbase by NiceThemes.
 *
 * Upcoming framework functions.
 * This file mission is to create compatibility between versions.
 *
 * @see nice_load_theming()
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

if ( ! function_exists( 'nice_get_file_uri' ) ) :
/**
 * Obtain the URI of a file given its relative location to the theme root folder.
 *
 * @since  2.0
 *
 * @param  string $file   Path of file relative to theme root folder.
 * @param  bool   $minify Whether to obtain a minified version of the file or not.
 *
 * @return string
 */
function nice_get_file_uri( $file, $minify = false ) {
	$file_info = nice_get_file_info( $file );

	$file_uri = empty( $file_info['uri'] ) ? null : $file_info['uri'];
	$file_uri = $minify ? nice_minified_script_maybe_uri( $file_uri ) : $file_uri;

	return $file_uri;
}
endif;

if ( ! function_exists( 'nice_get_file_info' ) ) :
/**
 * Obtain the absolute path to a file given its relative location to the theme root folder.
 *
 * This function checks if the file exists in the Child Theme by default.
 * If not, it checks inside the parent theme.
 *
 * @since  2.0
 *
 * @param  string $file Path of file relative to theme root folder.
 *
 * @return string
 */
function nice_get_file_info( $file ) {
	static $file_info = array();

	if ( ! empty( $file_info[ $file ] ) ) {
		return $file_info[ $file ];
	}

	$stylesheet_directory = get_stylesheet_directory();
	$template_directory   = get_template_directory();

	/**
	 * If we're using a child theme, search the file there first.
	 */
	if ( $stylesheet_directory !== $template_directory ) {
		$file_exists_in_child_theme = file_exists( $stylesheet_directory . '/' . $file );

		// If the file exists in the child theme, override the base URI.
		if ( $file_exists_in_child_theme ) {
			$base_path = $stylesheet_directory;
			$base_uri  = get_stylesheet_directory_uri();
		}
	}

	// Obtain base path, if not set yet.
	if ( empty( $base_path ) ) {
		$base_path = $template_directory;
	}

	// Obtain base URI, if not set yet.
	if ( empty( $base_uri ) ) {
		$base_uri = get_template_directory_uri();
	}

	$base_path = apply_filters( 'nice_base_path', $base_path, $file );
	$base_uri  = apply_filters( 'nice_base_uri', $base_uri, $file );

	$file_info[ $file ] = array(
		'base_path' => $base_path,
		'base_uri'  => $base_uri,
		'path'      => $base_path . '/' . $file,
		'uri'       => $base_uri . '/' . $file,
	);

	return $file_info[ $file ];
}
endif;

if ( ! function_exists( 'nice_minified_script_maybe_uri' ) ) :
/**
 * Obtain the URL of a (maybe) minified file.
 *
 * The criteria for the usage of minified files is the value of the `nice_use_minified_files`
 * filter. The default value for this filter is the opposite to that of the `WP_DEBUG` constant.
 * This means that, if `WP_DEBUG` is set to `true`, the non-minified files will be used instead of
 * the minified ones.
 *
 * Keep in mind, when using this function, that the file being used as parameter must exist inside
 * a folder called `js/`, which must contain another folder called `min/`. The file needs to have
 * the `.js` extension, and its minified counterpart (inside `js/min/`) needs to have `.min.js` as
 * extension. The function doesn't check for the existence of any files.
 *
 * @since 2.0
 *
 * @param  string $file_url URL of the file to be evaluated.
 * @return string
 */
function nice_minified_script_maybe_uri( $file_url ) {
	$minify_script = apply_filters( 'nice_minify_script', nice_use_minified_files(), $file_url );

	if ( $minify_script ) {
		// Modify file folder.
		$file_url = str_replace( '/js/', '/js/min/', $file_url );
		// Modify file extension.
		$file_url = str_replace( '.js', '.min.js', $file_url );
	}

	return $file_url;
}
endif;

if ( ! function_exists( 'nice_use_minified_files' ) ) :
/**
 * Check whether minified assets should be used or not.
 *
 * @since 2.0
 */
function nice_use_minified_files() {
	static $use_minified_files = null;

	if ( is_null( $use_minified_files ) ) {
		$default_value      = ! ( defined( 'WP_DEBUG' ) && WP_DEBUG );
		$use_minified_files = apply_filters( 'nice_use_minified_files', $default_value );
	}

	return $use_minified_files;
}
endif;

