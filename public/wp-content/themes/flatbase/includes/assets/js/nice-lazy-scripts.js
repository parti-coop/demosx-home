/**
 * Flatbase by NiceThemes.
 *
 * NiceLazyScripts
 *
 * Obtain the URL of a JS file to be loaded on demand.
 *
 * @since   2.0
 * @package Flatbase
 *
 * @param   {string} scriptName
 * @returns {string}
 */
var NiceLazyScripts = ( function( $ ) {
	'use strict';

	/**
	 * Check if the library has been fired for the first time.
	 *
	 * @type {boolean}
	 */
	var fired = false,

	/**
	 * List of scripts that can be loaded on demand.
	 *
	 * @type {{}}
	 */
	lazyScripts = {},

	/**
	 * Setup lazy scripts.
	 */
	setup = function() {
		lazyScripts = window.generalData.lazyScripts;
		$.ajaxSetup( { cache : window.generalData.AjaxCache } );
	},

	/**
	 * Extend jQuery functionality.
	 */
	extendLib = function() {
		$.NiceLazyScript = function( scriptName ) {
			if ( typeof lazyScripts !== 'undefined' && typeof lazyScripts[ scriptName ] !== 'undefined' ) {
				return lazyScripts[ scriptName ];
			}

			return null;
		};
	},

	/**
	 * Initialize lazy scripts.
	 */
	init = function() {
		if ( fired ) {
			return;
		}

		setup();
		extendLib();

		fired = true;
	};

	return {
		init: init
	};
} )( jQuery );

NiceLazyScripts.init();
