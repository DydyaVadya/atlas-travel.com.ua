<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 15:11
 */
?>
<div class="form-horizontal form-group edit-form">
	<fieldset class="info-controls col-md-4 col-sm-12">
		<!-- Map Longtitude-->
		<div class="form-group">
			<label class="col-xs-3 control-label" for="<?php echo $postType; ?>_lng">Долгота</label>
			<div class="col-xs-9">
				<input id="<?php echo $postType; ?>-lng" name="<?php echo $postType; ?>_lng" value="<?php echo $lng;?>" type="text" placeholder="" class="form-control input-md">
			</div>
		</div>
		<!-- Map Latitude-->
		<div class="form-group">
			<label class="col-xs-3 control-label" for="<?php echo $postType; ?>_lat">Широта</label>
			<div class="col-xs-9">
				<input id="<?php echo $postType; ?>-lat" name="<?php echo $postType; ?>_lat" value="<?php echo $lat;?>" type="text" placeholder="" class="form-control input-md">
			</div>
		</div>
		<div class="form-group">
			<label for="" class="col-xs-3 control-label">Масштаб</label>
			<div class="col-xs-9">
				<div class="col-xs-2">
					<output id="<?php echo $postType; ?>-zoom-range"><?php echo ($zoom!='')?($zoom):('2');?></output>
				</div>
				<div class="col-xs-10">
					<input type="range" id="<?php echo $postType; ?>-zoom"  name="<?php echo $postType; ?>_zoom" value="<?php echo ($zoom != '')?($zoom):('2');?>" min="1" max="21">
				</div>
			</div>
		</div>
		<?php do_action('odev-map-metabox', $ID) ?>
	</fieldset>
	<fieldset class="map-container col-md-8 col-sm-12">
		<div id="<?php echo $postType; ?>-map"></div>
	</fieldset>
</div>
