<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions that print out HTML.
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

if ( ! function_exists( 'nice_pagenavi' ) ) :
/**
 * nice_pagenavi()
 *
 * If wp_pagenavi exists, it shows it.
 * else, it shows the < previous | next > links.
 *
 * @since 1.0.0
 *
 */
function nice_pagenavi() {
	ob_start();

	if ( function_exists( 'wp_pagenavi' ) ) {

		wp_pagenavi();

	} else { ?>

		<?php if ( get_next_posts_link() || get_previous_posts_link() ) { ?>

			<nav class="nav-entries">
				<div class="nav-prev fl"><?php next_posts_link( ''. __( 'Older posts', 'nicethemes' ) . '' ); ?></div>
				<div class="nav-next fr"><?php previous_posts_link( ''. __( 'Newer posts', 'nicethemes' ) . '' ); ?></div>
				<div class="fix"></div>
			</nav>

		<?php } ?>

	<?php }

	$output = ob_get_contents();
	ob_end_clean();

	$output = apply_filters( 'nice_pagenavi', $output );

	echo $output;
}

endif;


if ( ! function_exists( 'nice_post_meta' ) ) :
/**
 * nice_post_meta()
 *
 * Post metadata, nicely displayed.
 *
 * @since 1.0.0
 *
 */
function nice_post_meta() {
	ob_start(); ?>

	<p class="post-meta">
		<span class="post-author"><i class="fa fa-user"></i> <?php the_author_posts_link(); ?></span>
		<span class="post-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><i class="fa fa-clock-o"></i> <?php the_time( get_option( 'date_format' ) ); ?></span>
		<span class="post-comments"><i class="fa fa-comments-o"></i> <?php comments_popup_link(__( 'No Comments', 'nicethemes' ), __( '1 Comment', 'nicethemes' ), __( '% Comments', 'nicethemes' ) ); ?></span>
		<?php edit_post_link( __( 'Edit', 'nicethemes' ), '<span class="small"><i class="fa fa-pencil"></i>', '</span>' ); ?>
	</p>
<?php

	$output = ob_get_contents();
	ob_end_clean();

	$output = apply_filters( 'nice_post_meta', $output );

	echo $output;
}
endif;

if ( ! function_exists( 'nice_post_meta_masonry' ) ) :
/**
 * nice_post_meta_masonry()
 *
 * Post metadata for the masonry template, nicely displayed.
 *
 * @since 1.0.0
 *
 */

function nice_post_meta_masonry() {
	ob_start(); ?>

	<p class="post-meta">
		<span class="post-author"><i class="fa fa-user"></i> <?php the_author_posts_link(); ?></span>
		<span class="post-date"><i class="fa fa-clock-o"></i><?php the_time( 'M j, Y' ); ?></span>
		<span class="post-comments"><i class="fa fa-comments-o"></i> <?php comments_popup_link( '0', '1', '%' ); ?></span>
		<?php edit_post_link( __( 'Edit', 'nicethemes' ), '<span class="edit"><i class="fa fa-pencil"></i>', '</span>' ); ?>
	</p>
<?php

	$output = ob_get_contents();
	ob_end_clean();

	$output = apply_filters( 'nice_post_meta_masonry', $output );

	echo $output;
}
endif;

if ( ! function_exists( 'nice_article_meta' ) ) :
/**
 * nice_article_meta()
 *
 * Articles metadata, nicely displayed.
 *
 * @since 1.0.0
 *
 */
function nice_article_meta() {

	global $nice_options;
	ob_start(); ?>

	<div class="entry-meta">

		<?php if ( isset( $nice_options['nice_views'] ) && nice_bool( $nice_options['nice_views'] ) ) : ?>
		<span class="nice-views">
			<?php $pageviews = nice_pageviews_count(); ?>
			<i class="fa fa-bullseye"></i><?php printf( _n( '1 view', '%s views', $pageviews, 'nicethemes' ), $pageviews ); ?>
		</span>
		<?php endif; ?>

		<?php if ( isset( $nice_options['nice_reading_time'] ) && nice_bool( $nice_options['nice_reading_time'] ) ) : ?>
		<span class="nice-reading-time">
			<?php nicethemes_reading_time( array( 'before' => '<i class="fa fa-bookmark"></i>' ) ); ?>
		</span>
		<?php endif; ?>

		<?php if ( isset( $nice_options['nice_likes'] ) && nice_bool( $nice_options['nice_likes'] ) ) : ?>
		<a class="nice-like<?php if ( ! nicethemes_likes_can( get_the_ID() ) ) echo ' liked';  ?>" data-id="<?php the_ID(); ?>" href="#" title="<?php _e( 'Like this', 'nicethemes' ); ?>">
			<i class="fa fa-heart"></i>
			<span class="like-count">
				<?php echo nicethemes_likes_count(); ?>
			</span>
		</a>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'nicethemes' ), '<span class="edit-link"><span class="edit"><i class="fa fa-pencil"></i>', '</span></span>' ); ?>

	</div>
<?php
	$output = ob_get_contents();
	ob_end_clean();

	$output = apply_filters( 'nice_article_meta', $output );

	echo $output;
}
endif;


if ( ! function_exists( 'nice_post_author' ) ) :
add_action( 'nice_post_author', 'nice_post_author', 10 );
/**
 * Post author info, nicely displayed.
 *
 * @since 1.0.0
 *
 * @param array $args Arguments to display post author.
 */
function nice_post_author() {

	global $post;

	// Return early in non-post views.
	if ( ! is_single() ) {
		return;
	}

	$display = nice_bool( nice_get_option( 'nice_post_author' ) );

	// Return early if the post doesn't have to be displayed.
	/**
	 * @hook nice_post_author_display
	 *
	 * Set to false if you don't want the post author to be displayed.
	 *
	 * @since 1.0.0
	 */
	if ( ! apply_filters( 'nice_post_author_display', $display ) ) {
		return;
	}

	ob_start();

	?>
	<div id="post-author">
		<div class="profile-image thumb"><?php echo get_avatar( get_the_author_meta( 'ID' ), '70' ); ?></div>
			<div class="profile-content">
				<h4 class="title"><?php printf( esc_attr__( 'About %s', 'nicethemes' ), get_the_author() ); ?></h4>
				<?php the_author_meta( 'description' ); ?>
				<div class="profile-link">
					<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
						<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'nicethemes' ), get_the_author() ); ?>
					</a>
				</div><!-- #profile-link	-->
		</div><!-- .post-entries -->
		<div class="fix"></div>
	</div><!-- #post-author -->
	<?php
	$output = ob_get_contents();
	ob_end_clean();

	echo $output;
}
endif;

if ( ! function_exists( 'nice_related_posts' ) ) :
/**
 * nice_related_posts()
 *
 * Echoes a list of the related posts/cpt by taxonomy
 *
 * @since 1.0.0
 *
 */
function nice_related_posts( $args = array() ) {

	$defaults = apply_filters( 'nice_related_posts_args', array(
							'post_type'      => 'post',
							'title'          => __( 'Related Posts', 'nicethemes' ),
							'taxonomy'       => 'category',
							'before_title'   => '<h3>',
							'after_title'    => '</h3>',
							'before'         => '<section id="related-posts" class="clearfix">',
							'after'          => '</section>',
							'posts_per_page' => 5,
							'ignore_sticky'  => 1,
							'icon_article'   => '<i class="fa fa-file-o"></i>',
							'icon_video'     => '<i class="fa fa-youtube-play"></i>',
							'echo'           => true)
			);

	$args = wp_parse_args( $args, $defaults );

	$args = apply_filters( 'nice_related_posts_args', $args );

	do_action( 'nice_related_posts_before', $args );

	$output = '';

	$categories = get_the_terms( get_the_ID(), $args['taxonomy'] );

	if ( $categories ) {

		$category_ids = array();

		foreach( $categories as $individual_category ) $category_ids[] = $individual_category->term_id;

		$query_args = array(
					'post_type'		=> $args['post_type'],
					'tax_query'		=> array(
										array(
												'taxonomy' => $args['taxonomy'] ,
												'field'    => 'id',
												'operator' => 'IN',
												'terms'    => $category_ids
											  )
										),
					'post__not_in'			=> array( get_the_ID() ),
					'posts_per_page'		=> $args['posts_per_page'] , // Number of related posts that will be shown.
					'ignore_sticky_posts'	=> $args['ignore_sticky']
					);

		$nice_query = new wp_query( $query_args );

		if ( $nice_query->have_posts() ) {

			$output .= $args['before'];
			$output .= $args['before_title'] . $args['title'] . $args['after_title'];
			$output .= '<ul class="clearfix">' . "\n";

			while( $nice_query->have_posts() ) : $nice_query->the_post();

				if ( has_post_format( 'video' ) ) {
					$li_class = 'format-video';
					$nice_icon = $args['icon_video'];
				} else {
					$li_class = 'format-article';
					$nice_icon = $args['icon_article'];
				}

				$output .= '<li class="' . $li_class . '">';
				$output .= '<a href="' . get_permalink() . '" rel="bookmark" title="' . get_the_title() . '">';
				$output .=  $nice_icon . get_the_title();
				$output .= '</a>';
				$output .= '</li>';

			endwhile;

			$output .= '</ul>' . "\n";
			$output .= $args['after'];

		}
	}

	wp_reset_query();

	// Allow child themes/plugins to filter here.
	$output = apply_filters( 'nice_related_posts_html', $output, $args );

	if ( $args['echo'] == true ) echo $output;
	else return $output;

	do_action( 'nice_related_posts_after', $args );

}

endif;

if ( ! function_exists( 'nice_header_properties' ) ) :
/**
 * Display additional properties for #header element.
 *
 * @since 2.0
 *
 * @param array $args List of properties and values for HTML tag.
 */
function nice_header_properties( array $args = array() ) {
	$output = '';

	$properties = array(
		'class' => nice_header_class( array(), false ),
		'data'  => nice_header_data( array(), false ),
	);

	if ( ! empty( $args ) ) {
		$properties = array_merge( $properties, $args );
	}

	/**
	 * @hook nice_header_properties
	 *
	 * Modify header properties by hooking in here.
	 *
	 * @since 1.0.0
	 */
	$properties = apply_filters( 'nice_header_properties', $properties );

	if ( is_array( $properties ) && ! empty( $properties ) ) {
		$output = join( ' ', $properties );
	}

	echo $output;
}
endif;

if ( ! function_exists( 'nice_welcome_message_class' ) ) :
/**
 * Add classes to the welcome message element.
 *
 *
 * @since  2.0
 *
 * @param  array $class List of classes for #header element.
 * @param  bool  $echo  Whether to print the output or not.
 *
 * @return string
 */
function nice_welcome_message_class( array $class = array(), $echo = true ) {
	$classes = array();

	$livesearch_align =  nice_get_option( 'nice_livesearch_align', 'center' ) ? nice_get_option( 'nice_livesearch_align', 'center' ) : 'center';

	$classes[] = nice_get_option( 'nice_welcome_message_skin', 'dark' );
	$classes[] = 'align-' . $livesearch_align;

	$classes[] = 'clearfix';

	if ( ! empty( $class ) ) {
		$classes = array_merge( $classes, $class );
	}

	$classes = array_map( 'esc_attr', $classes );

	/**
	 * @hook nice_welcome_message_class
	 *
	 * Modify welcome message classes by hooking in here.
	 *
	 * @since 2.0
	 */
	$classes = apply_filters( 'nice_welcome_message_class', $classes, $class );

	$output = nice_css_classes( $classes, $echo );

	return $output;
}
endif;

if ( ! function_exists( 'nice_post_thumbnail' ) ) :
/**
 * Nicely display the post thumbnail.
 *
 * The image is obtained by default using the `$content_width` size.
 *
 * @since 1.0.0
 *
 * @param array $args Arguments to display the thumbnail.
 */
function nice_post_thumbnail( $args = array() ) {
	// Return early if we don't need the thumbnail here.
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	/**
	 * @hook nice_post_thumbnail_display
	 *
	 * Set to false if you don't want to display the post thumbnail.
	 *
	 * @since 1.0.0
	 */
	if ( ! apply_filters( 'nice_post_thumbnail_display', true, $args ) ) {
		return;
	}

	/**
	 * @hook nice_post_thumbnail_default_args
	 *
	 * Hook in here to modify the default values for the post thumbnail.
	 *
	 * @since 1.0.0
	 */
	$defaults = apply_filters( 'nice_post_thumbnail_default_args', array(
		'size'    => nice_has_sidebar() ? 'large' : 'nice-extra-large',
		'class'   => 'wp-post-image',
		'before'  => '<figure class="featured-image">',
		'after'   => '</figure>',
		'link'    => '',
		'overlay' => true,
	) );

	// Process arguments.
	$args = wp_parse_args( $args, $defaults );
	/**
	 * @hook nice_post_thumbnail_args
	 *
	 * Hook in here to modify the arguments to generate the post thumbnail.
	 *
	 * @since 2.0
	 */
	$args = apply_filters( 'nice_post_thumbnail_args', $args );

	// Process link.
	$link = ! empty( $args['link'] ) ? $args['link'] : get_the_permalink();

	/**
	 * @hook nice_post_thumbnail_before
	 *
	 * Do something before the post thumbnail gets printed.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_post_thumbnail_before', $args );

	$image_wrapper_start = '';
	$image_wrapper_end   = '';

	if ( ! is_singular() || ! empty( $args['link'] ) ) {
		$image_wrapper_start = '<a class="item-caption-permalink" href="' . esc_url( $link ) . '"';

		if ( empty( $args['link'] ) ) {
			$image_wrapper_start .= ' title="' . sprintf( esc_attr__( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) . '"';
		} else {
			$image_wrapper_start .= ' target="_blank"';
		}

		$image_wrapper_start .= '>';

		$image_wrapper_end = '</a>';
	}

	ob_start();

	echo $args['before'], $image_wrapper_start; // WPCS: XSS ok.

	if ( is_singular() ) :
		nice_image( $args );
	else : ?>
		<?php if ( ! empty( $args['overlay'] ) ) : ?>
			<div class="overlay"></div>
		<?php endif; ?>

		<?php nice_image( $args ); ?>
	<?php
	endif;

	echo $image_wrapper_end, $args['after']; // WPCS: XSS ok.

	$output = ob_get_contents();
	ob_end_clean();

	/**
	 * @hook nice_post_thumbnail
	 *
	 * Modify the full output of a post thumbnail.
	 *
	 * @since 2.0
	 */
	$output = apply_filters( 'nice_post_thumbnail', $output );

	echo $output; // WPCS: XSS ok.

	/**
	 * @hook nice_post_thumbnail_after
	 *
	 * Do something after the post thumbnail gets printed.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_post_thumbnail_after', $args );
}
endif;

if ( ! function_exists( 'nice_video' ) ) :
/**
 * Wrapper for `nice_embed()`.
 *
 * @since  2.0
 *
 * @param  array $args Arguments to construct the output.
 *
 * @return string
 */
function nice_video( array $args = array() ) {
	/**
	 * @hook nice_video
	 *
	 * By-pass the output of the `nice_video()` function.
	 *
	 * @see nice_video()
	 *
	 * @since 2.0
	 */
	if ( $output = apply_filters( 'nice_video', '' ) ) {
		return $output;
	}

	/**
	 * @hook nice_video_default_args
	 *
	 * Modify the default arguments to generate the output of a post's video.
	 *
	 * @since 2.0
	 */
	$defaults = apply_filters( 'nice_video_default_args', array(
			'echo'  => true,
			'class' => 'featured-video',
		)
	);
	$args = wp_parse_args( $args, $defaults );

	/**
	 * @hook nice_video_args
	 *
	 * Modify the arguments to generate the output of a post's video.
	 *
	 * @since 2.0
	 */
	$args = apply_filters( 'nice_video_args', $args );

	// Obtain embedded code.
	$output = nice_embed( wp_parse_args( array( 'echo' => false ), $args ) );

	if ( $args['echo'] ) {
		echo $output; // WPCS: XSS Ok.
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_page_title' ) ) :
add_action( 'nice_single_title', 'nice_page_title' );
/**
 * Obtain page title for heading.
 *
 * @since  2.0
 *
 * @param  array $args
 *
 * @return string
 */
function nice_page_title( $args = array() ) {
	/**
	 * @hook nice_page_title_default_args
	 *
	 * Hook in here to modify the default arguments to generate the post title.
	 *
	 * @since 2.0
	 */
	$defaults = apply_filters( 'nice_page_title_default_args', array(
			'echo'   => true,
			'before' => '<h1 class="entry-title">',
			'after'  => '</h1>',
			'title'  => get_the_title(),
		)
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * @hook nice_page_title_args
	 *
	 * Hook in here to modify the arguments to generate the post title.
	 *
	 * @since 2.0
	 */
	$args = apply_filters( 'nice_page_title_args', $args );

	$title = $args['before'] . $args['title'] . $args['after'];

	/**
	 * @hook nice_page_title
	 *
	 * Modify the full output of a page title.
	 *
	 * @since 2.0
	 */
	$output = apply_filters( 'nice_page_title', $title, $args );

	if ( $args['echo'] ) {
		echo $output; // WPCS: XSS Ok.
	}

	return $output;

}
endif;

if ( ! function_exists( 'nice_wp_title' ) ) :
add_filter( 'wp_title', 'nice_wp_title' );
/**
 * Customize the page title.
 *
 * Hook origin:
 * @see wp_title()
 *
 * @since  2.0.2
 *
 * @param string $title Page title.
 *
 * @return string
 */
function nice_wp_title( $title ) {
	return $title . get_bloginfo( 'name' );
}
endif;
