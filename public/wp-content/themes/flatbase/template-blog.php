<?php
/**
 * Flatbase by NiceThemes.
 *
 * Template Name: Blog
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

get_header();

if ( get_query_var( 'paged' ) ) $paged = get_query_var( 'paged' ); elseif ( get_query_var( 'page' ) ) $paged = get_query_var( 'page' ); else $paged = 1;

query_posts( array( 'post_type' => 'post', 'paged' => $paged ) ); ?>

<!-- BEGIN #content -->
<section id="content" class="<?php echo $post->post_name; ?>">

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

			<!-- BEGIN .post -->
			<article class="post clearfix">

				<header>
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
					<?php nice_post_meta(); ?>
				</header>

				<div class="entry">

					<?php if ( has_post_thumbnail() ) :	?>

						<figure class="featured-image">
							<a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ); ?>">
								<?php nice_image( array( 'width' => 320, 'height' => 200, 'class' => 'wp-post-image' ) ); ?>
							</a>
						</figure>

					<?php endif; ?>

					<div class="post-content">
						<?php nice_excerpt( 400 ); ?>
					</div>

				</div>

			<!-- END .post -->
			</article>

	<?php endwhile; ?>

<?php else : ?>

	<?php _e( 'Sorry, no posts matched your criteria.', 'nicethemes' ); ?>

<?php endif; ?>

<?php nice_pagenavi(); ?>

<?php wp_reset_query(); ?>

<!-- END #content -->
</section>

<?php
get_sidebar();
get_footer();