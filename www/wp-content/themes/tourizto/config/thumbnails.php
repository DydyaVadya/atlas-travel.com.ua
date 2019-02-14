<?php
/**
 * Thumbnails configuration.
 *
 * @package Tourizto
 */

add_action( 'after_setup_theme', 'tourizto_register_image_sizes', 5 );
/**
 * Register image sizes.
 */
function tourizto_register_image_sizes() {
	set_post_thumbnail_size( 360, 203, true );

	// Registers a new image sizes.
	add_image_size( 'tourizto-thumb-s', 150, 150, true );
	add_image_size( 'tourizto-thumb-m', 460, 460, true );
	add_image_size( 'tourizto-thumb-l', 660, 371, true );
	add_image_size( 'tourizto-thumb-l-2', 766, 203, true );
	add_image_size( 'tourizto-thumb-xl', 1160, 508, true );

	add_image_size( 'tourizto-thumb-masonry', 900, 9999, false );

	add_image_size( 'tourizto-slider-thumb', 150, 86, true );

	add_image_size( 'tourizto-thumb-78-78', 78, 78, true );
	add_image_size( 'tourizto-thumb-260-147', 260, 147, true );
	add_image_size( 'tourizto-thumb-260-195', 260, 195, true );
	add_image_size( 'tourizto-thumb-260-260', 260, 260, true );
	add_image_size( 'tourizto-thumb-360-270', 360, 270, true );
	add_image_size( 'tourizto-thumb-480-271', 480, 271, true );
	add_image_size( 'tourizto-thumb-480-360', 480, 360, true );
	add_image_size( 'tourizto-thumb-560-315', 560, 315, true );
	add_image_size( 'tourizto-thumb-660-495', 660, 495, true );
	add_image_size( 'tourizto-thumb-760-571', 760, 571, true );
	add_image_size( 'tourizto-custom-post-370-216', 370, 216, true );
	add_image_size( 'tourizto-custom-adv_carusel-770-435', 770, 435, true );
	add_image_size( 'tourizto-custom-banner-370-276', 370, 276, true );
	add_image_size( 'tourizto-custom-project-368-310', 368, 310, true );
	add_image_size( 'tourizto-custom-project-760-643', 760, 643, true );
}
