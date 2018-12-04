<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to handle AJAX requests.
 *
 * @see nice_contact_ajax()
 * @see nicethemes_likes_ajax()
 * @see nice_contact_ajax()
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2016 NiceThemes
 * @since     1.0.3
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Only load file contents if we're in an AJAX context.
if ( defined( 'DOING_AJAX' ) and DOING_AJAX ) :

if ( ! function_exists( 'nice_contact_ajax' ) ) :
add_action( 'wp_ajax_nopriv_nice_contact_form', 'nice_contact_ajax' );
add_action( 'wp_ajax_nice_contact_form', 'nice_contact_ajax' );
/**
 * Handle the AJAX call from the contact form.
 *
 * @since 1.0.0
 */
function nice_contact_ajax() {

	global $nice_options;

	check_ajax_referer( 'play-nice', 'nonce' );

	if ( ! empty( $_POST ) ) {

		$admin_email = get_option( 'nice_email' );

		if ( trim( $admin_email ) == '' )
			$admin_email = get_bloginfo( 'admin_email' );

		$name    = $_POST['name'];
		$subject = $_POST['subject'];
		$mail    = $_POST['mail'];
		$msg     = $_POST['message'];

		$error = "";

		if ( ! $name ) {
			$error .= __( 'Please tell us your name','nicethemes' ) . "<br />";
		}

		if ( ! $mail ) {
			$error .= __( 'Please tell us your E-Mail address','nicethemes' ) . "<br />";
		}

		if ( ! $msg ) {
			$error .= __( 'Please add a message','nicethemes' );
		}

		if ( empty( $error ) ) {

			$mail_subject = '[' . get_bloginfo( 'name' ) . '] ' . __( 'New contact form received','nicethemes' );

			$body = __( 'Name: ', 'nicethemes' ) . "$name \n\n";
			if( !empty( $subject ) )
				$body .= __( 'Subject: ', 'nicethemes' ) ."$subject\n\n";

			$body .= __( 'Email: ', 'nicethemes') ."$mail \n\n" . __( 'Comments: ', 'nicethemes' )  ."$msg";

			$headers[] = __( 'From: ', 'nicethemes' ) . $name . ' <' . $mail . '>';
			$headers[] = __( 'Reply-To: ', 'nicethemes' ) . $mail ;
			$headers[] = "X-Mailer: PHP/" . phpversion();

			if ( $sent = wp_mail( $admin_email, $mail_subject, $body, $headers ) ) {
				_e( 'Thank you for leaving a message.', 'nicethemes' );
			} else {
				_e( 'There has been an error, please try again.', 'nicethemes' );
			}

		} else {
			echo $error;
		}
	}

	die();
}
endif;

if ( ! function_exists( 'nicethemes_likes_ajax' ) ) :
add_action( 'wp_ajax_nopriv_nicethemes_likes_add', 'nicethemes_likes_ajax' );
add_action( 'wp_ajax_nicethemes_likes_add', 'nicethemes_likes_ajax' );
/**
 * nicethemes_likes_ajax()
 *
 * Handles the ajax request for the like functionality
 *
 * @since 1.0.0
 *
 */
function nicethemes_likes_ajax() {

	check_ajax_referer( 'play-nice', 'nonce' );

	if ( ! empty( $_POST ) && ! empty( $_POST['id'] ) ) {

		if ( nicethemes_likes_can( $_POST['id'] ) ) {

			$count_key = '_like_count';
			$count = nicethemes_likes_count( $_POST['id'] );
			if ( $count == '' ) {
				delete_post_meta( $_POST['id'], $count_key );
				add_post_meta( $_POST['id'], $count_key, '1' );
				$count = 1;
			} else {
				$count++;
				update_post_meta( $_POST['id'], $count_key, $count );
			}

			$ip_list = get_post_meta( $_POST['id'], '_like_ip', true );

			$user_ip = nice_user_ip();

			if ( ( count( $ip_list ) != 0 ) && ( is_array( $ip_list ) ) ) {
				if ( ! in_array( $user_ip, $ip_list ) ) {
					$ip_list[] = $user_ip;
				}
				update_post_meta( $_POST['id'], '_like_ip', $ip_list );
			} else {
				$ip_list = array();
				$ip_list[] = $user_ip;
				update_post_meta( $_POST['id'], '_like_ip', $ip_list );
			}

			echo $count;

		}
	}

	die();

}

endif;


if ( ! function_exists( 'nice_masonry_blog_ajax' ) ) :
add_action( 'wp_ajax_nopriv_nice_more_posts_loader', 'nice_masonry_blog_ajax' );
add_action( 'wp_ajax_nice_more_posts_loader', 'nice_masonry_blog_ajax');
/**
 * nice_masonry_blog_ajax()
 *
 * Ajax function for the masonry blog
 *
 * @since 1.0.0
 *
 */
function nice_masonry_blog_ajax( $args = array() ) {

	check_ajax_referer( 'play-nice', 'nonce' );

	if ( ! empty( $_POST ) ) {

		$page = ( isset( $_POST['pageNumber'] ) ) ? $_POST['pageNumber'] : 0;

		$output  = '';
		$columns = 3;
		$loop    = 0;

		$query_args = array(
						'posts_per_page' => get_option('posts_per_page'),
						'paged'          => $page
					);

		// The Query
		$query = new WP_Query( $query_args );

		// The Loop
		if ( $query->have_posts() ) {

				while ( $query->have_posts() ) {
						$query->the_post();
							$loop++;

				$class = '';
				if ( $loop % $columns == 0 )
					$class = 'last';
				if ( ( $loop - 1 ) % $columns == 0 )
					$class = 'first';
						$output .= '<div id="post-' . get_the_ID() . '" class="masonry-item isotope-item columns-' . $columns . ' ' . $class . '">';
						$output .= '<!-- BEGIN .post -->';
						$output .= '<article class="post clearfix">';

						if ( has_post_thumbnail() ) :
							$output .= '<figure class="featured-image view view-more">';
							$output .= '<a href="' . get_permalink() . '" title="' . sprintf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) . '">';
							$output .= nice_image( array( 'width' => 580, 'height' => 405, 'class' => 'wp-post-image', 'echo' => false ) );
							$output .= '</a>';
							$output .= '</figure>';
						endif;

						$output .= '<header>';
						$output .= '<h2 class="post-title">';
						$output .= '<a href="' . get_permalink() . '" rel="bookmark" title="' . sprintf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) . '">' . get_the_title() . '</a>';
						$output .= '</h2>';
						ob_start();
			nice_post_meta_masonry();
						$post_meta_masonry = ob_get_contents();
						ob_end_clean();
						$output .= $post_meta_masonry;
			$output .= '</header>';
						$output .= '<div class="entry">';
						$output .= '<div class="post-content">';
							$nice_excerpt = substr( get_the_excerpt(), 0, 400 ); //truncate excerpt according to $len
							if ( strlen( $nice_excerpt ) < strlen( get_the_excerpt() ) ) {
									$nice_excerpt = $nice_excerpt . "...";
							}
						$output .= '<p>' . $nice_excerpt . '</p>';
						$output .= '<a class="readmore" href="' . get_permalink() . '" title="' . sprintf( __( 'Permanent Link to %s', 'nicethemes' ), esc_attr( get_the_title() ) ) . '">' . __( 'Read More', 'nicethemes' ) . '</a>';
						$output .= '</div>';
						$output .= '</div>';
						$output .= '<!-- END .post -->';
						$output .= '</article>';
						$output .= '</div>';
				}

		} else {
				$output .= '<div class="no-more-posts clearfix"><span>' . __( 'No More Posts Found.', 'nicethemes' ) . '</span></div>';
		}

		 echo $output;

		/* Restore original Post Data */
		wp_reset_postdata();

	}
	die();
}

endif;

endif;
