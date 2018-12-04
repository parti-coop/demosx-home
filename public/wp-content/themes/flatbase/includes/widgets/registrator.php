<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage widget registration.
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

if ( ! function_exists( 'nice_register_widgets' ) ) :
add_action( 'widgets_init', 'nice_register_widgets' );
/**
 * Register loaded widgets.
 *
 * @since 2.0
 */
function nice_register_widgets() {
	// Register blog author widget.
	if ( apply_filters( 'nice_register_widget_blog_author', true ) ) {
		register_widget( 'Nice_BlogAuthor' );
	}

	// Register Flickr widget.
	if ( apply_filters( 'nice_register_widget_flickr', true ) ) {
		register_widget( 'Nice_Flickr' );
	}

	// Register social widget.
	if ( apply_filters( 'nice_register_widget_social', true ) ) {
		register_widget( 'Nice_Social' );
	}

	// Register article categories widgets.
	if ( apply_filters( 'nice_register_widget_article_categories', true ) ) {
		register_widget( 'Nice_ArticleCategories' );
	}

	// Register popular articles.
	if ( apply_filters( 'nice_register_widget_popular_articles', true ) ) {
		register_widget( 'Nice_PopularArticles' );
	}

	// Register recent articles.
	if ( apply_filters( 'nice_register_widget_recent_articles', true ) ) {
		register_widget( 'Nice_Recent_Articles_Widget' );
	}

	// Register Twitter widget.
	if ( apply_filters( 'nice_register_widget_twitter', true ) ) {
		register_widget( 'Nice_Twitter' );
	}

}
endif;
