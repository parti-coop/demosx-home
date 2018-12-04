<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the general layout according to options and custom fields.
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

if ( ! function_exists( 'nice_body_class' ) ) :
add_filter( 'body_class', 'nice_body_class' );
/**
 * Add custom classes to default body class.
 *
 * @since  1.0.0
 *
 * @param  array $classes List of body classes.
 * @return array
 */
function nice_body_class( $classes ) {
	/**
	 * Return `custom-background` class if we have a background selected and it
	 * hasn't been applied previously.
	 *
	 * @note: This should be removed when we develop full compatibility with
	 *        both custom backgrounds and Customizer.
	 *
	 * @since 1.0.0
	 */
	if ( nice_get_option( 'nice_background_image' ) && ! get_background_image() ) {
		$classes[] = 'custom-background';
	}

	/**
	 * Add custom page template class for versions of WordPress that don't
	 * support it (< 4.1).
	 *
	 * @since 1.0.0
	 */
	if ( nice_is_homepage() && ! in_array( 'page-template-home', $classes, true ) ) {
		$classes[] = 'page-template-home';
	}

	/**
	 * Add class for boxed layout.
	 *
	 * @since 1.0
	 */
	if ( nice_boxed_layout() ) {
		$classes[] = 'boxed-layout';

		/**
		 * Add class for background color.
		 *
		 * @since 1.0
		 */
		if ( $background_color = nice_get_option( 'nice_background_color' ) ) {
			$classes[] = nice_theme_color_background_class( $background_color );
		}
	}

	/**
	 * Add class for content skin.
	 *
	 * @since 1.0
	 */
	$content_skin = get_post_meta( get_the_ID(), '_post_content_skin', true );

	if ( ! $content_skin || is_archive() || is_home() ) {
		$content_skin = nice_get_option( 'nice_content_skin' );
	}

	if ( in_array( $content_skin, array( 'light', 'dark' ), true ) ) {
		$classes[] = $content_skin . '-skin';
		$classes[] = $content_skin;
	}

	return $classes;
}
endif;

if ( ! function_exists( 'nice_body_data' ) ) :
/**
 * Print data attributes for body element.
 *
 * @since 1.0.0
 *
 * @param array $data
 */
function nice_body_data( array $data = null ) {
	$data = nice_body_get_data( $data );

	if ( is_array( $data ) && ! empty( $data ) ) {
		$data_parts = array();

		foreach ( $data as $key => $value ) {
			$data_parts[] = 'data-' . $key . '="' . str_replace( '_', '-', esc_attr( $value ) ) . '"';
		}
	}

	if ( ! empty( $data_parts ) ) {
		echo join( ' ', $data_parts ); // WPCS: XSS Ok.
	}
}
endif;

if ( ! function_exists( 'nice_body_get_data' ) ) :
/**
 * Obtain data attributes for body element.
 *
 * @since 2.0
 *
 * @param array  $data
 *
 * @return array
 */
function nice_body_get_data( array $data = null ) {
	$default_data = array();
	$default_data['btn-shape'] = 'default';

	if ( $nice_page_loader = nice_get_option( 'nice_page_loader' ) ) {
		$data['page-loader'] = sanitize_title( $nice_page_loader );
	}

	if ( $btn_color = nice_get_option( '_btn_color' ) ) {
		$data['btn-color'] = $btn_color;
	}

	if ( $btn_shape = nice_get_option( '_btn_shape' ) ) {
		$data['btn-shape'] = $btn_shape;
	}

	if ( nice_lazyload_images() ) {
		$data['use-lazyload'] = 'true';
	}

	$data = wp_parse_args( $data, $default_data );

	/**
	 * @hook nice_body_get_data
	 *
	 * Hook in here to add or remove data attributes.
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'nice_body_data', $data );
}
endif;

if ( ! function_exists( 'nice_post_content_styling' ) ) :
add_filter( 'nice_inline_styles', 'nice_post_content_styling', 20 );
/**
 * Add Custom Styling for the content
 *
 * @since 2.0
 *
 * @param string $inline_styles
 *
 * @return string
 */
function nice_post_content_styling( $inline_styles ) {
	$output = '';

	if ( is_page() || is_single() ) {
		$width_type  = get_post_meta( get_the_ID(), '_post_content_width', true );
		$width_value = get_post_meta( get_the_ID(), '_post_content_width_value', true );

		if ( 'limit' === $width_type && ! empty( $width_value[1] ) ) {
			$output = '#container { width: ' . floatval( esc_attr( $width_value[0] ) ) . strval( esc_attr( $width_value[1] ) ) . ' }';
		}
	}

	if ( ! empty( $output ) ) {
		$output = strip_tags( '/* Post Content */' . "\n\n" . $output );
		$inline_styles .= "\n" . $output;
	}

	return $inline_styles;
}
endif;

if ( ! function_exists( 'nice_content_full_width' ) ) :
/**
 * Set full-width width for content class.
 *
 * @since 2.0
 *
 * @param array $classes
 *
 * @return string
 */
function nice_content_full_width( array $classes = null ) {

	$classes[] = 'full-width';

	return $classes;
}
endif;
