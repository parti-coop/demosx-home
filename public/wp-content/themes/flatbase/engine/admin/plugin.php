<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Theme plugin talk with TGMPA.
 * This file includes functions to interact with the TGMPA class
 * https://github.com/TGMPA/TGM-Plugin-Activation
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_theme_uasort_plugins' ) ) :
/**
 * Helper function to sort plugins.
 *
 * @param $a
 * @param $b
 *
 * @return int
 */
function nice_theme_uasort_plugins( $a, $b ) {
	return strcmp( $a['name'], $b['name'] );
}
endif;

if ( ! function_exists( 'nice_plugin_row_meta' ) ) :
add_filter( 'plugin_row_meta', 'nice_plugin_row_meta', 9999, 4 );
/**
 * Filter the plugin data to add some meta of our own.
 *
 * @since 2.0
 *
 * @param array  $plugin_meta
 * @param string $plugin_file
 *
 * @return array
 */
function nice_plugin_row_meta( $plugin_meta, $plugin_file ) {
	static $tgmpa_plugins = null;

	if ( is_null( $tgmpa_plugins ) ) {
		$tgmpa_plugins = wp_list_pluck( Nice_TGM_Plugin_Activation::$instance->plugins, 'file_path' );
	}

	if ( in_array( $plugin_file, $tgmpa_plugins, true ) ) {
		$plugin_meta[] = sprintf( '<a href="%s">%s</a>',
			nice_admin_page_get_link( 'plugins' ),
			esc_html__( 'Handle with NiceThemes Plugin Installer', 'nice-framework' )
		);
	}

	return $plugin_meta;
}
endif;


if ( ! function_exists( 'nice_theme_requires_plugins' ) ) :
/**
 * Obtain whether or not the current theme requires or recommends plugins.
 *
 * @since 2.0
 *
 * @return bool
 */
function nice_theme_requires_plugins() {
	$theme_plugins = nice_theme_plugins();

	return ( ! empty( $theme_plugins ) );
}
endif;

if ( ! function_exists( 'nice_theme_plugins' ) ) :
/**
 * Obtain a TGMPA-ready list of plugins to be required or recommended by the theme.
 *
 * @since 2.0
 *
 * @return array
 */
function nice_theme_plugins() {
	/**
	 * @hook nice_theme_plugins
	 *
	 * Hook here to add or remove required/recommended plugins.
	 */
	return apply_filters( 'nice_theme_plugins', array() );
}
endif;

if ( ! function_exists( 'nice_theme_tgmpa_register' ) ) :
add_action( 'tgmpa_register', 'nice_theme_tgmpa_register' );
/**
 * Register the required plugins for this theme.
 *
 * @since 2.0
 */
function nice_theme_tgmpa_register() {
	if ( ! nice_theme_requires_plugins() ) {
		return;
	}

	/**
	 * @hook nice_theme_tgmpa_config
	 *
	 * Hook here modify TGMPA's configuration.
	 */
	$config = apply_filters( 'nice_theme_tgmpa_config', array() );

	tgmpa( nice_theme_plugins(), $config );
}
endif;

if ( ! function_exists( 'nice_theme_tgmpa_custom_config' ) ) :
add_filter( 'nice_theme_tgmpa_config', 'nice_theme_tgmpa_custom_config' );
/**
 * Obtain number config array for TGMPA.
 *
 * @since  2.0
 *
 * @return array
 */
function nice_theme_tgmpa_custom_config() {
	$system_status = Nice_Admin_System_Status::obtain();

	$strings = array(
		'page_title'                      => esc_html__( 'Install and activate required plugins', 'nice-framework' ),
		'menu_title'                      => esc_html__( 'Install Plugins', 'nice-framework' ),
		'installing'                      => esc_html__( 'Installing plugin: %s', 'nice-framework' ), // %s = plugin name
		'updating'                        => esc_html__( 'Updating plugin: %s', 'nice-framework' ), // %s = plugin name
		'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'nice-framework' ),
		'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'nice-framework' ), // %1$s = plugin name(s).
		'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'nice-framework' ), // %1$s = plugin name(s)
		'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'nice-framework' ), // %1$s = plugin name(s)
		'notice_ask_to_update_maybe'      => _n_noop( 'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'nice-framework' ), // %1$s = plugin name(s)
		'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'nice-framework' ), // %1$s = plugin name(s)
		'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'nice-framework' ), // %1$s = plugin name(s)
		'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'nice-framework' ),
		'update_link' 					  => _n_noop( 'Begin updating plugin', 'Begin updating plugins', 'nice-framework' ),
		'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'nice-framework' ),
		'return'                          => esc_html__( 'Return to the plugin installer', 'nice-framework' ),
		'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'nice-framework' ),
		'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'nice-framework' ),
		'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'nice-framework' ), // %1$s = plugin name
		'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'nice-framework' ), // %s = plugin name
		'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s', 'nice-framework' ), // %1$s = dashboard link
		'dismiss'                         => esc_html__( 'Dismiss this notice', 'nice-framework' ),
		'notice_cannot_install_activate'  => esc_html__( 'There are one or more required or recommended plugins to install, update or activate.', 'nice-framework' ),
		'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'nice-framework' ),
		'nag_type'                        => '', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'
	);

	$config = array(
		'id'           => 'nicethemes_' . $system_status->get_nice_theme_slug(), // Unique ID for hashing notices for multiple instances of TGMPA.
		'has_notices'  => true,                 // Show admin notices or not.
		'dismissable'  => true,                 // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                   // If 'dismissable' is false, this message will be output at top of nag.
		'menu'         => 'nice-theme-plugins', // Menu slug.
		'parent_slug'  => 'admin.php',          // Parent slug.
		'capability'   => 'manage_options',     // Minimum capability.
		'is_automatic' => false,                // Automatically activate plugins after installation or not.
		'message'      => sprintf( esc_html__( 'In order to be fully functional, %s requires some plugins, and recommends some others. You can install, update, activate or deactivate any of them from here.', 'nice-framework' ), $system_status->get_nice_theme_name() ),  // Message to output right before the plugins table.
		'strings'      => $strings,
	);

	return $config;
}
endif;


if ( ! function_exists( 'load_tgm_plugin_activation' ) ) :
/**
* Replace TGMPA global with an instance of our own class.
*
* @since 2.0
*/
function load_tgm_plugin_activation() {
	if ( ! class_exists( 'Nice_TGM_Plugin_Activation' ) ) {
		nice_loader( 'engine/admin/classes/class-nice-tgm-plugin-activation.php' );
	}

	$GLOBALS['tgmpa'] = Nice_TGM_Plugin_Activation::get_instance();
}
endif;
