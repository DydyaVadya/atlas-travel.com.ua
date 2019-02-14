<?php
/**
 * Template part to display Image grid widget.
 *
 * @package Tourizto
 * @subpackage widgets
 */
?>
<div class="widget-image-grid__holder invert <?php echo $columns_class; ?>">
	<figure class="widget-image-grid__inner">
		<?php echo $image; ?>
		<figcaption class="widget-image-grid__content">
			<?php echo $title; ?>

			<div class="entry-meta"><?php
				echo $date;
				echo $author;
				echo $terms;
			?></div>
		</figcaption>
	</figure>
</div>
