<?php
/**
 * NiceFramework by NiceThemes.
 *
 * Functions related to the meta boxes
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2015 NiceThemes
 * @since     1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_get_custom_fields' ) ) :
/**
 * Obtain the list of custom fields for the current post.
 *
 * @since 2.0.6
 */
function nice_get_custom_fields() {
	/**
	 * @hook nice_custom_fields_use_wp_option
	 *
	 * Use WP option to get the list of custom fields. This approach is not
	 * recommended anymore, and usage of the `nice_custom_fields` filter is
	 * preferred. However, the default value for this filter is set to `true`
	 * in order to provide backwards compatibility for themes that don't
	 * support the new approach yet.
	 *
	 * @since 2.0.6
	 */
	if ( apply_filters( 'nice_custom_fields_use_wp_option', true ) ) {
		return get_option( 'nice_custom_fields' );
	}

	/**
	 * @hook nice_custom_fields
	 *
	 * Hook in here to modify the list of custom fields for the current post.
	 *
	 * @since 2.0.6
	 */
	return apply_filters( 'nice_custom_fields', array(), get_post_type() );
}
endif;

if ( ! function_exists( 'nice_custom_get_info' ) ) :
/**
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array $field Option info in order return the html code.
 *
 * @return string with the text input.
 */
function nice_custom_get_info( $field ) {

	$id = nice_custom_get_id( $field );

	$output  = "\t" . '<div id="' . $id . '" class="nice-custom-info format-settings" >';
	$output .= "\t\t" . '<div>' . $field['desc']  . '</div>' . "\n";
	$output .= "\t" . '</div>' . "\n";

	return $output;
}
endif;


if ( ! function_exists( 'nice_custom_get_id' ) ) :
/**
 * Retrieve custom field ID for html purposes.
 *
 * @since 1.0.0
 *
 * @param array $field
 *
 * @return string ID
 */
function nice_custom_get_id( $field ) {

	return 'setting_' . $field['name'];
}
endif;


if ( ! function_exists( 'nice_custom_get_value' ) ) :
/**
 * Retrieve custom field value. If there's no value in db
 * it sets the standard value.
 *
 * @since    1.0.0
 * @updated  2.0
 *
 * @param $field
 *
 * @return string
 */
function nice_custom_get_value( $field ) {

	global $post;

	$db_value = get_post_meta( $post->ID, $field['name'], true );

	if ( $db_value ) {
		return $db_value;
	}

	return isset( $field['std'] ) ? $field['std'] : '';

}
endif;


if ( ! function_exists( 'nice_metabox_add' ) ) :
/**
 * Retrieve custom fields html. Print the result.
 *
 * @since 1.0.0
 * @updated 2.0
 *
 * @param $post
 * @param $callback
 */
function nice_metabox_add( $post, $callback ) {
	global $post;

	$nice_fields = nice_get_custom_fields();

	$output = $tabs = '';

	foreach ( $nice_fields as $key => $field ) :

		$nice_id = 'nicethemes_' . $field['name'];
		$nice_name = $field['name'];

		switch ( $field['type'] ) :

			case 'section':

				if ( $key >= 2 ) {
					$output .= '</div>' . "\n";
				}

				$tabs .= '<li>' . "\n" . '<a href="#section-' . $field['name'] . '">' ;
				if ( isset( $field['icon'] ) ) {
					$tabs .= $field['icon'];
				}
				$tabs .= $field['label'] . '</a></li>' . "\n";
				$output .= '<div id="section-' . $field['name'] . '" class="nice-tab-inner">' . "\n";

			break;

			default:

				$class = isset( $field['class'] ) ? $field['class'] : '';

				$conditions = nice_option_get_conditions( $field );

				$output .= '<div id="setting_' . $field['name'] . '" class="format-settings section section-' . $field['type'] . ' ' .  $class  . '"' . $conditions . '>' . "\n";
				$output .= '<div class="format-setting-wrap">';
				$field['id'] = $field['name'];

				if ( ! empty( $field['label'] ) ) {
					$output .= '<div class="format-setting-label">';
					$output .= '<label for="' . $field['id'] . '" class="label">' . $field['label'] . '</label>';
					$output .= '</div>';
				}

				// set field value
				$field['value'] = nice_custom_get_value( $field );

				if ( 'checkbox' !== $field['type'] ) {
					$output .= '<div class="format-setting-input">';
				}

				$output .= nice_option_do( $field );

				if ( 'checkbox' !== $field['type'] ) {
					$output .= '</div>';
				}

				if ( ! ( 'info' === $field['type'] ) ) {
					$desc_class = 'format-setting-description ';

					if ( 'checkbox' === $field['type'] ) {
						$desc_class = 'explain-checkbox';
					}

					$description = isset( $field['desc'] ) ? $field['desc'] : '';

					$output .= '<div id="nice-help-' . $field['id'] . '" class="' . $desc_class . '">';

					if ( 'checkbox' === $field['type'] ) {
						$output .= '<label for="' . $field['id'] . '">' .  $description  . '</label>';
					} else {
						$output .= $description;
					}

					$output .= '</div>';
				}

				$output .= '</div>' . "\n";
				$output .= '</div>' . "\n";

		endswitch;

	endforeach;

	if ( ! $tabs ) {
		$output = '<div id="nice-metaboxes" class="nice-container nice-metaboxes">' . $output . '</div>'  . "\n";
	} else {
		$tabs   = '<ul class="nice-nav" role="tablist">' . $tabs . '</ul>' . "\n";
		$output .= '</div>' . "\n";
		$output  = '<div id="nice-metaboxes-tabs" class="nice-container nice-metaboxes nice-tabs-vertical"><div class="nice-metabox-nav">' . $tabs . '</div><div class="nice-metabox-panels">' . $output . '</div></div>';
	}

	echo $output;

}
endif;


if ( ! function_exists( 'nice_metaboxes_init' ) ) :
/**
 * Add meta boxes for each post type.
 *
 * @since 1.0.0
 * @updated 2.0
 */
function nice_metaboxes_init() {
	$post_types = get_post_types();

	$nice_fields = nice_get_custom_fields();

	$nice_theme = wp_get_theme();

	foreach ( $post_types as $type ) :

		$metabox_title = apply_filters( 'nice_metabox_title', sprintf( esc_html__( '%s Settings', 'nice-framework' ), $nice_theme->get( 'Name' ) ), $type );

		$settings = array(
							'id'            => 'nicethemes-settings',
							'title'         => $metabox_title,
							'callback'      => 'nice_metabox_add',
							'page'          => $type,
							'priority'      => 'normal',
							'callback_args' => '',
						);

		if ( ! empty ( $nice_fields ) ) {
			add_meta_box( $settings['id'], $settings['title'], $settings['callback'], $settings['page'], $settings['priority'], $settings['callback_args'] );
		}

	endforeach;

}
endif;


if ( ! function_exists( 'nice_metabox_save_data' ) ) :
add_action( 'edit_post' , 'nice_metabox_save_data' );
/**
 * nice_metabox_save_data()
 *
 * Saves data for custom fields
 *
 * @since 1.0.0
 * @updated 2.0
 *
 * @return int
 */
function nice_metabox_save_data() {

	global $globals, $post;

	$id = '';

	if ( isset( $_POST['post_ID'] ) ) {

		$id = intval( $_POST['post_ID'] );

	}

	// Don't continue if we don't have a valid post ID.

	if ( ! ( 0 < $id ) ) {

		return;

	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $id;
	}

	if ( 'page' === $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $id ) ) {
			return $id;
		}
	} elseif ( ! current_user_can( 'edit_post', $id ) ) {
		return $id;
	}

	if ( isset( $_POST['action'] ) && ( 'editpost' === $_POST['action'] ) ) {

		$nice_fields = nice_get_custom_fields();

		foreach ( $nice_fields as $field ) {

			if ( 'info' !== $field['type'] ) {

				$old_value = '';
				$old_value = get_post_meta( $id, $field['name'], true );

				if ( isset( $_POST[$field['name'] ] ) ) {

					$new_value = '';
					$new_value = $_POST[ $field['name'] ];

					if ( $new_value && $new_value !== $old_value ) {
						update_post_meta( $id, $field['name'], $new_value );
					} elseif ( empty( $new_value ) && $old_value ) {
						delete_post_meta( $id, $field['name'], $old_value );
					} elseif ( empty( $old_value ) ) {
						add_post_meta( $id, $field['name'], $new_value, true );
					}

				} elseif ( 'checkbox' === $field['type'] && ! isset( $_POST[ $field['name'] ] ) ) {

					delete_post_meta( $id, $field['name'], $old_value );

				} else {

					if ( isset( $_POST[ $field['name'] ] ) ) {
						$new_value = $_POST[ $field['name'] ];
						update_post_meta( $id, $field['name'], $new_value );
					}
				}
			}
		}
	}
}
endif;


if ( ! function_exists( 'nice_metaboxes' ) ) :
add_action( 'add_meta_boxes', 'nice_metaboxes' );
/**
 * Init metaboxes action.
 *
 * @since 1.0.0
 */
function nice_metaboxes() {

	nice_metaboxes_init();

}
endif;
