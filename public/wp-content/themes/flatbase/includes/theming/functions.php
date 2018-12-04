<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the front-end setup of the theme.
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

if ( ! function_exists( 'nice_theme_button_class' ) ) :
/**
 * Return a button class from a given class name.
 *
 * @since  1.0.0
 *
 * @param  string  $class
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_button_class( $class, $echo = false ) {
	return nice_prefix_class( 'btn', $class, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_text_class' ) ) :
/**
 * Return a text class from a given class name.
 *
 * @since  1.0.0
 *
 * @param  string  $class
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_text_class( $class, $echo = false ) {
	return nice_prefix_class( 'text', $class, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_background_class' ) ) :
/**
 * Return a background class from a given class name.
 *
 * @since  1.0.0
 *
 * @param  string  $class
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_background_class( $class, $echo = false ) {
	return nice_prefix_class( 'background', $class, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_border_class' ) ) :
/**
 * Return a border class from a given class name.
 *
 * @since  1.0.0
 *
 * @param  string  $class
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_border_class( $class, $echo = false ) {
	return nice_prefix_class( 'border', $class, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_pills_class' ) ) :
/**
 * Return pills' class from a given class name.
 *
 * @since  1.0.0
 *
 * @param  string  $class
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_pills_class( $class, $echo = false ) {
	return nice_prefix_class( 'pills', $class, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_color_button_class' ) ) :
/**
 * Return button class from a color ID.
 *
 * Other button class functions:
 * @see nice_theme_style_button_class()
 * @see nice_theme_size_button_class()
 * @see nice_theme_shape_button_class()
 * @see nice_theme_outline_button_class()
 *
 * @since  1.0.0
 *
 * @param  string  $color
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_color_button_class( $color, $echo = false ) {
	return nice_theme_button_class( $color, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_color_text_class' ) ) :
/**
 * Return text class from a color ID.
 *
 * @since  1.0.0
 *
 * @param  string  $color
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_color_text_class( $color, $echo = false ) {
	return nice_theme_text_class( $color, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_color_background_class' ) ) :
/**
 * Return background class from a color ID.
 *
 * @since  1.0.0
 *
 * @param  string  $color
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_color_background_class( $color, $echo = false ) {
	return nice_theme_background_class( $color, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_color_border_class' ) ) :
/**
 * Return border class from a color ID.
 *
 * @since  1.0.0
 *
 * @param  string  $color
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_color_border_class( $color, $echo = false ) {
	return nice_theme_border_class( $color, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_color_pills_class' ) ) :
/**
 * Return pills' class from a color ID.
 *
 * @since  1.0.0
 *
 * @param  string  $color
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_color_pills_class( $color, $echo = false ) {
	return nice_theme_pills_class( $color, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_style_button_class' ) ) :
/**
 * Return button class from a style value.
 *
 * @since  1.0.0
 *
 * @param  string  $style
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_style_button_class( $style, $echo = false ) {
	return nice_theme_button_class( $style, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_size_button_class' ) ) :
/**
 * Return button class from a size value.
 *
 * @since  1.0.0
 *
 * @param  string  $size
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_size_button_class( $size, $echo = false ) {
	return nice_theme_button_class( $size, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_shape_button_class' ) ) :
/**
 * Return button class from a shape value.
 *
 * @since  1.0.0
 *
 * @param  string  $shape
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_shape_button_class( $shape, $echo = false ) {
	return nice_theme_button_class( $shape, $echo );
}
endif;

if ( ! function_exists( 'nice_theme_outline_button_class' ) ) :
/**
 * Return button class from an outline value.
 *
 * @since  1.0.0
 *
 * @param  string  $outline
 * @param  bool    $echo
 *
 * @return string
 */
function nice_theme_outline_button_class( $outline, $echo = false ) {
	return nice_theme_button_class( $outline, $echo );
}
endif;
