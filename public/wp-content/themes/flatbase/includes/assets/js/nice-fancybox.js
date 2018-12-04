/**
 * Flatbase by NiceThemes.
 *
 * Manage Fancybox implementation.
 *
 * @since   2.0
 * @package Flatbase
 */
var NiceFancybox = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	var fired = false,

	/**
	 * Fire Fancybox.
	 *
	 * @since 1.0.0
	 */
	handle = function() {
		var $fancyboxes = $( '.fancybox, [data-fancybox]' ),
			url = $.NiceLazyScript( 'fancybox' );

		if ( ! $fancyboxes.length ) {
			return;
		}

		if ( typeof $.fn.fancybox !== 'function' && url ) {
			$.getScript( url, setup );
		} else if ( typeof $.fn.fancybox === 'function' ) {
			setup();
		} else if ( typeof $.fn.fancybox !== 'function') {
			$.NiceDev.log( 'Warning: Fancybox is not initialized.' );
		}
	},

	setup = function() {
		$( document ).trigger( 'NiceFancyboxSetup' );

		$( '.fancybox, [data-fancybox]' ).fancybox();

	},

	/**
	 * Fire events on document ready, and bind other events.
	 *
	 * @since 1.0.0
	 */
	init = function() {
		if ( ! fired ) {
			$( handle );
			$( document ).on( 'NiceFancyboxSetup', function() {
                $.fancybox.defaults.hash = false;
            } );
			fired = true;
		}
	};

	// Expose the ready function to the world.
	return {
		init: init
	};

} )( jQuery );

NiceFancybox.init();
