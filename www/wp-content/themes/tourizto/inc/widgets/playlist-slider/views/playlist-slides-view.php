<?php
/**
 * Template part to display slides Playlist-slider widget.
 *
 * @package Tourizto
 * @subpackage widgets
 */
?>
<div class="sp-slide format-<?php echo esc_attr( $post_format ); ?> <?php echo esc_attr( $is_invert ); ?> sp-slide--<?php echo esc_attr( $visible_content ); ?> post-<?php the_ID(); ?>">
	<div class="sp-layer" data-position="bottomLeft" data-horizontal="0" data-show-transition="up" data-show-delay="500" data-hide-transition="down">
		<div class="entry-meta"><?php
			echo $date;
			echo $author;
			echo $category;
			echo $tag;
			echo $comments;
		?></div>
		<header class="entry-header">
			<?php echo $title; ?>
		</header>
	</div>
	<?php echo $slide; ?>
</div>
