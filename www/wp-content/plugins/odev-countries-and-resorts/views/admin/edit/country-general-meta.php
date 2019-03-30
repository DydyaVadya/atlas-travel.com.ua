<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 12:05
 */
?>
<div class="form-horizontal form-group edit-form" >
	<fieldset class="info-controls col-md-6 col-sm-12">
		<!-- Country Title -->
		<div class="form-group">
			<label class="col-sm-3 control-label">Страна</label>
			<div class="col-sm-9">
				<input id="post_title" name="post_title" value="<?php echo $name;?>" type="text" placeholder="Название" class="form-control input-md" required="" disabled="disabled">
			</div>
		</div>
		<!-- Country Population -->
		<div class="form-group">
			<label class="col-sm-3 control-label" for="<?php echo $postType; ?>_population">Население</label>
			<div class="col-sm-9">
				<input name="<?php echo $postType; ?>_population" value="<?php echo $population;?>" type="text" placeholder="" class="form-control input-md">
			</div>
		</div>
		<!-- Country Territory -->
		<div class="form-group">
			<label class="col-sm-3 control-label" for="<?php echo $postType; ?>_territory">Площадь</label>
			<div class="col-sm-9">
				<input name="<?php echo $postType; ?>_territory" value="<?php echo $territory;?>" type="text" placeholder="" class="form-control input-md">
			</div>
		</div>
		<!-- Country Language-->
		<div class="form-group">
			<label class="col-sm-3 control-label" for="<?php echo $postType; ?>_language">Язык</label>
			<div class="col-sm-9">
				<input name="<?php echo $postType; ?>_language" value="<?php echo $language;?>" type="text" placeholder="" class="form-control input-md">
			</div>
		</div>
		<?php do_action('odev-country-general-metabox', $ID) ?>
	</fieldset>
</div>

