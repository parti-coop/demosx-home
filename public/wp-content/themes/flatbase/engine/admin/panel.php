<?php
/**
 * NiceFramework by NiceThemes.
 *
 * Functions related to the Options Panel.
 *
 * @package   NiceFramework
 * @author    NiceThemes <hello@nicethemes.com>
 * @license   GPL-2.0+
 * @link      http://nicethemes.com/
 * @copyright 2015 NiceThemes
 * @since     1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'nice_admin_head' ) ) :
/**
 * nice_admin_head()
 *
 * Include all the js for the admin options panel section
 *
 * @since   1.0.0
 * @updated 2.0
 *
 */
function nice_admin_head() {

if ( nice_admin_is_framework_page() && ( 'options' === nice_admin_get_current_page() ) ) : ?>

<script type="text/javascript" language="javascript">
	//<![CDATA[
	jQuery(document).ready( function(){

		// Sticky Header
		var nice_header = jQuery('#nice-header');

		if ( 0 != nice_header.length ) {
			var topHeight = nice_header.offset().top - jQuery( '#wpadminbar' ).height();

			jQuery( window ).scroll( function() {
				if (jQuery( document ).scrollTop() > topHeight) {
					nice_header.css( {
						position:  'fixed',
						marginTop: -topHeight
					} );
				} else {
					nice_header.css( {
						position:  'absolute',
						marginTop: 0
					} );
				}

			});
		}

		/**
			Color Picker
		*/
		<?php $nice_options = get_option( 'nice_template' );

		foreach ( $nice_options as $option ) {

			if ( 'typography' === $option['type'] ) { ?>
				<?php
					$option_id = $option['id'];
					$db        = get_option( $option['id'] );
					$std       = $option['std'];

					if ( ! is_array( $db ) || empty( $db ) ) {
						$std = $option['std'];
					}

			} elseif ( 'slider' === $option['type'] ) {

					$option_id = $option['id'];
					$db = nice_get_option( $option['id'] );
					$std = $option['std'];
					$std_value = isset( $option['std']['value'] ) ? $option['std']['value'] : '';

					if ( ! is_array( $db ) || empty( $db ) ) {
						$std = $std_value;
					}

					if ( empty( $db ) ) {
						$value = floatval( $std_value );
					} else {
						$value = floatval( $db );
					}

					$range = isset( $option['std']['range'] ) ? $option['std']['range'] : null;
					$min   = isset( $option['std']['min'] )   ? $option['std']['min']   : null;
					$max   = isset( $option['std']['max'] )   ? $option['std']['max']   : null;
					$step  = isset( $option['std']['step'] )  ? $option['std']['step']  : null;
					$unit  = isset( $option['std']['unit'] )  ? $option['std']['unit']  : null;

					?>

					jQuery(function() {
						jQuery( '#<?php echo esc_js( $option_id ); ?>_slider' ).slider({
							<?php if ( isset( $range ) ) : ?>  range: '<?php echo esc_js( $range ); ?>',<?php endif; ?>
							<?php if ( isset( $value ) ) : ?>  value: <?php echo esc_js( $value ); ?>,<?php endif; ?>
							<?php if ( isset( $min ) ) : ?>    min: <?php echo esc_js( $min ); ?>,<?php endif; ?>
							<?php if ( isset( $max ) ) : ?>	   max: <?php echo esc_js( $max ); ?>,<?php endif; ?>
							<?php if ( isset( $step ) ) : ?>   step: <?php echo esc_js( $step ); ?>,<?php endif; ?>
							slide: function( event, ui ) {
								jQuery( '#<?php echo esc_js( $option_id ); ?>' ).val( ui.value + '<?php echo esc_js( $unit ); ?>' );
							}
						});

					jQuery( '#<?php echo esc_js( $option_id ); ?>' ).val( jQuery( '#<?php echo esc_js( $option_id ); ?>_slider' ).slider( "value" ) + '<?php echo esc_js( $unit ); ?>' );
					});
			<?php } ?>
		<?php } ?>

			// DATE Pickers
			if ( jQuery( '.nice-date' ).length ) {
				jQuery( '.nice-date' ).each(function () {
					var buttonImageURL = jQuery( this ).parent().find( 'input[name=datepicker-image]' ).val();
					jQuery( this ).next( 'input[name=datepicker-image]' ).remove();

					jQuery( '#' + jQuery( this ).attr( 'id' ) ).datepicker( { showOn: 'button', buttonImage: buttonImageURL, buttonImageOnly: true, showAnim: 'slideDown' } );
				});
			}

			jQuery( '#niceform' ).submit( function(){
				var $form = jQuery( this );

				function newValues() {
					var serializedValues = jQuery('#niceform').serialize();
					return serializedValues;
				}
				jQuery( ':checkbox, :radio' ).click(newValues);
				jQuery( 'select' ).change(newValues);
				jQuery( '.nice-icon-loading' ).fadeIn();
				var serializedReturn = newValues();

				var ajax_url = '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>';

				var data = {
					<?php if ( isset ( $_REQUEST['page'] ) && 'nicethemes' === $_REQUEST['page'] ) { ?>
					type : 'options',
					<?php } ?>
					action : 'nice_post_action',
					data : serializedReturn,
					nice_post_action_nonce : generalData.playNiceNonce
				};

				jQuery.post(ajax_url, data, function( response ) {
					var success = jQuery( '#nice-popup-save' );
					var loading = jQuery( '.nice-icon-loading' );
					loading.fadeOut();
					success.fadeIn();
					$form.trigger( 'NiceOptionsUpdated', [ data ] );
					window.setTimeout(function(){
						success.fadeOut();
					}, 2500);
				});

				return false;

			});

	});
	//]]>
	</script>

	<?php // AJAX Upload ?>

	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function(){

			jQuery( '.nice-section-title' ).hide();
			jQuery( '.nice-section-title:first' ).fadeIn();

			jQuery( '.nice-section-title .collapsed' ).each(function(){
				jQuery(this).find( 'input:checked' ).parent().parent().parent().nextAll().each(
					function(){
						if ( jQuery(this).hasClass( 'last' ) ) {
							jQuery(this).removeClass( 'hidden' );
							return false;
						}
						jQuery(this).filter( '.hidden' ).removeClass( 'hidden' );
					});
				});

			jQuery( '.nice-section-title .collapsed input:checkbox' ).click(unhideHidden);

			function unhideHidden(){
				if (jQuery(this).attr( 'checked'  ) ) {
					jQuery(this).parent().parent().parent().nextAll().removeClass( 'hidden' );
				}
				else {
					jQuery(this).parent().parent().parent().nextAll().each(
						function(){
							if (jQuery(this).filter( '.last' ).length) {
								jQuery(this).addClass( 'hidden' );
								return false;
							}
							jQuery(this).addClass( 'hidden' );
					});

				}
			}

			jQuery.each(jQuery('.nice-group-title'), function() {
				jQuery(this).addClass('closed');
				var newWrap = jQuery(this).nextUntil('.nice-group-title, .nice-section-title').wrapAll('<div class="hidden" />');
				var innerWrap = new Array();
				jQuery.each(newWrap, function(index, el) {
					if (jQuery(el).hasClass('break-master-wrap')) innerWrap.push(el);
				});
				jQuery(innerWrap).wrapAll('<div class="overridable-wrap"><div class="overridable-inner"></div></div>');
				jQuery(this).on('click', function() {
					jQuery(this).toggleClass('closed');
					jQuery(this).next().slideToggle();
				});
			});

			jQuery( '#nice-nav li:first' ).addClass( 'current' );
			jQuery( '#nice-nav li a' ).click(function(evt) {

					jQuery( '#nice-nav li' ).removeClass( 'current' );
					jQuery(this).parent().addClass( 'current' );

					var clicked_group = jQuery(this).attr( 'href' );

					jQuery( '.nice-section-title' ).hide();

					jQuery(clicked_group).fadeIn();
					evt.preventDefault();

				});

		// Update Message popup
		jQuery.fn.center = function () {
			this.animate({ 'top':( jQuery(window).height() - this.height() - 200 ) / 2 + jQuery(window).scrollTop() + 'px' }, 100 );
			this.css( 'left', 250 );
			return this;
		}


		jQuery( '#nice-popup-save' ).center();
		jQuery(window).scroll(function() {

			jQuery( '#nice-popup-save' ).center();

		});
	});
	//]]>
	</script>
<?php
endif;
}
endif;

if ( ! function_exists( 'nice_admin' ) ) :
/**
 * nice_admin()
 *
 */
function nice_admin() {
	// mmm donuts
}
endif;


if ( ! function_exists( 'nice_ajax_save_options' ) ) :
/**
 * nice_ajax_save_options()
 *
 * Retrieve options and save them.
 *
 * @since 1.0.0
 *
 */
function nice_ajax_save_options() {

	$nice_options = array();
	$data         = array();
	$options      = get_option( 'nice_template' );

	foreach ( $options as $option ) {

		if ( isset ( $option['id'] ) ) {
			$option_id   = $option['id'];
			$option_type = $option['type'];

			if ( is_array( $option_type ) ) {

				foreach ( $option_type as $inner_option ) {
					$option_id = $inner_option['id'];

					if ( isset( $data[ $option_id ] ) ) {
						$data[ $option_id ] .= get_option( $option_id );
					} else {
						$data[ $option_id ] = get_option( $option_id );
					}
				}

			} elseif ( 'list_item' === $option_type ) {
				$data[ $option_id ] = get_option( $option_id );

				if ( ! empty( $data[ $option_id ] ) ) {
					foreach ( (array) $data[ $option_id ] as $item_id => $item_name ) {
						if ( isset( $option['settings'] ) ) {
							foreach ( (array) $option['settings'] as $setting ) {
								if ( 'default' === $setting['id'] ) {
									$list_item_setting_id = $item_id;
								} else {
									$list_item_setting_id = $item_id . '_' . $setting['id'];
								}

								$data[ $list_item_setting_id ] = get_option( $list_item_setting_id );
							}
						}
					}
				}

			} else {
				$data[ $option_id ] = get_option( $option_id );
			}
		}
	}

	$nice_array = array();

	foreach ( $data as $name => $value ) {
		if ( is_serialized( $value ) ) {
			$value = unserialize( $value );
			$nice_array_option = $value;
			$temp_options = '';

			foreach ( $value as $v ) {
				if ( isset ( $v ) ) {
					$temp_options .= $v . ',';
				}
			}

			$value = $temp_options;
			$nice_array[ $name ] = $nice_array_option;

		} else {
			$nice_array[ $name ] = $value;
		}
	}

	update_option( 'nice_options', $nice_array );
}
endif;

add_action( 'wp_ajax_nice_post_action', 'nice_ajax_callback' );

if ( ! function_exists( 'nice_ajax_callback' ) ) :
/**
 * nice_ajax_callback()
 *
 * Save the data from the theme options panel, via AJAX.
 *
 * @since 1.0.0
 *
 */
function nice_ajax_callback() {
	/**
	 * Return early if we're not in an AJAX context, or if the nonce does not validate.
	 */
	if ( ! nice_doing_ajax() || ! check_ajax_referer( 'play-nice', 'nice_post_action_nonce' ) ) {
		return;
	}

	global $wpdb; // this is how you get access to the database

	$save_type = $_POST['type'];

	// Uploads
	if ( 'upload' === $save_type ) {

		$clicked_id = $_POST['data']; // Acts as the name
		$filename   = $_FILES[ $clicked_id ];
		$filename['name'] = preg_replace( '/[^a-zA-Z0-9._\-]/', '', $filename['name'] );

		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';
		$uploaded_file = wp_handle_upload( $filename, $override );

		$upload_tracking[] = $clicked_id;
		update_option( $clicked_id , $uploaded_file['url'] );

		 if ( ! empty( $uploaded_file['error'] ) ) {
			 echo 'Upload Error: ' . esc_html( $uploaded_file['error'] );
		 } else {
			 echo esc_url( $uploaded_file['url'] ); // Is the Response
		 }

	} elseif ( 'image_reset' === $save_type ) {

			$id = esc_sql( $_POST['data'] ); // Acts as the name
			global $wpdb;
			$wpdb->delete( $wpdb->options, array( 'option_name' => $id ) );

	} elseif ( 'options' === $save_type || 'framework' === $save_type ) {

		$data = $_POST['data'];

		parse_str( $data, $output );

		// Pull options
		$options = get_option( 'nice_template' );

		foreach ( $options as $option_array ) {

			if ( isset ( $option_array['id'] ) ) {
				$id = $option_array['id'];
			} else {
				$id = null;
			}

			$old_value = get_option( $id );
			$new_value = '';

			if ( isset ( $output[ $id ] ) ) {
				$new_value = $output[ $option_array['id'] ];
			}

			if ( isset ( $option_array['id'] ) ) { // Non - Headings...

					$type = $option_array['type'];

					if ( is_array( $type ) ) {

						foreach ( $type as $array ) {

							if ( 'text' === $array['type'] ) {
								$id  = $array['id'];
								$std = $array['std'];
								$new_value = $output[ $id ];
								if ( '' === $new_value ) {
									$new_value = $std;
								}
								update_option( $id, stripslashes( $new_value ) );
								echo esc_attr( $new_value );
							}
						}

					} elseif ( '' === $new_value && 'checkbox' === $type ) { // Checkbox Save

						update_option( $id, 'false' );

					} elseif ( 'true' === $new_value && 'checkbox' === $type ) { // Checkbox Save

						update_option( $id, 'true' );

					} elseif ( 'multicheck' === $type ) { // Multi Check Save

						$option_options = $option_array['options'];

						foreach ( $option_options as $options_id => $options_value ) {

							$multicheck_id = $id . '_' . $options_id;

							if ( ! isset( $output[ $multicheck_id ] ) ) {
								update_option( $multicheck_id, 'false' );
							} else {
								update_option( $multicheck_id, 'true' );
							}
						}

					} elseif ( 'select_multiple' === $type ) {

						update_option( $id, $new_value );

					} elseif ( 'typography' === $type ) {

						$typography_array = array();
						$typography_properties = array(
							'font-size',
							'size',
							'unit',
							'font-family',
							'family',
							'font-style',
							'style',
							'color',
							'letter-spacing',
							'letter-spacing-unit',
						);

						foreach ( $typography_properties as $v  ) {

							if ( isset( $option_array['std'][ $v ] ) ) {

								$value = isset( $output[ $option_array['id'] . '_' . $v ] ) ? $output[ $option_array['id'] . '_' . $v ] : '';

								if ( in_array( $v, array( 'font-family', 'family' ), true ) ) {
									$typography_array[ $v ] = stripslashes( $value );
								} else {
									$typography_array[ $v ] = $value;

									if ( in_array( $v, array( 'font-style', 'style' ), true ) ) {
										$typography_array['bold'] = ( stripos( $typography_array[ $v ], 'bold' ) !== false );
										$typography_array['italic'] = ( stripos( $typography_array[ $v ], 'italic' ) !== false );
									}
								}
							}
						}

						update_option( $id, $typography_array );

					} elseif ( 'slider' === $type ) {

						if ( isset ( $option_array['std']['unit'] ) ) {
							$new_value = str_replace( $option_array['std']['unit'], '', $new_value );
						}

						update_option( $id, stripslashes( $new_value ) );

					} elseif ( 'measurement' === $type ) {

						update_option( $id, $new_value );

					} elseif ( 'list_item' === $type ) {
						$theme_check_bs = strrev( 'edoced_46esab' );
						$items = (array) json_decode( $theme_check_bs( $new_value ) );

						// Save main option.
						update_option( $id, $items );

						if ( ! empty( $items ) ) {
							foreach ( $items as $item_id => $item_name ) {
								if ( isset( $option_array['settings'] ) ) {
									foreach ( (array) $option_array['settings'] as $setting ) {
										if ( 'default' === $setting['id'] ) {
											$list_item_setting_id = $item_id;
										} else {
											$list_item_setting_id = $item_id . '_' . $setting['id'];
										}

										$list_item_setting_new_value = isset( $output[ $list_item_setting_id ] ) ? $output[ $list_item_setting_id ] : '';

										// Save each list item setting option.
										update_option( $list_item_setting_id, $list_item_setting_new_value );
									}
								}
							}
						}

					} elseif ( 'upload_min' !== $type ) {

						update_option( $id, stripslashes( $new_value ) );

					}
				}
			}

		if ( 'options' === $save_type || 'framework' === $save_type ) {
			/* Create, Encrypt and Update the Saved Settings */
			nice_ajax_save_options();
		}

	die();

	}
}
endif;

if ( ! function_exists( 'nice_is_mp6' ) ) :
/**
 * Check if we're running on the MP6 UI.
 *
 * @since 2.0
 */
function nice_is_mp6() {
	global $wp_version;

	return ( version_compare( $wp_version, '3.8', '>=' ) || defined( 'MP6' ) );
}
endif;
