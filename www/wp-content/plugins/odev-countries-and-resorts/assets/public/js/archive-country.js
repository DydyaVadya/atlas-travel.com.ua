/**
 * Created by aleksandrfishchenko on 15.07.16.
 */

// ADD TAB LISTNER
(function($){
    $(function(){
        jQuery(document).ready(function($){

            $('.continents-map a').click(function (e) {
                e.preventDefault();
                var targetTabHash = jQuery(this).attr('href');
                $('#continent-tabs a[href="' + targetTabHash + '"]').tab('show');
            });


        });
    });
})(jQuery);