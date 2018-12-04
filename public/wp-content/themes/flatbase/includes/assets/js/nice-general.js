/* global script variables */
var $parallex_effect = true;
var window_width = 0;

jQuery(document).ready( function($) {
	isMobile = (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) ? true : false;

	/* Handles functionality that depends on window resize */
	innerWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	window_width = Math.max( jQuery( window ).width(), innerWidth );

	jQuery( window ).resize(function() {
		adjust_nav_functionality( true );
	});

	/*
		Livesearch label
	*/

	jQuery('body').bind( 'DOMSubtreeModified', function(){
		NiceFixLiveSearchWidth();
	});

	jQuery('#live-search input[type="text"]').each( function() {

		if ( this.value != '' ) {
			jQuery('label[for=' + jQuery(this).attr('name') + ']').addClass('has-text');
		}

		jQuery(this).focus( function() {
			if ( this.value == '' ) {
				jQuery('label[for=' + jQuery(this).attr('name') + ']').addClass('focus');
			}
		});

		jQuery(this).keyup( function() {

			if ( this.value != '') {
				jQuery('label[for=' + jQuery(this).attr('name') + ']').addClass('has-text');
			} else {
				jQuery('label[for=' + jQuery(this).attr('name') + ']').removeClass('has-text');
				jQuery('label[for=' + jQuery(this).attr('name') + ']').addClass('focus');
			}

		});

		jQuery(this).blur( function() {

			if ( this.value == '' ) {
				jQuery('label[for=' + jQuery(this).attr('name') + ']').removeClass('has-text');
				jQuery('label[for=' + jQuery(this).attr('name') + ']').removeClass('focus');
			}
		});
	});

	/*-----------------------------------------------------------------------------------*/
	/* FAQs
	/*-----------------------------------------------------------------------------------*/

	jQuery( '.faq .faq-title' ).click( function(e) {
		e.preventDefault();
		jQuery(this).toggleClass("active").parent().next().slideToggle();
		console.log(jQuery(this).next());
		return false;
	});

	jQuery('button').click(function(e) {

		if ( jQuery(this).hasClass('grid') ) {
			jQuery('#content .row .column').removeClass('list').addClass('grid');
		}
		else if( jQuery(this).hasClass('list') ) {
			jQuery('#content .row .column').removeClass('grid').addClass('list');
		}
	});

	/* miscellaneous */

	var $is_backtotop_displayed = false;
	var $scroll_position = 0;

	if ( jQuery.browser.msie || jQuery.browser.mozilla){ Screen = jQuery("html"); }
	else { Screen = jQuery("body"); }

	jQuery(window).scroll( function() {

		$scroll_position = jQuery(this).scrollTop();


		if ( window_width > 1024 && ( jQuery('body').hasClass('home') || jQuery('body').hasClass('page-template-template-home') ) ) {
			var scrollPos = jQuery(window).scrollTop();
			if ( jQuery("#header").outerHeight() > 0 ) {
				var nice_height = ( jQuery("#live-search").outerHeight() + jQuery("#header #top").outerHeight() ) - $scroll_position / 6;
				jQuery("#header").css('height', nice_height + 'px');
			} else {
				jQuery("#header").css('height', nice_height + 'px');
			}

			$ratio = $scroll_position / ( jQuery( '.welcome-message' ).outerHeight() + 200);
			$welcome_message_opacity = 1 - $ratio;
			if ( $welcome_message_opacity <= 1 && $welcome_message_opacity >= 0 ) {
				jQuery( '.welcome-message' ).css( 'opacity', $welcome_message_opacity );
			}
		}

	});

});

jQuery(window).load( function($) {

	/* Handles responsive menu */
	jQuery("#header #top #toggle-nav").click(function() {
		jQuery("#header #navigation").slideToggle( 'slow' );
		return false;
	});

	jQuery('#navigation .nav li .sub-menu').siblings('.down-arrow').addClass('active-down-arrow');
	adjust_nav_functionality( false );
	jQuery("#header #navigation li .active-down-arrow").click(function() {
		jQuery(this).siblings(".sub-menu").toggle();
		return false;
	});

});

jQuery(window).bind( 'load',   NiceFixLiveSearchWidth );
jQuery(window).bind( 'resize', NiceFixLiveSearchWidth );

function NiceFixLiveSearchWidth() {
	jQuery('#search-result').width(jQuery('#searchform .input').outerWidth());
}

var last_width = 0;
/**
 *  Adjusts functionality of header navigation
 */
function adjust_nav_functionality( resizing ) {

	var current_window_width = jQuery( window ).width();

	if ( isMobile && resizing && ( window_width === current_window_width ) ) {
		return;
	}

	window_width = current_window_width;

	if ( ( ( ! isMobile && window_width > 1024 ) || ( isMobile && window_width > 1366 ) ) ) {
		//jQuery('#header #main-nav').superfish();
		jQuery('#navigation li .down-arrow-anchor .down-arrow, .active-down-arrow').hide();
		jQuery('#header #navigation').show();
	} else if ( ( window_width <= 1024 && last_width !== window_width ) || ( isMobile && window_width <= 1366 ) && last_width !== 1366 ) {
		//jQuery('#header #main-nav').superfish('destroy');
		jQuery('#navigation li .down-arrow-anchor .down-arrow, .active-down-arrow').show();
		jQuery('#header #navigation').hide();
	}

	last_width = window_width;

}

/* Handles like functionality */
function nicethemes_likes_handler() {

	jQuery('.nice-like').click( function() {

		var $button = jQuery(this);
		if ( ! $button.hasClass( 'liked' ) ) {

			jQuery.ajax({
				type: "POST",
				url: generalData.adminAjaxURL,
				data: 'action=nicethemes_likes_add&nonce=' + generalData.playNiceNonce + '&id=' + $button.data('id'),
				success: function( likes ) {
					if ( likes != '' ){

						$button.addClass( 'liked' ); // set liked class
						$button.find('.like-count').html( likes ); // print number of likes

					}
				}
			});

		}

		return false;

	});
}

/**
 * Flatbase by NiceThemes.
 *
 * NiceGeneral
 *
 * General functionality for the theme.
 *
 * @since   1.0.0
 * @package Flatbase
 */
var NiceGeneral = ( function( $ ) {
	'use strict';

	/**
	 * Check if this module has already been loaded.
	 *
	 * @type {boolean}
	 */
	var fired = false,

    /**
     * Breakpoint (in px) for responsive behavior.
     *
     * @type {number}
     */
    breakpoint = 1024,

    /**
	 * Handle "back to top" functionality.
	 *
	 * @since 1.0.0
	 */
	backToTop = function() {
		var isBacktotopDisplayed = false,
			scrollPosition       = 0,
			breakpoint           = 100,
			$backButton          = null,

		/**
	     * Handle displaying and hiding "back to top" button.
	     *
	     * @since 1.0.0
		 */
		_buttonDisplay = function() {
			scrollPosition = $( window ).scrollTop();

			if ( scrollPosition < breakpoint && isBacktotopDisplayed ) {
				$backButton.animate( { bottom: '-48px', opacity: 0 }, 100 );

				isBacktotopDisplayed = false;
			} else if ( scrollPosition > breakpoint && ! isBacktotopDisplayed ) {
				$backButton.animate( { bottom: '17px', opacity: 1 }, 100 );
				isBacktotopDisplayed = true;
			}
		};

		// Setup displaying the button.
		$( document ).ready( function() {
			$backButton = $( '#back-to-top, .backtotop' );
			$( window ).scroll( $.NiceThrottle( 250, _buttonDisplay ) );
		} );
	},

	/**
     * Handle Local scroll.
     *
     * @since 1.0.0
     */
    setupScroll = function() {
	    var $header = null,
		    $links  = null,
		    url     = null,

	    _cleanupLinks = function() {
		    $links = $links.not('[href="#"]:not([data-target])');
	    },

        _setup = function() {
	        $links.each( function( i, link ) {
	        	$( link ).click( _scroll );
	        } );
        },

		/**
	     * Obtain gap (in px) produced by WP Admin bar.
	     *
	     * @since   1.0.0
	     *
		 * @returns {int}
	     *
	     * @private
	     */
		_getAdminBarGap = function() {
			var $adminBar = $( '#wpadminbar' );

			if ( $adminBar.length && 'fixed' === $adminBar.css( 'position' ) ) {
				return $adminBar.outerHeight();
			}

			return 0;
		},

		/**
	     * Obtain gap (in px) produced by fixed header.
	     *
	     * @since   1.0.0
	     *
		 * @returns {int}
	     *
	     * @private
	     */
		_getHeaderGap = function() {
			var headerFixed            = $header.hasClass( 'fixed' ) && ! $header.hasClass( 'vertical' ) && ( 'fixed' === $header.css( 'position' ) ),
				headerGap              = _getAdminBarGap(),
				headerShrinkable       = headerFixed && $header.hasClass( 'shrinkable' ),
				$logo                  = $( '#logo' ),
				headerShrinkableHeight = 0;

			if ( headerShrinkable && ! $header.hasClass( 'shrunk' ) && $( window ).width() >= breakpoint ) {
				headerShrinkableHeight = ( parseInt( $logo.css( 'padding-top' ) ) + parseInt( $logo.css( 'padding-bottom' ) ) ) / 2;
				headerShrinkableHeight += $logo.hasClass( 'has-image' ) ? $logo.height() * 0.9 : $logo.height();
			} else if ( headerShrinkable ) {
				headerShrinkableHeight = $header.outerHeight();
				headerShrinkableHeight -= $logo.hasClass( 'has-image' ) ? $logo.height() : 0;
				headerShrinkableHeight += $logo.hasClass( 'has-image' ) ? $logo.height() * 0.9 : 0;
			}

			headerGap += headerShrinkable ? headerShrinkableHeight : ( headerFixed ? $header.outerHeight() : 0 );

			return headerGap;
		},

		/**
	     * Scroll to local target.
	     *
	     * @since   1.0.0
	     *
		 * @param   {event} e
	     *
	     * @private
	     */
		_scroll = function( e ) {
			e.preventDefault();
			e.stopPropagation();

		    var target = $( this ).data( 'target' ) || $( this ).attr( 'href' );

			if ( ! $( target ).length ) {
				return;
			}

			var targetPosition = $( target ).offset().top - _getHeaderGap();

			if ( targetPosition < 0 ) {
				targetPosition = 0;
			}

			$( window ).scrollTo( targetPosition, 300 );
		};

		$( document ).ready( function() {
			$header = $( '#header' );
			$links  = $( 'body' ).find( 'a[data-target], #header a[href^="#"], #top-bar a[href^="#"]' );
			url     = $.NiceLazyScript( 'scrollTo' );

			_cleanupLinks();

			// Return early if we don't have any targets.
			if ( ! $links.length ) {
				return;
			}

			if ( typeof $.fn.scrollTo !== 'function' && url ) {
				$.getScript( url, _setup );
			} else if ( typeof $.fn.scrollTo === 'function' ) {
				_setup();
			} else if ( typeof $.fn.scrollTo !== 'function' ) {
				$.NiceDev.log( 'Warning: LocalScroll is not initialized.' );
			}
		} );
    },

    /**
     * Load required scripts on demand.
     */
    loadScripts = function() {
	    /**
	     * Load Nice Tabs Widget script if required.
	     *
	     * @private
	     */
        var _loadTabsWidgetMaybe = function() {
	        var url = $.NiceLazyScript( 'tabsWidget' ),
		        $tabs = $( 'ul.nice-tabs' );

	        if ( ! $tabs.length ) {
		        return;
	        }

	        if ( 'object' !== typeof window.NiceTabsWidget && url ) {
		        $.getScript( url );
	        }
        };

	    // Load Nice Tabs Widget script if required.
        $( document ).ready( _loadTabsWidgetMaybe );
    },

	/**
	 * Restore user agent class to deal with possible caching issues.
	 *
	 * @since 1.0.0
	 */
	setUserAgent = function() {
    	var supportedUserAgents = [
    		'lynx',
	        'gecko',
	        'opera',
	        'ns4',
	        'safari',
	        'chrome',
	        'ie'
	    ],
	        currentUSerAgent = 'unknown';

    	if ( navigator.userAgent.match( /(Lynx)/ ) ) {
    		currentUSerAgent = 'lynx';
	    } else if ( /Opera\//.test( navigator.userAgent ) ) {
    		currentUSerAgent = 'opera';
	    } else if ( navigator.userAgent.match( /(Mozilla\/4)/ ) ) {
    		currentUSerAgent = 'ns4';
	    } else if ( /gecko\/\d/i.test( navigator.userAgent ) ) {
		    currentUSerAgent = 'gecko';
	    } else if ( /Apple Computer/.test( navigator.vendor ) ) {
    		currentUSerAgent = 'safari';
	    } else if ( /Chrome\//.test( navigator.userAgent ) ) {
    		currentUSerAgent = 'chrome';
	    } else if ( /MSIE \d/.test( navigator.userAgent ) || /Trident\/(?:[7-9]|\d{2,})\..*rv:(\d+)/.exec( navigator.userAgent ) ) {
    		currentUSerAgent = 'ie';
	    }

	    $( document ).ready( function() {
		    $( 'body' ).removeClass( supportedUserAgents.join( ' ' ) ).addClass( currentUSerAgent );
	    } );
	},

	/**
	 * Fire events on document ready, and bind other events.
	 *
	 * @since 1.0.0
	 */
	init = function() {
		if ( fired ) {
			return;
		}

		setupScroll();
		backToTop();
		setUserAgent();
		loadScripts();
		fired = true;
	};

	// Expose the ready function to the world.
	return {
		init: init
	};

} )( jQuery );

NiceGeneral.init();