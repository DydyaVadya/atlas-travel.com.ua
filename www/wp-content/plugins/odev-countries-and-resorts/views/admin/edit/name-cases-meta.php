<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 19.07.16
 * Time: 17:30
 */
?>
<div class="form-horizontal row name-cases">
	<fieldset class="info-controls col-xs-12">
		<?php foreach ($casesNames as $case => $caseName): ?>
		<div class="row form-group">
			<div class="col-xs-12">
				<label><?php echo $caseName . ':'; ?></label>
			</div>
			<div class="col-xs-4">
				<input type="text" class="preposition-input" value="<?php echo $prepositionCases[$case]; ?>" name="preposition_<?php echo $case; ?>" id="preposition-<?php echo $case; ?>" placeholder="Предлог">
			</div>
			<div class="col-xs-8">
				<input type="text" class="preposition-input" value="<?php echo $nameCases[$case]; ?>" name="name_<?php echo $case; ?>" id="name-<?php echo $case; ?>" placeholder="<?php echo $caseName; ?>">
			</div>
		</div>
		<?php endforeach; ?>
		<?php do_action('odev-name-cases-metabox', $ID) ?>
	</fieldset>
</div>

