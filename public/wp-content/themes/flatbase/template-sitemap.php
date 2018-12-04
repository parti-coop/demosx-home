<?php
/**
 * Flatbase by NiceThemes.
 *
 * Template Name: Sitemap
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

			<article class="entry">

				<?php
					/**
					 * Display the contents of the current page.
					 *
					 * @since 1.0.0
					 */
					the_content();
				?>

			</article>

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

		<div class="two-col entry">
			<div class="col-1">

				<header>
				<h3><?php _e( 'Pages', 'nicethemes' ) ?></h3>
				</header>
					<ul>
						<?php wp_list_pages( 'depth=0&sort_column=menu_order&title_li=' ); ?>
					</ul>

				<header>
				<h3><?php _e( 'Article Categories', 'nicethemes' ) ?></h3>
				</header>
				<?php

				$cat_args = array(
					'taxonomy'     => 'article-category',
					'orderby'      => 'menu_order',
					'order'        => 'ASC',
					'hierarchical' => true,
					'parent'       => 0,
					'hide_empty'   => true,
					'child_of'     => 0
				);

				$categories = get_categories( $cat_args ); ?>
				<ul>
				<?php foreach ( $categories as $category ) : ?>

					<?php
						echo '<li><a href="' .  get_term_link( intval( $category->term_id ), 'article-category' ) . '" title="' . sprintf( esc_attr__( 'View all articles in %s', 'nicethemes' ), $category->name ) . '" ' . '>';
						echo $category->name;
						echo '</a> <span class="cat-count">(' . $category->count . ')</span></li>' . "\n\n";
					?>

				<?php endforeach; ?>
				</ul>
			</div>

			<div class="col-2">
				<header>
					<h3><?php _e( 'Posts per category', 'nicethemes' ); ?></h3>
				</header>
					<?php
						$cats = get_categories();
						foreach ( $cats as $cat ) { query_posts( 'cat=' . $cat->cat_ID ); }
					?>
				<header>
					<h4><?php echo $cat->cat_name; ?></h4>
				</header>
					<ul>
						<?php while (have_posts()) : the_post(); ?>
						<li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php _e( 'Comments', 'nicethemes' ) ?> (<?php echo $post->comment_count ?>)</li>
						<?php endwhile;  ?>
					</ul>


				<header>
					<h3><?php _e( 'FAQ', 'nicethemes' ); ?></h3>
				</header>

					<?php

					$args = array(
						'post_type'      => 'faq',
						'posts_per_page' => '-1',
						'orderby'        => 'menu_order',
						'order'          => 'DESC',
						'paged'          => $paged
					);

					$faq_query = new WP_Query( $args );

					if ( $faq_query->have_posts() ) : ?>
					<ul>
						<?php while( $faq_query->have_posts() ) : $faq_query->the_post(); ?>
						<li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li>
						<?php endwhile;  ?>
					</ul>
					<?php endif; ?>
			</div>

	<!-- END #content -->
	</section>

<?php get_footer();