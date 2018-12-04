<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the header different options.
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


if ( ! function_exists( 'nice_header_class' ) ) :
/**
 * Add classes to #header element.
 *
 * @see    nice_header_properties()
 *
 * @since  2.0
 *
 * @param  array $class List of classes for #header element.
 * @param  bool  $echo  Whether to print the output or not.
 *
 * @return string
 */
function nice_header_class( array $class = array(), $echo = true ) {
	$classes = array();

	if ( $header_background_color = nice_header_background_color() ) {
		$classes[] = nice_theme_color_background_class( $header_background_color );
	}

	$classes[] = 'header-' . nice_header_skin();
	$classes[] = 'submenu-' . nice_header_navigation_submenu_skin();

	$classes[] = 'nav-' . nice_header_nav_text_transform();
	$classes[] = 'subnav-' . nice_header_subnav_text_transform();

	if ( nice_bool( nice_get_option( 'nice_header_border' ) ) ) {
		$classes[] = 'has-border';
	}

	$classes[] = 'clearfix';

	if ( ! empty( $class ) ) {
		$classes = array_merge( $classes, $class );
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * @hook nice_header_class
	 *
	 * Modify header classes by hooking in here.
	 *
	 * @since 2.0
	 */
	$classes = apply_filters( 'nice_header_class', $classes, $class );

	$output = nice_css_classes( $classes, $echo );

	return $output;
}
endif;

if ( ! function_exists( 'nice_header_data' ) ) :
add_filter( 'nice_header_properties_args', 'nice_header_data' );
/**
 * Add data attributes to #header element.
 *
 * @see    nice_header_properties()
 *
 * @since  2.0
 *
 * @param  array $args List of properties and values for #header element.
 *
 * @param  bool  $echo Whether to print the output or not.
 *
 * @return string
 */
function nice_header_data( array $args = array(), $echo = true ) {
	$data = array();
	$atts = array();
	$output = '';

	if ( ! empty( $data ) ) {
		foreach ( $data as $key => $value ) {
			$atts[] = 'data-' . $key . '="' . esc_attr( $value ) . '"';
		}
	}

	if ( ! empty( $args ) ) {
		foreach ( $args as $key => $value ) {
			$atts[] = 'data-' . (string) $key . '="' . esc_attr( $value ) . '"';
		}
	}

	/**
	 * @hook nice_header_data
	 *
	 * Modify header data attributes by hooking in here.
	 *
	 * @since 2.0
	 */
	$atts = apply_filters( 'nice_header_data', $atts, $args );

	if ( ! empty( $atts ) ) {
		$output = join( ' ', $atts );
	}

	if ( $echo ) {
		echo $output; // WPCS: XSS ok.
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_logo_display' ) ) :
add_action( 'nice_logo', 'nice_logo_display', 10 );
/**
 * Display logo using framework functionality.
 *
 * @uses  nice_logo()
 *
 * @since 2.0
 */
function nice_logo_display() {
	?>
	<!-- BEGIN #logo -->
	<?php
		$class = ( is_rtl() ? 'fr' : 'fl' );

		nice_logo( array( 'before' => '<div id="logo" class="' . $class . '">',
						  'after'  => '</div>'
				 ) ); ?>
	<!-- END #logo -->
	<?php
}
endif;

if ( ! function_exists( 'nice_navigation_singular' ) ) :
add_filter( 'nice_navigation_menu_args', 'nice_navigation_singular', 5 );
/**
 * Return the menu id for post and pages.
 *
 * @see    nice_navigation_menu()
 *
 * @since  2.0
 *
 * @param  array $args Non-filtered arguments.
 * @return array       Filtered arguments.
 */
function nice_navigation_singular( $args ) {
	if ( is_page() || is_single() ) {
		$post_navigation_menu = get_post_meta( get_the_ID(), '_post_navigation_menu', true );
		if ( $post_navigation_menu ) {
			$args['menu'] = $post_navigation_menu;
		}
	}

	return $args;
}
endif;

if ( ! function_exists( 'nice_header_display' ) ) :
add_filter( 'wp', 'nice_header_display' );
/**
 * check if the header should be displayed in singular templates.
 *
 * @see    nice_header_display()
 *
 * @since  2.0
 *
 */
function nice_header_display() {
	if ( is_page() || is_single() ) {
		$_post_header = get_post_meta( get_the_ID(), '_post_header', true );

		if ( $_post_header && ( ! nice_bool( $_post_header ) ) ) {
			remove_action( 'nice_header', 'nice_header' );
		}
	}
}
endif;

if ( ! function_exists( 'nice_header_background_color' ) ) :
/**
 * Obtain background color for current page.
 *
 * @since  2.0
 *
 * @param  int $post_id
 *
 * @return mixed|string|null
 */
function nice_header_background_color( $post_id = null ) {
	$color = '';

	if ( is_page() || is_single() ) {
		$id    = $post_id ? $post_id : get_the_ID();
		$color = esc_url( get_post_meta( $id, '_post_header_background_color', true ) );
	}

	if ( empty( $color ) && null !== $color ) {
		$color = esc_url( nice_get_option( '_header_background_color' ) );
	}

	return $color;
}
endif;

if ( ! function_exists( 'nice_header_background_image' ) ) :
/**
 * Obtain background image for current page.
 *
 * @since  2.0
 *
 * @param  int $post_id
 *
 * @return mixed|string|null
 */
function nice_header_background_image( $post_id = null ) {
	$image = '';

	if ( is_page() || is_single() ) {
		$id    = $post_id ? $post_id : get_the_ID();
		$image = esc_url( get_post_meta( $id, '_post_header_background_image', true ) );

		if ( empty( $image ) && get_post_meta( $id, '_post_header_background_color', true ) ) {
			$image = null;
		}
	}

	if ( empty( $image ) && null !== $image ) {
		$image = esc_url( nice_get_option( '_header_background_image' ) );
	}

	return $image;
}
endif;

if ( ! function_exists( 'nice_header_background_image_repeat' ) ) :
/**
 * Obtain background-repeat property value for background header image.
 *
 * @since 2.0
 *
 * @param  int $post_id
 *
 * @return mixed|string|null
 */
function nice_header_background_image_repeat( $post_id = null ) {
	$id = $post_id ? $post_id : get_the_ID();

	if ( ( is_page() || is_single() ) ) {
		if ( $repeat = esc_attr( get_post_meta( $id, '_post_header_background_image_repeat', true ) ) ) {
			if ( ! empty( $repeat ) ) {
				return $repeat;
			}
		}
	}

	if ( esc_url( nice_get_option( '_header_background_image' ) ) ) {
		return nice_get_option( '_header_background_image_repeat' );
	}

	return null;
}
endif;

if ( ! function_exists( 'nice_header_background_image_position' ) ) :
/**
 * Obtain background-position property value for current page.
 *
 * @since  1.0.0
 *
 * @param  int $post_id
 *
 * @return string
 */
function nice_header_background_image_position( $post_id = null ) {
	$position  = '';

	if ( is_page() || is_single() ) {
		$id = $post_id ? $post_id : get_the_ID();

		if ( esc_url( get_post_meta( $id, '_post_header_background_image', true ) ) ) {
			$position = esc_attr( get_post_meta( $id, '_post_header_background_image_position', true ) );
		}
	}

	if ( empty( $position ) && esc_url( nice_get_option( '_header_background_image' ) ) ) {
		$position = nice_get_option( '_header_background_image_position' );
	}

	return $position;
}
endif;

if ( ! function_exists( 'nice_header_background_image_attachment' ) ) :
/**
 * Obtain background-attachment property value for current page.
 *
 * @since  2.0
 *
 * @param  int $post_id
 *
 * @return string
 */
function nice_header_background_image_attachment( $post_id = null ) {
	$attachment = '';

	if ( is_page() || is_single() ) {
		$id = $post_id ? $post_id : get_the_ID();

		if ( esc_url( get_post_meta( $id, '_post_header_background_image', true ) ) ) {
			$attachment = esc_attr( get_post_meta( $id, '_post_header_background_image_attachment', true ) );
		}
	}

	if ( empty( $attachment ) && esc_url( nice_get_option( '_header_background_image' ) ) ) {
		$attachment = nice_get_option( '_header_background_image_attachment' );
	}

	return $attachment;
}
endif;

if ( ! function_exists( 'nice_header_background_image_size' ) ) :
/**
 * Obtain background-size property value for current page.
 *
 * @since  2.0
 *
 * @param  int    $post_id
 *
 * @return string
 */
function nice_header_background_image_size( $post_id = null ) {
	$size      = '';

	if ( is_page() || is_single() ) {
		$id = $post_id ? $post_id : get_the_ID();

		if ( esc_url( get_post_meta( $id, '_post_header_background_image', true ) ) ) {
			$size = esc_attr( get_post_meta( $id, '_post_header_background_image_size', true ) );
		}
	}

	if ( empty( $size ) && esc_url( nice_get_option( '_header_background_image' ) ) ) {
		$size = nice_get_option( '_header_background_image_size' );
	}

	return $size;
}
endif;
