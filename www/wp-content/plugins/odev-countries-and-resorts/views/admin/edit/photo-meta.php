<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 08.07.16
 * Time: 17:53
 */
?>
<div class="<?php echo $postType; ?>-photos-b post-photos-b">
	<form>
		<div class="<?php echo $postType; ?>-photos-container post-photos-container">
			<div class="row"></div>
			<select name="<?php echo $postType; ?>_photos[]" id="post-photos" multiple="multiple">
			</select>
		</div>
		<!-- Image upload button -->
		<button type="button" id="upload-image-btn" name="upload-image-btn" class="btn btn-primary">Загрузить фото</button>
	</form>
</div>
