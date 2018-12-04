/**
 * NiceFramework by NiceThemes.
 *
 * This module contains general functionality for the theme.
 *
 * @since   1.0.0
 * @package NiceFramework
 */
var NiceFrameworkGeneral = ( function( $ ) {
	// Tell browsers we're not doing anything silly.
	'use strict';

	/* General JS for the NiceThemes Framework Admin Section */

	/**
	 * Process some header static data to be passed to other functions.
	 *
	 * @since 1.0.0
	 */
	var radioImageSelect = function() {
			$(document).on('click', '.nice-framework-admin-ui-radio-image', function() {
			$(this).closest('.type-radio-image').find('.nice-framework-admin-ui-radio-image').removeClass('nice-framework-admin-ui-radio-image-selected');
			$(this).toggleClass('nice-framework-admin-ui-radio-image-selected');
			$(this).parent().find('.nice-framework-admin-ui-radio').prop('checked', true).trigger('change');
		});
	},

	colorPicker = function() {
		$( '.nice-color:not(.delay)' ).each( function() {
			triggerColorPicker( $( this ) );
		} );
	},

		triggerColorPicker = function( $el ) {
			if ( $el.hasClass( 'nice-color-opacity' ) ) {
				$el.wpColorPickerOpacity().addClass( 'color-picker-loaded' );
			} else {
				$el.wpColorPicker( {
					change : _.throttle( function () {
						$( this ).trigger( 'change' );
						// Update the color dropdown background property.
						$( '.nice-select-color .' + $( this ).attr( 'name' ) ).css( 'background', $( this ).val() );
					}, 3000 )
				} ).addClass( 'color-picker-loaded' );
			}
		},

	/**
	 * Toggle category filter.
	 *
	 * Add fadein/fadeout effects to the default functionality.
	 *
	 * @since 1.0.0
	 */
	toggleTermFilter = function() {
		var filters = $( '.nice-admin-filter' );

		filters.each( function() {
			var filter = $( this );

			filter.hover( function() {
				if ( $( window ).width() > 749 ) {
					$( this ).children( 'ul' ).fadeIn().addClass( 'active' );
				}
			}, function() {
				if ( $( window ).width() > 749 ) {
					$( this ).children( 'ul' ).fadeOut().removeClass( 'active' );
				}
			} );

			filter.click( function( e ) {
				if ( $( window ).width() <= 749 ) {
					e.preventDefault();

					if ( $( this ).children( 'ul' ).hasClass( 'active' ) ) {
						$( this ).children( 'ul' ).fadeOut().removeClass( 'active' );
					} else {
						$( this ).children( 'ul' ).fadeIn().addClass( 'active' );
					}
				}
			} );

			$( window ).resize( function() {
				if ( $( window ).width() > 749 && filter.children( 'ul' ).hasClass( 'active' ) ) {
					filter.children( 'ul' ).hide().removeClass( 'active' );
				}
			} );
		} );
	},

	/**
	 * Replace the text of the filter button with the name of the selected category.
	 *
	 * @since 1.0
	 */
	modifyFilterText = function() {
		var sources = $( '.nice-admin-filter a.filter' );

		sources.each( function() {
			var source = $( this ),
				destination = source.closest( '.nice-admin-filter' ).find( '.sort-items span' );

			source.click( function( e ) {
				e.preventDefault();
				destination.html( $( this ).html() );
			} );
		} );
	},

	initMediaUploader = function() {

		var nice_file_frame;
		window.formfield = '';

		$( 'body' ).on( 'click', '.nice_upload_button', function(e) {

			e.preventDefault();

			var niceformfield = $(this).prev('input').attr( 'id' );

			// Create the media frame.
			nice_file_frame = wp.media.frames.file_frame = wp.media( {
				title: generalData.add_media_title,
				button: {
					text: generalData.use_this_file
				},
				multiple: $( this ).data( 'multiple' ) === '0' ? true : false // Set to true to
				                                                              // allow multiple
				                                                              // files to be
				                                                              // selected
			} );


			nice_file_frame.on( 'menu:render:default', function( view ) {
				// Store our views in an object.
				var views = {};

				// Unset default menu items
				view.unset( 'library-separator' );
				view.unset( 'gallery' );
				view.unset( 'featured-image' );
				view.unset( 'embed' );

				// Initialize the views in our view object.
				view.set( views );
			} );

			// When an image is selected, run a callback.
			nice_file_frame.on( 'select', function() {

				var selection = nice_file_frame.state().get('selection');
				selection.each( function( attachment, index ) {
					attachment = attachment.toJSON();
					if ( 0 === index ) {
						// place first attachment in field
						$('#' + niceformfield ).val(attachment.url);

						var btnContent = '',
						mime = attachment.mime,
						regex = /^image\/(?:jpe?g|png|gif|x-icon)$/i;

						if ( mime.match(regex) ) {

							btnContent = '<img src="' + attachment.url + '" />';

						}

						btnContent += '<a href="#" class="nice-upload-remove">' + generalData.remove_media_text + '</a>';

						$('#' + niceformfield).siblings( '.screenshot').slideDown().html(btnContent);

					} else {
						// Multiple attachments, do nothing.
					}

					parse_condition();
				});

			});

			// Finally, open the modal
			nice_file_frame.open();

		});

		$( 'body' ).on( 'click', '.nice-upload-remove', function(e) {

			e.preventDefault();

			jQuery(this).hide();
			jQuery(this).parents().parents().children( '.nice-upload' ).attr( 'value', '' );
			jQuery(this).parents( '.screenshot').slideUp();

			parse_condition();

			return false;
		});

	},

	/**
	 * Init jQuery UI .tabs();
	 *
	 * @since 2.0
	 */
	initTabs = function() {
		$( '#nice-metaboxes-tabs' ).tabs();
		$( '.nice-tabs' ).tabs();
	},

	/**
	 * Initialize accordions.
	 *
	 * @since 2.0
	 */
	initAccordion = function() {
		var accordion = $( '.nice-accordion' );
		if ( accordion.length ) {
			accordion.accordion( { collapsible: true, heightStyle: 'content' } );
		}
	},

	/**
	 * Manage showing and hiding of button tooltips.
	 *
	 * @since 2.0
	 */
	initNiceTooltip = function() {
		$( '.nice-tooltip' ).tooltip({
			items: 'img, a, input, select',
			position: {
				my: 'center bottom-15',
				at: 'center top',
				using: function( position, feedback ) {
					$( this ).css( position );
					$( '<div>' )
						.addClass( 'arrow' )
						.addClass( feedback.vertical )
						.addClass( feedback.horizontal )
						.appendTo( this );
				}
			},
			content: function() {
				var element = $( this ), result;
				if ( element.is( '[title]' ) ) {
					result = element.attr( 'title' );
				}
				if ( element.is( 'img' ) ) {
					result = element.attr( 'alt' );
				}
				return result;
			}
		} );
	},

	/**
	 * Manage opening and closing of toggabble elements.
	 *
	 * @since 2.0
	 */
	handleToggle = function() {
		var toggle = $( '.nice-toggle' );
		if ( toggle.length ) {
			toggle.each( function () {
				if ( 'closed' == $( this ).attr( 'data-id' ) ) {
					$( this ).accordion( { header: '.nice-toggle-title', collapsible: true, active: false  } );
				} else {
					$( this ).accordion( { header: '.nice-toggle-title', collapsible: true} );
				}
			} );
		}
	},

	/**
	 * Handle fancyBox within the admin section.
	 *
	 * @since 2.0
	 */
	handleFancybox = function() {
		$( '.fancybox' ).fancybox( { padding : 0 } );
	},

	/**
	 * Condition objects
	 *
	 * @since 2.0
	 */
	condition_objects = function() {
		return 'select, input[type="radio"]:checked, input[type="text"], input[type="hidden"], input[type="checkbox"]';
	},

	/**
	 * Match conditions
	 *
	 * @since 2.0
	 */
	match_conditions = function(condition) {
		var match;
		var regex = /(.+?):(is|not|contains|less_than|less_than_or_equal_to|greater_than|greater_than_or_equal_to)\((.*?)\),?/g;
		var conditions = [];

		while( match = regex.exec( condition ) ) {
			conditions.push({
				'check' : match[1],
				'rule'  : match[2],
				'value' : match[3] || ''
			});
		}

		return conditions;
	},

	/**
	 * Parse conditions
	 *
	 * @since 2.0
	 */
	parse_condition = function() {
		$( '.format-settings[id^="setting_"][data-condition]' ).each(function() {

		var passed;
		var conditions = match_conditions( $( this ).data( 'condition' ) );
		var operator = ( $( this ).data( 'operator' ) || 'and' ).toLowerCase();


		$.each( conditions, function( index, condition ) {


			var target	 = $( '#setting_' + condition.check );
			var targetEl = !! target.length && target.find( condition_objects() ).first();

			if ( ! target.length || ( ! targetEl.length && condition.value.toString() !== '' ) ) {
				return;
			}

			// if the field type is checkbox, then we have to see if it's checked
			if ( targetEl.attr('type') === 'checkbox' ) {
				var v1 = targetEl.is(':checked').toString();
			} else {
				var v1 = targetEl.length ? targetEl.val().toString() : '';
			}

			var v2 = condition.value.toString();
			var result;

			if ( 'is' === condition.rule || 'not' === condition.rule ) {
				switch ( v1 ) {
					case 'true':
						v1 = '1';
						break;
					case 'false':
						v1 = '0';
						break;
					default:
						break;
				}
				switch ( v2 ) {
					case 'true':
						v2 = '1';
						break;
					case 'false':
						v2 = '0';
						break;
					default:
						break;
				}
			}

			switch ( condition.rule ) {
			case 'less_than':
				result = ( parseInt( v1 ) < parseInt( v2 ) );
				break;
			case 'less_than_or_equal_to':
				result = ( parseInt( v1 ) <= parseInt( v2 ) );
				break;
			case 'greater_than':
				result = ( parseInt( v1 ) > parseInt( v2 ) );
				break;
			case 'greater_than_or_equal_to':
				result = ( parseInt( v1 ) >= parseInt( v2 ) );
				break;
			case 'contains':
				result = ( v1.indexOf(v2) !== -1 ? true : false );
				break;
			case 'is':
				result = ( v1 === v2 );
				break;
			case 'not':
				result = ( v1 !== v2 );
				break;
			}

			if ( 'undefined' === typeof passed ) {
				passed = result;
			}

			switch ( operator ) {
				case 'or':
					passed = ( passed || result );
					break;
				case 'and':
				default:
					passed = ( passed && result );
					break;
			}

		});

		if ( passed ) {
			$(this).animate({opacity: 'show' , height: 'show'}, 200);
		} else {
			$(this).animate({opacity: 'hide' , height: 'hide'}, 200);
		}

		var $setting =  $( this );

		setTimeout( function() {
			$setting.trigger( 'NiceSettingToggled' );
		}, 200 );

		passed = null;

		});
	},

	/**
	 * Init conditions.
	 *
	 * @since 2.0
	 */
	initConditions = function() {
		var delay = (function() {
		var timer = 0;
		return function(callback, ms) {
			clearTimeout(timer);
			timer = setTimeout(callback, ms);
		};
		})();

		$('.format-settings[id^="setting_"]').on( 'change.conditionals, keyup.conditionals', condition_objects(), function(e) {
			if (e.type === 'keyup') {
				// handle keyup event only once every 500ms
				delay(function() {
					parse_condition();
				}, 500);
			} else {
				parse_condition();
			}
		});
		parse_condition();
	},

	/**
	 * Fire functionality for list item fields.
	 *
	 * @since 2.0
	 */
	setupListItems = function() {
		var $lists = $( '.nice-framework-admin-ui-list-item' );

		// Fire callback to handle each list, if provided when setting the option.
		$lists.each( function( index, element ) {
			if ( 'undefined' === $( element ).data( 'js-callback' ) ) {
				return;
			}

			handleListItems( $( this ) );
		} );
	},

	/**
	 * Handle functionality for social icons.
	 *
	 * @param {jQuery} $list
	 *
	 * @since 2.0
	 */
	handleListItems = function( $list ) {
		var messages = window.generalData.listItemMessages;

		// Append "Add new" button to the bottom of the list.
		if ( $list.is( '.editable' ) ) {
			$list.append( '<a href="javascript:void(0);" class="add-new button"><i class="bi_interface-plus"></i> ' + messages.addOption + '</a>' );
		}

		// Append a placeholder for the list item field model to the body element.
		$( 'body' ).append( '<div id="' + $list.attr( 'id' ) + '_model-container" style="display: none;"></div>' );

		// Set model to copy form fields from.
		var $model = $list.find( 'li.html-model' ),

		// Set placeholder for the model.
		$modelContainer = $( '#' + $list.attr( 'id' ) + '_model-container' ),

		// Set items container.
		$itemsContainer = $list.find( 'li.section-list_item:last' ).parent(),

		// Set list of items.
		$items = $itemsContainer.find( 'li.section-list_item:not(:last)' ),

		// Set "add new" button.
		$addButton = $list.find( 'a.add-new' ),

		// Set input where data for this field is stored.
		$dataInput = $list.find( 'input.section-data' ),

		// Set user prefix for new options.
		userPrefix = $list.data( 'user-item-prefix' ),

		// Set object to contain options.
		options = {},

		/**
		 * Initialize color palette process.
		 */
		init = function() {
			// Set initial value for options.
			options = getOptions();

			// Place the HTML model inside its dedicated placeholder.
			$modelContainer.append( $model );

			// Process setup for all items.
			$items.each( function( index, element ) {
				setupItem( element );
			} );

			// Add field for new item when clicking the "add new" button.
			$addButton.click( addItemField );

			// Make list sortable if required.
			if ( $list.is( '.sortable' ) ) {
				$itemsContainer.sortable( {
					handle: 'h4.heading',
					stop: updateInputData
				} );
			}
		},

		/**
		 * Obtain current value of options.
		 */
		getOptions = function() {
			return JSON.parse( atob( $dataInput.val() ) );
		},

		/**
		 * Return option values from current state of DOM.
		 */
		getUpdatedOptions = function() {
			var newOptions = {};

			if ( 'undefined' === typeof options ) {
				options = getOptions();
			}

			$itemsContainer.find( 'li.section-list_item' ).each( function() {
				var $currentItem = $( this );

				if ( $currentItem.data( 'slug' ) && 'undefined' !== typeof options[ $currentItem.data( 'slug' ) ] ) {
					newOptions[ $currentItem.data( 'slug' ) ] = $currentItem.data( 'name' );
				}
			} );

			return newOptions;
		},

		/**
		 * Update input data to be saved.
		 */
		updateInputData = function() {
			options = getUpdatedOptions();

			$dataInput.val( btoa( JSON.stringify( options ) ) );
		},

		/**
		 * Setup HTML modifications and schedule actions for a given item.
		 *
		 * @param {obj} el
		 */
		setupItem = function( el ) {
			// Set current item.
			var $item       = $( el ),
				$nameField  = $item.find( '.nice-option-name input.nice-input' );

			$nameField.addClass( 'nice-input-option-name' );

			$item.$fields = $item.find( '.nice-input:not(.nice-input-option-name), .nice-color' );

			if ( $item.$fields.length ) {
				$item.$fields.each( function() {
					$( this ).data( 'slug', $( this ).attr( 'name' ) );
				} );
			}

			// Add controls to the UI.
			addControlButtons( $item );

			// Set actions on key up.
			$item.find( '.nice-option-name input.nice-input' ).keyup( function() {
				handleKeyUp( $( this ), $item );
			} );

			// Save item value to data input field when clicking "save" button.
			$item.find( '.save-option' ).click( function() {
				if ( ! $( this ).hasClass( 'button-disabled' ) ) {
					saveItem( $item );
				}
			} );

			// Remove item value from data input field when clicking "Remove" button.
			$item.find( '.remove-option' ).click( function() {
				removeItem( $item );
			} );

			$item.find( '.edit-option' ).click( function() {
				editItem( $( this ), $item );
			} );
		},

		/**
		 * Add controls to the UI.
		 *
		 * @param {jQuery} $item
		 */
		addControlButtons = function( $item ) {
			var $title = $item.find( 'h4 label' );

			$item.append( '<div class="option-menu"></div>' );

			var $menu = $item.find( '.option-menu' );

			// Add a message container.
			$menu.append( '<span class="field-valid-message"></span>' );

			// Add control buttons.
			if ( $list.is( '.editable' ) && ! $item.data( 'blocked' ) ) {
				$menu.append( '<a href="javascript:void(0);" class="save-option button button-primary button-disabled"><i class="bi_interface-tick"></i></a>' );
			}

			$menu.append( '<a href="javascript:void(0);" class="edit-option button"><i class="bi_editorial-pencil"></i></a>' );

			if ( $list.is( '.editable' ) && ! $item.data( 'blocked' ) ) {
				$menu.append( '<a href="javascript:void(0);" class="remove-option button"><i class="bi_editorial-trash"></i></a>' );
			}
		},

		/**
		 * Process key up action.
		 *
		 * @param {jQuery} $field
		 * @param {jQuery} $item
		 */
		handleKeyUp = function( $field, $item ) {
			var $title          = $item.find( 'h4 label' ),
				defaultTitle    = ( 'undefined' !== typeof $item.data( 'name' ) ) ? $item.data( 'name' ).trim() : '',
				placeholderText = defaultTitle ? defaultTitle : messages.newOptionName,
				titleText       = $field.val().trim() ? $field.val().trim() : placeholderText;

			// Enable "save" button and add message if the option name needs to be validated.
			if ( titleText && titleText !== defaultTitle && $item.find( '.save-option' ).hasClass( 'button-disabled' ) ) {
				$item.find( '.save-option' ).removeClass( 'button-disabled' );
				$item.find( '.field-valid-message' ).html( messages.validateOptionName );
			}

			// Disable save button and add message if the option name is empty or doesn't need to
			// be validated.
			if ( ( ! titleText || titleText === defaultTitle || titleText === placeholderText ) && ! $item.find( '.save-option' ).hasClass( 'button-enabled' ) ) {
				$item.find( '.save-option' ).addClass( 'button-disabled' );

				if ( $field.val().trim() ) {
					$item.find( '.field-valid-message' ).html(  );
				} else {
					$item.find( '.field-valid-message' ).html( messages.enterOptionName );
				}
			}

			// Change title while writing a new one.
			$title.html( titleText );
		},

		/**
		 * Process item edit option.
		 *
		 * @param {jQuery} $editButton
		 * @param {jQuery} $item
		 */
		editItem = function( $editButton, $item ) {
			$item.find( 'ul:first' ).toggle();
			$editButton.toggleClass( 'active' );
		},

		/**
		 * Save values for a given item to the data input field.
		 *
		 * @param {jQuery} $item
		 */
		saveItem = function( $item ) {
			var $nameField  = $item.find( '.nice-option-name input.nice-input' ),
				newOptionID = userPrefix + '_' + $nameField.val().toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'_' ).trim(),
				hasSlug = ( 'undefined' !== typeof options[ $item.data( 'slug' ) ] ),
				hasName = ( 'undefined' !== typeof options[ $item.data( 'name' ) ] );

			// Show alert if the option field doesn't have a value.
			if ( ! $nameField.val() ) {
				alert( messages.enterOptionName );
				$nameField.focus();

				return;
			}

			// Show alert if the option name is duplicated when creating a new option.
			if ( ! hasSlug && ( 'undefined' !== typeof options[ newOptionID ] ) ) {
				alert( messages.optionNameExists );
				$nameField.val( '' ).focus().keyup();

				return;
			}

			// Show alert if the option name is duplicated when modifying a saved option.
			if ( hasSlug && ( 'undefined' !== typeof options[ newOptionID ] ) && ( newOptionID !== $item.data( 'slug' ) ) ) {
				alert( messages.optionNameExists );
				$nameField.val( $item.data( 'name' ) ).focus().keyup();

				return;
			}

			// Save a new option or modify a saved one.
			if ( ! hasSlug || ! hasName || options[ $item.data( 'name' ) ] !== $nameField.val() ) {
				delete options[ $item.data( 'slug' ) ];

				if ( ! $item.data( 'default' ) && ! $item.data( 'blocked' ) ) {
					options[ newOptionID ] = $nameField.val();
					$item.data( 'slug', newOptionID );

					$item.$fields.each( function() {
						var currentFieldNewID,
							currentFieldNewName;

						if ( 'default' === $( this ).data( 'slug' ) ) {
							currentFieldNewID   = newOptionID;
							currentFieldNewName = newOptionID;
						} else {
							currentFieldNewID   = newOptionID + '_' + $( this ).data( 'slug' );
							currentFieldNewName = newOptionID + '_' + $( this ).data( 'slug' );
						}

						$( this ).attr( 'id', currentFieldNewID );
						$( this ).attr( 'name', currentFieldNewName );
					} );

				} else {
					options[ $item.data( 'slug' ) ] = $nameField.val();
				}

				$item.data( 'name', $nameField.val() );

				updateInputData();

				// Disable "save" button and show message after validating the option name.
				$item.find( '.save-option' ).addClass( 'button-disabled' );
				$item.find( '.field-valid-message' ).html( messages.validOptionName );
			}

			if ( ! hasSlug && ! $addButton.is( ':visible' ) ) {
				$addButton.show();
			}
		},

		/**
		 * Remove an item from the data input field.
		 *
		 * @param {jQuery} $item
		 */
		removeItem = function( $item ) {
			if ( ! confirm( messages.confirmRemoveOption ) ) {
				return;
			}

			var slug = $item.data( 'slug' );

			$item.remove();

			if ( slug ) {
				updateInputData();
			} else {
				$addButton.show();
			}
		},

		/**
		 * Add a field for a new item.
		 */
		addItemField = function() {
			var $clone        = $model.clone().removeClass( 'html-model' ),
				$colorPickers = $clone.find( '.nice-color' ),
				$title        = $clone.find( 'h4 label' );

			$title.html( messages.newOptionName );

			$itemsContainer.append( $clone );

			$colorPickers.each( function() {
				triggerColorPicker( $( this ) );
			} );

			setupItem( $clone );

			$clone.find( 'h4.heading' ).addClass( 'ui-sortable-handle' );

			$clone.find( '.edit-option' ).click();

			$( this ).hide();
		};

		// Initialize execution.
		init();
	},

	/**
	 * Initialize color selectors.
	 */
	initSelectColor = function() {
		var $selectors = $( '.nice-input.nice-select-color' );

		$selectors.each( function() { setupSelectColor( this ) } );
	},

	/**
	 * Setup select_color component.
	 *
	 * @param e
	 */
	setupSelectColor = function( e ) {
		var $select = $( e ),
			$placeholder = $select.find( '.nice-color-select-placeholder' ),
			$optionsContainer = $select.find( '.nice-color-select-options' ),
			$options = $select.find( '.option:not(.nice-color-select-placeholder)' ),
			$inputData = $select.find( 'input.nice-input' ),
			selectedColor,

			setSelectedColor = function( $option, value ) {
				$placeholder.html( $option.html() );
				$optionsContainer.hide();
				$placeholder = $select.find( '.nice-color-select-placeholder' );
				selectedColor = value;
			};

		$optionsContainer.hide();

		$placeholder.click( function() {
			$optionsContainer.toggle();
		} );

		$options.each( function() {
			var $option = $( this ),
				value   = $option.data( 'value' );

			if ( $option.data( 'selected' ) ) {
				setSelectedColor( $option, value );
			}

			$option.click( function() {
				setSelectedColor( $( this ), value );
				$inputData.val( value );
			} );
		} );
	},

	/**
	 * Setup watching for changes in options to update color selectors.
	 */
	watchColorSelectors = function() {
		var $form = $( '#niceform' );

		$form.on( 'NiceOptionsUpdated', updateColorSelectors );
	},

	/**
	 * Update color selectors.
	 */
	updateColorSelectors = function() {
		var $selectors  = $( '.nice-input.nice-select-color' ),
			selectorIDs = [];

		// Return early if we don't have any selectors.
		if ( ! $selectors.length ) {
			return;
		}

		$selectors.each( function() {
			if ( $( this ).data( 'id' ) ) {
				selectorIDs.push( $( this ).data( 'id' ) );
			}
		} );

		if ( selectorIDs.length ) {
			$.ajax( {
				method: 'POST',
				url: window.ajaxurl,
				data: {
					action:    'nice_option_update_select_color',
					nonce:     window.generalData.playNiceNonce,
					selectors: selectorIDs
				},
				success: function( response ) {
					response = JSON.parse( response );

					$selectors.each( function() {
						if ( $( this ).data( 'id' ) && 'undefined' !== typeof response[ $( this ).data( 'id' ) ] ) {
							$( this ).replaceWith( response[ $( this ).data( 'id' ) ] );
						}
					} );

					initSelectColor();
				}
			} );
		}
	},

	/**
	 * Make sure sections are ony displayed if they have visible settings
	 * to show.
	 *
	 * @since 2.0.8
	 */
	watchSections = function() {
		$( '.format-settings' ).bind( 'NiceSettingToggled', function( e ) {
			var $parent = $( this ).parent(),
				$group  = $parent.prev( '.nice-group-title' ),
				groupIsVisible = false;

			$parent.children( '.format-settings' ).each( function() {
				if ( 'none' !== $( this ).css( 'display' ) ) {
					groupIsVisible = true;
					return;
				}
			} );

			if ( groupIsVisible ) {
				$group.show();
			} else {
				$group.hide();
			}
		} );
	},

	/**
	 * Fire events on document ready, and bind other events.
	 *
	 * @since 2.0
	 */
	ready = function() {
		initConditions();
		initMediaUploader();
		radioImageSelect();
		colorPicker();
		initNiceTooltip();
		initTabs();
		initAccordion();
		handleToggle();
		handleFancybox();
		toggleTermFilter();
		modifyFilterText();
		setupListItems();
		initSelectColor();
		watchColorSelectors();
		watchSections();
	};

	// Expose the ready function to the world.
	return {
		ready: ready,
		initSelectColor: initSelectColor
	};

} )( jQuery );

jQuery( NiceFrameworkGeneral.ready );

/**
	Tumblog functions
**/

function nice_tumblog_fix_css() {
	jQuery( '#nice-metabox' ).addClass( 'nice-row' );
	$row = jQuery( 'div.format-settings:visible:last', '' );
	$row.addClass( 'border-bottom-none' );
}

function nice_tumblogStandard() {
	jQuery( 'div#setting_aside-info' ).hide();
	jQuery( 'div#setting_video-info' ).hide();
	jQuery( 'div#setting_image-info' ).hide();
	jQuery( 'div#setting_link' ).hide();
	jQuery( 'div#setting_quote' ).hide();
	jQuery( 'div#setting_quote-author' ).hide();
	jQuery( 'div#setting_link-info' ).hide();
	jQuery( 'div#setting_quote-info' ).hide();
	jQuery( 'div#setting_gallery-info' ).hide();
	jQuery( 'div#setting_audio_mp3' ).hide();
	jQuery( 'div#setting_audio_oga' ).hide();
	jQuery( 'div#setting_embed' ).show();
	nice_tumblog_fix_css();
}

function nice_tumblogAside() {
	jQuery( 'div#setting_video-info' ).hide();
	jQuery( 'div#setting_image-info' ).hide();
	jQuery( 'div#setting_link' ).hide();
	jQuery( 'div#setting_quote' ).hide();
	jQuery( 'div#setting_quote-author' ).hide();
	jQuery( 'div#setting_link-info' ).hide();
	jQuery( 'div#setting_quote-info' ).hide();
	jQuery( 'div#setting_gallery-info' ).hide();
	jQuery( 'div#setting_audio_mp3' ).hide();
	jQuery( 'div#setting_audio_oga' ).hide();
	jQuery( 'div#setting_aside-info' ).show();
	jQuery( 'div#setting_embed' ).show();
	nice_tumblog_fix_css();
}

function nice_tumblogVideo() {
	jQuery( 'div#setting_aside-info' ).hide();
	jQuery( 'div#setting_image-info' ).hide();
	jQuery( 'div#setting_quote' ).hide();
	jQuery( 'div#setting_quote-author' ).hide();
	jQuery( 'div#setting_quote-info' ).hide();
	jQuery( 'div#setting_link' ).hide();
	jQuery( 'div#setting_link-info' ).hide();
	jQuery( 'div#setting_gallery-info' ).hide();
	jQuery( 'div#setting_audio-info' ).hide();
	jQuery( 'div#setting_audio_mp3' ).hide();
	jQuery( 'div#setting_audio_oga' ).hide();
	jQuery( 'div#setting_video-info' ).show();
	jQuery( 'div#setting_embed').show();
	nice_tumblog_fix_css();
}

function nice_tumblogImage() {
	jQuery( 'div#setting_aside-info' ).hide();
	jQuery( 'div#setting_video-info' ).hide();
	jQuery( 'div#setting_embed' ).hide();
	jQuery( 'div#setting_quote-info' ).hide();
	jQuery( 'div#setting_quote' ).hide();
	jQuery( 'div#setting_quote-author' ).hide();
	jQuery( 'div#setting_link' ).hide();
	jQuery( 'div#setting_link-info' ).hide();
	jQuery( 'div#setting_gallery-info' ).hide();
	jQuery( 'div#setting_audio-info' ).hide();
	jQuery( 'div#setting_audio_mp3' ).hide();
	jQuery( 'div#setting_audio_oga' ).hide();
	jQuery( 'div#setting_image-info' ).show();
	nice_tumblog_fix_css();
}

function nice_tumblogQuote() {
	jQuery( 'div#setting_aside-info' ).hide();
	jQuery( 'div#setting_video-info' ).hide();
	jQuery( 'div#setting_embed' ).hide();
	jQuery( 'div#setting_image-info' ).hide();
	jQuery( 'div#setting_link' ).hide();
	jQuery( 'div#setting_link-info' ).hide();
	jQuery( 'div#setting_gallery-info' ).hide();
	jQuery( 'div#setting_audio-info' ).hide();
	jQuery( 'div#setting_audio_mp3' ).hide();
	jQuery( 'div#setting_audio_oga' ).hide();
	jQuery( 'div#setting_quote-info' ).show();
	jQuery( 'div#setting_quote' ).show();
	jQuery( 'div#setting_quote-author' ).show();
	nice_tumblog_fix_css();
}

function nice_tumblogLink() {
	jQuery( 'div#setting_aside-info' ).hide();
	jQuery( 'div#setting_video-info' ).hide();
	jQuery( 'div#setting_embed' ).hide();
	jQuery( 'div#setting_image-info' ).hide();
	jQuery( 'div#setting_quote' ).hide();
	jQuery( 'div#setting_quote-author' ).hide();
	jQuery( 'div#setting_quote-info' ).hide();
	jQuery( 'div#setting_gallery-info' ).hide();
	jQuery( 'div#setting_audio-info' ).hide();
	jQuery( 'div#setting_audio_mp3' ).hide();
	jQuery( 'div#setting_audio_oga' ).hide();
	jQuery( 'div#setting_link-info' ).show();
	jQuery( 'div#setting_link').show();
	nice_tumblog_fix_css();
}

function nice_tumblogGallery() {
	jQuery( 'div#setting_aside-info' ).hide();
	jQuery( 'div#setting_video-info' ).hide();
	jQuery( 'div#setting_embed' ).hide();
	jQuery( 'div#setting_image-info' ).hide();
	jQuery( 'div#setting_quote' ).hide();
	jQuery( 'div#setting_quote-author' ).hide();
	jQuery( 'div#setting_quote-info' ).hide();
	jQuery( 'div#setting_link' ).hide();
	jQuery( 'div#setting_link-info' ).hide();
	jQuery( 'div#setting_audio-info' ).hide();
	jQuery( 'div#setting_audio_mp3' ).hide();
	jQuery( 'div#setting_audio_oga' ).hide();
	jQuery( 'div#setting_gallery-info' ).show();
	nice_tumblog_fix_css();
}

function nice_tumblogAudio() {
	jQuery( 'div#setting_aside-info' ).hide();
	jQuery( 'div#setting_video-info' ).hide();
	jQuery( 'div#setting_embed' ).hide();
	jQuery( 'div#setting_image-info' ).hide();
	jQuery( 'div#setting_quote' ).hide();
	jQuery( 'div#setting_quote-author' ).hide();
	jQuery( 'div#setting_quote-info' ).hide();
	jQuery( 'div#setting_link' ).hide();
	jQuery( 'div#setting_link-info' ).hide();
	jQuery( 'div#setting_gallery-info' ).hide();
	jQuery( 'div#setting_audio-info' ).show();
	jQuery( 'div#setting_audio_mp3' ).show();
	jQuery( 'div#setting_audio_oga' ).show();
	nice_tumblog_fix_css();
}

jQuery(document).ready( function() {

	// actions
	if ( jQuery('#post-format-0').is(':checked') ) {
		nice_tumblogStandard();
	}
	jQuery('#post-format-0').click( nice_tumblogStandard );

	if ( jQuery('#post-format-aside').is(':checked') ) {
		nice_tumblogAside();
	}
	jQuery('#post-format-aside').click( nice_tumblogAside );

	if ( jQuery('#post-format-video').is(':checked') ) {
		nice_tumblogVideo();
	}
	jQuery('#post-format-video').click( nice_tumblogVideo );

	if ( jQuery('#post-format-image').is(':checked') ) {
		nice_tumblogImage();
	}
	jQuery('#post-format-image').click( nice_tumblogImage );

	if ( jQuery('#post-format-quote').is(':checked') ) {
		nice_tumblogQuote();
	}
	jQuery('#post-format-quote').click( nice_tumblogQuote );

	if ( jQuery('#post-format-link').is(':checked') ) {
		nice_tumblogLink();
	}
	jQuery('#post-format-link').click( nice_tumblogLink );

	if ( jQuery('#post-format-gallery').is(':checked') ) {
		nice_tumblogGallery();
	}
	jQuery('#post-format-gallery').click( nice_tumblogGallery );

	if ( jQuery('#post-format-audio').is(':checked') ) {
		nice_tumblogAudio();
	}
	jQuery('#post-format-audio').click( nice_tumblogAudio );


});

jQuery( 'body' ).addClass( 'mobile' );