/**
 * Created by aleksandrfishchenko on 07.07.16.
 */
// RESORT MAP INIT
jQuery( document ).ready( function () {
    if ( !document.getElementById( 'resort-map' ) ) {
        return;
    }
    var resortMap = initializeMap( "resort-map" );
    updateMapCoordinatesFromControls( resortMap, '#resort-lat', '#resort-lng', '#resort-zoom' );

    resortMap.addListener( 'dragend', function () {
        var currentCenter = resortMap.getCenter();
        jQuery( '#resort-lat' ).attr( 'value', currentCenter.lat() );
        jQuery( '#resort-lng' ).attr( 'value', currentCenter.lng() );
    } );
    resortMap.addListener( 'zoom_changed', function () {
        var currentZoom = resortMap.getZoom();
        jQuery( '#resort-zoom' ).attr( 'value', currentZoom );
        jQuery( '#resort-zoom-range' ).html( currentZoom );
    } );

    jQuery( '#resort-lat' ).on( 'change input', function () {
        updateMapCoordinatesFromControls( resortMap, '#resort-lat', '#resort-lng', '#resort-zoom' );
    } );
    jQuery( '#resort-lng' ).on( 'change input', function () {
        updateMapCoordinatesFromControls( resortMap, '#resort-lat', '#resort-lng', '#resort-zoom' );
    } );
    jQuery( '#resort-zoom' ).on( 'change input', function () {
        jQuery( '#resort-zoom-range' ).html( this.value );
        updateMapCoordinatesFromControls( resortMap, '#resort-lat', '#resort-lng', '#resort-zoom' );
    } );
    jQuery( '#title' ).on( 'change', function () {
        setMapCenterFromTitle( jQuery( this ).attr( 'value' ), resortMap );
    } );
} );

// INIT COUNTRY AUTOCOMPLETE
jQuery( document ).ready( function () {
    if ( typeof(window.countryIdNamePairs) === 'undefined' ) {
        console.log( 'Error while loading countries data from server.' );
        return;
    }

    // Get elements from the page
    var countryIdNamePairs = window.countryIdNamePairs;
    var $countryInput = jQuery( '#resort-country' );
    var $resortCountryPostIdInput = jQuery( '#resort-country-post-id' );

    // Transoform data to jquery autocmplete source
    var countryAutocompleteData = countryIdNamePairs.map( function ( country, index ) {
        // Set current value to input
        if ( +country.id === +$resortCountryPostIdInput.val() ) {
            $countryInput.val( country.name );
        }
        return {
            "label": country.name,
            "value": country.id
        };
    } );


    // Init autocomplete
    $countryInput.autocomplete( {
        source: countryAutocompleteData,
        focus: function ( event, ui ) {
            if ( ui.hasOwnProperty( 'item' ) && ui.item.hasOwnProperty( 'label' ) ) {
                $countryInput.val( ui.item.label );

                event.preventDefault();
            }
        },
        select: function ( event, ui ) {
            if ( ui.hasOwnProperty( 'item' ) && ui.item.hasOwnProperty( 'label' ) ) {
                $countryInput.val( ui.item.label );
                $resortCountryPostIdInput.val( ui.item.value );

                event.preventDefault();
            }
        }
    } );
} )
