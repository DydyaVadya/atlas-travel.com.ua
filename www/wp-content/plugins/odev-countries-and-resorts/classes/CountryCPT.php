<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 07.07.16
 * Time: 11:18
 */
require_once('CountryGeneralMeta.php');
require_once('TextMeta.php');
require_once('MapMeta.php');
require_once('OSSettingsMeta.php');
require_once('CountrySettingsMeta.php');
require_once('NameCasesMeta.php');
require_once('ThumbnailMeta.php');

/**
 * Class CountryCPT
 */
class CountryCPT {
    private static $postType        = 'country';
    private static $continentsNames = array(
        '1' => 'Европа',
        '2' => 'Азия',
        '3' => 'Африка',
        '4' => 'Сев. Америка',
        '5' => 'Южн. Америка',
        '6' => 'Австралия',
    );
    private        $generalMeta;
    private        $mapMeta;
    private        $textMeta;
    private        $settingsMeta;
    private        $OSSettingsMeta;
    private        $nameCasesMeta;
    private        $thumbnailMeta;

    public function __construct( $postType ) {
        self::$postType = $postType;
        $this->generalMeta = new CountryGeneralMeta( self::$postType );
        $this->textMeta = new TextMeta( self::$postType );
        $this->mapMeta = new MapMeta( self::$postType );
        $this->settingsMeta = new CountrySettingsMeta( self::$postType );
        $this->OSSettingsMeta = new OSSettingsMeta( self::$postType );
        $this->nameCasesMeta = new NameCasesMeta( self::$postType );
        $this->thumbnailMeta = new ThumbnailMeta( self::$postType );


        $this->setActionsAndFilters();
    }

    public function setActionsAndFilters() {
        add_action( 'init', array( $this, 'createPostType' ) );
        add_action( 'admin_init', array( $this, 'addMetaBoxes' ) );
        add_action( 'admin_menu', array( $this, 'changeMenuStructure' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminAssets' ) );
        add_action( 'save_post_' . self::$postType, array( $this, 'saveMetaData' ) );
        add_filter( 'archive_template', array( $this, 'setArchiveTemplate' ) );
        add_filter( 'single_template', array( $this, 'setSingleTemplate' ) );

        // Add ajax action for updating offers
        add_action('wp_ajax_import_country_offers', array($this, 'ajaxImportCountryOffers'));
        add_action('wp_ajax_nopriv_import_country_offers', array($this, 'ajaxImportCountryOffers'));

        // Add sortable columns to admin table view
        $this->addColumnsToTableView();
    }

    public static function getContinentName( $cId ) {
        if( isset(self::$continentsNames[$cId]) ) {
            return self::$continentsNames[$cId];
        }

        return false;
    }

    public static function getContinents() {
        return self::$continentsNames;
    }

    public static function getCountryPostIdByOtpuskId( $otpId ) {
        if( !is_numeric( $otpId ) ) {
            return false;
        }
        global $wpdb;
        $countryPostId = $wpdb->get_var( "
        SELECT post_id 
        FROM " . $wpdb->prefix . "postmeta
        WHERE meta_key='country_otpusk_id' AND meta_value='{$otpId}'
        " );

        return $countryPostId;
    }

    public static function getCountryPostSlug( $ID ) {
        $countryPost = get_post( $ID );

        if( $countryPost ) {
            return $countryPost->post_name;
        }
    }

    public static function getCountryPostBySlug ( $countrySlug) {
        $countryPostQuery = new WP_Query( [
            'post_name__in' => [ $countrySlug ],
            'post_type'     => self::$postType,
        ] );

        if( $countryPostQuery->have_posts() ) {
            return $countryPostQuery->post;
        }
        else {
            return false;
        }
    }

    public static function getPSlug() {
        return self::$postType;
    }

    public static function getCountryIdNamePairs() {
        global $wpdb;
        $postType = self::$postType;
        $query = "SELECT ID as id, post_title as name FROM {$wpdb->posts} WHERE post_type = '{$postType}' AND post_status IN ('draft', 'publish')";

        return $wpdb->get_results($query);
    }

    public function createPostType() {
        $labels = array(
            'name'                  => 'Страны',
            'singular_name'         => 'Страна',
            'menu_name'             => 'Страны',
            'name_admin_bar'        => 'Страну',
            'archives'              => 'Страны (Архив)',
            'parent_item_colon'     => 'Страна',
            'all_items'             => 'Все страны',
            'add_new_item'          => 'Добавить страну',
            'add_new'               => 'Добавить страну',
            'new_item'              => 'Новая Страна',
            'edit_item'             => 'Редактировать страну',
            'update_item'           => 'Обновить страну',
            'view_item'             => 'Просмотреть страну',
            'search_items'          => 'Искать страну',
            'not_found'             => 'Не найдено',
            'not_found_in_trash'    => 'Не найдено в корзине',
            'featured_image'        => 'Превью изображение',
            'set_featured_image'    => 'Задать превью изображение',
            'remove_featured_image' => 'Удалить превью изображение',
            'use_featured_image'    => 'Использовать как превью',
            'insert_into_item'      => 'Вставить',
            'uploaded_to_this_item' => 'Загружено',
            'items_list'            => 'Список стран',
            'items_list_navigation' => '',
            'filter_items_list'     => '',
        );
        $args = array(
            'label'               => 'Страна',
            'labels'              => $labels,
            'supports'            => array( 'thumbnail' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-location',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => ODevCatalogManager::getPrefix(),
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );
        register_post_type( self::$postType,
            $args );
    }

    public function addMetaBoxes() {
        $this->generalMeta->addMetaBox();
        $this->mapMeta->addMetaBox();
        $this->textMeta->addMetaBox();
        $this->settingsMeta->addMetaBox();
        $this->OSSettingsMeta->addMetaBox();
        $this->nameCasesMeta->addMetaBox();
        $this->thumbnailMeta->addMetaBox();

    }

    private function addColumnsToTableView() {
        add_filter( 'manage_edit-' . self::$postType . '_columns', function ( $columns ) {
            unset($columns['date']);
            $columns['hottours_module'] = 'Модуль горящих туров';
            $columns['date'] = 'Дата';

            return $columns;
        } );
        add_action( 'manage_' . self::$postType . '_posts_custom_column', function ( $column, $postId ) {
            switch( $column ) {
                case 'hottours_module': {
                    $hottoursModuleId = get_post_meta( $postId, self::$postType . '_hottours_id', true );

                    echo($hottoursModuleId ? $hottoursModuleId : 'Отсутствует');
                    break;
                }
            }
        }, 10, 2 );
    }

    public function saveMetaData( $countryObjectID ) {
        $ID = $countryObjectID;

        //Save meta only from edit page
        if(
            isset($_POST['action']) &&
            $_POST['action'] === 'editpost' &&
            get_current_screen()->post_type === self::$postType
        ) {
            $this->generalMeta->updateMeta( $ID );
            $this->mapMeta->updateMeta( $ID );
            $this->textMeta->updateMeta( $ID );
            $this->settingsMeta->updateMeta( $ID );
            $this->OSSettingsMeta->updateMeta( $ID );
            $this->nameCasesMeta->updateMeta( $ID );
            $this->thumbnailMeta->updateMeta( $ID );

        }
    }

    public function enqueueAdminAssets() {
        //CHECK SCREEN
        $currentScreen = get_current_screen();
        if( !in_array( $currentScreen->base,
                array( 'post' ) ) || !in_array( $currentScreen->post_type, array( self::$postType ) )
        ) {
            return;
        }

        AssetManager::enqueueBootstrap();
	    AssetManager::enqueueGMapsApiScript();


        wp_register_style( 'common-style',
            ODevCatalogManager::getAssetTypeUrl( 'ADMIN_CSS' ) . 'common.css' );
        wp_enqueue_style( 'common-style' );


        wp_register_script( 'common-script', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_JS' ) . 'common.js', array(
                'jquery',
                'gmap-api',
            ), false, true );
        wp_register_script( 'country', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_JS' ) . 'country.js', array(
                'jquery',
                'gmap-api',
                'common-script',
            ), '', true );

        wp_enqueue_script( 'common-script' );
        wp_enqueue_script( 'country' );
        $this->thumbnailMeta->enqueueAdminAssets();
    }

    public function setArchiveTemplate( $archiveTemplate ) {
        global $post;

        $cptSlug = self::$postType;
        if( is_post_type_archive( $cptSlug ) ) {
            // Register OnSite Apikey
            wp_register_script( 'os-apikey', 'https://export.otpusk.com/api/session?access_token=' . ODevCatalogManager::getExportApiKey(), [], false, false );
            wp_enqueue_script( 'os-apikey' );

            if( file_exists( get_template_directory() . "/archive-{$cptSlug}.php" ) ) {
                $archiveTemplate = get_template_directory() . "/archive-{$cptSlug}.php";
            } else {
                // Add page assets
                AssetManager::enqueueBootstrap();
                AssetManager::enqueueJQueryUI();
                AssetManager::enqueueJSClasses( [ 'LocationAutocomplete' ] );

                wp_register_script( 'country-archive',
                    ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_JS' ) . 'archive-country.js',
                    array(),
                    false,
                    true );
                wp_enqueue_script( 'country-archive' );

                wp_register_style( 'country-archive',
                    ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_CSS' ) . 'archive-country.css',
                    [],
                    false );
                wp_enqueue_style( 'country-archive' );

                $archiveTemplate = PLUGIN_ROOT_PATH . 'views/public/archive-country.php';
            }
        }

        return $archiveTemplate;
    }

    public function setSingleTemplate( $singleTemplate ) {
        $postType = self::$postType;
        if( get_post_type() === $postType ) {
            // Register OnSite Apikey
            wp_register_script( 'os-apikey', 'https://export.otpusk.com/api/session?access_token=' . ODevCatalogManager::getExportApiKey(), [], false, false );
            wp_enqueue_script( 'os-apikey' );

            if( file_exists( get_template_directory() . "/single-{$postType}.php" ) ) {
                $singleTemplate = get_template_directory() . "/single-{$postType}.php";
            } else {
                AssetManager::enqueueBootstrap();
	            AssetManager::enqueueGMapsApiScript();

                wp_register_style( 'single-country',
                    ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_CSS' ) . 'single-country.css' );
                wp_enqueue_style( 'single-country' );

                wp_register_script( 'single-country', ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_JS' ) . 'single-country.js', array(
                        'jquery',
                        'gmap-api',
                    ), false, true );

                //GET MAP COORDINATES FOR COUNTRY
                global $post;
                $ID = $post->ID;
                $postType = self::$postType;

                $countryPosition = array(
                    'lat'  => get_post_meta( $ID,
                        $postType . '_lat',
                        true ),
                    'lng'  => get_post_meta( $ID,
                        $postType . '_lng',
                        true ),
                    'zoom' => get_post_meta( $ID,
                        $postType . '_zoom',
                        true ),
                );
                wp_localize_script( 'single-country',
                    'countryPosition',
                    $countryPosition );

                wp_enqueue_script( 'single-country' );

                $singleTemplate = PLUGIN_ROOT_PATH . 'views/public/single-country.php';
            }
        }

        return $singleTemplate;
    }

    public function changeMenuStructure() {
        add_submenu_page( 'edit.php?post_type=' . self::$postType,
            'Настройки',
            'Настройки',
            'manage_options',
            'odev_countries_settings',
            array(
                $this,
                'countriesSettingsPage',
            ) );
    }

    public function countriesSettingsPage() {
        AssetManager::enqueueBootstrap();

        include(PLUGIN_ROOT_PATH . 'views/admin/settings/countries-settings.php');
        if( isset($_POST['update_countries']) ) {
            $this->importCountriesOffers();
        }
    }

    private function importCountriesOffers() {
        global $wpdb;
        $soapParams = array(
            'connection_timeout' => 9000,
            'keep_alive'         => 1,
            'exceptions'         => true,
            'trace'              => true,
            'cache_wsdl'         => WSDL_CACHE_NONE,
        );

        $soapClient = new SoapClient( 'http://api.otpusk.com?wsdl', $soapParams );

        if( is_soap_fault( $soapClient ) ) {
            echo 'Error while establishing connection';
            return false;
        }
        $soapKey = array( 'accessKey' => ODevCatalogManager::getExportApiKey() );
        if( !$soapClient->getAuthorization( $soapKey ) ) {
            echo 'Wrong api key';
            return false;
        }

        // Delete exisiting hotels offers
        $wpdb->query( "DELETE FROM odev_catalog_offer WHERE country_id != 0" );

        // Get otpusk countries list
        try {
            $countriesResponse = $soapClient->getCountryList( $soapKey );
        } catch ( Exception $ex ) {
            echo $ex->getMessage();
            return;
        }

        $offerRecords = [];
        foreach ( $countriesResponse->countries as $country ) {
            if(is_object($country->priceMin) && is_object($country->priceMin->offerId) && $country->priceMin->offerId->id > 0) {
                $offer = $country->priceMin;

                $offerRecord = [
                    "'" . $offer->offerId->id . "'",
                    $country->countryId->id,
                    "'" . $offer->room . "'",
                    $offer->type,
                    "'" . $offer->food . "'",
                    "'" . $offer->transport . "'",
                    "'" . $offer->checkIn . "'",
                    $offer->length,
                    $offer->price,
                    "'" . $offer->currency . "'",
                    $offer->uah,
                    "'" . $offer->operatorId . "'",
                ];

                $offerRecords[] = '(' . implode( ', ', $offerRecord ) . ')';
            }
        }

        $offerRecordsString = implode( ', ', $offerRecords );

        $sqlResult = $wpdb->query( "
            INSERT INTO odev_catalog_offer(offer_id, country_id, room, type, food, transport, check_in, length, price, currency, uah, operator_id)
            VALUES {$offerRecordsString}
            " );

        if($sqlResult === false) {
            return false;
        }

        return true;
    }

    public function ajaxImportCountryOffers() {
	    if( !$_GET['secretkey'] || $_GET['secretkey'] != ODevCatalogManager::getAjaxSecretKey() ) {
            echo 'Wrong key';
            exit;
        }

        try {
            $this->importCountriesOffers();
            echo 'Country offers import done.';
        }
        catch (Exception $exception) {
            echo 'Error while importing country offers.';
        }
        exit;
    }
}
