<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file helps implement LazyLoad on the current theme.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 *
 * @todo: Move to `public` after reorganizing VC integration files.
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_lazyload_images' ) ) :
/**
 * Check if images should be loaded using LazyLoad.
 *
 * @since 2.0
 *
 * @return bool
 */
function nice_lazyload_images() {
	static $use_lazyload = null;

	if ( is_null( $use_lazyload ) ) {
		/**
		 * @hook nice_lazyload_images
		 *
		 * LazyLoad usage is defined here.
		 *
		 * Hooked here:
		 * @see nice_setup_lazyload()
		 */
		$use_lazyload = apply_filters( 'nice_lazyload_images', false );
	}

	return $use_lazyload;
}
endif;

if ( ! function_exists( 'nice_set_lazyload_image_attributes' ) ) :
add_filter( 'wp_get_attachment_image_attributes', 'nice_set_lazyload_image_attributes', 10, 3 );
/**
 * Set image attributes for LazyLoad to work.
 *
 * @param $attr
 * @param $attachment
 * @param $size
 *
 * @return mixed
 */
function nice_set_lazyload_image_attributes( $attr, $attachment, $size ) {
	if ( nice_lazyload_images() ) {
		if ( isset( $attr['src'] ) ) {
			$attr['data-original'] = $attr['src'];
			unset( $attr['src'] );
		}

		if ( isset( $attr['data-original-set'] ) ) {
			$attr['data-original-set'] = $attr['srcset'];
			unset( $attr['srcset'] );
		}
	}

	return $attr;
}
endif;

if ( ! function_exists( 'nice_lazyload_replacements' ) ) :
/**
 * Replace default image markup with LazyLoad's requirements if LazyLoad is
 * enabled.
 *
 * @since 2.0
 *
 * @param  string $output Default image output.
 *
 * @return string
 */
function nice_lazyload_replacements( $output = '' ) {
	if ( ! stripos( $output, 'data-original=' ) ) {
		$output = str_replace( 'src="', 'data-original="', $output );
	}

	if ( ! stripos( $output, 'data-original-set=' ) ) {
		$output = str_replace( 'srcset="', 'data-original-set="', $output );
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_lazyload_setup' ) ) :
add_action( 'init', 'nice_lazyload_setup' );
/**
 * Setup LazyLoad where needed.
 *
 * @since 2.0
 */
function nice_lazyload_setup() {
	if ( nice_lazyload_images() ) {
		add_filter( 'nice_image_output', 'nice_lazyload_replacements' );
		add_filter( 'the_content', 'nice_lazyload_content_images' );

		if ( nice_bool_option( '_lazyload_images_logo' ) ) {
			add_filter( 'nice_logo_image_output', 'nice_lazyload_replacements' );
		}
	}
}
endif;

if ( ! function_exists( 'nice_lazyload_content_images' ) ) :
/**
 * Add LazyLoad attributes to images inside post contents.
 *
 * @since 2.0
 *
 * @param string $content
 *
 * @return string
 */
function nice_lazyload_content_images( $content = '' ) {
	preg_match_all( '/<img[^>]+>/i', $content, $images );

	if ( ! empty( $images[0] ) ) {
		foreach ( $images as $img ) {
			$modified_img = nice_lazyload_replacements( $img[0] );
			$content      = str_replace( $img[0], $modified_img, $content );
		}
	}

	return $content;
}
endif;
