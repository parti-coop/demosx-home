<?php
/**
 * Flatbase by NiceThemes.
 *
 * Manage page content in home page template.
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

if ( ! function_exists( 'nice_homepage_knowledgebase' ) ) :
/**
 * Display home page contents.
 *
 * @since  2.0
 *
 * @param  bool   $echo Whether to print the content or just return it.
 *
 * @return string
 */
function nice_homepage_knowledgebase( $echo = true ) {
	// Allow bypass.
	if ( $output = apply_filters( 'nice_homepage_knowledgebase', '' ) ) {
		return $output;
	}

	if ( ! $echo ) {
		ob_start();
	}

	//if ( nice_bool( nice_get_option( 'nice_homepage_infoboxes' ) ) ) {
		get_template_part( 'template-parts/home/knowledgebase' );
	//}

	if ( ! $echo ) {
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	return null;
}
endif;
