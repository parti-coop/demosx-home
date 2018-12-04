<?php
/**
 * NiceThemes Framework System Status
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
 * Class Nice_Admin_System_Status_Report
 *
 * Create reports using System Status information.
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   2.0
 */
class Nice_Admin_System_Status_Report {

	/** ==========================================================================
	 *  Properties.
	 *  ======================================================================= */

	/**
	 * System Status handler for system information.
	 *
	 * @since 2.0
	 *
	 * @var Nice_Admin_System_Status
	 */
	private $system_status = null;


	/** ==========================================================================
	 *  Constructing methods.
	 *  ======================================================================= */

	/**
	 * Setup initial data.
	 *
	 * @since 2.0
	 */
	public function __construct() {
		/**
		 * Initialize system status handler.
		 */
		$this->system_status = nice_admin_system_status();
	}

	/**
	 * Obtain a Nice_Admin_System_Status_Report object.
	 *
	 * The instance is saved to a static variable, so it can be retrieved
	 * later without needing to be reinitialized.
	 *
	 * @since 2.0
	 *
	 * @return Nice_Admin_System_Status_Report
	 */
	public static function obtain() {
		static $system_status_report = null;

		if ( is_null( $system_status_report ) ) {
			$system_status_report = new self();
		}

		return $system_status_report;
	}


	/** ==========================================================================
	 *  Report methods.
	 *  ======================================================================= */

	/**
	 * Obtain full report.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	public function get_report() {
		$report = array(
			'wp'      => $this->get_wp_report(),
			'server'  => $this->get_server_report(),
			'php'     => $this->get_php_report(),
			'mysql'   => $this->get_mysql_report(),
			'theme'   => $this->get_theme_report(),
			'plugins' => $this->get_plugins_report(),
			'users'   => $this->get_users_report(),
			'remote'  => $this->get_remote_report(),
			'license' => $this->get_license_report(),
		);

		return $report;
	}

	/**
	 * Obtain WordPress report.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	protected function get_wp_report() {
		$report = array(
			'url'             => array(
				'home_url' => $this->system_status->get_home_url(),
				'site_url' => $this->system_status->get_site_url(),
			),
			'version'         => $this->system_status->get_wp_version(),
			'required'        => $this->system_status->get_required_wp_version(),
			'recommended'     => $this->system_status->get_recommended_wp_version(),
			'multisite'       => $this->system_status->is_wp_multisite(),
			'locale'          => $this->system_status->get_wp_locale(),
			'post_types'      => $this->system_status->get_wp_post_types(),
			'taxonomies'      => $this->system_status->get_wp_taxonomies(),
			'mime_types'      => array(
				'mime_types'      => $this->system_status->get_wp_mime_types(),
				'file_extensions' => $this->system_status->get_wp_file_extensions(),
			),
			'uploads'         => array(
				'url'      => $this->system_status->get_wp_uploads_url(),
				'path'     => $this->system_status->get_wp_uploads_path(),
				'writable' => $this->system_status->is_wp_uploads_dir_writable(),
			),
			'rewrite_rules'   => $this->system_status->get_wp_rewrite_rules(),
			'memory_limit'    => array(
				'value'                 => $this->system_status->get_wp_memory_limit(),
				'value_formatted'       => $this->system_status->get_formatted_wp_memory_limit(),
				'recommended'           => $this->system_status->get_recommended_wp_memory_limit(),
				'recommended_formatted' => $this->system_status->get_formatted_recommended_wp_memory_limit(),
			),
			'max_upload_size' => array(
				'value'                 => $this->system_status->get_wp_max_upload_size(),
				'value_formatted'       => $this->system_status->get_formatted_wp_max_upload_size(),
				'recommended'           => $this->system_status->get_recommended_wp_max_upload_size(),
				'recommended_formatted' => $this->system_status->get_formatted_recommended_wp_max_upload_size(),
			),
			'debug_mode'      => $this->system_status->is_wp_debug_mode(),
			'importer'        => $this->system_status->is_wp_importer(),
		);

		return $report;
	}

	/**
	 * Obtain server report.
	 *
	 * @return array
	 */
	protected function get_server_report() {
		$report = array(
			'info'         => $this->system_status->get_server_info(),
			'timezone'     => array(
				'value'  => $this->system_status->get_server_timezone(),
				'is_utc' => $this->system_status->is_server_timezone_utc(),
			),
			'remote'       => array(
				'get'  => $this->system_status->is_wp_remote_get(),
				'post' => $this->system_status->is_wp_remote_post(),
			),
			'mod_security' => array(
				'value'       => $this->system_status->mod_security_enabled(),
				'recommended' => false,
			),
		);
		return $report;
	}

	/**
	 * Obtain PHP report.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	protected function get_php_report() {
		$report = array(
			'version'        => $this->system_status->get_php_version(),
			'recommended'    => $this->system_status->get_recommended_php_version(),
			'required'       => $this->system_status->get_required_php_version(),
			'phpinfo'        => array(
				'output' => $this->system_status->get_phpinfo(),
			),
			'max_input_vars' => array(
				'value'       => $this->system_status->get_php_max_input_vars(),
				'recommended' => $this->system_status->get_recommended_php_max_input_vars(),
			),
			'post_max_size'  => array(
				'value'                 => $this->system_status->get_php_post_max_size(),
				'value_formatted'       => $this->system_status->get_formatted_php_post_max_size(),
				'recommended'           => $this->system_status->get_recommended_php_post_max_size(),
				'recommended_formatted' => $this->system_status->get_formatted_recommended_php_post_max_size(),
			),
			'time_limit'     => array(
				'value'       => $this->system_status->get_php_time_limit(),
				'recommended' => $this->system_status->get_recommended_php_time_limit(),
			),
			'xdebug'         => array(
				'value'       => $this->system_status->xdebug_enabled(),
				'recommended' => false,
			),
		);

		return $report;
	}

	/**
	 * Obtain MySQL report.
	 *
	 * @return array
	 */
	protected function get_mysql_report() {
		$report = array(
			'version'     => $this->system_status->get_mysql_version(),
			'recommended' => $this->system_status->get_recommended_mysql_version(),
			'required'    => $this->system_status->get_required_mysql_version(),
		);

		return $report;
	}

	/**
	 * Obtain theme report.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	protected function get_theme_report() {
		$report = array(
			'slug'          => $this->system_status->get_nice_theme_slug(),
			'name'          => $this->system_status->get_nice_theme_name(),
			'version'       => $this->system_status->get_nice_theme_version(),
			'author'        => array(
				'url' => $this->system_status->get_nice_theme_author_url(),
			),
			'framework'     => array(
				'version' => $this->system_status->get_nice_framework_version(),
				'path'    => $this->system_status->get_nice_framework_path(),
			),
			'url'           => $this->system_status->get_nice_theme_url(),
			'path'          => $this->system_status->get_nice_theme_path(),
			'options'       => $this->system_status->get_nice_theme_options(),
			'demo_packs'    => array(
				'available' => $this->system_status->get_available_demo_packs(),
				'installed' => $this->system_status->get_installed_demo_packs(),
			),
			'child'         => false,
		);

		if ( $this->system_status->is_child_theme() ) {
			$report['child'] = array(
				'slug'          => $this->system_status->get_theme_slug(),
				'name'          => $this->system_status->get_theme_name(),
				'version'       => $this->system_status->get_theme_version(),
				'author'        => array(
					'url' => $this->system_status->get_theme_author_url(),
				),
				'path'          => $this->system_status->get_theme_path(),
			);
		}

		return $report;
	}

	/**
	 * Obtain plugins report.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	protected function get_plugins_report() {
		$report = array(
			'all' => $this->system_status->get_plugins(),
		);

		return $report;
	}

	/**
	 * Obtain plugins report.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	protected function get_users_report() {
		$report = array(
			'current'      => $this->system_status->get_wp_current_user(),
			'all'          => $this->system_status->get_wp_users(),
			'roles'        => $this->system_status->get_wp_roles(),
			'capabilities' => $this->system_status->get_wp_capabilities(),
		);

		// Remove user emails and password hashes. We don't need them.
		unset( $report['current']['data']['user_pass'] );
		unset( $report['current']['data']['user_email'] );
		foreach ( $report['all'] as &$user ) {
			unset( $user['data']['user_pass'] );
			unset( $user['data']['user_email'] );
		}

		return $report;
	}

	/**
	 * Obtain remote report.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	protected function get_remote_report() {
		$report = array(
			'nicethemes' => array(
				'updates_url' => $this->system_status->get_nice_updates_url(),
			),
			'aws'        => array(
				's3_endpoint'       => $this->system_status->get_aws_s3_endpoint(),
				's3_bucket_updates' => $this->system_status->get_aws_s3_bucket_updates(),
				's3_bucket_demos'   => $this->system_status->get_aws_s3_bucket_demos(),
			),
		);

		return $report;
	}

	/**
	 * Obtain license report.
	 *
	 * @since 2.0
	 *
	 * @return array
	 */
	protected function get_license_report() {
		$report = array(
			'key'    => $this->system_status->get_nice_license_key(),
			'status' => $this->system_status->get_nice_license_key_status(),
		);

		return $report;
	}


	/** ==========================================================================
	 *  Export methods.
	 *  ======================================================================= */

	/**
	 * Export full report in JSON format.
	 *
	 * @since 2.0
	 */
	public function export_json_report() {
		$report = $this->get_report();
		$output = json_encode( $report );

		header( 'Content-Description: File Transfer' );
		header( 'Cache-Control: public, must-revalidate' );
		header( 'Pragma: hack' );
		header( 'Content-Type: text/plain' );
		header( 'Content-Disposition: attachment; filename="nicethemes-system-status-report-' . date( 'Y-m-d-His' ) . '.json"' );
		header( 'Content-Length: ' . strlen( $output ) );

		echo $output;
		exit;
	}
}
