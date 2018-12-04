<?php
/**
 * NiceThemes PHP File Manager
 *
 * @package NiceFramework
 * @since   2.0.5
 */
namespace NiceThemes\PHP_File_Manager;

/**
 * Class NiceThemes\PHP_File_Manager\Advanced_Loader
 *
 * Manage loading of PHP files by path. Supports recursive and non-recursive
 * file loading, class autoloading and namespaces. Class names and files need
 * to comply with our custom naming strategy to be recognized by the autoloader.
 *
 * Naming Strategy for Classes and Interfaces:
 *
 * Class name (not namespaced): Nice_{Do_Something}
 *  Supported file names:
 *      - class-nice-{do-something}.php
 *      - class-{do-something}.php
 *
 * Class name (namespaced): NiceThemes\{Subnamespace}\{...}\{Do_Something}
 *  Supported file names:
 *      - class-nice-{subnamespace}-{...}-{do-something}.php
 *      - class-{subnamespace}-{...}-{do-something}.php
 *
 * Interface name (not namespaced): {Do_Something}_Interface
 *  Supported file names:
 *      - interface-nice-{do-something}.php
 *      - interface-{do-something}.php
 *
 * Interface name (namespaced): NiceThemes\{Subnamespace}\{...}\{Do_Something}
 *  Supported file names:
 *      - interface-nice-{subnamespace}-{...}-{do-something}.php
 *      - interface-{subnamespace}-{...}-{do-something}.php
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   2.0.5
 */
class Advanced_Loader extends Loader {
	/**
	 * List of files found inside the specified paths.
	 *
	 * @var array
	 */
	private $library = array();

	/**
	 * List of files excluded to be loaded by the current instance.
	 *
	 * @var array
	 */
	private $excluded_files = array();

	/**
	 * List of files waiting to be loaded by the current instance.
	 *
	 * @var array
	 */
	private $enqueued_files = array();

	/**
	 * List of registered classes.
	 *
	 * Format: array( 'Class_Name', 'path/to/class-name.php' ).
	 *
	 * @var array
	 */
	private $registered_classes = array();

	/**
	 * List of files containing classes that will be loaded only when needed.
	 *
	 * @var array
	 */
	private $skipped_class_files = array();

	/**
	 * List of already loaded files containing classes.
	 *
	 * @var array
	 */
	private $loaded_class_files = array();

	/**
	 * Files attempted to be loaded twice or more (TOM) times. Try to keep this
	 * array's count as low as possible.
	 *
	 * @var array
	 */
	private $attempted_tom = array();

	/**
	 * Load library and store it as a list of files.
	 *
	 * @param bool $debug Setup debugging mode.
	 */
	public function __construct( $debug = false ) {
		try {
			parent::__construct( $debug );

			/**
			 * Avoid including class files explicitly.
			 *
			 * @since 1.0
			 */
			$this->autoload_register();

		} catch ( \Exception $e ) {
			/**
			 * Catch errors and display them.
			 *
			 * @since 1.1
			 */
			wp_die( esc_html( $e->getMessage() ) );
		}
	}

	/**
	 * Add a path to the current instance.
	 *
	 * @param string $path             Absolute path to a folder or PHP file.
	 * @param array  $exclude          List of paths and files to exclude from loading.
	 * @param bool   $recursive        Whether to load files within $path recursively or not.
	 */
	protected function add_path( $path, array $exclude = array(), $recursive = true ) {
		$this->paths[ md5( $path ) ] = array(
			'path'      => $path,
			'exclude'   => $exclude,
			'recursive' => $recursive,
		);
	}

	/**
	 * Obtain all data from a path.
	 *
	 * @param  string $path
	 *
	 * @return array|null
	 */
	protected function get_path_data( $path ) {
		if ( ! isset( $this->paths[ md5( $path ) ] ) ) {
			return null;
		}

		return $this->paths[ md5( $path ) ];
	}

	/**
	 * Obtain the last path to be added to the current object.
	 *
	 * @return array
	 */
	protected function get_current_path() {
		if ( empty( $this->paths ) ) {
			return array();
		}

		$path = (array) end( $this->paths );

		reset( $this->paths );

		return $path;
	}

	/**
	 * Register a class by name and path to file. This helps speeding the
	 * class autoload process. The file should be registered in $this->library
	 * to be correctly loaded.
	 *
	 * @see   Advanced_Loader::autoload()
	 *
	 * @param string $class_name
	 * @param string $path
	 */
	public function register_class( $class_name, $path ) {
		$this->registered_classes[ $class_name ] = $path;
	}

	/**
	 * Load files inside the given path using additional directives.
	 *
	 * @param array $args {
	 *     Array of arguments to load files.
	 *
	 *     @type string $path      Full path to load files from.
	 *     @type array  $exclude   List of files and folders to exclude.
	 *     @type bool   $recursive Whether to load files recursively or not.
	 * }
	 */
	public function load( array $args = array() ) {
		$this->add_path( $args['path'], $args['exclude'], $args['recursive'] );
		$this->add_to_library( $args['path'] );
		$this->load_file_queue();
		$this->reset_file_queue();
	}

	/**
	 * Add a list of files to the loading queue.
	 *
	 * @param array $files List of files to enqueue.
	 */
	protected function enqueue_files( array $files ) {
		$this->enqueued_files = array_merge( $this->enqueued_files, $files );
	}

	/**
	 * Load files inside the current queue.
	 */
	protected function load_file_queue() {
		$current_path = $this->get_current_path();
		$this->load_files( $this->enqueued_files, $current_path['exclude'], ( count( $this->enqueued_files ) > 1 ) );
	}

	/**
	 * Reset current file queue.
	 */
	protected function reset_file_queue() {
		$this->enqueued_files = array();
	}

	/**
	 * Load a list of files.
	 *
	 * @param array $files        List of files to be loaded.
	 * @param array $exclude      List of paths to be excluded.
	 * @param bool  $skip_classes Allow skipping files that contain classes.
	 */
	protected function load_files( array $files, array $exclude = array(), $skip_classes = true ) {
		foreach ( $files as $file ) {
			// Check if file was already loaded.
			if ( in_array( $file, $this->loaded_files, true ) ) {
				$this->attempted_tom[] = $file;
				continue;
			}

			// Check if the file is inside the given list of exclusions.
			if ( in_array( $file, $exclude, true ) || in_array( basename( $file ), $exclude, true ) ) {
				$this->excluded_files[ md5( basename( $file ) ) ] = $file;
				continue;
			}

			// Check if the file contains a class and it needs to be skipped.
			if ( $skip_classes && ( self::is_class( $file ) || self::is_interface( $file ) ) ) {
				$this->skipped_class_files[ md5( basename( $file ) ) ] = $file;
				continue;
			}

			ob_start(); // Buffer output to avoid content to be printed.

			require $file;

			$this->loaded_files[] = $file; // Add to the list of loaded files.

			// If the file contains a class, add to the list of loaded classes.
			if ( self::is_class( $file ) || self::is_interface( $file ) ) {
				$this->loaded_class_files[] = $file;
			}

			ob_end_clean(); // Clean buffer.
		}
	}

	/**
	 * Load a list of files containing classes.
	 *
	 * @param array $files
	 */
	protected function load_class_files( array $files ) {
		$this->load_files( $files, array(), false );
	}

	/**
	 * Check if a file contains a class.
	 *
	 * @param  string $file Absolute path to PHP file.
	 *
	 * @return bool
	 */
	private static function is_class( $file = '' ) {
		return ( false !== stripos( $file, '/class-' ) || false !== stripos( $file, '/class-' ) || 0 === stripos( $file, 'class-' ) );
	}

	/**
	 * Check if a file contains an interface.
	 *
	 * @param  string $file Absolute path to PHP file.
	 *
	 * @return bool
	 */
	private static function is_interface( $file = '' ) {
		return ( false !== stripos( $file, '/interface-' ) || false !== stripos( $file, '/interface-' ) || 0 === stripos( $file, 'interface-' ) );
	}

	/**
	 * Add files from a path to the current library and enqueue them. The path
	 * should be already registered in $this->paths.
	 *
	 * @see   Loader::$paths
	 * @see   Loader::add_path()
	 *
	 * @uses  Loader::get_path_data()
	 * @uses  Loader::get_library()
	 *
	 * @param string $path
	 */
	protected function add_to_library( $path ) {
		$new_library = self::get_library( $this->get_path_data( $path ) );

		if ( empty( $new_library ) ) {
			return;
		}

		$this->library        = array_unique( array_merge( $this->library, $new_library ) );
		$this->enqueued_files = $new_library;
	}

	/**
	 * Obtain a list of files given a file or a folder.
	 *
	 * @param  array $path List containing files or folders.
	 *
	 * @return array       List of files.
	 */
	protected static function get_library( array $path ) {
		/**
		 * Add the contents of a directory.
		 */
		if ( is_dir( $path['path'] ) ) {
			return $path['recursive'] ? self::get_library_recursive( $path ) : self::get_library_non_recursive( $path );
		}

		/**
		 * Add a single PHP file.
		 */
		if ( is_file( $path['path'] ) && self::is_php( $path['path'] ) ) {
			return array( $path['path'] );
		}

		return array();
	}

	/**
	 * Obtain a list of PHP files within a path recursively.
	 *
	 * @note   This method doesn't check if the given path is a directory, so
	 *         you need to make sure of this before using it.
	 *
	 * @param  array $path Absolute path containing PHP files.
	 *
	 * @return array       List of PHP files inside the given path.
	 */
	private static function get_library_recursive( array $path ) {
		$library = array();

		/**
		 * Iterate path and obtain PHP files recursively.
		 */
		$directory_iterator = new \RecursiveDirectoryIterator( $path['path'], \RecursiveDirectoryIterator::SKIP_DOTS );
		$recursive_iterator = new \RecursiveIteratorIterator( $directory_iterator );

		foreach ( $recursive_iterator as $filename => $file ) {
			if ( ! self::is_php( $filename ) ) {
				continue;
			}

			$library[] = $filename;
		}

		return $library;
	}

	/**
	 * Obtain a list of PHP files within a path non-recursively.
	 *
	 * @note   This method doesn't check if the given path is a directory, so
	 *         you need to make sure of this before using it.
	 *
	 * @param  array $path Absolute path containing PHP files.
	 *
	 * @return array       List of PHP files inside the given path.
	 */
	private static function get_library_non_recursive( array $path ) {
		$library = array();

		$files = (array) scandir( $path['path'] );

		if ( false === $files ) {
			return array();
		}

		foreach ( $files as $file ) {
			if ( ! self::is_php( $file ) ) {
				continue;
			}

			$library[] = $path['path'] . $file;
		}

		return $library;
	}

	/**
	 * Register autoload functionality.
	 *
	 * @uses  spl_autoload_register()
	 */
	protected function autoload_register() {
		/**
		 * Allow deactivating autoload register.
		 *
		 * Keep in mind that setting this filter to false could break things.
		 */
		if ( ! apply_filters( __CLASS__ . '\\use_autoload_register', true ) ) {
			return;
		}

		spl_autoload_register( apply_filters( __CLASS__ . '\\autoload_callback', array( $this, 'autoload' ) ) );
	}

	/**
	 * Load a PHP file given a fully-qualified class name.
	 *
	 * @since 1.0
	 *
	 * @uses  Loader::__construct()
	 *
	 * @param string $class_name Fully-qualified name of class to be loaded.
	 */
	protected function autoload( $class_name ) {
		$load       = array();
		$class_file = $this->get_class_file( $class_name ) ? : $this->get_class_file_maybe( $class_name );

		if ( $class_file ) {
			$load[] = $class_file;
		}

		if ( empty( $load ) ) {
			return;
		}

		$this->load_class_files( $load );
	}

	/**
	 * Obtain the full path to a file containing a class.
	 *
	 * @param  string $class_name
	 *
	 * @return string|null
	 */
	protected function get_class_file( $class_name ) {
		if ( empty( $this->registered_classes[ $class_name ] ) ) {
			return null;
		}

		return $this->registered_classes[ $class_name ];
	}

	/**
	 * Attempt to obtain the name of a file containing a class.
	 *
	 * @param  string $class_name
	 *
	 * @return mixed|string|null
	 */
	protected function get_class_file_maybe( $class_name ) {
		$file = null;

		foreach ( $this->get_class_file_names( $class_name ) as $file_name ) {
			if ( in_array( $file_name, $this->skipped_class_files, true ) ) {
				$load[] = $file_name;
				break;
			}

			$key = md5( $file_name );

			if ( ! isset( $this->skipped_class_files[ $key ] ) ) {
				continue;
			}

			$file = $this->skipped_class_files[ $key ];
			break;
		}

		return $file;
	}

	/**
	 * Obtain probable file names for a class.
	 *
	 * @param  string $class_name
	 *
	 * @return array
	 */
	protected static function get_class_file_names( $class_name ) {
		// Return early if the class doesn't have our signature.
		if ( ! self::class_has_signature( $class_name ) ) {
			return array();
		}

		if ( self::has_namespace( $class_name ) ) {
			return self::get_namespaced_class_file_names( $class_name );
		}

		return self::get_global_class_file_names( $class_name );
	}

	/**
	 * Get probable file names for a namespaced class.
	 *
	 * @param  string $class_name
	 *
	 * @return array
	 */
	protected static function get_namespaced_class_file_names( $class_name ) {
		$is_interface = stripos( strtolower( $class_name ), 'interface' ) !== false;

		// Segment class by namespace separators.
		$segments = explode( '\\', $class_name );

		// Get rid of the first segment.
		unset( $segments[0] );

		// Re-format name of segments with our custom naming strategy.
		foreach ( $segments as $key => $value ) {
			$segments[ $key ] = preg_replace( '/\B([A-Z])/', '_$1', $value );
			$segments[ $key ] = strtolower( preg_replace( '/([a-z])([A-Z])/', '$1-$2', $segments[ $key ] ) );
		}

		$base_name = join( '-', $segments );
		$base_name = str_replace( '_', '-', $base_name );
		$base_name = str_replace( 'interface', '', $base_name );
		$base_name = trim( $base_name, '-' );

		$base_preffix = $is_interface ? 'interface' : 'class';

		$file_names = array(
			$base_preffix . '-' . $base_name . '.php',
			$base_preffix . '-nice-' . $base_name . '.php',
		);

		return $file_names;
	}

	/**
	 * Get probable names for a non-namespaced class.
	 *
	 * @param  string $class_name
	 *
	 * @return array
	 */
	protected static function get_global_class_file_names( $class_name ) {
		$file_names   = array();
		$is_interface = stripos( strtolower( $class_name ), 'interface' ) !== false;
		$base_preffix = $is_interface ? 'interface' : 'class';

		$base_name = preg_replace( '/\B([A-Z])/', '_$1', $class_name );
		$base_name    = str_replace( '__', '_', $base_name );
		$base_name    = str_replace( '_', '-', strtolower( $base_name ) );

		if ( $is_interface ) {
			$base_name = str_replace( 'interface', '', $base_name );
		}

		$base_name = trim( $base_name, '-' );

		$file_names = array_merge( $file_names, array(
			$base_preffix . '-' . str_replace( 'nice-', '', $base_name ) . '.php',
			$base_preffix . '-' . $base_name . '.php',
		) );

		return $file_names;
	}

	/**
	 * Check if a given class name contains the signature of our own classes.
	 *
	 * @param  string $class_name
	 *
	 * @return bool
	 */
	protected static function class_has_signature( $class_name ) {
		return ( 0 === strpos( $class_name, 'Nice_' ) ) || ( 0 === strpos( $class_name, 'NiceThemes\\' ) );
	}

	/**
	 * Check if a class is inside a namespace.
	 *
	 * @param  string $class_name
	 *
	 * @return bool
	 */
	private static function has_namespace( $class_name ) {
		return ( false !== strpos( $class_name, '\\' ) );
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

	- <?php echo sprintf( esc_html__( '%s paths loaded.', 'nice-framework' ), count( $this->paths ) ); ?>

	- <?php printf( esc_html__( '%s files in library.', 'nice-framework' ), count( $this->library ) ); ?>

	- <?php printf( esc_html__( '%s loaded files.', 'nice-framework' ), count( $this->loaded_files ) ); ?>

	- <?php printf( esc_html__( '%s skipped class files.', 'nice-framework' ), count( $this->skipped_class_files ) ); ?>

	- <?php printf( esc_html__( '%s loaded class files.', 'nice-framework' ), count( $this->loaded_class_files ) ); ?>

	- <?php printf( esc_html__( '%s files attempted to be loaded twice or more times.', 'nice-framework' ), count( $this->attempted_tom ) ); ?>

--><?php
	}
}
