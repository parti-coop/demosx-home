<?php
/**
 * NiceFramework by NiceThemes.
 *
 * This file includes functions to manage scripts for the framework and admin section.
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2016 NiceThemes
 * @since     2.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_admin_register_scripts' ) ) :
add_action( 'admin_enqueue_scripts', 'nice_admin_register_scripts' );
/**
 * Register JS files through the `nice_admin_register_scripts` action hook.
 *
 * Script registrations should be hooked here, so we can make sure everything loads at the same runtime.
 *
 * @uses admin_enqueue_scripts()
 *
 * @since 2.0
 */
function nice_admin_register_scripts( $hook ) {
	do_action( 'nice_admin_register_scripts', $hook );
}
endif;

if ( ! function_exists( 'nice_admin_scripts' ) ) :
add_action( 'nice_admin_register_scripts', 'nice_admin_scripts', 10, 1 );
/**
 * nice_admin_scripts()
 *
 * Include all the js and css for the admin section
 *
 * @since 1.0.0
 * @updated 2.0
 *
 */

function nice_admin_scripts( $hook ) {
	wp_register_script( 'nice-admin-notices', nice_get_file_uri( 'engine/admin/assets/js/nice-admin-notices.js' ), array( 'jquery' ), NICE_FRAMEWORK_VERSION, true );
	wp_enqueue_script ( 'nice-admin-notices' );
	wp_localize_script( 'nice-admin-notices', 'noticesData', array(
		'adminAjaxURL'  => admin_url() . 'admin-ajax.php',
		'playNiceNonce' => wp_create_nonce( 'play-nice' ),
	) );

	if ( nice_admin_is_framework_page() || ( 'post.php' === $hook || 'post-new.php' === $hook || 'page-new.php' === $hook || 'page.php' === $hook ) ) {

		global $wp_version, $post;

		$protocol = is_ssl() ? 'https' : 'http';

		wp_register_script( 'nice-webfont', "$protocol://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js", array( 'jquery' ), NICE_FRAMEWORK_VERSION, true );
		wp_localize_script( 'nice-webfont', 'NiceTypographyVars', array(
			'previewText' => esc_html__( 'The quick brown fox jumps over the lazy dog.', 'nice-framework' ),
			'buttonTitle' => esc_html__( 'Preview your customized typography settings', 'nice-framework' ),
		) );
		wp_register_script( 'nice-typography-preview',  nice_get_file_uri( 'engine/admin/assets/js/nice-typography-preview.js' ), array( 'jquery' ), NICE_FRAMEWORK_VERSION, true );
		wp_register_script( 'nice-framework-general',   nice_get_file_uri( 'engine/admin/assets/js/nice-general.js' ), array( 'jquery', 'wp-color-picker' ) );
		wp_register_script( 'wp-color-picker-opacity',  nice_get_file_uri( 'engine/admin/assets/js/wp-color-picker-opacity.js' ), array( 'jquery' ), NICE_FRAMEWORK_VERSION, true );
		wp_enqueue_script ( 'wp-color-picker-opacity' );
		wp_enqueue_script ( 'media-upload' );
		wp_enqueue_script ( 'thickbox' );
		wp_register_script( 'jquery-fancybox',          nice_get_file_uri( 'engine/admin/assets/js/jquery.fancybox.js' ), array( 'jquery' ), NICE_FRAMEWORK_VERSION, true );

		$current_admin_page = nice_admin_get_current_page();

		if ( ( 'demos' === $current_admin_page ) || ( 'plugins' === $current_admin_page ) ) {
			wp_register_script( 'isotope',             nice_get_file_uri( 'engine/admin/assets/js/min/isotope.pkgd.min.js' ),      array( 'jquery' ),                   NICE_FRAMEWORK_VERSION, true );
			wp_register_script( 'nice-item-browser',   nice_get_file_uri( 'engine/admin/assets/js/nice-item-browser.js' ),         array( 'jquery', 'imagesloaded', 'isotope' ), NICE_FRAMEWORK_VERSION, true );
			wp_enqueue_script ( 'imagesloaded' );
			wp_enqueue_script ( 'isotope' );
			wp_enqueue_script ( 'nice-item-browser' );

			if ( 'demos' === $current_admin_page ) {
				$system_status = nice_admin_system_status();

				wp_register_script( 'nice-demo-installer', nice_get_file_uri( 'engine/admin/assets/js/nice-demo-installer.js' ), array( 'nice-item-browser' ), NICE_FRAMEWORK_VERSION, true );
				wp_enqueue_script ( 'nice-demo-installer' );
				wp_localize_script( 'nice-demo-installer', 'generalData', array(
					'framework_version'  => NICE_FRAMEWORK_VERSION,
					'wp_version'         => $wp_version,
					'adminAjaxURL'       => admin_url() . 'admin-ajax.php',
					'playNiceNonce'      => wp_create_nonce( 'play-nice' ),
					'message_processing' => esc_html__( 'Processing...', 'nice-framework' ),
					'message_success'    => esc_html__( 'Process successful.', 'nice-framework' ),
					'message_error'      => esc_html__( 'Something went wrong.', 'nice-framework' ) . ' ' . sprintf( esc_html__( 'Please %1$sReload this page%2$s and check that you meet the requirements.', 'nice-framework' ), sprintf( '<a href="%s">', nice_admin_page_get_link( 'demos' ) ), '</a>' ),
					'installAnyway'      => esc_html__( 'Your system may need some improvement in order to import this demo site correctly. Do you want to continue anyway?', 'nice-framework' ),
					/**
					 * @hook nice_theme_demo_pack_random_importing_messages
					 *
					 * Hook here to change the random importing messages.
					 *
					 * @since 2.0
					 */
					'random_messages'    => apply_filters( 'nice_theme_demo_pack_random_importing_messages', array() ),
				) );
			}

			if ( 'plugins' === $current_admin_page ) {
				wp_register_script( 'nice-plugin-installer', nice_get_file_uri( 'engine/admin/assets/js/nice-plugin-installer.js' ), array( 'nice-item-browser' ), NICE_FRAMEWORK_VERSION, true );
				wp_enqueue_script ( 'nice-plugin-installer' );
			}
		}

		add_action       ( 'admin_head', 'nice_admin_head' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-input-mask' );
		wp_enqueue_script( 'jquery-fancybox' );
		wp_enqueue_script( 'nice-webfont' );
		wp_enqueue_script( 'nice-typography-preview' );
		wp_enqueue_script( 'nice-framework-general' );

		if ( function_exists( 'wp_enqueue_media' ) && version_compare( $wp_version, '3.5', '>=' ) ) {
			// Call for the media manager included from version 3.5
			wp_enqueue_media();
		}

		wp_localize_script( 'nice-framework-general', 'generalData', array(
			'post_id'           => isset( $post->ID ) ? $post->ID : null,
			'framework_version' => NICE_FRAMEWORK_VERSION,
			'wp_version'        => $wp_version,
			'adminAjaxURL'      => admin_url() . 'admin-ajax.php',
			'playNiceNonce'     => wp_create_nonce( 'play-nice' ),
			'add_media_title'   => esc_html__( 'Add Media','nice-framework' ),
			'use_this_file'     => esc_html__( 'Use This File','nice-framework' ),
			'upload_text'       => esc_html__( 'Use This File','nice-framework' ),
			'remove_media_text' => esc_html__( 'Remove Media', 'nice-framework' ),
			'listItemMessages'  => array(
				'addOption'           => esc_html__( 'Add new', 'nice-framework' ),
				'newOptionName'       => esc_html__( 'New Option Name', 'nice-framework' ),
				'validateOptionName'  => esc_html__( 'Please validate the name of this option', 'nice-framework' ),
				'validOptionName'     => esc_html__( 'Option name validated correctly.', 'nice-framework' ),
				'invalidOptionName'   => esc_html__( 'Option name validated correctly.', 'nice-framework' ),
				'enterOptionName'     => esc_html__( 'Please enter a name for this option.', 'nice-framework' ),
				'optionNameExists'    => esc_html__( 'There\'s already an option with that name. Please choose another one.', 'nice-framework' ),
				'confirmRemoveOption' => esc_html__( 'Are you sure you want to remove this item?', 'nice-framework' ),
			),
		) );
	}

}
endif;
