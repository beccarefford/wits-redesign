/**
 * Conductor Slider Customizer Preview
 */
( function ( wp, $ ) {
	"use strict";

	// Bail if the customizer isn't initialized
	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize, apiPreview;

	// Conductor Content Layouts Preview
	api.conductorSliderPreview = {
		init: function () {
			// When the previewer is active
			this.preview.bind( 'active', function() {
				// Trigger a resize event on the window (this will update Unslider slides)
				$( window ).resize();
			} );
		}
	};

	/**
	 * Capture the instance of the Preview since it is private
	 */
	apiPreview = api.Preview;
	api.Preview = apiPreview.extend( {
		initialize: function( params, options ) {
			api.conductorSliderPreview.preview = this;
			apiPreview.prototype.initialize.call( this, params, options );
		}
	} );

	// Document ready
	$( function () {
		// Init
		api.conductorSliderPreview.init();
	} );
} )( window.wp, jQuery );