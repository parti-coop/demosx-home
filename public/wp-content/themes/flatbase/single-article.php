<?php
/**
 * Flatbase by NiceThemes.
 *
 * The Template for displaying all single articles.
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
<section id="content" <?php nice_content_class(); ?> role="main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php
			/**
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 *
			 * @since 2.0
			 */
			get_template_part( 'content-single', get_post_type() );
		?>

		<?php
			/**
			 * @hook nice_post_author
			 *
			 * The post author is displayed here, depending on general
			 * and post-basis settings.
			 *
			 * @since 2.0
			 *
			 * Hooked here:
			 * @see nice_post_author() - 10
			 */
			do_action( 'nice_post_author' );
		?>

		<?php
			if ( nice_bool ( get_option( 'nice_related_articles' ) ) )
				nice_related_posts( array( 'post_type' => 'article', 'taxonomy' => 'article-category', 'posts_per_page' => 4, 'title' => __( 'Related Articles', 'nicethemes' ) ) );

		?>

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

<?php get_sidebar(); ?>

<?php get_footer();