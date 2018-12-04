<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Register your Product content.
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Obtain updater admin handler.
 */
$updater_admin = nice_theme_updater_admin();

$license_key_status = get_option( _nice_get_theme_slug() . '_license_key_status');

$nice_video_embed_url_tf_purchase_code = nice_get_remote_setting( 'video_embed_url_install_demos', 'https://www.youtube.com/embed/p_iPvfpxKbk?autoplay=1' );
?>
<div class="nice-support-page">

	<?php if ( $license_key_status !== 'valid' ) : ?>
		<div class="nice-notice nice-notice-large">
			<p><?php printf( esc_html__( 'In order to get support and one-click updates, you will need to %1$sregister your product%2$s by following the steps below.', 'nice-framework' ), '<strong>', '</strong>' ); ?></p>
		</div>
	<?php endif; ?>


	<div class="nice-notice nice-notice-large">

		<div class="grid">

			<?php if ( $license_key_status !== 'valid' ) : ?>

			<div class="columns-1">
				<p><?php esc_html_e( "Follow these simple steps. If you haven't purchased the theme on ThemeForest please go directly to step 3.", 'nice-framework' ); ?></p>
			</div>

			<div class="columns-3">
				<p><strong><?php esc_html_e( 'Step 1 - Get ThemeForest Purchase code', 'nice-framework' ); ?></strong></p>

				<p><?php printf( esc_html__( 'Get your purchase code from your "Downloads" section of your ThemeForest account. %1$sRead the tutorial here%2$s.', 'nice-framework' ), sprintf( '<a href="%s" target="_blank">', 'https://nicethemes.com/article/how-to-find-your-themeforest-purchase-code/' ), '</a>' ); ?></p>
			</div>

			<div class="columns-3">
				<p><strong><?php esc_html_e( 'Step 2 - Signup for Support', 'nice-framework' ); ?></strong></p>

				<p><?php printf( esc_html__( '%1$sClick here to signup%2$s at our support center. The account gives you access to our documentation, knowledgebase, video tutorials and ticket system. %3$sView a tutorial here%4$s.', 'nice-framework' ), sprintf( '<a href="%s" target="_blank">', 'https://nicethemes.com/register' ), '</a>', sprintf( '<a href="%s">', 'http://nicethemes.com/article/premium-theme-support/' ), '</a>' ); ?></p>
			</div>

			<div class="columns-3">

				<p><strong><?php esc_html_e( 'Step 3 - Get the Theme License Key', 'nice-framework' ); ?></strong></p>
				<p><?php printf( esc_html__( 'Go to your %1$sNiceThemes.com Account Dashboard%2$s and register your purchase with the purchase code you got in "Step 1" Then insert the "License Key" below.', 'nice-framework' ), sprintf( '<a href="%s" target="_blank">', 'http://nicethemes.com/dashboard/' ), '</a>' ); ?></p>
			</div>

			<?php endif; ?>

			<div class="columns-1">

				<h2><?php echo esc_html( $updater_admin->get_string( 'theme-license' ) ); ?></h2>

				<?php if ( $license_key_status !== 'valid' ) : ?>

					<p><?php esc_html_e( 'You are almost ready, please insert the theme license in the field below to get access to one-click updates. Do not forget to activate the license for this site.', 'nice-framework' ); ?></p>

				<?php else : ?>

					<p><?php esc_html_e( 'Thanks for registering your product! You will now have access to one-click updates and support.', 'nice-framework' ); ?></p>

				<?php endif; ?>

				<?php
					/**
					 * Obtain license message.
					 */
					$message = $updater_admin->get_license_message();
				?>

				<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">

					<?php settings_fields( $updater_admin->get_theme_slug() . '-license' ); ?>

					<table class="form-table">
						<tbody>

						<tr valign="top">
							<th scope="row" valign="top">

								<?php echo esc_attr( $updater_admin->get_string( 'license-key' ) ); ?>

							</th>
							<td>
								<input id="<?php echo esc_attr( $updater_admin->get_theme_slug() ); ?>_license_key" name="<?php echo esc_attr( $updater_admin->get_theme_slug() ); ?>_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $updater_admin->system_status->get_nice_license_key() ); ?>" />

								<p class="description">
									<?php echo esc_html( $message ); ?>
								</p>

							</td>
						</tr>

						<?php if ( $updater_admin->system_status->get_nice_license_key() ) : ?>
							<tr valign="top">
								<th scope="row" valign="top">

									<?php echo esc_html( $updater_admin->get_string( 'license-action' ) ); ?>

								</th>
								<td>

									<?php wp_nonce_field( $updater_admin->get_theme_slug() . '_nonce', $updater_admin->get_theme_slug() . '_nonce' ); ?>

									<?php if ( $updater_admin->updater->can_update() ) : ?>
										<input type="submit" class="button-secondary" name="<?php echo esc_attr( $updater_admin->get_theme_slug() ); ?>_license_deactivate" value="<?php echo esc_attr( $updater_admin->get_string( 'deactivate-license' ) ); ?>"/>
									<?php else : ?>
										<input type="submit" class="button-secondary" name="<?php echo esc_attr( $updater_admin->get_theme_slug() ); ?>_license_activate" value="<?php echo esc_attr( $updater_admin->get_string( 'activate-license' ) ); ?>"/>
									<?php endif; ?>

								</td>
							</tr>
						<?php endif; ?>

						</tbody>
					</table>

					<?php submit_button(); ?>

				</form>


			</div>
		</div>

	</div>

</div>
