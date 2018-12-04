/**
 * Nice Framework by NiceThemes.
 *
 * This module contains functionality for admin notices.
 *
 * @since   2.0
 *
 * @package NiceFramework
 */
var NiceAdminNotices = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	/**
	 * Make an AJAX call when dismissing TGMPA notice.
	 */
	var dismissTGMPA = function() {
		var notice = $( '#setting-error-tgmpa.is-dismissible' );

		var nice_ajax_settings = {
			action: 'nice_tgmpa_dismiss_notice',
			url:    noticesData.adminAjaxURL,
			nonce:  noticesData.playNiceNonce
		};

		notice.on('click', '.notice-dismiss', function ( event ) {
			event.preventDefault();

			$.post( noticesData.adminAjaxURL, nice_ajax_settings );
		});

		notice.on('click', '.nice-notice-dismiss', function ( event ) {
			notice.fadeTo( 100, 0, function() {
				notice.slideUp( 100, function() {
					notice.remove();
				});
			});

			$.post( noticesData.adminAjaxURL, nice_ajax_settings );
		});
	},

	/**
	 * Fire events on document ready.
	 *
	 * @since 2.0
	 */
	ready = function() {
		dismissTGMPA();
	};

	// Expose the ready function to the world.
	return {
		ready: ready
	};

} )( jQuery );

jQuery( NiceAdminNotices.ready );
