<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 12.09.16
 * Time: 16:31
 */
class AssetManager {

	private static $JSClasses = [
		'LocationAutocomplete',
	];

	public static function enqueueBootstrap() {
//		wp_register_style( 'bootstrap3min', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
//		wp_enqueue_style( 'bootstrap3min' );

        wp_register_style( 'local-bootstrap',
            ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_CSS' ) . 'local-bootstrap.css' );
        wp_enqueue_style( 'local-bootstrap' );

		wp_register_script( 'bootstram3min', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', array(), false, true );
		wp_enqueue_script( 'bootstram3min' );
	}

	public static function enqueueJQueryUI() {
		wp_register_style( 'jquery-ui', ODevCatalogManager::getAssetTypeUrl( 'PLUGINS_URL' ) . 'jquery-ui-1.12.0.odev_catalog/jquery-ui.min.css', array(), false );
		wp_register_style( 'jquery-ui-theme', ODevCatalogManager::getAssetTypeUrl( 'PLUGINS_URL' ) . 'jquery-ui-1.12.0.odev_catalog/jquery-ui.theme.min.css', array(), false );
		wp_register_style( 'jquery-ui-structure', ODevCatalogManager::getAssetTypeUrl( 'PLUGINS_URL' ) . 'jquery-ui-1.12.0.odev_catalog/jquery-ui.structure.min.css', array(), false );

		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'jquery-ui-theme' );
		wp_enqueue_style( 'jquery-ui-structure' );

		wp_register_script( 'jquery-ui', ODevCatalogManager::getAssetTypeUrl( 'PLUGINS_URL' ) . 'jquery-ui-1.12.0.odev_catalog/jquery-ui.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'jquery-ui' );
	}

	public static function enqueueJSClasses( $names ) {
		foreach ( $names as $name ) {
			if( in_array( $name, self::$JSClasses ) ) {
				wp_register_script(
					strtolower( $name ),
					ODevCatalogManager::getAssetTypeUrl( 'COMMON_JS' ) . 'classes/' . $name . '.js',
					array(),
					false,
					true
				);

				// Localize addition classes data
				switch( $name ) {
					case 'LocationAutocomplete':
						wp_localize_script( strtolower( $name ), 'wpAjaxUrl', admin_url( 'admin-ajax.php' ) );
						break;
				}

				wp_enqueue_script( strtolower( $name ) );
			}
		}
	}

	public static function enqueueGMapsApiScript() {
		wp_register_script( 'gmap-api', '//maps.googleapis.com/maps/api/js?key=' . ODevCatalogManager::getGmapsApiKey(), '', '', true );
		wp_enqueue_script( 'gmap-api' );
	}
}