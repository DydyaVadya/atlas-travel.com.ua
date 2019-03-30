<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 20.09.16
 * Time: 11:18
 */

?>

<?php get_header(); ?>

<?php while ( have_posts() ) :
    the_post(); ?>
    <?php
    $cntrySlug = CountryCPT::getPSlug();
    $rsrtSlug = ResortCPT::getPSlug();

    //COLLECT RESORT DATA
    $resortModel = new ResortModel(get_the_ID());
    $resortData = $resortModel->getData();

    //GET PARENT COUNTRY POST ID
    $otpCountryId = $resortData['otpusk_country_id'];
    $countryPostId = CountryCPT::getCountryPostIdByOtpuskId( $otpCountryId );
    if( $countryPostId ) {
        $resortData['country_post_id'] = $countryPostId;
    }

    //GET ALL RESORTS OF COUNTRY
    $countryResortsQ = new WP_Query( array(
        'post_type'      => $rsrtSlug,
        'posts_per_page' => -1,
        'post__not_in'   => array( $resortData['id'] ),
        'order'          => 'DESC',
        'meta_key'       => $rsrtSlug . '_priority',
        'meta_query'     => array(
            array(
                'key'   => $rsrtSlug . '_otpusk_country_id',
                'value' => $resortData['otpusk_country_id'],
            ),
        ),
    ) );
    $countryResorts = array();
    foreach ( $countryResortsQ->posts as $resort ) {
        $countryResorts[] = array(
            'id'         => $resort->ID,
            'title'      => $resort->post_title,
            'price_from' => ResortCPT::getMinPriceForResortPost($resort->ID),
        );
    }

    //GET PAGE HOTTOURS MODULE ID
    $osHottoursModuleId = false;
    if( isset($resortData['hottours_id']) && $resortData['hottours_id'] ) {
        $osHottoursModuleId = $resortData['hottours_id'];
    } else {
        // USE COUNTRY MODULE IF RESORT DOES NOT HAVE OWN MODULE
        $countryHottoursModuleId = get_post_meta( $resortData['country_post_id'],
            $cntrySlug . '_hottours_id', true );
        if( $countryHottoursModuleId ) {
            $osHottoursModuleId = $countryHottoursModuleId;
        }
    }

    //SET OS SEARCH SELECTED
    $osSearchDefaultLocation = '';
    if( $resortData['name_cases']['vn'] ) {
        $osSearchDefaultLocation = $resortData['name_cases']['vn'];
    }

    //GET HOTELS LIST BY RESORT
    $hotelsOnThePage = 12;
    $hotelsListModel = new HotelFilteredList( 'resort', $resortData['id'] );
    $hotelsListModel->where('turpravda_votes_count', '5', '>');
    $hotelsListModel->orderby( 'turpravda_rate', 'DESC' );
    $hotelsListModel->orderby( 'min_price_uah' );
    $hotelsListModel->setLimit( $hotelsOnThePage );

    //GET FIRSTLY HOTELS WITH REVIEWS AND MERGE WITH OTHERS
    $hotelsWithReviews = [];
    $hotelsWithoutReviews = [];

    $hotelsWithReviews = $hotelsListModel->getHotelList();

    if(count($hotelsWithReviews) < $hotelsOnThePage) {
        $hotelsListModel->where('turpravda_votes_count', '5', '<=');
        $hotelsListModel->setLimit($hotelsOnThePage - count($hotelsWithReviews));
        $hotelsWithoutReviews = $hotelsListModel->getHotelList();
    }

    $hotelsList = array_merge($hotelsWithReviews, $hotelsWithoutReviews);
    $resort_name_cases = unserialize(get_post_meta(get_the_ID(), 'resort_name_cases', true));
    ?>

    <div id="primary"
         class="content-area from-plugin">
        <main id="main"
              class="single-resort">

            <!-- Catalog breadcrumbs -->
            <div class="row">
                <div class="col-sm-12">
                    <?php include('partials/catalog-breadcrumbs.php'); ?>
                </div>
            </div>
            
            <!-- Resort page header-->
            <div class="resort-title-container clearfix">
                <h1 class="resort-map-title"><?php the_title(); ?></h1>
                <a href="<?php echo $_SERVER['REQUEST_URI'] . "hotels" ?>">Отели <?php echo $resort_name_cases["rd"]; ?></a>
            </div>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <!-- General Info and first part of decription	-->
                <?php if( isset($resortData['text_content_1']) ): ?>
                    <div class="description-map clearfix">
                        <div class="description-map-left">
                            <div class="section-title resort-description-title">
                                <?php if( isset($resortData['text_title_1']) && $resortData['text_title_1'] ): ?>
                                    <?php echo $resortData['text_title_1']; ?>
                                <?php else: ?>
                                    Описание курорта
                                <?php endif; ?>
                            </div>
                            <div class="resort-description-content">
                                <?php echo apply_filters( 'the_content', $resortData['text_content_1'] ); ?>
                            </div>
                        </div>
                        <div class="description-map-right">
                            <div class="resort-map"
                                id="resort-map"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- OnSite Hot Tours Module -->
                <?php if( $osHottoursModuleId ): ?>
                    <div class="os-hottours-container">
                        <div class="section-title resort-hot-title">
                            Лучшие предложения по
                            <?php echo (isset($resortData['hottours_id']) && $resortData['hottours_id']) ? 'курорту' : 'стране'; ?>
                        </div>
                        <?php
                        /*
                         * @Input:
                         * $osHottoursModuleId
                        */
                        include('partials/os-hot.php');
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Second part of desription -->
                <?php if( isset($resortData['text_content_2']) && trim(str_replace("&nbsp;","",strip_tags($resortData['text_content_2']))) != "" ): ?>
                    <?php /*<div class="section-title resort-description-title">
                        <?php if( isset($resortData['text_title_2']) && $resortData['text_title_2'] ): ?>
                            <?php echo $resortData['text_title_2']; ?>
                        <?php else: ?>
                            Описание курорта
                        <?php endif; ?>
                    </div>*/ ?>
                    <div class="resort-description-second">
                        <div class="resort-description-content ">
                            <?php echo apply_filters( 'the_content', $resortData['text_content_2'] ); ?>
                        </div>
                        <div class="resort-image">
                            <?php echo the_post_thumbnail(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                $photos = unserialize(unserialize($resortData['photos']));
                if($photos && is_array($photos)) {
                    ?>
                    <div class="ocar-gallery" data-gallery>
                        <?php
                        foreach ($photos as $photo_id){
                            $url = wp_get_attachment_url($photo_id);
                            if($url) {
                                ?>
                                <div>
                                    <a href="<?php echo $url; ?>">
                                        <img src="<?php echo $url; ?>">
                                    </a>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>

                <!-- OnSite Search Module -->
                <?php if( $resortData['os_search_status'] === 'on' ): ?>
                    <div class="c-m_country-search-container">
                        <form class="c-m_country-search-form">
                            <?php
                            /*
                                * @Input:
                                * $osSearchDefaultLocation
                            */
                            include('partials/os-search.php');
                            ?>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Popular resorts -->
                <?php if( count( $countryResorts ) ): ?>
                    <?php 
                    $countryNameRd = unserialize(get_post_meta($countryPostId, 'country_name_cases', true))["rd"];
                    ?>
                    <div class="resorts-container">
                        <div class="section-title all-resorts-title">Другие курорты <?php echo $countryNameRd; ?></div>
                        <div class="resorts-items">
                            <?php foreach ( $countryResorts as $countryResort ): ?>
                                <div class="all-resorts-item<?php if(!$countryResort['price_from']) echo ' no-price'; ?>">
                                    <div>
                                    <a href="<?php echo get_the_permalink( $countryResort['id'] ); ?>">
                                        <?php echo $countryResort['title']; ?>
                                    </a>
                                    <span><?php if ($countryResort['price_from']) echo number_format($countryResort['price_from'], 0, '.', ' ') . ' грн'; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                <?php endif; ?>

                <!-- Hotels list -->
                <?php if( count( $hotelsList ) ): ?>
                    <?php 
                    $ResortNameRd = $resort_name_cases["rd"];
                    ?>
                    <div class="section-title hotels-list-title">Лучшие отели <?php echo $ResortNameRd; ?></div>
                    <div class="section-subtitle">Согласно рейтинга Турправда</div>
                    <?php include('partials/short-hotels-list.php'); ?>
                    <div class="all-hotels-link">
                        <a href="<?php echo $_SERVER['REQUEST_URI'] . "hotels" ?>">Все отели <?php echo $resort_name_cases["rd"]; ?></a>
                    </div>
                <?php endif; ?>
            </article>
        </main>
    </div>
<?php endwhile; ?>

<?php get_footer(); ?>
