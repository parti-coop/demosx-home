<?php
/**
 * NiceThemes PHP File Manager.
 *
 * General functions for PHP file loading.
 *
 * @package NiceFramework
 * @since   2.0.5
 */
namespace NiceThemes\PHP_File_Manager;

/**
 * Obtain the current loader instance.
 *
 * @since  2.0.5
 *
 * @param array $args {
 *     Array of arguments to obtain the loader instance.
 *
 *     @type string $method    Preferred method for file loading. Accepted methods are "standard", "filesystem" and "advanced".
 *     @type bool   $debug     Whether to enable debug mode or not.
 *     @type bool   $reload    Whether to reload the current loader instance or not.
 * }
 *
 * @return Loader
 */
function obtain_loader_instance( array $args = null ) {
	static $loader = null;

	if ( is_null( $loader ) || ! empty( $args['reload'] ) ) {
		require_once dirname( __FILE__ ) . '/classes/class-loader.php';

		switch ( $args['method'] ) {
			case 'standard' :
				$loader = new Loader( (bool) $args['debug'] );
				break;
			case 'filesystem' :
				require_once dirname( __FILE__ ) . '/classes/class-filesystem-loader.php';
				$loader = new Filesystem_Loader( (bool) $args['debug'] );
				break;
			case 'advanced' :
				require_once dirname( __FILE__ ) . '/classes/class-advanced-loader.php';
				$loader = new Advanced_Loader( (bool) $args['debug'] );
				break;
			default :
				wp_die( esc_html__( 'Please specify a valid file loader.', 'nice-framework' ) );
				break;
		}
	}

	return $loader;
}

/**
 * Load a single PHP file or directory.
 *
 * @uses   Loader::__construct()
 *
 * @since  2.0.5
 *
 * @param array $args {
 *     Array of arguments to load files.
 *
 *     @type string $path      Full path to load files from.
 *     @type array  $exclude   List of files and folders to exclude.
 *     @type bool   $recursive Whether to load files recursively or not.
 *     @type string $method    Preferred method for file loading. Accepted methods are "default", "filesystem" and "advanced".
 *     @type bool   $debug     Whether to enable debug mode or not.
 *     @type bool   $reload    Whether to reload the current loader instance or not.
 * }
 */
function load( array $args ) {
	$args = wp_parse_args( $args, array(
			'path'      => '',
			'exclude'   => array(),
			'recursive' => true,
			'method'    => 'default',
			'debug'     => false,
			'reload'    => false,
		)
	);

	$load_args = array(
		'path'      => $args['path'],
		'exclude'   => $args['exclude'],
		'recursive' => (bool) $args['recursive'],
	);

	obtain_loader_instance( $args )->load( $load_args );
}

/**
 * Load a list of directories recursively.
 *
 * @uses   load()
 *
 * @since  2.0.5
 *
 * @param array $libraries List of libraries containing arguments.
 */
function load_libraries( array $libraries ) {
	foreach ( $libraries as $args ) {
		load( $args );
	}
}

/**
 * Register a class to be autoloaded when needed.
 *
 * This function only works when the preferred loading method is "advanced".
 *
 * @since 2.0.5
 *
 * @param string $class_name Name of class to be autoloaded.
 * @param string $path       Full path to file containing class.
 */
function register_class( $class_name, $path ) {
	$loader = obtain_loader_instance();

	if ( ! $loader instanceof Advanced_Loader ) {
		return;
	}

	$loader->register_class( $class_name, $path );
}
