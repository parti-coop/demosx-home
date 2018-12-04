<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Theme Options content.
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$options    = get_option( 'nice_template' );
$interface  = nice_formbuilder( $options );
$nice_nonce = function_exists( 'wp_create_nonce' ) ? wp_create_nonce( 'nice-options-update' ) : '';
$reset_url  = wp_nonce_url( nice_admin_page_get_link(), 'nice_reset_options', 'nice_reset' );

?>

<div class="nice-container wrap <?php echo nice_is_mp6() ? 'is-mp6' : ''; ?>" id="nice-container" >

	<div id="nice-popup-save" class="nice-save-popup">
		<div class="nice-save-save"><?php esc_html_e( 'Changes saved successfully', 'nice-framework' ); ?></div>
	</div>

	<form action="" enctype="multipart/form-data" id="niceform" autocomplete="off">
		<?php
		// Add nonce for added security.
		if ( function_exists( 'wp_nonce_field' ) ) {
			wp_nonce_field( 'nice-options-update' );
		}

		$current_theme = wp_get_theme();

		if ( '' !== $nice_nonce ) : ?>
			<input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr( $nice_nonce ); ?>" />
		<?php endif; ?>

		<!-- BEGIN #header -->
		<div id="nice-header" class="clearfix">

			<div class="grid">
				<div class="columns-2">
					<div class="theme-logo">
						<h2><?php echo esc_html( $current_theme->get( 'Name' ) ); ?></h2>
						<span class="version"><?php esc_html_e( 'Version', 'nice-framework' ); ?> <a href="<?php echo esc_url( nice_admin_page_get_link( 'changelog' ) ); ?>" title="<?php esc_attr_e( 'View Changelog', 'nice-framework' ); ?>" class="nice-tooltip"><?php echo esc_html( $current_theme->get( 'Version' ) ); ?></a></span>
					</div>
				</div>

				<div class="columns-2">
					<div class="icon-option">

						<span class="nice-icon-loading"></span>

						<?php if ( nice_development_mode() ) : ?>
							<a class="reset-options nice-tooltip" onclick="javascript: confirm( '<?php echo esc_js( esc_attr__( 'All your theme options will be removed. This operation cannot be undone.', 'nice-framework' ) ); ?>' );" href="<?php echo esc_url( $reset_url ); ?>" title="<?php esc_attr_e( 'Reset options to their default values', 'nice-framework' ); ?>"><i class="bi_interface-reverse"></i></a>
						<?php endif; ?>
						<a class="nice-tooltip backup-options" href="<?php echo esc_url( nice_admin_page_get_link( 'options_backup' ) ); ?>" title="<?php esc_attr_e( 'Import/Export Options', 'nice-framework' ); ?>"><i class="bi_interface-cloud-upload"></i></a>
						<input type="submit" value="<?php esc_attr_e( 'Save Changes', 'nice-framework' ); ?>" class="save-options-button button-highlighted" />

					</div>
				</div>
			</div>
			<!-- END #header -->
		</div>

		<!-- BEGIN #main -->
		<div id="main" class="grid">

			<div id="nice-nav" class="columns-4">
				<ul>
					<?php echo $interface->menu; ?>
				</ul>
			</div>

			<div id="nice-content" class="columns-3-4">
				<?php echo $interface->content; ?>
			</div>

			<!-- END #main -->
		</div>

	</form>

</div>
