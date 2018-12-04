<?php
/**
 * Flatbase by NiceThemes.
 *
 * Used to display bbPress templates
 *
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

	<?php while ( have_posts() ) : the_post(); ?>

		<article>

			<?php
			$nice_breadcrumbs_args = array(
					// Modify default BBPress Breadcrumbs
					'before' => '<div class="breadcrumb breadcrumbs nice-breadcrumb"><div class="breadcrumb-trail">',
					'after'  => '</div></div>',
					'sep'    => '<span class="sep">/</span>'
			);
			bbp_breadcrumb( $nice_breadcrumbs_args ); ?>

			<div class="entry clearfix">

				<?php the_content( __( 'Continue reading', 'nicethemes' ) . ' &raquo;' ); ?>

				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'nicethemes' ), 'after' => '</div>' ) ); ?>
			</div>

		</article>

	<?php endwhile; ?>

<?php else : ?>

	<header>
		<h2><?php _e( 'Not Found', 'nicethemes' ); ?></h2>
	</header>

	<p><?php _e( 'Sorry, but you are looking for something that isn\'t here.', 'nicethemes' ); ?></p>
	<?php get_search_form(); ?>

<?php endif; ?>

<!-- END #content -->
</section>

<!-- BEGIN #sidebar -->
<aside id="sidebar" role="complementary">
	<?php dynamic_sidebar( 'bbpress' ); ?>
<!-- END #sidebar -->
</aside>

<?php get_footer(); ?>