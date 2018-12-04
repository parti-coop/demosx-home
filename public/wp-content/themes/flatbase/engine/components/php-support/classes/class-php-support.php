<?php
/**
 * NiceThemes Framework
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0.8
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Nice_PHP_Support
 *
 * Check if the site is using a supported PHP version. Otherwise, provide tools
 * to switch to the previous theme before ours is activated.
 *
 * This class should be compatible with the lowest possible PHP version.
 *
 * @since 2.0.8
 */
class Nice_PHP_Support {
	/**
	 * Current PHP version.
	 *
	 * @var null|string
	 */
	private $php_version = null;

	/**
	 * Minimum required PHP version.
	 *
	 * @var string
	 */
	static public $minimum_required_php_version = '5.3';

	/**
	 * Check if the current version is supported.
	 *
	 * @var bool|mixed
	 */
	private $is_version_supported = true;

	/**
	 * Nice_PHP_Support constructor.
	 */
	public function __construct() {
		/**
		 * @hook nice_before_php_support_setup
		 *
		 * Hook in here to run actions before checking for support.
		 *
		 * @since 2.0.8
		 */
		do_action( 'nice_before_php_support_setup' );

		$this->php_version          = phpversion();
		$this->is_version_supported = version_compare( $this->php_version, $this::$minimum_required_php_version, '>=' );

		if ( $this->rollback_activation() ) {
			$this->switch_theme();
			$this->setup_admin_notice();
		}

		/**
		 * @hook nice_after_php_support_setup
		 *
		 * Hook in here to run actions after checking for support.
		 *
		 * @since 2.0.8
		 */
		do_action( 'nice_after_php_support_setup' );
	}

	/**
	 * Check if theme activation should be rolled back.
	 *
	 * @return bool
	 */
	public function rollback_activation() {
		$theme_activated = isset( $_GET['activated'] ) && 'true' === wp_unslash( $_GET['activated'] );

		return $theme_activated && ! $this->is_version_supported;
	}

	/**
	 * Setup switching to the previous theme.
	 */
	private function switch_theme() {
		add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ), 10, 2 );
	}

	/**
	 * Setup deactivation notice.
	 */
	private function setup_admin_notice() {
		// Update default admin notice: Theme not activated.
		add_filter( 'gettext', array( $this, 'set_admin_notice_text' ), 10, 3 );

		// Custom styling for default admin notice.
		add_action( 'admin_head', array( $this, 'add_admin_notice_css' ) );
	}

	/**
	 * Switch back to previous theme.
	 *
	 * @param string   $old_theme_name
	 * @param stdClass $old_theme
	 */
	public function after_switch_theme( $old_theme_name, $old_theme ) {
		$old_theme_name && switch_theme( $old_theme->stylesheet );
	}

	/**
	 * Set text for admin notice.
	 *
	 * @param string $translated
	 * @param string $original
	 * @param string $domain
	 *
	 * @return string
	 */
	public function set_admin_notice_text( $translated, $original, $domain ) {
		// Strings to translate.
		$strings = array(
			'New theme activated.' => 'The theme was not activated. You are using PHP ' . $this->php_version . ', which is not supported by this theme. Please update your PHP version to at least ' . $this::$minimum_required_php_version . ', or contact your hosting provider for help. Read more about WordPress requirements <a href="https://wordpress.org/about/requirements/" target="_blank">here</a>.',
			'Visit site' => '',
		);

		if ( isset( $strings[ $original ] ) ) {
			// Translate without running all the filters again.
			$translations = get_translations_for_domain( $domain );
			$translated   = $translations->translate( $strings[ $original ] );
		}

		return $translated;
	}

	/**
	 * Add CSS for admin notice.
	 */
	function add_admin_notice_css() {
		echo '<style>#message2 { border-left-color: #dc3232; }</style>';
	}
}
