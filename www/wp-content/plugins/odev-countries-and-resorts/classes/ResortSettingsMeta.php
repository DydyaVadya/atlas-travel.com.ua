<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 22.09.16
 * Time: 11:13
 */
class ResortSettingsMeta {

    private $postType;
    private $resortDisplayTypes = [
        'regular'   => 'Обычный',
        'popular'   => 'Популярный',
        'important' => 'Важнейший',
    ];

    public function __construct( $postType ) {
        $this->postType = $postType;
    }

    /**
     * @return array
     */
    public function getResortDisplayTypes() {
        return $this->resortDisplayTypes;
    }

    public function addMetaBox() {
        add_meta_box(
            'resort_settings_meta',
            'Настройка курорта',
            array( $this, 'paintMetaBoxForm' ),
            $this->postType,
            'side',
            'high'
        );
    }

    public function paintMetaBoxForm( $resortObject ) {
        $ID = $resortObject->ID;
        $postType = $this->postType;

        // Set list of resort types
        $types = $this->resortDisplayTypes;

        // Get saved value from meta
        $typeSelected = get_post_meta( $ID, $postType . '_display_type', true );
        if( !$typeSelected ) {
            $typeSelected = key( $types );
        }

        // Get resort parent country post id
        $resortCountryPostId = get_post_meta( $ID, $postType . '_country_post_id', true );
        if( !$resortCountryPostId ) {
            $resortCountryPostId = -1;
        }

        include(PLUGIN_ROOT_PATH . 'views/admin/edit/resort-settings-meta.php');
    }

    public function updateMeta( $resortObjectID ) {
        $ID = $resortObjectID;
        $postType = $this->postType;

        // Set selected value for display type
        if( isset($_POST[$postType . '_display_type']) ) {
            $typeSelected = $_POST[$postType . '_display_type'];
            update_post_meta( $ID, $postType . '_display_type', $typeSelected );
        }

        // Set resort parent country
        if( isset($_POST[$postType . '_country_post_id']) ) {
            $curResortCountryPostId = intval( get_post_meta( $ID, $postType . '_country_post_id', true ) );
            $newResortCountryPostId = intval( $_POST[$postType . '_country_post_id'] );

            if( $curResortCountryPostId !== $newResortCountryPostId ) {
                $newResortCountryOtpuskId = intval( get_post_meta( $newResortCountryPostId, CountryCPT::getPSlug() . '_otpusk_id', true ) );
                
                update_post_meta( $ID, $postType . '_country_post_id', $newResortCountryPostId );
                update_post_meta( $ID, $postType . '_otpusk_country_id', $newResortCountryOtpuskId );
            }
        }
    }

}