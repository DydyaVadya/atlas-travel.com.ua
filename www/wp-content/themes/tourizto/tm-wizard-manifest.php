<?php
/**
 * TM-Wizard configuration.
 *
 * @var array
 *
 * @package Tourizto
 */

$plugins = array(
	'cherry-data-importer' => array(
		'name'   => esc_html__( 'Cherry Data Importer', 'tourizto' ),
		'source' => 'remote', // 'local', 'remote', 'wordpress' (default).
		'path'   => 'https://github.com/CherryFramework/cherry-data-importer/archive/master.zip',
		'access' => 'base',
	),
	'cherry-projects' => array(
		'name'   => esc_html__( 'Cherry Projects', 'tourizto' ),
		'access' => 'skins',
	),
	'cherry-popups' => array(
		'name'   => esc_html__( 'Cherry PopUps', 'tourizto' ),
		'access' => 'base',
	),
	'cherry-team-members' => array(
		'name'   => esc_html__( 'Cherry Team Members', 'tourizto' ),
		'access' => 'skins',
	),
	'cherry-testi' => array(
	'name'   => esc_html__( 'Cherry Testimonials', 'tourizto' ),
		'source' => 'local',
		'path'   => TOURIZTO_THEME_DIR . '/assets/includes/plugins/cherry-testi.zip',
		'access' => 'skins',
	),
	'cherry-services-list' => array(
		'name'   => esc_html__( 'Cherry Services List', 'tourizto' ),
		'access' => 'skins',
	),
	'cherry-sidebars' => array(
		'name'   => esc_html__( 'Cherry Sidebars', 'tourizto' ),
		'access' => 'base',
	),
	'cherry-socialize' => array(
		'name'   => esc_html__( 'Cherry Socialize', 'tourizto' ),
		'access' => 'base',
	),
	'cherry-trending-posts' => array(
		'name'   => esc_html__( 'Cherry Trending Posts', 'tourizto' ),
		'access' => 'skins',
	),
	'booked' => array(
		'name'   => esc_html__( 'Booked Appointments', 'tourizto' ),
		'source' => 'local',
		'path'   => TOURIZTO_THEME_DIR . '/assets/includes/plugins/booked.zip',
		'access' => 'skins',
	),
	'jet-elements' => array(
		'name'   => esc_html__( 'Jet Elements addon For Elementor', 'tourizto' ),
		'source' => 'local',
		'path'   => TOURIZTO_THEME_DIR . '/assets/includes/plugins/jet-elements.zip',
		'access' => 'base',
	),
	'elementor' => array(
		'name'   => esc_html__( 'Elementor Page Builder', 'tourizto' ),
		'access' => 'base',
	),
	'tm-mega-menu' => array(
		'name'   => esc_html__( 'TM Mega Menu', 'tourizto' ),
		'source' => 'remote',
		'path'   => 'http://cloud.cherryframework.com/downloads/free-plugins/tm-mega-menu.zip',
		'access' => 'skins',
	),
	'tm-photo-gallery' => array(
		'name'   => esc_html__( 'TM Photo Gallery', 'tourizto' ),
		'access' => 'base',
	),
	'tm-timeline' => array(
		'name'   => esc_html__( 'TM Timeline', 'tourizto' ),
		'access' => 'skins',
	),
	'contact-form-7' => array(
		'name'   => esc_html__( 'Contact Form 7', 'tourizto' ),
		'access' => 'skins',
	),
	'simple-file-downloader' => array(
		'name'   => esc_html__( 'Simple File Downloader', 'tourizto' ),
		'access' => 'skins',
	),
	'shortcode-widget' => array(
		'name'   => esc_html__( 'Shortcode Widget', 'tourizto' ),
		'access' => 'skins',
	),
	'wordpress-social-login' => array(
		'name'   => esc_html__( 'WordPress Social Login', 'tourizto' ),
		'access' => 'skins',
	),
	'cherry-search' => array(
		'name'   => esc_html__( 'Cherry Search', 'tourizto' ),
		'access' => 'skins',
	),
);

/**
 * Skins configuration.
 *
 * @var array
 */
$skins = array(
	'base' => array(
		'cherry-data-importer',
		'cherry-popups',
		'cherry-sidebars',
		'cherry-socialize',
		'jet-elements',
		'elementor',
		'tm-photo-gallery',
	),
	'advanced' => array(
		'default' => array(
			'full'  => array(
				'booked',
				'cherry-projects',
				'cherry-services-list',
				'cherry-team-members',
				'cherry-testi',
				'cherry-trending-posts',
				'tm-mega-menu',
				'tm-timeline',
				'contact-form-7',
				'simple-file-downloader',
				'shortcode-widget',
				'wordpress-social-login',
				'cherry-search',
			),
			'lite'  => false,
			'demo'  => 'http://ld-wp.template-help.com/wordpress_64790',
			'thumb' => get_template_directory_uri() . '/assets/demo-content/default/default-thumb.png',
			'name'  => esc_html__( 'Tourizto', 'tourizto' ),
		),
	),
);

$texts = array(
	'theme-name' => esc_html__( 'Tourizto', 'tourizto' ),
);
