<?php
/**
 * Flatbase by NiceThemes.
 *
 * Manage page content in home page template.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      https://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_homepage_content' ) ) :
/**
 * Display home page contents.
 *
 * @since  2.0
 *
 * @param  bool   $echo Whether to print the content or just return it.
 *
 * @return string
 */
function nice_homepage_content( $echo = true ) {
	// Allow bypass.
	if ( $output = apply_filters( 'nice_homepage_content', '' ) ) {
		return $output;
	}

	if ( ! $echo ) {
		ob_start();
	}

	//if ( nice_bool( nice_get_option( 'nice_homepage_content' ) ) ) {
		get_template_part( 'template-parts/home/content' );
	//}

	if ( ! $echo ) {
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	return null;
}
endif;

if ( ! function_exists( 'nice_homepage_content_styles' ) ) :
add_filter( 'nice_homepage_styles', 'nice_homepage_content_styles' );
/**
 * Add inline styles for content section.
 *
 * @since  2.0
 *
 * @param  string $styles Default inline CSS.
 *
 * @return string
 */
function nice_homepage_content_styles( $styles = '' ) {
	return $styles . nice_homepage_block_styles( 'content' );
}
endif;
