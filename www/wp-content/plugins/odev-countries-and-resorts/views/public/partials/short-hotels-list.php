<?php
    /**
     * Created by PhpStorm.
     * User: aleksandrfishchenko
     * Date: 23.09.16
     * Time: 12:26
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

<style>
.c-m_country-hor-body.no-price .c-m_country-hor-rating{
    left: 130px;
}
</style>

<div class="c-m_country-bl-list-wrap">
    <div class="c-m_country-hor-bl-list clearfix">
        <?php if( !count( $hotelsList ) ): ?>
            <div class="col-xs-12">
                <h3>Отели не найдены</h3>
            </div>
        <?php endif; ?>
        <?php foreach ( $hotelsList as $hotel ): ?>
            <div class="c-m_country-hor-bl">
                <a href="<?php echo $hotel['link']; ?>" class="c-m_country-hor-wrap">
                    <?php
                    if($hotel['otpusk_image_url'])
                        echo '<div class="c-m_country-hor-img" style="background-image: url(' . $hotel['otpusk_image_url'] . ');"></div>';
                    else
                        echo '<div class="c-m_country-hor-img no-image"></div>';
                    ?>
                    <?php if($hotel['min_price_uah']): ?>
                        <div class="c-m_country-hor-price">от <b><?php echo number_format($hotel['min_price_uah'], 0, ',', '&nbsp;'); ?></b> <span>грн<br> <span>на двоих</span></span></div>
                    <?php endif; ?>
                    <div class="c-m_country-hor-body<?php echo $hotel['min_price_uah'] ? '' : ' no-price'; ?>">
                        <div class="c-m_country-hor-title">
                            <?php echo $hotel['name'] . ' <span>' . $hotel['stars'] . '*</span>'; ?>
                        </div>
                        <div class="c-m_country-hor-desc">
                            <?php echo $hotel['country'] . ', ' . $hotel['resort'] . ', Отели ' . $hotel['resort_names']['rd']; ?>
                        </div>
                        <div class="c-m_country-hor-rating">
                            <?php echo $hotel['turpravda_rate']; ?>
                            <div class="c-m_rating-container">
                                <div class="c-m_rating-value" data-value="<?php echo number_format(floatval(str_replace(',', '.', $hotel['turpravda_rate'])), 1, '.', ''); ?>"></div>
                            </div>
                            <span class="link">
                            <?php echo $hotel['turpravda_votes_count'] . ' ' . ODevCatalogManager::getPluralFormForNumber( $hotel['turpravda_votes_count'], [ 'отзыв', 'отзыва', 'отзывов' ] ); ?>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

