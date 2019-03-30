<?php

    /**
     * Created by PhpStorm.
     * User: aleksandrfishchenko
     * Date: 23.09.16
     * Time: 11:22
     */
    class HotelFilteredList {

        private $targetType;
        private $targetId;
        private $whereClauses = [];
        private $orderRules   = [];
        private $limit        = 48;
        private $offset       = 0;

        /**
         * HotelFilteredList constructor.
         * @param $targetType
         * @param $targetId
         */
        public function __construct( $targetType = 'all', $targetId = -1 ) {
            if( !is_numeric( $targetId ) ) {
                return false;
            }

            switch( $targetType ) {
                case 'country':
                    $this->whereClauses['target'] = [ 'country_post_id', '=', $targetId ];
                    break;
                case 'resort':
                    $this->whereClauses['target'] = [ 'resort_post_id', '=', $targetId ];
                    break;
                case 'hotel':
                    $this->whereClauses['target'] = [ 'post_id', '=', $targetId ];
                    break;
                default:
                    // If target not specified do nothing
                    break;
            }

            $this->targetType = $targetType;
            $this->targetId = $targetId;
        }

        /**
         * @param int $limit
         */
        public function setLimit( $limit ) {
            $this->limit = $limit;
        }

        /**
         * @param int $offset
         */
        public function setOffset( $offset ) {
            $this->offset = $offset;
        }

        /**
         * @param array $categories
         */
        public function setCategories( $categories = [] ) {
            if( count( $categories ) ) {
                $this->where( 'stars', '(' . implode( ', ', $categories ) . ')', 'IN' );
            }
        }

        /**
         * @param $field
         * @param $value
         * @param string $compare
         * @return bool
         */
        public function where( $field, $value, $compare = '=' ) {
            $resultStatus = false;

            switch( $compare ) {
                default:
                    $this->whereClauses[$field] = [ $field, $compare, $value ];
                    $resultStatus = true;
                    break;
            }

            return $resultStatus;
        }

        /**
         * @param int $rate
         */
        public function setRateFrom( $rate = 4 ) {
            $this->where( 'turpravda_rate', $rate, '>=' );
        }

        /**
         * @param $rule
         * Add order rule to hotel list
         * @return boolean result of execution
         */
        public function orderby( $field, $order = 'ASC' ) {
            $resultStatus = false;

            if( in_array( $order, [ 'ASC', 'DESC' ] ) ) {
                $this->orderRules[$field] = [ $field, $order ];
                $resultStatus = true;
            }

            return $resultStatus;
        }

        /**
         * @return array|null|object
         */
        public function getHotelList() {
            global $wpdb;
            $query = $this->compileSqlQuery();
            $hotelListResult = $wpdb->get_results( $query, ARRAY_A );

            // Extend result with fields
            if( $hotelListResult ) {
                $resortsNames = [];
                reset( $hotelListResult );
                while ( list($key, $hotel) = each( $hotelListResult ) ) {

                    // Add resorts names to hotel list response
                    $curResortPostId = $hotel['resort_post_id'];
                    if( !isset($resortsNames[$curResortPostId]) ) {
                        $resortNames = @unserialize( get_post_meta( $curResortPostId, 'resort_name_cases', true ) );
                        if( !is_array( $resortNames ) ) {
                            $resortNames = [];
                        }

                        $resortsNames[$curResortPostId] = $resortNames;
                    }
                    $hotelListResult[$key]['resort_names'] = $resortsNames[$curResortPostId];

                    // Calculate link to hotel page
                    $hotelListResult[$key]['link'] = home_url( '/' . ODevCatalogManager::getPrefix() . '/' . $hotel['country_slug'] . '/' . $hotel['resort_slug'] . '/' . $hotel['hotel_slug'] );

                    // Change size directory of image
                    $hotelListResult[$key]['otpusk_image_url'] = str_replace('160x120', '320x240', $hotelListResult[$key]['otpusk_image_url']);
                }
            }

            if( $hotelListResult === false ) {
                if( DEBUG_MODE ) {
                    echo '<pre>';
                    print_r( $wpdb->last_error );
                    print_r( $wpdb->last_query );
                    echo '</pre>';
                }
            }

            return $hotelListResult;

        }

        /**
         * @return null|string
         */
        public function count() {
            global $wpdb;

            $query = "SELECT count(*) FROM odev_catalog_hotel {$this->compileWhereClauses()}";

            return $wpdb->get_var($query);
        }

        /**
         * @return string
         */
        private function compileSqlQuery() {
        	global $wpdb;
            $WHERE = $this->compileWhereClauses();
            $ORDER = $this->compileOrderRules();
            $LIMIT = 'LIMIT ' . $this->offset . ', ' . $this->limit;

            $JOINS = [];
            $JOINS[] = 'LEFT JOIN odev_catalog_offer offers ON otpusk_id = offers.hotel_id';
            $JOINS[] = 'LEFT JOIN ' . $wpdb->prefix . 'posts hotel_posts ON post_id = hotel_posts.ID';
            $JOINS[] = 'LEFT JOIN ' . $wpdb->prefix . 'posts country_posts ON country_post_id = country_posts.ID';
            $JOINS[] = 'LEFT JOIN ' . $wpdb->prefix . 'posts resort_posts ON resort_post_id = resort_posts.ID';
            $JOINS = implode( ' ', $JOINS );

            $QUERY = "SELECT odev_catalog_hotel.*, 
              ifnull(offers.uah, '') as min_price_uah,
              offers.offer_id as min_price_offer_id,
              country_posts.post_title as country, country_posts.post_name as country_slug,
              resort_posts.post_title as resort, resort_posts.post_name as resort_slug,
              hotel_posts.post_title as name, hotel_posts.post_name as hotel_slug
            FROM odev_catalog_hotel {$JOINS} {$WHERE} {$ORDER} {$LIMIT}";

            return $QUERY;
        }

        /**
         * @return string
         */
        private function compileWhereClauses() {
            $WHERE = '';
            $whereClauses = $this->whereClauses;

            if( count( $whereClauses ) ) {
                reset( $whereClauses );
                while ( list($key, $condition) = each( $whereClauses ) ) {
                    $whereClauses[$key] = implode( ' ', $condition );
                }

                $WHERE = 'WHERE ' . implode( ' AND ', $whereClauses );
            }

            return $WHERE;
        }

        /**
         * @return string
         */
        private function compileOrderRules() {
            $ORDER = '';
            $orderRules = $this->orderRules;

            if( count( $orderRules ) ) {
                reset( $orderRules );
                while ( list($key, $order) = each( $orderRules ) ) {
                    if( is_array( $order ) ) {
                        $orderRules[$key] = implode( ' ', $order );
                    }
                }

                $ORDER = 'ORDER BY ' . implode( ', ', $orderRules );
            }

            return $ORDER;
        }
    }