<?php
/**
 * Template part for style-5 header layout.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tourizto
 */

$search_visible      = get_theme_mod( 'header_search', tourizto_theme()->customizer->get_default( 'header_search' ) );
$nav_panel_type      = get_theme_mod( 'header_nav_panel_type', tourizto_theme()->customizer->get_default( 'header_nav_panel_type' ) );
$nav_panel_position  = get_theme_mod( 'header_nav_panel_position', tourizto_theme()->customizer->get_default( 'header_nav_panel_position' ) );
$header_menu_style   = get_theme_mod( 'header_menu_style', tourizto_theme()->customizer->get_default( 'header_menu_style' ) );

$additional_classes = array(
	'header-nav-panel--' . sanitize_html_class( $nav_panel_type ),
	'header-nav-panel--' . sanitize_html_class( $nav_panel_position ),
	'header-menu--' . sanitize_html_class( $header_menu_style ),
);

?>
<div class="header-container_wrap container <?php echo join( ' ', $additional_classes ); ?>">
	<div class="row">
		<div class="col-xs-12 col-lg-3">
			<div class="site-branding">
				<?php tourizto_header_logo() ?>
				<?php tourizto_site_description(); ?>
			</div>
		</div>
		<div class="col-xs-12 col-lg-9 header-row__flex header-components__contact-button"><?php
			tourizto_contact_block( 'header' );
			tourizto_header_btn();
			?></div>
	</div>

	<div class="header-container__flex-wrap">
		<div class="header-nav-wrapper">
			<?php tourizto_main_menu(); ?>

			<?php if ( $search_visible ) : ?>
				<div class="header-components header-components__search-cart"><?php
					tourizto_header_search_toggle();
					?></div>
			<?php endif; ?>

			<?php tourizto_header_search( '<div class="header-search">%s<span class="search-form__close"></span></div>' ); ?>
		</div>
	</div>
</div>
