<?php
/**
 * NiceThemes Framework Customizer
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nice_Customizer' ) ) :
/**
 * Class Nice_Customizer
 *
 * Handle interactions with Customizer by using Kirki and some customized
 * scripting.
 *
 * @package Nice_Framework
 * @author  NiceThemes <hello@nicethemes.com>
 * @since   2.0
 */
class Nice_Customizer {
	/**
	 * Current theme options.
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * Options template, including default option values.
	 *
	 * @var array
	 */
	public $template = array();

	/**
	 * Type of option to be saved and retrieved by the Customizer. Accepted
	 * values are `option` and `theme_mod`.
	 *
	 * @var string
	 */
	public $option_type = 'option';

	/**
	 * Name of the main key for the options. If specified, the options will be
	 * saved as an array under this key. If not, they will be saved as single
	 * options.
	 *
	 * @var string
	 */
	public $option_name = '';

	/**
	 * ID for main panel. If not specified, all Customizer sections will appear
	 * in the first level.
	 *
	 * @var string
	 */
	public $panel_id = '';

	/**
	 * Name of the main panel. The recommended value is the name of the active
	 * theme.
	 *
	 * @var string
	 */
	public $panel_name = '';

	/**
	 * Description of main panel.
	 *
	 * @var string
	 */
	public $panel_description = '';

	/**
	 * Configuration ID. Options will not be saved if left empty.
	 *
	 * @var string
	 */
	public $config_id = '';

	/**
	 * Array consisting on out native option types as keys, and Kirki's option
	 * types as their equivalent values. We need this to know the correct
	 * format of each option before when registering it to the Customizer.
	 *
	 * @var array
	 */
	public $field_map = array();

	/**
	 * ID of the last registered section.
	 *
	 * @var string
	 */
	protected $current_section = '';

	/**
	 * Priority to match the last registered section.
	 *
	 * @var int
	 */
	protected $current_section_priority = 0;

	/**
	 * Priority of the last registered field.
	 *
	 * @var int
	 */
	protected $current_field_priority = 0;

	/**
	 * Priority of the main panel.
	 *
	 * @var int
	 */
	protected $panel_priority = 0;

	/**
	 * Fire main functionality.
	 *
	 * @param array $args
	 */
	public function __construct( array $args ) {
		$this->set_properties( $args );
		$this->load_dependencies();
		$this->set_section_priority();

		/**
		 * @hook nice_customizer
		 *
		 * Hook here to execute actions right before the object finish its
		 * initialization.
		 *
		 * @since 2.0
		 *
		 * @see Nice_Customizer::enqueue_styles()
		 * @see Nice_Customizer::enqueue_scripts()
		 */
		do_action( 'nice_customizer', $this );
	}

	/**
	 * Set values of existing properties.
	 *
	 * @param array $args
	 *
	 * @since 2.0
	 */
	protected function set_properties( array $args ) {
		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( property_exists( $this, $key ) ) {
					$this->{$key} = $value;
				}
			}
		}
	}

	/**
	 * Load required dependencies.
	 *
	 * @since 2.0
	 */
	protected function load_dependencies() {
		if ( ! class_exists( 'Kirki' ) ) {
			nice_loader( 'engine/admin/lib/kirki/kirki.php' );
		}
	}

	/**
	 * Enqueue custom styles.
	 *
	 * @since 2.0
	 */
	public function enqueue_styles() {
		wp_register_style( 'nice-customizer-styles', nice_get_file_uri( 'engine/admin/assets/css/customizer.css' ) );
		wp_enqueue_style( 'nice-customizer-styles' );

		/**
		 * @hook nice_customizer_styles
		 *
		 * Hook in here to modify inline styles for Customizer.
		 *
		 * @since 2.0.8
		 */
		wp_add_inline_style( 'nice-customizer-styles', apply_filters( 'nice_customizer_styles', '' ) );

		wp_register_style( 'nice-budicon', nice_get_file_uri( 'engine/admin/assets/css/budicon.css' ) );
		wp_enqueue_style( 'nice-budicon' );
	}

	/**
	 * Enqueue custom scripts.
	 *
	 * @since 2.0
	 */
	public function enqueue_scripts() {
		$headings_list = array();
		$groups_list   = array();

		 // Create a list of values to be passed to our custom script.
		if ( ! empty( $this->template ) ) {
			$current_group = null;

			foreach ( $this->template as $setting ) {
				if ( 'heading' === $setting['type'] ) {
					$current_group = null;
					$current_heading = sanitize_title( $setting['name'] );

					$headings_list[] = array(
						'id'    => sanitize_title( $setting['name'] ),
						'title' => $setting['name'],
						'icon'  => isset( $setting['icon'] ) ? $setting['icon'] : '',
						'hasParent' => isset( $setting['parent'] ),
					);
				} elseif ( isset( $current_heading ) && 'group' === $setting['type'] ) {
					$current_group = $current_heading . '-' . sanitize_title( $setting['name'] );

					$groups_list[ $current_group ] = array(
						'id'       => $current_group,
						'settings' => array(),
					);

				} elseif ( ! is_null( $current_group ) && isset( $setting['id'] ) ) {
					array_push( $groups_list[ $current_group ]['settings'], $setting['id'] );
				}
			}
		}

		$customizer_vars = array(
			'headings' => $headings_list,
			'groups'   => $groups_list,
		);

		wp_register_script( 'nice-customizer', nice_get_file_uri( 'engine/admin/assets/js/nice-customizer.js' ), array( 'jquery', 'customize-controls' ), false, true );
		wp_localize_script( 'nice-customizer', 'NiceCustomizerVars', $customizer_vars );
		wp_enqueue_script( 'nice-customizer' );
	}

	/**
	 * Set priority of current section.
	 *
	 * @since  2.0
	 *
	 * @param  mixed|null|int $priority
	 *
	 * @return int
	 */
	protected function set_section_priority( $priority = null ) {
		if ( is_integer( $priority ) ) {
			$this->current_section_priority = $priority;
		}

		return $this->current_section_priority += 10;
	}

	/**
	 * Set priority of current field.
	 *
	 * @since  2.0
	 *
	 * @param  null $priority
	 *
	 * @return int
	 */
	protected function set_field_priority( $priority = null ) {
		if ( ! is_null( $priority ) ) {
			$this->current_field_priority = $priority;
		}

		return $this->current_field_priority += 10;
	}

	/**
	 * Process initial setup.
	 *
	 * @since 2.0
	 */
	public function setup() {
		// Configure Kirki.
		Kirki::add_config( $this->config_id, array(
			'capability'    => 'edit_theme_options',
			'option_type'   => $this->option_type,
			'option_name'   => $this->option_name,
		) );

		// Return early if a panel ID wasn't set.
		if ( ! $this->panel_id  ) {
			return;
		}

		// Add main panel.
		Kirki::add_panel( $this->panel_id, array(
			'priority'    => $this->panel_priority,
			'title'       => $this->panel_name,
			'description' => $this->panel_description,
		) );
	}

	/**
	 * Fire registration of sections and settings.
	 *
	 * @since 2.0
	 */
	public function register() {
		// Return early if we don't have a template.
		if ( empty( $this->template ) ) {
			return;
		}

		foreach ( $this->template as $setting ) {
			$this->register_setting( $setting );
		}
	}

	/**
	 * Process registration of a given setting.
	 *
	 * @since 2.0
	 *
	 * @param array $setting
	 */
	protected function register_setting( array $setting ) {
		// Don't register setting if it should be ignored in Customizer.
		if ( ! empty( $setting['ignore_customizer'] ) ) {
			return;
		}

		switch ( $setting['type'] ) {
			case 'group' :
				$this->add_group( $setting );
				break;
			case 'heading' : // Add section if we have a heading.
				$this->add_section( $setting );
				break;
			default: // Add a new field.
				$this->add_field( $setting );
				break;
		}
	}

	/**
	 * Add the header for a group of options.
	 *
	 * @since 2.0
	 *
	 * @param array $setting
	 */
	protected function add_group( array $setting ) {
		Kirki::add_field( '', array(
			'type'     => 'custom',
			'settings' => $this->get_group_id( $setting ),
			'section'  => $this->current_section,
			'priority' => $this->set_field_priority(),
			'default'  => '<span class="settings-group-toggle">' . $setting['icon'] . ' ' . $this->get_group_name( $setting ) . '</span>',
		) );
	}

	/**
	 * Obtain an internal ID for group of options.
	 *
	 * @since  2.0
	 *
	 * @param  array $setting
	 *
	 * @return string
	 */
	protected function get_group_id( array $setting ) {
		return sanitize_title( $this->current_section . '-' . $this->get_group_name( $setting ) );
	}

	/**
	 * Obtain the name of a group from a list of setting values.
	 *
	 * @since  2.0
	 *
	 * @param  array $setting
	 *
	 * @return string
	 */
	protected function get_group_name( array $setting ) {
		$name = '';

		if ( isset( $setting['name'] ) ) {
			$name .= $setting['name'];
		}

		return $name;
	}

	/**
	 * Add a new section to Customizer.
	 *
	 * @since 2.0
	 *
	 * @param array $setting
	 */
	protected function add_section( array $setting ) {
		$this->current_section = sanitize_title( $setting['name'] );
		$this->set_field_priority( 0 );

		Kirki::add_section( $this->current_section, array(
			'title'      => $this->get_section_name( $setting ),
			'priority'   => $this->set_section_priority(),
			'capability' => 'edit_theme_options',
			'panel'      => $this->panel_id,
		) );
	}

	/**
	 * Obtain the name of a section from a list of setting values.
	 *
	 * @since  2.0
	 *
	 * @param  array $setting
	 *
	 * @return string
	 */
	protected function get_section_name( array $setting ) {
		$name = '';

		if ( isset( $setting['name'] ) ) {
			$name .= html_entity_decode( $setting['name'] );
		}

		return $name;
	}

	/**
	 * Add a field to the Customizer using a list of setting values.
	 *
	 * @since 2.0
	 *
	 * @param array $setting
	 */
	protected function add_field( array $setting ) {
		Kirki::add_field( $this->config_id, $this->get_field_properties( $setting ) );
	}

	/**
	 * Obtain field properties from a list of setting values.
	 *
	 * @since  2.0
	 *
	 * @param  array $setting
	 *
	 * @return array
	 */
	protected function get_field_properties( array $setting ) {
		$properties = array(
			'type'        => $this->get_field_type( $setting ),
			'settings'    => isset( $setting['id'] ) ? $setting['id'] : '',
			'label'       => isset( $setting['name'] ) ? $setting['name'] : '',
			'section'     => $this->current_section,
			'description' => isset( $setting['desc'] ) ? $setting['desc'] : '',
			'help'        => isset( $setting['tip'] ) ? $setting['tip'] : '',
			'default'     => $this->get_field_default( $setting ),
			'priority'    => $this->set_field_priority(),
			'choices'     => $this->get_field_choices( $setting ),
			'required'    => $this->get_required( $setting ),
		);

		// Allow extra properties from setting.
		$extra = ! empty( $setting['extra'] ) ? $setting['extra'] : array();

		// Merge obtained and extra properties.
		$properties = array_merge( $properties, $extra );

		// Unset all null properties.
		foreach ( $properties as $property_id => $property_value ) {
			if ( is_null( $property_value ) ) {
				unset( $properties[ $property_id ] );
			}
		}

		return $properties;
	}

	/**
	 * Obtain values to manage conditional fields from a list of setting values.
	 *
	 * @since  2.0
	 *
	 * @param  array $setting
	 *
	 * @return array|null
	 */
	protected function get_required( array $setting ) {
		$required = null;

		if ( isset( $setting['condition'] ) ) {
			$conditions = explode( ',', str_replace( ' ', '', trim( $setting['condition'], ',' ) ) );

			if ( ! empty( $conditions ) ) {
				foreach ( $conditions as $condition ) {
					$condition = explode( ':', $condition );

					$operator_map = array(
						'is'                       => '==',
						'not'                      => '!=',
						'less_than'                => '<',
						'less_than_or_equal_to'    => '<=',
						'greater_than'             => '>',
						'greater_than_or_equal_to' => '>=',
					);

					/**
					 * Normalize conditions for options that depend on radio
					 * on/off settings.
					 *
					 * @since 2.0.9
					 */
					if ( 'radio_on_off' === nice_get_option_type( $condition[0] ) ) {
						$condition[1] = str_replace( '(true)', '(1)', $condition[1] );
						$condition[1] = str_replace( '(false)', '(0)', $condition[1] );
					}

					$condition_setting  = ! empty( $this->option_name ) ? $this->option_name . '[' . $condition[0] . ']' : $condition[0];
					$condition_compare  = ! empty( $condition[1] ) ? substr( $condition[1], 0, stripos( $condition[1], '(' ) ) : 'is';
					$condition_operator = isset( $operator_map[ $condition_compare ] ) ? $operator_map[ $condition_compare ] : '==';
					$condition_value    = ! empty( $condition[1] ) ? substr( $condition[1], stripos( $condition[1], '(' ) + 1, -1 ) : true;

					$required[] = array(
						'setting'  => $condition_setting,
						'operator' => $condition_operator,
						'value'    => $condition_value,
					);
				}
			}
		}

		return $required;
	}

	/**
	 * Obtain the mapped type of a field from a list of setting values.
	 *
	 * @since  2.0
	 *
	 * @param  array $setting
	 *
	 * @return null
	 */
	protected function get_field_type( array $setting ) {
		if ( isset( $setting['type'] ) && isset( $this->field_map[ $setting['type'] ] ) ) {
			return $this->field_map[ $setting['type'] ];
		}

		return null;
	}

	/**
	 * Obtain default values for a field from a list of setting values.
	 *
	 * @since  2.0
	 *
	 * @param  array $setting
	 *
	 * @return array|bool|string
	 */
	protected function get_field_default( array $setting ) {
		switch ( $this->get_field_type( $setting ) ) {
			case 'switch':
				$default = nice_bool( $setting['std'] ) ? '1' : $setting['std'];
				break;
			case 'checkbox':
				$default = nice_bool( $setting['std'] ) ? 'true' : 'false';
				break;
			case 'slider':
				$default = isset( $setting['std']['value'] ) ? $setting['std']['value'] : '';
				break;
			case 'upload':
				$default = nice_get_option( $setting['id'] );
				break;
			case 'image':
				$default = nice_get_option( $setting['id'] );
				break;
			case 'typography':
				$default = array(
					'font-style'     => array(),
					'font-family'    => isset( $setting['std']['font-family'] ) ? $setting['std']['font-family'] : '',
					'font-size'      => isset( $setting['std']['font-size'] ) ? $setting['std']['font-size'] : '',
					'color'          => isset( $setting['std']['color'] ) ? $setting['std']['color'] : '',
					'option'         => $setting,
				);
				break;
			default:
				$default = isset( $setting['std'] ) ? $setting['std'] : '';
				break;
		}

		return $default;
	}

	/**
	 * Obtain options for `select_sidebar` settings.
	 *
	 * @since  2.0.9
	 *
	 * @return array|null
	 */
	protected static function get_sidebar_options() {
		static $sidebar_options = null;
		global $wp_registered_sidebars;

		if ( is_null( $sidebar_options ) && is_array( $wp_registered_sidebars ) ) {
			$sidebar_options = array(
				'' => esc_html__( 'Default', 'nice-framework' ),
			);

			foreach ( $wp_registered_sidebars as $sidebar ) {
				$sidebar_options[ $sidebar['id'] ] = $sidebar['name'];
			}
		}

		return $sidebar_options;
	}

	/**
	 * Obtain choices for a field from a list of setting values.
	 *
	 * @since  2.0
	 *
	 * @param  array $setting
	 *
	 * @return array
	 */
	protected function get_field_choices( array $setting ) {
		switch ( $this->get_field_type( $setting ) ) {
			case 'select':
				$choices = array();

				if ( 'select_sidebar' === $setting['type'] ) {
					$setting['options'] = self::get_sidebar_options();
				}

				if ( ! empty( $setting['options'] ) ) {
					foreach ( $setting['options'] as $value => $label ) {
						if ( 'select_color' === $setting['type'] ) {
							if ( ! $value ) {
								$value = esc_html__( 'Default', 'nice-framework' );
							}

							$choices[ $value ] = $label['name'];
						} else {
							$choices[ $value ] = $label;
						}
					}
				}
				break;
			case 'switch':
				$choices = array();
				if ( ! empty( $setting['options'] ) ) {
					foreach ( $setting['options'] as $value => $option ) {
						$choices[ $value ] = $option['label'];
					}
				}
				break;
			case 'slider':
				$choices = array(
					'min'  => $setting['std']['min'],
					'max'  => $setting['std']['max'],
					'step' => 1,
				);
				break;
			case 'radio-image':
				$choices = array();
				if ( ! empty( $setting['options'] ) ) {
					foreach ( $setting['options'] as $value => $option ) {
						$choices[ $value ] = $option['image'];
					}
				}
				break;
			case 'typography':
				$default_unit  = empty( $setting['std']['unit'] ) ? array( 'px' ) : array( $setting['std']['unit'] );
				$units         = empty( $setting['units'] ) ? $default_unit : $setting['units'];

				$choices = array(
					'font-style'     => array(
						'bold'   => ! empty( $setting['font_styles']['bold'] ),
						'italic' => ! empty( $setting['font_styles']['italic'] ),
					),
					'font-family'    => isset( $setting['std']['font-family'] ),
					'font-size'      => true,
					'font-weight'    => false,
					'line-height'    => false,
					'letter-spacing' => false,
					'color'          => isset( $setting['std']['color'] ),
					'fonts'          => self::get_fonts(),
					'units'          => $units,
				);
				break;
			default:
				$choices = null;
				break;
		}

		return $choices;
	}

	/**
	 * Obtain fonts for typography fields.
	 *
	 * @since  2.0
	 *
	 * @return array
	 */
	protected static function get_fonts() {
		static $fonts = array();

		if ( empty( $fonts ) ) {
			$websafe_fonts = nice_get_websafe_fonts();
			$google_fonts  = nice_google_font_names();

			sort( $google_fonts );

			$raw_fonts_data = array_merge( $websafe_fonts, $google_fonts );

			foreach ( $raw_fonts_data as $font ) {
				$fonts[ $font ] = $font;
			}
		}

		return $fonts;
	}
}
endif;
