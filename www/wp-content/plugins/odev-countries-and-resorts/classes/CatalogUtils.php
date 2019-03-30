<?php

abstract class ODevCatalogUtils {

    public static function addActions() {
        add_action( 'admin_menu', 'ODevCatalogUtils::changeMenuStructure' );
        add_action( 'admin_enqueue_scripts', 'ODevCatalogUtils::enqueue_scripts' );
        add_action( 'wp_ajax_change_all_widgets_status', 'ODevCatalogUtils::change_all_widgets_status' );
    }

    public static function changeMenuStructure() {
        add_submenu_page( 'tools.php',
            'Каталог',
            'Каталог',
            'manage_options',
            'odev_catalog_utils',
            'ODevCatalogUtils::catalogUtilsPage'
        );
    }

    public function catalogUtilsPage() {
        include(PLUGIN_ROOT_PATH . 'views/admin/settings/catalog-utils.php');
    }

    public static function enqueue_scripts(){
        wp_register_script( 'odev-utils', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_JS' ) . 'utils.js', array( 'jquery' ), null, true );
        $wpAjaxUrl = admin_url('admin-ajax.php');
        wp_localize_script('odev-utils', 'wpAjaxUrl', $wpAjaxUrl);
        wp_localize_script('odev-utils', 'catalog_utils_nonce', wp_create_nonce('catalog_utils_nonce'));
        wp_enqueue_script( 'odev-utils' );
    }

    static function change_all_widgets_status(){
        if($_POST['submit'] == 'enableAllOtpuskWidgets'){
            $widget = 'otpusk';
            $status = true;
        }
        if($_POST['submit'] == 'disableAllOtpuskWidgets'){
            $widget = 'otpusk';
            $status = false;
        }
        if($_POST['submit'] == 'enableAllTurpravdaWidgets'){
            $widget = 'turpravda';
            $status = true;
        }
        if($_POST['submit'] == 'disableAllTurpravdaWidgets'){
            $widget = 'turpravda';
            $status = false;
        }
        if(!isset($widget) || !isset($status)) return false;
        if ( empty($_POST['catalog_utils_nonce'])) return false;
        if ( ! wp_verify_nonce($_POST['catalog_utils_nonce'], 'catalog_utils_nonce') ) return false;
        global $wpdb;
        $query = "UPDATE odev_catalog_hotel SET {$widget}_module_status={$status}";
        $response = $wpdb->query($query);
        if($response === false)
            echo "Error";
        else
            echo "Обновлено {$response} отелей";
        die();
    }

}