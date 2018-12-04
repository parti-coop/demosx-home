<?php
/**
 * Flatbase by NiceThemes.
 *
 * Template Name: Gallery
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2016 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

get_header(); ?>

	<!-- BEGIN #content -->
	<section id="content" <?php nice_content_class( 'main-content full-width' ); ?> role="main">

		<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/**
					 * @hook nice_before_single_post
					 *
					 * Hook here to add HTML elements before the contents of the post are
					 * displayed.
					 *
					 * @since 2.0
					 *
					 * Hooked here:
					 * @see nice_post_content_title() - 10
					 */
					do_action( 'nice_before_single_post' );
				?>

				<div class="entry clearfix">
					<?php
						/**
						 * Display the contents of the current page.
						 *
						 * @since 1.0.0
						 */
						the_content();
					?>

					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'nicethemes' ), 'after' => '</div>' ) ); ?>

				</div>

				<?php nicethemes_gallery(); ?>

				<?php
					/**
					 * @hook nice_after_single_post
					 *
					 * Hook here to add HTML elements after the contents of the page are
					 * displayed.
					 *
					 * @since 2.0
					 */
					do_action( 'nice_after_single_post' );
				?>

		<?php endwhile; ?>

	<!-- END #content -->
	</section>

<?php get_footer();