<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 16:49
 */
class CountrySettingsMeta {
	private $postType;

	public function __construct($postType) {
		$this->postType = $postType;
	}

	public function addMetaBox() {
		add_meta_box(
			'country_settings_meta',
			'Настройка области видимости',
			array($this, 'paintMetaBoxForm'),
			$this->postType,
			'side',
			'high'
		);
	}

	public function paintMetaBoxForm($countryObject) {
		$ID = $countryObject->ID;
		$postType = $this->postType;

		$countryPopular = false;

		if(get_post_meta($ID, $postType.'_is_popular', true)) {
			$countryPopular = 'checked';
		}

		include(PLUGIN_ROOT_PATH. 'views/admin/edit/country-settings-meta.php');
	}

	public function updateMeta($countryObjectID) {
		$ID = $countryObjectID;
		$postType = $this->postType;

		$countryPopular = false;
		if(isset($_POST[$postType.'_is_popular'])) {
			$countryPopular = true;
		}
		update_post_meta($ID, $postType.'_is_popular', $countryPopular);
	}
}