<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Changelog content.
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$system_status = nice_admin_system_status();

?>


<div class="changelog">
	<div class="feature-section">
		<p><?php printf( esc_html__( 'This is %1$s changelog. %2$sView %3$s changelog%4$s.', 'nice-framework' ), '<strong>' . esc_attr( $system_status->get_nice_theme_name() ) . '</strong>', sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'changelog_framework' ) ) ), 'NiceFramework', '</a>' ); ?></p>

		<?php
			/**
			 * Initialize WordPress' file system handler.
			 *
			 * @var WP_Filesystem_Base $wp_filesystem
			 */
			WP_Filesystem();
			global $wp_filesystem;

			if ( $wp_filesystem->exists( $system_status->get_nice_theme_changelog_path() ) ) {
				$changelog = $wp_filesystem->get_contents( $system_status->get_nice_theme_changelog_path() );

				$changelog = nl2br( esc_html( $changelog ) );
				$changelog = str_replace( '=<br />', '=', $changelog );

				$changelog = explode( sprintf( '=== %s Changelog ===', $system_status->get_nice_theme_name() ), $changelog );
				$changelog = end( $changelog );

				$changelog = preg_replace( '/`(.*?)`/', '<code>\\1</code>', $changelog );
				$changelog = preg_replace( '/[\040]\*\*(.*?)\*\*/', ' <strong>\\1</strong>', $changelog );
				$changelog = preg_replace( '/[\040]\*(.*?)\*/', ' <em>\\1</em>', $changelog );
				$changelog = preg_replace( '/= (.*?) =/', '<h4>Version \\1</h4>', $changelog );
				$changelog = preg_replace( '/\[(.*?)\]\((.*?)\)/', '<a href="\\2">\\1</a>', $changelog );

			} else {
				$changelog = '<p>' . esc_html__( 'No valid changelog was found.', 'nice-framework' ) . '</p>';
			}

			echo $changelog;
		?>
	</div>
</div>

