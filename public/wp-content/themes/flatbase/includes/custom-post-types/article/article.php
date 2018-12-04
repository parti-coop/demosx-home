<?php
/**
 * Flatbase by NiceThemes.
 *
 * Register and manage "article" post type.
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

if ( ! function_exists( 'nice_article_register' ) ) :
add_action( 'init', 'nice_article_register' );
/**
 * Register "article" post type.
 *
 * @since 1.0.0
 *
 * @uses  register_post_type();
 */
function nice_article_register() {

	$labels = array(
		'name'               => __( 'Articles', 'nicethemes' ),
		'singular_name'      => __( 'Article', 'nicethemes' ),
		'add_new'            => __( 'Add New', 'nicethemes' ),
		'add_new_item'       => __( 'Add New Article', 'nicethemes' ),
		'edit_item'          => __( 'Edit Article', 'nicethemes' ),
		'new_item'           => __( 'New Article', 'nicethemes' ),
		'view_item'          => __( 'View Article', 'nicethemes' ),
		'search_items'       => __( 'Search Articles', 'nicethemes' ),
		'not_found'          => __( 'No Articles found', 'nicethemes' ),
		'not_found_in_trash' => __( 'No Articles found in Trash', 'nicethemes' ),
		'parent_item_colon'  => ''
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'article', 'with_front' => false ),
		'capability_type'    => 'page',
		'hierarchical'       => false,
		'menu_icon'          => nice_admin_menu_icon( 'btn-article.png' ),
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'comments', 'author', 'revisions' )
	);

	register_post_type( 'article', $args );

}

endif;

if ( ! function_exists( 'nice_article_taxonomies' ) ) :
add_action( 'init', 'nice_article_taxonomies', 0 );
/**
 * Register taxonomies for "article" post type.
 *
 * @since 1.0.0
 *
 * @uses  register_post_type();
 */
function nice_article_taxonomies() {

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

	register_taxonomy( 'article-category', array( 'article' ) ,
							array(
								'hierarchical' => true,
								'labels'       => $nice_category_labels,
								'show_ui'      => true,
								'query_var'    => true,
								'rewrite'      => array( 'slug' => 'article-category' ),
							)
					 );

	$nice_tag_labels = array(
		'name'              => __( 'Tags', 'nicethemes' ),
		'singular_name'     => __( 'Tag', 'nicethemes' ),
		'search_items'      => __( 'Tags', 'nicethemes' ),
		'all_items'         => __( 'All Tags', 'nicethemes' ),
		'parent_item'       => __( 'Parent Tag', 'nicethemes' ),
		'parent_item_colon' => __( 'Parent Tag:', 'nicethemes' ),
		'edit_item'         => __( 'Edit Tag', 'nicethemes' ),
		'update_item'       => __( 'Update Tag', 'nicethemes' ),
		'add_new_item'      => __( 'Add New Tag', 'nicethemes' ),
		'new_item_name'     => __( 'New Tag', 'nicethemes' )
	);

	register_taxonomy( 'article-tag', array( 'article', 'faq' ) ,
							array(
								'hierarchical' => false,
								'labels'       => $nice_tag_labels,
								'show_ui'      => true,
								'query_var'    => true,
								'rewrite'      => array( 'slug' => 'article-tag' ),
							)
					 );

	register_taxonomy( 'post_format', 'article',
							array(
								'public'       => true,
								'hierarchical' => false,
								'labels'       => array(
									'name'          => _x( 'Format', 'post format', 'nicethemes' ),
									'singular_name' => _x( 'Format', 'post format', 'nicethemes' ),
								),
								'query_var' => true,
								'show_ui'   => false,
								'_builtin'  => true,
								'show_in_nav_menus' => current_theme_supports( 'post-formats' ),
							)
					);


}
endif;

if ( ! function_exists( 'nice_article_columns' ) ) :
add_filter( 'manage_edit-article_columns', 'nice_article_columns' ) ;
/**
 * Add custom columns for the Dashboard list of Articles
 *
 * @since 1.0.7
 *
 */
function nice_article_columns( $columns ) {

	$columns = array(
		'cb'               => '<input type="checkbox" />',
		'title'            => __( 'Article', 'nicethemes' ),
		'article-category' => __( 'Article Category', 'nicethemes' ),
		'article-tag'      => __( 'Tags', 'nicethemes' ),
		'author'           => __( 'Author', 'nicethemes' ),
		'date'             => __( 'Date', 'nicethemes' )
	);

	return $columns;
}

endif;

if ( ! function_exists( 'nice_article_columns_custom' ) ) :
add_action( 'manage_article_posts_custom_column', 'nice_article_columns_custom', 10, 2 );
/**
 * Process each custom column (categories and tags)
 *
 * @since 1.0.7
 *
 */
function nice_article_columns_custom( $column, $post_id ) {

	global $post;

	switch( $column ) {

		/* If displaying the 'category' column. */
		case 'article-category' :

			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'article-category' );

			/* If terms were found. */
			if ( ! empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'article-category' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'article-category', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				echo '&mdash;';
			}

			break;

		/* If displaying the 'taf' column. */
		case 'article-tag' :

			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'article-tag' );

			/* If terms were found. */
			if ( ! empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'article-tag' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'article-tag', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				echo '&mdash;';
			}

			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

endif;


if ( ! function_exists( 'nice_article_columns_sortable' ) ) :
add_filter( 'manage_edit-article_sortable_columns', 'nice_article_columns_sortable' );
/**
 * Make sortable columns
 *
 * @since 1.0.7
 *
 */

function nice_article_columns_sortable( $columns ) {

	$columns['article-category'] = 'article-category';
	$columns['article-tag']      = 'article-tag';
	$columns['author']           = 'author';

	return $columns;
}
endif;
