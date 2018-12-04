<?php
/**
 * Flatbase by NiceThemes.
 *
 * Main hooks for home page functionality.
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

if ( ! function_exists( 'nice_homepage_do' ) ) :
add_action( 'homepage', 'nice_homepage_do' );
/**
 * Print homepage contents.
 *
 * @since 1.0.0
 */
function nice_homepage_do() {
	$homepage_elements = nice_homepage_get_elements();
	$replacements      = nice_homepage_replacements();

	$output = '';

	foreach ( $homepage_elements as $key ) {
		if ( isset( $replacements[ $key ] ) ) {
			ob_start();

			/**
			 * Only try to execute callback function if it exists.
			 */
			if ( function_exists( $replacements[ $key ][0] ) ) {
				// Update position of current block.
				nice_homepage_update_position();

				// Process content.
				$content = call_user_func( $replacements[ $key ][0], $replacements[ $key ][1] );

				// Save string for output.
				$output .= $content ? $content : ob_get_contents();

				if ( ! $output || ( isset( $previous_content ) && $output === $previous_content ) ) {
					nice_homepage_rewind_position();
				}

				$previous_content = $output;
			}

			ob_end_clean();
		}
	}

	echo $output;
}
endif;

if ( ! function_exists( 'nice_homepage_get_elements' ) ) :
/**
 * Obtain the list of elements to show in the homepage.
 *
 * @since 2.0
 */
function nice_homepage_get_elements() {
	$elements = array();
	$homepage_elements = nice_get_option( 'nice_homepage_elements' );
	$homepage_template = apply_filters( 'nice_homepage_template', $homepage_elements );

	if ( $homepage_template ) {
		$elements_str = str_replace( ' ', '', $homepage_template );
		$elements_str = str_replace( '][', ',', $elements_str );
		$elements_str = str_replace( '[', '', $elements_str );
		$elements_str = str_replace( ']', '', $elements_str );

		$elements = explode( ',', $elements_str );
	}

	return $elements;
}
endif;

if ( ! function_exists( 'nice_homepage_get_container_class' ) ) :
add_filter( 'nice_container_class', 'nice_homepage_get_container_class' );
/**
 * Override class for main container element in the homepage template.
 *
 * @since  2.0
 *
 * @param  string $class Current HTML class.
 *
 * @return string
 */
function nice_homepage_get_container_class( $class = '' ) {
	if ( nice_is_homepage() ) {
		$class[] = 'homepage-container';
	}

	return $class;
}
endif;


if ( ! function_exists( 'nice_homepage_styles' ) ) :
add_action( 'nice_register_styles', 'nice_homepage_styles' );
/**
 * Add inline styles for home page.
 *
 * @since 2.0
 */
function nice_homepage_styles() {
	if ( $styles = apply_filters( 'nice_homepage_styles', '' ) ) {
		wp_add_inline_style( 'nice-styles', str_replace( '}', "}\n", preg_replace('/\s+/', ' ', $styles ) ) );
	}
}
endif;
