<?php
/**
 * The template for displaying the style-2 footer layout.
 *
 * @package Tourizto
 */
?>

<div <?php tourizto_footer_container_class(); ?>>
	<div class="site-info container"><?php
		tourizto_footer_logo();
		tourizto_footer_menu();
		tourizto_contact_block( 'footer' );
		tourizto_social_list( 'footer' );
		tourizto_footer_copyright();
	?></div><!-- .site-info -->
</div><!-- .container -->
