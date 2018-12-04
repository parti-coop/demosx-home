<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the different typography options.
 *
 * @see nice_custom_fonts()
 * @see nice_load_web_fonts()
 * @see nice_default_fonts()
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2017 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_custom_fonts' ) ) :
add_filter( 'nice_inline_styles', 'nice_custom_fonts' );
/**
 * Add custom fonts.
 *
 * @since 1.0.0
 */
function nice_custom_fonts( $inline_styles ) {

	global $nice_options;

	// Obtain all breakpoints.
	$breakpoints = nice_responsive_breakpoints();

	$output = '';

	if ( nice_bool( nice_get_option( 'nice_custom_typography' ) ) ) {

		if ( $font_body = nice_get_option( 'nice_font_body' ) ) {
			$output .= 'body { ' . nice_custom_font_css( $font_body ) . ' }' . "\n";
		}

		if ( $font_nav = nice_get_option( 'nice_font_nav' ) ) {
			$output .= '#top #navigation .nav > li a { ' . nice_custom_font_css( $font_nav ) . ' !important }' . "\n";
		}

		if ( $font_sub_nav = nice_get_option( 'nice_font_subnav' ) ) {
			$output .= '#top #navigation .nav li ul li a { ' . nice_custom_font_css( $font_sub_nav ) . ' !important }' . "\n";
		}

		if ( $font_headings = nice_get_option( 'nice_font_headings' ) ) {
			$output .= 'h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6, #call-to-action { ' . nice_custom_font_css( $font_headings ) . ' !important; }' . "\n";
		}

		if ( $font_buttons = nice_get_option( 'nice_font_buttons' ) ) {
			$output .= '.button-primary, .button-blue, .button-secondary, .header .nav li.current-page a, .header .nav-callout, .cta-button, input[type="submit"], button, #commentform .button, #respond input[type="submit"], .nice-contact-form input[type="submit"], .blog-masonry #posts-ajax-loader-button { ' . nice_custom_font_css( $font_buttons ) . ' }' . "\n";
		}

		if ( $font_inputs = nice_get_option( 'nice_font_inputs' ) ) {
			$output .= 'input, textarea, placeholder, #live-search .input label { ' . nice_custom_font_css( $font_inputs ) . '; }' . "\n";
		}

		if ( isset( $nice_options['nice_font_infobox_title'] ) && $nice_options['nice_font_infobox_title'] )
			$output .= '.nice-infoboxes .infobox-title { ' . nice_custom_font_css( $nice_options['nice_font_infobox_title'] ) . ' }' . "\n";

		if ( isset( $nice_options['nice_font_infobox_content'] ) && $nice_options['nice_font_infobox_content'] )
			$output .= '.infobox .entry-excerpt{ ' . nice_custom_font_css( $nice_options['nice_font_infobox_content'] ) . ' }' . "\n";

		if ( isset( $nice_options['nice_font_welcome_message'] ) && $nice_options['nice_font_welcome_message'] )
			$output .= '.welcome-message h2 { ' . nice_custom_font_css( $nice_options['nice_font_welcome_message'] ) . ' }' . "\n";

		if ( isset( $nice_options['nice_font_welcome_message_extended'] ) && $nice_options['nice_font_welcome_message_extended'] )
			$output .= '.welcome-message p, .welcome-message p a { ' . nice_custom_font_css( $nice_options['nice_font_welcome_message_extended'] ) . ' }' . "\n";

	}

	if ( nice_bool( nice_get_option( 'nice_texttitle' ) ) ) {
		$st = nice_get_option( 'nice_font_site_title' );
		$output .= '#header #top #logo a .text-logo { ' . nice_custom_font_css( $st ) . ' }' . "\n";
	}


	if ( ! empty( $output ) ) {
		$output = strip_tags( '/* Nice Custom Fonts */' . "\n\n" . $output );
		$inline_styles .= "\n" . $output;

	}
	return $inline_styles;

}
endif;


if ( ! function_exists( 'nice_set_default_web_fonts' ) ) :
add_filter( 'nice_web_fonts', 'nice_set_default_web_fonts' );
/**
 * Set default web fonts when none are defined.
 *
 * @since 2.0
 *
 * @param array $web_fonts
 *
 * @return array
 */
function nice_set_default_web_fonts( array $web_fonts = array() ) {
	if ( ! nice_bool_option( '_custom_typography' ) ) {
		$web_fonts = array_merge( $web_fonts, array(
				'Lato',
				'Nunito',
			)
		);
	}

	return $web_fonts;
}
endif;

if ( ! function_exists( 'nice_setup_web_fonts' ) ) :
add_filter( 'nice_use_web_font', 'nice_setup_web_fonts', 10, 3 );
/**
 * Check which web fonts should be loaded.
 *
 * @since  2.0
 *
 * @param  bool   $load
 * @param  string $key
 *
 * @return bool
 */
function nice_setup_web_fonts( $load, $key ) {
	switch ( $key ) {
		case 'nice_font_site_title':
			$load = nice_bool_option( '_texttitle' );
			break;
		default:
			$load = nice_bool_option( '_custom_typography' );
	}

	return $load;
}
endif;


if ( ! function_exists( 'nice_theme_web_fonts' ) ) :
add_action( 'wp_enqueue_scripts', 'nice_theme_web_fonts' );
/**
 * Register the font stylesheet with the default web fonts.
 *
 * @since 2.0
 */
function nice_theme_web_fonts() {
	$web_fonts_uri = nice_get_web_fonts_uri();

	if ( ! $web_fonts_uri  ) {
		return;
	}

	wp_register_style( 'nice-theme-fonts', $web_fonts_uri );
	wp_enqueue_style ( 'nice-theme-fonts' );
}
endif;

if ( ! function_exists( 'nice_web_fonts_async_styles' ) ) :
add_filter( 'nice_theme_async_styles', 'nice_web_fonts_async_styles', 10, 2 );
/**
 * List of styles that can be loaded asynchronously.
 *
 * @since 2.0
 *
 * @param array $scripts
 * @param array $context
 *
 * @return array
 */
function nice_web_fonts_async_styles( $scripts = array(), $context = array() ) {
	static $added_scripts = array();

	if ( nice_load_async_styles() && nice_bool_option( '_async_google_fonts' ) ) {
		if ( ! in_array( 'nice-theme-fonts', $added_scripts, true ) ) {
			$added_scripts[] = 'nice-theme-fonts';
		}

		$href = isset( $context['href'] ) ? $context['href'] : null;
		$handle = isset( $context['handle'] ) ? $context['handle'] : null;

		if ( ! in_array( $handle, $added_scripts, true ) && stripos( $href, 'fonts.googleapis.com' ) ) {
			$added_scripts[] = $handle;
		}
	}

	$scripts = array_merge( $scripts, $added_scripts );

	return $scripts;
}
endif;