<?php
/**
 * Template part to display loop-view news-smart-box widget.
 *
 * @package Tourizto
 * @subpackage widgets
 */
?>
<div id="news-smart-box-<?php echo esc_attr( $instance ); ?>" <?php echo $data_attr_line; ?>>
	<?php echo $this->get_navigation_box( $current_term_slug, $alt_terms_slug_list ); ?>
	<div class="news-smart-box__wrapper">
		<div class="news-smart-box__listing row">
			<?php echo $this->get_instance( $current_term_slug ); ?>
		</div>
	</div>
</div>
