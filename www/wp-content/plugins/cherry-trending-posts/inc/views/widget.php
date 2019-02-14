<?php
/**
 * Represents the view for the `Cherry Trending Posts` widget.
 *
 * @package   Cherry_Trending_Posts
 * @author    Template Monster
 * @license   GPL-3.0+
 * @copyright 2012 - 2016, Template Monster
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} ?>

<div class="cherry-trend-widget-list__item cherry-trend-post">
	<?php echo $image; ?>
	<div class="cherry-trend-post__header">
		<?php echo '<h5 class="cherry-trend-post__title">' . $title . '</h5>'; ?>
	</div>
	<div class="cherry-trend-post__content">
		<?php echo $excerpt; ?>
		<div class="cherry-trend-post__meta">
			<?php echo $date; ?>
			<?php echo $author; ?>
			<?php echo $comments; ?>
			<?php echo $category; ?>
			<?php echo $tag; ?>
			<?php echo $view; ?>
			<?php echo $rating; ?>
		</div>
	</div>
	<?php echo $button; ?>
</div>
