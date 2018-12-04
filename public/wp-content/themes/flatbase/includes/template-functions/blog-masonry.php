<?php
/**
 * Flatbase by NiceThemes.
 *
 * Utilitary functions for contact template.
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

if ( ! function_exists( 'nice_masonry_blog_enable_scripts' ) ) :
add_action( 'nice_register_scripts', 'nice_masonry_blog_enable_scripts', 0 );
/**
 * Enable scripts for Masonry blog.
 *
 * @since 1.0.0
 */
function nice_masonry_blog_enable_scripts() {
	if ( ! is_page_template( 'page-templates/blog-masonry.php' ) ) {
		return;
	}

	/**
	 * @hook nice_load_more_posts_scripts
	 *
	 * Activate JS to load more posts via AJAX.
	 *
	 * @since 1.0.0
	 */
	add_filter( 'nice_load_more_posts_scripts', '__return_true', 10 );
}
endif;

if ( ! function_exists( 'nice_masonry_blog_query' ) ) :
add_action( 'nice_blog_masonry_before', 'nice_masonry_blog_query' );
/**
 * Run query for blog template.
 *
 * @since 1.0.0
 */
function nice_masonry_blog_query() {
	/*
	 * Obtain number of the current page.
	 */
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} elseif ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' );
	} else {
		$paged = 1;
	}

	/*
	 * Obtain arguments to run posts query.
	 */
	$query_args = apply_filters( 'nice_masonry_blog_template_query_args', array(
			'post_type' => 'post',
			'paged'	    => $paged,
		)
	);

	/*
	 * Perform posts query.
	 */
	query_posts( $query_args );
}
endif;

/**
 * Initialize Masonry Loop.
 *
 * @since 1.0.0
 */
add_action( 'nice_blog_masonry_before', 'nice_masonry_loop_init' );

if ( ! function_exists( 'nice_blog_masonry_show_load_more_button' ) ) :
/**
 * Check if the "Load More" button should be loaded in Masonry blog.
 *
 * @since 1.0.0
 */
function nice_blog_masonry_show_load_more_button() {
	/**
	 * Check if the "Load More" button should be displayed.
	 *
	 * @since 1.0.0
	 */
	$load_more_button = ( 'on_button' === nice_get_option( 'nice_masonry_posts_load_method' ) );

	return apply_filters( 'nice_blog_masonry_show_load_more_button', $load_more_button );
}
endif;

/**
 * Iterate Masonry Loop when loading post template.
 *
 * @since 1.0.0
 */
add_action( 'get_template_part_template-parts/archive/content-masonry', 'nice_masonry_loop_add' );

if ( ! function_exists( 'nice_masonry_columns' ) ) :
/**
 * Obtain number of columns for Masonry blog.
 *
 * @since  1.0.0
 *
 * @return integer
 */
function nice_masonry_columns() {
	return apply_filters( 'nice_masonry_columns', 3 );
}
endif;

if ( ! function_exists( 'masonry_loop' ) ) :
/**
 * Get the current iteration of the Masonry loop.
 *
 * @since  1.0.0
 *
 * @return int
 *
 * @todo: refactor without using globals.
 */
function masonry_loop() {
	if ( ! isset( $GLOBALS['nice_masonry_loop'] ) ) {
		nice_masonry_loop_init();
	}

	return $GLOBALS['nice_masonry_loop'];
}
endif;

if ( ! function_exists( 'nice_masonry_loop_init' ) ) :
/**
 * Set up current iteration in Masonry blog.
 *
 * @since 1.0.0
 *
 * @todo: refactor without using globals.
 */
function nice_masonry_loop_init() {
	global $nice_masonry_loop;

	if ( is_null( $nice_masonry_loop ) ) {
		$nice_masonry_loop = 0;
	}
}
endif;

if ( ! function_exists( 'nice_masonry_loop_add' ) ) :
/**
 * Increase Masonry loop global variable for next iteration.
 *
 * @since 1.0.0
 */
function nice_masonry_loop_add() {
	static $loop;

	if ( is_null( $loop ) ) {
		$loop = 0;
	}

	global $nice_masonry_loop;

	$loop++;

	$nice_masonry_loop = $loop;
}
endif;
