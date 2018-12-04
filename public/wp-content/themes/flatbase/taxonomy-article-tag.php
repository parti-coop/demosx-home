<?php
/**
 * Flatbase by NiceThemes.
 *
 * The template for displaying `article-tag` taxonomy pages.
 *
 * @link      http://codex.wordpress.org/Template_Hierarchy
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

get_header(); ?>

<!-- BEGIN #content -->
<section id="content" <?php nice_content_class( 'main-content' ); ?> role="main">

<?php if ( have_posts() ) : ?>

		<header>
			<h1 class="archive-header"><span class="cat"><?php echo single_cat_title(); ?></span></h1>
		</header>

		<?php while ( have_posts() ) : the_post(); ?>

			<!-- BEGIN .post -->
			<article class="post clearfix">

				<header>
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
				</header>


				<div class="post-content">
						<?php nice_excerpt(); ?>
				</div>

			<!-- END .post -->
			</article>

		<?php endwhile; ?>

		<?php nice_pagenavi(); ?>

<?php else : ?>

	<?php _e( 'Sorry, no posts matched your criteria.', 'nicethemes' ); ?>

<?php endif; ?>

<!-- END #content -->
</section>

<?php get_sidebar(); ?>

<?php get_footer();