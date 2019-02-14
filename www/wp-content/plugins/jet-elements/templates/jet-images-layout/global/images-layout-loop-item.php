<?php
/**
 * Images list item template
 */
$settings = $this->get_settings();
$col_class = '';
$data_attr = '';

if ( 'grid' === $settings['layout_type'] ) {
	$col_class = jet_elements_tools()->col_classes( array(
		'desk' => $this->__get_html( 'columns' ),
		'tab'  => $this->__get_html( 'columns_tablet' ),
		'mob'  => $this->__get_html( 'columns_mobile' ),
	) );
}

$link_type = $this->__loop_item( array( 'item_link_type' ), '%s' );

if ( 'lightbox' === $link_type ) {
	$link = $this->__loop_item( array( 'item_image', 'url' ), '%s' );
	$data_attr = 'data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . $this->get_id() . '"';
} else {
	$link = $this->__loop_item( array( 'item_url' ), '%s' );
}

?>
<div class="jet-images-layout__item <?php echo $col_class ?>">
	<div class="jet-images-layout__inner">
		<a class="jet-images-layout__link" href="<?php echo $link; ?>" <?php echo $data_attr; ?>>
			<div class="jet-images-layout__image">
				<?php
					if ( 'justify' === $settings['layout_type'] ) {
						echo $this->__loop_image_item( 'item_image', '<img class="jet-images-layout__image-instance" src="%1$s" data-width="%2$s" data-height="%3$s" alt="">' );
					} else {
						echo $this->__loop_item( array( 'item_image', 'url' ), '<img class="jet-images-layout__image-instance" src="%s" alt="">' );
					}
				?>
			</div>
			<div class="jet-images-layout__content">
					<?php
						echo $this->__loop_item( array( 'item_icon' ), '<div class="jet-images-layout__icon"><div class="jet-images-layout-icon-inner"><i class="%s"></i></div></div>' );
					?>

					<?php
						echo $this->__loop_item( array( 'item_title' ), '<h5 class="jet-images-layout__title">%s</h5>' );
						echo $this->__loop_item( array( 'item_desc' ), '<div class="jet-images-layout__desc">%s</div>' );
					?>

			</div>
		</a>
	</div>
	<div class="jet-images-layout__image-loader"><span></span></div>
</div>
