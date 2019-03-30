<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 26.09.16
 * Time: 12:17
 */

/**
 * HotelModel data:
 * @post_id
 * @otpusk_id
 * @resort_post_id
 * @resort_otpusk_id
 * @country_post_id
 * @country_otpusk_id
 * @stars
 * @turpravda_link
 * @turpravda_rate
 * @turpravda_votes_count
 * @otpusk_image_url
 * @min_price_uah
 * @min_price_offer_id
 * @name
 * @nameTr
 * @otpusk_module_status
 * @turpravda_module_status
 * @last_updated
 * @country
 * @country_slug
 * @resort
 * @resort_slug
 * @hotel_slug
 * @resort_names
 * @link
 */

?>
<?php get_header(); ?>

<?php while ( have_posts() ) :
    the_post(); ?>
    <?php
    // Get page hotel data
    $hotelPostId = get_the_ID();
    $hotelModel = new HotelModel( $hotelPostId );
    $hotelData = $hotelModel->getData();

    // Get hotel photos
    $hotelPhotosIdsRaw = get_post_meta( $hotelPostId, HotelCPT::getPSlug() . '_photos', true );
    $hotelPhotosIds = @unserialize( $hotelPhotosIdsRaw );
    if( !is_array( $hotelPhotosIds ) ) {
        $hotelPhotosIds = [];
    }

    //Get page hotel offer data
    $hotelOffer = false;
    if( $hotelData['min_price_offer_id'] ) {
        $hotelOfferModel = new OfferModel( $hotelData['min_price_offer_id'] );
        $hotelOffer = $hotelOfferModel->getData();

        $hotelOffer->check_in = new DateTime( $hotelOffer->check_in );
        $hotelOffer->check_in = $hotelOffer->check_in->format( 'd.m.Y' );
    }
    ?>
    <div id="primary"
         class="content-area from-plugin">
        <main id="main"
              class="single-hotel">

            <!-- Catalog breadcrumbs -->
            <div class="row">
                <div class="col-sm-12">
                    <?php include('partials/catalog-breadcrumbs.php'); ?>
                </div>
            </div>

            <!-- Hotels page header -->
            <header class="hotel-header grid">
                <div class="width-main">
                    <h1 class="hotel-title">
                        <?php the_title(); ?>
                    </h1>
                    <h3 class="hotel-location">
                        <?php
                        echo get_the_title( $hotelData['country_post_id'] );
                        echo ', ';
                        echo get_the_title( $hotelData['resort_post_id'] );
                        ?>
                    </h3>
                    <div class="hotel-stars">
                        <?php
                        if($hotelData['stars'] == "HV1" || $hotelData['stars'] == "HV2")
                            echo $hotelData['stars'];
                        else {
                            for($i = 0; $i < $hotelData['stars']; $i++){
                                echo "<span class='filled-star'></span>";
                            }
                            for($i = 5; $i > $hotelData['stars']; $i--){
                                echo "<span class='empty-star'></span>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <!--Rating info-->
                <?php if( $hotelData['turpravda_rate'] ): ?>
                <div class="width-sidebar">
                    <div class="hotel-rating clearfix">
                        <div class="rating-container">
                            <div class="rating-value" 
                                 style="width: <?php echo 10 * floatval(str_replace(',', '.', $hotelData['turpravda_rate'])); ?>%;">
                            </div>
                        </div>
                        <div class="rating-label">
                            <?php echo $hotelData['turpravda_rate']; ?>
                        </div>
                    </div>
                    <a href="#reviews" data-scrollspy class="reviews-link">
                        <?php echo $hotelData['turpravda_votes_count'] . ' ' . 
                                   ODevCatalogManager::getPluralFormForNumber( $hotelData['turpravda_votes_count'], [ 'отзыв', 'отзыва', 'отзывов' ] ); ?>
                    </a>
                </div>
                <?php endif; ?>
            </header><!-- .page-header -->
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <?php if( get_the_content() || count( $hotelPhotosIds ) ): ?>
                <div class="hotel-desc-image">
                    <!--  Local hotel description  -->
                    <?php if( get_the_content() ): ?>
                            <?php the_content(); ?>
                    <?php endif; ?>

                    <!-- Hotel photo -->
                    <?php if( count( $hotelPhotosIds ) ): ?>
                        <?php
                        reset($hotelPhotosIds);
                        $wpAttachmentData = wp_get_attachment_image_src( current($hotelPhotosIds), 'large' );
                        $hPhotoUrl = $wpAttachmentData[0];
                        ?>
                        <div class="hotel-first-photo" style="background-image: url(<?php echo $hPhotoUrl; ?>);">
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="tabs">
                    <div class="line"></div>
                    <button data-link="details" class="active">Подробно об отеле</button>
                    <?php if( $hotelData['turpravda_module_status'] ): ?>
                    <button data-link="reviews">Отзывы</button>
                    <?php endif; ?>
                    <button data-link="search-hotels">Туры</button>
                    <?php /*
                    <button style="display: none;" data-link="similar-hotels">Похожие отели</button>
                    */ ?>
                </div>
                
                <div class="tabs-content">
                    <div id="details">
                        <!-- Otpusk description widget -->
                        <?php if( $hotelData['otpusk_module_status'] ): ?>
                            <div class="row otpusk-module-block">
                                <div class="col-sm-12">
                                    <script src="//export.otpusk.com/js/hotel?id=<?php echo $hotelData['otpusk_id']; ?>&k=<?php echo ODevCatalogManager::getExportApiKey(); ?>"></script>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="reviews" class="grid">
                        <!--  Turpravda reviews widget  -->
                        <?php if( $hotelData['turpravda_module_status'] ): ?>   
                        
                            <style>
                                #cg-r3{width:100%;height:615px !important;border:none; padding: 0 20px;}
                                .infHtmlcg-r3{width: 66%;}
                                .agencyLink{display:block; text-align:center; font-size:12px !important; color:#7a7a7a !important; margin-top: 0px;  margin-bottom: 10px;}
                            </style>
                            <div class="infHtmlcg-r3">
                                <iframe style="height: 615px;" id="cg-r3" class="cg-r3 tp-informer big-informer" src="https://www.turpravda.com/informers/hotel/?htl=<?php echo $hotelData['otpusk_id']; ?>&tp=9&skin=1&count=5"></iframe>
                                <a class="agencyLink" href="<?php echo $hotelData['turpravda_link']; ?>"><?php the_title(); ?></a>
                            </div>                        
                            <!--div class="row turpravda-widget-block">
                                <div class="col-sm-12">
                                    <iframe id="turpravda-widget"
                                            src="https://www.turpravda.com/informers/hotel/?htl=<?php echo $hotelData['otpusk_id']; ?>&tp=9&skin=1&count=3"></iframe>
                                </div>
                            </div-->
                        <?php endif; ?>
                        <!--  Min price offer details  -->
                        <?php if( $hotelOffer ):?>
                            <div class="hotel-offer">
                                <div class="hotel-offer-title">Лучшее предложение</div>
                                <div class="hotel-offer-details">
                                    <div class="hotel-offer-date">
                                        <?php echo $hotelOffer->check_in . " на " . $hotelOffer->length . ' ' . ODevCatalogManager::getPluralFormForNumber( $hotelOffer->length, [ 'день',
                                                                                                                                                                                    'дня',
                                                                                                                                                                                    'дней' ] ); ?>
                                    </div>
                                    <div class="hotel-offer-food">
                                        <?php echo "Питание: " . strtoupper( $hotelOffer->food ); ?>
                                    </div>
                                    <div class="hotel-offer-transport">
                                        <?php
                                        if($hotelOffer->transport == "air")
                                            echo "Авиаперелет";
                                        elseif($hotelOffer->transport == "bus")
                                            echo "Автобус";
                                        elseif($hotelOffer->transport == "train")
                                            echo "Поезд";
                                        elseif($hotelOffer->transport == "ship")
                                            echo "Судно";
                                        elseif($hotelOffer->transport == "no")
                                            echo "Транспорт не включён";
                                        else echo $hotelOffer->transport;
                                        ?>
                                    </div>
                                    <div>
                                        Номер: <?php echo $hotelOffer->room; ?>
                                    </div>
                                    <?php
                                    if($_SERVER['SERVER_NAME'] == 'hotels-demo.odev.io')
                                        $link = 'https://www.otpusk.com/info/os/e4/';
                                    else {
                                        $active_offer_target = HotelCPT::getActiveOfferTarget();
                                        switch ($active_offer_target){
                                            case 'home':
                                                $link = get_home_url();
                                                break;
                                            case 'self':
                                            default:
	                                            $link = get_permalink();
                                                break;
                                        }
                                    }
                                    $link .= '#!f=1&t=' . str_replace(' ', '%20', get_the_title())  . ' ' . $hotelData['stars'] . '*';
                                    $link .= '&i=' . $hotelOffer->hotel_id . '&y=hotel';
                                    $date = DateTime::createFromFormat('d.m.Y', $hotelOffer->check_in );
                                    $c = clone $date;
                                    $v = clone $date;
                                    $c = $c->sub(new DateInterval('P2D'));
                                    $v = $v->add(new DateInterval('P2D'));
                                    $link .= '&c=' . $c->format( 'Y-m-d' )
                                          . '&v=' . $v->format( 'Y-m-d' )
                                          . '&l=' . $hotelOffer->length . '&p=2' // duration and peoples
                                          . '&d=1544' // city
                                          . '&page=form';

                                    $hotel_offer_blank = HotelCPT::isOfferTargetBlank() ? 'target="_blank"' : '';
                                    ?>
                                    <a href="<?= $link ?>" <?= $hotel_offer_blank ?> class="hotel-offer-price">
                                        <?= number_format($hotelOffer->uah, 0, '.', ' ') . ' <span>грн</span>' ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- Hotel photos -->
                        <?php if( count( $hotelPhotosIds ) ): ?>
                            <div class="wp-images">
                                <?php foreach ( $hotelPhotosIds as $hPhotoId ): ?>
                                    <?php
                                    $wpAttachmentData = wp_get_attachment_image_src( $hPhotoId, 'full' );
                                    $hPhotoUrl = $wpAttachmentData[0];
                                    ?>
                                    <img src="<?php echo $hPhotoUrl; ?>"
                                            alt="">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="search-hotels">
                        <!--  OnSite Search Module  -->
                        <div class="c-m_country-search-container">
                            <form class="c-m_country-search-form">
                                <?php
                                $osSearchDefaultLocation = $hotelData['name'];
                                /*
                                    * @Input:
                                    * $osSearchDefaultLocation
                                */
                                include('partials/os-search.php');
                                ?>
                            </form>
                        </div>
                    </div>
                    <?php /*
                    <div id="similar-hotels">
                        <!--  Otpusk link  -->
                        <?php $otpuskHotelLink = "//www.otpusk.com/hotel/{$hotelData['otpusk_id']}-{$hotelData['nameTr']}"; ?>
                        <!--a class="agency-link"
                        href="<?php echo $otpuskHotelLink; ?>">
                            <?php echo $hotelData['name']; ?>
                        </a-->
                    </div>
                    */ ?>
                </div>

            </article>
        </main><!-- #main -->
    </div><!-- #primary -->
<?php endwhile; ?>

<script src="<?php echo plugins_url("odev-countries-and-resorts/assets/public/js/jquery.sticky.js"); ?>"></script>
<script>
if (!window.jQuery) {
  var jq = document.createElement('script'); jq.type = 'text/javascript';
  jq.src = 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js';
  document.getElementsByTagName('head')[0].appendChild(jq);
}
var tabs = jQuery('.tabs > button');
jQuery('.tabs .line').css({
    'width': tabs.filter('.active').width(),
    'left': tabs.filter('.active').position().left,
});
tabs.click(function(){
    jQuery('html, body').animate({scrollTop:jQuery('#' + jQuery(this).attr('data-link')).offset().top}, 800);
});
jQuery(".tabs").sticky({topSpacing:0});
jQuery(window).scroll(function(){
    var index = 0;
//     jQuery('.tabs > button').each(function(i, obj){
    for(var i = tabs.length - 1; i >= 0; i--){
        if((jQuery('#' + tabs.eq(i).attr('data-link')).offset().top - 120) < jQuery(window).scrollTop()){
            index = i;
            break;
        }
    };
    jQuery('.tabs button').eq(index).addClass('active').siblings('button').removeClass('active');
    jQuery('.tabs .line').css({
        'width': tabs.filter('.active').width(),
        'left': tabs.filter('.active').position().left + (1 * tabs.filter('.active').css('padding-left').replace(/[^-\d\.]/g, '')),
    });
});
jQuery('a[data-scrollspy]').click(function(e){
    e.preventDefault();
    var target = jQuery(jQuery(this).attr('href'));
    if(target.length)
        jQuery('html, body').animate({scrollTop: target.first().position().top - 90}, 1000);
    else
        console.log(target);
});
<?php /*
tabs.click(function(){
    var index = tabs.index(this);
    if(jQuery('.tabs-content > button').eq(index).css('display') == 'block')
        return false;
    jQuery(this).addClass('active').siblings('button').removeClass('active');
    jQuery('.tabs .line').animate({
        'width': tabs.filter('.active').outerWidth(),
        'left': tabs.filter('.active').position().left,
    }, 400);
    jQuery('.tabs-content > *.active').fadeOut(200, function(){
        jQuery('.tabs-content > *').eq(index).addClass('active').siblings().removeClass('active');
        jQuery('.tabs-content > *.active').fadeIn(200);
    });
});
*/ ?>
</script>

<?php get_footer(); ?>
