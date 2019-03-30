<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 17:01
 */
?>
<div class="form-horizontal row display-meta-form">
	<fieldset class="info-controls col-sm-12">
		<div class="form-group">
			<div class="col-sm-12">
				<input id="<?php echo $postType; ?>_is_popular" name="<?php echo $postType; ?>_is_popular" <?php echo $countryPopular; ?>
				       type="checkbox" placeholder="" class="form-control input-md">
				<label for="<?php echo $postType; ?>_is_popular">Популярная страна</label>
			</div>
		</div>
		<?php do_action('odev-country-settings-metabox', $ID) ?>
	</fieldset>
</div>
