<?php
/**
 * NiceFramework by NiceThemes.
 *
 * Theme framework template tags
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2016 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_css_classes' ) ) :
/**
 * Display classes from a given array.
 *
 * @since  2.0
 *
 * @param  array $classes
 * @param  bool  $echo
 *
 * @return string
 */
function nice_css_classes( $classes = array(), $echo = true ) {
	$output = '';

	if ( ! empty( $classes ) ) {
		$output .= 'class="';
		$output .= esc_attr( join( ' ', $classes ) );
		$output .= '"';
	}

	if ( $echo ) {
		echo $output;
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_data_attributes' ) ) :
/**
 * Set data attributes from a given array.
 *
 * @since  2.0
 *
 * @param  array  $data_attributes
 * @param  bool   $echo
 *
 * @return string
 */
function nice_data_attributes( $data_attributes = array(), $echo = true ) {
	$output = implode( ' ', array_map( '_nice_map_data_attributes', array_keys( $data_attributes ), $data_attributes ) );

	if ( $echo ) {
		echo $output;
	}

	return $output;
}
endif;
