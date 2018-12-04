<?php
/**
 * NiceFramework by NiceThemes.
 *
 * General functions for the framework
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2016 NiceThemes
 * @since     1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'is_admin_niceframework' ) ) :
/**
 * Check if current page is part of Nice Framework.
 *
 * @since 1.0.0
 * @updated 2.0
 *
 * This function may be eventually deprecated.
 * @see nice_admin_is_framework_page()
 *
 * @return bool
 */
function is_admin_niceframework() {

	if ( isset ( $_REQUEST['page'] ) ) {

		$page = $_REQUEST['page'];

		if ( 'nice' === substr( $page, 0, 4 ) ) {
			return true;
		}

	}

	return false;

}
endif;


if ( ! function_exists( 'is_admin_post' ) ) :
/**
 * Check if current page is a post page.
 *
 * @since 1.0.0
 * @updated 2.0
 *
 * @return bool
 */
function is_admin_post() {

	if ( is_admin_niceframework() ) {
		return true;
	}

	return false;

}
endif;


if ( ! function_exists( 'nice_set_html_att' ) ) :
/**
 * Set attributes to an HTML element.
 *
 * @since 1.0.0
 * @updated 2.0
 *
 * @return bool
 */
function nice_set_html_att( $args ) {
	// defaults
	$separator = '=';

	if ( ! is_array( $args ) ) {
		parse_str( $args, $args );
	}

	extract( $args );

	if ( $tag && $value ) :

		$regex = '/' . $tag . $separator . '"(.*?)"/';

		$new_value = $tag . $separator . '"' . $value . '"';

		if ( false !== stripos( $code, $new_value ) ) {
			$code = preg_replace( $regex , $new_value , stripslashes( $code ) );
		} else { // We're assuming here that at least one attribute was previously set for the element.
			$code = str_replace( '">', '" ' . $new_value . '>', stripslashes( $code ) );
		}

	endif;

	return $code;

}
endif;


if ( ! function_exists( 'nice_add_html_att' ) ) :
/**
 * Add attributes to an HTML element.
 *
 * @since 1.0.1
 * @updated 2.0
 *
 * @return bool
 */
function nice_add_html_att( $args ) {

	// defaults
	$separator = '=';

	if ( ! is_array( $args ) ) {
		parse_str( $args, $args );
	}

	extract( $args );

	if ( $tag && $value ) :

		$code = preg_replace( "/(<\b[^><]*)>/i", "$1 $tag$separator\"$value\">", $code );

	endif;

	return $code;

}
endif;


if ( ! function_exists( 'nice_get_html_att' ) ) :
/**
 * Get attribute from an HTML element.
 *
 * @since 1.0.1
 * @updated 2.0
 *
 * @return mixed|bool
 */
function nice_get_html_att( $args ) {

	// defaults
	$separator = '=';
	$urldecode = true;

	if ( ! is_array( $args ) ) {
		parse_str( $args, $args );
	}

	extract( $args );

	if ( $html && $tag ) :

	$r = '/' . preg_quote( $tag ) . '=([\'"])?((?(1).+?|[^\s>]+))(?(1)\1)/is' ;

	if ( preg_match ( $r, $html, $match ) ) {
		if ( ! empty( $urldecode ) ) {
			return urldecode( $match[2] );
		}

		return $match[2];
	}

	endif;

	return false;

}
endif;


if ( ! function_exists( 'nice_add_url_param' ) ) :
/**
 * Add a parameter to an URL.
 *
 * @since 1.0.1
 * @updated 2.0
 *
 * @param $args
 * @return bool|string
 */
function nice_add_url_param ( $args ) {

	if ( ! is_array( $args ) ) {
		parse_str( $args, $args );
	}

	extract( $args );

	if ( $url ) {

		$url_data = parse_url ( $url );

		if ( ! isset ( $url_data['query'] ) ) {
			$url_data['query'] = '';
		}

		$params = array();

		parse_str( $url_data['query'], $params );

		$params[ $tag ] = $value;

		$url_data['query'] = http_build_query( $params );

		return nice_build_url( $url_data );
	}

	return false;
}
endif;


if ( ! function_exists( 'nice_build_url' ) ) :
/**
 * Build an URL.
 *
 * @since 1.0.1
 * @updated 2.0
 *
 * @param $url_data
 * @return string
 */
function nice_build_url( $url_data ) {

	$url = '';

	if ( isset( $url_data['host'] ) ) {

		$url .= ! empty( $url_data['scheme'] ) ? $url_data['scheme'] . '://' : '//';

		if ( isset( $url_data['user'] ) ) {

			$url .= $url_data['user'];

			if ( isset ( $url_data['pass'] ) ) {
				$url .= ':' . $url_data['pass'];
			}

			$url .= '@';
		}

		$url .= $url_data['host'];

		if ( isset ( $url_data['port']) ) {
			$url .= ':' . $url_data['port'];
		}
	}

	$url .= $url_data['path'];

	if ( isset ( $url_data['query'] ) ) {
		$url .= '?' . $url_data['query'];
	}

	if ( isset ( $url_data['fragment'] ) ) {
		$url .= '#' . $url_data['fragment'];
	}

	return $url;
}
endif;


if ( ! function_exists( 'nice_admin_notices' ) ) :
add_action( 'admin_notices', 'nice_admin_notices' );
/**
 * Print admin notices.
 *
 * @since 1.0.0
 * @updated 2.0
 *
 * @return bool
 */
function nice_admin_notices() {
	/**
	 * @hook nice_admin_notices
	 *
	 * Hook here to add an admin notice.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_admin_notices' );
}
endif;


if ( ! function_exists( 'nice_bool' ) ) :
/**
 * Solve the bool PHP problem for strings.
 *
 * @since 1.0.1
 * @updated 2.0
 *
 * @return bool
 */
function nice_bool( $value = false ) {

	if ( is_string ( $value ) ) {

		if ( $value && ( 'false' !== strtolower( $value ) ) ) {
			return true;
		} else {
			return false;
		}

	} else {

		return ( $value ? true : false );
	}

}
endif;


if ( ! function_exists( 'nicethemes_more_themes_rss' ) ) :
/**
 * Fetch the RSS feed for themes.
 *
 * @since 1.0.2
 * @updated 2.0
 *
 * @return object
 */
function nicethemes_more_themes_rss() {
	require_once( ABSPATH . WPINC . '/feed.php' );

	$rss = fetch_feed( 'http://demo.nicethemes.com/feed/?post_type=theme' );

	if ( ! is_wp_error( $rss ) ) {
		return $rss->get_items();
	}

	return false;
}
endif;


if ( ! function_exists( 'nicethemes_theme_url' ) ) :
/**
 * Build the nicethemes.com theme URL.
 *
 * @since 1.0.2
 * @updated 2.0
 *
 * @return string
 */
function nicethemes_theme_url( $name = '' ) {
	return 'http://nicethemes.com/theme/' . trim( sanitize_title( $name ) ) . '/';
}
endif;


if ( ! function_exists( 'nice_unit_wrapper' ) ) :
/**
 * Wrap some value with a unit symbol (i.e.: $12, 29mt, 500px).
 *
 * @since 1.0.6
 * @updated 2.0
 *
 * @return string|bool
 */
function nice_unit_wrapper( $value, $symbol, $symbol_position = 'before' ) {

	if ( empty ( $symbol ) ) {
		return false;
	}

	if ( 'before' === $symbol_position ) {
		return $symbol_position . $value;
	} else {
		return $value . $value;
	}

	return false;

}
endif;


if ( ! function_exists( 'nice_css_unit' ) ) :
/**
 * Validate the CSS unit of a given value.
 *
 * @since 2.0
 *
 * @param float|string $value
 *
 * @return string
 */
function nice_css_unit( $value ) {
	$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';

	$regexr = preg_match( $pattern, $value, $matches );
	$value  = floatval( isset( $matches[1] ) ? $matches[1] : $value );
	$unit   = isset( $matches[2] ) ? $matches[2] : 'px';

	$value = $value . ( ( floatval( 0 ) !== $value ) ? $unit : '' );

	return $value;
}
endif;


if ( ! function_exists( 'nice_admin_menu_icon' ) ) :
/**
 * Get the icon path for the CPTs/etc.
 *
 * Since WP 3.8, the dashboard changed completely,
 * Different skins are available and the icons can be light or dark.
 *
 * @since 1.1.0
 * @updated 2.0
 *
 * @return string
 */
function nice_admin_menu_icon( $nice_icon = 'btn-nicethemes.png' ) {
	// if WP is higher dan 3.8 alpha and the admin color is not light, return the white icons
	if ( version_compare( $GLOBALS['wp_version'], '3.8-alpha', '>' ) && ( 'light' !== get_user_option( 'admin_color' ) ) ) {
		$icon = nice_get_file_uri( 'engine/admin/assets/images/light/' . $nice_icon );
	} else {
		$icon = nice_get_file_uri( 'engine/admin/assets/images/' . $nice_icon );
	}

	return $icon;
}

endif;


if ( ! function_exists( 'nice_remove_action_object' ) ) :
/**
 * Remove an action added with an object which isn't available to be passed as a parameter.
 *
 * @uses nice_remove_filter_object()
 *
 * @since  2.0
 *
 * @param string $tag
 * @param string $class
 * @param string $method
 * @param int    $priority
 *
 * @return bool
 */
function nice_remove_action_object( $tag, $class, $method, $priority = 10 ) {
	return nice_remove_filter_object( $tag, $class, $method, $priority );
}
endif;

if ( ! function_exists( 'nice_remove_filter' ) ) :
/**
 * Remove a filter added with an object which isn't available to be passed as a parameter.
 *
 * @since  2.0
 *
 * @param string $tag
 * @param string $class
 * @param string $method
 * @param int    $priority
 *
 * @return bool
 */
function nice_remove_filter_object( $tag, $class, $method, $priority = 10 ) {
	global $wp_version, $wp_filter;

	// Fallback to legacy function.
	if ( version_compare( $wp_version, '4.7', '<' ) ) {
		return nice_remove_legacy_filter_object( $tag, $class, $method, $priority );
	}

	if ( ! ( isset( $wp_filter[ $tag ]->callbacks[ $priority ] ) && is_array( $wp_filter[ $tag ]->callbacks[ $priority ] ) ) ) {
		return false;
	}

	foreach ( (array) $wp_filter[ $tag ]->callbacks[ $priority ] as $filter_id => $filter_data ) {
		if ( isset( $filter_data['function'] ) && is_array( $filter_data['function'] ) ) {
			if ( ( $filter_data['function'][1] === $method ) && is_object( $filter_data['function'][0] ) && ( get_class( $filter_data['function'][0] ) === $class ) ) {
				unset( $wp_filter[ $tag ]->callbacks[ $priority ][ $filter_id ] );

				return true;
			}
		}
	}

	return false;
}
endif;
/**
 * Remove a filter added with an object which isn't available to be passed as a parameter.
 *
 * Only works for WP < 4.7.
 *
 * @since  2.0
 *
 * @param string $tag
 * @param string $class
 * @param string $method
 * @param int    $priority
 *
 * @return bool
 */
function nice_remove_legacy_filter_object( $tag, $class, $method, $priority = 10 ) {
	global $wp_version, $wp_filter;

	if ( version_compare( $wp_version, '4.7', '>=' ) ) {
		return false;
	}

	if ( ! ( isset( $wp_filter[ $tag ][ $priority ] ) && is_array( $wp_filter[ $tag ][ $priority ] ) ) ) {
		return false;
	}

	foreach ( (array) $wp_filter[ $tag ][ $priority ] as $filter_id => $filter_data ) {
		if ( isset( $filter_data['function'] ) && is_array( $filter_data['function'] ) ) {
			if ( ( $filter_data['function'][1] === $method ) && is_object( $filter_data['function'][0] ) && ( get_class( $filter_data['function'][0] ) === $class ) ) {
				unset( $wp_filter[ $tag ][ $priority ][ $filter_id ] );

				return true;
			}
		}
	}

	return false;
}

if ( ! function_exists( 'nice_shortcode_tree' ) ) :
/**
 * Parse nested shortcodes into a tree hierarchy.
 *
 * @since  2.0
 *
 * @param string $shortcodes
 *
 * @return \WordPress\ShortcodeTree
 */
function nice_shortcode_tree( $shortcodes ) {
	if ( ! class_exists( '\WordPress\ShortcodeTree' ) ) {
		nice_loader( 'engine/admin/lib/wp-shortcode-tree/' );
	}

	return \WordPress\ShortcodeTree::fromString( $shortcodes );
}
endif;

/**
 * Obtain parent theme's name.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_parent_theme_name() {
	$template = wp_get_theme()->get_template();
	
	return wp_get_theme( $template )->Name;
}

/**
 * Try to obtain the actual theme name. The `_nice_theme_name` filter should be
 * hooked from theme implementation. The default value is the actual name of
 * the parent theme.
 *
 * @internal
 *
 * @since   2.0.4
 * @update  2.0.9
 *
 * @uses    wp_get_theme()
 *
 * @param   bool $force_parent Set to false to obtain the name of the current Child Theme.
 *
 * @return  string
 */
function _nice_get_theme_name( $force_parent = true ) {
	$template = $force_parent ? wp_get_theme()->get_template() : null;

	/**
	 * @hook _nice_theme_name
	 *
	 * @internal
	 *
	 * Hook in here in case you need to force the name of the current theme.
	 * This can be specially useful during update requests.
	 *
	 * Usage of this filter is not recommended for final users unless they know
	 * exactly what they're doing.
	 */
	return apply_filters( '_nice_theme_name', wp_get_theme( $template )->Name );
}

/**
 * Try to obtain the actual theme slug. The `_nice_theme_slug` filter should be
 * hooked from theme implementation. The default value is the actual name of
 * the parent theme folder.
 *
 * @internal
 *
 * @since   2.0.4
 *
 * @uses    wp_get_theme()
 *
 * @return  string
 */
function _nice_get_theme_slug() {
	/**
	 * @hook _nice_theme_slug
	 *
	 * Hook in here to force the slug of the current parent theme. This can be
	 * specially useful during update requests.
	 *
	 * Usage of this filter is not recommended for final users unless they know
	 * exactly what they're doing.
	 */
	return apply_filters( '_nice_theme_slug', wp_get_theme()->get_template() );
}

/**
 * Trigger a warning when a function or method is used incorrectly.
 *
 * @internal
 *
 * @since 2.0
 *
 * @param string $function The function that was called.
 * @param string $message  A message explaining what has been done incorrectly.
 * @param string $version  The theme or framework version where the message was added (optional).
 * @param bool   $is_theme Whether the error message is related to the theme or framework.
 */
function _nice_doing_it_wrong( $function, $message, $version = null, $is_theme = true ) {
	/**
	 * @hook nice_doing_it_wrong_run
	 *
	 * Fires when the given function is being used incorrectly.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_doing_it_wrong_run', $function, $message, $version, $is_theme );

	/**
	 * Trigger error.
	 *
	 * @since 2.0
	 */
	if ( nice_debug() && nice_development_mode() ) {
		$product_name = $is_theme ? nice_parent_theme_name() : esc_html__( 'NiceFramework', 'nice-framework' );
		$version = is_null( $version ) ? '' : sprintf( esc_html__( '(This message was added in %s version %s.)', 'nice-framework' ), $product_name, $version );
		trigger_error( sprintf( esc_html__( '%1$s was called incorrectly. %2$s %3$s', 'nice-framework' ), $function, $message, $version ), E_USER_WARNING ); // WPCS: XSS ok.
	}
}
