<?php
/**
 * NiceThemes PHP File Manager
 *
 * @package NiceFramework
 * @since   2.0.5
 */
namespace NiceThemes\PHP_File_Manager;

/**
 * Class NiceThemes\PHP_File_Manager\Filesystem_Loader
 *
 * Manage loading of PHP files by path, non-recursively.
 *
 * This class uses native the WordPress Filesystem API to get information from
 * files and directories. It's supposed to be more stable than the other
 * options, but it can decrease the website's performance.
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   2.0.5
 */
class Filesystem_Loader extends Loader {
	/**
	 * Reference to WP Filesystem.
	 *
	 * @var null|\WP_Filesystem_Base
	 */
	protected $filesystem = null;

	/**
	 * Filesystem_Loader constructor.
	 *
	 * @param bool $debug
	 */
	function __construct( $debug = false ) {
		parent::__construct( $debug );

		require_once ABSPATH . 'wp-admin/includes/file.php';

		/**
		 * Initialize WordPress' file system handler.
		 *
		 * @var \WP_Filesystem_Base $wp_filesystem
		 */
		WP_Filesystem( false, false, true );

		global $wp_filesystem;

		$this->filesystem = $wp_filesystem;
	}

	/**
	 * Load all files in a given path.
	 *
	 * @param array $args {
	 *     Array of arguments to load files.
	 *
	 *     @type string $path Full path to load files from.
	 * }
	 */
	public function load( array $args ) {
		$file_list = $this->filesystem->is_dir( $args['path'] ) ? $this->filesystem->dirlist( $args['path'], false, true ) : array();

		if ( false === $file_list ) {
			return;
		}

		$this->add_path( $args['path'] );

		foreach ( (array) $file_list as $file_name => $file_data ) {
			$this->load_php_file( $args['path'] . $file_name );
		}

		if ( ! $this->filesystem->is_file( $args['path'] ) ) {
			return;
		}

		$this->load_php_file( $args['path'] );
	}
}