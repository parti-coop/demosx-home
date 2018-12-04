<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the different shortcodes.
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

if ( ! function_exists( 'nicethemes_knowledgebase_shortcode' ) ) :
/**
 * nicethemes_knowledgebase_shortcode()
 *
 * The Knowledge Base shortcode
 *
 * @since 1.0.0
 *
 */
function nicethemes_knowledgebase_shortcode( $atts ) {

	extract(
		shortcode_atts(
			array(
				'columns'     => '3',
				'category'    => '0',
				'exclude'     => '',
				'include'     => '',
				'numberposts' => ''
			), $atts
		)
	);

	$html = nicethemes_knowledgebase(
		array(
			'echo'        => false,
			'columns'     => $columns,
			'category'    => $category,
			'exclude'     => $exclude,
			'include'     => $include,
			'numberposts' => $numberposts
		)
	);

	$html = apply_filters( 'nicethemes_knowledgebase_shortcode', $html );

	return $html;
}
add_shortcode( 'nicethemes_knowledgebase', 'nicethemes_knowledgebase_shortcode' );
endif;

if ( ! function_exists( 'nicethemes_infoboxes_shortcode' ) ) :
add_shortcode( 'nicethemes_infoboxes', 'nicethemes_infoboxes_shortcode' );
/**
 * nicethemes_infoboxes_shortcode()
 *
 * The infoboxes shortcode
 *
 * @since 1.0.0
 *
 */
function nicethemes_infoboxes_shortcode( $atts ) {

	extract(
		shortcode_atts(
			array(
				'columns'     => '3',
				'rows'        => false,
				'numberposts' => '3'
			), $atts
		)
	);

	$html = nicethemes_infoboxes(
		array(
			'echo'        => false,
			'columns'     => $columns,
			'rows'        => $rows,
			'numberposts' => $numberposts
		)
	);

	$html = apply_filters( 'nicethemes_infoboxes_shortcode', $html );

	return $html;
}

endif;

if ( ! function_exists( 'nicethemes_gallery_shortcode' ) ) :
/**
 * nicethemes_gallery_shortcode()
 *
 * desc
 *
 * @since 1.0.0
 *
 */
function nicethemes_gallery_shortcode( $atts ) {

	extract(
		shortcode_atts(
			array(
				'columns' => '4',
				'rows'    => false,
				'ids'     => null
			), $atts
		)
	);

	$html =
		nicethemes_gallery(
			array(
				'echo'    => false,
				'columns' => $columns,
				'rows'    => $rows,
				'ids'     => $ids
			)
		);

	$html = apply_filters( 'nicethemes_gallery_shortcode', $html );

	return $html;
}
add_shortcode( 'nicethemes_gallery', 'nicethemes_gallery_shortcode' );
endif;

if ( ! function_exists( 'nicethemes_faq_shortcode' ) ) :
/**
 * nicethemes_faq_shortcode()
 *
 * FAQ Shortcode
 *
 * @since 2.0.2
 *
 */
function nicethemes_faq_shortcode( $atts ) {

	extract(
		shortcode_atts(
			array(
				'numberposts'  => '',
				'type'         => '',
				'orderby'      => 'menu_order',
				'order'        => '',
				'category'     => '0'
			), $atts
		)
	);

	$html = nice_faq(
		array(
			'echo'        => false,
			'type'        => $type,
			'category'    => $category,
			'numberposts' => $numberposts,
			'orderby'     => $orderby,
			'order'       => $order
		)
	);

	$html = apply_filters( 'nicethemes_faq_shortcode', $html );

	return $html;
}
add_shortcode( 'nicethemes_faq', 'nicethemes_faq_shortcode' );
endif;
