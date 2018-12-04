<?php
/**
 * NiceFramework by NiceThemes.
 *
 * This file provides an implementation of our integration with the Kirki
 * library to help managing Customizer-related functionality.
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

if ( ! function_exists( 'nice_customizer' ) ) :
add_action( 'after_setup_theme', 'nice_customizer' );
/**
 * Fire Customizer registrations.
 *
 * @since 2.0
 */
function nice_customizer() {
	// Return early if Nice Customizer is not supported.
	if ( ! get_theme_support( 'nice-customizer' ) ) {
		return;
	}

	// Try to return early if we're not in Customizer.
	if ( function_exists( 'is_customize_preview' ) && ! is_customize_preview() ) {
		return;
	}

	/**
	 * @hook nice_customizer_init
	 *
	 * Fire actions right before the Customizer is implemented.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_customizer_init' );

	$system_status = nice_admin_system_status();

	nice_loader( 'engine/admin/classes/class-nice-customizer.php' );

	$nice_customizer = new Nice_Customizer( array(
			'options'                  => nice_get_options(),
			'template'                 => nice_options_template(),
			'option_type'              => 'option',
			'option_name'              => 'nice_options',
			'config_id'                => 'nice_customizer',
			'field_map'                => nice_customizer_field_map(),
			'current_section_priority' => 999,
			'panel_id'                 => 'nicethemes_panel',
			'panel_priority'           => 10,
			'panel_name'               => sprintf( esc_html__( '%s Settings', 'nice-framework' ), $system_status->get_nice_theme_name() ),
			'panel_description'        => esc_html__( 'Configure your theme settings here', 'nice-framework' ),
		)
	);

	$nice_customizer->setup();
	$nice_customizer->register();
}
endif;

if ( ! function_exists( 'nice_customizer_field_map' ) ) :
/**
 * Obtain a map of field types for Kirki customizer.
 *
 * @since 2.0
 */
function nice_customizer_field_map() {
	/**
	 * Array pairs need to be declared with the name of our fields for the
	 * key, and the name of Kirki's fields for the value.
	 *
	 * Example:
	 *
	 * 'nicethemes' => 'kirki',
	 */
	$map = array(
		'checkbox'       => 'checkbox',
		'radio_on_off'   => 'switch',
		'select'         => 'select',
		'select_color'   => 'select',
		'select_sidebar' => 'select',
		'slider'         => 'slider',
		'color'          => 'color',
		'image'          => 'image',
		'upload'         => 'upload',
		'typography'     => 'typography',
		'radio_image'    => 'radio-image',
		'text'           => 'text',
		'textarea'       => 'textarea',
		'list_item'      => 'list-item',
	);

	return apply_filters( 'nice_customizer_field_map', $map );
}
endif;

if ( ! function_exists( 'nice_customizer_add_actions' ) ) :
add_action( 'nice_customizer', 'nice_customizer_add_actions' );
/**
 * Add actions to `nice_customizer` hook.
 *
 * @since 2.0
 *
 * @param Nice_Customizer $nice_customizer
 */
function nice_customizer_add_actions( $nice_customizer ) {
	if ( ! $nice_customizer instanceof Nice_Customizer ) {
		return;
	}

	add_action( 'customize_controls_enqueue_scripts', array( $nice_customizer, 'enqueue_styles' ) );
	add_action( 'customize_controls_enqueue_scripts', array( $nice_customizer, 'enqueue_scripts' ) );
}
endif;

if ( ! function_exists( 'nice_customizer_set_kirki_url' ) ) :
add_action( 'nice_customizer', 'nice_customizer_set_kirki_url' );
/**
 * Set the URL where Kirki is installed.
 *
 * @since 2.0
 */
function nice_customizer_set_kirki_url() {
	Kirki::$url = str_replace( 'kirki.php', '', nice_get_file_uri( 'engine/admin/lib/kirki/kirki.php' ) );
}
endif;
