<?php
/**
 * NiceFramework by NiceThemes.
 *
 * Functions related to the backup functions
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2016 NiceThemes
 * @since     1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


// If it's admin, load the logic and notices.
if ( is_admin() ) {
	add_action( 'admin_menu',    'nice_options_backup_logic',   12 );
	add_action( 'admin_notices', 'nice_options_backup_notices', 10 );
}


if ( ! function_exists( 'nice_options_backup_notices' ) ) :
/**
 * nice_options_backup_notices()
 *
 * Display the different notices according to results.
 *
 * @since 1.1.5
 *
 * @return void
 */

function nice_options_backup_notices() {
	if ( 'options_backup' === nice_admin_get_current_page() ) {
		echo '<div id="import-notice" class="nice-wp-notice updated"><p>' . sprintf( esc_html__( 'Please note that this backup manager backs up only your options and not your content. To backup your content, please use the %sWordPress Export Tool%s.', 'nice-framework' ), '<a href="' . esc_url( admin_url( 'export.php' ) ) . '">', '</a>' ) . '</p></div><!--/#import-notice .message-->' . "\n";

		if ( isset( $_GET['error'] ) && true === nice_bool( $_GET['error'] ) ) {
			echo '<div id="message" class="nice-wp-notice error"><p>' . esc_html__( 'There was a problem importing your options. Please Try again.', 'nice-framework' ) . '</p></div>';
		} elseif ( isset( $_GET['error-export'] ) && true === nice_bool( $_GET['error-export'] ) ) {
			echo '<div id="message" class="nice-wp-notice error"><p>' . esc_html__( 'There was a problem exporting your options. Please Try again.', 'nice-framework' ) . '</p></div>';
		} elseif ( isset( $_GET['invalid'] ) && true === nice_bool( $_GET['invalid'] ) ) {
			echo '<div id="message" class="nice-wp-notice error"><p>' . esc_html__( 'The import file you\'ve provided is invalid. Please try again.', 'nice-framework' ) . '</p></div>';
		} elseif ( isset( $_GET['imported'] ) && true === nice_bool( $_GET['imported'] ) ) {
			echo '<div id="message" class="nice-wp-notice updated"><p>' . sprintf( esc_html__( 'Options successfully imported. | Return to %sTheme Options%s', 'nice-framework' ), '<a href="' . nice_admin_page_get_main_link() . '">', '</a>' ) . '</p></div>';
		}
	}
}
endif;


if ( ! function_exists( 'nice_options_backup_logic' ) ) :
/**
 * nice_options_backup_logic()
 *
 * Given the params, call the import or export actions.
 *
 * @since 1.1.5
 * @return void
 */

function nice_options_backup_logic() {
	if ( ! isset( $_POST['nice-options-import'] ) && isset( $_POST['nice-options-export'] ) && ( true === nice_bool( $_POST['nice-options-export'] ) ) ) {
		nice_options_backup_export();
	}

	if ( ! isset( $_POST['nice-options-export'] ) && isset( $_POST['nice-options-import'] ) && ( true === nice_bool( $_POST['nice-options-import'] ) ) ) {
		// Redirect using result of import process.
		nice_options_backup_redirect( nice_options_backup_import() );
	}
}
endif;


if ( ! function_exists( 'nice_options_backup_export' ) ) :
/**
 * nice_options_backup_export()
 *
 * Get the current options and put them into a json file.
 *
 * @since 1.1.5
 * @return void
 */

function nice_options_backup_export() {
	check_admin_referer( 'nice-options-export' ); // Security check.

	$options = get_option( 'nice_options' );

	if ( ! $options ) {
		return;
	}

	// Add a custom marker.
	$options['nice-options-backup-validator'] = date( 'Y-m-d h:i:s' );

	// Generate the export file.
	$output = json_encode( (array) $options );

	header( 'Content-Description: File Transfer' );
	header( 'Cache-Control: public, must-revalidate' );
	header( 'Pragma: hack' );
	header( 'Content-Type: text/plain' );
	header( 'Content-Disposition: attachment; filename="nicethemes-theme-options-backup-' . date( 'Y-m-d-His' ) . '.json"' );
	header( 'Content-Length: ' . strlen( $output ) );

	echo $output;
	exit;
}
endif;


if ( ! function_exists( 'nice_options_backup_import' ) ) :
/**
 * nice_options_backup_import()
 *
 * Get the options from a well formatted .json
 * Put them in the DB.
 *
 * @since 1.1.5
 *
 * @param  string           $file_path Optional. Full path to a file.
 *
 * @return mixed|null|array
 */
function nice_options_backup_import( $file_path = null ) {
	// Check the nonce
	if ( is_null( $file_path ) ) {
		check_admin_referer( 'nice-options-import' );

		if ( ( ! isset( $_FILES['nice-options-import-file'] ) || empty( $_FILES['nice-options-import-file']['name'] ) ) ) {
			return null; // We can't import the settings without a settings file.
		}
	}

	/**
	 * Initialize WordPress' file system handler.
	 *
	 * @var WP_Filesystem_Base $wp_filesystem
	 */
	WP_Filesystem();
	global $wp_filesystem;

	// Extract file contents
	$file_name = ! is_null( $file_path ) ? $file_path : $_FILES['nice-options-import-file']['tmp_name'];
	$upload = $wp_filesystem->get_contents( $file_name );

	// Decode the JSON from the uploaded file
	$options = json_decode( $upload, true );

	// Check for errors
	if ( ! $options || ! empty( $_FILES['nice-options-import-file']['error'] ) ) {
		return array(
			'error' => 'true',
		);
	}

	// Make sure this is a valid backup file.
	if ( ! isset( $options['nice-options-backup-validator'] ) ) {
		return array(
			'invalid' => 'true',
		);
	} else {
		unset( $options['nice-options-backup-validator'] ); // Now that we've checked it, we don't need the field anymore.
	}

	// Make sure the options are saved to the global options collection as well.
	$nice_options = get_option( 'nice_options', array() );

	$has_updated = false; // If this is set to true at any stage, we update the main options collection.

	// Cycle through data, import settings
	foreach ( (array) $options as $key => $settings ) {
		$settings = maybe_unserialize( $settings ); // Unserialize serialized data before inserting it back into the database.

		// We can run checks using get_option(), as the options are all cached. See wp-includes/functions.php for more information.
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

	// Add success flag to result.
	return array(
		'imported' => 'true',
	);
}
endif;

if ( ! function_exists( 'nice_options_backup_redirect' ) ) :
/**
 * Redirect after backup with a result query.
 *
 * @since 2.0
 *
 * @param array $query
 */
function nice_options_backup_redirect( array $query = null ) {
	// Return early if we have a null query.
	if ( is_null( $query ) ) {
		return;
	}

	$url = nice_admin_page_get_link( 'options_backup' );

	// Add parameters to query.
	if ( ! empty( $query) ) {
		foreach ( $query as $key => $value ) {
			$url = add_query_arg( $key, $value, $url );
		}
	}

	wp_redirect( $url );
	exit;
}
endif;
