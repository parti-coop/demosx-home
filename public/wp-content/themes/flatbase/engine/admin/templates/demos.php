<?php
/**
 * Nice Admin by NiceThemes.
 *
 * Demos content.
 *
 * @package Nice_Framework
 * @since   2.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Obtain system status handler.
 */
$system_status = nice_admin_system_status();

/**
 * Obtain available demo packs.
 *
 * @see nice_theme_get_demo_packs()
 */
$demo_packs = nice_theme_get_demo_packs();

/**
 * Obtain and validate a demo slug that may be passed as a parameter.
 */
$current_demo_slug = isset( $_REQUEST['demo'] ) ? strip_tags( $_REQUEST['demo'] ) : '';
if ( ! empty( $current_demo_slug ) && ! array_key_exists( $current_demo_slug, $demo_packs ) ) {
	$current_demo_slug = false;
}

?>
<div class="nice-install-items nice-install-demos">

	<?php if ( ! empty( $demo_packs ) ) : ?>

		<?php $nice_video_embed_url_install_demos = nice_get_remote_setting( 'video_embed_url_install_demos', 'https://www.youtube.com/embed/Vqnpvvv6vpQ?autoplay=1' ); ?>

		<?php
			$tags     = array();
			$colors   = array();
			$features = array();

			ob_start();
		?>

		<div class="nice-item-browser nice-demo-browser isotope">

			<?php add_thickbox(); ?>

			<?php foreach ( $demo_packs as $demo_slug => $demo_pack ) : ?>

				<?php $demo_pack = nice_theme_obtain_demo_pack( $demo_slug ); ?>

				<?php
					$theme_updates_page  = add_query_arg( 'demo', $demo_pack->get_slug(), nice_admin_page_get_link( 'support' ) );
					$plugin_updates_page = add_query_arg( 'demo', $demo_pack->get_slug(), nice_admin_page_get_link( 'plugins' ) );

					$demo_pack_warnings       = count( $demo_pack->get_system_warnings() );
					$demo_pack_errors         = count( $demo_pack->get_system_errors() );
					$demo_pack_exceptions     = $demo_pack_warnings + $demo_pack_errors;
					$demo_pack_has_warnings   = ! empty( $demo_pack_warnings );
					$demo_pack_has_errors     = ! empty( $demo_pack_errors );
					$demo_pack_has_exceptions = $demo_pack_has_warnings || $demo_pack_has_errors;

					$updates_required  = array();
					$ribbon_icon_class = $ribbon_tooltip = '';

					if ( ! $demo_pack->can_install() ) {
						if ( ! $demo_pack->is_theme_version() ) {
							$updates_required[] = esc_html__( 'Theme', 'nice-framework' );
						}
						if ( ! $demo_pack->is_framework_version() ) {
							$updates_required[] = esc_html__( 'Framework', 'nice-framework' );
						}
						if ( $demo_pack->have_active_outdated_plugins() ) {
							$updates_required[] = esc_html__( 'Plugins', 'nice-framework' );
						}
					}
				?>

				<?php
					$isotope_class = '';

					if ( $demo_pack->have_tags() ) {
						foreach ( $demo_pack->get_tags() as $tag_slug => $tag_name ) {
							$tags[ $tag_slug ]  = $tag_name;
							$isotope_class     .= ' tag-' . $tag_slug;
						}
					}

					if ( $demo_pack->have_colors() ) {
						foreach ( $demo_pack->get_colors() as $color_slug => $color_name ) {
							$colors[ $color_slug ]  = $color_name;
							$isotope_class         .= ' color-' . $color_slug;
						}
					}

					if ( $demo_pack->have_features() ) {
						foreach ( $demo_pack->get_features() as $feature_slug => $feature_name ) {
							$features[ $feature_slug ]  = $feature_name;
							$isotope_class             .= ' feature-' . $feature_slug;
						}
					}

					$isotope_class = trim( $isotope_class );
				?>

				<?php
					$is_current = $current_demo_slug === $demo_pack->get_slug();

					if ( $demo_pack->is_installed() ) {
						$ribbon_icon_class = 'dashicons dashicons-yes';
					}
				?>

				<div id="demo-<?php echo esc_attr( $demo_pack->get_slug() ); ?>" class="item <?php echo esc_attr( $isotope_class ); ?> isotope-item <?php echo $demo_pack->is_installed() ? 'active' : ''; ?> <?php echo $demo_pack->is_partially_installed() ? 'partial' : ''; ?> <?php echo $is_current ? 'current' : ''; ?>">

					<div class="item-screenshot">
						<img src="<?php echo esc_url( $demo_pack->get_featured_image() ); ?>" alt="<?php echo esc_attr( $demo_pack->get_name() ); ?>" />
					</div>

					<?php if ( ! $demo_pack->can_install() ) : ?>
						<div class="item-update">
							<?php printf( esc_html__( 'Updates: %s', 'nice-framework' ), esc_attr( join( ', ', $updates_required ) ) ); ?>
						</div>
					<?php endif; ?>

					<h3 class="item-name">
						<?php
						if ( $demo_pack->is_installed() ) {
							echo sprintf( '<span>%s</span> ', ( $demo_pack->is_fully_installed() ? esc_html__( 'Installed:', 'nice-framework' ) : esc_html__( 'Partially Installed:', 'nice-framework' ) ) );
						}
						echo esc_html( $demo_pack->get_name() );
						?>
					</h3>

					<div class="item-actions">

						<a href="#TB_inline?width=640&height=480&inlineId=demo-install-<?php echo esc_attr( $demo_pack->get_slug() ); ?>" id="button-warning-<?php echo esc_attr( $demo_pack->get_slug() ); ?>" class="button button-primary thickbox <?php echo $is_current ? 'button-call-details' : ''; ?>"  title="<?php printf( ( $demo_pack->is_installed() ? esc_attr__( 'Reinstall %s', 'nice-framework' ) : esc_attr__( 'Install %s', 'nice-framework' ) ), esc_attr( $demo_pack->get_name() ) ); ?>"><?php echo $demo_pack->is_installed() ? esc_html__( 'Reinstall', 'nice-framework' ) : esc_html__( 'Install', 'nice-framework' ); ?></a>

						<?php if ( $demo_pack->get_preview_url() ) : ?>
							<a href="<?php echo esc_url( $demo_pack->get_preview_url() ); ?>" target="_blank" class="nice-tooltip button button-secondary button-preview" title="<?php printf( esc_attr__( 'Preview %s', 'nice-framework' ), esc_attr( $demo_pack->get_name() ) ); ?>"><i class="dashicons dashicons-visibility"></i></a>
						<?php endif; ?>

					</div>

					<?php if ( ( '' !== $ribbon_icon_class ) || ( '' !== $ribbon_tooltip ) ) : ?>
						<div class="item-ribbon <?php echo $demo_pack->is_installed() ? 'active' : ''; ?>">
							<a href="#" class="item-ribbon-icon nice-tooltip" title="<?php echo esc_attr( $ribbon_tooltip ); ?>"><i class="<?php echo esc_attr( $ribbon_icon_class ); ?>"></i></a>
						</div>
					<?php endif; ?>

					<div id="demo-install-<?php echo esc_attr( $demo_pack->get_slug() ); ?>" class="demo-thickbox" style="display: none;">
						<div class="demo-install">

							<div class="demo-thickbox-content">

								<div id="tabs" class="nice-tabs">
									<ul class="nice-nav">
										<li><a href="#demo-description"><?php esc_html_e( 'Demo Description', 'nice-framework' ); ?></a></li>
										<?php $badge_type = $demo_pack_has_errors ? 'error' : 'warning'; ?>
										<li><a href="#demo-requirements"><?php esc_html_e( 'Requirements', 'nice-framework' ); ?><?php echo ( $demo_pack_has_exceptions ? ' <span class="nice-badge ' . $badge_type . '">' . intval( $demo_pack_exceptions ) . '</span>' : '' ); ?></a></li>
										<li><a href="#demo-important"><?php esc_html_e( 'Important Notes', 'nice-framework' ); ?></a></li>
									</ul>
									<div id="demo-description" class="demo-description">

										<div class="clearfix">
											<div class="demo-images">
												<img src="<?php echo esc_url( $demo_pack->get_featured_image() ); ?>" />
											</div>

											<h1><?php echo esc_attr( $demo_pack->get_name() ); ?></h1>

											<?php if ( $demo_pack->have_tags() || $demo_pack->have_colors() || $demo_pack->have_features() ) : ?>

												<div class="clearfix">

												<?php if ( $demo_pack->have_tags() ) : ?>
													<span class="demo-tags">
														<strong><i class="dashicons dashicons-tag"></i></strong> <?php echo esc_html( implode( ', ', $demo_pack->get_tags() ) ); ?>
													</span>
												<?php endif; ?>

												<?php if ( $demo_pack->have_colors() ) : ?>
													<span class="demo-colors">
														<strong><i class="dashicons dashicons-admin-appearance"></i></strong> <?php echo esc_html( implode( ', ', $demo_pack->get_colors() ) ); ?>
													</span>
												<?php endif; ?>

												<?php if ( $demo_pack->have_features() ) : ?>
													<span class="demo-features">
														<strong><i class="dashicons dashicons-admin-plugins"></i></strong> <?php echo esc_html( implode( ', ', $demo_pack->get_features() ) ); ?>
													</span>
												<?php endif; ?>

												</div>

											<?php endif; ?>

											<?php
												$demo_description = $demo_pack->get_description()
											?>

											<?php if ( ! empty( $demo_description ) ) : ?>
												<div class="demo-description">
													<?php echo wpautop( $demo_description ); ?>
												</div>
											<?php endif; ?>
										</div>

									</div>

									<div id="demo-requirements" class="demo-requirements">
										<?php
											$demo_requirements_errors = array();
											$notice_type = $demo_pack_has_errors ? 'error' : 'warning';
										?>
										<h3><?php printf( esc_html__( 'Requirements to install "%s"', 'nice-framework' ) , esc_attr( $demo_pack->get_name() ) ); ?></h3>

										<div class="grid">

											<div class="columns-1">
												<div class="nice-notice <?php echo ( $demo_pack_has_exceptions ? $notice_type : 'info' ); ?>">
													<p>
														<?php if ( $demo_pack_has_warnings && ! $demo_pack_has_errors ) :
															esc_html_e( 'The demo installer may work fine with your current setup, but we recommend making some improvements to make sure the process doesn\'t fail. You can check which system requirements are showing warnings and fix them before proceeding.', 'nice-framework' );
														elseif ( $demo_pack_has_errors ) :
															esc_html_e( 'The demo installer will probably fail with your current setup. Please check which system requirements are showing errors and fix them before proceeding.', 'nice-framework' );
														else :
															esc_html_e( 'Your current setup is fine for importing demo sites in most WordPress setups. However, this list of requirements only serves as a reference, and you may need to increase your resources depending on your particular installation, server or hosting plan.', 'nice-framework' );
														endif; ?>
													</p>
													<p>
														<?php printf( esc_html__( 'For more information, read our %1$sImportant Notes%2$s, %3$scheck your System Status%4$s, or learn more about %5$ssystem status%6$s and %7$sserver requirements%8$s.', 'nice-framework' ), '<a href="#" onclick="event.preventDefault(); jQuery(\'a[href=#demo-important]\').trigger(\'click\');">', '</a>', '<a href="' . esc_url( nice_admin_page_get_link( 'system_status' ) ) . '">', '</a>', '<a href="https://nicethemes.com/article/system-status/" target="_blank">', '</a>', '<a href="https://nicethemes.com/article/server-requirements/" target="_blank">', '</a>' ); ?>
													</p>

												</div>
											</div>

											<div class="columns-2">
												<div id="system-requirements" class="requirements">
													<h4><?php esc_html_e( 'System Requirements', 'nice-framework' ); ?></h4>

													<?php nice_demo_pack_system_requirements( $demo_pack ); ?>
												</div>
											</div>

											<div class="columns-2">
												<div id="theme-requirements" class="requirements">

													<h4><?php esc_html_e( 'Theme Requirements', 'nice-framework' ); ?></h4>

													<ul>
														<li>
															<?php
															if ( $demo_pack->get_theme_version() ) {
																if ( $demo_pack->is_theme_version() ) {
																	$tooltip = '';
																	$icon_class   = 'bi_interface-circle-tick';
																} else {
																	$tooltip = sprintf( esc_html__( '%s is active, but outdated.', 'nice-framework' ), $system_status->get_nice_theme_name() ) . '<br />' . sprintf( esc_html__( 'Its current version is %s.', 'nice-framework' ), $system_status->get_nice_theme_version() ) . '<br />' . esc_html__( 'It must be updated.', 'nice-framework' );
																	$icon_class   = 'bi_interface-circle-cross';
																	$demo_requirements_errors[] = $tooltip;
																}
															} else {
																$tooltip = '';
																$icon_class   = 'bi_interface-circle-tick';

															}
															?>
															<a class="nice-tooltip nice-tooltip-info" title="<?php echo esc_attr( $tooltip ); ?>"><i class="<?php echo esc_attr( $icon_class ); ?>"></i><?php echo esc_attr( $system_status->get_nice_theme_name() ) . ( $demo_pack->get_theme_version() ? ' ' . esc_attr( $demo_pack->get_theme_version() ) : '' ); ?></a>
														</li>

														<li>
															<?php
															if ( $demo_pack->get_framework_version() ) {
																if ( $demo_pack->is_framework_version() ) {
																	$tooltip = '';
																	$icon_class   = 'bi_interface-circle-tick';
																} else {
																	$tooltip = esc_html__( 'Nice Framework is active, but outdated.', 'nice-framework' ) . '<br />' . sprintf( esc_html__( 'Its current version is %s.', 'nice-framework' ), $system_status->get_nice_framework_version() ) . '<br />' . esc_html__( 'It must be updated.', 'nice-framework' );
																	$icon_class   = 'bi_interface-circle-cross';
																	$demo_requirements_errors[] = $tooltip;
																}
															} else {
																$tooltip = '';
																$icon_class   = 'bi_interface-circle-tick';
															}
															?>
															<a class="nice-tooltip nice-tooltip-info" title="<?php echo esc_attr( $tooltip ); ?>"><i class="<?php echo esc_attr( $icon_class ); ?>"></i> <?php echo esc_attr__( 'Nice Framework', 'nice-framework' ) . ( $demo_pack->get_framework_version() ? ' ' . esc_attr( $demo_pack->get_framework_version() ) : '' ); ?></a>
														</li>
													</ul>

												</div>

												<?php if ( $demo_pack->have_plugins() ) : ?>

													<div id="plugin-requirements" class="requirements">

														<h4><?php esc_html_e( 'Plugin Requirements', 'nice-framework' ); ?></h4>

														<?php nice_demo_pack_plugin_requirements( $demo_pack ); ?>

													</div>

												<?php endif; ?>
											</div>
										</div>

									</div><!-- /#demo-requirements -->

									<div id="demo-important">
										<h2><?php printf( esc_html__( 'Before you install "%s", please read:', 'nice-framework' ), esc_attr( $demo_pack->get_name() ) ); ?></h2>

										<p><?php esc_html_e( 'By installing the demo pack you will be agreeing that you have read and understood these notes:', 'nice-framework' ); ?></p>

										<div class="grid cols-2">

											<div class="columns-2">

												<h3><?php esc_html_e( 'Avoid importing in Live Sites', 'nice-framework' ); ?> <span class="nice-badge warning"><?php esc_html_e( 'Recommended', 'nice-framework' ); ?></span></h3><p>
												<?php printf( esc_html__( 'Because of the nature of this process, %1$swe strongly advise you against using this feature in live sites%2$s. Its main purpose is to provide a quick start when creating new ones, and %1$snot all hosting services offer the necessary requirements to use the demo importer out the box%2$s.', 'nice-framework' ), '<strong>', '</strong>' ); ?></p>

											</div>

											<div class="columns-2">

												<h3><?php esc_html_e( 'New content will be added', 'nice-framework' ); ?></h3>
												<p><?php printf( esc_html__( 'Installing a demo %1$sadds new content%2$s to your site. This means that the new content will end up %1$smixed with any content you may have now%2$s (unless you choose to remove it first by checking the box in the sidebar).', 'nice-framework' ), '<strong>', '</strong>' ); ?></p>

											</div>

											<div class="columns-2">

												<h3><?php esc_html_e( 'Do you want to remove content?', 'nice-framework' ); ?></h3>
												<p><?php printf( esc_html__( 'If you choose to %1$sremove the current content%2$s, your current pages, posts, categories, tags, widgets and plugin-related data %1$swill be erased%2$s. Choose this option only if you want to make a fresh start.', 'nice-framework' ), '<strong>', '</strong>' ); ?></p>

											</div>

											<div class="columns-2">

												<h3><?php esc_html_e( 'Back up your site', 'nice-framework' ); ?></h3>
												<p><?php printf( esc_html__( 'The current state of the site %1$swill not be backed up%2$s. This means that %1$sthis process can\'t be automatically undone%2$s; if you may want to restore the site as it is now, %1$syou should perform a manual backup%2$s before proceeding.', 'nice-framework' ), '<strong>', '</strong>' ); ?></p>

											</div>

											<div class="columns-2">

												<h3><?php esc_html_e( 'Stay online', 'nice-framework' ); ?></h3>
												<p><?php printf( esc_html__( 'Some of the contents to import (mainly images) are hosted in the cloud. You\'ll need to be %1$sconnected to the internet%2$s to complete the import.', 'nice-framework' ), '<strong>', '</strong>' ); ?></p>

											</div>

											<div class="columns-2">

												<h3><?php esc_html_e( 'Be patient', 'nice-framework' ); ?></h3>
												<p><?php printf( esc_html__( 'Importing content for an entire site %1$scan take minutes%2$s, so grab a cup of coffee and have some patience while the contents are being installed.', 'nice-framework' ), '<strong>', '</strong>' ); ?></p>

											</div>

										</div>
									</div>

								</div>

							</div>

							<div class="demo-thickbox-sidebar">
								<h1><?php esc_html_e( 'Before you continue', 'nice-framework' ); ?></h1>

								<?php if ( $demo_pack_has_errors ) : ?>
									<div class="nice-notice error">
										<?php printf( esc_html__( 'The demo installer will probably fail with your current setup. Please check the %1$sRequirements%2$s tab.', 'nice-framework' ), '<a href="#" onclick="event.preventDefault(); jQuery(\'a[href=#demo-requirements]\').trigger(\'click\');">', '</a>' ); ?>
									</div>
								<?php endif; ?>

								<?php if ( $demo_pack->is_fully_installed() ) : ?>
									<h3><?php esc_html_e( 'Please bear in mind', 'nice-framework' ); ?></h3>
									<p><?php printf( esc_html__( 'This demo %1$sis already installed%2$s. Nothing will actually be done unless you enable the option to %1$sremove the current content%2$s.', 'nice-framework' ), '<strong>', '</strong>' ); ?></p>
								<?php elseif ( $demo_pack->is_partially_installed() ) : ?>
									<h3><?php esc_html_e( 'Something went wrong last time.', 'nice-framework' ); ?></h3>
									<p><?php printf( esc_html__( 'This demo %1$sis installed, but incomplete%2$s. It\'s not fully functional. If this is not intentional, consider resetting it by enabling the option to %1$sremove the current content%2$s.', 'nice-framework' ), '<strong>', '</strong>' ); ?></p>
								<?php endif; ?>

								<?php if ( $demo_pack->can_install() && ! $demo_pack->should_install() ) : ?>
									<?php // @NOTE: Some requirements are not met, can be installed anyway. (If the requirements not met are plugins then the installer will take care) ?>
								<?php endif; ?>

								<?php if ( $demo_pack->should_install() ) : ?>
									<?php if ( $demo_pack->is_fully_installed() ) : ?>
										<p><strong><?php esc_html_e( 'This demo is already installed.', 'nice-framework' ); ?></strong></p>
									<?php elseif ( $demo_pack->is_partially_installed() ) : ?>
										<p>
											<strong><?php esc_html_e( 'This demo is installed, but not fully functional.', 'nice-framework' ); ?></strong><br />
											<?php esc_html_e( 'If this is not intentional, you should:', 'nice-framework' ); ?>
											<ol>
												<li><?php esc_html_e( 'Reinstall it removing all current content.', 'nice-framework' ); ?></li>
											</ol>
										</p>
									<?php elseif ( ! $demo_pack_has_exceptions ) : ?>
										<div class="nice-notice info"><p><?php esc_html_e( 'Everything is set and you are good to go!', 'nice-framework' ); ?></p><p><?php esc_html_e( 'Go ahead and try this demo by clicking the "Install" button!', 'nice-framework' ); ?></div>
									<?php endif; ?>

								<?php elseif ( $demo_pack->can_install() ) : ?>
									<?php if ( $demo_pack->is_fully_installed() ) : ?>
										<p>
											<strong><?php esc_html_e( 'This demo is installed, but not fully functional.', 'nice-framework' ); ?></strong><br />
											<?php esc_html_e( 'If this is not intentional, you should:', 'nice-framework' ); ?>
											<ol>
												<li><a href="<?php echo esc_url( $plugin_updates_page ); ?>"><?php esc_html_e( 'Install, update and activate all needed plugins.', 'nice-framework' ); ?></a></li>
											</ol>
										</p>
									<?php elseif ( $demo_pack->is_partially_installed() ) : ?>
										<h3><?php esc_html_e( 'Something went wrong.', 'nice-framework' ); ?></h3>
										<p>
											<strong><?php esc_html_e( 'This demo is installed, but not fully functional.', 'nice-framework' ); ?></strong><br />
											<?php esc_html_e( 'If this is not intentional, you should:', 'nice-framework' ); ?>
											<ol>
												<?php if ( $demo_pack->have_active_outdated_plugins() ) : ?>
												<li><a href="<?php echo esc_url( $plugin_updates_page ); ?>"><?php esc_html_e( 'Install, update and activate all needed plugins.', 'nice-framework' ); ?></a></li>
												<?php endif; ?>
												<li><?php esc_html_e( 'Reinstall it removing all current content.', 'nice-framework' ); ?></li>
											</ol>
										</p>
									<?php else : ?>
										<!-- There are some plugins that need to be installed and active -->
									<?php endif; ?>

								<?php else : ?>

									<?php if ( $demo_pack->is_fully_installed() ) : ?>
										<h3><?php esc_html_e( 'Something went wrong.', 'nice-framework' ); ?></h3>
										<p>
											<strong><?php esc_html_e( 'This demo is installed, but probably malfunctioning.', 'nice-framework' ); ?></strong><br />
											<?php esc_html_e( 'To fix this, you should:', 'nice-framework' ); ?>
											<ol>
												<?php if ( $demo_pack->theme_actions_required() ) : ?>
													<li><a href="<?php echo esc_url( $theme_updates_page ); ?>"><?php esc_html_e( 'Update both theme and framework.', 'nice-framework' ); ?></a></li>
												<?php endif; ?>

												<?php if ( $demo_pack->have_active_outdated_plugins() ) : ?>
												<li><a href="<?php echo esc_url( $plugin_updates_page ); ?>"><?php esc_html_e( 'Install, update and activate all needed plugins.', 'nice-framework' ); ?></a></li>
												<?php endif; ?>
											</ol>
										</p>
									<?php elseif ( $demo_pack->is_partially_installed() ) : ?>
										<h3><?php esc_html_e( 'Something went wrong.', 'nice-framework' ); ?></h3>
										<p>
											<strong><?php esc_html_e( 'This demo is installed, but probably malfunctioning.', 'nice-framework' ); ?></strong><br />
											<?php esc_html_e( 'To fix this, you should:', 'nice-framework' ); ?>
											<ol>
												<?php if ( $demo_pack->theme_actions_required() ) : ?>
													<li><a href="<?php echo esc_url( $theme_updates_page ); ?>"><?php esc_html_e( 'Update both theme and framework.', 'nice-framework' ); ?></a></li>
												<?php endif; ?>
												<?php if ( $demo_pack->have_active_outdated_plugins() ) : ?>
													<li><a href="<?php echo esc_url( $plugin_updates_page ); ?>"><?php esc_html_e( 'Install, update and activate all needed plugins.', 'nice-framework' ); ?></a></li>
												<?php endif; ?>
												<li><?php esc_html_e( 'Reinstall it removing all current content.', 'nice-framework' ); ?></li>
											</ol>
										</p>
									<?php else : ?>
										<h3><?php esc_html_e( "You're almost good to go!", 'nice-framework' ); ?></h3>
											<p><?php esc_html_e( 'You need to take some action before installing this demo, because some requirements are not met. So, please:', 'nice-framework' ); ?></p>
											<ol>
												<?php if ( $demo_pack->theme_actions_required() ) : ?>
													<li><a href="<?php echo esc_url( $theme_updates_page ); ?>"><?php esc_html_e( 'Update both theme and framework.', 'nice-framework' ); ?></a></li>
												<?php endif; ?>
												<?php if ( $demo_pack->have_active_outdated_plugins() ) : ?>
													<li><a href="<?php echo esc_url( $plugin_updates_page ); ?>"><?php esc_html_e( 'Install, update and activate all needed plugins.', 'nice-framework' ); ?></a></li>
												<?php endif; ?>
											</ol>
											<p><?php esc_html_e( 'Please check the requirements tab to see what you are failing at.', 'nice-framework' ); ?></p>
									<?php endif; ?>

								<?php endif; ?>

								<?php if ( $demo_pack->can_install() ) : ?>

									<div class="nice-toggle" data-id="closed">
										<h4 class="nice-toggle-title"><?php esc_html_e( 'Remove current website content?', 'nice-framework' ); ?></h4>
										<div class="nice-toggle-inner">
											<p><?php esc_html_e( 'You can remove the content from this site before installing the demo. You will lose all the current site contents (pages, posts, etc)', 'nice-framework' ); ?></p>
											<p><input id="checkbox-reset-<?php echo esc_attr( $demo_pack->get_slug() ); ?>" class="nice-checkbox checkbox-reset" name="checkbox-reset-<?php echo esc_attr( $demo_pack->get_slug() ); ?>" value="1" type="checkbox"><label for="checkbox-reset-<?php echo esc_attr( $demo_pack->get_slug() ); ?>"> <?php esc_html_e( 'Yes, I want to remove all the content of this site and I take the responsibility for it.', 'nice-framework' ); ?></label></p>
										</div>
									</div>

								<?php endif; ?>

							</div>

							<div class="demo-thickbox-toolbar item-actions">

								<div class="toolbar-info">

								</div>

								<div class="toolbar-actions">

									<a href="#" data-demo-slug="<?php echo esc_attr( $demo_pack->get_slug() ); ?>" data-demo-exceptions="<?php echo intval( $demo_pack_exceptions ); ?>" class="button button-primary button-install <?php echo $demo_pack->can_install() ? 'button-call-warning' : ''; ?>" <?php echo ! $demo_pack->can_install() ? 'disabled="disabled"' : ''; ?> title="<?php printf( ( $demo_pack->is_installed() ? esc_attr__( 'Reinstall %s', 'nice-framework' ) : esc_attr__( 'Install %s', 'nice-framework' ) ), esc_attr( $demo_pack->get_name() ) ); ?>"><?php echo $demo_pack->is_installed() ? esc_html__( 'Reinstall', 'nice-framework' ) : esc_html__( 'Install', 'nice-framework' ); ?></a>

									<?php if ( $demo_pack->get_preview_url() ) : ?>
										<a href="<?php echo esc_url( $demo_pack->get_preview_url() ); ?>" target="_blank" class="button button-secondary button-preview" title="<?php printf( esc_attr__( 'Preview %s', 'nice-framework' ), esc_attr( $demo_pack->get_name() ) ); ?>"><?php esc_html_e( 'Preview', 'nice-framework' ); ?></a>
									<?php endif; ?>

									<a href="#" class="button button-secondary button-cancel" title="<?php esc_attr_e( 'Cancel and return to the Demo Installer', 'nice-framework' ); ?>"><?php esc_html_e( 'Cancel', 'nice-framework' ); ?></a>

								</div>

							</div>

						</div>
					</div>

				</div>

			<?php endforeach; ?>

		</div>

		<?php
			$demo_packs_output = ob_get_contents();
			ob_end_clean();

			$filters = array(
				'tag'     => array(
					'icon'  => '<i class="dashicons dashicons-tag"></i>',
					'title' => esc_html__( 'Tag', 'nice-framework' ),
					'terms' => $tags,
					'sort'  => true,
				),
				'color'   => array(
					'icon'  => '<i class="dashicons dashicons-admin-appearance"></i>',
					'title' => esc_html__( 'Color', 'nice-framework' ),
					'terms' => $colors,
					'sort'  => true,
				),
				'feature' => array(
					'icon'  => '<i class="dashicons dashicons-admin-plugins"></i>',
					'title' => esc_html__( 'Feature', 'nice-framework' ),
					'terms' => $features,
					'sort'  => true,
				),
			);

			ob_start();
		?>

		<?php foreach ( $filters as $filter_key => $filter_data ) : ?>

			<?php if ( ! empty( $filter_data['terms'] ) ) : ?>

				<?php
					if ( $filter_data['sort'] ) {
						ksort( $filter_data['terms'] );
					}
				?>

				<div class="nice-admin-filter">

					<a class="sort-items filter-title"><?php echo $filter_data['icon']; ?> <span><?php echo esc_html( $filter_data['title'] ); ?></span></a>

					<ul class="option-set" data-filter-group="<?php echo esc_attr( $filter_key ); ?>">

						<li><a class="filter active" data-filter-value="" href="#filter-<?php echo esc_attr( $filter_key ); ?>-all"><?php esc_html_e( 'All', 'nice-framework' ); ?></a></li>

						<?php foreach ( $filter_data['terms'] as $term_key => $term_name ) : ?>
							<li><a class="filter" data-filter-value=".<?php echo esc_attr( $filter_key ); ?>-<?php echo esc_attr( $term_key ); ?>" href="#filter-<?php echo esc_attr( $filter_key ); ?>-<?php echo esc_attr( $term_key ); ?>"><?php echo esc_html( $term_name ); ?></a></li>
						<?php endforeach; ?>

					</ul>

				</div>

			<?php endif; ?>

		<?php endforeach; ?>

		<?php
			$filters_output = trim( ob_get_contents() );
			ob_end_clean();

			if ( ! empty( $filters_output ) ) {
				echo '<div class="nice-item-filters">';
				echo '<div class="nice-item-filters-description"><i class="dashicons dashicons-controls-play"></i>' . sprintf( esc_html__( 'Learn how easy is to install a demo content pack %1$shere%2$s or %3$swatching the step-by-step video%4$s.', 'nice-framework' ), sprintf( '<a href="%s" target="_blank">', 'https://nicethemes.com/article/importing-demo-content/' ), '</a>', sprintf( '<a class="fancybox fancybox.iframe" href="%s">', esc_url( $nice_video_embed_url_install_demos ) ), '</a>' ) . '</div>' ;
				echo $filters_output;
				echo '</div>';
			}
		?>

		<div class="isotope-empty" style="display: none;">
			<p>
				<?php esc_html_e( 'No demo packs match your selection.', 'nice-framework' ); ?>
				<br />
				<?php esc_html_e( 'Try a different combination.', 'nice-framework' ); ?>
			</p>
		</div>

		<?php echo $demo_packs_output; ?>

		<div class="nice-notice clearfix">

			<h4><?php esc_html_e( 'Please Read', 'nice-framework' ); ?></h4>

			<p><?php esc_html_e( "Before installing any demo pack, you must understand the implications of the process. By clicking the \"Install\" button YOU CONFIRM that you have read the \"Requirements\" and \"Important Notes\" contents, and take full responsibility for the outcome of the process.", 'nice-framework' ); ?>
			<p><?php esc_html_e( 'This implies importing contents such as pages, posts, theme options, widgets, sidebars and other settings. It will replicate what you see in the live demo. The importing process will replace your current theme options and widgets. It can also take some minutes to complete.', 'nice-framework' ); ?></p>
			<p><?php esc_html_e( 'The process will not remove any of your site contents unless you choose to do so.', 'nice-framework' ); ?></p>

		</div>

		<div class="nice-notice">

			<h4><?php esc_html_e( 'Requirements', 'nice-framework' ); ?></h4>

			<p><?php printf( esc_html__( "DEMO IMPORT REQUIREMENTS:\n\n- Memory Limit of %s, and max execution time (php time limit) of %s seconds.\n\n- Theme, Framework and Plugin requirements listed in the \"Requirements\" tab.", 'nice-framework' ), $system_status->get_formatted_recommended_wp_memory_limit(), $system_status->get_recommended_php_time_limit() ); ?>
			</p>

		</div>

		<div class="nice-notice">
			<p><?php printf( esc_html__( '%1$sIMPORTANT:%2$s Some of the %3$sincluded plugins%4$s need to be installed and activated before you %1$sinstall a demo%2$s. Please check the %5$s"System Status"%6$s page to ensure your server meets all requirements for a successful import.', 'nice-framework' ), '<strong>', '</strong>', sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'plugins' ) ) ), '</a>', sprintf( '<a href="%s">', esc_url( nice_admin_page_get_link( 'system_status' ) ) ), '</a>' ); ?></p>
		</div>

	<?php else : ?>

		<p class="about-description">
			<?php printf( esc_html__( "Can't load any demo pack for %s. Please check your internet connection.", 'nice-framework' ), esc_attr( $system_status->get_nice_theme_name() ) ); ?>
		</p>

	<?php endif; ?>

</div>
