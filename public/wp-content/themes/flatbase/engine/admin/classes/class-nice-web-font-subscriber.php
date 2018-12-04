<?php
/**
 * NiceThemes Framework Web Font Subscriber
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
 * Class Nice_Web_Font_Subscriber
 *
 * This class helps managing collections of web fonts. Currently, it only
 * provides an integration with Google Fonts.
 *
 * @since 2.0
 */
class Nice_Web_Font_Subscriber {
	/**
	 * List of fonts registered to the current object.
	 *
	 * @var array
	 */
	protected $registered_fonts = array();

	/**
	 * Base URI to serve web fonts from.
	 *
	 * @var string
	 */
	protected $base_uri = '//fonts.googleapis.com/css';

	/**
	 * Nice_Web_Font_Subscriber constructor.
	 *
	 * @param array $fonts
	 */
	public function __construct( array $fonts = array() ) {
		if ( ! empty( $fonts ) ) {
			foreach ( $fonts as $font ) {
				$this->register_font( $font );
			}
		}
	}

	/**
	 * Check if a font is registered to the object.
	 *
	 * @param string $font
	 *
	 * @return bool
	 */
	public function is_registered( $font = '' ) {
		return ! empty( $this->registered_fonts ) && in_array( $font, $this->registered_fonts, true );
	}

	/**
	 * Register a font to the object.
	 *
	 * @param string $font
	 */
	public function register_font( $font = '' ) {
		if ( ! $font || $this->is_registered( $font ) ) {
			return;
		}

		$this->registered_fonts[] = $font;
	}

	/**
	 * Remove a registered font from the object.
	 *
	 * @param string $font
	 */
	public function deregister_font( $font = '' ) {
		if ( ! $this->is_registered( $font ) ) {
			return;
		}

		$key = array_search( $font, $this->registered_fonts );
		unset( $this->registered_fonts[ $key ] );
	}

	/**
	 * Obtain URI for one or more web fonts.
	 *
	 * @param string $fonts
	 *
	 * @return string
	 */
	public function get_uri( $fonts = '' ) {
		$uri = null;
		$family = '';

		if ( is_string( $fonts ) && $this->is_registered( $fonts ) ) {
			$family .= $fonts;
		} elseif ( is_array( $fonts ) && ! empty( $fonts ) ) {
			$valid_fonts = array();

			foreach ( $fonts as $font ) {
				if ( $this->is_registered( $font ) ) {
					$valid_fonts[] = $font;
				}
			}

			if ( ! empty( $valid_fonts ) ) {
				$family .= join( $valid_fonts, '|' );
			}
		}

		if ( $family ) {
			$uri = $this->base_uri . '?family=' . $family;
		}

		return $uri;
	}
}
