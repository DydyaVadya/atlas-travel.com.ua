<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 08.07.16
 * Time: 16:06
 */
class PhotosMeta {
	private $postType;
	
	public function __construct($postType) {
		$this->postType = $postType;
	}
	
	public function addMetaBox() {
		add_meta_box(
			$this->postType.'_photo_meta',
			'Фотографии',
			array($this, 'paintMetaBoxForm'),
			$this->postType,
			'advanced',
			'high'
		);
	}
	
	public function paintMetaBoxForm($postObject) {
		$ID = $postObject->ID;
		$postType = $this->postType;
		
		$photosIds = @unserialize(get_post_meta($ID, $postType.'_photos', true));
        if(!is_array($photosIds)) {
            $photosIds = [];
        }
		$photosArray = array();
		foreach ($photosIds as $photoId) {
			$src = wp_get_attachment_image_src($photoId, 'thumbnail');
			if(!$src)
				continue;
			$photo = array();
			$photo['id'] = $photoId;
			$photo['url'] = current($src);
			$photoMetaData = wp_get_attachment_metadata($photo['id']);
			$photo['caption'] = $photoMetaData['image_meta']['caption'];
			$photosArray[] = $photo;
		}

		wp_localize_script('media-uploader', 'postPhotos', $photosArray);

		include(PLUGIN_ROOT_PATH .'views/admin/edit/photo-meta.php');
	}
	
	public function updateMeta($postObjectID) {
		$ID = $postObjectID;
		$postType = $this->postType;
		
		update_post_meta($ID, $postType.'_photos', serialize($_POST[$postType.'_photos']));
	}

	public function enqueueAdminAssets() {
		wp_enqueue_media();

        wp_register_script( 'media-uploader', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_JS' ) . 'media-uploader.js', array( 'jquery', ), false, true );
        wp_enqueue_script( 'media-uploader' );
	}
}