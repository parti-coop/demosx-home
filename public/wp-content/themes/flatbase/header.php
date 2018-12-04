<?php
/**
 * Flatbase by NiceThemes.
 *
 * The Header for our theme.
 *
 * Displays all of the `<head>` section and everything up till `<div id="container">`
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

global $nice_options;
?>
<!DOCTYPE html>
<!--[if IE 7]> <html class="ie ie7" <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 8]> <html class="ie ie8" <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if IE 9]> <html class="ie ie9" <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<!-- Pingback -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?> <?php nice_body_data(); ?>>
<?php
	/**
	 * @hook nice_before_wrapper
	 *
	 * Hook here to display elements before the `#wrapper` element.
	 *
	 * @since 2.0
	 *
	 * Hooked here:
	 * @see nice_full_page_overlay_loader() - 10 (Displays the full page overlay markup when needed)
	 */
	do_action( 'nice_before_wrapper' );
?>

<!-- BEGIN #wrapper -->
<div id="wrapper">

	<!-- BEGIN #header -->
	<header id="header" <?php nice_header_properties(); ?>>

		<!-- BEGIN #top -->
		<div id="top">

			<div class="col-full">

			<?php
				/**
				 * @hook nice_logo
				 *
				 * The site's logo (image or text) is printed here. Hook to add,
				 * remove or modify HTML elements from the site's logo.
				 *
				 * @since 2.0
				 *
				 * Hooked here:
				 * @see nice_logo_display() - 10
				 */
				do_action( 'nice_logo' );
			?>

			<?php
				/**
				 * @hook nice_navigation_menu
				 *
				 * The main navigation menu is printed here. Hook to add,
				 * remove or modify HTML elements from the main navigation.
				 *
				 * @since 2.0
				 *
				 * Hooked here:
				 *
				 * @see nice_navigation_menu() - 10 (prints the main navigation menu)
				 */
				do_action( 'nice_navigation_menu' );
			?>

			</div>

		<!-- END #top -->
		</div>

	<?php

	$nice_livesearch_enable = nice_get_option( '_livesearch_enable' );
	$is_home = is_front_page() || is_page_template( 'template-home.php' );

	if ( ( nice_bool( $nice_livesearch_enable ) && ( $is_home ) ) || apply_filters( 'nice_livesearch_enable', false ) ) : ?>
	<!-- #live-search -->
	<section id="live-search" <?php nice_welcome_message_class(); ?>>
		<div class="container col-full">

			<?php

			$nice_welcome_message = get_option( 'nice_welcome_message' );
			$nice_welcome_message_extended = get_option( 'nice_welcome_message_extended' );

			if ( ( ( $nice_welcome_message != '' ) || ( $nice_welcome_message_extended != '' ) ) && is_front_page() ) : ?>

				<!-- BEGIN .welcome-message -->
				<section class="welcome-message clearfix">

					<div class="col-full">

						<?php if ( $nice_welcome_message != '' ) : ?>
							<header>
								<h2><?php echo stripslashes( htmlspecialchars_decode( nl2br( $nice_welcome_message ) ) ); ?></h2>
							</header>
						<?php endif ;?>

						<?php if ( $nice_welcome_message_extended != '' ) : ?>
							<p><?php echo stripslashes( htmlspecialchars_decode( nl2br( $nice_welcome_message_extended ) ) ); ?></p>
						<?php endif ;?>

					</div>

				<!-- END .welcome-message -->
				</section>

			<?php endif; ?>

			<div id="search-wrap">
				<form role="search" method="get" id="searchform" class="clearfix" action="<?php echo home_url( '/' ); ?>" autocomplete="off">
					<div class="input">
					<label for="s"><?php echo apply_filters( 'nice_livesearch_label', __( 'Have a question? Ask or enter a search term.', 'nicethemes' ) ); ?></label>
					<input type="text" name="s" id="s" />
					<input type="submit" id="searchsubmit" value="&#xf002;" />
					</div>
				</form>
			</div>
		</div>
	</section>
	<!-- /#live-search -->

	<?php endif; ?>

	<!-- END #header -->
	</header>

<?php if ( ! is_page_template( 'template-home.php' ) ) : ?>
<!-- BEGIN #container -->
<div id="container" class="clearfix"> <?php //nice_container_class(); ?>
<?php endif; ?>