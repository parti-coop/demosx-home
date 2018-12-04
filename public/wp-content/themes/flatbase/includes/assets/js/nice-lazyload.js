/**
 * Flatbase by NiceThemes.
 *
 * NiceLazyLoad
 *
 * LazyLoad implementation for images.
 *
 * @since   2.0
 * @package Flatbase
 */
var NiceLazyLoad = ( function( $ ) {
	'use strict';

	/**
	 * Check if the module has already been fired.
	 *
	 * @type {boolean}
	 */
	var fired = false,

	/**
	 * Setup LazyLoad for images requesting it.
	 */
	setup = function() {
		var url         = $.NiceLazyScript( 'lazyload' ),
			$lazyImages = $( 'img[data-original]:not([data-lazyload]), img[data-original-set]:not([data-lazyload])' );

		if ( ! $lazyImages.length ) {
			return;
		}

		// Apply actual height to images based on their aspect ratio.
		$lazyImages.each( function( i, obj ) {
			var $obj = $( obj ),
				width = $obj.attr( 'width' ),
				height = $obj.attr( 'height' ),
				newHeight = height * ( $obj.width() / width );

			$obj.attr( 'data-lazyload', true );

			// Ignore images inside grid cells - they already have their height set.
			if ( ! $obj.parents( '.item-caption' ).length || $obj.parents( '.item-height-auto' ).length ) {
				$obj.height( newHeight );
			}
		} );

		if ( url ) {
			$.getScript( url, loadImages );
		} else {
			loadImages();
		}
	},

	/**
	 * Load images.
	 */
	loadImages = function() {
		var _load = function() {
			if ( 'function' !== typeof window.LazyLoad ) {
				$.NiceDev.log( 'Warning: LazyLoad is not initialized.' );

				return;
			}

			var ll = new window.LazyLoad( {
				elements_selector: 'img[data-original], img[data-original-set]',
				// Restore automatic height to images once tey have been loaded.
				callback_load : function( el ) {
					$( el ).height( '' );
				}
			} );

			// Update when Pace is done after DOM changes.
			$( document ).on( 'NiceDOMChanged', function() { ll.update(); } );
		};

		// Wait for LazyLoad library to be loaded.
		if ( 'undefined' === typeof window.LazyLoad ) {
			$( document ).ready( _load );
		} else {
			_load();
		}
	},

	/**
	 * Initialize LazyLoad functionality.
	 */
	init = function() {
		if ( fired ) {
			$.NiceDev.log( 'Warning: This module cannot be initialized more than once.' );

			return;
		}

		$( setup );

		$( document ).on( 'NiceDOMChanged', setup );

		fired = true;
	};

	return {
		init: init
	};
} )( jQuery );

NiceLazyLoad.init();
