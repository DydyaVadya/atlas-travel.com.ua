<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Tourizto
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
    <script type="text/javascript" src="<?php echo get_bloginfo('template_url') . '/assets/js/vider.js';?>"></script>
    <link rel="stylesheet" href="<?php echo get_bloginfo('template_url') . '/assets/css/vider.css';?>">



</head>

<body <?php body_class(); ?>>
<?php tourizto_get_page_preloader(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'tourizto' ); ?></a>
	<header id="masthead" <?php tourizto_header_class(); ?> role="banner">
		<?php tourizto_ads_header() ?>
		<?php tourizto_get_template_part( 'template-parts/header/mobile-panel' ); ?>
		<?php tourizto_get_template_part( 'template-parts/header/top-panel', get_theme_mod( 'header_layout_type', tourizto_theme()->customizer->get_default( 'header_layout_type' ) ) ); ?>

		<div <?php tourizto_header_container_class(); ?>>
			<?php tourizto_get_template_part( 'template-parts/header/layout', get_theme_mod( 'header_layout_type', tourizto_theme()->customizer->get_default( 'header_layout_type' ) ) ); ?>
		</div><!-- .header-container -->
	</header><!-- #masthead -->

	<div id="content" <?php tourizto_content_class(); ?>>
