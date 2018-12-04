<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains the function to hook custom fields for infobox.
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

if ( ! function_exists( 'nice_custom_fields_infobox' ) ) :
add_filter( 'nice_custom_fields', 'nice_custom_fields_infobox', 10, 2 );
/**
 * Load array with custom fields for posts.
 * then save the array into WP options.
 *
 * @since 2.0
 *
 * @param $nice_fields
 * @param $post_type
 *
 * @return array
 */
function nice_custom_fields_infobox( array $nice_fields = array(), $post_type ) {
	if ( 'infobox' === $post_type ) {
		/**
		 * Header
		 */

		$nice_fields[] = array(
			'name'  => 'infobox-details',
			'label' => esc_html__( 'Infobox Details', 'nicethemes' ),
			'type'  => 'section',
			'icon'  => '<i class="bi_layout-header"></i>',
		);

		$nice_fields[] = array(
			'name'  => 'infobox-item-info',
			'label' => __( 'Info Box Image', 'nicethemes' ),
			'type'  => 'info',
			'desc'  => __( 'Info Boxes Items use the WordPress featured image as the feedback image. Don\'t know what featured images are? How to use them? <a href="http://en.support.wordpress.com/featured-images/#setting-a-featured-image">Take a look at WordPress docs on Featured Images</a>.', 'nicethemes' )
		);

		$nice_fields[] = array (
			'name'  => 'infobox_readmore',
			'std'   => '',
			'label' => __( '"Read more" URL', 'nicethemes' ),
			'type'  => 'text',
			'desc'  => __( 'Add an URL for your Read More button in your Info Box on homepage (optional)', 'nicethemes' )
		);

		$nice_fields[] = array (
			'name'  => 'infobox_readmore_text',
			'std'   => '',
			'label' => __( '"Read more" Text', 'nicethemes' ),
			'type'  => 'text',
			'desc'  => __( 'Add the anchor text for the "Read More" link.', 'nicethemes' )
		);

		$nice_fields[] = array (
			'name'  => 'infobox_readmore_window',
			'std'   => '',
			'label' => __( 'Open in a new window/tab', 'nicethemes' ),
			'type'  => 'checkbox',
			'desc'  => __( 'Tick this option if you want your link to be opened in a new window/tab (optional)', 'nicethemes' )
		);

	}

	return $nice_fields;
}
endif;
