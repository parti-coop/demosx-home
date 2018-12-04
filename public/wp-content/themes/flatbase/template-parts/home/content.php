<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for the homepage content.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( get_the_content() ) :

	$title       = nice_get_option( 'nice_homepage_content_title' );
	$description = nice_get_option( 'nice_homepage_content_description' );
?>
	<div id="homepage-content" class="<?php nice_homepage_block_class( 'content' ); ?>">

		<div class="inner">

			<div class="overlay"></div>

			<div class="block-wrapper col-full">

				<?php if ( $title or $description ) : ?>
					<header class="homepage-section-title homepage-content-title">

						<?php if ( $title ) : ?>
							<h2 class="content-title title"><?php echo esc_html( $title ); ?></h2>
						<?php endif; ?>

						<?php if ( $description ) : ?>
							<div class="content-description description">
								<?php echo wpautop( $description ); ?>
							</div>
						<?php endif; ?>

					</header>
				<?php endif; ?>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>

			</div>

		</div>

	</div>

<?php endif;
