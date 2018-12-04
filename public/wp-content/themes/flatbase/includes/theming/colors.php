<?php
/**
 * Flatbase by NiceThemes.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://www.nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_theme_colors_option' ) ) :
/**
 * Obtain theme colors option.
 *
 * @since  2.0
 *
 * @return string
 */
function nice_theme_colors_option() {
	$prefix = NICE_PREFIX;

	return $prefix . '_colors';
}
endif;


if ( ! function_exists( 'nice_theme_default_colors' ) ) :
/**
 * Build the default array of colors.
 *
 * @since  2.0
 *
 * @return array
 */
function nice_theme_default_colors() {
	$nice_default_colors = array(
		array(
			'name' => esc_html__( 'Accent Color', 'nicethemes' ),
			'id'   => 'nice_accent_color',
			'std'  => array(
				'default' => '#5bc4be',
			),
		),
		array(
			'name' => esc_html__( 'Complementary Color', 'nicethemes' ),
			'id'   => 'nice_complementary_color',
			'std'  => array(
				'default' => '#35a49e',
			),
		),
		array(
			'name' => esc_html__( 'Black', 'nicethemes' ),
			'id'   => 'nice_black_color',
			'std'  => array(
				'default' => '#222',
			),
		),
		array(
			'name' => esc_html__( 'Dark 1', 'nicethemes' ),
			'id'   => 'nice_dark_color_1',
			'std'  => array(
				'default' => '#333',
			),
		),
		array(
			'name' => esc_html__( 'Dark 2', 'nicethemes' ),
			'id'   => 'nice_dark_color_2',
			'std'  => array(
				'default' => '#454545',
			),
		),
		array(
			'name' => esc_html__( 'Dark 3', 'nicethemes' ),
			'id'   => 'nice_dark_color_3',
			'std'  => array(
				'default' => '#686868',
			),
		),
		array(
			'name' => esc_html__( 'White', 'nicethemes' ),
			'id'   => 'nice_white_color',
			'std'  => array(
				'default' => '#fff',
			),
		),
		array(
			'name' => esc_html__( 'Light 1', 'nicethemes' ),
			'id'   => 'nice_light_color_1',
			'std'  => array(
				'default' => '#eee',
			),
		),
		array(
			'name' => esc_html__( 'Light 2', 'nicethemes' ),
			'id'   => 'nice_light_color_2',
			'std'  => array(
				'default' => '#ddd',
			),
		),
		array(
			'name' => esc_html__( 'Light 3', 'nicethemes' ),
			'id'   => 'nice_light_color_3',
			'std'  => array(
				'default' => '#bbb',
			),
		),
	);

	/**
	 * @hook nice_theme_default_colors
	 *
	 * Hook here to change the default colors.
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'nice_theme_default_colors', $nice_default_colors );
}
endif;


if ( ! function_exists( 'nice_theme_colors' ) ) :
/**
 * Obtain theme colors.
 *
 * @since  1.0.0
 *
 * @return array
 */
function nice_theme_colors() {
	static $colors = null;

	if ( is_null( $colors ) ) {
		$colors = array();

		$prefix        = NICE_PREFIX;
		$colors_option = $prefix . '_colors';

		$nice_colors = nice_get_option( $colors_option );

		// Double check for colors.
		if ( empty( $nice_colors ) ) {
			$default_colors = nice_theme_default_colors();

			if ( ! empty( $default_colors ) ) {
				foreach ( $default_colors as $default_color ) {
					$nice_colors[ $default_color['id'] ] = $default_color['name'];
				}
			}
		}

		if ( ! empty( $nice_colors ) ) {
			foreach ( $nice_colors as $color_id => $color_name ) {
				$colors[ $color_id ] = array(
					'name'   => $color_name,
					'id'     => $color_id,
					'css_id' => str_replace( '_', '-', str_replace( 'nice_', '', $color_id ) ),
					'option' => $color_id,
					'value'  => nice_get_option( $color_id ),
				);
			}
		}

		/**
		 * @hook nice_theme_colors
		 *
		 * Hook here to change the colors.
		 *
		 * @since 1.0.0
		 */
		$colors = apply_filters( 'nice_theme_colors', $colors );
	}

	return $colors;
}
endif;

if ( ! function_exists( 'nice_theme_color_name' ) ) :
/**
 * Obtain the name of a color. Returns false if the color does not exist.
 *
 * @since  1.0.0
 *
 * @param string $color_id
 *
 * @return bool|string
 */
function nice_theme_color_name( $color_id ) {
	$nice_colors = nice_theme_colors();

	if ( ! empty( $nice_colors[ $color_id ]['name'] ) ) {
		return $nice_colors[ $color_id ]['name'];
	}

	return false;
}
endif;

if ( ! function_exists( 'nice_theme_color_css_id' ) ) :
/**
 * Obtain the CSS ID of a color. Returns false if the color does not exist.
 *
 * @since  1.0.0
 *
 * @param string $color_id
 *
 * @return bool|string
 */
function nice_theme_color_css_id( $color_id ) {
	$nice_colors = nice_theme_colors();

	if ( ! empty( $nice_colors[ $color_id ]['css_id'] ) ) {
		return $nice_colors[ $color_id ]['css_id'];
	}

	return false;
}
endif;

if ( ! function_exists( 'nice_theme_color_option' ) ) :
/**
 * Obtain the option of a color. Returns false if the color does not exist.
 *
 * @since  1.0.0
 *
 * @param string $color_id
 *
 * @return bool|string
 */
function nice_theme_color_option( $color_id ) {
	$nice_colors = nice_theme_colors();

	if ( ! empty( $nice_colors[ $color_id ]['option'] ) ) {
		return $nice_colors[ $color_id ]['option'];
	}

	return false;
}
endif;

if ( ! function_exists( 'nice_theme_color_value' ) ) :
/**
 * Obtain the value of a color. Returns false if the color does not exist.
 *
 * @since  1.0.0
 *
 * @param string $color_id
 *
 * @return bool|string
 */
function nice_theme_color_value( $color_id ) {
	$nice_colors = nice_theme_colors();

	if ( ! empty( $nice_colors[ $color_id ]['value'] ) ) {
		return $nice_colors[ $color_id ]['value'];
	}

	return false;
}
endif;


if ( ! function_exists( 'nice_admin_color_dropdown_styles' ) ) :
add_filter( 'nice_admin_inline_styles', 'nice_admin_color_dropdown_styles' );
/**
 * Add Styles for the colors dropdown within the admin section.
 *
 * @since 1.0.0
 *
 * @param string $admin_inline_styles
 *
 * @return string
 */
function nice_admin_color_dropdown_styles( $admin_inline_styles ) {
	$nice_colors = nice_theme_colors();

	if ( ! empty( $nice_colors ) ) {
		$output = '';

		foreach ( $nice_colors as $color ) {
			$background_color = $color['value'];
			$text_color       = nice_contrast_color( $background_color );

			$output .= '.nice-select-color .' . $color['id'] . ' { background-color: ' . $background_color . '; color: ' . $text_color . '; }' . "\n";
		}

		$admin_inline_styles .= $output;
	}

	return $admin_inline_styles;
}
endif;

if ( ! function_exists( 'nice_admin_color_list_item_styles' ) ) :
add_filter( 'nice_admin_inline_styles', 'nice_admin_color_list_item_styles' );
/**
 * Add Styles for the list item colors within the admin section.
 *
 * @since 1.0.0
 *
 * @param string $admin_inline_styles
 *
 * @return string
 */
function nice_admin_color_list_item_styles( $admin_inline_styles ) {
	$nice_colors = nice_theme_colors();

	if ( ! empty( $nice_colors ) ) {
		$output = '';

		foreach ( $nice_colors as $color ) {
			$background_color = $color['value'];
			$text_color       = nice_contrast_color( $background_color );

			$output .= '.nice-framework-admin-ui-list-item .' . $color['id'] . ' { background-color: ' . $background_color . '; color: ' . $text_color . '; }' . "\n";
		}

		$admin_inline_styles .= $output;
	}

	return $admin_inline_styles;
}
endif;


if ( ! function_exists( 'nice_theme_colors_dropdown_values' ) ) :
/**
 * Return an array of the theme colors to use in the dropdown values.
 *
 * @since  1.0.0
 *
 * @return array
 */
function nice_theme_colors_dropdown_values() {
	$nice_colors = nice_theme_colors();

	$colors = array();
	$colors[''] = array(
		'name'  => esc_html__( 'Default', 'nicethemes' ),
		'value' => '',
	);

	if ( ! empty( $nice_colors ) ) {
		foreach ( $nice_colors as $color ) {
			$colors[ $color['id'] ] = array(
				'name'  => $color['name'],
				'value' => $color['value'],
			);
		}
	}

	return $colors;
}
endif;

if ( ! function_exists( 'nice_theme_colors_option_value' ) ) :
add_filter( 'nice_option_nice_colors', 'nice_theme_colors_option_value' );
/**
 * Hook into the colors array to make sure the default colors are always displayed.
 *
 * @since 2.0
 *
 * @return array
 */
function nice_theme_colors_option_value( $colors = array() ) {
	$default_colors = nice_theme_default_colors();
	$colors = empty( $colors ) ? array() : (array) $colors;

	foreach ( $default_colors as $key => $default_color ) {
		if ( ! isset( $colors[ $default_color['id'] ] ) ) {
			$colors = array_merge(
				array_slice( $colors, 0, $key ),
				array( $default_color['id'] => $default_color['name'] ),
				array_slice( $colors, $key )
			);
		}
	}

	return $colors;
}
endif;


add_action( 'after_setup_theme', '_nice_theme_migration_2_0' );
function _nice_theme_migration_2_0() {

	if ( nice_bool( get_option( '_nice_flatbase_migration_2_0_done' ) ) ) {
		return;
	}

	// Background color
	$background_color = get_option( 'nice_background_color' );

	if ( false !== strpos( $background_color, '#' ) ) { // Check hex color
		update_option( '_nice_flatbase_support_legacy_background_color', true );
	}

	// Header background color
	$header_background_color = get_option( 'nice_header_background_color' );

	if ( false !== strpos( $header_background_color, '#' ) ) { // Check hex color
		update_option( '_nice_flatbase_support_legacy_header_background_color', true );
	}

	if ( nice_bool( nice_get_option( 'nice_custom_typography' ) ) ) {

		$nice_font_body = get_option( 'nice_font_body' );

		if ( false !== strpos( $nice_font_body['color'], '#' ) ) { // Check hex color
			update_option( '_nice_flatbase_support_legacy_font_body_color', true );
		}

		// Font Nav
		$nice_font_nav = get_option( 'nice_font_nav' );

		if ( false !== strpos( $nice_font_nav['color'], '#' ) ) { // Check hex color
			update_option( '_nice_flatbase_support_legacy_font_nav_color', true );
		}

		// Font SubNav
		$nice_font_subnav = get_option( 'nice_font_subnav' );

		if ( false !== strpos( $nice_font_subnav['color'], '#' ) ) { // Check hex color
			update_option( '_nice_flatbase_support_legacy_font_subnav_color', true );
		}

		// Infobox title
		$nice_font_infobox_title = get_option( 'nice_font_infobox_title' );

		if ( false !== strpos( $nice_font_infobox_title['color'], '#' ) ) { // Check hex color
			update_option( '_nice_flatbase_support_legacy_font_infobox_title_color', true );
		}

		// Infobox Content
		$nice_font_infobox_content = get_option( 'nice_font_infobox_content' );

		if ( false !== strpos( $nice_font_infobox_content['color'], '#' ) ) { // Check hex color
			update_option( '_nice_flatbase_support_legacy_font_infobox_content_color', true );
		}

		// Live search tagline
		$nice_font_welcome_message = get_option( 'nice_font_welcome_message' );

		if ( false !== strpos( $nice_font_welcome_message['color'], '#' ) ) { // Check hex color
			update_option( '_nice_flatbase_support_legacy_font_welcome_message_color', true );
		}

		// Live search extended
		$nice_font_welcome_message_extended = get_option( 'nice_font_welcome_message_extended' );

		if ( false !== strpos( $nice_font_welcome_message_extended['color'], '#' ) ) { // Check hex color
			update_option( '_nice_flatbase_support_legacy_font_welcome_message_extended_color', true );
		}
	}

	update_option( '_nice_flatbase_migration_2_0_done', true );
}

add_filter( 'nice_theme_default_colors', 'nice_theme_add_legacy_colors' );
function nice_theme_add_legacy_colors( $colors ) {

	// Background color
	if ( nice_bool( get_option( '_nice_flatbase_support_legacy_background_color' ) ) ) {
		$colors[] = array(
			'name' => esc_html__( 'Background Color (Legacy)', 'nicethemes' ),
			'id'   => 'nice_background_legacy_color',
			'std'  => array(
				'default' => get_option( 'nice_background_color' ) ? : '#f0f0f0',
			),
		);
	}

	// Header background color
	if ( nice_bool( get_option( '_nice_flatbase_support_legacy_header_background_color' ) ) ) {
		$colors[] = array(
			'name' => esc_html__( 'Header BG Color (Legacy)', 'nicethemes' ),
			'id'   => 'nice_header_background_legacy_color',
			'std'  => array(
				'default' => get_option( 'nice_header_background_color' ) ? : '#35a49e',
			),
		);
	}
	// Check if the option for custom fonts is ON
	if ( nice_bool( nice_get_option( 'nice_custom_typography' ) ) ) {

		// Font body color
		if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_body_color' ) ) ) {

			$nice_font_body = get_option( 'nice_font_body' );
			$nice_font_body = isset( $nice_font_body['color'] ) ? $nice_font_body['color'] : '';

			$colors[] = array(
				'name' => esc_html__( 'Font Body Color (Legacy)', 'nicethemes' ),
				'id'   => 'nice_font_body_legacy_color',
				'std'  => array(
					'default' => $nice_font_body ? : '#8B989E',
				),
			);
		}

		// Font nav
		if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_nav_color' ) ) ) {

			$nice_font_nav = get_option( 'nice_font_nav' );
			$nice_font_nav = isset( $nice_font_nav['color'] ) ? $nice_font_nav['color'] : '';

			$colors[] = array(
				'name' => esc_html__( 'Font Navigation Color (Legacy)', 'nicethemes' ),
				'id'   => 'nice_font_nav_legacy_color',
				'std'  => array(
					'default' => $nice_font_nav ? : '#eff2f3',
				),
			);
		}

		// Font Sub nav
		if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_subnav_color' ) ) ) {

			$nice_font_subnav = get_option( 'nice_font_subnav' );
			$nice_font_subnav = isset( $nice_font_subnav['color'] ) ? $nice_font_subnav['color'] : '';

			$colors[] = array(
				'name' => esc_html__( 'Font Sub Navigation Color (Legacy)', 'nicethemes' ),
				'id'   => 'nice_font_subnav_legacy_color',
				'std'  => array(
					'default' => $nice_font_subnav ? : '#ffffff',
				),
			);
		}

		// Infoboxes Title
		if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_infobox_title_color' ) ) ) {

			$nice_font_infobox_title = get_option( 'nice_font_infobox_title' );
			$nice_font_infobox_title = isset( $nice_font_infobox_title['color'] ) ? $nice_font_infobox_title['color'] : '';

			$colors[] = array(
				'name' => esc_html__( 'Font Infobox Title Color (Legacy)', 'nicethemes' ),
				'id'   => 'nice_font_infobox_title_legacy_color',
				'std'  => array(
					'default' => $nice_font_infobox_title ? : '#4B4D4B',
				),
			);
		}

		// Infoboxes Content
		if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_infobox_content_color' ) ) ) {

			$nice_font_infobox_content = get_option( 'nice_font_infobox_content' );
			$nice_font_infobox_content = isset( $nice_font_infobox_content['color'] ) ? $nice_font_infobox_content['color'] : '';

			$colors[] = array(
				'name' => esc_html__( 'Font Infobox Content Color (Legacy)', 'nicethemes' ),
				'id'   => 'nice_font_infobox_content_legacy_color',
				'std'  => array(
					'default' => $nice_font_infobox_content ? : '#8B989E',
				),
			);
		}

		// Livesearch Title
		if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_welcome_message_color' ) ) ) {

			$nice_font_welcome_message = get_option( 'nice_font_welcome_message' );
			$nice_font_welcome_message = isset( $nice_font_welcome_message['color'] ) ? $nice_font_welcome_message['color'] : '';

			$colors[] = array(
				'name' => esc_html__( 'Font Live Search Title Color (Legacy)', 'nicethemes' ),
				'id'   => 'nice_font_welcome_message_legacy_color',
				'std'  => array(
					'default' => $nice_font_welcome_message ? : '#ffffff',
				),
			);
		}

		// Livesearch Extended
		if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_welcome_message_extended_color' ) ) ) {

			$nice_font_welcome_message_extended = get_option( 'nice_font_welcome_message_extended' );
			$nice_font_welcome_message_extended = isset( $nice_font_welcome_message_extended['color'] ) ? $nice_font_welcome_message_extended['color'] : '';

			$colors[] = array(
				'name' => esc_html__( 'Font Live Search Extended Color (Legacy)', 'nicethemes' ),
				'id'   => 'nice_font_welcome_message_extended_legacy_color',
				'std'  => array(
					'default' => $nice_font_welcome_message_extended ? : '#dddddd',
				),
			);
		}

	}

	return $colors;
}

add_filter( 'nice_option_nice_background_color', 'nice_theme_background_color' );
function nice_theme_background_color( $value ) {

	if ( false !== strpos( $value, '#' ) && nice_bool( get_option( '_nice_flatbase_support_legacy_background_color' ) ) ) {
		$value = 'nice_background_legacy_color';
	}

	return $value;
}

add_filter( 'nice_option_nice_header_background_color', 'nice_theme_header_background_color' );
function nice_theme_header_background_color( $value ) {

	if ( false !== strpos( $value, '#' ) && nice_bool( get_option( '_nice_flatbase_support_legacy_header_background_color' ) ) ) {
		$value = 'nice_header_background_legacy_color';
	}

	return $value;
}

add_filter( 'nice_option_nice_light_skin_text_color', 'nice_theme_light_skin_text_color' );
function nice_theme_light_skin_text_color( $value ) {

	if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_body_color' ) ) ) {
		if ( empty( $value ) ) {
			$value = 'nice_font_body_legacy_color';
		}

	}

	return $value;
}

add_filter( 'nice_option_nice_light_skin_nav_text_color', 'nice_theme_light_skin_nav_text_color' );
function nice_theme_light_skin_nav_text_color( $value ) {

	if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_nav_color' ) ) ) {
		if ( empty( $value ) ) {
			$value = 'nice_font_nav_legacy_color';
		}
	}

	return $value;
}

add_filter( 'nice_option_nice_light_skin_subnav_text_color', 'nice_theme_light_skin_subnav_text_color' );
function nice_theme_light_skin_subnav_text_color( $value ) {

	if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_subnav_color' ) ) ) {
		if ( empty( $value ) ) {
			$value = 'nice_font_subnav_legacy_color';
		}
	}

	return $value;
}

add_filter( 'nice_option_nice_dark_skin_welcome_message_color', 'nice_theme_dark_skin_welcome_message_color' );
function nice_theme_dark_skin_welcome_message_color( $value ) {

	if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_welcome_message_color' ) ) ) {
		if ( empty( $value ) ) {
			$value = 'nice_font_welcome_message_legacy_color';
		}
	}

	return $value;
}

add_filter( 'nice_option_nice_dark_skin_welcome_message_extended_color', 'nice_theme_dark_skin_welcome_message_extended_color' );
function nice_theme_dark_skin_welcome_message_extended_color( $value ) {

	if ( nice_bool( get_option( '_nice_flatbase_support_legacy_font_welcome_message_extended_color' ) ) ) {
		if ( empty( $value ) ) {
			$value = 'nice_font_welcome_message_extended_legacy_color';
		}
	}

	return $value;
}

if ( ! function_exists( 'nice_customizer_selectable_colors_styles' ) ) :
add_filter( 'nice_customizer_styles', 'nice_customizer_selectable_colors_styles' );
/**
 * Add styles for selectable colors in Customizer view.
 *
 * @since 2.0.2
 *
 * @param  string $styles
 *
 * @return string
 */
function nice_customizer_selectable_colors_styles( $styles = '' ) {
	$nice_colors = nice_theme_colors();

	foreach ( $nice_colors as $color ) {
		$styles .= '[data-value="' . esc_attr( $color['id'] ) . '"]:before { background-color: ' . esc_attr( $color['value'] ) . '; }' . "\n";
	}

	return $styles;
}
endif;