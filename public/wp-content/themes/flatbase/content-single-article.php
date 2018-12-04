<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for displaying article content.
 *
 * Used for single posts.
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
?>
	<!-- BEGIN .post -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php // add pageview ?>
		<?php nice_pageviews(); ?>

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

		<?php
			/**
			 * Load template for the current post format.
			 *
			 * @since 2.0
			 */
			get_template_part( 'template-parts/article/single', get_post_format() );
		?>

		<?php
			/**
			 * @hook nice_after_single_post
			 *
			 * Hook here to add HTML elements after the contents of the post are
			 * displayed.
			 *
			 * @since 2.0
			 */
			do_action( 'nice_after_single_post' );
		?>

	</article><!-- END .post -->
