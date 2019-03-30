<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 18.07.16
 * Time: 10:26
 */
?>

<style>
	/*==== Стилизация для модуля горящих туров с типом "плитки" ====*/

	/* Отступы для контейнера, содержащего все блоки */
	body .hot-block_tiles .hot-wrapper {
		margin-top: 0px; /*отступ сверху*/
		margin-left: 0px; /*отступ слева*/
		margin-bottom: 0px; /*отступ снизу*/
		margin-right: -10px !important; /*Внимание!!! должно совпадать с положительным margin-right в .hot-otp-form-wrap ниже*/
	}

	/* Отступы между блоками */
	body .hot-block_tiles .hot-otp-form-wrap {
		margin-top: 10px; /*отступ сверху*/
		margin-right: 10px; /*отступ справа. Внимание!!! должно совпадать с отрицательным значением margin-right в .hot-wrapper выше*/
	}

	/* Ширина основного блока */
	body .hot-block_tiles .hot-otp-img,
	body .hot-block_tiles .hot-otp-tour-block {
		width: 310px !important; /*(в пределах от 240 до 360 пикселей)*/
		min-width: 0;
	}

	/* Градиент основного блока */
	body .hot-block_tiles .hot-otp-tour-block {
		background: -moz-linear-gradient(-10deg, rgba(32,32,32,0.75) 0%, rgba(32,32,32,0) 50%), -moz-linear-gradient(10deg, rgba(32,32,32,0.75) 0%, rgba(32,32,32,0) 50%);
		background: -webkit-linear-gradient(-10deg, rgba(32,32,32,0.75) 0%,rgba(32,32,32,0) 50%), -webkit-linear-gradient(10deg, rgba(32,32,32,0.75) 0%,rgba(32,32,32,0) 50%);
		background: linear-gradient(170deg, rgba(32,32,32,0.75) 0%,rgba(32,32,32,0) 50%), linear-gradient(10deg, rgba(32,32,32,0.75) 0%,rgba(32,32,32,0) 50%);
	}

	/* Скругление углов основного блока */
	body .hot-block_tiles .hot-otp-form-wrap {
		border-radius: 4px;
	}

	/* Плашка под нижний текст */
	body .hot-block_tiles .hot-otp-img:after {
		background: rgba(255,0,0,.7); /*фон плашки (цвет, градиент, картинка)*/
		height: 0; /*высота плашки, например: height: 73px;*/
	}

	/* Значение прозрачности фоновой картинки основного блока при наведении */
	body .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-img {
		opacity: .8;
	}

	/*=== Стилизация частей основного блока ===*/

	/*== 1. Заголовок блока (верхяя строка, большие буквы) ==*/
	body .hot-block_tiles .hot-otp-description {
		font-family: arial; /*семейство шрифта*/
		font-size: 18px; /*размер шрифта*/
		line-height: 21px; /*высота линии*/
		font-weight: bold; /*жирный шрифт, для обычного font-weight: normal;*/
		font-style: normal; /*без курсива, для курсива font-style: italic;*/
		color: #fff !important; /*цвет*/
		padding-top: 18px; /*отступ сверху*/
		padding-left: 20px; /*отступ слева*/
		padding-right: 20px;  /*отступ справа*/
	}
	/* При наведении мышки на основной блок */
	body .hot-block_tiles .hot-otp-tour-block:hover .hot-otp-description {
		text-decoration: none !important; /*подчеркивания нет, чтобы было text-decoration: underline*/
		color: #fff !important; /*цвет*/
	}

	/*== 2. Строка под заголовком (буквы поменьше) ==*/
	body .hot-block_tiles .hot-otp-place {
		font-family: arial; /*семейство шрифта*/
		font-size: 13px; /*размер шрифта*/
		line-height: 15px; /*высота линии*/
		font-weight: bold; /*жирный шрифт, для обычного font-weight: normal;*/
		font-style: normal !important; /*без курсива, для курсива font-style: italic*/
		color: #fff !important; /*цвет*/
		padding-top: 0; /*отступ сверху*/
		padding-left: 20px; /*отступ слева*/
		padding-right: 20px;  /*отступ справа*/
	}
	/* При наведении мышки на основной блок */
	body .hot-block_tiles .hot-otp-tour-block:hover .hot-otp-place {
		text-decoration: none !important; /*подчеркивания нет, чтобы было text-decoration: underline*/
		color: #fff !important; /*цвет*/
	}

	/*== 3. Строка с описанием тура в нижнем левом углу (курсив) ==*/
	body .hot-block_tiles .hot-otp-tour-info {
		font-family: arial; /*семейство шрифта*/
		font-size: 13px; /*размер шрифта*/
		line-height: 16px; /*высота линии*/
		font-weight: normal; /*обычный шрифт, для жирного font-weight: bold;*/
		font-style: italic; /*с курсивом, если без курсива font-style: normal;*/
		color: #fff; /*цвет*/
		left: 20px; /*отступ слева*/
		bottom: 13px; /*отступ снизу*/
		width: 130px; /*ширина блока*/
	}
	/* При наведении мышки на основной блок */
	body .hot-block_tiles .hot-otp-tour-block:hover .hot-otp-tour-info {
		text-decoration: none !important; /*подчеркивания нет, чтобы было text-decoration: underline*/
		color: #fff !important; /*цвет*/
	}

	/*== 4. Блок с ценой тура в нижнем правом углу (описание + цена) ==*/
	body .hot-block_tiles .hot-price-block {
		padding-right: 20px; /*отступ справа*/
		padding-bottom: 11px; /*отступ снизу*/
	}

    .hot-block_tiles .hot-otp-price{
        float: right;
    }

	/*== 4.1. Блок с ценой тура в нижнем правом углу (описание) ==*/
	body .hot-block_tiles .hot-otp-price-count,
	body .hot-block_tiles .hot-otp-price-count nobr {
		font-family: arial; /*семейство шрифта*/
		font-size: 11px; /*размер шрифта*/
		line-height: 10px; /*высота линии*/
		font-weight: normal; /*обычный шрифт, для жирного font-weight: bold;*/
		font-style: normal; /*без курсива, для курсива font-style: italic;*/
		color: #fff !important; /*цвет*/
	}
	body .hot-block_tiles .hot-otp-price-count {
		width: 60px; /*ширина блока*/
	}
	/* При наведении мышки на основной блок */
	body .hot-block_tiles .hot-otp-tour-block:hover .hot-otp-price-count,
	body .hot-block_tiles .hot-otp-tour-block:hover .hot-otp-price-count nobr {
		text-decoration: none !important; /*подчеркивания нет, чтобы было text-decoration: underline*/
		color: #fff !important; /*цвет*/
	}

	/*== 4.2. Блок с ценой тура в нижнем правом углу (цена) ==*/
	body .hot-block_tiles .hot-otp-price a {
		font-family: arial; /*семейство шрифта*/
		font-size: 24px; /*размер шрифта*/
		line-height: 24px; /*высота линии*/
		font-weight: bold; /*жирный шрифт, для обычного font-weight: normal;*/
		font-style: normal; /*без курсива, для курсива font-style: italic;*/
		color: #fff !important; /*цвет*/
        padding-left: 2px;
	}
	/* При наведении мышки на основной блок */
	body .hot-block_tiles .hot-otp-tour-block:hover .hot-otp-price a {
		text-decoration: none !important; /*подчеркивания нет, чтобы было text-decoration: underline*/
		color: #fff !important; /*цвет*/
	}
	
	
	
	
	.os-hottours-title{
        margin: 45px 0 25px 0;
    }
    .os-hottours-container .hot-block_tiles .hot-wrapper{
        margin: 0 0 0 -20px !important;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap{
        margin: 23px 0 0 0;
        width: 25%;
        box-sizing: border-box;
        display: inline-block;
        padding: 0 0 0 20px !important;
    }
	.os-hottours-container .hot-block_tiles .hot-otp-form-wrap:nth-of-type(4n + 1){
		clear: both;
	}
    .os-hottours-container .hot-block_tiles .hot-otp-img,
    .os-hottours-container .hot-block_tiles .hot-otp-img > a{
        height: 130px;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-img > a{
        display: block;
        overflow: hidden;
        max-width: 360px;
        border-radius: 3px; 
    }
    .os-hottours-container .hot-block_tiles .hot-otp-img,
    .os-hottours-container .hot-block_tiles .hot-otp-img img,
    .os-hottours-container .hot-block_tiles .hot-otp-tour-block {
        width: 100% !important;
        border-radius: 3px; 
    }  
    .os-hottours-container .hot-block_tiles .hot-otp-img img{
		min-height: initial !important;
		transform: translateY(-10%);
        z-index: 1;
        position: relative;
        min-width: 0;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-tour-block{
        background: #fff none !important;
        border: 1px solid #4e90c7;   
        margin: -3px 0 0 0;
        padding: 13px 13px 33px 13px;
        height: auto;
        position: relative;
    }
    body .os-hottours-container .hot-block_tiles .hot-otp-description{
        padding: 0;
        margin-top: 7px !important;
        font-size: 14px;
        font-weight: 300;
    }
    body .os-hottours-container .hot-block_tiles .hot-hidden-resort{
        display: inline;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-description,
    .os-hottours-container .hot-block_tiles *{
        color: #4e90c7 !important;
        font-size: 14px;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-info_place {
        margin: 5px 0 0 !important;
        font-size: 14px;
        color: #4e90c7 !important;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-place{
        font-size: 0;
        color: #4e90c7 !important;
        font-weight: 300;
        padding: 0;
    }
    .os-hottours-container .hot-otp-hotel-name{
        font-size: 20px;
    }
    .os-hottours-container .hot-otp-hotel-name,
    .os-hottours-container .hot-otp-hotel-name span{
        font-weight: 700;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info{
        position: relative;
        top: 19px;
        padding-left: 41px;
        width: 100%;
        left: 0;
        box-sizing: border-box;
        font-size: 0;
        line-height: 0;
        font-style: normal;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info *{
        position: relative;
        font-size: 14px;
        line-height: 17px;
        margin-top: 8px;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-transp-bl,
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-food-hb,
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-food-bl{
        display: block;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-transp-bl:before,
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-date-bl:before,
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-food-bl:before{
        content: "";
        display: block;
        position: absolute;
        left: -30px;
        top: -2px;
        width: 21px;
        height: 17px;
        background-size: 17px 17px;
        background-position: center;
        background-repeat: no-repeat;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-date-bl:before{
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAARCAYAAAA7bUf6AAACVUlEQVQ4T53SQUgUYRQH8P97M7Nu5ToaHdJKog7RISpIaEEiL1GQkwieIiIKisKcAunqVYR2twgMiojoEFHEFCRdjBBGSKiIiA6VLKEUia4a7O7M972YLWldV1iaw3zwvY/f/L/3hvD3cVJ+NwiPAHz13OS2pf3K1Un7WQBbABzz3KQX1Sl6Hbk23mAq6SbCHRByXl+ysSvlb60EnlxKTjoZfw4CWwSnQoMeP7+4f56clP8AkGkBvS1HnLQvlYjnJqkcIcgegJqpdFgk898IUd8fBBiBll5hOs/AJCseUYb+VJnEULxDGdIpkE3K1Bkz5GEAh5eQqDv3RcsEM8dF5AyA7VWa+5mIbmmt88S0D4LjpcZWu/tqk1ltvxyZIcFirYAmJAhYvyxJwGRH4zo09G5dXC+wd6V94eDAqNloo34uh8WXAx2hMziWyHNCv+jf/as7NdEcUjC1DInG13NjtL4QxHMAWAzZCcUuQc4K6CYMnSZFHwHoOitvB0WYmuKzK5DoS6gz5qOCIr3LEHYBnAZwW5FOG8LvS9ctqAaOBUZVJKo718daRMN82teejZIFxfgGK5b/+fBCx2JnZqyVGKHX2z7VlRptrIr0DHyIFez5eyBYRqDPKZOORi4AzwjlmbJ4GIKgLtdwIrB/rK2KlOsseq8GuyCchOAuQ6c18ZsoLUu+KVpXIBvXWLHZpi+6ON3aL4CFfJiWmNVG0AcE/IqKwWvETZeAINacHcK3zXaBaWapsSrCAYxD5Hut/wmIWgC0lQI5KX8QhMsAzJqBfwdDCK7+BpEyMu0PyN8AAAAAAElFTkSuQmCC");
    }
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-transp-bl:before{
        top: 2px;
        background-size: 21px 14px;
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAOCAYAAADABlfOAAAB2UlEQVQ4T6WTPWhTURiGnzcx1dqKCHURUcFBkC6iUG+2gDiZRBAXxUERBa1NKgUtSEkLFRQ1Scc46NwO2sRBB8VBkxTEQcHBRVwqKGIkYtXY+0mKoentTSJ4tnPe93sO35/4zxO98aKPUC0p4ziwA/RIHZlmQjKvrw4LrKmNGAwCPcu6Ki2hqZQFXm4qn8C4CmxBfMV4b2hCsn6MEaDX81lNpmMtodFs6baM03+DashyZpoXDAN9Phm+EpyZTTpzLaHxqfJuc20O2UNclQRDJrb7wOYNG9tbce6kUnLr+hI0du3ZhmBPqOve4MDn5qBounhY0hiwx6/2BlPrXLs8czG80KwrmimfEzYOrDWYrG78ke2trt8VXFy8btLBNo38Yt2hrYWz+757PYqli/eR4k3CR2BzI4s20Jv5pFNv1qqjo9PTwZ8ftuUwO9VxvJYNZsHAzsKFgXe+0KVHM8Wy5QngygqTeIvLqOCWp0n5fNJpzs4T1nSNZ0pDBhngk6HxamUh9zQV+R3LlJ4AkYbVlR14kAg/bpXZqpE6lH7udHf9ej1zPvKtEeSZ2Tf5xP5+vy1r+DuvaX3k0sVRpPpmYcbJwrBzt139/wkazZSPCLskWXI2ES52augfAMmj/JVEpy8AAAAASUVORK5CYII=");
    }
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-food-bl:before{
        top: 2px;
        background-size: 18px 12px;
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAAMCAYAAABvEu28AAABSElEQVQoU6XRP0hCURgF8HOeEg4SNDY0NEVDS0m9h0tQtIQvIXKouaUhn0M0BQ1ODvkiWqMiCvtj8KDAIYQINaitHFqCampsKvLdL4QSpcwX3fU798e53yVaHNMupQCxQMw6cWOrWZytoIl0ISfkGAQpJ2Es/gmaWs8H3yqBOQjHAekHEATwCuCK5EZb58P2QSzm1qPfGkVXCoNK4yGBMwBdAoz80OLarzGandefvmYNUHS1NKBEziGSBKkDMH95+qPvXYWOF8LP1UwNGl7O+9s7ArcQ3hNqV8Cmi63Ds45lTDZAZro4A2JHUzLkatwk0NvqI6pzl6rvJB6+qTUy7eIRgJAiRzWROy/IZ2bJsYxkDYrYxbIGlAWSAbjvHeKeY+nT9Y1OQeYIvIiSuGeIzDuWnqBpX/RAfBnPF5sEGVm77GbFTf8X+gCplGnUBVhYPAAAAABJRU5ErkJggg==");
    }
    .os-hottours-container .hot-block_tiles .hot-otp-tour-info .hot-otp-date-bl:after{
        content: " ";
    }
    .os-hottours-container .hot-block_tiles .hot-price-block{
        background-color: #a9cf55;
        border: 1px solid #fff;
        border-radius: 3px;
        padding: 6px 15px 0 15px;
        position: absolute;
        left: 12px;
        width: 180px;
        top: -26px;
        z-index: 2;
        height: 38px;
        font-size: 13px;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:first-child .hot-price-block{
        top: -26px !important;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-img{
        opacity: 1;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-tour-block{
        background-color: #4e90c7 !important;
    }
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-tour-block,
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-tour-block .hot-otp-description,
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-tour-block .hot-otp-place,
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-tour-block .hot-otp-description,
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-tour-block *{
        text-decoration: none !important;
        color: #fff !important;
    }

    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-tour-info .hot-otp-date-bl:before,
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-tour-info .hot-otp-transp-air:before,
    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:hover .hot-otp-tour-info .hot-otp-food-bl:before{
        -webkit-filter: brightness(10);
        filter: brightness(10);
    }

	@media screen and (max-width: 979px){
		.os-hottours-container .hot-block_tiles .hot-otp-form-wrap:nth-of-type(4n + 1){
			clear: none;
		}
		.os-hottours-container .hot-block_tiles .hot-otp-form-wrap:nth-of-type(3n + 1){
			clear: both;
		}
	}
    @media screen and (max-width: 767px){
		.os-hottours-container .hot-block_tiles .hot-otp-form-wrap:nth-of-type(3n + 1){
			clear: none;
		}
	    .os-hottours-container .hot-block_tiles .hot-otp-form-wrap:nth-of-type(2n + 1){
		    clear: both;
	    }
        .os-hottours-container .hot-block_tiles .hot-otp-form-wrap{
            width: 100%;
        }
        .os-hottours-container .hot-block_tiles .hot-otp-img,
        .os-hottours-container .hot-block_tiles .hot-otp-tour-block{
            margin-left: auto;
            margin-right: auto;
        }
    }
	@media screen and (max-width: 480px){
		.os-hottours-container .hot-block_tiles .hot-otp-form-wrap:nth-of-type(n + 1){
			clear: both;
		}
	}
</style>

<div id="otpusk_onsite_hot<?php echo $osHottoursModuleId; ?>"><img src="https://export.otpusk.com/os/ajax-loader.gif"/></div>
<script>
    window.otpHotToursRendered = function(tours) {
        var proposals = document.getElementsByClassName('hot-otp-form-wrap');
        var clearfix = document.createElement("div");
        clearfix.className = "clearfix";
        for(var i = 0; i < proposals.length; i++){
            if((i + 1) % 4 == 0){
                proposals[i].parentNode.insertBefore(clearfix, proposals[i].nextSibling);
            }
        }
    }
	var hottoursModuleId = <?php echo $osHottoursModuleId; ?>;
	window['osTarget' + hottoursModuleId] = ""; // URL расположения модуля поиска
	(function(d, s) {
		function delayedLoad() {
			var js, fjs = d.getElementsByTagName(s)[0];
			js = d.createElement(s);
			js = d.createElement(s);
			js.id = "OShotGetData" + hottoursModuleId;
			js.src = "https://export.otpusk.com/js/view?id=" + hottoursModuleId;
			fjs.parentNode.insertBefore(js, fjs);
		}
		if (window.addEventListener) {
			window.addEventListener("load", delayedLoad, false);
		} else if (window.attachEvent) {
			window.attachEvent("onload", delayedLoad);
		}
	}(document, "script"));

</script>
