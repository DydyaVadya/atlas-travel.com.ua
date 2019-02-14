<?php
/**
 * Template part for top panel in header.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tourizto
 */

// Don't show top panel if all elements are disabled.
if ( ! tourizto_is_top_panel_visible() ) {
	return;
}
?>

<div <?php echo tourizto_get_html_attr_class( array( 'top-panel' ), 'top_panel_bg' ); ?>>
	<div class="container">
		<div class="top-panel__container">
			<?php tourizto_top_message( '<div class="top-panel__message">%s</div>' ); ?>
			<?php tourizto_contact_block( 'header_top_panel' ); ?>

			<div class="top-panel__wrap-items">
				<div class="top-panel__menus">
					<?php tourizto_top_menu(); ?>
					<?php tourizto_login_link(); ?>
				</div>
			</div>
		</div>
	</div>
</div><!-- .top-panel -->
