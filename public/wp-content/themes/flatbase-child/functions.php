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

