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

<?php
	$is_home = is_front_page() || is_page_template( 'template-home.php' );
?>

<!-- BEGIN #wrapper -->
<div id="wrapper">

	<!-- BEGIN #header -->
	<header id="header" <?php nice_header_properties(); ?> style="background-color: #272f3d !important;<?php if ( $is_home ): ?>background-image: url('<?php echo get_stylesheet_directory_uri().'/images/cover_bg.png' ?>'); background-size: contain; 	background-repeat: repeat;<?php endif; ?>">

		<!-- BEGIN #top -->
		<div id="top" style="<?php if ( $is_home ): ?>border-bottom: 0;<?php endif; ?>">

			<div class="col-full">

			<div id="logo" class="fl demosx-logo">
				<h1>
						<span id="default-logo-wrapper" class="header-logo-wrapper">
							<a href="<?php echo home_url( '/' ) ?>" title="데모스X">
								<img src="<?php echo get_stylesheet_directory_uri().'/images/logo.png' ?>" id="default-logo" height="21" alt="데모스X" title="데모스X" class="img-logo img-custom-logo" style="height: 21px;">
								<img src="<?php echo get_stylesheet_directory_uri().'/images/logo.png' ?>" id="retina-logo" height="21" alt="데모스X" title="데모스X" class="img-logo img-logo-retina img-custom-logo" style="height: 21px;">
							</a>
						</span>
				</h1>
			</div>

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

	if ( ( nice_bool( $nice_livesearch_enable ) && ( $is_home ) ) || apply_filters( 'nice_livesearch_enable', false ) ) : ?>
	<!-- #live-search -->
	<section id="live-search" <?php nice_welcome_message_class(array('demosx-cover-section')); ?>>
		<div class="container col-full">

			<?php

			if ( is_front_page() ) : ?>

				<!-- BEGIN .welcome-message -->
				<section class="welcome-message welcome-message-demosx clearfix">

					<div class="col-full" style="margin-top: 32px;">

						<header>
							<h2>
								<span class="text-nowrap">더 민주적인 세상을 위한</span>
								<br>
								<span class="text-nowrap">오픈소스 시민참여 플랫폼<span class="hidden-xs">,</span></span>
								<br class="visible-xs-block">
								<span class="text-nowrap">데모스X</span>
							</h2>
						</header>

						<p class="cover-content">
							데모스X는
							<span class="text-nowrap">시민과 기관이 함께 정책에 대해</span>
							<span class="text-nowrap">의견을 나누고 함께 실행하는</span>
							<span class="text-nowrap">시민참여 플랫폼입니다.</span>
							<a href="#" class="text-nowrap" style="border-bottom: 1px solid #58c2bd !important; text-decoration: none;">지금 바로 도입하세요!</a>
						</p>

						<p>
							<a href="#" class="btn btn-default btn-lg btn-cover-call-to-action">오픈소스 살펴보기</a>
						</p>
					</div>

					<!-- BEGIN features -->
					<div class="col-full text-align-center image-zoomIn demosx-features">
						<div class="nice-infoboxes">
							<div class="item columns-3 first">
								<div class="thumb">
									<div class="thumb-image-helper"></div>
									<img class="nice-image demox-image-suggestion" alt="" title="" style="" src="<?php echo get_stylesheet_directory_uri().'/images/icon_smartphone.png' ?>">
								</div>
								<div class="infobox-title">
									제안 올리기
								</div>
								<div class="infobox-content">
									일상을 바꾸는 제안,
									<br>제목과 간단한 설명만 적으면
									<br>끝입니다.
								</div>
							</div>
							<div class="item columns-3">
								<div class="thumb">
									<div class="thumb-image-helper"></div>
									<img class="nice-image demox-image-vote" alt="" title="" style="" src="<?php echo get_stylesheet_directory_uri().'/images/icon_updown.png' ?>">
								</div>
								<div class="infobox-title">
									찬반 투표하기
								</div>
								<div class="infobox-content">
									제안에 대한 의사를
									<br>찬성과 반대, 중립으로
									<br>표현합니다.
								</div>
							</div>
							<div class="item columns-3 last">
								<div class="thumb">
									<div class="thumb-image-helper"></div>
									<img class="nice-image demox-image-talk" alt="" title="" style="" src="<?php echo get_stylesheet_directory_uri().'/images/icon_speech.png' ?>">
								</div>
								<div class="infobox-title">
									공감하고
									<span class="text-nowrap">이야기 나누기</span>
								</div>
								<div class="infobox-content">
									마음을 움직이는 제안에
									<br>공감을 누르고,
									<br>댓글로 대화에 참여합니다.
								</div>
							</div>
						</div>
					</div>
					<!-- END features -->

				<!-- END .welcome-message -->
				</section>

			<?php endif; ?>
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
