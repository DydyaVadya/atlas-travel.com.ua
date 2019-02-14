/**
 * TM mega menu settings fields init
 *
 */
( function ($) {
	'use strict';

	$( '.jquery-ui-slider' ).each( function() {

		var slider = $( this ),
			input  = $( '#' + $( this ).data( 'id' ) );

		slider.slider( {
			range: $( this ).data( 'range' ),
			value: input.val(),
			min:   parseInt( $( this ).data( 'min' ) ),
			max:   parseInt( $( this ).data( 'max' ) ),
			slide: function( event, ui ) {
				input.val( ui.value );
			}
		} );

		input.change( function() {
			slider.slider( 'value', input.val() );
		} );

	} );
} )( jQuery );