/**
 * Created by aleksandrfishchenko on 07.07.16.
 */
"use strict";

function initializeMap(containerId) {
    var mapOptions = {
        mapTypeid: google.maps.MapTypeId.ROADMAP,
        center: new google.maps.LatLng(0, 0),
        zoom: 2
    };
    var map = new google.maps.Map(document.getElementById(containerId), mapOptions);
    return map;
}
function updateMapCoordinatesFromControls(mapHandler, latCtrlId, lngCtrlId, zoomCtrlId) {
    var lat = jQuery(latCtrlId).attr('value'),
        lng = jQuery(lngCtrlId).attr('value'),
        zoom = parseFloat(jQuery(zoomCtrlId).attr('value'));
    if(lat != '') {
        lat = parseFloat(lat);
    }
    else {
        lat = 0;
    }
    if(lng != '') {
        lng = parseFloat(lng);
    }
    else {
        lng = 0;
    }
    var mapLocation = new google.maps.LatLng(lat, lng);
    mapHandler.setCenter(mapLocation);
    mapHandler.setZoom(zoom);
}
function setMapCenterFromTitle (title, mapHandler) {
    var url = 'https://maps.googleapis.com/maps/api/geocode/json';
    var data = 'address=' + title;
    jQuery.getJSON(url, data, function(result) {
        var cityCoordinates;
        cityCoordinates = stripJSONToLocation(result);
        var cityLocation = new google.maps.LatLng(cityCoordinates.lat, cityCoordinates.lng);
        mapHandler.setCenter(cityLocation);
    });
}
function stripJSONToLocation(json) {
    return json['results'][0].geometry.location;
}
