<?php
/**
 * Flatbase by NiceThemes.
 *
 * Used to display archive-type pages if nothing more specific matches a query. For example, puts
 * together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a new template file for
 * each specific one. For example, we already have tag.php for Tag archives and a category.php for
 * Category archives.
 *
 * @link      http://codex.wordpress.org/Template_Hierarchy
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

<?php if ( have_posts() ) : ?>

		<header>
			<?php if ( is_category() ) { ?>
			<h1 class="archive-header"><span class="cat"><?php _e( 'Category Archives', 'nicethemes' ); ?> : <?php echo single_cat_title(); ?></span> <span class="fr rss"><?php $cat_obj = $wp_query->get_queried_object(); $cat_id = $cat_obj->cat_ID; echo '<a href="' . get_category_feed_link( $cat_id, '' )  . '">'; _e( "RSS feed for this section", "nicethemes" ); echo '</a>'; ?></span></h1>
			<?php } elseif ( is_day() ) { ?>
			<h1 class="archive-header"><?php _e( 'Daily Archives', 'nicethemes' ); ?>: <?php the_time( get_option( 'date_format' ) ); ?></h1>

			<?php } elseif ( is_month() ) { ?>
			<h1 class="archive-header"><?php _e( 'Monthly Archives', 'nicethemes' ); ?>: <?php the_time( 'F, Y' ); ?></h1>

			<?php } elseif ( is_year() ) { ?>
			<h1 class="archive-header"><?php _e( 'Yearly Archives', 'nicethemes' ); ?>: <?php the_time( 'Y' ); ?></h1>

			<?php } elseif ( is_author() ) { ?>
			<h1 class="archive-header"><?php _e( 'Archive by Author', 'nicethemes' ); ?></h1>

			<?php } elseif ( is_tag() ) { ?>
			<h1 class="archive-header"><?php _e( 'Tag Archives', 'nicethemes' ); ?> : <?php echo single_tag_title( '', true ); ?></h1>

			<?php } ?>
		</header>

		<?php while ( have_posts() ) : the_post(); ?>

				<!-- BEGIN .post -->
				<article class="post clearfix">

					<header>
						<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
						<?php nice_post_meta(); ?>
					</header>

					<?php if ( has_post_thumbnail() ) { ?>
						<figure class="featured-image">
							<?php nice_image( array ( 'width' => 620, 'height' => 285, 'class' => 'wp-post-image' ) ); ?>
						</figure>
					<?php } ?>

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

<?php
get_sidebar();
get_footer();