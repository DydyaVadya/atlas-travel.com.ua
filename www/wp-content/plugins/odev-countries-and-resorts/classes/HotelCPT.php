<?php

/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 20.09.16
 * Time: 15:54
 */

require_once('PhotosMeta.php');
require_once('AssetManager.php');
require_once('ODevCatalogManager.php');
require_once('HotelSettings.php');

class HotelCPT {

	private static $postType      = 'hotel';
	private        $photosMeta;
	private        $hotelSettings;
	private        $default_hotels_per_page = 555;

	private static $hotel_offer_targets = [
		[
			'title' => 'на странице отеля (без перехода)',
			'value' => 'self' // default
		],
		[
			'title' => 'на главной странице',
			'value' => 'home'
		]
	];

	public function __construct( $postType ) {
		self::$postType = $postType;

		$this->photosMeta = new PhotosMeta( self::$postType );
		$this->hotelSettings = new HotelSettings( self::$postType );

		$this->setActionsAndFilters();
	}

	public function setActionsAndFilters() {
		add_action( 'init', array( $this, 'createPostType', ) );
		add_action( 'admin_menu', array( $this, 'changeMenuStructure' ) );
		add_action( 'admin_init', array( $this, 'addMetaBoxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminAssets' ) );
		add_action( 'save_post_' . self::$postType, array( $this, 'saveMetaData' ) );
		add_action( 'delete_post', array( $this, 'deleteData' ) );
		add_filter( 'archive_template', array( $this, 'setArchiveTemplate' ) );
		add_filter( 'single_template', array( $this, 'setSingleTemplate' ) );

		// Add ajax action for updating offers
		add_action( 'wp_ajax_import_hotel_offers', array( $this, 'ajaxImportHotelOffers' ) );
		add_action( 'wp_ajax_nopriv_import_hotel_offers', array( $this, 'ajaxImportHotelOffers' ) );

		// Add ajax action for updating hotel reviews data
		add_action( 'wp_ajax_import_hotel_rates', array( $this, 'ajaxImportHotelReviewsData' ) );
		add_action( 'wp_ajax_nopriv_import_hotel_rates', array( $this, 'ajaxImportHotelReviewsData' ) );

		// Add sortable columns to admin table view
		$this->addColumnsToTableView();
	}

	private function addColumnsToTableView() {
		add_filter( 'manage_edit-' . self::$postType . '_columns', function ( $columns ) {
			unset($columns['date']);

			$columns['category'] = 'Категория';
			$columns['location'] = 'Местоположение';
			$columns['date'] = 'Дата';

			return $columns;
		} );
		add_action( 'manage_' . self::$postType . '_posts_custom_column', function ( $column, $postId ) {
			switch( $column ) {
				case 'location': {
					$hotelModel = new HotelModel( $postId );
					$hotelData = $hotelModel->getData();
					if( $hotelData ) {
						$countryPostId = $hotelData['country_post_id'];
						$resortPostId = $hotelData['resort_post_id'];
						echo edit_post_link( get_the_title( $countryPostId ), '', '', $countryPostId );
						echo ' / ';
						echo edit_post_link( get_the_title( $resortPostId ), '', '', $resortPostId );
					}
					else {
						echo '---';
					}
					break;
				}
				case 'category': {
					$hotelModel = new HotelModel( $postId );
					$hotelData = $hotelModel->getData();
					if( $hotelData ) {
						if( is_numeric( $hotelData['stars'] ) ) {
							echo str_repeat(
								'<span class="dashicons dashicons-star-filled"></span>',
								$hotelData['stars']
							);
						}
						else {
							echo $hotelData['stars'];
						}
					}
					else {
						echo '---';
					}
					break;
				}
			}
		}, 10, 2 );
	}

	public static function getPSlug() {
		return self::$postType;
	}

	public static function getOfferTargets() {
		return self::$hotel_offer_targets;
	}

	public static function getActiveOfferTarget() {
		return $current_hotel_offer_target_value = get_option(
			'odev-hotel-offer-target',
			array_values( HotelCPT::getOfferTargets() )[0]['value']
		);
	}

	public static function isOfferTargetBlank() {
		return boolval( get_option( 'odev-hotel-offer-target-is-blank', 1 ) );
	}

	public static function getHotelPostBySlug( $hotelSlug ) {
		$hotelPostQuery = new WP_Query( [
			'post_name__in' => [ $hotelSlug ],
			'post_type'     => self::$postType,
		] );

		if( $hotelPostQuery->have_posts() ) {
			return $hotelPostQuery->post;
		}
		else {
			return false;
		}
	}

	public function addMetaBoxes() {
		$this->photosMeta->addMetaBox();
		$this->hotelSettings->addMetaBox();
	}

	public function saveMetaData( $hotelObjectID ) {
		$ID = $hotelObjectID;

		//Save meta only from edit page
		if(
			isset($_POST['action']) &&
			$_POST['action'] === 'editpost' &&
			get_current_screen()->post_type === self::$postType
		) {
			$this->photosMeta->updateMeta( $ID );
			$this->hotelSettings->updateData( $ID );
		}
	}

	public function deleteData( $hotelPostId ) {
		if( get_post_type( $hotelPostId ) !== self::$postType ) {
			return;
		}
		$ID = $hotelPostId;
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare( "
            DELETE FROM odev_catalog_hotel
            WHERE post_id = %d
            ", $ID )
		);
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

		wp_register_style( 'common-style', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_CSS' ) . 'common.css' );
		wp_enqueue_style( 'common-style' );

		wp_register_script( 'hotel', ODevCatalogManager::getAssetTypeUrl( 'ADMIN_JS' ) . 'hotel.js', array( 'jquery', ), false, true );

		// Prepare data for hotel resort autocomplete
		$resortIdNamePairs = ResortCPT::getResortIdNamePairs();
		if( $resortIdNamePairs ) {
			wp_localize_script( 'hotel', 'resortIdNamePairs', $resortIdNamePairs );
		}

		wp_enqueue_script( 'hotel' );

		$this->photosMeta->enqueueAdminAssets();
	}

	public function createPostType() {
		$labels = array(
			'name'                  => 'Отели',
			'singular_name'         => 'Отель',
			'menu_name'             => 'Отели',
			'name_admin_bar'        => 'Отель',
			'archives'              => 'Отели',
			'parent_item_colon'     => 'Отель',
			'all_items'             => 'Все отели',
			'add_new_item'          => 'Добавить новый отель',
			'add_new'               => 'Добавить новый',
			'new_item'              => 'Новый отель',
			'edit_item'             => 'Редактировать отель',
			'update_item'           => 'Обновить отель',
			'view_item'             => 'Просмотреть отель',
			'search_items'          => 'Искать отель',
			'not_found'             => 'Не найдено',
			'not_found_in_trash'    => 'Не найдено в корзине',
			'featured_image'        => 'Превью изображение',
			'set_featured_image'    => 'Задать превью изображение',
			'remove_featured_image' => 'Удалить превью изображение',
			'use_featured_image'    => 'Использовать как превью',
			'insert_into_item'      => 'Вставить',
			'uploaded_to_this_item' => 'Загружено',
			'items_list'            => '',
			'items_list_navigation' => '',
			'filter_items_list'     => '',
		);
		$args = array(
			'label'               => 'Отель',
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-building',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( self::$postType,
			$args );
	}

	public function changeMenuStructure() {
		add_submenu_page( 'edit.php?post_type=' . self::$postType,
			'Настройки',
			'Настройки',
			'manage_options',
			'odev_hotels_settings',
			array(
				$this,
				'hotelsSettingsPage',
			) );
	}

	public function hotelsSettingsPage() {

		if( DEBUG_MODE ) {
			ini_set( 'display_errors', 1 );
			ini_set( 'display_startup_errors', 1 );
			ini_set( "max_execution_time", 0 );
		}

		if(isset($_POST['hotel-offer-target'])){
			foreach (HotelCPT::getOfferTargets() as $hotel_offer_target){
				if($hotel_offer_target['value'] == $_POST['hotel-offer-target']){
					update_option('odev-hotel-offer-target', $_POST['hotel-offer-target']);
					break;
				}
			}
		}

		if(!empty($_POST)) {
			$offer_target_is_blank = isset( $_POST['hotel-offer-target-is-blank'] ) ? boolval( $_POST['hotel-offer-target-is-blank'] ) : 0;
			update_option( 'odev-hotel-offer-target-is-blank', $offer_target_is_blank );
		}

		$import_page = 0;
		$import_mode = 'manual';
		if(isset($_POST['count-hotels-per-query'])){
			$count_hotels_per_query = intval($_POST['count-hotels-per-query']);
			if($count_hotels_per_query > $this->default_hotels_per_page || $count_hotels_per_query < 1)
				$count_hotels_per_query = $this->default_hotels_per_page;
		} else {
			$count_hotels_per_query = $this->default_hotels_per_page;
		}
		if(isset($_POST['offset-hotels-per-query'])){
			$offset_hotels_per_query = intval($_POST['offset-hotels-per-query']);
			if($offset_hotels_per_query >= $this->default_hotels_per_page || $offset_hotels_per_query < 0)
				$offset_hotels_per_query = 0;
		} else {
			$offset_hotels_per_query = 0;
		}

		if( isset($_POST['import_page']) ) {
			$import_page = $_POST['import_page'];
		}

		if( isset($_POST['import_hotels']) ) {
			if( $this->importHotels( $import_page, $count_hotels_per_query, $offset_hotels_per_query ) ) {
				$import_mode = 'auto';
			}
			$offset_hotels_per_query += $count_hotels_per_query;
			if($offset_hotels_per_query >= $this->default_hotels_per_page) {
				$offset_hotels_per_query = 0;
				$import_page ++;
			}
		}

		if( isset($_POST['import_hotel_offers']) ) {
			$this->importHotelOffers();
		}

		AssetManager::enqueueBootstrap();

		include(PLUGIN_ROOT_PATH . 'views/admin/settings/hotels-settings.php');
	}

	private function importHotels( $page, $count_hotels_per_query, $offset_hotels_per_query ) {
		$isImportFull = true;

		global $wpdb;
		$otpuskSoapApiClient = ODevCatalogManager::establishOtpuskSoapApiConnection();
		$otpuskSoapApiKey = ODevCatalogManager::getOtpuskSoapApiKey();

		$hotelsResponse = $otpuskSoapApiClient->getHotelList( $otpuskSoapApiKey,
			array(
				'page' => $page,
			) );

		echo "<div class='alert alert-warning'>Всего отелей на странице " . count( $hotelsResponse->hotels ) . "</div>";

		if( !count( $hotelsResponse->hotels ) ) {
			$isImportFull = false;
		}

		if( DEBUG_MODE ) {
			ini_set( 'display_errors', 1 );
			ini_set( 'display_startup_errors', 1 );
			ini_set( "max_execution_time", 0 );
		}

		$current_hotels_per_query = 1;

		foreach ( $hotelsResponse->hotels as $hotel ) {

			if($current_hotels_per_query <= $offset_hotels_per_query) {
				$current_hotels_per_query++;
				continue;
			}

			if($current_hotels_per_query == $count_hotels_per_query + $offset_hotels_per_query)
				break;

			$hotelSlug = strtolower( $hotel->nameTr );
			$hotelOtpuskId = $hotel->hotelId->id;
			$isPostExists = $wpdb->get_var( "
            select post_id
            from " . $wpdb->prefix . "postmeta
            where meta_value = {$hotelOtpuskId} and meta_key='hotel_otpusk_id'
            " );
			if( !$isPostExists ) {
				$ID = wp_insert_post( [
					'post_title'  => $hotel->name,
					'post_name'   => $hotelSlug,
					'post_type'   => self::$postType,
					'post_status' => 'publish',
				] );

				//PREPARE OTPUSK IMAGE URL
				$hotelImageUrl = '';
				if( is_array( $hotel->images ) && count( $hotel->images ) ) {
					$hotelImage = current( $hotel->images );
					$hotelImageUrl = $hotelImage->href;
				}

				add_post_meta( $ID, 'hotel_otpusk_id', $hotelOtpuskId, true );

				$hotelFields = [
					'post_id'               => $ID,
					'otpusk_id'             => $hotel->hotelId->id,
					'resort_post_id'        => ResortCPT::getResortPostIdByOtpuskId( $hotel->cityId->id ),
					'resort_otpusk_id'      => $hotel->cityId->id,
					'country_post_id'       => CountryCPT::getCountryPostIdByOtpuskId( $hotel->countryId->id ),
					'country_otpusk_id'     => $hotel->countryId->id,
					'stars'                 => $hotel->stars,
					'turpravda_link'        => $hotel->turpravdaLink,
					'turpravda_rate'        => $hotel->rating->rate,
					'turpravda_votes_count' => ($hotel->rating->reviews ? $hotel->rating->reviews : 0),
					'otpusk_image_url'      => $hotelImageUrl,
					'name'                  => $hotel->name,
					'nameTr'                => $hotel->nameTr,
				];

				$insertResult = $wpdb->insert( 'odev_catalog_hotel', $hotelFields );
			}
			else {
				echo "<div class='alert alert-danger'>Отель не добавлен {$hotel->name}, #{$hotel->hotelId->id}, Конфликт с отелем #{$isPostExists}</div>";
			}
			$current_hotels_per_query++;

		}

		return $isImportFull;
	}

	private function importHotelOffers() {

		ini_set( "max_execution_time", 0 );

		if( DEBUG_MODE ) {
			ini_set( 'display_errors', 1 );
			ini_set( 'display_startup_errors', 1 );
		}

		$importStatus = false;

		// Initialize connections
		global $wpdb;
		$otpuskSoapApiClient = ODevCatalogManager::establishOtpuskSoapApiConnection();
		$otpuskSoapApiKey = ODevCatalogManager::getOtpuskSoapApiKey();

		if( isset( $_GET['limit'] ) ) {
			$limit = intval( $_GET['limit'] );
			if($limit < 1)
				$limit = 1;
		} else {
			$limit = 40;
		}

		if( isset( $_GET['offset'] ) ) {
			$offset = intval( $_GET['offset'] );
			if($offset < 0)
				$offset = 0;
		} else {
			$offset = 0;
		}

		// Delete existing hotels offers
		if($offset == 0)
			$wpdb->query( "DELETE FROM odev_catalog_offer WHERE hotel_id != 0" );

		$maxPage = $offset + $limit + 1;

		if( DEBUG_MODE ) {

			echo "limit: " . $limit . ' ';
			echo "offset: " . $offset . ' ';
			echo "maxPage: " . $maxPage . ' ';
			echo "<hr>";
			echo "memory_limit: " . ini_get( 'memory_limit' ) . '<hr>';

			function convert_memory( $size ) {
				$unit = array( 'b', 'kb', 'mb', 'gb', 'tb', 'pb' );
				return @round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . ' ' . $unit[ $i ];
			}

		}

		$hotelOfferRecords = [];

		for ( $page = 1 + $offset; $page < $maxPage; $page++ ) {

			if( DEBUG_MODE ) {
				echo "page: " . $page . '<br>used memory: ' . convert_memory( memory_get_usage() );
				echo "<hr>";
			}

			$hotelsResponse = $otpuskSoapApiClient->getHotelList( $otpuskSoapApiKey,
				array(
					'page' => $page,
				) );

			if( !count( $hotelsResponse->hotels ) ) {
				break;
			}
			foreach ( $hotelsResponse->hotels as $hotel ) {
				if( is_object( $hotel->priceMin ) && is_object( $hotel->priceMin->offerId ) && $hotel->priceMin->offerId->id > 0 ) {
					$offer = $hotel->priceMin;
					$hotelOfferRecord = [
						"'" . $offer->offerId->id . "'",
						$hotel->hotelId->id,
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

					$hotelOfferRecords[] = '(' . implode( ', ', $hotelOfferRecord ) . ')';
				}
			}
		}

		$offerRecordsString = implode( ', ', $hotelOfferRecords );

		$sql = "INSERT INTO odev_catalog_offer(offer_id, hotel_id, room, type, food, transport, check_in, length, price, currency, uah, operator_id)
            VALUES {$offerRecordsString}";
		$sqlResult = $wpdb->query( $sql );

		if( $sqlResult !== false ) {
			$importStatus = true;
		} else {
			if( DEBUG_MODE ) {
				var_dump( $sql );
				var_dump( $sqlResult );
			}
		}

		return $importStatus;
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
			}
			else {
				// Add page assets
				AssetManager::enqueueBootstrap();
				AssetManager::enqueueJQueryUI();

				wp_register_script( 'archive-hotel',
					ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_JS' ) . 'archive-hotel.js',
					array(),
					false,
					true );
				wp_enqueue_script( 'archive-hotel' );

				wp_register_style( 'archive-hotel',
					ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_CSS' ) . 'archive-hotel.css',
					[],
					false );
				wp_enqueue_style( 'archive-hotel' );

				$archiveTemplate = PLUGIN_ROOT_PATH . 'views/public/archive-hotel.php';
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
			}
			else {
				AssetManager::enqueueBootstrap();

				wp_register_style( 'single-hotel', ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_CSS' ) . 'single-hotel.css' );
				wp_enqueue_style( 'single-hotel' );
				wp_register_script( 'single-hotel', ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_JS' ) . 'single-hotel.js', [ 'jquery' ], false, true );
				wp_enqueue_script( 'single-hotel' );
                wp_enqueue_script( 'jquery-fancybox', 'https://export.otpusk.com/os/widgets/jquery.fancybox.pack.js', [ 'jquery' ], false );
				wp_register_style( 'jquery-fancybox', 'https://export.otpusk.com/os/widgets/jquery.fancybox.css' );
				wp_enqueue_style( 'hotel-slider', 'https://export.otpusk.com/os/widgets/hotel_slider.css' );

				$singleTemplate = PLUGIN_ROOT_PATH . 'views/public/single-hotel.php';
			}
		}

		return $singleTemplate;
	}

    public function ajaxImportHotelOffers() {
        if ( !$_GET[ 'secretkey' ] || $_GET[ 'secretkey' ] != ODevCatalogManager::getAjaxSecretKey() ) {
            echo 'Wrong key';
            exit;
        }

        try {
            $this->importHotelOffers();
            echo 'Hotel offers import done.';
        } catch ( Exception $exception ) {
            echo 'Error while importing hotel offers.';
            var_dump($exception);
        }
        exit;
    }

	public function ajaxImportHotelReviewsData() {
		if( !isset($_GET['secretkey']) || $_GET['secretkey'] != ODevCatalogManager::getAjaxSecretKey() ) {
			echo 'Wrong key';
			exit;
		}

		if( $this->importHotelReviewsData() ) {
			echo "Hotel rates import done";
		}
		else {
			echo 'Error while importing hotel offers.';
		}

		exit;
	}

	private function importHotelReviewsData() {

        ini_set('memory_limit', '-1');
		ini_set( "max_execution_time", 0 );

		if( DEBUG_MODE ) {
			ini_set( 'display_errors', 1 );
			ini_set( 'display_startup_errors', 1 );
		}

		$importStatus = true;

		// Initialize connections
		global $wpdb;
		$otpuskSoapApiClient = ODevCatalogManager::establishOtpuskSoapApiConnection();
		$otpuskSoapApiKey = ODevCatalogManager::getOtpuskSoapApiKey();

		if( isset( $_GET['limit'] ) ) {
			$limit = intval( $_GET['limit'] );
			if($limit < 1)
				$limit = 1;
		} else {
			$limit = 40;
		}

		if( isset( $_GET['offset'] ) ) {
			$offset = intval( $_GET['offset'] );
			if($offset < 0)
				$offset = 0;
		} else {
			$offset = 0;
		}

		$maxPage = $offset + $limit + 1;

		if( DEBUG_MODE ) {

			echo "limit: " . $limit . ' ';
			echo "offset: " . $offset . ' ';
			echo "maxPage: " . $maxPage . ' ';
			echo "<hr>";
			echo "memory_limit: " . ini_get( 'memory_limit' ) . '<hr>';

			function convert_memory( $size ) {
				$unit = array( 'b', 'kb', 'mb', 'gb', 'tb', 'pb' );
				return @round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . ' ' . $unit[ $i ];
			}

		}

		// Get existing hotels and rates
		$currentHotelsList = $wpdb->get_results('select otpusk_id, turpravda_votes_count, turpravda_rate from odev_catalog_hotel where turpravda_votes_count > 0', OBJECT_K);

		for ( $page = 1 + $offset; $page < $maxPage; $page++ ) {

			if( DEBUG_MODE ) {
				echo "page: " . $page . '<br>used memory: ' . convert_memory( memory_get_usage() );
				echo "<hr>";
			}

			$hotelsResponse = $otpuskSoapApiClient->getHotelList(
				$otpuskSoapApiKey,
				array(
					'page' => $page,
				)
			);

			if( !count( $hotelsResponse->hotels ) ) {
				break;
			}

			foreach ( $hotelsResponse->hotels as $hotel ) {
				if(is_object($hotel->rating ) && $hotel->rating->reviews) {
					$hId = $hotel->hotelId->id;
					$hRating = $hotel->rating;

					// Check for hotel
					if(isset($currentHotelsList[$hId])) {
						$currentHRate = $currentHotelsList[$hId]->turpravda_rate;
						$currentHVotesCount = $currentHotelsList[$hId]->turpravda_votes_count;

						// Check differens between existing and new rates
						if($currentHRate == $hRating->rate && $currentHVotesCount == $hRating->reviews) {
							continue;
						}
					}

					$sqlResult = $wpdb->update(
						'odev_catalog_hotel',
						[
							'turpravda_rate' => $hRating->rate,
						    'turpravda_votes_count' => $hRating->reviews,
						    'turpravda_module_status' => true
						],
						[
							'otpusk_id' => $hId
						],
						['%s', '%d', '%d'],
						['%d']
					);

					if($sqlResult === false) {
						$importStatus = false;
					}
				}
			}
		}

		return $importStatus;
	}
}
