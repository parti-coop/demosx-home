<?php
/**
 * NiceThemes Framework Theme Updater
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'EDD_Theme_Updater' ) ) :
	nice_loader( 'engine/admin/lib/edd-theme-updater/theme-updater-class.php' );
endif;

if ( ! class_exists( 'Nice_Theme_Updater' ) ) :
/**
 * Class Nice_Theme_Updater
 *
 * Customize EDD_Theme_Updater class.
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   2.0
 */
class Nice_Theme_Updater extends EDD_Theme_Updater {

	/** ==========================================================================
	 *  Properties.
	 *  ======================================================================= */

	/**
	 * Remote URL for the latest version of the framework.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $remote_framework_url = null;

	/**
	 * Remote URL for the changelog.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $remote_changelog_url = null;

	/**
	 * Framework version.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $framework_version = null;

	/**
	 * Theme updater admin handler.
	 *
	 * @since 2.0
	 *
	 * @var Nice_Theme_Updater_Admin
	 */
	public $updater_admin = null;

	/**
	 * File system method.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $file_system_method = null;

	/**
	 * File system credentials.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $file_system_credentials = null;

	/**
	 * File system handler.
	 *
	 * @since 2.0
	 *
	 * @var WP_Filesystem_Base
	 */
	protected $file_system = null;

	/**
	 * WordPress' upgrades handler.
	 *
	 * @since 2.0
	 *
	 * @var Theme_Upgrader
	 */
	protected $wp_upgrader = null;

	/**
	 * System status handler.
	 *
	 * @since 2.0
	 *
	 * @var Nice_Admin_System_Status
	 */
	public $system_status = null;


	/** ==========================================================================
	 *  Getters & Setters.
	 *  ======================================================================= */

	/**
	 * Obtain file system handler.
	 *
	 * @since 2.0
	 *
	 * @return WP_Filesystem_Base
	 */
	public function get_file_system() {
		if ( is_null( $this->file_system ) ) {
			$this->file_system = WP_Filesystem( $this->file_system_credentials );
		}

		return $this->file_system;
	}


	/** ==========================================================================
	 *  Constructing methods.
	 *  ======================================================================= */

	/**
	 * Obtain a Nice_Theme_Updater object.
	 *
	 * The instance is saved to a static variable, so it can be retrieved
	 * later without needing to be reinitialized.
	 *
	 * @since 2.0
	 *
	 * @param array $args
	 * @param array $strings
	 *
	 * @return Nice_Theme_Updater
	 */
	public static function obtain( $args = array(), $strings = array() ) {
		static $theme_updater = null;

		if ( is_null( $theme_updater ) ) {
			$theme_updater = new self( $args, $strings );
		}

		return $theme_updater;
	}

	/**
	 * Custom constructor.
	 *
	 * @since 2.0
	 *
	 * @param array $args
	 * @param array $strings
	 */
	function __construct( $args = array(), $strings = array() ) {
		/**
		 * Obtain handlers.
		 */
		$this->system_status = Nice_Admin_System_Status::obtain();
		$this->updater_admin = Nice_Theme_Updater_Admin::obtain();

		/**
		 * Initialize properties.
		 */
		$use_intranet = false;
		$updates_url  = $use_intranet ? 'http://intranet.nicethemes.com/dev' : NICE_UPDATES_URL;

		$this->remote_framework_url    = $updates_url . '/framework/framework.zip';
		$this->remote_changelog_url    = $updates_url . '/framework/changelog.txt';
		$this->framework_version       = isset( $args['framework_version'] ) ? $args['framework_version'] : '';
		$this->file_system_method      = get_filesystem_method();
		$this->file_system_credentials = isset( $_POST['nice_ftp_credentials'] ) ? $_POST['nice_ftp_credentials'] : '';

		/**
		 * Call parent constructor.
		 */
		parent::__construct( $args, $strings );

		/**
		 * Add hooks.
		 */
		if ( ! nice_doing_ajax() && is_admin() ) {
			global $pagenow;

			// Check if we are in the update process page.
			if ( 'update.php' === $pagenow ) {
				// Framework update process.
				add_action( 'update-custom_upgrade-framework', array( $this, 'framework_update' ) );

				// Link to updates page after the process.
				add_filter( 'install_theme_complete_actions',     array( $this, 'theme_complete_actions' ) );
				add_filter( 'update_theme_complete_actions',      array( $this, 'theme_complete_actions' ) );
				add_filter( 'update_bulk_theme_complete_actions', array( $this, 'theme_complete_actions' ) );

			// Check if we are in the themes page.
			} elseif ( 'themes.php' === $pagenow ) {
				// Alter standard theme list.
				add_filter( 'wp_prepare_themes_for_js', array( $this, 'prepare_themes_for_js' ) );

			} else {
				// Show update nags.
				add_action('load-' . $pagenow, array( $this, 'load_themes_screen' ) );
			}

			// Process nag ignore requests.
			add_action( 'load-' . $pagenow, array( $this, 'update_nag_ignore' ) );
			add_action( 'load-' . $pagenow, array( $this, 'framework_update_nag_ignore' ) );
		}
	}


	/** ==========================================================================
	 *  API.
	 *  ======================================================================= */

	/**
	 * Obtain theme update transient.
	 *
	 * @since 2.0
	 *
	 * @return stdClass
	 */
	function get_theme_update_transient() {
		$api_response = get_transient( $this->response_key );

		if ( false === $api_response ) {
			$this->check_for_update();
		}

		return $api_response;
	}

	/**
	 * Obtain theme's latest version.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	function get_latest_version() {
		static $latest_version = null;

		if ( is_null( $latest_version ) ) {
			// Force transient to be reset.
			if ( isset( $_REQUEST['force-check'] ) && nice_bool( wp_unslash( $_REQUEST['force-check'] ) ) ) {
				$this->delete_theme_update_transient();
			}

			$api_response = $this->get_theme_update_transient();

			if ( false === $api_response ) {
				$latest_version = '';
			} else {
				$latest_version = $api_response->new_version;
			}
		}

		return $latest_version;
	}

	/**
	 * Obtain whether or not there is a theme update available.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	function has_update() {
		static $has_update = null;

		if ( is_null( $has_update ) ) {
			$has_update = version_compare( $this->get_latest_version(), $this->updater_admin->get_version(), '>' );
		}

		return $has_update;
	}


	/** ==========================================================================
	 *  Framework.
	 *  ======================================================================= */

	/**
	 * Obtain framework's latest version.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	function get_framework_latest_version() {
		static $framework_latest_version = null;

		if ( is_null( $framework_latest_version ) ) {

			// Force transient to be reset.
			if ( isset( $_REQUEST['force-check'] ) && nice_bool( wp_unslash( $_REQUEST['force-check'] ) ) ) {
				delete_transient( 'nice_framework_latest_version' );
			}

			$framework_latest_version = get_transient( 'nice_framework_latest_version' );

			if ( false === $framework_latest_version ) {
				$temp_file_path = download_url( $this->remote_changelog_url );

				if ( ! is_wp_error( $temp_file_path ) && $file_contents = file( $temp_file_path ) ) {
					foreach ( $file_contents as $line_num => $line ) {
						$current_line = $line;

						if ( $line_num > 1 ) {
							if ( preg_match( '/^[=]/', $line ) ) {
								$current_line = substr( $current_line, 0, strpos( $current_line, '(' ) ); // compatible with php4
								$current_line = preg_replace( '~[^0-9,.]~', '', $current_line );
								$framework_latest_version = $current_line;

								break;
							}
						}
					}

					unlink( $temp_file_path );
					update_option( 'nice_framework_remote_version', $framework_latest_version );

				} else {
					$framework_latest_version = get_option( 'nice_framework_version' );
				}

				set_transient( 'nice_framework_latest_version', $framework_latest_version, HOUR_IN_SECONDS );
			}
		}

		return $framework_latest_version;
	}

	/**
	 * Obtain whether or not there is a framework update available.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	function framework_has_update() {
		static $framework_has_update = null;

		if ( is_null( $framework_has_update ) ) {
			$framework_has_update = version_compare( $this->get_framework_latest_version(), $this->system_status->get_nice_framework_version(), '>' );
		}

		return $framework_has_update;
	}

	/**
	 * Grab the last version of the framework, and unzip it in the theme directory.
	 *
	 * Based on WordPress' theme upgrader upgrade method.
	 *
	 * @see Theme_Upgrader::upgrade()
	 *
	 * @since 2.0
	 */
	function framework_update() {
		// Ensure current user can update.
		if ( ! current_user_can( 'update_themes' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to update themes for this site.', 'nice-framework') );
		}

		// Ensure the current site can be updated.
		if ( ! $this->can_update() ) {
			wp_die( sprintf( esc_html__( 'You need to <a href="%s" target="_blank">get a license</a> to obtain support and updates.', 'nice-framework'), esc_url( $this->updater_admin->shop_url ) ) );
		}

		// Obtain theme slug.
		$theme = $this->updater_admin->get_theme_slug();

		// Validate nonce.
		check_admin_referer( 'upgrade-framework_' . $theme );

		// Load required scripts.
		wp_enqueue_script( 'customize-loader' );
		wp_enqueue_script( 'updates' );

		// Define process title.
		global $title;
		$title = esc_html__( 'Update Nice Framework', 'nice-framework' );

		// Show admin header.
		require_once( ABSPATH . 'wp-admin/admin-header.php' );

		// Define process nonce and URL.
		$nonce = 'upgrade-framework_' . $theme;
		$url   = 'update.php?action=upgrade-framework&theme=' . urlencode( $theme );

		// Initialize WordPress' theme upgrader.
		$this->wp_upgrader = new Theme_Upgrader( new Theme_Upgrader_Skin( compact( 'title', 'nonce', 'url', 'theme' ) ) );
		$this->wp_upgrader->init();

		// Customize upgrader strings.
		$this->wp_upgrader->strings['up_to_date']          = esc_html__( 'The framework is at the latest version.', 'nice-framework' );
		$this->wp_upgrader->strings['no_package']          = esc_html__( 'Update package not available.', 'nice-framework' );
		$this->wp_upgrader->strings['downloading_package'] = esc_html__( 'Downloading update from NiceThemes server&#8230;', 'nice-framework' );
		$this->wp_upgrader->strings['unpack_package']      = esc_html__( 'Unpacking the update&#8230;', 'nice-framework' );
		$this->wp_upgrader->strings['remove_old']          = esc_html__( 'Removing the old version of the framework&#8230;', 'nice-framework' );
		$this->wp_upgrader->strings['remove_old_failed']   = esc_html__( 'Could not remove the old framework.', 'nice-framework' );
		$this->wp_upgrader->strings['process_failed']      = esc_html__( 'Framework update failed.', 'nice-framework' );
		$this->wp_upgrader->strings['process_success']     = esc_html__( 'Nice Framework updated successfully.', 'nice-framework' );

		// Check for a new version of the Nice Framework.
		if ( $this->framework_has_update() ) {

			// Handle maintenance mode our own way.
			add_filter( 'upgrader_pre_install', array( $this, 'current_before' ), 10, 2 );
			add_filter( 'upgrader_post_install', array( $this, 'current_after' ), 10, 2 );

			/**
			 * Apply the framework update.
			 */
			$this->wp_upgrader->run( array(
				'package'           => $this->remote_framework_url,
				'destination'       => $this->system_status->get_nice_framework_path(),
				'clear_destination' => true,
				'clear_working'     => true,
				'hook_extra'        => array(
					'theme'  => $theme,
					'type'   => 'framework',
					'action' => 'update',
				),
			) );

			// Remove our maintenance mode handling.
			remove_filter( 'upgrader_pre_install',  array( $this, 'current_before' ) );
			remove_filter( 'upgrader_post_install', array( $this, 'current_after' ) );

		} else {
			/**
			 * Let user know about not needing to update.
			 */
			$this->wp_upgrader->skin->before();
			$this->wp_upgrader->skin->set_result(false);
			$this->wp_upgrader->skin->error('up_to_date');
			$this->wp_upgrader->skin->after();
		}

		// Show admin footer.
		include(ABSPATH . 'wp-admin/admin-footer.php');
	}


	/** ==========================================================================
	 *  UI.
	 *  ======================================================================= */

	/**
	 * Show update nags.
	 *
	 * @since 2.0
	 */
	function load_themes_screen() {
		add_action( 'admin_notices', array( &$this, 'framework_update_nag' ) );
		add_action( 'admin_notices', array( &$this, 'update_nag' ) );
	}

	/**
	 * Link to updates page after the process.
	 *
	 * @since 2.0
	 *
	 * @param $actions
	 *
	 * @return array
	 */
	function theme_complete_actions( $actions ) {
		$custom_actions = array(
			'nice_updates_page' => '<a target="_parent" href="' . $this->updater_admin->page_url . '">' . sprintf( esc_html__( 'Return to %1$s', 'nice-framework' ), nice_admin_page_get_page_title( 'support' ) ) . '</a>',
		);

		if ( ! empty( $this->updater_admin->demo_pack ) ) {
			$custom_actions['nice_demo_details_page'] = '<a target="_parent" href="' . add_query_arg( 'demo', $this->updater_admin->demo_pack->get_slug(), nice_admin_page_get_link( 'demos' ) ) . '">' . sprintf( esc_html__( 'Return to %1$s demo details', 'nice-framework' ), $this->updater_admin->demo_pack->get_name() ) . '</a>';
		}

		return $custom_actions;
	}

	/**
	 * Alter standard theme list.
	 *
	 * @since 2.0
	 *
	 * @param $prepared_themes
	 *
	 * @return array
	 */
	function prepare_themes_for_js( $prepared_themes ) {
		// Obtain theme slug.
		$theme = $this->updater_admin->get_theme_slug();

		if ( $prepared_themes[ $theme ]['hasUpdate'] ) {
			// Theme updates.
			if ( $this->has_update() ) {
				$new_version = $this->get_latest_version();

				if ( $new_version ) {
					$update_string = sprintf(
						esc_html( $this->updater_admin->get_string( 'update-available' ) ),
						sprintf( '<strong>%1$s %2$s</strong>', esc_html( $prepared_themes[ $theme ]['name'] ), esc_html( $new_version ) ),
						sprintf( '<a href="%s">', esc_url( $this->updater_admin->get_page_url() ) ),
						'</a>'
					);
				} else {
					$update_string = sprintf(
						esc_html( $this->updater_admin->get_string( 'update-available-themes' ) ),
						sprintf( '<strong>%1$s %2$s</strong>', esc_html( $prepared_themes[ $theme ]['name'] ), esc_html( $new_version ) ),
						sprintf( '<a href="%s">', esc_url( $this->updater_admin->get_page_url() ) ),
						'</a>'
					);
				}

				$prepared_themes[ $theme ]['update'] .= '<p>' . $update_string . '</p>';
			}
		}

		return $prepared_themes;
	}

	/**
	 * Print a notice when the theme needs an update.
	 *
	 * @since 2.0
	 */
	function update_nag() {
		if ( $this->has_update() ) {
			$meta_key = $this->updater_admin->get_theme_slug() . '_update_ignore_nag';

			// If we have a newer version, we show the dismissed notices again.
			if ( version_compare( $this->get_latest_version(), get_user_meta( get_current_user_id(), $meta_key, '>' ) ) ) {
				delete_user_meta( get_current_user_id(), $meta_key );

			// Return early if the user has dismissed the notice.
			} elseif ( get_user_meta( get_current_user_id(), $meta_key ) ) {
				return;
			}

			// Obtain dismiss URL.
			$screen      = get_current_screen();
			$dismiss_url = wp_nonce_url(
				admin_url( $screen->parent_file ),
				$meta_key,
				$meta_key . '_nonce'
			);

			// Print notice.
			echo '<div id="' . esc_attr( $this->updater_admin->get_theme_slug() ) . '-update-nag" class="nice-wp-notice update-nag">';
			printf(
				esc_html( $this->updater_admin->get_string( 'update-available-notice' ) ),
				sprintf( '<strong>%1$s %2$s</strong>', esc_html( $this->updater_admin->get_theme_name() ),esc_html( $this->get_latest_version() ) ),
				sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'support' ) ) ),
				'</a>',
				sprintf( '<a href="%s">', esc_url( $dismiss_url ) ),
				'</a>'
			);
			echo '</div>';
		}
	}

	/**
	 * Print a notice when the Nice Framework needs an update.
	 *
	 * @since 2.0
	 */
	function framework_update_nag() {
		if ( $this->framework_has_update() ) {
			$meta_key = 'nice_framework_update_ignore_nag';

			// If we have a newer version, we show the dismissed notices again.
			if ( version_compare( $this->get_framework_latest_version(), get_user_meta( get_current_user_id(), $meta_key, '>' ) ) ) {
				delete_user_meta( get_current_user_id(), $meta_key );

			// Return early if the user has dismissed the notice.
			} elseif ( get_user_meta( get_current_user_id(), $meta_key ) ) {
				return;
			}

			// Obtain dismiss URL.
			$screen      = get_current_screen();
			$dismiss_url = wp_nonce_url(
				admin_url( $screen->parent_file ),
				$meta_key,
				$meta_key . '_nonce'
			);

			// Print notice.
			echo '<div id="framework-update-nag" class="nice-wp-notice update-nag">';
			printf(
				esc_html( $this->strings['update-available-notice'] ),
				'Nice Framework ' . esc_html( $this->get_framework_latest_version() ),
				'<a href="' . esc_url( nice_admin_page_get_link( 'support' ) ) . '">',
				'</a>',
				'<a href="' . esc_url( $dismiss_url ) . '">',
				'</a>'
			);
			echo '</div>';
		}
	}

	function update_nag_ignore() {
		if (   isset( $_GET[ $this->updater_admin->get_theme_slug() . '_update_ignore_nag_nonce' ] )
		       && wp_verify_nonce( $_GET[ $this->updater_admin->get_theme_slug() . '_update_ignore_nag_nonce' ], $this->updater_admin->get_theme_slug() . '_update_ignore_nag' )
		) {
			add_user_meta( get_current_user_id(), $this->updater_admin->get_theme_slug() . '_update_ignore_nag', $this->get_latest_version(), true );
		}
	}

	function framework_update_nag_ignore() {
		if (   isset( $_GET['nice_framework_update_ignore_nag_nonce'] )
		       && wp_verify_nonce( $_GET['nice_framework_update_ignore_nag_nonce'], 'nice_framework_update_ignore_nag' )
		) {
			add_user_meta( get_current_user_id(), 'nice_framework_update_ignore_nag', $this->get_framework_latest_version(), true );
		}
	}


	/** ==========================================================================
	 *  Auxiliary methods.
	 *  ======================================================================= */

	/**
	 * Obtain whether or not the updates are available.
	 *
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function can_update() {
		return ( 'valid' === $this->system_status->get_nice_license_key_status() );
	}

	/**
	 * Turn on maintenance mode before attempting to upgrade the framework.
	 *
	 * Based on WordPress' theme upgrader method with the same name.
	 *
	 * @see Theme_Upgrader::current_before()
	 *
	 * @since 2.0
	 *
	 * @param bool|WP_Error $return
	 * @param array         $theme
	 *
	 * @return bool|WP_Error
	 */
	public function current_before( $return, $theme ) {
		// Return early if this is an error.
		if ( is_wp_error( $return ) ) {
			return $return;
		}

		// Return early if this is not a framework update.
		if ( ! ( isset( $theme['type'] ) && 'framework' === $theme['type'] ) ) {
			return $return;
		}

		// Return early if the update is not for the current theme.
		if ( ( isset($theme['theme'] ) ? $theme['theme'] : '' ) !== get_stylesheet() ) {
			return $return;
		}

		// Enable maintenance mode.
		if ( ! $this->wp_upgrader->bulk ) {
			$this->wp_upgrader->maintenance_mode( true );
		}

		return $return;
	}

	/**
	 * Turn off maintenance mode after upgrading the framework.
	 *
	 * Based on WordPress' theme upgrader method with the same name.
	 *
	 * @see Theme_Upgrader::current_after()

	 * @since 2.0
	 *
	 * @param bool|WP_Error $return
	 * @param array         $theme
	 *
	 * @return bool|WP_Error
	 */
	public function current_after( $return, $theme ) {
		// Return early if this is an error.
		if ( is_wp_error( $return ) ) {
			return $return;
		}

		// Return early if this is not a framework update.
		if ( ! ( isset( $theme['type'] ) && 'framework' === $theme['type'] ) ) {
			return $return;
		}

		// Return early if the update is not for the current theme.
		if ( ( isset( $theme['theme'] ) ? $theme['theme'] : '' ) !== get_stylesheet() ) {
			return $return;
		}

		// Disable maintenance mode.
		if ( ! $this->wp_upgrader->bulk ) {
			$this->wp_upgrader->maintenance_mode( false );
		}

		return $return;
	}
}
endif;
