<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 23.09.16
 * Time: 15:41
 */
class HotelModel {
    private $post_id;

    public function __construct($postId) {
        $this->post_id = $postId;
    }

    public function getData() {
        //TODO: Describe own method to extract data from db
        $hotelsListModel = new HotelFilteredList('hotel', $this->post_id);
        $hotelsListData = $hotelsListModel->getHotelList();

        if($hotelsListData) {
            return $hotelsListData[0];
        }
        else {
            return false;
        }
    }

    public function getRawTableRecord() {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM odev_catalog_hotel where post_id = {$this->post_id}", ARRAY_A);
    }
}