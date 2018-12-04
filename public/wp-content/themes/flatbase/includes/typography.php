<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the different typography options.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0.2
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_font_weight' ) ) :
/**
 * Return an array of the different font weight options
 *
 * @since  1.1.1
 *
 * @return array
 */
function nice_font_weight() {
	$font_weight = array(
		''  => esc_html__( 'Default CSS', 'nicethemes' ),
		100 => esc_html__( '100', 'nicethemes' ),
		200 => esc_html__( '200', 'nicethemes' ),
		300 => esc_html__( '300', 'nicethemes' ),
		400 => esc_html__( '400', 'nicethemes' ),
		500 => esc_html__( '500', 'nicethemes' ),
		600 => esc_html__( '600', 'nicethemes' ),
		700 => esc_html__( '700', 'nicethemes' ),
		800 => esc_html__( '800', 'nicethemes' ),
		900 => esc_html__( '900', 'nicethemes' ),
	);

	return $font_weight;
}
endif;