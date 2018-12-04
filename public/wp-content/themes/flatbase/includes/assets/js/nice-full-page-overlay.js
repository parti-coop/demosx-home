/**
 * Flatbase by NiceThemes.
 *
 * NiceFullPageOverlay
 *
 * Setup full page overlay if needed.
 *
 * @since   2.0
 * @package Flatbase
 */
var NiceFullPageOverlay = ( function( $ ) {
	'use strict';

	/**
	 * Define if the overlay should be used.
	 *
	 * @type {boolean}
	 */
	var useOverlay = false,

	/**
	 * Save the HTML object for the overlay component.
	 */
	$overlay = null,

	/**
	 * Set a flag to know if the NiceFullPageOverlay object has been triggered.
	 *
	 * @type {boolean}
	 */
	fired = false,

	/**
	 * Show overlay.
	 */
	show = function() {
		if ( ! useOverlay ) {
			return;
		}

		$overlay.css( 'display', 'flex' ).removeClass( 'nice-page-loader-hidden' );
	},

	/**
	 * Hide overlay.
	 */
	hide = function() {
		if ( ! useOverlay ) {
			return;
		}

		$overlay.addClass( 'nice-page-loader-hidden' );

		setTimeout( function() {
			$( 'body' ).trigger( 'overlayHidden' );
		}, 2000 );
	},

	/**
	 * Show loader when clicking certain links.
	 *
	 * @since 1.0.0
	 */
	setupLinks = function() {
		var $links = $( 'a[href*="' + window.generalData.homeURL + '"]:not([href*="' + window.generalData.adminURL + '"]):not([target="_blank"]):not([href^="#"]):not([href*="#comments"]):not([href^="mailto:"]):not(.comment-edit-link):not(.comment-reply-link):not(.comments-link):not(#cancel-comment-reply-link):not(.comment-reply-link):not([data-target]):not(.toggle-nav):not([href*="action="]):not(.add_to_cart_button):not([rel^="prettyPhoto"]):not(.pretty_photo):not(.fancybox):not([data-fancybox])' );

		$links.each( function() {
			if ( ! $( this ).data( 'saved' ) ) {
				var $link = $( this ),
					$body = $( 'body' );

				$( this ).data( 'saved', true );

				$( this ).click( function( e ) {
					// Avoid firing the loader if an icon is clicked.
					if ( $( e.target ).is( 'i' ) ) {
						return;
					}

					// Return early if alt, ctrl, meta or shift keys are pressed.
					if ( e.altKey || e.ctrlKey || e.metaKey || e.shiftKey ) {
						return;
					}

					// Manage redirection and setup hiding overlay in Safari.
					if ( $body.is( '.safari' ) ) {
						e.preventDefault();

						setTimeout( function() {
							hide();
							$overlay.css( 'display', 'none' );
							window.location = $link.attr( 'href' );
						}, 500 );
					}

					// Display overlay.
					show();

					// Return early for Safari.
					if ( $body.is( '.safari' ) ) {
						return;
					}

					$( window ).bind( 'unload', function() {
						// Hide overlay after three seconds to prevent it from hiding the page content
						// when coming back from history.
						setTimeout( function() {
							hide();
						}, 3000 );
					} );
				} );
			}
		} );
	},

	/**
	 * Fire object functionality.
	 */
	ready = function() {
		if ( fired ) {
			return;
		}

		$( document ).ready( function () {
			useOverlay = ( 'full-page' === $( 'body' ).data( 'page-loader' ) );
			$overlay   = useOverlay ? $( '.nice-page-loader.nice-page-loader-full' ) : null;

			if ( ! useOverlay ) {
				return;
			}

			Pace.on( 'done', hide );

			Pace.on( 'done', setupLinks );
		} );

		fired = true;
	};

	return {
		ready: ready,
		show: show,
		hide: hide
	};
} )( jQuery );

NiceFullPageOverlay.ready();