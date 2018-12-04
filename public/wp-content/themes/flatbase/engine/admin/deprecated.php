<?php
/**
 * NiceFramework
 *
 * This file contains  deprecated functions from past Framework versions. You shouldn't use these
 * functions and look for the alternatives instead. The functions will be removed
 * in a later version.
 *
 * @package NiceFramework
 * @since   2.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nicethemes' ) ) :
/**
 * nicethemes()
 *
 * Create admin panel with options from the array.
 *
 * @since 1.0.0
 *
 */
function nicethemes() {
	$reset_url = wp_nonce_url( admin_url( 'admin.php?page=nicethemes'), 'nice_reset_options', 'nice_reset' );
	$options = get_option( 'nice_template' );

	$interface = nice_formbuilder( $options );
	$selected = isset( $_GET['page'] ) ? $_GET['page'] : 'nicethemes';
	?>

	<div class="nice-admin-wrapper">

			<div class="nice-admin-frame">

				<div class="header">
					<nav role="navigation" class="header-nav drawer-nav nav-horizontal">
							<ul class="main-nav">

								<li class="nice-page">
									<a class="<?php echo $selected == 'nicethemes' ? 'current' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'nicethemes' ), 'index.php' ) ) ); ?>"><i class="dashicons dashicons-admin-settings"></i> <?php esc_html_e( 'Theme Options', 'nice-framework' ); ?></a>
								</li>
								<li class="nice-page">
									<a class="<?php echo $selected == 'nice-theme-about' ? 'current' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'nice-theme-about' ), 'index.php' ) ) ); ?>"><i class="dashicons dashicons-info"></i> <?php esc_html_e( 'About', 'nice-framework' ); ?></a>
								</li>

							</ul>
						</nav>
				</div><!-- .header -->

				<div class="container clearfix">
								<div class="heading">

			<div class="masthead">
				<div class="section-description">
					<h1>Theme Options</h1>
					<p>Welcome to the Settings Panel. Here you can set up and configure all of the different options for this magnificent plugin.</p>
				</div>
			</div>

		</div>

		<div class="page-content clearfix">

			<div class="content">

			<!-- END SARASA -->

				<div class="nice-container wrap <?php if ( nice_is_mp6() ) : echo 'is-mp6'; endif; ?>" id="nice-container">

					<div id="nice-popup-save" class="nice-save-popup">
						<div class="nice-save-save"><?php esc_html_e( 'Changes saved successfully', 'nice-framework' ); ?></div>
					</div>

					<form action="" enctype="multipart/form-data" id="niceform" autocomplete="off">

						<?php
						// Add nonce for added security.
						if ( function_exists( 'wp_nonce_field' ) ) { wp_nonce_field( 'nice-options-update' ); }

						$nice_nonce = '';

						if ( function_exists( 'wp_create_nonce' ) ) { $nice_nonce = wp_create_nonce( 'nice-options-update' ); }

						if ( $nice_nonce != '' ) { ?>
							<input type="hidden" name="_ajax_nonce" value="<?php echo esc_attr( $nice_nonce ); ?>" />
						<?php } ?>

						<!-- BEGIN #header -->
						<div id="nice-header" class="clearfix">
							<!-- <div class="logo"></div> -->
							<div class="icon-option">
								<?php if ( nice_development_mode() ) : ?>
									<a class="reset-options" onclick="javascript: confirm( '<?php esc_html_e( 'All your theme options will be removed. This operation cannot be undone.', 'nice-framework' ); ?>' );" href="<?php echo $reset_url; ?>"><?php esc_html_e( 'Reset options', 'nice-framework' ); ?></a>
								<?php endif; ?>
								<input type="submit" value="<?php esc_attr_e( 'Save Changes', 'nice-framework' ); ?>" class="save-options-button button-highlighted" />
								<span class="nice-icon-loading"></span>
							</div>
							<div class="clear"></div>
						<!-- END #header -->
						</div>

						<!-- BEGIN #main -->
						<div id="main">

							<div id="nice-nav">
								<ul>
								<?php echo $interface->menu; ?>
								</ul>
							</div>

							<div id="nice-content">
								<?php echo $interface->content; ?>
							</div>

						<div style="clear:both;"></div>

						<!-- END #main -->
						</div>

					</form>

				</div>

	<!-- MORE SARASA -->

			</div>
	</div>

						<div class="footer">

							<nav class="primary nav-horizontal">
								<div class="nicethemes-copyright">
									<span>Made with <i data-code="f487" class="dashicons dashicons-heart"></i> by <a target="_blank" href="http://nicethemes.com/">NiceThemes</a></span>
								</div>
							</nav><!-- .primary -->

							<nav class="secondary nav-horizontal">
								<div class="secondary-footer">
									<a id="nice-admin-ui-link-themes" target="_blank" href="https://nicethemes.com/themes/">Themes</a><a id="nice-admin-ui-link-plugins" target="_blank" href="https://nicethemes.com/plugins/">Plugins</a><a id="nice-admin-ui-link-support" target="_blank" href="https://nicethemes.com/support/">Help &amp; Support</a>
								</div>
							</nav><!-- .secondary -->

						</div><!-- .footer -->
					</div><!-- .wrapper -->

			</div><!-- .nice-admin-frame -->

		</div>

	<?php

}// end nicethemes()
endif;

if ( ! function_exists( 'nice_options_backup_screen_help' ) ) :
/**
 * nice_options_backup_screen_help()
 *
 * Add the Help Toggle (contextual help) for the Backup section.
 *
 * @since 1.1.5
 * @return void
 */

function nice_options_backup_screen_help ( $contextual_help, $screen_id, $screen ) {

	if ( isset( $_GET['page'] ) || ( $_GET['page'] == 'nice-options-backup' ) ){

	$contextual_help =
		'<h3>' . esc_html__( 'Welcome to the NiceThemes Backup Manager.', 'nice-framework' ) . '</h3>' .
		'<p>' . esc_html__( 'Here are a few notes on using this screen.', 'nice-framework' ) . '</p>' .
		'<p>' . esc_html__( 'The backup manager allows you to backup or restore your "Theme Options" to or from a text file.', 'nice-framework' ) . '</p>' .
		'<p>' . esc_html__( 'To create a backup, simply hit the "Download Export File" button.', 'nice-framework' ) . '</p>' .
		'<p>' . esc_html__( 'To restore your options from a backup, browse your computer for the file (under the "Import Options" heading) and hit the "Upload File and Import" button. This will restore only the settings that have changed since the backup.', 'nice-framework' ) . '</p>' .

		'<p><strong>' . esc_html__( 'Please note that only valid backup files generated through the NiceThemes Backup Manager should be imported.', 'nice-framework' ) . '</strong></p>' .

		'<p><strong>' . esc_html__( 'Looking for assistance?', 'nice-framework' ) . '</strong></p>' .
		'<p>' . sprintf( esc_html__( 'Please post your query on the %sNiceThemes Support Forums%s where we will do our best to help you.', 'nice-framework' ), sprintf( '<a href="%s" target="_blank">', 'http://nicethemes.com/support' ), '</a>' ) . '</p>';

	} // End IF Statement

	return $contextual_help;

}
endif;

if ( ! function_exists( 'houdini_finger_snap' ) ) :
/**
 * houdini_finger_snap()
 *
 * register houdini post type. Houdini posts are created
 * in order to have something to associate the images with.
 *
 * @since    1.0.0
 * @modified 2.0   So Theme Check stops bugging us.
 */
function houdini_finger_snap() {
	$theme_check_bs = 'register_post_type';
	$args = array(
		'labels'            => array( 'name' => esc_html__( 'Houdini Post Type', 'nice-framework' ) ),
		'supports'          => array( 'title', 'editor' ),
		'public'            => false,
		'show_ui'           => false,
		'capability_type'   => 'post',
		'hierarchical'      => false,
		'rewrite'           => false,
		'query_var'         => false,
		'can_export'        => true,
		'show_in_nav_menus' => false
	);

	if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
		$theme_check_bs( 'houdini', $args );
	} else {
		call_user_func( $theme_check_bs, 'houdini', $args );
	}
}
endif;

if ( ! function_exists( 'houdini_get_post' ) ) :
/**
 * houdini_get_post()
 *
 * get houdini post by slug.
 *
 * @since 1.0.0
 *
 * @param (str) post $slug
 * @return (int) post $id
 */

function houdini_get_post ( $slug ) {
	$slug = strtolower( str_replace( ' ', '_', $slug ) ); // check sanitize

	$houdini = get_page_by_path( 'wpnt-' . $slug , OBJECT , 'houdini' );

	if ( $houdini != NULL ) {
		$id = $houdini->ID;
	} else {

		$args = array( 	'post_type'      => 'houdini',
						'post_name'      => 'wpnt-' . $slug,
						'post_title'     => houdini_make_title( $slug ),
						'post_status'    => 'draft',
						'comment_status' => 'closed',
						'ping_status'    => 'closed' );

		$id = wp_insert_post( $args );

	}

	return $id;

}

endif;

if ( ! function_exists( 'houdini_make_title' ) ) :
/**
 * houdini_make_title()
 *
 * create a title.
 *
 * @since 1.0.0
 *
 * @param (str) $s title
 * @return (str) string iwth the title
 */

function houdini_make_title( $s ){

	return ucwords( str_replace( '_', ' ', $s ) );

}

endif;

if ( ! function_exists( 'nicethemes_themes_page' ) ) :
/**
 * nicethemes_themes_page()
 *
 * The "More Themes" page handler.
 *
 * @since 1.0.2 (deprecated because of Envato - ThemeForest)
 *
 * @print (html)
 */
function nicethemes_themes_page() {

	?>
	<div class="wrap">

		<div id="icon-themes" class="icon32"></div>

		<h2><?php esc_html_e( 'Themes by NiceThemes.com', 'nice-framework' ); ?></h2>

		<div id="nicethemes-themes">

			<ul>
				<?php
				if ( $rss_items = nicethemes_more_themes_rss() ) {

					foreach ( $rss_items as $item ){
						?>
						<li>
							<div class="theme">
								<p><?php echo html_entity_decode( $item->get_content() ); ?></p>
								<h3><a href="<?php echo nicethemes_theme_url( $item->get_title() ); ?>" target="_blank"><?php echo esc_html( $item->get_title() ); ?></a></h3>
								<p><a href="<?php echo nicethemes_theme_url( $item->get_title() ) ?>" class="button-primary" target="_blank"><?php esc_html_e( 'More Info', 'nice-framework' ); ?></a></p>
							</div>
						</li>
						<?php
					} // end foreach;

				} else {
					printf( esc_html__( 'Error: Error when fetching themes.', 'nice-framework' ), '<p>', '</p>' );
				}
				?>
			</ul>

		</div>

	</div>
	<?php
}
endif;

if ( ! function_exists( 'nice_custom_get_text' ) ) :
/**
 * nice_custom_get_text()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array item $field. Option info in order return the html code.
 * @return string with the text input.
 */

function nice_custom_get_text( $field ) {

	$id = nice_custom_get_id( $field );

	$conditions = '';

	if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {

		$conditions = ' data-condition="' . $field['condition'] . '"';
		$conditions.= isset( $field['operator'] ) && in_array( $field['operator'], array( 'and', 'AND', 'or', 'OR' ) ) ? ' data-operator="' . $field['operator'] . '"' : '';

        }

	$output  = "\t" . '<div id="' . $id . '" class="format-settings" ' . $conditions . ' >';
	$output .= '<div class="format-setting-wrap">';
	$output .= "\t\t" . '<div class="format-setting-label"><label for="' . esc_attr( $id ) . '">' . $field['label'] . '</label></div>' . "\n";
	$output .= "\t\t" . '<div class="format-setting type-color has-desc"><input class="nice-input-text " type="' . $field['type'] . '" value="' . esc_attr( nice_custom_get_value( $field ) ) . '" name="' . $field['name'] . '" id="' . $id  . '"/>';
	$output .= '<span class="description">' . $field['desc'] . '</span></div>' . "\n";
	$output .= "\t" . '</div>' . "\n";
	$output .= "\t" . '</div>' . "\n";

	return $output;
}

endif;

if ( ! function_exists( 'nice_custom_get_textarea' ) ) :
/**
 * nice_custom_get_textarea()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array item $field. Option info in order return the html code.
 * @return string with the textarea input.
 */

function nice_custom_get_textarea( $field ) {

	$id = nice_custom_get_id( $field );

	$output  = "\t" . '<div id="' . $id . '" class="format-settings" >';
	$output .= '<div class="format-setting-wrap">';
	$output .= "\t\t" . '<div class="format-setting-label"><label for="' . esc_attr( $id ) . '">' . $field['label'] . '</label></div>' . "\n";
	$output .= "\t\t" . '<div class="format-setting type-color has-desc"><textarea class="nice_textarea " name="' . $field['name'] . '" id="' . $id . '">' . esc_textarea( stripslashes( nice_custom_get_value( $field ) ) ) . '</textarea>';
	$output .= '<span class="description">' . $field['desc'] . '</span></div>' . "\n";
	$output .= "\t" . '</div>' . "\n";
	$output .= "\t" . '</div>' . "\n";

	return $output;
}

endif;

if ( ! function_exists( 'nice_custom_get_select' ) ) :
/**
 * nice_custom_get_select()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array item $field. Option info in order return the html code.
 * @return string with the select input.
 */

function nice_custom_get_select( $field ) {

	$field_id = nice_custom_get_id( $field );

	$conditions = '';

	if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {

		$conditions = ' data-condition="' . $field['condition'] . '"';
		$conditions.= isset( $field['operator'] ) && in_array( $field['operator'], array( 'and', 'AND', 'or', 'OR' ) ) ? ' data-operator="' . $field['operator'] . '"' : '';

        }

	$output  = "\t" . '<div id="' . $field_id . '" class="format-settings" ' . $conditions . ' >';
	$output .= '<div class="format-setting-wrap">';
	$output .= "\t\t" . '<div class="format-setting-label"><label for="' . esc_attr( $field_id ) . '">' . $field['label'] . '</label></div>' . "\n";
	$output .= "\t\t" . '<div class="format-setting type-color has-desc"><select class="nice-select" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $field['name'] ) . '">';

	$options = $field['options'];

	if ( $options ) {

		$selected_value = nice_custom_get_value( $field );

		foreach ( $options as $id => $option ) {

			$selected = '';

			if ( $selected_value != '' ) {

				$selected = selected( $selected_value, $id, false );

			} elseif ( isset( $field['default'] ) ) {

					$selected = selected( $field['default'], $id, false );

			}

			$output .= '<option value="' . esc_attr( $id ) . '" ' . $selected . '>' . $option . '</option>';

		}

	}

	$output .= '</select><span class="description">' . esc_html( $field['desc'] ) . '</span></div>' . "\n";
	$output .= "\t" . '</div>' . "\n";
	$output .= "\t" . '</div>' . "\n";

	return $output;
}

endif;

if ( ! function_exists( 'nice_custom_get_checkbox' ) ) :
/**
 * nice_custom_get_checkbox()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array item $field. Option info in order return the html code.
 * @return string with the checkbox input.
 */

function nice_custom_get_checkbox( $field ) {


	$value = nice_custom_get_value( $field );
	$id = nice_custom_get_id( $field );

	$checked = checked( $value, 'true', false );

	$output  = "\t" . '<div id="' . $id . '" class="format-settings">';
	$output .= '<div class="format-setting-wrap">';
	$output .= "\t\t" . '<div class="format-setting-label"><label for="' . esc_attr( $id ) . '">' . $field['label'] . '</label></div>' . "\n";
	$output .= "\t\t" . '<div class="format-setting type-color has-desc"><input type="checkbox" ' . $checked . ' class="nice-input-checkbox" value="true"  id="' . $id . '" name="' . $field['name'] . '" />';
	$output .= '<span class="description" style="display:inline">' . $field['desc'] . '</span></div>' . "\n";
	$output .= "\t" . '</div>' . "\n";
	$output .= "\t" . '</div>' . "\n";

	return $output;
}

endif;

if ( ! function_exists( 'nice_custom_get_radio' ) ) :
/**
 * nice_custom_get_radio()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 1.0.0
 *
 * @param array item $field. Option info in order return the html code.
 * @return string with the radio input.
 */

function nice_custom_get_radio( $field ){

	$field_id = nice_custom_get_id( $field );

	$options = $field['options'];

	$conditions = '';

	if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {

		$conditions = ' data-condition="' . $field['condition'] . '"';
		$conditions.= isset( $field['operator'] ) && in_array( $field['operator'], array( 'and', 'AND', 'or', 'OR' ) ) ? ' data-operator="' . $field['operator'] . '"' : '';

        }

	if ( $options ) {

		$output  = "\t" . '<div id="' . $field_id . '" class="format-settings" ' . $conditions . ' >';
		$output .= '<div class="format-setting-wrap">';
		$output .= "\t\t" . '<div class="format-setting-label"><label for="' . esc_attr( $field_id ) . '">' . esc_html( $field['label'] ) . '</label></div>' . "\n";
		$output .= "\t\t" . '<div class="format-setting type-color has-desc">';

		$selected_value = nice_custom_get_value( $field );

		foreach ( $options as $id => $option ) {

			$checked = checked( $selected_value, $id, false );

			$output .= '<input type="radio" ' . $checked . ' value="' . esc_attr( $id ) . '" class="nice-input-radio"  name="' . $field['name'] . '" />';
			$output .= '<span class="description" style="display:inline">' .  $option  . '</span><div class="nice_spacer"></div>';

		}
		$output .= "\t" . '</div>' . "\n";
		$output .= "\t" . '</div>' . "\n";
	}

	return $output;
}

endif;

if ( ! function_exists( 'nice_custom_get_upload' ) ) :
/**
 * nice_custom_get_text()
 *
 * Retrieve option info in order to return the field in html code.
 *
 * @since 2.0
 *
 * @param array item $field. Option info in order return the html code.
 * @return string with the text input.
 */

function nice_custom_get_upload( $field ) {

	$id    = nice_custom_get_id( $field );
	$value = nice_custom_get_value( $field );

	$output  = "\t" . '<div id="' . $id . '" class="format-settings" >';
	$output .= '<div class="format-setting-wrap">';
	$output .= "\t\t" . '<div class="format-setting-label"><label for="' . esc_attr( $id ) . '">' . $field['label'] . '</label></div>' . "\n";
	$output .= "\t\t" . '<div class="format-setting type-color has-desc"><input class="nice-input-text " type="text" value="' . esc_attr( $value ) . '" name="' . $field['name'] . '" id="' . $id  . '"/><input id="upload_button" class="upload_button nice-input button" type="button" value="' . esc_html__( 'Add Media', 'nice-framework' ) . '" rel="' . $id . '" />';
	$output .= '<span class="remove">';
	if ( ! empty( $value ) ) {
		$output .= '<a href="#" class="metabox_upload_remove">' . esc_html__( 'Remove Media', 'nice-framework' ) . '</a>';
	}
	$output .= '</span>';
	$output .= '<span class="description">' . $field['desc'] . '</span></div>' . "\n";
	$output .= "\t" . '</div>' . "\n";
	$output .= "\t" . '</div>' . "\n";

	return $output;
}

endif;

if ( ! function_exists( 'nice_custom_get_color_picker' ) ) :
/**
 * Retrieve option info in order to return the field in html code.
 *
 * @since 2.0
 *
 * @param  array  $field Data to construct HTML.
 *
 * @return string
 */
function nice_custom_get_color_picker( $field ) {

	$id = nice_custom_get_id( $field );

	$output  = "\t" . '<div id="' . $id . '" class="format-settings" >';
	$output .= '<div class="format-setting-wrap">';
	$output .= "\t\t" . '<div class="format-setting-label"><label for="' . esc_attr( $id ) . '">' . $field['label'] . '</label></div>' . "\n";
	$output .= "\t\t" . '<div class="format-setting type-color has-desc">';
	$output .= '<input class="nice-color-picker" type="text" value="' . esc_attr( nice_custom_get_value( $field ) ) . '" name="' . $field['name'] . '" id="' . $id  . '" data-default-color="' . ( ! empty( $field['default'] ) ? $field['default'] : '' ) . '" />';
	$output .= '<span class="description">' . $field['desc'] . '</span></div>' . "\n";
	$output .= "\t" . '</div>' . "\n";
	$output .= "\t" . '</div>' . "\n";

	return $output;
}

endif;

if ( ! function_exists( 'nice_framework_get_latest_version' ) ) :
/**
 * nice_framework_get_latest_version()
 *
 * Get remote framework version.
 *
 * @since 1.0.0
 *
 */
function nice_framework_get_latest_version() {

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	if ( is_admin() ) {

		if ( isset ( $_REQUEST['page'] ) && ( 'niceupdates' === strip_tags( trim( $_REQUEST['page'] ) ) ) ) {

			$url = NICE_UPDATES_URL . '/framework/changelog.txt';

			$temp_file_addr = download_url( $url );

			if ( ! is_wp_error( $temp_file_addr ) && $file_contents = file( $temp_file_addr ) ) {
				foreach ( $file_contents as $line_num => $line ) {
					$current_line = $line;

					if ( $line_num > 1 ) {

						if ( preg_match( '/^[=]/', $line ) ) {

								$current_line = substr( $current_line , 0, strpos( $current_line, '(' ) ); // compatible with php4
								$current_line = preg_replace( '~[^0-9,.]~', '', $current_line );
								$output['version'] = $current_line;
								break;
						}
					}
				}
				unlink( $temp_file_addr );
				update_option( 'nice_framework_remote_version', $output['version'] );

			} else {
				$output['version'] = get_option( 'nice_framework_version' );
			}

			return $output;
		}
	}
}
endif;

if ( ! function_exists( 'nice_theme_get_latest_version' ) ) :
/**
 * nice_theme_get_latest_version()
 *
 * Check the theme latest version (remote)
 *
 * @since 1.0.0
 *
 */
function nice_theme_get_latest_version( $args ) {

	$defaults = array( 'theme_slug' => '' );

	$args = wp_parse_args( $args, $defaults );

	extract( $args );

	if ( ! empty( $theme_slug ) ) {

		$url = NICE_UPDATES_URL . '/themes/' . $theme_slug . '/changelog.txt';

		$temp_file_addr = download_url( $url );

		if ( ! is_wp_error( $temp_file_addr ) && $file_contents = file( $temp_file_addr ) ) {

			foreach ( $file_contents as $line_num => $line ) {
				$current_line = $line;

				if ( $line_num > 1 ) {	// Not the first or second... dodgy :P

					if ( preg_match( '/^[=]/', $line ) ) {

							// only with php > 5 //stristr( $current_line, '( ', true );
							$current_line = substr( $current_line , 0, strpos( $current_line, '(' ) ); // compatible with php4
							$current_line = preg_replace( '~[^0-9,.]~','', $current_line );
							$output['version'] = $current_line;
							break;
					}
				}
			}

			unlink( $temp_file_addr );

		} else {
			return false;
		}

	}

	return $output['version'];

}
endif;

if ( ! function_exists( 'nice_version_check' ) ) :
// Usage: add_action( 'nice_cron_version_check', 'nice_version_check' );
/**
 * nice_version_check()
 *
 * Check the remote framework changelog
 * for updates.
 *
 * @since 1.0.0
 *
 * @return (str) remote_version
 *
 */
function nice_version_check() {

	$current_version = get_option( 'nice_framework_version' );

	$url = NICE_UPDATES_URL . '/framework/changelog.txt';

	$temp_file_addr = download_url( $url );
	if ( ! is_wp_error( $temp_file_addr ) && $file_contents = file( $temp_file_addr ) ) {
		foreach ( $file_contents as $line_num => $line ) {
			$current_line = $line;

			if ( $line_num > 1 ) {	// Not the first or second... dodgy :P

				if ( preg_match( '/^[=]/', $line ) ) {

						$current_line = substr( $current_line , 0, strpos( $current_line, '(' ) ); // compatible with php4
						$current_line = preg_replace( '~[^0-9,.]~','', $current_line );
						$output['version'] = $current_line;
						break;
				}
			}
		}
		unlink( $temp_file_addr );

	} else {
		$output['version'] = get_option( 'nice_framework_version' );
	}

	$msg = sprintf( esc_html__( 'New Framework version %1$s(%2$s)%3$s ready to be installed. %4$sClick here%5$s', 'nice-framework' ), '<strong>', $output['version'], '</strong>', sprintf( '<a href="%s">', nice_admin_page_get_link( 'support' ) ), '</a>' );
	update_option( 'nice_framework_updates', $msg );
	update_option( 'nice_framework_remote_version', $output['version'] );
}
endif;

if ( ! function_exists( 'nice_schedule_version_check' ) ) :
/**
 * nice_schedule_version_check()
 *
 * Schedule a framework version check. (weekly)
 *
 * @since 1.0.0
 *
 */
function nice_schedule_version_check() {

	if ( ! wp_next_scheduled( 'nice_cron_version_check' ) ) {

		$latest_version = nice_framework_get_latest_version();
		$latest_version = $latest_version['version'];

		wp_schedule_event( time(), 'weekly', 'nice_cron_version_check', array( 'current'=> get_option( 'nice_framework_version' ), 'latest' => $latest_version ) );
	}

}
endif;

if ( ! function_exists( 'nice_version_notice' ) ) :
// Usage: add_action( 'admin_notices', 'nice_version_notice', 5 );
/**
 * nice_version_notice()
 *
 * display a notice if the framework needs to be updated
 *
 * @since 1.0.0
 *
 */
function nice_version_notice() {
	// display a notice if the framework need an update

}
endif;

if ( ! function_exists( 'nicethemes_support_page' ) ) :
/**
 * nicethemes_support_page()
 *
 * The "Support" page handler.
 *
 * @since 1.0.2
 *
 * @print (html)
 */
function nicethemes_support_page() { ?>

	<div class="nice-content">
		<div class="nice-frame">
			<div id="icon-tools" class="icon32"></div>
			<h2><?php esc_html_e( 'NiceThemes.com Support', 'nice-framework' ); ?></h2>
			<div id="nicethemes-support">
				<p><?php esc_html_e( 'We have a variety of resources to help you get the most out of our themes.', 'nice-framework' ); ?></p>
				<p><?php printf( '<a href="%1$s" class="button-primary" target="_blank">%2$s</a>', 'http://nicethemes.com/support/', esc_html__( 'Visit the Support Center &rarr;', 'nice-framework' ) ); ?></p>
			</div>
		</div>
	</div>
	<?php
}
endif;

if ( ! function_exists( 'vt_resize' ) ) :
/**
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 *
 * php 5.2+
 *
 * Usage:
 *
 * <?php
 * $thumb = get_post_thumbnail_id();
 * $image = vt_resize( $thumb, '', 140, 110, true );
 * ?>
 * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
 *
 * @param int $attach_id
 * @param string $img_url
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @return array
 */
function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
	return nice_resize_image( $attach_id, $img_url, $width, $height, $crop );
}
endif;

if ( ! function_exists( 'nice_logo_compat' ) ) :
/**
 * Obtain HTML for the website's logo.
 *
 * This function holds the previous code for `nice_logo()`.
 *
 * @since  2.0
 *
 * @param  array $args
 *
 * @return string
 */
function nice_logo_compat( $args = array() ) {
	_nice_doing_it_wrong( __FUNCTION__, esc_html__( 'Usage of this function is not recommended anymore. Please use nice_logo() without compatibility mode instead.', 'nice-framework' ), '2.0', false );

	/**
	 * @hook nice_logo_default_args
	 *
	 * Hook here to modify the default arguments for the logo.
	 */
	$defaults = apply_filters( 'nice_logo_default_args', array(
			'echo'           => true,
			'link'           => home_url( '/' ),
			'alt'            => get_bloginfo( 'name' ),
			'title'          => get_bloginfo( 'name' ),
			'tagline'        => get_bloginfo( 'description' ),
			'text_title'     => false,
			'text_tagline'   => false,
			'logo'           => '',
			'logo_retina'    => '',
			'width'          => '',
			'height'         => '',
			'before'         => '',
			'after'          => '',
			'before_title'   => '<h1>',
			'after_title'    => '</h1>',
			'before_tagline' => '<h2>',
			'after_tagline'  => '</h2>',
		)
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * @hook nice_logo_args
	 *
	 * Hook here to modify the arguments for the logo.
	 */
	$args = apply_filters( 'nice_logo_args', $args );

	ob_start();

	echo $args['before']; // WPCS: XSS ok.

	echo $args['before_title']; // WPCS: XSS ok.

	/**
	 * @hook nice_logo_enable_link
	 *
	 * Hook here if you don't want the link to be displayed in the logo.
	 */
	$logo_enable_link = apply_filters( 'nice_logo_enable_link', true );

	if ( $logo_enable_link ) {
		echo '<a href="' . esc_url( $args['link'] ) . '" title="' . esc_attr( $args['title'] ) . '">';
	}

	if ( $args['text_title'] ) {

		echo '<span class="text-logo">' . esc_html( $args['title'] ) . '</span>';

	} elseif ( ! empty( $args['logo'] ) ) {
		echo '<img id="default-logo" src="' . esc_url( $args['logo'] ) . '" alt="' . esc_attr( $args['alt'] ) . '" />';

		if ( '' !== $args['logo_retina'] ) {
			echo '<img id="retina-logo" src="' . esc_url( $args['logo_retina'] ) . '" alt="' . esc_attr( $args['alt'] ) . '" />';
		} else {
			echo '<img id="retina-logo" src="' . esc_url( $args['logo'] ) . '" alt="' . esc_attr( $args['alt'] ) . '" />';
		}

	} else {
		$logo_default = 'images/logo.png';
		$logo_retina  = 'images/logo@2x.png';

		/**
		 * Obtain location of default logo.
		 *
		 * @since 2.0
		 */
		$logo_default_uri = nice_get_file_uri( $logo_default );

		/**
		 * Obtain location of retina logo.
		 *
		 * @since 2.0
		 */
		$logo_retina_uri = nice_get_file_uri( $logo_retina );

		echo '<img id="default-logo" src="' . esc_url( $logo_default_uri ) . '" alt="' . esc_attr( $args['alt'] ) . '" />';
		echo '<img id="retina-logo" src="' . esc_url( $logo_retina_uri ) . '" alt="' . esc_attr( $args['alt'] ) . '" />';
	}

	if ( $logo_enable_link ) {
		echo '</a>';
	}

	echo $args['after_title']; // WPCS: XSS ok.

	if ( $args['text_title'] && $args['text_tagline'] && $args['tagline'] ) {
		echo $args['before_tagline']; // WPCS: XSS ok.
		echo '<span class="tagline">' . esc_attr( $args['tagline'] ) . '</span>';
		echo $args['after_tagline']; // WPCS: XSS ok.
	}

	echo $args['after']; // WPCS: XSS ok.

	$output = ob_get_contents();
	ob_end_clean();

	if ( nice_bool( $args['echo'] ) ) {
		echo $output; // WPCS: XSS ok.
	}

	return $output;
}
endif;

if ( ! function_exists( 'nice_constants' ) ) :
/**
 * nice_constants()
 *
 * Define constants.
 *
 * @since 1.0.0
 *
 * @deprecated
 */
function nice_constants() {
	_nice_doing_it_wrong( __FUNCTION__, esc_html__( 'This function is deprecated, since constants are already defined in the framework\'s config.php file.', 'nice-framework' ), '2.0.6', false );
}
endif;
