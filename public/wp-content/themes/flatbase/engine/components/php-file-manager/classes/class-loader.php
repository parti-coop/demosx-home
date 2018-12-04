<?php
/**
 * NiceThemes PHP File Manager
 *
 * @package NiceFramework
 * @since   2.0.5
 */
namespace NiceThemes\PHP_File_Manager;

/**
 * Class NiceThemes\PHP_File_Manager\Loader
 *
 * Manage loading of PHP files by path, non-recursively.
 *
 * This class uses native PHP functions to get information from files and
 * directories.
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   2.0.5
 */
class Loader {
	/**
	 * Absolute paths to libraries that need to be loaded.
	 *
	 * Each path can either be a directory or a single file.
	 *
	 * @var array
	 */
	protected $paths = array();

	/**
	 * List of single files already loaded by the current instance.
	 *
	 * @var array
	 */
	protected $loaded_files = array();

	/**
	 * Set debugging mode.
	 *
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * Loader constructor.
	 *
	 * @param bool $debug Setup debugging mode.
	 */
	public function __construct( $debug = false ) {
		$this->debug   = $debug;
		$report_action = array( $this, 'report' );

		// Make sure the report doesn't get printed during AJAX calls.
		add_action( 'wp_footer', function() use ( $report_action ) {
			add_action( 'shutdown', $report_action );
		} );
	}

	/**
	 * Add a new path to the list.
	 *
	 * @param string $path
	 */
	protected function add_path( $path ) {
		$this->paths[] = $path;
		$this->paths = array_unique( $this->paths );
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
		$dir = is_dir( $args['path'] ) ? scandir( $args['path'] ) : array();

		if ( false === $dir ) {
			return;
		}

		$this->add_path( $args['path'] );

		foreach ( (array) $dir as $file_name ) {
			$this->load_php_file( $args['path'] . $file_name );
		}

		if ( ! is_file( $args['path'] ) ) {
			return;
		}

		$this->load_php_file( $args['path'] );
	}

	/**
	 * Load single PHP file.
	 *
	 * @param string $file Full path to a PHP file.
	 */
	protected function load_php_file( $file ) {
		if ( ! self::is_php( $file ) || in_array( $file, $this->loaded_files, true ) ) {
			return;
		}

		require $file;

		$this->loaded_files[] = $file;
		$this->loaded_files = array_unique( $this->loaded_files );
	}

	/**
	 * Check if a file has the .php extension.
	 *
	 * @param  string $file Absolute path to file or just the file name.
	 *
	 * @return bool
	 */
	protected static function is_php( $file ) {
		return ( 'php' === pathinfo( $file, PATHINFO_EXTENSION ) );
	}

	/**
	 * Print out a status report of the current instance.
	 */
	public function report() {
		if ( ! $this->debug || nice_doing_ajax() ) {
			return;
		}
	?>
<!--
	<?php esc_html_e( 'NiceThemes Loader Data:', 'nice-framework' ); ?>

	- <?php printf( esc_html__( '%s paths loaded.', 'nice-framework' ), count( $this->paths ) ); ?>

	- <?php printf( esc_html__( '%s loaded files.', 'nice-framework' ), count( $this->loaded_files ) ); ?>

--><?php
	}
}
