

jQuery(document).ready(function($) {
    $(window).scroll(function(){
        if ($(this).scrollTop() > 1) {
            $('.mobile-panel').addClass('fixed');
        } else {
            $('.mobile-panel').removeClass('fixed');
        }
    });
})
