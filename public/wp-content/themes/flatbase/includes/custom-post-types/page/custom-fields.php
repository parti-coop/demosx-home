<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains the function to hook custom fields for pages.
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

if ( ! function_exists( 'nice_custom_fields_page' ) ) :
add_filter( 'nice_custom_fields', 'nice_custom_fields_page', 10, 2 );
/**
 * Load array with custom fields for pages.
 * then save the array into WP options.
 *
 * @since  2.0
 *
 * @param  array  $nice_fields
 * @param  string $post_type
 *
 * @return array
 */
function nice_custom_fields_page( array $nice_fields = array(), $post_type ) {
	if ( 'page' === $post_type ) {
		/**
		 * Header
		 */
		$nice_fields[] = array(
			'name'  => 'page-header',
			'label' => esc_html__( 'Header', 'nicethemes' ),
			'type'  => 'section',
			'icon'  => '<i class="bi_layout-header"></i>',
		);


		$nice_fields[] = array(
			'name'      => '_post_navigation_menu',
			'std'       => '',
			'label'     => esc_html__( 'Navigation Menu', 'nicethemes' ),
			'type'      => 'select',
			'options'   => nice_get_navigation_menus(),
			'desc'      => esc_html__( 'Select the navigation menu to use for this page.', 'nicethemes' ),
		);


		$nice_fields[] = array(
			'name'      => '_post_header_skin',
			'std'       => '',
			'label'     => esc_html__( 'Navigation Content Skin', 'nicethemes' ),
			'type'      => 'select',
			'options'   => array(
				''      => esc_html__( 'Inherit', 'nicethemes' ),
				'light' => esc_html__( 'Light', 'nicethemes' ),
				'dark'  => esc_html__( 'Dark', 'nicethemes' ),
			),
			'desc'      => esc_html__( 'Select the header content skin to use for this page navigation menu.', 'nicethemes' ),
		);

		$nice_fields[] = array(
			'name'      => '_post_navigation_submenu_skin',
			'std'       => '',
			'label'     => esc_html__( 'Navigation Sub-Menu Content Skin', 'nicethemes' ),
			'type'      => 'select',
			'options'   => array(
				''      => esc_html__( 'Inherit', 'nicethemes' ),
				'light' => esc_html__( 'Light', 'nicethemes' ),
				'dark'  => esc_html__( 'Dark', 'nicethemes' ),
			),
			'desc'      => esc_html__( 'Select the navigation sub-menu content skin to use for this page.', 'nicethemes' ),
		);

		$nice_fields[] = array(
			'name'      => '_post_header_background_color',
			'std'       => '',
			'label'     => esc_html__( 'Header Background Color', 'nicethemes' ),
			'type'      => 'select_color',
			'options'   => nice_theme_colors_dropdown_values(),
			'desc'      => esc_html__( 'Choose a background color.', 'nicethemes' ),
			'class'     => 'nice-select-color',
		);

		$nice_fields[] = array(
			'name'      => '_post_header_background_image',
			'std'       => '',
			'label'     => esc_html__( 'Header Background Image', 'nicethemes' ),
			'type'      => 'upload',
			'desc'      => esc_html__( 'Upload/select a background image.', 'nicethemes' ),
		);


		$nice_fields[] = array(
			'name'      => '_post_header_background_image_repeat',
			'std'       => '',
			'label'     => esc_html__( 'Background Image Repeat', 'nicethemes' ),
			'type'      => 'select',
			'options' => array(
				''          => esc_html__( 'Inherit', 'nicethemes' ),
				'no-repeat' => esc_html__( 'No Repeat', 'nicethemes' ),
				'repeat'    => esc_html__( 'Repeat All', 'nicethemes' ),
				'repeat-x'  => esc_html__( 'Repeat Horizontally', 'nicethemes' ),
				'repeat-y'  => esc_html__( 'Repeat Vertically', 'nicethemes' ),
			),
			'desc'      => esc_html__( 'Select how your background image should be repeated.', 'nicethemes' ),
			'condition' => '_post_header_background_image:not()',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'      => '_post_header_background_image_position',
			'std'       => '',
			'label'     => esc_html__( 'Header Background Image Position', 'nicethemes' ),
			'type'      => 'select',
			'options' => array(
				''              => esc_html__( 'Inherit', 'nicethemes' ),
				'left top'      => esc_html__( 'Left Top', 'nicethemes' ),
				'left center'   => esc_html__( 'Left Center', 'nicethemes' ),
				'left bottom'   => esc_html__( 'Left Bottom', 'nicethemes' ),
				'center top'    => esc_html__( 'Center Top', 'nicethemes' ),
				'center center' => esc_html__( 'Center Center', 'nicethemes' ),
				'center bottom' => esc_html__( 'Center Bottom', 'nicethemes' ),
				'right top'     => esc_html__( 'Right Top', 'nicethemes' ),
				'right center'  => esc_html__( 'Right Center', 'nicethemes' ),
				'right bottom'  => esc_html__( 'Right Bottom', 'nicethemes' ),
			),
			'desc'      => esc_html__( 'Select where your background image should be positioned.', 'nicethemes' ),
			'condition' => '_post_header_background_image:not()',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'      => '_post_header_background_image_size',
			'std'       => '',
			'label'     => esc_html__( 'Header Background Image Size', 'nicethemes' ),
			'type'      => 'select',
			'options' => array(
				''        => esc_html__( 'Inherit', 'nicethemes' ),
				'auto'    => esc_html__( 'Auto', 'nicethemes' ),
				'cover'   => esc_html__( 'Cover', 'nicethemes' ),
				'contain' => esc_html__( 'Contain', 'nicethemes' ),
			),
			'desc'      => esc_html__( 'Select the size for you background image.', 'nicethemes' ),
			'condition' => '_post_header_background_image:not()',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'      => '_post_header_background_image_attachment',
			'std'       => '',
			'label'     => esc_html__( 'Header Background Image Attachment', 'nicethemes' ),
			'type'      => 'select',
			'options' => array(
				''        => esc_html__( 'Inherit', 'nicethemes' ),
				'scroll'  => esc_html__( 'Scroll', 'nicethemes' ),
				'fixed'   => esc_html__( 'Fixed', 'nicethemes' ),
			),
			'desc'      => esc_html__( 'Select how your background image should be attached to the page.', 'nicethemes' ),
			'condition' => '_post_header_background_image:not()',
			'operator'  => 'and',
		);

		/**
		 * Content
		 */

		$nice_fields[] = array(
			'name'  => 'page-content',
			'label' => esc_html__( 'Content', 'nicethemes' ),
			'type'  => 'section',
			'icon'  => '<i class="bi_doc-binder-three"></i>',
		);

		$nice_fields[] = array(
			'name'  => 'page-info',
			'label' => __( 'Page Information', 'nicethemes' ),
			'type'  => 'info',
			'desc'  => __( '<p>You can select the page template from the "Template" dropdown on the "Page Attributes" box.</p><p>If you are using the "Gallery Template" plase be sure to upload images from the "Add Media" button at the top of this page. (Don\'t insert the gallery in the page content, just add the images).</p>', 'nicethemes' )
		);

		$nice_fields[] = array(
			'name'    => '_post_content_skin',
			'std'     => '',
			'label'   => esc_html__( 'Content Skin', 'nicethemes' ),
			'type'    => 'select',
			'options' => array(
				''      => esc_html__( 'Inherit', 'nicethemes' ),
				'light' => esc_html__( 'Light', 'nicethemes' ),
				'dark'  => esc_html__( 'Dark', 'nicethemes' ),
			),
			'desc'    => esc_html__( 'Select the content skin for this page.', 'nicethemes' ),
		);

		$nice_fields[] = array(
			'name'    => '_post_title_content',
			'std'     => 'true',
			'label'   => esc_html__( 'Display Page Title in Content', 'nicethemes' ),
			'type'    => 'radio_on_off',
			'options' => array(
				'true'  => array( 'label' => esc_html__( 'On', 'nicethemes' ) ),
				'false' => array( 'label' => esc_html__( 'Off', 'nicethemes' ) ),
			),
			'desc'    => esc_html__( 'Show the page title in the content area.', 'nicethemes' ),
		);

		$nice_fields[] = array(
			'name'    => '_post_title_content_breadcrumbs',
			'std'     => 'true',
			'label'   => esc_html__( 'Display Bradcrumbs with Title', 'nicethemes' ),
			'type'    => 'radio_on_off',
			'options' => array(
				'true'  => array( 'label' => esc_html__( 'On', 'nicethemes' ) ),
				'false' => array( 'label' => esc_html__( 'Off', 'nicethemes' ) ),
			),
			'desc'    => esc_html__( 'Show the breadcrumbs with the title.', 'nicethemes' ),
			'condition' => '_post_title_content:is(true)',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'    => '_post_title_content_breadcrumbs_position',
			'std'     => '',
			'label'   => esc_html__( 'Breadcrumbs Position', 'nicethemes' ),
			'type'    => 'select',
			'options' => array(
				''      => esc_html__( 'Inherit', 'nicethemes' ),
				'before' => esc_html__( 'Before', 'nicethemes' ),
				'after'  => esc_html__( 'After', 'nicethemes' ),
			),
			'desc'    => esc_html__( 'Select the breadcrumbs position.', 'nicethemes' ),
			'condition' => '_post_title_content:is(true),_post_title_content_breadcrumbs:is(true)',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'      => '_post_title_text_transform',
			'std'       => '',
			'label'     => esc_html__( 'Title Text Transform', 'nicethemes' ),
			'type'      => 'select',
			'options'   => array(
				''           => esc_html__( 'Inherit', 'nicethemes' ),
				'none'       => esc_html__( 'Default CSS', 'nicethemes' ),
				'uppercase'  => esc_html__( 'Uppercase', 'nicethemes' ),
				'lowercase'  => esc_html__( 'Lowercase', 'nicethemes' ),
				'capitalize' => esc_html__( 'Capitalize', 'nicethemes' ),
			),
			'desc'      => esc_html__( 'Select the title text transformation.', 'nicethemes' ),
			'condition' => '_post_title_content:is(true)',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'      => '_post_title_font_weight',
			'std'       => '',
			'label'     => esc_html__( 'Title Font Weight', 'nicethemes' ),
			'type'      => 'select',
			'options'   => nice_font_weight(),
			'desc'      => esc_html__( 'Select the title font weight.', 'nicethemes' ),
			'condition' => '_post_title_content:is(true)',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'      => '_post_title_font_size',
			'std'       => '',
			'label'     => esc_html__( 'Title Font Size', 'nicethemes' ),
			'type'      => 'select',
			'options'   => array(
				''             => esc_html__( 'Inherit', 'nicethemes' ),
				'h1'           => esc_html__( 'h1', 'nicethemes' ),
				'h2'           => esc_html__( 'h2', 'nicethemes' ),
				'h3'           => esc_html__( 'h3', 'nicethemes' ),
				'h4'           => esc_html__( 'h4', 'nicethemes' ),
				'h5'           => esc_html__( 'h5', 'nicethemes' ),
				'h6'           => esc_html__( 'h6', 'nicethemes' ),
				'fontsize-12'  => esc_html__( 'Size 12px', 'nicethemes' ),
				'fontsize-40'  => esc_html__( 'Size 40px', 'nicethemes' ),
				'fontsize-50'  => esc_html__( 'Size 50px', 'nicethemes' ),
				'fontsize-75'  => esc_html__( 'Size 75px', 'nicethemes' ),
				'fontsize-100' => esc_html__( 'Size 100px', 'nicethemes' ),
				'fontsize-125' => esc_html__( 'Size 125px', 'nicethemes' ),
				'fontsize-150' => esc_html__( 'Size 150px', 'nicethemes' ),
			),
			'desc'      => esc_html__( 'Select the title font size.', 'nicethemes' ),
			'condition' => '_post_title_content:is(true)',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'      => '_post_content_width',
			'std'       => '',
			'label'     => esc_html__( 'Content Width', 'nicethemes' ),
			'type'      => 'select',
			'options'   => array(
				''      => esc_html__( 'Inherit', 'nicethemes' ),
				'full'  => esc_html__( 'Full', 'nicethemes' ),
				'limit' => esc_html__( 'Limit', 'nicethemes' ),
			),
			'desc'      => esc_html__( 'Select whether or not to limit the content width for this page.', 'nicethemes' ),
		);

		$nice_fields[] = array(
			'name'      => '_post_content_width_value',
			'std'       => '',
			'label'     => esc_html__( 'Custom Width', 'nicethemes' ),
			'type'      => 'measurement',
			'desc'      => esc_html__( 'Define the custom width for the content area (in px or in %).', 'nicethemes' ),
			'condition' => '_post_content_width:is(limit)',
			'operator'  => 'and',
		);

		/**
		 * Sidebar
		 */

		$nice_fields[] = array(
			'name'  => 'page-sidebar',
			'label' => esc_html__( 'Sidebar', 'nicethemes' ),
			'type'  => 'section',
			'icon'  => '<i class="bi_layout-sidebar-r-a"></i>',
		);

		$nice_fields[] = array(
			'name'      => '_post_sidebar',
			'std'       => 'true',
			'label'     => esc_html__( 'Display Sidebar', 'nicethemes' ),
			'type'      => 'radio_on_off',
			'desc'      => esc_html__( 'Show a sidebar for this page.', 'nicethemes' ),
			'options'   => array(
				'true'  => array( 'label' => esc_html__( 'On', 'nicethemes' ) ),
				'false' => array( 'label' => esc_html__( 'Off', 'nicethemes' ) ),
			),
		);

		$nice_fields[] = array(
			'name'      => '_post_sidebar_id',
			'std'       => '',
			'label'     => esc_html__( 'Select Sidebar', 'nicethemes' ),
			'type'      => 'select_sidebar',
			'desc'      => esc_html__( 'Select the sidebar for this page.', 'nicethemes' ),
			'condition' => '_post_sidebar:is(true)',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'label'     => esc_html__( 'Sidebar Position', 'nicethemes' ),
			'desc'      => esc_html__( 'Set the position of the sidebar for this page.', 'nicethemes' ),
			'name'      => '_post_sidebar_position',
			'type'      => 'radio_image',
			'std'       => 'right',
			'tip'       => '',
			'options'   => array(
				'right' => array(
					'label' => esc_html__( 'Right Sidebar', 'nicethemes' ),
					'image' => nice_get_file_uri( 'engine/admin/assets/images/layout/right-sidebar.png' ),
				),
				'left'  => array(
					'label' => esc_html__( 'Left Sidebar', 'nicethemes' ),
					'image' => nice_get_file_uri( 'engine/admin/assets/images/layout/left-sidebar.png' ),
				),
			),
			'condition' => '_post_sidebar:is(true)',
			'operator'  => 'and',
		);

		/**
		 * Footer
		 */

		$nice_fields[] = array(
			'name'  => 'page-footer',
			'label' => esc_html__( 'Footer', 'nicethemes' ),
			'type'  => 'section',
			'icon'  => '<i class="bi_layout-footer"></i>',
		);

		$nice_fields[] = array(
			'name'    => '_post_footer',
			'std'     => 'true',
			'label'   => esc_html__( 'Display Footer', 'nicethemes' ),
			'type'    => 'radio_on_off',
			'desc'    => esc_html__( 'Show the footer for this page.', 'nicethemes' ),
			'options' => array(
				'true'  => array( 'label' => esc_html__( 'On', 'nicethemes' ) ),
				'false' => array( 'label' => esc_html__( 'Off', 'nicethemes' ) ),
			),
		);

		$nice_fields[] = array(
			'name'    => '_post_footer_skin',
			'std'     => '',
			'label'   => esc_html__( 'Footer Skin', 'nicethemes' ),
			'type'    => 'select',
			'options' => array(
				''      => esc_html__( 'Inherit', 'nicethemes' ),
				'dark'  => esc_html__( 'Dark', 'nicethemes' ),
				'light' => esc_html__( 'Light', 'nicethemes' ),
			),
			'desc'    => esc_html__( 'Select the footer skin for this page.', 'nicethemes' ),
			'condition' => '_post_footer:is(true)',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'      => '_post_footer_widgets',
			'std'       => 'true',
			'label'     => esc_html__( 'Display Footer Widgets Area', 'nicethemes' ),
			'type'      => 'radio_on_off',
			'desc'      => esc_html__( 'Show the footer widgets area for this page.', 'nicethemes' ),
			'options'   => array(
				'true'  => array( 'label' => esc_html__( 'On', 'nicethemes' ) ),
				'false' => array( 'label' => esc_html__( 'Off', 'nicethemes' ) ),
			),
			'condition' => '_post_footer:is(true)',
			'operator'  => 'and',
		);

		$nice_fields[] = array(
			'name'      => '_post_footer_extended',
			'std'       => 'true',
			'label'     => esc_html__( 'Display Copyright Area', 'nicethemes' ),
			'type'      => 'radio_on_off',
			'desc'      => esc_html__( 'Show the footer copyright area for this page.', 'nicethemes' ),
			'options'   => array(
				'true'  => array( 'label' => esc_html__( 'On', 'nicethemes' ) ),
				'false' => array( 'label' => esc_html__( 'Off', 'nicethemes' ) ),
			),
			'condition' => '_post_footer:is(true)',
			'operator'  => 'and',
		);

	}

	return $nice_fields;
}
endif;
