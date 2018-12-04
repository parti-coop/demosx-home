<?php
/**
 * NiceFramework by NiceThemes.
 *
 * This file contains functionality that helps manage PHP file paths and
 * automatic file loading.
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
 * Load PHP Manager functions file.
 *
 * Most functions in this file are wrappers for the PHP File Manager library.
 *
 * @see \NiceThemes\PHP_File_Manager\
 */
require dirname( __FILE__ ) . '/functions.php';

/**
 * Begin using the file manager.
 *
 * Use an alias to access the PHP File Manager namespace when needed.
 */
use NiceThemes\PHP_File_Manager as phpfm;

if ( ! function_exists( 'nice_loader' ) ) :
/**
 * Load a single PHP file, or all files inside a directory.
 *
 * $path can be either the full path or a relative path to the theme's folder.
 * For relative paths, the function will try to load files from the Child
 * Theme's directory first (if they exist), and then from the parent theme.
 *
 * @since   1.0.0
 * @updated 2.0
 * @updated 2.0.5
 *
 * @param   string $path      Path to single PHP file or directory containing
 *                            PHP files.
 * @param   bool   $recursive Whether to load the given path recursively or not.
 */
function nice_loader( $path, $recursive = false ) {
	static $template_path, $stylesheet_path, $method;

	if ( is_null( $template_path ) ) {
		$template_path = trailingslashit( get_template_directory() );
	}

	if ( is_null( $stylesheet_path ) ) {
		$stylesheet_path = trailingslashit( get_stylesheet_directory() );
	}

	if ( is_null( $method ) ) {
		$method = nice_loader_method();
	}

	/**
	 * Allow files to be recursively loaded from both parent and child themes
	 * when both template and stylesheet directories are not specified.
	 *
	 * @since 2.0
	 */
	if ( strpos( $path, $template_path ) === false && strpos( $path, $stylesheet_path ) === false ) {
		$theme_file_path        = nice_get_theme_file_path( $path, $stylesheet_path );
		$parent_theme_file_path = nice_get_parent_theme_file_path( $path, $template_path );

		// Try to load files from stylesheet directory (the child theme folder, when using a child theme).
		nice_loader( $theme_file_path );

		// Try to load files from template folder (only if using a child theme).
		if ( $theme_file_path !== $parent_theme_file_path ) {
			nice_loader( $parent_theme_file_path );
		}

		// Return early if the specified path doesn't exist as given (applies to relative paths).
		if ( ! file_exists( '/' . $path ) ) {
			return;
		}
	}

	// Finally load the file or directory.
	nice_load( array(
			'path'      => $path,
			'method'    => $method,
			'debug'     => defined( 'NICETHEMES_DEVELOPMENT_MODE' ) && NICETHEMES_DEVELOPMENT_MODE,
			'recursive' => $recursive,
		)
	);
}
endif;

/**
 * Obtain the preferred loading method.
 *
 * @since 2.0.5
 *
 * @return string
 */
function nice_loader_method() {
	static $method = null;

	if ( is_null( $method ) ) {
		/**
		 * @hook nice_loader_method
		 *
		 * Hook in here to modify the default loader method.
		 *
		 * @since 2.0.5
		 */
		$method = apply_filters( 'nice_loader_method', defined( 'NICE_LOADER_METHOD' ) ? NICE_LOADER_METHOD : 'standard' );
	}

	return $method;
}

/**
 * Load a single PHP file or directory.
 *
 * This function is a wrapper for NiceThemes\PHP_File_Manager|load().
 *
 * @uses   load()
 *
 * @since  2.0.5
 *
 * @param array $args See wrapped function for complete list of arguments.
 */
function nice_load( array $args ) {
	phpfm\load( $args );
}

/**
 * Register classes that need to be autoloaded to speed up the process.
 *
 * This function only works for the "advanced" loader method.
 *
 * @since 2.0.5
 *
 * @param array $classes List of classes to be autoloaded.
 *
 * Usage:
 *
 * nice_autoload_classes( array(
	'Some_Class'       => 'relative/path/to/some-class.php',
	'Some_Other Class' => 'relative/path/to/some-other-class.php',
 * ) );
 */
function nice_autoload_classes( array $classes ) {
	if ( 'advanced' !== nice_loader_method() ) {
		return;
	}

	foreach ( $classes as $class_name => $path ) {
		nice_autoload_class( $class_name, $path );
	}
}

/**
 * Register a class that needs to be autoloaded to speed up the process.
 *
 * @since 2.0.5
 *
 * @param string $class_name
 * @param string $path
 *
 * Usage:
 *
 * nice_autoload_classes( 'Some_Class', 'relative/path/to/some-class.php' );
 */
function nice_autoload_class( $class_name, $path ) {
	if ( 'advanced' !== nice_loader_method() ) {
		return;
	}

	static $template_path, $stylesheet_path;

	if ( is_null( $template_path ) ) {
		$template_path   = trailingslashit( get_template_directory() );
	}
	if ( is_null( $stylesheet_path ) ) {
		$stylesheet_path = trailingslashit( get_stylesheet_directory() );
	}

	/**
	 * Allow files to be recursively loaded from both parent and child themes
	 * when both template and stylesheet directories are not specified.
	 *
	 * @since 2.0
	 */
	if ( strpos( $path, $template_path ) === false && strpos( $path, $stylesheet_path ) === false ) {
		$theme_file_path        = nice_get_theme_file_path( $path, $stylesheet_path );
		$parent_theme_file_path = nice_get_parent_theme_file_path( $path, $template_path );

		// Try to register file from stylesheet directory (the child theme folder, when using a child theme).
		phpfm\register_class( $class_name, $theme_file_path );

		// Try to register file from template folder (only if using a child theme).
		if ( $theme_file_path !== $parent_theme_file_path ) {
			phpfm\register_class( $class_name, $parent_theme_file_path );
		}

		return;
	}

	if ( ! file_exists( $path ) ) {
		return;
	}

	phpfm\register_class( $class_name, $path );
}

/**
 * Obtain the path to a file inside the current theme or child theme.
 *
 * @since 2.0.5
 *
 * @param  string $path            Relative path to a file inside the theme.
 * @param  string $stylesheet_path The current stylesheet path. Optional.
 *
 * @return string
 */
function nice_get_theme_file_path( $path, $stylesheet_path = null ) {
	static $use_fallback;

	if ( is_null( $use_fallback ) ) {
		$use_fallback = ! function_exists( 'get_theme_file_path' );
	}

	if ( $use_fallback ) {
		if ( ! $stylesheet_path ) {
			$stylesheet_path = get_stylesheet_directory();
		}

		return trailingslashit( $stylesheet_path ) . $path;
	}

	return get_theme_file_path( $path );
}

/**
 * Obtain the path to a file inside the current parent theme.
 *
 * @since 2.0.5
 *
 * @param  string $path          Relative path to a file inside the theme.
 * @param  string $template_path The current template path. Optional.
 *
 * @return string
 */
function nice_get_parent_theme_file_path( $path, $template_path = null ) {
	static $use_fallback;

	if ( is_null( $use_fallback ) ) {
		$use_fallback = ! function_exists( 'get_theme_file_path' );
	}

	if ( $use_fallback ) {
		if ( ! $template_path ) {
			$template_path = get_template_directory();
		}

		return trailingslashit( $template_path ) . $path;
	}

	return get_parent_theme_file_path( $path );
}

/**
 * Get relative path from the theme's root folder to the directory where a
 * given file is located.
 *
 * @since  2.0.5
 *
 * @see    plugin_dir_path()
 *
 * @param  string $file Full path to a file.
 *
 * @return string
 */
function nice_dir_path( $file ) {
	static $stylesheet_path, $template_path;

	if ( is_null( $stylesheet_path ) ) {
		$stylesheet_path = get_stylesheet_directory();
	}

	if ( is_null( $template_path ) ) {
		$template_path = get_template_directory();
	}

	$dirname = dirname( $file );

	// Catch files from symlinked framework and theme directories.
	if ( false !== strpos( $dirname, NICE_FRAMEWORK_REALPATH ) ) {
		$dirname = str_replace( NICE_FRAMEWORK_REALPATH, NICE_FRAMEWORK_ABSPATH, $dirname );
	} elseif ( false !== strpos( $dirname, NICE_THEME_REALPATH ) ) {
		$dirname = str_replace( NICE_THEME_REALPATH, NICE_THEME_ABSPATH, $dirname );
	}

	if ( false !== strpos( $dirname, NICE_THEME_ABSPATH ) ) {
		$dirname = str_replace( NICE_THEME_ABSPATH, '', $dirname );
	}

	$stylesheet_realpath = realpath( $stylesheet_path );

	// Catch files from symlinked Child Themes.
	if ( ( $stylesheet_realpath !== $stylesheet_path ) && false !== strpos( $dirname, $stylesheet_realpath ) ) {
		$dirname = str_replace( $stylesheet_realpath, '', $dirname );
	}

	$dirname = str_replace( $stylesheet_path, '', $dirname );
	$dirname = str_replace( $template_path, '', $dirname );

	return trailingslashit( trim( $dirname, '/' ) );
}
