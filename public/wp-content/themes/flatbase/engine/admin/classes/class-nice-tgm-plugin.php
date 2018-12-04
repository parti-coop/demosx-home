<?php
/**
 * NiceThemes Framework Plugin Activation
 *
 * @package Nice_Framework
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if ( ! class_exists( 'Nice_TGM_Plugin' ) ) :
/**
 * Class Nice_TGM_Plugin
 *
 * Helper class to handle internal data of plugins managed with Nice TGMPA.
 *
 * @since 2.0
 */
class Nice_TGM_Plugin {

	/** ==========================================================================
	 *  Properties.
	 *  ======================================================================= */

	/**
	 * Plugin name.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $name = null;

	/**
	 * Plugin slug.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $slug = null;

	/**
	 * Plugin local installation path.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $path = null;

	/**
	 * Plugin description.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $description = null;

	/**
	 * Plugin image URL, or fallback if there is no image.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $image_url = null;

	/**
	 * Whether or not this plugin is external to the official repository.
	 *
	 * @since 2.0
	 *
	 * @var bool
	 */
	protected $external = null;

	/**
	 * Plugin external source URL.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $source_url = null;

	/**
	 * Plugin installed version.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $version = null;

	/**
	 * Plugin latest available version.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $new_version = null;

	/**
	 * Plugin version required by the theme.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $theme_required_version = null;

	/**
	 * Plugin version required by the demo (if passed).
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $demo_required_version = null;

	/**
	 * Plugin URL.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $url = null;

	/**
	 * Plugin author's name.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $author_name = null;

	/**
	 * Plugin author's URL.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $author_url = null;

	/**
	 * Whether or not this plugin is required by TGMPA.
	 *
	 * @since 2.0
	 *
	 * @var bool
	 */
	protected $required = null;

	/**
	 * Whether or not this plugin is recommended (not required) by TGMPA.
	 *
	 * @since 2.0
	 *
	 * @var bool
	 */
	protected $recommended = null;

	/**
	 * Whether or not this plugin is installed.
	 *
	 * @since 2.0
	 *
	 * @var bool
	 */
	protected $installed = null;

	/**
	 * Whether or not this plugin is active for the current site only.
	 *
	 * @since 2.0
	 *
	 * @var bool
	 */
	protected $active = null;

	/**
	 * Whether or not this plugin is active for the current network.
	 *
	 * @since 2.0
	 *
	 * @var bool
	 */
	protected $network_active = null;

	/**
	 * Whether or not this plugin is a must-use.
	 *
	 * @since 2.0
	 *
	 * @var bool
	 */
	protected $must_use = null;

	/**
	 * Plugin tags.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	protected $tags = null;

	/**
	 * Plugin available actions.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	protected $actions = null;

	/**
	 * Plugin enabled actions.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	protected $enabled_actions = null;

	/**
	 * Demo pack to check against (if passed).
	 *
	 * @since 2.0
	 *
	 * @var Nice_Theme_Demo_Pack
	 */
	protected $demo_pack = null;

	/**
	 * Current Nice TGMPA instance.
	 *
	 * @since 2.0
	 *
	 * @var Nice_TGM_Plugin_Activation
	 */
	protected $nice_tgmpa = null;

	/**
	 * System status handler.
	 *
	 * @since 2.0
	 *
	 * @var Nice_Admin_System_Status
	 */
	protected $system_status = null;


	/** ==========================================================================
	 *  Constructing methods.
	 *  ======================================================================= */

	/**
	 * Main constructor.
	 *
	 * @param string|array         $plugin_slug Plugin TGMPA slug.
	 * @param Nice_Theme_Demo_Pack $demo_pack   Demo pack to check against.
	 *
	 * @throws Exception
	 */
	public function __construct( $plugin_slug, $demo_pack = null ) {
		// Initialize System Status.
		$this->system_status = Nice_Admin_System_Status::obtain();

		// Obtain plugin data.
		$plugin_data = $this->system_status->get_plugin( $plugin_slug );

		// Validate plugin data.
		if ( empty( $plugin_data ) ) {
			throw new Exception( esc_html__( 'Invalid plugin.', 'nice-framework' ) );
		} elseif ( ! ( isset( $plugin_data['required'] ) && isset( $plugin_data['recommended'] ) ) ) {
			throw new Exception( esc_html__( 'Invalid plugin data.', 'nice-framework' ) );
		} elseif ( ! ( $plugin_data['required'] || $plugin_data['recommended'] ) ) {
			throw new Exception( esc_html__( 'Non-TGM plugin.', 'nice-framework' ) );
		}

		// Assign plugin data.
		$this->name           = $plugin_data['name'];
		$this->slug           = $plugin_data['slug'];
		$this->path           = $plugin_data['path'];
		$this->description    = $plugin_data['description'];
		$this->external       = $plugin_data['external'];
		$this->version        = $plugin_data['version'];
		$this->new_version    = $plugin_data['new_version'];
		$this->url            = $plugin_data['url'];
		$this->author_name    = $plugin_data['author_name'];
		$this->author_url     = $plugin_data['author_url'];
		$this->required       = $plugin_data['required'];
		$this->recommended    = $plugin_data['recommended'];
		$this->installed      = $plugin_data['installed'];
		$this->active         = $plugin_data['active'];
		$this->network_active = $plugin_data['network_active'];
		$this->must_use       = $plugin_data['must_use'];

		// Assign demo pack, if passed.
		if ( $demo_pack instanceof Nice_Theme_Demo_Pack ) {
			$this->demo_pack = $demo_pack;
		}

		// Assign current Nice TGMPA instance.
		$this->nice_tgmpa = Nice_TGM_Plugin_Activation::get_instance();
	}

	/**
	 * Obtain a Nice_TGM_Plugin object.
	 *
	 * New instances are saved to a static variable, so they can be retrieved
	 * later without needing to be reinitialized.
	 *
	 * @since 2.0
	 *
	 * @param string|array         $plugin_slug Plugin TGMPA slug.
	 * @param Nice_Theme_Demo_Pack $demo_pack   Demo pack to check against.
	 *
	 * @return WP_Error|Nice_TGM_Plugin
	 */
	public static function obtain( $plugin_slug, $demo_pack = null ) {
		static $tgm_plugins = array();

		try {
			$plugin = new self( $plugin_slug, $demo_pack );

			$tgm_plugins[ $plugin->get_slug() ] = $plugin;

		} catch ( Exception $e ) {
			return new WP_Error( 'invalid_plugin', $e->getMessage() );
		}

		return $tgm_plugins[ $plugin->get_slug() ];
	}


	/** ==========================================================================
	 *  Getters & Setters.
	 *  ======================================================================= */

	/**
	 * Obtain name.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
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
	 * Obtain local installation path.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Obtain description.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Obtain image URL.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_image_url() {
		if ( is_null( $this->image_url ) ) {
			$this->set_image_url();
		}

		return $this->image_url;
	}

	/**
	 * Set image URL.
	 *
	 * @since 2.0
	 */
	protected function set_image_url() {
		$image_url = NICE_TPL_DIR . '/engine/admin/assets/images/placeholder-plugin.png';

		if ( isset( $this->nice_tgmpa->plugins[ $this->get_slug() ]['image_url'] ) ) {
			$image_url = $this->nice_tgmpa->plugins[ $this->get_slug() ]['image_url'];
		}

		$this->image_url = $image_url;
	}

	/**
	 * Obtain whether or not this plugin is external.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function get_external() {
		return $this->external;
	}

	/**
	 * Obtain source URL.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_source_url() {
		if ( is_null( $this->source_url ) ) {
			$this->set_source_url();
		}

		return $this->source_url;
	}

	/**
	 * Set source URL.
	 *
	 * @since 2.0
	 */
	protected function set_source_url() {
		$this->source_url = $this->nice_tgmpa->get_download_url( $this->get_slug() );
	}

	/**
	 * Obtain installed version.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Obtain latest available version.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_new_version() {
		return $this->new_version;
	}

	/**
	 * Obtain theme required version.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_theme_required_version() {
		if ( is_null( $this->theme_required_version ) ) {
			$this->set_theme_required_version();
		}

		return $this->theme_required_version;
	}

	/**
	 * Set theme required version.
	 *
	 * @since 2.0
	 */
	protected function set_theme_required_version() {
		$theme_required_version = '';

		if ( isset( $this->nice_tgmpa->plugins[ $this->get_slug() ]['version'] ) ) {
			$theme_required_version = $this->nice_tgmpa->plugins[ $this->get_slug() ]['version'];
		}

		$this->theme_required_version = $theme_required_version;
	}

	/**
	 * Obtain demo required version.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_demo_required_version() {
		if ( is_null( $this->demo_required_version ) ) {
			$this->set_demo_required_version();
		}

		return $this->demo_required_version;
	}

	/**
	 * Set demo required version.
	 *
	 * @since 2.0
	 */
	protected function set_demo_required_version() {
		$demo_required_version = '';

		if ( $this->is_demo_required() ) {
			$demo_plugins = $this->demo_pack->get_plugins();

			if ( isset( $demo_plugins[ $this->get_path() ]['version'] ) ) {
				$demo_required_version = $demo_plugins[ $this->get_path() ]['version'];
			}
		}

		$this->demo_required_version = $demo_required_version;
	}

	/**
	 * Obtain required version.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_required_version() {
		if ( $this->get_demo_required_version() && version_compare( $this->get_theme_required_version(), $this->get_demo_required_version(), '<' ) ) {
			return $this->get_demo_required_version();
		}

		return $this->get_theme_required_version();
	}

	/**
	 * Obtain URL.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * Obtain author name.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_author_name() {
		return $this->author_name;
	}

	/**
	 * Obtain author URL.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_author_url() {
		return $this->author_url;
	}

	/**
	 * Obtain whether or not this plugin is required.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function get_required() {
		return $this->required;
	}

	/**
	 * Obtain whether or not this plugin is recommended.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function get_recommended() {
		return $this->recommended;
	}

	/**
	 * Obtain whether or not this plugin is installed.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function get_installed() {
		return $this->installed;
	}

	/**
	 * Obtain whether or not this plugin is active.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function get_active() {
		return $this->active;
	}

	/**
	 * Obtain whether or not this plugin is network active.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function get_network_active() {
		return $this->network_active;
	}

	/**
	 * Obtain whether or not this plugin is must_use.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function get_must_use() {
		return $this->must_use;
	}

	/**
	 * Obtain tags.
	 *
	 * @since 2.0
	 *
	 * @return array
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
	protected function set_tags() {
		$tags = array();

		if ( ! empty( $this->nice_tgmpa->plugins[ $this->get_slug() ]['tags'] ) ) {
			$tgmpa_tags = array_unique( array_values( $this->nice_tgmpa->plugins[ $this->get_slug() ]['tags'] ) );

			foreach ( $tgmpa_tags as $tgmpa_tag ) {
				$tags[ sanitize_title( $tgmpa_tag ) ] = $tgmpa_tag;
			}
		}

		ksort( $tags );

		$this->tags = $tags;
	}

	/**
	 * Obtain actions.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	public function get_actions() {
		if ( is_null( $this->actions ) ) {
			$this->set_actions();
		}

		return $this->actions;
	}

	/**
	 * Set actions.
	 *
	 * @since 2.0
	 */
	protected function set_actions() {
		$actions = array();

		// If the plugin is not installed, all we can do is install it.
		if ( ! $this->is_installed() ) {
			$actions[] = 'install';

		// If the plugin is installed:
		} else {
			// If the version is lower than the required, all we can do is update it.
			if ( version_compare( $this->get_version(), $this->get_required_version(), '<' ) ) {
				$actions[] = 'update';

			// If the version is greater or equal than the required:
			} else {
				// If there is a newer version available, we can update it.
				if ( $this->get_new_version() && version_compare( $this->get_version(), $this->get_new_version(), '<' ) ) {
					$actions[] = 'update';
				}

				// If the plugin is inactive, we can activate it.
				if ( ! $this->is_active() ) {
					$actions[] = 'activate';

				// If the plugin is active, we can deactivate it.
				} else {
					$actions[] = 'deactivate';
				}
			}
		}

		$this->actions = $actions;
	}

	/**
	 * Obtain enabled actions.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	public function get_enabled_actions() {
		if ( is_null( $this->enabled_actions ) ) {
			$this->set_enabled_actions();
		}

		return $this->enabled_actions;
	}

	/**
	 * Set enabled actions.
	 *
	 * @since 2.0
	 */
	public function set_enabled_actions() {
		$enabled_actions = array();

		foreach ( $this->get_actions() as $action ) {
			if (
				   ( 'install' === $action && $this->can_install() )
				|| ( 'update' === $action && $this->can_update() )
				|| ( 'activate' === $action && $this->can_activate() )
				|| ( 'deactivate' === $action && $this->can_deactivate() )
			) {
				$enabled_actions[] = $action;
			}
		}

		$this->enabled_actions = $enabled_actions;
	}

	/**
	 * Obtain actions URLS.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	public function get_actions_urls() {
		$actions_urls = array_flip( $this->get_actions() );

		foreach ( $actions_urls as $action => &$action_url ) {
			// Don't provide action URLs for disabled actions.
			if ( ! in_array( $action, $this->get_enabled_actions(), true ) ) {
				$action_url = false;
				continue;
			}

			$action_query_args = array(
				'page'             => urlencode( $this->nice_tgmpa->menu ),
				'plugin'           => urlencode( $this->get_slug() ),
				'tgmpa-' . $action => $action . '-plugin',
			);

			if ( ! empty( $this->demo_pack ) ) {
				$action_query_args['demo'] = $this->demo_pack->get_slug();
			}

			$action_url = esc_url( wp_nonce_url(
				add_query_arg(
					$action_query_args,
					admin_url( $this->nice_tgmpa->parent_slug )
				),
				'tgmpa-' . $action,
				'tgmpa-nonce'
			) );
		}

		return $actions_urls;
	}

	/**
	 * Obtain improved TGMPA data.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	public function get_improved_tgmpa_data() {
		$tgmpa_data = $this->nice_tgmpa->plugins[ $this->get_slug() ];

		if ( $this->has_valid_source() ) {
			$tgmpa_data['source'] = $this->get_source_url();
		}

		if ( $this->is_installed() ) {
			$tgmpa_data['file_path'] = $this->get_path();
		}

		return $tgmpa_data;
	}


	/** ==========================================================================
	 *  Conditionals.
	 *  ======================================================================= */

	/**
	 * Obtain whether or not this plugin is external.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_external() {
		return $this->get_external();
	}

	/**
	 * Obtain whether or not this plugin is required.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_theme_required() {
		return $this->get_required();
	}

	/**
	 * Obtain whether or not the plugin is required by the demo pack.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_demo_required() {
		if ( empty( $this->demo_pack ) ) {
			return false;
		}

		$demo_plugins_slugs  = $this->demo_pack->get_plugins_slugs();
		$plugin_path_or_slug = ( false !== $this->get_path() ) ? $this->get_path() : $this->get_slug();

		return array_key_exists( $plugin_path_or_slug, $demo_plugins_slugs ) || in_array( $plugin_path_or_slug, $demo_plugins_slugs, true );
	}

	/**
	 * Obtain whether or not the plugin is required by the theme (or the demo pack, if passed).
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function is_required() {
		if ( ! empty ( $this->demo_pack ) ) {
			return $this->is_demo_required();
		}

		return $this->is_theme_required();
	}

	/**
	 * Obtain whether or not this plugin is required.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_theme_recommended() {
		return $this->get_recommended();
	}

	/**
	 * Obtain whether or not this plugin is required.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_demo_recommended() {
		return ! $this->is_demo_required();
	}

	/**
	 * Obtain whether or not this plugin is recommended.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_recommended() {
		if ( ! empty ( $this->demo_pack ) ) {
			return $this->is_demo_recommended();
		}

		return $this->is_theme_recommended();
	}

	/**
	 * Obtain whether or not the plugin has a valid source.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function has_valid_source() {
		$source_url = $this->get_source_url();

		return ! empty( $source_url );
	}

	/**
	 * Obtain whether or not the plugin has an available update.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function has_update() {
		$new_version = $this->get_new_version();

		return ! empty( $new_version );
	}

	/**
	 * Obtain whether or not installing this plugin is an enabled action.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_installation_enabled() {
		/**
		 * For now, we allow all plugins (either external or from the
		 * repository) to be installed in all cases. In the future,
		 * we may require a valid license to allow external plugins.
		 */
		return true;
	}

	/**
	 * Obtain whether or not this plugin is installed.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_installed() {
		return $this->get_installed();
	}

	/**
	 * Obtain whether or not this plugin is active.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_active() {
		return $this->get_active();
	}

	/**
	 * Obtain whether or not this plugin is network_active.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_network_active() {
		return $this->get_network_active();
	}

	/**
	 * Obtain whether or not this plugin is must_use.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_must_use() {
		return $this->get_must_use();
	}

	/**
	 * Obtain whether or not the plugin is currently inactive.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_inactive() {
		return ( $this->is_installed() && ! ( $this->is_active() || $this->is_network_active() || $this->is_must_use() ) );
	}

	/**
	 * Obtain whether or not the plugin can be installed.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function can_install() {
		if ( ! $this->is_installation_enabled() ) {
			return false;
		}

		return in_array( 'install', $this->get_actions(), true );
	}

	/**
	 * Obtain whether or not the plugin can be updated.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function can_update() {
		if ( ! $this->is_installation_enabled() ) {
			return false;
		}

		return in_array( 'update', $this->get_actions(), true );
	}

	/**
	 * Obtain whether or not the plugin can be activated.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function can_activate() {
		return in_array( 'activate', $this->get_actions(), true );
	}

	/**
	 * Obtain whether or not the plugin can be deactivated.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function can_deactivate() {
		return in_array( 'deactivate', $this->get_actions(), true );
	}
}
endif;
