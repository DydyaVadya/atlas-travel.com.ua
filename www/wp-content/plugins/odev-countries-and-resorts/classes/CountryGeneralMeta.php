<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 11:30
 */
class CountryGeneralMeta {
	private $postType;

	public function __construct($postType) {
		$this->postType = $postType;
	}

	public function addMetaBox() {
		add_meta_box(
			'country_general_meta',
			'Информация о стране',
			array($this, 'paintMetaBoxForm'),
			$this->postType,
			'advanced',
			'high'
		);
	}

	public function paintMetaBoxForm($countryObject) {
		$ID = $countryObject->ID;
		$postType = $this->postType;
		$name = get_the_title($ID);
		$population = esc_html(get_post_meta($ID, $postType.'_population', true));
		$territory =  esc_html(get_post_meta($ID, $postType.'_territory', true));
		$language =  esc_html(get_post_meta($ID, $postType.'_language', true));
		include(PLUGIN_ROOT_PATH. 'views/admin/edit/country-general-meta.php');
	}

	public function updateMeta($countryObjectID) {
		$ID = $countryObjectID;
		$postType = $this->postType;

		update_post_meta($ID, $postType.'_population', sanitize_text_field($_POST[$postType.'_population']));
		update_post_meta($ID, $postType.'_territory', sanitize_text_field($_POST[$postType.'_territory']));
		update_post_meta($ID, $postType.'_language', sanitize_text_field($_POST[$postType.'_language']));
	}
}