<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Getting Started content.
 *
 * @package Nice_Framework
 * @since   1.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="changelog">
	<h3><?php esc_html_e( 'Theme Options Panel', 'nice-framework' );?></h3>

	<div class="grid">

		<div class="columns-3">

			<h4><?php esc_html_e( 'Theme Options Panel','nice-framework' );?></h4>

			<p><?php printf( esc_html__( 'It is really simple to find. Just look where it says  %1$sTheme Options%2$s and with some simple clicks you will be able to customize your theme. You will find options to modify  the layout, colors, set a logo, change the typography and more, much more.', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'options' ) ) ), '</a>' );?></p>
		</div>

		<div class="columns-3">
			<h4><?php esc_html_e( 'How to use the options', 'nice-framework' );?></h4>

			<p><?php printf( esc_html__( 'The %1$sTheme Options Panel%2$s is extremely straightforward. Once you are there, you will find the different Option Sections on the left part of the screen and once you click on any of those you will be able to see the set of options for that section.', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'options' ) ) ), '</a>' );?></p>
		</div>

		<div class="columns-3">
			<h4><?php esc_html_e( 'Do not forget to save the changes!', 'nice-framework' );?></h4>
			<p><?php esc_html_e( 'Once you are done editing the theme options, do not forget to save them. If you do not save the changes, then you won\'t be able to see them.', 'nice-framework' );?></p>
		</div>

	</div>
</div>

<br />

<?php if ( nice_theme_requires_plugins() ) : ?>

	<div class="info-install-plugins">
		<h3><?php esc_html_e( 'Installing the required plugins', 'nice-framework' ); ?></h3>

		<div class="grid">
			<div class="columns-3">

				<h4><?php esc_html_e( 'Theme compatible Plugins', 'nice-framework' ); ?></h4>
				<p><?php esc_html_e( 'In most cases, part of the site functionality is handled by different plugins.', 'nice-framework' ); ?></p>
				<p><?php printf( esc_html__( 'In most cases, part of the site functionality is handled by different plugins. If you visit the %1$sInstall Plugins%2$s page you will find a complete list of the plugins that are fully compatible with this theme.', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'plugins' ) ) ), '</a>' );?></p>
			</div>

			<div class="columns-3">
				<h4><?php esc_html_e( 'Install', 'nice-framework' );?></h4>
				<p><?php printf( esc_html__( 'In that same %1$slist of plugins%2$s you can see which ones are strictly required to make your site work. You will be able to see which ones are required, and others that we suggest you to use, and most important, you will be able to install them in the blink of an eye.', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'plugins' ) ) ), '</a>' );?></p>
			</div>

			<div class="columns-3">
				<h4><?php esc_html_e( 'Activate', 'nice-framework' );?></h4>
				<p><?php esc_html_e( 'There are cases in which you already have some of the required plugins installed already, if that is your case, you will need to activate them in order to make them work. You can also activate them directly from that screen.', 'nice-framework' );?></p>
			</div>

		</div>
	</div>

<br />

<?php endif; ?>

<div class="changelog">

	<h3><?php esc_html_e( 'Importing Demo Contents', 'nice-framework' );?></h3>

	<?php if ( nice_theme_has_demo_packs() ) : ?>

		<div class="grid">

			<div class="columns-3">
				<h4><?php esc_html_e( 'Choose the Demo Pack you want to import','nice-framework' );?></h4>
				<p><?php printf( esc_html__( 'In the %1$sDemo Packs Importer Page%2$s you will find the different demos we have created for this theme. You can browse through the different options we have curated for you, preview them and see the special requirements for each one of them.', 'nice-framework' ), sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'demos' ) ) ), '</a>' );?></p>
			</div>

			<div class="columns-3">
				<h4><?php esc_html_e( 'Make sure you meet the requirements', 'nice-framework' );?></h4>
				<p><?php esc_html_e( 'Each demo has special functions, and it is quite likely that you will need to install or activate a plugin to fully import the contents correctly.', 'nice-framework' );?></p>
			</div>

			<div class="columns-3">
				<h4><?php esc_html_e( 'Click and install', 'nice-framework' ); ?></h4>
				<p><?php esc_html_e( 'Once you decided which one you would like to import, all you need to do is click on the import button. As simple as that, all the contents will be imported and your site will be configured automatically!', 'nice-framework' );?></p>
			</div>
		</div>

	<?php else : ?>

		<div class="grid">

			<div class="columns-3">
				<h4><?php esc_html_e( 'Getting the demo contents XML','nice-framework' );?></h4>
				<p><?php printf( esc_html__( 'To make your site look like the demo site you will need to import the demo contents and configure the options. You can find the %1$sdemo content XML file here%2$s.', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/theme-xml-demo-content/' ), '</a>' );?></p>
			</div>

			<div class="columns-3">
				<h4><?php esc_html_e( 'How to import the file', 'nice-framework' );?></h4>
				<p><?php esc_html_e( 'On Your WordPress Dashboard, go to Tools > Import > WordPress. Install the WordPress Importer. Browse for the XML file and import it.', 'nice-framework' );?></p>
			</div>

			<div class="columns-3">
				<h4><?php esc_html_e( 'After importing contents', 'nice-framework' ); ?></h4>
				<p><?php printf( esc_html__( 'You will need to: Set up the front page, set up the widgets for the different widgetized spaces, Configure the navigation menu, and save the theme options. You can read more about this %1$son this link%2$s', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/theme-xml-demo-content/' ), '</a>' ); ?></p>
			</div>
		</div>

	<?php endif; ?>

</div>

<br />

<div class="changelog">
	<h3><?php esc_html_e( 'Customizing your theme', 'nice-framework' );?></h3>

	<div class="grid">

		<div class="columns-3">

			<h4><?php esc_html_e( 'Use a Child Theme','nice-framework' );?></h4>
			<p><?php printf( esc_html__( 'We highly encourage all of our users to customize their websites by using a child theme. That way you will not lose your changes once a new update is applied. If you are not familiar with child themes, we have a %1$sfull article explaining how to use them%2$s', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/article/how-to-create-and-use-child-themes/' ), '</a>' ); ?></p>

		</div>

		<div class="columns-3">
			<h4><?php esc_html_e( 'Custom CSS', 'nice-framework' );?></h4>
			<p><?php esc_html_e( "You can choose to add custom styling within the theme files or the options panel. In the Theme Options panel, you'll find a special input where you can insert your custom CSS. Another option is to add your custom CSS to the custom.css you can find within the theme files.", 'nice-framework' );?></p>
		</div>

		<div class="columns-3">
			<h4><?php esc_html_e( 'Custom PHP', 'nice-framework' ); ?></h4>
			<p><?php esc_html_e( 'You can also add custom PHP scripts in your functions.php file within the theme files.', 'nice-framework' ); ?></p>
		</div>
	</div>
</div>

<br />

<div class="changelog">
	<h3><?php esc_html_e( 'Need Help?', 'nice-framework' );?></h3>

	<div class="grid">

		<div class="columns-3">

			<h4><?php esc_html_e( 'Phenomenal Support','nice-framework' );?></h4>
			<p><?php printf( esc_html__( 'We do our best to provide the most reliable support you will ever find. If you encounter a problem or have a question, simply open a ticket using our %1$ssupport forums%2$s.', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/support' ), '</a>' );?></p>
		</div>

		<div class="columns-3">
			<h4><?php esc_html_e( 'Documentation', 'nice-framework' );?></h4>
			<p><?php printf( esc_html__( 'Our %1$sdocumentation and guides%2$s will help you get your problem solved without waiting for an answer.', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/support' ), '</a>' );?></p>
		</div>

		<div class="columns-3">
			<h4><?php esc_html_e( 'Articles', 'nice-framework' );?></h4>
			<p><?php printf( esc_html__( 'Our %1$sKnowledge Base%2$s is plenty of useful articles that will help you with any problem or question you may have.', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/support' ), '</a>' );?></p>
		</div>
	</div>
</div>

<br />

<div class="changelog">
	<h3><?php esc_html_e( 'Stay Up to Date', 'nice-framework' );?></h3>

	<div class="grid">

		<div class="columns-3">

			<h4><?php esc_html_e( 'New releases and updates','nice-framework' );?></h4>
			<p><?php printf( esc_html__( 'New releases are out periodically. Subscribe to our newsletter to stay up to date with our latest releases and updates. %1$sSign up now%2$s to ensure you do not miss a release!', 'nice-framework' ), sprintf( '<a href="%s">', '#' ), '</a>' );?></p>
		</div>

		<div class="columns-3">
			<h4><?php esc_html_e( 'Get Alerted About New Products', 'nice-framework' );?></h4>
			<p><?php printf( esc_html__( '%1$sYou can sign up%2$s to hear about the latest releases and updates about our products.', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/register' ), '</a>' );?></p>
		</div>

		<div class="columns-3">
			<h4><?php esc_html_e( 'Follow us on', 'nice-framework' );?></h4>
			<p><?php printf( esc_html__( '%1$sYou can sign up%2$s to hear about the latest tutorial releases that explain how to take your site further.', 'nice-framework' ), sprintf( '<a href="%s">', 'https://nicethemes.com/register' ), '</a>' );?></p>
		</div>

	</div>
</div>
