<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 16:19
 */
?>

<div class="form-horizontal text-form">
    <br>
    <?php foreach ( $texts as $key => $text ): ?>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon">Заголовок</span>
                    <input type="text"
                           class="form-control"
                           name="<?php echo $postType . '_text_title_' . $key; ?>"
                           value="<?php echo $text['title']; ?>"
                           placeholder="Заголовок">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <?php
                wp_editor( $text['content'],
                           $postType . '_text_content_' . $key );
                ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php do_action('odev-text-metabox', $ID) ?>
</div>
