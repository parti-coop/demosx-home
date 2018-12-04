<?php
/**
 * Flatbase by NiceThemes.
 *
 * The default template for this theme's footer.
 *
 * Contains footer content and the closing of the #container and #wrapper div elements.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/theme/flatbase
 * @copyright 2017 NiceThemes
 * @since     1.0.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
	<?php if ( ! is_page_template( 'template-home.php' ) ) : ?>
		<!-- END #container -->
		</div>
	<?php endif; ?>

	<?php
		/**
		 * @hook nice_footer
		 *
		 * The HTML fot the site's footer is processed here. Hook in to add
		 * or remove elements.
		 *
		 * @since 2.0
		 *
		 * Hooked here:
		 * @see nice_footer() - 10 (Prints out the general footer)
		 */
		do_action( 'nice_footer' );
	?>

	</div><!-- END #wrapper -->

	<?php
		/**
		 * @hook nice_after_wrapper
		 *
		 * Hook here to display elements after the `#wrapper` element.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nice_after_wrapper' );
	?>

	<?php wp_footer(); ?>
</body>
</html>