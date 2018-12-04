<?php
/**
 * Flatbase by NiceThemes.
 *
 * Page Template.
 *
 * This template is the default page template. It is used to display content when someone is viewing
 * a singular view of a page (page post type) unless another page template overrules this one.
 *
 * @link      http://codex.wordpress.org/Pages
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
?>
	<?php get_header(); ?>

	<!-- BEGIN #content -->
	<section id="content" <?php nice_content_class( 'main-content' ); ?> role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php
				/**
				 * Load the page content template.
				 *
				 * @since 2.0
				 */
				get_template_part( 'content', 'page' );
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

	</section><!-- END #content -->

	<?php get_sidebar(); ?>

	<?php get_footer();