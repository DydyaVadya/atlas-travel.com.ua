<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 11.07.16
 * Time: 16:34
 */
get_header();

$cntrySlug = CountryCPT::getPSlug();
$rsrtSlug = ResortCPT::getPSlug();

//GET COUNTRIES
$countriesPosts = new WP_Query( array(
    'post_type'      => $cntrySlug,
    'posts_per_page' => -1,
    'orderby'        => array(
        'title' => 'ASC',
    ),
) );
$allCountries = array();
$popularCountries = array();
//APPLY META FIELDS AND SPLIT FOR CONTINENTS
foreach ( $countriesPosts->posts as $countryPost ) {
    $ID = $countryPost->ID;
    $countryPostMeta = get_post_custom( $ID );

    while ( list($mKey, $mValue) = each( $countryPostMeta ) ) {
        if( substr( $mKey, 0, 1 ) === '_' ) {
            unset($countryPostMeta[$mKey]);
        }
        else {
            $countryPostMeta[$mKey] = current( $mValue );
        }
    }

    $country = array(
        'id'     => $countryPost->ID,
        'title'  => $countryPost->post_title,
        'status' => $countryPost->post_status,
    );
    $country = array_merge( $country, $countryPostMeta );
    $currentContinent = $country['country_continent'];

    //ADD COUNTRY TO CONTINENT SUB ARRAY
    $allCountries[$currentContinent][] = $country;
    //ADD COUNTRY TO POPULAR ARRAY
    if( $country['country_is_popular'] > 0 ) {
        $popularCountries[] = $country;
    }
}
//SORT CONTINENTS
ksort( $allCountries );

//GET RESORTS
$popularResortsList = new WP_Query( array(
    'post_type'      => $rsrtSlug,
    'posts_per_page' => 20,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'meta_query'     => array(
        array(
            'key'   => $rsrtSlug . '_display_type',
            'value' => 'important',
        ),
    ),
) );
$countryResorts = array();
if( $popularResortsList->have_posts() ) {
    foreach ( $popularResortsList->posts as $resortPost ) {
        $resortPriceFrom = ResortCPT::getMinPriceForResortPost( $resortPost->ID );
        $resortNameCases = unserialize( get_post_meta( $resortPost->ID, 'resort_name_cases', true ) );
        $resortNameCasesPrepositions = unserialize( get_post_meta( $resortPost->ID, 'resort_preposition_cases', true ) );

        $countryResorts[] = array(
            'id'                 => $resortPost->ID,
            'title'              => $resortPost->post_title,
            'titleVn'            => $resortNameCases['vn'],
            'titlePrepositionVn' => $resortNameCasesPrepositions['vn'],
            'priceFrom'          => $resortPriceFrom,
        );
    }
}

//usort($countryResorts, function($a, $b) {
//    return intval($b['priceFrom']) - intval($a['priceFrom']);
//});
?>

<div class="c-m_container">
    <!-- Catalog breadcrumbs -->
    <div class="row">
        <div class="col-sm-12">
            <?php include('partials/catalog-breadcrumbs.php'); ?>
        </div>
    </div>

    <div class="c-m_world-map">
        <img src="<?php echo plugins_url("odev-countries-and-resorts/assets/public/img/world-map.png"); ?>">
        <?php
        $markers = array( 'red', 'orange', 'purple', 'teal', 'green', 'yellow' );
        foreach ( CountryCPT::getContinents() as $cId => $cName ) {
            ?>
            <a href="#continent-<?php echo $cId; ?>">
                    <div class="c-m_world-map-item c-m_id-<?php echo $cId; ?>">
                        <?php echo $cName; ?><br>
                        <img
                            src="<?php echo esc_url( ODevCatalogManager::getAssetTypeUrl( 'PUBLIC_IMG' ) . 'marker-' . $markers[intval( $cId ) - 1] . '.png' ); ?>"
                            alt="<?php echo $cName; ?>"<?php if( $cId == 4 ) {
                            echo ' width="27"';
                        } ?>>
                    </div>
                </a>
            <?php
        }
        ?>
    </div>
    <div class="c-m_search-container">
        <form class="clearfix">
            <span class=c-m_search-input-container>
            <input type="text"
                   placeholder="Отель, курорт или страна"
                   class="c-m_search-input fl"
                   id="search-location-input">
            </span>
            <input type="submit"
                   id="search-location-submit"
                   class="c-m_search-submit fl"
                   value="Поиск">
            <div id="search-location-autocomplete"></div>
        </form>
    </div>
    <div id="continent-tabs-wrap">
        <ul class="nav nav-tabs c-m_search-menu clearfix"
            role="tablist"
            id="continent-tabs">
            <li class="active"
                role="presentation">
                <a href="#popular"
                   class="c-m_popular-link"
                   aria-controls="popular"
                   role="tab"
                   data-toggle="tab">Популярные</a>
            </li>
            <?php foreach ( $allCountries as $continentId => $continentCountries ): ?>
                <li role="presentation">
                    <a href="#continent-<?php echo $continentId; ?>"
                       aria-controls="continent-<?php echo $continentId; ?>"
                       role="tab"
                       data-toggle="tab"
                       class="c-m_id-<?php echo $continentId; ?>-link"><?php echo CountryCPT::getContinentName( $continentId ); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="tab-content c-m_content clearfix">
            <div role="tabpanel clearfix"
                 class="tab-pane fade in active c-m_country-list c-m_first-list"
                 id="popular">
                <?php
                foreach ( $popularCountries as $country ):
                    $image = get_the_post_thumbnail_url( $country['id'], 'medium' );
                    if( in_array( $country['status'], array( 'publish' ) ) ) {
                        ?>
                        <a href="<?php echo get_the_permalink( $country['id'] ); ?>"
                           class="c-m_country-item">
                                <div style="background-image: url(<?php echo $image; ?>);">
                                    <div class="c-m_country-item-title">
                                        <?php echo $country['title']; ?>
                                    </div>
                                </div>
                            </a>
                        <?php
                    }
                    else {
                        ?>

                        <div class="c-m_country-item">
                                <div style="background-image: url(<?php echo $image; ?>);">
                                    <div class="c-m_country-item-title">
                                        <?php echo $country['title']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                    ?>
                    <?php
                endforeach;
                ?>
            </div>
            <?php foreach ( $allCountries as $continentId => $continentCountries ):
                ?>
                <div role="tabpanel"
                     class="tab-pane fade c-m_country-list c-m_first-list"
                     id="continent-<?php echo $continentId; ?>">
                    <?php
                    foreach ( $continentCountries as $country ):
                        $image = get_the_post_thumbnail_url( $country['id'], 'medium' );
                        if( in_array( $country['status'], array( 'publish' ) ) ) {
                            ?>
                            <a href="<?php echo get_the_permalink( $country['id'] ); ?>"
                               class="c-m_country-item">
                                    <div style="background-image: url(<?php echo $image; ?>);">
                                        <div class="c-m_country-item-title">
                                            <?php echo $country['title']; ?>
                                        </div>
                                    </div>
                                </a>
                            <?php
                        }
                        else {
                            ?>

                            <div class="c-m_country-item"
                                 style="background-image: url(<?php echo $image; ?>);">
                                    <div class="c-m_country-item-title">
                                        <?php echo $country['title']; ?>
                                    </div>
                                </div>
                            <?php
                        }
                        ?>
                        <?php
                    endforeach;
                    ?>
                </div>
                <?php
            endforeach;
            ?>
        </div>
    </div>
    <div class="c-m_content">
        <div class="c-m_price-list-container">
            <div class="c-m_price-list-title">Популярные курорты</div>
            <div class="c-m_price-list">
                <?php foreach ( $countryResorts as $pResort ): ?>
                    <div class="c-m_price-list-item<?php echo $pResort['priceFrom'] ? '' : " no-price"; ?>">
                        <a href="<?php echo get_the_permalink( $pResort['id'] ); ?>">
                            Туры <?php echo($pResort['titlePrepositionVn'] ? $pResort['titlePrepositionVn'] : 'в'); ?> <?php echo($pResort['titleVn'] ? $pResort['titleVn'] : $pResort['title']); ?>
                        </a>
                        <span><?php echo $pResort['priceFrom'] ? number_format( $pResort['priceFrom'], 0, '.', ' ' ) . ' грн' : ''; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery( '.c-m_world-map a' ).click( function () {
        var href = jQuery( this ).attr( 'href' );
        var scrollTop = jQuery( '.c-m_search-menu' ).offset().top;
        if ( jQuery( '#wpadminbar' ).length )
            scrollTop -= jQuery( '#wpadminbar' ).height();
        if ( jQuery( '.c-m_search-menu a[href="' + href + '"]' ).length )
            jQuery( 'html,body' ).animate( { scrollTop: scrollTop }, 400, function () {
                jQuery( '.c-m_search-menu a[href="' + href + '"]' ).click();
            } );
    } );

    jQuery( document ).ready( function () {
        var searchLocation = new window.application.LocationAutocomplete( 'search-location-input', 'search-location-autocomplete', 'search-location-submit' );
    } );
</script>

<?php get_footer(); ?>
