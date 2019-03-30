<?php
    /**
     * Created by PhpStorm.
     * User: aleksandrfishchenko
     * Date: 20.09.16
     * Time: 16:25
     */
?>

<div class="wrap">
    <form id="hotels-settings-form"
          action=""
          method="post">

        <h2>Импорт отелей</h2>

        <input type="hidden"
               name="import_mode"
               value="<?= $import_mode ?>">

        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label for="import-page">Страница:</label>
                </th>
                <td>
                    <input type="text"
                           id="import-page"
                           name="import_page"
                           value="<?= $import_page ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="count-hotels-per-query">К-во отелей для обработки на запрос:</label>
                    <p class="description">Максимум 555. Используется при ограничениях на сервере при импорте</p>
                </th>
                <td>
                    <input type="number"
                           min="0"
                           max="555"
                           id="count-hotels-per-query"
                           name="count-hotels-per-query"
                           value="<?= $count_hotels_per_query ?>">
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="hotels-per-query">Смещение отелей на странице в запросе:</label>
                </th>
                <td>
                    <input type="number"
                           min="0"
                           max="554"
                           id="offset-hotels-per-query"
                           name="offset-hotels-per-query"
                           value="<?= $offset_hotels_per_query ?>">
                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit"
                   id="import-hotels"
                   name="import_hotels"
                   class="button-primary"
                   value="Импортировать отели">
            <input type="submit"
                   id="import-hotel-offers"
                   name="import_hotel_offers"
                   class="button"
                   value="Импортировать минимальные цены">
        </p>

        <h2>Блок "Лучшее предложение"</h2>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    Открывать предложение
                    <p class="description">Если выбрано "главная страница", на ней должен быть размещен <a target="_blank" href="https://export.otpusk.com/search_module.php">модуль поиска</a></p>
                </th>
                <td>

	                <?php
	                $current_hotel_offer_target_value = HotelCPT::getActiveOfferTarget();
	                foreach (HotelCPT::getOfferTargets() as $hotel_offer_target):
                        $checked = $hotel_offer_target['value'] == $current_hotel_offer_target_value ? 'checked="checked"' : '';
                        ?>
                        <label style="display: block; padding-bottom: 5px;">
                            <input type="radio"
                                   name="hotel-offer-target"
                                   value="<?= $hotel_offer_target['value'] ?>"
                                   <?= $checked ?>>
                            <?= $hotel_offer_target['title'] ?>
                        </label>
                        <?php
                    endforeach;

                    $is_hotel_offer_blank = HotelCPT::isOfferTargetBlank();
                    $checked = $is_hotel_offer_blank ? 'checked="checked"' : '';
                    ?>
                    <label>
                        <input type="checkbox"
                               name="hotel-offer-target-is-blank"
                               value="1"
                               <?= $checked ?>>
                        Открывать в новой вкладке
                    </label>

                </td>
            </tr>
        </table>

        <p class="submit">
            <input type="submit"
                   name="save"
                   class="button-primary"
                   value="Сохранить">
        </p>

    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var importMode = jQuery('input[name=import_mode]').val();

        if (importMode === 'auto') {
            setTimeout(function () {
                jQuery('#import-hotels').trigger('click');
            }, 0);
        }
    })
</script>
