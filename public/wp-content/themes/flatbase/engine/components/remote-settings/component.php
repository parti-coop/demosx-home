<?php
/**
 * NiceFramework by NiceThemes.
 *
 * This file contains functionality that helps manage remote settings.
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2017 NiceThemes
 * @since     2.0.6
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Obtain remotely hosted settings from NiceThemes server.
 *
 * Passing `nice-remote-settings=refresh` in the query string of any page resets the transient.
 *
 * @since 2.0
 *
 * @return array
 */
function nice_remote_settings() {
	static $remote_settings = null;

	if ( is_null( $remote_settings ) ) {
		$remote_settings = get_transient( 'nice_remote_settings' );

		$force_fetch = ( isset( $_REQUEST['nice-remote-settings'] ) && ( 'refresh' === $_REQUEST['nice-remote-settings'] ) );

		if ( $force_fetch || ( false === $remote_settings ) ) {
			/**
			 * @hook nice_remote_settings
			 *
			 * Hook here if you want to use a different remote settings URL.
			 *
			 * @since 2.0
			 */
			$remote_settings_url = trailingslashit( apply_filters( 'nice_remote_settings_url', 'http://updates.nicethemes.com' ) ) . 'nice-remote-settings.json';

			$response = wp_remote_get( $remote_settings_url );

			if ( ! is_wp_error( $response ) && 300 > $response['response']['code'] && 200 <= $response['response']['code'] ) {
				$remote_settings = (array) json_decode( wp_remote_retrieve_body( $response ), true );

				set_transient( 'nice_remote_settings', $remote_settings, MONTH_IN_SECONDS );

			} else {
				$remote_settings = array();
			}
		}
	}

	return $remote_settings;
}

/**
 * Obtain a remote setting.
 *
 * @param string $setting_key
 * @param mixed  $setting_fallback_value
 *
 * @return mixed
 */
function nice_get_remote_setting( $setting_key, $setting_fallback_value = false ) {
	static $settings_values = array();

	if ( ! isset( $settings_values[ $setting_key ] ) ) {
		$remote_settings = nice_remote_settings();

		/**
		 * @hook nice_remote_setting_$setting_key
		 *
		 * Hook here to change the value of a remote setting.
		 *
		 * @since 2.0
		 */
		$settings_values[ $setting_key ] = apply_filters( "nice_remote_setting_$setting_key", isset( $remote_settings[ $setting_key ] ) ? $remote_settings[ $setting_key ] : $setting_fallback_value );
	}

	return $settings_values[ $setting_key ];
}
