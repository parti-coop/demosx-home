<?php
/**
 * Flatbase by NiceThemes.
 *
 * Utilitary functions for contact template.
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

if ( ! function_exists( 'nice_heading_contact_page' ) ) :
add_filter( 'nice_heading', 'nice_heading_contact_page' );
/**
 * Add Google Maps embed code after page heading in contact page.
 *
 * @since  2.0
 *
 * @param  string string $output
 *
 * @return string
 */
function nice_heading_contact_page( $output = '' ) {
	if ( is_page_template( 'page-templates/contact.php' ) && $nice_google_map = nice_get_option( 'nice_google_map' ) ) {
		ob_start();

		nice_embed( array(
				'embed_id' => 'nice-contact-map',
				'embed'  => $nice_google_map,
				'class'  => 'nice-contact-map-container',
				'width'  => '100%',
				'height' => 340,
			)
		);

		$output .= ob_get_contents();
		ob_end_clean();
	}

	if ( $output ) {
		echo $output;
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_enqueue_contact_map_scripts' ) ) :
add_action( 'nice_register_scripts', 'nice_enqueue_contact_map_scripts', 20 );
/**
 * Enqueue scripts to handle contact map.
 *
 * @since 2.0
 */
function nice_enqueue_contact_map_scripts() {

}
endif;
