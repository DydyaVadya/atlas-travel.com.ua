/**
 * Created by aleksandrfishchenko on 23.09.16.
 */

(function($) {
  $(document).ready(function() {
      // Initialize rating slider
      var $hotelRateFromCtrl = $('#hotel-rate-from'),
          $hotelRateSliderCtrl = $('#hotel-rate-slider');
      $hotelRateSliderCtrl.slider({
          min: 4,
          max: 10,
          value: $hotelRateFromCtrl.val(),
          change: function(event, ui) {
              $hotelRateFromCtrl.val(ui.value);
          },
          slide: function(event, ui){
              $hotelRateFromCtrl.css('width', ((ui.value - 4) * 100 / 6) + "%");
          }
      });;
  })
})(jQuery);
