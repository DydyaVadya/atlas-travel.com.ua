<?php
/**
 * Template part to display thumbnails Playlist-slider widget.
 *
 * @package Tourizto
 * @subpackage widgets
 */
?>
<div class="sp-thumbnail post-<?php the_ID(); ?>">
	<div class="sp-thumbnail-image-container">
		<?php echo $image; ?>
	</div>
	<div class="sp-thumbnail-text">
		<div class="entry-meta"><?php
			echo $date;
			echo $author;
			echo $comments;
		?></div>
		<?php echo $title; ?>
	</div>
</div>
