/**
 * Flatbase by NiceThemes.
 *
 * This file manages Isotope integration into Portfolio by NiceThemes.
 *
 * @since   2.0
 * @package Flatbase
 */
var NiceIsotope = (function ( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	/**
	 * Grid objects.
	 *
	 * @since 1.0.0
	 *
	 * @type {jQuery}
	 */
	var $grids,

	/**
	 * Groups of filtering objects, by grid ID.
	 *
	 * @since 1.0.0
	 *
	 * @type {Array}
	 */
	filters = [],

	/**
	 * Generic CSS selector for grid items.
	 *
	 * @since 1.0.0
	 *
	 * @type {string}
	 */
	itemSelector = '.item',

	/**
	 * Breakpoints for responsive behaviour in small viewports.
	 *
	 * These breakpoints should describe the minimum size for each kind of
	 * device.
	 *
	 * IMPORTANT: All values here should match the ones defined in
	 * `sass/variables/_structure.scss`. Otherwise responsive layouts may
	 * break. The `columns` property is the number of columns that a grid
	 * should display once the viewport size is lower that the breakpoint.
	 *
	 * @since 1.0.0
	 *
	 * @type {*[]}
	 */
	breakpoints = [ {
			name:    'desktop',
			size:    1024,
			columns: 2
		}, {
			name:    'mobile-lg',
			size:    425,
			columns: 1
		}
	],

	/**
	 * Currently active breakpoint.
	 *
	 * @since 1.0.0
	 *
	 * @type {undefined|object}
	 */
	currentBreakpoint,

	/**
	 * Setup active breakpoint based on window resizing.
	 *
	 * @since 1.0.0
	 */
	watchBreakpoints = function() {
		 breakpointWatcher();
		$( window ).resize( breakpointWatcher );
	},

	/**
	 * Find and set current breakpoint based on window size.
	 *
	 * @since 1.0.0
	 */
	breakpointWatcher = function() {
		var matchedBreakpoint;

		for ( var i = 0; i < breakpoints.length; i++ ) {
			if ( windowWidth() < breakpoints[ i ].size ) {
				matchedBreakpoint = breakpoints[ i ];
			}
		}

		currentBreakpoint = matchedBreakpoint;
	},

	/**
	 * Obtain list of filters by grid.
	 *
	 * @returns {Array}
	 */
	getFilters = function () {
		var filtersList = [],
			filterContainers = $.makeArray( $( '.isotope-filter' ) ),
			/**
			 * Check if a grid needs to be paginated and has more than one page.
			 *
			 * @since 1.0.0
			 *
			 * @param   {jQuery}  $grid
			 *
			 * @returns {boolean}
			 */
			isPaged = function ( $grid ) {
				var hasPages = $grid.data( 'max-pages' ) && $grid.data( 'max-pages' ) > 1,
					hasPagination = $grid.data( 'pagination' ) && 'disabled' !== $grid.data( 'pagination' );

				return ( hasPages && hasPagination );
			};

		$grids.each( function ( i ) {
			// Prevent Isotope filters from fire when using pagination.
			if ( isPaged( $( this ) ) ) {
				return;
			}

			filtersList[ i ] = $( filterContainers[ i ] ).find( 'a.filter' );
		} );

		return filtersList;
	},

	/**
	 * Set list of filters for the current page.
	 *
	 * @since 1.0.0
	 */
	setFilters = function () {
		filters = getFilters();
	},

	/**
	 * Obtain current window width.
	 *
	 * @since   1.0.0
	 *
	 * @returns {int}
	 */
	windowWidth = function () {
		return $( window ).width();
	},

	/**
	 * Obtain the gutter of a grid.
	 *
	 * @since 1.0.0
	 *
	 * @param  {jQuery}  $grid
	 *
	 * @return {int}
	 */
	getGridGutter = function ( $grid ) {
		var gutter = 1,
			baseGutter = 30;

		if ( $grid.hasClass( 'no-gap-h' ) || $grid.hasClass( 'no-gap' ) ) {
			gutter = 0;
		} else if ( $grid.hasClass( 'px-gutter-h' ) || $grid.hasClass( 'px-gutter' ) ) {
			gutter = 1;
		} else if ( $grid.hasClass( 'half-gutter-h' ) || $grid.hasClass( 'half-gutter' ) ) {
			gutter = baseGutter / 2;
		} else if ( $grid.hasClass( 'single-gutter-h' ) || $grid.hasClass( 'single-gutter' ) ) {
			gutter = baseGutter;
		} else if ( $grid.hasClass( 'double-gutter-h' ) || $grid.hasClass( 'double-gutter' ) ) {
			gutter = baseGutter * 2;
		}

		return gutter;
	},

	/**
	 * Fire Isotope when needed.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery} $grid Grid to apply Isotope.
	 */
	initIsotope = function ( $grid ) {
		// Define layout mode.
		var layoutMode = $( $grid ).data( 'layout-mode' ) ? $( $grid ).data( 'layout-mode' ) : 'packery';

		// Define Isotope parameters.
		var isotopeParameters = {
			transitionDuration : 0,
			layoutMode :         layoutMode,
			resizable :          false,
			isOriginLeft:        ( 'rtl' !== $( 'html' ).attr( 'dir' ) ),
		};

		// Define layout-related parameters.
		var layoutParameters = {};

		// Add `gutter` parameter.
		if ( 'masonry' === layoutMode || 'fitRows' === layoutMode || 'packery' === layoutMode || 'masonryHorizontal' === layoutMode ) {
			if ( getGridGutter( $grid ) ) {
				layoutParameters.gutter = 0;
			}
		}

		isotopeParameters[ layoutMode ] = layoutParameters;

		recalculateGridItemsSize( $grid );

		// Fire Isotope using given parameters.
		$grid.isotope( isotopeParameters );

		//noinspection JSUnresolvedFunction
		$( window ).resize( function () {
			recalculateGridItemsSize( $grid );
		} );

		return true;
	},

	/**
	 * Resize grid items on window resize, or just resize the grid if the width of its items is
	 * automatically calculated by pure CSS.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery} $grid
	 */
	recalculateGridItemsSize = function( $grid ) {
		var gutter      = getGridGutter( $grid ),
			isFullWidth = ( $grid.width() >= windowWidth() ) || $grid.hasClass( 'grid-full' );

		if ( gutter ) { // Resize grid items and reset Isotope if grid items have a gutter.
			$grid.removeClass( 'grid-full' );

			resizeGridItems( $grid );

		} else if ( isFullWidth ) { // Resize whole grid for full width and no gutter.
			var width;

			if ( ( 'undefined' !== typeof currentBreakpoint ) ) {
				$grid.removeClass( 'grid-full' );
				width = '100%';
			} else {
				$grid.addClass( 'grid-full' );
				width = $grid.parent().width();
			}

			$grid.css( {
				'width' : width
			} );

			resizeGridItems( $grid );
		} else {
			resizeGridItems( $grid );
		}

		if ( 'undefined' === typeof currentBreakpoint ) {
			setGridItemClasses( $grid );
		}

		$grid.isotope();
	},

	/**
	 * Manage filtering of terms.
	 *
	 * @since 1.0.0
	 */
	handleTermFilter = function () {
		// Setup valid filters.
		setFilters();

		// Fire Isotope once on page load.
		handleFirstLoad();

		// Set behavior when filtering elements.
		handleSelection();
	},

	handleResizedGridItems = function( $grid ) {
		var setResized = function() {
			if ( 'undefined' !== typeof currentBreakpoint ) {
				$grid.find( '.item' ).addClass( 'isotope-item-resized' );
			} else {
				$grid.find( '.item' ).removeClass( 'isotope-item-resized' );
			}
		};

		setResized();

		$( window ).resize( setResized );
	},

	/**
	 * Load implementation for the first time.
	 *
	 * @since 1.0.0
	 */
	handleFirstLoad = function () {
		// Run after images have been loaded.
		$grids.each( function () {
			var $grid  = $( this ),
				loaded = false;

			if ( ! $grid.hasClass( 'js-resize' ) ) {
				$grid.addClass( 'js-resize' );
			}

			window.Pace.on( 'done', function () {
				setGridItemClasses( $grid );

				resizeGridItems( $grid );

				handleResizedGridItems( $grid );

				loaded = initIsotope( $grid );

				$grid.bind( 'gridReload', function() {
					setGridItemClasses( $grid );
					resizeGridItems( $grid );
					handleResizedGridItems( $grid );
				} );

				$( window ).resize( function() {
					if ( ! loaded ) {
						loaded = initIsotope( $grid );
					}
				} );
			} );
		} );

		setupFilterSelector();
	},

	/**
	 * Assign grid item class based on their purely CSS-calculated size.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery} $grid
	 */
	setGridItemClasses = function ( $grid ) {
		var columnSize = getRealItemSize( $grid );

		$grid.find( itemSelector ).each( function () {
			if ( $( this ).hasClass( 'single-size' ) || $( this ).hasClass( 'double-size' ) ) {
				return;
			}

			if ( Math.ceil( $( this ).width() ) > Math.ceil( columnSize ) ) {
				$( this ).addClass( 'double-size' );
			} else {
				$( this ).addClass( 'single-size' );
			}
		} );
	},

	/**
	 * Setup actions for filters selector.
	 *
	 * @since 1.0.0
	 */
	setupFilterSelector = function () {
		$( '.isotope-filter' ).each( function () {
			var $container = $( this ),
				$list      = $container.find( '.terms' ),
				$select    = $container.find( '.select-term a' );

			/**
			 * Hide open list when clicking outside of it.
			 *
			 * @param {Event} e
			 */
			var hideList = function ( e ) {
				if ( ! $list.hasClass( 'mobile-hidden' ) && ! $select.is( e.target ) ) {
					$list.addClass( 'mobile-hidden' );
					$( document ).unbind( 'click', hideList );
				}
			};

			$select.click( function ( e ) {
				e.preventDefault();

				// Setup term display for mobile devices.
				$list.toggleClass( 'mobile-hidden' );
				$( document ).bind( 'click', hideList );
			} );

			$list.find( 'a' ).click( function () {
				// Replace text in select element.
				$select.html( $( this ).html() );

				// Set clicked element as active.
				$list.find( 'a' ).removeClass( 'active' );
				$( this ).addClass( 'active' );
			} );
		} );
	},

	/**
	 * Resize grid items to their absolute size.
	 *
	 * @since 1.0.0
	 *
	 * @param {jQuery} $grid
	 */
	resizeGridItems = function ( $grid ) {
		var itemSize = getItemSize( $grid );

		if ( 'undefined' !== typeof currentBreakpoint ) {
			var columns   = ( currentBreakpoint.columns <= $grid.data( 'columns' ) ) ? currentBreakpoint.columns : $grid.data( 'columns' ),
				itemWidth = ( $grid.width() / columns );

			$grid.find( '.item' ).css( 'width', Math.floor( itemWidth ) );
		} else {
			$grid.find( '.item.single-size' ).css( 'width', itemSize );
			$grid.find( '.item.double-size' ).css( 'width', itemSize * 2 );
		}

		$grid.addClass( 'resized' );
	},

	/**
	 * Obtain real size for single columns without rounding.
	 *
	 * @since   1.0.0
	 *
	 * @param   {jQuery} $grid
	 *
	 * @returns {number}
	 */
	getRealItemSize = function ( $grid ) {
		return ( $grid.width() / $grid.data( 'columns' ) );
	},

	/**
	 * Obtain size for single columns, possible rounded to floor.
	 *
	 * @since   1.0.0
	 *
	 * @param   {jQuery} $grid
	 *
	 * @returns {number}
	 */
	getItemSize = function ( $grid ) {
		var gutter = getGridGutter( $grid ),
			width  = getRealItemSize( $grid );

		return ( gutter <= 1 ) ? Math.floor( width ) : width;
	},

	/**
	 * Fire Isotope on term selection and assign HTML classes to active and
	 * inactive elements.
	 *
	 * @since 1.0.0
	 */
	handleSelection = function () {
		// Return early if we don't have any filters.
		if ( ! filters.length ) {
			return;
		}

		$grids.each( function ( $i ) {
			if ( 'undefined' === typeof filters[ $i ] ) {
				return;
			}

			var activeCategory;

			var $triggers = filters[ $i ],
				$container = $( $triggers.context ),
				$grid = $( this );

			$triggers.click( function ( e ) {
				e.preventDefault();

				// Store name of class to show.
				var category = $( this ).attr( 'data-class' ),
					selector = ( itemSelector !== category ) ? category : itemSelector;

				// Don't fire filter if the clicked category is the same as the current one.
				if ( activeCategory === category ) {
					return;
				}

				activeCategory = category;

				$grid.css( 'opacity', 0 );

				setTimeout( function() {
					// Fire Isotope to filter items by required terms.
					$grid.isotope( { filter: selector } );

					if ( $grid.data( 'animated' ) ) {
						$grid.find( '.item' ).NiceScheduleAnimation();
					}

					$grid.animate( { 'opacity': 1 }, 500 );

					// Scroll to filter position if required.
					if ( $container.data( 'scroll' ) ) {
						var filterPosition = $container.offset().top,
							$adminBar = $( '#wpadminbar' );

						if ( $adminBar.length ) {
							filterPosition += $adminBar.outerHeight();
						}

						$( window ).scrollTo( filterPosition, 1000 );
					}
				}, 500 );
			} );

		} );
	},

	/**
	 * Initialize Isotope actions.
	 *
	 * @since 1.0.0
	 */
	init = function() {
		handleTermFilter();
		watchBreakpoints();
	},

	/**
	 * Fire events on document ready, and bind other events.
	 *
	 * @since 1.0.0
	 */
	ready = function () {
		var url = $.NiceLazyScript( 'isotope' );

		$( document ).ready( function() {
			$grids = $( '.isotope-grid' );

			if ( $grids.length && typeof $.fn.isotope !== 'function' && url ) {
				$.getScript( url, init );
			} else if ( $grids.length && typeof $.fn.isotope === 'function' ) {
				init();
			} else if ( $grids.length && typeof $.fn.isotope !== 'function' ) {
				$.NiceDev.log( 'Warning: Isotope is not initialized.' );
			}
		} );
	};

	// Expose the ready function to the world.
	return {
		ready : ready
	};

} )( jQuery );

NiceIsotope.ready();
