<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for the homepage content.
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

$nice_infobox_enable = get_option( 'nice_infobox_enable' );

if ( $nice_infobox_enable === 'true' ) :

	$classes = array();

	$nice_infobox_order  = get_option( 'nice_infobox_order' );

	if ( $nice_infobox_order === '' ) {
		$nice_infobox_order = 'date';
	}

	$text_align = nice_get_option( '_infobox_text_align', true );

	switch ( $text_align ) {
		case 'center' :
			$text_align_class = ' text-align-center';
			break;
		case 'left' :
			$text_align_class = ' text-align-left';
			break;
		case 'right' :
			$text_align_class = ' text-align-right';
			break;
		default :
			$text_align_class = ' text-align-center';
	}

	nicethemes_infoboxes( array(
							'orderby'     => $nice_infobox_order,
							'numberposts' => 3,
							'before'      => '<section id="infoboxes" ' . nice_infobox_class( $classes, false ) . '><div class="col-full ' . $text_align_class . '">',
							'after'       => '</div></section><!--/.#infobox-->'
							)
						);

endif;
