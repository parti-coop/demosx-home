<?php
/**
 * Flatbase by NiceThemes.
 *
 * Template Name: Home
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

	<?php get_header(); ?>

		<?php
		/**
		 * @hook homepage
		 *
		 * Display available home page elements.
		 *
		 * This action is meant to be compatible with the Homepage
		 * Control plugin, by WooThemes.
		 *
		 * @link  https://wordpress.org/plugins/homepage-control/
		 *
		 * @since 2.0
		 *
		 * Hooked here:
		 * @see nice_homepage_do()
		 */
		do_action( 'homepage' );
	?>

<?php get_footer();