( function( $, data ) {

	"use strict";

	var touriztoElementor = {

		init: function() {

			if ( ! _.isEmpty( data.widgets ) ) {
				_.each( data.widgets, touriztoElementor.widgetsWalker );
			}

		},

		widgetsWalker: function( widget ) {
			elementor.hooks.addAction( 'panel/open_editor/widget/wp-widget-' + widget, touriztoElementor.initCb );
		},

		initCb: function( panel, model, view ) {

			var initInterval;

			initInterval = setInterval( function() {

				var $controls = panel.$el.find( '.widget-content' );

				if ( $controls.length ) {

					window.clearInterval( initInterval );

					if ( CherryJsCore.ui_elements.iconpicker && window.cherry5IconSets ) {
						CherryJsCore.ui_elements.iconpicker.setIconsSets( window.cherry5IconSets );
					}

					$( 'body' ).trigger( {
						type: 'cherry-ui-elements-init',
						_target: $controls
					} );
				}
			}, 200 );

		}

	};

	touriztoElementor.init();

}( jQuery, window.touriztoEditData ) );
