/**
 * Flatbase by NiceThemes.
 *
 * Trigger events when the window size switches between desktop and mobile sizes depending
 * on the breakpoint value.
 *
 * @since 2.0
 *
 * @package Flatbase
 */
var NiceMobileEvents = ( function( $ ) {
	'use strict';

	/**
	 * Window width breakpoint for mobile viewports.
	 *
	 * @type {number}
	 */
	var breakpoint = 1024,

	/**
	 * Check if the module has already been initialized.
	 *
	 * @type {boolean}
	 */
	fired = false,

	/**
	 * Trigger events.
	 *
	 * @private
	 */
	setTriggers = function() {
		// Switch from desktop to mobile.
		if ( $( window ).width() < breakpoint && 'desktop' === $( window ).data( 'viewport-type' ) ) {
			$( window ).data( 'viewport-type', 'mobile' );
			$( window ).trigger( 'desktop2mobile' );

		// Switch from mobile to desktop.
		} else if ( $( window ).width() >= breakpoint && 'mobile' === $( window ).data( 'viewport-type' ) ) {
			$( window ).data( 'viewport-type', 'desktop' );
			$( window ).trigger( 'mobile2desktop' );
		}
	},

	/**
	 * Show and hide navigation menu when clicking on toggle button.
	 *
	 * @since 1.0.0
	 */
	toggleMobileNavigation = function() {
		var $header,
			$trigger,
		    $navigation,
			$navigationList,

	    /**
	     * Fire toggle functionality for a given trigger.
	     *
	     * @since   1.0.0
	     * @param   event
	     * @private
	     */
	    _toggle = function( event ) {
		    event.preventDefault();
		    event.stopPropagation();

		    // Return early for large viewports.
		    if ( $( window ).width() >= breakpoint ) {
			    return;
		    }

		    // Toggle navigation visibility.
		    if ( ! $header.hasClass( 'navigation-closing' ) ) {
			    if ( ! $navigation.hasClass( 'toggled' ) ) {
				    $navigation.addClass( 'toggled' );
			    } else {
				    $navigation.removeClass( 'toggled' );
			    }
		    }

		    // Setup animations.
		    if ( $navigation.hasClass( 'toggled' ) ) {
			    $navigationList.addClass( 'slideInDown' ).removeClass( 'slideOutUp' );
			    $navigation.addClass( 'visible' );
		    } else {
			    $header.addClass( 'navigation-closing' );
			    $navigationList.removeClass( 'slideInDown' ).addClass( 'slideOutUp' );

			    return setTimeout( function() {
				    $header.removeClass( 'navigation-closing' );
				    $navigation.removeClass( 'visible' );

				    // Call helper functions.
				    _expand();
				    _animate();
			    }, 1000 );
		    }

		    // Call helper functions.
		    _expand();
		    _animate();
	    },

        /**
	     * Add helper class to header depending on navigation visibility.
	     *
	     * @since   1.0.0
	     * @private
	     */
		_expand = function() {
			if ( $navigation.is( ':visible' ) && $( window ).width() < breakpoint ) {
				$header.addClass( 'expanded' );
			} else {
				$header.removeClass( 'expanded' );
			}
        },

        /**
         * Animate navigation list.
         *
         * @since   1.0.0
         * @private
         */
		_animate = function() {
			if ( $navigation.is( ':visible' ) && $( window ).width() < breakpoint ) {
				$navigationList.addClass( 'animated' );
			} else {
				$navigationList.removeClass( 'animated' );
			}
		};

		$( document ).ready( function() {
			// Return early if we have an overlay menu.
			if ( $( 'body' ).hasClass( 'has-overlay-menu' ) ) {
				return;
			}

			$header = $( '#header' );
			$trigger = $( '#toggle-nav-container' ).find( '.toggle-nav' );
			$navigation = $( $trigger.data( 'focus' ) );
			$navigationList = $navigation.find( 'ul:first' );

			// Setup click event.
			$( $trigger ).click( _toggle );

			// Setup resizing event.
			$( window ).resize( _expand );
			$( window ).resize( _animate );
		} );
	},

	/**
     * Move top bar navigation inside main navigation in mobile sizes.
     *
     * @since 1.0.0
     */
    moveTopBarNavigation = function() {
    	$( document ).ready( function() {
		    var $topNav      = $( '#top-nav' ),
			    $mainNav     = $( '#main-nav' ),
			    $topNavItems = $topNav.find( '> .menu-item' );

		    if ( ! $topNav || ! $topNavItems ) {
			    return;
		    }

		    $( 'body' )
			    .bind( 'NiceSuperfishDestroy', function() {
				    $mainNav.append( $topNavItems );
			    } )
			    .bind( 'NiceSuperfishCreate', function() {
				    $topNav.append( $topNavItems );
			    } );
	    } );
    },

	/**
	 * Initialize mobile functionality.
	 */
	init = function() {
		if ( fired ) {
			return;
		}

		// Set desktop as the default viewport type.
		$( window ).data( 'viewport-type', 'desktop' );

		// Try to fire triggers for the first time.
		setTriggers();

		// Setup triggers to be fired on window resize.
		$( window ).resize( setTriggers );

		// Setup toggling mobile navigation.
		toggleMobileNavigation();

		// Setup relocating top bar navigation.
		moveTopBarNavigation();

		fired = true;
	};

	return {
		init: init
	};
} )( jQuery );

NiceMobileEvents.init();
