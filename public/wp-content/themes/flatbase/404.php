<?php
/**
 * Flatbase by NiceThemes.
 *
 * The template for displaying 404 pages (Not Found).
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2016 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php get_header(); ?>
<!-- BEGIN #content -->
<section id="content" <?php nice_content_class( 'main-content full-width' ); ?> role="main">

	<header>
		<h2><?php _e( 'Not Found', 'nicethemes' ); ?></h2>
	</header>
	<p><?php _e( 'Sorry, but you are looking for something that isn\'t here.', 'nicethemes' ); ?></p>

	<?php get_search_form(); ?>

<!-- END #content -->
</section>

<?php get_footer();