/**
 * Nice Framework by NiceThemes.
 *
 * This module contains functionality for the Plugin Installer.
 *
 * @since   2.0
 *
 * @package NiceFramework
 */
var NicePluginInstaller = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	/**
	 * Bulk actions.
	 *
	 * @since 2.0
	 */
	var initPluginInstaller = function() {
		jQuery( '.nice-install-plugins #nice-bulk-actions-form' ).submit( function() {
			var action = jQuery( '.nice-install-plugins #nice-bulk-actions-form #nice-bulk-action' ).val().replace( 'tgmpa-bulk-', '' ).replace( 'install-activate', 'install' );
			var group  = jQuery( '.nice-install-plugins #nice-bulk-actions-form #nice-bulk-plugins' ).val();

			var action_selector = '[data-action-' + action + '="1"]' ;
			var group_selector  = '[data-group-' + group + '="1"]' ;

			var selected_plugins = jQuery('.nice-install-plugins .item' + action_selector + group_selector );

			if ( ! ( 0 < selected_plugins.length ) ) {
				alert( 'No plugins match the requested action.' );
				return false;
			}
		} );
	},

	/**
	 * Fire events on document ready.
	 *
	 * @since 2.0
	 */
	ready = function() {
		initPluginInstaller();
	};

	// Expose the ready function to the world.
	return {
		ready: ready
	};

} )( jQuery );

jQuery( NicePluginInstaller.ready );
