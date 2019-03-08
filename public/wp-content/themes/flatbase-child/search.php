<?php
/**
 * Flatbase by NiceThemes.
 *
 * The template for displaying Search Results pages (and livesearch results, when it comes via AJAX)
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

if ( ! empty( $_GET['ajax'] ) ? $_GET['ajax'] : null ) : // Is Live Search

if ( have_posts() ) : ?>

<ul id="search-result">

<?php while ( have_posts() ) : the_post();

		if ( has_post_format( 'video' ) ) {
			$li_class = 'format-video';
			$nice_icon = '<i class="fa fa-youtube-play"></i>';
		} elseif ( 'faq' == get_post_type() ) {
			$li_class = 'format-faq';
			$nice_icon = '<i class="fa fa-question-circle"></i>';
		} elseif ( 'page' == get_post_type() ) {
			$li_class = 'format-page';
			$nice_icon = '<i class="fa fa-file-o"></i>';
		} elseif ( 'post' == get_post_type() ) {
			$li_class = 'format-post';
			$nice_icon = '<i class="fa fa-file-o"></i>';
		} else {
			$li_class = 'format-article';
			$nice_icon = '<i class="fa fa-file-o"></i>';
		} ?>

		<li class="<?php echo $li_class; ?>">
			<a href="<?php the_permalink(); ?>"><?php echo $nice_icon; ?><?php the_title(); ?></a>
		</li>

	<?php endwhile; ?>
</ul>

<?php else : ?>

<ul id="search-result">
	<li class="no-results"><i class="fa fa-exclamation-circle"></i><?php _e( 'Sorry, no posts were found.', 'nicethemes' ); ?></li>
</ul>

<?php endif;

else : // Is Normal Search

get_header(); ?>

<!-- BEGIN #content -->
<div id="content">

<header>
	<h1 class="archive-header">검색 결과: <?php the_search_query(); ?></h1>
</header>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

			<article class="post clearfix">

				<header>
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ); ?>"><?php the_title(); ?></a></h2>
					<?php nice_post_meta(); ?>
				</header>

				<?php if ( has_post_thumbnail() ) :?>

					<figure class="featured-image">
						<?php nice_image( array( 'width' => 620, 'height' => 285, 'class' => 'wp-post-image' ) ); ?>
					</figure>

				<?php endif; ?>

				<?php nice_excerpt(); ?>

			</article>

	<?php endwhile; ?>

	<?php nice_pagenavi(); ?>

<?php else : ?>

	검색 결과가 없습니다.

<?php endif; ?>

<!-- END #content -->
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
<?php endif;
