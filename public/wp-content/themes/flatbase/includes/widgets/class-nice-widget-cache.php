<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains a class that helps managing cache for widgets.
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

if ( ! class_exists( 'Nice_Widget_Cache' ) ) :
/**
 * Nice_Widget_Cache
 *
 * Manage cache for widgets.
 *
 * @since 2.0
 */
class Nice_Widget_Cache {
	/**
	 * Internal ID for caching purposes.
	 *
	 * @since 2.0
	 * @var   string
	 */
	protected $cache_id = '';

	/**
	 * Expiration time for the widget cache.
	 *
	 * @since 2.0
	 * @var   int
	 */
	protected $expiration = 300;

	/**
	 * Indicate if cache is enabled.
	 *
	 * @since 2.0
	 * @var   bool
	 */
	protected $enabled = true;

	/**
	 * Initialize widget properties.
	 *
	 * @since 2.0
	 *
	 * @param string $cache_id
	 * @param int    $expiration
	 */
	public function __construct( $cache_id, $expiration = 300 ) {
		// Check if cache should be used.
		$enabled = ! ( defined( 'WP_DEBUG' ) && WP_DEBUG );
		$this->enabled = apply_filters( 'nice_use_widget_cache', $enabled );

		if ( $this->enabled ) {
			$this->cache_id   = $cache_id;
			$this->expiration = $expiration;
		}
	}

	/**
	 * Retrieve widget cache.
	 *
	 * @since  2.0
	 *
	 * @return string
	 */
	public function get() {
		if ( ! $this->enabled ) {
			return null;
		}

		return get_transient( $this->cache_id );
	}

	/**
	 * Save widget cache.
	 *
	 * @param mixed $data
	 */
	public function set( $data ) {
		if ( $this->enabled ) {
			return;
		}

		set_transient( $this->cache_id, $data, $this->expiration );
	}

	/**
	 * Remove widget cache.
	 *
	 * @since 2.0
	 */
	public function flush() {
		if ( ! $this->enabled ) {
			return;
		}

		delete_transient( $this->cache_id );
	}
}
endif;
