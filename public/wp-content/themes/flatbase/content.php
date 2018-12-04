<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for displaying content.
 *
 * Used for index/archive/search.
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
		<?php
			/**
			 * Load template for the current post format.
			 *
			 * Keep in mind that the current template will be loaded only for
			 * archive-like views.
			 *
			 * @since 1.0.0
			 */
			get_template_part( 'template-parts/archive/content', get_post_format() );
		?>
	</article><!-- END .post -->
