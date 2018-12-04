/**
 * Nice Framework by NiceThemes.
 *
 * This module contains functionality for the item browser used by the Demo and Plugin installers.
 *
 * @since   2.0
 *
 * @package NiceFramework
 */

var NiceItemBrowser = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	/**
	 * Process data.
	 *
	 * @since 2.0
	 */
	var initItemBrowser = function() {

		var $container = jQuery( '.nice-install-items .nice-item-browser.isotope' ),
			filters    = {};

		$container.imagesLoaded( function() {
			$container.isotope( {
				itemSelector : '.isotope-item',
				isOriginLeft : ( 'rtl' !== $( 'html' ).attr( 'dir' ) )
			} );
		} );

		jQuery( '.nice-admin-filter a' ).click( function() {
			var $this = jQuery( this );
			if ( $this.hasClass( 'selected' ) ) {
				return;
			}

			var $optionSet = $this.parents( '.option-set' );

			$optionSet.find( '.selected' ).removeClass( 'selected' );
			$this.addClass( 'selected' );

			var group = $optionSet.attr( 'data-filter-group' );

			filters[ group ] = $this.attr( 'data-filter-value' );

			var isoFilters = [];

			for ( var prop in filters ) {
				isoFilters.push( filters[ prop ] )
			}

			var selector = isoFilters.join( '' );

			$container.isotope( {
				filter: selector
			} );

			if ( ! $container.data( 'isotope' ).filteredItems.length ) {
				jQuery( '.nice-install-items .isotope-empty' ).fadeIn( 'slow' );
			} else {
				jQuery( '.nice-install-items .isotope-empty' ).fadeOut( 'fast' );
			}

			return false;
		} );

	},

	/**
	 * Fire events on document ready.
	 *
	 * @since 2.0
	 */
	ready = function() {
		initItemBrowser();
	};

	// Expose the ready function to the world.
	return {
		ready: ready
	};

} )( jQuery );

jQuery( NiceItemBrowser.ready );
