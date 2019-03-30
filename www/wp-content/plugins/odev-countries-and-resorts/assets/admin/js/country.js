/**
 * Created by aleksandrfishchenko on 07.07.16.
 */
jQuery(document).ready(function() {
    var countryMap = initializeMap("country-map")
    updateMapCoordinatesFromControls(countryMap, '#country-lat', '#country-lng', '#country-zoom');

    countryMap.addListener('dragend', function() {
        var currentCenter = countryMap.getCenter();
        jQuery('#country-lat').attr('value', currentCenter.lat());
        jQuery('#country-lng').attr('value', currentCenter.lng());
    });
    countryMap.addListener('zoom_changed', function() {
        var currentZoom = countryMap.getZoom();
        jQuery('#country-zoom').attr('value', currentZoom);
        jQuery('#country-zoom-range').html(currentZoom);
    });
    jQuery('#country-lat').on('change input',function() {
        updateMapCoordinatesFromControls(countryMap, '#country-lat', '#country-lng', '#country-zoom');
    });
    jQuery('#country-lng').on('change input',function() {
        updateMapCoordinatesFromControls(countryMap, '#country-lat', '#country-lng', '#country-zoom');
    });
    jQuery('#country-zoom').on('change input',function() {
        jQuery('#country-zoom-range').html(this.value);
        updateMapCoordinatesFromControls(countryMap, '#country-lat', '#country-lng', '#country-zoom');
    });
    jQuery('#country_title').on('change', function() {
        setMapCenterFromTitle(jQuery(this).attr('value'), countryMap);
    });
});
