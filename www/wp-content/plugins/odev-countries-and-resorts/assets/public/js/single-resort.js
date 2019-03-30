/**
 * Created by aleksandrfishchenko on 20.09.16.
 */


//CREATE PAGE MAP
jQuery(document).ready(function () {
    if (typeof(window.resortPosition) === 'undefined') {
        return;
    }
    var resortPosition = window.resortPosition;
    for (var i in resortPosition) {
        var optValue;
        optValue = parseFloat(resortPosition[i]);
        if (isNaN(optValue)) {
            optValue = 0;
        }
        resortPosition[i] = optValue;
    }

    var cMapOpt = {
        mapTypeid: google.maps.MapTypeId.ROADMAP,
        center: new google.maps.LatLng(resortPosition.lat, resortPosition.lng),
        zoom: resortPosition.zoom,
        scrollwheel: false
    };
    var cMap = new google.maps.Map(document.getElementById('resort-map'), cMapOpt);
    
    jQuery('.description-map-right').click(function(){
        jQuery(this).addClass('active');
        cMap.setOptions({'scrollwheel': true});
        setTimeout(function(){ google.maps.event.trigger(cMap, 'resize'); }, 500);
    });
    jQuery(document).click(function(event) {
        if (jQuery(event.target).closest(".description-map-right").length) return;
        setTimeout(function(){ google.maps.event.trigger(cMap, 'resize'); }, 500);
        cMap.setOptions({'scrollwheel': false});
        jQuery('.description-map-right').removeClass('active');
        event.stopPropagation();
    });

    if(jQuery('[data-gallery]').length){
        var images = jQuery('[data-gallery] img');
        jQuery('body').append(
            '<div class="gallery-modal" style="display: none; overflow-y: scroll;">' +
                '<div class="gallery-dialog">' +
                    '<a class="gallery-close"></a>' +
                    '<div class="gallery-container">' +
                        '<ul class="images-list">' +
                        '</ul>' +
                        '<a href="" class="gallery-previous"></a>' +
                        '<a href="" class="gallery-next"></a>' +
                        '<div class="gallery-dotnav">' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>');
        var gallery = jQuery('.gallery-modal');
        images.each(function(index, dom){
            jQuery(this).attr('data-index', index);
            gallery.find('.images-list').append('<img style="display: none;" data-index="' + index + '" src="' + jQuery(this).attr('src') + '">');
            gallery.find('gallery-dotnav').append('<button data-index="' + index + '"></button>');
        }).click(function(){
            gallery.find('.images-list img').eq(jQuery(this).attr('data-index')).addClass('active').show(0)
                .siblings().removeClass('active').hide(0);
            gallery.css('display', 'block').addClass('open');
            return false;
        });
        gallery.find('.gallery-close').click(function(){
            gallery.removeClass('open');
            setTimeout(function(){
                gallery.css('display', 'none');
            }, 150)
        });
        jQuery(document).click(function(event) {
            if (jQuery(event.target).closest(".gallery-container").length) return;
            gallery.removeClass('open');
            setTimeout(function(){
                gallery.css('display', 'none');
            }, 150);
            event.stopPropagation();
        });
        jQuery('.gallery-previous, .gallery-next').click(function(){
            var count = gallery.find('.images-list img').length;
            var current = gallery.find('.images-list img.active');
            current.removeClass('active').hide(0, function(){
                if(jQuery(this).hasClass('gallery-next')) {
                    var newIndex = current.attr('data-index') + 1;
                    if (newIndex == count)
                        newIndex = 0;
                    gallery.find('.images-list img').eq(newIndex).addClass('active').show(0);
                } else {
                    var newIndex = current.attr('data-index') - 1;
                    if (newIndex == -1)
                        newIndex = count - 1;
                    gallery.find('.images-list img').eq(newIndex).addClass('active').show(0);
                }
            });
            return false;
        });
    }
});
