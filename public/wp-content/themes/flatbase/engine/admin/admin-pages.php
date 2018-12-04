<?php
/**
 * NiceThemes Framework Admin Pages
 *
 * This file defines admin menus and pages.
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'nice_admin_set_page_menus') ) :
add_filter( 'nice_admin_page_menus', 'nice_admin_set_page_menus' );
/**
 * Define the available menus.
 *
 * @since 2.0
 *
 * @return array
 */
function nice_admin_set_page_menus() {
	$page_menus = array(
		'wp',
		'top',
		'main',
		'footer',
	);

	return $page_menus;
}
endif;


if ( ! function_exists( 'nice_admin_set_pages') ) :
add_filter( 'nice_admin_pages', 'nice_admin_set_pages' );
/**
 * Define the available pages.
 *
 * Accepted page attributes:
 * @see Nice_Admin_Page::sanitize_page()
 *
 * @since 2.0
 *
 * @return array
 */
function nice_admin_set_pages() {

	$system_status = nice_admin_system_status();
	$theme_name    = $system_status->get_nice_theme_name();

	$pages = array(
		// Theme Options (main page)
		array(
			'key'         => 'options',
			'page_title'  => esc_html__( 'Theme Options', 'nice-framework' ),
			'description' => esc_html__( 'Welcome to the Settings Panel. Here you can set up and configure all of the different options for this magnificent theme.', 'nice-framework' ),
			'menu_slug'   => 'nice-theme-options',
			'icon'        => 'dashicons dashicons-admin-settings',
			'menu'        => array(
				'wp'        => 10,
				'top'       => false,
				'main'      => 10,
				'footer'    => 10,
				'admin_bar' => 10,
			),
		),

		// Theme Options Backup
		array(
			'key'         => 'options_backup',
			'page_title'  => esc_html__( 'Import/Export Theme Options', 'nice-framework' ),
			'description' => esc_html__( 'You can import or export your Theme Options from one site to another by using the forms on this page.', 'nice-framework' ),
			'menu_slug'   => 'nice-theme-options-backup',
			'menu'        => array(
				'wp'        => false,
				'top'       => false,
				'main'      => false,
				'footer'    => false,
				'admin_bar' => false,
			),
		),

		// About
		array(
			'key'         => 'about',
			'page_title'  => sprintf( esc_html__( 'Welcome to %s', 'nice-framework' ), $theme_name ),
			'menu_title'  => esc_html__( 'Welcome', 'nice-framework' ),
			'description' => sprintf( esc_html__( '%s is now installed and ready to use! Get ready to build something beautiful. We hope you enjoy it!', 'nice-framework' ), $theme_name),
			'menu_slug'   => 'nice-theme-about',
			'icon'        => 'dashicons dashicons-admin-home',
			'menu'        => array(
				'wp'        => false,
				'top'       => 10,
				'main'      => false,
				'footer'    => false,
				'admin_bar' => false,
			),
		),

		// Getting Started
		array(
			'key'         => 'getting_started',
			'page_title'  => esc_html__( 'Getting Started', 'nice-framework' ),
			'description' => sprintf( esc_html__( 'Use the tips below to get started using %s. Your website will be up and running in no time!', 'nice-framework' ), $theme_name ),
			'menu_slug'   => 'nice-theme-getting-started',
			'icon'        => 'dashicons dashicons-welcome-learn-more',
			'menu'        => array(
				'wp'        => false,
				'top'       => 20,
				'main'      => false,
				'footer'    => false,
				'admin_bar' => false,
			),
		),

		// Product Registration
		array(
			'key'         => 'register_product',
			'page_title'  => esc_html__( 'Register your Product', 'nice-framework' ),
			'menu_title'  => esc_html__( 'Product Registration', 'nice-framework' ),
			'description' => esc_html__( 'Please register your purchase to get support and automatic theme updates. We hope you enjoy it!', 'nice-framework' ),
			'menu_slug'   => 'nice-theme-register',
			'icon'        => 'dashicons dashicons-admin-network',
			'menu'        => array(
				'wp'        => 15,
				'top'       => 30,
				'main'      => false,
				'footer'    => false,
				'admin_bar' => 15,
			),
		),

		// Support
		array(
			'key'         => 'support',
			'page_title'  => esc_html__( 'Support &amp; Updates', 'nice-framework' ),
			'description' => esc_html__( "We're here for you. How can we help you today?", 'nice-framework' ),
			'menu_slug'   => 'nice-theme-support',
			'menu'        => array(
				'wp'        => 20,
				'top'       => false,
				'main'      => 40,
				'footer'    => false,
				'admin_bar' => 20,
			),
		),

		// Theme Changelog
		array(
			'key'         => 'changelog',
			'page_title'  => sprintf( esc_html__( '%s Changelog', 'nice-framework' ), $system_status->get_nice_theme_name() ),
			'menu_title'  => esc_html__( 'Changelog', 'nice-framework' ),
			'menu_slug'   => 'nice-theme-changelog',
			'menu'        => array(
				'wp'        => false,
				'top'       => false,
				'main'      => false,
				'footer'    => 20,
				'admin_bar' => false,
			),
		),

		// Framework Changelog
		array(
			'key'         => 'changelog_framework',
			'page_title'  => sprintf( esc_html__( '%s Changelog', 'nice-framework' ), 'NiceFramework' ),
			'menu_slug'   => 'nice-theme-changelog-framework',
			'menu'        => array(
				'wp'        => false,
				'top'       => false,
				'main'      => false,
				'footer'    => false,
				'admin_bar' => false,
			),
		),

		// System Status
		array(
			'key'         => 'system_status',
			'page_title'  => esc_html__( 'System Status', 'nice-framework' ),
			'description' => esc_html__( 'Some useful information about your installation.', 'nice-framework' ),
			'menu_slug'   => 'nice-theme-system-status',
			'menu'        => array(
				'wp'        => false,
				'top'       => false,
				'main'      => false,
				'footer'    => 30,
				'admin_bar' => false,
			),
		),

	);

	/**
	 * @hook nice_admin_show_install_plugins
	 *
	 * Hook here to manually enable or disable the plugin installer.
	 *
	 * @since  2.0
	 */
	if ( apply_filters( 'nice_admin_show_install_plugins', nice_theme_requires_plugins() ) ) {
		// Install Plugins
		$pages[] = array(
			'key'         => 'plugins',
			'page_title'  => esc_html__( 'Install Plugins', 'nice-framework' ),
			'description' => esc_html__( 'Some of these plugins are required, other are recommended. You can install, update, activate or deactivate any of them from here.', 'nice-framework' ),
			'menu_slug'   => 'nice-theme-plugins',
			'icon'        => 'dashicons dashicons-admin-plugins',
			'menu'        => array(
				'wp'        => 30,
				'top'       => false,
				'main'      => 30,
				'footer'    => false,
				'admin_bar' => 30,
			),
		);
	}

	/**
	 * @hook nice_admin_show_install_demos
	 *
	 * Hook here to manually enable or disable the demo installer.
	 *
	 * @since  2.0
	 */
	if ( apply_filters( 'nice_admin_show_install_demos', nice_theme_has_demo_packs() ) ) {
		// Install Demos
		$pages[] = array(
			'key'         => 'demos',
			'menu_title'  => esc_html__( 'Install Demos', 'nice-framework' ),
			'page_title'  => esc_html__( 'Install Demo Packs', 'nice-framework' ),
			'description' => esc_html__( 'Installing a demo pack makes your site look like its preview. Instead of starting from scratch, you can edit and replace the default content with yours.', 'nice-framework' ),
			'menu_slug'   => 'nice-theme-demos',
			'icon'        => 'dashicons dashicons-layout',
			'menu'        => array(
				'wp'        => 40,
				'top'       => false,
				'main'      => 20,
				'footer'    => false,
				'admin_bar' => 40,
			),
		);
	}

	return $pages;
}
endif;
