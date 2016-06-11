/**
 * Conductor Widget - Custom Fields Add-On
 */
var conductor = conductor || {};

( function ( $ ) {
	"use strict";

	// Defaults

	// Extend Conductor Widget Backbone Views
	conductor.widgets.conductor.views = $.extend( conductor.widgets.conductor.views, {
		output_custom_fields: Backbone.View.extend( {
			el: '.conductor-output',
			$widget: false,
			widget_output_view: false,
			$custom_fields: false,
			// jQuery Autocomplete options
			autocomplete_options: {
				source: conductor.widgets.conductor.custom_fields.slice( 0 ), // Create a copy of the original source,
				delay: 50,
				autoFocus: true
			},
			events : {
				// "change" event on custom fields AutoComplete input element
				'change .conductor-widget-custom-fields' : 'customFieldsChange',
				'input .conductor-widget-custom-fields' : 'customFieldsChange',
				'propertychange .conductor-widget-custom-fields' : 'customFieldsChange'
			},
			initialize: function() {
				var self = this;

				// Bind "this" to all functions/callbacks
				_.bindAll( this,
					'resetAutocompleteSource',
					'customFieldsChange',
					'removeElement',
					'autocompleteSelect',
					'destroy' );

				// Store a reference to the widget
				this.$widget = this.$el.parents( '.widget' );

				// Store a reference to the "output" view
				this.widget_output_view = this.$widget.data( 'conductor-output' );

				// Store a reference to the custom fields input box
				this.$custom_fields = this.$el.find( '.conductor-widget-custom-fields' );


				// Reset jQuery Autocomplete Source
				this.resetAutocompleteSource();

				/*
				 * jQuery Autocomplete
				 */
				// Add jQuery AutoComplete event handlers
				this.autocomplete_options.select = this.autocompleteSelect; // When the user has selected a value

				// Initialize jQuery AutoComplete with a custom CSS class
				this.$custom_fields.autocomplete( this.autocomplete_options ).autocomplete( 'widget' ).addClass( 'conductor-custom-fields' );

				// Only on the customizer (add special CSS class to Autocomplete UI)
				if ( conductor.hasOwnProperty( 'is_customizer' ) && conductor.is_customizer === '1' ) {
					this.$custom_fields.autocomplete( 'widget' ).addClass( 'conductor-custom-fields-customizer' );
				}

				/*
				 * listenTo
				 */
				this.listenTo( this.widget_output_view, 'removeElement', this.removeElement );
			},
			// Reset the jQuery Autocomplete source for this particular view (ensures that each widget has its own custom field data)
			resetAutocompleteSource: function() {
				var source = conductor.widgets.conductor.custom_fields.slice( 0 ); // Create a copy of the original source
				this.autocomplete_options.source = source; // Reset the autocomplete source

				// Adjust AutoComplete source based on custom fields in this widget
				_.each( this.widget_output_view.collection.models, function( model ) {
					// Adjust the "source" for this autocomplete widget
					if ( model.get( 'type' ) === 'custom_field' ) {
						source.splice( source.map( function( cf ) { return cf.value; } ).indexOf( model.get( 'id' ) ), 1 ); // Remove this custom field
					}
				} );
			},
			// Triggered when the custom fields input is changed and prevents the Customizer from refreshing since this field is arbitrary
			customFieldsChange: function( event ) {
				// Only on the customizer
				if ( conductor.hasOwnProperty( 'is_customizer' ) && conductor.is_customizer === '1' ) {
					event.stopImmediatePropagation(); // prevent this event from firing to other callbacks/event handlers
				}
			},
			// Triggered before an element is removed from Conductor Widget output elements
			removeElement: function( model ) {
				// Reset jQuery Autocomplete Source
				this.resetAutocompleteSource();

				var source = this.autocomplete_options.source,
					original_cf = [],
					cf_index =  source.map( function( cf ) { return cf.value; } ).indexOf( model.get( 'id' ) );

				// Adjust the "source" for this autocomplete widget
				if ( model.get( 'type' ) === 'custom_field' ) {
					original_cf = conductor.widgets.conductor.custom_fields.map( function( cf ) { return cf.value; } ).indexOf( model.get( 'id' ) );
					original_cf = conductor.widgets.conductor.custom_fields[original_cf];

					// Check to see if this custom field already exists and update the properties
					if ( cf_index !== -1 && original_cf.hasOwnProperty( 'label' ) && original_cf.hasOwnProperty( 'value' ) ) {
						source[cf_index] = {
							label: original_cf.label,
							value: source[cf_index]
						};

						// Sort alphabetically based on label
						source.sort( this.sortByCustomFieldLabel );

						this.$custom_fields.autocomplete( 'option', 'source', source ); // Reset the source
					}
					// Add this custom field back to the source if it doesn't already exist
					else if ( source.map( function( cf ) { return cf.value; } ).indexOf( model.get( 'id' ) ) === -1 && original_cf.hasOwnProperty( 'label' ) && original_cf.hasOwnProperty( 'value' ) ) {
						source.push( original_cf ); // Add to the end of the data set

						// Sort alphabetically based on label
						source.sort( this.sortByCustomFieldLabel );

						this.$custom_fields.autocomplete( 'option', 'source', source ); // Reset the source
					}
				}
			},
			// jQuery Autocomplete select callback
			autocompleteSelect: function( event, ui ) {
				// Reset jQuery Autocomplete Source
				this.resetAutocompleteSource();

				var output_list_length = this.widget_output_view.collection.length,
					step_size = conductor.widgets.conductor.output.priority_step_size,
					source = this.autocomplete_options.source;

				// Append the new item to the output list and refresh jQuery Sortable to ensure it recognizes the new element
				this.widget_output_view.$output_list.append( _.template( conductor.widgets.conductor.templates.custom_field )( {
					priority: ( ( output_list_length + 1 ) * step_size ),
					custom_field: ui.item.value // Custom field name
				} ) ).sortable( 'refresh' ).trigger( 'sortstop' );

				// Adjust the "source" for this autocomplete widget
				source.splice( source.map( function( cf ) { return cf.value; } ).indexOf( ui.item.value ), 1 ); // Remove this custom field
				this.$custom_fields.autocomplete( 'option', 'source', source );

				// Clear the input box
				this.$custom_fields.val( '' );

				return false;
			},
			// Used as a callback/compare function when sorting arrays
			sortByCustomFieldLabel: function( a, b ) {
				var vals = { a: a.label.toLowerCase(), b: b.label.toLowerCase() };

				if ( vals.a < vals.b ) {
					return -1;
				}
				if ( vals.a > vals.b ) {
					return 1;
				}

				return 0;
			},
			// Completely destroy this view and all event handlers
			destroy: function() {
				this.undelegateEvents();
				this.remove();
			}
		} )
	} );

	// Extend Conductor Widget Backbone Views, underscore templates used to add to sortable elements
	conductor.widgets.conductor.templates = $.extend( conductor.widgets.conductor.templates, {
		custom_field: '<li class="ui-state-default conductor-widget-output-element conductor-widget-output-custom-field visible" ' +
						'data-priority="<%- priority %>" data-id="<%- custom_field %>" data-label="<%- custom_field %>" data-type="custom_field" data-type-label="custom field" data-visible="true">' +
						'<div class="dashicons dashicons-sort"></div> ' +
						'<span class="conductor-widget-output-element-label conductor-widget-output-element-label-editable editable-input">' +
							'<span class="label">' +
								'<%- custom_field %>' +
							'</span>' +
							' <div class="dashicons dashicons-edit"></div>' +
							'<div class="conductor-widget-output-element-label-input">' +
								'<input value="" placeholder="Enter custom field label..." data-original="<%- custom_field %>" data-current="" />' +
								'<div class="dashicons dashicons-no-alt conductor-widget-discard"></div>' +
								'<div class="dashicons dashicons-yes conductor-widget-save"></div>' +
							'</div>' +
						'</span>' +
						'<span class="conductor-widget-output-element-controls">' +
							'<div class="dashicons dashicons-no-alt conductor-widget-remove"></div> ' +
							'<div class="dashicons dashicons-visibility conductor-widget-visibility"></div> ' +
						'</span>' +
					'</li>'
	} );


	/**
	 * Document Ready
	 */
	$( function() {
		var $widgets = $( '.widget', '#widgets-right' );

		/*
		 * Custom Fields
		 */

		// Initialize Backbone Views on Conductor Widgets (on initial page load)
		$widgets.filter( '[id*="conductor-widget"]' ).each( function( i, el ) {
			// Create a new output view and store it in widget data
			$( el ).data( 'conductor-output-custom-fields', new conductor.widgets.conductor.views.output_custom_fields( {
				el: $( el ).find( '.conductor-output' ) // Attach this view to the widgets output list
			} ) );
		} );

		// Need to listen to the document for widget-added
		$( document ).on( 'widget-added', function( event, $widget ) {
			// Conductor Widgets
			if ( $widget.attr( 'id' ).indexOf( 'conductor-widget' ) !== -1 ) {
				// Store the output view in widget data
				$widget.data( 'conductor-output-custom-fields', new conductor.widgets.conductor.views.output_custom_fields( {
					el: $widget.find( '.conductor-output' ) // Attach this view to the widgets output list
				} ) );
			}
		} );

		// Need to listen to the document for widget-updated
		$( document ).on( 'widget-updated', function( event, $widget ) {
			var conductor_output_view = $widget.data( 'conductor-output-custom-fields' );

			// Only on Conductor widgets
			if ( conductor_output_view && conductor_output_view instanceof Backbone.View ) {
				// Destroy the current view (custom function to undelegateEvents prior to removal)
				conductor_output_view.destroy();

				// Store the new view in widget data
				$widget.data( 'conductor-output-custom-fields', new conductor.widgets.conductor.views.output_custom_fields( {
					el: $widget.find( '.conductor-output' ) // Attach this view to the widgets output list
				} ) );
			}
		} );
	} );
}( jQuery ) );