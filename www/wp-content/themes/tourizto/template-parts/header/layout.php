<?php
/**
 * Template part for default header layout.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tourizto
 */

$header_contact_block_visibility = get_theme_mod( 'header_contact_block_visibility', tourizto_theme()->customizer->get_default( 'header_contact_block_visibility' ) );
$header_btn_visibility           = get_theme_mod( 'header_btn_visibility', tourizto_theme()->customizer->get_default( 'header_btn_visibility' ) );
$search_visible                  = get_theme_mod( 'header_search', tourizto_theme()->customizer->get_default( 'header_search' ) );
?>
<div class="header-container_wrap container">

	<?php if ( $header_contact_block_visibility || $header_btn_visibility ) : ?>
		<div class="header-row__flex header-components__contact-button header-components__grid-elements"><?php
			tourizto_contact_block( 'header' );
			tourizto_header_btn();
		?></div>
	<?php endif; ?>

	<div class="header-container__flex-wrap">
		<div class="header-container__flex">
			<div class="site-branding">
				<?php tourizto_header_logo() ?>
				<?php tourizto_site_description(); ?>
			</div>

			<div class="header-nav-wrapper">
				<?php tourizto_main_menu(); ?>
				<?php if ( $search_visible ) : ?>
					<div class="header-components header-components__search-cart">
						<?php tourizto_header_search_toggle(); ?>
						<?php tourizto_social_list( 'header' ); ?>
					</div>
				<?php endif; ?>

			</div>
		</div>

		<?php tourizto_header_search( '<div class="header-search">%s<span class="search-form__close"></span></div>' ); ?>
<!--		--><?php //tourizto_social_list( 'header' ); ?>
	</div>
</div>
