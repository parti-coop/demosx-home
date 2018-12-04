<?php
/**
 * NiceThemes Framework System Status
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   1.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Obtain the system status handler.
 */
$system_status = nice_admin_system_status();
?>

<div class="nice-system-status-report">
	<form method="post" action="<?php echo esc_url( nice_admin_page_get_link( 'system_status' ) ); ?>">
		<?php wp_nonce_field( 'nice-system-status-report-download' ); ?>
		<input type="hidden" name="nice-system-status-report-download" value="1" />
		<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Get System Status Report', 'nice-framework' ); ?>" />
	</form>
	<p>
		<?php esc_html_e( 'Click the button to generate and download a full report. Then, attach it to your support ticket.', 'nice-framework' ); ?>
	</p>
</div>

<div class="nice-system-status">

	<table class="widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3"><?php esc_html_e( 'WordPress', 'nice-framework' ); ?></th>
		</tr>
		</thead>

		<tbody>

		<tr>
			<td class="title"><?php esc_html_e( 'Home URL:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( "The URL of the site's homepage.", 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><?php echo esc_url( $system_status->get_home_url() ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Site URL:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The root URL of the site.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><?php echo esc_url( $system_status->get_site_url() ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Version:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The version of WordPress installed on the site.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->get_wp_version() < $system_status->get_required_wp_version() ) ? 'wrong' : 'right'; ?>">
				<?php echo esc_html( $system_status->get_wp_version() ); ?>
				<?php if ( $system_status->get_wp_version() < $system_status->get_recommended_wp_version() ) : ?>
					<br /><?php printf( esc_html__( 'Recommended version: %s or higher.', 'nice-framework' ), esc_attr( $system_status->get_recommended_wp_version() ) ); ?>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Multisite:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'Whether or not is WordPress Multisite enabled.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><span class="choice"><?php echo $system_status->is_wp_multisite() ? '&#x2713;' : '&#x2717;'; ?></span></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Memory Limit:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The maximum amount of memory (RAM) that the site can use at one time.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->get_wp_memory_limit() < $system_status->get_recommended_wp_memory_limit() ) ? 'wrong' : 'right'; ?>">
				<?php echo esc_html( $system_status->get_formatted_wp_memory_limit() ); ?>
				<?php if ( $system_status->get_wp_memory_limit() < $system_status->get_recommended_wp_memory_limit() ) : ?>
					<br /><?php printf( esc_html__( 'Recommended value: %s.', 'nice-framework' ), esc_attr( $system_status->get_formatted_recommended_wp_memory_limit() ) ); ?> <a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank"><?php esc_html_e( 'Please increase it', 'nice-framework' ); ?></a>.
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Maximum Upload File Size:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The largest file size that can be uploaded to this WordPress installation.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->get_wp_max_upload_size() < $system_status->get_recommended_wp_max_upload_size() ) ? 'wrong' : 'right'; ?>">
				<?php echo esc_html( $system_status->get_formatted_wp_max_upload_size() ); ?>
				<?php if ( $system_status->get_wp_max_upload_size() < $system_status->get_recommended_wp_max_upload_size() ) : ?>
					<br /><?php printf( esc_html__( 'Recommended value: %s.', 'nice-framework' ), esc_attr( $system_status->get_formatted_recommended_wp_max_upload_size() ) ); ?>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Allowed File Extensions:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The file extensions the current user can upload to this WordPress installation.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><?php echo esc_html( implode( ', ', $system_status->get_wp_file_extensions() ) ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Language:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The language currently used by WordPress.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><?php echo esc_html( $system_status->get_wp_locale() ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Upload Directory:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'Whether or not your upload directory is writable.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->is_wp_uploads_dir_writable() ? 'right' : 'wrong' ); ?>"><?php echo $system_status->is_wp_uploads_dir_writable() ? esc_html__( 'Writable', 'nice-framework' ) : esc_html__( 'Not writable', 'nice-framework' ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Debug Mode:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'Whether or not is WordPress in debug mode.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><span class="choice"><?php echo $system_status->is_wp_debug_mode() ? '&#x2713;' : '&#x2717;'; ?></span></td>
		</tr>

		</tbody>
	</table>

	<table class="widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3"><?php esc_html_e( 'Server', 'nice-framework' ); ?></th>
		</tr>
		</thead>

		<tbody>

		<tr>
			<td class="title"><?php esc_html_e( 'Server Information:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'Information about the web server that is currently hosting the site.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><?php echo esc_html( $system_status->get_server_info() ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Server Timezone:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The default timezone for the server. It should be UTC.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( ! $system_status->is_server_timezone_utc() ) ? 'wrong' : 'right'; ?>">
				<?php echo esc_html( $system_status->get_server_timezone() ); ?>
				<?php if ( ! $system_status->is_server_timezone_utc() ) : ?>
					<br /><?php esc_html_e( 'The default timezone should be UTC.', 'nice-framework' ); ?>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Remote GET method:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'Whether or not can the GET method be used to communicate with different APIs.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( ! $system_status->is_wp_remote_get() ) ? 'wrong' : 'right'; ?>">
				<span class="choice"><?php echo $system_status->is_wp_remote_get() ? '&#x2713;' : '&#x2717;'; ?></span>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Remote POST method:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'Whether or not can the POST method be used to communicate with different APIs.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( ! $system_status->is_wp_remote_post() ) ? 'wrong' : 'right'; ?>">
				<span class="choice"><?php echo $system_status->is_wp_remote_post() ? '&#x2713;' : '&#x2717;'; ?></span>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'mod_security:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'Whether the mod_security extension is enabled. This extension may cause issues with file uploads.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->mod_security_enabled() ) ? 'wrong' : 'right'; ?>">
				<?php if ( $system_status->xdebug_enabled() ) : ?>
					<?php esc_html_e( 'Enabled', 'nice-framework' ); ?>
				<?php else : ?>
					<?php esc_html_e( 'Disabled', 'nice-framework' ); ?>
				<?php endif; ?>
			</td>
		</tr>

		</tbody>
	</table>

	<table class="widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3"><?php esc_html_e( 'PHP', 'nice-framework' ); ?></th>
		</tr>
		</thead>

		<tbody>

		<tr>
			<td class="title"><?php esc_html_e( 'Version:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The version of PHP installed on your server.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo $system_status->php_version_ok() ? 'right' : 'wrong'; ?>">
				<?php if ( $system_status->get_php_version() ) : ?>
					<?php echo esc_html( $system_status->get_php_version() ); ?>
				<?php else : ?>
					<?php printf( esc_html__( "%s isn't available.", 'nice-framework' ), '<code>phpversion()</code>' ); ?>
				<?php endif; ?>
				<?php if ( ! $system_status->php_version_ok() ) : ?>
					<br />
					<?php printf( esc_html__( 'Recommended version: %s or higher.', 'nice-framework' ), esc_html( $system_status->get_recommended_php_version() ) ); ?>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Maximum Input Variables:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The maximum number of variables the server can use for a single function to avoid overloads.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->get_php_max_input_vars() < $system_status->get_recommended_php_max_input_vars() ) ? 'wrong' : 'right'; ?>">
				<?php if ( ! is_null( $system_status->get_php_max_input_vars() ) ) : ?>
					<?php echo esc_html( $system_status->get_php_max_input_vars() ); ?>
				<?php else : ?>
					<?php printf( esc_html__( "%s isn't available.", 'nice-framework' ), '<code>ini_get()</code>' ); ?>
				<?php endif; ?>
				<?php if ( $system_status->get_php_max_input_vars() < $system_status->get_recommended_php_max_input_vars() ) : ?>
					<br /><?php printf( esc_html__( 'Recommended value: %s.', 'nice-framework' ), esc_html( $system_status->get_recommended_php_max_input_vars() ) ); ?>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'POST Maximum Size:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The largest file size that can be contained in one POST request.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->get_php_post_max_size() < $system_status->get_recommended_php_post_max_size() ) ? 'wrong' : 'right'; ?>">
				<?php if ( ! is_null( $system_status->get_php_post_max_size() ) ) : ?>
					<?php echo esc_html( $system_status->get_formatted_php_post_max_size() ); ?>
				<?php else : ?>
					<?php printf( esc_html__( "%s isn't available.", 'nice-framework' ), '<code>ini_get()</code>' ); ?>
				<?php endif; ?>
				<?php if ( $system_status->get_php_post_max_size() < $system_status->get_recommended_php_post_max_size() ) : ?>
					<br /><?php printf( esc_html__( 'Recommended value: %s.', 'nice-framework' ), esc_html( $system_status->get_formatted_recommended_php_post_max_size() ) ); ?>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Time Limit:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The amount of time (in seconds) that the site will spend on a single operation before timing out (to avoid server lockups).', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->get_php_time_limit() < $system_status->get_recommended_php_time_limit() ) ? 'wrong' : 'right'; ?>">
				<?php if ( ! is_null( $system_status->get_php_time_limit() ) ) : ?>
					<?php echo esc_html( $system_status->get_php_time_limit() ); ?>
				<?php else : ?>
					<?php printf( esc_html__( "%s isn't available.", 'nice-framework' ), '<code>ini_get()</code>' ); ?>
				<?php endif; ?>
				<?php if ( $system_status->get_php_time_limit() < $system_status->get_recommended_php_time_limit() ) : ?>
					<br /><?php printf( esc_html__( 'Recommended value: %s.', 'nice-framework' ), esc_html( $system_status->get_recommended_php_time_limit() ) ); ?>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Xdebug:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'Whether the Xdebug extension is enabled. This value should always be disabled in live sites.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->xdebug_enabled() ) ? 'wrong' : 'right'; ?>">
				<?php if ( $system_status->xdebug_enabled() ) : ?>
					<?php esc_html_e( 'Xdebug is enabled. Please disable it if this is a live site.', 'nice-framework' ); ?>
				<?php else : ?>
					<?php esc_html_e( 'Disabled', 'nice-framework' ); ?>
				<?php endif; ?>
			</td>
		</tr>

		</tbody>
	</table>

	<table class="widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3"><?php esc_html_e( 'MySQL', 'nice-framework' ); ?></th>
		</tr>
		</thead>

		<tbody>

		<tr>
			<td class="title"><?php esc_html_e( 'Version:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The version of MySQL installed on your server.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description <?php echo ( $system_status->get_mysql_version() < $system_status->get_required_mysql_version() ) ? 'wrong' : 'right'; ?>">
				<?php echo esc_html( $system_status->get_mysql_version() ); ?>
				<?php if ( $system_status->get_mysql_version() < $system_status->get_recommended_mysql_version() ) : ?>
					<br /><?php printf( esc_html__( 'Recommended version: %s or higher.', 'nice-framework' ), esc_html( $system_status->get_recommended_mysql_version() ) ); ?>
				<?php endif; ?>
			</td>
		</tr>

		</tbody>
	</table>

	<table class="widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3"><?php esc_html_e( 'Active Theme', 'nice-framework' ); ?></th>
		</tr>
		</thead>

		<tbody>

		<tr>
			<td class="title"><?php esc_html_e( 'Name:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The name of the currently active theme.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><?php echo esc_html( $system_status->get_theme_name() ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Version:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The installed version of the currently active theme.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><?php echo esc_html( $system_status->get_theme_version() ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Framework Version:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The installed version of the Nice Framework.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><?php echo esc_html( $system_status->get_nice_framework_version() ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( "Author's URL:", 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( "The currently active theme developer's URL.", 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description"><?php echo esc_html( $system_status->get_theme_author_url() ); ?></td>
		</tr>

		<tr>
			<td class="title"><?php esc_html_e( 'Child Theme:', 'nice-framework' ); ?></td>
			<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'Whether or not is the currently active theme a child theme.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
			<td class="description">
				<span class="choice"><?php echo $system_status->is_child_theme() ? '&#x2713;' : '&#x2717;'; ?></span>
				<?php if ( ! $system_status->is_child_theme()  ) : ?>
					<br /><?php printf( esc_html__( "If you're modifying %s, we recommend using a child theme.", 'nice-framework' ), esc_attr( $system_status->get_nice_theme_name() ) ); ?> <a href="http://codex.wordpress.org/Child_Themes" target="_blank"><?php esc_html_e( 'Learn about them', 'nice-framework' ); ?></a>.
				<?php endif; ?>
			</td>
		</tr>

		<?php if ( $system_status->is_child_theme() ) : ?>

			<tr>
				<td class="title"><?php esc_html_e( 'Parent Theme Name:', 'nice-framework' ); ?></td>
				<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The name of the parent theme.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
				<td class="description"><?php echo esc_html( $system_status->get_parent_theme_name() ); ?></td>
			</tr>

			<tr>
				<td class="title"><?php esc_html_e( 'Parent Theme Version:', 'nice-framework' ); ?></td>
				<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( 'The installed version of the parent theme.', 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
				<td class="description"><?php echo esc_html( $system_status->get_parent_theme_version() ); ?></td>
			</tr>

			<tr>
				<td class="title"><?php esc_html_e( "Parent Theme Author's URL:", 'nice-framework' ); ?></td>
				<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr__( "The parent theme developer's URL.", 'nice-framework' ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
				<td class="description"><?php echo esc_html( $system_status->get_parent_theme_author_url() ); ?></td>
			</tr>

		<?php endif; ?>

		</tbody>
	</table>

	<table class="widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3"><?php esc_html_e( 'Installed Demo Packs', 'nice-framework' ); ?></th>
		</tr>
		</thead>

		<tbody>

		<?php $installed_demo_packs = $system_status->get_installed_demo_packs(); ?>

		<?php if ( ! empty( $installed_demo_packs ) ) : ?>

			<?php if ( 1 < count( $installed_demo_packs ) ) : ?>

				<tr>
					<td colspan="3" class="description wrong">
						<strong><?php esc_html_e( 'WARNING:', 'nice-framework' ); ?></strong> <?php esc_html_e( 'You have more than one demo pack installed.', 'nice-framework' ); ?><br />
						<?php esc_html_e( 'This means their content could be merged, causing unexpected results.', 'nice-framework' ); ?><br />
						<?php esc_html_e( 'If this is not intentional, consider reinstalling the one you prefer with the removing content option enabled.', 'nice-framework' ); ?>
					</td>
				</tr>

			<?php endif; ?>

			<?php foreach ( $system_status->get_installed_demo_packs() as $demo_pack ) : ?>

				<tr>
					<td class="title">
						<?php if ( $demo_pack['preview_url'] ) : ?>
						<a href="<?php echo esc_url( $demo_pack['preview_url'] ); ?>" target="_blank">
							<?php endif; ?>
							<?php echo esc_html( $demo_pack['name'] ); ?>
							<?php if ( $demo_pack['preview_url'] ) : ?>
						</a>
					<?php endif; ?>
						<?php if ( $demo_pack['partially_installed'] ) : ?>
							<br />
							(<?php esc_html_e( 'Partial', 'nice-framework' ); ?>)
						<?php endif; ?>
					</td>
					<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr( $demo_pack['description'] ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
					<td class="description <?php echo $demo_pack['maybe_incomplete'] ? 'wrong' : 'right'; ?>">
						<?php if ( $demo_pack['maybe_incomplete'] ) : ?>
							<?php esc_html_e( 'This demo pack may not be showing all its data, because of a partial install or required plugins not being active.', 'nice-framework' ); ?><br />
							<?php esc_html_e( 'If this is not intentional, consider checking its details and reinstalling it with the removing content option enabled.', 'nice-framework' ); ?>
						<?php else : ?>
							<span class="choice"><?php echo '&#x2713;'; ?></span>
						<?php endif; ?>
					</td>
				</tr>

			<?php endforeach; ?>

		<?php else : ?>

			<tr>
				<td colspan="3"><?php esc_html_e( 'Currently, no demo packs are installed.', 'nice-framework' ); ?></td>
			</tr>

		<?php endif; ?>

		</tbody>
	</table>

	<table class="widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3"><?php esc_html_e( 'Active Plugins', 'nice-framework' ); ?></th>
		</tr>
		</thead>

		<tbody>

		<?php $active_plugins = $system_status->get_active_plugins(); ?>
		<?php if ( ! empty( $active_plugins ) ) : ?>

			<?php foreach ( $active_plugins as $plugin ) : ?>

				<?php
					$plugin_info = array();
					if ( $plugin['required'] ) {
						$plugin_info[] = esc_html__( 'Required', 'nice-framework' );
					} elseif ( $plugin['recommended'] ) {
						$plugin_info[] = esc_html__( 'Recommended', 'nice-framework' );
					}

					if ( $plugin['must_use'] ) {
						$plugin_info[] = esc_html__( 'Must Use', 'nice-framework' );
					} elseif ( $plugin['network_active'] ) {
						$plugin_info[] = esc_html__( 'Network', 'nice-framework' );
					}
				?>

				<tr>
					<td class="title">
						<?php if ( $plugin['url'] ) : ?>
							<a href="<?php echo esc_url( $plugin['url'] ); ?>" target="_blank">
						<?php endif; ?>
							<?php echo esc_html( $plugin['name'] ); ?>
						<?php if ( $plugin['url'] ) : ?>
							</a>
						<?php endif; ?>
						<?php echo esc_html( $plugin['version'] ); ?>
						<?php if ( $plugin['new_version'] ) : ?>
							<br />
							<em><?php printf( esc_html__( 'Update Available: %s', 'nice-framework' ), esc_attr( $plugin['new_version'] ) ); ?></em>
						<?php endif; ?>
						<?php if ( ! empty( $plugin_info ) ) : ?>
							<br />
							(<?php echo esc_html( implode( ', ', $plugin_info ) ); ?>)
						<?php endif; ?>
					</td>
					<td class="help"><a class="nice-help-button nice-tooltip" title="<?php echo esc_attr( $plugin['description'] ); ?>"><i class="dashicons dashicons-editor-help"></i></a></td>
					<td class="description">
						<?php if ( $plugin['author_url'] ) : ?>
							<a href="<?php echo esc_url( $plugin['author_url'] ); ?>" target="_blank">
						<?php endif; ?>
							<?php echo esc_html( $plugin['author_name'] ); ?>
						<?php if ( $plugin['author_url'] ) : ?>
							</a>
						<?php endif; ?>
					</td>
				</tr>

		<?php endforeach; ?>

		<?php else : ?>

			<tr>
				<td colspan="3"><?php esc_html_e( 'Currently, no plugins are active.', 'nice-framework' ); ?></td>
			</tr>

		<?php endif; ?>

		</tbody>
	</table>

	<table class="widefat" cellspacing="0">
		<thead>
		<tr>
			<th colspan="3">
				<?php esc_html_e( 'Required &amp; Recommended Plugins', 'nice-framework' ); ?><br />
				<em>(<?php printf( esc_html__( 'which you should %1$sinstall, activate and keep updated%2$s', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'plugins' ) ) ), '</a>' ); ?>)</em>
			</th>
		</tr>
		</thead>

		<tbody>

		<?php ob_start(); ?>

		<?php $required_plugins = $system_status->get_required_plugins(); ?>

		<?php if ( ! empty( $required_plugins ) ) : ?>

			<?php foreach ( $required_plugins as $plugin_data ) : ?>

				<?php
					$plugin = Nice_TGM_Plugin::obtain( $plugin_data['slug'] );

					if ( ! ( $plugin instanceof Nice_TGM_Plugin ) ) {
						continue;
					}

					$plugin_info = array();

					if ( ! $plugin->is_installed() ) {
						$plugin_info[] = __( 'Not Installed', 'nice-framework' );
					} else {
						if ( $plugin->is_inactive() ) {
							$plugin_info[] = __( 'Inactive', 'nice-framework' );
						}

						if ( version_compare( $plugin->get_version(), $plugin->get_theme_required_version(), '<' ) ) {
							$plugin_info[] = __( 'Needs Update', 'nice-framework' );
						}
					}

					if ( empty( $plugin_info ) ) {
						continue;
					}

					if ( $plugin->is_required() ) {
						array_unshift( $plugin_info, esc_html__( 'Required', 'nice-framework' ) );
					}
				?>

				<tr>
					<td class="title">
						<?php if ( $plugin->get_url() ) : ?>
							<a href="<?php echo esc_url( $plugin->get_url() ); ?>" target="_blank">
						<?php endif; ?>
							<?php echo esc_html( $plugin->get_name() ); ?>
						<?php if ( $plugin->get_url() ) : ?>
							</a>
						<?php endif; ?>
						<?php if ( $plugin->get_theme_required_version() ) : ?>
							<?php echo esc_html( $plugin->get_theme_required_version() ); ?>
						<?php endif; ?>
						<?php if ( $plugin->has_update() ) : ?>
							<br />
							<em><?php printf( esc_html__( 'Update Available: %s', 'nice-framework' ), esc_attr( $plugin->get_new_version() ) ); ?></em>
						<?php endif; ?>
						<?php if ( ! empty( $plugin_info ) ) : ?>
							<br />
							(<?php echo esc_html( implode( ', ', $plugin_info ) ); ?>)
						<?php endif; ?>
					</td>
					<td class="help">
						<?php if ( $plugin->get_description() ) : ?>
							<a class="nice-help-button nice-tooltip" title="<?php echo esc_attr( $plugin->get_description() ); ?>"><i class="dashicons dashicons-editor-help"></i></a>
						<?php endif; ?>
					</td>
					<td class="description">
						<?php if ( $plugin->get_author_name() ) : ?>
							<?php if ( $plugin->get_author_url() ) : ?>
								<a href="<?php echo esc_url( $plugin->get_author_url() ); ?>" target="_blank">
							<?php endif; ?>
							<?php echo esc_html( $plugin->get_author_name() ); ?>
							<?php if ( $plugin->get_author_url() ) : ?>
								</a>
							<?php endif; ?>
						<?php endif; ?>
					</td>
				</tr>

			<?php endforeach; ?>

		<?php endif; ?>

		<?php
			$required_plugins_output = trim( ob_get_contents() );
			ob_end_clean();
		?>

		<?php if ( ! empty( $required_plugins_output ) ) : ?>
			<?php echo $required_plugins_output; // WPCS: XSS Ok. ?>
		<?php else : ?>
			<tr>
				<td colspan="3"><?php esc_html_e( 'Currently, no required or recommended plugins are missing, inactive or outdated.', 'nice-framework' ); ?></td>
			</tr>
		<?php endif; ?>

		</tbody>
	</table>

</div>
