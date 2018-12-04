/**
 * Flatbase by NiceThemes.
 *
 * Manage Superfish-related functionality.
 *
 * @since   2.0
 * @package Flatbase
 */
var NiceSuperfish = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	var superfishApplied   = false,
	    superfishDestroyed = false,
	    downArrowsBound    = false,
		breakpoint         = 1024,
		navigation         = null,

	/**
	 * Initialize Superfish functionality.
	 *
	 * @since 1.0.0
	 */
	init = function() {
		$( document ).ready( function() {
			navigation = $( '#navigation' );

			// Return early if we're using the overlay menu.
			if ( $( 'body' ).hasClass( 'has-overlay-menu' ) ) {
				return;
			}

			// Return early if navigation area is not available.
			if ( ! navigation.length ) {
				return;
			}

			handleNavigation();
			handleTriggers();
		} );
	},

	/**
	 * Fire Superfish.
	 *
	 * @since 1.0.0
	 */
	handleNavigation = function() {
		handleNavigationFunctionality();

		$( window ).resize( function() {
			handleNavigationFunctionality();
		} );
	},

	/**
	 * Handle internal functionality of navigation.
	 *
	 * @since 1.0.0
	 */
	handleNavigationFunctionality = function() {
		/**
		 * Force superfishDestroyed to false if we have a large viewport.
		 */
		if ( ( $( window ).width() >= breakpoint ) && superfishDestroyed ) {
			superfishDestroyed = false;
		}

		if ( ( $( window ).width() < breakpoint ) && ! superfishDestroyed ) {
			navigation.superfish( 'destroy' );
			navigation.find( '.menu-item-has-children > a, #navigation .menu-item-has-children .megamenu-column > a' ).append( '<span class="down-arrow"><i class="fa fa-angle-down"></i></span>' );

			superfishApplied = false;
			superfishDestroyed = true;

			$( 'body' ).trigger( 'NiceSuperfishDestroy' );

			if ( ! downArrowsBound ) {
				$( '.down-arrow' ).bind( 'click', function( e ) {
					e.preventDefault();
					$( this ).parent().siblings( '.sub-menu' ).toggle();
					if ( $( this ).find( '.fa' ).hasClass( 'fa-rotate-180' ) ) {
						$( this ).find( '.fa' ).removeClass( 'fa-rotate-180' );
					} else {
						$( this ).find( '.fa' ).addClass( 'fa-rotate-180' );
					}

				} );
			}

		} else if ( ! superfishApplied && ! superfishDestroyed ) {
			applySuperfish();

			$( '.down-arrow' ).remove();
			superfishApplied = true;

			$( 'body' ).trigger( 'NiceSuperfishCreate' );
		}

		/**
		 * Apply correct `display` property to .megamenu sub menus.
		 */
		$( 'li.megamenu .sub-menu' ).on( 'show', function() {
			$( this ).css( 'display', 'table' );
		} );
	},

	/**
	 * Apply Superfish.
	 *
	 * @since 1.0.0
	 */
	applySuperfish = function() {
		navigation.superfish( {
			speed: 'normal',
			hoverClass: 'hover',
			onShow: function() {
				/**
				 * Hook into Superfish options to apply a different delay for
				 * megamenu items.
				 */
				var sfOptions = navigation.data( 'sf-options' ),
				    delay     = $( this ).parent().hasClass( 'megamenu' ) ? 800 : 600;

				navigation.data( 'sf-options', $.extend( sfOptions, { delay: delay } ) );
			}
		} );

		superfishApplied = true;
	},

	/**
	 * Allow show and hide events to be hooked to on() function.
	 *
	 * @since 1.0
	 */
	handleTriggers = function () {
		$.each( [ 'show', 'hide' ], function ( index, event ) {
			var el = $.fn[ event ];

			$.fn[ event ] = function () {
				this.trigger( event );

				return el.apply( this, arguments );
			};
		} );
	},

	/**
	 * Fire events on document ready, and bind other events.
	 *
	 * @since 1.0.0
	 */
	ready = function() {
		var url = $.NiceLazyScript( 'superfish' );

		if ( typeof $.fn.superfish !== 'function' && url ) {
			$.getScript( url, init );
		} else if ( typeof $.fn.superfish === 'function' ) {
			init();
		} else if ( typeof $.fn.superfish !== 'function' ) {
			$.NiceDev.log( 'Warning: Superfish is not initialized.' );
		}
	};

	// Expose the ready function to the world.
	return {
		ready: ready
	};

} )( jQuery );

jQuery( NiceSuperfish.ready );
