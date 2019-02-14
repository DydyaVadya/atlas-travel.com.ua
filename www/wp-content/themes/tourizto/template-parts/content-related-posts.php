<?php
/**
 * The template for displaying related posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Tourizto
 * @subpackage single-post
 */
?>
<div class="<?php echo esc_attr( $grid_class ); ?>">
	<article class="related-post hentry page-content">
		<figure class="post-thumbnail"><?php
			echo $image;
		?></figure>
		<div class="related-post__content">
			<header class="entry-header">
				<div class="entry-meta"><?php
					echo $date;
					echo $author;
					echo $category;
					echo $comment_count;
				?></div><?php

				echo $title;
			?></header>
			<div class="entry-content"><?php
				echo $excerpt;
			?></div>
			<footer class="entry-footer">
				<div class="entry-meta"><?php
					echo $tag;
				?></div>
			</footer>
		</div>
	</article><!--.related-post-->
</div>
