<?php
/**
 * Menus configuration.
 *
 * @package Tourizto
 */

add_action( 'after_setup_theme', 'tourizto_register_menus', 5 );
/**
 * Register menus.
 */
function tourizto_register_menus() {

	register_nav_menus( array(
		'top'          => esc_html__( 'Top', 'tourizto' ),
		'main'         => esc_html__( 'Main', 'tourizto' ),
		'main_landing' => esc_html__( 'Landing Main', 'tourizto' ),
		'footer'       => esc_html__( 'Footer', 'tourizto' ),
		'social'       => esc_html__( 'Social', 'tourizto' ),
	) );
}
