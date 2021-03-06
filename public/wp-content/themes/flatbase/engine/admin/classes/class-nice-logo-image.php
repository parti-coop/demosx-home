<?php
/**
 * NiceThemes Framework Logo Image
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nice_Logo_Image' ) ) :
/**
 * Class Nice_Logo_Image
 *
 * Process, obtain and print HTML for an image meant to be used as the site's
 * logo. This class supports retina and SVG images.
 *
 * @since 2.0
 */
class Nice_Logo_Image {
	/**
	 * Internal name for the image.
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * ID for the image. Used as value for the `id` attribute in the generated
	 * HTML.
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * ID for the retina image. Used as value for the `id` attribute in the generated
	 * HTML.
	 *
	 * @var string
	 * @since 2.0.3
	 */
	protected $id_retina = '';

	/**
	 * Name of HTML tag for image container element.
	 *
	 * @var string
	 */
	protected $container_tag = 'span';

	/**
	 * List of HTML classes to be applied to the image container.
	 *
	 * @var array
	 */
	protected $container_class = array();

	/**
	 * List of HTML classes to be applied to the image.
	 *
	 * @var array
	 */
	protected $img_class = array();

	/**
	 * Alternative HTML text for the image.
	 *
	 * @var string
	 */
	public $alt = '';

	/**
	 * HTML title for the image.
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * URL of the image.
	 *
	 * @var string
	 */
	protected $url = '';

	/**
	 * URL of the image's retina version, if available.
	 *
	 * @var string
	 */
	protected $url_retina = '';

	/**
	 * A valid URL to link the image to.
	 *
	 * @var string
	 */
	public $href = '';

	/**
	 * HTML to be printed before the image's markup.
	 *
	 * @var string
	 */
	public $before = '';

	/**
	 * HTML to be printed after the image's markup.
	 *
	 * @var string
	 */
	public $after = '';

	/**
	 * Image width, in pixels.
	 *
	 * @var int
	 */
	public $width = 0;

	/**
	 * Image height, in pixels.
	 *
	 * @var int
	 */
	public $height = 0;

	/**
	 * Generated HTML.
	 *
	 * @var string
	 */
	protected $output = '';

	/**
	 * Nice_Logo_Images constructor.
	 *
	 * Assign values to properties using an array.
	 *
	 * @param array $atts
	 */
	public function __construct( array $atts = array() ) {
		if ( empty( $atts ) ) {
			return;
		}

		foreach ( $atts as $property => $value ) {
			if ( ! property_exists( __CLASS__, $property ) ) {
				continue;
			}

			$this->{$property} = $value;
		}

		$this->obtain_width();
		$this->obtain_height();
	}

	/**
	 * Remove transients generated by this class.
	 *
	 * @since 1.0.0
	 */
	static public function delete_transients() {
		delete_transient( 'nice_images_width' );
		delete_transient( 'nice_images_height' );
	}

	/**
	 * Set width to be applied to the HTML tag, if only a height was
	 * specified when creating the object.
	 *
	 * @since 1.0.0
	 */
	protected function obtain_width() {
		if ( $this->width || ( ! $this->width && ! $this->height ) ) {
			return;
		}

		$images_width = get_transient( 'nice_images_width' ) ? : array();

		if ( is_array( $images_width ) && isset( $images_width[ $this->id ] ) ) {
			$width = $images_width[ $this->id ];
		} else {
			$image_size  = @getimagesize( $this->url );
			$real_width  = isset( $image_size[0] ) ? $image_size[0] : 0;
			$real_height = isset( $image_size[1] ) ? $image_size[1] : 0;
			$index       = $real_height ? ( $real_height / $this->height ) : 0;
			$width       = $real_width ? ( $real_width / $index ) : 0;

			if ( $width ) {
				$images_width[ $this->id ] = $width;
				delete_transient( 'nice_images_width' );
				set_transient( 'nice_images_width', $images_width, 60 * 60 * 24 );
			}
		}

		$this->width = $width;
	}

	/**
	 * Set height to be applied to the HTML tag, if only a width was
	 * specified when creating the object.
	 *
	 * @since 1.0.0
	 */
	protected function obtain_height() {
		if ( $this->height || ( ! $this->height && ! $this->width ) ) {
			return;
		}

		$images_height = get_transient( 'nice_images_height' ) ? : array();

		if ( is_array( $images_height ) && isset( $images_height[ $this->id ] ) ) {
			$height = $images_height[ $this->id ];
		} else {
			$image_size  = @getimagesize( $this->url );
			$real_width  = isset( $image_size[0] ) ? $image_size[0] : 0;
			$real_height = isset( $image_size[1] ) ? $image_size[1] : 0;
			$index       = $real_width ? ( $real_width / $this->height ) : 0;
			$height      = $real_height ? ( $real_height / $index ) : 0;

			if ( $height ) {
				$images_height[ $this->id ] = $height;
				delete_transient( 'nice_images_height' );
				set_transient( 'nice_images_height', $height, 60 * 60 * 24 );
			}
		}

		$this->height = $height;
	}

	/**
	 * Generate HTML.
	 */
	public function process_output() {
		if ( $this->output ) {
			_nice_doing_it_wrong( __METHOD__, esc_html__( 'The output property should be empty before processing HTML', 'nice-framework' ), '2.0' );
		}

		$this->output .= $this->before;

		$this->output .= '<' . $this->container_tag . ' id="' . $this->id . '-wrapper" ' . $this->get_container_class_attr() . '>';

		if ( $this->href ) {
			$this->output .= '<a href="' . esc_url( $this->href ) . '"';
			$this->output .= $this->title ? ' title="' . esc_attr( $this->title ) . '"' : '';
			$this->output .= '>';
		}

		$this->output .= $this->get_default_size_html();
		$this->output .= $this->get_retina_html();

		if ( $this->href ) {
			$this->output .= '</a>';
		}

		$this->output .= '</' . $this->container_tag . '>';

		$this->output .= $this->after;

		/**
		 * @hook nice_logo_image_output
		 *
		 * Hook here to modify the output of logo images.
		 */
		$this->output = apply_filters( 'nice_logo_image_output', $this->output, $this );
	}

	/**
	 * Reset generated HTML.
	 */
	public function flush_output() {
		$this->output = null;
	}

	/**
	 * Obtain generated HTML.
	 *
	 * @return string
	 */
	public function get_output() {
		return $this->output;
	}

	/**
	 * Print out generated HTML.
	 */
	public function print_output() {
		echo $this->output; // WPCS: XSS ok.
	}

	/**
	 * Obtain HTML for the normal-sized version of the image.
	 *
	 * @return string
	 */
	public function get_default_size_html() {
		return $this->is_svg( $this->url ) ? $this->get_svg_html( $this->url, false ) : $this->get_img_html( $this->url );
	}

	/**
	 * Obtain HTML for the retina version of the image.
	 *
	 * @return string
	 */
	public function get_retina_html() {
		$url = $this->url_retina ? : $this->url;

		return $this->is_svg( $url ) ? $this->get_svg_html( $url, true ) : $this->get_img_html( $url, true );
	}

	/**
	 * Obtain HTML for the image.
	 *
	 * @param  string $url
	 * @param  bool   $retina
	 *
	 * @return null|string
	 */
	public function get_img_html( $url = '', $retina = false ) {
		if ( ! $url ) {
			return null;
		}

		$output = '<img src="' . esc_url( $url ) . '"';

		if ( $this->id ) {
			$id = $this->id;

			if ( $retina ) {
				$id = $this->id_retina ? $this->id_retina : $id . '-retina';
			}

			$output .= ' id="' . esc_attr( $id ) . '"';
		}

		if ( $this->width ) {
			$output .= ' width="' . esc_attr( (int) $this->width ) . '"';
		}

		if ( $this->height ) {
			$output .= ' height="' . esc_attr( (int) $this->height ) . '"';
		}

		if ( $this->alt ) {
			$output .= ' alt="' . esc_attr( $this->alt ) . '"';
		}

		if ( $this->title ) {
			$output .= ' title="' . esc_attr( $this->title ) . '"';
		}

		$classes = $this->get_img_class_attr( $retina );
		$output .=  $classes ? ' ' . $classes : '';

		$output .= ' />' . "\n";

		return $output;
	}

	/**
	 * Check if a given URL matches an SVG file.
	 *
	 * @since  2.0.9
	 *
	 * @param  string $url
	 *
	 * @return bool
	 */
	protected static function is_svg( $url ) {
		$filetype = wp_check_filetype( $url );

		return 'svg' === $filetype['ext'];
	}

	/**
	 * Obtain HTML for an SVG image.
	 *
	 * @param  string $url
	 * @param  bool   $retina
	 *
	 * @return string
	 */
	public function get_svg_html( $url = '', $retina = false ) {
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
		}

		WP_Filesystem();
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		global $wp_filesystem;

		$output = $wp_filesystem->get_contents( esc_url( $url ) );

		if ( $this->id ) {
			$id = $this->id;

			if ( $retina ) {
				$id .= '-retina';
			}

			$output = preg_replace( '/<svg/', '<svg id="' . esc_attr( $id ) . '"', $output );
		}

		if ( $this->width ) {
			$output = preg_replace( '/<svg/', '<svg width="' . esc_attr( (int) $this->width ) . '"', $output );
		}

		if ( $this->height ) {
			$output = preg_replace( '/<svg/', '<svg height="' . esc_attr( (int) $this->height ) . '"', $output );
		}

		if ( $this->alt ) {
			$output = preg_replace( '/<svg/', '<svg alt="' . esc_attr( $this->alt ) . '"', $output );
		}

		if ( $this->title ) {
			$output = preg_replace( '/<svg/', '<svg title="' . esc_attr( $this->title ) . '"', $output );
		}

		if ( ! empty( $this->img_class ) ) {
			$output = preg_replace( '/<svg/', '<svg ' . $this->get_img_class_attr( $retina ), $output );
		}

		return $output;
	}

	/**
	 * Obtain `class` HTML attribute and its values for the image container.
	 *
	 * @return string
	 */
	protected function get_container_class_attr() {
		$classes = array(
			'header-logo-wrapper',
		);

		$classes = array_merge( $classes, $this->container_class );

		$output = nice_css_classes( $classes, false );

		return $output;
	}

	/**
	 * Obtain `class` HTML attribute and its values for the current image.
	 *
	 * @param  bool $retina
	 *
	 * @return string
	 */
	protected function get_img_class_attr( $retina = false ) {
		$classes = array(
			'img-logo',
		);

		if ( $retina ) {
			$classes[] = 'img-logo-retina';
		}

		$classes = array_merge( $classes, $this->img_class );

		$output = nice_css_classes( $classes, false );

		return $output;
	}
}
endif;
