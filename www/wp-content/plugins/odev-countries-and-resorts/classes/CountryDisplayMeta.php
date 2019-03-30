<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 16:49
 */
class CountryDisplayMeta {
	private $postTypeSlug;

	public function __construct($postTypeSlug) {
		$this->postTypeSlug = $postTypeSlug;
	}

	public function addMetaBox() {
		add_meta_box(
			'country_display_meta',
			'Настройка области видимости',
			array($this, 'paintMetaBoxForm'),
			$this->postTypeSlug,
			'side',
			'high'
		);
	}

	public function paintMetaBoxForm($countryObject) {
		$ID = $countryObject->ID;
		$slug = $this->postTypeSlug;

		$countryPopular = false;

		if(get_post_meta($ID, $slug.'_is_popular', true)) {
			$countryPopular = 'checked';
		}

		include(PLUGIN_ROOT_PATH. 'views/admin/edit/country-display-meta.php');
	}

	public function updateMeta($countryObjectID) {
		$ID = $countryObjectID;
		$slug = $this->postTypeSlug;

		$countryPopular = false;
		if(isset($_POST[$slug.'_is_popular'])) {
			$countryPopular = true;
		}
		update_post_meta($ID, $slug.'_is_popular', $countryPopular);
	}
}