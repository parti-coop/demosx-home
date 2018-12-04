<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage custom styles for admin post editor.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2016 NiceThemes
 * @since     1.0.4
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_add_editor_custom_styles' ) ) :
add_action( 'after_wp_tiny_mce', 'nice_add_editor_custom_styles' );
/**
 * Make sure custom styles are correctly applied to TinyMCE editor.
 *
 * We use jQuery to wait for the document to be ready, and then we rely on a
 * little timeout to ensure TinyMCE is completely loaded before applying styles
 * to the content iframe. We do it via jQuery since iframes can't be efficiently
 * modified via linked or inline CSS.
 *
 * This function is pretty hacky, since neither WordPress nor TinyMCE offer
 * a simple way to apply styles dynamically to a TinyMCE box at the time.
 * Maybe in the future we could come up with a more elegant way to achieve this.
 *
 * {@link http://codex.wordpress.org/TinyMCE}
 *
 * @since 1.0.0
 */
function nice_add_editor_custom_styles() {
	global $nice_options;
	if ( ! $nice_options ) {
		$nice_options = get_option( 'nice_options' );
	}

	if (   isset( $nice_options['nice_custom_typography'] )
	    && nice_bool( $nice_options['nice_custom_typography'] )
		&& ! empty( $nice_options['nice_font_body'] )
	) {
		?>
		<script>
			function nice_apply_editor_custom_styles( $ ) {
				setTimeout( function () {
					var tinymce = $( 'iframe#content_ifr' ).contents().find( '#tinymce' );
					tinymce.css( 'color', '<?php echo $nice_options['nice_font_body']['color']; ?>' );
					tinymce.css( 'font-family', '<?php echo $nice_options['nice_font_body']['family']; ?>' );
					tinymce.css( 'font-style', '<?php echo $nice_options['nice_font_body']['style']; ?>' );
					tinymce.css( 'font-size', '<?php echo $nice_options['nice_font_body']['size']; ?>' );
				}, 1 )
			}
			jQuery( document ).ready( function() {
				nice_apply_editor_custom_styles( jQuery );
			} );
			jQuery( '#content-tmce' ).click( function() {
				nice_apply_editor_custom_styles( jQuery );
			} );
		</script>
		<?php
	}
}
endif;
