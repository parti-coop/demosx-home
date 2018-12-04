<?php
/**
 * Table of Contents (functions.php)
 *
 *	- nice_logo()
 *	- nice_copyright()
 *	- nice_breadcrumbs()
 *  - breadcrumb_trail_get_parents()
 *  - nice_sidebar_class()
 *  - nice_sidebar_get_class()
 *  - nice_content_class()
 *  - nice_get_content_class()
 *  - nice_container_class()
 *  - nice_get_container_class()
 *  - nice_prefix_class()
 *  - nice_parse_data_attributes()
 *  - nice_get_file_info()
 *  - nice_get_file_path()
 *  - nice_get_file_uri()
 *  - nice_minified_script_maybe_uri()
 *  - nice_color_brightness()
 *  - nice_color_hex2rgba()
 *  - nice_options_enqueue()
 *  - nice_custom_fields_enqueue()
 *  - nice_array_enqueue()
 *  - nice_array_enforce()
 *  - _nice_map_data_attributes()
 */

if ( ! function_exists( 'nice_logo' ) ) :
/**
 * Obtain HTML for the website's logo.
 *
 * @since  2.0
 *
 * @param  array $args
 *
 * @return string
 */
function nice_logo( $args = array() ) {
	// Use older function if compatibility mode is set to true.
	if ( apply_filters( 'nice_logo_compatibility', true ) ) {
		return nice_logo_compat( $args );
	}

	// Load dependencies.
	if ( ! class_exists( 'Nice_Logo_Image' ) ) {
		nice_loader( 'engine/admin/classes/class-nice-logo-image.php' );
	}
	if ( ! class_exists( 'Nice_Logo' ) ) {
		nice_loader( 'engine/admin/classes/class-nice-logo.php' );
	}

	/**
	 * @hook nice_logo_default_args
	 *
	 * Hook here to modify the default arguments for the logo.
	 */
	$defaults = apply_filters( 'nice_logo_default_args', array(
			'echo'                  => true,
			'href'                  => home_url( '/' ),
			'link'                  => null, // @deprecated
			'alt'                   => get_bloginfo( 'name' ),
			'title'                 => get_bloginfo( 'name' ),
			'tagline'               => get_bloginfo( 'description' ),
			'text_title'            => false,
			'text_tagline'          => false,
			'logo'                  => '',
			'logo_retina'           => '',
			'width'                 => '',
			'height'                => '',
			'before'                => '',
			'after'                 => '',
			'before_title'          => '<h1>',
			'after_title'           => '</h1>',
			'before_tagline'        => '<h2>',
			'after_tagline'         => '</h2>',
		)
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * @hook nice_logo_args
	 *
	 * Hook here to modify the arguments for the logo.
	 */
	$args = apply_filters( 'nice_logo_args', $args );

	/**
	 * Support `link` argument.
	 *
	 * Make sure $link argument takes precedence over $href. This will help us
	 * keep backwards compatibility for users modifying the $link argument
	 * through a filter.
	 *
	 * @since 2.0.7
	 */
	if ( ! empty( $args['link'] ) ) {
		$args['href'] = $args['link'];
	}

	if ( ! empty( $args['images'] ) && is_array( $args['images'] ) ) {
		$images = array();

		foreach ( $args['images'] as $image_args ) {
			$images[] = new Nice_Logo_Image( $image_args );
		}

		$args['images'] = $images;
	}

	if ( ! empty( $args['default_image'] ) && is_array( $args['default_image'] ) ) {
		$args['default_image'] = new Nice_Logo_Image( $args['default_image'] );
	}

	$logo = new Nice_Logo( $args );
	$logo->process_output();

	if ( nice_bool( $args['echo'] ) ) {
		$logo->print_output();
	}

	return $logo->get_output();
}
endif;

if ( ! function_exists( 'nice_copyright' ) ) :
/**
 * nice_copyright()
 *
 * The copyright function.
 *
 * @since 2.0
 *
 * @param  $args array Array of arguments.
 *
 * @return string
 */
function nice_copyright( $args = array() ) {
	/**
	 * @hook nice_copyright_default_args
	 *
	 * Hook here if you want to change the default copyright args.
	 *
	 * @since 2.0
	 */
	$defaults = apply_filters( 'nice_copyright_default_args', array(
		'echo'   => true,
		'before' => '<p>',
		'after'  => '</p>',
		'text'   => '',
	) );

	$args = wp_parse_args( $args, $defaults );

	/**
	 * @hook nice_copyright_args
	 *
	 * Hook here if you want to change the copyright args.
	 *
	 * @since 2.0
	 */
	$args = apply_filters( 'nice_copyright_args', $args );

	ob_start();

	/**
	 * @hook nice_copyright_before
	 *
	 * Hook here any code which should be processed before the copyright.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_copyright_before', $args );

	$output = $args['before'] . $args['text'] . $args['after'];

	/**
	 * @hook nice_copyright_html
	 *
	 * Hook here if you want to change the copyright HTML output.
	 *
	 * @since 2.0
	 */
	$output = apply_filters( 'nice_copyright_html', $output, $args );

	if ( nice_bool( $args['echo'] ) ) {
		echo $output; // WPCS: XSS ok.
	}

	/**
	 * @hook nice_copyright_before
	 *
	 * Hook here any code which should be processed after the copyright.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_copyright_after', $args );

	if ( true !== $args['echo'] ) {
		return $output;
	}
}
endif;


if ( ! function_exists( 'nice_breadcrumbs' ) ) :
/**
 * nice_breadcrumbs()
 *
 * Breadcrumbs, nicely displayed.
 *
 * @since 1.0.0
 *
 * @param  $args array Array of arguments.
 *
 * @return string
 */
function nice_breadcrumbs( $args = array() ) {

	global $wp_rewrite;

	/**
	 * @hook nice_breadcrumbs_default_args
	 *
	 * Hook here to change the deault breadcrumbs arguments.
	 *
	 * @since 1.0.0
	 */
	$defaults = apply_filters( 'nice_breadcrumbs_default_args', array(
		'separator'  => '/',
		'before'     => '',
		'after'      => false,
		'front_page' => true,
		'show_home'  => esc_html__( 'Home', 'nice-framework' ),
		'echo'       => true,
	) );

	if ( is_singular() ) {
		$post = get_queried_object();
		$defaults[ "singular_{$post->post_type}_taxonomy" ] = false;
	}

	$args = wp_parse_args( $args, $defaults );

	$args = apply_filters( 'nice_breadcrumbs_args', $args );

	do_action( 'nice_breadcrumbs_before', $args );

	$trail = array();
	$path = '';

	if ( ! is_front_page() && $args['show_home'] ) {
		$trail[] = '<a href="' . home_url( '/' ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home" class="trail-begin">' . $args['show_home'] . '</a>';
	}

	if ( is_singular() ) {
		$post = get_queried_object();
		$post_id = absint( get_queried_object_id() );
		$post_type = $post->post_type;
		$parent = absint( $post->post_parent );

		$post_type_object = get_post_type_object( $post_type );

		if ( 'post' === $post_type  ) {

			/* If $front has been set, add it to the $path. */
			$path .= trailingslashit($wp_rewrite->front);

			/* If there's a path, check for parents. */
			if ( ! empty( $path ) ) {
				$trail = array_merge( $trail, breadcrumb_trail_get_parents( '', $path ) );
			}

			/**
			 * Map the permalink structure tags to actual links.
			 *
			 * @note The following functionality is not available. We'll need to check if we should add it eventually.
			 *
			 * @link {https://gist.github.com/kingsidharth/2191564}
			 */
			if ( function_exists( 'breadcrumb_trail_map_rewrite_tags' ) ) {
				$trail = array_merge( $trail, breadcrumb_trail_map_rewrite_tags( $post_id, get_option( 'permalink_structure' ), $args ) );
			}

		} elseif ( 'page' !== $post_type ) {

			/* If $front has been set, add it to the $path. */
			if ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front ) {
				$path .= trailingslashit( $wp_rewrite->front );
			}

			/* If there's a slug, add it to the $path. */
			if ( ! empty( $post_type_object->rewrite['slug'] ) ) {
				$path .= $post_type_object->rewrite['slug'];
			}

			/* If there's a path, check for parents. */
			if ( ! empty( $path ) ) {
				$trail = array_merge( $trail, breadcrumb_trail_get_parents( '', $path ) );
			}

			/* If there's an archive page, add it to the trail. */
			if ( ! empty( $post_type_object->has_archive ) ) {
				$trail[] = '<a href="' . get_post_type_archive_link( $post_type ) . '" title="' . esc_attr( $post_type_object->labels->name ) . '">' . $post_type_object->labels->name . '</a>';
			}
		}

		/* If the post type path returns nothing and there is a parent, get its parents. */
		if ( ( empty( $path ) && 0 !== $parent ) || ( 'attachment' === $post_type ) ) {
			$trail = array_merge( $trail, breadcrumb_trail_get_parents( $parent, '' ) );

		/* Or, if the post type is hierarchical and there's a parent, get its parents. */
		} elseif ( 0 !== $parent && is_post_type_hierarchical( $post_type ) ) {
			$trail = array_merge( $trail, breadcrumb_trail_get_parents( $parent, '' ) );
		}

		/* Display terms for specific post type taxonomy if requested. */
		if ( ! empty( $args[ "singular_{$post_type}_taxonomy" ] ) && $terms = get_the_term_list( $post_id, $args[ "singular_{$post_type}_taxonomy" ], '', ', ', '' ) ) {
			$trail[] = $terms;
		}

		/* End with the post title. */
		$post_title = get_the_title();
		if ( ! empty( $post_title ) ) {
			$trail['trail_end'] = $post_title;
		}
	}

	$nice_breadcrumbs = '';

	if ( ! empty( $trail ) && is_array( $trail ) ) {

		/* Open the breadcrumb trail containers. */
		$nice_breadcrumbs = '<div class="breadcrumb breadcrumbs nice-breadcrumb"><div class="breadcrumb-trail">';

		/* If $before was set, wrap it in a container. */
		$nice_breadcrumbs .= ( ! empty( $args['before'] ) ? '<span class="trail-before">' . $args['before'] . '</span> ' : '' );

		/* Wrap the $trail['trail_end'] value in a container. */
		if ( ! empty( $trail['trail_end'] ) ) {
			$trail['trail_end'] = '<span class="trail-end">' . $trail['trail_end'] . '</span>';
		}

		/* Format the separator. */
		$separator = ( ! empty( $args['separator'] ) ? '<span class="sep">' . $args['separator'] . '</span>' : '<span class="sep">/</span>' );

		/* Join the individual trail items into a single string. */
		$nice_breadcrumbs .= join( " {$separator} ", $trail );

		/* If $after was set, wrap it in a container. */
		$nice_breadcrumbs .= ( ! empty( $args['after'] ) ? ' <span class="trail-after">' . $args['after'] . '</span>' : '' );

		/* Close the breadcrumb trail containers. */
		$nice_breadcrumbs .= '</div></div>';
	}

	/* Allow developers to filter the breadcrumb trail HTML. */
	$nice_breadcrumbs = apply_filters( 'breadcrumb_trail', $nice_breadcrumbs, $args );

	/* Output the breadcrumb. */
	if ( $args['echo'] ) {
		echo $nice_breadcrumbs;
	} else {
		return $nice_breadcrumbs;
	}
}
endif;


if ( ! function_exists( 'breadcrumb_trail_get_parents' ) ) :
/**
 * breadcrumb_trail_get_parents()
 *
 * @since 1.0.0
 *
 * @param $post_id
 * @param $path
 *
 * @return array
 */
function breadcrumb_trail_get_parents( $post_id = '', $path = '' ) {

	/* Set up an empty trail array. */
	$trail = array();

	/* Trim '/' off $path in case we just got a simple '/' instead of a real path. */
	$path = trim( $path, '/' );

	/* If neither a post ID nor path set, return an empty array. */
	if ( empty( $post_id ) && empty( $path ) ) {
		return $trail;
	}

	/* If the post ID is empty, use the path to get the ID. */
	if ( empty( $post_id ) ) {

		/* Get parent post by the path. */
		$parent_page = get_page_by_path( $path );

		/* If a parent post is found, set the $post_id variable to it. */
		if ( ! empty( $parent_page ) ) {
			$post_id = $parent_page->ID;
		}
	}

	/* If a post ID and path is set, search for a post by the given path. */
	if ( 0 === $post_id && ! empty( $path ) ) {

		/* Separate post names into separate paths by '/'. */
		$path = trim( $path, '/' );
		preg_match_all( '/\/.*?\z/', $path, $matches );

		/* If matches are found for the path. */
		if ( isset( $matches ) ) {

			/* Reverse the array of matches to search for posts in the proper order. */
			$matches = array_reverse( $matches );

			/* Loop through each of the path matches. */
			foreach ( $matches as $match ) {

				/* If a match is found. */
				if ( isset( $match[0] ) ) {

					/* Get the parent post by the given path. */
					$path = str_replace( $match[0], '', $path );
					$parent_page = get_page_by_path( trim( $path, '/' ) );

					/* If a parent post is found, set the $post_id and break out of the loop. */
					if ( ! empty( $parent_page ) && $parent_page->ID > 0 ) {
						$post_id = $parent_page->ID;
						break;
					}
				}
			}
		}
	}

	/* While there's a post ID, add the post link to the $parents array. */
	while ( $post_id ) {

		/* Get the post by ID. */
		$page = get_post( $post_id );

		/* Add the formatted post link to the array of parents. */
		$parents[]  = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . get_the_title( $post_id ) . '</a>';

		/* Set the parent post's parent to the post ID. */
		$post_id = $page->post_parent;
	}

	/* If we have parent posts, reverse the array to put them in the proper order for the trail. */
	if ( isset( $parents ) ) {
		$trail = array_reverse( $parents );
	}

	/* Return the trail of parent posts. */
	return $trail;
}
endif;


if ( ! function_exists( 'nice_sidebar_class' ) ) :
/**
 * Print HTML class(es) for the wrapper of a sidebar.
 *
 * @since 2.0
 *
 * @param string       $sidebar_id Unique ID of the current sidebar..
 * @param string|array $class      String or array containing custom classes.
 */
function nice_sidebar_class( $sidebar_id = 'primary', $class = '' ) {
	nice_css_classes( nice_get_sidebar_class( $sidebar_id, $class ) );
}
endif;

if ( ! function_exists( 'nice_sidebar_get_class' ) ) :
/**
 * Obtain array of HTML classes for sidebar element.
 *
 * @since  2.0
 *
 * @param  string $sidebar_id
 * @param  string $class
 *
 * @return array
 */
function nice_get_sidebar_class( $sidebar_id = 'primary', $class = '' ) {
	// Set initial classes.
	$classes = array( 'sidebar', $sidebar_id );

	// Add custom classes.
	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}

		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	return apply_filters( 'nice_sidebar_class', $classes, $sidebar_id, $class );
}
endif;

if ( ! function_exists( 'nice_content_class' ) ) :
/**
 * HTML class for post content.
 *
 * @since  2.0
 *
 * @param  string $class
 * @param  bool   $echo
 *
 * @return string
 */
function nice_content_class( $class = '', $echo = true ) {
	$output = 'class="' . join( ' ', nice_get_content_class( $class ) ) . '"';

	if ( $echo ) {
		echo $output;
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_get_content_class' ) ) :
/**
 * Obtain HTML class for post content.
 *
 * @since  2.0
 *
 * @param  string $class
 * @return string
 */
function nice_get_content_class( $class = '' ) {
	$classes = array();

	// Add custom classes.
	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}

		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	return apply_filters( 'nice_content_class', $classes, $class );
}
endif;

if ( ! function_exists( 'nice_container_class' ) ) :
/**
 * HTML class for post content.
 *
 * @since 2.0
 *
 * @param mixed|array|string $class
 */
function nice_container_class( $class = null ) {
	nice_css_classes( nice_get_container_class( $class ) );
}
endif;


if ( ! function_exists( 'nice_get_container_class' ) ) :
/**
 * Obtain HTML class for post content.
 *
 * @since  2.0
 *
 * @param  mixed|array|string $class
 *
 * @return array
 */
function nice_get_container_class( $class = null ) {
	$classes = array(
		'clearfix',
		'col-full',
	);

	// Add classes from function argument.
	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}

		$classes = array_merge( $classes, $class );
	}

	$classes = array_map( 'esc_attr', $classes );

	return apply_filters( 'nice_container_class', $classes );
}
endif;


if ( ! function_exists( 'nice_prefix_class' ) ) :
/**
 * Add a prefix to a CSS class name.
 *
 * @since  1.0.0
 *
 * @param  string  $prefix
 * @param  string  $class
 * @param  bool    $echo
 *
 * @return string
 */
function nice_prefix_class( $prefix, $class, $echo = false ) {
	$class = trim( $class );

	// Don't work with empty classes.
	if ( ! empty( $class ) ) {
		$nice_prefix = trim( str_replace( '-', '', NICE_PREFIX ) ) . '-';
		$prefix      = trim( str_replace( '-', '', $prefix ) ) . '-';

		// Always work with hyphens.
		$class = str_replace( '_', '-', $class );

		// Remove Nice prefix.
		if ( 0 === strpos( $class, $nice_prefix ) ) {
			$class = substr( $class, strlen( $nice_prefix ) ) . '';
		}

		// Add prefix, only if it's not already there.
		if ( 0 !== strpos( $class, $prefix ) ) {
			$class = $prefix . $class;
		}
	}

	// Print class name, if requested.
	if ( $echo ) {
		echo esc_attr( $class );
	}

	return $class;
}
endif;


if ( ! function_exists( 'nice_parse_data_attributes' ) ) :
/**
 * Parse HTML data attributes using and array with key and value sets.
 *
 * @since 2.0
 *
 * @param  array $data
 * @param  bool  $echo
 *
 * @return mixed|void|array
 */
function nice_parse_data_attributes( array $data = array(), $echo = true ) {
	$parsed_data = array();

	if ( ! empty( $data ) && $total = count( $data ) ) {
		$i = 0;

		foreach ( $data as $key => $value ) {
			if ( $echo ) {
				echo 'data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
				$i++;

				if ( $i < $total ) {
					echo ' ';
				}
			}

			$parsed_data[] = 'data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
	}

	if ( ! $echo ) {
		return join( ' ', $parsed_data );
	}
}
endif;

if ( ! function_exists( 'nice_get_file_info' ) ) :
/**
 * Obtain the absolute path to a file given its relative location to the theme root folder.
 *
 * This function checks if the file exists in the Child Theme by default.
 * If not, it checks inside the parent theme.
 *
 * @since  2.0
 *
 * @param  string $file Path of file relative to theme root folder.
 *
 * @return string
 */
function nice_get_file_info( $file ) {
	static $file_info = array();

	if ( ! empty( $file_info[ $file ] ) ) {
		return $file_info[ $file ];
	}

	$stylesheet_directory = get_stylesheet_directory();
	$template_directory   = get_template_directory();

	/**
	 * If we're using a child theme, search the file there first.
	 */
	if ( $stylesheet_directory !== $template_directory ) {
		$file_exists_in_child_theme = file_exists( $stylesheet_directory . '/' . $file );

		// If the file exists in the child theme, override the base URI.
		if ( $file_exists_in_child_theme ) {
			$base_path = $stylesheet_directory;
			$base_uri  = get_stylesheet_directory_uri();
		}
	}

	// Obtain base path, if not set yet.
	if ( empty( $base_path ) ) {
		$base_path = $template_directory;
	}

	// Obtain base URI, if not set yet.
	if ( empty( $base_uri ) ) {
		$base_uri = get_template_directory_uri();
	}

	$base_path = apply_filters( 'nice_base_path', $base_path, $file );
	$base_uri  = apply_filters( 'nice_base_uri', $base_uri, $file );

	$file_info[ $file ] = array(
		'base_path' => $base_path,
		'base_uri'  => $base_uri,
		'path'      => $base_path . '/' . $file,
		'uri'       => $base_uri . '/' . $file,
	);

	return $file_info[ $file ];
}
endif;


if ( ! function_exists( 'nice_get_file_path' ) ) :
/**
 * Obtain the absolute path to a file given its relative location to the theme root folder.
 *
 * This function checks if the file exists in the Child Theme by default.
 * If not, it checks inside the parent theme.
 *
 * @since  2.0
 *
 * @param  string $file Path of file relative to theme root folder.
 *
 * @return string
 */
function nice_get_file_path( $file ) {
	$file_info = nice_get_file_info( $file );

	return empty( $file_info['path'] ) ? null : $file_info['path'];
}
endif;


if ( ! function_exists( 'nice_get_file_uri' ) ) :
/**
 * Obtain the URI of a file given its relative location to the theme root folder.
 *
 * @since  2.0
 *
 * @param  string $file   Path of file relative to theme root folder.
 * @param  bool   $minify Whether to obtain a minified version of the file or not.
 *
 * @return string
 */
function nice_get_file_uri( $file, $minify = false ) {
	$file_info = nice_get_file_info( $file );

	$file_uri = empty( $file_info['uri'] ) ? null : $file_info['uri'];
	$file_uri = $minify ? nice_minified_script_maybe_uri( $file_uri ) : $file_uri;

	return $file_uri;
}
endif;


if ( ! function_exists( 'nice_minified_script_maybe_uri' ) ) :
/**
 * Obtain the URL of a (maybe) minified file.
 *
 * The criteria for the usage of minified files is the value of the `nice_use_minified_files`
 * filter. The default value for this filter is the opposite to that of the `WP_DEBUG` constant.
 * This means that, if `WP_DEBUG` is set to `true`, the non-minified files will be used instead of
 * the minified ones.
 *
 * Keep in mind, when using this function, that the file being used as parameter must exist inside
 * a folder called `js/`, which must contain another folder called `min/`. The file needs to have
 * the `.js` extension, and its minified counterpart (inside `js/min/`) needs to have `.min.js` as
 * extension. The function doesn't check for the existence of any files.
 *
 * @since 2.0
 *
 * @param  string $file_url URL of the file to be evaluated.
 * @return string
 */
function nice_minified_script_maybe_uri( $file_url ) {
	$minify_script = apply_filters( 'nice_minify_script', nice_use_minified_files(), $file_url );

	if ( $minify_script ) {
		// Modify file folder.
		$file_url = str_replace( '/js/', '/js/min/', $file_url );
		// Modify file extension.
		$file_url = str_replace( '.js', '.min.js', $file_url );
	}

	return $file_url;
}
endif;

if ( ! function_exists( 'nice_use_minified_files' ) ) :
/**
 * Check whether minified assets should be used or not.
 *
 * @since 2.0
 */
function nice_use_minified_files() {
	static $use_minified_files = null;

	if ( is_null( $use_minified_files ) ) {
		$default_value      = ! ( defined( 'WP_DEBUG' ) && WP_DEBUG );
		$use_minified_files = apply_filters( 'nice_use_minified_files', $default_value );
	}

	return $use_minified_files;
}
endif;

if ( ! function_exists( 'nice_options_enqueue' ) ) :
/**
 * Merge arrays of theme options.
 *
 * If a position is given (array key), then the splice will take
 * effect after that element.
 *
 * @since 2.0
 *
 * @param  array   $nice_options
 * @param  array   $nice_options_enqueue
 * @param  string  $position
 * @param  bool    $before
 *
 * @return array
 */
function nice_options_enqueue( &$src_options, $new_options, $position_key = null, $before = false ) {
	nice_array_enforce( $src_options );
	nice_array_enforce( $new_options );

	return nice_array_enqueue( 'id', $src_options, $new_options, $position_key, $before );
}
endif;

if ( ! function_exists( 'nice_custom_fields_enqueue' ) ) :
/**
 * Merge arrays of theme options.
 *
 * If a position is given (array key), then the splice will take
 * effect after that element.
 *
 * @since 2.0
 *
 * @param  array   $nice_options
 * @param  array   $nice_options_enqueue
 * @param  string  $position
 * @param  bool    $before
 *
 * @return array
 */
function nice_custom_fields_enqueue( &$src_fields, $new_fields, $position_key = null, $before = false ) {
	nice_array_enforce( $src_fields );
	nice_array_enforce( $new_fields );

	return nice_array_enqueue( 'name', $src_fields, $new_fields, $position_key, $before );
}
endif;

if ( ! function_exists( 'nice_array_enqueue' ) ) :
/**
 * Merge an array inside another.
 *
 * If a position is given ($position_key), then the splice will take
 * effect after that element.
 *
 * @since 2.0.8
 *
 * @param  string $src_key      Name of reference key inside source array elements.
 * @param  array  $src          Source array where new elements will be inserted (passed as reference).
 * @param  array  $new_elements New elements to be inserted inside the source array.
 * @param  string $position_key Value matching $src_key that should be used as reference point to insert new elements.
 * @param  bool   $before       Whether the new elements should be placed before the element matching $position_key.
 *
 * @return array
 */
function nice_array_enqueue( $src_key, array &$src, array $new_elements, $position_key = null, $before = false ) {
	$splice_key = null;

	if ( $position_key ) {
		foreach ( $src as $key => $option ) {
			if ( ! empty( $option[ $src_key ] ) && ( $position_key === $option[ $src_key ] ) ) {
				$splice_key = $key;
				continue;
			}
		}

		// We'll be making the splice from the next element if the `$before` flag is not set to `true`
		if ( ! $before ) {
			$splice_key++;
		}
	}

	if ( $splice_key ) {
		array_splice( $src, $splice_key, 0, $new_elements );
	} else {
		$src = array_merge( $src, $new_elements );
	}

	return $src;
}
endif;

/**
 * Force a given variable to an array.
 *
 * @since 2.0.8
 *
 * @param mixed $data Variable to be forced to an array (passed as reference).
 */
function nice_array_enforce( &$data ) {
	if ( is_null( $data ) ) {
		$data = array();
	}

	$data = (array) $data;
}

if ( ! function_exists( '_nice_map_data_attributes' ) ) :
/**
 * Map div data inside `nice_data_attributes`.
 *
 * @internal
 *
 * @since  1.0.0
 *
 * @param  string $key
 * @param  string $value
 *
 * @return string
 */
function _nice_map_data_attributes( $key, $value ) {

	if ( ! empty( $value ) ) {
		return 'data-' . $key . '="' . trim( esc_attr( $value ) ) . '"';
	}

	return '';
}
endif;

if ( ! function_exists( 'nice_on_demand_js' ) ) :
/**
 * Check if JS dependencies should be loaded on demand.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function nice_on_demand_js() {
	/**
	 * @hook nice_on_demand_js
	 *
	 * Hook here to change the default value.
	 *
	 * @since 2.0
	 */
	return apply_filters( 'nice_on_demand_js', true );
}
endif;

if ( ! function_exists( 'nice_load_async_styles' ) ) :
/**
 * Check if CSS files should be loaded asynchronously.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function nice_load_async_styles() {
	/**
	 * @hook nice_load_async_styles
	 *
	 * Hook here to change the default value.
	 *
	 * @since 2.0
	 */
	return apply_filters( 'nice_load_async_styles', true );
}
endif;

if ( ! function_exists( 'nice_on_demand_scripts' ) ) :
/**
 * Obtain a list of scripts that can be loaded on demand.
 *
 * Each element in the array should be paired as $wp_handle => $js_handle,
 * where $wp_handle is the string given as the first argument to
 * wp_register_script(), and $js_handle is a name to identify the file via
 * JavaScript.
 *
 * @since 2.0
 *
 * @return array
 */
function nice_on_demand_scripts() {
	static $js_array = null;

	if ( is_null( $js_array ) ) {
		/**
		 * @hook nice_register_on_demand_scripts
		 *
		 * Scripts to be loaded on demand should be registered at this point.
		 *
		 * Hooked here:
		 * @see nice_register_on_demand_scripts()
		 *
		 * @since 1.0.0
		 */
		do_action( 'nice_register_on_demand_scripts' );

		$js_array = array();
		$scripts  = nice_on_demand_scripts_instance()->get_scripts();

		if ( ! empty( $scripts ) ) {
			/**
			 * @var $script Nice_On_Demand_Script
			 */
			foreach ( $scripts as $script ) {
				$js_array[ $script->js_handle ] = $script->get_url();
			}
		}
	}

	return $js_array;
}
endif;

if ( ! function_exists( 'nice_on_demand_scripts_instance' ) ) :
/**
 * Obtain the current instance of Nice_On_Demand_Scripts, which contains
 * a list of registered scripts to be loaded on demand.
 *
 * @since 2.0
 *
 * @return Nice_On_Demand_Scripts
 */
function nice_on_demand_scripts_instance() {
	/**
	 * @var Nice_On_Demand_Scripts $scripts_instance
	 */
	static $scripts_instance = null;

	if ( is_null( $scripts_instance ) ) {
		if ( ! class_exists( 'Nice_On_Demand_Scripts' ) ) {
			nice_loader( 'engine/admin/classes/class-nice-on-demand-scripts.php' );
		}

		$scripts_instance = new Nice_On_Demand_Scripts();
	}

	return $scripts_instance;
}
endif;

if ( ! function_exists( 'nice_register_on_demand_script' ) ) :
/**
 * Register a script to be loaded on demand.
 *
 * @since 1.0.0
 *
 * @param string $wp_handle The same value given to wp_register_script().
 * @param string $js_handle An internal identifier to locate the file in JS.
 */
function nice_register_on_demand_script( $wp_handle, $js_handle = '' ) {
	if ( ! nice_on_demand_js() ) {
		return;
	}

	if ( ! class_exists( 'Nice_On_Demand_Script' ) ) {
		nice_loader( 'engine/admin/classes/class-nice-on-demand-script.php' );
	}

	nice_on_demand_scripts_instance()->add_script( new Nice_On_Demand_Script( $wp_handle, $js_handle ) );
}
endif;

if ( ! function_exists( 'nice_get_async_scripts' ) ) :
/**
 * Obtain a list of scripts that can be loaded asynchronously.
 *
 * @since 2.0
 */
function nice_get_async_scripts() {
	/**
	 * @hook nice_async_scripts
	 *
	 * Hook here to add or remove asynchronous scripts.
	 */
	return apply_filters( 'nice_async_scripts', array() );
}
endif;

if ( ! function_exists( 'nice_get_defer_scripts' ) ) :
/**
 * Obtain a list of scripts that can be loaded in a deferred thread.
 *
 * @since 2.0
 */
function nice_get_defer_scripts() {
	/**
	 * @hook nice_async_scripts
	 *
	 * Hook here to add or remove deferred scripts.
	 */
	return apply_filters( 'nice_defer_scripts', array() );
}
endif;

if ( ! function_exists( 'nice_get_async_styles' ) ) :
/**
 * Obtain a list of styles that can be loaded asynchronously.
 *
 * @since 2.0
 *
 * @param array $context
 *
 * @return array
 */
function nice_get_async_styles( $context = array() ) {
	/**
	 * @hook nice_async_styles
	 *
	 * Hook here to add or remove asynchronous styles.
	 */
	return apply_filters( 'nice_async_styles', array(), $context );
}
endif;
