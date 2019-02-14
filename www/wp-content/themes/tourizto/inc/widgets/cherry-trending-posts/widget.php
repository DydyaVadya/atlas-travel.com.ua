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
		<?php echo '<h4 class="cherry-trend-post__title">' . $title . '</h4>'; ?>
	</div>
	<div class="cherry-trend-post__content">
		<?php echo $excerpt; ?>

		<div class="cherry-trend-post__meta cherry-trend-post__meta-first entry-meta"><?php
			echo $date;
			echo $author;
			echo $category;
		?></div>
		<div class="cherry-trend-post__meta cherry-trend-post__meta-second entry-meta"><?php
			echo $tag;
			echo $comments;
			echo $view;
			echo $rating;
		?></div>

	</div>
	<?php echo $button; ?>
</div>
