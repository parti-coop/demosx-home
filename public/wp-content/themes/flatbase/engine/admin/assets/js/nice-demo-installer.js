/**
 * Nice Framework by NiceThemes.
 *
 * This module contains functionality for the Demo Installer.
 *
 * @since   2.0
 *
 * @package NiceFramework
 */
var NiceDemoInstaller = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	/**
	 * Process data.
	 *
	 * @since 2.0
	 */
	var initDemoInstaller = function() {

		jQuery.fn.niceTextRotator = function( messages ) {
			var iteration             = 0;
			var random_messages_index = -1;

			var shuffle = function( array ) {
				var currentIndex = array.length, temporaryValue, randomIndex;
				while ( 0 !== currentIndex ) {
					randomIndex = Math.floor( Math.random() * currentIndex );
					currentIndex -= 1;
					temporaryValue = array[ currentIndex ];
					array[ currentIndex ] = array[ randomIndex ];
					array[ randomIndex ] = temporaryValue;
				}
				return array;
			};

			shuffle( messages.secondary );

			return this.each( function() {
				var el = $( this );

				var rotate = function() {
					var message = messages.primary;

					el.fadeOut( 0, function() {
						if ( 0 === ( iteration % 2 ) ) {
							if( ( random_messages_index + 1 ) === messages.secondary.length ) {
								random_messages_index = -1
							}

							message = messages.secondary[ random_messages_index + 1 ];

							random_messages_index++;

							el.addClass( 'random-importing-message' );

						} else {
							el.removeClass( 'random-importing-message' );
						}

						el.text( message ).fadeIn( 'slow' );

						iteration++;
					});
				};

				setInterval( rotate, 4000 );
			});
		};

		jQuery( '.nice-install-demos .button-call-warning' ).click( function() {
			var demo_slug = jQuery( this ).data( 'demo-slug' );

			jQuery( '#TB_closeWindowButton' ).click();

			setTimeout( function() {
				jQuery( '#button-warning-' + demo_slug ).click();
			}, 300);

			return false;
		});

		jQuery( '.nice-install-demos .button-cancel' ).click( function() {
			jQuery( '#TB_closeWindowButton' ).click();

			return false;
		});

		jQuery( '.demo-install .button-install' ).click( function() {
			var button_install = jQuery( this );

			if ( button_install.attr( 'disabled' ) ) {
				return false;
			}

			// Show a warning if the demo has any requirement exceptions.
			if ( button_install.data( 'demo-exceptions' ) ) {
				var installAnyway = confirm( generalData.installAnyway );

				if ( ! installAnyway ) {
					return false;
				}
			}

			var demo_slug  = button_install.data( 'demo-slug' );
			var reset_site = jQuery( '#checkbox-reset-' + demo_slug ).prop( 'checked' );

			var body           = jQuery( 'body' );
			var import_overlay = jQuery( '.nice-demo-import-overlay' );
			var import_window  = jQuery( '.nice-demo-import-window' );
			var import_loader  = jQuery( '#nice-demo-import-loader' );
			var import_results = jQuery( '#nice-demo-import-results' );

			import_loader.removeClass();
			import_results.html( '' );

			body.addClass( 'modal-open' );
			import_overlay.show();
			import_window.show();

			var nice_importing_class   = '';
			var nice_importing_message = generalData.message_processing;

			var nice_import_data = {
				'action'                 : 'nice_theme_import_demo_pack',
				'demo_slug'              : demo_slug,
				'obtain_status'          : true,
				'reset_site'             : reset_site,
				'nice_demo_import_nonce' : generalData.playNiceNonce
			};

			var nice_import_before = function() {
				import_loader.removeClass();
				if ( '' !== nice_importing_class ) {
					import_loader.addClass( nice_importing_class );
				}

				var random_importing_messages = [];
				var importing_messages_html   = nice_importing_message;

				if ( ( 'undefined' !== typeof generalData.random_messages ) && ( 0 < generalData.random_messages.length ) ) {
					random_importing_messages = generalData.random_messages;
					importing_messages_html = '<span class="importing-messages">' + importing_messages_html + '</span>';
				}

				import_results.hide().html( importing_messages_html ).fadeIn( 'slow' );

				if ( 0 < random_importing_messages.length ) {
					jQuery( '.importing-messages' ).niceTextRotator( {
						'primary'   : nice_importing_message,
						'secondary' : random_importing_messages
					} );
				}
			};

			var nice_import_success = function( response ) {
				var info,
					message = '';

				try {
					info = JSON.parse( response );
				} catch ( e ) {
					console.log( 'Couldn\'t parse response as JSON.' );
					console.log( response );
					console.log( e );

					return;
				}

				if ( info.message ) {
					message = info.message;
				} else {
					message = generalData.message_success;
				}

				import_results.hide().html( message ).fadeIn( 'slow' );

				if ( 1 === info.more ) {
					nice_ajax_settings.data.obtain_status = false;
					nice_ajax_settings.data.reset_site    = false;

					if ( '' !== info.next_step ) {
						nice_importing_class = 'demo-step-' + info.next_step;
					} else {
						nice_importing_class = '';
					}

					if ( info.total_posts_number ) {
						nice_ajax_settings.data.total_posts_number = info.total_posts_number;
					}

					if ( info.processed_posts_number ) {
						nice_ajax_settings.data.processed_posts_number = info.processed_posts_number;
					}

					if ( '' !== info.next_step_message ) {
						nice_importing_message = info.next_step_message;
					} else {
						nice_importing_message = generalData.message_processing;
					}

					setTimeout( function() {
						do_ajax( nice_ajax_settings );
					}, 4000 );

				} else {
					if ( 1 === info.error ) {
						import_loader.addClass( 'demo-error' );
					} else {
						import_loader.addClass( 'demo-success' );
					}
				}
			};

			var nice_import_error = function() {
				import_loader.addClass( 'demo-error' );

				import_results.hide().html( generalData.message_error ).fadeIn( 'slow' );
			};

			var nice_ajax_settings = {
				type       : 'POST',
				url        : generalData.adminAjaxURL,
				data       : nice_import_data,
				beforeSend : nice_import_before,
				success    : nice_import_success,
				error      : nice_import_error
			};

			var do_ajax = function( settings ) {
				console.log( settings );
				$.ajax( settings );
			};

			do_ajax( nice_ajax_settings );

			return false;
		} );

		jQuery( document ).ready( function() {
			jQuery('.nice-install-demos .item.current .button-call-details').click();
		} );
	},

	/**
	 * Fire events on document ready.
	 *
	 * @since 2.0
	 */
	ready = function() {
		initDemoInstaller();
	};

	// Expose the ready function to the world.
	return {
		ready: ready
	};

} )( jQuery );

jQuery( NiceDemoInstaller.ready );
