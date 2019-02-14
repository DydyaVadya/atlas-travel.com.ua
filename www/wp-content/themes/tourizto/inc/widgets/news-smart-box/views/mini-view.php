<?php
/**
 * Template part to display mini-view news-smart-box widget.
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
	</div>
</div>
