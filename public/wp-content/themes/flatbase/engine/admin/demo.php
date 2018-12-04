<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Functions related to the Demo Installer.
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_theme_has_demo_packs' ) ) :
/**
 * Obtain whether or not the current theme has available demo packs.
 *
 * @return bool
 */
function nice_theme_has_demo_packs() {
	$demo_packs = nice_theme_get_demo_packs();

	return ( ! empty( $demo_packs ) );
}
endif;

if ( ! function_exists( 'nice_theme_obtain_demo_pack' ) ) :
/**
 * Obtain an instance of the Nice_Theme_Demo_Pack class.
 *
 * @since 2.0
 *
 * @param  string $slug
 *
 * @return WP_Error|Nice_Theme_Demo_Pack
 */
function nice_theme_obtain_demo_pack( $slug ) {
	return Nice_Theme_Demo_Pack::obtain( $slug );
}
endif;

if ( ! function_exists( 'nice_theme_get_demo_pack' ) ) :
/**
 * Obtain a single available demo pack from its slug. Returns null if the slug is not found.
 *
 * @param string $slug Slug of the wanted demo pack.
 *
 * @return null|array
 */
function nice_theme_get_demo_pack( $slug ) {
	static $demo_packs = null;

	if ( is_null( $demo_packs ) ) {
		$demo_packs = nice_theme_get_demo_packs();
	}

	return isset( $demo_packs[ $slug ] ) ? $demo_packs[ $slug ] : null;
}
endif;

if ( ! function_exists( 'nice_theme_get_demo_packs' ) ) :
/**
 * Obtain the demo packs available for the current theme.
 *
 * @return array
 */
function nice_theme_get_demo_packs() {
	static $demo_packs = null;

	if ( is_null( $demo_packs ) ) {
		$demo_packs = get_transient( 'nice_theme_demo_packs' );

		if ( empty( $demo_packs ) ) {
			/**
			 * @hook nice_theme_demo_packs
			 *
			 * Hook here to modify the automatically generated demo pack list.
			 *
			 * @since  2.0
			 */
			$demo_packs = apply_filters( 'nice_theme_demo_packs', array() );

			uasort( $demo_packs, 'nice_theme_uasort_demo_packs' );

			set_transient( 'nice_theme_demo_packs', $demo_packs, HOUR_IN_SECONDS );
		}
	}

	return $demo_packs;
}
endif;


if ( ! function_exists( 'nice_theme_uasort_demo_packs' ) ) :
/**
 * Helper function to sort demo packs.
 *
 * @param $a
 * @param $b
 *
 * @return int
 */
function nice_theme_uasort_demo_packs( $a, $b ) {
	$order_a = isset( $a['menu_order'] ) ? $a['menu_order'] : 0;
	$order_b = isset( $b['menu_order'] ) ? $b['menu_order'] : 0;

	if ( $order_a === $order_b ) {
		return 0;
	}

	return $order_a > $order_b ? 1 : -1;
}
endif;

if ( ! function_exists( 'nice_theme_import_demo_pack' ) ) :
add_action( 'wp_ajax_nice_theme_import_demo_pack', 'nice_theme_import_demo_pack' );
/**
 * Handle the AJAX request for a demo pack import.
 *
 * @since 2.0
 */
function nice_theme_import_demo_pack() {
	/**
	 * Return early if we're not in an AJAX context, or if the nonce does not validate.
	 */
	if ( ! nice_doing_ajax() || ! check_ajax_referer( 'play-nice', 'nice_demo_import_nonce' ) ) {
		return;
	}

	// Obtain the demo pack.
	$demo_slug = isset( $_POST['demo_slug'] ) ? strip_tags( wp_unslash( $_POST['demo_slug'] ) ) : '';
	$demo_pack = nice_theme_obtain_demo_pack( $demo_slug );

	if ( ! ( $demo_pack instanceof Nice_Theme_Demo_Pack ) ) {
		$error_message = $demo_pack->get_error_message();
	}

	if ( isset( $error_message ) && ! empty( $error_message ) ) {
		echo wp_json_encode( array(
			'message' => sprintf( '<strong>%s</strong> %s', esc_html__( 'ERROR:', 'nice-framework' ), $error_message . ' ' . sprintf( ' <a href="%s">%s</a>.', nice_admin_page_get_link( 'demos' ), esc_html__( 'Reload this page', 'nice-framework' ) ) ),
			'more'    => 0,
		) );

		die();
	}

	/**
	 * If requested, enable the site reset process.
	 */
	if ( isset( $_POST['reset_site'] ) && true === nice_bool( wp_unslash( $_POST['reset_site'] ) ) ) {
		$demo_pack->enable_site_reset();
	}

	/**
	 * Trigger the importing process.
	 */
	$demo_pack->import();
}
endif;


if ( ! function_exists( 'nice_theme_demo_pack_import_template' ) ) :
add_action( 'nice_admin_page_screen', 'nice_theme_demo_pack_import_template', 40 );
/**
 * Print markup for demo pack importing after the footer.
 *
 * @since 2.0
 */
function nice_theme_demo_pack_import_template() {
	if ( 'demos' === nice_admin_get_current_page() ) {
		?>
			<div class="nice-demo-import-window" style="display: none;">
				<div id="nice-demo-import-loader"></div>
				<div id="nice-demo-import-results"></div>
			</div>
			<div class="nice-demo-import-overlay" style="display: none;"></div>
		<?php
	}
}
endif;

if ( ! function_exists( 'nice_demo_pack_system_requirements' ) ) :
/**
 * Print out formatted HTML for demo pack system requirements.
 *
 * @since 2.0.4
 *
 * @param Nice_Theme_Demo_Pack $demo_pack
 */
function nice_demo_pack_system_requirements( Nice_Theme_Demo_Pack $demo_pack ) {
	$requirements = $demo_pack->get_system_requirements();

	if ( ! empty( $requirements ) ) : ?>
		<ul>
			<?php foreach ( $requirements as $id => $requirement ) : ?>
				<?php nice_demo_pack_system_requirement( $demo_pack, $id, $requirement ); ?>
			<?php endforeach; ?>
		</ul>
	<?php endif;
}
endif;

if ( ! function_exists( 'nice_demo_pack_system_requirement' ) ) :
/**
 * Print out formatted HTML for a system requirement.
 *
 * @since 2.0.4
 *
 * @param Nice_Theme_Demo_Pack $demo_pack
 * @param string               $id
 * @param array                $requirement
 */
function nice_demo_pack_system_requirement( Nice_Theme_Demo_Pack $demo_pack, $id = '', array $requirement ) {
	if ( empty( $requirement ) ) {
		return;
	}

	$recommended_value_text = sprintf( esc_html__( '%s Version %s+', 'nice-framework' ), $requirement['title'], $requirement['recommended'] );

	$enabled_text  = esc_html__( 'Enabled', 'nice-framework' );
	$disabled_text = esc_html__( 'Disabled', 'nice-framework' );

	$warnings = $demo_pack->get_system_warnings();
	$errors   = $demo_pack->get_system_errors();

	if ( is_bool( $requirement['current'] ) ) {
		$requirement['current'] = $requirement['current'] ? $enabled_text : $disabled_text;
	}

	if ( is_bool( $requirement['recommended'] ) ) {
		$requirement['recommended'] = $requirement['recommended'] ? $enabled_text : $disabled_text;
		$recommended_value_text = sprintf( '%s: %s', $requirement['title'], $requirement['recommended'] );
	}

	if ( is_bool( $requirement['required'] ) ) {
		$requirement['required'] = $requirement['required'] ? $enabled_text : $disabled_text;
	}

	if ( ! empty( $requirement['messages']['recommended'] ) ) {
		$recommended_value_text = $requirement['messages']['recommended'];
	}

	$icon_class = 'bi_interface-circle-tick';
	$tooltip = ! empty( $requirement['messages']['success'] ) ? $requirement['messages']['success'] : '';

	if ( isset( $errors[ $id ] ) ) {
		$icon_class = 'bi_interface-circle-cross';
		$tooltip = ! empty( $requirement['messages']['error'] ) ? $requirement['messages']['error'] : '';
	}

	if ( isset( $warnings[ $id ] ) ) {
		$icon_class = 'bi_interface-circle-minus';
		$tooltip = ! empty( $requirement['messages']['warning'] ) ? $requirement['messages']['warning'] : '';
	}

	?>
	<li>
		<a class="nice-tooltip nice-tooltip-info" title="<?php echo esc_attr( $tooltip ); ?>"><i class="<?php echo esc_attr( $icon_class ); ?>"></i> <?php echo esc_html( $recommended_value_text ); ?></a>
	</li>
	<?php
}
endif;

if ( ! function_exists( 'nice_demo_pack_plugin_requirements' ) ) :
/**
 * Print out formatted HTML for demo pack plugin requirements.
 *
 * @since 2.0.4
 *
 * @param Nice_Theme_Demo_Pack $demo_pack
 */
function nice_demo_pack_plugin_requirements( Nice_Theme_Demo_Pack $demo_pack ) {
	$requirements = $demo_pack->get_plugins();

	if ( ! empty( $requirements ) ) : ?>
		<?php if ( $demo_pack->has_plugin_exceptions() ) : ?>
			<p>
				<?php esc_html_e( 'Some of the required plugins are inactive, outdated or missing. The Demo Installer process will take care of these requirements automatically.', 'nice-framework' ); ?>
			</p>
		<?php endif; ?>

		<ul>
			<?php foreach ( $requirements as $id => $requirement ) : ?>
				<?php nice_demo_pack_plugin_requirement( $demo_pack, $id, $requirement ); ?>
			<?php endforeach; ?>
		</ul>
	<?php endif;
}
endif;

if ( ! function_exists( 'nice_demo_pack_plugin_requirement' ) ) :
/**
 * Print out formatted HTML for a plugin requirement.
 *
 * @since 2.0.4
 *
 * @param Nice_Theme_Demo_Pack $demo_pack
 * @param string               $plugin_slug
 * @param array                $plugin_data
 */
function nice_demo_pack_plugin_requirement( Nice_Theme_Demo_Pack $demo_pack, $plugin_slug = '', array $plugin_data ) {
?>
	<li>
		<?php
		if ( $plugin_data['active'] ) {
			if ( $plugin_data['outdated'] ) {
				$tooltip = esc_attr( sprintf( esc_html__( '%s is active, but outdated.', 'nice-framework' ), $plugin_data['name'] ) . '<br />' . sprintf( esc_html__( 'Its current version is %s.', 'nice-framework' ), $demo_pack->system_status->get_plugin_version( $plugin_slug ) ) . '<br />' . esc_html__( 'It must be updated or deactivated.', 'nice-framework' ), $plugin_data['name'] );
				$icon_class   = 'bi_interface-circle-cross';
				$demo_requirements_errors[] = $tooltip;
			} else {
				$tooltip = esc_attr( sprintf( esc_html__( '%s is active.', 'nice-framework' ), $plugin_data['name'] ) . '<br />' . ( ( ! empty( $plugin_data['version'] ) ) ? sprintf( esc_html__( 'Its current version is %s.', 'nice-framework' ), $demo_pack->system_status->get_plugin_version( $plugin_slug ) ) . '<br />' : '') . esc_html__( 'No action is needed.', 'nice-framework' ) );
				$icon_class   = 'bi_interface-circle-tick';
			}

		} elseif ( $plugin_data['installed'] ) {
			if ( $plugin_data['outdated'] ) {
				$tooltip = esc_attr( sprintf( esc_html__( '%s is installed, but outdated and inactive.', 'nice-framework' ), $plugin_data['name'] ) . '<br />' . sprintf( esc_html__( 'Its current version is %s.', 'nice-framework' ), $demo_pack->system_status->get_plugin_version( $plugin_slug ) ) . '<br />' . esc_html__( 'It should be updated and activated.', 'nice-framework' ), $plugin_data['name'] );
				$icon_class   = 'bi_interface-circle-minus soft-warning';
				$demo_requirements_errors[] = $tooltip;
			} else {
				$tooltip = esc_attr( sprintf( esc_html__( '%s is installed, but inactive.', 'nice-framework' ), $plugin_data['name'] ) . '<br />' . ( ( ! empty( $plugin_data['version'] ) ) ? sprintf( esc_html__( 'Its current version is %s.', 'nice-framework' ), $demo_pack->system_status->get_plugin_version( $plugin_slug ) ) . '<br />' : '') . esc_html__( 'It should be activated.', 'nice-framework' ) );
				$icon_class   = 'bi_interface-circle-minus soft-warning';
				$demo_requirements_errors[] = $tooltip;
			}

		} elseif ( $plugin_data['missing'] ) {
			$tooltip = esc_attr( sprintf( esc_html__( '%s is not installed.', 'nice-framework' ), $plugin_data['name'] ) . '<br />' . esc_html__( 'It should be installed and active.', 'nice-framework' ) );
			$icon_class   = 'bi_interface-circle-minus soft-warning';
			$demo_requirements_errors[] = $tooltip;
		}
		?>
		<a class="nice-tooltip nice-tooltip-info" title="<?php echo esc_attr( $tooltip ); ?>"><i class="<?php echo esc_attr( $icon_class ); ?>"></i> <?php echo esc_html( $plugin_data['name'] ) . ( ! empty( $plugin_data['version'] ) ? ' ' . esc_attr( $plugin_data['version'] ) : '' ); ?></a>
	</li>
	<?php
}
endif;
