(function($){
    $(function(){
        jQuery(document).ready(function($){

            var ajaxRunning = false;

            $('.ajaxBtn').click(function(e){
                e.preventDefault();
                e.stopPropagation();
                if(ajaxRunning)
                    alert('Другой запрос в исполнении');
                else {
                    ajaxRunning = true;
                    var btn = $(this);
                    btn.siblings('.spinner').css('visibility', 'visible');
                    $.post(
                        wpAjaxUrl,
                        {
                            'action': 'change_all_widgets_status',
                            'submit': btn.attr('name'),
                            'catalog_utils_nonce': catalog_utils_nonce
                        },
                        function (response) {
                            ajaxRunning = false;
                            btn.siblings('.spinner').css('visibility', 'hidden');
                            alert(response);
                        }
                    );
                }
            });

        });
    });
})(jQuery);