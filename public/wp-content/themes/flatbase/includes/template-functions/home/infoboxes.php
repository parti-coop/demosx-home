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

if ( ! function_exists( 'nice_homepage_infoboxes' ) ) :
/**
 * Display home page contents.
 *
 * @since  2.0
 *
 * @param  bool   $echo Whether to print the content or just return it.
 *
 * @return string
 */
function nice_homepage_infoboxes( $echo = true ) {
	// Allow bypass.
	if ( $output = apply_filters( 'nice_homepage_infoboxes', '' ) ) {
		return $output;
	}

	if ( ! $echo ) {
		ob_start();
	}

	get_template_part( 'template-parts/home/infoboxes' );

	if ( ! $echo ) {
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	return null;
}
endif;

if ( ! function_exists( 'nice_infobox_class' ) ) :
/**
 * Add classes to the infoboxes block element.
 *
 *
 * @since  2.0
 *
 * @param  array $class List of classes for #header element.
 * @param  bool  $echo  Whether to print the output or not.
 *
 * @return string
 */
function nice_infobox_class( array $class = array(), $echo = true ) {
	$classes = array();

	$classes[] = 'infoboxes home-block clearfix';

	if ( $nice_infobox_background_color = nice_get_option( '_infobox_background_color', true ) ) {
		$classes[] = nice_theme_color_background_class( $nice_infobox_background_color );
	}

	$classes[] = nice_get_option( '_infobox_skin', true );

	$image_effect = nice_get_option( '_infobox_image_effect', true );

	switch ( $image_effect ) {
		case 'zoomIn' :
			$classes[] = ' image-zoomIn';
			break;
		case 'no' :
			$classes[] = '';
			break;
		default :
			$classes[] = ' image-zoomIn';
	}

	if ( ! empty( $class ) ) {
		$classes = array_merge( $classes, $class );
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * @hook nice_infobox_class
	 *
	 * Modify infobox classes by hooking in here.
	 *
	 * @since 2.0
	 */
	$classes = apply_filters( 'nice_infobox_class', $classes, $class );

	$output = nice_css_classes( $classes, $echo );

	return $output;
}
endif;
