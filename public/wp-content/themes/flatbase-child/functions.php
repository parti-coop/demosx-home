<?php
/**
 * DemosX by Parti Coop.
 *
 * Functions
 *
 * @package   Demosx
 * @author    Parti Coop <contact@parti.xyz>
 * @license   GPL-2.0+
 * @copyright 2017 Parti Coop
 * @since     1.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_child_theme_enqueue_styles' ) ) :
add_action( 'wp_enqueue_scripts', 'nice_child_theme_enqueue_styles' );
/**
 * nice_child_theme_enqueue_styles()
 *
 * Include the parent theme styles.
 *
 * @since 1.0
 *
 */
function nice_child_theme_enqueue_styles() {
	wp_enqueue_style( 'flatbase-stylesheet', get_template_directory_uri() . '/style.css' );
}
endif;

if ( ! function_exists( 'nice_homepage_do' ) ) :
add_action( 'homepage', 'nice_homepage_do' );
/**
 * Print homepage contents.
 *
 * @since 1.0.0
 */
function nice_homepage_do() {
  $homepage_elements = nice_homepage_get_elements();
  $replacements      = array( 'knowledgebase' => array( 'nice_homepage_knowledgebase', false ) );

  $output = '';

  foreach ( $homepage_elements as $key ) {
    if ( isset( $replacements[ $key ] ) ) {
      ob_start();

      /**
       * Only try to execute callback function if it exists.
       */
      if ( function_exists( $replacements[ $key ][0] ) ) {
        // Update position of current block.
        nice_homepage_update_position();

        // Process content.
        $content = call_user_func( $replacements[ $key ][0], $replacements[ $key ][1] );

        // Save string for output.
        $output .= $content ? $content : ob_get_contents();

        if ( ! $output || ( isset( $previous_content ) && $output === $previous_content ) ) {
          nice_homepage_rewind_position();
        }

        $previous_content = $output;
      }

      ob_end_clean();
    }
  }

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
      $output .= '<a href="#" onClick="alert(\'운영가이드는 2019년 1월 중 공개 예정입니다\'); return false;" title="' . sprintf( esc_attr__( 'View all articles in %s', 'nicethemes' ), $category->name ) . '" ' . '>';
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
        $output .= '<h4><a href="#" onClick="alert(\'운영가이드는 2019년 1월 중 공개 예정입니다\'); return false;" title="' . sprintf( esc_attr__( 'View all articles in %s', 'nicethemes' ), $sub_category->name ) . '" >' . esc_html( $sub_category->name ) . '</a></h4></header>' . "\n\n";
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

      $output .= '<li>' . $article_icon . ' <a href="#" onClick="alert(\'운영가이드는 2019년 1월 중 공개 예정입니다\'); return false;" title="' . sprintf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) .'">' . get_the_title() . '</a></li>';

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

