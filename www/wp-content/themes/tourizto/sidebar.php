<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Tourizto
 */
$sidebar_position = get_theme_mod( 'sidebar_position' );
$sidebar_single_product = get_theme_mod( 'enable_single_product_sidebar' );

if ( is_active_sidebar( 'sidebar' ) && 'fullwidth' !== $sidebar_position && ! tourizto_is_product_page() ) {
	do_action( 'tourizto_render_widget_area', 'sidebar' );
}

if ( is_active_sidebar( 'shop-sidebar' ) && 'fullwidth' !== $sidebar_position && tourizto_is_product_page() ) {
	if ( is_product() && $sidebar_single_product ) {
		do_action( 'tourizto_render_widget_area', 'shop-sidebar' );
	} else if ( is_shop() || is_product_taxonomy()  ) {
		do_action( 'tourizto_render_widget_area', 'shop-sidebar' );
	}
}