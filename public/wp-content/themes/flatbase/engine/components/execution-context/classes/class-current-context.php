<?php
/**
 * NiceThemes Execution Context
 *
 * @package NiceFramework
 * @since   2.0.6
 */
namespace NiceThemes\Execution_Context;

/**
 * Class NiceThemes\Execution_Context\Current_Context
 *
 * Manage data for current execution context.
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   2.0.6
 */
class Current_Context {
	/**
	 * Check if we're in admin context.
	 *
	 * @var bool
	 */
	protected $is_admin = false;

	/**
	 * Check if we're in AJAX context.
	 *
	 * @var bool
	 */
	protected $doing_ajax = false;

	/**
	 * Check if we're in debugging mode.
	 *
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * Check if we're in development mode.
	 *
	 * @var bool
	 */
	protected $development_mode = false;

	/**
	 * Current_Context constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = array() ) {
		foreach ( $args as $key => $value ) {
			if ( property_exists( $this, $key ) ) {
				$this->{$key} = $value;
			}
		}
	}

	/**
	 * Magic method to get data from protected/private properties.
	 *
	 * @param  string $property
	 *
	 * @return mixed|null
	 */
	public function __get( $property ) {
		if ( ! property_exists( $this, $property ) ) {
			return null;
		}

		return $this->{$property};
	}
}
