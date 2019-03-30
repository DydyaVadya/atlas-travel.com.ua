<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 18.07.16
 * Time: 10:25
 */
/*
 * @Input:
 * $osSearchDefaultLocation
*/
?>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url("odev-countries-and-resorts/assets/public/css/module-search.css"); ?>">
<script>
	var osGeo = "<?php echo $osSearchDefaultLocation; ?>"; // страна, курорт или отель по умолчанию в форме поиска
	var osTarget = "<?php if($_SERVER['SERVER_NAME'] == 'hotels-demo.odev.io') echo 'https://www.otpusk.com/info/os/e4/'; ?>"; // URL для отправки формы
	var osContainer = null; // Элемент DIV, в котором выводить результаты поиска
</script>
<script src="https://export.otpusk.com/js"></script>
<script>
	(function($) {
		$(document).ready(function() {
			$('.c-m_country-search-form').on('DOMSubtreeModified', function () {
				$('.medium-big').removeClass('medium-big');
				$('.medium-form').removeClass('medium-form');
				$('.small-form').removeClass('small-form');
			});
		});
	})(jQuery);
</script>
<style>
	.datepicker-days .table-condensed{
		margin-bottom: 0;
	}
	.datepicker thead tr{
		border: 1px solid #d7d7d7;
	}
	.datepicker .header{
		background-color: #ccc;
		border: 1px solid #ccc;
	}
	.datepicker .n-arr{
		float: right;
	}
	.datepicker .switch{
		text-align: center;
	}
</style>