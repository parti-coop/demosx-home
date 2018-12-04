<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for displaying content.
 *
 * Used for single articles.
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
	<!-- BEGIN .entry -->
	<div class="entry">
		<?php
			/**
			 * If this post has a video embed, then show it.
			 * If not, show post thumbnail instead.
			 *
			 * @since 2.0
			 */
			nice_video() or nice_post_thumbnail();
		?>

		<!-- BEGIN .post-content -->
		<div class="post-content">
			<?php
				/**
				 * Display the contents of the current post.
				 *
				 * @since 1.0
				 */
				the_content();
			?>
		</div><!-- END .post-content -->
	</div><!-- END .entry -->

	<footer class="entry-meta">

		<span class="tag-links">
			<?php echo get_the_term_list( get_the_ID(), 'article-tag', '<i class="fa fa-tags"></i>', '', '' ); ?>
		</span>
		<span class="category-links">
			<?php echo get_the_term_list( get_the_ID(), 'article-category', '<i class="fa fa-archive"></i>', '', '' ); ?>
		</span>

	</footer>
