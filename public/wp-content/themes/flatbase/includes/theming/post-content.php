<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to customize the post/page/article content section.
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

if ( ! function_exists( 'nice_post_thumbnail_display' ) ) :
add_filter( 'nice_post_thumbnail_display', 'nice_post_thumbnail_display' );
/**
 * Set if the post thumbnail should be displayed or hidden for the current post.
 *
 * @since  2.0
 *
 * @param  bool $display
 *
 * @return bool
 */
function nice_post_thumbnail_display( $display = false ) {
	$post    = get_post();
	$is_post = ( $post instanceof WP_Post ) ? is_singular( array( 'post' ) ) : false;

	if ( $is_post ) {
		$post_thumbnail_display = get_post_meta( get_the_ID(), '_post_thumbnail_content', true );

		if ( '' === $post_thumbnail_display ) {
			$post_thumbnail_display = true;
		}

		$display = nice_bool( $post_thumbnail_display );
	}

	return $display;
}
endif;

if ( ! function_exists( 'nice_post_author_display' ) ) :
add_filter( 'nice_post_author_display', 'nice_post_author_display' );
/**
 * Set if the post author should be displayed or hidden for the current post.
 *
 * @since  2.0
 *
 * @param  bool $display
 *
 * @return bool
 */
function nice_post_author_display( $display = false ) {
	$hide = get_post_meta( get_the_ID(), '_post_author_hide', true );
	$show = get_post_meta( get_the_ID(), '_post_author_display', true );

	if ( $display && $hide ) {
		$display = false;
	} elseif ( ! $display && $show ) {
		$display = true;
	}

	return $display;
}
endif;


if ( ! function_exists( 'nice_post_password_form' ) ) :
add_filter( 'the_password_form', 'nice_post_password_form' );
/**
 * Use custom markup for the password form in protected posts.
 *
 * @return string
 */
function nice_post_password_form() {
	$label = 'pwbox-' . get_the_ID();

	$output  = '<form class="post-password-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">';
	$output .= '<p>' . esc_html__( 'To view this protected post, enter the password below:', 'nicethemes' ) . '</p>';
	$output .= '<p><label for="' . esc_attr( $label ) . '">' . esc_html__( 'Password:', 'nicethemes' ) . '</label>';
	$output .= '<input name="post_password" id="' . esc_attr( $label ) . '" type="password" size="20" maxlength="20" />';
	$output .= '<input type="submit" name="Submit" value="' . esc_attr__( 'Submit', 'nicethemes' ) . '"/>';
	$output .= '</p></form>';

	return $output;
}
endif;