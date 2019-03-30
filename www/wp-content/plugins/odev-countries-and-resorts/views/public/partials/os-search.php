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
<script>
	var osGeo = "<?php echo $osSearchDefaultLocation; ?>"; // страна, курорт или отель по умолчанию в форме поиска
	var osTarget = "<?php if($_SERVER['SERVER_NAME'] == 'hotels-demo.odev.io') echo 'https://www.otpusk.com/info/os/e5/'; ?>"; // URL для отправки формы
	var osContainer = null; // Элемент DIV, в котором выводить результаты поиска
</script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&subset=cyrillic" rel="stylesheet">
<link rel="Stylesheet" href="https://export.otpusk.com/os/onsite/form.css" type="text/css" />
<link rel="Stylesheet" href="https://export.otpusk.com/os/onsite/result.css" type="text/css" />
<link rel="Stylesheet" href="https://export.otpusk.com/os/onsite/tour.css" type="text/css" />
<script src="https://export.otpusk.com/js/onsite/"></script>
<script src="https://export.otpusk.com/js/order"></script>