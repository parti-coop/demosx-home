<?php
/**
 * Nice Admin by NiceThemes.
 *
 * The header content.
 *
 * @package Nice_Framework
 * @since   1.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$top_menu_pages  = nice_admin_get_menu_pages( 'top' );
$main_menu_pages = nice_admin_get_menu_pages( 'main' );

?>
<div class="nice-admin-wrapper">

	<div class="nice-admin-frame">

		<?php if ( ! empty( $top_menu_pages ) ) : ?>
			<div class="header">

				<nav role="navigation" class="header-nav drawer-nav nav-horizontal">
					<ul class="main-nav">

						<?php foreach ( $top_menu_pages as $key ) : ?>
							<li class="nice-page">
								<a class="<?php echo ( nice_admin_get_current_page() === $key )  ? 'current' : ''; ?>" href="<?php echo esc_url( nice_admin_page_get_link( $key ) ); ?>"><i class="dashicons <?php echo esc_attr( nice_admin_page_get_icon( $key ) ); ?>"></i> <?php echo esc_html( nice_admin_page_get_menu_title( $key ) ); ?></a>
							</li>
						<?php endforeach; ?>

					</ul>
				</nav>

			</div><!-- .header -->
		<?php endif; ?>

		<div class="container clearfix">

			<div class="heading">

				<div class="masthead">
					<div class="section-description">
						<h1><?php echo esc_html( nice_admin_page_get_page_title() ); ?></h1>
						<p><?php echo esc_html( nice_admin_page_get_description() ); ?></p>
					</div>
				</div>

				<?php if ( ! empty( $main_menu_pages ) ) : ?>
					<div class="heading-nav clearfix">
						<nav role="navigation" class="nav-horizontal nice-dashboard-tabs">
							<ul id="nice-dashboard-nav" class="main-nav nav fl">

								<?php foreach ( $main_menu_pages as $key ) : ?>
									<li class="nice-page">
										<a class="<?php echo ( nice_admin_get_current_page() === $key )  ? 'current' : ''; ?>" href="<?php echo esc_url( nice_admin_page_get_link( $key ) ); ?>">
											<i class="<?php echo esc_attr( nice_admin_page_get_icon( $key ) ); ?>"></i> <?php echo esc_html( nice_admin_page_get_menu_title( $key ) ); ?>
										</a>
									</li>
								<?php endforeach; ?>

							</ul>
						</nav>
					</div>
				<?php endif; ?>

			</div>

			<?php if ( nice_admin_show_wp_notices() && nice_admin_get_wp_notices() ) : ?>
				<div id="nice-theme-wp-notices" class="nice-wp-notices clearfix <?php echo nice_admin_show_wp_notices_third_parties() ? '' : 'hide-third-parties'; ?>">
					<?php nice_admin_print_wp_notices() ?>
				</div>
			<?php endif; ?>

			<div id="page-<?php echo esc_attr( nice_admin_get_current_page() ); ?>" class="page-content clearfix">
				<div class="content">

