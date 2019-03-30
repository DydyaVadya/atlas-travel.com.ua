<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 22.09.16
 * Time: 16:37
 */
?>

<div class="row">
    <div class="col-xs-12">
        <!-- Module controls -->
        <div class="input-group">
            <span class="input-group-addon">
                <input type="checkbox"
                       name="<?php echo $postType . '_otpusk_module_status'; ?>"
                       id="otpusk-module-status" <?php echo($otpuskMdlStatus ? 'checked' : ''); ?>>
            </span>
            <label class="form-control"
                   for="otpusk-module-status">Виджет описания отеля</label>
        </div>
        <br>
        <div class="input-group">
            <span class="input-group-addon">
                <input type="checkbox"
                       name="<?php echo $postType . '_turpravda_module_staus'; ?>"
                       id="turpravda-module-status" <?php echo($turpravdaMdlStatus ? 'checked' : ''); ?>>
            </span>
            <label class="form-control"
                   for="turpravda-module-status">Виджет отзывов об отеле</label>
        </div>
        <br>

        <!-- Category control -->
        <div class="input-group">
            <span class="input-group-addon">Категория</span>
            <select name="<?php echo $postType . '_stars'; ?>"
                    class="form-control">
                <?php foreach ( $hCategories as $hCategory ): ?>
                    <option value="<?php echo $hCategory; ?>" <?php echo($hCategory === strtoupper( $hCurrentCategory ) ? 'selected' : ''); ?>>
                        <?php echo $hCategory; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>
        <!-- Parent country controls -->
        <div class="form-group">
            <label for="">Курорт</label>
            <div>
                <input type="text"
                       class="form-control"
                       id="hotel-resort"
                       required>
                <input type="hidden"
                       name="<?php echo $postType; ?>_resort_post_id"
                       id="<?php echo $postType; ?>-resort-post-id"
                       value="<?php echo $hotelResortPostId; ?>">
            </div>
        </div>
        <?php do_action('odev-hotel-settings-metabox', $ID) ?>
    </div>
</div>
