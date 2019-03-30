<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 16:18
 */
class TextMeta {
    private $postType;

    // Sets manually to max 2
    private $textCount = 2;

    public function __construct( $postType ) {
        $this->postType = $postType;
    }

    public function addMetaBox() {
        add_meta_box( $this->postType . '_text_meta',
                      'Текствоая информация',
                      array( $this, 'paintMetaBoxForm' ),
                      $this->postType,
                      'advanced',
                      'high' );
    }

    public function paintMetaBoxForm( $postObject ) {
        $ID = $postObject->ID;
        $postType = $this->postType;

        // GET TEXTS FROM META

        $textIndex = 1;
        $texts = [];
        while ($textIndex <= $this->textCount) {
            $texts[$textIndex] = [
                'title' => get_post_meta($ID, $postType . '_text_title_' . $textIndex, true),
                'content' => get_post_meta($ID, $postType . '_text_content_' . $textIndex, true),
            ];
            $textIndex++;
        }

        // Display text editors
        include(PLUGIN_ROOT_PATH . 'views/admin/edit/text-meta.php');
    }

    public function updateMeta( $postObjectID ) {
        $ID = $postObjectID;
        $postType = $this->postType;

        $textIndex = 1;
        while ($textIndex <= $this->textCount) {
            update_post_meta($ID, $postType . '_text_title_' . $textIndex, $_POST[$postType . '_text_title_' . $textIndex]);
            update_post_meta($ID, $postType . '_text_content_' . $textIndex, $_POST[$postType . '_text_content_' . $textIndex]);
            $textIndex++;
        }
    }
}