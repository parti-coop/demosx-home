<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the sidebar configuration.
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


if ( ! function_exists( 'nice_sidebar_set' ) ) :
add_filter( 'nice_sidebar_id', 'nice_sidebar_set' );
/**
 * Set ID of sidebar for the current view.
 *
 * @see nice_sidebar_do()
 *
 * @return string
 */
function nice_sidebar_set() {

	if ( is_page_template( 'page-templates/blog.php' ) ) {
		$sidebar_id = 'primary';
	} elseif ( is_page_template( 'template-faq.php' ) || is_page_template( 'template-faq-scroll.php' ) ) {
		$sidebar_id = 'faq';
	} elseif ( is_singular( 'article' ) || is_tax( 'article-category' ) || is_tax( 'article-tag' ) ) {
		$sidebar_id = 'knowledgebase';
	} elseif ( ! is_page() || is_front_page() || is_home() ) {
		$sidebar_id = 'primary';
	} else {
		$sidebar_id = 'page';
	}

	return $sidebar_id;
}
endif;

if ( ! function_exists( 'nice_has_sidebar' ) ) :
/**
 * Check if the theme should be currently displaying a sidebar.
 *
 * This function returns the result of a static variable to make sure secondary
 * loops don't overwrite the value for the main page being displayed. Set the
 * $reload parameter to true if you need to refresh the result.
 *
 * @since  2.0
 *
 * @param  bool $reload
 *
 * @return bool
 */
function nice_has_sidebar( $reload = false ) {
	static $has_sidebar = null;

	if ( is_null( $has_sidebar ) || $reload ) {
		$has_sidebar = true;
	}

	if ( is_404() ) {
		$has_sidebar = false;
	}

	if ( is_page() || is_singular() ) {
		$_post_sidebar = get_post_meta( get_the_ID(), '_post_sidebar', true );
		$has_sidebar = nice_bool( $_post_sidebar ) || empty( $_post_sidebar );
	}

	return $has_sidebar;
}
endif;

if ( ! function_exists( 'nice_sidebar_theming_body_class' ) ) :
add_filter( 'body_class', 'nice_sidebar_theming_body_class' );
/**
 * Assign a body class if the sidebar is active for that page/post.
 *
 * @since 2.0
 *
 * @param  array $classes
 * @return array
 */
function nice_sidebar_theming_body_class( $classes ) {
	if ( nice_has_sidebar() ) {
		$classes[] = 'has-sidebar';
	}

	return $classes;
}
endif;

if ( ! function_exists( 'nice_sidebar_theming' ) ) :
add_action( 'wp', 'nice_sidebar_theming' );
/**
 * Assign the sidebar ID and position.
 *
 * @since 2.0
 */
function nice_sidebar_theming() {
	if ( ! is_page() && ! is_single() ) {
		return;
	}

	if ( ! nice_has_sidebar() ) {
		remove_action( 'nice_sidebar', 'nice_sidebar_do' );
		add_filter( 'nice_content_class', 'nice_content_full_width' );
		return;
	}

	// Assign sidebar ID.
	add_filter( 'nice_sidebar_id', 'nice_sidebar_theming_id' );

	// Assign sidebar position.
	add_filter( 'nice_sidebar_position', 'nice_sidebar_theming_position' );
}
endif;

if ( ! function_exists( 'nice_sidebar_theming_id' ) ) :
/**
 * Helper function to return the current post sidebar ID.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_sidebar_theming_id() {

	$sidebar_id = get_post_meta( get_the_ID(), '_post_sidebar_id', true );

	return nice_bool( $sidebar_id ) ? $sidebar_id : nice_sidebar_set();
}
endif;

if ( ! function_exists( 'nice_sidebar_theming_position' ) ) :
/**
 * Helper function to return the current post sidebar position.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_sidebar_theming_position() {
	return get_post_meta( get_the_ID(), '_post_sidebar_position', true );
}
endif;
