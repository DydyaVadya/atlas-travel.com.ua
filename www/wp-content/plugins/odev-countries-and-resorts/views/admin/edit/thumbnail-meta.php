<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 08.07.16
 * Time: 17:53
 */
?>
<div class="form-horizontal">
    <div class="form-group">
        <div class="<?php echo $postType; ?>-photos-b post-photos-b">
            <form>
                <div class="<?php echo $postType; ?>-photos-container post-photos-container">
                    <div class="row"></div>
                    <select name="<?php echo $postType; ?>_thumbnails[]" id="post-photos">
                    </select>
                </div>
                <!-- Image upload button -->
                <a type="button" id="upload-image-btn" name="upload-image-btn" class="btn btn-primary">Задать превью изображение
                </a>
            </form>
        </div>
    </div>
</div>
