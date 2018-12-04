<?php
/**
 * Bossa by NiceThemes.
 *
 * This file contains functions to manage and alter colors.
 *
 * @package   Bossa
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://www.nicethemes.com/theme-page
 * @copyright 2016 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_color_brightness' ) ) :
/**
 * Convert a hex color to another given a brightness percentage.
 *
 * @since 2.0
 *
 * @param  string $hex
 * @param  float  $percent
 *
 * @return string
 */
function nice_color_brightness( $hex, $percent ) {
	// Check if shorthand hex value given (eg. #FFF instead of #FFFFFF)
	if ( 3 === strlen( $hex ) ) {
		$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	}

	// Work out if hash given
	$hash = '';
	if ( stristr( $hex, '#' ) ) {
		$hex  = str_replace( '#', '', $hex );
		$hash = '#';
	}

	/// HEX TO RGB
	$rgb = array(
		hexdec( substr( $hex, 0, 2 ) ),
		hexdec( substr( $hex, 2, 2 ) ),
		hexdec( substr( $hex, 4, 2 ) ),
	);

	//// CALCULATE
	for ( $i = 0; $i < 3; $i ++ ) {
		// See if brighter or darker
		if ( $percent > 0 ) {
			// Lighter
			$rgb[ $i ] = round( $rgb[ $i ] * $percent ) + round( 255 * ( 1 - $percent ) );
		} else {
			// Darker
			$positive_percent = $percent - ( $percent * 2 );
			$rgb[ $i ]        = round( $rgb[ $i ] * $positive_percent );
		}

		// In case rounding up causes us to go to 256
		if ( $rgb[ $i ] > 255 ) {
			$rgb[ $i ] = 255;
		}
	}

	//// RGB to Hex
	$hex = '';
	for ( $i = 0; $i < 3; $i ++ ) {
		// Convert the decimal digit to hex
		$hex_digit = dechex( $rgb[ $i ] );

		// Add a leading zero if necessary
		if ( 1 === strlen( $hex_digit ) ) {
			$hex_digit = '0' . $hex_digit;
		}

		// Append to the hex string
		$hex .= $hex_digit;
	}

	return $hash . $hex;
}
endif;

if ( ! function_exists( 'nice_color_hex2rgb' ) ) :
/**
 * Correctly parse a given hex color to its RGB counterpart.
 *
 * @since  2.0
 *
 * @param  string
 *
 * @return array
 */
function nice_color_hex2rgb( $hex_color ) {
	$hex_color = str_replace( '#', '', $hex_color );

	if ( ! is_string( $hex_color ) || ! ctype_xdigit( $hex_color ) ) {
		$hex_color = '000000';
	}

	switch ( strlen( $hex_color ) ) {
		case 3:
			$rgb_color = array(
				hexdec( substr( $hex_color, 0, 1 ) . substr( $hex_color, 0, 1 ) ),
				hexdec( substr( $hex_color, 1, 1 ) . substr( $hex_color, 1, 1 ) ),
				hexdec( substr( $hex_color, 2, 1 ) . substr( $hex_color, 2, 1 ) ),
			);
			break;
		case 6:
			$rgb_color = array(
				hexdec( substr( $hex_color, 0, 2 ) ),
				hexdec( substr( $hex_color, 2, 2 ) ),
				hexdec( substr( $hex_color, 4, 2 ) ),
			);
			break;
		default :
			$rgb_color = array(
				hexdec( '00' ),
				hexdec( '00' ),
				hexdec( '00' ),
			);
			break;
	}

	return $rgb_color;
}
endif;

if ( ! function_exists( 'nice_color_rgb2hex' ) ) :
/**
 * Return an HEX color from an RGB
 *
 * @since 2.0
 *
 * @param  array $rgb
 *
 * @return string
 */
function nice_color_rgb2hex( $rgb ) {
	$hex = "#";
	$hex .= str_pad( dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad( dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad( dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

	return $hex; // returns the hex value including the number sign (#)
}
endif;


if ( ! function_exists( 'nice_color_lighten' ) ) :
/**
 * Imitate the sass function lighten().
 *
 * @since 2.0
 *
 * @param  string $color
 * @param  float  $percent (from 0 to 1)
 *
 * @return string
 */
function nice_color_lighten( $color, $percent ) {
	$rgb = nice_color_hex2rgb( $color );
	$hsl = nice_color_rgb2hsl( $rgb[0], $rgb[1], $rgb[2] );

	$newH = $hsl[2] + abs( $percent );
	$hsl[2] = $newH;

	$new_rgb = nice_color_hsl2rgb( $hsl[0], $hsl[1], $hsl[2]);

	return nice_color_rgb2hex( $new_rgb );
}
endif;



if ( ! function_exists( 'nice_color_darken' ) ) :
/**
 * Imitate the sass function darken().
 *
 * @since 2.0
 *
 * @param  string $color
 * @param  float  $percent (from 0 to 1)
 *
 * @return string
 */
function nice_color_darken( $color, $percent ) {
	$rgb = nice_color_hex2rgb( $color );
	$hsl = nice_color_rgb2hsl( $rgb[0], $rgb[1], $rgb[2] );

	$newH = $hsl[2] - abs( $percent );
	$hsl[2] = $newH;

	$new_rgb = nice_color_hsl2rgb( $hsl[0], $hsl[1], $hsl[2]);

	return nice_color_rgb2hex( $new_rgb );
}
endif;


if ( ! function_exists( 'nice_color_hex2rgba' ) ) :
/**
 * Return the equivalent RGBA value for a hex color and an opacity amount.
 *
 * @since 2.0
 *
 * @param  string  $color
 * @param  int     $opacity
 *
 * @return string
 */
function nice_color_hex2rgba( $color, $opacity = null ) {
	$default = 'rgb(0,0,0)';

	//Return default if no color provided
	if ( empty( $color ) ) {
		return $default;
	}

	// Sanitize $color if "#" is provided
	if ( '#' === $color[0] ) {
		$color = substr( $color, 1 );
	}

	// Check if color has 6 or 3 characters and get values
	if ( 6 === strlen( $color ) ) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( 3 === strlen( $color ) ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $default;
	}

	// Convert hexdec to rgb
	$rgb = array_map( 'hexdec', $hex );

	// Check if opacity is set(rgba or rgb)
	if ( $opacity ) {
		if ( abs( $opacity ) > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode( ',', $rgb ) . ')';
	}

	// Return rgb(a) color string
	return $output;
}
endif;

if ( ! function_exists( 'nice_color_rgb2hsl' ) ) :
/**
 * Return the equivalent hsl value for a rgb color.
 *
 * @since 2.0
 *
 * @param  string  $r
 * @param  string  $g
 * @param  string  $b
 *
 * @return array
 */
function nice_color_rgb2hsl( $r, $g, $b ) {
	$oldR = $r;
	$oldG = $g;
	$oldB = $b;

	$r /= 255;
	$g /= 255;
	$b /= 255;

	$max = max( $r, $g, $b );
	$min = min( $r, $g, $b );

	$h;
	$s;
	$l = ( $max + $min ) / 2;
	$d = $max - $min;

		if ( $d == 0 ) {
			$h = $s = 0; // achromatic
		} else {
			$s = $d / ( 1 - abs( 2 * $l - 1 ) );

		switch( $max ) {
			case $r:
				$h = 60 * fmod( ( ( $g - $b ) / $d ), 6 );
				if ( $b > $g ) {
					$h += 360;
				}
				break;

			case $g:
				$h = 60 * ( ( $b - $r ) / $d + 2 );
				break;

			case $b:
				$h = 60 * ( ( $r - $g ) / $d + 4 );
				break;
		}
	}

	return array( round( $h, 2 ), round( $s, 2 ), round( $l, 2 ) );
}
endif;

if ( ! function_exists( 'nice_color_hsl2rgb' ) ) :
/**
 * Return the equivalent rgb value for a hsl color.
 *
 * @since 2.0
 *
 * @param  string  $h
 * @param  string  $s
 * @param  string  $l
 *
 * @return array
 */
function nice_color_hsl2rgb( $h, $s, $l ) {
	$r;
	$g;
	$b;

	$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
	$x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
	$m = $l - ( $c / 2 );

	if ( $h < 60 ) {
		$r = $c;
		$g = $x;
		$b = 0;
	} else if ( $h < 120 ) {
		$r = $x;
		$g = $c;
		$b = 0;
	} else if ( $h < 180 ) {
		$r = 0;
		$g = $c;
		$b = $x;
	} else if ( $h < 240 ) {
		$r = 0;
		$g = $x;
		$b = $c;
	} else if ( $h < 300 ) {
		$r = $x;
		$g = 0;
		$b = $c;
	} else {
		$r = $c;
		$g = 0;
		$b = $x;
	}

	$r = ( $r + $m ) * 255;
	$g = ( $g + $m ) * 255;
	$b = ( $b + $m  ) * 255;

	return array( floor( $r ), floor( $g ), floor( $b ) );
}
endif;

if ( ! function_exists( 'nice_contrast_color' ) ) :
/**
 * Return a color with high contrast to the given one..
 *
 * @since 2.0
 *
 * @param  string  $color
 *
 * @return string
 */
function nice_contrast_color( $color ) {
	static $contrast_colors = array();

	if ( ! isset( $contrast_colors[ $color ] ) ) {
		$contrast_color_light = apply_filters( 'nice_contrast_color_light', '#eee' );
		$contrast_color_dark  = apply_filters( 'nice_contrast_color_dark', '#333' );

		$color_rgba = array_combine( array( 'r', 'g', 'b', 'a' ), sscanf( trim( str_replace(' ', '', nice_color_hex2rgba( $color, 1 ) ) ), 'rgba(%d, %d, %d, %f )' ) );

		$contrast_colors[ $color ] = ( ( $color_rgba['r'] * 0.299 + $color_rgba['g'] * 0.587 + $color_rgba['b'] * 0.114 ) > 186 ) ? $contrast_color_dark : $contrast_color_light;
	}

	return $contrast_colors[ $color ];
}
endif;

