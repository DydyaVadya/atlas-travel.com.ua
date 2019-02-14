<?php
/**
 * Extends basic functionality for better TM Mega Menu compatibility
 *
 * @package Tourizto
 */

/**
 * Check if Mega Menu plugin is activated.
 *
 * @return bool
 */
function tourizto_is_mega_menu_active() {
	return class_exists( 'tm_mega_menu' );
}

add_filter( 'tourizto_theme_script_variables', 'tourizto_pass_mega_menu_vars' );

/**
 * Pass Mega Menu variables.
 *
 * @param  array  $vars Variables array.
 * @return array
 */
function tourizto_pass_mega_menu_vars( $vars = array() ) {

	if ( ! tourizto_is_mega_menu_active() ) {
		return $vars;
	}

	$vars['megaMenu'] = array(
		'isActive' => true,
		'location' => get_option( 'tm-mega-menu-location' ),
	);

	return $vars;
}
