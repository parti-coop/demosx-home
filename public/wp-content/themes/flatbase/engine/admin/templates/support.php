<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Support & Updates content.
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
$updater_admin      = nice_theme_updater_admin();
$license_key_status = $updater_admin->system_status->get_nice_license_key_status();

?>

<div class="nice-support-page">

	<?php if ( empty( $license_key_status ) || ( 'valid' !== $license_key_status ) ) : ?>
	<div class="nice-box">
		<p><?php printf( esc_html__( '%s is now installed and ready to use!', 'nice-framework' ), '<strong>' . esc_attr( $updater_admin->system_status->get_nice_theme_name() ) . '</strong>' ); ?> <?php printf( esc_html__( 'In order to get support and %1$sone-click updates%2$s, you will need to %1$s%3$sregister your product%4$s%2$s.', 'nice-framework' ), '<strong>', '</strong>', sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'register_product' ) ) ), '</a>' ); ?></p>
	</div>
	<br />
	<?php endif; ?>

	<div class="nice-box popular-articles">

		<h2><i class="bi_doc-file-line-a"></i> <?php esc_html_e( 'Popular Articles', 'nice-framework' ); ?></h2>

		<p><?php esc_html_e( 'Here is a list of the most popular articles regarding theme configuration. You can read any of them by following the links or you can check the rest of articles and documentation in our support section at NiceThemes.com', 'nice-framework' );?></p>

		<div class="grid support-articles">

			<div class="columns-2">

				<ul class="article-list">
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/configuring-theme-options/', esc_attr__( 'Configuring the Theme Options', 'nice-framework' ), esc_html__( 'Configuring the Theme Options', 'nice-framework' ) ); ?></li>
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/using-featured-images/', esc_attr__( 'Using Featured Images', 'nice-framework' ), esc_html__( 'Using Featured Images', 'nice-framework' ) ); ?></li>
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/using-navigation-menus/', esc_attr__( 'Setting up and using the navigation menus', 'nice-framework' ), esc_html__( 'Setting up and using the navigation menus', 'nice-framework' ) ); ?></li>
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/using-seo-friendly-permalinks/', esc_attr__( 'Using SEO friendly permalinks', 'nice-framework' ), esc_html__( 'Using SEO friendly permalinks', 'nice-framework' ) ); ?></li>
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/using-wordpress-widgets/', esc_attr__( 'How to use WordPress widgets?', 'nice-framework' ), esc_html__( 'How to use WordPress widgets?', 'nice-framework' ) ); ?></li>
				</ul>

			</div>

			<div class="columns-2">

				<ul class="article-list">
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/how-to-customize-a-theme/', esc_attr__( 'How to customize my theme?', 'nice-framework' ), esc_html__( 'How to customize my theme?', 'nice-framework' ) ); ?></li>
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/how-to-create-and-use-child-themes/', esc_attr__( 'How to create and use Child Themes?', 'nice-framework' ), esc_html__( 'How to create and use Child Themes?', 'nice-framework' ) ); ?></li>
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/how-to-translate-themes-and-plugins/', esc_attr__( 'How to translate my Theme?', 'nice-framework' ), esc_html__( 'How to translate my Theme?', 'nice-framework' ) ); ?></li>
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/setting-home-page/', esc_attr__( 'Setting up the Home Page', 'nice-framework' ), esc_html__( 'Setting up the Home Page', 'nice-framework' ) ); ?></li>
					<li><i class="bi_doc-file-line-a"></i><?php printf( '<a href="%1$s" title="%2$s" target="_blank">%3$s</a>', 'https://nicethemes.com/article/importing-demo-content/', esc_attr__( 'Importing demo content', 'nice-framework' ), esc_html__( 'Importing demo content', 'nice-framework' ) ); ?></li>
				</ul>

			</div>
		</div>

	</div>
</div>

<br />

<div class="grid">
	<div class="columns-3">
		<div class="nice-box">
			<h2><i class="bi_doc-papers"></i> <?php esc_html_e( 'Documentation', 'nice-framework' ); ?></h2>

			<p><?php esc_html_e( 'Our online documentation is an amazing resource for learning all the special instructions for using this theme.', 'nice-framework' ); ?></p>

			<p><a href="https://nicethemes.com/support"><?php esc_html_e( 'View documentation', 'nice-framework' ); ?></a></p>

		</div>
	</div>

	<div class="columns-3">
		<div class="nice-box">
			<h2><i class="bi_doc-papers"></i> <?php esc_html_e( 'Knowledge Base', 'nice-framework' ); ?></h2>

			<p><?php esc_html_e( 'In our Knowledge Base you can find additional content that is not inside of our documentation. This information is more specific and unique to different aspects of the theme.', 'nice-framework' );?></p>

			<p><a href="https://nicethemes.com/support"><?php esc_html_e( 'View articles', 'nice-framework' ); ?></a></p>

		</div>
	</div>

	<div class="columns-3">
		<div class="nice-box">
			<h2><i class="bi_interface-help-a"></i> <?php esc_html_e( 'Support Forums', 'nice-framework' ); ?></h2>

			<p><?php esc_html_e( 'Through the forums we offer top notch support. Make sure to register your purchase first to access our support services.', 'nice-framework' ); ?></p>

			<p><a href="https://nicethemes.com/support/support-forum/"><?php esc_html_e( 'Visit the forums', 'nice-framework' ); ?></a></p>
		</div>
	</div>
</div>

<br />

<div class="grid">
	<div class="columns-3">

		<div class="nice-box">
		<h2><i class="bi_music-repeat"></i> <?php esc_html_e( 'Framework Updates', 'nice-framework' ); ?></h2>

		<?php
			/**
			 * Initialize file system handler.
			 */
			$file_system = $updater_admin->updater->get_file_system();
		?>

		<?php if ( false === $file_system ) : ?>

			<?php request_filesystem_credentials( $updater_admin->get_updates_url() ); ?>

		<?php else : ?>

			<p><?php printf( esc_html__( 'You are running %1$s %2$s', 'nice-framework' ), '<strong>NiceFramework</strong>', '<strong>' . esc_attr( $updater_admin->system_status->get_nice_framework_version() ) . '</strong>' ); ?></p>

			<?php if ( $updater_admin->updater->framework_has_update() ) : ?>

				<?php if ( ! empty( $updater_admin->demo_pack ) && ! $updater_admin->demo_pack->is_framework_version() ) : ?>
					<p><strong><?php printf( esc_html__( '%1$s demo requires at least %2$s.', 'nice-framework' ), esc_attr( $updater_admin->demo_pack->get_name() ), esc_attr( $updater_admin->demo_pack->get_framework_version() ) ); ?></strong></p>
				<?php endif; ?>

				<?php if ( $updater_admin->updater->can_update() ) : ?>

					<?php
						$update_onclick = 'onclick="if ( confirm(\'' . esc_js( $updater_admin->get_string( 'update-framework-notice' ) ) . '\') ) {return true;} return false;"';
						$update_url     = wp_nonce_url( 'update.php?action=upgrade-framework&amp;theme=' . urlencode( $updater_admin->get_theme_slug() ), 'upgrade-framework_' . $updater_admin->get_theme_slug() );

						if ( ! empty( $updater_admin->demo_pack ) ) {
							$update_url = add_query_arg( 'demo', $updater_admin->demo_pack->get_slug(), $update_url );
						}
					?>

					<p><a class="button button-primary" href="<?php echo esc_url( $update_url ); ?>" <?php echo $update_onclick; ?>><?php printf( esc_html__( 'Update to %s', 'nice-framework' ), esc_attr( $updater_admin->updater->get_framework_latest_version() ) ); ?></a></p>

				<?php else : ?>

					<p><strong><?php printf( esc_html__( 'Version %s is available.', 'nice-framework' ), esc_attr( $updater_admin->updater->get_framework_latest_version() ) ); ?></strong></p>

				<?php endif; ?>

			<?php else : ?>

				<p><?php esc_html_e( 'You are up to date.', 'nice-framework' ); ?></p>

				<div class="button-force-check-container"><a class="button button-force-check" href="<?php echo esc_url( nice_admin_page_get_link( 'support' ) ); ?>&force-check=1"><?php esc_html_e( 'Check again', 'nice-framework' ); ?></a></div>

			<?php endif; ?>

			<?php if ( ! $updater_admin->updater->can_update() ) : ?>

				<p><?php printf( esc_attr__( '%1$sPlease register your product to get one-click updates%2$s.', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'register_product' ) ) ), '</a>' ); ?></p>

			<?php endif; ?>

		<?php endif; ?>
		</div>

	</div>

	<div class="columns-3">

		<div class="nice-box">

		<h2><i class="bi_music-repeat"></i> <?php esc_html_e( 'Theme Updates', 'nice-framework' ); ?></h2>

		<p><?php printf( esc_html__( 'You are running %s %s', 'nice-framework' ), '<strong>' . esc_attr( $updater_admin->system_status->get_nice_theme_name() ) . '</strong>', '<strong>' . esc_attr( $updater_admin->system_status->get_nice_theme_version() ) . '</strong>' ); ?></p>

		<?php if ( $updater_admin->updater->has_update() ) : ?>

			<?php if ( ! empty( $updater_admin->demo_pack ) && ! $updater_admin->demo_pack->is_theme_version() ) : ?>
				<p><strong><?php printf( esc_html__( '%1$s demo requires at least %2$s.', 'nice-framework' ), esc_attr( $updater_admin->demo_pack->get_name() ), esc_attr( $updater_admin->demo_pack->get_theme_version() ) ); ?></strong></p>
			<?php endif; ?>

			<?php if ( $updater_admin->updater->can_update() ) : ?>

				<?php
					$update_onclick = 'onclick="if ( confirm(\'' . esc_js( $updater_admin->get_string( 'update-notice' ) ) . '\') ) {return true;}return false;"';
					$update_url     = wp_nonce_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $updater_admin->get_theme_slug() ), 'upgrade-theme_' . $updater_admin->get_theme_slug() );

					if ( ! empty( $updater_admin->demo_pack ) ) {
						$update_url = add_query_arg( 'demo', $updater_admin->demo_pack->get_slug(), $update_url );
					}
				?>

				<p><a class="button button-primary" href="<?php echo esc_url( $update_url ); ?>" <?php echo $update_onclick; ?>><?php printf( esc_html__( 'Update to %s', 'nice-framework' ), esc_attr( $updater_admin->updater->get_latest_version() ) ); ?></a></p>

			<?php else : ?>

				<p><strong><?php printf( esc_html__( 'Version %s is available.', 'nice-framework' ), esc_attr( $updater_admin->updater->get_latest_version() ) ); ?></strong></p>

			<?php endif; ?>

		<?php else : ?>

			<p><?php esc_html_e( 'You have the latest version.', 'nice-framework' ); ?></p>

			<div class="button-force-check-container"><a class="button button-force-check" href="<?php echo esc_url( nice_admin_page_get_link( 'support' ) ); ?>&force-check=1"><?php esc_html_e( 'Check again', 'nice-framework' ); ?></a></div>

		<?php endif; ?>

		<?php if ( ! $updater_admin->updater->can_update() ) : ?>

			<p><?php printf( esc_html__( '%1$sPlease register your product to get one-click updates%2$s.', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'register_product' ) ) ), '</a>' ); ?></p>

		<?php endif; ?>

		</div>

	</div>

	<div class="columns-3">

		<div class="nice-box">

			<h2><i class="bi_web-code"></i> <?php esc_html_e( 'Changelog & System Status', 'nice-framework' ); ?></h2>

			<a href="<?php echo esc_url( nice_admin_page_get_link( 'changelog' ) ); ?>"><?php esc_html_e( 'Theme Changelog', 'nice-framework' ); ?></a><br />
			<a href="<?php echo esc_url( nice_admin_page_get_link( 'system_status' ) ); ?>"><?php esc_html_e( 'System Status', 'nice-framework' ); ?></a>

		</div>

	</div>

</div>
