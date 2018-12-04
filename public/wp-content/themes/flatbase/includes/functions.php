<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains generic functions for this theme.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase/
 * @copyright 2016 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_pageviews_count' ) ) :
/**
 * nice_pageviews_count()
 *
 * Handles the pageview count
 * returns the number of pageviews
 *
 * @since 1.0.0
 *
 */

function nice_pageviews_count() {

	$post_ID = get_the_ID();

	$count_key = '_pageview_count';
	$count = get_post_meta( $post_ID, $count_key, true );
	if ( $count == '' ) return 0;

	return $count;
}

endif;


if ( ! function_exists( 'nice_pageviews' ) ) :
/**
 * nice_pageviews()
 *
 * Handles the pageview count
 * returns the number of pageviews
 *
 * @since 1.0.0
 *
 */

function nice_pageviews() {

	$count = nice_pageviews_count();
	$count++;
	update_post_meta( get_the_ID(), '_pageview_count', $count );

	return $count;
}

endif;


if ( ! function_exists( 'nicethemes_likes_count' ) ) :
/**
 * nicethemes_likes_count()
 *
 * Returns the number of likes for a certain post/page/cpt
 *
 * @since 1.0.0
 *
 */
function nicethemes_likes_count( $id = 0 ) {

	if ( ! $id ) $id = get_the_ID();
	$count_key = '_like_count';
	$likes = get_post_meta( $id, $count_key, true );
	if ( $likes == '' ) return 0;

	return $likes;
}

endif;


if ( ! function_exists( 'nicethemes_likes_can' ) ) :
/**
 * nicethemes_likes_can()
 *
 * Returns a boolean determining if the current IP
 * already liked the content or not
 *
 * @since 1.0.0
 *
 */
function nicethemes_likes_can( $id = 0 ) {

	if ( ! $id ) return false;

	$ip_list = get_post_meta( $id, '_like_ip', true );

	if ( ( $ip_list == '' ) || ( is_array( $ip_list ) && ! in_array( nice_user_ip(), $ip_list ) ) ){
		return true;
	}

	return false;

}

endif;


if ( ! function_exists( 'nicethemes_reading_time' ) ) :
/**
 * nicethemes_reading_time()
 *
 * Echoes the estimated time to read by the amount of
 * text of the post/page/cpt
 *
 * @since 1.0.0
 *
 */

function nicethemes_reading_time( $args = array() ) {

	$defaults = apply_filters( 'nicethemes_reading_time_default_args', array(
							'words_per_minute' => 300,
							'display_seconds'  => true,
							'echo'             => true,
							'before'           => '',
							'after'            => '')
			);

	$args = wp_parse_args( $args, $defaults );

	$args = apply_filters( 'nicethemes_reading_time_args', $args );

	do_action( 'nicethemes_reading_time_before', $args );

	$output = '';

	$output .= $args['before'];

	$content = get_the_content();
	$num_words = str_word_count( strip_tags( $content ) );

	$minutes = floor( $num_words / $args['words_per_minute'] );
	$seconds = floor( $num_words % $args['words_per_minute'] / ( $args['words_per_minute'] / 60 ) );
	$estimated_time = '';
	if ( ! $args['display_seconds'] ) {
		if( $seconds >= 30 ) {
			$minutes = $minutes + 1;
		}
		$estimated_time = $estimated_time . ' '. sprintf( _n( '1 min read', '%s min read', $minutes, 'nicethemes' ), $minutes );
	} else {
		$estimated_time = $estimated_time . ' '. sprintf( _n( '1 min ', '%s min ', $minutes, 'nicethemes' ), $minutes ) . ', ' . sprintf( _n( '1 sec read', '%s sec read', $seconds, 'nicethemes' ), $seconds );
	}

	if ( $minutes < 1 ) {
		$estimated_time = __( 'Less than a minute', 'nicethemes' );
	}

	$output .= $estimated_time;

	$output .= $args['after'];

	// Allow child themes/plugins to filter here.
	$output = apply_filters( 'nicethemes_reading_time_html', $output, $args );

	if ( $args['echo'] == true ) {
		echo $output;
	} else {
		return $output;
	}

	do_action( 'nicethemes_reading_time_after', $args );

}

endif;


if ( ! function_exists( 'nice_opengraph_for_posts' ) ) :
add_action( 'wp_head', 'nice_opengraph_for_posts' );
/**
 * nice_opengraph_for_posts()
 *
 * Print the Facebook opengraph tags.
 *
 * @since 1.0.0
 *
 */

function nice_opengraph_for_posts() {

	if ( is_singular() && apply_filters( 'nice_opengraph_enable', true ) ) {
		global $post;
		setup_postdata( $post );
		$output  = '<meta property="og:type" content="article" />' . "\n";
		$output .= '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
		$output .= '<meta property="og:url" content="' . get_permalink() . '" />' . "\n";
		$output .= '<meta property="og:description" content="' . esc_attr( get_the_excerpt() ) . '" />' . "\n";
		if ( has_post_thumbnail() ) {
			$imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
			$output .= '<meta property="og:image" content="' . $imgsrc[0] . '" />' . "\n";
		}
		echo $output;
	}

}

endif;


if ( ! function_exists( 'nicethemes_knowledgebase' ) ) :
/**
 * nicethemes_knowledgebase()
 *
 * Create a list of articles, by category, within a grid.
 *
 * @since 1.0.0
 *
 */
function nicethemes_knowledgebase( $args = array() ) {

	global $post;

	$defaults = apply_filters( 'nicethemes_knowledgebase_default_args', array(
						'columns'       => 2,
						'numberposts'   => 5,
						'orderby'       => 'menu_order',
						'order'         => 'ASC',
						'echo'          => true,
						'title'         => '',
						'before'        => '',
						'after'         => '',
						'before_title'  => '<h3>',
						'after_title'   => '</h3>',
						'category'      => 0,
						'hide_empty'    => true,
						'exclude'       => '',
						'include'       => '',
						'icon_article'  => '<i class="fa fa-file-o"></i>',
						'icon_video'    => '<i class="fa fa-youtube-play"></i>')
						);

	$args = wp_parse_args( $args, $defaults );

	$cat_args = array(
					'taxonomy'      => 'article-category',
					'orderby'       => 'menu_order',
					'order'         => 'ASC',
					'hierarchical'  => true,
					'parent'        => $args['category'],
					'hide_empty'    => $args['hide_empty'],
					'child_of'      => $args['category'],
					'exclude'       => $args['exclude'],
					'include'       => $args['include']
				);

	$categories = get_categories( $cat_args );
	$loop = 0;

	$output = '';

	$output .= $args['before'];

	if ( $categories ) :
	$output .= '<div class="nice-knowledgebase grid clearfix">';

	// foreach categories
	foreach ( $categories as $category ) :

		$loop++;

		$class = '';

		// open the row &  set the column class if it's the first or the last one :)
		if ( ( $loop - 1 ) % $args['columns'] == 0 ) {
			$class = 'first';
			$output .= '<div class="row clearfix">';
		}
		elseif ( $loop % $args['columns'] == 0 ) {
			$class = 'last';
		}

		$output .= '<div class="columns-' . $args['columns'] . ' '. $class .'">';

		$output .= '<header>';
		$output .= $args['before_title'];
		if ( apply_filters( 'nicethemes_knowledgebase_enable_category_link', true ) ) {
			$output .= '<a href="' .  get_term_link( intval( $category->term_id ), 'article-category' ) . '" title="' . sprintf( esc_attr__( 'View all articles in %s', 'nicethemes' ), $category->name ) . '" ' . '>';
		}
		$output .= $category->name;
		if ( apply_filters( 'nicethemes_knowledgebase_enable_category_link', true ) ) {
			$output .= '</a>';
		}
		$output .= '<span class="cat-count">(' . $category->count . ')</span>';
		$output .= $args['after_title'];
		$output .= '</header>' . "\n\n";

		if ( apply_filters( 'nicethemes_knowledgebase_display_category_description', false ) ) {
			$output .= wpautop( $category->description );
		}

		// Sub category
		if ( apply_filters( 'nicethemes_knowledgebase_display_subcategory', true ) ) {
			$sub_category = get_category( $category );

			$subcat_args = array(
									'orderby'  => 'menu_order',
									'order'    => 'ASC',
									'taxonomy' => 'article-category',
									'child_of' => $sub_category->cat_ID,
									'parent'   => $sub_category->cat_ID
			);

			$sub_categories = get_categories( $subcat_args );

			foreach ( $sub_categories as $sub_category ) {

				$sub_category_link = get_term_link( $category, 'article-category' );

				$output .= '<ul class="sub-categories">' . "\n";
				$output .= '<li>' . "\n";
				$output .= '<header>' . "\n";
				$output .= '<h4><a href="' . esc_url( $sub_category_link ) . '" title="' . sprintf( esc_attr__( 'View all articles in %s', 'nicethemes' ), $sub_category->name ) . '" >' . esc_html( $sub_category->name ) . '</a></h4></header>' . "\n\n";
				$output .= '</li>' . "\n";
				$output .= '</ul>' . "\n\n";
			}
		}

		$cat_post_num = $args['numberposts'];

		$sub_category_num = count( $sub_categories );

		if ( $sub_category_num != 0 ) {
			$cat_post_num_smart = $cat_post_num - $sub_category_num;
		} else {
			$cat_post_num_smart = $cat_post_num;
		}


		$cat_post_args = array(
								'numberposts'  => $cat_post_num_smart,
								'post_type'    => 'article',
								'orderby'      => $args['orderby'],
								'order'        => $args['order']
								);

		$cat_post_args['tax_query'] = array(
										array(
												'taxonomy'  => 'article-category',
												'field'     => 'id',
												'operator'  => 'IN',
												'terms'     => $category->term_id
												)
											);

		$cat_posts = get_posts( $cat_post_args );

		$output .= '<ul class="category-posts">';

		foreach ( $cat_posts as $post ) : setup_postdata( $post );

			$format = get_post_format();
			if ( $format === false ) { $article_icon = $args["icon_article"]; }
			elseif( $format == 'video') { $article_icon = $args["icon_video"]; }

			$output .= '<li>' . $article_icon . ' <a href="' . get_permalink() . '" title="' . sprintf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) .'">' . get_the_title() . '</a></li>';

		endforeach;

		$output .= '</ul>';
		$output .= '</div>'; // close column

		// close the row div
		if ( ( $loop  % $args['columns'] == 0 ) && ( $loop != 1 ) ) $output .= '</div>';

	endforeach; // end foreach

	if ( ( $loop  % $args['columns'] != 0 ) ) $output .= '</div>';

	$output .= '</div>';

	endif;

	$output .= $args['after'];

	wp_reset_postdata();

	$output = apply_filters( 'nicethemes_knowledgebase_html', $output, $args );

	if ( $args['echo'] == true ) echo $output;
	else return $output;

	do_action( 'nicethemes_knowledgebase_after', $args );

}

endif;


if ( ! function_exists( 'nice_attachments_from_gallery' ) ) :
/**
 * nice_attachments_from_gallery()
 *
 * Returns ids of attachments from gallery
 *
 * @since 1.0.0
 *
*/
function nice_attachments_from_gallery() {
	global $post;

	$pattern = get_shortcode_regex();
	$ids     = array();

	// Find the "gallery" shortcode and puts the image ids in an associative array at $matches[3].
	if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches ) ) {
		$count = count( $matches[3] ); // In case there is more than one gallery in the post.

		for ( $i = 0; $i < $count; $i ++ ) {
			$atts = shortcode_parse_atts( $matches[3][ $i ] );

			if ( isset( $atts['ids'] ) ) {
				$attachment_ids = explode( ',', $atts['ids'] );
				$ids = array_merge( $ids, $attachment_ids );
			}
		}
	}

	if ( ! empty( $ids ) ) {
		$ids = array_flip( $ids );
	}

	/**
	 * @hook nice_attachments_from_gallery
	 *
	 * Hook in here to modify the list of attachment IDs for the current post.
	 *
	 * @since 1.0.0
	 */
	$ids = apply_filters( 'nice_attachments_from_gallery', $ids );

	return $ids;
}
endif;



if ( ! function_exists( 'nice_content_without_gallery' ) ) :
add_filter( 'the_content', 'nice_content_without_gallery' );
/**
 * nice_content_without_gallery()
 *
 * Removes gallery shortcodes from content and returns the content
 *
 * @since 1.0.0
 *
 */
function nice_content_without_gallery( $content ) {
	global $post;

	if ( is_page_template( 'template-gallery.php' ) || has_post_format( 'gallery', get_the_ID() ) )
		$content = preg_replace( '/\[gallery[^\]]*\]/', '',  $content );

	return $content;
}

endif;


if ( ! function_exists( 'add_query_vars_filter' ) ) :
/**
 * add_query_vars_filter()
 *
 * Add query vars for the livesearch functionality.
 * This way, pages and posts won't be included in the search results.
 *
 * @since 1.0.0
 *
 */
function add_query_vars_filter( $vars ) {

	global $wp_query;

	$vars[] = 'ajax';
	$vars[] = 'livesearch';

	return $vars;

}

endif;

add_filter( 'query_vars', 'add_query_vars_filter' );


if ( ! function_exists( 'nice_clean_live_search' ) ) :
/**
 * nice_clean_live_search()
 *
 * Exclude pages and posts from the livesearch functionality
 *
 * @since 1.0.0
 *
 */
function nice_clean_live_search( $query ) {

	if ( ! is_admin() && $query->is_main_query() ) {
		if ( $query->is_search ) {
			if ( get_query_var('ajax') == true ) {
				$post_type = apply_filters( 'nice_live_search_post_type', array( 'article', 'faq' ) );
				$query->set( 'post_type', $post_type );
			}
		}
	}
}

endif;

add_action( 'pre_get_posts', 'nice_clean_live_search' );


if ( ! function_exists( 'nicethemes_infoboxes' ) ) :
/**
 * nicethemes_infoboxes()
 *
 * Create a list of articles, by category, within a grid.
 *
 * @since 1.0.0
 *
 */
function nicethemes_infoboxes( $args = array() ) {

	$defaults = apply_filters( 'nicethemes_infoboxes_default_args', array(
						'columns'       => 3,
						'rows'          => false,
						'numberposts'   => 3,
						'orderby'       => 'menu_order',
						'echo'          => true,
						'order'         => 'ASC',
						'height'        => 270,
						'width'         => 480,
						'before'        => '',
						'after'         => '',
						'before_title'  => '',
						'after_title'   => '' )
		);


	$args = wp_parse_args( $args, $defaults );

	$args = apply_filters( 'nicethemes_infoboxes_args', $args );

	do_action( 'nicethemes_infoboxes_before', $args );

	$output = '';

	$query = new WP_Query( array(
				'post_type'      => 'infobox',
				'orderby'        => $args['orderby'],
				'posts_per_page' => $args['numberposts'],
				'order'          => $args['order']
			));

	if ( $query->have_posts() ) :

		$output .= $args['before'] . "\n";

		$tpl = '<div class="%%CLASS%%"><div class="thumb">%%IMAGE%%</div><div class="infobox-title">%%TITLE%%</div><div class="infobox-content">%%CONTENT%%</div>%%READMORE%%</div>';
		$tpl = apply_filters( 'nicethemes_infoboxes_item_template', $tpl, $args );

		$loop = 0;

		$output .= '<div class="nice-infoboxes grid">' . "\n";

		while ( $query->have_posts() ) : $query->the_post();

			$loop++;

			$template = $tpl;

			// get the custom fields
			$infobox_readmore = get_post_meta ( get_the_ID(), 'infobox_readmore', true );
			$infobox_readmore_anchor = get_post_meta ( get_the_ID(), 'infobox_readmore_text', true );
			$infobox_readmore_window = get_post_meta ( get_the_ID(), 'infobox_readmore_window', true );

			$infobox_url_target = '';
			if ( $infobox_readmore_window == true ) $infobox_url_target = 'target="_blank"';

			$class = 'item post-' . get_the_ID() . ' columns-' . esc_attr( intval( $args['columns'] ) );
			if ( $loop % $args['columns'] == 0 ) $class .= ' last';
			if ( ( $loop - 1 ) % $args['columns'] == 0 ) $class .= ' first';

			$template = str_replace( '%%CLASS%%', $class, $template );

			/* The Image */
			$image = '';

			if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) :

				$image_size = apply_filters( 'nicethemes_infoboxes_image_size', array( $args['width'], $args['height'] ) );

				if ( $infobox_readmore <> '' ) :
					$image .= '<a href="' . $infobox_readmore . ' " rel="bookmark" title="' . sprintf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) .'" ' . $infobox_url_target . '>';
				endif;

				if ( function_exists( 'nice_image' ) ){
					$image .= nice_image( array ( 'echo' => 'false', 'key' =>'infobox-image', 'width' => $args['width'], 'height' => $args['height'] ) );
				} else {
					$image .= get_the_post_thumbnail( get_the_ID() , $image_size );
				}

				if ( $infobox_readmore <> '' ) : $image .= '</a>' ; endif;

			endif;

			$template = str_replace( '%%IMAGE%%', $image, $template );


			/* Title */

			$title = $args['before_title'];

			if ( $infobox_readmore <> '' ) {
				$title .= '<a href="' . $infobox_readmore . '" rel="bookmark" title="' . sprintf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) .'" ' . $infobox_url_target . '>' . get_the_title() . '</a>';
			} else {
				$title .= get_the_title();
			}

			$title .= $args['after_title'];

			$template = str_replace( '%%TITLE%%', $title, $template );


			/* Content */

			if ( '' != get_the_excerpt() ) {
				$content = get_the_excerpt();
			} else {
				$content = get_the_content();
			}

			$content = apply_filters( 'nicethemes_infoboxes_content', $content, $query->post );
			$template = str_replace( '%%CONTENT%%', $content, $template );

			/* Read more Link */

			$readmore = '';

			if ( $infobox_readmore <> '' ) :

				$readmore .= '<a href="' . $infobox_readmore . '" rel="bookmark" class="read-more" title="' . sprintf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) . '" ' . $infobox_url_target . '>';

				if ( $infobox_readmore_anchor <> '' ) {
					$readmore .= $infobox_readmore_anchor;
				} else {
					$readmore .= __( 'Read more', 'nicethemes' );
				}

			$readmore .= '</a>';

			endif;

			$template = str_replace( '%%READMORE%%', $readmore, $template );

			$template = apply_filters( 'nicethemes_infoboxes_template', $template, $query->post );

			$output .= $template;

		endwhile;

		// close grid div
		$output .= '</div><!--/.infoboxes .grid -->' . "\n";

		$output .= $args['after'] . "\n";

	endif;

	wp_reset_postdata();

	// Allow child themes/plugins to filter here.
	$output = apply_filters( 'nicethemes_infoboxes_html', $output, $query, $args );

	if ( $args['echo'] == true ) echo $output;
	else return $output;

	do_action( 'nicethemes_infoboxes_after', $args );

}

endif;


if ( ! function_exists( 'nice_home_videos' ) ) :
/**
 * nice_home_videos()
 *
 * Create a list of articles with a video post format
 *
 * @since 1.0.0
 *
 */

function nice_home_videos( $args = array() ) {

	$defaults = apply_filters( 'nice_home_videos_default_args', array(
						'columns'       => 3,
						'numberposts'   => 5,
						'orderby'       => 'menu_order',
						'echo'          => true,
						'title'         => __( 'Video Library', 'nicethemes' ),
						'before_title'  => '<h2>',
						'after_title'   => '</h2>',
						'before'        => '',
						'after'         => ''
						)
						);

	$args = wp_parse_args( $args, $defaults );

	$args = apply_filters( 'nice_home_videos_args', $args );

	do_action( 'nice_home_videos_before', $args );

	$video_posts_args = array(
								'posts_per_page' => $args['numberposts'],
								'orderby'        => $args['orderby'],
								'post_type'      => 'article',
								'order'          => 'ASC',
								'tax_query'      => array(
														array(
															'taxonomy' => 'post_format',
															'field'    => 'slug',
															'terms'    => array( 'post-format-video' ),
															)
														)
								);

	$query = new WP_Query( $video_posts_args );

	$v = 0;
	$output = '';

	if ( $query->have_posts() ) :

		$output .= $args['before'];

		while ( $query->have_posts() ) : $query->the_post();

			$v++;

			if ( $v == 1 ){
				$embed = get_post_meta( get_the_ID(), 'embed', true );
				if ( $embed <> '' ) {
					$output .= '<div id="" class="video-content entry">';
					$output .=  nice_embed( array ( 'id' => get_the_ID(), 'echo' => false, 'width' => 960, 'height' => 540 ) );
					$output .=  '</div>';
				}
			}

			if ( $v == 1 ) {

				$output .= '<div id="" class="video-list">';
				$output .= $args['before_title'] . $args['title'] . $args['after_title'];
				$output .= '<ul>';
			}

			$output .= '<li><i class="fa fa-youtube-play"></i> <a href="' . get_permalink() .'">' . get_the_title() . '</a></li>';

		endwhile;

		if ( $v > 0 ) $output .= '</ul></div>';

		$output .= $args['after'];

	endif;

	if ( $args['echo'] == true ) echo $output;
	else return $output;

	do_action( 'nice_home_videos_after', $args );

}

endif;


if ( ! function_exists( 'nicethemes_gallery' ) ) :
/**
 * nicethemes_gallery()
 *
 * Create a list of articles, by category, within a grid.
 *
 * @since 1.0.0
 *
 */

function nicethemes_gallery( $args = array() ) {

	global $post;

	$defaults = apply_filters( 'nicethemes_gallery_default_args', array(
						'ids'         => null,
						'columns'     => 3,
						'rows'        => false,
						'numberposts' => -1,
						'orderby'     => 'menu_order',
						'echo'        => true,
						'size'        => 'medium_large',
						'order'       => 'ASC',
						'width'       => 480,
						'height'      => 480,
						'before'      => '',
						'after'       => '')
		);


	$args = wp_parse_args( $args, $defaults );

	$args = apply_filters( 'nicethemes_gallery_args', $args );

	do_action( 'nicethemes_gallery_before', $args );

	$output = '';

	if ( ! empty ( $args['ids'] ) ) {

		// we get the ids parameter containing the list of images
		// Set the list in an array
		$ids            = array();
		$attachment_ids = explode( ',', $args['ids'] );
		$ids            = array_merge( $ids, $attachment_ids );
		$attachments    = array_flip( $ids );

	} else {

		// get the images from the media uploaded to the page/post/cpt
		$attachments = get_children( array(
											'post_parent'    => get_the_ID(),
											'post_type'      => 'attachment',
											'post_mime_type' => 'image',
											'order'          => $args['order'],
											'numberposts'    => $args['numberposts'],
											'orderby'        => $args['orderby']
											)
					);

		if ( empty( $attachments ) ) {
			// if the gallery shortcode is used, we get the images from that
			$attachments = nice_attachments_from_gallery();
		}

	}

	if ( ! empty( $attachments ) && ( count( $attachments ) > 1 ) ) :

		// begin parsing the images, creating the gallery
		$output .= $args['before'] . "\n";

		// The template for each gallery item
		$tpl = '<div class="%%CLASS%%"><figure class="thumb">%%IMAGE%%</figure></div>';
		$tpl = apply_filters( 'nicethemes_gallery_item_template', $tpl, $args );

		$loop = 0;

		$output .= '<div class="nice-gallery grid">' . "\n";

		foreach ( $attachments as $att_id => $attachment ) : $loop++;

			$template = $tpl;

			$class = 'item columns-' . esc_attr( intval( $args['columns'] ) );
			if ( $loop % $args['columns'] == 0 ) {
				$class .= ' last';
			}

			if ( ( $loop - 1 ) % $args['columns'] == 0 ) {
				$class .= ' first';
				if ( $args['rows'] ) $output .= '<div class="row">' . "\n";
			}

			$template = str_replace( '%%CLASS%%', $class, $template );

			$image = '<a data-fancybox="group" class="fancybox" rel="group" href="' . wp_get_attachment_url( $att_id ) . '" title="' . get_the_title( $att_id ) . '">';

			/**
			 * Support native image sizes, if any was specified.
			 */
			$image_size = 'medium';

			if ( $args['size'] ) {
				$image_size = esc_attr( $args['size'] );
			}

			/**
			 * @hook nicethemes_gallery_image_size
			 *
			 * Modify the image size of gallery items.
			 *
			 * @since 1.0.0
			 */
			$image_size = apply_filters( 'nicethemes_gallery_image_size', $image_size );

			if ( function_exists( 'nice_image' ) ) {
				$image .= nice_image( array(
						'size' => $image_size,
						'id'   => $att_id,
						'echo' => false,
					)
				);
			} else {
				$image .= get_the_post_thumbnail( $att_id, $image_size );
			}

			$image .= '<div class="mask"></div></a>';

			$template = str_replace( '%%IMAGE%%', $image, $template );

			// $post ??
			$template = apply_filters( 'nicethemes_gallery_template', $template, $post );

			$output .= $template;

			if ( ( $loop % $args['columns'] == 0 ) && $args['rows'] ) {
				$output .= '</div>';
			}

		endforeach;

		if ( ( $loop  % $args['columns'] != 0 ) && $args['rows'] ) $output .= '</div>';

		$output .= '</div>';

		$output .= $args['after'] . "\n";

	else :

		$output .= __( 'There are no images for this gallery', 'nicethemes' );

	endif;

	$output = apply_filters( 'nicethemes_gallery_html', $output, $attachments, $args );

	if ( $args['echo'] == true ) echo $output;
	else return $output;

	do_action( 'nicethemes_gallery_after', $args );

}

endif;

if ( ! function_exists( 'nice_header_skin' ) ) :
/**
 * Return the navigation content menu skin
 *
 * @since 2.0
 *
 * @return string
 */
function nice_header_skin() {
	$nice_header_skin  = nice_get_option( 'nice_header_skin', 'dark' );
	$single            = is_page() || is_single();

	if ( $single ) {
		$_post_header_skin = get_post_meta( get_the_ID(), '_post_header_skin', true );

		if ( ! empty( $_post_header_skin ) ) {
			$nice_header_skin = $_post_header_skin;
		}
	}

	return $nice_header_skin;
}
endif;

if ( ! function_exists( 'nice_header_background_color' ) ) :
/**
 * Return the header background color.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_header_background_color() {
	if ( is_page() || is_single() ) {
		$_post_background_color = get_post_meta( get_the_ID(), '_post_header_background_color', true );

		if ( ! empty( $_post_background_color ) ) {
			$background_color = $_post_background_color;
		}
	}

	if ( empty( $background_color ) ) {
		$background_color = nice_get_option( '_header_background_color' );
	}

	return $background_color;
}
endif;

if ( ! function_exists( 'nice_header_navigation_submenu_skin' ) ) :
/**
 * Return the navigation content submenu skin
 *
 * @since 2.0
 *
 * @return string
 */
function nice_header_navigation_submenu_skin() {
	$nice_navigation_submenu_skin = nice_get_option( 'nice_header_submenu_skin', 'dark' );

	if ( is_page() || is_single() ) {
		$_post_navigation_submenu_skin = get_post_meta( get_the_ID(), '_post_navigation_submenu_skin', true );

		if ( ! empty( $_post_navigation_submenu_skin ) ) {
			$nice_navigation_submenu_skin = $_post_navigation_submenu_skin;
		}
	}

	return $nice_navigation_submenu_skin;
}
endif;

if ( ! function_exists( 'nice_boxed_layout' ) ) :
/**
 * Whether the general layout should be boxed.
 *
 * @since 2.0
 *
 * @return bool
 */
function nice_boxed_layout() {
	$boxed_layout = ( 'boxed' === nice_get_option( 'nice_layout_type' ) );

	/**
	 * @hook nice_boxed_layout
	 *
	 * Check if the layout should be boxed or not.
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'nice_boxed_layout', $boxed_layout );
}
endif;

if ( ! function_exists( 'nice_header_nav_text_transform' ) ) :
/**
 * Return the navigation text transform
 *
 * @since 2.0
 *
 * @return string
 */
function nice_header_nav_text_transform() {

	return nice_get_option( 'nice_nav_text_transform', 'uppercase' );
}
endif;

if ( ! function_exists( 'nice_header_subnav_text_transform' ) ) :
/**
 * Return the sub navigation text transform
 *
 * @since 2.0
 *
 * @return string
 */
function nice_header_subnav_text_transform() {

	return nice_get_option( 'nice_subnav_text_transform', 'uppercase' );
}
endif;

if ( ! function_exists( 'nice_footer_skin' ) ) :
/**
 * Return the footer skin
 *
 * @since 2.0
 *
 * @return string
 */
function nice_footer_skin() {
	$nice_footer_skin = nice_get_option( 'nice_footer_skin', 'dark' );

	if ( is_page() || is_single() ) {
		$post_footer_skin = get_post_meta( get_the_ID(), '_post_footer_skin', true );

		if ( ! empty( $post_footer_skin ) && $post_footer_skin !== '' ) {
			$nice_footer_skin = $post_footer_skin;
		}
	}

	return $nice_footer_skin;
}
endif;

if ( ! function_exists( 'nice_faq' ) ) :
/**
 * nice_faq()
 *
 * Create a list of faq
 *
 * @since 2.0.2
 *
 */
function nice_faq( $args = array() ) {

	$defaults = apply_filters( 'nice_faq_default_args', array(
					'numberposts'  => -1,
					'type'         => '',
					'orderby'      => 'menu_order',
					'echo'         => true,
					'order'        => apply_filters( 'nice_faq_order', 'ASC' ),
					'category'     => 0
				)
	);

	$args = wp_parse_args( $args, $defaults );

	$args = apply_filters( 'nice_faq_args', $args );

	do_action( 'nice_faq_before', $args );

	$query_args = array(
					'post_type'      => 'faq',
					'posts_per_page' => $args['numberposts'],
					'orderby'        => $args['orderby'],
					'order'          => $args['order']
				);

	if ( $args['category'] ) {

		$query_args['tax_query'] = array(
										array(
												'taxonomy'  => 'faq-category',
												'field'     => 'id',
												'operator'  => 'IN',
												'terms'     => $args['category']
												)
											);

	}

	$query = new WP_Query( $query_args );

	// Prioritize scroll format over accordion.
	$output = nice_faq_get_scroll_output( $query, $args ) ?: nice_faq_get_output( $query, $args );

	wp_reset_postdata();

	// Allow child themes/plugins to filter here.
	$output = apply_filters( 'nice_faq_html', $output, $query, $args );

	if ( $args['echo'] === true ) {
		echo $output;
	} else {
		return $output;
	}

	do_action( 'nice_faq_after', $args );

}
endif;

if ( ! function_exists( 'nice_faq_get_output' ) ) :
/**
 * Obtain the output of FAQ list.
 *
 * @since  2.0.3
 *
 * @param  WP_Query $query
 * @param  array    $args
 *
 * @return string
 */
function nice_faq_get_output( $query, $args = array() ) {
	if ( ! $query->have_posts() ) {
		return '';
	}

	$output = '';

	while ( $query->have_posts() ) : $query->the_post();
		$output .= '<article id="faq-' . get_the_ID() . '" class="faq clearfix">' . "\n";
		$output .= '<header><span class="faq-title"><a name="faq-' . get_the_ID() . '">' . get_the_title() . '</a></span></header>' . "\n";
		$output .= '<div class="entry-content">' . "\n";

		// Make sure we get the post's content correctly formatted.
		ob_start();
		the_content();
		$output .= ob_get_clean();

		$output .= '</div>' . "\n";
		$output .= '</article>' . "\n";
	endwhile;

	return $output;
}
endif;

if ( ! function_exists( 'nice_faq_get_scroll_output' ) ) :
/**
 * Obtain the output of FAQ list in scroll format.
 *
 * @since  2.0.3
 *
 * @param  WP_Query $query
 * @param  array    $args
 *
 * @return string
 */
function nice_faq_get_scroll_output( $query, $args = array() ) {
	if ( ! $query->have_posts() || empty( $args['type'] ) || $args['type'] !== 'scroll' ) {
		return '';
	}

	$questions = '<ul class="faq-questions">' . "\n";

	$output = '';

	while ( $query->have_posts() ) : $query->the_post();
		$questions .= '<li><a href="#faq-' . get_the_ID() . '" data-target="#faq-' . get_the_ID() . '">' . get_the_title() . '</a></li>' . "\n";

		$output .= '<article id="faq-' . get_the_ID() . '" class="faq-entry">' . "\n";
		$output .= '<header><h3><a name="faq-' . get_the_ID() . '">' . get_the_title() . '</a></h3></header>' . "\n";
		$output .= '<div class="entry-content">' . "\n";
		$output .= get_the_content() . "\n";
		$output .= '</div>' . "\n";
		$output .= '</article>' . "\n";

	endwhile;

	$questions .= '</ul>';
	$output = $questions . $output;

	return $output;
}
endif;

