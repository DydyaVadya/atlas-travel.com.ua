<?php
    /**
     * Created by PhpStorm.
     * User: aleksandrfishchenko
     * Date: 23.09.16
     * Time: 16:39
     */
?>
<?php
    // Get data about resort and create Hotel List Model
    $resortSlug = get_query_var( 'resort_slug', false );
    $countrySlug = get_query_var('country_slug', false);
    $resortPost = false;

    $hotelListModel = false;
    $resortData = false;

    // Prepare data
    if( $resortSlug && $resortPost = ResortCPT::getResortPostBySlug( $resortSlug ) ) {
        // Get resort data
        $resortModel = new ResortModel( $resortPost->ID );
        $resortData = $resortModel->getData();

        // Prepare hotel list data
        $hotelsPerPage = 48;
        $hotelListModel = new HotelFilteredList( 'resort', $resortPost->ID );
        $hotelListModel->setLimit($hotelsPerPage);

        // Set category filters values
        // Firstly check for post form values, then check get parameters from pagination
        $hotelCategoriesChecked = [ '1', '2', '3', '4', '5' ];
        if( isset($_POST['hotel_category']) ) {
            $hotelCategoriesChecked = $_POST['hotel_category'];
            $hotelListModel->setCategories($hotelCategoriesChecked);
        }
        else if(isset($_GET['category'])) {
            $categories = explode(',', $_GET['category']);
            if(is_array($categories)) {
                $hotelCategoriesChecked = $categories;
                $hotelListModel->setCategories( $hotelCategoriesChecked );
            }
        }

        // Set rate filters
        // Firstly check for post form values, then check get parameters from pagination
        $hotelRateFrom = 4;
        if( isset($_POST['hotel_rate_from']) ) {
            $hotelRateFrom = $_POST['hotel_rate_from'];
        }
        else if(isset($_GET['rate'])) {
            $hotelRateFrom = $_GET['rate'];
        }

        if($hotelRateFrom > 4) {
            $hotelListModel->setRateFrom( $hotelRateFrom );
        }

        $hotelListModel->orderby( 'turpravda_rate', 'DESC' );
        $hotelListModel->orderby( 'min_price_uah' );

        // Check total count
        $totalHotels = $hotelListModel->count();
        $totalHotelPages = ceil($totalHotels/$hotelsPerPage);
        if(!$totalHotelPages) {
            $totalHotelPages = 1;
        }
        // Get pagination variables from request
        $requestedHotelPage = get_query_var('paged', 0);
        if(!$requestedHotelPage) {
            $requestedHotelPage = 1;
        }

        if($requestedHotelPage > $totalHotelPages) {
            // Redirect if page parameter out of range
            $countrySlug = $countrySlug ? $countrySlug : get_query_var('country_slug', '');
            $resortSlug = $resortSlug ? $resortSlug : get_query_var('resort_slug', '');

            if($countrySlug && $resortSlug) {
                wp_redirect(home_url('/' . ODevCatalogManager::getPrefix() . '/' . $countrySlug . '/' . $resortSlug . '/hotels'));
            }
            else {
                wp_redirect(home_url('/' . ODevCatalogManager::getPrefix() . '/'));
            }
            exit;
        }
        // Apply pagination to model
        $hotelListModel->setOffset( ($requestedHotelPage - 1) * $hotelsPerPage );

        // Get hotel list data
        $hotelsList = $hotelListModel->getHotelList();

    }
    // If resort does not find, redirect to catalog main page
    else {
        wp_redirect(home_url('/' . ODevCatalogManager::getPrefix()));
        exit;
    }

    // Set page title

    $pageTitle = 'Отели ';
    if( $resortPost ) {
        $pageTitle .= $resortData['name_cases']['rd'];
    }

?>
<?php get_header(); ?>
<div id="primary"
     class="content-area from-plugin">
    <main id="main" class="single-country">

        <!-- Catalog breadcrumbs -->
        <div class="row">
            <div class="col-sm-12">
                <?php include('partials/catalog-breadcrumbs.php'); ?>
            </div>
        </div>
        
        <?php 
        if( $resortPost ) {
            ?>
            <div class="back-link-row">
                <a href="<?php echo get_the_permalink( $resortData['id'] ); ?>">← Вернуться в <?php echo $resortData['title']; ?></a>
            </div>
            <?php
        }
        ?>
        
        <!-- Hotels page filters form -->
        <div class="hotels-filter">
            <form method="post" action="<?php echo home_url('/' . ODevCatalogManager::getPrefix() . '/' . $countrySlug . '/' . $resortSlug . '/hotels'); ?>">
                <div class="filter-block hotels-filter-category">
                    <label for="" class="hotel-category-label">
                        Категория отеля
                    </label>
                    <div class="filter-values">
                    <?php for ( $index = 5; $index > 0; $index-- ): ?>
                        <label>
                            <input
                                type="checkbox"
                                id="hotel-category-<?php echo $index; ?>"
                                name="hotel_category[]"
                                value="<?php echo $index; ?>"
                                <?php echo(in_array( $index, $hotelCategoriesChecked ) ? 'checked' : ''); ?>><?php echo $index . "*"; ?></label>
                    <?php endfor; ?>
                    </div>
                </div>
                <div class="filter-block hotels-filter-rate">
                    <label for="" class="hotel-rate-label">
                        Оценка<br>
                        <i>по отзывам</i>
                    </label>
                    <div class="filter-values">
                        <div id="hotel-rate-slider">
                            <input type="text" name="hotel_rate_from" id="hotel-rate-from"
                                    style="display: none;"
                                    value="<?php echo $hotelRateFrom; ?>" readonly>
                        </div>
                        <div class="hotel-slider-legenda">
                            <span>4</span>
                            <span>5</span>
                            <span>6</span>
                            <span>7</span>
                            <span>8</span>
                            <span>9</span>
                            <span>10</span>
                        </div>
                    </div>
                </div>
                <input type="submit" value="Подобрать отель" name="filter_hotels">
            </form>
        </div>

        <article>
            <header class="hotels-page-header">
                <h1 class="hotels-page-title"><?php echo $pageTitle; ?></h1>
                <div class="hotels-page-subtitle">Согласно рейтинга Турправда</div>
            </header>
            <?php include('partials/short-hotels-list.php'); ?>
        </article>

        <!-- Pagination -->
        <div class="pagination">
            <?php
                $pagination = paginate_links([
                    'total' => $totalHotelPages,
                    'current' => $requestedHotelPage,
                    'prev_text' => 'Назад',
                    'next_text' => 'Вперед',
                    'type' => 'array',
                    'add_args' => [
                        'rate' => $hotelRateFrom,
                        'category' => urlencode(implode(',', $hotelCategoriesChecked))
                    ]
                ]);
            ?>
            <nav>
                <ul class="pagination">
                    <?php if($pagination): foreach ($pagination as $link): ?>
                        <li>
                            <?php echo $link; ?>
                        </li>
                    <?php endforeach; endif; ?>
                </ul>
            </nav>
        </div>
    </main>
</div>
<?php get_footer(); ?>
