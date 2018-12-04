<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to implement theme options.
 *
 * @see nice_options()
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase/
 * @copyright 2016 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_options_global' ) ) :
add_action( 'init', 'nice_options_global' );
/**
 * make options global.
 *
 * @since 1.0.0
 */
function nice_options_global() {
	global $nice_options;
	$nice_options = get_option( 'nice_options' );
}
endif;

if ( ! isset( $content_width ) ) {
	$content_width = 620;
}

if ( ! function_exists( 'nice_options' ) ) :
add_action( 'admin_head', 'nice_options' );
add_action( 'nice_customizer_init', 'nice_options' );
/**
 * Set up theme options.
 *
 * @since 1.0.0
 */
function nice_options() {
	global $wp_version;

	$prefix = NICE_PREFIX;

	$nice_options = array();

	/**
	 * General Settings.
	*/


	/**
	 * Design & Styles
	*/

	$nice_options[] = array(
		'name' => esc_html__( 'Design & Styles', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_setting-wrench"></i>'
	);

	/**
	 * Design & Styles > General
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'General', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_music-eq-a"></i>',
	);

	// Make favicon setting available only WP version is < 4.3.
	if ( version_compare( $wp_version, '4.3', '<' ) ) {
		$nice_options[] = array(
			'name'  => esc_html__( 'Favicon', 'nicethemes' ),
			'desc'  => esc_html__( 'Upload a favicon.', 'nicethemes' ),
			'id'    => $prefix . '_favicon',
			'std'   => '',
			'type'  => 'upload',
			'extra' => array( 'type' => 'image' ),
		);
	}

	$nice_options[] = array(
		'name'    => esc_html__( 'Content Skin', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the general content skin.', 'nicethemes' ),
		'id'      => $prefix . '_content_skin',
		'type'    => 'radio_image',
		'std'     => 'light',
		'tip'     => '',
		'options' => array(
			'light' => array(
				'label' => esc_html__( 'Light', 'nicethemes' ),
				'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-light.png' ),
			),
			'dark'  => array(
				'label' => esc_html__( 'Dark', 'nicethemes' ),
				'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-dark.png' ),
			),
		),
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Sidebar Position', 'nicethemes' ),
		'desc'    => esc_html__( 'Set the position of the sidebar.', 'nicethemes' ),
		'id'      => $prefix . '_sidebar_position',
		'type'    => 'radio_image',
		'std'     => is_rtl() ? 'left' : 'right',
		'tip'     => '',
		'options' => array(
			'right' => array(
				'label' => esc_html__( 'Right Sidebar', 'nicethemes' ),
				'image' => nice_get_file_uri( 'engine/admin/assets/images/options/layout-sidebar-right.png' ),
			),
			'left'  => array(
				'label' => esc_html__( 'Left Sidebar', 'nicethemes' ),
				'image' => nice_get_file_uri( 'engine/admin/assets/images/options/layout-sidebar-left.png' ),
			),
		),
	);

	/**
	 * Design & Styles > Colors
	 */

	$nice_options[] = array(
		'name'              => esc_html__( 'Colors', 'nicethemes' ),
		'type'              => 'group',
		'id'                => $prefix . '_palette',
		'icon'              => '<i class="bi_tool-paint-roler"></i>',
		'ignore_customizer' => true,
	);


	$nice_options[] = array(
		'name'              => esc_html__( 'Color Palette', 'nicethemes' ),
		'desc'              => esc_html__( 'Define all the colors you will need.', 'nicethemes' ),
		'id'                => $prefix . '_colors',
		'user_item_prefix'  => $prefix . '_user_color',
		'type'              => 'list_item',
		'std'               => nice_theme_default_colors(),
		'tip'               => '',
		'sortable'          => true,
		'editable'          => true,
		'edit_defaults'     => false,
		'ignore_customizer' => true,
		'option_name_label' => esc_html__( 'Color name', 'nicethemes' ),
		'settings'          => array(
			array(
				'name'      => esc_html__( 'Color', 'nicethemes' ),
				'desc'      => esc_html__( 'Choose the color. You can also adjust its opacity.', 'nicethemes' ),
				'id'        => 'default',
				'type'      => 'color',
				'class'     => 'nice-color-opacity',
				'std'       => '',
				'tip'       => '',
			),
		),
	);

	/**
	 * Design & Styles > Layout
	 */

	$nice_options[] = array(
		'name'              => esc_html__( 'Layout', 'nicethemes' ),
		'type'              => 'group',
		'icon'              => '<i class="bi_doc-compose-a"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Background Image', 'nicethemes' ),
		'desc' => esc_html__( 'Upload or choose the background image.', 'nicethemes' ),
		'id'   => $prefix . '_background_image',
		'std'  => '',
		'type' => 'upload',
		'extra' => array( 'type' => 'image' ),
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Background Image Repeat', 'nicethemes' ),
		'desc' => '',
		'id'   => $prefix . '_background_image_repeat',
		'std'  => 'repeat',
		'type' => 'select',
		'tip'  => '',
		'options'	=> array(
							'no-repeat' => esc_html__( 'No Repeat', 'nicethemes' ),
							'repeat'    => esc_html__( 'Repeat', 'nicethemes' ),
							'repeat-x'  => esc_html__( 'Repeat horizontally', 'nicethemes' ),
							'repeat-y'  => esc_html__( 'Repeat vertically', 'nicethemes' )
						)
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Background Image Position', 'nicethemes' ),
		'desc' => '',
		'id'   => $prefix . '_background_image_position',
		'std'  => 'left top',
		'type' => 'select',
		'tip'  => '',
		'options' 	=> array(
							'center top'    => esc_html__( 'Center Top', 'nicethemes' ),
							'center center' => esc_html__( 'Center Center', 'nicethemes' ),
							'center bottom' => esc_html__( 'Center Bottom', 'nicethemes' ),
							'left top'      => esc_html__( 'Left Top', 'nicethemes' ),
							'left center'   => esc_html__( 'Left Center', 'nicethemes' ),
							'left bottom'   => esc_html__( 'Left Bottom', 'nicethemes' ),
							'right top'     => esc_html__( 'Right Top', 'nicethemes' ),
							'right center'  => esc_html__( 'Right Center', 'nicethemes' ),
							'right bottom'  => esc_html__( 'Right Bottom', 'nicethemes' )
						)
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Background Color', 'nicethemes' ),
		'desc' => esc_html__( 'Choose the background color.', 'nicethemes' ),
		'id'   => $prefix . '_background_color',
		'type'    => 'select_color',
		'class'   => 'nice-select-color',
		'std'     => '',
		'tip'     => '',
		'options' => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Layout Type', 'nicethemes' ),
		'desc' => esc_html__( 'Select the layout type.', 'nicethemes' ),
		'id'   => $prefix . '_layout_type',
		'type' => 'select',
		'std'  => 'full',
		'tip'  => '',
		'options' => array(
			'boxed' => esc_html__( 'Boxed', 'nicethemes' ),
			'full'  => esc_html__( 'Full Width', 'nicethemes' )
		)
	);

	/**
	 * Design & Styles > Page Loader
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Page Loader', 'nicethemes' ),
		'type' => 'group',
		'id'   => $prefix . '_loader',
		'icon' => '<i class="bi_music-repeat"></i>',
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Select the Page Loader', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the page loader for your website.', 'nicethemes' ),
		'id'      => $prefix . '_page_loader',
		'type'    => 'select',
		'std'     => 'none',
		'tip'     => '',
		'options' => array(
			''          => esc_html__( 'None', 'nicethemes' ),
			'top_bar'   => esc_html__( 'Top Bar', 'nicethemes' ),
			'full_page' => esc_html__( 'Full Page Overlay', 'nicethemes' ),
		),
		'extra'   => array(
			'type' => 'radio-buttonset',
		),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Page Loader Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Choose the page loader color.', 'nicethemes' ),
		'id'        => $prefix . '_page_loader_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
		'condition' => $prefix . '_page_loader:is(top_bar)',
		'operator'  => 'and',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Page Loader Background Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Choose the page loader background color.', 'nicethemes' ),
		'id'        => $prefix . '_page_loader_full_page_background_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
		'condition' => $prefix . '_page_loader:is(full_page)',
		'operator'  => 'and',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Page Loader Icon', 'nicethemes' ),
		'desc'      => esc_html__( 'Choose the page loader icon.', 'nicethemes' ),
		'id'        => $prefix . '_page_loader_full_page_loader_icon',
		'type'      => 'select',
		'std'       => 'spin',
		'options'   => array(
			'spin' => esc_html__( 'Spin', 'nicethemes' ),
			'beat' => esc_html__( 'Beat', 'nicethemes' ),
		),
		'condition' => $prefix . '_page_loader:is(full_page)',
		'operator'  => 'and',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Page Loader Icon Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Choose the page loader icon color.', 'nicethemes' ),
		'id'        => $prefix . '_page_loader_full_page_loader_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
		'condition' => $prefix . '_page_loader:is(full_page)',
		'operator'  => 'and',
	);

	/**
	 * Design & Styles > Blog & Posts
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Blog & Posts', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_doc-article"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Masonry Blog Posts Load Method', 'nicethemes' ),
		'desc' => esc_html__( 'Select the method for loading masonry blog posts.', 'nicethemes' ),
		'id'   => $prefix . '_masonry_posts_load_method',
		'type' => 'select',
		'std'  => 'on_scroll',
		'tip'  => '',
		'options' => array(
			'on_scroll' => esc_html__( 'On Scroll', 'nicethemes' ),
			'on_button' => esc_html__( 'On Clicking Button', 'nicethemes' )
		)
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Post Author Box', 'nicethemes' ),
		'desc' => esc_html__( 'This will enable the post author box on the single posts page. Edit description in Users > Your Profile.', 'nicethemes' ),
		'id'   => $prefix . '_post_author',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);

	/**
	 * Design & Styles > Buttons
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Buttons', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_tool-mouse"></i>',
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Default Button Shape', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the default shape for your site\'s buttons.', 'nicethemes' ),
		'id'      => $prefix . '_btn_shape',
		'type'    => 'radio_image',
		'std'     => '',
		'tip'     => '',
		'options' => array(
			'' => array(
				'label' => esc_html__( 'Default', 'nicethemes' ),
				'image' => nice_get_file_uri( 'engine/admin/assets/images/options/btn-general-default.png' ),
			),
			'round' => array(
				'label' => esc_html__( 'Round', 'nicethemes' ),
				'image' => nice_get_file_uri( 'engine/admin/assets/images/options/btn-general-round.png' ),
			),
			'circle'  => array(
				'label' => esc_html__( 'Circle', 'nicethemes' ),
				'image' => nice_get_file_uri( 'engine/admin/assets/images/options/btn-general-circle.png' ),
			),
			'square'  => array(
				'label' => esc_html__( 'Square', 'nicethemes' ),
				'image' => nice_get_file_uri( 'engine/admin/assets/images/options/btn-general-square.png' ),
			),
		),
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Default Button Color', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the default color for your site\'s buttons.', 'nicethemes' ),
		'id'      => $prefix . '_btn_color',
		'type'    => 'select_color',
		'class'   => 'nice-select-color',
		'std'     => '',
		'tip'     => '',
		'options' => nice_theme_colors_dropdown_values(),
	);

	/**
	 * Design & Styles > Call to Action
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Call to Action', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_music-eq-a"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Call to Action Text', 'nicethemes' ),
		'desc' => esc_html__( 'Add the text that you would like to appear in the global call to action section.', 'nicethemes' ),
		'id'   => $prefix . '_cta_text',
		'std'  => '',
		'tip'  => '',
		'type' => 'textarea'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Call to Action Button Link URL', 'nicethemes' ),
		'desc' => esc_html__( 'Please enter the URL for the call to action section here.', 'nicethemes' ),
		'id'   => $prefix . '_cta_url',
		'std'  => '',
		'tip'  => '',
		'type' => 'text'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Call to Action Button Text', 'nicethemes' ),
		'desc' => esc_html__( 'If you would like a button to be the link in the global call to action section, please enter the text for it here.', 'nicethemes' ),
		'id'   => $prefix . '_cta_url_text',
		'std'  => '',
		'tip'  => '',
		'type' => 'text'
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Call to Action Skin', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the call to action skin.', 'nicethemes' ),
		'id'      => $prefix . '_cta_skin',
		'type'    => 'radio_image',
		'std'     => 'light',
		'tip'     => '',
		'options' => array(
			'light' => array( 'label' => esc_html__( 'Light', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-light.png' ) ),
			'dark'  => array( 'label' => esc_html__( 'Dark', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-dark.png' ) ),
		),
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Call to Action Background Color', 'nicethemes' ),
		'desc'    => esc_html__( 'Choose the background color for the call to action.', 'nicethemes' ),
		'id'      => $prefix . '_cta_background_color',
		'type'    => 'select_color',
		'class'   => 'nice-select-color',
		'std'     => '',
		'tip'     => '',
		'options' => nice_theme_colors_dropdown_values(),
	);

	/**
	 * Header
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Header', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_layout-header"></i>'
	);

	/**
	 * Header > Logo
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Logo', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_media-image-d"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Custom Logo', 'nicethemes' ),
		'desc' => esc_html__( 'Upload a custom logo.', 'nicethemes' ),
		'id'   => $prefix . '_logo',
		'std'  => '',
		'type'  => 'upload',
		'extra' => array( 'type' => 'image' ),
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Custom Logo (Retina)', 'nicethemes' ),
		'desc' => esc_html__( 'Upload a custom logo for retina displays. Upload at exactly 2x the size of your standard logo.', 'nicethemes' ),
		'id'   => $prefix . '_logo_retina',
		'std'  => '',
		'type'  => 'upload',
		'extra' => array( 'type' => 'image' ),
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Logo Height', 'nicethemes' ),
		'desc' => esc_html__( 'Change the logo height. This setting will standarize the logo height for retina devices.', 'nicethemes' ),
		'id'   => $prefix . '_logo_height',
		'std'  => array( 'range' => 'min', 'value' => '65', 'min' => '10', 'max' => '400', 'unit' => 'px' ),
		'type' => 'slider'
	);


	$nice_options[] = array(
		'name' => esc_html__( 'Text Title', 'nicethemes' ),
		'desc' => esc_html__( 'Enable if you want Blog Title and Tagline to be text-based. Setup title/tagline in WP -> Settings -> General.', 'nicethemes' ),
		'id'   => $prefix . '_texttitle',
		'std'  => 'false',
		'type' => 'checkbox'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Site Title Typography', 'nicethemes' ),
		'desc' => esc_html__( 'Change the site title typography. ( Only if Text Title is enabled )', 'nicethemes' ),
		'id'   => $prefix . '_font_site_title',
		'std'  => array( 'size' => '30', 'family' => 'Nunito', 'style' => '','color' => '#fff'),
		'type' => 'typography',
		'condition'   => $prefix . '_texttitle:is(true)',
		'operator'    => 'and',
	);

	/**
	 * Header > Design
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Design', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_setting-wrench"></i>',
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Header Skin', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the header content skin.', 'nicethemes' ),
		'id'      => $prefix . '_header_skin',
		'type'    => 'radio_image',
		'std'     => 'dark',
		'tip'     => '',
		'options' => array(
			'light' => array( 'label' => esc_html__( 'Light', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-light.png' ) ),
			'dark'  => array( 'label' => esc_html__( 'Dark', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-dark.png' ) ),
		),
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Header Background Color', 'nicethemes' ),
		'desc' => '',
		'id'   => $prefix . '_header_background_color',
		'tip'  => '',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);



	$nice_options[] = array(
		'name' => esc_html__( 'Header background Image', 'nicethemes' ),
		'desc' => esc_html__( 'Upload or choose the header background image.', 'nicethemes' ),
		'id'   => $prefix . '_header_background_image',
		'std'  => '',
		'type'  => 'upload',
		'extra' => array( 'type' => 'image' ),
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Header background image repeat', 'nicethemes' ),
		'desc' => '',
		'id'   => $prefix . "_header_background_image_repeat",
		'std'  => 'repeat',
		'type' => 'select',
		'tip'  => '',
		'options' => array(
			'no-repeat' => esc_html__( 'No Repeat', 'nicethemes' ),
			'repeat'    => esc_html__( 'Repeat', 'nicethemes' ),
			'repeat-x'  => esc_html__( 'Repeat horizontally', 'nicethemes' ),
			'repeat-y'  => esc_html__( 'Repeat vertically', 'nicethemes' )
		),
		'operator' => 'and',
		'condition' => $prefix . '_header_background_image:not()',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Header background image position', 'nicethemes' ),
		'desc' => '',
		'id'   => $prefix . '_header_background_image_position',
		'std'  => 'left top',
		'type' => 'select',
		'tip'  => '',
		'options' => array(
			'center top'    => esc_html__( 'Center Top', 'nicethemes' ),
			'center center' => esc_html__( 'Center Center', 'nicethemes' ),
			'center bottom' => esc_html__( 'Center Bottom', 'nicethemes' ),
			'left top'      => esc_html__( 'Left Top', 'nicethemes' ),
			'left center'   => esc_html__( 'Left Center', 'nicethemes' ),
			'left bottom'   => esc_html__( 'Left Bottom', 'nicethemes' ),
			'right top'     => esc_html__( 'Right Top', 'nicethemes' ),
			'right center'  => esc_html__( 'Right Center', 'nicethemes' ),
			'right bottom'  => esc_html__( 'Right Bottom', 'nicethemes' )
		),
		'operator' => 'and',
		'condition' => $prefix . '_header_background_image:not()',
	);

	$nice_options[] = array(
		'name'     => esc_html__( 'Background Image Size', 'nicethemes' ),
		'id'       => $prefix . '_header_background_image_size',
		'type'     => 'select',
		'std'      => 'auto',
		'options'  => array(
			'auto'    => esc_html__( 'Auto', 'nicethemes' ),
			'cover'   => esc_html__( 'Cover', 'nicethemes' ),
			'contain' => esc_html__( 'Contain', 'nicethemes' ),
		),
		'desc'     => esc_html__( 'Select the size for you background image.', 'nicethemes' ),
		'operator' => 'and',
		'condition' => $prefix . '_header_background_image:not()',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Background Image Attachment', 'nicethemes' ),
		'id'        => $prefix . '_header_background_image_attachment',
		'type'      => 'select',
		'std'       => 'scroll',
		'options'   => array(
			'scroll' => esc_html__( 'Scroll', 'nicethemes' ),
			'fixed'  => esc_html__( 'Fixed', 'nicethemes' ),
		),
		'desc'      => esc_html__( 'Select how your background image should be attached to the page.', 'nicethemes' ),
		'condition' => $prefix . '_header_background_image:not()',
		'operator'  => 'and',
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Navigation Sub-Menu Content Skin', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the navigation sub-menu content skin.', 'nicethemes' ),
		'id'      => $prefix . '_header_submenu_skin',
		'type'    => 'radio_image',
		'std'     => 'dark',
		'tip'     => '',
		'options' => array(
			'light' => array( 'label' => esc_html__( 'Light', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-light.png' ) ),
			'dark'  => array( 'label' => esc_html__( 'Dark', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-dark.png' ) ),
		),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Header Border', 'nicethemes' ),
		'desc'      => esc_html__( 'Add a semi-transparent border at the bottom of the header.', 'nicethemes' ),
		'id'        => $prefix . '_header_border',
		'std'       => 'false',
		'type'      => 'checkbox'
	);

	/**
	 * Footer
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Footer', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_layout-footer"></i>'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Footer Layout', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_music-eq-a"></i>',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Full Width Footer', 'nicethemes' ),
		'desc'      => esc_html__( 'Make the footer contents take the full available width.', 'nicethemes' ),
		'id'        => $prefix . '_footer_full_width',
		'std'       => false,
		'tip'       => '',
		'type'      => 'checkbox',
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Footer Skin', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the footer skin.', 'nicethemes' ),
		'id'      => $prefix . '_footer_skin',
		'type'    => 'radio_image',
		'std'     => 'dark',
		'tip'     => '',
		'options' => array(
			'light' => array( 'label' => esc_html__( 'Light', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-light.png' ) ),
			'dark'  => array( 'label' => esc_html__( 'Dark', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-dark.png' ) ),
		),
	);

	/**
	 * Footer > Extended
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Footer Extended', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_layout-footer"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Enable Custom Copyright', 'nicethemes' ),
		'desc' => esc_html__( 'Enable if you want to write your own copyright text.', 'nicethemes' ),
		'id'   => $prefix . '_custom_copyright_enable',
		'std'  => 'false',
		'tip'  => '',
		'type' => 'checkbox'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Custom Copyright Text', 'nicethemes' ),
		'desc' => esc_html__( 'Please enter the copyright section text. e.g. All Rights Reserved, Nice Themes.', 'nicethemes' ),
		'id'   => $prefix . '_custom_copyright_text',
		'std'  => '',
		'tip'  => '',
		'type' => 'text',
		'condition'   => $prefix . '_custom_copyright_enable:is(true)',
		'operator'    => 'and',
	);

	/**
	 * Footer > Widgets
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Footer Widgets', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_interface-dashboard"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Footer Columns', 'nicethemes' ),
		'desc' => '',
		'id'   => $prefix . '_footer_columns',
		'std'  => array( 'range' => 'min', 'value' => '3', 'min' => '2', 'max' => '4' ),
		'type' => 'slider'
	);

	/**
	 * Footer > Back To Top
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Back To Top', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_interface-top-r"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Display Back To Top Button', 'nicethemes' ),
		'desc' => esc_html__( 'Enable if you want the "Back To Top button" to be displayed in the bottom right corner of the site.', 'nicethemes' ),
		'id'   => $prefix . '_back_to_top',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);

	/**
	 * Customize
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Customize', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_misc-cube"></i>',
	);

	/**
	 * Customize > General
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'General', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_web-internet-a"></i>',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'HTML Background Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the HTML background color.', 'nicethemes' ),
		'id'        => $prefix . '_html_background_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Likes "Liked" Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the "liked" color.', 'nicethemes' ),
		'id'        => $prefix . '_likes_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	/**
	 * Customize > Light Skin
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Light Skin', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_doc-binder-blank"></i>',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Background Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Light Skin background color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_background_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Text Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Light Skin text color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_text_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Link Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Light Skin link color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_link_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Headings Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Light Skin headings color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_heading_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Primary Menu Text Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Light Skin Primary Menu Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_nav_text_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Secondary Menu Text Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Light Skin Secondary Menu Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_subnav_text_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Secondary Menu Background Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Light Skin Secondary Menu Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_subnav_background_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Welcome Message Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Live Search Tagline Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_welcome_message_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Welcome Message (extended) Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Live Search Tagline (extended) Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_welcome_message_extended_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Footer Background Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Light Skin Footer Background Color.', 'nicethemes' ),
		'id'        => $prefix . '_light_skin_footer_background_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	/**
	 * Customize > Dark Skin
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Dark Skin', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_doc-binder-blank dark-background"></i>',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Background Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Dark Skin background color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_background_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Text Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Dark Skin text color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_text_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Link Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Dark Skin link color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_link_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Headings Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Dark Skin headings color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_heading_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Primary Menu Text Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Dark Skin Primary Menu Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_nav_text_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Secondary Menu Text Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Dark Skin Secondary Menu Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_subnav_text_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Secondary Menu Background Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Dark Skin Secondary Menu Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_subnav_background_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Welcome Message Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Live Search Tagline Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_welcome_message_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Welcome Message (extended) Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Live Search Tagline (extended) Text Color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_welcome_message_extended_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Footer Background Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the Dark Skin Footer Background Color.', 'nicethemes' ),
		'id'        => $prefix . '_dark_skin_footer_background_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	/**
	 * Customize > Navigation
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Navigation', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_interface-hamburger"></i>',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Menu Highlight Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the menu hover and active effect color (By default an opaque version of the menu color will be used).', 'nicethemes' ),
		'id'        => $prefix . '_menu_highlight_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Menu Bar Highlight Color', 'nicethemes' ),
		'desc'      => esc_html__( 'Set the menu hover top bar color (By default the accent color will be used).', 'nicethemes' ),
		'id'        => $prefix . '_menu_bar_highlight_color',
		'type'      => 'select_color',
		'std'       => '',
		'class'     => 'nice-select-color',
		'options'   => nice_theme_colors_dropdown_values(),
	);

	$nice_options[] = array(
		'name'     => esc_html__( 'Navigation Items Text Transform', 'nicethemes' ),
		'id'       => $prefix . '_nav_text_transform',
		'type'     => 'select',
		'std'      => 'uppercase',
		'options'  => array(
			'default'    => esc_html__( 'None', 'nicethemes' ),
			'uppercase'  => esc_html__( 'Uppercase', 'nicethemes' ),
			'lowercase'  => esc_html__( 'Lowercase', 'nicethemes' ),
			'capitalize' => esc_html__( 'Capitalize', 'nicethemes' ),
		),
		'desc'     => esc_html__( 'Select the navigation items text transformation.', 'nicethemes' ),
	);

	$nice_options[] = array(
		'name'     => esc_html__( 'Sub Navigation Text Transform', 'nicethemes' ),
		'id'       => $prefix . '_subnav_text_transform',
		'type'     => 'select',
		'std'      => 'uppercase',
		'options'  => array(
			'default'    => esc_html__( 'None', 'nicethemes' ),
			'uppercase'  => esc_html__( 'Uppercase', 'nicethemes' ),
			'lowercase'  => esc_html__( 'Lowercase', 'nicethemes' ),
			'capitalize' => esc_html__( 'Capitalize', 'nicethemes' ),
		),
		'desc'     => esc_html__( 'Select the sub navigation items text transformation.', 'nicethemes' ),
	);

	/**
	 * Customize > Custom Code & Scripts
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Custom Code & Scripts', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_web-code"></i>',
	);

	/**
	 *
	 * @hook nice_use_custom_css
	 *
	 * Allow using your custom CSS from the theme.
	 *
	 * @since 1.0.0
	 */
	if ( version_compare( $wp_version, '4.7', '<' ) || apply_filters( 'nice_use_custom_css', true ) ) {
		$nice_options[] = array(
			'name'  => esc_html__( 'Custom CSS', 'nicethemes' ),
			'desc'  => esc_html__( 'Quickly add some CSS to your theme by adding it to this block.', 'nicethemes' ),
			'id'    => $prefix . '_custom_css',
			'std'   => '',
			'tip'   => '',
			'type'  => 'textarea',
			'extra' => array(
				'type'    => 'code',
				'choices' => array(
					'language' => 'css',
					'theme'    => 'monokai',
				),
			),
		);
	}

	$nice_options[] = array(
		'name'  => esc_html__( 'Custom JavaScript', 'nicethemes' ),
		'desc'  => esc_html__( 'Quickly add some JavaScript to your theme by adding it to this block.', 'nicethemes' ),
		'id'    => $prefix . '_custom_js',
		'std'   => '',
		'tip'   => '',
		'type'  => 'textarea',
		'extra' => array(
			'type'    => 'code',
			'choices' => array(
				'language' => 'js',
				'theme'    => 'monokai',
			),
		),
	);

	/**
	 *
	 * @hook nice_use_tracking_code
	 *
	 * Allow using your custom tracking code from the theme.
	 *
	 * @since 1.0.0
	 */
	if ( apply_filters( 'nice_use_tracking_code', true ) ) {
		$nice_options[] = array(
			'name'  => esc_html__( 'Tracking Code', 'nicethemes' ),
			'desc'  => esc_html__( 'Insert your tracking code if you have one (i.e. from Google Analytics).', 'nicethemes' ),
			'id'    => $prefix . '_tracking_code',
			'std'   => '',
			'tip'   => '',
			'type'  => 'textarea',
			'extra' => array(
				'type'    => 'code',
				'choices' => array(
					'language' => 'xml',
					'theme'    => 'monokai',
				),
			),
		);
	}

	/**
	 * Customize > Admin
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Admin', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_interface-dashboard"></i>',
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Display Theme Admin Bar Menu', 'nicethemes' ),
		'desc'    => esc_html__( 'Show the Theme Admin Bar Menu when viewing the theme.', 'nicethemes' ),
		'id'      => $prefix . '_admin_bar_menu',
		'std'     => '1',
		'options' => array(
			1 => array( 'label' => esc_html__( 'On', 'nicethemes' ) ),
			0 => array( 'label' => esc_html__( 'Off', 'nicethemes' ) ),
		),
		'type'    => 'radio_on_off',
	);


	/**
	 * Home Options
	 */
	$nice_options[] = array(
		'name' => esc_html__( 'Home Options', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_interface-home-a"></i>'
	);

	/**
	 * Home
	 */

	$nice_options[] = array(
		'id'   => $prefix . '_homepage',
		'name' => esc_html__( 'Home Template', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_interface-home-a"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Home Page Sections', 'nicethemes' ),
		'desc' => esc_html__( 'Define and order the sections you want to show in the home page. Recommended plugins may add new sections, so make sure you have them all active to see all available placeholders.', 'nicethemes' ),
		'id'   => $prefix . '_homepage_elements',
		'std'  => nice_options_homepage_default_elements(),
		'type' => 'textarea',
		'tip'  => '',
	);

	/**
	 * Home Options > Live Search
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Live Search & Welcome Message', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_interface-search"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Display Live Search', 'nicethemes' ),
		'desc' => esc_html__( 'This will enable the live search block on the home page.', 'nicethemes' ),
		'id'   => $prefix . '_livesearch_enable',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Section Skin', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the welcome message and live search content skin.', 'nicethemes' ),
		'id'      => $prefix . '_welcome_message_skin',
		'type'    => 'radio_image',
		'std'     => 'dark',
		'tip'     => '',
		'options' => array(
			'light' => array( 'label' => esc_html__( 'Light', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-light.png' ) ),
			'dark'  => array( 'label' => esc_html__( 'Dark', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-dark.png' ) ),
		),
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Section Alignment', 'nicethemes' ),
		'desc'    => esc_html__( 'How do you wish to align the section text.', 'nicethemes' ),
		'id'      => $prefix . '_livesearch_align',
		'type'    => 'select',
		'std'     => 'center',
		'options' => array(
			''       => esc_html__( 'Default', 'nicethemes' ),
			'left'   => esc_html__( 'Left', 'nicethemes' ),
			'center' => esc_html__( 'Center', 'nicethemes' ),
		),
		'condition'   => $prefix . '_livesearch_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Welcome Message', 'nicethemes' ),
		'desc' => esc_html__( 'Insert the text that will appear above the search bar.', 'nicethemes' ),
		'id'   => $prefix . '_welcome_message',
		'std'  => '',
		'tip'  => '',
		'type' => 'textarea',
		'condition'   => $prefix . '_livesearch_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Welcome Message (extended)', 'nicethemes' ),
		'desc' => esc_html__( 'Insert the text that will appear below the Live Search Text.', 'nicethemes' ),
		'id'   => $prefix . '_welcome_message_extended',
		'std'  => '',
		'tip'  => '',
		'type' => 'textarea',
		'condition'   => $prefix . '_livesearch_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Live Search Placeholder', 'nicethemes' ),
		'desc' => esc_html__( 'Insert the text that will appear in the search input for default.', 'nicethemes' ),
		'id'   => $prefix . '_welcome_message_placeholder',
		'std'  => esc_html__( 'Have a question? Ask or enter a search term.', 'nicethemes' ),
		'tip'  => '',
		'type' => 'text',
		'condition'   => $prefix . '_livesearch_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Section Padding (Top)', 'nicethemes' ),
		'desc'      => esc_html__( 'Change the welcome message and livesearch padding top.', 'nicethemes' ),
		'id'        => $prefix . '_livesearch_padding_top',
		'std'       => array(
			'range' => 'min',
			'value' => '70',
			'min'   => '0',
			'max'   => '500',
			'unit'  => 'px',
		),
		'type'      => 'slider',
		'condition'   => $prefix . '_livesearch_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Section Padding (Bottom)', 'nicethemes' ),
		'desc'      => esc_html__( 'Change the welcome message and livesearch padding bottom.', 'nicethemes' ),
		'id'        => $prefix . '_livesearch_padding_bottom',
		'std'       => array(
			'range' => 'min',
			'value' => '90',
			'min'   => '0',
			'max'   => '500',
			'unit'  => 'px',
		),
		'type'      => 'slider',
		'condition'   => $prefix . '_livesearch_enable:is(true)',
		'operator'    => 'and',
	);

	/**
	 * Home Options > Info Boxes
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Info Boxes', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_interface-thumbnail"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Enable Info Boxes', 'nicethemes' ),
		'desc' => esc_html__( 'This will enable the info boxes to be shown in the home page.', 'nicethemes' ),
		'id'   => $prefix . '_infobox_enable',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Info Box Skin', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the info boxes skin.', 'nicethemes' ),
		'id'      => $prefix . '_infobox_skin',
		'type'    => 'radio_image',
		'std'     => 'light',
		'tip'     => '',
		'options' => array(
			'light' => array( 'label' => esc_html__( 'Light', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-light.png' ) ),
			'dark'  => array( 'label' => esc_html__( 'Dark', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-dark.png' ) ),
		),
		'condition'   => $prefix . '_infobox_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Info Boxes Background Color', 'nicethemes' ),
		'desc' => esc_html__( 'Choose the background color for the infoboxes.', 'nicethemes' ),
		'id'   => $prefix . '_infobox_background_color',
		'type'    => 'select_color',
		'class'   => 'nice-select-color',
		'std'     => '',
		'tip'     => '',
		'options' => nice_theme_colors_dropdown_values(),
		'condition'   => $prefix . '_infobox_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Info Boxes Order', 'nicethemes' ),
		'desc' => esc_html__( 'Select the view order you wish to set for the info boxes items on the home page.', 'nicethemes' ),
		'id'   => $prefix . '_infobox_order',
		'std'  => 'date',
		'type' => 'select',
		'tip'  => '',
		'options' => array( 'date' => esc_html__( 'Date', 'nicethemes' ), 'menu_order' => esc_html__( 'Page Order', 'nicethemes' ), 'title' => esc_html__( 'Title', 'nicethemes' ), 'rand' => esc_html__( 'Random', 'nicethemes' ) ),
		'condition'   => $prefix . '_infobox_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Info Boxes Text Alignment', 'nicethemes' ),
		'desc'    => esc_html__( 'How do you wish to align the infobox text.', 'nicethemes' ),
		'id'      => $prefix . '_infobox_text_align',
		'type'    => 'select',
		'std'     => '',
		'options' => array(
			''       => esc_html__( 'Default', 'nicethemes' ),
			'left'   => esc_html__( 'Left', 'nicethemes' ),
			'center' => esc_html__( 'Center', 'nicethemes' ),
			'right'  => esc_html__( 'Right', 'nicethemes' ),
		),
		'condition'   => $prefix . '_infobox_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Info Boxes Image Effect', 'nicethemes' ),
		'desc'    => esc_html__( 'The image effect on hover.', 'nicethemes' ),
		'id'      => $prefix . '_infobox_image_effect',
		'type'    => 'select',
		'std'     => '',
		'options' => array(
			''       => esc_html__( 'Default', 'nicethemes' ),
			'no'     => esc_html__( 'None', 'nicethemes' ),
			'zoomIn' => esc_html__( 'Zoom In', 'nicethemes' ),
		),
		'condition'   => $prefix . '_infobox_enable:is(true)',
		'operator'    => 'and',
	);

	/**
	 * Home Options > Knowledge Base
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Knowledge Base', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_doc-papers"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Number of Articles per Category', 'nicethemes' ),
		'desc' => esc_html__( 'Select the number of articles entries that should appear per category in the home page .(Default is 5)', 'nicethemes' ),
		'id'   => $prefix . '_articles_entries',
		'std'  => array( 'range' => 'min', 'value' => '5', 'min' => '1', 'max' => '15' ),
		'type' => 'slider'
	);

	/**
	 * Home Options > Knowledge Base Videos
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Knowledge Base Videos', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_tool-video"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Enable Videos', 'nicethemes' ),
		'desc' => esc_html__( 'This will enable the videos section to be shown in the home page.', 'nicethemes' ),
		'id'   => $prefix . '_video_enable',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);


	$nice_options[] = array(
		'name' => esc_html__( 'Number of Videos to Show', 'nicethemes' ),
		'desc' => esc_html__( 'Select the number of video entries that should appear in the home page .(Default is 5)', 'nicethemes' ),
		'id'   => $prefix . '_video_entries',
		'std'  => array( 'range' => 'min', 'value' => '5', 'min' => '1', 'max' => '10' ),
		'type' => 'slider',
		'condition'   => $prefix . '_video_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Videos Order', 'nicethemes' ),
		'desc' => esc_html__( 'Select the view order you wish to set for the videos items on the home page.', 'nicethemes' ),
		'id'   => $prefix . '_video_order',
		'std'  => 'date',
		'type' => 'select',
		'tip'  => '',
		'options' => array( 'date' => esc_html__( 'Date', 'nicethemes' ), 'menu_order' => esc_html__( 'Page Order', 'nicethemes' ), 'title' => esc_html__( 'Title', 'nicethemes' ), 'rand' => esc_html__( 'Random', 'nicethemes' ) ),
		'condition'   => $prefix . '_video_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name'    => esc_html__( 'Knowledge Base Videos Skin', 'nicethemes' ),
		'desc'    => esc_html__( 'Select the knowledge base videos skin.', 'nicethemes' ),
		'id'      => $prefix . '_homepage_video_skin',
		'type'    => 'radio_image',
		'std'     => 'light',
		'tip'     => '',
		'options' => array(
			'light' => array( 'label' => esc_html__( 'Light', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-light.png' ) ),
			'dark'  => array( 'label' => esc_html__( 'Dark', 'nicethemes' ), 'image' => nice_get_file_uri( 'engine/admin/assets/images/options/skin-dark.png' ) ),
		),
		'condition'   => $prefix . '_video_enable:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Knowledge Base Videos Background Color', 'nicethemes' ),
		'desc' => esc_html__( 'Choose the background color for the Knowledge Base Videos section.', 'nicethemes' ),
		'id'   => $prefix . '_homepage_video_background_color',
		'type'    => 'select_color',
		'class'   => 'nice-select-color',
		'std'     => '',
		'tip'     => '',
		'options' => nice_theme_colors_dropdown_values(),
		'condition'   => $prefix . '_video_enable:is(true)',
		'operator'    => 'and',
	);

	/**
	 * Typography
	 */
	$nice_options[] = array(
		'name' => esc_html__( 'Typography', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_editorial-pen"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Enable Custom Typography', 'nicethemes' ),
		'desc' => esc_html__( 'Enable if you want to pick your fonts.', 'nicethemes' ),
		'id'   => $prefix . '_custom_typography',
		'std'  => 'false',
		'type' => 'checkbox'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'General Typography', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the general font.', 'nicethemes' ) ,
		'id'   => $prefix . '_font_body',
		'std'  => array( 'size' => '15', 'unit' => 'px', 'family' => 'Lato', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Navigation', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the navigation font.', 'nicethemes' ),
		'id'   => $prefix . '_font_nav',
		'std'  => array( 'size' => '15', 'unit' => 'px', 'family' => 'Lato', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Sub Navigation (Submenus)', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the navigation submenu font.', 'nicethemes' ),
		'id'   => $prefix . '_font_subnav',
		'std'  => array( 'size' => '12', 'unit' => 'px', 'family' => 'Lato', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Headings', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the headings font family.', 'nicethemes' ),
		'id'   => $prefix . '_font_headings',
		'std'  => array( 'family' => 'Nunito', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Form Inputs', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the buttons font family.', 'nicethemes' ),
		'id'   => $prefix . '_font_inputs',
		'std'  => array( 'family' => 'Lato', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Buttons', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the buttons font family.', 'nicethemes' ),
		'id'   => $prefix . '_font_buttons',
		'std'  => array( 'family' => 'Nunito', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Infoboxes Title', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the infoboxes font.', 'nicethemes' ),
		'id'   => $prefix . '_font_infobox_title',
		'std'  => array( 'size' => '21', 'unit' => 'px', 'family' => 'Nunito', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Infoboxes Content', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the infoboxes font.', 'nicethemes' ) ,
		'id'   => $prefix . '_font_infobox_content',
		'std'  => array( 'size' => '15', 'unit' => 'px', 'family' => 'Lato', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Welcome Message Tagline', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the welcome message font.', 'nicethemes' ) ,
		'id'   => $prefix . "_font_welcome_message",
		'std'  => array( 'size' => '32', 'unit' => 'px', 'family' => 'Nunito', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Welcome Message (extended)', 'nicethemes' ) ,
		'desc' => esc_html__( 'Change the extended welcome message font.', 'nicethemes' ) ,
		'id'   => $prefix . "_font_welcome_message_extended",
		'std'  => array( 'size' => '16', 'unit' => 'px', 'family' => 'Lato', 'style' => 'normal' ),
		'type' => 'typography',
		'condition'   => $prefix . '_custom_typography:is(true)',
		'operator'    => 'and',
	);


	/**
	 * Knowledge Base
	 */
	$nice_options[] = array(
		'name' => esc_html__( 'Knowledge Base', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_doc-papers"></i>'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Display Views', 'nicethemes' ),
		'desc' => esc_html__( 'Enable to display the amount of article views below the article title.', 'nicethemes' ),
		'id'   => $prefix . '_views',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Display Likes', 'nicethemes' ),
		'desc' => esc_html__( 'Enable to display the amount of likes below the article title.', 'nicethemes' ),
		'id'   => $prefix . '_likes',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Display Reading Time', 'nicethemes' ),
		'desc' => esc_html__( 'Enable to display the reading time below the article title.', 'nicethemes' ),
		'id'   => $prefix . '_reading_time',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Display Article Author', 'nicethemes' ),
		'desc' => esc_html__( 'This will enable the display of the article author information below the content.', 'nicethemes' ),
		'id'   => $prefix . '_article_author',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Display Related Articles', 'nicethemes' ),
		'desc' => esc_html__( 'This will enable display of the related articles below the content.', 'nicethemes' ),
		'id'   => $prefix . '_related_articles',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox'
	);


	/**
	 * Contact Information.
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Contact Information', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_com-email"></i>'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Contact Form Email Address', 'nicethemes' ),
		'desc' => esc_html__( 'Enter the email address where you\'d like to receive emails from the contact form, or leave blank to use admin email.', 'nicethemes' ),
		'id'   => $prefix . '_email',
		'std'  => '',
		'tip'  => '',
		'type' => 'text'
							);

	$nice_options[] = array(
		'name' => esc_html__( 'Google Maps Embed Code', 'nicethemes' ),
		'desc' => esc_html__( 'Insert the Google Map embed code for the contact template.', 'nicethemes' ),
		'id'   => $prefix . '_google_map',
		'std'  => '',
		'tip'  => '',
		'type' => 'textarea'
	);

	/**
	 * Social Media.
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Social Media', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_web-share-b"></i>'
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Open in New Tab', 'nicethemes' ),
		'desc' => esc_html__( 'Open social links in a new tab.', 'nicethemes' ),
		'id'   => $prefix . '_social_links_new_tab',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox',
	);

	$nice_options[] = array(
		'name'              => esc_html__( 'Social Links', 'nicethemes' ),
		'desc'              => esc_html__( 'Define all the social links you will need.', 'nicethemes' ),
		'id'                => $prefix . '_social_links',
		'user_item_prefix'  => $prefix . '_user_social',
		'type'              => 'list_item',
		'std'               => nice_theme_default_social_links(),
		'tip'               => '',
		'sortable'          => true,
		'editable'          => true,
		'edit_defaults'     => false,
		'ignore_customizer' => true,
		'option_name_label' => esc_html__( 'Social Network', 'nicethemes' ),
		'settings'          => array(
			array(
				'name' => esc_html__( 'URL', 'nicethemes' ),
				'desc' => esc_html__( 'Enter the full URL for your social media profile.', 'nicethemes' ),
				'id'   => 'url',
				'type' => 'text',
				'std'  => '',
				'tip'  => '',
			),
			array(
				'name' => esc_html__( 'Icon Class', 'nicethemes' ),
				'desc' => esc_html__( 'Enter the class for your icon.', 'nicethemes' ),
				'id'   => 'icon_class',
				'type' => 'text',
				'std'  => '',
				'tip'  => '',
			),
		),
	);

	/**
	 * Performance
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Performance', 'nicethemes' ),
		'type' => 'heading',
		'icon' => '<i class="bi_interface-dashboard"></i>',
	);

	/**
	 * Performance > Images
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Images', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_media-image-d"></i>',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'JPEG Quality', 'nicethemes' ),
		'desc' => esc_html__( 'Change the JPEG compression-level of uploaded images and thumbnails. Default is 90.', 'nicethemes' ),
		'id'   => $prefix . '_jpeg_quality',
		'std'  => array(
			'range' => 'min',
			'value' => '90',
			'min'   => '40',
			'max'   => '100',
			'unit'  => '%',
		),
		'type' => 'slider',
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Enable LazyLoad for images', 'nicethemes' ),
		'desc' => esc_html__( 'Only load images when they\'re about to enter the viewport while scrolling. This can make your pages load faster and help reduce server load.', 'nicethemes' ),
		'id'   => $prefix . '_lazyload_images',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox',
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Apply LazyLoad to logo images', 'nicethemes' ),
		'desc'      => esc_html__( 'Check if you want to apply LazyLoad to your logo images too. Not recommended if you want your logo to be displayed right away.', 'nicethemes' ),
		'id'        => $prefix . '_lazyload_images_logo',
		'std'       => 'false',
		'tip'       => '',
		'type'      => 'checkbox',
		'condition' => $prefix . '_lazyload_images:is(true)',
		'operator'  => 'and',
	);

	/**
	 * Performance > CSS
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'CSS', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_web-code"></i>',
		'ignore_customizer' => true,
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Load CSS asynchronously', 'nicethemes' ),
		'desc' => esc_html__( 'Check this option to load non-prioritary CSS files in separated threads, so your pages can load faster. Keep in mind that, when using this feature, some styling may be applied a little later than usual.', 'nicethemes' ),
		'id'   => $prefix . '_async_styles',
		'std'  => 'false',
		'tip'  => '',
		'type' => 'checkbox',
		'ignore_customizer' => true,
	);

	$nice_options[] = array(
		'name'      => esc_html__( 'Load Google Fonts asynchronously', 'nicethemes' ),
		'desc'      => esc_html__( 'Check this option to load Google Fonts in a separated thread, so your pages can load faster. Keep in mind that some styling may be applied a little later than usual.', 'nicethemes' ),
		'id'        => $prefix . '_async_google_fonts',
		'std'       => 'false',
		'tip'       => '',
		'type'      => 'checkbox',
		'condition' => $prefix . '_async_styles:is(true)',
		'operator'  => 'and',
		'ignore_customizer' => true,
	);

	$nice_options[] = array(
		'name' => esc_html__( 'JavaScript', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_web-code"></i>',
		'ignore_customizer' => true,
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Load JavaScript files asynchronously', 'nicethemes' ),
		'desc' => esc_html__( 'Load some JavaScript files in different threads, and only load dependencies when they are needed. This can make your pages load faster and help reduce server load.', 'nicethemes' ),
		'id'   => $prefix . '_load_js_on_demand',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox',
		'ignore_customizer' => true,
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Load minified JavaScript files', 'nicethemes' ),
		'desc' => esc_html__( 'Reduce JavaScript filesize by loading processed files instead of their development versions. This can make your pages load faster and help reduce server load. Disabling this option is not recommended for production websites.', 'nicethemes' ),
		'id'   => $prefix . '_load_minified_js',
		'std'  => 'true',
		'tip'  => '',
		'type' => 'checkbox',
		'ignore_customizer' => true,
	);

	/**
	 * Performance > Advanced
	 */

	$nice_options[] = array(
		'name' => esc_html__( 'Advanced', 'nicethemes' ),
		'type' => 'group',
		'icon' => '<i class="bi_music-eq-a"></i>',
		'ignore_customizer' => true,
	);

	$nice_options[] = array(
		'name' => esc_html__( 'Enable Development Mode', 'nicethemes' ),
		'desc' => esc_html__( 'Development mode can help you debug potential issues while building your site. It is not recommended for production websites. If you run into an issue and create a support ticket, we may ask you to temporarily activate this setting.', 'nicethemes' ),
		'id'   => $prefix . '_development_mode',
		'std'  => 'false',
		'tip'  => '',
		'type' => 'checkbox',
		'ignore_customizer' => true,
	);

	/**
	 * @hook nice_options
	 *
	 * Let other functions add, remove or modify options.
	 *
	 */
	$nice_options = apply_filters( 'nice_options', $nice_options );

	if ( serialize( get_option( 'nice_template' ) ) !== serialize( $nice_options ) ) {
		update_option( 'nice_template', $nice_options );
	}
}
endif;

if ( ! function_exists( 'nice_options_homepage_elements_after' ) ) :
add_action( 'nice_option_nice_homepage_elements_after', 'nice_options_homepage_elements_after' );
/**
 * Add a link to reset to default values for home page elements.
 *
 * @since  1.0.0
 *
 * @return string
 */
function nice_options_homepage_elements_after() {
	$output  = '<p>' . esc_html__( 'Available placeholders:', 'nicethemes' ) . '</p>';
	$output .= '<a style="cursor: pointer; text-decoration: underline;" id="nice-homepage-elements-reset-values" data-focus="#nice_homepage_elements" data-defaults="' . nice_options_homepage_default_elements() . '" onclick="jQuery( jQuery( this ).data( \'focus\' ) ).val( jQuery( this ).data( \'defaults\' ) );">' . esc_html__( 'Reset to default sections', 'nicethemes' ) . '</a>';

	return $output;
}
endif;

if ( ! function_exists( 'nice_options_homepage_default_elements' ) ) :
/**
 * Return default home page elements.
 *
 * @since  1.0.0
 *
 * @return string
 */
function nice_options_homepage_default_elements() {
	$default_elements = '';

	$elements = array(
		'content',
		'infoboxes',
		'knowledgebase',
		'knowledgebase_videos'
	);

	if ( post_type_exists( 'slide' ) ) {
		array_unshift( $elements, 'slides' );
	}

	/**
	 * @hook nice_options_homepage_default_elements
	 *
	 * Hook here to change the default home page elements.
	 *
	 * @since 1.0.0
	 */
	$elements = apply_filters( 'nice_options_homepage_default_elements', $elements );

	if ( ! empty( $elements ) && is_array( $elements ) ) {
		$formatted_elements = array();

		foreach ( $elements as $element ) {
			$formatted_elements[] = '[' . esc_attr( $element ) . ']';
		}
	}

	if ( ! empty( $formatted_elements ) ) {
		$default_elements = join( ' ', $formatted_elements );
	}

	return $default_elements;
}
endif;
