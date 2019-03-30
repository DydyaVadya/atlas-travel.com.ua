<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 08.07.16
 * Time: 16:06
 */
class ThumbnailMeta {
    private $postType;

    public function __construct($postType) {
        $this->postType = $postType;
    }

    public function addMetaBox() {
        add_meta_box(
            $this->postType.'_thumbnail_meta',
            'Превью изображение 2',
            array($this, 'paintMetaBoxForm'),
            $this->postType,
            'side',
            'low'
        );
    }

    public function paintMetaBoxForm($postObject) {
        $ID = $postObject->ID;
        $postType = $this->postType;

        $thumbnailIds = @unserialize(get_post_meta($ID, $postType.'_thumbnails', true));
        $thumbnailId = is_array($thumbnailIds) ? array_shift ($thumbnailIds) : $thumbnailIds;
            $src = wp_get_attachment_image_src($thumbnailId, 'thumbnail');
            $thumbnail = array();
            $thumbnail['id'] = $thumbnailId;
            $thumbnail['url'] = is_array($src) ? current($src) : '';
            $thumbnailMetaData = wp_get_attachment_metadata($thumbnail['id']);
            $thumbnail['caption'] = is_array($thumbnailMetaData) ? $thumbnailMetaData['image_meta']['caption'] : '';
            $thumbnailArray[] = $thumbnail;

        wp_localize_script('thumbnail-uploader', 'postThumbnails', $thumbnailArray);

        include(PLUGIN_ROOT_PATH .'views/admin/edit/thumbnail-meta.php');
    }

    public function updateMeta($postObjectID) {
        $ID = $postObjectID;
        $postType = $this->postType;

        update_post_meta($ID, $postType.'_thumbnails', serialize($_POST[$postType.'_thumbnails']));
	}

	public function enqueueAdminAssets() {
		wp_enqueue_media();

        wp_register_script( 'thumbnail-uploader', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_JS' ) . 'thumbnail-uploader.js', array( 'jquery', ), false, true );
        wp_enqueue_script( 'thumbnail-uploader' );
	}
}