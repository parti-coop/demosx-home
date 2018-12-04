<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage navigation menus.
 *
 * @see nice_nav_menus()
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

if ( ! function_exists( 'nice_nav_menus' ) ) :
add_action( 'init', 'nice_nav_menus' );
/**
 * Register navigation menus.
 *
 * @since 1.0.0
 */
function nice_nav_menus() {

	register_nav_menu( 'navigation-menu', __( 'Navigation Menu', 'nicethemes' ) );
	register_nav_menu( 'footer-menu',     __( 'Footer Menu',     'nicethemes' ) );
}
endif;

if ( ! function_exists( 'nice_get_navigation_menus' ) ) :
/**
 * Return an array containing the navigation menus for select options.
 *
 * @since 2.0
 */
function nice_get_navigation_menus() {
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
	$menus_array = array();
	$menus_array[''] = esc_html__( 'Inherit', 'nicethemes' );

	foreach ( $menus as $menu ) {
		$menus_array[ $menu->slug ] = $menu->name;
	}

	return $menus_array;
}
endif;

if ( ! function_exists( 'nice_navigation_menu' ) ) :
add_action( 'nice_navigation_menu', 'nice_navigation_menu' );
/**
 * Print main navigation menu.
 *
 * @since 2.0
 */
function nice_navigation_menu() {
	/**
	 * @hook nice_before_navigation_menu
	 *
	 * Hook here to add HTML elements right before the menu is printed.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_before_navigation_menu' );

	$nav_class = '';

	$menu_class = is_rtl() ? 'nav fl clearfix ' : 'nav fr clearfix ';

	$menu_class .= nice_bool( nice_get_option( 'nice_header_menu_hide_arrows', false ) ) ? ' no-arrows' : '';

	$menu_class .= nice_bool( nice_get_option( 'nice_header_menu_hide_separators', false ) ) ? ' no-separators' : '';

	$menu_class .= nice_bool( nice_get_option( 'nice_header_menu_remove_border', false ) ) ? ' sub-menu-no-border' : '';

	$args = array(
		'menu'            => '',
		'container'       => 'nav',
		'container_class' => $nav_class,
		'container_id'    => 'navigation',
		'menu_class'      => $menu_class,
		'menu_id'         => 'main-nav',
		'echo'            => true,
		'fallback_cb'     => '',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 0,
		'walker'          => new Nice_Walker_Nav_Menu(),
		'theme_location'  => 'navigation-menu'
	);

	/**
	 * @hook nice_navigation_menu_args
	 *
	 * Hook in here to modify the default arguments to create the main
	 * navigation menu.
	 *
	 * @since 2.0
	 */
	$args = apply_filters( 'nice_navigation_menu_args', $args );

	// Print menu using given arguments.
	wp_nav_menu( $args );

	/**
	 * @hook nice_after_navigation_menu
	 *
	 * Hook here to add HTML elements right after the menu is printed.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_after_navigation_menu' );
}
endif;

if ( ! function_exists( 'nice_navigation_menu_start' ) ) :
add_action( 'nice_before_navigation_menu', 'nice_navigation_menu_start', 10 );
/**
 * Open wrapper tag for navigation menu.
 *
 * @since 2.0
 */
function nice_navigation_menu_start() {
?>
	<a href="#" id="toggle-nav"><i class="fa fa-bars"></i></a>

<?php
}
endif;
