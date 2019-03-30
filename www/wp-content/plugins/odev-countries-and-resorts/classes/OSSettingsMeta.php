<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 11.07.16
 * Time: 14:50
 */
class OSSettingsMeta {
	private $postType;

	public function __construct($postType) {
		$this->postType = $postType;
	}

	public function addMetaBox() {
		add_meta_box(
			$this->postType.'_os_settings_meta',
			'Настройки НаСайт',
			array($this, 'paintMetaBoxForm'),
			$this->postType,
			'side',
			'high'
		);
	}

	public function paintMetaBoxForm($postObject) {
		$ID = $postObject->ID;
		$postType = $this->postType;

		$hottoursId = get_post_meta($ID, $postType.'_hottours_id', true);

		$osSearchStatus = '';
		if(get_post_meta($ID, $postType.'_os_search_status', true) === 'on') {
			$osSearchStatus = 'checked';
		}

		include(PLUGIN_ROOT_PATH.'/views/admin/edit/os-settings-meta.php');
	}

	public function updateMeta($postObjectID) {
		$ID = $postObjectID;
		$postType = $this->postType;

		update_post_meta($ID, $postType.'_hottours_id', sanitize_text_field($_POST[$postType.'_hottours_id']));

		$osSearchStatus = 'off';
		if(isset($_POST[$postType.'_os_search_status'])) {
			$osSearchStatus = 'on';
		}
		update_post_meta($ID, $postType.'_os_search_status', $osSearchStatus);
	}
}