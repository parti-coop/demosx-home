<?php
/**
 * Nice Admin by NiceThemes.
 *
 * About content.
 *
 * @package Nice_Framework
 * @since   1.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$system_status = nice_admin_system_status();
$theme_name    = $system_status->get_nice_theme_name();

?>

<div class="about">

	<div class="feature-section">

		<div class="grid">
			<div class="columns-2-3">

			<h2><?php esc_html_e( 'Get ready to have an amazing website', 'nice-framework' );?></h2>

			<p><?php printf( esc_html__( 'Welcome and thank you for installing %s! It means the world to us at NiceThemes. We are fully commited to making your experience perfect.', 'nice-framework' ), $theme_name ); ?></p>

			<p><?php printf( esc_html__( 'If this is your first experience with %s, we recommend you visiting the following pages:', 'nice-framework' ), $theme_name ); ?></p>

			<div class="grid">
				<div class="columns-2">
					<ul>
						<li><i class="dashicons dashicons dashicons-admin-network"></i> <?php printf( esc_html__( '%1$sProduct Registration%2$s', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'options' ) ) ), '</a>' );?></li>
						<li><i class="dashicons dashicons-admin-settings"></i> <?php printf( esc_html__( '%1$sTheme Options Panel%2$s', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'register_product' ) ) ), '</a>' );?></li>

					</ul>
				</div>
				<div class="columns-2">
					<ul>
						<li><i class="dashicons dashicons dashicons-welcome-learn-more"></i> <?php printf( esc_html__( '%1$sGetting Started%2$s', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'register_product' ) ) ), '</a>' );?></li>
						<li><i class="dashicons dashicons-admin-generic"></i> <?php printf( esc_html__( '%1$sSupport and Updates%2$s', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'support' ) ) ), '</a>' );?></li>
					</ul>
				</div>
			</div>

			</div>

			<div class="columns-1-3">

				<h3><?php esc_html_e( 'Theme Options', 'nice-framework' );?></h3>
				<p><?php printf( esc_html__( 'The %1$sTheme Options Panel%2$s is extremely straightforward. Once you are there, you will find the different Option Sections on the left part of the screen and once you click on any of those you will be able to see the set of options for that section.', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'options' ) ) ), '</a>' );?></p>

				<h3><?php esc_html_e( 'Register your purchase', 'nice-framework' );?></h3>
				<p><?php printf( esc_html__( 'Please %1$sregister your purchase%2$s to get support and automatic theme updates.', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'register_product' ) ) ), '</a>' );?></p>

			</div>
		</div>

	</div>
</div>

<hr />

<div class="changelog">
	<h3><?php esc_html_e( 'Customizing your theme', 'nice-framework' );?></h3>

	<div class="grid">

		<div class="columns-3">

			<h3><?php esc_html_e( 'Using a Child Theme','nice-framework' );?></h3>
			<p><?php printf( esc_html__( 'We highly encourage all of our users to customize their websites by using a child theme. That way you will not lose your changes once a new update is applied. If you are not familiar with child themes, we have a %1$sfull article explaining how to use them%2$s', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/article/how-to-create-and-use-child-themes/' ), '</a>' ); ?></p>

		</div>

		<div class="columns-3">
			<h3><?php esc_html_e( 'Custom CSS', 'nice-framework' );?></h3>
			<p><?php esc_html_e( "You can choose to add custom styling within the theme files or the options panel. In the Theme Options panel, you'll find a special input where you can insert your custom CSS. Another option is to add your custom CSS to the custom.css you can find within the theme files.", 'nice-framework' );?></p>
		</div>

		<div class="columns-3">
			<h3><?php esc_html_e( 'Custom PHP', 'nice-framework' ); ?></h3>
			<p><?php esc_html_e( 'You can also add custom PHP scripts in your functions.php file within the theme files.', 'nice-framework' ); ?></p>
		</div>
	</div>
</div>

<hr />

<div class="changelog">
	<h2><?php esc_html_e( 'Need Help?', 'nice-framework' );?></h2>

	<div class="grid">

		<div class="columns-3">

			<h3><?php esc_html_e( 'Phenomenal Support','nice-framework' );?></h3>
			<p><?php printf( esc_html__( 'We do our best to provide the most reliable support you will ever find. If you encounter a problem or have a question, simply open a ticket using our %1$ssupport forums%2$s.', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/support' ), '</a>' );?></p>
		</div>

		<div class="columns-3">
			<h3><?php esc_html_e( 'Documentation', 'nice-framework' );?></h3>
			<p><?php printf( esc_html__( 'Our %1$sdocumentation and guides%2$s will help you get your problem solved without waiting for an answer.', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/support' ), '</a>' );?></p>
		</div>

		<div class="columns-3">
			<h3><?php esc_html_e( 'Articles', 'nice-framework' );?></h3>
			<p><?php printf( esc_html__( 'Our %1$sKnowledge Base%2$s is plenty of useful articles that will help you with any problem or question you may have.', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/support' ), '</a>' );?></p>
		</div>
	</div>
</div>

