<?php

    /**
     * Created by PhpStorm.
     * User: aleksandrfishchenko
     * Date: 26.09.16
     * Time: 16:30
     */
    class OfferModel {
        private $offer_id;

        public function __construct($offer_id) {
            $this->offer_id = $offer_id;
        }

        public function getData() {
            global $wpdb;

            return $wpdb->get_row("SELECT * FROM odev_catalog_offer WHERE offer_id = {$this->offer_id}");
        }
    }