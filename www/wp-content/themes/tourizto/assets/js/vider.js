
// появление шапки при скроле
jQuery(document).ready(function($) {
    $(window).scroll(function(){
        if ($(this).scrollTop() > 1) {
            $('.mobile-panel').addClass('fixed');
        } else {
            $('.mobile-panel').removeClass('fixed');
        }
    });
// конец

// затемнение фона при открытии меню
    $(document).ready(function(){
        $(".menu-toggle").click(function(){
            $(".milk").addClass("milk-shadow"); return false;
        });
    });
// конец
// убрать фон при крестике
    $(document).ready(function(){
        $(".close").click(function(){
            $(".milk").removeClass("milk-shadow"); return false;
        });
    });
// конец
})



