<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for the homepage content.
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

// Display Knowledge Base Articles
if ( apply_filters( 'nice_homepage_knowledgebase', true ) ) {

	$number_articles = nice_get_option( '_articles_entries', 5 );

	nicethemes_knowledgebase( array(
								'columns'     => 3,
								'numberposts' => $number_articles,
								'before'      => '<section id="knowledgebase" class="home-block clearfix"><div class="col-full">',
								'after'       => '</div></section>'
								)
							);
}
