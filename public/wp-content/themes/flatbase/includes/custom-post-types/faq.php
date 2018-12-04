<?php
/**
 * Flatbase by NiceThemes.
 *
 * Register and manage "faq" post type.
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

if ( ! function_exists( 'nice_faq_register' ) ) :
add_action( 'init', 'nice_faq_register' );
/**
 * Register "faq" post type.
 *
 * @since 1.0.0
 *
 * @uses  register_post_type();
 */
function nice_faq_register() {

	$labels = array(
		'name'               => __( 'FAQs', 'nicethemes' ),
		'singular_name'      => __( 'FAQ', 'nicethemes' ),
		'add_new'            => __( 'Add New', 'nicethemes' ),
		'add_new_item'       => __( 'Add New FAQ', 'nicethemes' ),
		'edit_item'          => __( 'Edit FAQ', 'nicethemes' ),
		'new_item'           => __( 'New FAQ', 'nicethemes' ),
		'view_item'          => __( 'View FAQ', 'nicethemes' ),
		'search_items'       => __( 'Search FAQs', 'nicethemes' ),
		'not_found'          => __( 'No FAQs found', 'nicethemes' ),
		'not_found_in_trash' => __( 'No FAQs found in Trash', 'nicethemes' ),
		'parent_item_colon'  => ''
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'faq' ),
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_icon'          => nice_admin_menu_icon( 'btn-faq.png' ),
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'page-attributes' )
	);

	register_post_type( 'faq', $args );

}

endif;

if ( ! function_exists( 'nice_faq_taxonomies' ) ) :
add_action( 'init', 'nice_faq_taxonomies', 0 );
/**
 * Register taxonomies for "faq" post type.
 *
 * @since 2.0.1
 *
 * @uses  register_post_type();
 */
function nice_faq_taxonomies() {

	$nice_category_labels = array(
		'name'              => __( 'Categories', 'nicethemes' ),
		'singular_name'     => __( 'Category', 'nicethemes' ),
		'search_items'      => __( 'Categories', 'nicethemes' ),
		'all_items'         => __( 'All Categories', 'nicethemes' ),
		'parent_item'       => __( 'Parent Category', 'nicethemes' ),
		'parent_item_colon' => __( 'Parent Category:', 'nicethemes' ),
		'edit_item'         => __( 'Edit Category', 'nicethemes' ),
		'update_item'       => __( 'Update Category', 'nicethemes' ),
		'add_new_item'      => __( 'Add New Category', 'nicethemes' ),
		'new_item_name'     => __( 'New Category', 'nicethemes' )
	);

	register_taxonomy( 'faq-category', array( 'faq' ) ,
						array(
							'hierarchical' => true,
							'labels'       => $nice_category_labels,
							'show_ui'      => true,
							'query_var'    => true,
							'rewrite'      => array( 'slug' => 'faq-category' ),
						)
				 );

}
endif;

if ( ! function_exists( 'nice_faq_editor_title' ) ) :
add_filter( 'enter_title_here', 'nice_faq_editor_title' );
/**
 * Change the default string for the Title input.
 *
 * @since  1.0.0
 *
 * @param  string $title Current title.
 *
 * @return string
 */
function nice_faq_editor_title( $title ) {

	if ( $custom = apply_filters( 'nice_faq_editor_title', '' ) ) {
		return $custom;
	}

	$screen = get_current_screen();

	$title = ( 'faq' == $screen->post_type ) ? sprintf( __( 'Enter the FAQ question', 'nicethemes' ), $screen->post_type ) : $title;

	return $title;
}
endif;
