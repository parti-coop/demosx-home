/**
 * NiceThemes Typography Live Preview
 *
 * Generates a live preview using the setting specified in a "custom
 * typography" field.
 *
 * @since   2.0
 * @package Nice_Framework
 */
var NiceTypographyPreview = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	var loadedFonts = [],

	/**
	 * Handle preview.
	 *
	 * @since 2.0
	 */
	preview = function() {
		// Select all buttons.
		var buttons = $( '.nice-typography-preview-button' ),

	    /**
	     * Add HTML elements to hold live preview.
	     *
	     * @param   $control
	     * @returns {*}
	     * @private
	     */
	    _addPreviewContainer = function( $control ) {
		    var html = '<div class="typography-preview"><a href="#" class="preview_remove"><i class="icon-cancel"></i></a><div class="preview-text">' + window.NiceTypographyVars.previewText + '</div></div>';

		    $( $control ).after( html );

		    return $( $control ).next( '.typography-preview' );
	    },

	    /**
	     * Obtain current color of typography control section.
	     *
	     * @param $control
	     * @returns {*}
	     * @private
	     */
	    _getColor = function( $control ) {
		    return $control.find( '.nice-typography-color' ).val();
	    },

	    /**
	     * Obtain current font family of typography control section.
	     *
	     * @param $control
	     * @returns {*}
	     * @private
	     */
	    _getFontFamily = function( $control ) {
		    return $control.find( '.nice-typography-family' ).val();
	    },

	    /**
	     * Obtain current fonts style of typography control section.
	     *
	     * @param $control
	     * @returns {*}
	     * @private
	     */
	    _getFontStyle = function( $control ) {
		    var style = 'normal',
		        value = $control.find( '.nice-typography-style' ).val();

		    if ( value.indexOf( 'italic' ) !== -1 ) {
			    style = 'italic';
		    }

		    return style;
	    },

	    /**
	     * Obtain current font weight of typography control section.
	     *
	     * @param $control
	     * @returns {*}
	     * @private
	     */
	    _getFontWeight = function( $control ) {
		    var weight = 'normal',
		        value = $control.find( '.nice-typography-style' ).val();

		    if ( value.indexOf( 'bold' ) !== -1 ) {
			    weight = 'bold';
		    }

		    return weight;
	    },

	    /**
	     * Obtain current font size of typography control section.
	     *
	     * @param $control
	     * @returns {*}
	     * @private
	     */
	    _getFontSize = function( $control ) {
		    var size;

		    if ( $control.find( '.nice-typography-size' ).length ) {
			    size = $control.find( '.nice-typography-size' ).val() + 'px';
		    } else if ( $control.find( '.nice-typography-font-size' ).length ) {
			    size = $control.find( '.nice-typography-font-size' ).val();

			    if ( $control.find( '.nice-typography-font-unit' ).length ) {
				    size += $control.find( '.nice-typography-font-unit' ).val();
			    } else {
				    size += 'px';
			    }
		    }

		    return size;
	    },

	    _getLetterSpacing = function( $control ) {
		    var letter_spacing;

		    if ( $control.find( '.nice-typography-letter-spacing' ).length ) {
			    letter_spacing = $control.find( '.nice-typography-letter-spacing' ).val();

			    if ( $control.find( '.nice-typography-letter-spacing-unit' ).length ) {
				    letter_spacing += $control.find( '.nice-typography-letter-spacing-unit' ).val();
			    } else {
				    letter_spacing += 'px';
			    }
		    }
		    return letter_spacing;
	    },

	    /**
	     * Obtain typography preview.
	     *
	     * @param   button
	     * @private
	     */
	    _processPreview = function( button ) {
		    // Obtain current control section.
		    var $control = $( button ).closest( '.controls' ),

	        // Obtain button icon.
	        $buttonIcon = $( button ).find( 'span' ),

	        // Obtain all preview containers.
	        $previewContainers = $( '.typography-preview' ),

	        // Obtain current preview container.
		    $previewContainer = $control.next( '.typography-preview' ),

	        // Obtain styles for typography changes.
		    typographySettings = {
			    color:         _getColor( $control ),
			    fontFamily:    _getFontFamily( $control ),
			    fontStyle:     _getFontStyle( $control ),
			    fontWeight:    _getFontWeight( $control ),
			    fontSize:      _getFontSize( $control ),
			    letterSpacing: _getLetterSpacing( $control )
		    };

		    // Obtain preview container if not set yet.
		    if ( ! $previewContainer.length ) {
			    $previewContainer = _addPreviewContainer( $control );
		    }

		    // Obtain element containing preview text.
		    var $preview = $previewContainer.find( '.preview-text' ),

	        // Obtain button to remove the current preview.
	        $previewRemove = $previewContainer.find( '.preview_remove' );

		    // Add class to icon.
		    $buttonIcon.addClass( 'refresh' );

		    // Set class to the current preview container.
		    $previewContainers.removeClass( 'current' );
		    $previewContainer.addClass( 'current' );

		    // Set new styles.
		    $preview.css( typographySettings );

		    // Schedule removals on click.
		    $previewRemove.click( function( event ) {
			    event.preventDefault();
			    $buttonIcon.removeClass( 'refresh' );
			    $previewContainer.remove();
		    } );
	    };

		// Setup functionality when button is clicked.
		buttons.each( function() {
			$( this ).live( 'click', function( event ) {
				event.preventDefault();
				_processPreview( this );
			} );
		} );

	},

    /**
     * Add preview buttons to all typography control sections.
     *
     * @since 2.0
     */
    addButtons = function() {
	    var previewButtonHTML = '<a href="#" class="nice-typography-preview-button" title="' + window.NiceTypographyVars.buttonTitle + '"><span>' + '</span></a>';

	    $( 'input.nice-typography-last' ).each( function () {
		    $( this ).after( previewButtonHTML );
	    } );
    },

    /**
     * Load all Google Fonts.
     *
     * @since 2.0
     */
    loadFonts = function () {
	    // Obtain all typography selectors.
	    var $typographySelectors = $( '.nice-typography-family' ),

        /**
         * Load currently selected font.
         *
         * @private
         */
        _loadCurrentFont = function() {
		    var $selected = $( this ).find( ':selected' );

		    if ( 'google-font' === $selected.data( 'type' ) && $.inArray( $selected.val(), loadedFonts ) < 0 ) {
			    WebFont.load( {
				    google: {
					    families: [ $selected.val() + $selected.data( 'variant' ) ]
				    }
			    } );

			    loadedFonts.push( $selected.val() );
		    }
	    };

	    // Load selected fonts once on page load.
	    $typographySelectors.each( _loadCurrentFont );

	    // Load selected fonts when selectors change.
	    $typographySelectors.change( _loadCurrentFont );
    },

	/**
	 * Fire events on document ready, and bind other events.
	 *
	 * @since 2.0
	 */
	ready = function() {
		loadFonts();
		addButtons();
		preview();
	};

	// Expose the ready function to the world.
	return {
		ready: ready
	};

} )( jQuery );

jQuery( NiceTypographyPreview.ready );
