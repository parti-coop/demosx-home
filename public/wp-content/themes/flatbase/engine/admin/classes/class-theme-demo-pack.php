<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Functions related to the Demo Installer.
 *
 * @package Nice_Framework
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! class_exists( 'Nice_Theme_Demo_Pack' ) ) :
/**
 * Class Nice_Theme_Demo_Pack
 *
 * This class handles the process of importing a demo pack.
 *
 * @since 2.0
 *
 * @property-read Nice_Admin_System_Status $system_status
 */
final class Nice_Theme_Demo_Pack {

	/** ==========================================================================
	 *  Properties.
	 *  ======================================================================= */

	/**
	 * Slug of the demo pack currently being imported.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $slug = null;

	/**
	 * Configuration of the demo pack (from the current theme).
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $config = null;

	/**
	 * Local path of all downloaded demo packs.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $uploads_path = null;

	/**
	 * Local URL of all downloaded demo packs.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $uploads_url = null;

	/**
	 * Local path of the demo pack file(s).
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $path = null;

	/**
	 * Local URL of the demo pack file(s).
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $url = null;

	/**
	 * External source URL of the demo pack file(s).
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $external_url = null;

	/**
	 * Minimum theme version required by the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $theme_version = null;

	/**
	 * Minimum framework version required by the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $framework_version = null;

	/**
	 * Plugins used by the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $plugins = null;

	/**
	 * Plugins used by the demo pack, currently active.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $active_plugins = null;

	/**
	 * Plugins used by the demo pack, currently installed.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $installed_plugins = null;

	/**
	 * Plugins used by the demo pack, currently outdated.
	 *
	 * These plugins doesn't count as installed (since they're useless unless updated).
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $outdated_plugins = null;

	/**
	 * Plugins used by the demo pack, currently missing.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $missing_plugins = null;

	/**
	 * System requirements to install the current demo pack.
	 *
	 * @since 2.0.4
	 *
	 * @var array
	 */
	private $system_requirements = array();

	/**
	 * System requirements that are not met, but the demo pack still may be
	 * installed correctly without them.
	 *
	 * @since 2.0.4
	 *
	 * @var array
	 */
	private $system_warnings = array();

	/**
	 * System requirements that are not met, and the demo pack installation
	 * will likely fail without them.
	 *
	 * @since 2.0.4
	 *
	 * @var array
	 */
	private $system_errors = array();

	/**
	 * Order that the demo pack will have in the installer.
	 *
	 * @since 2.0
	 *
	 * @var int
	 */
	private $menu_order = null;

	/**
	 * Name of the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $name = null;

	/**
	 * Description of the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $description = null;

	/**
	 * Tags of the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $tags = null;

	/**
	 * Colors (specialized tags) of the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $colors = null;

	/**
	 * Features (specialized tags) of the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $features = null;

	/**
	 * Images which illustrate the demo pack.
	 *
	 * The first one is used as featured.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $images = null;

	/**
	 * Featured image of the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $featured_image = null;

	/**
	 * Live preview URL of the demo pack.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $preview_url = null;

	/**
	 * Import status.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $import_status = null;

	/**
	 * Importing messages.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $importing_messages = null;

	/**
	 * Install status.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private $install_status = null;

	/**
	 * Date and time of the current attempt of the process.
	 *
	 * @since 2.0
	 *
	 * @var   string
	 */
	private $execution_time = '';

	/**
	 * Whether or not any change was made during the current process.
	 *
	 * @since 2.0
	 *
	 * @var bool
	 */
	private $did_something = false;

	/**
	 * Whether or not an error occurred during the current process.
	 *
	 * @since 2.0
	 *
	 * @var bool
	 */
	private $error_occurred = false;

	/**
	 * Response for AJAX requests.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private $ajax_response = array();

	/**
	 * System Status handler for system information.
	 *
	 * @since 2.0
	 *
	 * @var Nice_Admin_System_Status
	 */
	private $system_status = null;

	/**
	 * WordPress' file system handler.
	 *
	 * @since 2.0
	 *
	 * @var WP_Filesystem_Base
	 */
	private $wp_filesystem = null; // WPCS: override Ok.

	/**
	 * Total number of posts to import for this demo pack.
	 *
	 * @since 2.0.6
	 *
	 * @var int
	 */
	private $total_posts_number = null;

	/**
	 * Number of posts already processed for this demo pack.
	 *
	 * @since 2.0.6
	 *
	 * @var int
	 */
	private $processed_posts_number = null;

	/**
	 * Maximum number of posts to be processed in a single request.
	 *
	 * @since 2.0.6
	 *
	 * @var int
	 */
	private $posts_bulk_limit = null;

	/**
	 * WordPress Importer object for the current instance.
	 *
	 * @since 2.0.6
	 *
	 * @var null|WP_Import
	 */
	private $wp_importer = null;

	/** ==========================================================================
	 *  Constructing methods.
	 *  ======================================================================= */

	/**
	 * Setup initial data.
	 *
	 * @since 2.0
	 *
	 * @param string $slug
	 *
	 * @throws Exception   If the requested demo pack does not exist.
	 */
	public function __construct( $slug ) {
		/**
		 * Get the demo pack configuration from theme file.
		 */
		$config = nice_theme_get_demo_pack( $slug );

		/**
		 * Throw an exception if the requested demo pack does not exist.
		 */
		if ( is_null( $config ) ) {
			throw new Exception( sprintf( __( '%s is not an available demo pack.', 'nice-framework' ), $slug ) );
		}

		/**
		 * Initialize basic attributes.
		 */
		$this->slug          = $slug;
		$this->config        = $config;
		$this->system_status = nice_admin_system_status();

		/**
		 * Add import status hooks.
		 */
		$this->add_import_status_hooks();

		/**
		 * Add importing messages hooks.
		 */
		$this->add_importing_messages_hooks();
	}

	/**
	 * Obtain a Nice_Theme_Demo_Pack object.
	 *
	 * New instances are saved to a static variable, so they can be retrieved
	 * later without needing to be reinitialized.
	 *
	 * @since 2.0
	 *
	 * @param string $slug
	 *
	 * @return WP_Error|Nice_Theme_Demo_Pack
	 */
	public static function obtain( $slug ) {
		if ( ! $slug ) {
			return new WP_Error( 'demo_not_exists', __( 'The demo pack slug was not provided.', 'nice-framework' ) );
		}

		static $demo_imports = array();

		if ( ! isset( $demo_imports[ $slug ] ) ) {
			try {
				$demo_imports[ $slug ] = new self( $slug );

			} catch ( Exception $ex ) {
				return new WP_Error( 'demo_not_exists', $ex->getMessage() );
			}
		}

		return $demo_imports[ $slug ];
	}


	/** ==========================================================================
	 *  Getters & Setters.
	 *  ======================================================================= */

	/**
	 * Obtain the value of a property.
	 *
	 * @since  2.0
	 *
	 * @param  string $property
	 *
	 * @return null|string
	 */
	public function __get( $property ) {
		if ( method_exists( $this, 'get_' . $property ) ) {
			return call_user_func( array( $this, 'get_' . $property ) );
		}

		if ( property_exists( $this, $property ) ) {
			return $this->$property;
		}

		return null;
	}

	/**
	 * Obtain slug.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Obtain uploads path.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_uploads_path() {
		if ( is_null( $this->uploads_path ) ) {
			$this->set_uploads_path();
		}

		return $this->uploads_path;
	}

	/**
	 * Set uploads path.
	 *
	 * @since 2.0
	 */
	private function set_uploads_path() {
		$this->uploads_path = $this->system_status->get_wp_uploads_path() . '/demo-packs';
	}

	/**
	 * Obtain uploads URL.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_uploads_url() {
		if ( is_null( $this->uploads_url ) ) {
			$this->set_uploads_url();
		}

		return $this->uploads_url;
	}

	/**
	 * Set uploads URL.
	 *
	 * @since 2.0
	 */
	private function set_uploads_url() {
		$this->uploads_url = $this->system_status->get_wp_uploads_url() . '/demo-packs';
	}

	/**
	 * Obtain local path.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_path() {
		if ( is_null( $this->path ) ) {
			$this->set_path();
		}

		return $this->path;
	}

	/**
	 * Set local path.
	 *
	 * @since 2.0
	 */
	private function set_path() {
		if ( $this->is_external_url() ) {
			$this->path = $this->get_uploads_path() . '/' . $this->get_slug();

		} else {
			$this->path = get_template_directory() . '/includes/admin/demo-packs/' . $this->get_slug();
		}
	}

	/**
	 * Obtain local URL.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_url() {
		if ( is_null( $this->url ) ) {
			$this->set_url();
		}

		return $this->url;
	}

	/**
	 * Set local url.
	 *
	 * @since 2.0
	 */
	private function set_url() {
		if ( $this->is_external_url() ) {
			$this->url = $this->get_uploads_url() . '/' . $this->get_slug();

		} else {
			$this->url = $this->system_status->get_demo_packs_local_url() . '/' . $this->get_slug();
		}
	}

	/**
	 * Obtain external URL.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_external_url() {
		if ( is_null( $this->external_url ) ) {
			$this->set_external_url();
		}

		return $this->external_url;
	}

	/**
	 * Set external URL.
	 *
	 * @since 2.0
	 */
	private function set_external_url() {
		$this->external_url = isset( $this->config['external_url'] ) ? $this->config['external_url'] : '';
	}

	/**
	 * Obtain minimum theme version.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_theme_version() {
		if ( is_null( $this->theme_version ) ) {
			$this->set_theme_version();
		}

		return $this->theme_version;
	}

	/**
	 * Set minimum theme version.
	 *
	 * @since 2.0
	 */
	private function set_theme_version() {
		$this->theme_version = isset( $this->config['theme_version'] ) ? $this->config['theme_version'] : '';
	}

	/**
	 * Obtain minimum framework version.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_framework_version() {
		if ( is_null( $this->framework_version ) ) {
			$this->set_framework_version();
		}

		return $this->framework_version;
	}

	/**
	 * Set minimum framework version.
	 *
	 * @since 2.0
	 */
	private function set_framework_version() {
		$this->framework_version = isset( $this->config['framework_version'] ) ? $this->config['framework_version'] : '2.0';
	}

	/**
	 * Obtain used plugins.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_plugins() {
		if ( is_null( $this->plugins ) ) {
			$this->set_plugins();
		}

		return $this->plugins;
	}

	/**
	 * Set used plugins.
	 *
	 * @since 2.0
	 */
	private function set_plugins() {
		$plugins = array();

		if ( isset( $this->config['plugins'] ) ) {
			$all_plugins_slugs = $this->system_status->get_plugins_slugs();

			foreach ( $this->config['plugins'] as $plugin_path => $plugin_data ) {
				$plugins[ $plugin_path ] = array(
					'name'      => is_array( $plugin_data )         ? $plugin_data['name']    : $plugin_data,
					'slug'      => '',
					'version'   => isset( $plugin_data['version'] ) ? $plugin_data['version'] : '',
					'active'    => false,
					'installed' => false,
					'outdated'  => false,
					'missing'   => true,
				);

				$plugins[ $plugin_path ]['slug'] = isset( $all_plugins_slugs[ $plugin_path ] ) ? $all_plugins_slugs[ $plugin_path ] : $this->system_status->get_plugin_slug_from_path( $plugin_path );
			}
		}

		foreach ( $this->system_status->get_installed_plugins() as $plugin_path => $plugin_data ) {
			if ( array_key_exists( $plugin_path, $plugins ) ) {
				$plugins[ $plugin_path ]['active']    = $plugin_data['active'] || $plugin_data['network_active'];
				$plugins[ $plugin_path ]['installed'] = ! $plugins[ $plugin_path ]['active'];
				$plugins[ $plugin_path ]['outdated']  = ! empty( $plugins[ $plugin_path ]['version'] ) && ! version_compare( $this->system_status->get_plugin_version( $plugin_path ), $plugins[ $plugin_path ]['version'], '>=' );
				$plugins[ $plugin_path ]['missing']   = false;
			}
		}

		$this->plugins = $plugins;
	}

	/**
	 * Obtain used plugins which are currently active.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_active_plugins() {
		if ( is_null( $this->active_plugins ) ) {
			$this->set_active_plugins();
		}

		return $this->active_plugins;
	}

	/**
	 * Set used plugins which are currently active.
	 *
	 * @since 2.0
	 */
	private function set_active_plugins() {
		$active_plugins = array();

		foreach ( $this->get_plugins() as $plugin_path => $plugin_data ) {
			if ( $plugin_data['active'] ) {
				$active_plugins[ $plugin_path ] = $plugin_data;
			}
		}

		$this->active_plugins = $active_plugins;
	}

	/**
	 * Obtain used plugins which are currently installed.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_installed_plugins() {
		if ( is_null( $this->installed_plugins ) ) {
			$this->set_installed_plugins();
		}

		return $this->installed_plugins;
	}

	/**
	 * Set used plugins which are currently installed.
	 *
	 * @since 2.0
	 */
	private function set_installed_plugins() {
		$installed_plugins = array();

		foreach ( $this->get_plugins() as $plugin_path => $plugin_data ) {
			if ( $plugin_data['installed'] ) {
				$installed_plugins[ $plugin_path ] = $plugin_data;
			}
		}

		$this->installed_plugins = $installed_plugins;
	}

	/**
	 * Obtain used plugins which are currently outdated.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_outdated_plugins() {
		if ( is_null( $this->outdated_plugins ) ) {
			$this->set_outdated_plugins();
		}

		return $this->outdated_plugins;
	}

	/**
	 * Set used plugins which are currently outdated.
	 *
	 * @since 2.0
	 */
	private function set_outdated_plugins() {
		$outdated_plugins = array();

		foreach ( $this->get_plugins() as $plugin_path => $plugin_data ) {
			if ( $plugin_data['outdated'] ) {
				$outdated_plugins[ $plugin_path ] = $plugin_data;
			}
		}

		$this->outdated_plugins = $outdated_plugins;
	}

	/**
	 * Obtain used plugins which are currently missing.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_missing_plugins() {
		if ( is_null( $this->missing_plugins ) ) {
			$this->set_missing_plugins();
		}

		return $this->missing_plugins;
	}

	/**
	 * Set used plugins which are currently missing.
	 *
	 * @since 2.0
	 */
	private function set_missing_plugins() {
		$missing_plugins = array();

		foreach ( $this->get_plugins() as $plugin_path => $plugin_data ) {
			if ( $plugin_data['missing'] ) {
				$missing_plugins[ $plugin_path ] = $plugin_data;
			}
		}

		$this->missing_plugins = $missing_plugins;
	}

	/**
	 * Check if the current demo pack presents some plugin exception.
	 *
	 * @since 2.0.4
	 *
	 * @return bool
	 */
	public function has_plugin_exceptions() {
		$inactive_plugins = $this->get_inactive_plugins();

		if ( ! empty( $inactive_plugins ) ) {
			return true;
		}

		$outdated_plugins = $this->get_outdated_plugins();

		if ( ! empty( $outdated_plugins ) ) {
			return true;
		}

		$missing_plugins = $this->get_missing_plugins();

		if ( ! empty( $missing_plugins ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Reset plugins.
	 *
	 * @since 2.0
	 */
	public function reset_plugins() {
		$this->plugins           = null;
		$this->active_plugins    = null;
		$this->installed_plugins = null;
		$this->outdated_plugins  = null;
		$this->missing_plugins   = null;
	}

	/**
	 * Obtain the list of system requirements for the current demo pack.
	 *
	 * @since 2.0.4
	 *
	 * @return array
	 */
	public function get_system_requirements() {
		if ( empty( $this->system_requirements ) ) {
			$this->set_system_requirements();
		}

		return $this->system_requirements;
	}

	/**
	 * Set the list of system requirements for the current demo pack.
	 *
	 * @since 2.0.4
	 */
	private function set_system_requirements() {
		$this->system_requirements = array(
			'wp_version'         => array(
				'title'       => esc_html__( 'WordPress', 'nice-framework' ),
				'type'        => 'version',
				'current'     => $this->system_status->get_wp_version(),
				'recommended' => $this->system_status->get_recommended_wp_version(),
				'required'    => $this->system_status->get_required_wp_version(),
				'messages'    => array(
					'recommended' => sprintf( esc_html__( 'WordPress %s or higher', 'nice-framework' ), $this->system_status->get_recommended_wp_version() ),
					'success'     => sprintf( esc_html__( 'Your current WordPress version is %s. You should be fine.', 'nice-framework' ), $this->system_status->get_wp_version() ),
					'warning'     => sprintf( esc_html__( 'Your current WordPress version is %s. We recommend you to update to version %s or higher.', 'nice-framework' ), $this->system_status->get_wp_version(), $this->system_status->get_recommended_wp_version() ),
					'error'       => sprintf( esc_html__( 'Your current WordPress version is %s. This version is not supported by the theme. We recommend you to update to version %s or higher.', 'nice-framework' ), $this->system_status->get_wp_version(), $this->system_status->get_recommended_wp_version() ),
				),
			),
			'wp_upload_dir'      => array(
				'title'       => esc_html__( 'Upload Directory', 'nice-framework' ),
				'type'        => 'bool',
				'current'     => $this->system_status->is_wp_uploads_dir_writable(),
				'recommended' => null,
				'required'    => true,
				'messages'    => array(
					'recommended' => esc_html__( 'Writable Upload Directory', 'nice-framework' ),
					'success'     => esc_html__( 'Your upload directory is writable. You should be fine.', 'nice-framework' ),
					'warning'     => null,
					'error'       => esc_html__( 'Your upload directory is not writable', 'nice-framework' ),
				),
			),
			'mysql_version'      => array(
				'title'       => esc_html__( 'MySQL', 'nice-framework' ),
				'type'        => 'version',
				'current'     => $this->system_status->get_mysql_version(),
				'recommended' => $this->system_status->get_recommended_mysql_version(),
				'required'    => $this->system_status->get_required_mysql_version(),
				'messages'    => array(
					'recommended' => sprintf( esc_html__( 'MySQL %s or higher', 'nice-framework' ), $this->system_status->get_recommended_mysql_version() ),
					'success'     => sprintf( esc_html__( 'Your current MySQL version is %s. You should be fine.', 'nice-framework' ), $this->system_status->get_mysql_version() ),
					'warning'     => sprintf( esc_html__( 'Your current MySQL version is %s. We recommend you to update to version %s or higher.', 'nice-framework' ), $this->system_status->get_mysql_version(), $this->system_status->get_recommended_mysql_version() ),
					'error'       => sprintf( esc_html__( 'Your current MySQL version is %s. This version is not supported by the theme. We recommend you to update to version %s or higher.', 'nice-framework' ), $this->system_status->get_mysql_version(), $this->system_status->get_recommended_mysql_version() ),
				),
			),
			'php_version'        => array(
				'title'       => esc_html__( 'PHP', 'nice-framework' ),
				'type'        => 'version',
				'current'     => $this->system_status->get_php_version(),
				'recommended' => $this->system_status->get_recommended_php_version(),
				'required'    => $this->system_status->get_required_php_version(),
				'messages'    => array(
					'recommended' => sprintf( esc_html__( 'PHP %s or higher', 'nice-framework' ), $this->system_status->get_recommended_php_version() ),
					'success'     => sprintf( esc_html__( 'Your current PHP version is %s. You should be fine.', 'nice-framework' ), $this->system_status->get_php_version() ),
					'warning'     => sprintf( esc_html__( 'Your current PHP version is %s. We recommend you to update to version %s or higher.', 'nice-framework' ), $this->system_status->get_php_version(), $this->system_status->get_recommended_php_version() ),
					'error'       => sprintf( esc_html__( 'Your current PHP version is %s. This version is not supported by the theme. We recommend you to update to version %s or higher.', 'nice-framework' ), $this->system_status->get_php_version(), $this->system_status->get_recommended_php_version() ),
				),
			),
			'php_memory_limit'   => array(
				'title'       => esc_html__( 'PHP Memory Limit', 'nice-framework' ),
				'type'        => 'number',
				'current'     => $this->system_status->get_wp_memory_limit(),
				'recommended' => $this->system_status->get_recommended_wp_memory_limit(),
				'required'    => $this->system_status->get_required_wp_memory_limit(),
				'messages'    => array(
					'recommended' => sprintf( esc_html__( 'PHP Memory Limit: %s or higher', 'nice-framework' ), $this->system_status->get_formatted_recommended_wp_memory_limit() ),
					'success'     => sprintf( esc_html__( 'Your current PHP Memory Limit is %s. You should be fine.', 'nice-framework' ), $this->system_status->get_formatted_wp_memory_limit() ),
					'warning'     => sprintf( esc_html__( 'Your current PHP Memory Limit is %s. We recommend you to increase it to at least %s.', 'nice-framework' ), $this->system_status->get_formatted_wp_memory_limit(), $this->system_status->get_formatted_recommended_wp_memory_limit() ),
					'error'       => sprintf( esc_html__( 'Your current PHP Memory Limit is %s. We recommend you to increase it to at least %s.', 'nice-framework' ), $this->system_status->get_formatted_wp_memory_limit(), $this->system_status->get_formatted_recommended_wp_memory_limit() ),
				),
			),
			'php_time_limit'     => array(
				'title'       => esc_html__( 'PHP Time Limit', 'nice-framework' ),
				'type'        => 'number',
				'current'     => $this->system_status->get_php_time_limit(),
				'recommended' => $this->system_status->get_recommended_php_time_limit(),
				'required'    => $this->system_status->get_required_php_time_limit(),
				'allow_zero'  => true,
				'messages'    => array(
					'recommended' => sprintf( esc_html__( 'PHP Time Limit: %s seconds or higher', 'nice-framework' ), $this->system_status->get_recommended_php_time_limit() ),
					'success'     => sprintf( esc_html__( 'Your current PHP Time Limit is %s seconds. You should be fine.', 'nice-framework' ), $this->system_status->get_php_time_limit() ),
					'warning'     => sprintf( esc_html__( 'Your current PHP Time Limit is %s seconds. We recommend you to increase it to at least %s seconds.', 'nice-framework' ), $this->system_status->get_php_time_limit(), $this->system_status->get_recommended_php_time_limit() ),
					'error'       => sprintf( esc_html__( 'Your current PHP Time Limit is %s seconds. We recommend you to increase it to at least %s seconds.', 'nice-framework' ), $this->system_status->get_php_time_limit(), $this->system_status->get_recommended_php_time_limit() ),
				),
			),
			'php_max_upload_size' => array(
				'title'       => esc_html__( 'PHP Maximum Upload File Size', 'nice-framework' ),
				'type'        => 'number',
				'current'     => $this->system_status->get_wp_max_upload_size(),
				'recommended' => $this->system_status->get_recommended_wp_max_upload_size(),
				'required'    => $this->system_status->get_required_wp_max_upload_size(),
				'messages'    => array(
					'recommended' => sprintf( esc_html__( 'PHP Maximum Upload File Size: %s', 'nice-framework' ), $this->system_status->get_formatted_recommended_wp_max_upload_size() ),
					'success'     => sprintf( esc_html__( 'Your current PHP Maximum Upload File Size is %s. You should be fine.', 'nice-framework' ), $this->system_status->get_formatted_wp_max_upload_size() ),
					'warning'     => sprintf( esc_html__( 'Your current PHP Maximum Upload File Size is %s. We recommend you to increase it to at least %s.', 'nice-framework' ), $this->system_status->get_formatted_wp_max_upload_size(), $this->system_status->get_formatted_recommended_wp_max_upload_size() ),
					'error'       => sprintf( esc_html__( 'Your current PHP Maximum Upload File Size is %s. We recommend you to increase it to at least %s.', 'nice-framework' ), $this->system_status->get_formatted_wp_max_upload_size(), $this->system_status->get_formatted_recommended_wp_max_upload_size() ),
				),
			),
			'php_max_post_size'  => array(
				'title'       => esc_html__( 'PHP Maximum POST Size', 'nice-framework' ),
				'type'        => 'number',
				'current'     => $this->system_status->get_php_post_max_size(),
				'recommended' => $this->system_status->get_recommended_php_post_max_size(),
				'required'    => $this->system_status->get_required_php_post_max_size(),
				'messages'    => array(
					'recommended' => sprintf( esc_html__( 'PHP Maximum POST Size: %s or higher', 'nice-framework' ), $this->system_status->get_formatted_php_post_max_size() ),
					'success'     => sprintf( esc_html__( 'Your current PHP Maximum Post Size is %s. You should be fine.', 'nice-framework' ), $this->system_status->get_formatted_php_post_max_size() ),
					'warning'     => sprintf( esc_html__( 'Your current PHP Maximum Post Size is %s. We recommend you to increase it to at least %s.', 'nice-framework' ), $this->system_status->get_formatted_php_post_max_size(), $this->system_status->get_formatted_recommended_php_post_max_size() ),
					'error'       => sprintf( esc_html__( 'Your current PHP Maximum Post Size is %s. We recommend you to increase it to at least %s.', 'nice-framework' ), $this->system_status->get_formatted_php_post_max_size(), $this->system_status->get_formatted_recommended_php_post_max_size() ),
				),
			),
			'php_max_input_vars' => array(
				'title'       => esc_html__( 'PHP Maximum Input Variables', 'nice-framework' ),
				'type'        => 'number',
				'current'     => $this->system_status->get_php_max_input_vars(),
				'recommended' => $this->system_status->get_recommended_php_max_input_vars(),
				'required'    => $this->system_status->get_required_php_max_input_vars(),
				'messages'    => array(
					'recommended' => sprintf( esc_html__( 'PHP Maximum Input Variables: %s or higher', 'nice-framework' ), $this->system_status->get_recommended_php_max_input_vars() ),
					'success'     => sprintf( esc_html__( 'Your current value for PHP Maximum Input Variables is %s. You should be fine.', 'nice-framework' ), $this->system_status->get_php_max_input_vars() ),
					'warning'     => sprintf( esc_html__( 'Your current value for PHP Maximum Input Variables is %s. We recommend you to increase it to at least %s.', 'nice-framework' ), $this->system_status->get_php_max_input_vars(), $this->system_status->get_recommended_php_max_input_vars() ),
					'error'       => sprintf( esc_html__( 'Your current value for PHP Maximum Input Variables is %s. We recommend you to increase it to at least %s.', 'nice-framework' ), $this->system_status->get_php_max_input_vars(), $this->system_status->get_recommended_php_max_input_vars() ),
				),
			),
			'php_remote_get'     => array(
				'title'       => esc_html__( 'PHP Remote GET', 'nice-framework' ),
				'type'        => 'bool',
				'current'     => $this->system_status->get_wp_remote_get(),
				'recommended' => null,
				'required'    => true,
				'messages'    => array(
					'recommended' => esc_html__( 'PHP Remote GET: Enabled', 'nice-framework' ),
					'success'     => esc_html__( 'PHP Remote GET is enabled. You should be fine.', 'nice-framework' ),
					'warning'     => esc_html__( 'PHP Remote GET is disabled.', 'nice-framework' ),
					'error'       => esc_html__( 'PHP Remote GET is disabled.', 'nice-framework' ),
				),
			),
			'php_xdebug_on'      => array(
				'title'       => esc_html__( 'Xdebug', 'nice-framework' ),
				'type'        => 'bool',
				'current'     => $this->system_status->xdebug_enabled(),
				'recommended' => false,
				'required'    => null,
				'messages'    => array(
					'recommended' => esc_html__( 'Xdebug: Disabled', 'nice-framework' ),
					'success'     => esc_html__( 'Xdebug is disabled. You should be fine.', 'nice-framework' ),
					'warning'     => esc_html__( 'Xdebug is enabled.', 'nice-framework' ),
					'error'       => null,
				),
			),
			'mod_security'    => array(
				'title'       => esc_html__( 'mod_security', 'nice-framework' ),
				'type'        => 'bool',
				'current'     => $this->system_status->mod_security_enabled(),
				'recommended' => false,
				'required'    => null,
				'messages'    => array(
					'recommended' => esc_html__( 'mod_security: Disabled', 'nice-framework' ),
					'success'     => esc_html__( 'mod_security was not detected in your server. You should be fine.', 'nice-framework' ),
					'warning'     => esc_html__( 'mod_security is enabled.', 'nice-framework' ),
					'error'       => null,
				),
			),
		);

		/**
		 * @hook nice_demo_pack_system_requirements
		 *
		 * Hook in here to modify the list of system requirements for the
		 * current demo pack.
		 *
		 * @since 2.0.4
		 */
		$this->system_requirements = apply_filters( 'nice_demo_pack_system_requirements', $this->system_requirements, $this );

		/**
		 * Set exceptions (warnings and errors) for the current demo pack.
		 */
		foreach ( $this->system_requirements as $id => $requirement ) {
			$comparison_point   = is_null( $requirement['required'] ) ? $requirement['recommended'] : $requirement['required'];
			$exception_property = is_null( $requirement['required'] ) ? 'system_warnings' : 'system_errors';

			switch ( $requirement['type'] ) {
				case 'version':
					if ( version_compare( $requirement['current'], $comparison_point, '<' ) ) {
						$this->{$exception_property}[ $id ] = $requirement;
					}
					break;
				case 'number':
					if ( $requirement['current'] < $comparison_point ) {
						$this->{$exception_property}[ $id ] = $requirement;

						// If zero is valid for the current requirement, unset the exception.
						if ( ! empty( $requirement['allow_zero'] ) && 0 === intval( $requirement['current'] ) ) {
							unset( $this->{$exception_property}[ $id ] );
						}
					}
					break;
				case 'bool':
					if ( $requirement['current'] !== $comparison_point ) {
						$this->{$exception_property}[ $id ] = $requirement;
					}
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Obtain the list of system warnings for the current demo pack.
	 *
	 * @since 2.0.4
	 *
	 * @return array
	 */
	public function get_system_warnings() {
		if ( empty( $this->system_requirements ) ) {
			$this->set_system_requirements();
		}

		return $this->system_warnings;
	}

	/**
	 * Obtain the list of system errors for the current demo pack.
	 *
	 * @since 2.0.4
	 *
	 * @return array
	 */
	public function get_system_errors() {
		if ( empty( $this->system_requirements ) ) {
			$this->set_system_requirements();
		}

		return $this->system_errors;
	}

	/**
	 * Obtain menu order.
	 *
	 * @since 2.0
	 *
	 * @return null|int
	 */
	public function get_menu_order() {
		if ( is_null( $this->menu_order ) ) {
			$this->set_menu_order();
		}

		return $this->menu_order;
	}

	/**
	 * Set menu order.
	 *
	 * @since 2.0
	 */
	private function set_menu_order() {
		$this->name = isset( $this->config['menu_order'] ) ? intval( $this->config['menu_order'] ) : 0;
	}

	/**
	 * Obtain name.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_name() {
		if ( is_null( $this->name ) ) {
			$this->set_name();
		}

		return $this->name;
	}

	/**
	 * Set name.
	 *
	 * @since 2.0
	 */
	private function set_name() {
		$this->name = ( isset( $this->config['name'] ) && ! empty( $this->config['name'] ) ) ? $this->config['name'] : ucfirst( $this->slug );
	}

	/**
	 * Obtain description.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_description() {
		if ( is_null( $this->description ) ) {
			$this->set_description();
		}

		return $this->description;
	}

	/**
	 * Set description.
	 *
	 * @since 2.0
	 */
	private function set_description() {
		$this->description = ( isset( $this->config['description'] ) && ! empty( $this->config['description'] ) ) ? $this->config['description'] : sprintf( esc_html__( 'Demo Pack for %s theme.', 'nice-framework' ), $this->system_status->get_nice_theme_name() );
	}

	/**
	 * Obtain tags.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_tags() {
		if ( is_null( $this->tags ) ) {
			$this->set_tags();
		}

		return $this->tags;
	}

	/**
	 * Set tags.
	 *
	 * @since 2.0
	 */
	private function set_tags() {
		$tags = array();

		if ( ! empty( $this->config['tags'] ) ) {
			$config_tags = array_unique( array_values( $this->config['tags'] ) );

			foreach ( $config_tags as $config_tag ) {
				$tags[ sanitize_title( $config_tag ) ] = $config_tag;
			}
		}

		ksort( $tags );

		$this->tags = $tags;
	}

	/**
	 * Obtain colors.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_colors() {
		if ( is_null( $this->colors ) ) {
			$this->set_colors();
		}

		return $this->colors;
	}

	/**
	 * Set colors.
	 *
	 * @since 2.0
	 */
	private function set_colors() {
		$colors = array();

		if ( ! empty( $this->config['colors'] ) ) {
			$config_colors = array_unique( array_values( $this->config['colors'] ) );

			foreach ( $config_colors as $config_color ) {
				$colors[ sanitize_title( $config_color ) ] = $config_color;
			}
		}

		ksort( $colors );

		$this->colors = $colors;
	}

	/**
	 * Obtain features.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_features() {
		if ( is_null( $this->features ) ) {
			$this->set_features();
		}

		return $this->features;
	}

	/**
	 * Set features.
	 *
	 * @since 2.0
	 */
	private function set_features() {
		$features = array();

		if ( ! empty( $this->config['features'] ) ) {
			$config_features = array_unique( array_values( $this->config['features'] ) );

			foreach ( $config_features as $config_feature ) {
				$features[ sanitize_title( $config_feature ) ] = $config_feature;
			}
		}

		ksort( $features );

		$this->features = $features;
	}

	/**
	 * Obtain images.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_images() {
		if ( is_null( $this->images ) ) {
			$this->set_images();
		}

		return $this->images;
	}

	/**
	 * Set images.
	 *
	 * @since 2.0
	 */
	private function set_images() {
		if ( isset( $this->config['images'] ) && ! empty( $this->config['images'] ) ) {
			$images = $this->config['images'];

			if ( ! $this->is_external_url() ) {
				foreach ( $images as &$image ) {
					$image = $this->get_url() . '/images/' . basename( $image );
				}
			}

		} else {
			$images = array(
				NICE_TPL_DIR . '/engine/admin/assets/images/demo-nicethemes.png',
			);
		}

		$this->images = $images;
	}

	/**
	 * Obtain featured image.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_featured_image() {
		if ( is_null( $this->featured_image ) ) {
			$this->set_featured_image();
		}

		return $this->featured_image;
	}

	/**
	 * Set featured image.
	 *
	 * @since 2.0
	 */
	private function set_featured_image() {
		$images = $this->get_images();

		$this->featured_image = $images[0];
	}

	/**
	 * Obtain preview URL.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_preview_url() {
		if ( is_null( $this->preview_url ) ) {
			$this->set_preview_url();
		}

		return $this->preview_url;
	}

	/**
	 * Set preview URL.
	 *
	 * @since 2.0
	 */
	private function set_preview_url() {
		$this->preview_url = ( isset( $this->config['preview_url'] ) && ! empty( $this->config['preview_url'] ) ) ? $this->config['preview_url'] : '';
	}

	/**
	 * Obtain the import status of a single process.
	 *
	 * @since 2.0
	 *
	 * @param string $process
	 *
	 * @return null|bool
	 */
	public function get_single_import_status( $process ) {
		if ( isset( $this->import_status[ $process ] ) ) {
			return $this->import_status[ $process ];
		}

		return null;
	}

	/**
	 * Set the import status of a single process.
	 *
	 * @since 2.0
	 *
	 * @param string $process
	 * @param bool   $status
	 */
	public function set_single_import_status( $process, $status ) {
		if ( isset( $this->import_status[ $process ] ) ) {
			$this->import_status[ $process ] = $status;
		}
	}

	/**
	 * Obtain import status.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_import_status() {
		if ( is_null( $this->import_status ) ) {
			$this->set_import_status();
		}

		return $this->import_status;
	}

	/**
	 * Obtain page ID by title.
	 *
	 * @since 2.0
	 *
	 * @param string $title
	 *
	 * @return null|string
	 */
	private static function get_page_id( $title ) {
		global $wpdb;

		$page_id = $wpdb->get_var( $wpdb->prepare(
			"SELECT ID
			FROM $wpdb->posts
			WHERE post_title = %s
			AND post_type = %s
		", $title, 'page' ) );

		return $page_id;
	}

	/**
	 * Set import status.
	 *
	 * @since 2.0
	 */
	private function set_import_status() {
		/**
		 * @hook nice_theme_demo_pack_import_status
		 *
		 * Hook here the status functions of all import processes.
		 *
		 * @since 2.0
		 */
		$import_status = apply_filters( 'nice_theme_demo_pack_import_status', array(), $this );

		$saved_import_status = get_option( 'nice_' . $this->system_status->get_nice_theme_slug() . '_' . $this->get_slug() . '_demo_import_status', array() );
		if ( ! empty( $saved_import_status ) ) {
			foreach ( $saved_import_status as $process_id => $process_status ) {
				if ( isset( $import_status[ $process_id ] ) ) {
					$import_status[ $process_id ] = $process_status;
				}
			}
		}

		$this->import_status = $import_status;
	}

	/**
	 * Reset import status.
	 *
	 * @since 2.0
	 */
	public function reset_import_status() {
		$this->import_status = array_fill_keys( array_keys( $this->get_import_status() ) , false );
	}

	/**
	 * Save import status to database.
	 *
	 * @since 2.0
	 */
	public function save_import_status() {
		update_option( 'nice_' . $this->system_status->get_nice_theme_slug() . '_' . $this->get_slug() . '_demo_import_status', $this->get_import_status(), false );
	}

	/**
	 * Obtain importing messages.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_importing_messages() {
		if ( is_null( $this->importing_messages ) ) {
			$this->set_importing_messages();
		}

		return $this->importing_messages;
	}

	/**
	 * Set importing messages.
	 *
	 * @since 2.0
	 */
	private function set_importing_messages() {
		/**
		 * @hook nice_theme_demo_pack_importing_messages
		 *
		 * Hook here the importing messages of all import processes.
		 *
		 * @since 2.0
		 */
		$this->importing_messages = apply_filters( 'nice_theme_demo_pack_importing_messages', array(), $this );
	}

	/**
	 * Obtain install status.
	 *
	 * @since 2.0
	 *
	 * @return null|string
	 */
	public function get_install_status() {
		if ( is_null( $this->install_status ) ) {
			$this->set_install_status();
		}

		return $this->install_status;
	}

	/**
	 * Set install status.
	 *
	 * @since 2.0
	 */
	public function set_install_status() {
		$this->install_status = 'none';

		$import_status = $this->get_import_status();

		if ( ! empty( $import_status ) ) {
			$count_done   = 0;
			$count_undone = 0;

			foreach ( $import_status as $process_id => $process_import_status ) {
				$process_import_status ? $count_done ++ : $count_undone ++;
			}

			if ( 0 === $count_undone ) { // If all processes have been completed, the demo is fully installed.
				$this->install_status = 'full';
			} elseif ( 0 < $count_done ) { // If some processes have been completed, the demo is parttially installed.
				$this->install_status = 'partial';
			}
		}
	}

	/**
	 * Set did something.
	 *
	 * @since 2.0
	 */
	public function set_did_something() {
		$this->did_something = true;
	}

	/**
	 * Set error occurred.
	 *
	 * @since 2.0
	 */
	public function set_error_occurred() {
		$this->error_occurred = true;
	}

	/**
	 * Obtain the total number of posts to be imported from the current pack.
	 *
	 * @since 2.0.6
	 *
	 * @return null|int
	 */
	private function get_total_posts_number() {
		return $this->total_posts_number;
	}

	/**
	 * Set the total number of posts to be imported from the current demo pack.
	 *
	 * @since 2.0.6
	 *
	 * @param int $number
	 */
	private function set_total_posts_number( $number = 0 ) {
		$this->total_posts_number = $number;
	}

	/**
	 * Obtain the number of posts that were already processed by the importer.
	 *
	 * @since 2.0.6
	 *
	 * @return null
	 */
	private function get_processed_posts_number() {
		return $this->processed_posts_number;
	}

	/**
	 * Set the number of posts that were already processed by the importer.
	 *
	 * @since 2.0.6
	 *
	 * @param int $number
	 */
	private function set_processed_posts_number( $number = 0 ) {
		$this->processed_posts_number = $number;
	}

	/**
	 * Update the number of posts that were already processed by the importer.
	 *
	 * @since 2.0.6
	 */
	public function update_processed_posts_number() {
		check_ajax_referer( 'play-nice', 'nice_demo_import_nonce' );

		if ( ! $this->get_posts_bulk_limit() ) {
			return;
		}

		if ( isset( $_POST['processed_posts_number'] ) ) {
			$this->set_processed_posts_number( intval( $_POST['processed_posts_number'] ) );
			unset( $_POST['processed_posts_number'] );
			return;
		}

		$processed_posts_number = $this->get_processed_posts_number() + $this->get_posts_bulk_limit();
		$total_posts_number     = $this->get_total_posts_number();

		if ( $processed_posts_number > $total_posts_number ) {
			$processed_posts_number = $total_posts_number;
		}

		$this->set_processed_posts_number( $processed_posts_number );
	}

	/**
	 * Setup WordPress Importer functionality.
	 *
	 * @since 2.0.8
	 */
	private function setup_wp_importer() {
		/**
		 * If the standard WordPress Importer plugin is active, deactivate it and end the request.
		 */
		if ( $this->system_status->is_plugin_active( 'wordpress-importer/wordpress-importer.php' ) ) {
			deactivate_plugins( 'wordpress-importer/wordpress-importer.php', true );

			$this->set_did_something();
			$this->add_ajax_message( esc_html__( 'WordPress Importer plugin was deactivated to ensure compatibility.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		/**
		 * Load the bundled version of the WordPress Importer plugin.
		 */

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			/**
			 * Set importers to be loaded.
			 */
			define( 'WP_LOAD_IMPORTERS', true );
		}

		if ( ! class_exists( 'WP_Import' ) ) {
			nice_loader( 'engine/admin/lib/wordpress-importer/wordpress-importer.php' );
		}
	}

	/**
	 * Obtain the WP_Import object for this instance.
	 *
	 * @since 2.0.6
	 *
	 * @return null|WP_Import
	 */
	private function get_wp_importer() {
		if ( is_null( $this->wp_importer ) ) {
			$this->wp_importer = new WP_Import();
		}

		return $this->wp_importer;
	}

	/**
	 * Obtain the maximum number of posts to process in a single request.
	 *
	 * @since 2.0.6
	 *
	 * @return null|int
	 */
	private function get_posts_bulk_limit() {
		if ( is_null( $this->posts_bulk_limit ) ) {
			$this->set_posts_bulk_limit();
		}

		return $this->posts_bulk_limit;
	}

	/**
	 * Set the maximum number of posts to process in a single request.
	 *
	 * @since 2.0.6
	 */
	private function set_posts_bulk_limit() {
		$bulk_limit = 0;

		/**
		 * @hook nice_demo_pack_use_posts_bulk_limit
		 *
		 * Hook in here to dectivate the bulk limit.
		 *
		 * @since 2.0.6
		 */
		if ( apply_filters( 'nice_demo_pack_use_posts_bulk_limit', true ) ) {
			$memory_limit = $this->system_status->get_wp_memory_limit();

			if ( $memory_limit > 805306368 ) { // 768MB and more
				$auto_bulk_limit = 45;
			} elseif ( ( 536870912 <= $memory_limit ) && ( $memory_limit <= 805306368 ) ) { // 512MB - 768MB
				$auto_bulk_limit = 30;
			} elseif ( ( 268435456 <= $memory_limit ) && ( $memory_limit <= 536870912 ) ) { // 256MB - 512MB
				$auto_bulk_limit = 20;
			} elseif ( ( 134217728 <= $memory_limit ) && ( $memory_limit <= 268435456 ) ) { // 128MB - 256MB
				$auto_bulk_limit = 15;
			} else { // Less than 128MB
				$auto_bulk_limit = 5;
			}

			/**
			 * @hook nice_demo_pack_posts_bulk_limit
			 *
			 * Hook in here to modify the maximum number of posts to process in
			 * a single request.
			 *
			 * @since 2.0.6
			 */
			$bulk_limit = apply_filters( 'nice_demo_pack_posts_bulk_limit', $auto_bulk_limit, $memory_limit );
		}

		$this->posts_bulk_limit = $bulk_limit;
	}


	/** ==========================================================================
	 *  Wrappers.
	 *  ======================================================================= */

	/**
	 * Obtain next import step.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_next_import_step() {
		$import_status = $this->get_import_status();

		if ( ! empty( $import_status ) ) {
			foreach ( $import_status as $step => $status ) {
				if ( false === $status ) {
					return $step;
				}
			}
		}

		return '';
	}

	/**
	 * Obtain next import step message.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_next_import_step_message() {
		$importing_messages = $this->get_importing_messages();
		$next_step          = $this->get_next_import_step();

		if ( ! empty( $importing_messages[ $next_step ] ) ) {
			return $importing_messages[ $next_step ];
		}

		return '';
	}


	/**
	 * Whether or not the installed version of the theme is the minimum required.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_theme_version() {
		if ( ! $this->get_theme_version() ) {
			return true;
		}

		return version_compare( $this->system_status->get_nice_theme_version(), $this->get_theme_version(), '>=' );
	}

	/**
	 * Whether or not the installed version of the framework is the minimum required.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_framework_version() {
		return version_compare( $this->system_status->get_nice_framework_version(), $this->get_framework_version(), '>=' );
	}

	/**
	 * Whether or not a plugin is used by the demo pack.
	 *
	 * @since 2.0
	 *
	 * @param string $plugin_slug_or_path
	 *
	 * @return bool
	 */
	public function is_plugin_used( $plugin_slug_or_path ) {
		$plugin_path = $this->get_plugin_path_from_slug( $plugin_slug_or_path );

		return array_key_exists( $plugin_path, $this->get_plugins() );
	}

	/**
	 * Obtain plugins slugs.
	 *
	 * @since 2.0
	 *
	 * @return array()
	 */
	public function get_plugins_slugs() {
		return wp_list_pluck( $this->get_plugins(), 'slug' );
	}

	/**
	 * Obtain whether there are plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_plugins() {
		$plugins = $this->get_plugins();

		return ! empty( $plugins );
	}

	/**
	 * Obtain whether there are active plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_active_plugins() {
		$active_plugins = $this->get_active_plugins();

		return ! empty( $active_plugins );
	}

	/**
	 * Obtain whether there are installed plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_installed_plugins() {
		$installed_plugins = $this->get_installed_plugins();

		return ! empty( $installed_plugins );
	}

	/**
	 * Obtain whether there are outdated plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_outdated_plugins() {
		$outdated_plugins = $this->get_outdated_plugins();

		return ! empty( $outdated_plugins );
	}

	/**
	 * Obtain whether there are missing plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_missing_plugins() {
		$missing_plugins = $this->get_missing_plugins();

		return ! empty( $missing_plugins );
	}

	/**
	 * Obtain whether there are active updated plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_active_updated_plugins() {
		$active_updated_plugins = $this->get_active_updated_plugins();

		return ! empty( $active_updated_plugins );
	}

	/**
	 * Obtain active plugins which are up to date (not outdated).
	 *
	 * @since 2.0
	 *
	 * @return array()
	 */
	public function get_active_updated_plugins() {
		return array_diff_key( $this->get_active_plugins(), $this->get_outdated_plugins() );
	}

	/**
	 * Obtain whether there are active outdated plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_active_outdated_plugins() {
		$active_outdated_plugins = $this->get_active_outdated_plugins();

		return ! empty( $active_outdated_plugins );
	}

	/**
	 * Obtain active plugins which are outdated.
	 *
	 * @since 2.0
	 *
	 * @return array()
	 */
	public function get_active_outdated_plugins() {
		return array_intersect_key( $this->get_active_plugins(), $this->get_outdated_plugins() );
	}

	/**
	 * Obtain whether there are inactive plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_inactive_plugins() {
		$inactive_plugins = $this->get_inactive_plugins();

		return ! empty( $inactive_plugins );
	}

	/**
	 * Obtain used plugins which are currently inactive.
	 *
	 * @since 2.0
	 *
	 * @return null|array
	 */
	public function get_inactive_plugins() {
		return array_diff_key( $this->get_installed_plugins(), $this->get_active_plugins() );
	}

	/**
	 * Obtain whether there are installed updated plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_installed_updated_plugins() {
		$installed_updated_plugins = $this->get_installed_updated_plugins();

		return ! empty( $installed_updated_plugins );
	}

	/**
	 * Obtain installed plugins which are up to date (not outdated).
	 *
	 * @since 2.0
	 *
	 * @return array()
	 */
	public function get_installed_updated_plugins() {
		return array_diff_key( $this->get_installed_plugins(), $this->get_outdated_plugins() );
	}

	/**
	 * Obtain whether there are installed outdated plugins.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_installed_outdated_plugins() {
		$installed_outdated_plugins = $this->get_installed_outdated_plugins();

		return ! empty( $installed_outdated_plugins );
	}

	/**
	 * Obtain active plugins which are outdated.
	 *
	 * @since 2.0
	 *
	 * @return array()
	 */
	public function get_installed_outdated_plugins() {
		return array_intersect_key( $this->get_installed_plugins(), $this->get_outdated_plugins() );
	}

	/**
	 * Whether or not the demo will be installed from an external URL.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_external_url() {
		$external_url = $this->get_external_url();
		return ! empty( $external_url );
	}

	/**
	 * Whether or not the demo requires to take action on the theme before installing it.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function theme_actions_required() {
		return ! ( $this->is_theme_version() && $this->is_framework_version() );
	}

	/**
	 * Whether or not the demo requires to take action on plugins before installing it.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function plugin_actions_required() {
		$outdated_plugins = $this->get_active_outdated_plugins();

		return ! empty( $outdated_plugins );
	}

	/**
	 * Whether or not the demo recommends to take action on plugins before installing it.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function plugin_actions_recommended() {
		$installed_plugins = $this->get_installed_plugins();
		$missing_plugins   = $this->get_missing_plugins();

		return ! ( empty( $installed_plugins ) && empty( $missing_plugins ) );
	}

	/**
	 * Whether or not the demo can be installed.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function can_install() {
		return ! $this->theme_actions_required() && ! $this->plugin_actions_required();
	}

	/**
	 * Whether or not the demo should be installed.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function should_install() {
		return $this->can_install() && ! $this->plugin_actions_recommended();
	}

	/**
	 * Obtain whether or not the demo is installed.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_installed() {
		return $this->is_fully_installed() || $this->is_partially_installed();
	}

	/**
	 * Obtain whether or not the demo is fully installed.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_fully_installed() {
		return 'full' === $this->get_install_status();
	}

	/**
	 * Obtain whether or not the demo is partially installed.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_partially_installed() {
		return 'partial' === $this->get_install_status();
	}

	/**
	 * Obtain whether there are tags.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_tags() {
		$tags = $this->get_tags();

		return ! empty( $tags );
	}

	/**
	 * Obtain whether there are colors.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_colors() {
		$colors = $this->get_colors();

		return ! empty( $colors );
	}

	/**
	 * Obtain whether there are features.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function have_features() {
		$features = $this->get_features();

		return ! empty( $features );
	}


	/** ==========================================================================
	 *  AJAX.
	 *  ======================================================================= */

	/**
	 * Obtain AJAX context.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function doing_ajax() {
		check_ajax_referer( 'play-nice', 'nice_demo_import_nonce' );

		return nice_doing_ajax() && isset( $_POST['action'] ) && 'nice_theme_import_demo_pack' === $_POST['action'];
	}

	/**
	 * Add a message to the AJAX response.
	 *
	 * @since 2.0
	 *
	 * @param string $message
	 */
	public function add_ajax_message( $message ) {
		if ( ! isset( $this->ajax_response['message'] ) ) {
			$this->ajax_response['message'] = $message;
		} else {
			$this->ajax_response['message'] .= ' ' . $message;
		}
	}

	/**
	 * Print AJAX response in JSON format.
	 *
	 * @since 2.0
	 */
	public function send_ajax_response() {
		$message = isset( $this->ajax_response['message'] ) ? $this->ajax_response['message'] : '';

		if ( $this->error_occurred ) {
			if ( empty( $message ) ) {
				$message = esc_html__( 'Unknown error.', 'nice-framework' );
			}

			$this->ajax_response['message'] = sprintf( '<strong>%s</strong> %s', esc_html__( 'ERROR:', 'nice-framework' ), $message ) . ' ' . sprintf( esc_html__( '%1$sReload this page%2$s.', 'nice-framework' ), sprintf( '<a href="%s">', nice_admin_page_get_link( 'demos' ) ), '</a>' );
		}

		$this->ajax_response['link'] = admin_url( 'index.php' );

		$this->ajax_response['more']              = intval( $this->did_something );
		$this->ajax_response['next_step']         = $this->get_next_import_step();
		$this->ajax_response['next_step_message'] = $this->get_next_import_step_message();

		$total_posts_number    = $this->get_total_posts_number();
		$imported_posts_number = $this->get_processed_posts_number();

		if ( ! is_null( $total_posts_number ) ) {
			$this->ajax_response['total_posts_number'] = intval( $total_posts_number );
		}

		if ( ! is_null( $imported_posts_number ) ) {
			$this->ajax_response['processed_posts_number'] = intval( $imported_posts_number );
		}

		// If the next step is the plugin installation/activation, enable the plugin installer.
		if ( 'prepare_plugins' === $this->ajax_response['next_step'] ) {
			$this->enable_install_plugins();
		}

		$this->ajax_response['error'] = intval( $this->error_occurred );

		$json = wp_json_encode( $this->ajax_response );

		echo $json; // WPCS: XSS Ok.

		die();
	}


	/** ==========================================================================
	 *  Import handling methods.
	 *  ======================================================================= */

	/**
	 * Obtain import context.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function doing_import() {
		return $this->doing_ajax() && 'nice_theme_demo_pack_import' === current_filter();
	}

	/**
	 * Add import status.
	 *
	 * @since 2.0
	 */
	private function add_import_status_hooks() {
		remove_all_filters( 'nice_theme_demo_pack_import_status' );

		add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_prepare_plugins' ) );

		if ( $this->do_site_reset() ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_reset_site' ) );
		}

		if ( $this->is_external_url() ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_download_files' ) );
		}

		add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_wp_content' ) );

		add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_wp_menus' ) );

		add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_widgets' ) );

		add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_theme_options' ) );

		add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_wp_options' ) );

		if ( $this->is_plugin_used( 'nice-infoboxes' ) ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_nice_infoboxes' ) );
		}

		if ( $this->is_plugin_used( 'nice-knowledge-base' ) ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_nice_knowledge_base' ) );
		}

		if ( $this->is_plugin_used( 'nice-portfolio' ) ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_nice_portfolio' ) );
		}

		if ( $this->is_plugin_used( 'nice-restaurant' ) ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_nice_restaurant' ) );
		}

		if ( $this->is_plugin_used( 'nice-team' ) ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_nice_team' ) );
		}

		if ( $this->is_plugin_used( 'nice-testimonials' ) ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_nice_testimonials' ) );
		}

		if ( $this->is_plugin_used( 'nice-likes' ) ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_nice_likes' ) );
		}

		if ( $this->is_plugin_used( 'woocommerce' ) ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_woocommerce' ) );
		}

		if ( $this->is_plugin_used( 'revslider' ) ) {
			add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_revslider' ) );
		}

		add_filter( 'nice_theme_demo_pack_import_status', array( $this, 'import_status_wp_remove_transients' ) );
	}

	/**
	 * Add importing messages.
	 *
	 * @since 2.0
	 */
	private function add_importing_messages_hooks() {
		remove_all_filters( 'nice_theme_demo_pack_importing_messages' );

		add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_prepare_plugins' ) );

		if ( $this->do_site_reset() ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_reset_site' ) );
		}

		if ( $this->is_external_url() ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_download_files' ) );
		}

		add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_wp_content' ) );

		add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_wp_menus' ) );

		add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_widgets' ) );

		add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_theme_options' ) );

		add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_wp_options' ) );

		if ( $this->is_plugin_used( 'nice-infoboxes' ) ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_nice_infoboxes' ) );
		}

		if ( $this->is_plugin_used( 'nice-knowledge-base' ) ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_nice_knowledge_base' ) );
		}

		if ( $this->is_plugin_used( 'nice-portfolio' ) ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_nice_portfolio' ) );
		}

		if ( $this->is_plugin_used( 'nice-restaurant' ) ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_nice_restaurant' ) );
		}

		if ( $this->is_plugin_used( 'nice-team' ) ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_nice_team' ) );
		}

		if ( $this->is_plugin_used( 'nice-testimonials' ) ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_nice_testimonials' ) );
		}

		if ( $this->is_plugin_used( 'nice-likes' ) ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_nice_likes' ) );
		}

		if ( $this->is_plugin_used( 'woocommerce' ) ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_woocommerce' ) );
		}

		if ( $this->is_plugin_used( 'revslider' ) ) {
			add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_revslider' ) );
		}

		add_filter( 'nice_theme_demo_pack_importing_messages', array( $this, 'importing_message_wp_remove_transients' ) );
	}

	/**
	 * Add import processes.
	 *
	 * @since 2.0
	 */
	private function add_import_hooks() {
		remove_all_actions( 'nice_theme_demo_pack_import' );

		// Return early if we're not in an AJAX context.
		if ( ! $this->doing_ajax() ) {
			return;
		}

		if ( $this->do_obtain_status() ) {
			add_action( 'nice_theme_demo_pack_import', array( $this, 'obtain_status' ) );
		}

		add_action( 'nice_theme_demo_pack_import', array( $this, 'prepare_plugins' ) );

		if ( $this->do_site_reset() ) {
			add_action( 'nice_theme_demo_pack_import', array( $this, 'reset_site' ) );
		}

		if ( $this->is_external_url() ) {
			add_action( 'nice_theme_demo_pack_import', array( $this, 'download_files' ) );
		}

		add_action( 'nice_theme_demo_pack_import', array( $this, 'import_wp_content' ) );

		add_action( 'nice_theme_demo_pack_import', array( $this, 'import_wp_menus' ) );

		add_action( 'nice_theme_demo_pack_import', array( $this, 'import_theme_options' ) );

		add_action( 'nice_theme_demo_pack_import', array( $this, 'import_widgets' ) );

		add_action( 'nice_theme_demo_pack_import', array( $this, 'import_wp_options' ) );

		if ( $this->is_plugin_used( 'nice-infoboxes' ) ) {
			add_filter( 'nice_theme_demo_pack_import', array( $this, 'import_nice_infoboxes' ) );
		}

		if ( $this->is_plugin_used( 'nice-knowledge-base' ) ) {
			add_filter( 'nice_theme_demo_pack_import', array( $this, 'import_nice_knowledge_base' ) );
		}

		if ( $this->is_plugin_used( 'nice-portfolio' ) ) {
			add_filter( 'nice_theme_demo_pack_import', array( $this, 'import_nice_portfolio' ) );
		}

		if ( $this->is_plugin_used( 'nice-restaurant' ) ) {
			add_filter( 'nice_theme_demo_pack_import', array( $this, 'import_nice_restaurant' ) );
		}

		if ( $this->is_plugin_used( 'nice-team' ) ) {
			add_filter( 'nice_theme_demo_pack_import', array( $this, 'import_nice_team' ) );
		}

		if ( $this->is_plugin_used( 'nice-testimonials' ) ) {
			add_filter( 'nice_theme_demo_pack_import', array( $this, 'import_nice_testimonials' ) );
		}

		if ( $this->is_plugin_used( 'nice-likes' ) ) {
			add_filter( 'nice_theme_demo_pack_import', array( $this, 'import_nice_likes' ) );
		}

		if ( $this->is_plugin_used( 'woocommerce' ) ) {
			add_filter( 'nice_theme_demo_pack_import', array( $this, 'import_woocommerce' ) );
		}

		if ( $this->is_plugin_used( 'revslider' ) ) {
			add_filter( 'nice_theme_demo_pack_import', array( $this, 'import_revslider' ) );
		}

		add_filter( 'nice_theme_demo_pack_import', array( $this, 'remove_transients' ) );
	}

	/**
	 * Trigger an import of the current demo pack.
	 *
	 * @since 2.0
	 */
	public function import() {
		/**
		 * Return early if we're not in an AJAX context, or if the nonce does not validate.
		 */
		if ( ! $this->doing_ajax() || ! check_ajax_referer( 'play-nice', 'nice_demo_import_nonce' ) ) {
			return;
		}

		/**
		 * Abort the request if the demo pack can't be installed.
		 */
		if ( ! $this->can_install() ) {
			$this->set_error_occurred();
			$this->add_ajax_message( esc_html__( 'The minimum requirements aren\'t met. This demo pack can\'t be installed.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		/**
		 * Add import hooks.
		 */
		$this->add_import_hooks();

		/**
		 * Initialize import-related properties.
		 */
		$this->get_import_status();
		$time = isset( $_SERVER['REQUEST_TIME'] ) ? wp_unslash( $_SERVER['REQUEST_TIME'] ) : 0;
		$this->execution_time = intval( $time );

		/**
		 * @hook nice_theme_demo_pack_do_import
		 *
		 * Hook here if you want to disable the demo pack import process.
		 *
		 * @since 2.0
		 */
		if ( apply_filters( 'nice_theme_demo_pack_do_import', true, $this ) ) {
			/**
			 * Initialize WordPress' file system handler.
			 *
			 * @var WP_Filesystem_Base $wp_filesystem
			 */
			WP_Filesystem();
			global $wp_filesystem;
			$this->wp_filesystem = $wp_filesystem;

			// If necessary, create the local uploads directory.
			if ( ! $this->wp_filesystem->exists( $this->get_uploads_path() ) ) {
				$result = $this->wp_filesystem->mkdir( $this->get_uploads_path() );

				// Abort the request if the local uploads directory couldn't be created.
				if ( ! $result ) {
					$this->set_error_occurred();
					$this->add_ajax_message( esc_html__( 'The directory for the demo packs couldn\'t be created.', 'nice-framework' ) );
					$this->send_ajax_response();
				}
			}

			/**
			 * @hook nice_theme_demo_pack_import
			 *
			 * All import processes should be hooked here.
			 *
			 * @since 2.0
			 */
			do_action( 'nice_theme_demo_pack_import', $this );
		}

		/**
		 * Send final response.
		 */
		$this->add_ajax_message( esc_html__( 'Done!', 'nice-framework' ) );
		$this->add_ajax_message( ' ' . sprintf( esc_html__( '%1$sView your site%4$s, %2$sgo to the Dashboard%4$s, or just %3$sreload this page%4$s.', 'nice-framework' ), sprintf( '<a href="%s" target="_blank">', get_bloginfo( 'siteurl' ) ), sprintf( '<a href="%s">', admin_url( 'index.php' ) ), sprintf( '<a href="%s">', nice_admin_page_get_link( 'demos' ) ), '</a>' ) );
		$this->send_ajax_response();
	}


	/** ==========================================================================
	 *  Trigger process and methods.
	 *  ======================================================================= */

	/**
	 * Obtain whether or not the status obtaining process should be executed.
	 *
	 * @since 2.0
	 */
	public function do_obtain_status() {
		check_ajax_referer( 'play-nice', 'nice_demo_import_nonce' );

		return ( isset( $_POST['obtain_status'] ) && true === nice_bool( wp_unslash( $_POST['obtain_status'] ) ) );
	}


	/**
	 * Obtain import status.
	 *
	 * @since 2.0
	 */
	public function obtain_status() {
		/**
		 * Reset and update import status.
		 */
		if ( $this->do_site_reset() ) {
			$this->reset_import_status();
			$this->save_import_status();
		}

		/**
		 * Send response.
		 */
		$this->set_did_something();
		$this->add_ajax_message( esc_html__( 'We\'re good to go!', 'nice-framework' ) );
		$this->send_ajax_response();
	}


	/** ==========================================================================
	 *  Plugin installer handling methods.
	 *  ======================================================================= */

	/**
	 * Obtain whether or not the plugin installer should be enabled.
	 *
	 * @since 2.0
	 */
	public function do_install_plugins() {
		/**
		 * @hook nice_demo_import_do_install_plugins
		 *
		 * Hook here if you want to force enabling or disabling the plugin installer.
		 *
		 * @since 2.0
		 */
		return apply_filters( 'nice_theme_demo_pack_plugin_installer', nice_bool( get_option( 'nice_demo_import_do_install_plugins', false ) ), $this );
	}

	/**
	 * Enable the plugin installer.
	 *
	 * @since 2.0
	 */
	public function enable_install_plugins() {
		update_option( 'nice_demo_import_do_install_plugins', true, false );
	}

	/**
	 * Disable the plugin installer.
	 *
	 * @since 2.0
	 */
	public function disable_install_plugins() {
		delete_option( 'nice_demo_import_do_install_plugins' );
	}


	/** ==========================================================================
	 *  Site reset handling methods.
	 *  ======================================================================= */

	/**
	 * Obtain whether or not the site reset process should be executed.
	 *
	 * @since 2.0
	 */
	public function do_site_reset() {
		/**
		 * @hook nice_theme_demo_pack_site_reset
		 *
		 * Hook here if you want to force the site reset process.
		 *
		 * @since 2.0
		 */
		return apply_filters( 'nice_theme_demo_pack_site_reset', nice_bool( get_option( 'nice_demo_import_do_site_reset', false ) ), $this );
	}

	/**
	 * Enable the site reset process.
	 *
	 * @since 2.0
	 */
	public function enable_site_reset() {
		update_option( 'nice_demo_import_do_site_reset', true, false );
	}

	/**
	 * Disable the site reset process.
	 *
	 * @since 2.0
	 */
	public function disable_site_reset() {
		delete_option( 'nice_demo_import_do_site_reset' );
	}


	/** ==========================================================================
	 *  Import processes.
	 *  ======================================================================= */

	/**
	 * Install and activate required plugins.
	 *
	 * @since 2.0
	 */
	public function prepare_plugins() {
		// Unique ID for this process.
		$process_id = 'prepare_plugins';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		// Abort the request if the plugin installer is not available.
		if ( ! ( class_exists( 'Nice_TGM_Plugin_Activation' ) && class_exists( 'Nice_TGM_Plugin' ) && class_exists( 'TGMPA_Bulk_Installer' ) && class_exists( 'TGMPA_Bulk_Installer_Skin' ) ) ) {
			$this->set_error_occurred();
			$this->add_ajax_message( esc_html__( 'The plugin installer couldn\'t be loaded.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		// Initialize a flag to detect if at least one plugin is installed or activated.
		$handled_plugins = false;

		// Don't print the process output.
		add_filter( 'nice_tgmpa_bulk_installer_skin_print_output', '__return_false' );
		add_filter( 'nice_tgmpa_print_bulk_output', '__return_false' );

		// Capture the output, just in case.
		ob_start();

		// Deactivate WooCommerce's after-activation redirection.
		if ( $this->is_plugin_used( 'woocommerce' ) ) {
			add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
		}

		/**
		 * Install missing plugins.
		 */
		if ( $this->have_missing_plugins() ) {
			$tgm_plugins = array();

			foreach ( $this->get_missing_plugins() as $plugin_path => $plugin_data ) {
				$tgm_plugin = Nice_TGM_Plugin::obtain( $plugin_data['slug'], $this );

				if ( ! is_wp_error( $tgm_plugin ) ) {
					$tgm_plugins[ $tgm_plugin->get_slug() ] = $tgm_plugin;
				}
			}

			if ( ! empty( $tgm_plugins ) ) {
				// Do the installation.
				Nice_TGM_Plugin_Activation::$instance->process_bulk_install( $tgm_plugins );

				// Refresh plugins data.
				Nice_TGM_Plugin_Activation::$instance->refresh_plugins_data();
				$this->reset_plugins();

				// Set the flag.
				$handled_plugins = true;
			}
		}

		/**
		 * Activate inactive plugins.
		 */
		if ( $this->have_inactive_plugins() ) {
			$tgm_plugins = array();

			foreach ( $this->get_inactive_plugins() as $plugin_path => $plugin_data ) {
				$tgm_plugin = Nice_TGM_Plugin::obtain( $plugin_data['slug'], $this );

				if ( ! is_wp_error( $tgm_plugin ) ) {
					$tgm_plugins[ $tgm_plugin->get_slug() ] = $tgm_plugin;
				}
			}

			if ( ! empty( $tgm_plugins ) ) {
				// Do the activation.
				Nice_TGM_Plugin_Activation::$instance->process_bulk_activate( $tgm_plugins );

				// Reset plugins data.
				Nice_TGM_Plugin_Activation::$instance->refresh_plugins_data();
				$this->reset_plugins();

				// Set the flag.
				$handled_plugins = true;
			}
		}

		// Remove WooCommerce's install notice.
		if ( $this->is_plugin_used( 'woocommerce' ) && $this->system_status->is_plugin_active( 'woocommerce' ) ) {
			WC_Admin_Notices::remove_notice( 'install' );
		}

		// Clean the output.
		ob_end_clean();

		// Enable printing process output again.
		remove_filter( 'nice_tgmpa_bulk_installer_skin_print_output', '__return_false' );
		remove_filter( 'nice_tgmpa_print_bulk_output', '__return_false' );

		/**
		 * Disable plugin installer for next AJAX call.
		 */
		$this->disable_install_plugins();

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( $handled_plugins ?esc_html__( 'All the required plugins were installed and activated successfully.', 'nice-framework' ) :esc_html__( 'No plugins needed to be installed or activated.', 'nice-framework' ) );
		$this->send_ajax_response();
	}

	/**
	 * Eliminate all previous content of the site.
	 *
	 * @since 2.0
	 */
	public function reset_site() {
		// Unique ID for this process.
		$process_id = 'reset_site';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Initialize WordPress' database handler.
		 *
		 * @var wpdb $wpdb
		 */
		global $wpdb;

		// Delete all posts.
		$table_name = $wpdb->posts;
		$t = "SHOW TABLES LIKE '$table_name';";
		if ( $wpdb->get_var( $t ) === $table_name ) { // WPCS: unprepared SQL ok.
			$d = "DELETE FROM `$table_name`;";
			$wpdb->query( $d );  // WPCS: unprepared SQL ok.
		}

		// Delete all post meta.
		$table_name = $wpdb->postmeta;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok.
			$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok.
		}

		// Delete all terms (except for Uncategorized).
		$table_name = $wpdb->terms;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE term_id > %d;", 1 ) ); // WPCS: unprepared SQL ok.
		}

		// Delete all term meta (except for Uncategorized).
		$table_name = $wpdb->termmeta;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE term_id > %d;", 1 ) ); // WPCS: unprepared SQL ok.
		}

		// Delete all term taxonomies (except for Uncategorized).
		$table_name = $wpdb->term_taxonomy;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE term_id > %d;", 1 ) ); // WPCS: unprepared SQL ok.
		}

		// Delete all term relationships.
		$table_name = $wpdb->term_relationships;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok.
			$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok.
		}

		// Delete all comments.
		$table_name = $wpdb->comments;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok.
			$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok.
		}

		// Delete all comment meta.
		$table_name = $wpdb->commentmeta;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok.
			$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok.
		}

		// Delete some options.
		$table_name = $wpdb->options;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok.
			// Delete all widgets.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE option_name LIKE %s", 'widget_%' ) ); // WPCS: unprepared SQL ok.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE option_name = %s", 'sidebars_widgets' ) ); // WPCS: unprepared SQL ok.

			// Delete NiceThemes' products data.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `$table_name` WHERE option_name LIKE %s AND option_name NOT IN ( %s, %s )", 'nice_%', 'nice_demo_import_do_site_reset', 'nice_' . $this->system_status->get_nice_theme_slug() . '_' . $this->get_slug() . '_demo_import_status' ) ); // WPCS: unprepared SQL ok.
		}

		// Delete WooCommerce's data.
		if ( $this->is_plugin_used( 'woocommerce' ) && $this->system_status->is_plugin_active( 'woocommerce' ) ) {
			// Delete WooCommerce's term data.
			$table_name = $wpdb->prefix . 'woocommerce_termmeta';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
				$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
			}
		}

		// Delete Slider Revolution's data.
		if ( $this->is_plugin_used( 'revslider' ) && $this->system_status->is_plugin_active( 'revslider' ) ) {
			// Delete Slider Revolution's CSS data.
			$table_name = $wpdb->prefix . 'revslider_css';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
				$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
			}

			// Delete Slider Revolution's layer animations.
			$table_name = $wpdb->prefix . 'revslider_layer_animations';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
				$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
			}

			// Delete Slider Revolution's navigations.
			$table_name = $wpdb->prefix . 'revslider_navigations';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
				$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
			}

			// Delete Slider Revolution's sliders.
			$table_name = $wpdb->prefix . 'revslider_sliders';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
				$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
			}

			// Delete Slider Revolution's slides.
			$table_name = $wpdb->prefix . 'revslider_slides';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
				$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
			}

			// Delete Slider Revolution's static slides.
			$table_name = $wpdb->prefix . 'revslider_static_slides';
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) === $table_name ) { // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
				$wpdb->query( "DELETE FROM `$table_name`;" );  // WPCS: unprepared SQL ok. // WPCS: unprepared SQL ok.
			}

			// Recreate default data.
			update_option( 'revslider_checktables', '0' );
			update_option( 'revslider_change_database', true );
			RevSliderFront::createDBTables();
		}

		// Obtain contents of uploads folder.
		$uploads_files = $this->wp_filesystem->dirlist( $this->system_status->get_wp_uploads_path() );

		// Exclude index file.
		if ( isset( $uploads_files['index.php'] ) ) {
			unset( $uploads_files['index.php'] );
		}

		// Exclude sites folder.
		if ( isset( $uploads_files['sites'] ) ) {
			unset( $uploads_files['sites'] );
		}

		// Exclude demo packs folder.
		if ( isset( $uploads_files['demo-packs'] ) ) {
			unset( $uploads_files['demo-packs'] );
		}

		// Exclude Visual Composer folder.
		if ( isset( $uploads_files['js_composer'] ) ) {
			unset( $uploads_files['js_composer'] );
		}

		// Exclude Slider Revolution folder.
		if ( isset( $uploads_files['revslider'] ) ) {
			unset( $uploads_files['revslider'] );

			// Obtain contents of Slider Revolution folder.
			$revslider_files = $this->wp_filesystem->dirlist( $this->system_status->get_wp_uploads_path() . '/revslider' );

			// Exclude templates folder.
			if ( isset( $revslider_files['templates'] ) ) {
				unset( $revslider_files['templates'] );
			}

			// Add the rest of the folders to the main array.
			foreach ( (array) $revslider_files as $revslider_file_key => $revslider_file ) {
				$revslider_file['name'] = 'revslider/' . $revslider_file['name'];

				$uploads_files[ 'revslider/' . $revslider_file_key ] = $revslider_file;
			}

		}

		/**
		 * Empty uploads folder.
		 */
		if ( ! empty( $uploads_files ) ) {
			foreach ( $uploads_files as $uploads_file ) {
				$result = $this->wp_filesystem->delete( $this->system_status->get_wp_uploads_path() . '/' . $uploads_file['name'], true );

				// Abort the request if the file couldn't be deleted.
				if ( ! $result ) {
					$this->set_error_occurred();
					$this->add_ajax_message( esc_html__( 'Some old upload files couldn\'t be removed.', 'nice-framework' ) );
					$this->send_ajax_response();
				}
			}
		}

		/**
		 * Disable site reset for next AJAX call.
		 */
		$this->disable_site_reset();

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( esc_html__( 'All website content was removed successfully.', 'nice-framework' ) );
		$this->send_ajax_response();
	}

	/**
	 * Download an extract the demo pack files from the external URL.
	 *
	 * @since 2.0
	 */
	public function download_files() {
		// Unique ID for this process.
		$process_id = 'download_files';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if we don't have an external URL.
		 */
		if ( ! $this->is_external_url() ) {
			return;
		}

		// Demo pack ZIP file path.
		$demo_file_path = $this->get_path() . '/demo-pack.zip';

		// Abort the request if `wp_remote_get()` is not available.
		if ( ! $this->system_status->is_wp_remote_get() ) {
			$this->set_error_occurred();
			$this->add_ajax_message( sprintf( esc_html__( 'The remote GET method (%s), which is needed to download the demo packs, is disabled. Please enable it and try again.', 'nice-framework' ), '<code>wp_remote_get</code>' ) );
			$this->send_ajax_response();
		}

		/**
		 * Obtain the ZIP file from the external URL.
		 */
		$demo_file = wp_remote_retrieve_body( wp_remote_get( $this->get_external_url(), array(
			'timeout' => 60,
		) ) );

		// Abort the request if the ZIP file couldn't be downloaded.
		if ( empty( $demo_file ) ) {
			$this->set_error_occurred();
			$this->add_ajax_message( esc_html__( 'The demo pack ZIP file couldn\'t be downloaded.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		/**
		 * Remove all demo pack files which may exist from previous executions of this process.
		 */
		if ( $this->wp_filesystem->is_dir( $this->get_path() ) ) {
			$result = $this->wp_filesystem->delete( $this->get_path(), true );

			// Abort the request if the local directory couldn't be created.
			if ( ! $result ) {
				$this->set_error_occurred();
				$this->add_ajax_message( esc_html__( 'Some old demo pack files couldn\'t be removed.', 'nice-framework' ) );
				$this->send_ajax_response();
			}
		}

		/**
		 * Create the local directory.
		 */
		$result = $this->wp_filesystem->mkdir( $this->get_path() );

		// Abort the request if the local directory couldn't be created.
		if ( ! $result ) {
			$this->set_error_occurred();
			$this->add_ajax_message( esc_html__( 'The directory for the demo pack files couldn\'t be created.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		/**
		 * Save ZIP file to the local path.
		 */
		$result = $this->wp_filesystem->put_contents( $demo_file_path, $demo_file );

		// Abort the request if the ZIP file couldn't be saved.
		if ( ! $result ) {
			$this->set_error_occurred();
			$this->add_ajax_message( esc_html__( 'The demo pack ZIP file couldn\'t be saved.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		/**
		 * Extract demo pack files from ZIP file.
		 */
		$result = unzip_file( $demo_file_path, $this->get_path() );

		// Abort the request if the files couldn't be extracted.
		if ( ! $result ) {
			$this->set_error_occurred();
			$this->add_ajax_message( esc_html__( 'The demo pack files couldn\'t be extracted.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		/**
		 * Delete the ZIP file.
		 */
		$this->wp_filesystem->delete( $demo_file_path );

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( esc_html__( 'The demo pack files were downloaded successfully.', 'nice-framework' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import WordPress' content.
	 *
	 * Feeds from a `wp-content.xml` file in the demo pack directory.
	 *
	 * @uses Nice_Theme_Demo_Pack::replace_wp_content_placeholders()
	 *
	 * @since 2.0
	 */
	public function import_wp_content() {
		// Unique ID for this process.
		$process_id = 'wp_content';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/wp-content.xml';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Setup WordPress Importer functionality.
		 *
		 * @since 2.0.8
		 */
		$this->setup_wp_importer();

		/**
		 * Update the number of processed posts both at the end and the start of the process.
		 */
		add_filter( 'import_start', array( $this, 'update_processed_posts_number' ) );
		add_filter( 'import_end', array( $this, 'update_processed_posts_number' ) );

		/**
		 * Prepare the array of posts right before importing.
		 */
		add_filter( 'wp_import_posts', array( $this, 'prepare_posts' ) );

		/**
		 * Avoid importing process from generating additional thumbnails.
		 */
		add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array', 10 );

		/**
		 * Allow requests from local IPs.
		 */
		add_filter( 'http_request_host_is_external', '__return_true' );

		// Capture the output.
		ob_start();

		/**
		 * Do the import.
		 */
		$wp_import = $this->get_wp_importer();
		$wp_import->fetch_attachments = true;
		$wp_import->import( $import_file );

		/**
		 * Stop allowing requests from local IPs.
		 */
		remove_filter( 'http_request_host_is_external', '__return_true' );

		// Clean the output.
		ob_end_clean();

		// Replace placeholders which may be in the content.
		if ( $this->all_posts_processed() ) {
			$this->replace_wp_content_placeholders();
		}

		/**
		 * Update import status.
		 */
		$this->set_did_something();

		if ( $this->all_posts_processed() ) {
			$this->set_single_import_status( $process_id, true );
		}

		$this->save_import_status();

		/**
		 * Send response.
		 */
		if ( $this->get_posts_bulk_limit() && ! $this->all_posts_processed() ) {
			$this->add_ajax_message( sprintf(
				esc_html__( '%s of %s content items were processed.', 'nice-framework' ),
				$this->get_processed_posts_number(),
				$this->get_total_posts_number()
			) );
		}

		if ( $this->all_posts_processed() ) {
			$this->process_orphans_maybe( $import_file ); // Process all possible missing posts.
			$this->add_ajax_message( esc_html__( 'WordPress content was imported successfully.', 'nice-framework' ) );
		}

		$this->send_ajax_response();
	}

	/**
	 * Process all posts that may have benn excluded from the main importing
	 * process while running a bulk.
	 *
	 * This can be a little expensive in terms of resource usage, specially
	 * if we're importing a demo with a lot of children. So we should try not
	 * having a lot of children in the first place.
	 *
	 * @since 2.0.8
	 *
	 * @param string $import_file
	 */
	private function process_orphans_maybe( $import_file ) {
		// Return early if we're not importing in bulks.
		if ( ! $this->get_posts_bulk_limit() ) {
			return;
		}

		remove_filter( 'import_start', array( $this, 'update_processed_posts_number' ) );
		remove_filter( 'import_end', array( $this, 'update_processed_posts_number' ) );
		remove_filter( 'wp_import_posts', array( $this, 'prepare_posts' ) );

		// Capture the output.
		ob_start();

		/**
		 * Do the import.
		 */
		$wp_import = $this->get_wp_importer();
		$wp_import->fetch_attachments = false;
		$wp_import->import( $import_file );

		// Clean the output.
		ob_end_clean();
	}

	/**
	 * Check if all posts to be imported from the current pack were already
	 * processed.
	 *
	 * @since 2.0.6
	 *
	 * @return bool
	 */
	private function all_posts_processed() {
		if ( ! $this->get_posts_bulk_limit() ) {
			return true;
		}

		return $this->get_total_posts_number() === $this->get_processed_posts_number();
	}

	/**
	 * Slice the array containing all posts to be imported, so we can break the
	 * import process in different steps.
	 *
	 * @since  2.0.6
	 *
	 * @param  array $posts
	 *
	 * @return array
	 */
	public function prepare_posts( $posts ) {
		$bulk_limit = $this->get_posts_bulk_limit();

		// Exclude menu items from import process in case there's a bulk limit,
		// so we can import them later without conflicts.
		if ( $bulk_limit ) {
			foreach ( $posts as $key => $post ) {
				if ( 'nav_menu_item' === $post['post_type'] ) {
					unset( $posts[ $key ] );
				}
			}
		}

		$this->set_total_posts_number( count( $posts ) );

		if ( $bulk_limit ) {
			$posts = array_slice( $posts, $this->get_processed_posts_number(), $bulk_limit );
		}

		return $posts;
	}

	/**
	 * Import WordPress' menus.
	 *
	 * Feeds from a `wp-menus.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_wp_menus() {
		// Unique ID for this process.
		$process_id = 'wp_menus';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		// Menus import file path.
		$import_file = $this->get_path() . '/wp-menus.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Obtain and parse menus.
		 */
		$import_menus = json_decode( $this->wp_filesystem->get_contents( $import_file ), true );

		/**
		 * Obtain the current menu locations.
		 */
		$nav_menu_locations = get_theme_mod( 'nav_menu_locations' );

		/**
		 * Add import menu locations.
		 */
		foreach ( $import_menus as $import_location => $import_menu ) {
			$menu = get_term_by( 'name', $import_menu, 'nav_menu' );

			if ( $menu ) {
				$nav_menu_locations[ $import_location ] = $menu->term_id;
			}
		}

		/**
		 * Save the new menu locations.
		 */
		set_theme_mod( 'nav_menu_locations', $nav_menu_locations );

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( esc_html__( 'WordPress menus were imported successfully.', 'nice-framework' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import widgets.
	 *
	 * Feeds from a `widgets.wie` file in the demo pack directory.
	 *
	 * Uses the Widget Importer & Exporter plugin to process the file.
	 * @see https://wordpress.org/plugins/widget-importer-exporter/
	 *
	 * @since 2.0
	 */
	public function import_widgets() {
		// Unique ID for this process.
		$process_id  = 'widgets';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/widgets.wie';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * If the Widget Importer & Exporter plugin is active, deactivate it and end the request.
		 */
		if ( $this->system_status->is_plugin_active( 'widget-importer-exporter/widget-importer-exporter.php' ) ) {
			deactivate_plugins( 'widget-importer-exporter/widget-importer-exporter.php', true );

			$this->set_did_something();
			$this->add_ajax_message( esc_html__( 'Widget Importer & Exporter plugin was deactivated to ensure compatibility.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		/**
		 * Load the bundled version of the Widget Importer & Exporter plugin.
		 */
		if ( ! function_exists( 'wie_import_data' ) ) {
			nice_loader( 'engine/admin/lib/widget-importer-exporter/' );
		}

		/**
		 * Obtain and parse widgets data.
		 */
		$widgets_data = json_decode( $this->wp_filesystem->get_contents( $import_file ) );

		/**
		 * Do the import.
		 */
		if ( ! empty( $widgets_data ) ) {
			/**
			 * @hook nice_register_sidebars
			 *
			 * Force registering sidebars here in case they haven't been registered before.
			 */
			do_action( 'nice_register_sidebars' );

			// Import widget data.
			wie_import_data( $widgets_data );
		}

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( empty( $widgets_data ) ?esc_html__( 'No widgets needed to be imported.', 'nice-framework' ) :esc_html__( 'The widgets were imported successfully.', 'nice-framework' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import theme options.
	 *
	 * Feeds from a `theme-options.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_theme_options() {
		// Unique ID for this process.
		$process_id = 'theme_options';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/theme-options.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Obtain and parse theme options.
		 */
		$theme_options = json_decode( $this->wp_filesystem->get_contents( $import_file ), true );

		// Abort the request if there are no options to export.
		if ( empty( $theme_options ) ) {
			$this->set_error_occurred();
			$this->add_ajax_message( esc_html__( 'There are no theme options to import.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		// Abort the request if the options file wasn't properly generated.
		if ( ! isset( $theme_options['nice-options-backup-validator'] ) ) {
			$this->set_error_occurred();
			$this->add_ajax_message( esc_html__( 'There are no valid theme options to import.', 'nice-framework' ) );
			$this->send_ajax_response();
		} else {
			unset( $theme_options['nice-options-backup-validator'] );
		}

		// Replace placeholders which may be in the content.
		$theme_options = $this->replace_theme_options_placeholders( $theme_options );

		/**
		 * Do the import.
		 *
		 * We replicate the functionality here, because it's far more simple than customizing the function.
		 *
		 * @see nice_options_backup_import()
		 */
		$nice_options = get_option( 'nice_options', array() );

		$has_updated = false;

		foreach ( (array) $theme_options as $key => $settings ) {
			$settings = maybe_unserialize( $settings );

			if ( get_option( $key ) !== $settings ) {
				update_option( $key, $settings );
			}

			if ( ! isset( $nice_options[ $key ] ) || $nice_options[ $key ] !== $settings ) {
				$nice_options[ $key ] = $settings;
				$has_updated = true;
			}
		}

		if ( true === $has_updated ) {
			update_option( 'nice_options', $nice_options );
		}

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( esc_html__( 'The theme options were imported successfully.', 'nice-framework' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import WordPress' options.
	 *
	 * Feeds from a `wp-options.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_wp_options() {
		// Unique ID for this process.
		$process_id = 'wp_options';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/wp-options.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Obtain and parse options.
		 */
		$wp_options = json_decode( $this->wp_filesystem->get_contents( $import_file ), true );

		/**
		 * Import the regular options.
		 */
		if ( isset( $wp_options['values'] ) ) {
			foreach ( $wp_options['values'] as $option_name => $option_value ) {
				update_option( $option_name, $option_value, true );
			}
		}

		/**
		 * Import the options which store page IDs.
		 */
		if ( isset( $wp_options['pages'] ) ) {
			foreach ( $wp_options['pages'] as $option_name => $page_title ) {
				$page_id = self::get_page_id( $page_title );

				if ( ! is_null( $page_id ) ) {
					update_option( $option_name, $page_id, true );
				}
			}
		}

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( esc_html__( 'WordPress options were imported successfully.', 'nice-framework' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import Nice Infoboxes data.
	 *
	 * Feeds from a `nice-infoboxes.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_nice_infoboxes() {
		// Unique ID for this process.
		$process_id = 'nice_infoboxes';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if the plugin is not active.
		 */
		if ( ! $this->system_status->is_plugin_active( 'nice-infoboxes' ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/nice-infoboxes.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Do the import.
		 */
		nice_infoboxes_import_settings( $import_file );

		// Remove the plugin settings notice.
		nice_infoboxes_admin_disable_update_notice();

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( sprintf( esc_html__( '%s data was imported successfully.', 'nice-framework' ), 'Nice Infoboxes' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import Nice Knowledge Base data.
	 *
	 * Feeds from a `nice-knowledge-base.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_nice_knowledge_base() {
		// Unique ID for this process.
		$process_id = 'nice_knowledge_base';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if the plugin is not active.
		 */
		if ( ! $this->system_status->is_plugin_active( 'nice-knowledge-base' ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/nice-infoboxes.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Do the import.
		 */
		nice_knowledgebase_import_settings( $import_file );

		// Remove the plugin settings notice.
		if ( function_exists( 'nice_knowledgebase_admin_disable_update_notice' ) ) {
			nice_knowledgebase_admin_disable_update_notice();
		}

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( sprintf( esc_html__( '%s data was imported successfully.', 'nice-framework' ), 'Nice Knowledge Base' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import Nice Portfolio data.
	 *
	 * Feeds from a `nice-portfolio.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_nice_portfolio() {
		// Unique ID for this process.
		$process_id = 'nice_portfolio';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if the plugin is not active.
		 */
		if ( ! $this->system_status->is_plugin_active( 'nice-portfolio' ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/nice-portfolio.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Do the import.
		 */
		nice_portfolio_import_settings( $import_file );

		// Remove the plugin settings notice.
		nice_portfolio_admin_disable_update_notice();

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( sprintf( esc_html__( '%s data was imported successfully.', 'nice-framework' ), 'Nice Portfolio' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import Nice Restaurant data.
	 *
	 * Feeds from a `nice-restaurant.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_nice_restaurant() {
		// Unique ID for this process.
		$process_id = 'nice_restaurant';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if the plugin is not active.
		 */
		if ( ! $this->system_status->is_plugin_active( 'nice-restaurant' ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/nice-restaurant.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Do the import.
		 */
		nice_restaurant_import_settings( $import_file );

		// Remove the plugin settings notice.
		nice_restaurant_admin_disable_update_notice();

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( sprintf( esc_html__( '%s data was imported successfully.', 'nice-framework' ), 'Nice Restaurant' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import Nice Team data.
	 *
	 * Feeds from a `nice-team.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_nice_team() {
		// Unique ID for this process.
		$process_id = 'nice_team';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if the plugin is not active.
		 */
		if ( ! $this->system_status->is_plugin_active( 'nice-team' ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/nice-team.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Do the import.
		 */
		nice_team_import_settings( $import_file );

		// Remove the plugin settings notice.
		nice_team_admin_disable_update_notice();

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( sprintf( esc_html__( '%s data was imported successfully.', 'nice-framework' ), 'Nice Team' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import Nice Testimonials data.
	 *
	 * Feeds from a `nice-testimonials.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_nice_testimonials() {
		// Unique ID for this process.
		$process_id = 'nice_testimonials';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if the plugin is not active.
		 */
		if ( ! $this->system_status->is_plugin_active( 'nice-testimonials' ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/nice-testimonials.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Do the import.
		 */
		nice_testimonials_import_settings( $import_file );

		// Remove the plugin settings notice.
		nice_testimonials_admin_disable_update_notice();

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( sprintf( esc_html__( '%s data was imported successfully.', 'nice-framework' ), 'Nice Testimonials' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import Nice Likes data.
	 *
	 * Feeds from a `nice-likes.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_nice_likes() {
		// Unique ID for this process.
		$process_id = 'nice_likes';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if the plugin is not active.
		 */
		if ( ! $this->system_status->is_plugin_active( 'nice-likes' ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/nice-likes.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Do the import.
		 */
		nice_likes_import_settings( $import_file );

		// Remove the plugin settings notice.
		nice_likes_admin_disable_update_notice();

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( sprintf( esc_html__( '%s data was imported successfully.', 'nice-framework' ), 'Nice Likes' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import WooCommerce data.
	 *
	 * Feeds from a `woocommerce.json` file in the demo pack directory.
	 *
	 * @since 2.0
	 */
	public function import_woocommerce() {
		// Unique ID for this process.
		$process_id = 'woocommerce';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if the plugin is not active.
		 */
		if ( ! $this->system_status->is_plugin_active( 'woocommerce' ) ) {
			return;
		}

		// Import file path.
		$import_file = $this->get_path() . '/woocommerce.json';

		/**
		 * Return early if the import file does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_file ) ) {
			return;
		}

		/**
		 * Obtain and parse options.
		 */
		$woocommerce_options = json_decode( $this->wp_filesystem->get_contents( $import_file ), true );

		/**
		 * Import the regular options.
		 */
		if ( isset( $woocommerce_options['values'] ) ) {
			foreach ( $woocommerce_options['values'] as $option_name => $option_value ) {
				update_option( $option_name, $option_value, true );
			}
		}

		/**
		 * Import the options which store page IDs.
		 */
		if ( isset( $woocommerce_options['pages'] ) ) {
			foreach ( $woocommerce_options['pages'] as $option_name => $page_title ) {
				$page_id = self::get_page_id( $page_title );

				if ( ! is_null( $page_id ) ) {
					update_option( $option_name, $page_id, true );
				}
			}
		}

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( sprintf( esc_html__( '%s data was imported successfully.', 'nice-framework' ), 'WooCommerce' ) );
		$this->send_ajax_response();
	}

	/**
	 * Import Revslider content.
	 *
	 * Feeds from a `revslider` folder in the demo pack directory, with a zip for each slider.
	 *
	 * @since 2.0
	 */
	public function import_revslider() {
		/**
		 * Avoid importing process from generating additional thumbnails for sliders.
		 */
		add_filter( 'intermediate_image_sizes_advanced', '__return_empty_array', 10 );

		// Unique ID for this process.
		$process_id = 'revslider';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		/**
		 * Return early if the plugin is not active.
		 */
		if ( ! $this->system_status->is_plugin_active( 'revslider' ) ) {
			return;
		}

		// Import folder path.
		$import_folder = $this->get_path() . '/revslider';

		/**
		 * Return early if the import folder does not exist.
		 */
		if ( ! $this->wp_filesystem->exists( $import_folder ) ) {
			return;
		}

		// Obtain names of files to import.
		$file_names = array_values( array_diff( scandir( $import_folder ), array( '.', '..' ) ) );

		/**
		 * Return early if there are no files to import.
		 */
		if ( empty( $file_names ) ) {
			return;
		}

		/**
		 * Initialize handler.
		 *
		 * @var RevSlider $revslider
		 */
		try {
			$revslider = new RevSlider();

		} catch ( Exception $e ) {
			// Abort the request if something went wrong.
			$this->set_error_occurred();
			$this->add_ajax_message( sprintf( esc_html__( "%s content couldn't be processed.", 'nice-framework' ), 'Slider Revolution' ) );
			$this->send_ajax_response();
		}

		// Obtain all sliders.
		$all_sliders = $revslider->getAllSliderAliases();

		// Do not import existent sliders (just in case).
		foreach ( $file_names as $key => $file_name ) {
			if ( in_array( pathinfo( $file_name, PATHINFO_FILENAME ), $all_sliders, true ) ) {
				unset( $file_names[ $key ] );
			}
		}

		// Loop through sliders.
		foreach ( $file_names as $file_name ) {
			$slider_key = pathinfo( $file_name, PATHINFO_FILENAME );

			/**
			 * @hook nice_theme_demo_pack_import_revslider_animations
			 *
			 * By default, animations are imported.
			 * Hook here to change that behavior for the current slider.
			 *
			 * @since 2.0
			 */
			$update_animations = apply_filters( 'nice_theme_demo_pack_import_revslider_animations', true, $slider_key );

			/**
			 * @hook nice_theme_demo_pack_import_revslider_static_styles
			 *
			 * By default, static styles are not imported (they often make the process run out of memory).
			 * Hook here to change that behavior for the current slider.
			 *
			 * @since 2.0
			 */
			$update_static_styles = apply_filters( 'nice_theme_demo_pack_import_revslider_static_styles', 'none', $slider_key );

			/**
			 * @hook nice_theme_demo_pack_import_revslider_navigation
			 *
			 * By default, navigations are imported.
			 * Hook here to change that behavior for the current slider.
			 *
			 * @since 2.0
			 */
			$update_navigation = apply_filters( 'nice_theme_demo_pack_import_revslider_navigation', true, $slider_key );

			// Define file path.
			$file_path  = $import_folder . '/' . $file_name;

			// Capture the output.
			ob_start();

			try {
				/**
				 * Import the slider.
				 */
				$response = $revslider->importSliderFromPost( $update_animations, $update_static_styles, $file_path, false, false, $update_navigation );

				if ( ! ( isset( $response['success'] ) && ( true === nice_bool( $response['success'] ) ) ) ) {
					// Abort the request if something went wrong.
					$this->set_error_occurred();
					$this->add_ajax_message( sprintf( esc_html__( "%s content couldn't be imported.", 'nice-framework' ), 'Slider Revolution' ) );
					$this->send_ajax_response();
				}

			} catch ( Exception $e ) {
				// Abort the request if something went wrong.
				$this->set_error_occurred();
				$this->add_ajax_message( sprintf( esc_html__( "%s content couldn't be imported.", 'nice-framework' ), 'Slider Revolution' ) );
				$this->send_ajax_response();
			}

			// Clean the output.
			ob_end_clean();

			/**
			 * Send an early response to force this process to be executed again.
			 * Since RS importer it's a memory-eating process, we don't want to import more than one slider per request.
			 */
			$remaining_sliders = ( count( $file_names ) - 1 );
			$ajax_message  = sprintf( esc_html__( '"%s" slider was imported successfully.', 'nice-framework' ), $slider_key );
			$ajax_message .= ' ' . ( ( 0 < $remaining_sliders ) ? sprintf( _n( '%s more slider remaining.', '%s more sliders remaining.', $remaining_sliders, 'nice-framework' ), $remaining_sliders ) : esc_html__( 'Global settings remaining.', 'nice-framework' ) );
			$this->set_did_something();
			$this->add_ajax_message( $ajax_message );
			$this->send_ajax_response();
		}

		/**
		 * Initialize WordPress' database handler.
		 *
		 * @var wpdb $wpdb
		 */
		global $wpdb;

		// Obtain all slides.
		$slides_data = $wpdb->get_results( "SELECT id, params, layers FROM `{$wpdb->prefix}revslider_slides`", ARRAY_A );

		if ( ! empty( $slides_data ) ) {
			// Obtain Slider Revolution uploads URL.
			$revslider_uploads_url = untrailingslashit( $this->system_status->get_wp_uploads_url() ) . '/revslider';

			// Obtain host from Slider Revolution uploads URL.
			$revslider_uploads_url_parts = parse_url( $revslider_uploads_url );

			// Obtain pattern to find Slider Revolution uploads URL (correct or not) in strings.
			$pattern = '/(' . $revslider_uploads_url_parts['scheme'] . ':\/\/' . $revslider_uploads_url_parts['host'] . '\/[^"]*?\/revslider\/)/';

			// Loop through slides.
			foreach ( $slides_data as $slide_data ) {
				// Obtain current slide parameters.
				$slide_params  = json_decode( $slide_data['params'], true );
				$update_params = false;

				// Process current slide parameters.
				if ( ! empty( $slide_params ) ) {
					foreach ( $slide_params as $slide_param => $slide_param_value ) {
						if ( 1 === preg_match( $pattern, $slide_param_value, $revslider_uploads_url_matches ) ) {
							$slide_params[ $slide_param ] = str_replace( untrailingslashit( $revslider_uploads_url_matches[0] ), $revslider_uploads_url, $slide_param_value );
						}
					}

					$slide_params  = wp_json_encode( $slide_params );
					$update_params = ( $slide_params !== $slide_data['params'] );
				}

				// Obtain current slide layers.
				$slide_layers  = json_decode( $slide_data['layers'], true );
				$update_layers = false;

				// Process current slide layers.
				if ( ! empty( $slide_layers ) ) {
					foreach ( $slide_layers as $slide_layer_key => $slide_layer_params ) {
						if ( ! empty( $slide_layer_params ) ) {
							foreach ( $slide_layer_params as $slide_layer_param => $slide_layer_param_value ) {
								if ( 1 === preg_match( $pattern, $slide_layer_param_value, $revslider_uploads_url_matches ) ) {
									$slide_layers[ $slide_layer_key ][ $slide_layer_param ] = str_replace( untrailingslashit( $revslider_uploads_url_matches[0] ), $revslider_uploads_url, $slide_layer_param_value );
								}
							}
						}
					}

					$slide_layers  = wp_json_encode( $slide_layers );
					$update_layers = ( $slide_layers !== $slide_data['layers'] );
				}

				// Update current slide.
				if ( $update_params || $update_layers ) {
					$fields = array();
					$format = array();

					if ( $update_params && ! empty( $slide_params ) ) {
						$fields['params'] = $slide_params;
						$format[]         = '%s';
					}

					if ( $update_layers && ! empty( $slide_layers ) ) {
						$fields['layers'] = $slide_layers;
						$format[]         = '%s';
					}

					// Update the current slide in the database.
					$result = $wpdb->update( $wpdb->prefix . 'revslider_slides', $fields, array( 'id' => $slide_data['id'] ), $format, array( '%d' ) );

					if ( false === $result ) {
						// Abort the request if something went wrong.
						$this->set_error_occurred();
						$this->add_ajax_message( sprintf( esc_html__( '%s content couldn\'t be updated in the database.', 'nice-framework' ), 'Slider Revolution' ) );
						$this->send_ajax_response();
					}
				}
			}
		}

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( sprintf( esc_html__( '%s content was imported successfully.', 'nice-framework' ), 'Slider Revolution' ) );
		$this->send_ajax_response();

		/**
		 * Restore thumbnail generation to its default status.
		 */
		remove_filter( 'intermediate_image_sizes_advanced', '__return_empty_array', 10 );
	}

	/**
	 * Remove transients from previous theme setups.
	 *
	 * @since 2.0
	 */
	public function remove_transients() {
		$process_id = 'wp_remove_transients';

		/**
		 * Return early if we're not in an import context, or if the process has already been executed.
		 */
		if ( ! ( $this->doing_import() && ! $this->get_single_import_status( $process_id ) ) ) {
			return;
		}

		$transients = array(
			'nice_images_width',
			'nice_images_height',
		);

		/**
		 * @hook nice_theme_demo_pack_remove_transients
		 *
		 * The list of transients to be removed during the import process is
		 * defined here.
		 */
		$transients = apply_filters( 'nice_theme_demo_pack_remove_transients', $transients );

		if ( empty( $transients ) || ! is_array( $transients ) ) {
			$this->set_did_something();
			$this->add_ajax_message( esc_html__( 'No transients needed to be removed.', 'nice-framework' ) );
			$this->send_ajax_response();
		}

		foreach ( $transients as $transient ) {
			delete_transient( $transient );
		}

		/**
		 * Update import status.
		 */
		$this->set_did_something();
		$this->set_single_import_status( $process_id, true );
		$this->save_import_status();

		/**
		 * Send response.
		 */
		$this->add_ajax_message( esc_html__( 'Transients were removed successfully.', 'nice-framework' ) );
		$this->send_ajax_response();
	}


	/** ==========================================================================
	 *  Import status.
	 *  ======================================================================= */

	/**
	 * Import status flag for plugins preparing.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_prepare_plugins( $import_status ) {
		// Add unique ID for this process.
		$import_status['prepare_plugins'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for site reset.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_reset_site( $import_status ) {
		// Add unique ID for this process.
		$import_status['reset_site'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for demo pack files download.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_download_files( $import_status ) {
		// Add unique ID for this process.
		$import_status['download_files'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for WordPress' content.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_wp_content( $import_status ) {
		// Add unique ID for this process.
		$import_status['wp_content'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for WordPress' menus.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_wp_menus( $import_status ) {
		// Add unique ID for this process.
		$import_status['wp_menus'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for widgets.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_widgets( $import_status ) {
		// Add unique ID for this process.
		$import_status['widgets'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for theme options.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_theme_options( $import_status ) {
		// Add unique ID for this process.
		$import_status['theme_options'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for WordPress' options.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_wp_options( $import_status ) {
		// Add unique ID for this process.
		$import_status['wp_options'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for Nice Infoboxes data.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_nice_infoboxes( $import_status ) {
		// Add unique ID for this process.
		$import_status['nice_infoboxes'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for Nice Knowledge Base data.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_nice_knowledge_base( $import_status ) {
		// Add unique ID for this process.
		$import_status['nice_knowledge_base'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for Nice Portfolio data.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_nice_portfolio( $import_status ) {
		// Add unique ID for this process.
		$import_status['nice_portfolio'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for Nice Restaurant data.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_nice_restaurant( $import_status ) {
		// Add unique ID for this process.
		$import_status['nice_restaurant'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for Nice Team data.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_nice_team( $import_status ) {
		// Add unique ID for this process.
		$import_status['nice_team'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for Nice Testimonials data.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_nice_testimonials( $import_status ) {
		// Add unique ID for this process.
		$import_status['nice_testimonials'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for Nice Likes data.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_nice_likes( $import_status ) {
		// Add unique ID for this process.
		$import_status['nice_likes'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for WooCommerce data.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_woocommerce( $import_status ) {
		// Add unique ID for this process.
		$import_status['woocommerce'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for Slider Revolution data.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_revslider( $import_status ) {
		// Add unique ID for this process.
		$import_status['revslider'] = false;

		return $import_status;
	}

	/**
	 * Import status flag for transients that need to be removed.
	 *
	 * @since 2.0
	 *
	 * @param array $import_status
	 *
	 * @return array
	 */
	public function import_status_wp_remove_transients( $import_status ) {
		// Add unique ID for this process.
		$import_status['wp_remove_transients'] = false;

		return $import_status;
	}


	/** ==========================================================================
	 *  Importing messages.
	 *  ======================================================================= */

	/**
	 * Importing message for site reset.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_reset_site( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['reset_site'] = esc_html__( 'Removing all website content...', 'nice-framework' );

		return $importing_messages;
	}

	/**
	 * Importing message for plugins preparing.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_prepare_plugins( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['prepare_plugins'] = esc_html__( 'Installing and activating required plugins...', 'nice-framework' );

		return $importing_messages;
	}

	/**
	 * Importing message for demo pack files download.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_download_files( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['download_files'] = esc_html__( 'Downloading demo pack files...', 'nice-framework' );

		return $importing_messages;
	}

	/**
	 * Importing message for WordPress' content.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_wp_content( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['wp_content'] = esc_html__( 'Processing WordPress content...', 'nice-framework' );

		return $importing_messages;
	}

	/**
	 * Importing message for WordPress' menus.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_wp_menus( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['wp_menus'] = esc_html__( 'Processing WordPress menus...', 'nice-framework' );

		return $importing_messages;
	}

	/**
	 * Importing message for widgets.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_widgets( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['widgets'] = esc_html__( 'Processing WordPress widgets...', 'nice-framework' );

		return $importing_messages;
	}

	/**
	 * Importing message for theme options.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_theme_options( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['theme_options'] = esc_html__( 'Processing theme options...', 'nice-framework' );

		return $importing_messages;
	}

	/**
	 * Importing message for WordPress' options.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_wp_options( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['wp_options'] = esc_html__( 'Processing WordPress options...', 'nice-framework' );

		return $importing_messages;
	}

	/**
	 * Importing message for Nice Infoboxes data.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_nice_infoboxes( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['nice_infoboxes'] = sprintf( esc_html__( 'Processing %s data...', 'nice-framework' ), 'Nice Infoboxes' );

		return $importing_messages;
	}

	/**
	 * Importing message for Nice Knowledge Base data.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_nice_knowledge_base( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['nice_knowledge_base'] = sprintf( esc_html__( 'Processing %s data...', 'nice-framework' ), 'Nice Knowledge Base' );

		return $importing_messages;
	}

	/**
	 * Importing message for Nice Portfolio data.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_nice_portfolio( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['nice_portfolio'] = sprintf( esc_html__( 'Processing %s data...', 'nice-framework' ), 'Nice Portfolio' );

		return $importing_messages;
	}

	/**
	 * Importing message for Nice Restaurant data.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_nice_restaurant( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['nice_restaurant'] = sprintf( esc_html__( 'Processing %s data...', 'nice-framework' ), 'Nice Restaurant' );

		return $importing_messages;
	}

	/**
	 * Importing message for Nice Team data.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_nice_team( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['nice_team'] = sprintf( esc_html__( 'Processing %s data...', 'nice-framework' ), 'Nice Team' );

		return $importing_messages;
	}

	/**
	 * Importing message for Nice Testimonials data.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_nice_testimonials( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['nice_testimonials'] = sprintf( esc_html__( 'Processing %s data...', 'nice-framework' ), 'Nice Testimonials' );

		return $importing_messages;
	}

	/**
	 * Importing message for Nice Likes data.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_nice_likes( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['nice_likes'] = sprintf( esc_html__( 'Processing %s data...', 'nice-framework' ), 'Nice Likes' );

		return $importing_messages;
	}

	/**
	 * Importing message for WooCommerce data.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_woocommerce( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['woocommerce'] = sprintf( esc_html__( 'Processing %s data...', 'nice-framework' ), 'WooCommerce' );

		return $importing_messages;
	}

	/**
	 * Importing message for Slider Revolution data.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_revslider( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['revslider'] = sprintf( esc_html__( 'Processing %s content...', 'nice-framework' ), 'Slider Revolution' );

		return $importing_messages;
	}

	/**
	 * Importing message for WordPress' transients.
	 *
	 * @since 2.0
	 *
	 * @param array $importing_messages
	 *
	 * @return array
	 */
	public function importing_message_wp_remove_transients( $importing_messages ) {
		// Add unique ID for this process.
		$importing_messages['wp_remove_transients'] = esc_html__( 'Removing old transients...', 'nice-framework' );

		return $importing_messages;
	}


	/** ==========================================================================
	 *  Auxiliary methods.
	 *  ======================================================================= */

	/**
	 * Replace placeholders with values after using the WordPress Importer plugin.
	 *
	 * Helper method for the WordPress' content import process.
	 * @see Nice_Theme_Demo_Pack::import_wp_content()
	 *
	 * @uses Nice_Theme_Demo_Pack::preg_replace_replacement()
	 *
	 * @since 1.2.0
	 */
	private function replace_wp_content_placeholders() {
		// Define placeholder patterns.
		$placeholder_like    = '%{nice_import_field %}%';
		$placeholder_pattern = '/({nice_import_field )([^}]*?)(})/';

		// Initialize WordPress' database handler.
		/** @var wpdb $wpdb */
		global $wpdb;

		// Obtain posts with placeholders.
		$posts_data = $wpdb->get_results( $wpdb->prepare( "
			SELECT ID, post_content FROM $wpdb->posts WHERE post_content LIKE %s",
			$placeholder_like
		), ARRAY_A );

		if ( ! empty( $posts_data ) ) {
			foreach ( $posts_data as $post_data ) {
				$post_id      = $post_data['ID'];
				$post_content = $post_data['post_content'];

				// Obtain placeholders in current post.
				preg_match_all( $placeholder_pattern, $post_content, $post_placeholders );

				if ( ! empty( $post_placeholders ) ) {

					// Separate attributes from placeholders.
					$all_attributes = $post_placeholders[2];
					$placeholders   = $post_placeholders[0];

					foreach ( $placeholders as $placeholder_key => $placeholder ) {
						// Obtain attributes of the current placeholder.
						$attributes = shortcode_parse_atts( $all_attributes[ $placeholder_key ] );

						// Obtain pattern string.
						$pattern = '/(' . preg_quote( $placeholder, '/' ) . ')/';

						// Obtain replacement string.
						$replacement = $this->preg_replace_replacement( $attributes );

						// Replace the string in the post content.
						$post_content = preg_replace( $pattern, $replacement, $post_content );
					}

					// Update the post.
					wp_update_post( array(
						'ID'           => $post_id,
						'post_content' => $post_content,
					) );
				}
			}
		}

		// Obtain postmeta fields with placeholders.
		$postmetas_data = $wpdb->get_results( $wpdb->prepare( "
			SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_value LIKE %s;",
			$placeholder_like
		), ARRAY_A );

		if ( ! empty( $postmetas_data ) ) {
			foreach ( $postmetas_data as $postmeta_data ) {
				$post_id      = $postmeta_data['post_id'];
				$meta_key     = $postmeta_data['meta_key'];
				$meta_value   = $postmeta_data['meta_value'];

				// Obtain placeholders in current post.
				preg_match_all( $placeholder_pattern, $meta_value, $postmeta_placeholders );

				if ( ! empty( $postmeta_placeholders ) ) {

					// Separate attributes from placeholders.
					$all_attributes = $postmeta_placeholders[2];
					$placeholders   = $postmeta_placeholders[0];

					foreach ( $placeholders as $placeholder_key => $placeholder ) {
						// Obtain attributes of the current placeholder.
						$attributes = shortcode_parse_atts( $all_attributes[ $placeholder_key ] );

						// Obtain pattern string.
						$pattern = '/(' . preg_quote( $placeholder, '/' ) . ')/';

						// Obtain replacement string.
						$replacement = $this->preg_replace_replacement( $attributes );

						// Replace the string in the meta value.
						$meta_value = preg_replace( $pattern, $replacement, $meta_value );
					}

					// Update the post.
					update_post_meta( $post_id, $meta_key, $meta_value );
				}
			}
		}
	}

	/**
	 * Replace placeholders with values after importing theme options.
	 *
	 * Helper method for the theme options import process.
	 * @see  Nice_Theme_Demo_Pack::import_theme_options()
	 *
	 * @uses Nice_Theme_Demo_Pack::preg_replace_replacement()
	 *
	 * @since 1.2.0
	 *
	 * @param array $theme_options
	 *
	 * @return null|array
	 */
	private function replace_theme_options_placeholders( array $theme_options ) {
		// Define placeholder patterns.
		$placeholder_pattern = '/({nice_import_field )([^}]*?)(})/';

		if ( empty( $theme_options ) ) {
			return null;
		}

		foreach ( $theme_options as $option_name => $option_value ) {
			$is_serialized = is_serialized( $option_value );

			if ( $is_serialized ) {
				/** @noinspection PhpUsageOfSilenceOperatorInspection */
				$option_value = @unserialize( $option_value );
			}

			// Obtain placeholders in current option.
			if ( ! is_array( $option_value ) && ! is_object( $option_value ) ) {
				preg_match_all( $placeholder_pattern, $option_value, $option_placeholders );
			}

			if ( ! empty( $option_placeholders ) ) {

				// Separate attributes from placeholders.
				$all_attributes = $option_placeholders[2];
				$placeholders   = $option_placeholders[0];

				foreach ( $placeholders as $placeholder_key => $placeholder ) {
					// Obtain attributes of the current placeholder.
					$attributes = shortcode_parse_atts( $all_attributes[ $placeholder_key ] );

					// Obtain pattern string.
					$pattern = '/(' . preg_quote( $placeholder, '/' ) . ')/';

					// Obtain replacement string.
					$replacement = $this->preg_replace_replacement( $attributes );

					// Replace the string in the option value.
					$option_value = preg_replace( $pattern, $replacement, $option_value );

					// Serialize the option value, if needed.
					if ( $is_serialized ) {
						/** @noinspection PhpUsageOfSilenceOperatorInspection */
						$option_value = @serialize( $option_value );
					}

					// Save option value.
					$theme_options[ $option_name ] = $option_value;
				}
			}
		}

		return $theme_options;
	}

	/**
	 * Replacement string for `preg_replace_callback()`.
	 *
	 * Helper method for the WordPress' content placeholders replace.
	 * @see Nice_Theme_Demo_Pack::replace_wp_content_placeholders()
	 *
	 * @uses Nice_Theme_Demo_Pack::preg_replace_url_replacement()
	 * @uses Nice_Theme_Demo_Pack::preg_replace_image_id_replacement()
	 * @uses Nice_Theme_Demo_Pack::preg_replace_post_id_replacement()
	 * @uses Nice_Theme_Demo_Pack::preg_replace_term_id_replacement()
	 * @uses Nice_Theme_Demo_Pack::preg_replace_user_id_replacement()
	 *
	 * @since 1.2.0
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function preg_replace_replacement( $attributes ) {
		$replacement = '';

		if ( isset( $attributes['type'] ) ) {
			switch ( $attributes['type'] ) {
				case 'url':
					$replacement = $this->preg_replace_url_replacement( $attributes );
					break;
				case 'image_id':
					$replacement = $this->preg_replace_image_id_replacement( $attributes );
					break;
				case 'post_id':
					$replacement = $this->preg_replace_post_id_replacement( $attributes );
					break;
				case 'term_id':
					$replacement = $this->preg_replace_term_id_replacement( $attributes );
					break;
				case 'user_id':
					$replacement = $this->preg_replace_user_id_replacement( $attributes );
					break;
			}
		}

		return $replacement;
	}

	/**
	 * URL type replacement string for `preg_replace_callback()`.
	 *
	 * Helper method for the WordPress' content placeholders replace.
	 * @see Nice_Theme_Demo_Pack::preg_replace_replacement()
	 *
	 * @since 1.2.0
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function preg_replace_url_replacement( $attributes ) {
		$replacement = '';

		if ( 'url' === $attributes['type'] && ! empty( $attributes['url'] ) ) {
			if ( 'home' === $attributes['url'] ) {
				static $home_url = null;

				if ( is_null( $home_url ) ) {
					$home_url = untrailingslashit( $this->system_status->get_home_url() );
				}

				$replacement = $home_url;

			} elseif ( 'uploads' === $attributes['url'] ) {
				static $uploads_url = null;

				if ( is_null( $uploads_url ) ) {
					$uploads_url = untrailingslashit( $this->system_status->get_wp_uploads_url() );
				}

				$replacement = $uploads_url;

			} elseif ( false !== strpos( $attributes['url'], 'replacement_image' ) ) {
				static $replacement_image_urls = array();

				if ( 'replacement_image' === $attributes['url'] ) {
					$replacement_context = 'default';
				} else {
					$replacement_context = str_replace( '_replacement_image', '', $attributes['url'] );
				}

				if ( ! isset( $replacement_image_urls[ $replacement_context ] ) ) {
					global $wpdb;
					$replacement_image_id  = intval( $wpdb->get_var( $wpdb->prepare( "
						SELECT P.ID FROM `$wpdb->postmeta` PM
						JOIN $wpdb->posts P ON PM.post_id = P.ID
						WHERE PM.meta_key = %s
						AND PM.meta_value = %d
						AND P.post_type = %s",
						'nice_' . $replacement_context . '_replacement_image',
						1,
						'attachment'
					) ) );
					$replacement_image_url = ( ( 0 < $replacement_image_id ) ) ? wp_get_attachment_url( $replacement_image_id ) : '';

					$replacement_image_urls[ $replacement_context ] = $replacement_image_url;
				}

				$replacement = $replacement_image_urls[ $replacement_context ];
			}

			if ( isset( $attributes['encoded'] ) && nice_bool( $attributes['encoded'] ) ) {
				$replacement = urlencode( $replacement );
			}
		}

		return $replacement;
	}

	/**
	 * Image type replacement string for `preg_replace_callback()`.
	 *
	 * Helper method for the WordPress' content placeholders replace.
	 * @see Nice_Theme_Demo_Pack::preg_replace_replacement()
	 *
	 * @since 1.2.0
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function preg_replace_image_id_replacement( $attributes ) {
		$replacement = 0;

		if ( 'image_id' === $attributes['type'] ) {
			if ( ! empty( $attributes['slug'] ) ) {
				static $image_slugs_ids = array();

				if ( ! isset( $image_slugs_ids[ $attributes['slug'] ] ) ) {
					$image_ids = get_posts( array(
						'post_type'      => 'attachment',
						'name'           => $attributes['slug'],
						'posts_per_page' => 1,
						'fields'         => 'ids',
					) );

					$image_slugs_ids[ $attributes['slug'] ] = ( ! empty( $image_ids ) ) ? intval( array_pop( $image_ids ) ) : 0;

				}

				$replacement = $image_slugs_ids[ $attributes['slug'] ];
			}
		}

		return $replacement;
	}

	/**
	 * Post type replacement string for `preg_replace_callback()`.
	 *
	 * Helper method for the WordPress' content placeholders replace.
	 * @see Nice_Theme_Demo_Pack::preg_replace_replacement()
	 *
	 * @since 1.2.0
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function preg_replace_post_id_replacement( $attributes ) {
		$replacement = 0;

		if ( 'post_id' === $attributes['type'] ) {
			if ( ! ( empty( $attributes['slug'] ) || empty( $attributes['post_type'] ) ) ) {
				static $post_slugs_ids = array();

				if ( ! isset( $post_slugs_ids[ $attributes['post_type'] ][ $attributes['slug'] ] ) ) {
					$post_ids = get_posts( array(
						'post_type'      => $attributes['post_type'],
						'name'           => $attributes['slug'],
						'posts_per_page' => 1,
						'fields'         => 'ids',
					) );

					$post_slugs_ids[ $attributes['post_type'] ][ $attributes['slug'] ] = ( ! empty( $post_ids ) ) ? intval( array_pop( $post_ids ) ) : 0;
				}

				$replacement = $post_slugs_ids[ $attributes['post_type'] ][ $attributes['slug'] ];
			}
		}

		return $replacement;
	}

	/**
	 * Term type replacement string for `preg_replace_callback()`.
	 *
	 * Helper method for the WordPress' content placeholders replace.
	 * @see Nice_Theme_Demo_Pack::preg_replace_replacement()
	 *
	 * @since 1.2.0
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function preg_replace_term_id_replacement( $attributes ) {
		$replacement = 0;

		if ( 'term_id' === $attributes['type'] ) {
			if ( ! ( empty( $attributes['slug'] ) || empty( $attributes['taxonomy'] ) ) ) {
				static $term_slugs_ids = array();

				if ( ! isset( $term_slugs_ids[ $attributes['taxonomy'] ][ $attributes['slug'] ] ) ) {
					$term = get_term_by( 'slug', $attributes['slug'], $attributes['taxonomy'] );

					$term_slugs_ids[ $attributes['taxonomy'] ][ $attributes['slug'] ] = ( ! empty( $term ) ) ? intval( $term->term_id ) : 0;
				}

				$replacement = $term_slugs_ids[ $attributes['taxonomy'] ][ $attributes['slug'] ];
			}
		}

		return $replacement;
	}

	/**
	 * User type replacement string for `preg_replace_callback()`.
	 *
	 * Helper method for the WordPress' content placeholders replace.
	 * @see Nice_Theme_Demo_Pack::preg_replace_replacement()
	 *
	 * @since 1.2.0
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function preg_replace_user_id_replacement( $attributes ) {
		$replacement = 0;

		if ( 'user_id' === $attributes['type'] ) {
			if ( ! empty( $attributes['username'] ) ) {
				static $user_names_ids = array();

				if ( ! isset( $user_names_ids[ $attributes['username'] ] ) ) {
					$user = get_user_by( 'login', $attributes['username'] );
					if ( empty( $user ) ) {
						$user = wp_get_current_user();
					}

					$user_names_ids[ $attributes['username'] ] = intval( $user->get( 'ID' ) );
				}

				$replacement = $user_names_ids[ $attributes['username'] ];
			}
		}

		return $replacement;
	}

	/**
	 * Obtain a plugin path from its slug.
	 *
	 * @since 1.0
	 *
	 * @param string $plugin_slug
	 *
	 * @return bool
	 */
	public function get_plugin_path_from_slug( $plugin_slug ) {
		$plugin_paths = array_keys( $this->get_plugins() );

		foreach ( $plugin_paths as $plugin_path ) {
			if ( preg_match( '|^' . $plugin_slug . '/|', $plugin_path ) ) {
				return $plugin_path;
			}
		}

		return $plugin_slug;
	}
}
endif;
