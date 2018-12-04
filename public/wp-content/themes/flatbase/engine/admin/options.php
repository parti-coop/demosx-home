<?php
/**
 * NiceFramework by NiceThemes.
 *
 * Functions related to options fields
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

if ( ! function_exists( 'nice_options_reset' ) && isset( $_GET['nice_reset'] ) ) :
add_action( 'after_setup_theme', 'nice_options_reset', -2 );
/**
 * Wipe all theme options if required.
 *
 * @since 2.0
 */
function nice_options_reset() {
	// Return early if we're not running in development mode.
	if ( ! nice_development_mode() ) {
		return;
	}

	// Return early if the nonce is not valid.
	if ( ! isset( $_GET['nice_reset'] ) || ! wp_verify_nonce( wp_unslash( $_GET['nice_reset'] ), 'nice_reset_options' ) ) {
		return;
	}

	// Reset options.
	update_option( 'nice_options', nice_default_options() );

	// Schedule notice.
	add_action( 'admin_notices', 'nice_options_reset_notice' );
}
endif;


if ( ! function_exists( 'nice_options_reset_notice' ) ) :
/**
 * Add a notice after resetting options.
 *
 * @since 2.0
 */
function nice_options_reset_notice() {
	// Return early if not running in development mode.
	if ( ! nice_development_mode() ) {
		return;
	}

	?>
	<div class="notice updated reset nice-wp-notice is-dismissible" >
		<p><?php esc_html_e( 'All theme options were reset to defaults.', 'nice-framework' ); ?></p>
	</div>
	<?php
}
endif;

/**
 * Return the default value of a theme setting before it's been saved to the
 * database.
 *
 * @since  2.0.9
 *
 * @param  string $option_name
 *
 * @return mixed|null
 */
function nice_default_setting( $option_name ) {
	static $default_settings = null;

	if ( is_null( $default_settings ) ) {
		/**
		 * @hook nice_default_settings
		 *
		 * Hook in here to define your theme's default settings.
		 *
		 * This hook is supposed to run only once to set all default values
		 * when the function is called for the first time.
		 *
		 * @since 2.0.9
		 */
		$default_settings = apply_filters( 'nice_default_settings', array() );

		foreach ( $default_settings as $key => $value ) {
			$original_key = $key;
			_nice_clean_option_name( $key );

			if ( $original_key === $key ) {
				continue;
			}

			unset( $default_settings[ $original_key ] );
			$default_settings[ $key ] = $value;
		}
	}

	_nice_clean_option_name( $option_name );

	return isset( $default_settings[ $option_name ] ) ? $default_settings[ $option_name ] : null;
}

if ( ! function_exists( 'nice_options_template' ) ) :
/**
 * Obtain and set options template.
 *
 * @since  2.0
 *
 * @param  array $template If set, the template will be updated with the given array.
 *
 * @return array
 */
function nice_options_template( array $template = null ) {
	static $options_template;

	if ( ! empty( $template ) ) {
		update_option( 'nice_template', $template );

		$options_template = null;
	}

	if ( is_null( $options_template ) ) {
		$options_template = get_option( 'nice_template' );
	}

	return $options_template;
}
endif;

/**
 * Set default value to an option array without an `std` argument.
 *
 * @since 2.0.9
 *
 * @private
 *
 * @param array $option
 */
function _nice_set_option_default( &$option ) {
	static $ignore_std = null;

	/**
	 * @hook _nice_option_default_ignore_std
	 *
	 * @private
	 *
	 * Set to true if you prefer to ignore all `std` arguments and use default
	 * settings instead. Recommended only for testing purposes.
	 */
	is_null( $ignore_std ) && ( $ignore_std = apply_filters( '_nice_option_default_ignore_std', false ) );

	if ( isset( $option['std'] ) && $ignore_std ) {
		unset( $option['std'] );
	}

	if ( isset( $option['std'] ) || ! isset( $option['id'] ) ) {
		return;
	}

	if ( $default = nice_default_setting( $option['id'] ) ) {
		$option['std'] = $default ? : null;
	}
}

/**
 * Normalize option names without a prefix.
 *
 * @since 2.0.9
 *
 * @private
 *
 * @param string $option_name
 */
function _nice_clean_option_name( &$option_name ) {
	if ( ! defined( 'NICE_PREFIX' ) || 0 === stripos( $option_name, NICE_PREFIX ) ) {
		return;
	}

	$option_name = str_replace( '__', '_', NICE_PREFIX . '_' . $option_name );
}

if ( ! function_exists( 'nice_default_options' ) ) :
/**
 * Obtain default values for all options.
 *
 * @since 2.0
 *
 * @return array
 */
function nice_default_options() {
	$template_data   = nice_options_template();
	$default_options = array();

	if ( ! empty( $template_data ) ) {
		foreach ( $template_data as $template_entry ) {
			// Make sure the current option has an `std` argument if a default has been set.
			_nice_set_option_default( $template_entry );

			if ( isset( $template_entry['id'] ) && isset( $template_entry['std'] ) ) {
				if ( 'slider' === $template_entry['type'] ) {
					$std_value = isset ( $template_entry['std']['value'] ) ? $template_entry['std']['value'] : '' ;
					$default_options[ $template_entry['id'] ] = $std_value;

				} elseif ( 'list_item' === $template_entry['type'] ) {
					$item_ids_names = array();

					if ( isset( $template_entry['std'] ) ) {

						foreach ( (array) $template_entry['std'] as $item ) {
							$item_ids_names[ $item['id'] ] = $item['name'];

							if ( isset( $template_entry['settings'] ) ) {
								foreach ( (array) $template_entry['settings'] as $setting ) {
									if ( isset( $item['std'][ $setting['id'] ] ) ) {
										$setting_std = $item['std'][ $setting['id'] ];
									} elseif ( isset( $setting['std'] ) ) {
										$setting_std = $setting['std'];
									}

									if ( 'default' === $setting['id'] ) {
										$setting_id = $item['id'];
									} else {
										$setting_id = $item['id'] . '_' . $setting['id'];
									}

									if ( ! empty( $setting_std ) ) {
										$default_options[ $setting_id ] = $setting_std;
									}
								}
							}
						}
					}

					$default_options[ $template_entry['id'] ] = $item_ids_names;

				} else {
					$default_options[ $template_entry['id'] ] = $template_entry['std'];
				}
			}
		}
	}

	return $default_options;
}
endif;


if ( ! function_exists( 'nice_options_update_customizer' ) ) :
add_filter( 'option_nice_options', 'nice_options_update_customizer' );
add_filter( 'default_option_nice_options', 'nice_options_update_customizer' );
/**
 * Make sure options are always updated when using the Customizer.
 *
 * @since  2.0
 *
 * @param  array $options
 *
 * @return array
 */
function nice_options_update_customizer( $options ) {
	// Return early if we're not in Customizer context.
	if ( ! is_customize_preview() ) {
		return $options;
	}

	static $saved = false;

	$status = isset( $_REQUEST['customize_changeset_status'] ) ? wp_unslash( $_REQUEST['customize_changeset_status'] ) : null;
	$uuid   = ! empty( $_REQUEST['customize_changeset_uuid'] ) ? wp_unslash( $_REQUEST['customize_changeset_uuid'] ) : null;

	// Return early if we don't have a session ID.
	if ( ! $uuid ) {
		return $options;
	}

	$customized = array();

	// Try to obtain values to be modified.
	if ( ! empty( $_REQUEST['customize_changeset_data'] ) ) {
		$data = json_decode( wp_unslash( $_REQUEST['customize_changeset_data'] ), true );

		foreach ( $data as $key => $change ) {
			$customized[ $key ] = $change['value'];
		}
	} elseif ( ! empty( $_REQUEST['customized'] ) ) {
		$customized = json_decode( wp_unslash( $_REQUEST['customized'] ), true );
	}

	/**
	 * If there are no values to modify, reset the session. This should only
	 * happen when the Customizer is loaded and not upon further modifications.
	 */
	if ( empty( $customized ) ) {
		nice_customizer_session_reset( $uuid );
	}

	// Return early if the Customizer is not active.
	if ( ! isset( $_REQUEST['wp_customize'] ) ) {
		return $options;
	}

	// Return early if the Customizer is not active.
	if ( 'on' !== $_REQUEST['wp_customize'] ) {
		return $options;
	}

	// Modify original options.
	$options = nice_options_customizer_changeset( $uuid, $options, $customized );

	/**
	 * If the Customizer is saving, make sure to update options in the DB.
	 */
	if ( ! $saved && 'publish' === $status ) {
		// Temporarily remove filters, so we don't fall in a loop.
		remove_filter( 'option_nice_options', __FUNCTION__ );
		remove_filter( 'default_option_nice_options', __FUNCTION__ );

		// Update DB.
		update_option( 'nice_options', $options );

		// Mark options as saved.
		$saved = true;

		// Restore filters.
		add_filter( 'option_nice_options', __FUNCTION__ );
		add_filter( 'default_option_nice_options', __FUNCTION__ );
	}

	return $options;
}
endif;

if ( ! function_exists( 'nice_options_customizer_changeset' ) ) :
/**
 * Obtain options for Customizer preview.
 *
 * @since 2.0.6
 *
 * @param string $uuid    Customizer session ID.
 * @param array  $options Current theme options.
 * @param array  $changes Modifications introduced through the Customizer.
 *
 * @return array
 */
function nice_options_customizer_changeset( $uuid, $options, $changes ) {
	if ( ! is_customize_preview() || ! $uuid ) {
		return $options;
	}

	if ( ! session_id() ) {
		session_start();
	}

	if ( empty( $_SESSION['nice_customize'][ $uuid ] ) ) {
		$_SESSION['nice_customize'][ $uuid ] = array();
	}

	$customized = array_merge( $_SESSION['nice_customize'][ $uuid ], $changes );

	$_SESSION['nice_customize'][ $uuid ] = $customized;

	foreach ( $customized as $key => $value ) {
		$key = str_replace( 'nice_options[', '', $key );
		$key = str_replace( ']', '', $key );

		$options[ $key ] = $value;
	}

	return $options;
}
endif;

if ( ! function_exists( 'nice_customizer_session_reset' ) ) :
/**
 * Reset Customizer session.
 *
 * @since 2.0.6
 *
 * @param string $uuid Customizer session identifier.
 */
function nice_customizer_session_reset( $uuid ) {
	if ( ! session_id() ) {
		session_start();
	}

	unset( $_SESSION['nice_customize'][ $uuid ] );
}
endif;

if ( ! function_exists( 'nice_get_options' ) ) :
/**
 * Obtain list of theme options and its current values.
 *
 * @since  2.0
 */
function nice_get_options() {
	return get_option( 'nice_options' );
}
endif;

if ( ! function_exists( 'nice_get_option_default' ) ) :
/**
 * Obtain the default value for a given option.
 *
 * @since  2.0
 *
 * @param  string $option_name
 *
 * @return mixed|null
 */
function nice_get_option_default( $option_name ) {
	static $default_options;

	if ( is_null( $default_options ) ) {
		$default_options = nice_default_options();
	}

	return isset( $default_options[ $option_name ] ) ? $default_options[ $option_name ] : null;
}
endif;

if ( ! function_exists( 'nice_get_option' ) ) :
/**
 * Obtain the current value for a specific theme option.
 *
 * @since  2.0
 *
 * @param  string $option  Name of the option.
 * @param  mixed  $default Default value if the option is not set.
 *
 * @return mixed
 */
function nice_get_option( $option, $default = null ) {
	static $options;

	if ( is_null( $options ) ) {
		$options = nice_get_options();
	}

	_nice_clean_option_name( $option );

	// Normalize option name if we received an array.
	if ( is_array( $option ) ) {
		if ( isset( $option['id'] ) ) {
			$option = $option['id'];
		} else { // Return early if we don't have a name for the option.
			return null;
		}
	}

	// Obtain the current value of the option, or its default.
	if ( ! empty( $options[ $option ] ) && ! is_null( $options[ $option ] ) ) {
		$option_value = $options[ $option ];
	} elseif ( isset( $options[ $option ] ) && in_array( $options[ $option ], array( '', '0', 0, 'false', false ), true ) ) {
		$option_value = $options[ $option ];
	} elseif ( null !== ( $default_saved_value = nice_get_option_default( $option ) ) ) {
		$option_value = $default_saved_value ? : null;
	} elseif ( ! is_null( $default ) ) {
		$option_value = $default;
	} else {
		$option_value = null;
	}

	/**
	 * @hook nice_option
	 *
	 * General hook to modify options.
	 *
	 * @since 2.0
	 */
	$option_value = apply_filters( 'nice_option', $option_value, $option );

	/**
	 * @hook nice_option_{option_name}
	 *
	 * Hook to modify specific options.
	 *
	 * @since 2.0
	 */
	$option_value = apply_filters( 'nice_option_' . $option, $option_value );

	return $option_value;
}
endif;

if ( ! function_exists( 'nice_get_option_type' ) ) :
/**
 * Obtain option type given an option's name.
 *
 * @since 2.0.9
 */
function nice_get_option_type( $name ) {
	static $options = array();
	$type = null;

	if ( empty( $options ) ) {
		$options_template = nice_options_template();

		foreach ( $options_template as $key => $option ) {
			isset( $option[ 'id' ] ) && ( $options[ $option[ 'id' ] ] = $option );
		}
	}

	if ( isset( $options[ $name ] ) ) {
		$type = isset( $options[ $name ]['type'] ) ? $options[ $name ]['type'] : $type;
	}

	return $type;
}
endif;

if ( ! function_exists( 'nice_bool_option' ) ) :
/**
 * Check if an option has a boolean value.
 *
 * @since 1.0.0
 *
 * @uses   nice_bool()
 * @uses   nice_get_option()
 *
 * @param  string $option
 * @param  mixed  $default
 *
 * @return bool
 */
function nice_bool_option( $option, $default = null ) {
	return nice_bool( nice_get_option( $option, $default ) );
}
endif;

if ( ! function_exists( 'nice_int_option' ) ) :
/**
 * Obtain a theme setting as an integer value.
 *
 * @since 2.0.8
 *
 * @param string       $option_name  Name of the option.
 * @param string|null  $default      (Optional)
 *
 * @return int
 */
function nice_int_option( $option_name, $default = null ) {
	return intval( nice_get_option( $option_name, $default ) );
}
endif;

if ( ! function_exists( 'nice_option_equals' ) ) :
/**
 * Check if the stored value for an option matches another value.
 *
 * @since 2.0.8
 *
 * @param string $option_name Name of the option.
 * @param mixed  $match       The value that needs to be compared.
 * @param null   $type        Data type for matched value (optional).
 * @param null   $default     Default value of the option (optional).
 *
 * @return bool
 */
function nice_option_equals( $option_name, $match, $type = null, $default = null ) {
	if ( $type ) {
		settype( $match, $type );
	}

	return nice_get_option( $option_name, $default ) === $match;
}
endif;

if ( ! function_exists( 'nice_get_list_item_option' ) ) :
/**
 * Obtain the current value for an option associated to a specific list item setting.
 *
 * @since  2.0
 *
 * @param  string $list_item_id ID of the specific list item.
 * @param  string $setting_id   ID of the specific setting.
 * @param  mixed  $default      Default value if the option is not set.
 *
 * @return mixed
 */
function nice_get_list_item_option( $list_item_id, $setting_id = 'default', $default = null ) {
	$option = $list_item_id;

	// The default setting needs no suffix.
	if ( 'default' !== $setting_id ) {
		$option = $list_item_id . '_' . $setting_id;
	}

	return nice_get_option( $option, $default );
}
endif;


if ( ! function_exists( 'nice_customizer_options' ) ) :
/**
 * Obtain the names of the options managed via Customizer.
 *
 * @since 2.0
 */
function nice_customizer_options() {
	return apply_filters( 'nice_customizer_options', array() );
}
endif;


if ( ! function_exists( 'nice_formbuilder' ) ) :
/**
 * nice_formbuilder()
 *
 * retrieve the options array, creating the html structure for the
 * options menu.
 *
 * @since 1.0.0
 *
 * @param array $nice_options. Theme Options.
 *
 * @return object with menu and content.
 */
function nice_formbuilder( $nice_options ) {

	$interface = new stdClass();
	$interface->menu = '';
	$interface->content = '';

	foreach ( $nice_options as $key => $option ) :

		if ( ( 'heading' !== $option['type'] ) && ( 'group' !== $option['type'] ) ) {

			$class = isset( $option['class'] ) ? $option['class'] : '';

			$conditions = nice_option_get_conditions( $option );

			$interface->content .= '<div id="setting_' . esc_attr( $option['id'] ) . '" class="format-settings section section-' . esc_attr( $option['type'] ) . ' ' . esc_attr( $class ) . '"' . $conditions . '>' . "\n";
			$interface->content .= '<div class="format-setting-wrap clearfix">';

			if ( ( 'upload' !== $option['type'] ) && ( 'color' !== $option['type'] ) && ( 'heading' !== $option['type'] ) ) {

				$interface->content .= '<h3 class="heading"><label for="' . esc_attr( $option['id'] ) . '">' . esc_html( $option['name'] ) . '</label></h3>' . "\n";

			} else {

				$interface->content .= '<h3 class="heading">' . esc_html( $option['name'] ) . '</h3>' . "\n";

			}

			if ( 'checkbox' !== $option['type'] && ( 'info' !== $option['type'] ) && '' !== $option['desc'] ) {

				$interface->content .= '<a id="btn-help-' . $option['id'] . '" class="nice-help-button nice-tooltip" title="' . esc_attr( $option['desc'] ) . '"><i class="dashicons dashicons-editor-help"></i></a>' . "\n";
			}

			$interface->content .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";

		}

		switch ( $option['type'] ) {

			case 'heading' :

				if ( $key >= 2 ) {
					$interface->content .= '</div>' . "\n";
				}

				$jquery_click_hook = preg_replace( '/[^a-zA-Z0-9\s]/', '', strtolower( $option['name'] ) );
				$jquery_click_hook = str_replace( ' ', '-', $jquery_click_hook );
				$jquery_click_hook	 = 'nice-option-' . $jquery_click_hook;

				$menu_class = isset( $option['parent'] ) ? 'child' : '';

				$interface->menu .= '<li class="' . $menu_class . '">' . "\n";
				$interface->menu .= '<a title="' . esc_attr( $option['name'] ) . '" href="#' . $jquery_click_hook . '">' . "\n";
				$icon = isset( $option['icon'] ) ? $option['icon'] : '';
				$interface->menu .= $icon . esc_html( $option['name'] ) . '</a><div></div></li>' . "\n";
				$interface->content .= '<div class="nice-section-title" id="' . $jquery_click_hook . '"><h2>' . esc_html( $option['name'] ) . '</h2>' . "\n";

			break;

			case 'group' :

				$icon = isset( $option['icon'] ) ? $option['icon'] : '';
				$interface->content .= '<div class="nice-group-title"><h3>' . $icon . esc_html( $option['name'] ) . '<span></span></h3></div>' . "\n";

			break;

			default :
				$option['value'] = nice_get_option( $option['id'] );
				$interface->content .= nice_option_do( $option );

		}

		// Allow adding extra HTML after the option.
		if ( ! empty( $option['id'] ) ) {
			$interface->content .= apply_filters( 'nice_option_' . $option['id'] . '_after', '' );
		}

		$explain_class = 'explain';

		if ( 'heading' !== $option['type'] && 'group' !== $option['type'] ) {

			if ( 'checkbox' !== $option['type'] ) {
				$interface->content .= '<br />';
			} else {
				$explain_class = 'explain-checkbox';
			}

			if ( ! isset( $option['desc'] ) ) {
				$explain_value = '';
			} else {
				$explain_value = $option['desc'];
			}

			$interface->content .= '</div><div id="nice-help-' . esc_attr( $option['id'] ) . '" class="' . esc_attr( $explain_class ) . '">';

			if ( 'checkbox' === $option['type'] ) {
				$interface->content .= '<label for="' . esc_attr( $option['id'] ) . '">' . $explain_value . '</label>';
			} else {
				$interface->content .= $explain_value;
			}

			$interface->content .= '</div></div>' . "\n";
			$interface->content .= '</div></div>' . "\n";
		}

	endforeach;

	$interface->content .= '</div>';

	return $interface;

}
endif;


if ( ! function_exists( 'nice_option_do' ) ) :
/**
 * nice_option_do()
 *
 * Call the appropriate function to do the option
 *
 * @since  2.0
 *
 * @param  array $option - Option and config data to do the field.
 *
 * @return string   Markup with the option field.
 */
function nice_option_do( $option ) {

	/* allow filters to be executed on the array */
	$option = apply_filters( 'nice_option_do', $option );

	/* build the function name */
	$function_name = 'nice_option_get_' . $option['type'];

	/* call the function & pass in arguments array */
	if ( function_exists( $function_name ) ) {
		return call_user_func( $function_name, $option );
	} else {
		return '<p>' . esc_html__( 'Sorry, this function does not exist', 'nice-framework' ) . ': <code>' . $function_name . '();</code></p>';
	}

}
endif;


if ( ! function_exists( 'nice_option_get_conditions' ) ) :
/**
 * nice_option_get_conditions()
 *
 * See if the field has conditions
 * If it does, create the html and return that
 *
 * @since 2.0
 *
 * @param  array $option
 *
 * @return string
 */
function nice_option_get_conditions( $option ) {
	$conditions = '';

	if ( isset( $option['condition'] ) && ! empty( $option['condition'] ) ) {
		$conditions  = ' data-condition="' . esc_attr( $option['condition'] ) . '"';
		$conditions .= isset( $option['operator'] ) && in_array( $option['operator'], array( 'and', 'AND', 'or', 'OR' ), true ) ? ' data-operator="' . esc_attr( $option['operator'] ) . '"' : '';
	}

	return $conditions;
}
endif;


if ( ! function_exists( 'nice_option_get_text' ) ) :
/**
 * nice_option_get_text()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since  1.0.0
 *
 * @param  array $option Option info in order return the html code.
 *
 * @return string    Text input.
 */
function nice_option_get_text( $option ) {
	$field_value = $option['value'];

	return '<input class="nice-input" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '" type="' . esc_attr( $option['type'] ) . '" value="' . esc_attr( $field_value ) . '" />';
}
endif;


if ( ! function_exists( 'nice_option_get_heading' ) ) :
/**
 * nice_option_get_heading()
 *
 * @since  1.0.0
 *
 * @param  array $option
 *
 * @return null
 */
function nice_option_get_heading( $option ) {
	// Nothing yet.
	return null;
}
endif;

if ( ! function_exists( 'nice_option_get_select' ) ) :
/**
 * nice_option_get_select()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the select input.
 */

function nice_option_get_select( $option ) {
	$field_value = $option['value'];
	$field_class = isset( $option['class'] ) ? ' ' . $option['class'] : '' ;

	$output = '<select class="nice-input' . esc_attr( $field_class ) . '" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '">' . "\n";

	foreach ( $option['options'] as $o => $n ) {

		$selected = selected( $field_value, $o, false );

		$output .= '<option value="' . esc_attr( $o ) . '" class="' . esc_attr( $o ) . '" ' . $selected . '>';
		$output .= esc_html( $n );
		$output .= '</option>' . "\n";

		$selected = null;
	}

	$output .= '</select>' . "\n";

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_select_color' ) ) :
/**
 * nice_option_get_select_color()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the select input.
 */
function nice_option_get_select_color( $option ) {
	$field_value = $option['value'];
	$field_class = isset( $option['class'] ) ? ' ' . $option['class'] : '' ;

	$output = '<div class="nice-input' . esc_attr( $field_class ) . '" data-name="' . esc_attr( $option['id'] ) . '" data-id="' . esc_attr( $option['id'] ) . '">' . "\n";

	$output .= '<div class="option nice-color-select-placeholder" data-default-text="' . esc_attr__( 'Please select a color', 'nice-framework' ) . '">' . esc_html__( 'Please select a color', 'nice-framework' ) . '</div>' . "\n";

	$output .= '<div class="nice-color-select-options">' . "\n";

	foreach ( $option['options'] as $o => $n ) {
		$selected = ( $field_value === $o ) ? 'true' : 'false';

		$output .= '<div class="option" data-value="' . esc_attr( $o ) . '" data-selected="' . $selected . '">';
		$output .= esc_html( $n['name'] );
		$output .= '<span class="' . esc_attr( $o ) . '" style="background-color: ' . esc_attr( $n['value'] ) . '">&nbsp;</span>' . "\n";
		$output .= '</div>' . "\n";

		$selected = null;
	}

	$output .= '</div>' . "\n";
	$output .= '<input class="nice-input" type="hidden" name="' . esc_attr( $option['id'] ) . '" value="' . esc_attr( $field_value ) . '" id="' . esc_attr( $option['id'] ) . '">' . "\n";
	$output .= '</div>' . "\n";

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_update_select_color' ) ) :
add_action( 'wp_ajax_nice_option_update_select_color', 'nice_option_update_select_color' );
/**
 * Update color selector via AJAX once theme options are saved.
 *
 * @since 2.0
 */
function nice_option_update_select_color() {
	check_ajax_referer( 'play-nice', 'nonce' );

	if ( ! empty( $_POST['selectors'] ) && is_array( $_POST['selectors'] ) ) {
		if ( function_exists( 'nice_options' ) ) {
			nice_options();
		}

		$selectors        = wp_unslash( $_POST['selectors'] );
		$options          = get_option( 'nice_template' );
		$matching_options = array();
		$response         = array();

		foreach ( $options as $option ) {
			if ( isset( $option['id'] ) && in_array( $option['id'], $selectors, true ) ) {
				$option['value'] = nice_get_option( $option['id'] );
				$matching_options[ $option['id'] ] = $option;
			}
		}

		foreach ( $selectors as $selector ) {
			if ( ! isset( $matching_options[ $selector ] ) ) {
				continue;
			}

			$response[ $selector ] = nice_option_do( $matching_options[ $selector ] );
		}
	}

	echo wp_json_encode( $response );

	die();
}
endif;

if ( ! function_exists( 'nice_option_get_select_sidebar' ) ) :
/**
 * nice_option_get_select_sidebar()
 *
 * Retrieve option info among all the registered sidebars.
 *
 * @since 2.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the select input.
 */
function nice_option_get_select_sidebar( $option ) {

	$field_id = $option['id'];

	$output = '<select class="nice-select" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_id ) . '">';

	$sidebars = $GLOBALS['wp_registered_sidebars'];

	if ( $sidebars ) {

		$field_value = $option['value'];

		$output .= '<option value="" >' . esc_html__( 'Default', 'nice-framework' ) . '</option>';

		foreach ( $sidebars as $sidebar ) {

			$selected = '';

			if ( '' !== $field_value  ) {

				$selected = selected( $field_value, $sidebar['id'], false );

			} elseif ( isset( $option['default'] ) ) {

				$selected = selected( $option['default'], $sidebar['id'], false );

			}

			$output .= '<option value="' . esc_attr( $sidebar['id'] ) . '" ' . $selected . '>' . $sidebar['name'] . '</option>';

		}

	}

	$output .= '</select>';

	return $output;
}
endif;



if ( ! function_exists( 'nice_option_get_textarea' ) ) :
/**
 * nice_option_get_textarea()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array $option Option info in order return the html code.
 * @return string with the textarea input.
 */
function nice_option_get_textarea( $option ) {
	$cols = '8';

	if ( isset( $option['options'] ) ) {
		$ta_options = $option['options'];
		if ( isset( $ta_options['cols'] ) ) {
			$cols = $ta_options['cols'];
		} else {
			$cols = '8';
		}
	}

	$field_value = $option['value'];

	return '<textarea class="nice-input" name="' . $option['id'] . '" id="' . $option['id'] . '" cols="' . $cols . '" rows="8">' . esc_textarea( stripslashes( $field_value ) ) . '</textarea>' . "\n";
}
endif;

if ( ! function_exists( 'nice_option_get_file' ) ) :
/**
 * nice_option_get_file()
 *
 * Retrieve option info in order to return the field in html code.
 * Works with WordPress media uploader.
 * If there's an image, it shows it with a "remove" button
 * Check medialibrary.php
 *
 * @since 1.0.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the file input.
 */
function nice_option_get_file( $option ) {

	$field_value = $option['value'];

	$output  = '<input id="' . esc_attr( $option['id'] ) . '" class="nice-upload" type="text" size="36" name="' . esc_attr( $option['id'] ) . '" value="' . esc_attr( $field_value ) . '" autocomplete="off" />';
	$output .= '<input id="upload_image_button" class="nice_upload_button nice-input nice-tooltip" type="button" value="' . esc_attr__( 'Browse', 'nice-framework' ) . '" title="' . esc_attr__( 'Upload or select a file from the media library', 'nice-framework' ) . '" />';

	$output .= '<div class="screenshot" id="' . $option['id'] . '_image">' . "\n";

	if ( '' !== $field_value ) {
		$output .= '<img src="' . $field_value . '" alt="" />' . "\n";
		$output .= '<a href="#" class="nice-upload-remove">' . esc_html__( 'Remove Media', 'nice-framework' ) . '</a>' . "\n";
	}

	$output .= '</div>' . "\n";

	return $output;

}
endif;

if ( ! function_exists( 'nice_option_get_upload' ) ) :
/**
 * nice_option_get_upload()
 *
 * Wrapper for nice_option_get_file().
 *
 * @see nice_option_get_file()
 *
 * @since 1.0.0
 *
 * @param array $option
 *
 * @return string
 */
function nice_option_get_upload( $option ) {
	return nice_option_get_file( $option );
}
endif;

if ( ! function_exists( 'nice_option_get_checkbox' ) ) :
/**
 * nice_option_get_checkbox()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the checkbox input.
 */
function nice_option_get_checkbox( $option ) {
	$field_value = $option['value'];

	$checked = checked( nice_bool( $field_value ), true, false );

	return '<input type="checkbox" class="checkbox nice-input" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '" value="true" ' . $checked . ' />';
}
endif;


if ( ! function_exists( 'nice_option_get_radio' ) ) :
/**
 * nice_option_get_radio()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the radio input.
 */
function nice_option_get_radio( $option ) {
	$output = '';
	$field_value = $option['value'];

	foreach ( $option['options'] as $o => $n ) {
		$checked = checked( $field_value, $o, false );

		$output .= '<input class="nice-input nice-radio" type="radio" name="' . esc_attr( $option['id'] ) . '" value="' . $o . '" ' . $checked . ' />' . esc_html( $n ) . '<br />';
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_color' ) ) :
/**
 * nice_option_get_color()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the color input.
 */
function nice_option_get_color( $option ) {
	$field_value = $option['value'];
	$field_class = isset( $option['class'] ) ? ' ' . $option['class'] : '';

	/* set the default color */
	$field_default = $option['std'] ? 'data-default-color="' . $option['std'] . '"' : '';

	return '<input class="nice-color' . esc_attr( $field_class ) . '" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '" type="text" autocomplete="off" value="' . esc_attr( $field_value ) . '" ' . $field_default . ' />';
}
endif;

if ( ! function_exists( 'nice_option_get_date' ) ) :
/**
 * nice_option_get_date()
 *
 * Retrieve option info in order to return the field in html code + js date picker.
 *
 * @since 1.0.1
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the date input.
 */
function nice_option_get_date( $option ) {
	$field_value = $option['value'];

	$output  = '<input class="nice-date" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '" type="text" value="' . esc_attr( $field_value ) . '" />';
	$output .= '<input type="hidden" name="datepicker-image" value="' . NICE_FRAMEWORK_URI . '/admin/assets/images/calendar.png" />';

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_select_multiple' ) ) :
/**
 * nice_option_get_select_multiple()
 *
 * Retrieve option info in order to return the field in html code + js date picker.
 *
 * @since 1.0.12
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the date input.
 */
function nice_option_get_select_multiple( $option ) {
	$field_value = $option['value'];

	$output = '<select class="nice-input" style="height:auto;" name="' . esc_attr( $option['id'] ) . '[]" id="' . esc_attr( $option['id'] ) . '[]" multiple="multiple" >' . "\n";

	foreach ( $option['options'] as $o => $n ) {

		$selected = '';

		if ( '' !== $field_value ) {

			if ( is_array( $field_value ) ) {
				if ( false !== ( $key = array_search( $n, $field_value ) ) ) {
					$selected = ' selected="selected"';
				}
			}

		} else {

			if ( isset( $field_value ) ) {
				$selected = selected( $field_value, $o, false );
			}

		}

		$output .= '<option value="' . esc_attr( $o ) . '"' . $selected . '>';
		$output .= esc_html( $n );
		$output .= '</option>' . "\n";
	}

	$output .= '</select>' . "\n";

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_list_item' ) ) :
/**
 * nice_option_get_list_item()
 *
 * Retrieve option info in order to return the field in HTML code.
 *
 * @uses nice_option_get_list_item_html()
 *
 * @since 2.0
 *
 * @param array $option
 *
 * @return string
 */
function nice_option_get_list_item( $option ) {
	$output = '';

	// Prepare default option value.
	$option_default = array();
	if ( ! empty( $option['std'] ) ) {
		foreach ( (array) $option['std'] as $item ) {
			$option_default[ $item['id'] ] = $item['name'];
		}
	}

	// Obtain `value` attribute.
	$option['value'] = nice_get_option( $option['id'], $option_default );

	// Double check for option value.
	if ( empty( $option['value'] ) && ! empty( $option_default ) ) {
		$option['value'] = (array) $option_default;
	}

	if ( is_array( $option['value'] ) && ! empty( $option['value'] ) && is_array( $option['settings'] ) && ! empty( $option['settings'] ) ) {
		$editable = ! empty( $option['editable'] );
		$sortable = ! empty( $option['sortable'] );
		$field_class  = 'nice-framework-admin-ui-list-item';
		$field_class .= $editable ? ' editable' : '';
		$field_class .= $sortable ? ' sortable' : '';

		if ( ! empty( $option['user_item_prefix'] ) ) {
			$user_item_prefix = $option['user_item_prefix'];

		} else {
			$nice_prefix = NICE_PREFIX;
			if ( '_' === substr( $nice_prefix, -1 ) ) {
				$nice_prefix = substr( $nice_prefix, 0, strlen( $nice_prefix ) - 1 );
			}
			$nice_user_prefix = $nice_prefix . '_user';

			$user_item_prefix = $option['id'];
			if ( false === strpos( $user_item_prefix, $nice_user_prefix, 0 ) ) {
				$nice_prefix_position = strpos( $user_item_prefix, $nice_prefix, 0 );
				if ( false !== $nice_prefix_position ) {
					$user_item_prefix = substr_replace( $user_item_prefix, '', $nice_prefix_position, strlen( $nice_prefix ) );
				}
				$user_item_prefix = $nice_user_prefix . $user_item_prefix;
			}
		}

		$item_js_callback = isset( $option['js_callback'] ) ? $option['js_callback'] : '';

		// Open field container.
		$output = '<div class="' . $field_class . '" data-js-callback="' . $item_js_callback . '" data-user-item-prefix="' . $user_item_prefix . '">' . "\n";

		// Open items container.
		$output .= '<ul>' . "\n";

		// Add items.
		foreach ( $option['value'] as $item_id => $item_name ) {
			// Construct item array.
			$item = array(
				'name' => $item_name,
				'id'   => $item_id,
			);

			// Add default item setting values.
			if ( ! empty( $option['std'] ) ) {
				foreach ( $option['std'] as $default_item ) {
					if ( $item_id === $default_item['id'] ) {
						$item['std'] = $default_item['std'];
						break;
					}
				}
			}

			// Define item slug.
			$item['slug'] = $item['id'];

			$output .= nice_option_get_list_item_html( $item, $option, $editable );
		}

		$output .= nice_option_get_list_item_html_model( $option, $editable );

		// Close items container.
		$output .= '</ul>' . "\n";

		$theme_check_bs = strrev( 'edocne_46esab' );

		// Add items IDs and names, encoded and serialized.
		$output .= '<input class="section-data" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '" type="hidden" value="' . $theme_check_bs( wp_json_encode( $option['value'] ) ) . '" />';

		// Close field container.
		$output .= '</div>' . "\n";
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_list_item_html' ) ) :
/**
 * nice_option_get_list_item_html()
 *
 * Helper method to obtain the HTML code for an item of a list.
 * @see nice_option_get_list_item()
 *
 * @uses nice_option_get_list_item_setting_html()
 *
 * @since 2.0
 *
 * @param array $list_item
 * @param array $option
 * @param bool  $editable
 *
 * @return string
 */
function nice_option_get_list_item_html( $list_item, $option, $editable = true ) {
	$settings = $option['settings'];

	$output = '';

	$item_name = isset( $list_item['name'] ) ? $list_item['name'] : '';
	$item_slug = $item_name ? array_search( $item_name, $option['value'] ) : '';

	$is_blocked = false;
	$is_default = false;

	if ( ! empty( $option['std'] ) ) {
		foreach ( (array) $option['std'] as $default_option ) {
			if ( $default_option['id'] === $list_item['slug'] ) {
				if ( $editable && empty( $option['edit_defaults'] ) ) {
					$is_blocked = true;
				}

				$is_default = true;

				continue;
			}
		}
	}

	// Open item container.
	$output .= '<li id="list_item_' . $list_item['id'] . '" class="format-settings section section-list_item" data-blocked="' . $is_blocked . '" data-default="' . $is_default . '" data-name="' . $item_name . '" data-slug="' . $item_slug . '">' . "\n";
	$output .= '<div class="format-setting-wrap clearfix">' . "\n";

	// Add item name.
	$output .= '<h4 class="heading">' . "\n";
	$output .= '<label for="' . $list_item['id'] . '">' . $list_item['name'] . '</label>' . "\n";
	$output .= '</h4>' . "\n";

	// Add settings container.
	$output .= '<ul class="clearfix">' . "\n";

	if ( $editable && ! $is_blocked ) {
		$output .= nice_option_get_list_item_setting_html( array(
			'name'  => ! empty( $option['option_name_label'] ) ? $option['option_name_label'] : esc_html__( 'Option Name', 'nice-framework' ),
			'id'    => $list_item['id'] . '_option_name',
			'type'  => 'text',
			'std'   => $list_item['name'],
			'class' => 'nice-option-name',
		) );
	}

	// Add item settings.
	foreach ( $settings as $setting ) {
		// Apply the default value of the item to the setting.
		if ( ! empty( $list_item['std'][ $setting['id'] ] ) ) {
			$setting['std'] = $list_item['std'][ $setting['id'] ];
		}

		// Add the list item ID to the setting ID in the condition field.
		if ( ! empty( $setting['condition'] ) ) {
			$conditions = explode( ',', $setting['condition'] );

			foreach ( $conditions as &$condition ) {
				$condition = $list_item['id'] . '_' . $condition;
			}

			$setting['condition'] = implode( ',', $conditions );
		}

		// Combine the setting ID with the list item ID. The default setting needs no suffix.
		if ( 'default' === $setting['id'] ) {
			$setting['id'] = $list_item['id'];
		} else {
			$setting['id'] = $list_item['id'] . '_' . $setting['id'];
		}

		$output .= nice_option_get_list_item_setting_html( $setting );
	}

	// Close settings container.
	$output .= '</ul>' . "\n";

	// Close item container.
	$output .= '</div>' . "\n";
	$output .= '</li>' . "\n";

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_list_item_html_model' ) ) :
/**
 * nice_option_get_list_item_html()
 *
 * Helper method to obtain the HTML code for an item of a list.
 * @see nice_option_get_list_item()
 *
 * @uses nice_option_get_list_item_setting_html()
 *
 * @since 2.0
 *
 * @param array $option
 * @param bool  $editable
 *
 * @return string
 */
function nice_option_get_list_item_html_model( $option, $editable = true ) {
	$settings = $option['settings'];

	$output = '';

	// Open item container.
	$output .= '<li id="" class="format-settings section section-list_item html-model">' . "\n";
	$output .= '<div class="format-setting-wrap clearfix">' . "\n";

	// Add item name.
	$output .= '<h4 class="heading">' . "\n";
	$output .= '<label for=""></label>' . "\n";
	$output .= '</h4>' . "\n";

	// Add settings container.
	$output .= '<ul class="clearfix">' . "\n";

	if ( $editable ) {
		$output .= nice_option_get_list_item_setting_html( array(
			'name'  => ! empty( $option['option_name_label'] ) ? $option['option_name_label'] : esc_html__( 'Option Name', 'nice-framework' ),
			'id'    => 'option_name',
			'type'  => 'text',
			'std'   => '',
			'class' => 'nice-option-name',
		) );
	}

	// Add item settings.
	foreach ( $settings as $setting ) {
		if ( 'color' === $setting['type'] ) {
			$setting['class'] = trim( $setting['class'] . ' delay' );
		}

		$output .= nice_option_get_list_item_setting_html( $setting );
	}

	// Close settings container.
	$output .= '</ul>' . "\n";

	// Close item container.
	$output .= '</div>' . "\n";
	$output .= '</li>' . "\n";

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_list_item_setting_html' ) ) :
/**
 * nice_option_get_list_item_setting_html()
 *
 * Helper method to obtain the HTML code for a setting of an item of a list.
 * @see nice_option_get_list_item_html()
 *
 * @since 2.0
 *
 * @param array $list_item_setting
 *
 * @return string
 */
function nice_option_get_list_item_setting_html( $list_item_setting ) {
	$output = '';

	if ( ! in_array( $list_item_setting['type'], array( 'heading', 'group', 'list_item' ), true ) ) {
		// Prepare default list item setting value.
		$list_item_setting_default = ( ! empty( $list_item_setting['std'] ) ) ? $list_item_setting['std'] : null;

		// Obtain `value` attribute.
		$list_item_setting['value'] = nice_get_option( $list_item_setting['id'], $list_item_setting_default );

		$data = array();
		$data_attributes = array();

		if ( isset( $list_item_setting['item_slug'] ) ) {
			$data['data-slug'] = $list_item_setting['item_slug'];
		}

		if ( isset( $list_item_setting['item_name'] ) ) {
			$data['data-name'] = $list_item_setting['item_name'];
		}

		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $value ) {
				$data_attributes[] = $key . '="' . $value . '"';
			}
		}

		$data_attributes_html = join( ' ', $data_attributes );

		// Open setting container.
		$class = ! empty( $list_item_setting['class'] ) ? ' ' . $list_item_setting['class'] : '';
		$output .= '<li id="setting_' . $list_item_setting['id'] . '" class="format-settings section section-' . $list_item_setting['type'] . $class . '" ' . $data_attributes_html . ' ' . nice_option_get_conditions( $list_item_setting ) . '>' . "\n";
		$output .= '<div class="format-setting-wrap clearfix">' . "\n";

		// Add setting name.
		$output .= '<h5 class="heading">' . "\n";
		$output .= '<label for="' . $list_item_setting['id'] . '">' . $list_item_setting['name'] . '</label>' . "\n";
		$output .= '</h5>' . "\n";

		// Add help button.
		if ( ! empty( $list_item_setting['desc'] ) ) {
			if ( 'checkbox' !== $list_item_setting['type'] && ( 'info' !== $list_item_setting['type'] ) && '' !== $list_item_setting['desc'] ) {
				$output .= '<a id="btn-help-' . $list_item_setting['id'] . '" class="nice-help-button nice-tooltip" title="' . esc_attr( $list_item_setting['desc'] ) . '"><i class="dashicons dashicons-editor-help"></i></a>' . "\n";
			}
		}

		// Open option container.
		$output .= '<div class="option">' . "\n";

		// Open controls container.
		$output .= '<div class="controls">' . "\n";

		// Add setting controls.
		$output .= nice_option_do( $list_item_setting );

		// Allow adding extra HTML after the setting.
		$output .= apply_filters( 'nice_option_' . $list_item_setting['id'] . '_after', '' );

		// Close controls container.
		$output .= '</div>' . "\n";

		// Add setting help.
		if ( ! empty( $list_item_setting['desc'] ) ) {
			$explain_class = ( 'checkbox' === $list_item_setting['type'] ) ? 'explain-checkbox' : 'explain';

			// Open setting help container.
			$output .= '<div id="nice-help-' . esc_attr( $list_item_setting['id'] ) . '" class="' . esc_attr( $explain_class ) . '">';

			// Add setting help.
			if ( 'checkbox' === $list_item_setting['type'] ) {
				$output .= '<label for="' . esc_attr( $list_item_setting['id'] ) . '">' . $list_item_setting['desc'] . '</label>';
			} else {
				$output .= $list_item_setting['desc'];
			}

			// Close setting help container.
			$output .= '</div>' . "\n";
		}

		// Close option container.
		$output .= '</div>' . "\n";

		// Close setting container.
		$output .= '</div>' . "\n";
		$output .= '</li>' . "\n";
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_get_websafe_fonts' ) ) :
/**
 * Obtain a list of supported web-safe fonts.
 *
 * @since 2.0
 */
function nice_get_websafe_fonts() {
	$fonts = array(
		'Arial, sans-serif',
		'Verdana, Geneva',
		'Trebuchet',
		'Georgia',
		'Times New Roman',
		'Tahoma, Geneva',
		'Palatino',
		'Helvetica',
		'Calibri',
		'Myriad',
		'Lucida',
		'Arial Black',
		'Gill',
		'Geneva, Tahoma',
		'Impact',
		'Courier',
		'Century Gothic',
	);

	/**
	 * @hook nice_websafe_fonts
	 *
	 * Hook in here to modify the default list of web-safe fonts.
	 *
	 * @since 2.0
	 */
	return apply_filters( 'nice_websafe_fonts', $fonts );
}
endif;

if ( ! function_exists( 'nice_option_get_typography' ) ) :
/**
 * nice_option_get_typography()
 *
 * Retrieve option info in order to return the field in html code + js date picker.
 *
 * @since 1.0.12
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the date input.
 */
function nice_option_get_typography( $option ) {
	$field_value = $option['value'];

	/**
	* Font family
	*/
	$font_family   = nice_get_font_family( $option );
	$font_size     = nice_get_font_size( $field_value );
	$default_fonts = nice_get_websafe_fonts();
	$family_key    = isset( $option['std']['font-family'] ) ? 'font-family': 'family';
	$family_id     = esc_attr( $option['id'] . '_' . $family_key );

	$output = '<select class="nice-typography nice-typography-family nice-tooltip" name="' . $family_id . '" id="' . $family_id . '" title="' . esc_attr__( 'Set the Font Family', 'nice-framework' ) . '">' . "\n";
	$output .= '<option value="">---- ' . esc_html__( 'Web Safe Fonts', 'nice-framework' ) . ' ----</option>' . "\n";

	foreach ( $default_fonts as $font ) {
		$font_selected = ( strpos( $font_family, $font ) !== false );
		$output .= '<option value="' . $font . '" ' . selected( $font_selected, true, false ) . ' data-type="websafe">' . $font . '</option>' . "\n";
	}

	/**
	* Google webfonts
	*/
	$google_fonts = nice_get_google_fonts();
	sort( $google_fonts );

	$output .= '<option value="">---- ' . esc_html__( 'Google Fonts', 'nice-framework' ) . ' ----</option>' . "\n";

	foreach ( $google_fonts as $key => $google_font ) {
		$selected = selected( $font_family, $google_font['name'], false );
		$name     = $google_font['name'];
		$output .= '<option value="' . esc_attr( $name ) . '" ' . $selected . ' data-type="google-font" data-variant="' . $google_font['variant'] . '">' . $name . '</option>' . "\n";
	}

	$output .= '</select>' . "\n\n";

	/**
	* Font weight
	*/
	$font_weight = nice_get_font_style( $field_value );

	if ( empty( $option['font_styles'] ) ) {
		$thin       = selected( $font_weight, '300',         false );
		$thinitalic = selected( $font_weight, '300 italic',  false );
		$normal     = selected( $font_weight, 'normal',      false );
		$italic     = selected( $font_weight, 'italic',      false );
		$bold       = selected( $font_weight, 'bold',        false );
		$bolditalic = selected( $font_weight, 'bold italic', false );

		if ( ( '' !== $thin ) && ( '' !== $thinitalic ) && ( '' !== $normal ) && ( '' !== $italic ) && ( '' !== $bold ) && ( '' !== $bolditalic ) ) {
			$normal = 'selected="selected"';
		}

		$style_options  = '<option value="300" ' . $thin . '>' . esc_html__( 'Thin', 'nice-framework' ) . '</option>';
		$style_options .= '<option value="300 italic" ' . $thinitalic . '>' . esc_html__( 'Thin/Italic', 'nice-framework' ) . '</option>';
		$style_options .= '<option value="normal" ' . $normal . '>' . esc_html__( 'Normal', 'nice-framework' ) . '</option>';
		$style_options .= '<option value="italic" ' . $italic . '>' . esc_html__( 'Italic', 'nice-framework' ) . '</option>';
		$style_options .= '<option value="bold" ' . $bold . '>' . esc_html__( 'Bold', 'nice-framework' ) . '</option>';
		$style_options .= '<option value="bold italic" ' . $bolditalic . '>' . esc_html__( 'Bold/Italic', 'nice-framework' ) . '</option>';

	} else {
		$style_options = '';
		foreach ( $option['font_styles'] as $font_style_value => $font_style_name ) {
			$style_options .= '<option value="' . $font_style_value . '" ' . selected( $font_weight, $font_style_value, false ) . '>' . $font_style_name . '</option>';
		}
	}

	$style_key = isset( $option['std']['font-style'] ) ? 'font-style' : 'style';
	$style_id  = esc_attr( $option['id'] . '_' . $style_key );

	$output .= '<select class="nice-typography nice-typography-style nice-tooltip" name="' . $style_id . '" id="' . $style_id . '" title="' . esc_attr__( 'Set the Font Weight', 'nice-framework' ) . '">';
	$output .= $style_options;
	$output .= '</select>';

	/*
	* Font Size
	*/
	if ( $font_size && ( isset( $option['std']['size'] ) || isset( $option['std']['font-size'] ) ) ) {
		$font_size_val  = floatval( $font_size );
		$font_size_unit = str_replace( $font_size_val, '', $font_size );
		$font_size      = $font_size_val . $font_size_unit;

		$size_key = isset( $option['std']['font-size'] ) ? 'font-size' : 'size';
		$size_id  = esc_attr( $option['id'] . '_' . $size_key );

		if ( empty( $option['min'] ) && empty( $option['max'] ) ) {
			$output .= '<select class="nice-typography nice-typography-size nice-tooltip" name="' . $size_id . '" id="' . $size_id . '" title="' . esc_attr__( 'Set the Font Size', 'nice-framework' ) . '">' . "\n";

			for ( $i = 9; $i < 71; $i++ ) {

				$active = selected( $font_size, ( $i . $font_size_unit ), false );
				$output .= '<option value="' . esc_attr( $i ) . '" ' . $active . '>' . esc_html( $i . ' px' ) . '</option>' . "\n";

			}

			$output .= '</select>' . "\n\n";

		} else {
			$min = empty( $option['min'] ) ? '' : 'min="' . $option['min'] . '"';
			$max = empty( $option['max'] ) ? '' : 'max="' . $option['max'] . '"';

			$output .= '<input type="number" class="nice-typography nice-typography-font-size nice-tooltip" value="' . $font_size_val . '" name="' . $size_id . '" id="' . $size_id . '" step="any" ' . $min . ' ' . $max . ' title="' . esc_attr__( 'Set the Font Size', 'nice-framework' ) . '">';

			$units = empty( $option['units'] ) ? array( $option['std']['unit'] ) : $option['units'];
			$select_unit_disabled = disabled( 1 === count( $units ), true, false );
			$output .= '<select class="nice-typography nice-typography-unit nice-tooltip" name="' . esc_attr( $option['id'] . '_unit' ) . '" id="' . esc_attr( $option['id'] . '_unit' ) . '" ' . $select_unit_disabled . ' title="' . esc_attr__( 'Set the Font Size Unit', 'nice-framework' ) . '">';
			foreach ( $units as $k => $v ) {
				$output .= '<option value="' . $v . '" ' . selected( $v, $font_size_unit, false ) . '>' . $v . '</option>';
			}
			$output .= '</select>';
		}
	}

	/**
	* Letter Spacing
	*/
	if ( isset( $option['std']['letter-spacing'] ) ) {

		$letter_spacing_default_value = ! empty( $option['std']['letter-spacing'] ) ? $option['std']['letter-spacing'] : 0;

		$letter_spacing_value = isset( $field_value['letter-spacing'] ) ? $field_value['letter-spacing'] : $letter_spacing_default_value;

		$output .= '<input name="' . esc_attr( $option['id'] . '_letter-spacing' ) . '" type="number" value="' . $letter_spacing_value . '" class="nice-typography nice-typography-letter-spacing nice-tooltip" step="0.05" min="-5" max="10" title="' . esc_attr__( 'Set the Letter Spacing', 'nice-framework' ) . '" id="' . esc_attr( $option['id'] . '_letter-spacing' ) . '" />';

		$letter_spacing_units = array( 'px' => esc_html__( 'px', 'nice-framework' ), 'em' => esc_html__( 'em', 'nice-framework' ) );
		$select_unit_disabled = disabled( 1 === count( $letter_spacing_units ), true, false );

		$letter_spacing_unit_value = isset( $field_value['letter-spacing-unit'] ) ? $field_value['letter-spacing-unit'] : $option['std']['letter-spacing-unit'];

		$output .= '<select class="nice-typography nice-typography-letter-spacing-unit nice-tooltip" name="' . esc_attr( $option['id'] . '_letter-spacing-unit' ) . '" id="' . esc_attr( $option['id'] . '_letter-spacing-unit' ) . '" ' . $select_unit_disabled . ' title="' . esc_attr__( 'Set the Letter Spacing Unit', 'nice-framework' ) . '">';

		foreach ( $letter_spacing_units as $k => $v ) {
			$output .= '<option value="' . $k . '" ' . selected( $k, $letter_spacing_unit_value, false ) . '>' . $v . '</option>';
		}

		$output .= '</select>';
	}

	/**
	* Font color
	*/
	if ( isset( $option['std']['color'] ) ) {

		$font_color = isset( $field_value['color'] ) ? $field_value['color'] : $option['std']['color'];
		$field_default = $option['std']['color'] ? 'data-default-color="' . $option['std']['color'] . '"' : '';

		$output .= '<input class="nice-color nice-typography-color" name="' . esc_attr( $option['id'] . '_color' ) . '" id="' . esc_attr( $option['id'] . '_color' ) . '" type="text" value="' . esc_attr( $font_color ) . '" ' . $field_default . ' />' . "\n\n";

	}

	$output .= '<input type="hidden" class="nice-typography-last" />';

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_typography_field_name' ) ) :
/**
 * Obtain the name for a typography field.
 *
 * @since 2.0
 *
 * @param $option_id
 * @param $field_name
 * @param $context
 *
 * @return string
 */
function nice_option_get_typography_field_name( $option_id, $field_name, $context ) {
	$field_base_name = ( 'customizer' === $context ? 'nice_options[' . $option_id . '][' . $field_name . ']' : $option_id . '_' . $field_name );
	return esc_attr( $field_base_name );
}
endif;

if ( ! function_exists( 'nice_get_font_family' ) ) :
/**
 * Obtain font-family from an array of typography values.
 *
 * @since  2.0
 *
 * @param  array  $setting List of values of the given option.
 *
 * @return string
 */
function nice_get_font_family( array $setting = array() ) {
	$option = isset( $setting['id'] ) ? nice_get_option( $setting['id'] ) : $setting;

	if ( isset( $option['family'] ) ) {
		$family = $option['family'];
	} elseif ( isset( $option['font-family'] ) ) {
		$family = $option['font-family'];
	} else {
		$family = 'inherit';
	}

	return $family;
}
endif;

if ( ! function_exists( 'nice_get_font_style' ) ) :
/**
 * Obtain font-style from an array of typography values.
 *
 * @since  2.0
 *
 * @param  array  $typography List of typography values.
 *
 * @return string
 */
function nice_get_font_style( array $typography = null ) {
	$style = 'inherit';

	if ( is_null( $style ) ) {
		return $style;
	}

	if ( isset( $typography['style'] ) ) {
		$style = $typography['style'];
	} elseif ( isset( $typography['font-style'] ) ) {
		$style = $typography['font-style'];
	} elseif ( ! empty( $typography['italic'] ) || ! empty( $typography['bold'] ) ) {
		$style  = ! empty( $typography['italic'] ) ? 'italic ' : '';
		$style .= ! empty( $typography['bold'] ) ? 'bold' : '';
	} else {
		$style = 'normal';
	}

	return trim( $style );
}
endif;

if ( ! function_exists( 'nice_get_font_size' ) ) :
/**
 * Obtain font-size from an array of typography values.
 *
 * @since  2.0
 *
 * @param  array  $typography List of typography values.
 *
 * @return string
 */
function nice_get_font_size( array $typography = null ) {
	if ( isset( $typography['size'] ) ) {
		$size = floatval( $typography['size'] );
	} elseif ( isset( $typography['font-size'] ) ) {
		$size = floatval( $typography['font-size'] );
	}

	// Add unit.
	if ( isset( $size ) ) {
		$valid_units = array( 'px', 'pt', 'em', 'rem', '%' );
		$unit = empty( $typography['unit'] ) ? 'px' : $typography['unit'];

		if ( in_array( $unit, $valid_units, true ) ) {
			$size .= $unit;
		}
	}

	if ( ! isset( $size ) ) {
		$size = 'inherit';
	}

	return $size;
}
endif;

if ( ! function_exists( 'nice_get_font_letter_spacing' ) ) :
/**
 * Obtain letter-spacing from an array of typography values.
 *
 * @since  2.0
 *
 * @param  array  $typography List of typography values.
 *
 * @return string
 */
function nice_get_font_letter_spacing( array $typography = null ) {
	if ( isset( $typography['letter-spacing'] ) ) {
		$letter_spacing = floatval( $typography['letter-spacing'] );
	}

	// Add unit.
	if ( isset( $letter_spacing ) ) {
		$valid_units = array( 'px', 'em' );
		$unit = empty( $typography['letter-spacing-unit'] ) ? 'px' : $typography['letter-spacing-unit'];

		if ( in_array( $unit, $valid_units, true ) ) {
			$letter_spacing .= $unit;
		}
	}

	if ( ! isset( $letter_spacing ) ) {
		$letter_spacing = 'inherit';
	}

	return $letter_spacing;
}
endif;

if ( ! function_exists( 'nice_option_get_info' ) ) :
/**
 * nice_option_get_info()
 *
 * Display an information field.
 *
 * @since 1.0.5
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the text input.
 */
function nice_option_get_info( $option ) {
	return $option['desc'];
}
endif;


if ( ! function_exists( 'nice_option_get_password' ) ) :
/**
 * nice_option_get_password()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.6
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the text input.
 */
function nice_option_get_password( $option ) {
	$field_value = $option['value'];

	return '<input class="nice-input" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '" type="' . esc_attr( $option['type'] ) . '" value="' . esc_attr( $field_value ) . '" />';
}
endif;

if ( ! function_exists( 'nice_option_get_slider' ) ) :
/**
 * nice_option_get_slider()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.6
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the text input.
 */
function nice_option_get_slider( $option ) {
	$output  = '<div id="' . esc_attr( $option['id'] ) . '_slider" ></div>';
	$output .= '<input type="text" name="' . esc_attr( $option['id'] ) . '" id="' . esc_attr( $option['id'] ) . '" value="' . esc_attr( $option['value'] ) . '" />';

	return $output;
}
endif;


if ( ! function_exists( 'nice_option_get_select_pages' ) ) :
/**
 * nice_option_get_select_pages()
 *
 * Display a select dropdown with the site pages.
 *
 * @since 2.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the select input.
 */
function nice_option_get_select_pages( $option ) {
	$field_value = $option['value'];

	$args = array(
		'name'             => $option['id'],
		'id'               => $option['id'],
		'echo'             => false,
		'selected'         => absint( $field_value ),
		'sort_column'      => 'menu_order',
		'sort_order'       => 'ASC',
		'show_option_none' => esc_html__( 'Select an option', 'nice-framework' ),
	);

	$output = wp_dropdown_pages( $args ); // WPCS: XSS Ok.

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_radio_image' ) ) :
/**
 * nice_option_get_radio_image()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 2.0
 *
 * @param array $option Option info in order return the html code.
 * @return string with the text input.
 */
function nice_option_get_radio_image( $option ) {
	$field_value = $option['value'];
	$field_name  = $option['id'];
	$field_id    = $option['id'];

	$output = '<div class="type-radio-image">';

	foreach ( $option['options'] as $key => $radio_option ) {

		$output .= '<div class="nice-framework-admin-ui-radio-images">';
		$output .= '<p style="display:none">';
		$output .= '<input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . $key . '"' . checked( $field_value, $key, false ) . ' class="nice-framework-admin-ui-radio nice-framework-admin-ui-images" />';
		$output .= '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $radio_option['label'] ) . '</label></p>';
		$output .= '<img src="' . esc_url( $radio_option['image'] ) . '" alt="' . esc_attr( $radio_option['label'] ) . '" title="' . esc_attr( $radio_option['label'] ) . '" class="nice-framework-admin-ui-radio-image nice-tooltip' . ( $field_value === $key ? ' nice-framework-admin-ui-radio-image-selected' : '' ) . '" />';
		$output .= '</div>';

	}

	$output .= '</div>';

	return $output;
}
endif;

if ( ! function_exists( 'nice_option_get_radio_on_off' ) ) :
/**
 * nice_option_get_radio_on_off()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 2.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the text input.
 */
function nice_option_get_radio_on_off( $option ) {
	// $field_value = is_null( $option['value'] ) ? $option['std'] : $option['value'];
	$field_value = nice_int_option( $option['id'], $option['std'] );

	if ( 'nice_post_header' === $option['id'] ) {
		$lol = true;
	}

	$output = '<div class="on-off-switch">';

	foreach ( $option['options'] as $key => $radio_option ) {

		$checked = checked( $field_value, $key, false );

		$output .= '<input class="nice-input nice-radio nice-framework-admin-ui-radio" type="radio" name="' . esc_attr( $option['id'] ) . '" value="' . $key . '" ' . $checked . ' id="' . esc_attr( $option['id'] . '-' . $key ) . '" />';
		$output .= '<label for="' . esc_attr( $option['id'] . '-' . $key ) . '" onclick="">' . esc_attr( $radio_option['label'] ) . '</label>';
	}

	$output .= '<span class="slide-button"></span>';
	$output .= '</div>';

	return $output;

}
endif;

if ( ! function_exists( 'nice_option_get_measurement' ) ) :
/**
 * nice_option_get_measurement()
 *
 * Retrieve option info in order to return the field in html code.
 * Inspired from OT plugin.
 *
 * @since 2.0
 *
 * @param array $option Option info in order return the html code.
 *
 * @return string with the text input.
 */
function nice_option_get_measurement( $option ) {
	$field_value = $option['value'];

	$field_class = isset( $option['class'] ) ? $option['class'] : '' ;

	$output = '<input type="text" name="' . esc_attr( $option['id'] ) . '[0]" id="' . esc_attr( $option['id'] ) . '-0" value="' . ( isset( $field_value[0] ) ? esc_attr( $field_value[0] ) : '' ) . '" class="nice-input ' . esc_attr( $field_class ) . '" autocomplete="off" />' . "\n";

	$output .= '<select name="' . esc_attr( $option['id'] ) . '[1]" id="' . esc_attr( $option['id'] ) . '-1" class="nice-select ' . esc_attr( $field_class ) . '" autocomplete="off">' . "\n";

		$output .= '<option value="">' . esc_html__( 'unit', 'nice-framework' ) . '</option>' . "\n";

		foreach ( nice_measurement_unit_types( $option['id'] ) as $unit ) {
			$output .= '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value[1] ) ? selected( $field_value[1], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>' . "\n";
		}

	$output .= '</select>' . "\n";

	return $output;
}
endif;

if ( ! function_exists( 'nice_measurement_unit_types' ) ) :
/**
 * Measurement Units
 *
 * Returns an array of all available unit types.
 *
 * @since  2.0
 *
 * @param  string $field_id
 *
 * @return array
*/
function nice_measurement_unit_types( $field_id = '' ) {
	/**
	 * @hook nice_measurement_unit_types
	 *
	 * Hook in here to modify the default measurement unit types.
	 *
	 * @since 2.0
	 */
	return apply_filters( 'nice_measurement_unit_types', array(
		'px' => 'px',
		'%'  => '%',
		'em' => 'em',
		'pt' => 'pt',
	), $field_id );
}
endif;


if ( ! function_exists( 'nice_heading_tags_options' ) ) :
/**
 * Return an array of the different Heading tags.
 *
 * @since  2.0
 *
 * @return array
 */
function nice_heading_tags_options() {
	$heading_tags = array(
		''   => esc_html__( 'Default', 'nice-framework' ),
		'h1' => esc_html__( 'h1',      'nice-framework' ),
		'h2' => esc_html__( 'h2',      'nice-framework' ),
		'h3' => esc_html__( 'h3',      'nice-framework' ),
		'h4' => esc_html__( 'h4',      'nice-framework' ),
		'h5' => esc_html__( 'h5',      'nice-framework' ),
		'h6' => esc_html__( 'h6',      'nice-framework' ),
	);

	/**
	 * @hook nice_heading_tags_options
	 *
	 * Hook in here to modify the default array of heading tags.
	 *
	 * @since 2.0
	 */
	return apply_filters( 'nice_heading_tags_options', $heading_tags );
}
endif;

if ( ! function_exists( 'nice_text_transform_options' ) ) :
/**
 * Return an array of the different text transform options.
 *
 * @since  2.0
 *
 * @return array
 */
function nice_text_transform_options() {
	$text_transform = array(
		''           => esc_html__( 'Default',    'nice-framework' ),
		'uppercase'  => esc_html__( 'UPPERCASE',  'nice-framework' ),
		'lowercase'  => esc_html__( 'lowercase',  'nice-framework' ),
		'capitalize' => esc_html__( 'Capitalize', 'nice-framework' ),
	);

	/**
	 * @hook nice_text_transform_options
	 *
	 * Hook in here to modify the default array of text-transform options.
	 *
	 * @since 2.0
	 */
	return apply_filters( 'nice_text_transform_options', $text_transform );
}
endif;
