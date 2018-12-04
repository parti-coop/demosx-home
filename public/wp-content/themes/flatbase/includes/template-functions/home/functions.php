<?php
/**
 * Flatbase by NiceThemes.
 *
 * Utilitary functions for home page template.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      https://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_is_homepage' ) ) :
/**
 * Check if the current page has the Home page template.
 *
 * @since  2.0
 *
 * @return bool
 */
function nice_is_homepage() {
	return apply_filters( 'nice_is_homepage', is_page_template( 'template-home.php' ) );
}
endif;

if ( ! function_exists( 'nice_homepage_replacements' ) ) :
/**
 * List of elements to be replaced within the homepage content template,
 * including their callbacks and parameters.
 *
 * @since  2.0
 *
 * @return array
 */
function nice_homepage_replacements() {
	/**
	 * @hook nice_homepage_replacements
	 *
	 * Hook here to change the home page replacements.
	 */

	$args = array(
				'content'              => array( 'nice_homepage_content', false ),
				'infoboxes'            => array( 'nice_homepage_infoboxes', false ),
				'knowledgebase'        => array( 'nice_homepage_knowledgebase', false ),
				'knowledgebase_videos' => array( 'nice_homepage_knowledgebase_videos', false ),
			);

	return apply_filters( 'nice_homepage_replacements', $args );
}
endif;

if ( ! function_exists( 'nice_homepage_block_styles' ) ) :
/**
 * Add inline styles for infoboxes section.
 *
 * @since  2.0
 *
 * @param  string $name Name of the homepage block section.
 *
 * @return string
 */
function nice_homepage_block_styles( $name ) {
	// Allow bypass.
	if ( $styles = apply_filters( 'nice_homepage_block_styles', '', $name ) ) {
		return $styles;
	}

	$background_image_key = 'nice_homepage_' . $name . '_background_image';
	if ( $bg_image = nice_get_option( $background_image_key ) ) {
		$styles .= '#homepage-' . $name . ' {
			background-image: url(' . $bg_image . ');
		}';
	}

	$background_color_key = 'nice_homepage_' . $name . '_background_color';
	if ( ( $background_color = nice_get_option( $background_color_key ) ) && ( 1 > ( $background_color_opacity = floatval( nice_get_option( $background_color_key . '_opacity' ) ) ) ) ) {
		$styles .= '#homepage-' . $name . ' .inner > .overlay {
			background-color: ' . nice_color_hex2rgba( nice_get_option( $background_color ), $background_color_opacity ) . ';
		}';
	}

	return $styles;
}
endif;

if ( ! function_exists( 'nice_homepage_block_class' ) ) :
/**
 * Obtain the full HTML class for a homepage block section.
 *
 * @since   2.0
 *
 * @param  string $name Name of the home section.
 * @param  bool   $echo Whether to print the result or just return it.
 *
 * @return string
 */
function nice_homepage_block_class( $name, $echo = true ) {
	// Allow bypass.
	if ( $class = apply_filters( 'nice_homepage_block_class', '' ) ) {
		return $class;
	}

	$class = 'home-block position-' . nice_get_homepage_position();

	if ( $background_color = nice_get_option( 'nice_homepage_' . $name . '_background_color' ) ) {
		$class .= ' custom-background';

		if ( 1 === intval( nice_get_option( 'nice_homepage_' . $name . '_background_color_opacity' ) ) ) {
			$class .= ' ' . nice_theme_color_background_class( $background_color );
		}
	}

	if ( nice_get_option( 'nice_homepage_' . $name . '_background_image' ) ) {
		$class .= ' has-background-image';
	}

	if ( $skin = nice_get_option( 'nice_homepage_' . $name . '_text_skin' ) ) {
		$class .= ' '. $skin;
	}

	if ( $echo ) {
		echo $class;
	}

	return $class;
}
endif;

if ( ! function_exists( 'nice_get_homepage_position' ) ) :
/**
 * Obtain the position of the current homepage block.
 *
 * @since  1.0.0
 *
 * @param  string $update Indicate if the current position should be updated.
 *
 * @return int
 */
function nice_get_homepage_position( $update = null ) {
	static $position = 0;

	if ( 'increase' === $update ) {
		$position++;
	} elseif ( 'decrease' === $update ) {
		$position--;
	}

	return $position;
}
endif;

if ( ! function_exists( 'nice_homepage_position' ) ) :
/**
 * Print the position of the current homepage block.
 *
 * @since 2.0
 */
function nice_homepage_position() {
	echo nice_get_homepage_position();
}
endif;

if ( ! function_exists( 'nice_homepage_update_position' ) ) :
/**
 * Update the position of the current homepage block.
 *
 * @since 2.0
 */
function nice_homepage_update_position() {
	nice_get_homepage_position( 'increase' );
}
endif;

if ( ! function_exists( 'nice_homepage_rewind_position' ) ) :
/**
 * Rewind the position of the current homepage block.
 *
 * @since 2.0
 */
function nice_homepage_rewind_position() {
	nice_get_homepage_position( 'decrease' );
}
endif;

if ( ! function_exists( 'nice_homepage_pre_footer_widgets' ) ) :
add_action( 'homepage', 'nice_homepage_pre_footer_widgets', 500 );
/**
 * Add the pre-footer widgets section in the home template.
 *
 * @since 2.0
 */
function nice_homepage_pre_footer_widgets() {

	get_template_part( 'template-parts/home/pre-footer-widgets' );

}
endif;
