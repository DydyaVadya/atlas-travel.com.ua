<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 19.07.16
 * Time: 17:13
 */
class NameCasesMeta {
	private $postType;
	private $casesNames = array(
		'rd' => 'Родительный',
		'vn' => 'Винительный',
		'pr' => 'Предложный',
		'dt' => 'Дательный',
		'tr' => 'Перевод'
	);

	public function __construct($postType) {
		$this->postType = $postType;
	}

	public function addMetaBox() {
		add_meta_box(
			$this->postType.'_name_cases_meta',
			'Падежи и предлоги',
			array($this, 'paintMetaBoxForm'),
			$this->postType,
			'side',
			'high'
		);
	}

	public function paintMetaBoxForm($postObject) {
		$ID = $postObject->ID;
		$postType = $this->postType;

		$nameCases = unserialize(get_post_meta($ID, $postType. '_name_cases', true));
		$prepositionCases = unserialize(get_post_meta($ID, $postType. '_preposition_cases', true));
		$casesNames = $this->casesNames;

		include(PLUGIN_ROOT_PATH . 'views/admin/edit/name-cases-meta.php');
	}

	public function updateMeta($postObjectId) {
		$ID = $postObjectId;
		$postType = $this->postType;

		$cases = $this->casesNames;
		$nameCases = array();
		$prepositionCases = array();
		reset($cases);
		while(list($case, $value) = each($cases)) {
			$nameCases[$case] = sanitize_text_field($_POST['name_'. $case]);
			$prepositionCases[$case] = sanitize_text_field($_POST['preposition_'. $case]);
		}

		if(count($nameCases)) {
			update_post_meta( $ID, $postType . '_name_cases', serialize( $nameCases ) );
			update_post_meta( $ID, $postType . '_preposition_cases', serialize( $prepositionCases ) );
		}
	}
}