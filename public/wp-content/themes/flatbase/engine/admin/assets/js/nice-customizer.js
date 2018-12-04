/**
 * NiceCustomizer.
 *
 * Handle functionality related to our Customizer implementation.
 *
 * @since   2.0
 * @package Nice_Framework
 */
var NiceCustomizer = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	/**
	 * Add icons to sections.
	 *
	 * @since 2.0
	 */
	var addIcons = function() {
		var headings = window.NiceCustomizerVars.headings,
		    _addIcon = function( heading ) {
			    var $title = $( '#accordion-section-' + heading.id ).find( '.accordion-section-title' );
			    $title.prepend( heading.icon );

			    if ( heading.hasParent ) {
				    if ( $( 'html[dir="rtl"]' ).length ) {
					    $title.css( 'padding-right', parseInt( $title.css( 'padding-right' ) ) * 2.5 );
				    } else {
					    $title.css( 'padding-left', parseInt( $title.css( 'padding-left' ) ) * 2.5 );
				    }
			    }
		    };

		$( document ).ready( function() {
			for ( var i = 0; i < headings.length; i++ ) {
				_addIcon( headings[i] );
			}
		} );
	},

    /**
     * Handle toggle of setting groups.
     *
     * @since 2.0
     */
    handleGroups = function() {
	    var groups = window.NiceCustomizerVars.groups;

	    var _toggleGroup = function( group ) {
		    var $group             = $ ( '#customize-control-' + group.id ),
		        $toggle            = $group.find ( '.settings-group-toggle' ),
		        $settingContainers = [];

		    for ( var i = 0; i < group.settings.length; i++ ) {
			    $settingContainers.push( $( '#customize-control-' + group.settings[i] ) );
		    }

		    _toggleContainers( $settingContainers );
		    $toggle.toggleClass( 'closed' );

		    $toggle.click( function() {
			    _toggleContainers( $settingContainers );
			    $toggle.toggleClass( 'closed' );
		    } );
	    };

	    var _toggleContainers = function( $containers ) {
		    for ( var i = 0; i < $containers.length; i++ ) {
			    $containers[i].toggleClass( 'hidden' );
		    }
	    };

	    $( document ).ready( function() {
		    for ( var key in groups ) {
			    if ( groups.hasOwnProperty( key ) ) {
				    _toggleGroup( groups[key] );
			    }
		    }
	    } );
	},

	/**
	 * Remove "checked" attribute from checkboxes that are not initially
	 * checked. Since WordPress 4.9, changes in Customizer messes up the
	 * implementation of checkboxes in Kirki, so this workaround is needed
	 * until we come up with a more stable solution.
	 *
	 * @since 2.0.9
	 */
	fixCheckboxes = function() {
		var checkboxes = $( 'input[type="checkbox"]' );

		checkboxes.each( function() {
			if ( ! $( this ).data( 'checked' ) && $( this ).attr( 'checked' ) ) {
				$( this ).removeAttr( 'checked' );
			}
		} );
	},

	/**
	 * Fire events on document ready, and bind other events.
	 *
	 * @since 2.0
	 */
	ready = function() {
		addIcons();
		handleGroups();
		fixCheckboxes();
	};

	// Expose the ready function to the world.
	return {
		ready: ready
	};

} )( jQuery );

jQuery( NiceCustomizer.ready );
