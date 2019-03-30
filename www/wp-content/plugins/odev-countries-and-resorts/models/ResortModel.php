<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 23.09.16
 * Time: 17:42
 */
class ResortModel {
    private $post_id;
    private $fields;

    public function __construct($postId) {
        $this->post_id = $postId;
    }

    public function getData() {
        $postType = ResortCPT::getPSlug();

        $data = array(
            'id'    => $this->post_id,
            'title' => get_the_title($this->post_id),
        );

        $resortMeta = get_post_custom( $this->post_id );

        while ( list($key, $values) = each( $resortMeta ) ) {
            if( substr( $key, 0, 1 ) === '_' ) {
                continue;
            }

            // MAKE KEYS WITHOUT SLUG
            $shortKey = str_replace( $postType . '_', '', $key );

            // MANUALY UNSERIALIZE META FIELDS
            if( in_array( $key, [ $postType . '_name_cases', $postType . '_preposition_cases' ] ) ) {
                $metaValueArray = unserialize( get_post_meta(
                    $data['id'],
                    $key,
                    true
                ) );
                if( is_array( $metaValueArray ) ) {
                    $data[$shortKey] = $metaValueArray;
                } else {
                    $data[$shortKey] = [];
                }
                continue;
            }
            $data[$shortKey] = current( $values );
        }

        $this->fields = $data;
        return $data;
    }
}