<?php
/**
 * Template part for mobile panel in header.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tourizto
 */
?>
<div class="mobile-panel invert">
	<div class="mobile-panel__inner">
		<?php tourizto_menu_toggle( 'main-menu' ); ?>
		<div class="header-components">
			<?php tourizto_header_search_toggle(); ?>
		</div>
	</div>
	<?php tourizto_header_search( '<div class="header-search">%s<span class="search-form__close"></span></div>' ); ?>
</div>
