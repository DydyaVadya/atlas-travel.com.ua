<?php
function importCountriesListFromDB() {
    global $wpdb;
    $countriesFields = 'rec_id, fCountry, fNameRd, fNameVn, fNamePr, fNameDt, fNameTr, fCapitalId, fSouthWest, fNorthEast, fZoom, fContinent';
    $countriesQuery = "select $countriesFields from tCountries";
    $otpuskCountries = $wpdb->get_results($countriesQuery);
    foreach ($otpuskCountries as $oCountry) {
        $NameCases = array(
            'rd' => $oCountry->fNameRd,
            'vn' => $oCountry->fNameVn,
            'pr' => $oCountry->fNamePr,
            'dt' => $oCountry->fNameDt,
            'tr' => $oCountry->fNameTr
        );
        $otpuskCapitalId = $oCountry->fCapitalId;
        $optuskCountryId = $oCountry->rec_id;
        $Name = $oCountry->fCountry;
        $coordinatesLongtitude = $oCountry->fNorthEast;
        $coordinatesLatitude = $oCountry->fSouthWest;
        $coordinatesZoom = $oCountry->fZoom;

        $postIds = $wpdb->get_results("select ID from wp_posts where post_title='".$Name."'");
        $postId = '';
        if(count($postIds) > 0) {
            $postId = $postIds[0]->ID;
        }
        else {
            $postId = wp_insert_post(array(
                'post_title' => $Name,
                'post_type' => 'odev_country',
                'post_content' => '',
                'post_status' => 'publish'
            ));
        }
        if(!$postId || $postId != '') {
            update_post_meta($postId, 'odev_country_name_cases', serialize($NameCases));
            update_post_meta($postId, 'odev_country_otpusk_id', $optuskCountryId);
            update_post_meta($postId, 'odev_country_capital_id', $otpuskCapitalId);
            update_post_meta($postId, 'odev_country_map_lat', $coordinatesLatitude);
            update_post_meta($postId, 'odev_country_map_lng', $coordinatesLongtitude);
            update_post_meta($postId, 'odev_country_map_zoom', $coordinatesZoom);
        }
    }
}
function importResortsListFromDB($limit, $offset)
{
    global $wpdb;
    $resortsFields = 'rec_id, fCountryID, fName, fNameRd, fNameVn, fNamePr, fNameDt, fNameTr, fSouthWest, fNorthEast, fZoom, fPriority';
    $resortsQuery = "select $resortsFields from tCities limit {$limit} offset {$offset}";
    $otpuskResorts = $wpdb->get_results($resortsQuery);

    foreach ($otpuskResorts as $oResort) {
        $NameCases = array(
            'rd' => $oResort->fNameRd,
            'vn' => $oResort->fNameVn,
            'pr' => $oResort->fNamePr,
            'dt' => $oResort->fNameDt,
            'tr' => $oResort->fNameTr
        );
        $otpuskCountryId = $oResort->fCountryID;
        $otpuskResortId = $oResort->rec_id;
        $Name = $oResort->fName;
	    $nameTr = $oResort->fNameTr;
        $resortPriority = $oResort->fPriority;
        $coordinatesLongtitude = $oResort->fNorthEast;
        $coordinatesLatitude = $oResort->fSouthWest;
        $coordinatesZoom = $oResort->fZoom;

        $postIds = $wpdb->get_results("select ID from wp_posts where post_title='".$Name."'");
        $postId = '';
        if(count($postIds) > 0) {
            $postId = $postIds[0]->ID;
        }
        else {
            $postId = wp_insert_post(array(
                'post_title' => $Name,
                'post_type' => 'resort',
	            'post_name' => $nameTr,
                'post_content' => '',
                'post_status' => 'draft'
            ));
        }
        if (!$postId || $postId != '') {
            update_post_meta($postId, 'resort_name_cases', serialize($NameCases));
            update_post_meta($postId, 'resort_otpusk_country_id', $otpuskCountryId);
            update_post_meta($postId, 'resort_otpusk_id', $otpuskResortId);
            update_post_meta($postId, 'resort_lng', $coordinatesLongtitude);
            update_post_meta($postId, 'resort_lat', $coordinatesLatitude);
            update_post_meta($postId, 'resort_zoom', $coordinatesZoom);
            update_post_meta($postId, 'resort_priority', $resortPriority);
        }
    }
    return ($offset + $limit);
}
function importCPriorityAndContinent() {
    $allCountries = new WP_Query(array(
        'post_type' => 'country',
        'post_status' => 'any',
        'posts_per_page' => -1
    ));
    global $wpdb;
    $countriesFields = 'rec_id, fContinent, fPriority';
    $countriesQuery = "select $countriesFields from tCountries";
    $otpuskCountriesObj = $wpdb->get_results($countriesQuery);
    $otpuskCountriesArr = array();
    foreach ($otpuskCountriesObj as $otpuskCountryObj) {
        $otpuskCountriesArr[$otpuskCountryObj->rec_id] = array(
            'continent' => $otpuskCountryObj->fContinent,
            'priority' => $otpuskCountryObj->fPriority
        );
    }
    $updatedCount = 0;
    foreach ($allCountries->posts as $countryPost) {
        $otpuskCId = intval(get_post_meta($countryPost->ID, 'country_otpusk_id', true));
        $ID = $countryPost->ID;
        if(isset($otpuskCountriesArr[$otpuskCId]['priority']) && isset($otpuskCountriesArr[$otpuskCId]['continent'])) {
            update_post_meta($ID, 'country_priority', $otpuskCountriesArr[$otpuskCId]['priority']);
            update_post_meta($ID, 'country_continent', $otpuskCountriesArr[$otpuskCId]['continent']);
            $updatedCount++;
        }
    }
    echo '<pre>';
    echo 'Обновлено '.$updatedCount.' стран.';
    echo '</pre>';
}
?>