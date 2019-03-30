<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 11.07.16
 * Time: 14:57
 */
?>

<div class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-7 control-label">ID модуля горящих:</label>
        <div class="col-xs-5">
            <input
                type="text"
                id="<?php echo $postType; ?>-hottours-id"
                name="<?php echo $postType; ?>_hottours_id"
                placeholder="XXXX"
                value="<?php echo $hottoursId; ?>"
                class="form-control"
            >
        </div>
    </div>
    <div class="form-group">
        <label for="<?php echo $postType; ?>_os_search_status"
               class="col-sm-7 control-label">Модуль поиска:</label>
        <div class="col-xs-5">
            <input
                type="checkbox"
                id="<?php echo $postType; ?>-os-search-status"
                name="<?php echo $postType; ?>_os_search_status"
                class="form-control"
                <?php echo $osSearchStatus; ?>
            >
        </div>
    </div>
    <?php do_action('odev-os-settings-metabox', $ID) ?>
</div>
