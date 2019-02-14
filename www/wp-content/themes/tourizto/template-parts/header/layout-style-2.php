<?php
/**
 * Template part for style-2 header layout.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tourizto
 */

$search_visible        = get_theme_mod( 'header_search', tourizto_theme()->customizer->get_default( 'header_search' ) );
?>
<div class="header-container_wrap container">

	<div class="header-row__flex">
		<div class="site-branding">
			<?php tourizto_header_logo() ?>
			<?php tourizto_site_description(); ?>
		</div>

		<div class="header-row__flex header-components__contact-button"><?php
			tourizto_contact_block( 'header' );
			tourizto_header_btn();
		?></div>
	</div>

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
