/**
 * Created by aleksandrfishchenko on 15.07.16.
 */

//CREATE PAGE MAP
jQuery(document).ready(function() {
   if(typeof(window.countryPosition) === 'undefined') {
       return;
   }
   var countryPosition = window.countryPosition;
   for(var i in countryPosition) {
       var optValue;
       optValue = parseFloat(countryPosition[i]);
       if(isNaN(optValue)) {
           optValue = 0;
       }
       countryPosition[i] = optValue;
   }

   var cMapOpt = {
       mapTypeid: google.maps.MapTypeId.ROADMAP,
       center: new google.maps.LatLng(countryPosition.lat, countryPosition.lng),
       zoom: countryPosition.zoom,
       scrollwheel: false
   };
   var cMap = new google.maps.Map(document.getElementById('country-map'), cMapOpt);
   
    jQuery('#country-map').click(function(){
        setTimeout(function(){ google.maps.event.trigger(cMap, 'resize'); }, 500);
        cMap.setOptions({'scrollwheel': true});
        jQuery(this).addClass('active');
    });
    jQuery(document).click(function(event) {
        if (jQuery(event.target).closest("#country-map").length) return;
        setTimeout(function(){ google.maps.event.trigger(cMap, 'resize'); }, 500);
        cMap.setOptions({'scrollwheel': false});
        jQuery('#country-map').removeClass('active');
        event.stopPropagation();
    });
});
