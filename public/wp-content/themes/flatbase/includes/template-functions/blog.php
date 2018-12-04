<?php
/**
 * Flatbase by NiceThemes.
 *
 * Utilitary functions for blog template.
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

if ( ! function_exists( 'nice_blog_query' ) ) :
add_action( 'nice_blog_before', 'nice_blog_query' );
/**
 * Run query for blog template.
 *
 * @since 1.0.0
 */
function nice_blog_query() {
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
	$query_args = apply_filters( 'nice_blog_template_query_args', array(
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
