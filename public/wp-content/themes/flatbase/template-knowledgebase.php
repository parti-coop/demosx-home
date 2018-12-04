<?php
/**
 * Flatbase by NiceThemes.
 *
 * Template Name: Knowledgebase
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
	<section id="content" <?php nice_content_class( 'main-content full-width' ); ?> role="main">


		<?php while ( have_posts() ) : the_post(); ?>

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

				<section id="knowledgebase" class="articles-grid three-col clearfix">

					<?php

						if ( isset( $nice_options['nice_articles_entries'] ) ) {
							$number_articles = $nice_options['nice_articles_entries'];
						} else {
							$number_articles = 5;
						}

						$args = array(
							'columns'     => 3,
							'numberposts' => $number_articles
						);

						nicethemes_knowledgebase( $args );

					?>

				</section>

				<?php
					/**
					 * @hook nice_after_single_post
					 *
					 * Hook here to add HTML elements after the contents of the page are
					 * displayed.
					 *
					 * @since 2.0
					 */
					do_action( 'nice_after_single_post' );
				?>

		<?php endwhile; ?>

	<!-- END #content -->
	</section>

<?php get_footer();