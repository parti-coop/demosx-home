<?php
/**
 * Flatbase by NiceThemes.
 *
 * The home pre footer widgets.
 *
 * @package   Flatbase
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      https://nicethemes.com/product/flatbase
 * @copyright 2017 NiceThemes
 * @since     2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<?php $nice_footer_columns = ( ! empty( $nice_options['nice_footer_columns'] ) ) ? $nice_options['nice_footer_columns'] : '3'; ?>

<?php $class = ' columns-' . esc_attr( intval( $nice_footer_columns ) ); ?>

<?php if (  is_active_sidebar( 'pre-footer-1' ) ||
			is_active_sidebar( 'pre-footer-2' ) ||
			is_active_sidebar( 'pre-footer-3' ) ) : ?>

	<section id="pre-footer-widgets" class="pre-footer-widgets home-block clearfix">

		<div class="col-full">

			<div class="grid">

				<div class="widget-section odd first  <?php echo $class; ?>">
					<?php dynamic_sidebar( 'pre-footer-1' ); ?>
				</div>

				<div class="widget-section even  <?php echo $class; ?>">
					<?php dynamic_sidebar( 'pre-footer-2' ); ?>
				</div>

				<?php if ( $nice_footer_columns == '3' || $nice_footer_columns == '4' ) : ?>
				<?php if ( $nice_footer_columns == '3' ) $class .= ' last'; ?>
				<div class="widget-section odd  <?php echo $class; ?>">
					<?php dynamic_sidebar( 'pre-footer-3' ); ?>
				</div>
				<?php endif; ?>
				<?php if ( $nice_footer_columns == '4' ) : ?>
				<div class="widget-section odd <?php echo $class; ?> last">
					<?php dynamic_sidebar( 'pre-footer-4' ); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>

	<!-- END #home-widgets -->
	</section>

<?php endif; ?>
