<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the loader component for this theme.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_full_page_overlay_loader' ) ) :
add_action( 'nice_before_wrapper', 'nice_full_page_overlay_loader' );
/**
 * Print HTML for the full page loader.
 *
 * @since 2.0
 */
function nice_full_page_overlay_loader() {
	if ( 'full_page' !== nice_get_option( 'nice_page_loader' ) ) {
		return;
	}

	?>
		<div class="nice-page-loader nice-page-loader-full">
			<?php
				/**
				 * @hook nice_full_page_overlay_loader
				 *
				 * Print out loader content.
				 *
				 * Hooked here:
				 * @see nice_full_page_overlay_loader_content() - 10
				 *
				 * @since 2.0
				 */
				do_action( 'nice_full_page_overlay_loader' );
			?>
		</div>
	<?php
}
endif;

if ( ! function_exists( 'nice_full_page_overlay_loader_content' ) ) :
add_action( 'nice_full_page_overlay_loader', 'nice_full_page_overlay_loader_content' );
/**
 * Display content for full page overlay.
 *
 * @since 2.0
 */
function nice_full_page_overlay_loader_content() {
	$icon = nice_get_option( '_page_loader_full_page_loader_icon', 'spin' );

	ob_start();

	if ( 'beat' === $icon ) : ?>

		<div class="loader"></div>

	<?php elseif ( 'spin' === $icon ) : ?>

		<svg class="spinner" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"></circle>
		</svg>

	<?php endif; ?>

	<?php
	$content = ob_get_clean();

	/**
	 * @hook nice_full_page_overlay_loader_content
	 *
	 * Hook in here to modify the HTML output of the loader.
	 *
	 * @since 2.0
	 */
	echo apply_filters( 'nice_full_page_overlay_loader_content', $content );
}
endif;
