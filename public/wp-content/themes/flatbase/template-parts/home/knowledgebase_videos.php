<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for the homepage content.
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

$nice_video_enable = get_option( 'nice_video_enable' );
$nice_video_order  = get_option( 'nice_video_order' );

if ( $nice_video_order === '' ) {
	$nice_video_order = 'date';
}

if ( $nice_video_enable === 'true' ) :

	if ( isset( $nice_options['nice_video_entries'] ) ) {
		$number_videos = $nice_options['nice_video_entries'];
	} else {
		$number_videos = 5;
	}

	$classes   = array();
	$classes[] = 'videos home-block clearfix';
	$classes[] =  nice_get_option( '_homepage_video_skin', true );

	if ( $nice_homepage_video_background_color = nice_get_option( '_homepage_video_background_color', true ) ) {
		$classes[] = nice_theme_color_background_class( $nice_homepage_video_background_color );
	}

	nice_home_videos( array(
						'numberposts' => $number_videos,
						'orderby'     => $nice_video_order,
						'before'      => '<section id="home-videos" ' . nice_css_classes( $classes, false ) . '><div class="col-full">',
						'after'       => '</div></section>'
						)
					);

endif;
