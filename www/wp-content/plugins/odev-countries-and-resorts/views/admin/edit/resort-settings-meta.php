<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 22.09.16
 * Time: 11:22
 */
?>

<div class="form-horizontal">
    <!-- Display options controls -->
    <div class="form-group">
        <label for=""
               class="control-label col-sm-6">Популярность курорта</label>
        <div class="col-sm-6">
            <?php foreach ( $types as $type => $typeLabel ): ?>
                <?php
                $typeId = $type .'-'. $postType . '-type';
                $typeName = $postType . '_display_type';
                ?>
                <div class="radio">
                    <label for="<?php echo $typeId; ?>">
                        <input type="radio"
                               id="<?php echo $typeId; ?>"
                               name="<?php echo $typeName; ?>"
                               aria-label="<?php echo $typeLabel; ?>"
                               value="<?php echo $type; ?>"
                               <?php echo( $type == $typeSelected ? 'checked' : '' ); ?>>
                        <?php echo $typeLabel; ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Parent country controls -->
    <div class="form-group">
        <label for=""
               class="col-sm-12">Страна</label>
        <div class="col-xs-12">
            <input type="text"
                   class="form-control" id="resort-country" required>
            <input type="hidden"
                   name="<?php echo $postType; ?>_country_post_id"
                   id="<?php echo $postType; ?>-country-post-id"
                   value="<?php echo $resortCountryPostId; ?>">
        </div>
    </div>
    <?php do_action('odev-resort-settings-metabox', $ID) ?>
</div>
