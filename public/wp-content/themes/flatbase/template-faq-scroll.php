<?php
/**
 * Flatbase by NiceThemes.
 *
 * Template Name: Faq (Scroll)
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
<section id="content" class="<?php echo $post->post_name; ?>">

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

			<header>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>

			<?php nice_breadcrumbs(); ?>

			<div class="entry clearfix">

				<?php the_content(); ?>

				<?php

					nice_faq( array(
								'type'  => 'scroll'
							)
						);
				?>
			</div>


	<?php endwhile; ?>

<?php endif; ?>

<!-- END #content -->
</section>

<?php get_sidebar(); ?>

<?php get_footer();