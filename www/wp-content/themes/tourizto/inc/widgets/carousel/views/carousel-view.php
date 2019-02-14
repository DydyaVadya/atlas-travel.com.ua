<?php
/**
 * Template part to display Carousel widget.
 *
 * @package Tourizto
 * @subpackage widgets
 */
?>

<div class="inner">
	<div class="content-wrapper">
		<figure class="post-thumbnail">
			<?php echo $image; ?>
		</figure>
		<div class="entry-meta">
			<?php echo $date; ?>
			<?php echo $author; ?>
			<?php echo $terms_line; ?>
		</div>
		<header class="entry-header">
			<?php echo $title; ?>
		</header>
		<div class="entry-content">
			<?php echo $content; ?>
		</div>
		<footer class="entry-footer">
			<?php echo $more_button; ?>
		</footer>
	</div>
</div>
