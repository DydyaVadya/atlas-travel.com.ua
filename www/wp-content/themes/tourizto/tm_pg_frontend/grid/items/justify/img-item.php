<?php
/**
 * Image item
 *
 * @package templates/frontend/grid/items
 */

$folder     = $data->gallery_folder;
$image_copy = image_downsize( $data->ID, 'copy' );
$image      = image_downsize( $data->ID, $data->images_size );
$data_attrs = sprintf(
	'data-image-width="%1$s" data-image-height="%2$s"',
	isset( $image[1] ) ? $image[1] : 445,
	isset( $image[2] ) ? $image[2] : 350
);
?>

<div class="tm_pg_gallery-item col-xs-12 col-lg-<?php echo $data->size . ' ' . apply_filters( 'tm-pg-gallery-item-class', '' ) ?>" data-id="<?php echo $data->ID ?>" data-type="img" <?php echo $data_attrs ?>>
	<div class="tm_pg_gallery-item-wrapper">
		<?php $image = image_downsize( $data->ID, 'copy' ) ?>
		<a href="<?php echo $image[0] ?>" class="tm_pg_gallery-item_link" data-effect="fadeIn">
			<?php if ( $data->display['title'][ $folder ] || $data->display['description'][ $folder ] ): ?>
				<div class="tm_pg_gallery-item_meta">
					<div class="tm_pg_gallery-item_icon-wrapper">
						<?php $show_default_icon = true; ?>
						<?php if ( $data->display['icon'][ $folder ] ): ?>
							<i class="tm_pg_gallery-item_icon nc-icon-outline media-1_image-02"></i>
							<?php $show_default_icon = false; ?>
						<?php endif; ?>
					</div>
					<?php if ( $data->display['title'][ $folder ] ): ?>
						<h3 class="tm_pg_gallery-item_title"><?php echo $data->post_title ?></h3>
						<?php $show_default_icon = false; ?>
					<?php endif; ?>
					<?php if ( $data->display['description'][ $folder ] ): ?>
						<p class="tm_pg_gallery-item_description"><?php echo wp_trim_words( $data->post_content, intval( $data->display['description_trim'][ $folder ] ) ); ?></p>
						<?php $show_default_icon = false; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php $image = image_downsize( $data->ID, $data->images_size ) ?>
			<img src="<?php echo $image[0] ?>" alt="">
		</a>
	</div>
</div>
