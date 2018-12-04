<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains a utilitary class with helper methods to manage widgets.
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

if ( ! class_exists( 'Nice_Widget_Normalizer' ) ) :
/**
 * Class Nice_Widget_Normalizer
 *
 * Manage common operations for widgets.
 *
 * @since 2.0
 */
class Nice_Widget_Normalizer {
	/**
	 * Default values and sanitization functions.
	 *
	 * @since 2.0
	 * @var   array
	 */
	protected $instance_defaults = array();

	/**
	 * Default/fallback values for widget instance.
	 *
	 * @since 2.0
	 * @var   array
	 */
	private $default_instance = array();

	/**
	 * Sanitization functions for instance settings.
	 *
	 * @since 2.0
	 * @var   array
	 */
	private $instance_sanitizers = array();

	/**
	 * Initialize object.
	 *
	 * @since 2.0
	 *
	 * @param array $instance_defaults
	 */
	public function __construct( $instance_defaults = array() ) {
		$this->instance_defaults = $instance_defaults;
		$this->set_default_instance();
		$this->set_instance_sanitizers();
	}

	/**
	 * Initialize default widget instance.
	 *
	 * @since 2.0
	 */
	private function set_default_instance() {
		$default_instance = array();

		if ( ! empty( $this->instance_defaults ) ) {
			foreach ( $this->instance_defaults as $key => $value ) {
				if ( ! isset( $value[0] ) ) {
					continue;
				}

				$default_instance[ $key ] = $value[0];
			}
		}

		$this->default_instance = $default_instance;
	}

	/**
	 * Initialize settings sanitizers.
	 *
	 * @since 2.0
	 */
	private function set_instance_sanitizers() {
		$instance_sanitizers = array();

		if ( ! empty( $this->instance_defaults ) ) {
			foreach ( $this->instance_defaults as $key => $value ) {
				if ( ! isset( $value[1] ) ) {
					continue;
				}

				$instance_sanitizers[ $key ] = $value[1];
			}
		}

		$this->instance_sanitizers = $instance_sanitizers;
	}

	/**
	 * Normalize a given widget instance using the default one.
	 *
	 * @since  2.0
	 *
	 * @param  array $instance
	 * @return array
	 */
	function normalize_instance( $instance = array() ) {
		$defaults = $this->default_instance;
		$instance = wp_parse_args( $instance, $defaults );

		foreach ( $instance as $key => $value ) {
			if ( ! empty( $this->instance_sanitizers[ $key ] )
			     && function_exists( $this->instance_sanitizers[ $key ] )
			) { // Sanitize with specific function if provided.
				$value = call_user_func( $this->instance_sanitizers[ $key ], $value );
			} else { // Sanitize with fallback function.
				$value = esc_attr( $value );
			}

			$instance[ $key ] = $value;
		}

		return $instance;
	}
}
endif;
