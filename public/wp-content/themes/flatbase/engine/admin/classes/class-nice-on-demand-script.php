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

if ( ! class_exists( 'Nice_On_Demand_Script' ) ) :
/**
 * Class Nice_On_Demand_Script
 *
 * This class allows to create instances that represent JavaScript files to be
 * loaded on demand via an AJAX call only when needed.
 *
 * Important: Files registered using this class need to be registered
 * previously using `wp_register_script()`.
 *
 * @see wp_register_script()
 *
 * @since 2.0
 */
class Nice_On_Demand_Script {
	/**
	 * Handle used by WordPress to identify the script. It should be the same
	 * as the one given to `wp_register_script()` when setting up the file.
	 *
	 * @var string
	 */
	public $wp_handle = '';

	/**
	 * Custom handle used to identify the file during a JavaScript process.
	 *
	 * @var string
	 */
	public $js_handle = '';

	/**
	 * URL of the file.
	 *
	 * @var string
	 */
	private $url = '';

	/**
	 * Script dependencies.
	 *
	 * @var array
	 */
	private $deps = array();

	/**
	 * Nice_On_Demand_Script constructor.
	 *
	 * @param string $wp_handle Handle used by WordPress to identify the script.
	 * @param string $js_handle Custom handle used to identify the file during a JavaScript process.
	 */
	public function __construct( $wp_handle, $js_handle ) {
		// Throw an error if the file is not previously registered.
		if ( ! wp_script_is( $wp_handle, 'registered' ) ) {
			_nice_doing_it_wrong( __METHOD__, sprintf( esc_html__( 'The %s script should be registered before setting it to load on demand.', 'nice-framework' ), $wp_handle ), '2.0', false );

			return;
		}

		$this->wp_handle = $wp_handle;
		$this->js_handle = $js_handle;
		$this->url       = $this->get_url();
		$this->deps      = $this->get_deps();
	}

	/**
	 * Obtain the URL of the current script.
	 *
	 * @return string
	 */
	public function get_url() {
		global $wp_scripts;

		if ( ! $this->url ) {
			$this->url = $wp_scripts->registered[ $this->wp_handle ]->src;
		}

		return $this->url;
	}

	/**
	 * Obtain dependencies for the current script.
	 *
	 * @return array
	 */
	public function get_deps() {
		global $wp_scripts;

		if ( ! $this->deps ) {
			$this->deps = $wp_scripts->registered[ $this->wp_handle ]->deps;
		}

		return $this->deps;
	}
}
endif;
