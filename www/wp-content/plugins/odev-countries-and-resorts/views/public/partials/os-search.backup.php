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
<style type="text/css">
	/* Конфигурация стилей для формы поиска туров */
	.os-form-wrap {
		border: 5px solid #0799ff; /* цвет рамки вокруг формы */
		padding: 22px 40px; /* отступ от рамки до содержимого формы */
		width: 100%;
	}
	/* стиль рамки выпадающего списка туров */
	.ui-autocomplete {
		border:1px solid #0799ff;
	}
	.ui-autocomplete .ui-menu-item .ui-corner-all .price-from {
		color: #0799ff; /* цвет цен в списке туров */
	}
	.ui-autocomplete .ui-menu-item .ui-corner-all .label {
		color: #0799ff; /* цвет названий направлений в списке туров */
	}
	.ui-autocomplete .ui-menu-item .ui-corner-all.ui-state-focus {
		/* фон для выделенного элемента списка туров */
		background-color: #1db8ff;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#1db8ff), to(#057eff));
		background-image: -webkit-linear-gradient(top, #1db8ff, #057eff);
		background-image: -moz-linear-gradient(top, #1db8ff, #057eff);
		background-image: -ms-linear-gradient(top, #1db8ff, #057eff);
		background-image: -o-linear-gradient(top, #1db8ff, #057eff);
		background-image: linear-gradient(to bottom, #1db8ff, #057eff);
	}
	.ui-autocomplete .ui-menu-item .ui-corner-all.ui-state-focus .price-from{
		color: #ffffff;   /* цвет цены тура в выделенном эл-те списка туров */
	}
	.ui-autocomplete .ui-menu-item .ui-corner-all.ui-state-focus .label {
		color: #ffffff; /* цвет названий направлений в выделенном эл-те списка туров */
	}
	.os-block_select, .os-block_select option {
		color: #0799ff; /* цвет в списках выбора (select) */
	}
	.os-block_field, .os-os input[placeholder] {
		color: #0799ff !important; /* цвет текста в текстовых полях */
	}
	/* цвет рамки вокруг календарика */
	.datepicker {
		border: 1px solid #0799ff;
	}
	/* Стили для календаря (цвет шапки с месяцем и выделенной даты) */
	.table-condensed .prev, .table-condensed .next, .table-condensed .switch {
		/* цвет шапки календаря */
		background-color: #1db7ff;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#1db7ff), to(#0781ff));
		background-image: -webkit-linear-gradient(top, #1db7ff, #0781ff);
		background-image: -moz-linear-gradient(top, #1db7ff, #0781ff);
		background-image: -ms-linear-gradient(top, #1db7ff, #0781ff);
		background-image: -o-linear-gradient(top, #1db7ff, #0781ff);
		background-image: linear-gradient(to bottom, #1db7ff, #0781ff);
		color: #fff; /* цвет текста в шапке */
	}
	.table-condensed .active {
		background-color: #0799ff; /* фон для выбранной в календаре даты */
		color: #ffffff; /* цвет выделенной даты */
	}
	.os-l-link {
		/* цвет текста и подчеркивания ссылки "Уточнить "*/
		border-bottom: 1px dotted #0799ff;
		color: #0799ff;
	}
	.os-rating-values .selected, .selected .os-block_label_title, .selected .abbr, .os-price-slider .os-block-field {
		/* цвет текста для выбранных элементов в расширенных параметрах поиска */
		color: #0799ff;
	}
	.os-form-submit_button {
		/* цвет фона кнопки "Найти" */
		background-color: #32a9fd;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#32a9fd), to(#017bc9));
		background-image: -webkit-linear-gradient(top, #32a9fd, #017bc9);
		background-image: -moz-linear-gradient(top, #32a9fd, #017bc9);
		background-image: -ms-linear-gradient(top, #32a9fd, #017bc9);
		background-image: -o-linear-gradient(top, #32a9fd, #017bc9);
		background-image: linear-gradient(to bottom, #32a9fd, #017bc9);
		/* цвет текста на кнопке */
		color: #ffffff;
		/* стиль рамки */
		border:5px solid #dbdbdb;
	}
	.os-form-submit_button:hover {
		border-color: #BAEBFF; /* цвет рамки вокруг кнопки "Найти" при наведении */
	}
	.os-form-submit_button:active {
		/* цвет фона кнопки "Найти" при нажатии */
		background-color: #0075BF;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#0075BF), to(#32a9fd));
		background-image: -webkit-linear-gradient(top, #0075BF, #32a9fd);
		background-image: -moz-linear-gradient(top, #0075BF, #32a9fd);
		background-image: -ms-linear-gradient(top, #0075BF, #32a9fd);
		background-image: -o-linear-gradient(top, #0075BF, #32a9fd);
		background-image: linear-gradient(to bottom, #0075BF, #32a9fd);
	}
	/* ------------------------------------------------------------------------------ */
	/* Конфигурация стилей для результатов поиска туров*/
	/* Цвет ссылок в результатах поиска */
	.os-result-title_link, .os-result-description_link, .os-result-tour {
		color: #ED2629;
	}
	/* Стили для цен в результатах поиска */
	.os-offer-price_value, .os-offer-price_date, .os-offer-price_value_fcurrency {
		color: #ED2629; /* цвет надписей в цене */
	}
	.selected .os-offer-price {
		border: 1px solid #008dde !important; /* стиль рамки выделенной цены */
		/* фон выделенной цены */
		background-color: #efefef;
		background: -webkit-radial-gradient(#efefef 65%, #c4c4c4);
		background: -o-radial-gradient(#efefef 65%, #c4c4c4);
		background: -moz-radial-gradient(#efefef 65%, #c4c4c4);
		background: radial-gradient(#efefef 65%, #c4c4c4);
	}
	.os-offer-price_link {
		/* фон кнопки выбрать" в цене */
		background-color: #6dbbee;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#6dbbee), to(#035fdd));
		background-image: -webkit-linear-gradient(top, #6dbbee, #035fdd);
		background-image: -moz-linear-gradient(top, #6dbbee, #035fdd);
		background-image: -ms-linear-gradient(top, #6dbbee, #035fdd);
		background-image: -o-linear-gradient(top, #6dbbee, #035fdd);
		background-image: linear-gradient(to bottom, #6dbbee, #035fdd);
		/* цвет текста на кнопке "выбрать" в цене */
		color: #ffffff;
	}
	/* ------------------------------------------------------------------------------ */
	/* цвет ссылок и пунктов меню на странице тура*/
	.os-back_link, .os-tour-menu .item_link, .os-hotel-info-item_value a, .os-rating_link {
		color: #0799ff;
	}
	.os-food-room_link, .os-review-block_title_value, .os-review-block_toggle, .os-rating-detail_toggle_value {
		border-bottom: 1px dotted;
		color: #0799ff;
	}
	/* */
	.os-tour-info_people {

	}
	.os-tour_buy, .form-submit_button, a.os-tour_buy {
		/* цвет фона кнопок "Заказать" и "Отправить" */
		background-color: #32a9fd;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#32a9fd), to(#017bc9));
		background-image: -webkit-linear-gradient(top, #32a9fd, #017bc9);
		background-image: -moz-linear-gradient(top, #32a9fd, #017bc9);
		background-image: -ms-linear-gradient(top, #32a9fd, #017bc9);
		background-image: -o-linear-gradient(top, #32a9fd, #017bc9);
		background-image: linear-gradient(to bottom, #32a9fd, #017bc9);
		/* цвет текста на кнопках */
		color: #ffffff;
		/* стиль рамки */
		border:5px solid #dbdbdb;
	}
	/* цвет рамки кнопки при наведении на кнопку */
	.os-tour_buy:hover, .form-submit_button:hover {
		border-color: #BAEBFF !important; /* цвет рамки вокруг кнопки "Найти" при наведении */
	}
	.os-tour_buy:active, .form-submit_button:active, a.os-tour_buy:active {
		/* цвет фона кнопок "Заказать" и "Отправить" при нажатии */
		background-color: #0075BF;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#0075BF), to(#32a9fd));
		background-image: -webkit-linear-gradient(top, #0075BF, #32a9fd);
		background-image: -moz-linear-gradient(top, #0075BF, #32a9fd);
		background-image: -ms-linear-gradient(top, #0075BF, #32a9fd);
		background-image: -o-linear-gradient(top, #0075BF, #32a9fd);
		background-image: linear-gradient(to bottom, #0075BF, #32a9fd);
	}
	/* цвет надписей параметров тура (кол-во человек, дата вылета, тип номера, питание) */
	.os-tour-info_people, .os-tour-info-dates-from_value, .os-tour-info-dates-to_value,
	.os-tour-info-food_value, .os-tour-info_add .item.selected {
		color: #0799ff;
	}
	/* цвет цены тура в правой колонке */
	.os-tour-price_value {
		color: #cc3333;
	}
	/* цвет ссылок "закрыть" в таблицах с ценами на другие дни */
	.os-tour-info-dates_close, .os-tour-info-food_close {
		color: #c93636;
	}
	/* стили фона и рамочки блока с ценой в таблицах цен */
	.os-tour-info_date_price .date_link, .os-tour-info_food .date_link {
		border: 1px solid #cccccc; /* стиль рамки */
		/* оформление фона */
		background-color: #f9f9f9;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#f9f9f9), to(#ebebeb));
		background-image: -webkit-linear-gradient(top, #f9f9f9, #ebebeb);
		background-image: -moz-linear-gradient(top, #f9f9f9, #ebebeb);
		background-image: -ms-linear-gradient(top, #f9f9f9, #ebebeb);
		background-image: -o-linear-gradient(top, #f9f9f9, #ebebeb);
		background-image: linear-gradient(to bottom, #f9f9f9, #ebebeb);
	}
	/* цвета цен в таблице с ценами */
	.os-tour-info_date_price .date_link .uah,  .os-tour-info_food .date_link .uah{
		color: #666666;
	}
	.os-tour-info_date_price .date_link .usd, .os-tour-info_food .date_link .usd {
		color: #888888;
	}
	/* цвета цен при наведении на блок с ценой */
	.os-tour-info_date_price .date_link:hover .uah, .os-tour-info_food .date_link:hover .uah {
		color: #0799ff;
	}
	.os-tour-info_date_price .date_link:hover .usd, .os-tour-info_food .date_link:hover .usd {
		color: #0799ff;
	}
	/* оформление выделенной цены в таблице цен */
	.os-tour-info_date_price .date_link.selected, .os-tour-info_food .date_link.selected {
		border: 1px solid #0977BC; /* стиль рамки */
		/* фон выделенной цены */
		background-color: #0977bc;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#0977bc), to(#35a8f4 60%));
		background-image: -webkit-linear-gradient(top, #0977bc, #35a8f4 60%);
		background-image: -moz-linear-gradient(top, #0977bc, #35a8f4 60%);
		background-image: -ms-linear-gradient(top, #0977bc, #35a8f4 60%);
		background-image: -o-linear-gradient(top, #0977bc, #35a8f4 60%);
		background-image: linear-gradient(to bottom, #0977bc, #35a8f4 60%);
	}
	/* цвет надписей выделенной цены */
	.os-tour-info_date_price .date_link.selected .uah, .os-tour-info_food .date_link.selected .uah {
		color: #ffffff;
	}
	.os-tour-info_date_price .date_link.selected .usd, .os-tour-info_food .date_link.selected .usd {
		color: #ffffff;
	}
	/* стиль кнопки "изменить "*/
	.os-tour-info-dates_change, .os-tour-info-food_change {
		/* цвет фона кнопки */
		background-color: #f7f7f7;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#f7f7f7), to(#e4e4e4));
		background-image: -webkit-linear-gradient(top, #f7f7f7, #e4e4e4);
		background-image: -moz-linear-gradient(top, #f7f7f7, #e4e4e4);
		background-image: -ms-linear-gradient(top, #f7f7f7, #e4e4e4);
		background-image: -o-linear-gradient(top, #f7f7f7, #e4e4e4);
		background-image: linear-gradient(to bottom, #f7f7f7, #e4e4e4);
		/* стиль рамки */
		border: 1px solid #e4e4e4;
		/* цвет надписи */
		color: #666;
	}
	/* стиль кнопок "изменить" при наведении */
	.os-tour-info-block:hover .os-tour-info-dates_change, .os-tour-info-block:hover .os-tour-info-food_change, .os-tour-info-block:hover .os-tour-info-food_change {
		color: #FfFfFf; /* цвет надписи на кнопке */
		/* цвет фона кнопки */
		background-color: #32a9fd;
		background-image: -webkit-gradient(linear, left top, left bottom, from(#32a9fd), to(#017bc9));
		background-image: -webkit-linear-gradient(top, #32a9fd, #017bc9);
		background-image: -moz-linear-gradient(top, #32a9fd, #017bc9);
		background-image: -ms-linear-gradient(top, #32a9fd, #017bc9);
		background-image: -o-linear-gradient(top, #32a9fd, #017bc9);
		background-image: linear-gradient(to bottom, #32a9fd, #017bc9);
	}
</style>
<script>
	var osGeo = "<?php echo $osSearchDefaultLocation; ?>"; // страна, курорт или отель по умолчанию в форме поиска
	var osTarget = ""; // URL для отправки формы
	var osContainer = null; // Элемент DIV, в котором выводить результаты поиска
</script>
<script src="https://export.otpusk.com/js"></script>
