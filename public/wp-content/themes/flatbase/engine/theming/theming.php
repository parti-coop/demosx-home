<?php
/**
 * Table of Contents (theming.php)
 *
 *	- nice_register_styles()
 *	- nice_register_scripts()
 *	- nice_register_on_demand_scripts()
 *	- nice_dequeue_on_demand_scripts()
 *  - nice_setup_async_scripts()
 *  - nice_setup_defer_scripts()
 *  - nice_setup_async_styles()
 *	- nice_wp_footer()
 *	- nice_tracking_code()
 *	- nice_twitter_script()
 *	- nice_wp_head()
 *	- nice_favicon()
 *  - nice_render_title_tag()
 *	- nice_custom_css()
 *	- nice_custom_js()
 *	- nice_excerpt()
 *	- nice_load_textdomain()
 *	- nice_sidebar_position_class()
 *	- nice_browser_class()
 *	- nice_custom_font_css()
 *	- nice_blog_url()
 *	- nice_add_version_meta()
 *  - nice_user_ip()
 *  - nice_upload_mimes()
 *  - nice_sidebars_init()
 *  - nice_sidebar_do()
 *  - nice_opengraph_for_posts()
 */

if ( ! function_exists( 'nice_register_styles' ) ) :
add_action( 'wp_enqueue_scripts', 'nice_register_styles' );
/**
 * Register CSS styles through the `nice_register_styles` action hook.
 *
 * Styles should be hooked here, so we can make sure everything loads at the same runtime.
 *
 * @since 2.0
 */
function nice_register_styles() {
	do_action( 'nice_register_styles', get_template_directory_uri() );
}
endif;

if ( ! function_exists( 'nice_register_scripts' ) ) :
add_action( 'wp_enqueue_scripts', 'nice_register_scripts' );
/**
 * Register JS files through the `nice_register_scripts` action hook.
 *
 * Script registrations should be hooked here, so we can make sure everything loads at the same runtime.
 *
 * @uses wp_register_script()
 * @uses wp_enqueue_script()
 *
 * @since 2.0
 */
function nice_register_scripts() {
	if ( ! is_admin() ) {
		do_action( 'nice_register_scripts', get_template_directory_uri() );
	}
}
endif;

if ( ! function_exists( 'nice_register_on_demand_scripts' ) ) :
add_action( 'nice_register_on_demand_scripts', 'nice_register_on_demand_scripts' );
/**
 * Register scripts to be loaded on demand.
 *
 * @since 2.0
 *
 * @return mixed|null|array
 */
function nice_register_on_demand_scripts() {
	static $scripts = null;

	if ( is_null( $scripts ) ) {
		/**
		 * @hook nice_on_demand_scripts
		 *
		 * Hook here to modify the list of scripts that need to be loaded on demand.
		 *
		 * @since 2.0
		 */
		$scripts = apply_filters( 'nice_on_demand_scripts', array() );

		if ( is_array( $scripts ) && ! empty( $scripts ) ) {
			foreach ( $scripts as $wp_handler => $js_handler ) {
				nice_register_on_demand_script( $wp_handler, $js_handler );
			}
		}
	}

	return $scripts;
}
endif;

if ( ! function_exists( 'nice_dequeue_on_demand_scripts' ) ) :
add_action( 'wp_print_scripts', 'nice_dequeue_on_demand_scripts', -1000 );
add_action( 'wp_print_footer_scripts', 'nice_dequeue_on_demand_scripts', -1000 );
/**
 * Dequeue all scripts that need to be loaded on-demand, so they are not
 * printed in the HTML.
 *
 * @since 2.0
 */
function nice_dequeue_on_demand_scripts() {
	$on_demand_scripts = nice_on_demand_scripts_instance()->get_scripts();

	if ( empty( $on_demand_scripts ) ) {
		return;
	}

	global $wp_scripts;

	$dequeued = array();

	/**
	 * @var Nice_On_Demand_Script $script
	 */
	foreach ( $on_demand_scripts as $script ) {
		if ( wp_script_is( $script->wp_handle, 'enqueued' ) ) {
			wp_dequeue_script( $script->wp_handle );

			$dequeued[] = $script->wp_handle;

			/**
			 * @var _WP_Dependency $wp_script
			 *
			 * Make sure to remove on-demand scripts used as dependencies, so
			 * WordPress doesn't print them.
			 *
			 * This is a bit ugly, but there isn't an efficient way to deal
			 * with the issue right now.
			 */
			foreach ( $wp_scripts->registered as $wp_script ) {
				$key = array_search( $script->wp_handle, $wp_script->deps, true );

				if ( false === $key ) {
					continue;
				}

				if ( in_array( $script->wp_handle, $wp_script->deps, true ) ) {
					$dequeued[] = $wp_script->deps[ $key ];
					unset( $wp_script->deps[ $key ] );
				}
			}

			/**
			 * Make sure dependencies for on-demand scripts are still being
			 * enqueued even after dequeueing the script.
			 */
			foreach ( $script->get_deps() as $dep ) {
				if ( ! wp_script_is( $dep, 'enqueued' ) && ! in_array( $dep, $dequeued, true ) ) {
					wp_enqueue_script( $dep );
				}
			}
		}
	}
};
endif;

if ( ! function_exists( 'nice_setup_async_scripts' ) ) :
add_filter( 'script_loader_tag', 'nice_setup_async_scripts', 10, 2 );
/**
 * Add "async" attribute to script tag for asynchronous scripts.
 *
 * @since  2.0
 *
 * @param  string $tag
 * @param  string $handle
 *
 * @return string
 */
function nice_setup_async_scripts( $tag, $handle ) {
	$scripts = nice_on_demand_js() ? nice_get_async_scripts() : array();

	if ( empty( $scripts ) ) {
		// Remove filter, so it won't fire again.
		remove_filter( 'script_loader_tag', 'nice_setup_async_scripts', 10 );

		return $tag;
	}

	if ( in_array( $handle, $scripts, true ) ) {
		$tag = str_replace( 'src=', 'async="async" src=', $tag );
	}

	return $tag;
};
endif;

if ( ! function_exists( 'nice_setup_defer_scripts' ) ) :
add_filter( 'script_loader_tag', 'nice_setup_defer_scripts', 10, 2 );
/**
 * Add "async" attribute to script tag for deferred scripts.
 *
 * @since  2.0
 *
 * @param  string $tag
 * @param  string $handle
 *
 * @return string
 */
function nice_setup_defer_scripts( $tag, $handle ) {
	$scripts = nice_on_demand_js() ? nice_get_defer_scripts() : array();

	if ( empty( $scripts ) ) {
		// Remove filter, so it won't fire again.
		remove_filter( 'script_loader_tag', 'nice_setup_defer_scripts', 10 );

		return $tag;
	}

	if ( in_array( $handle, $scripts, true ) ) {
		$tag = str_replace( 'src=', 'defer="defer" src=', $tag );
	}

	return $tag;
};
endif;

if ( ! function_exists( 'nice_async_styles_tag' ) ) :
add_filter( 'style_loader_tag', 'nice_async_styles_tag', 10, 4 );
/**
 * Add "async" attribute to script tag for asynchronous styles.
 *
 * This will only work if loading of styles on demand is disabled.
 *
 * @since  2.0
 *
 * @param  string $tag
 * @param  string $handle
 * @param  string $href
 * @param  string $media
 *
 * @return string
 */
function nice_async_styles_tag( $tag, $handle, $href, $media ) {
	$styles = nice_get_async_styles( array(
			'tag'    => $tag,
			'handle' => $handle,
			'href'   => $href,
			'media'  => $media,
		)
	);

	if ( empty( $styles ) ) {
		// Remove filter, so it won't fire again.
		remove_filter( 'style_loader_tag', 'nice_setup_async_styles', 10 );

		return $tag;
	}

	if ( in_array( $handle, $styles, true ) && $href ) {
		$tag  = str_replace( "media='$media'", "media='none' onload='media=\"$media\"'", $tag );
		$tag .= "<noscript><link rel='stylesheet' href='$href' type='text/css' media='$media' /></noscript>\n";
	}

	return $tag;
};
endif;

if ( ! function_exists( 'nice_wp_footer' ) ) :
add_action( 'wp_footer', 'nice_wp_footer' );
/**
 * nice_wp_footer()
 *
 * include all the functions to be triggered
 * when wp_footer is invoked.
 *
 * @since 1.0.0
 */
function nice_wp_footer() {
	if ( function_exists( 'nice_tracking_code' ) ) {
		nice_tracking_code();
	}
}
endif;

if ( ! function_exists( 'nice_tracking_code' ) ) :
/**
 * Print tracking code.
 *
 * @uses  nice_get_option()
 *
 * @since 1.0.0
 */
function nice_tracking_code() {
	$tracking_code_option = nice_get_option( 'nice_tracking_code' );
	$str = $tracking_code_option ? $tracking_code_option : get_option( 'nice_tracking_code' );

	if ( ! empty( $str ) && apply_filters( 'nice_do_tracking_code', true ) ) {
		echo stripslashes( $str ) . "\n";
	}
}
endif;


if ( ! function_exists( 'nice_twitter_script' ) ) :
/**
 * nice_twitter_script()
 *
 * get twitter scripts to include latest tweets.
 *
 * @since 1.0.0
 * @deprecated 1.0.6
 */
function nice_twitter_script ( $id, $username, $limit ) {
	?>

	<script type="text/javascript">
	function relative_time(time_value) {
		var values = time_value.split(" ");
		time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
		var parsed_date = Date.parse(time_value);
		var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
		var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
		delta = delta + (relative_to.getTimezoneOffset() * 60);
		if (delta < 60) {
		return 'less than a minute ago';
		} else if(delta < 120) {
		return 'about a minute ago';
		} else if(delta < (60*60)) {
		return (parseInt(delta / 60)).toString() + ' <?php esc_js( esc_html__( 'minutes ago', 'nice-framework' ) ); ?>';
		} else if(delta < (120*60)) {
		return 'about an hour ago';
		} else if(delta < (24*60*60)) {
		return 'about ' + (parseInt(delta / 3600)).toString() + ' <?php esc_js( esc_html__( 'hours ago', 'nice-framework' ) ); ?>';
		} else if(delta < (48*60*60)) {
		return '1 day ago';
		} else {
		return (parseInt(delta / 86400)).toString() + ' <?php esc_js( esc_html__( 'days ago', 'nice-framework' ) ); ?>';
		}
	}

	function NiceTwitterCallback(twitters) {
	var statusHTML = [];
	for (var i=0; i<twitters.length; i++){
	var username = twitters[i].user.screen_name;
	var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
	return '<a href="'+url+'">'+url+'</a>';
	}).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
	return reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
	});
	statusHTML.push( '<li><span>'+status+'</span> <a style="font-size:85%" href="http://twitter.com/'+username+'/statuses/'+twitters[i].id_str+'">'+relative_time(twitters[i].created_at)+'</a></li>' );
	}
	document.getElementById( 'twitter_update_list_<?php echo esc_js( $id ); ?>' ).innerHTML = statusHTML.join( '' );
	}
	</script>

	<script type="text/javascript" src="https://api.twitter.com/1/statuses/user_timeline.json?id=<?php echo esc_js( $username ); ?>&amp;callback=NiceTwitterCallback&amp;count=<?php echo esc_js( $limit ); ?>"></script>
	<?php
}
endif;


if ( ! function_exists( 'nice_wp_head' ) ) :
add_action( 'wp_head', 'nice_wp_head', 10 );
/**
 * nice_wp_head()
 *
 * include all the functions to be triggered
 * when wp_head is invoked.
 *
 * @since 1.0.0
 * @updated 1.0.2
 * @updated 2.0
 */
function nice_wp_head() {

	if ( function_exists( 'nice_favicon' ) ) {
		nice_favicon();
	}

	if ( function_exists( 'nice_render_title_tag' ) ) {
		nice_render_title_tag();
	}

}

endif;


if ( ! function_exists( 'nice_favicon' ) ) :
/**
 * nice_favicon()
 *
 * print tracking favicon, from nice_options
 *
 * @since 1.0.0
 */
function nice_favicon() {
	$favicon_option = nice_get_option( 'nice_favicon' );
	$favicon = $favicon_option ? $favicon_option : get_option( 'nice_favicon' );

	if ( $favicon ) {
		echo "\n<!-- Custom Favicon -->\n";
		echo '<link rel="shortcut icon" href="' . esc_url( $favicon ) . '"/>' . "\n";
	}
}
endif;


if ( ! function_exists( 'nice_render_title_tag' ) ) :
/**
 * Display title tag.
 *
 * This function is provided to ensure backwards compatibility for WP < 4.1,
 * where `title-tag` theme support is not available.
 *
 * @see _wp_render_title_tag()
 * @see wp_get_document_title()
 *
 * @since 2.0
 */
function nice_render_title_tag() {
	if ( function_exists( '_wp_render_title_tag' ) && current_theme_supports( 'title-tag' ) ) {
		return;
	}
	?>
		<title><?php wp_title( '&laquo;', true, 'right' ); ?></title>
	<?php
}
endif;


if ( ! function_exists( 'nice_skin_css' ) ) :
add_action( 'nice_register_styles', 'nice_skin_css', 9999 );
/**
 * nice_skin_css()
 *
 * @since 1.0.0
 * @updated 2.0
 */
function nice_skin_css() {
	$skin = get_option( 'nice_skin_stylesheet' );

	if ( ! empty( $skin ) ) {
		wp_register_style( 'nice-skin', get_stylesheet_directory_uri() . '/skins/' . $skin );
		wp_enqueue_style ( 'nice-skin' );
	}
}
endif;


if ( ! function_exists( 'nice_custom_css' ) ) :
add_action( 'nice_register_styles', 'nice_custom_css', 9999 );
/**
 * Include custom.css file.
 *
 * @since 1.0.0
 * @updated 2.0
 */
function nice_custom_css() {
	if ( file_exists( get_stylesheet_directory() . '/custom.css' ) ) {
		wp_register_style( 'nice-custom', get_stylesheet_directory_uri() . '/custom.css' );
		wp_enqueue_style ( 'nice-custom' );
	}
}
endif;

if ( ! function_exists( 'nice_custom_css_inline_styles' ) ) :
add_filter( 'nice_inline_styles', 'nice_custom_css_inline_styles', 9999 );
/**
 * Include the custom CSS from the options field.
 *
 * @uses  nice_get_option()
 *
 * @since 2.0
 *
 * @param string $inline_styles
 *
 * @return string
 */
function nice_custom_css_inline_styles( $inline_styles ) {
	$custom_css_setting = nice_get_option( 'nice_custom_css' );
	$custom_css         = $custom_css_setting ? $custom_css_setting : get_option( 'nice_custom_css' );

	if ( ! empty( $custom_css ) && is_string( $custom_css ) ) {
		$custom_css = strip_tags( '/* Custom CSS */' . "\n\n" . $custom_css );
		$inline_styles .= "\n" . stripslashes( $custom_css );
	}

	return $inline_styles;
}
endif;


if ( ! function_exists( 'nice_custom_js' ) ) :
add_action( 'nice_register_scripts', 'nice_custom_js', 9999 );
/**
 * nice_custom_js()
 *
 * Print custom JavaScript from Options and include custom.js if exists.
 *
 * @since 1.0.2
 * @updated 2.0
 */
function nice_custom_js() {
	// Obtain custom JS from the options field.
	$custom_js_setting = nice_get_option( 'nice_custom_js' );
	$custom_js         = $custom_js_setting ? $custom_js_setting : get_option( 'nice_custom_js' );

	// Load `custom.js` file, if available.
	if ( file_exists( nice_get_file_path( 'custom.js' ) ) ) {
		$custom_handler = 'nice-custom-scripts';

		wp_register_script( $custom_handler, nice_get_file_uri( 'custom.js' ), array( 'jquery' ), false, true );
		wp_enqueue_script ( $custom_handler );
	}

	// Add or print the custom JS from the options field.
	if ( ! empty( $custom_js ) && is_string( $custom_js ) ) {
		if ( ! empty( $custom_handler ) ) {
			wp_add_inline_script( $custom_handler, $custom_js );

		} else {
			$html  = "\n<!-- Custom JS (inline) -->\n";
			$html .= "<script type=\"text/javascript\">\n" . $custom_js . "</script>\n\n";
			echo stripslashes( $html );
		}
	}
}
endif;


if ( ! function_exists( 'nice_excerpt' ) ) :
/**
 * nice_excerpt()
 *
 * shorten excerpts as you want.
 *
 * @since 1.0.0
 *
 * @param int $length of excerpt
 *
 * @return string
 */
function nice_excerpt( $length = 200 ) {
	global $post;

	/**
	 * @hook nice_excerpt_length
	 *
	 * Re-adjust the length of the excerpt here.
	 *
	 * @since 2.0.6
	 */
	$length = apply_filters( 'nice_excerpt_length', $length );

	$nice_excerpt = substr( get_the_excerpt(), 0, $length ); // truncate excerpt according to $len

	if ( strlen( $nice_excerpt ) < strlen( get_the_excerpt() ) ) {
		$nice_excerpt = $nice_excerpt . '...';
	}

	echo wpautop( esc_html( $nice_excerpt ) ) . "\n";
}

endif;


/**
 * WP image support.
 */
if ( function_exists( 'add_theme_support' ) ) {

	if ( function_exists( 'add_image_size' ) ) {
		add_theme_support( 'post-thumbnails' );
	}

	add_theme_support( 'automatic-feed-links' );
}


if ( ! function_exists( 'nice_comment_reply' ) ) :
add_action( 'wp_print_scripts', 'nice_comment_reply', 10 );
/**
 * nice_comment_reply()
 *
 * Comments reply.
 *
 * @since 1.0.0
 */
function nice_comment_reply() {
	if ( is_singular() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;


if ( ! function_exists( 'nice_load_textdomain' ) ) :
add_action( 'init', 'nice_load_textdomain', 0 );
/**
 * nice_load_textdomain()
 *
 * load textdomain
 *
 * @since 1.0.0
 * @updated 2.0
 */
function nice_load_textdomain() {
	$system_status = nice_admin_system_status();

	/**
	 * Load framework textdomain.
	 */
	load_theme_textdomain( 'nice-framework', $system_status->get_nice_framework_path() . '/languages' );

	$nice_theme_textdomain = apply_filters( 'nice_theme_textdomain', 'nicethemes' );

	/**
	 * Load theme textdomain.
	 */
	$loaded = load_theme_textdomain( $nice_theme_textdomain, $system_status->get_nice_theme_path() . '/languages' );

	// Try with the old folder as a fallback.
	if ( ! $loaded ) {
		load_theme_textdomain( $nice_theme_textdomain, $system_status->get_nice_theme_path() . '/lang' );
	}
}

endif;


if ( ! function_exists( 'nice_sidebar_position_class' ) ) :
add_filter( 'body_class', 'nice_sidebar_position_class' );
/**
 * nice_sidebar_position_class()
 *
 * set bodyclass for the sidebar alignment.
 *
 * @since   1.0.1
 * @updated 2.0
 *
 * @param   array $classes
 *
 * @return  array
 */
function nice_sidebar_position_class( $classes = array() ) {
	/**
	 * @hook nice_sidebar_position
	 *
	 * Hook in here to modify the sidebar's position.
	 *
	 * @since 1.0.0
	 */
	$position = apply_filters( 'nice_sidebar_position', nice_get_option( '_sidebar_position' ) );

	switch ( $position ) {
		case 'left':
			$classes[] = 'sidebar-left';
			break;
		case 'right':
			$classes[] = 'sidebar-right';
			break;
		case 'none':
			$classes[] = 'sidebar-none';
			break;
		default:
			$classes[] = 'sidebar-right';
			break;
	}

	/**
	 * @hook nice_sidebar_position_class
	 *
	 * Hook here if you want to change the sidebar position class.
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'nice_sidebar_position_class', $classes );
}
endif;


if ( ! function_exists( 'nice_browser_class' ) ) :
add_filter( 'body_class', 'nice_browser_class' );
/**
 * nice_browser_class()
 *
 * set bodyclass for the visitor browser
 *
 * @since 1.0.1
 *
 */
function nice_browser_class( $classes ) {

	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

	if ( $is_lynx) {
		$classes[] = 'lynx';
	} elseif ( $is_gecko ) {
		$classes[] = 'gecko';
	} elseif ( $is_opera ) {
		$classes[] = 'opera';
	} elseif ( $is_NS4 ) {
		$classes[] = 'ns4';
	} elseif ( $is_safari ) {
		$classes[] = 'safari';
	} elseif ( $is_chrome ) {
		$classes[] = 'chrome';
	} elseif ( $is_IE ) {
		$classes[] = 'ie';
	} else {
		$classes[] = 'unknown';
	}

	if ( $is_iphone) {
		$classes[] = 'iphone';
	}

	return apply_filters( 'nice_browser_class', $classes );
}
endif;


if ( ! function_exists( 'nice_device_body_class' ) ) :
add_filter( 'body_class', 'nice_device_body_class' );
/**
 * nice_device_body_class()
 *
 * set bodyclass for mobile/desktop
 *
 * @since 2.0
 */
function nice_device_body_class( $classes ) {

	if ( wp_is_mobile() ) {
		$classes[] = 'mobile';
	} else {
		$classes[] = 'desktop';
	}

	return $classes;
}
endif;


if ( ! function_exists( 'nice_custom_font_css' ) ) :
/**
 * nice_custom_font_css()
 *
 * return the css for custom font option.
 *
 * @since 1.0.3
 * @updated 2.0
 *
 * @param array $option
 * @param array $args
 *      Optional. Arguments to construct the CSS code.
 *      @type array $exclude    CSS properties to be excluded (left out).
 *      @type array $include    CSS properties to be included (all others will be left out).
 *
 * @return string
 */
function nice_custom_font_css( $option, array $args = array() ) {
	$google_fonts   = nice_get_google_fonts();
	$family         = nice_get_font_family( $option );
	$style          = nice_get_font_style( $option );
	$size           = nice_get_font_size( $option );
	$letter_spacing = nice_get_font_letter_spacing( $option );

	foreach ( $google_fonts as $google_font ) {
		// if it is a Google Font, then put it between ''
		if ( $family === $google_font['name'] ) {
			$family = '\'' . $family . '\', arial, sans-serif';
		}
	}

	// Define properties and values.
	$properties = array(
		'font-family'    => stripslashes( $family ),
		'font-size'      => ( 'inherit' === $size  ) ? '' : $size,
		'font-style'     => stripos( $style, 'italic' ) !== false ? 'italic' : 'normal',
		'font-weight'    => stripos( $style, 'bold' ) !== false ? 'bold' : 'normal',
		'color'          => empty( $option['color'] ) ? '' : $option['color'] . ' !important',
		'letter-spacing' => $letter_spacing,
	);

	// Exclude requested CSS properties.
	if ( ! empty( $args['exclude'] ) && is_array( $args['exclude'] ) ) {
		$properties = array_diff_key( $properties, array_flip( $args['exclude'] ) );
	}

	// Include only requested CSS properties. Already excluded properties can't be reincluded.
	if ( ! empty( $args['include'] ) && is_array( $args['include'] ) ) {
		$properties = array_intersect_key( $properties, array_flip( $args['include'] ) );
	}

	// Construct CSS code.
	$css = '';
	foreach ( $properties as $name => $value ) {
		$css .= $value ? $name . ': '. $value . ';' : '';
	}

	return $css;
}
endif;


if ( ! function_exists( 'nice_blog_url' ) ) :
/**
 * nice_blog_url()
 *
 * return the url of the page configured for posts.
 *
 * @since 1.0.6
 */
function nice_blog_url() {

	if ( $posts_page_id = get_option( 'page_for_posts' ) ) {
		return home_url( get_page_uri( $posts_page_id ) );
	} else {
		return home_url( '/' );
	}

}
endif;


if ( ! function_exists( 'nice_add_version_meta' ) ) :
add_action( 'wp_head', 'nice_add_version_meta', 8 );
/**
 * nice_add_version_meta()
 *
 * Add generator metadata with the fw and theme version.
 *
 * @since 1.0.6
 * @updated 2.0
 */
function nice_add_version_meta() {
	$system_status = nice_admin_system_status();

	echo '<meta name="generator" content="' . esc_attr( $system_status->get_nice_theme_name() ) . ' ' . esc_attr( $system_status->get_nice_theme_version() ) .'" />' . "\n";
	echo '<meta name="generator" content="Nice Framework ' . esc_attr( $system_status->get_nice_framework_version() ) . '" />' . "\n";
}
endif;


if ( ! function_exists( 'nice_user_ip' ) ) :
/**
 * nice_user_ip()
 *
 * Get the visitor IP address
 *
 * @since 1.0.6
 */
function nice_user_ip() {
	return $_SERVER['REMOTE_ADDR'];
}
endif;


if ( ! function_exists( 'nice_upload_mimes' ) ) :
add_filter( 'upload_mimes', 'nice_upload_mimes' );
/**
 * nice_upload_mimes()
 *
 * Adds more support for file uploads in the theme.
 *
 * @since 1.1.2
 */
function nice_upload_mimes( $existing_mimes = array() ) {

	//  Add file extension 'extension' with mime type 'mime/type'
	$existing_mimes['ico'] = 'image/ico';

	// return the new full result
	return $existing_mimes;
}
endif;


if ( ! function_exists( 'nice_sidebars_init' ) ) :
add_action( 'init', 'nice_sidebars_init' );
add_action( 'nice_customizer_init', 'nice_sidebars_init', 20 );
/**
 * Register sidebars for this theme through this function.
 *
 * @since 2.0
 */
function nice_sidebars_init() {
	// Hook for registering custom sidebars.
	do_action( 'nice_register_sidebars' );
}
endif;


if ( ! function_exists( 'nice_sidebar_do' ) ) :
add_action( 'nice_sidebar', 'nice_sidebar_do' );
/**
 * Display the sidebar inside a hook.
 *
 * @since 2.0
 *
 * @param string $sidebar_id ID of the required sidebar.
 */
function nice_sidebar_do( $sidebar_id = 'primary' ) {
	$sidebar_id = apply_filters( 'nice_sidebar_id', $sidebar_id );

	if ( is_active_sidebar( $sidebar_id ) ) : ?>
		<!-- BEGIN #sidebar -->
		<aside id="sidebar" <?php nice_sidebar_class( $sidebar_id ); ?> role="complementary">
			<?php dynamic_sidebar( $sidebar_id ); ?>
		<!-- END #sidebar -->
		</aside>
	<?php endif;
}
endif;


if ( ! function_exists( 'nice_opengraph_for_posts' ) ) :
add_action( 'wp_head', 'nice_opengraph_for_posts' );
/**
 * Print the Facebook opengraph tags.
 *
 * @since 2.0
 */
function nice_opengraph_for_posts() {
	if ( is_singular() && apply_filters( 'nice_opengraph_enable', true ) ) {

		if ( ! get_post() ) {
			global $post;
			setup_postdata( $post );
		}

		$output  = '<meta property="og:type" content="article" />' . "\n";
		$output .= '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
		$output .= '<meta property="og:url" content="' . get_permalink() . '" />' . "\n";
		$output .= '<meta property="og:description" content="' . esc_attr( get_the_excerpt() ) . '" />' . "\n";

		if ( has_post_thumbnail() ) {
			$imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );
			$output .= '<meta property="og:image" content="' . $imgsrc[0] . '" />' . "\n";
		}

		/**
		 * @hook nice_opengraph_for_posts
		 *
		 * Hook here if you want to modify the open graph tags for posts.
		 *
		 * @since 2.0
		 */
		$output = apply_filters( 'nice_opengraph_for_posts', $output );

		echo $output; // WPCS: XSS Ok.
	}
}
endif;
