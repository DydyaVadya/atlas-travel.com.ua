<?php
/**
 * Add tm mega menu options
 *
 * @package   tm_mega_menu
 * @author    TemplateMonster
 * @license   GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

if ( ! class_exists( 'tm_mega_menu_options' ) ) {

	/**
	 * tm mega menu options management class
	 *
	 * @since  1.0.0
	 */
	class tm_mega_menu_options {

		/**
		 * build plugin instance
		 */
		public function __construct() {

			add_filter( 'tm_mega_menu_optimization_options_list', array( $this, 'add_optimization_options') );
		}

		/**
		 * Add mega menu options
		 *
		 * @since  1.0.0
		 *
		 * @return array             filtered sections array
		 */
		public function megamenu_options() {

			$menus         = get_registered_nav_menus();
			$options_menus = array(
				'0' => __( 'Select main theme menu', 'tm-mega-menu' )
			);

			$options_menus = array_merge( $options_menus, $menus );

			$menu_options  = array(
				'tm-megamenu-main-settings'		=> array(
					'type'						=> 'section',
					'title'						=> __( 'TM Megamenu settings', 'tm-mega-menu' )
				),
				'tm-mega-menu-location'			=> array(
					'type'						=> 'field',
					'field'						=> 'select',
					'title'						=> __( 'Main theme menu location', 'tm-mega-menu' ),
					'callback_args'				=> array(
						'label'					=> __( 'Select the menu location for your main menu', 'tm-mega-menu' ),
						'value'					=> array( 'primary' ),
						'options'				=> $menus,
					),
					'section'					=> 'tm-megamenu-main-settings'
				),
				'tm-mega-menu-mobile-trigger'	=> array(
					'type'						=> 'field',
					'field'						=> 'slider',
					'title'						=> __( 'Mobile starts from', 'tm-mega-menu' ),
					'callback_args'				=> array(
						'label'					=> __( 'Select the window dimensions for mobile menu layout switching.', 'tm-mega-menu' ),
						'max'					=> 1200,
						'min'					=> 480,
						'value'					=> 768,
					),
					'section'					=> 'tm-megamenu-main-settings'
				),
				'tm-mega-menu-effect'			=> array(
					'type'						=> 'field',
					'field'						=> 'select',
					'title'						=> __( 'Animation effect', 'tm-mega-menu' ),
					'callback_args'				=> array(
						'value'					=> 'slide-top',
						'class'					=> 'width-full',
						'label'					=> __( 'Animation effects for the dropdown menu', 'tm-mega-menu' ),
						'options'				=> array(
							'fade-in'			=> __( 'Fade In', 'tm-mega-menu' ),
							'slide-top'			=> __( 'Slide from top', 'tm-mega-menu' ),
						)
					),
					'section'					=> 'tm-megamenu-main-settings'
				),
				'tm-mega-menu-duration'			=> array(
					'type'						=> 'field',
					'field'						=> 'slider',
					'title'						=> __( 'Transition duration', 'tm-mega-menu' ),
					'callback_args'				=> array(
						'label'					=> __( 'Select a transition duration for the dropdown menu.', 'tm-mega-menu' ),
						'max'					=> 1000,
						'min'					=> 200,
						'value'					=> 300,
					),
					'section'					=> 'tm-megamenu-main-settings'
				),
				'tm-mega-menu-parent-container'	=> array(
					'type'						=> 'field',
					'field'						=> 'text',
					'title'						=> __( 'Menu parent container CSS selector', 'tm-mega-menu' ),
					'callback_args'				=> array(
						'label'					=> __( 'Enter CSS selector name for mega menu parent container (if needed)', 'tm-mega-menu' ),
						'value'					=> apply_filters( 'tm_mega_menu_default_parent', '.tm-mega-menu' )
					),
					'section'					=> 'tm-megamenu-main-settings'
				)
			);

			return apply_filters( 'tm_mega_menu_options', $menu_options );
		}

		/**
		 * Add mega menu caching to optimization options
		 * @param array $options optimization options array
		 */
		function add_optimization_options( $options ) {

			$options['mega-menu-cache'] = array(
				'type'			=> 'switcher',
				'title'			=> __( 'Mega menu caching enabled', 'tm-mega-menu' ),
				'label'			=> __( 'Enable / Disable', 'tm-mega-menu' ),
				'decsription'	=> '',
				'hint'			=>  array(
					'type'		=> 'text',
					'content'	=> __( 'Enable caching for mega menu items', 'tm-mega-menu' )
				),
				'value'			=> 'false',
			);

			return $options;
		}

	}

	new tm_mega_menu_options();

}