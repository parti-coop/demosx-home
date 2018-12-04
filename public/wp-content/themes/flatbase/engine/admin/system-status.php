<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Functions related to the System Status handlers.
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_admin_system_status' ) ) :
/**
 * nice_admin_system_status()
 *
 * Obtain an instance of the Nice_Admin_System_Status class.
 *
 * @since 2.0
 *
 * @return Nice_Admin_System_Status
 */
function nice_admin_system_status() {
	return Nice_Admin_System_Status::obtain();
}
endif;

if ( ! function_exists( 'nice_admin_system_status_report' ) ) :
/**
 * nice_admin_system_status_report()
 *
 * Obtain an instance of the Nice_Admin_System_Status_Report class.
 *
 * @since 2.0
 *
 * @return Nice_Admin_System_Status_Report
 */

function nice_admin_system_status_report() {
	return Nice_Admin_System_Status_Report::obtain();
}
endif;

if ( ! function_exists( 'nice_admin_export_system_status_report_download' ) ) :
add_action( 'admin_menu', 'nice_admin_export_system_status_report_download', 20 );
/**
 * nice_admin_export_system_status_report_download()
 *
 * If requested, export a full system status report.
 *
 * @since 2.0
 */

function nice_admin_export_system_status_report_download() {
	if ( isset( $_POST['nice-system-status-report-download'] ) && ( true === nice_bool( $_POST['nice-system-status-report-download'] ) ) ) {
		check_admin_referer( 'nice-system-status-report-download' );

		// Initialize the System Status Report handler.
		$system_status_report = nice_admin_system_status_report();

		// Export a full report in JSON format.
		$system_status_report->export_json_report();
	}
}
endif;
