<?php
/**
 * NiceThemes Framework Logo
 *
 * @package Nice_Framework
 * @license GPL-2.0+
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nice_Logo' ) ) :
/**
 * Class Nice_Logo
 *
 * Process, obtain and print HTML for the site's logo. This class supports
 * text-based logo and multiple images, and includes a default output method.
 *
 * @since 2.0
 */
class Nice_Logo {
	/**
	 * List of images to be printed. Each element in the array will be used to
	 * create an instance of Nice_Logo_Image.
	 *
	 * @see Nice_Logo_Image
	 *
	 * @var array
	 */
	protected $images = array();

	/**
	 * Default image to be used in case the list of images is empty. The array
	 * will be used to create an instance of Nice_Logo_Image.
	 *
	 * @see Nice_Logo_Image
	 *
	 * @var Nice_Logo_Image
	 */
	protected $default_image = null;

	/**
	 * A valid link for the logo to point to. It will be used as the default
	 * link for images that don't have a link specified.
	 *
	 * @var string
	 */
	protected $href = '';

	/**
	 * Whether the logo should link to a specific URL or not.
	 *
	 * @var bool
	 */
	protected $enable_link = true;

	/**
	 * Default alternative text for images that don't have it specified.
	 *
	 * @see Nice_Logo_Image::$alt
	 *
	 * @var string
	 */
	protected $alt = '';

	/**
	 * Default title text for images that don't have it specified. Used as the
	 * logo title when $text_title is set to true. Normally, it will be the
	 * site's title.
	 *
	 * @see Nice_Logo_Image::$title
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Text used as tagline when both $text_title and $text_tagline are set to
	 *true. Normally, it will be the site's tagline.
	 *
	 * @var string
	 */
	protected $tagline = '';

	/**
	 * Whether to use a text-based logo instead of images.
	 *
	 * @var bool
	 */
	protected $text_title = false;

	/**
	 * Whether to use a text-based tagline when $text_title is set to true.
	 *
	 * @var bool
	 */
	protected $text_tagline = false;

	/**
	 * HTML to be added before the logo markup.
	 *
	 * @var string
	 */
	protected $before = '';

	/**
	 * HTML to be added after the logo markup.
	 *
	 * @var string
	 */
	protected $after = '';

	/**
	 * HTML to be printed right before the logo is printed, and after any
	 * higher-level opening wrappers.
	 *
	 * @var string
	 */
	protected $before_title = '';

	/**
	 * HTML to be printed right after the logo is printed, and before any
	 * higher-level closing wrappers.
	 *
	 * @var string
	 */
	protected $after_title = '';

	/**
	 * HTML to be printed before the text-based tagline when both $text_title
	 * and $text_tagline are set to true.
	 *
	 * @var string
	 */
	protected $before_tagline = '';

	/**
	 * HTML to be printed after the text-based tagline when both $text_title
	 * and $text_tagline are set to true.
	 *
	 * @var string
	 */
	protected $after_tagline = '';

	/**
	 * Default HTML to be printed before an image that doesn't have a specific
	 * value for its $before property.
	 *
	 * @see Nice_Logo_Image::$before
	 *
	 * @var string
	 */
	protected $before_image = '';

	/**
	 * Default HTML to be printed after an image that doesn't have a specific
	 * value for its $after property.
	 *
	 * @see Nice_Logo_Image::$after
	 *
	 * @var string
	 */
	protected $after_image = '';

	/**
	 * Generated HTML.
	 *
	 * @var string
	 */
	protected $output = '';

	/**
	 * Nice_Logo constructor.
	 *
	 * Assign values to properties using an array.
	 *
	 * @param array $atts
	 */
	public function __construct( array $atts = array() ) {
		// Return early if we don't receive attributes.
		if ( empty( $atts ) ) {
			return;
		}

		// Assign values.
		foreach ( $atts as $property => $value ) {
			if ( ! property_exists( __CLASS__, $property ) ) {
				continue;
			}

			$this->{$property} = $value;
		}
	}

	/**
	 * Magic method to obtain the value of protected and private properties,
	 * and to set them if needed.
	 *
	 * @param $property
	 *
	 * @return mixed
	 */
	public function __get( $property ) {
		// Return value of property if the property exists.
		if ( property_exists( $this, $property ) ) {
			return $this->$property;
		}

		return null;
	}

	/**
	 * Generate HTML.
	 */
	public function process_output() {
		if ( $this->output ) {
			_nice_doing_it_wrong( __METHOD__, esc_html__( 'The output property should be empty before processing HTML', 'nice-framework' ), '2.0' );
		}

		$this->output .= $this->before;

		if ( $this->text_title ) {
			$this->output .= $this->get_text_title();
		} elseif ( ! empty( $this->images ) ) {
			$this->output .= $this->get_images_output();
		} else {
			$this->output .= $this->get_default_output();
		}

		$this->output .= $this->after;
	}

	/**
	 * Obtain text title when it applies.
	 *
	 * @return null|string
	 */
	public function get_text_title() {
		if ( ! $this->text_title ) {
			return null;
		}

		$output = $this->before_title;

		if ( $this->enable_link && $this->href ) {
			$output .= '<a href="' . esc_url( $this->href ) . '" title="' . esc_html( $this->title ) . '">';
		}

		$output .= '<span class="text-logo">' . esc_html( $this->title ) . '</span>';

		if ( $this->text_tagline && $this->tagline ) {
			$output .= $this->before_tagline;
			$output .= '<span class="tagline">' . esc_attr( $this->tagline ) . '</span>';
			$output .= $this->after_tagline;
		}

		if ( $this->enable_link && $this->href ) {
			$output .= '</a>';
		}

		$output .= $this->after_title;

		return $output;
	}

	/**
	 * Obtain HTML for a single image.
	 *
	 * @param Nice_Logo_Image $image
	 *
	 * @return string
	 */
	public function get_single_image_output( Nice_Logo_Image $image ) {
		if ( ! $image->alt && $this->alt ) {
			$image->alt = $this->alt;
		}

		if ( ! $image->title && $this->title ) {
			$image->title = $this->title;
		}

		if ( ! $image->href && $this->href ) {
			$image->href = $this->href;
		}

		if ( ! $this->enable_link ) {
			$image->href = null;
		}

		if ( ! $image->before && $this->before_image ) {
			$image->before = $this->before_image;
		}

		if ( ! $image->after && $this->after_image ) {
			$image->after = $this->after_image;
		}

		$image->process_output();

		return $image->get_output();
	}

	/**
	 * Obtain HTML for all images contained in the $images array.
	 *
	 * @see Nice_Logo::$images
	 *
	 * @return null|string
	 */
	public function get_images_output() {
		if ( empty( $this->images ) ) {
			return null;
		}

		$output = $this->before_title;

		foreach ( $this->images as $image ) {
			$output .= $this->get_single_image_output( $image );
		}

		$output .= $this->after_title;

		return $output;
	}

	/**
	 * Obtain HTML for the default image.
	 *
	 * @see Nice_Logo::$default_image
	 *
	 * @return string
	 */
	public function get_default_output() {
		$output  = $this->before_title;
		$output .= $this->get_single_image_output( $this->default_image );
		$output .= $this->after_title;

		return $output;
	}

	/**
	 * Reset logo output.
	 */
	public function flush_output() {
		$this->output = null;
	}

	/**
	 * Obtain logo output.
	 *
	 * @return string
	 */
	public function get_output() {
		return $this->output;
	}

	/**
	 * Print out logo output.
	 */
	public function print_output() {
		echo $this->output; // WPCS: XSS ok.
	}
}
endif;
