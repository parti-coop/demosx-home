<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for displaying content.
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
		<?php if ( $tags = get_the_tag_list() ) : ?>
			<span class="tag-links">
				<?php the_tags( '<i class="fa fa-tag"></i>', '', '' ); ?>
			</span>
		<?php endif; ?>

		<?php if ( $categories = get_the_category_list() ) : ?>
			<span class="category-links">
				<i class="fa fa-archive"></i> <?php the_category( ' ', '', '' ); ?>
			</span>
		<?php endif; ?>
	</footer>
