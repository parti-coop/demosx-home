<?php
/**
 * Flatbase by NiceThemes.
 *
 * This file contains functions to manage the footer layout and the different options.
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

if ( ! function_exists( 'nice_footer' ) ) :
add_action( 'nice_footer', 'nice_footer', 10 );
/**
 * Print the footer
 *
 * @since 1.0.0
 */
function nice_footer() {

	/**
	 * @hook nice_before_footer
	 *
	 * Hook here to add HTML elements right before the header is printed.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_before_footer' );
	?>
		<!-- BEGIN #footer -->
		<footer id="footer" <?php nice_footer_class(); ?>>
			<?php
				/**
				 * @hook nice_footer_widgets
				 *
				 * Print widgets.
				 *
				 * @since 2.0
				 *
				 * Hooked here:
				 * @see nice_footer_widgets()
				 */
				do_action( 'nice_footer_widgets' );
			?>

			<?php
				/**
				 * @hook nice_footer_extended
				 *
				 * Print extended footer.
				 *
				 * @since 2.0
				 *
				 * Hooked here:
				 * @see nice_footer_extended()
				 */
				do_action( 'nice_footer_extended' );
			?>
		</footer><!-- END #footer -->
	<?php
	/**
	 * @hook nice_footer_after
	 *
	 * Hook here to add HTML elements right after the header is printed.
	 *
	 * @since 2.0
	 */
	do_action( 'nice_after_footer' );
}
endif;

if ( ! function_exists( 'nice_footer_widgets' ) ) :
add_action( 'nice_footer_widgets', 'nice_footer_widgets' );
/**
 * Print the footer widgets.
 *
 * @since 2.0
 *
 * @param  array $args
 * @return string
 */
function nice_footer_widgets( $args = array() ) {
	$class = 'col-full';

	if ( nice_footer_full_width() ) {
		$class = 'full-width';
	}

	$defaults = array(
		'echo'    => true,
		'columns' => '4',
		'before'  => '<!-- BEGIN #footer-widget --><div id="footer-widgets" class="' . $class . '">',
		'after'   => '</div><!-- /#footer-widgets -->',
	);

	$args = wp_parse_args( $args, $defaults );
	$args = apply_filters( 'nice_footer_widgets_args', $args );

	do_action( 'nice_footer_widgets_before', $args );

	// Initialize output.
	ob_start();

	// Obtain number of columns.
	$footer_columns = nice_get_option( 'nice_footer_columns' );
	$footer_columns = intval( $footer_columns ? $footer_columns : $args['columns'] );

	// Obtain HTML class.
	$class = ' columns-' . esc_attr( $footer_columns );

	if (   is_active_sidebar( 'footer-1' )
	    || is_active_sidebar( 'footer-2' )
	    || is_active_sidebar( 'footer-3' )
	    || is_active_sidebar( 'footer-4' )
	) : ?>
		<?php echo $args['before']; ?>

		<div id="footer-grid" class="footer-grid grid">
			<div class="widget-section first <?php echo esc_attr( $class ); ?>">
				<?php dynamic_sidebar( 'footer-1' ); ?>
			</div>
			<div class="widget-section even <?php echo esc_attr( $class ); ?>">
				<?php dynamic_sidebar( 'footer-2' ); ?>
			</div>
			<?php if ( 3 === $footer_columns or 4 === $footer_columns ) : ?>
				<?php if ( 3 === $footer_columns ) :
					$class .= ' last';
				endif; ?>
				<div class="widget-section odd <?php echo esc_attr( $class ); ?>">
					<?php dynamic_sidebar( 'footer-3' ); ?>
				</div>
			<?php endif; ?>
			<?php if ( 4 === $footer_columns ) : ?>
				<div class="widget-section odd <?php echo esc_attr( $class ); ?> last">
					<?php dynamic_sidebar( 'footer-4' ); ?>
				</div>
			<?php endif; ?>
		</div>

		<?php echo $args['after']; ?>
	<?php endif;

	$output = ob_get_contents();
	ob_end_clean();

	/**
	 * @hook nice_footer_widgets_html
	 *
	 * Hook here to change the HTML output of the footer widgets.
	 *
	 * @since 1.0
	 */
	$output = apply_filters( 'nice_footer_widgets_html', $output, $args );

	if ( $args['echo'] ) {
		echo $output;
	}

	return $output;

	do_action( 'nice_footer_widgets_after', $args );

}
endif;

if ( ! function_exists( 'nice_footer_extended' ) ) :
add_action( 'nice_footer_extended', 'nice_footer_extended' );
/**
 * Print the extended footer
 *
 * @since 2.0
 */
function nice_footer_extended() {
	$class = 'col-full';

	if ( nice_footer_full_width() ) {
		$class = 'full-width';
	}

	?>

	<div id="extended-footer">
		<div id="extended-footer-content" class="<?php echo esc_attr( $class ); ?>">
			<div class="grid">
				<div class="columns-2">
					<?php
						/**
						 * @hook nice_copyright
						 *
						 * Print copyright-related HTML.
						 *
						 * @since 2.0
						 *
						 * Hooked here:
						 * @see nice_copyright_display()
						 */
						do_action( 'nice_copyright' );
					?>
				</div>
				<div class="columns-2">
					<?php
						/**
						 * @hook nice_footer_menu
						 *
						 * Print HTML related to a navigation menu.
						 *
						 * @since 2.0
						 *
						 * Hooked here:
						 * @see nice_footer_menu()
						 */
						do_action( 'nice_footer_menu' );
					?>
				</div>
			</div>
		</div>
	</div>
<?php
}
endif;

if ( ! function_exists( 'nice_copyright_display' ) ) :
add_action( 'nice_copyright', 'nice_copyright_display' );
/**
 * Display copyright text using framework functionality.
 *
 * @since 2.0
 */
function nice_copyright_display() {
	nice_copyright( array(
		'before' => '<div id="copyright">',
		'after'  => '</div>',
	) );
}
endif;

if ( ! function_exists( 'nice_copyright_filter' ) ) :
add_filter( 'nice_copyright_default_args', 'nice_copyright_filter' );
/**
 * Set the copyright arguments for this the function nice_copyright()
 *
 * @since 1.0.0
 *
 * @see   nice_copyright()
 *
 * @param  array  $args
 *
 * @return array
 */
function nice_copyright_filter( $args ) {

	global $nice_options;

	$text = '';

	$custom_copyright_enable = get_option( 'nice_custom_copyright_enable' );

	if ( ! empty( $custom_copyright_enable ) && nice_bool( $custom_copyright_enable ) ) {
		$custom_copyright_text = nice_get_option( 'nice_custom_copyright_text' );

		if ( ! empty( $custom_copyright_text ) ) {
			$text .= $custom_copyright_text;
		}

	} else {

		$text = '<a href="https://nicethemes.com/product/flatbase/" target="_blank" title="Flatbase WordPress Knowlegebase Theme">Flatbase</a> ' . sprintf( esc_attr__( 'by %s', 'nicethemes' ), '<a href="https://nicethemes.com" title="Wordpress Nice Themes">NiceThemes</a>' ) .' &copy; ' . date( 'Y' ). '. &mdash; ' . sprintf( esc_attr__( 'Powered by %s', 'nicethemes' ), '<a href="http://wordpress.org">WordPress</a>' ) . '.';

	}

	$args['text'] = $text;

	return $args;
}
endif;

if ( ! function_exists( 'nice_footer_extended_menu' ) ) :
add_action( 'nice_footer_menu', 'nice_footer_extended_menu' );
/**
 * Display copyright navigation menu.
 *
 * @since 2.0
 */
function nice_footer_extended_menu() {


	$defaults = array(
		'menu'            => '',
		'container'       => 'nav',
		'container_class' => '',
		'container_id'    => 'footer-navigation',
		'menu_class'      => is_rtl() ? 'nav fl clearfix' : 'nav fr clearfix',
		'menu_id'         => 'footer-nav',
		'echo'            => true,
		'fallback_cb'     => '',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 0,
		'walker'          => '',
		'theme_location'  => 'footer-menu'
	);

	wp_nav_menu( $defaults );

}
endif;

if ( ! function_exists( 'nice_footer_display' ) ) :
add_filter( 'wp', 'nice_footer_display' );
/**
 * Check if the footer sections should be displayed
 *
 * @see    nice_footer_display()
 *
 * @since  2.0
 */
function nice_footer_display() {
	if ( ! is_page() && ! is_single() ) {
		return;
	}

	$footer         = get_post_meta( get_the_ID(), '_post_footer', true );
	$footer_widgets = get_post_meta( get_the_ID(), '_post_footer_widgets', true );

	if ( ! empty( $footer ) && ! nice_bool( $footer ) ) {
		remove_action( 'nice_footer', 'nice_footer' );
	}

	if ( ( '' !== $footer_widgets ) && ( ! nice_bool( $footer_widgets ) ) ) {
		remove_action( 'nice_footer_widgets', 'nice_footer_widgets' );
	}

	$footer_extended = get_post_meta( get_the_ID(), '_post_footer_extended', true );

	if ( ( '' !== $footer_extended ) && ( ! nice_bool( $footer_extended ) ) ) {
		remove_action( 'nice_footer_extended', 'nice_footer_extended' );
	}
}
endif;


if ( ! function_exists( 'nice_footer_full_width' ) ) :
/**
 * Check if the footer sections should be displayed in full width
 *
 * @see    nice_footer_display()
 *
 * @since  2.0
 */
function nice_footer_full_width() {
	$is_full_width = nice_bool( nice_get_option( 'nice_footer_full_width' ) );

	if ( is_page() || is_single() ) {
		$post_footer_width = get_post_meta( get_the_ID(), '_post_footer_width', true );

		if ( 'full' === $post_footer_width ) {
			$is_full_width = true;
		} elseif ( 'limit' === $post_footer_width ) {
			$is_full_width = false;
		}
	}

	if ( ! nice_boxed_layout() && $is_full_width ) {
		return true;
	}

	return false;
}
endif;

if ( ! function_exists( 'nice_footer_class' ) ) :
/**
 * Add classes to #footer element.
 *
 * @see    nice_footer_properties()
 *
 * @since  1.0.0
 *
 * @param  mixed|array|string $class List of classes for #footer element.
 * @param  bool               $echo  Whether to print the output or just return it.
 *
 * @return string
 */
function nice_footer_class( $class = null, $echo = true ) {
	// Return early if given class is not string or array.
	if ( ! is_null( $class ) && ! ( is_array( $class ) || is_string( $class ) ) ) {
		return null;
	}

	// Default classes.
	$classes = array(
		'site-footer',
	);

	// Skin class.
	if ( $skin = nice_footer_skin() ) {
		$classes[] = $skin;
	}

	if ( ! is_array( $class ) && is_string( $class ) ) {
		$class = explode( ' ', $class );
	}

	if ( ! empty( $class ) ) {
		foreach ( $class as $c ) {
			$classes[] = $c;
		}
	}

	/**
	 * @hook nice_footer_class
	 *
	 * Hook here to modify the array of footer classes.
	 *
	 * @since 1.0.0
	 */
	$footer_class = apply_filters( 'nice_footer_class', $classes );

	$output = nice_css_classes( $footer_class, false );

	if ( $echo ) {
		echo $output;
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_back_to_top' ) ) :
add_action( 'wp_footer', 'nice_back_to_top' );
/**
 * Add the code for the back to top button (if enabled through options).
 *
 * @since 1.0.0
 */
function nice_back_to_top() {
	global $nice_options;
	$nice_back_to_top = ! empty( $nice_options['nice_back_to_top'] ) && 'true' == $nice_options['nice_back_to_top'];

	ob_start();

	// Start template. ?>
	<?php if ( $nice_back_to_top ) : ?>
		<a href="#wrapper" data-target="#wrapper" class="backtotop">
			<i class="fa fa-angle-up"></i>
		</a>
	<?php endif; ?>
	<?php // End template.

	$output = ob_get_contents();
	ob_end_clean();

	$output = apply_filters( 'nice_back_to_top', $output );

	echo $output;
}
endif;

if ( ! function_exists( 'nice_footer_call_to_action' ) ) :
add_action( 'nice_before_footer', 'nice_footer_call_to_action' );
/**
 * Display the call to action
 *
 *
 * @since  2.0
 */
function nice_footer_call_to_action() {
	$nice_cta_text     = get_option( 'nice_cta_text' );
	$nice_cta_url      = get_option( 'nice_cta_url' );
	$nice_cta_url_text = get_option( 'nice_cta_url_text' );

	if ( $nice_cta_text !== '' || $nice_cta_url_text !== '' ) :

		$classes   = array();
		$classes[] = 'home-cta-block clearfix';

		if ( $nice_cta_background_color = nice_get_option( '_cta_background_color', true ) ) {
			$classes[] = nice_theme_color_background_class( $nice_cta_background_color );
		}

		$classes[] = nice_get_option( '_cta_skin', true );

		if ( $nice_cta_url_text !== '' ) {
			$classes[] = esc_attr( 'has-cta-button' );
		}


		?>
		<!-- BEGIN #call-to-action .home-cta-block -->
		<section id="call-to-action" <?php nice_css_classes( $classes ); ?>>

			<div class="col-full">
				<div class="cta-wrapper">
					<?php if ( $nice_cta_text != '' ) : ?>
						<div class="cta-text"><?php echo esc_html( $nice_cta_text ); ?></div>
					<?php endif; ?>

					<?php if ( $nice_cta_url_text != '' ) : ?>
						<span class="cta-button-wrapper">
							<a class="cta-button btn-xl" href="<?php echo esc_url( $nice_cta_url ); ?>" title="<?php echo esc_attr( $nice_cta_url_text ); ?>"><?php echo esc_html( $nice_cta_url_text ); ?></a>
						</span>
					<?php endif; ?>
				</div>
			</div>

		</section>

	<?php endif;
}
endif;
