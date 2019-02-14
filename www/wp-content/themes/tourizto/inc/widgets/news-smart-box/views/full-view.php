<?php
/**
 * Template part to display full-view news-smart-box widget.
 *
 * @package Tourizto
 * @subpackage widgets
 */

?>
<div class="news-smart-box__item-inner">
	<div class="news-smart-box__item-header">
		<?php echo $image; ?>
	</div>
	<div class="news-smart-box__item-content">
		<div class="entry-meta"><?php
			echo $date;
			echo $author;
			echo $category;
			echo $tags;
			echo $comments;
		?></div>

		<?php echo $title; ?>
		<?php echo $excerpt; ?>
		<?php echo $more_btn; ?>
	</div>
</div>
