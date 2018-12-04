<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the front-end setup of the theme.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2016 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_theme_setup' ) ) :
add_action( 'after_setup_theme', 'nice_theme_setup' );
/**
 * Basic setup for this theme.
 *
 * @since 1.0.0
 */
function nice_theme_setup() {

	add_theme_support( 'post-thumbnails' );

	$hard_crop = get_option( 'nice_wp_resize_crop' );
	$hard_crop = nice_bool( $hard_crop );

	add_image_size( 'nice-template-blog',         480, 480, $hard_crop );
	add_image_size( 'nice-single-post',           730, 338, $hard_crop );
	add_image_size( 'nice-template-masonry-blog', 580, 405, $hard_crop );
	add_image_size( 'nice-template-search',       665, 285, $hard_crop );

	add_theme_support(
		'post-formats',
		array(
			'video'
			)
	);

	add_post_type_support( 'article', 'post-formats' );

	add_theme_support( 'title-tag' );

	// Add support for Customizer.
	add_theme_support( 'nice-customizer' );

	if ( is_singular() ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
endif;

/**
 * Make sure to use logo functionality from FW 2.0+ if available.
 *
 * @since 1.1.1
 */
add_filter( 'nice_logo_compatibility', '__return_false' );

if ( ! function_exists( 'nice_logo_filter' ) ) :
add_filter( 'nice_logo_default_args', 'nice_logo_filter' );
/**
 * Set the logo arguments for the function `nice_logo()`.
 *
 * @see    nice_logo()
 *
 * @since  1.0.0
 *
 * @param  array $args Non-filtered arguments.
 * @return array       Filtered arguments.
 */
function nice_logo_filter( $args ) {
	$nice_text_title = get_option( 'nice_texttitle' );

	if ( ! empty( $nice_text_title ) && nice_bool( $nice_text_title ) ) {
		$args['text_title'] = true;
	}

	/**
	 * Make sure to use logo functionality from FW 2.0+ if available.
	 *
	 * @since 1.1.1
	 */
	if ( version_compare( NICE_FRAMEWORK_VERSION, '2.0', '<' ) ) {
		$nice_logo = get_option( 'nice_logo' );

		if ( ! empty( $nice_logo ) ) {
			$args['logo'] = $nice_logo;
		}

		$nice_logo_retina = get_option( 'nice_logo_retina' );
		if ( ! empty( $nice_logo_retina ) ) {
			$args['logo'] = $nice_logo_retina;
		}
	} else {
		$args['default_image'] = nice_logo_default_image();
		$args['images'] = apply_filters( 'nice_logo_images', array() );
	}

	return $args;
}
endif;

if ( ! function_exists( 'nice_logo_default_image' ) ) :
/**
 * Set default image values for logo.
 *
 * @since 1.1.1
 *
 * @param array $args
 *
 * @return array
 */
function nice_logo_default_image( array $args = array() ) {
	return array(
		'name'       => 'default-logo',
		'id'         => 'default-logo',
		'id_retina'  => 'retina-logo',
		'url'        => nice_get_file_uri( 'images/logo.png' ),
		'url_retina' => nice_get_file_uri( 'images/logo@2x.png' ),
		'img_class'  => array( 'img-default-logo' ),
	);
}
endif;

if ( ! function_exists( 'nice_logo_images' ) ) :
add_filter( 'nice_logo_images', 'nice_logo_images' );
/**
 * Obtain logo images.
 *
 * @since 1.1.1
 *
 * @param array $images
 *
 * @return array
 */
function nice_logo_images( array $images = array() ) {
	$logo_url = nice_get_option( '_logo' );

	if ( $logo_url ) {
		$images[] = array(
			'name'       => 'custom-logo',
			'id'         => 'default-logo',
			'id_retina'  => 'retina-logo',
			'url'        => esc_url( $logo_url ),
			'url_retina' => esc_url( nice_get_option( '_logo_retina' ) ),
			'img_class'  => array( 'img-custom-logo' ),
			'height'     => nice_get_option( '_logo_height' ),
		);
	}

	return $images;
}
endif;

if ( ! function_exists( 'nice_logo_delete_transients' ) ) :
add_action( 'update_option_nice_options', 'nice_logo_delete_transients' );
/**
 * Remove image size transients when options are updated.
 *
 * @since 1.1.1
 */
function nice_logo_delete_transients() {
	if ( ! class_exists( 'Nice_Logo_Image' ) ) {
		nice_loader( 'engine/admin/classes/class-nice-logo-image.php' );
	}

	Nice_Logo_Image::delete_transients();
}
endif;

if ( ! function_exists( 'nicethemes_gallery_filter' ) ) :
add_filter( 'nicethemes_gallery_default_args', 'nicethemes_gallery_filter' );
/**
 * nicethemes_gallery_filter()
 *
 * Set the gallery arguments for the function nice_gallery
 *
 * @since 1.0.0
 *
 * @param  array  $args
 * @return string
 */

function nicethemes_gallery_filter( $args ) {

	$args['columns'] = 5;

	return $args;
}

endif;

if ( ! class_exists( 'Nice_Walker_Nav_Menu' ) ) :
// Add the nice bar above each parent item for the main menu.
class Nice_Walker_Nav_Menu extends Walker_Nav_Menu {

// add main/sub classes to li's and links
 function start_el( &$output, $item, $depth = 0, $args = array(), $current_category = 0 ) {
	global $wp_query;
	$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent

	// depth dependent classes
	$depth_classes = array(
		( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
		( $depth >=2 ? 'sub-sub-menu-item' : '' ),
		( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
		'menu-item-depth-' . $depth
	);
	$depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

	// passed classes
	$classes = empty( $item->classes ) ? array() : (array) $item->classes;
	$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

	// build html
	$output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

	if ( $depth < 1 )
		$args->link_after = '<mark class="bar"></mark>';
	else
		$args->link_after = '';

	// link attributes
	$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
	$attributes .= ! empty( $item->target )	 ? ' target="' . esc_attr( $item->target	 ) .'"' : '';
	$attributes .= ! empty( $item->xfn )		? ' rel="'	. esc_attr( $item->xfn		) .'"' : '';
	$attributes .= ! empty( $item->url )		? ' href="'   . esc_attr( $item->url		) .'"' : '';
	$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';

	$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
		$args->before,
		$attributes,
		$args->link_before,
		apply_filters( 'the_title', $item->title, $item->ID ),
		$args->link_after,
		$args->after
	);

	// build html
	$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
}
}

endif;

if ( ! function_exists( 'nice_livesearch_label' ) ) :
add_action( 'nice_livesearch_label', 'nice_livesearch_label' );
/**
 * Set the live search label via option
 *
 * @since 2.0
 */
function nice_livesearch_label( $label ) {

	$label = nice_get_option( '_welcome_message_placeholder' );

	return $label;
}
endif;