<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to customize the post/page/article title section.
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

if ( ! function_exists( 'nice_post_content_title' ) ) :
add_action( 'nice_before_single_post', 'nice_post_content_title', 10 );
/**
 * Set the title for the entry content on single templates.
 *
 * @since 2.0
 */
function nice_post_content_title() {
	$post_content_title = get_post_meta( get_the_ID(), '_post_title_content', true );

	if ( empty( $post_content_title ) || nice_bool( $post_content_title ) ) :

		$breadcrumbs_display  = nice_post_breadcrumbs_display();
		$breadcrumbs_position = nice_post_breadcrumbs_display_position();

	?>

		<?php if ( is_singular( 'article' ) ) : ?>

			<header class="entry-header">
				<?php
					if ( $breadcrumbs_display && $breadcrumbs_position === 'before' ) {
						nice_breadcrumbs( array( 'singular_article_taxonomy' => 'article-category' ) );
					}

					do_action( 'nice_single_title' );

					if ( $breadcrumbs_display && $breadcrumbs_position === 'after' ) {
						nice_breadcrumbs( array( 'singular_article_taxonomy' => 'article-category' ) );
					}

					nice_article_meta();
				?>
			</header>

		<?php else : ?>

			<header class="entry-header">
				<?php
					if ( is_page() && ( $breadcrumbs_display && $breadcrumbs_position === 'before' ) ) {
						nice_breadcrumbs();
					}

					do_action( 'nice_single_title' );

					if ( is_page() && ( $breadcrumbs_display && $breadcrumbs_position === 'after' ) ) {
						nice_breadcrumbs();
					} elseif( is_single() ) {
						nice_post_meta();
					}
				?>
			</header>

		<?php endif; ?>

	<?php endif;
}
endif;

if ( ! function_exists( 'nice_post_breadcrumbs_display' ) ) :
/**
 * Set if the post breadcrumbs should be displayed or hidden for the current post.
 *
 * @since  2.0
 *
 * @param  bool $display
 *
 * @return bool
 */
function nice_post_breadcrumbs_display( $display = false ) {

	if ( is_page() || is_single() ) {
		$breadcrumbs_display = get_post_meta( get_the_ID(), '_post_title_content_breadcrumbs', true );

		if ( '' === $breadcrumbs_display ) {
			$breadcrumbs_display = true;
		}

		$display = nice_bool( $breadcrumbs_display );
	}

	return $display;
}
endif;

if ( ! function_exists( 'nice_post_breadcrumbs_display_position' ) ) :
/**
 * Set the breadcrumbs position for the current post.
 *
 * @since  2.0
 *
 * @param  string $position
 *
 * @return string
 */
function nice_post_breadcrumbs_display_position( $position = '' ) {

	if ( is_page() || is_single() ) {
		$breadcrumbs_position = get_post_meta( get_the_ID(), '_post_title_content_breadcrumbs_position', true );

		if ( '' === $breadcrumbs_position ) {
			$breadcrumbs_position = 'before';
		}

	}

	return $breadcrumbs_position;
}
endif;

if ( ! function_exists( 'nice_post_title_text_transform' ) ) :
/**
 * Obtain title text transformation for current page.
 *
 * @since  2.0
 *
 * @param  int $post_id
 *
 * @return string
 */
function nice_post_title_text_transform( $post_id = null ) {
	if ( is_page() || is_single() ) {
		$id        = $post_id ? $post_id : get_the_ID();
		$transform = esc_attr( get_post_meta( $id, '_post_title_text_transform', true ) );
	}

	if ( empty( $transform ) ) {
		$transform = nice_get_option( '_heading_text_transform', null );
	}

	return $transform;
}
endif;

if ( ! function_exists( 'nice_post_title_font_weight' ) ) :
/**
 * Obtain title font weight for the current page.
 *
 * @since  2.0
 *
 * @param  int    $post_id
 *
 * @return string
 */
function nice_post_title_font_weight( $post_id = null ) {
	if ( is_page() || is_single() ) {
		$id        = $post_id ? $post_id : get_the_ID();
		$transform = esc_attr( get_post_meta( $id, '_post_title_font_weight', true ) );
	}

	if ( empty( $transform ) ) {
		$transform = nice_get_option( '_heading_font_weight', null );
	}

	return $transform;
}
endif;

if ( ! function_exists( 'nice_post_title_font_size' ) ) :
/**
 * Obtain title font size for the current page.
 *
 * @since  2.0
 *
 * @param  int    $post_id
 *
 * @return string
 */
function nice_post_title_font_size( $post_id = null ) {
	if ( is_page() || is_single() ) {
		$id        = $post_id ? $post_id : get_the_ID();
		$size = esc_attr( get_post_meta( $id, '_post_title_font_size', true ) );
	}

	if ( empty( $size ) ) {
		$size = nice_get_option( '_heading_font_size', null );
	}

	if ( empty( $size ) ) {
		return 'h1';
	}

	return $size;
}
endif;

if ( ! function_exists( 'nice_post_heading_title_args' ) ) :
add_filter( 'nice_page_title_args', 'nice_post_heading_title_args' );
/**
 * Set heading header class according to size, transformation, weight.
 *
 * @since  2.0
 *
 * @param  array $args
 *
 * @return array
 */
function nice_post_heading_title_args( $args ) {
	$classes[] = 'page-title';

	$classes[] = nice_post_title_font_size();
	$classes[] = nice_post_title_font_weight();
	$classes[] = nice_post_title_text_transform();

	if ( nice_post_breadcrumbs_display() ) {
		$classes[] = 'breadcrumbs-' . nice_post_breadcrumbs_display_position();
	}

	$args['before'] = '<h1 ' . nice_css_classes( $classes, false ) . '>';
	$args['after']  = '</h1>';

	return $args;
}
endif;