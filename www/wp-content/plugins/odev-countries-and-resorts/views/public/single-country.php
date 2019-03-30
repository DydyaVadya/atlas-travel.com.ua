<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 15.07.16
 * Time: 12:40
 */
?>

<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
    <?php
    $cntrySlug = CountryCPT::getPSlug();
    $rsrtSlug = ResortCPT::getPSlug();

    //COLLECT COUNTRY DATA
    $countryData = array(
        'id'    => get_the_ID(),
        'title' => get_the_title(),
    );
    $countryMeta = get_post_custom( get_the_ID() );

    while ( list($key, $values) = each( $countryMeta ) ) {
        if( substr( $key,
                0,
                1 ) === '_'
        ) {
            continue;
        }

        // MAKE KEYS WITHOUT SLUG
        $shortKey = str_replace( $cntrySlug . '_', '', $key );

        // MANUALY UNSERIALIZE META FIELDS
        if( in_array( $key, [ $cntrySlug . '_name_cases', $cntrySlug . '_preposition_cases' ] ) ) {
            $metaValueArray = unserialize( get_post_meta(
                $countryData['id'],
                $key,
                true
            ) );

            if( is_array( $metaValueArray ) ) {
                $countryData[$shortKey] = $metaValueArray;
            }
            else {
                $countryData[$shortKey] = [];
            }
            continue;
        }
        $countryData[$shortKey] = current( $values );
    }

    //GET CAPITAL NAME
    $capitalQuery = new WP_Query( array(
        'post_type'      => $rsrtSlug,
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'meta_query'     => array(
            array(
                'key'   => $rsrtSlug . '_otpusk_id',
                'value' => $countryData['capital_id'],
            ),
        ),
    ) );
    $countryData['capital'] = '';
    if( $capitalQuery->have_posts() ) {
        $countryData['capital'] = current( $capitalQuery->posts )->post_title;
    }

    //GET POPULAR RESORTS OF COUNTRY
    $popularResortsQ = new WP_Query( array(
        'post_type'      => $rsrtSlug,
        'posts_per_page' => -1,
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'meta_key'       => $rsrtSlug . '_display_type',
        'meta_query'     => array(
            array(
                'key'   => $rsrtSlug . '_otpusk_country_id',
                'value' => $countryData['otpusk_id'],
            ),
            array(
                'key'     => $rsrtSlug . '_display_type',
                'value'   => 'important',
                'compare' => 'LIKE',
            ),
        ),
    ) );
    $popularResorts = array();
    foreach ( $popularResortsQ->posts as $resortPost ) {
        $popularResorts[] = array(
            'id'         => $resortPost->ID,
            'title'      => $resortPost->post_title,
            'price_from' => ResortCPT::getMinPriceForResortPost( $resortPost->ID ),
        );
    }
    //GET NON POPULAR RESORTS
    $regularResortsQ = new WP_Query( array(
        'post_type'      => $rsrtSlug,
        'posts_per_page' => -1,
        'orderby'        => 'post_title',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'   => $rsrtSlug . '_otpusk_country_id',
                'value' => $countryData['otpusk_id'],
            ),
            array(
                'key'     => $rsrtSlug . '_display_type',
                'value'   => 'important',
                'compare' => 'NOT LIKE',
            ),
        ),
    ) );
    $regularResorts = array();
    foreach ( $regularResortsQ->posts as $resortPost ) {
        $regularResorts[] = array(
            'id'         => $resortPost->ID,
            'title'      => $resortPost->post_title,
            'price_from' => ResortCPT::getMinPriceForResortPost( $resortPost->ID ),
        );
    }

    //GET PAGE HOTTOURS MODULE ID
    $osHottoursModuleId = false;
    if( isset($countryData['hottours_id']) ) {
        $osHottoursModuleId = $countryData['hottours_id'];
    }

    //SET OS SEARCH SELECTED
    $osSearchDefaultLocation = '';
    if( $countryData['name_cases']['vn'] ) {
        $osSearchDefaultLocation = $countryData['name_cases']['vn'];
    }

    //GET HOTELS LIST BY RESORT
    $hotelsOnThePage = 12;
    $hotelsListModel = new HotelFilteredList( 'country', $countryData['id'] );
    $hotelsListModel->where( 'turpravda_votes_count', '5', '>' );
    $hotelsListModel->orderby( 'turpravda_rate', 'DESC' );
    $hotelsListModel->orderby( 'min_price_uah' );
    $hotelsListModel->setLimit( $hotelsOnThePage );

    //GET FIRSTLY HOTELS WITH REVIEWS AND MERGE WITH OTHERS
    $hotelsWithReviews = [];
    $hotelsWithoutReviews = [];

    $hotelsWithReviews = $hotelsListModel->getHotelList();

    if( count( $hotelsWithReviews ) < $hotelsOnThePage ) {
        $hotelsListModel->where( 'turpravda_votes_count', '5', '<=' );
        $hotelsListModel->setLimit( $hotelsOnThePage - count( $hotelsWithReviews ) );
        $hotelsWithoutReviews = $hotelsListModel->getHotelList();
    }

    $hotelsList = array_merge( $hotelsWithReviews, $hotelsWithoutReviews );
    ?>
    <div id="primary"
         class="content-area from-plugin">
        <main id="main"
              class="c-m_country-container single-country">

            <!-- Catalog breadcrumbs -->
            <div class="row">
                <div class="col-sm-12">
                    <?php include('partials/catalog-breadcrumbs.php'); ?>
                </div>
            </div>

            <!-- Country page header-->
            <div class="c-m_country-map-container">
                <div class="c-m_country-map-title"><?php the_title(); ?></div>
                <div class="c-m_country-map"
                     id="country-map"></div>
            </div>
            <div class="c-m_country-desc">
                <div class="c-m_country-desc-col c-m_country-desc-left">
                    <?php if( isset($countryData['text_content_1']) ): ?>
                        <?php if( isset($countryData['text_title_1']) && $countryData['text_title_1'] ): ?>
                            <div class="c-m_country-desc-title"><?php echo $countryData['text_title_1']; ?></div>
                        <?php else: ?>
                            <div class="c-m_country-desc-title">Описание страны</div>
                        <?php endif; ?>
                        <?php echo apply_filters( 'the_content', $countryData['text_content_1'] ); ?>
                    <?php endif; ?>
                </div>
                <div class="c-m_country-desc-col c-m_country-desc-right">
                    <div class="c-m_country-image"
                         style="background-image:url(<?php echo get_the_post_thumbnail_url( get_the_ID(), 'medium' ); ?>);"></div>
                    <div class="c-m_country-desc-list">
                        <div class="c-m_country-desc-list_item">
                            <span>Столица</span>
                            <span><?php echo $countryData['capital']; ?></span>
                        </div>
                        <div class="c-m_country-desc-list_item">
                            <span>Язык</span>
                            <span><?php echo $countryData['language']; ?></span>
                        </div>
                        <div class="c-m_country-desc-list_item">
                            <span>Площадь</span>
                            <span><?php echo $countryData['territory']; ?></span>
                        </div>
                        <div class="c-m_country-desc-list_item">
                            <span>Население</span>
                            <span><?php echo $countryData['population']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular resorts -->
            <?php if( count( $popularResorts ) ): ?>
                <div class="c-m_price-list-container">
                    <div class="c-m_price-list-title-line"></div>
                    <div class="c-m_price-list-title">Популярные курорты</div>
                    <div class="c-m_price-list">
                        <?php foreach ( $popularResorts as $popularResort ): ?>
                            <div class="c-m_price-list-item<?php echo $popularResort['price_from'] ? '' : " no-price"; ?>">
                                <a href="<?php echo get_the_permalink( $popularResort['id'] ); ?>"><?php echo $popularResort['title']; ?></a>
                                <span><?php echo $popularResort['price_from'] ? number_format($popularResort['price_from'], 0, '.', ' ') . ' грн' : ''; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="row c-m-regular-resorts-list collapse"
                         id="c-m_regular-resorts-list">
                        <?php
                        $resortsPerCol = ceil( count( $regularResorts ) / 3 );
                        if($resortsPerCol === 0)
	                        $resortsPerCol = 1;
                        $regResortsCols = array_chunk( $regularResorts, $resortsPerCol );
                        foreach ( $regResortsCols as $resorts ):
                            ?>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <?php foreach ( $resorts as $regularResort ): ?>
                                    <div class="c-m_price-list-item">
                                        <a href="<?php echo get_the_permalink( $regularResort['id'] ); ?>">
                                            <?php echo $regularResort['title']; ?>
                                        </a> 
                                        <span><?php echo $regularResort['price_from'] ? number_format($regularResort['price_from'], 0, '.', ' ') . ' грн' : ''; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="c-m_all-resorts-wrap">
                        <a class="c-m_all-resorts-link"
                           role="button"
                           data-toggle="collapse"
                           href="#c-m_regular-resorts-list"
                           aria-expanded="false"
                           aria-controls="c-m_regular-resorts-list">курорты</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- OnSite Hot Tours Module -->
            <?php if( $osHottoursModuleId ): ?>
                <div class="os-hottours-container">
                    <div class="c-m_country-desc-title">Лучшие предложения по стране</div>
                    <?php
                    /*
                        * @Input:
                        * $osHottoursModuleId
                    */
                    include('partials/os-hot.php');
                    ?>
                </div>
            <?php endif; ?>

            <!-- OnSite Search Module -->
            <?php if( $countryData['os_search_status'] === 'on' ): ?>
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
            
            <!-- Second part of desription -->
            <?php if( isset($countryData['text_content_2']) && trim(str_replace("&nbsp;","",strip_tags($countryData['text_content_2']))) != ""): ?>
                <div class="c-m_country-desc c-m_country-desc-second">
                    <div class="c-m_country-desc-col c-m_country-desc-left">
                            <?php /*if( isset($countryData['text_title_2']) && $countryData['text_title_2'] ): ?>
                                <h3><?php echo $countryData['text_title_2']; ?></h3>
                            <?php else: ?>
                                <h3>Описание страны</h3>
                            <?php endif;*/ ?>
                            <?php echo apply_filters( 'the_content', $countryData['text_content_2'] ); ?>
                    </div>
                    <div class="c-m_country-desc-col c-m_country-desc-right">
                        <?php
                        $photos = unserialize(unserialize($countryData['thumbnails']));
                        if($photos && is_array($photos)) {
                        $url = wp_get_attachment_url($photos[0]);
                        if($url) {
                        ?>
                        <div class="c-m_country-image"
                             style="background-image:url(<?php echo $url; ?>);"></div>
                    <?php } }  ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Hotels list -->
            <?php if( count( $hotelsList ) ): ?>
                <div class="c-m_country-desc-title">Лучшие отели страны</div>
                <div class="c-m_country-desc-subtitle">Согласно рейтинга Турправда</div>
                <?php include('partials/short-hotels-list.php'); ?>
            <?php endif; ?>


        </main><!-- #main -->
    </div><!-- #primary -->
<?php endwhile; ?>
<?php get_footer(); ?>
