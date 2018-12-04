<?php
/**
 * NiceFramework by NiceThemes.
 *
 * This file provides an implementation of our web font subscriber.
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2017 NiceThemes
 * @since     2.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_web_fonts_instance' ) ) :
/**
 * Obtain current web fonts instance.
 *
 * @since 2.0
 *
 * @private
 */
function nice_web_fonts_instance() {
	static $instance;

	if ( is_null( $instance ) ) {
		if ( ! class_exists( 'Nice_Web_Font_Subscriber' ) ) {
			nice_loader( 'engine/admin/classes/class-nice-web-font-subscriber.php' );
		}

		$instance = new Nice_Web_Font_Subscriber();
	}

	return $instance;
}
endif;

if ( ! function_exists( 'nice_register_web_font' ) ) :
/**
 * Register a web font.
 *
 * @since 2.0
 *
 * @param string $font
 */
function nice_register_web_font( $font = '' ) {
	nice_web_fonts_instance()->register_font( $font );
}
endif;

if ( ! function_exists( 'nice_web_font_is_registered' ) ) :
/**
 * Check if a web font is already registered.
 *
 * @since 2.0
 *
 * @param string $font
 *
 * @return string
 */
function nice_web_font_is_registered( $font = '' ) {
	return nice_web_fonts_instance()->is_registered( $font );
}
endif;

if ( ! function_exists( 'nice_web_font_uri' ) ) :
/**
 * Obtain the URI for one or more webfonts.
 *
 * @since 2.0
 *
 * @param string|array $fonts
 *
 * @return string
 */
function nice_web_font_uri( $fonts = '' ) {
	return nice_web_fonts_instance()->get_uri( $fonts );
}
endif;

if ( ! function_exists( 'nice_get_web_fonts' ) ) :
/**
 * Obtain list of webfonts.
 *
 * @since 2.0
 *
 * @return array
 */
function nice_get_web_fonts() {
	static $processed_fonts = null;

	if ( ! is_null( $processed_fonts ) ) {
		return $processed_fonts;
	}

	$processed_fonts = array();
	$web_fonts       = array();
	$google_fonts    = nice_get_google_fonts();
	$nice_options    = nice_get_options();

	if ( ! empty( $nice_options ) ) {
		foreach ( $nice_options as $key => $option ) {
			if ( ! isset( $option['font-family'] ) && ! isset( $option['family'] ) ) {
				continue;
			}

			/**
			 * @hook nice_use_web_font
			 *
			 * Check if the current font should be used.
			 *
			 * @since 2.0
			 */
			$use_font = apply_filters( 'nice_use_web_font', true, $key, $option );

			if ( ! $use_font ) {
				continue;
			}

			$web_fonts[] = $use_font ? ( isset( $option['font-family'] ) ? $option['font-family'] : $option['family'] ) : '';
		}
	}

	/**
	 * @hook nice_web_fonts
	 *
	 * Web fonts are defined here.
	 *
	 * @since 2.0
	 */
	$web_fonts = apply_filters( 'nice_web_fonts', $web_fonts );

	if ( empty( $web_fonts ) ) {
		return array();
	}

	// Process fonts.
	foreach ( $google_fonts as $font ) {
		if ( in_array( $font['name'], $web_fonts, true ) ) {
			$processed_fonts[] = $font['name'] . $font['variant'];
			nice_register_web_font( $font['name'] . $font['variant'] );
		}
	}

	return $processed_fonts;
}
endif;

if ( ! function_exists( 'nice_get_web_fonts_uri' ) ) :
/**
 * Obtain URL for web fonts.
 *
 * @since  2.0
 *
 * @return string
 */
function nice_get_web_fonts_uri() {
	return nice_web_fonts_instance()->get_uri( nice_get_web_fonts() );
}
endif;
