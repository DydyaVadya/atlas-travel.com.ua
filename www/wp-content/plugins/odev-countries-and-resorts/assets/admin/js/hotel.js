(function ( $ ) {
    $( document ).ready( function () {
        if ( typeof(window.resortIdNamePairs) === 'undefined' ) {
            console.log( 'Error while loading resorts list from server' );
            return;
        }

        // Get elements from the page
        var resortIdNamePairs = window.resortIdNamePairs;
        var $resortInput = $( '#hotel-resort' );
        var $hotelResortPostIdInput = $( '#hotel-resort-post-id' );

        // Transform resorts data to jquery autocomplete source
        var resortAutocompleteData = resortIdNamePairs.map( function ( resort ) {
            // Set current text value to resort input
            if ( +resort.id === +$hotelResortPostIdInput.val() ) {
                $resortInput.val( resort.name );
            }

            return {
                "label": resort.name,
                "value": resort.id
            };
        } );

        //Init resort autocomplete
        $resortInput.autocomplete( {
            source: resortAutocompleteData,
            focus: function ( event, ui ) {
                if ( ui.hasOwnProperty( 'item' ) && ui.item.hasOwnProperty( 'label' ) ) {
                    $resortInput.val( ui.item.label );

                    event.preventDefault();
                }
            },
            select: function ( event, ui ) {
                if(ui.hasOwnProperty('item') && ui.item.hasOwnProperty('label') && ui.item.hasOwnProperty('value')) {
                    $resortInput.val(ui.item.label);
                    $hotelResortPostIdInput.val(ui.item.value);
                    event.preventDefault();
                }
            }
        } );
    } )
})( jQuery );