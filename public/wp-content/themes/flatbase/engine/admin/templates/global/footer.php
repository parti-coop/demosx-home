<?php
/**
 * Nice Admin by NiceThemes.
 *
 * The footer content.
 *
 * @package Nice_Framework
 * @since   1.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$footer_menu_pages = nice_admin_get_menu_pages( 'footer' );

?>
				</div>
			</div>

			<div class="footer">

				<nav class="primary nav-horizontal">
					<div class="nicethemes-copyright">
						<span><?php printf( esc_html__( 'Made with %s by %s', 'nice-framework' ), '<i data-code="f487" class="dashicons dashicons-heart"></i>', '<a target="_blank" href="http://nicethemes.com/">NiceThemes</a>' ); ?></span>
					</div>
				</nav><!-- .primary -->

				<nav class="secondary nav-horizontal">
					<div class="secondary-footer">
						<a id="nice-admin-ui-link-themes" target="_blank" href="https://nicethemes.com/themes/"><?php esc_html_e( 'Themes', 'nice-framework' ); ?></a>
						<?php /*<a id="nice-admin-ui-link-plugins" target="_blank" href="https://nicethemes.com/plugins/"><?php esc_html_e( 'Plugins', 'nice-framework' ); ?></a>*/ ?>
						<a id="nice-admin-ui-link-support" target="_blank" href="https://nicethemes.com/support/"><?php esc_html_e( 'Help &amp; Support', 'nice-framework' ); ?></a>
						<?php if ( ! empty( $footer_menu_pages ) ) : ?>
							</div>
							<div class="secondary-footer">
							<?php foreach ( $footer_menu_pages as $key ) : ?>
								<a id="nice-admin-ui-link-<?php echo esc_attr( $key ); ?>" class="<?php echo ( nice_admin_get_current_page() === $key )  ? 'current' : ''; ?>" href="<?php echo esc_url( nice_admin_page_get_link( $key ) ); ?>"><?php echo esc_html( nice_admin_page_get_menu_title( $key ) ); ?></a>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</nav><!-- .secondary -->

			</div><!-- .footer -->
		</div><!-- .wrapper -->

</div><!-- .nice-admin-frame -->

</div>

