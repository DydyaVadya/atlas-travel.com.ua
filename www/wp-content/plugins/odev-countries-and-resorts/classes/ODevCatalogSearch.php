<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 28.09.16
 * Time: 17:47
 */
abstract class ODevCatalogSearch {
    private static $actionName         = 'catalog_search';
    private static $transliterationMap = [
        'а' => 'a', 'б' => 'b', 'в' => 'v',
        'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'yo', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
        'э' => 'ee', 'ю' => 'yu', 'я' => 'ya',
    ];
    private static $keybardCharsMap    = [
        'й' => 'q', 'ц' => 'w', 'у' => 'e',
        'к' => 'r', 'е' => 't', 'н' => 'y',
        'г' => 'u', 'ш' => 'i', 'щ' => 'o',
        'з' => 'p', 'х' => '[', 'ъ' => ']',
        'ф' => 'a', 'ы' => 's', 'в' => 'd',
        'а' => 'f', 'п' => 'g', 'р' => 'h',
        'о' => 'j', 'л' => 'k', 'д' => 'l',
        'ж' => ';', 'э' => '\'', 'я' => 'z',
        'ч' => 'x', 'с' => 'c', 'м' => 'v',
        'и' => 'b', 'т' => 'n', 'ь' => 'm',
        'б' => ',', 'ю' => '.',
    ];

    public static function addActions() {
        add_action( 'wp_ajax_' . self::$actionName, 'ODevCatalogSearch::actionSearch' );
        add_action( 'wp_ajax_nopriv_' . self::$actionName, 'ODevCatalogSearch::actionSearch' );
    }

    /**
     * @return string
     */
    public static function getActionName() {
        return self::$actionName;
    }

    public static function actionSearch() {
        // Set headers and init results array
        header( 'Content-Type: application/json' );
        $result = [];

        // Check input query
        if( isset($_POST['query']) && $_POST['query'] ) {
            $query = $_POST['query'];

            // Find post by original query
            $result = self::findCatalogDataByQuery( $query );

        }
        else {
            // If requested without query string show countries
            $result = self::getPriorityCountriesList();
        }

        // Add links to result data
        $result = self::prepareData( $result );

        echo json_encode( $result );
        exit;

    }

    private static function findCatalogDataByQuery( $query ) {
        $foundResults = [];

        $foundResults = self::findCountriesByTitle( $query );
        $foundResults = array_merge( $foundResults, self::findResortsByTitle( $query ) );
        if( !count( $foundResults ) ) {
           // If no results for country and city try to convert keybard map string
           $convertedQuery = self::convertKeyboardLanguage($query);
           $foundResults = self::findCountriesByTitle( $convertedQuery );
           $foundResults = array_merge( $foundResults, self::findResortsByTitle( $convertedQuery ) );

           if( !count($foundResults)) {
               // If no result with converted keybard map try transliterate original query
               $transliteratedQuery = self::transliterateString($query);
               $foundResults = self::findCountriesByTitle( $transliteratedQuery );
               $foundResults = array_merge( $foundResults, self::findResortsByTitle( $transliteratedQuery ) );
           }
           else {
               $query = $convertedQuery;
           }
        }
        $foundResults = array_merge( $foundResults, self::findHotelsByTitle( $query ) );

        return $foundResults;
    }

    private static function findCountriesByTitle( $query ) {
        $posttype = CountryCPT::getPSlug();
        global $wpdb;
        $convQuery = self::convertKeyboardLanguage($query);
        $translitQuery = self::transliterateString($query);

        $foundPosts = $wpdb->get_results( "
                SELECT ID as id, post_title as label, post_type as type, IFNULL(priority_meta.meta_value, 0) as priority
                FROM {$wpdb->posts} 
                LEFT JOIN {$wpdb->postmeta} priority_meta on (id = priority_meta.post_id AND priority_meta.meta_key = '{$posttype}_priority')
                WHERE post_status != 'auto-draft' AND post_type='{$posttype}' AND (post_title LIKE '{$query}%' OR post_title LIKE '{$convQuery}%' OR post_title LIKE '{$translitQuery}%')
                ORDER BY priority DESC
                LIMIT 10
                ", ARRAY_A
        );

        if( !$foundPosts ) {
            $foundPosts = [];
        }

        return $foundPosts;
    }

    private static function transliterateString( $string ) {
        $string = mb_strtolower($string);
        $transliterationMap = self::$transliterationMap;
        if( !self::isCyrillic( $string ) ) {
            $transliterationMap = array_flip( $transliterationMap );
        }

        $stringChars = preg_split( '//u', $string, -1, PREG_SPLIT_NO_EMPTY );
        $transliteratedChars = [];

        foreach ( $stringChars as $position => $char ) {
            $transliteratedChar = $char;
            if( isset($transliterationMap[$char]) ) {
                $transliteratedChar = $transliterationMap[$char];
            }
            $transliteratedChars[$position] = $transliteratedChar;
        }

        return implode( '', $transliteratedChars );
    }

    private static function convertKeyboardLanguage( $string ) {
        $string = mb_strtolower($string);
        $keyConvertingMap = self::$keybardCharsMap;
        if( !self::isCyrillic( $string ) ) {
            $keyConvertingMap = array_flip( $keyConvertingMap );
        }

        $stringChars = preg_split( '//u', $string, -1, PREG_SPLIT_NO_EMPTY );
        $convertedChars = [];

        foreach ( $stringChars as $position => $char ) {
            $convertedChar = $char;
            if( isset($keyConvertingMap[$char])) {
                $convertedChar = $keyConvertingMap[$char];
            }

            $convertedChars[$position] = $convertedChar;
        }

        return implode($convertedChars);
    }

    private static function isCyrillic( $string ) {
        return preg_match( '/[А-Яа-яЁё]/u', $string );
    }

    private static function findResortsByTitle( $query ) {
        $posttype = ResortCPT::getPSlug();
        global $wpdb;
        $convQuery = self::convertKeyboardLanguage($query);
        $translitQuery = self::transliterateString($query);

        $foundPosts = $wpdb->get_results( "
                SELECT ID as id, post_title as label, post_type as type, 
                IFNULL(priority_meta.meta_value, 0) as priority, 
                IFNULL(country_id_meta.meta_value, '') as country_post_id
                FROM {$wpdb->posts} 
                LEFT JOIN {$wpdb->postmeta} priority_meta on (id = priority_meta.post_id AND priority_meta.meta_key = '{$posttype}_priority')
                LEFT JOIN {$wpdb->postmeta} country_id_meta on (id = country_id_meta.post_id AND country_id_meta.meta_key = '{$posttype}_country_post_id')
                WHERE post_status != 'auto-draft' AND post_type='{$posttype}' AND (post_title LIKE '{$query}%' OR post_title LIKE '{$convQuery}%' OR post_title LIKE '{$translitQuery}%')
                ORDER BY priority DESC
                LIMIT 10
                ", ARRAY_A
        );

        if( !$foundPosts ) {
            $foundPosts = [];
        }

        return $foundPosts;
    }

    private static function findHotelsByTitle( $query ) {
        $posttype = HotelCPT::getPSlug();
        global $wpdb;
        $translitQuery = self::transliterateString($query);

        $foundPosts = $wpdb->get_results( "
                SELECT 
                    ID as id, 
                    post_title as label, 
                    post_type as type, 
                    hotel_data.stars as hotel_category,
                    hotel_data.country_post_id as country_post_id,
                    hotel_data.resort_post_id as resort_post_id,
                    hotel_data.turpravda_rate as hotel_rate
                FROM {$wpdb->posts} 
                LEFT JOIN odev_catalog_hotel hotel_data ON (id = hotel_data.post_id)
                WHERE post_status != 'auto-draft' AND post_type='{$posttype}' AND (post_title LIKE '%{$query}%' OR post_title LIKE '%{$translitQuery}%')
                ORDER BY hotel_rate DESC
                LIMIT 10
                ", ARRAY_A
        );

        if( !$foundPosts ) {
            $foundPosts = [];
        }

        return $foundPosts;
    }

    private static function getPriorityCountriesList() {
        $resultCountries = [];

        $countriesQuery = new WP_Query( [
            'post_type'      => CountryCPT::getPSlug(),
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
            'meta_query'     => [
                'key'   => CountryCPT::getPSlug(),
                'value' => 2,
            ],
        ] );

        if( $countriesQuery->have_posts() ) {
            foreach ( $countriesQuery->posts as $countryPost ) {
                $resultCountries[] = [
                    'id'    => $countryPost->ID,
                    'label' => $countryPost->post_title,
                    'type'  => CountryCPT::getPSlug(),
                ];
            }
        }

        return $resultCountries;
    }

    private static function prepareData( $searchData ) {
        $resultData = $searchData;

        reset( $resultData );
        while ( list($key, $item) = each( $resultData ) ) {
            $resultData[$key]['link'] = get_the_permalink( $item['id'] );

            switch( $item['type'] ) {
                case 'hotel': {
                    $resultData[$key]['geo'] = '';

                    if( isset($item['country_post_id']) ) {
                        $countryName = get_the_title( $item['country_post_id'] );
                        $resultData[$key]['geo'] .= $countryName;

                        unset($resultData[$key]['country_post_id']);
                    }

                    if( isset($item['resort_post_id']) ) {
                        $resortName = get_the_title( $item['resort_post_id'] );
                        $resultData[$key]['geo'] .= ', ' . $resortName;

                        unset($resultData[$key]['resort_post_id']);
                    }

                    if( isset($item['hotel_category']) ) {
                        $resultData[$key]['label'] .= ' ' . $item['hotel_category'] . '*';
                        unset($resultData[$key]['hotel_category']);
                    }
                    break;
                }
                case 'resort': {
                    $resultData[$key]['geo'] = '';

                    if( isset($item['country_post_id']) ) {
                        $countryName = get_the_title( $item['country_post_id'] );
                        $resultData[$key]['geo'] .= $countryName;
                        unset($resultData[$key]['country_post_id']);
                    }
                    break;
                }
            }
        }

        return $resultData;
    }

}