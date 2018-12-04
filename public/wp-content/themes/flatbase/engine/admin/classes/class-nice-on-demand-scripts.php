<?php
/**
 * NiceThemes Framework - On-Demand Scripts
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nice_On_Demand_Scripts' ) ) :
/**
 * Class Nice_On_Demand_Scripts
 *
 * This class allows to create an instance which main purpose is to collect an
 * array of `Nice_On_Demand_Script` objects.
 *
 * @since 2.0
 */
class Nice_On_Demand_Scripts {
	/**
	 * Collection of instances of `Nice_On_Demand_Script`
	 *
	 * @see Nice_On_Demand_Script
	 *
	 * @var array
	 */
	protected $scripts = array();

	/**
	 * Nice_On_Demand_Scripts constructor.
	 *
	 * @param array $scripts List of `Nice_On_Demand_Script` objects.
	 */
	public function __construct( array $scripts = array() ) {
		if ( empty( $scripts ) ) {
			return;
		}

		foreach ( $scripts as $script ) {
			$this->add_script( $script );
		}
	}

	/**
	 * Add a script to the list.
	 *
	 * Scripts are only added when they have no dependencies, or all its
	 * dependencies have already been registered.
	 *
	 * @param Nice_On_Demand_Script $script
	 */
	public function add_script( Nice_On_Demand_Script $script ) {
		$deps = $script->get_deps();

		if ( empty( $deps ) ) {
			$this->scripts[ $script->wp_handle ] = $script;

			return;
		}

		foreach ( $deps as $dep ) {
			if ( ! wp_script_is( $dep, 'registered' ) ) {
				return;
			}
		}

		$this->scripts[ $script->wp_handle ] = $script;
	}

	/**
	 * Remove a script from the list.
	 *
	 * @param string $wp_handle
	 */
	public function remove_script( $wp_handle ) {
		if ( ! isset( $this->scripts[ $wp_handle ] ) ) {
			return;
		}

		unset( $this->scripts[ $wp_handle ] );
	}

	/**
	 * Obtain the list of scripts.
	 *
	 * @return array
	 */
	public function get_scripts() {
		return $this->scripts;
	}
}
endif;
