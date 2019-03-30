<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 15:11
 */
class MapMeta {
	private $postType;

	public function __construct($postType) {
		$this->postType = $postType;
	}

	public function addMetaBox() {
		add_meta_box(
			$this->postType.'_map_meta',
			'Карта',
			array($this, 'paintMetaBoxForm'),
			$this->postType,
			'advanced',
			'high'
		);
	}

	public function paintMetaBoxForm($postObject) {
		$ID = $postObject->ID;
		$postType = $this->postType;

		$lat = esc_html(get_post_meta($ID, $postType.'_lat', true));
		$lng =  esc_html(get_post_meta($ID, $postType.'_lng', true));
		$zoom =  esc_html(get_post_meta($ID, $postType.'_zoom', true));
		include(PLUGIN_ROOT_PATH. 'views/admin/edit/map-meta.php');
	}

	public function updateMeta($postObjectID) {
		$ID = $postObjectID;
		$postType = $this->postType;

		update_post_meta($ID, $postType.'_lat', sanitize_text_field($_POST[$postType.'_lat']));
		update_post_meta($ID, $postType.'_lng', sanitize_text_field($_POST[$postType.'_lng']));
		update_post_meta($ID, $postType.'_zoom', sanitize_text_field($_POST[$postType.'_zoom']));
	}
}