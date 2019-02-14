<?php
/**
 * Template part to display Taxonomy-tiles widget.
 *
 * @package Tourizto
 * @subpackage widgets
 */
?>
<div class="widget-taxonomy-tiles__holder invert grid-item <?php echo $class; ?> term-<?php echo $term->term_id; ?>">
	<figure class="widget-taxonomy-tiles__inner">
		<a href="<?php echo $permalink; ?>"><?php echo $image; ?></a>
		<figcaption class="widget-taxonomy-tiles__content">
			<div class="widget-taxonomy-tiles__row">
				<?php echo $title; ?>
				<?php echo $count; ?>
			</div>

			<div class="widget-taxonomy-tiles__row widget-taxonomy-tiles__hidden-content">
				<?php echo $description; ?>

				<a href="<?php echo $permalink ?>" class="widget-taxonomy-tiles__permalink"><i class="nc-icon-mini arrows-1_simple-right"></i></a>
			</div>
		</figcaption>
	</figure>
</div>
