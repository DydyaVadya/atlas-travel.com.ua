/**
 * Created by aleksandrfishchenko on 22.09.16.
 */

//TODO: Refactor thumbnails rendering
jQuery(document).ready(function () {

    jQuery('#upload-image-btn').click(function () {
        openWPMediaUploader();
    });
    if (typeof(postThumbnails) != 'undefined') {
        var imageContainerHtml = '';
        postThumbnails.forEach(function (image, index, images) {
            imageContainerHtml += createImageThumbnail(image.url, image.caption, image.id);
            jQuery('#post-photos').append(jQuery('<option>', {
                value: image.id,
                text: image.url,
                selected: true
            }));
        });
        jQuery('.post-photos-container .row').append(imageContainerHtml);
        jQuery('.post-photos-container .remove-image').click(function () {
            var imgId = jQuery(this).parent().attr('post-photo-id');
            jQuery('#post-photos option[value=' + imgId + ']').first().remove();
            jQuery(this).parent().remove();

        });
    }
    if (jQuery(".thumbnail-image[post-photo-id='null']").length) {
        jQuery('.remove-image').hide();
    }
});

var wp_media_uploader = null;

function openWPMediaUploader() {
    wp_media_uploader = wp.media({
        frame: "post",
        state: "insert",
        multiple: false
    });

    wp_media_uploader.on("insert", function () {
        var countOfImages = wp_media_uploader.state().get("selection").length;
        var postImages = wp_media_uploader.state().get("selection").models;
        var imageContainerHtml = '';
        postImages.forEach(function (image, index, images) {
            imageContainerHtml += createImageThumbnail(image.changed.sizes.thumbnail.url, image.changed.caption, image.id);
            jQuery('#post-photos').append(jQuery('<option>', {
                value: image.id,
                text: image.changed.url,
                selected: true
            }));
        });
        jQuery('.post-photos-container .row').append(imageContainerHtml);
        jQuery('.post-photos-container .remove-image').click(function () {
            var imgId = jQuery(this).parent().attr('post-photo-id');
            jQuery('#post-photos option[value=' + imgId + ']').remove();
            jQuery(this).parent().remove();

        })
    });
    wp_media_uploader.open();
}

function createImageThumbnail(imgSrc, imgAlt, imgId) {
    jQuery('.country-photos-container .row').empty();
    var thumbnail = '';
    thumbnail += '<div class="thumbnail-image" post-photo-id="' + imgId + '">';
    thumbnail += '<a href="#" class="thumbnail">';
    thumbnail += '<img width="266" height="193" src="' + imgSrc + '" alt="' + imgAlt + '" />';
    thumbnail += '</a>';
    thumbnail += '<div class="remove-image">Удалить превью изображение</div>';
    thumbnail += '</div>';
    return thumbnail;
}