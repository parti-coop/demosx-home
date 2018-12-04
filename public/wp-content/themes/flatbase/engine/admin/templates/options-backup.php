<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Default content.
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Initialize System Status handler.
$system_status = nice_admin_system_status();

// Obtain export type.
if ( isset( $_POST['export-type'] ) ) {
	$export_type = esc_attr( $_POST['export-type'] );
}

?>

<div class="wrap">

	<div class="grid">

		<div class="columns-2">

			<div class="nice-box">

				<h3><?php esc_html_e( 'Import Theme Options', 'nice-framework' ); ?></h3>

				<p><?php esc_html_e( 'If you have options in a backup file on your computer, this form can import those into this site. To get started, upload your backup file to import from below.', 'nice-framework' ); ?></p>

				<div class="form-wrap">
					<form enctype="multipart/form-data" method="post" action="<?php echo esc_url( nice_admin_page_get_link( 'options_backup' ) ); ?>">
						<?php wp_nonce_field( 'nice-options-import' ); ?>
						<label for="nice-options-import-file"><?php printf( esc_html__( 'Upload File: (Maximum Size: %s)', 'nice-framework' ), esc_attr( $system_status->get_formatted_php_post_max_size() ) ); ?></label>
						<input type="file" id="nice-options-import-file" name="nice-options-import-file" size="25" />
						<input type="hidden" name="nice-options-import" value="1" />
						<input type="submit" class="button" value="<?php esc_html_e( 'Upload File and Import', 'nice-framework' ); ?>" />
					</form>
				</div><!--/.form-wrap-->

			</div>
		</div>

		<div class="columns-2">

			<div class="nice-box">

				<h3><?php esc_html_e( 'Export Theme Options', 'nice-framework' ); ?></h3>

				<p><?php esc_html_e( 'When you click the button below, a text file (.json) will be created for you to save to your computer.', 'nice-framework' ); ?></p>
				<p><?php printf( esc_html__( 'This text file can be used to restore your options here on "%s", or to easily setup another website with the same options.', 'nice-framework' ), esc_attr( get_bloginfo( 'name' ) ) ); ?></p>

				<form method="post" action="<?php echo esc_url( nice_admin_page_get_link( 'options_backup' ) ); ?>">
					<?php wp_nonce_field( 'nice-options-export' ); ?>
					<input type="hidden" name="nice-options-export" value="1" />
					<input type="submit" class="button" value="<?php esc_html_e( 'Download Export File', 'nice-framework' ); ?>" />
				</form>
			</div>

		</div>
	</div>


</div><!--/.wrap-->
