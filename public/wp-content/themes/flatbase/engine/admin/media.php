<?php
/**
 * NiceFramework by NiceThemes.
 *
 * Functions related to media
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2015 NiceThemes
 * @since     1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * nice_jpeg_quality()
 *
 * Set the compression quality for images.
 * Takes the value from the theme options. If it's not defined, it set it to the default value = 90
 * Later used for the filters.
 *
 * @since 1.0.6
 *
 * @return (int)
 */

if ( ! function_exists( 'nice_jpeg_quality' ) ) :

	function nice_jpeg_quality() {
		$jpeg_quality = nice_get_option( 'nice_jpeg_quality' );

		if ( $jpeg_quality && $jpeg_quality <= 100 ) {
			return $jpeg_quality;
		}

		return 90;
	}

endif;

add_filter( 'jpeg_quality',         'nice_jpeg_quality' );
add_filter( 'wp_editor_set_quality', 'nice_jpeg_quality' );

if ( ! function_exists( 'nice_mime_types' ) ) :
add_filter( 'upload_mimes', 'nice_mime_types' );
/**
 * nice_mime_types()
 *
 * Add new mime types
 *
 * @since 2.0
 *
 * @param (array)
 * @return (array)
 */
function nice_mime_types( $mimes ) {

	// Add mime types.
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}
endif;

if ( ! function_exists( 'nice_embed' ) ) :
/**
 * nice_embed()
 *
 * nicely embed videos
 *
 * @since 1.0.0
 *
 * @param (array) [field, width, height, class, id]
 * @return (str/bool) html/0
 */
function nice_embed( $args ) {

	//Defaults
	$field    = 'embed';
	$width    = null;
	$height   = null;
	$class    = 'video';
	$id       = null;
	$wrap     = true;
	$echo     = true;
	$embed    = '';
	$embed_id = 'nice_embed';

	if ( ! is_array( $args ) ) {
		parse_str( $args, $args );
	}

	extract( $args );

	if ( empty( $embed ) ) {

		if ( empty( $id ) ) {
			global $post;
			$id = $post->ID;
		}

		$embed = get_post_meta( $id, $field, true );

	}

	if ( ! empty ( $embed ) ) {

		$embed = html_entity_decode( $embed ); // Decode HTML entities.

		$embed = nice_add_html_att( array( 'tag' => 'id', 'value' => $embed_id, 'code' => $embed ) );

		if ( $width || $height ) {

			$embed = nice_set_html_att( array( 'tag' => 'width', 'value' => $width, 'code' => $embed ) );
			$embed = nice_set_html_att( array( 'tag' => 'height', 'value' => $height, 'code' => $embed ) );

		}

		if ( $url = nice_get_html_att( array( 'html' => $embed, 'tag' => 'src', 'urldecode' => false ) ) ) {

			if ( strpos( $url, 'youtube' ) > 0 ) {

				$url = nice_add_url_param( array( 'url' => $url, 'tag' => 'enablejsapi', 'value' => '1' ) );

			} elseif ( strpos( $url, 'vimeo' ) > 0 ) {

				$url = nice_add_url_param( array( 'url' => $url, 'tag' => 'api', 'value' => '1' ) );
				$url = nice_add_url_param( array( 'url' => $url, 'tag' => 'player_id', 'value' => $embed_id ) );

			}

			$embed = nice_set_html_att( array( 'tag' => 'src', 'value' => $url, 'code' => $embed ) );

		}

		if ( nice_bool( $wrap ) ) {
			$html = '<div class="' . $class . '">' . $embed . '</div>';
		} else {
			$html = $embed;
		}

		if ( nice_bool( $echo ) ) {
			echo $html;
		} else {
			return $html;
		}

	} else {

		return false;

	}
}
endif;


/**
 * get_nice_image_path()
 *
 * Get image path / works with Multisite
 *
 * @since 1.0.0
 *
 * @param int $thumb_id
 * @return string $scr containing the full path
 */
function get_nice_image_path( $thumb_id = null, $full_path = false ) {

	$src = wp_get_attachment_url( $thumb_id );

	global $blog_id;

	if ( isset( $blog_id ) && $blog_id > 0 ) {

		$image_parts = explode( '/files/', $src );

		if ( isset( $image_parts[1] ) ) {

			if ( $full_path ) {
				$src = $image_parts[0] . '/blogs.dir/' . $blog_id . '/files/' . $image_parts[1];
			} else {
				$src = '/blogs.dir/' . $blog_id . '/files/' . $image_parts[1];
			}

		}

	}

	return $src;
}

if ( ! function_exists( 'nice_get_image_sizes' ) ) :
/**
 * Obtain information from registered image sizes.
 *
 * @since  1.0.0
 *
 * @param  string $size Name of registered image size.
 *
 * @return array|bool
 */
function nice_get_image_sizes( $size = '' ) {
	global $_wp_additional_image_sizes;

	$sizes = array();
	$get_intermediate_image_sizes = get_intermediate_image_sizes();

	// Create the full array with sizes and crop info
	foreach ( $get_intermediate_image_sizes as $_size ) {

		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
			$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
			$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
			$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );

		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	// Get only 1 size if found
	if ( $size ) {
		if ( isset( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		} else {
			return false;
		}
	}

	return $sizes;
}
endif;

if ( ! function_exists( 'nice_image' ) ) :
/**
 * Display image. If $scr not defined search for
 * featured image or images associated to the post.
 *
 * @since 1.0.0
 * @updated 2.0
 *
 * @param  array  $args Arbitrary arguments for the function.
 *
 * @return string
 */
function nice_image( $args ) {
	global $post, $wp_version;

	$width     = null;
	$height    = null;
	$crop      = null;
	$class     = '';
	$limit     = 1;
	$id        = null;
	$echo      = true;
	$src       = '';
	$size      = ( version_compare( $wp_version, '4.4', '>=' ) ) ? 'medium_large' : 'medium';
	$no_height = '';
	$attr      = array();
	$alt       = '';
	$title     = '';
	$thumb_id  = '';

	if ( ! is_array( $args ) ) {
		parse_str( $args, $args );
	}

	extract( $args );

	// Set post ID
	if ( empty( $id ) ) {
		$id = $post->ID;
	}

	// Get standard size, if not defined
	if ( empty( $width ) && empty( $height ) ) {
		$image_sizes = nice_get_image_sizes();

		if ( ! empty( $size ) && ! empty( $image_sizes[ $size ] ) ) {
			$width  = $image_sizes[ $size ]['width'];
			$height = $image_sizes[ $size ]['height'];
		} else {
			$width  = 768;
			$height = 0;
		}
	}

	if ( $src ) {
		$src = esc_attr( $src );
	}

	if ( ! $src ) {
		/* start searching for the image */
		// the ID is an attachment
		if ( 'attachment' === get_post_type( $id ) ) {
			// get the data from the attachment
			$thumb_id = $id;
			$src      = get_nice_image_path( $id );

		} elseif ( has_post_thumbnail( $id ) ) { // they send a post/page/cpt
			$thumb_id = get_post_thumbnail_id( $id );
			$src = get_nice_image_path( $thumb_id );

		} else { // they send nothing, get image from the content.
			// check the first attachment
			$attachments = get_children( array(
					'post_parent'    => $id,
					'numberposts'    => $limit,
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => 'DESC',
					'orderby'        => 'menu_order date',
				)
			);

			// Search for and get the post attachment
			if ( ! empty( $attachments ) ) {
				$attachments_values = array_values( $attachments );
				$first_attachment = array_shift( $attachments_values );
				// get the first attachment.
				$thumb_id = get_post_thumbnail_id( $first_attachment->ID );
				$src      = get_nice_image_path( $first_attachment->ID );

			} else {
				// retrieve the post content to find the first <img> appearance
				$matches = '';
				$post    = get_post( $id );

				ob_start();
				ob_end_clean();

				$how_many = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );

				if ( ! empty( $matches[1][0] ) ) {
					$src = $matches[1][0];
				}
			}
		}
	}

	// OUTPUT
	$output = '';

	// Image CSS class.
	if ( $class ) {
		$class = 'nice-image ' . esc_attr( $class );
	} else {
		$class = 'nice-image';
	}

	// Image metadata
	if ( $thumb_id ) {
		if ( ! $alt ) {
			$alt = esc_attr( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) );
		}

		if ( ! $title ) {
			$title = esc_attr( get_the_title( $thumb_id ) );
		}
	}

	$image_data = false;

	if ( is_null( $crop ) ) {
		$crop = nice_get_option( 'nice_wp_resize_crop' );
		$crop = is_null( $crop ) ? get_option( 'nice_wp_resize_crop' ) : $crop;
	}

	if ( ! ( is_array( $crop ) && ( 2 === count( $crop ) ) ) ) {
		$crop = nice_bool( $crop );
	}

	if ( $crop ) {
		// resize image
		if ( $thumb_id ) {
			// resize image
			$nice_image = nice_resize_image( $thumb_id, '', $width, $height, $crop );
			$src        = $nice_image['url'];

		} elseif ( $src ) {
			$nice_image = nice_resize_image( '', $src, $width, $height, $crop );
			$src        = $nice_image['url'];
		}

		if ( ! empty( $nice_image ) ) {
			$image_data = $nice_image;
		}

	} else {
		// use the thumbnail sizes
		if ( $size ) {
			$thumb_size = $size; // for predefined size
		} else {
			$thumb_size = array( $width, $height ); // on the fly size
		}
	}

	// Create a string with the extra arguments that we're gonna add to the <img /> tag.
	if ( is_array( $attr ) && ! empty( $attr ) ) {
		$extra_attr = '';

		foreach ( $attr as $_property => $_value ) {
			$extra_attr .= $_property . '="' . $_value . '"';
		}
	}

	if ( ! isset( $extra_attr ) ) {
		$extra_attr = '';
	}

	// Start generating the output for the different resizing options.
	if ( ! empty( $nice_image['url'] ) ) {
		$output .= '<img src="' . esc_url( $nice_image['url'] ) . '" class="' . $class . '"  title="' . $title . '" alt="' . $alt . '" ' . $extra_attr . ' />';

	} elseif ( version_compare( $wp_version, '4.4', '>=' ) && ! empty( $thumb_id ) && ! empty( $thumb_size ) ) {
		$image_args = array(
			'class' => $class,
			'title' => $title,
			'alt'   => $alt,
		);

		if ( is_array( $attr ) && ! empty( $attr ) ) {
			$image_args = array_merge( $image_args, $attr );
		}

		$output .= wp_get_attachment_image( $thumb_id, $thumb_size, false, $image_args );

	} else {
		$image_data = wp_get_attachment_image_src( $thumb_id, $thumb_size );

		if ( ! empty( $image_data ) ) {
			$src = $image_data[0];
		}

		if ( ! empty( $src ) ) {
			$set_width  = ' width="' . esc_attr( $width ) . '" ';
			$set_height = '';

			if ( ! $no_height && 0 < $height ) {
				$set_height = ' height="' . esc_attr( $height ) . '" ';
			}

			// WP resize without cropping.
			$output .= '<img src="' . esc_url( $src ) . '" class="' . $class . '"  title="' . $title . '" alt="' . $alt . '" ' . $set_width . ' ' . $set_height . ' ' . $extra_attr . ' />';
		}
	}

	/**
	 * @hook nice_image
	 *
	 * Hook here to perform actions after generating the image output.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_image', $output, $image_data, $args );

	/**
	 * @hook nice_image_output
	 *
	 * Hook here to modify the final output of the image.
	 *
	 * @since 2.0
	 */
	$output = apply_filters( 'nice_image_output', $output, $image_data, $args );

	if ( nice_bool( $echo ) ) {
		echo $output;
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_resize_image' ) ) :
/**
 * Resize images dynamically using wp built in functions
 *
 * @author Victor Teixeira
 *
 * @since 2.0
 *
 * Usage:
 *
 * <?php
 * $thumb = get_post_thumbnail_id();
 * $image = nice_resize_image( $thumb, '', 140, 110, true );
 * ?>
 * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
 *
 * @param int $attach_id
 * @param string $img_url
 * @param int $width
 * @param int $height
 * @param bool|array $crop
 * @return array
 */
function nice_resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
	// Cast $width and $height to integer
	$width = intval( $width );
	$height = intval( $height );

	// this is an attachment, so we have the ID
	if ( $attach_id ) {
		$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
		$file_path = get_attached_file( $attach_id );
	// this is not an attachment, let's use the image url
	} else if ( $img_url ) {
		$file_path = parse_url( esc_url( $img_url ) );
		$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];

		// Look for Multisite Path
		if ( false === file_exists( $file_path ) ) {

			global $blog_id;
			$file_path = parse_url( $img_url );

			if ( preg_match( '/files/', $file_path['path'] ) ) {

				$path = explode( '/', $file_path['path'] ) ;

				foreach ( $path as $k => $v ) {

					if ( 'files' === $v ) {
						$path[ $k - 1 ] = 'wp-content/blogs.dir/' . $blog_id;
					}

				}

				$path = implode( '/',$path );
			}

			$file_path = $_SERVER['DOCUMENT_ROOT'] . $path;
		}

		$orig_size = getimagesize( $file_path );

		$image_src[0] = $img_url;
		$image_src[1] = $orig_size[0];
		$image_src[2] = $orig_size[1];
	}

	$file_info = pathinfo( $file_path );

	// check if file exists
	if ( empty( $file_info['dirname'] ) && empty( $file_info['filename'] ) && empty( $file_info['extension'] )  ) {
		return;
	}

	$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
	if ( ! file_exists( $base_file ) ) {
		return;
	}

	$extension = '.'. $file_info['extension'];

	// the image path without the extension
	$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];

	$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;

	// checking if the file size is larger than the target size
	// if it is smaller or the same size, stop right here and return
	if ( $image_src[1] > $width ) {
		// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
		if ( file_exists( $cropped_img_path ) ) {
			$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );

			$nice_image = array(
				'url' => $cropped_img_url,
				'width' => $width,
				'height' => $height,
			);
			return $nice_image;
		}

		// $crop = false or no height set
		if ( false === $crop || ! $height ) {
			// calculate the size proportionately
			$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
			$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;

			// checking if the file already exists
			if ( file_exists( $resized_img_path ) ) {
				$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );

				$nice_image = array(
					'url' => $resized_img_url,
					'width' => $proportional_size[0],
					'height' => $proportional_size[1],
				);
				return $nice_image;
			}
		}

		// check if image width is smaller than set width
		$img_size = getimagesize( $file_path );
		if ( $img_size[0] <= $width ) {
			$width = $img_size[0];
		}

		// Check if GD Library installed
		if ( ! function_exists ( 'imagecreatetruecolor' ) ) {
			echo 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library';
			return;
		}

		// no cache files - let's finally resize it
		if ( function_exists( 'wp_get_image_editor' ) ) {
			$image = wp_get_image_editor( $file_path );
			if ( ! is_wp_error( $image ) ) {
				$image->resize( $width, $height, $crop );
				$save_data = $image->save();
				if ( isset( $save_data['path'] ) ) {
					$new_img_path = $save_data['path'];
				}
			}
		} else {
			// Use the corresponding deprecated function as a fallback, for backwards compatibility.
			$theme_check_bs = 'image_resize'; // Avoid Theme Check warning.
			if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
				$new_img_path = $theme_check_bs( $file_path, $width, $height, $crop );
			} else {
				$new_img_path = call_user_func( $theme_check_bs, $file_path, $width, $height, $crop );
			}
		}

		$new_img_size = getimagesize( $new_img_path );
		$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );

		// resized output
		$nice_image = array(
			'url'    => $new_img,
			'width'  => $new_img_size[0],
			'height' => $new_img_size[1],
		);

		/**
		 * @hook nice_resized_image
		 *
		 * Hook here to perform actions after resizing an image.
		 *
		 * @since 2.0
		 */
		do_action( 'nice_resized_image', $nice_image, $image_src );

		return $nice_image;
	}

	// default output - without resizing
	$nice_image = array(
		'url'    => $image_src[0],
		'width'  => $width,
		'height' => $height,
	);
	return $nice_image;
}
endif;

if ( ! function_exists( 'nice_image_html_svg' ) ) :
/**
 * Obtain HTML for an SVG image given its media ID.
 *
 * @since 2.0
 *
 * @param int $thumb_id
 *
 * @return string
 */
function nice_image_html_svg( $thumb_id ) {
	require_once( ABSPATH . 'wp-admin/includes/file.php' );

	if ( 'image/svg+xml' !== get_post_mime_type( $thumb_id ) ) {
		_nice_doing_it_wrong( __FUNCTION__, esc_html__( 'This function should receive the ID of an SVG file.', 'nice-framework' ), '2.0', false );
		return null;
	}

	/**
	 * Initialize WordPress' file system handler.
	 *
	 * @var WP_Filesystem_Base $wp_filesystem
	 */
	WP_Filesystem();
	global $wp_filesystem;

	$image_url = get_nice_image_path( $thumb_id );

	$html = $wp_filesystem->get_contents( $image_url );

	return $html;
}
endif;

if ( ! function_exists( 'nice_get_post_playlists' ) ) :
/**
 * Retrieve galleries from the passed post's content.
 *
 * @since  2.0
 *
 * @param  int|WP_Post $post Optional. Post ID or object.
 * @param  bool        $html Whether to return HTML or data in the array.
 * @return array             A list of arrays, each containing gallery data and parsed sources
 *		                     from the expanded shortcode.
 */
function nice_get_post_playlists( $post, $html = true ) {
	if ( ! $post = get_post( $post ) ) {
		return array();
	}

	if ( ! has_shortcode( $post->post_content, 'playlist' ) && ! has_shortcode( $post->post_content, 'audio' ) ) {
		return array();
	}

	$playlists = array();
	if ( preg_match_all( '/' . get_shortcode_regex() . '/s', $post->post_content, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $shortcode ) {
			if ( in_array( $shortcode[2], array( 'playlist', 'audio' ) ) ) {
				$playlist = do_shortcode_tag( $shortcode );

				if ( $html ) {
					$playlists[] = $playlist;
				} else {
					$data = shortcode_parse_atts( $shortcode[3] );
					$data['is_audio'] = ( 'audio' === $shortcode[2] );
					$playlists[] = $data;
				}
			}
		}
	}

	/**
	 * Filter the list of all found galleries in the given post.
	 *
	 * @param array   $playlists Associative array of all found post galleries.
	 * @param WP_Post $post      Post object.
	 */
	return apply_filters( 'nice_get_post_playlists', $playlists, $post );
}
endif;

if ( ! function_exists( 'nice_get_post_playlist' ) ) :
/**
 * Check a specified post's content for playlist and, if present, return the first.
 *
 * @since  2.0
 *
 * @param  int|WP_Post        $post Optional. Post ID or WP_Post object. Default is global $post.
 * @param  bool               $html Whether to return HTML or data.
 *
 * @return mixed|string|array       Gallery data parsed from the expanded shortcode.
 */
function nice_get_post_playlist( $post = 0, $html = true ) {
	$playlists = nice_get_post_playlists( $post, $html );
	$playlist = reset( $playlists );

	/**
	 * Filter the first-found post gallery.
	 *
	 * @param array       $playlist   The first-found post gallery.
	 * @param int|WP_Post $post      Post ID or object.
	 * @param array       $playlists Associative array of all found post galleries.
	 */
	return apply_filters( 'nice_get_post_playlist', $playlist, $post, $playlists );
}
endif;
