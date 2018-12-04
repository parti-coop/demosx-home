<?php
/**
 * NiceThemes Framework Plugin Activation
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load dependencies.
 */
if ( ! class_exists( 'Nice_TGM_Plugin' ) ) {
	nice_loader( 'engine/admin/classes/class-nice-tgm-plugin.php' );
}

if ( ! class_exists( 'Nice_TGM_Plugin_Activation' ) && class_exists( 'TGM_Plugin_Activation' ) ) :
/**
 * Class Nice_TGM_Plugin_Activation
 *
 * Properly customize TGMPA class.
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   2.0
 */
class Nice_TGM_Plugin_Activation extends TGM_Plugin_Activation {

	/** ==========================================================================
	 *  Properties.
	 *  ======================================================================= */

	/**
	 * Holds a demo pack object.
	 *
	 * @since 2.0
	 *
	 * @var Nice_Theme_Demo_Pack
	 */
	protected $demo_pack = null;

	/**
	 * Holds a system status object.
	 *
	 * @since 2.0
	 *
	 * @var Nice_Admin_System_Status
	 */
	protected $system_status = null;

	/**
	 * Holds a copy of itself, so it can be referenced by the class name.
	 *
	 * @since 1.0.0
	 *
	 * @var Nice_TGM_Plugin_Activation|TGM_Plugin_Activation
	 */
	public static $instance;

	/** ==========================================================================
	 *  Constructing methods.
	 *  ======================================================================= */

	/**
	 * Initialize custom properties.
	 *
	 * @internal This method should change to `protected` when the parent constructor does.
	 *
	 * @since 2.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'do_plugin_activate' ) );

		parent::__construct();

		// Initialize System Status.
		$this->system_status = Nice_Admin_System_Status::obtain();

		// Customize action links.
		add_filter( 'tgmpa_notice_action_links', array( $this, 'tgmpa_notice_action_links' ), 10 );

		// Handle notice dismissal.
		add_action( 'wp_ajax_nice_tgmpa_dismiss_notice', array( $this, 'dismiss_notice_callback' ) );

		// Maybe set the demo pack.
		$this->set_demo_pack();
	}

	/**
	 * Return the singleton instance of the child class, instead of the parent.
	 *
	 * @since 2.0
	 *
	 * @return Nice_TGM_Plugin_Activation
	 */
	public static function get_instance() {
		static $fake_plugins_page = false;

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();

			/**
			 * This is ugly. If we're in the Demo Importer, we need to lie to TGMPA
			 * and make it believe we're in the Plugin Installer page. Otherwise it
			 * won't load the `TGMPA_Bulk_Installer` class, which we need there.
			 *
			 * @see tgmpa_load_bulk_installer()
			 * @see Nice_Theme_Demo_Pack::prepare_plugins()
			 */
			if (   ( nice_doing_ajax() && ( ! empty( $_POST['action'] ) && 'nice_theme_import_demo_pack' === $_POST['action'] ) )
				&& ( ! empty( self::$instance->demo_pack ) && self::$instance->demo_pack->do_install_plugins() ) ) {
				$fake_plugins_page = true;
			}
		}

		if ( $fake_plugins_page ) {
			$functions_backtrace = wp_list_pluck( debug_backtrace(), 'function' );

			// We only need to fake the plugins page when loading the bulk installer.
			if ( in_array( 'tgmpa_load_bulk_installer', $functions_backtrace, true ) ) {
				$_GET['page'] = self::$instance->menu;
			} else {
				unset( $_GET['page'] );
			}
		}

		return self::$instance;
	}

	/**
	 * Force load in Demo Importer AJAX calls.
	 *
	 * @since 2.0
	 */
	public function init() {
		if (   ( nice_doing_ajax() && ( ! empty( $_POST['action'] ) && 'nice_theme_import_demo_pack' === $_POST['action'] ) )
			&& ( ! empty( $this->demo_pack ) && $this->demo_pack->do_install_plugins() ) ) {
				add_filter( 'tgmpa_load', '__return_true' );
		}

		parent::init();
	}

	/** ==========================================================================
	 *  Getters.
	 *  ======================================================================= */

	/**
	 * Obtain TGMPA plugins list.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	public function get_tgmpa_plugins() {
		return Nice_TGM_Plugin_Activation::$instance->plugins;
	}


	/** ==========================================================================
	 *  Plugin Installer page.
	 *  ======================================================================= */

	/**
	 * Replace default plugin installation form.
	 *
	 * @since 2.0
	 *
	 * @return null Aborts early if we're processing a plugin installation action.
	 */
	public function install_plugins_page() {
		// Return early if processing a plugin installation action.
		if ( $this->do_bulk_action() || $this->do_plugin_install() ) {
			return;
		}

		// Refresh plugins data to be sure we're aware of the latest changes.
		$this->refresh_plugins_data();

		$tgmpa_plugins  = $this->plugins;
		uasort( $tgmpa_plugins, array( $this, 'uasort_tgmpa_plugins' ) );

		$tags = array();

		ob_start();
		?>

			<div class="nice-item-browser nice-plugin-browser isotope">

				<?php foreach ( $tgmpa_plugins as $tgmpa_plugin_data ) : ?>
					<?php
						$plugin = Nice_TGM_Plugin::obtain( $tgmpa_plugin_data['slug'], $this->demo_pack );

						// Skip invalid plugins.
						if ( is_wp_error( $plugin ) ) {
							continue;
						}

						// Skip non-demo plugins.
						if ( ( ! empty( $this->demo_pack ) ) && ( ! $plugin->is_demo_required() ) ) {
							continue;
						}

						$isotope_class = '';

						foreach ( $plugin->get_tags() as $tag_slug => $tag_name ) {
							$tags[ $tag_slug ] = $tag_name;
							$isotope_class    .= ' tag-'. $tag_slug;
						}

						$isotope_class = trim( $isotope_class );

						$bulk_actions_data = '';
						foreach ( $plugin->get_enabled_actions() as $action_slug ) {
							$bulk_actions_data .= ' data-action-'. $action_slug . '="1"';
						}
						$bulk_actions_data = trim( $bulk_actions_data );

						$bulk_groups_data = 'data-group-all="1"';
						if ( $plugin->is_required() ) {
							$bulk_groups_data .= ' data-group-required="1"';
						} elseif ( $plugin->is_recommended() ) {
							$bulk_groups_data .= ' data-group-recommended="1"';
						}

						$ribbon_icon_class = $ribbon_tooltip = '';
					?>

					<div class="item <?php echo esc_attr( $isotope_class ); ?> isotope-item <?php echo $plugin->is_active() ? 'active' : ''; ?>" <?php echo $bulk_actions_data; ?> <?php echo $bulk_groups_data; ?>>

						<div class="item-screenshot">
							<img src="<?php echo esc_url( $plugin->get_image_url() ); ?>" alt="" />
						</div>

						<h3 class="item-name">
							<?php
								if ( $plugin->is_active() ) {
									printf( '<span>%s</span> ', esc_attr__( 'Active:', 'nice-framework' ) );
									$ribbon_icon_class = 'dashicons dashicons-yes';
								}
								echo esc_attr( $plugin->get_name() );
							?>

							<span class="item-info">
								<?php if ( $plugin->is_installed() ) :
									printf( esc_html__( 'Version %s by %s', 'nice-framework' ), esc_attr( $plugin->get_version() ), esc_attr( $plugin->get_author_name() ) );
								else :
									printf( esc_html__( 'By %s', 'nice-framework' ), esc_attr( $plugin->get_author_name() ) );
								endif; ?>
							</span>

						</h3>

						<?php if ( $plugin->can_update() ) : ?>
							<?php if ( $plugin->get_new_version() ) : ?>
								<div class="item-update new-version">
									<?php printf( esc_html__( 'New Version: %s', 'nice-framework' ), esc_attr( $plugin->get_new_version() ) ); ?>
								</div>
							<?php else : ?>
								<div class="item-update">
									<?php printf( esc_html__( 'Minimum Required Version: %s', 'nice-framework' ), esc_attr( $plugin->get_required_version() ) ); ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<div class="item-actions">
							<?php
								$action_link_texts = array(
									'install'    => array(
										'title'  => esc_html__( 'Install %s', 'nice-framework' ),
										'anchor' => esc_html__( 'Install', 'nice-framework' ),
									),
									'update'     => array(
										'title'  => esc_html__( 'Update %s', 'nice-framework' ),
										'anchor' => esc_html__( 'Update', 'nice-framework' ),
									),
									'activate'   => array(
										'title'  => esc_html__( 'Activate %s', 'nice-framework' ),
										'anchor' => esc_html__( 'Activate', 'nice-framework' ),
									),
									'deactivate' => array(
										'title'  => esc_html__( 'Deactivate %s', 'nice-framework' ),
										'anchor' => esc_html__( 'Deactivate', 'nice-framework' ),
									),
								);
							?>
							<?php foreach ( $plugin->get_actions_urls() as $action => $action_url ) : ?>
								<a <?php echo $action_url ? 'href="' . esc_url( $action_url ) . '"' : ''; ?> <?php echo $action_url ? '' : 'disabled="disabled"'; ?> class="button button-primary" title="<?php echo $action_url ? sprintf( esc_attr( $action_link_texts[ $action ]['title'] ), esc_attr( $plugin->get_name() ) ) : sprintf( esc_attr__( 'You need to register %s to install or update %s.', 'nice-framework' ), esc_attr( $this->system_status->get_nice_theme_name() ), esc_attr( $plugin->get_name() ) ); ?>"><?php echo esc_attr( $action_link_texts[ $action ]['anchor'] ); ?></a>
							<?php endforeach; ?>
						</div>

						<?php if ( $plugin->is_required() ) :
							if ( ! $plugin->is_active() ) {
								$ribbon_tooltip    = esc_html__( 'This plugin is required. Please install it and activate it.', 'nice-framework' );
								$ribbon_icon_class = 'dashicons dashicons-warning';
							}
						endif; ?>

						<?php if ( ( '' !== $ribbon_icon_class ) || ( '' !== $ribbon_tooltip ) ) : ?>
							<div class="item-ribbon <?php echo $plugin->is_active() ? 'active' : ''; ?>">
								<a href="#" class="item-ribbon-icon nice-tooltip" title="<?php echo esc_attr( $ribbon_tooltip ); ?>"><i class="<?php echo esc_attr( $ribbon_icon_class ); ?>"></i></a>
							</div>
						<?php endif; ?>

					</div>
				<?php endforeach; ?>

				<?php
					$plugins_output = ob_get_contents();
					ob_end_clean();
				?>

				<div class="nice-bulk-actions">
					<form id="nice-bulk-actions-form" method="get" action="<?php admin_url( 'admin.php' ); ?>">

						<input type="hidden" name="page" value="<?php echo esc_attr( nice_admin_page_get_menu_slug( 'plugins' ) ); ?>" />

						<select id="nice-bulk-action" name="tgmpa-bulk-actions">
							<?php $first = true; ?>
							<?php foreach ( $this->get_bulk_actions() as $action_key => $action_name ) : ?>
								<option <?php echo $first ? 'selected="selected"' : ''; ?> value="tgmpa-bulk-<?php echo esc_attr( $action_key ); ?>"><?php echo esc_html( $action_name ); ?></option>
								<?php $first = false; ?>
							<?php endforeach; ?>
						</select>

						<select id="nice-bulk-plugins" name="tgmpa-bulk-plugins">
							<?php $first = true; ?>
							<?php foreach ( $this->get_bulk_plugin_groups() as $plugin_group_key => $plugin_group_name ) : ?>
								<option <?php echo $first ? 'selected="selected"' : ''; ?> value="<?php echo esc_attr( $plugin_group_key ); ?>"><?php echo esc_html( $plugin_group_name ); ?></option>
								<?php $first = false; ?>
							<?php endforeach; ?>
						</select>

						<?php if ( ! empty( $this->demo_pack ) ) : ?>
							<input type="hidden" name="demo" value="<?php echo esc_attr( $this->demo_pack->get_slug() ); ?>" />
						<?php endif; ?>

						<?php wp_nonce_field( 'nice-bulk-actions', 'tgmpa-bulk-nonce' ); ?>

						<input type="submit" id="nice-bulk-submit" value="<?php esc_attr_e( 'Apply', 'nice-framework' ); ?>" class="button button-primary" />
					</form>
				</div>

				<?php
					$filters = array();

					if ( empty( $this->demo_pack ) ) {
						$filters['tag'] = array(
							'icon'  => '<i class="dashicons dashicons-admin-plugins"></i>',
							'title' => esc_html__( 'All Plugins', 'nice-framework' ),
							'terms' => $tags,
							'sort'  => false,
						);
					}

					ob_start();
				?>

				<?php foreach ( $filters as $filter_key => $filter_data ) : ?>

					<?php if ( ! empty( $filter_data['terms'] ) ) : ?>

						<?php
							if ( $filter_data['sort'] ) {
								ksort( $filter_data['terms'] );
							}
						?>

						<div id="filter-<?php echo esc_attr( $filter_key ); ?>" class="filter-wrapper nice-admin-filter">

							<a class="sort-items filter-title"><?php echo $filter_data['icon']; ?> <span><?php echo esc_html( $filter_data['title'] ); ?></span></a>

							<ul class="filter option-set" data-filter-group="<?php echo esc_attr( $filter_key ); ?>">

								<li><a class="filter active" data-filter-value="" href="#filter-<?php echo esc_attr( $filter_key ); ?>-all"><?php esc_html_e( 'All Plugins', 'nice-framework' ); ?></a></li>

								<?php foreach ( $filter_data['terms'] as $term_key => $term_name ) : ?>
									<li><a class="filter" data-filter-value=".<?php echo esc_attr( $filter_key ); ?>-<?php echo esc_attr( $term_key ); ?>" href="#filter-<?php echo esc_attr( $filter_key ); ?>-<?php echo esc_attr( $term_key ); ?>"><?php echo esc_html( $term_name ); ?></a></li>
								<?php endforeach; ?>

							</ul>

						</div>

					<?php endif; ?>

				<?php endforeach; ?>

				<?php
					$filters_output = trim( ob_get_contents() );
					ob_end_clean();
				?>

				<?php
					if ( ! empty( $filters_output ) ) {
						echo '<div class="nice-item-filters">' . $filters_output . '</div>';
					}
				?>

				<div class="isotope-empty" style="display: none;">
					<p>
						<?php esc_html_e( 'No plugins match your selection.', 'nice-framework' ); ?>
						<br />
						<?php esc_html_e( 'Try a different combination.', 'nice-framework' ); ?>
					</p>
				</div>

				<?php echo $plugins_output; ?>

			</div>

		<?php
	}


	/** ==========================================================================
	 *  Customized functionality.
	 *  ======================================================================= */

	/**
	 * Remove the current TGMPA version.
	 *
	 * @since 2.0
	 */
	public function show_tgmpa_version() {	}

	/**
	 * Remove the default menu items.
	 *
	 * @since 2.0
	 *
	 * @param array $args Menu item configuration.
	 */
	protected function add_admin_menu( array $args ) {	}

	/**
	 * Customize the URL to the TGMPA Install page.
	 *
	 * @since 2.0
	 *
	 * @return string Properly encoded URL (not escaped).
	 */
	public function get_tgmpa_url() {
		static $tgmpa_url = null;

		if ( is_null( $tgmpa_url ) ) {
			$tgmpa_url = parent::get_tgmpa_url();

			if ( ( 'plugins' === nice_admin_get_current_page() ) && ! empty( $this->demo_pack ) ) {
				$tgmpa_url = add_query_arg( 'demo', $this->demo_pack->get_slug(), $tgmpa_url );
			}
		}

		return $tgmpa_url;
	}

	/**
	 * Use TGMPA for installations and updates only.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	protected function do_plugin_install() {
		if (   ( isset( $_GET['tgmpa-activate'] ) && 'activate-plugin' === $_GET['tgmpa-activate'] )
			|| ( isset( $_GET['tgmpa-deactivate'] ) && 'deactivate-plugin' === $_GET['tgmpa-deactivate'] ) ) {
			return false;
		}

		/**
		 * Make initial parent method validations, and obtain TGMPA plugin slug.
		 */
		if ( empty( $_GET['plugin'] ) ) {
			return false;
		}

		$plugin_slug = $this->sanitize_key( urldecode( $_GET['plugin'] ) );

		if ( ! isset( $this->plugins[ $plugin_slug ] ) ) {
			return false;
		}

		// Obtain plugin from slug.
		$plugin = Nice_TGM_Plugin::obtain( $plugin_slug );

		// Validate plugin.
		if ( is_wp_error( $plugin ) ) {
			return false;
		}

		// Abort if the current action is disabled.
		if ( ! $plugin->is_installation_enabled() ) {
			printf( esc_html__( 'You need to register %s to install or update %s.', 'nice-framework' ), esc_html( $this->system_status->get_nice_theme_name() ), esc_html( $plugin->get_name() ) );
			return true;
		}

		return parent::do_plugin_install();
	}

	/**
	 * TGMPA should never complete its actions, because we always want to link to the Plugin Installer.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function is_tgmpa_complete() {
		return false;
	}

	/**
	 * Add custom plugin attributes.
	 *
	 * @since 2.0
	 *
	 * @param array $plugin
	 *
	 * @return null
	 */
	public function register( $plugin ) {
		$plugin = wp_parse_args( $plugin, array(
			// TGMPA values
			'name'               => '',      // String
			'slug'               => '',      // String
			'source'             => 'repo',  // String
			'required'           => false,   // Boolean
			'version'            => '',      // String
			'force_activation'   => false,   // Boolean
			'force_deactivation' => false,   // Boolean
			'external_url'       => '',      // String
			'is_callable'        => '',      // String|Array
			// Our own
			'description'        => '',      // String
			'author_name'        => '',      // String
			'author_url'         => '',      // String
			'menu_order'         => 10,      // Integer
		) );

		return parent::register( $plugin );
	}


	/** ==========================================================================
	 *  Plugin updates.
	 *  ======================================================================= */

	/**
	 * Use our own check for plugin updates availability.
	 *
	 * @since 2.0
	 *
	 * @param string $plugin_slug
	 *
	 * @return false|string
	 */
	public function does_plugin_have_update( $plugin_slug ) {
		$plugin = Nice_TGM_Plugin::obtain( $plugin_slug );

		if ( $plugin instanceof Nice_TGM_Plugin ) {
			return $plugin->has_update();
		}

		return false;
	}


	/** ==========================================================================
	 *  Redirections.
	 *  ======================================================================= */

	/**
	 * Handle activations and deactivations.
	 *
	 * This function is hooked in `admin_init`, and forces a redirect to the install page.
	 * This way, post-activation tasks are immediately executed.
	 *
	 * @since 2.0
	 */
	public function do_plugin_activate() {
		if ( isset( $_GET['tgmpa-activate'] ) && 'activate-plugin' === $_GET['tgmpa-activate'] ) {
			$action = 'activate';

		} elseif ( isset( $_GET['tgmpa-deactivate'] ) && 'deactivate-plugin' === $_GET['tgmpa-deactivate'] ) {
			$action = 'deactivate';
		}

		if ( empty( $action ) ) {
			return false;
		}

		$plugin_slug = $this->sanitize_key( urldecode( $_GET['plugin'] ) );

		if ( isset( $this->plugins[ $plugin_slug ]['file_path'], $_GET[ 'tgmpa-' . $action ] ) ) {
			check_admin_referer( 'tgmpa-' . $action, 'tgmpa-nonce' );

			$active_plugins = $this->system_status->get_active_plugins();

			switch ( $action ) {
				case 'activate' :
					if ( ! in_array( $this->plugins[ $plugin_slug ]['file_path'], array_keys( $active_plugins ), true ) ) {
						activate_plugin( $this->plugins[ $plugin_slug ]['file_path'] );

						if ( $this->is_plugin_active( $plugin_slug ) ) {
							/**
							 * @hook nice_tgmpa_plugin_activated_{$plugin_slug}
							 *
							 * Hook here to execute actions after activating a specific plugin.
							 */
							do_action( "nice_tgmpa_plugin_activated_{$plugin_slug}", $this->plugins[ $plugin_slug ] );
						}
					}
					break;

				case 'deactivate' :
					if ( in_array( $this->plugins[ $plugin_slug ]['file_path'], array_keys( $active_plugins ), true ) ) {
						deactivate_plugins( $this->plugins[ $plugin_slug ]['file_path'] );

						if ( ! $this->is_plugin_active( $plugin_slug ) ) {
							/**
							 * @hook nice_tgmpa_plugin_deactivated_{$plugin_slug}
							 *
							 * Hook here to execute actions after deactivating a specific plugin.
							 */
							do_action( "nice_tgmpa_plugin_deactivated_{$plugin_slug}", $this->plugins[ $plugin_slug ] );
						}
					}
					break;
			}

		}

		/**
		 * @hook  nice_tgmpa_plugin_activate_redirect
		 * @hook  nice_tgmpa_plugin_deactivate_redirect
		 *
		 * Hook here if you want to disable the redirect after activating or deactivating plugins.
		 *
		 * @since 2.0
		 */
		if ( apply_filters( 'nice_tgmpa_plugin_' . $action . '_redirect', true, $plugin_slug ) ) {
			wp_redirect( $this->get_tgmpa_url() );
		}
	}


	/** ==========================================================================
	 *  Bulk actions.
	 *  ======================================================================= */

	/**
	 * Helper method to obtain the available bulk actions.
	 *
	 * @since 2.0
	 *
	 * @see Nice_TGM_Plugin_Activation::install_plugins_page()
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		$plugins_enabled_actions = array();

		foreach ( $this->plugins as $tgmpa_plugin_data ) {
			$plugin = Nice_TGM_Plugin::obtain( $tgmpa_plugin_data['slug'], $this->demo_pack );

			// Skip invalid plugins.
			if ( is_wp_error( $plugin ) ) {
				continue;
			}

			// If we have a demo pack, skip its non-required plugins.
			if ( ! empty( $this->demo_pack ) && ! $plugin->is_demo_required() ) {
				continue;
			}

			$plugins_enabled_actions = array_merge( $plugins_enabled_actions, $plugin->get_enabled_actions() );
		}

		$plugins_enabled_actions = array_values( array_unique( $plugins_enabled_actions ) );

		if ( in_array( 'install', $plugins_enabled_actions, true ) ) {
			$plugins_enabled_actions[] = 'install-activate';
		}

		$bulk_actions = array_intersect_key( array(
			'install-activate' => esc_html__( 'Install &amp; Activate', 'nice-framework' ),
			'install'          => esc_html__( 'Install', 'nice-framework' ),
			'update'           => esc_html__( 'Update', 'nice-framework' ),
			'activate'         => esc_html__( 'Activate', 'nice-framework' ),
			'deactivate'       => esc_html__( 'Deactivate', 'nice-framework' ),
		), array_flip( $plugins_enabled_actions ) );

		return $bulk_actions;
	}

	/**
	 * Helper method to obtain the available bulk plugins groups.
	 *
	 * @since 2.0
	 *
	 * @see Nice_TGM_Plugin_Activation::install_plugins_page()
	 *
	 * @return array
	 */
	protected function get_bulk_plugin_groups() {
		$have_required_plugins    = false;
		$have_recommended_plugins = false;

		foreach ( $this->plugins as $tgmpa_plugin_data ) {
			$plugin = Nice_TGM_Plugin::obtain( $tgmpa_plugin_data['slug'], $this->demo_pack );

			// Skip invalid plugins.
			if ( is_wp_error( $plugin ) ) {
				continue;
			}

			// If we have a demo pack, skip its non-required plugins.
			if ( ! empty( $this->demo_pack ) && ! $plugin->is_demo_required() ) {
				continue;
			}

			if ( $plugin->is_required() ) {
				$have_required_plugins = true;
			} elseif ( $plugin->is_recommended() ) {
				$have_recommended_plugins = true;
			}
		}

		$plugins_groups = array(
			'all' => esc_html__( 'All Plugins', 'nice-framework' ),
		);

		if ( $have_required_plugins && $have_recommended_plugins ) {
			$plugins_groups['required']    = esc_html__( 'Required Only', 'nice-framework' );
			$plugins_groups['recommended'] = esc_html__( 'Recommended Only', 'nice-framework' );
		}

		return $plugins_groups;
	}

	/**
	 * Process bulk actions.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	protected function do_bulk_action() {
		// Make sure we have a bulk action ongoing.
		if ( ! $this->doing_bulk_action() ) {
			return false;
		}

		// Validate request.
		check_admin_referer( 'nice-bulk-actions', 'tgmpa-bulk-nonce' );

		// Obtain requested actions and plugins
		$actions = $this->get_bulk_requested_actions();
		$plugins = $this->get_bulk_requested_plugins();

		// Validate requested actions and plugins.
		if ( is_wp_error( $actions ) || is_wp_error( $plugins ) ) {
			if (
				( is_wp_error( $actions ) && 'invalid_actions' === $actions->get_error_code() )
			 || ( is_wp_error( $plugins ) && 'invalid_plugin_group' === $plugins->get_error_code() )
			) {
				$this->print_bulk_output(
					esc_html__( 'What?', 'nice-framework' ),
					sprintf( '<strong>%s</strong>', esc_html__( 'ERROR:', 'nice-framework' ) ) . ' ' . esc_html__( 'Invalid action or plugin group requested.', 'nice-framework' )
				);

			} else {
				$this->print_bulk_output_header(
					sprintf( '%s: %s', $this->get_bulk_requested_actions_name(), $this->get_bulk_requested_plugin_group_name() )
				);

				if ( is_wp_error( $actions ) && 'no_permissions' === $actions->get_error_code() ) {
					$this->print_bulk_output_content(
						sprintf( '<strong>%s</strong>', esc_html__( 'ERROR:', 'nice-framework' ) ) . ' ' . $actions->get_error_message()
					);

				} elseif ( is_wp_error( $plugins ) && 'no_plugins' === $plugins->get_error_code() ) {
					$this->print_bulk_output_content(
						$plugins->get_error_message()
					);
				}

				$this->print_bulk_output_footer();
			}

			return true;
		}

		/**
		 * At this point, we can safely assume we have valid actions and plugins to process.
		 */

		// Print the output header.
		$this->print_bulk_output_header(
			sprintf( '%s: %s', $this->get_bulk_requested_actions_name(), $this->get_bulk_requested_plugin_group_name() )
		);

		// Execute bulk installation.
		if ( in_array( 'install', $actions, true ) ) {
			$this->process_bulk_install( $plugins );
			// Reset plugins information.
			$plugins = $this->get_bulk_requested_plugins();
		}


		// Execute bulk update.
		if ( in_array( 'update', $actions, true ) ) {
			$this->process_bulk_update( $plugins );
			// Reset plugins information.
			$plugins = $this->get_bulk_requested_plugins();
		}

		// Execute bulk activation.
		if ( in_array( 'activate', $actions, true ) ) {
			$this->process_bulk_activate( $plugins );
			// Reset plugins information.
			$plugins = $this->get_bulk_requested_plugins();
		}

		// Execute bulk deactivation.
		if ( in_array( 'deactivate', $actions, true ) ) {
			$this->process_bulk_deactivate( $plugins );
			// Reset plugins information.
			$plugins = $this->get_bulk_requested_plugins();
		}

		// Print the output footer.
		$this->print_bulk_output_footer();

		return true;
	}

	/**
	 * Helper method to check if there is an ongoing bulk action.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	protected function doing_bulk_action() {
		return ! ( empty( $_GET['tgmpa-bulk-actions'] ) || empty( $_GET['tgmpa-bulk-plugins'] ) );
	}

	/**
	 * Helper method to obtain the requested actions slug.
	 *
	 * @since 2.0
	 *
	 * @return bool|string
	 */
	protected function get_bulk_requested_actions_slug() {
		static $requested_actions_slug = null;

		if ( is_null( $requested_actions_slug ) ) {
			$requested_actions = str_replace( 'tgmpa-bulk-', '', strip_tags( $_GET['tgmpa-bulk-actions'] ) );
			$allowed_actions   = $this->get_bulk_actions();

			if ( array_key_exists( $requested_actions, $allowed_actions ) ) {
				$requested_actions_slug = $requested_actions;
			} else {
				$requested_actions_slug = false;
			}
		}

		return $requested_actions_slug;
	}

	/**
	 * Helper method to obtain the requested plugin group slug.
	 *
	 * @since 2.0
	 *
	 * @return bool|string
	 */
	protected function get_bulk_requested_plugin_group_slug() {
		static $requested_plugin_group_slug = null;

		if ( is_null( $requested_plugin_group_slug ) ) {
			$requested_plugin_group = strip_tags( $_GET['tgmpa-bulk-plugins'] );
			$allowed_plugin_group   = $this->get_bulk_plugin_groups();

			if ( array_key_exists( $requested_plugin_group, $allowed_plugin_group ) ) {
				$requested_plugin_group_slug = $requested_plugin_group;
			} else {
				$requested_plugin_group_slug = false;
			}
		}

		return $requested_plugin_group_slug;
	}

	/**
	 * Helper method to obtain the requested actions name.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	protected function get_bulk_requested_actions_name() {
		static $requested_actions_name = null;

		if ( is_null( $requested_actions_name ) ) {
			if ( $this->get_bulk_requested_actions_slug() ) {
				$allowed_actions             = $this->get_bulk_actions();
				$bulk_requested_actions_slug = $this->get_bulk_requested_actions_slug();
				$requested_actions_name      = $allowed_actions[ $bulk_requested_actions_slug ];
			} else {
				$requested_actions_name = '';
			}
		}

		return $requested_actions_name;
	}

	/**
	 * Helper method to obtain the requested plugin group name.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	protected function get_bulk_requested_plugin_group_name() {
		static $requested_plugin_group_name = null;

		if ( is_null( $requested_plugin_group_name ) ) {
			if ( $this->get_bulk_requested_plugin_group_slug() ) {
				$allowed_plugin_groups            = $this->get_bulk_plugin_groups();
				$bulk_requested_plugin_group_slug = $this->get_bulk_requested_plugin_group_slug();
				$requested_plugin_group_name      = $allowed_plugin_groups[ $bulk_requested_plugin_group_slug ];
			} else {
				$requested_plugin_group_name = '';
			}
		}

		return $requested_plugin_group_name;
	}

	/**
	 * Helper method to obtain and validate requested bulk actions.
	 *
	 * @since 2.0
	 *
	 * @return WP_Error|array
	 */
	protected function get_bulk_requested_actions() {
		// Obtain requested and allowed actions.
		$requested_actions = str_replace( 'tgmpa-bulk-', '', strip_tags( $_GET['tgmpa-bulk-actions'] ) );
		$allowed_actions   = $this->get_bulk_actions();

		// Return an error if the requested actions are not allowed.
		if ( ! array_key_exists( $requested_actions, $allowed_actions ) ) {
			return new WP_Error( 'invalid_actions', esc_html__( 'The requested actions are invalid.', 'nice-framework' ) );
		}

		// Create an array of valid requested actions.
		$valid_requested_actions = array();

		foreach ( explode( '-', $requested_actions ) as $requested_action ) {
			// Validate capabilities for each requested action.
			switch ( $requested_action ) {
				case 'install':
					$capability = 'install_plugins';
					break;
				case 'update':
					$capability = 'update_plugins';
					break;
				case 'activate':
				case 'deactivate':
					$capability = 'activate_plugins';
					break;
				default : // Should never be reached.
					$capability = 'manage_plugins';
					break;
			}

			// Add the actions the current user can perform.
			if ( current_user_can( $capability ) ) {
				$valid_requested_actions[] = $requested_action;
			}
		}

		// Return an error if the current user can't perform any of the requested actions.
		if ( empty( $valid_requested_actions ) ) {
			return new WP_Error( 'no_permissions', esc_html__( "You don't have enough permissions to do this.", 'nice-framework' ) );
		}

		// Return valid requested actions.
		return $valid_requested_actions;
	}

	/**
	 * Helper method to obtain and validate requested plugins for bulk actions.
	 *
	 * @since 2.0
	 *
	 * @return WP_Error|array
	 */
	protected function get_bulk_requested_plugins() {
		// Obtain requested and allowed plugin groups.
		$requested_plugin_group = strip_tags( $_GET['tgmpa-bulk-plugins'] );
		$allowed_plugin_groups  = $this->get_bulk_plugin_groups();

		// Return an error if the requested plugin group is invalid.
		if ( ! array_key_exists( $requested_plugin_group, $allowed_plugin_groups ) ) {
			return new WP_Error( 'invalid_plugin_group', esc_html__( 'The requested plugin group is invalid.', 'nice-framework' ) );
		}

		// Initialize plugins list.
		$plugins = array();

		// Obtain a Nice_TGM_Plugin object for each valid plugin.
		foreach ( $this->plugins as $plugin_slug => $plugin_data ) {
			$plugin = Nice_TGM_Plugin::obtain( $plugin_data['slug'], $this->demo_pack );

			// Skip invalid plugins.
			if ( is_wp_error( $plugin ) ) {
				continue;
			}

			// If we have a demo pack, skip its non-required plugins.
			if ( ! empty( $this->demo_pack ) && ! $plugin->is_demo_required() ) {
				continue;
			}

			// Skip plugins which don't belong to the requested group.
			if (
				   ( ( 'required' === $requested_plugin_group ) && ! $plugin->is_required() )
				|| ( ( 'recommended' === $requested_plugin_group ) && ! $plugin->is_recommended() )
			) {
				continue;
			}

			$plugins[ $plugin_slug ] = $plugin;
		}

		// Return an error if the plugins list is empty.
		if ( empty( $plugins ) ) {
			switch ( $requested_plugin_group ) {
				case 'required':
					$word_plugins = esc_html__( 'required plugins', 'nice-framework' );
					break;
				case 'recommended':
					$word_plugins = esc_html__( 'recommended plugins', 'nice-framework' );
					break;
				default :
					$word_plugins = esc_html__( 'plugins', 'nice-framework' );
					break;
			}

			if ( ! empty( $this->demo_pack ) ) {
				$error_message = sprintf( esc_html__( 'The %1$s demo has no %2$s to be processed.', 'nice-framework' ), $this->demo_pack->get_name(), $word_plugins );
			} else {
				$error_message = sprintf( esc_html__( 'There are no %1$s to be processed.', 'nice-framework' ), $word_plugins );
			}

			return new WP_Error( 'no_plugins', $error_message );
		}

		// Return plugins list.
		return $plugins;
	}

	/**
	 * Helper method to install all the given plugins.
	 *
	 * @since 2.0
	 *
	 * @param array $plugins
	 */
	public function process_bulk_install( $plugins ) {
		$plugins_names   = array();
		$plugins_sources = array();

		foreach ( $plugins as $plugin_slug => $plugin ) {
			// Skip invalid plugins.
			if ( ! ( $plugin instanceof Nice_TGM_Plugin ) ) {
				continue;
			}

			// Skip plugins with installation disabled.
			if ( ! $plugin->is_installation_enabled() ) {
				continue;
			}

			// Skip already installed plugins.
			if ( $plugin->is_installed() ) {
				continue;
			}

			// Skip plugins without valid sources.
			if ( ! $plugin->has_valid_source() ) {
				continue;
			}

			// Store plugin name and source.
			$plugins_names[]   = $plugin->get_name();
			$plugins_sources[] = $plugin->get_source_url();
		}

		// Return early with a message if we have no plugins to install.
		if ( empty( $plugins_names ) || empty( $plugins_sources ) ) {
			switch ( $this->get_bulk_requested_plugin_group_slug() ) {
				case 'required':
					$word_plugins = esc_html__( 'required plugins', 'nice-framework' );
					break;
				case 'recommended':
					$word_plugins = esc_html__( 'recommended plugins', 'nice-framework' );
					break;
				default :
					$word_plugins = esc_html__( 'plugins', 'nice-framework' );
					break;
			}

			$this->print_bulk_output_content(
				sprintf( esc_html__( 'Currently, no %1$s need to be installed.', 'nice-framework' ), $word_plugins )
			);

			return;
		}

		/**
		 * Load dependencies.
		 */
		if ( ! class_exists( 'Nice_TGMPA_Bulk_Installer' ) ) {
			nice_loader( 'engine/admin/classes/class-nice-tgmpa-bulk-installer.php' );
		}
		if ( ! class_exists( 'Nice_TGMPA_Bulk_Installer_Skin' ) ) {
			nice_loader( 'engine/admin/classes/class-nice-tgmpa-bulk-installer-skin.php' );
		}

		// Initialize the installer.
		$installer = new Nice_TGMPA_Bulk_Installer(
			new Nice_TGMPA_Bulk_Installer_Skin(
				array(
					'url'          => esc_url_raw( $this->get_tgmpa_url() ),
					'nonce'        => 'bulk-plugins',
					'names'        => $plugins_names,
					'install_type' => 'install',
				)
			)
		);

		// Adjust source selection.
		add_filter( 'upgrader_source_selection', array( $this, 'maybe_adjust_source_dir' ), 1, 3 );
		// Remove footer actions.
		add_filter( 'tgmpa_update_bulk_plugins_complete_actions', '__return_empty_array', 999 );

		// Execute bulk update.
		$installer->bulk_install( $plugins_sources, array(
			'clear_destination'           => true,
			'abort_if_destination_exists' => false,
			'clear_working'               => true,
			'is_multi'                    => true,
			'clear_update_cache'          => true,
		) );

		// Remove added filters.
		remove_filter( 'upgrader_source_selection', array( $this, 'maybe_adjust_source_dir' ), 1 );
		remove_filter( 'tgmpa_update_bulk_plugins_complete_actions', '__return_empty_array', 999 );

		// Refresh plugin data.
		$this->refresh_plugins_data();
	}

	/**
	 * Helper method to update all the given plugins.
	 *
	 * @since 2.0
	 *
	 * @param array $plugins
	 */
	public function process_bulk_update( $plugins ) {
		$plugins_names = array();
		$plugins_paths = array();
		$plugins_data  = array();

		foreach ( $plugins as $plugin_slug => $plugin ) {
			// Skip invalid plugins.
			if ( ! ( $plugin instanceof Nice_TGM_Plugin ) ) {
				continue;
			}

			// Skip plugins with installation disabled.
			if ( ! $plugin->is_installation_enabled() ) {
				continue;
			}

			// Skip plugins without available updates.
			if ( ! $plugin->has_update() ) {
				continue;
			}

			// Skip plugins without valid sources.
			if ( ! $plugin->has_valid_source() ) {
				continue;
			}

			// Store plugin name and path
			$plugins_names[] = $plugin->get_name();
			$plugins_paths[] = $plugin->get_path();

			// Store plugin data.
			$plugins_data[ $plugin_slug ] = $plugin->get_improved_tgmpa_data();
		}

		// Return early with a message if we have no plugins to update.
		if ( empty( $plugins_names ) || empty( $plugins_paths ) || empty( $plugins_data ) ) {
			switch ( $this->get_bulk_requested_plugin_group_slug() ) {
				case 'required':
					$word_plugins = esc_html__( 'required plugins', 'nice-framework' );
					break;
				case 'recommended':
					$word_plugins = esc_html__( 'recommended plugins', 'nice-framework' );
					break;
				default :
					$word_plugins = esc_html__( 'plugins', 'nice-framework' );
					break;
			}

			$this->print_bulk_output_content(
				sprintf( esc_html__( 'Currently, no %1$s need to be updated.', 'nice-framework' ), $word_plugins )
			);

			return;
		}

		/**
		 * Load dependencies.
		 */
		if ( ! class_exists( 'Nice_TGMPA_Bulk_Installer' ) ) {
			nice_loader( 'engine/admin/classes/class-nice-tgmpa-bulk-installer.php' );
		}
		if ( ! class_exists( 'Nice_TGMPA_Bulk_Installer_Skin' ) ) {
			nice_loader( 'engine/admin/classes/class-nice-tgmpa-bulk-installer-skin.php' );
		}

		// Initialize the installer.
		$installer = new Nice_TGMPA_Bulk_Installer(
			new Nice_TGMPA_Bulk_Installer_Skin(
				array(
					'url'          => esc_url_raw( $this->get_tgmpa_url() ),
					'nonce'        => 'bulk-plugins',
					'names'        => $plugins_names,
					'install_type' => 'update',
				)
			)
		);

		// Adjust source directory.
		add_filter( 'upgrader_source_selection', array( $this, 'maybe_adjust_source_dir' ), 1, 3 );
		// Remove footer actions.
		add_filter( 'tgmpa_update_bulk_plugins_complete_actions', '__return_empty_array', 999 );

		// Inject plugins data into updates transient.
		$this->inject_update_info( $plugins_data );

		// Execute bulk update.
		$installer->bulk_upgrade( $plugins_paths, array(
			'clear_destination'           => true,
			'abort_if_destination_exists' => false,
			'clear_working'               => true,
			'is_multi'                    => true,
			'clear_update_cache'          => true,
		) );

		// Remove added filters.
		remove_filter( 'upgrader_source_selection', array( $this, 'maybe_adjust_source_dir' ), 1 );
		remove_filter( 'tgmpa_update_bulk_plugins_complete_actions', '__return_empty_array', 999 );

		// Refresh plugin data.
		$this->refresh_plugins_data();
	}

	/**
	 * Helper method to activate all the given plugins.
	 *
	 * @since 2.0
	 *
	 * @param array $plugins
	 */
	public function process_bulk_activate( $plugins ) {
		$plugins_names = array();
		$plugins_paths = array();

		foreach ( $plugins as $plugin_slug => $plugin ) {
			// Skip invalid plugins.
			if ( ! ( $plugin instanceof Nice_TGM_Plugin ) ) {
				continue;
			}

			// Skip not inactive plugins.
			if ( ! $plugin->is_inactive() ) {
				continue;
			}

			// Store plugin name and valid installation path.
			$plugins_names[ $plugin_slug ] = $plugin->get_name();
			$plugins_paths[ $plugin_slug ] = $plugin->get_path();
		}

		// Return early with a message if we have no plugins to activate.
		if ( empty( $plugins_names ) || empty( $plugins_paths ) ) {
			switch ( $this->get_bulk_requested_plugin_group_slug() ) {
				case 'required':
					$word_plugins = esc_html__( 'required plugins', 'nice-framework' );
					break;
				case 'recommended':
					$word_plugins = esc_html__( 'recommended plugins', 'nice-framework' );
					break;
				default :
					$word_plugins = esc_html__( 'plugins', 'nice-framework' );
					break;
			}

			$this->print_bulk_output_content(
				sprintf( esc_html__( 'Currently, no %1$s need to be activated.', 'nice-framework' ), $word_plugins )
			);

			return;
		}

		// Execute bulk activation.
		activate_plugins( $plugins_paths );

		// Verify which plugins could be activated.
		$plugins_activated = array();
		$plugins_error     = array();

		foreach ( $plugins_paths as $plugin_slug => $plugin_path ) {
			if ( is_plugin_active( $plugin_path ) ) {
				/**
				 * @hook nice_tgmpa_plugin_activated_{$plugin_slug}
				 *
				 * Hook here to execute actions after activating a specific plugin.
				 */
				do_action( "nice_tgmpa_plugin_activated_{$plugin_slug}", $plugins[ $plugin_slug ] );

				$plugins_activated[ $plugin_slug ] = $plugins_names[ $plugin_slug ];
			} else {
				$plugins_error[ $plugin_slug ] = $plugins_names[ $plugin_slug ];
			}
		}

		// Print the success output content.
		if ( ! empty( $plugins_activated ) ) {
			$this->print_bulk_output_content(
				esc_html__( 'The following plugins have been successfully activated:', 'nice-framework' ),
				$plugins_activated
			);
		}

		// Print the error output content.
		if ( ! empty( $plugins_error ) ) {
			$this->print_bulk_output_content(
				sprintf( '<strong>%s</strong>', esc_html__( 'ERROR:', 'nice-framework' ) ) . ' ' . esc_html__( 'The following plugins could not activated:', 'nice-framework' ),
				$plugins_error
			);
		}
	}

	/**
	 * Helper method to deactivate all the given plugins.
	 *
	 * @since 2.0
	 *
	 * @param array $plugins
	 */
	public function process_bulk_deactivate( $plugins ) {
		$plugins_names = array();
		$plugins_paths = array();

		foreach ( $plugins as $plugin_slug => $plugin ) {
			// Skip invalid plugins.
			if ( ! ( $plugin instanceof Nice_TGM_Plugin ) ) {
				continue;
			}

			// Skip not active plugins.
			if ( ! $plugin->is_active() ) {
				continue;
			}

			// Store plugin name and valid installation path.
			$plugins_names[ $plugin_slug ] = $plugin->get_name();
			$plugins_paths[ $plugin_slug ] = $plugin->get_path();
		}

		// Return early with a message if we have no plugins to deactivate.
		if ( empty( $plugins_names ) || empty( $plugins_paths ) ) {
			switch ( $this->get_bulk_requested_plugin_group_slug() ) {
				case 'required':
					$word_plugins = esc_html__( 'required plugins', 'nice-framework' );
					break;
				case 'recommended':
					$word_plugins = esc_html__( 'recommended plugins', 'nice-framework' );
					break;
				default :
					$word_plugins = esc_html__( 'plugins', 'nice-framework' );
					break;
			}

			$this->print_bulk_output_content(
				sprintf( esc_html__( 'Currently, no %1$s need to be deactivated.', 'nice-framework' ), $word_plugins )
			);

			return;
		}

		// Execute bulk activation.
		deactivate_plugins( $plugins_paths );

		// Verify which plugins could be deactivated.
		$plugins_deactivated = array();
		$plugins_error       = array();

		foreach ( $plugins_paths as $plugin_slug => $plugin_path ) {
			if ( ! is_plugin_active( $plugin_path ) ) {
				/**
				 * @hook nice_tgmpa_plugin_deactivated_{$plugin_slug}
				 *
				 * Hook here to execute actions after deactivating a specific plugin.
				 */
				do_action( "nice_tgmpa_plugin_deactivated_{$plugin_slug}", $plugins[ $plugin_slug ] );

				$plugins_deactivated[ $plugin_slug ] = $plugins_names[ $plugin_slug ];
			} else {
				$plugins_error[ $plugin_slug ] = $plugins_names[ $plugin_slug ];
			}
		}

		// Print the success output content.
		if ( ! empty( $plugins_deactivated ) ) {
			$this->print_bulk_output_content(
				esc_html__( 'The following plugins have been successfully deactivated:', 'nice-framework' ),
				$plugins_deactivated
			);
		}

		// Print the error output content.
		if ( ! empty( $plugins_error ) ) {
			$this->print_bulk_output_content(
				sprintf( '<strong>%s</strong>', esc_html__( 'ERROR:', 'nice-framework' ) ) . ' ' . esc_html__( 'The following plugins could not activated:', 'nice-framework' ),
				$plugins_error
			);
		}
	}

	/**
	 * Helper method to print the whole output of a bulk action.
	 *
	 * @since 2.0
	 *
	 * @param string $title
	 * @param string $message
	 * @param array  $plugin_names
	 */
	protected function print_bulk_output( $title, $message, $plugin_names = array() ) {
		if ( ! $this->can_print_bulk_output() ) {
			return;
		}

		$this->print_bulk_output_header( $title );
		$this->print_bulk_output_content( $message, $plugin_names );
		$this->print_bulk_output_footer();
	}

	/**
	 * Helper method to print the output header of a bulk action.
	 *
	 * @since 2.0
	 *
	 * @param string $title
	 */
	protected function print_bulk_output_header( $title ) {
		if ( ! $this->can_print_bulk_output() ) {
			return;
		}

		printf( '<h3>%s</h3>', esc_html( $title ) );

		if ( ! empty( $this->demo_pack ) ) {
			printf( '<h4>%s</h4>', sprintf( esc_html__( 'Demo: %s', 'nice-framework' ), esc_html( $this->demo_pack->get_name() ) ) );
		}
	}

	/**
	 * Helper method to print the output content of a bulk action.
	 *
	 * @since 2.0
	 *
	 * @param string $message
	 * @param array  $plugin_names
	 */
	protected function print_bulk_output_content( $message, $plugin_names = array() ) {
		if ( ! $this->can_print_bulk_output() ) {
			return;
		}

		printf( '<p>%s</p>', esc_html( $message ) );

		if ( ! empty( $plugin_names ) ) {
			printf( '<ul>' );

			foreach ( $plugin_names as $plugin_name ) {
				printf( '<li><strong>%s</strong></li>', esc_html( $plugin_name ) );
			}

			printf( '</ul>' );
		}
	}

	/**
	 * Helper method to print the output footer of a bulk action.
	 *
	 * @since 2.0
	 */
	protected function print_bulk_output_footer() {
		if ( ! $this->can_print_bulk_output() ) {
			return;
		}

		printf( '<p><a href="%1$s" target="_parent">%2$s</a></p>', esc_url( $this->get_tgmpa_url() ), esc_html( $this->strings['return'] ) );
	}

	/**
	 * Obtain whether or not the output of the bulk process should be printed.
	 *
	 * @since 2.0
	 */
	protected function can_print_bulk_output() {
		/**
		 * @hook nice_tgmpa_print_bulk_output
		 *
		 * Hook here to avoid printing the bulk process output.
		 *
		 * @since 2.0
		 */
		return apply_filters( 'nice_tgmpa_print_bulk_output', true );
	}


	/** ==========================================================================
	 *  Notices.
	 *  ======================================================================= */

	/**
	 * Replace default action links in notices.
	 *
	 * since 2.0
	 *
	 * @param $action_links
	 *
	 * @return string
	 */
	public function tgmpa_notice_action_links( $action_links ) {
		if ( ( 'plugins' === nice_admin_get_current_page() ) && ! empty( $this->demo_pack ) ) {
			$all_text = sprintf( esc_html__( 'Install %s demo plugins', 'nice-framework' ), $this->demo_pack->get_name() );
		} else {
			$all_text = esc_html__( 'Install plugins', 'nice-framework' );
		}

		return array(
			'all'     => '<a href="' . $this->get_tgmpa_url() . '">' . $all_text . '</a>',
			'dismiss' => '<a class="nice-notice-dismiss" href="#">' . esc_html( $this->strings['dismiss'] ) . '</a>',
		);
	}

	/**
	 * Add our notice class.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	protected function get_admin_notice_class() {
		$admin_notice_class  = parent::get_admin_notice_class();
		$admin_notice_class .= ' nice-wp-notice';

		return trim( $admin_notice_class );
	}

	/**
	 * Callback for the notice dismissal request.
	 *
	 * @since 2.0
	 */
	public function dismiss_notice_callback() {
		if ( ! ( nice_doing_ajax() && ( 'wp_ajax_nice_tgmpa_dismiss_notice' === current_filter() ) ) ) {
			return;
		}

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'play-nice' ) ) {
			return;
		}

		// Obtain the ID of this instance.
		$instance_id = 'nicethemes_' . $this->system_status->get_nice_theme_slug();

		// Dismiss notice.
		update_user_meta( get_current_user_id(), 'tgmpa_dismissed_notice_' . $instance_id, 1 );
	}


	/** ==========================================================================
	 *  Demo pack related methods.
	 *  ======================================================================= */

	/**
	 * Set the demo pack, if passed as a query parameter.
	 *
	 * @since 2.0
	 */
	public function set_demo_pack() {
		// Obtain demo slug.
		if ( nice_doing_ajax() && isset( $_POST['action'] ) && ( 'nice_theme_import_demo_pack' === $_POST['action'] ) ) {
			$demo_slug = isset( $_POST['demo_slug'] ) ? strip_tags( $_POST['demo_slug'] ) : '';
		} else {
			$demo_slug = isset( $_REQUEST['demo'] ) ? strip_tags( $_REQUEST['demo'] ) : '';
		}

		// Validate demo slug.
		if ( empty( $demo_slug ) ) {
			return;
		}

		// Obtain demo pack.
		$demo_pack = nice_theme_obtain_demo_pack( $demo_slug );
		if ( is_wp_error( $demo_pack ) ) {
			return;
		}

		// Save demo pack.
		$this->demo_pack = $demo_pack;

		// Add admin notice.
		add_action( 'admin_notices', array( $this, 'demo_pack_admin_notice' ) );

		// Customize strings.
		add_action( 'tgmpa_register', array( $this, 'demo_pack_custom_strings' ), 9999 );

		// Customize action links.
		add_filter( 'tgmpa_notice_action_links', array( $this, 'demo_pack_tgmpa_notice_action_links' ), 20 );
	}

	/**
	 * Helper method to print a notice when we have a demo pack.
	 *
	 * @since 2.0
	 */
	public function demo_pack_admin_notice() {
		// Return early if we're not in the Plugins page.
		if ( 'plugins' !== nice_admin_get_current_page() ) {
			return;
		}

		// Return early if an action is ongoing.
		if ( ! empty( $_REQUEST['plugin'] ) || ! empty( $_GET['tgmpa-bulk-actions'] ) || ! empty( $_GET['tgmpa-bulk-plugins'] ) ) {
			return;
		}

		if ( ! empty( $this->demo_pack ) ) {
			?>
			<div id="demo-plugins-nag" class="updated nice-wp-notice settings-error notice">
				<p>
					<strong>
						<span><?php printf( esc_html__( 'Before installing the %s demo, the following plugins should be installed, updated and activated.', 'nice-framework' ), '<em>' . esc_html( $this->demo_pack->get_name() ) . '</em>' ); ?></span><br />
						<span><?php esc_html_e( "Otherwise, you won't obtain its full functionality, and it also may not work as expected.", 'nice-framework' ); ?></span><br />
						<span><a href="<?php echo esc_url( add_query_arg( 'demo', $this->demo_pack->get_slug(), nice_admin_page_get_link( 'demos' ) ) ); ?>"><?php printf( esc_html__( 'View %s demo details', 'nice-framework' ), '<em>' . esc_html( $this->demo_pack->get_name() ) . '</em>' ) ; ?></a> | <a href="<?php echo esc_url( nice_admin_page_get_link( 'plugins' ) ); ?>"><?php esc_html_e( 'View the full list of plugins', 'nice-framework' ); ?></a></span>
					</strong>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Helper method to customize strings when we have a demo pack.
	 *
	 * @since 2.0
	 */
	public function demo_pack_custom_strings() {
		// Return early if we're not in the Plugins page.
		if ( 'plugins' !== nice_admin_get_current_page() ) {
			return;
		}

		if ( ! empty( $this->demo_pack ) ) {
			$this->strings['return'] = sprintf( esc_html__( 'Return to %s demo plugin installer', 'nice-framework' ), $this->demo_pack->get_name() );
		}
	}

	/**
	 * Helper method to default action links in notices.
	 *
	 * since 2.0
	 *
	 * @param $action_links
	 *
	 * @return string
	 */
	public function demo_pack_tgmpa_notice_action_links( $action_links ) {
		// Return early if we're not in the Plugins page.
		if ( 'plugins' !== nice_admin_get_current_page() ) {
			return $action_links;
		}

		if ( ! empty( $this->demo_pack ) ) {
			$action_links['all'] = '<a href="' . $this->get_tgmpa_url() . '">' . sprintf( esc_html__( 'Install %s demo plugins', 'nice-framework' ), $this->demo_pack->get_name() ) . '</a>';
		}

		return $action_links;
	}


	/** ==========================================================================
	 *  Auxiliary methods.
	 *  ======================================================================= */

	/**
	 * Helper method to refresh plugins data.
	 *
	 * @since 2.0
	 */
	public function refresh_plugins_data() {
		// Clear plugins cache.
		wp_clean_plugins_cache( true );

		// Fetch plugin data from WordPress' repository.
		wp_update_plugins();

		// Reset system status plugin information.
		$this->system_status->reset_plugins();
	}

	/**
	 * Helper method to sort required plugins.
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	protected function uasort_tgmpa_plugins( $a, $b ) {
		$order_a = isset( $a['menu_order'] ) ? $a['menu_order'] : 0;
		$order_b = isset( $b['menu_order'] ) ? $b['menu_order'] : 0;

		if ( $order_a === $order_b ) {
			return 0;
		}

		return $order_a > $order_b ? 1 : -1;
	}
}
endif;
