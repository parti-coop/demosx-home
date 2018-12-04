<?php
/**
 * NiceThemes Framework Admin Page functions
 *
 * This file contains general functions that allow interactions with
 * this helper in an easier way.
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nice_admin_page_obtain_instance' ) ) :
/**
 * Instantiate the `Nice_Admin_Page` class.
 *
 * @since 2.0
 */
function nice_admin_page_obtain_instance() {
	static $admin_page = null;

	if ( is_null( $admin_page ) ) {
		// Make sure we have the needed dependencies, just in case.
		if ( ! function_exists( 'nice_theme_requires_plugins' ) ) {
			nice_loader( 'engine/admin/plugin.php' );
		}

		if ( ! function_exists( 'nice_theme_has_demo_packs' ) ) {
			nice_loader( 'engine/admin/demo.php' );
		}

		$admin_page = new Nice_Admin_Page();
	}

	return $admin_page;
}
endif;


if ( ! function_exists( 'nice_admin_get_current_page') ) :
/**
 * Obtain the key of the current admin page.
 *
 * @since 2.0
 *
 * @return bool|string
 */
function nice_admin_get_current_page() {
	$admin_page = nice_admin_page_obtain_instance();

	return $admin_page->get_page_key();
}
endif;


if ( ! function_exists( 'nice_admin_is_framework_page') ) :
/**
 * Obtain whether or not the current page is an admin page.
 *
 * @since 2.0
 *
 * @return bool
 */
function nice_admin_is_framework_page() {
	$admin_page = nice_admin_page_obtain_instance();

	return $admin_page->is_admin_page();
}
endif;


if ( ! function_exists( 'nice_admin_get_menu_pages') ) :
/**
 * Obtain a list of admin pages keys.
 *
 * @since 2.0
 *
 * @param string $menu
 *
 * @return array
 */
function nice_admin_get_menu_pages( $menu = 'wp' ) {
	static $menus = array();

	if ( ! isset( $menus[ $menu ] ) ) {
		$admin_page = nice_admin_page_obtain_instance();

		$menus[ $menu ] = array_keys( $admin_page->get_menu_pages( $menu ) );
	}

	return $menus[ $menu ];
}
endif;


if ( ! function_exists( 'nice_admin_page_get_page_title') ) :
/**
 * Obtain an admin page attribute from its key.
 *
 * If no key is given, it tries to get it from the current page.
 *
 * @since 2.0
 *
 * @param string $key
 *
 * @return string
 */
function nice_admin_page_get_page_title( $key = '' ) {
	$admin_page = nice_admin_page_obtain_instance();

	$title = $admin_page->get_page_attribute( 'page_title', $key );

	return $title ? $title : '';
}
endif;


if ( ! function_exists( 'nice_admin_page_get_description') ) :
/**
 * Obtain an admin page description from its key.
 *
 * If no key is given, it tries to get it from the current page.
 *
 * @since 2.0
 *
 * @param string $key
 *
 * @return string
 */
function nice_admin_page_get_description( $key = '' ) {
	$admin_page = nice_admin_page_obtain_instance();

	$description = $admin_page->get_page_attribute( 'description', $key );

	return $description ? $description : '';
}
endif;


if ( ! function_exists( 'nice_admin_page_get_menu_title') ) :
/**
 * Obtain an admin page menu title from its key.
 *
 * If no key is given, it tries to get it from the current page.
 *
 * @since 2.0
 *
 * @param string $key
 *
 * @return string
 */
function nice_admin_page_get_menu_title( $key = '' ) {
	$admin_page = nice_admin_page_obtain_instance();

	$menu_title = $admin_page->get_page_attribute( 'menu_title', $key );

	return $menu_title ? $menu_title : '';
}
endif;


if ( ! function_exists( 'nice_admin_page_get_icon') ) :
/**
 * Obtain an admin page icon from its key.
 *
 * If no key is given, it tries to get it from the current page.
 *
 * @since 2.0
 *
 * @param string $key
 *
 * @return string
 */
function nice_admin_page_get_icon( $key = '' ) {
	$admin_page = nice_admin_page_obtain_instance();

	$icon = $admin_page->get_page_attribute( 'icon', $key );

	return $icon ? $icon : '';
}
endif;


if ( ! function_exists( 'nice_admin_page_get_menu_slug') ) :
/**
 * Obtain an admin page menu_slug from its key.
 *
 * If no key is given, it tries to get it from the current page.
 *
 * @since 2.0
 *
 * @param string $key
 *
 * @return string
 */
function nice_admin_page_get_menu_slug( $key = '' ) {
	$admin_page = nice_admin_page_obtain_instance();

	$menu_slug = $admin_page->get_page_attribute( 'menu_slug', $key );

	return $menu_slug ? $menu_slug : '';
}
endif;


if ( ! function_exists( 'nice_admin_page_get_link') ) :
/**
 * Obtain an admin page link from its key.
 *
 * If no key is given, it tries to get it from the current page.
 *
 * @since 2.0
 *
 * @param string $key
 *
 * @return string
 */
function nice_admin_page_get_link( $key = '' ) {
	$admin_page = nice_admin_page_obtain_instance();

	$link = $admin_page->get_page_link( $key );

	if ( $link ) {
		return $link;
	}

	return '';
}
endif;

if ( ! function_exists( 'nice_admin_page_get_main_link') ) :
/**
 * Obtain an admin page link for the main page.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_admin_page_get_main_link() {
	$admin_page = nice_admin_page_obtain_instance();

	$link = $admin_page->get_main_page_link();

	if ( $link ) {
		return $link;
	}

	return '';
}
endif;


if ( ! function_exists( 'nice_admin_page_get_hookname') ) :
/**
 * Obtain an admin page hookname from its key.
 *
 * If no key is given, it tries to get it from the current page.
 *
 * @since 2.0
 *
 * @param string $key
 *
 * @return string
 */
function nice_admin_page_get_hookname( $key = '' ) {
	$admin_page = nice_admin_page_obtain_instance();

	$hookname = $admin_page->get_page_hookname( $key );

	if ( $hookname ) {
		return $hookname;
	}

	return '';
}
endif;

if ( ! function_exists( 'nice_admin_page_get_main_hookname') ) :
/**
 * Obtain an admin page hookname for the main page.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_admin_page_get_main_hookname() {
	$admin_page = nice_admin_page_obtain_instance();

	$hookname = $admin_page->get_main_page_hookname();

	if ( $hookname ) {
		return $hookname;
	}

	return '';
}
endif;


if ( ! function_exists( 'nice_admin_show_wp_notices') ) :
/**
 * Whether to show or not WordPress' notices in admin pages.
 *
 * @since 2.0
 *
 * @return bool
 */
function nice_admin_show_wp_notices() {
	static $show_wp_notices = null;

	if ( is_null( $show_wp_notices ) ) {
		/**
		 * @hook nice_admin_show_wp_notices
		 *
		 * Hook here to show or hide WordPress' notices in admin pages.
		 *
		 * @since 2.0
		 */
		$show_wp_notices = apply_filters( 'nice_admin_show_wp_notices', false );
	}

	return $show_wp_notices;
}
endif;

if ( ! function_exists( 'nice_admin_show_wp_notices_third_parties') ) :
/**
 * Whether to show or not WordPress' notices by third parties in admin pages.
 *
 * @since 2.0
 *
 * @return bool
 */
function nice_admin_show_wp_notices_third_parties() {
	static $show_wp_notices_third_parties = null;

	if ( is_null( $show_wp_notices_third_parties ) ) {
		/**
		 * @hook nice_admin_show_wp_notices_third_parties
		 *
		 * Hook here to show or hide WordPress' notices by third parties in admin pages.
		 *
		 * @since 2.0
		 */
		$show_wp_notices_third_parties = apply_filters( 'nice_admin_show_wp_notices_third_parties', false );
	}

	return $show_wp_notices_third_parties;
}
endif;


if ( ! function_exists( 'nice_admin_get_wp_notices') ) :
/**
 * Obtain captured WordPress' notices.
 *
 * @since 2.0
 *
 * @return string
 */
function nice_admin_get_wp_notices() {
	static $wp_notices = null;

	if ( is_null( $wp_notices ) ) {
		$admin_page = nice_admin_page_obtain_instance();

		$wp_notices = $admin_page->get_wp_notices();
	}

	return $wp_notices;
}
endif;


if ( ! function_exists( 'nice_admin_print_wp_notices') ) :
/**
 * Print captured WordPress' notices.
 *
 * @uses nice_admin_get_wp_notices()
 *
 * @since 2.0
 */
function nice_admin_print_wp_notices() {
	echo nice_admin_get_wp_notices();
}
endif;
