<?php
/**
 * Flatbase by NiceThemes.
 *
 * Template Name: Faq (Accordion)
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2016 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

get_header(); ?>

	<!-- BEGIN #content -->
	<section id="content" <?php nice_content_class( 'main-content' ); ?> role="main">

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

					<?php the_content(); ?>

					<?php nice_faq(); ?>

				</div>

		<?php endwhile; ?>

<!-- END #content -->
</section>

<?php get_sidebar(); ?>

<?php get_footer();