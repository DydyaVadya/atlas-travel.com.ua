<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 12.09.16
 * Time: 15:53
 */
abstract class ODevCatalogManager {

	private static $ASSET_URLS    = '';
	private static $pluginRootUrl = '';
	private static $exportApiKey  = '2e6fe-52c6d-8cabd-d4e2f-be34d';
	private static $gmapsApiKey   = 'AIzaSyA7rZgdl5kOdTCcOP2oe7L9FsNEx1e1ApA';
	private static $ajaxSecretKey = 'secrethiddenconcealedkey';
	private static $prefix        = 'hotels';

	/**
	 * @return string
	 */
	public static function getGmapsApiKey() {
		return self::$gmapsApiKey;
	}

	public static function getPrefix(){
	    return self::$prefix;
    }

	public static function setPluginBaseUrl( $pluginRootUrl ) {
		self::$pluginRootUrl = $pluginRootUrl;
		self::$ASSET_URLS = [ 'ADMIN_JS'    => $pluginRootUrl . 'assets/admin/js/',
		                      'ADMIN_CSS'   => $pluginRootUrl . 'assets/admin/css/',
		                      'PUBLIC_JS'   => $pluginRootUrl . 'assets/public/js/',
		                      'PUBLIC_CSS'  => $pluginRootUrl . 'assets/public/css/',
		                      'PUBLIC_IMG'  => $pluginRootUrl . 'assets/public/img/',
		                      'PLUGINS_URL' => $pluginRootUrl . 'assets/plugins/',
		                      'COMMON_JS'   => $pluginRootUrl . 'assets/common/js/', ];
	}

	/**
	 * @return string
	 */
	public static function getAjaxSecretKey() {
		return self::$ajaxSecretKey;
	}

	public static function getAssetTypeUrl( $type ) {
		if( isset(self::$ASSET_URLS[ $type ]) ) {
			return self::$ASSET_URLS[ $type ];
		}

		return false;
	}

	public static function addActions() {
		add_action( 'init', 'ODevCatalogManager::catalogRewriteRules' );
        add_action('init', 'ODevCatalogManager::allowLargeJoins');
	}

    public static function addFilters() {
        add_filter( 'post_type_link', 'ODevCatalogManager::catalogPermalinksBuilder', 10, 3 );
        add_filter( 'query_vars', 'ODevCatalogManager::addArchiveQueryVars' );
        add_filter( 'body_class', 'ODevCatalogManager::bodyClass' );
    }

    public static function bodyClass($classes) {
        if(self::isCatalogPage())
            $classes[] = 'odev-catalog';
        return $classes;
    }

    public static function allowLargeJoins()
    {
        global $wpdb;
        $wpdb->query('SET SQL_BIG_SELECTS=1');
    }

	public static function catalogRewriteRules() {
		global $wp_rewrite;
		$wp_rewrite->add_rewrite_tag( '%country%', '([^/]+)', CountryCPT::getPSlug() . '=' );
		$wp_rewrite->add_rewrite_tag( '%resort%', '([^/]+)', ResortCPT::getPSlug() . '=' );
		$wp_rewrite->add_rewrite_tag( '%hotel%', '([^/]+)', HotelCPT::getPSlug() . '=' );

		$wp_rewrite->add_permastruct( ResortCPT::getPSlug(), self::$prefix . '/%country%/%resort%/%hotel%', [] );
		$wp_rewrite->add_permastruct( CountryCPT::getPSlug(), self::$prefix . '/%country%/%resort%/%hotel%', [] );
		$wp_rewrite->add_permastruct( HotelCPT::getPSlug(), self::$prefix . '/%country%/%resort%/%hotel%', [] );

		$wp_rewrite->add_rule(
			'^' . self::$prefix . '/([^/]+)/([^/]+)/(hotels)/page/(\d+)',
			'index.php?country_slug=$matches[1]&resort_slug=$matches[2]&resort_section=$matches[3]&paged=$matches[4]&post_type=' . HotelCPT::getPSlug(),
			'top' );
		$wp_rewrite->add_rule(
			'^' . self::$prefix . '/([^/]+)/([^/]+)/(hotels)',
			'index.php?country_slug=$matches[1]&resort_slug=$matches[2]&resort_section=$matches[3]&post_type=' . HotelCPT::getPSlug(),
			'top' );
		$wp_rewrite->add_rule(
//			'^' . self::$prefix . '/([^/]+)/([^/]+)^[\D\/]+\/(\d+)/([^/]+)',
			'^' . self::$prefix . '/([^/]+)/([^/]+)/([^/]+)',
			'index.php?country_slug=$matches[1]&resort_slug=$matches[2]&hotel=$matches[3]',
			'top' );
		$wp_rewrite->add_rule(
			'^' . self::$prefix . '/([^/]+)/([^/]+)',
			'index.php?country_slug=$matches[1]&resort=$matches[2]',
			'top' );
		$wp_rewrite->add_rule(
			'^' . self::$prefix . '/([^/]+)',
			'index.php?country=$matches[1]',
			'bottom' );
	}

	public static function catalogPermalinksBuilder( $permalink, $post, $leavename ) {
		if( empty($permalink) || in_array( $post->post_status, array( 'draft', 'pending', 'auto-draft' ) ) ) {
			return $permalink;
		}
		switch( $post->post_type ) {
			case HotelCPT::getPSlug(): {
				$hotelModel = new HotelModel( $post->ID );
				if( $hotelData = $hotelModel->getData() ) {
					$permalink = str_replace( '%country%', $hotelData['country_slug'], $permalink );
					$permalink = str_replace( '%resort%', $hotelData['resort_slug'], $permalink );
				}
				else {
					$permalink = str_replace( '%country%', 'country', $permalink );
					$permalink = str_replace( '%resort%/', 'resort', $permalink );
				}

				$otpResortId = get_post_meta( $post->ID, '_otpusk_country_id', true );

				return $permalink;
			}
			case ResortCPT::getPSlug(): {
				$otpCountryId = get_post_meta( $post->ID, 'resort_otpusk_country_id', true );
				$postCountryId = CountryCPT::getCountryPostIdByOtpuskId( $otpCountryId );
				if( $postCountryId ) {
					$postCountrySlug = CountryCPT::getCountryPostSlug( $postCountryId );
					$permalink = str_replace( '%country%', $postCountrySlug, $permalink );
				}
				$permalink = str_replace( '%hotel%', '', $permalink );

				return $permalink;
			}
			case CountryCpt::getPSlug(): {
				$permalink = str_replace( '%resort%/', '', $permalink );
				$permalink = str_replace( '%hotel%', '', $permalink );

				return $permalink;
			}
		}

		return $permalink;
	}

	public static function addArchiveQueryVars( $vars ) {
		$vars[] = 'country_slug';
		$vars[] = 'resort_slug';
		$vars[] = 'resort_section';

		return $vars;
	}

	public static function getCatalogPageBreadcrumbs() {
		// Get current request params

		// Get country request parameter
		$countrySlug = get_query_var( CountryCPT::getPSlug(), get_query_var( 'country_slug', false ) );
		$countryPost = false;
		if( $countrySlug !== false ) {
			$countryPost = CountryCPT::getCountryPostBySlug( $countrySlug );
		}

		// Get resort request parameter
		$resortSlug = get_query_var( ResortCPT::getPSlug(), get_query_var( 'resort_slug', false ) );
		$resortSectionSlug = get_query_var( 'resort_section', false );
		$resortPost = false;
		if( $resortSlug !== false ) {
			$resortPost = ResortCPT::getResortPostBySlug( $resortSlug );
		}

		// Get hotel request parameter
		$hotelSlug = get_query_var( HotelCPT::getPSlug(), false );
		$hotelPost = false;
		if( $hotelSlug !== false ) {
			$hotelPost = HotelCPT::getHotelPostBySlug( $hotelSlug );
		}

		// Build links array
		$links = [];

		// Add catalog root link
		$links[] = [ 'link'  => home_url( '/' ),
		             'label' => 'Главная' ];
		$links[] = [ 'link'  => home_url( self::$prefix . '/' ),
		             'label' => 'Каталог' ];
		if( $countryPost ) {
			$countryLink = get_the_permalink( $countryPost->ID );
			$links[] = [ 'link'  => $countryLink,
			             'label' => $countryPost->post_title ];
		}

		if( $resortPost ) {
			$resortLink = get_the_permalink( $resortPost->ID );
			$links[] = [ 'link'  => $resortLink,
			             'label' => $resortPost->post_title ];
		}

		if( $resortPost && ($resortSectionSlug || $hotelPost) ) {
			$resortModel = new ResortModel( $resortPost->ID );
			$resortData = $resortModel->getData();
			$links[] = [ 'link'  => $resortLink . 'hotels',
			             'label' => 'Отели ' . $resortData['name_cases']['rd'] ];
		}

		if( $hotelPost ) {
			$hotelModel = new HotelModel( $hotelPost->ID );
			$hotelData = $hotelModel->getData();
			$links[] = [ 'link'  => get_the_permalink( $hotelPost->ID ),
			             'label' => $hotelPost->post_title . ' ' . $hotelData['stars'] . '*' ];
		}

		// Return links array
		return $links;
	}

	public static function getPluralFormForNumber( $number, $words ) {
		$cases = array( 2, 0, 1, 1, 1, 2 );
		return $words[ ($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[ min( $number % 10, 5 ) ] ];
	}

	public static function establishOtpuskSoapApiConnection() {

        if(!extension_loaded('soap')) {
            wp_die('SOAP not loaded', 'Error');
        }

		$soapParams = array(
			'connection_timeout' => 9000,
			'keep_alive'         => 1,
			'exceptions'         => true,
			'trace'              => true,
			'cache_wsdl'         => WSDL_CACHE_NONE,
		);

		$soapClient = new SoapClient( 'http://api.otpusk.com?wsdl', $soapParams );

		if( is_soap_fault( $soapClient ) ) {
			error_log( 'Error while establishing connection' );
			return null;
		}

		$soapKey = self::getOtpuskSoapApiKey();

		if( !$soapClient->getAuthorization( $soapKey ) ) {
			error_log( 'Wrong api key' );
			return null;
		}

		return $soapClient;
	}

	public static function getOtpuskSoapApiKey() {
		return array( 'accessKey' => ODevCatalogManager::getExportApiKey() );
	}

	/**
	 * @return string
	 */
	public static function getExportApiKey() {
		return self::$exportApiKey;
	}

    public static function isCatalogPage() {
        if(is_singular(CountryCPT::getPSlug()) || is_singular(ResortCPT::getPSlug()) || is_singular(HotelCPT::getPSlug()) ||
            is_post_type_archive(CountryCPT::getPSlug()) || is_post_type_archive(HotelCPT::getPSlug()))
            return true;
        return false;
    }

	public static function prepare_database(){

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    global $wpdb;

        if($wpdb->get_var("SHOW TABLES LIKE odev_catalog_hotel") != 'odev_catalog_hotel') {
            $sql = "CREATE TABLE odev_catalog_hotel (
              post_id int(11) NOT NULL,
              otpusk_id int(11) DEFAULT NULL,
              resort_post_id int(11) DEFAULT NULL,
              resort_otpusk_id int(11) DEFAULT NULL,
              country_post_id int(11) DEFAULT NULL,
              country_otpusk_id int(11) DEFAULT NULL,
              stars varchar(11) DEFAULT NULL,
              turpravda_link varchar(256) DEFAULT NULL,
              turpravda_rate varchar(10) DEFAULT NULL,
              turpravda_votes_count int(11) DEFAULT NULL,
              otpusk_image_url varchar(256) DEFAULT NULL,
              name varchar(128) NOT NULL,
              nameTr varchar(128) DEFAULT NULL,
              otpusk_module_status tinyint(1) NOT NULL DEFAULT '0',
              turpravda_module_status tinyint(1) NOT NULL DEFAULT '0',
              last_updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY  (post_id),
              KEY otpusk_id (otpusk_id,resort_post_id,resort_otpusk_id,country_post_id,country_otpusk_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            dbDelta( $sql );
        }

        if($wpdb->get_var("SHOW TABLES LIKE odev_catalog_offer") != 'odev_catalog_offer') {
            $sql = "CREATE TABLE odev_catalog_offer (
              offer_id bigint(20) NOT NULL,
              country_id int(11) NOT NULL,
              resort_id int(11) NOT NULL,
              hotel_id int(11) NOT NULL,
              room varchar(32) CHARACTER SET latin1 NOT NULL,
              type int(11) DEFAULT NULL,
              food varchar(8) CHARACTER SET latin1 NOT NULL,
              transport varchar(16) CHARACTER SET latin1 NOT NULL,
              check_in datetime NOT NULL,
              length int(11) NOT NULL,
              price int(11) NOT NULL,
              currency varchar(8) CHARACTER SET latin1 NOT NULL,
              uah int(11) NOT NULL,
              operator_id int(11) DEFAULT NULL,
              PRIMARY KEY  (offer_id,hotel_id,country_id,resort_id),
              KEY hotel_id (hotel_id),
              KEY resort_id (resort_id),
              KEY country_id (country_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            dbDelta( $sql );
        }

    }
}