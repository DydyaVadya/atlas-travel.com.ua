<?php
/**
 * Template part to display a single layout.
 *
 * @package Tourizto
 * @subpackage widgets
 */
?>
<?php echo $this->render_layout( array(
	'layout'  => $this->instance['layout'],
	'wrapper' => '<div class="widget-fpblock__items widget-fpblock__items-%1$s">%2$s</div>',
) );
