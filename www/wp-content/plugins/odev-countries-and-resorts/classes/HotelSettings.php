<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 22.09.16
 * Time: 16:13
 */
class HotelSettings {

    private static $hotelCategories = [ '1', '2', '3', '4', '5', 'HV1', 'HV2' ];
    private        $postType;

    public function __construct( $postType ) {
        $this->postType = $postType;
    }


    public function addMetaBox() {
        add_meta_box(
            'hotel_settings_meta',
            'Настройка отеля',
            array( $this, 'paintMetaBoxForm' ),
            $this->postType,
            'side',
            'high'
        );
    }

    /**
     * Paint form inputs on hotel edit page
     * @param $hotelObject
     */
    public function paintMetaBoxForm( $hotelObject ) {
        global $wpdb;
        $ID = $hotelObject->ID;
        $postType = $this->postType;

        // Get hotel modules status from database
        $hotelModel = new HotelModel( $ID );
        $hotelData = $hotelModel->getData();

        $otpuskMdlStatus = $hotelData['otpusk_module_status'] ? true : false;
        $turpravdaMdlStatus = $hotelData['turpravda_module_status'] ? true : false;

        // Get parent resort post id
        $hotelResortPostId = $hotelData['resort_post_id'];
        if( !$hotelResortPostId ) {
            $hotelResortPostId = -1;
        }

        // Get hotel category
        $hCategories = self::$hotelCategories;
        $hCurrentCategory = $hotelData['stars'];

        include(PLUGIN_ROOT_PATH . 'views/admin/edit/hotel-settings.php');
    }

    /**
     * Updating data afte submiting edit page
     * @param $hotelObjectId
     */
    public function updateData( $hotelObjectId ) {
        global $wpdb;
        $ID = $hotelObjectId;
        $postType = $this->postType;

        // Trying to existing data from catalog hotel table
        $hotelModel = new HotelModel( $ID );
        $curHotelTableRecord = $hotelModel->getRawTableRecord();
        $newHotelTableRecord = [];

        // If record exists copy existing data to new record
        if( $curHotelTableRecord ) {
            $newHotelTableRecord = $curHotelTableRecord;
        }

        // Update modules states
        $newHotelTableRecord['otpusk_module_status'] = isset($_POST[$postType . '_otpusk_module_status']) ? true : false;
        $newHotelTableRecord['turpravda_module_status'] = isset($_POST[$postType . '_turpravda_module_staus']) ? true : false;

        // Update categroy data
        $newHotelTableRecord['stars'] = '';
        if( isset($_POST[$postType . '_stars']) ) {
            $newHotelTableRecord['stars'] = $_POST[$postType . '_stars'];
        }

        // Check hotel resort post id form input
        if( isset($_POST[$postType . '_resort_post_id']) ) {
            // Get hotel resort post id input value
            $newHotelResortPostId = intval( $_POST[$postType . '_resort_post_id'] );

            // Compare with existing, if value differs or doesn't exist change it
            if( !isset($newHotelTableRecord['resort_post_id']) || $newHotelResortPostId != $curHotelTableRecord['resort_post_id'] ) {
                // Get country and resort ids from resort data
                $newHotelResortOtpuskId = get_post_meta( $newHotelResortPostId, ResortCPT::getPSlug() . '_otpusk_id', true );
                $newHotelCountryPostId = get_post_meta( $newHotelResortPostId, ResortCPT::getPSlug() . '_country_post_id', true );
                $newHotelCountryOtpuskId = get_post_meta( $newHotelResortPostId, ResortCPT::getPSlug() . '_otpusk_country_id', true );

                // Apply data if exists
                $newHotelTableRecord['resort_post_id'] = $newHotelResortPostId;
                $newHotelTableRecord['resort_otpusk_id'] = $newHotelResortOtpuskId ? $newHotelResortOtpuskId : '';
                $newHotelTableRecord['country_post_id'] = $newHotelCountryPostId ? $newHotelCountryPostId : '';
                $newHotelTableRecord['country_otpusk_id'] = $newHotelCountryOtpuskId ? $newHotelCountryOtpuskId : '';
            }
        }

        // Update if hotel record exists
        if( isset($newHotelTableRecord['post_id']) ) {
            $wpdb->update(
                'odev_catalog_hotel',
                $newHotelTableRecord,
                [
                    'post_id' => $ID,
                ],
                [],
                [ '%d' ]
            );
        }
        // Create new if hotel record doesn't exist
        else {
            $newHotelTableRecord['post_id'] = $ID;
            $wpdb->insert(
                'odev_catalog_hotel',
                $newHotelTableRecord
            );
        }
    }

}