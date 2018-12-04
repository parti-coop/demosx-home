<?php
/**
 * NiceThemes Framework Admin Template functions
 *
 * This file contains general functions that allow interactions with
 * this helper in an easier way.
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output a partial template.
 *
 * @since  2.0
 *
 * @uses   Nice_Admin_Page_Template_Handler::get_template_part()
 *
 * @param  string            $template
 * @param  string            $part
 * @param  bool              $return
 * @param  array             $extract
 *
 * @return mixed|null|string           String if `$return` is set to `true`.
 */
function nice_admin_get_template_part( $template, $part = '', $return = false, $extract = null ) {
	$template_part = Nice_Admin_Page_Template_Handler::get_template_part(
		$template, $part, $return, $extract
	);

	if ( $return ) {
		return $template_part;
	}

	return null;
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * @uses   Nice_Admin_Page_Template_Handler::locate_template()
 *
 * @since  2.0
 *
 * @param  string|array $template_names  Template file(s) to search for, in order.
 * @param  bool         $load            If true the template file will be loaded if it is found.
 * @param  bool         $require_once    Whether to require_once or require. Default true.
 *                                       Has no effect if $load is false.
 *
 * @return string                        The template filename if one is located.
 */
function nice_admin_locate_template( $template_names, $load = false, $require_once = true ) {
	return Nice_Admin_Page_Template_Handler::locate_template( $template_names, $load, $require_once );
}

/**
 * Load an HTML template for the admin-facing side of the plugin.
 *
 * @uses   Nice_Admin_Template_Handler::load_template(
 * @since  2.0
 *
 * @param  string $file_path
 * @param  array  $extract
 *
 * @return bool              `true` if the file could be loaded, else `false`.
 */
function nice_admin_load_template( $file_path, $extract = null ) {
	return Nice_Admin_Page_Template_Handler::load_template( $file_path, $extract );
}
