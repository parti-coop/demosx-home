<?php
/**
 * Flatbase by NiceThemes.
 *
 * The Template for displaying all single FAQs.
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
?>
	<?php get_header(); ?>

	<!-- BEGIN #content -->
	<section id="content" <?php nice_content_class( 'main-content full-width' ); ?> role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article <?php post_class(); ?>>

				<header>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>

				<div class="entry">

					<div class="post-content">
						<?php
							/**
							 * Display the contents of the current post.
							 *
							 * @since 1.0
							 */
							the_content();
						?>
					</div>

				</div>

			</article>

			<?php nice_pagenavi(); ?>

			<?php
			/**
			 * If comments are open or we have at least one comment,
			 * load up the comment template.
			 *
			 * @since 1.0.0
			 */
			if ( comments_open() or get_comments_number() ) :
				comments_template();
			endif;
			?>

		<?php endwhile; ?>

	<!-- END #content -->
	</section>

	<?php get_footer();