<?php
/**
 * Template part for style-3 header layout.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tourizto
 */

$vertical_menu_slide             = ( ! is_rtl() ) ? 'right' : 'left';
$header_contact_block_visibility = get_theme_mod( 'header_contact_block_visibility', tourizto_theme()->customizer->get_default( 'header_contact_block_visibility' ) );
$header_btn_visibility           = get_theme_mod( 'header_btn_visibility', tourizto_theme()->customizer->get_default( 'header_btn_visibility' ) );
$search_visible                  = get_theme_mod( 'header_search', tourizto_theme()->customizer->get_default( 'header_search' ) );

?>
<div class="header-container_wrap container">
	<?php tourizto_vertical_main_menu( $vertical_menu_slide ); ?>

	<?php if ( $header_contact_block_visibility || $header_btn_visibility ) : ?>
		<div class="header-row__flex header-components__contact-button header-components__grid-elements"><?php
			tourizto_contact_block( 'header' );
			tourizto_header_btn();
		?></div>

	<?php endif; ?>

	<div class="header-container__flex-wrap">

		<div class="header-container__flex">

			<div class="site-branding big-logo">

				<?php tourizto_header_logo() ?>

				<?php tourizto_site_description(); ?>

            <!--меню в шапке-->
			</div>
            <div class="heder_menu_vider">
            <?php wp_nav_menu('menu=Header_new'); ?>
            </div>
            <!--меню в шапке-->

            <div class="header-components header-components__search-cart">
                <!--вывод виджета телефон шапка-->
                <div class="top_phone">
                    <?php dynamic_sidebar( 'top-area' ); ?>
                </div>
                <!--вывод виджета телефон шапка-->

                 <!--                кнопка отправить заявку-->
                <div class="head_button_wrap">
                    <a href="#contact_form_pop" class="fancybox-inline head_button">Отправить заявку</a>
                </div>

                <div style="display:none" class="fancybox-hidden">
                    <div id="contact_form_pop">
                        <?php echo do_shortcode('[contact-form-7 id="5923" title="lead-form-header"]'); ?>
                    </div>
                </div>
                <!--                кнопка отправить заявку-->

                <?php
				tourizto_header_search_toggle();
				tourizto_vertical_menu_toggle( 'main-menu' );
			?>

            </div>
		</div>

		<?php tourizto_header_search( '<div class="header-search">%s<span class="search-form__close"></span>777</div>' ); ?>

    </div>

</div>
