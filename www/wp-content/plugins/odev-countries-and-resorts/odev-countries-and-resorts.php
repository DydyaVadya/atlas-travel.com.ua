<?php
/*
Plugin Name: ODev Страны и курорты
Description: Справочник стран и курортов от ODev.
Version: 0.4
Author: ODEV
*/
ini_set('default_socket_timeout', 600);

define( 'PLUGIN_ROOT_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_ROOT_URL', plugin_dir_url( __FILE__ ) );
define( 'DEBUG_MODE', true );

include('classes/ODevCatalogManager.php');
include('classes/ODevCatalogSearch.php');
include('classes/AssetManager.php');
include('classes/CountryCPT.php');
include('classes/ResortCPT.php');
include('classes/HotelCPT.php');
include('classes/CatalogUtils.php');
include('models/HotelFilteredList.php');
include('models/HotelModel.php');
include('models/ResortModel.php');
include('models/OfferModel.php');

ODevCatalogManager::setPluginBaseUrl( PLUGIN_ROOT_URL );
ODevCatalogSearch::addActions();

$countryCpt = new CountryCPT( 'country' );
$resortCpt = new ResortCPT( 'resort' );
$hotelCpt = new HotelCPT( 'hotel' );

ODevCatalogManager::addActions();
ODevCatalogManager::addFilters();

ODevCatalogUtils::addActions();

register_activation_hook (__FILE__, ['ODevCatalogManager', 'prepare_database']);