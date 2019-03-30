<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 08.07.16
 * Time: 15:40
 */

require_once('MapMeta.php');
require_once('PhotosMeta.php');
require_once('TextMeta.php');
require_once('NameCasesMeta.php');
require_once('OSSettingsMeta.php');
require_once('ResortSettingsMeta.php');

class ResortCPT {
    private static $postType = 'resort';
    private        $mapMeta;
    private        $photosMeta;
    private        $textMeta;
    private        $OSSettingsMeta;
    private        $settingsMeta;
    private        $nameCasesMeta;

    public function __construct( $postType ) {
        self::$postType = $postType;
        $this->textMeta = new TextMeta( self::$postType );
        $this->mapMeta = new MapMeta( self::$postType );
        $this->photosMeta = new PhotosMeta( self::$postType );
        $this->OSSettingsMeta = new OSSettingsMeta( self::$postType );
        $this->settingsMeta = new ResortSettingsMeta( self::$postType );
        $this->nameCasesMeta = new NameCasesMeta( self::$postType );

        $this->setActionsAndFilters();
    }

    public function setActionsAndFilters() {
        add_action( 'init', array( $this, 'createPostType' ) );
        add_action( 'admin_init', array( $this, 'addMetaBoxes' ) );
        add_action( 'admin_menu', array( $this, 'changeMenuStructure' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminAssets' ) );
        add_action( 'save_post_' . self::$postType, array( $this, 'saveMetaData' ) );
        add_filter( 'single_template', array( $this, 'setSingleTemplate' ) );

        // Add ajax action for updating offers
        add_action('wp_ajax_import_resort_offers', array($this, 'ajaxImportResortOffers'));
        add_action('wp_ajax_nopriv_import_resort_offers', array($this, 'ajaxImportResortOffers'));

        // Add sortable columns to admin table view
        $this->addColumnsToTableView();
    }

    private function addColumnsToTableView() {
        add_filter( 'manage_edit-' . self::$postType . '_columns', function ( $columns ) {
            unset($columns['date']);

            $columns['country'] = 'Страна';
            $columns['display_type'] = 'Популярность';
            $columns['hottours_module'] = 'Модуль горящих туров';
            $columns['date'] = 'Дата';

            return $columns;
        } );
        add_filter( 'manage_edit-' . self::$postType . '_sortable_columns', function ( $columns ) {
            $columns['country'] = 'country';
            $columns['display_type'] = 'display_type';

            return $columns;
        } );
        add_action( 'manage_' . self::$postType . '_posts_custom_column', function ( $column, $postId ) {
            switch( $column ) {
                case 'country': {
                    $countryPostId = get_post_meta( $postId, self::$postType . '_country_post_id', true );
                    if( $countryPostId ) {
                        echo edit_post_link( get_the_title( $countryPostId ), '', '', $countryPostId );
                    }
                    else {
                        echo '---';
                    }
                    break;
                }
                case 'display_type': {
                    $dType = get_post_meta( $postId, self::$postType . '_display_type', true );
                    $displayTypes = $this->settingsMeta->getResortDisplayTypes();

                    echo(isset($displayTypes[$dType]) ? $displayTypes[$dType] : '---');
                    break;
                }
                case 'hottours_module': {
                    $hottoursModuleId = get_post_meta( $postId, self::$postType . '_hottours_id', true );

                    echo($hottoursModuleId ? $hottoursModuleId : 'Отсутствует');
                    break;
                }
            }
        }, 10, 2 );
        add_action( 'pre_get_posts', function ( $query ) {
            $post_type = $query->get( 'post_type' );

            if( !is_admin() || $post_type !== self::$postType ) {
                return;
            }

            $orderby = $query->get( 'orderby' );
            switch( $orderby ) {
                case 'country':
                    $query->set( 'meta_key', self::$postType . '_country_post_id' );
                    $query->set( 'orderby', 'meta_value_num' );
                    break;
                case 'display_type':
                    $query->set( 'meta_key', self::$postType . '_display_type' );
                    $query->set( 'orderby', 'meta_value' );
                    break;
            }
        } );
    }

    public static function getResortPostIdByOtpuskId( $otpId ) {
        if( !is_numeric( $otpId ) ) {
            return false;
        }
        global $wpdb;
        $countryPostId = $wpdb->get_var( "
        SELECT post_id 
        FROM " . $wpdb->prefix . "postmeta
        WHERE meta_key='resort_otpusk_id' AND meta_value='{$otpId}'
        " );

        return $countryPostId;
    }

    public static function getResortPostBySlug( $resortSlug ) {
        $resortPostQuery = new WP_Query( [
            'post_name__in' => [ $resortSlug ],
            'post_type'     => self::$postType,
        ] );

        if( $resortPostQuery->have_posts() ) {
            return $resortPostQuery->post;
        }
        else {
            return false;
        }
    }

    public static function getPSlug() {
        return self::$postType;
    }

    public static function getResortIdNamePairs() {
        global $wpdb;
        $postType = self::$postType;
        $query = "SELECT ID as id, post_title as name FROM {$wpdb->posts} WHERE post_type = '{$postType}' AND post_status IN ('draft', 'publish')";

        return $wpdb->get_results($query);
    }

    public static function getMinPriceForResortPost($postId) {
        // Get otpusk resort id
        $otpuskResortId = get_post_meta($postId, self::$postType . '_otpusk_id', true);

        if(!$otpuskResortId) {
            return false;
        }

        global $wpdb;
        $minUahPrice = $wpdb->get_var(
            $wpdb->prepare("
            SELECT uah FROM odev_catalog_offer WHERE resort_id = %d
            ", $otpuskResortId)
        );
        return $minUahPrice;
    }

    public function createPostType() {
        $labels = array(
            'name'                  => 'Курорты',
            'singular_name'         => 'Курорт',
            'menu_name'             => 'Курорты',
            'name_admin_bar'        => 'Курорт',
            'archives'              => '',
            'parent_item_colon'     => '',
            'all_items'             => 'Все курорты',
            'add_new_item'          => 'Добавить курорт',
            'add_new'               => 'Добавить новый',
            'new_item'              => 'Новый',
            'edit_item'             => 'Изменить',
            'update_item'           => 'Обновить',
            'view_item'             => 'Просмотреть',
            'search_items'          => 'Искать',
            'not_found'             => 'Не найден',
            'not_found_in_trash'    => 'Не найден в корзине',
            'featured_image'        => 'Превью изображение',
            'set_featured_image'    => 'Задать превью изображение',
            'remove_featured_image' => 'Удалить превью изображение',
            'use_featured_image'    => 'Использовать как превью',
            'insert_into_item'      => 'Вставить',
            'uploaded_to_this_item' => 'Загружено',
            'items_list'            => 'Список курортов',
            'items_list_navigation' => '',
            'filter_items_list'     => '',
        );
        $args = array(
            'label'               => 'Курорт',
            'labels'              => $labels,
            'supports'            => array(
                'title',
                'thumbnail',
            ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-location-alt',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
        );
        register_post_type( self::$postType, $args );
    }

    public function addMetaBoxes() {
        $this->textMeta->addMetaBox();
        $this->mapMeta->addMetaBox();
        $this->photosMeta->addMetaBox();
        $this->OSSettingsMeta->addMetaBox();
        $this->settingsMeta->addMetaBox();
        $this->nameCasesMeta->addMetaBox();
    }

    public function saveMetaData( $resortObjectID ) {
        $ID = $resortObjectID;

        //Save meta only from edit page
        if(
            isset($_POST['action']) &&
            $_POST['action'] === 'editpost' &&
            get_current_screen()->post_type === self::$postType
        ) {
            $this->textMeta->updateMeta( $ID );
            $this->mapMeta->updateMeta( $ID );
            $this->photosMeta->updateMeta( $ID );
            $this->OSSettingsMeta->updateMeta( $ID );
            $this->settingsMeta->updateMeta( $ID );
            $this->nameCasesMeta->updateMeta( $ID );
        }
    }

    public function enqueueAdminAssets() {
        $currentScreen = get_current_screen();
        if( !in_array( $currentScreen->base, array( 'post' ) ) || !in_array( $currentScreen->post_type,
                array( self::$postType ) )
        ) {
            return;
        }

        AssetManager::enqueueBootstrap();
        AssetManager::enqueueJQueryUI();
	    AssetManager::enqueueGMapsApiScript();

        wp_register_style( 'common-style', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_CSS' ) . 'common.css' );
        wp_enqueue_style( 'common-style' );

        wp_register_script( 'common-script', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_JS' ) . 'common.js', array(
            'jquery',
            'gmap-api',
        ), false, true );
        wp_register_script( 'resort', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_JS' ) . 'resort.js', array(
            'jquery',
            'common-script',
        ), false, true );

        // Prepare data for country autocomplete
        $countryIdNamePairs = CountryCPT::getCountryIdNamePairs();
        if( $countryIdNamePairs ) {
            // Send data to resort edit page script
            wp_localize_script( 'resort', 'countryIdNamePairs', $countryIdNamePairs );
        }

        wp_enqueue_script( 'common-script' );
        wp_enqueue_script( 'resort' );

        $this->photosMeta->enqueueAdminAssets();
    }

    public function setSingleTemplate( $singleTemplate ) {
        $cptSlug = self::$postType;

        if( get_post_type() === $cptSlug ) {
            // Register OnSite Apikey
            wp_register_script( 'os-apikey', 'https://export.otpusk.com/api/session?access_token=' . ODevCatalogManager::getExportApiKey(), [], false, false );
            wp_enqueue_script( 'os-apikey' );

            if( file_exists( get_template_directory() . "/single-{$cptSlug}.php" ) ) {
                $singleTemplate = get_template_directory() . "/single-{$cptSlug}.php";
            }
            else {
                AssetManager::enqueueBootstrap();
	            AssetManager::enqueueGMapsApiScript();

                wp_register_style( 'single-resort',
                    ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_CSS' ) . 'single-resort.css' );
                wp_enqueue_style( 'single-resort' );

                wp_register_script( 'single-resort',
                    ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_JS' ) . 'single-resort.js',
                    array(
                        'jquery',
                        'gmap-api',
                    ), false, true );

                //GET MAP COORDINATES FOR COUNTRY
                global $post;
                $ID = $post->ID;
                $postType = self::$postType;

                $resortPosition = array(
                    'lat'  => get_post_meta( $ID, $postType . '_lat',
                        true ),
                    'lng'  => get_post_meta( $ID, $postType . '_lng',
                        true ),
                    'zoom' => get_post_meta( $ID,
                        $postType . '_zoom',
                        true ),
                );
                wp_localize_script( 'single-resort', 'resortPosition', $resortPosition );

                wp_enqueue_script( 'single-resort' );

                $singleTemplate = PLUGIN_ROOT_PATH . 'views/public/single-resort.php';
            }
        }

        return $singleTemplate;
    }

    public function changeMenuStructure() {
        add_submenu_page( 'edit.php?post_type=' . self::$postType, 'Настройки', 'Настройки', 'manage_options',
            'odev_resorts_settings', array(
                $this,
                'resortsSettingsPage',
            ) );
    }

    public function resortsSettingsPage() {
        $limit = 100;
        $offset = 0;
        if( isset($_POST['update_resorts_data']) ) {
            $this->importResortOffers();
        }

        AssetManager::enqueueBootstrap();
        include(PLUGIN_ROOT_PATH . 'views/admin/settings/resorts-settings.php');
    }

    private function syncResortsData() {
        global $wpdb;
        $soapParams = array(
            'connection_timeout' => 9000,
            'keep_alive'         => 1,
            'exceptions'         => true,
            'trace'              => true,
            'cache_wsdl'         => WSDL_CACHE_NONE,
        );

        $soapClient = new SoapClient( 'http://api.otpusk.com?wsdl',
            $soapParams );

        if( is_soap_fault( $soapClient ) ) {
            echo '<h3>Error while establishing connection</h3>';
            return false;
        }
        $soapKey = array( 'accessKey' => ODevCatalogManager::getExportApiKey() );
        if( !$soapClient->getAuthorization( $soapKey ) ) {
            echo '<h3>Wrong key</h3>';
            return false;
        }
    }

    private function importResortOffers() {
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
        $wpdb->query( "DELETE FROM odev_catalog_offer WHERE resort_id != 0" );

        // Get otpusk resorts list
        try {
            $resortsResponse = $soapClient->getCityList($soapKey);
        }
        catch (Exception $ex) {
            echo $ex->getMessage();
            return;
        }

        $offerRecords = [];
        foreach ($resortsResponse->cities as $resort) {
            if(is_object($resort->priceMin) && is_object($resort->priceMin->offerId) && $resort->priceMin->offerId->id > 0) {
                $offer = $resort->priceMin;

                $offerRecord = [
                    "'" . $offer->offerId->id . "'",
                    $resort->cityId->id,
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
            INSERT INTO odev_catalog_offer(offer_id, resort_id, room, type, food, transport, check_in, length, price, currency, uah, operator_id)
            VALUES {$offerRecordsString}
            " );

        if($sqlResult === false) {
            return false;
        }

        return true;
    }

    public function ajaxImportResortOffers() {
        $secretKey = 'catalog_key';
        if ( !$_GET[ 'secretkey' ] || $_GET[ 'secretkey' ] != ODevCatalogManager::getAjaxSecretKey() ) {
            echo 'Wrong key';
            exit;
        }

        try {
            $this->importResortOffers();
            echo 'Resort offers import done.';
        } catch ( Exception $exception ) {
            echo 'Error while importing resort offers.';
        }
        exit;
    }
}
