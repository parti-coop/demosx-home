<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file handles the theme demo packs.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_theme_demo_packs_remote_data_url' ) ) :
add_filter( 'nice_demo_packs_remote_data_url', 'nice_theme_demo_packs_remote_data_url', 10, 2 );
/**
 * Make sure the demo packs are obtained from the correct URL.
 *
 * @since  2.0
 *
 * @param  string                   $url
 * @param  Nice_Admin_System_Status $system_status
 *
 * @return string
 */
function nice_theme_demo_packs_remote_data_url( $url, $system_status ) {
	if ( version_compare( NICE_FRAMEWORK_VERSION, '2.0.4', '<' ) ) {
		$url = $system_status->get_demo_packs_remote_url() . '/flatbase/demo-packs.json';
	}

	return $url;
}
endif;

if ( ! function_exists( 'nice_theme_register_demo_packs' ) ) :
add_filter( 'nice_theme_demo_packs', 'nice_theme_register_demo_packs' );
/**
 * Obtain a list of demo packs from remote and local sources.
 *
 * @since 2.0
 *
 * @return array
 */
function nice_theme_register_demo_packs() {
	static $demo_packs = null;

	if ( is_null( $demo_packs ) ) {
		$system_status = nice_admin_system_status();

		$demo_packs = $system_status->get_nice_theme_demo_packs();
	}

	return $demo_packs;
}
endif;


if ( ! function_exists( 'nice_theme_demo_pack_random_importing_messages' ) ) :
add_filter( 'nice_theme_demo_pack_random_importing_messages', 'nice_theme_demo_pack_random_importing_messages' );
/**
 * Add random importing messages to the demo pack installer.
 *
 * @since 2.0
 *
 * @param array $random_importing_messages
 *
 * @return array
 */
function nice_theme_demo_pack_random_importing_messages( $random_importing_messages ) {
	$system_status = nice_admin_system_status();

	$added_messages = array(
		esc_attr__( 'Thank you for choosing NiceThemes.', 'nicethemes' ),
		sprintf( esc_attr__( 'Enjoying %s? Let us know what you think!', 'nicethemes' ), $system_status->get_nice_theme_name() ),
		esc_attr__( 'We want you to have the best experience. Please let us help you if something behaves unexpectedly.', 'nicethemes' ),
		esc_attr__( 'What great things are you doing today?', 'nicethemes' ),
		esc_html__( 'It\'s a beautiful day to create, isn\'t it?', 'nicethemes' ),
	);

	// Make sure HTML entities are correctly decoded.
	foreach ( $added_messages as $k => $v ) {
		$val = html_entity_decode( $v, ENT_QUOTES | ENT_XML1, 'UTF-8' );
		$added_messages[ $k ] = $val;
	}

	$random_importing_messages = array_merge( $random_importing_messages, $added_messages );

	return $random_importing_messages;
}
endif;

if ( ! function_exists( 'nice_theme_demo_pack_bbpress_go_home_youre_drunk' ) ) :
add_action( 'after_setup_theme', 'nice_theme_demo_pack_bbpress_go_home_youre_drunk' );
/**
 * Prevent bbPress from printing out content during the importer's request.
 *
 * @since 2.0.0
 */
function nice_theme_demo_pack_bbpress_go_home_youre_drunk() {
	if ( ! nice_doing_ajax() ) {
		return;
	}

	if ( empty( $_POST['action'] ) || 'nice_theme_import_demo_pack' !== wp_unslash( $_POST['action'] ) ) {
		return;
	}

	check_ajax_referer( 'play-nice', 'nice_demo_import_nonce' );

	remove_action( 'admin_menu', 'bbp_admin_menu' );
	remove_action( 'admin_init', 'bbp_admin_init' );
	remove_action( 'admin_head', 'bbp_admin_head' );
	remove_action( 'admin_notices', 'bbp_admin_notices' );
	remove_action( 'custom_menu_order', 'bbp_admin_custom_menu_order' );
	remove_action( 'menu_order', 'bbp_admin_menu_order' );
	remove_action( 'wpmu_new_blog', 'bbp_new_site', 10, 6 );
}
endif;
