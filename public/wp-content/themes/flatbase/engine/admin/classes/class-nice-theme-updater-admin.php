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

if ( ! class_exists( 'EDD_Theme_Updater_Admin' ) ) :
	nice_loader( 'engine/admin/lib/edd-theme-updater/theme-updater-admin.php' );
endif;

if ( ! class_exists( 'Nice_Theme_Updater_Admin' ) ) :
/**
 * Class Nice_Theme_Updater_Admin
 *
 * Customize EDD_Theme_Updater_Admin class.
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   2.0
 */
class Nice_Theme_Updater_Admin extends EDD_Theme_Updater_Admin {

	/** ==========================================================================
	 *  Properties.
	 *  ======================================================================= */

	/**
	 * Shop URL.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	public $shop_url = null;

	/**
	 * Updates page URL.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	public $page_url = null;

	/**
	 * Remote updates URL.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	public $updates_url = null;

	/**
	 * Framework version.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	public $framework_version = null;

	/**
	 * Theme updater handler.
	 *
	 * @since 2.0
	 *
	 * @var Nice_Theme_Updater
	 */
	public $updater = null;

	/**
	 * Demo pack object.
	 *
	 * @since 2.0
	 *
	 * @var Nice_Theme_Demo_Pack
	 */
	public $demo_pack = null;

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
	 * Obtain theme name from parent class property.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_theme_name() {
		return $this->item_name;
	}

	/**
	 * Obtain theme slug from parent class property.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_theme_slug() {
		return $this->theme_slug;
	}

	/**
	 * Obtain theme version from parent class property.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Obtain a string from its key, from parent class property.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_string( $key ) {
		return isset( $this->strings[ $key ] ) ? $this->strings[ $key ] : '';
	}

	/**
	 * Obtain shop URL.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_shop_url() {
		return $this->shop_url;
	}

	/**
	 * Obtain updates page URL.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_page_url() {
		return $this->page_url;
	}

	/**
	 * Obtain remote updates URL.
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	public function get_updates_url() {
		return $this->updates_url;
	}

	/**
	 * Set the theme updater handler.
	 *
	 * (Since we're extending the parent method, this isn't a typical setter.)
	 *
	 * @since 2.0
	 */
	function updater() {
		// Ensure dependencies are loaded.
		if ( ! class_exists( 'Nice_Theme_Updater' )  ) {
			nice_loader( 'engine/admin/classes/class-nice-theme-updater.php' );
		}

		$this->updater = Nice_Theme_Updater::obtain(
			array(
				'remote_api_url'    => $this->remote_api_url,
				'version'           => $this->version,
				'framework_version' => $this->framework_version,
				'license'           => $this->system_status->get_nice_license_key(),
				'item_name'         => $this->item_name,
				'author'            => $this->author,
			),
			$this->strings
		);
	}


	/** ==========================================================================
	 *  Constructing methods.
	 *  ======================================================================= */

	/**
	 * Obtain a Nice_Theme_Updater_Admin object.
	 *
	 * The instance is saved to a static variable, so it can be retrieved
	 * later without needing to be reinitialized.
	 *
	 * @since 2.0
	 *
	 * @return Nice_Theme_Updater_Admin
	 */
	public static function obtain() {
		static $theme_updater_admin = null;

		if ( is_null( $theme_updater_admin ) ) {
			$theme_updater_admin = new self();
		}

		return $theme_updater_admin;
	}

	/**
	 * Extend parent class constructor.
	 *
	 * @since 2.0
	 */
	function __construct() {
		/**
		 * Obtain handlers.
		 */
		$this->system_status = Nice_Admin_System_Status::obtain();

		/**
		 * Initialize properties.
		 */
		$this->shop_url    = 'https://nicethemes.com/';
		$this->page_url    = nice_admin_page_get_link( 'support' );
		$this->updates_url = NICE_UPDATES_URL;

		// Maybe set the demo pack.
		$this->set_demo_pack();

		/**
		 * Call parent constructor.
		 */
		parent::__construct(
			// Config settings
			$config = array(
				'remote_api_url'    => $this->shop_url,
				'item_name'         => $this->system_status->get_real_theme_name(),
				'theme_slug'        => $this->system_status->get_real_theme_slug(),
				'version'           => $this->system_status->get_nice_theme_version(),
				'framework_version' => $this->system_status->get_nice_framework_version(),
				'author'            => 'NiceThemes',
				'download_id'       => '', // Optional, used for generating a license renewal link
				'renew_url'         => '', // Optional, allows for a custom license renewal link
			),

			// Strings
			$strings = array(
				'theme-license'             => esc_html__( 'Theme License', 'nice-framework' ),
				'enter-key'                 => esc_html__( 'Enter your theme license key.', 'nice-framework' ),
				'validate-key'              => esc_html__( 'Validate License Key', 'nice-framework' ),
				'license-key'               => esc_html__( 'License Key', 'nice-framework' ),
				'license-action'            => esc_html__( 'License Action', 'nice-framework' ),
				'deactivate-license'        => esc_html__( 'Deactivate License', 'nice-framework' ),
				'activate-license'          => esc_html__( 'Activate License', 'nice-framework' ),
				'status-unknown'            => esc_html__( 'License status is unknown.', 'nice-framework' ),
				'renew'                     => esc_html__( 'Renew?', 'nice-framework' ),
				'unlimited'                 => esc_html__( 'unlimited', 'nice-framework' ),
				'license-key-is-active'     => esc_html__( 'License key is active.', 'nice-framework' ),
				'expires%s'                 => esc_html__( 'Expires %s.', 'nice-framework' ),
				'%1$s/%2$-sites'            => esc_html__( 'You have %1$s / %2$s sites activated.', 'nice-framework' ),
				'license-key-expired-%s'    => esc_html__( 'License key expired %s.', 'nice-framework' ),
				'license-key-expired'       => esc_html__( 'License key has expired.', 'nice-framework' ),
				'license-keys-do-not-match' => esc_html__( 'License keys do not match.', 'nice-framework' ),
				'license-is-inactive'       => esc_html__( 'License is inactive.', 'nice-framework' ),
				'license-key-is-disabled'   => esc_html__( 'License key is disabled.', 'nice-framework' ),
				'site-is-inactive'          => esc_html__( 'Site is inactive.', 'nice-framework' ),
				'license-unknown'           => esc_html__( 'License is unknown.', 'nice-framework' ),
				'license-status-unknown'    => esc_html__( 'License status is unknown.', 'nice-framework' ),
				'update-notice'             => esc_html__( "Updating the theme will lose any customizations you have made to its code. 'Cancel' to stop, 'OK' to update.", 'nice-framework' ),
				'update-framework-notice'   => esc_html__( "Updating the framework will lose any customizations you have made to its code. 'Cancel' to stop, 'OK' to update.", 'nice-framework' ),
				'update-available'          => esc_html__( '%1$s is available. %2$sPlease update it now%3$s.', 'nice-framework' ),
				'update-available-notice'   => esc_html__( '%1$s is available. %2$sPlease update it now%3$s or %4$sdismiss this notice%5$s.', 'nice-framework' ),
				'update-available-themes'   => esc_html__( 'A new version of %1$s is available. %2$sPlease update it now%3$s.', 'nice-framework' ),
			)
		);

		/**
		 * We're not gonna use the default UI.
		 */
		remove_action( 'admin_menu', array( $this, 'license_menu' ) );

		/**
		 * Add demo pack related actions.
		 */
		if ( 'support' === nice_admin_get_current_page() ) {
			add_action( 'admin_notices', array( $this, 'demo_pack_admin_notice' ) );
		}
	}


	/**
	 * Set the demo pack, if passed as a parameter.
	 */
	public function set_demo_pack() {
		// Obtain demo slug.
		$demo_slug = isset( $_REQUEST['demo'] ) ? strip_tags( $_REQUEST['demo'] ) : '';
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

		// Adapt page url.
		$this->page_url = add_query_arg( 'demo', $this->demo_pack->get_slug(), $this->page_url );
	}


	/** ==========================================================================
	 *  License.
	 *  ======================================================================= */

	/**
	 * Activates the license key.
	 *
	 * @since 2.0
	 */
	function activate_license() {
		parent::activate_license();

		$this->updater->delete_theme_update_transient();
	}

	/**
	 * Deactivates the license key.
	 *
	 * @since 2.0
	 */
	function deactivate_license() {
		parent::deactivate_license();

		$this->updater->delete_theme_update_transient();
	}


	/** ==========================================================================
	 *  API.
	 *  ======================================================================= */

	/**
	 * Makes a call to the API.
	 *
	 * @since 2.0
	 *
	 * @param array $api_params
	 *
	 * @return array $response
	 */
	function get_api_response( $api_params ) {

		// Call the custom API.
		$response = wp_remote_post( $this->remote_api_url, array( 'timeout' => 180, 'sslverify' => false, 'body' => $api_params ) );

		// Make sure the response came back okay.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response = json_decode( wp_remote_retrieve_body( $response ) );

		return $response;
	}


	/** ==========================================================================
	 *  Disabled methods.
	 *  ======================================================================= */

	/**
	 * Disable parent class menu item.
	 *
	 * @since 2.0
	 */
	function license_menu() {}

	/**
	 * Disable parent class license page.
	 *
	 * @since 2.0
	 */
	function license_page() {}


	/** ==========================================================================
	 *  Auxiliary methods.
	 *  ======================================================================= */

	/**
	 * Helper method to print a notice when we have a demo pack.
	 *
	 * @since 2.0
	 */
	public function demo_pack_admin_notice() {
		if ( ! empty( $this->demo_pack ) ) {
			?>
			<div id="demo-theme-nag" class="updated nice-wp-notice settings-error notice">
				<p>
					<strong>
						<span><?php printf( esc_html__( 'Before you can install the %1$s demo, %2$s and %3$s must be updated.', 'nice-framework' ), '<em>' . esc_html( $this->demo_pack->get_name() ) . '</em>', esc_html( $this->system_status->get_nice_theme_name() ), 'Nice Framework' ); ?></span><br />
						<span><?php esc_html_e( 'You can update them both from the bottom of this page.', 'nice-framework' ); ?></span><br />
						<span><a href="<?php echo esc_url( add_query_arg( 'demo', $this->demo_pack->get_slug(), nice_admin_page_get_link( 'demos' ) ) ); ?>"><?php printf( esc_html__( 'View %s demo details', 'nice-framework' ), '<em>' . esc_html( $this->demo_pack->get_name() ) . '</em>' ) ; ?></a> | <a href="<?php echo esc_url( nice_admin_page_get_link( 'support' ) ); ?>"><?php printf( esc_html__( 'View default %s page', 'nice-framework' ), esc_html( nice_admin_page_get_page_title( 'support' ) ) ) ; ?></a></span>
					</strong>
				</p>
			</div>
			<?php
		}
	}
}
endif;
