<?php
/**
 * NiceThemes Framework Admin Template Handler
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   1.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Nice_Admin_Page_Template_Handler
 *
 * Set of methods for template management.
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   1.0
 */
class Nice_Admin_Page_Template_Handler {
	/**
	 * Output a partial template.
	 *
	 * @since  1.0
	 *
	 * @uses   Nice_Admin_Template_Handler::load_template()
	 *
	 * @param  string               $template
	 * @param  string               $part
	 * @param  bool                 $return
	 * @param  array                $extract
	 *
	 * @return mixed|null|string              String if `$return` is set to `true`.
	 */
	public static function get_template_part( $template, $part = '', $return = false, $extract = null ) {
		// Run actions before loading template.
		do_action( 'nice_admin_before_template_' . $template, $part );

		// Setup possible parts.
		$template_names = array();
		if ( ! empty( $part ) ) {
			$template_names[] = $template . '-' . $part . '.php';
		}
		$template_names[] = $template . '.php';

		$located_template = self::locate_template( $template_names );
		$located_template = apply_filters( 'nice_admin_located_template', $located_template );

		if ( $return ) {
			ob_start();
		}

		self::load_template( $located_template, $extract );

		// Run actions after loading template.
		do_action( 'nice_admin_after_template_' . $template, $part );

		if ( $return ) {
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}

		return null;
	}

	/**
	 * Load an HTML template.
	 *
	 * @since 1.0
	 *
	 * @param  string $file_path
	 * @param  array  $extract
	 * @return bool              `true` if the file could be loaded, else `false`.
	 */
	public static function load_template( $file_path, $extract = null ) {
		$file_path = apply_filters( 'nice_admin_template_path', $file_path );

		if ( file_exists( $file_path ) ) {
			if ( is_array( $extract ) ) {
				extract( $extract );
			}

			require $file_path;

			return true;
		}

		return false;
	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * @since  1.0
	 *
	 * @param  string|array $template_names  Template file(s) to search for, in order.
	 *
	 * @return string                        The template filename if one is located.
	 */
	public static function locate_template( $template_names ) {
		// Try to find a template file
		foreach ( (array) $template_names as $template_name ) {

			// Continue if template is empty
			if ( empty( $template_name ) ) {
				continue;
			}

			// Trim off any slashes from the template name.
			$template_name = ltrim( $template_name, '/' );

			// Try locating this template file by looping through the template paths.
			foreach ( self::get_theme_template_paths() as $template_path ) {
				if ( file_exists( $template_path . $template_name ) ) {
					$located = $template_path . $template_name;
					break;
				}
			}

			if ( isset( $located ) ) {
				break;
			}
		}

		return $located;
	}

	/**
	 * Returns a list of paths to check for template locations.
	 *
	 * @since 1.0
	 */
	public static function get_theme_template_paths() {
		$template_dir = self::get_theme_template_dir_name();

		$file_paths = array(
			1   => trailingslashit( get_stylesheet_directory() ) . $template_dir,
			10  => trailingslashit( get_template_directory() ) . $template_dir,
			100 => self::get_templates_dir(),
		);

		$file_paths = apply_filters( 'nice_admin_file_paths', $file_paths );

		// sort the file paths based on priority
		ksort( $file_paths, SORT_NUMERIC );

		$template_paths = array_map( 'trailingslashit', $file_paths );
		$template_paths = apply_filters( 'nice_admin_template_paths', $template_paths );

		return $template_paths;
	}

	/**
	 * Returns the path to the framework admin templates directory
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	public static function get_templates_dir() {
		// Allow by-pass.
		if ( $templates_path = apply_filters( 'nice_admin_get_templates_dir', '' ) ) {
			return trailingslashit( $templates_path );
		}

		if ( defined( 'NICE_ADMIN_TEMPLATES_PATH' ) && NICE_ADMIN_TEMPLATES_PATH ) {
			return trailingslashit( NICE_ADMIN_TEMPLATES_PATH );
		}

		$templates_path = get_theme_root() . '/' . get_template() . '/engine/admin/templates/';

		return $templates_path;
	}

	/**
	 * Returns the template directory name.
	 *
	 * Themes can filter this by using the nice_portfolio_templates_dir filter.
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	public static function get_theme_template_dir_name() {
		$templates_dir = apply_filters( 'nice_admin_templates_dir', 'includes/admin/templates' );
		return trailingslashit( $templates_dir );
	}
}
