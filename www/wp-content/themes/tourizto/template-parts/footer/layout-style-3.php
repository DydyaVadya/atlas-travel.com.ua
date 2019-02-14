<?php
/**
 * The template for displaying the style-3 footer layout.
 *
 * @package Tourizto
 */
?>

<div <?php tourizto_footer_container_class(); ?>>
	<div class="site-info container-wide">
		<div class="site-info-wrap">
			<div class="site-info-block"><?php
				tourizto_footer_logo();
				tourizto_footer_copyright();
			?></div>
			<?php tourizto_footer_menu(); ?>
			<div class="site-info-block"><?php
				tourizto_contact_block( 'footer' );
				tourizto_social_list( 'footer' );
			?></div>
		</div>
	</div><!-- .site-info -->
</div><!-- .container -->
