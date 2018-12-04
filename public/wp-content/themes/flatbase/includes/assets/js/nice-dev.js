/**
 * NiceDev.
 *
 * Development and debug helper.
 *
 * @since   2.0
 * @package Flatbase
 */
var NiceDev = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	/**
	 * Check if the module has been initialized.
	 *
	 * @type {boolean}
	 */
	var fired = false,

	/**
	 * Extend jQuery functionality.
	 *
	 * @since 1.0.0
	 */
	extendLib = function() {
		/**
		 * NiceDev
		 *
		 * Development and debugging helper.
		 *
		 * @since 1.0.0
		 */
		// Setup development tool.
		$.NiceDev = {
			log: function( message ) {
				if ( window.generalData.devMode ) {
					window.console.log( '[NiceDevLog]', message );
				}
			}
		};
	},

	/**
	 * Initialize helpers
	 *
	 * @since 1.0.0
	 */
	init = function() {
		if ( fired ) {
			return;
		}

		extendLib();

		fired = true;
	};

	// Expose the ready function to the world.
	return {
		init: init
	};

} )( jQuery );

NiceDev.init();
