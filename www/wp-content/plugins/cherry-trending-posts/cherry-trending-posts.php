<?php
/**
 * Plugin Name: Cherry Trending Posts
 * Plugin URI:  https://github.com/CherryFramework/cherry-trending-posts
 * Description: Adds rating and views count for posts and custom post types.
 * Version:     1.0.1
 * Author:      Zemez
 * Author URI:  https://zemez.io/wordpress/
 * Text Domain: cherry-trending-posts
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 *
 * @package  Trending Posts
 * @category Core
 * @author   Template Monster
 * @license  GPL-3.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// If class 'Cherry_Trending_Posts' not exists.
if ( ! class_exists( 'Cherry_Trending_Posts' ) ) {

	/**
	 * Sets up and initializes the `Cherry Trending Posts` plugin.
	 *
	 * @since 1.0.0
	 */
	class Cherry_Trending_Posts {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $core = null;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Define plugin constants.
			add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );

			// Internationalize the text strings used.
			add_action( 'plugins_loaded', array( $this, 'lang' ), 2 );

			// Set up a Cherry core.
			add_action( 'after_setup_theme', require( trailingslashit( dirname( __FILE__ ) ) . 'cherry-framework/setup.php' ), 0 );
			add_action( 'after_setup_theme', array( $this, 'get_core' ), 1 );
			add_action( 'after_setup_theme', array( 'Cherry_Core', 'load_all_modules' ), 2 );

			// Load the plugin files.
			add_action( 'after_setup_theme', array( $this, 'includes' ), 10 );

			// Load the admin files.
			add_action( 'after_setup_theme', array( $this, 'admin' ), 10 );

			// Load public-facing stylesheet and JavaScript.
			add_action( 'wp_enqueue_scripts', array( $this, 'public_assets' ), 9 );

			// Enqueue public-facing JavaScript only on specific action.
			add_action( 'cherry_trend_posts_assets', array( $this, 'enqueue_assets' ) );
		}

		/**
		 * Loads the core functions. These files are needed before loading anything else in the
		 * themes or plugins because they have required functions for use.
		 *
		 * @since 1.0.0
		 */
		public function get_core() {
			global $chery_core_version;

			if ( null !== $this->core ) {
				return $this->core;
			}

			if ( 0 < sizeof( $chery_core_version ) ) {
				$core_paths = array_values( $chery_core_version );

				require_once( $core_paths[0] );
			} else {
				die( 'Class Cherry_Core not found' );
			}

			$this->core = new Cherry_Core( array(
				'base_dir' => CHERRY_TREND_POSTS_DIR . 'cherry-framework',
				'base_url' => CHERRY_TREND_POSTS_URI . 'cherry-framework',
				'modules'  => array(
					'cherry-utility' => array(
						'autoload' => true,
					),
					'cherry-js-core' => array(
						'autoload' => false,
					),
					'cherry-ui-elements' => array(
						'autoload' => false,
					),
					'cherry-widget-factory' => array(
						'autoload' => true,
					),
				),
			) );

			return $this->core;
		}

		/**
		 * Defines constants for the plugin.
		 *
		 * @since 1.0.0
		 */
		public function constants() {

			/**
			 * Set the version number of the plugin.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_TREND_POSTS_VERSION', '1.0.1' );

			/**
			 * Set constant path to the plugin directory.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_TREND_POSTS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

			/**
			 * Set constant path to the plugin URI.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_TREND_POSTS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

			/**
			 * Set the cache time.
			 *
			 * @since 1.0.0
			 */
			if ( ! defined( 'CHERRY_TREND_POSTS_CACHE_TIME' ) ) {
				define( 'CHERRY_TREND_POSTS_CACHE_TIME', 30 * DAY_IN_SECONDS );
			}
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 */
		public function lang() {
			load_plugin_textdomain( 'cherry-trending-posts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Loads files from the '/inc' folder.
		 *
		 * @since 1.0.0
		 */
		public function includes() {
			require_once( CHERRY_TREND_POSTS_DIR . 'inc/class-trending-posts-data.php' );
			require_once( CHERRY_TREND_POSTS_DIR . 'inc/class-trending-posts-widget.php' );
			require_once( CHERRY_TREND_POSTS_DIR . 'inc/class-trending-posts-callback-views.php' );
			require_once( CHERRY_TREND_POSTS_DIR . 'inc/class-trending-posts-callback-rating.php' );
		}

		/**
		 * Loads admin files.
		 *
		 * @since 1.0.0
		 */
		public function admin() {

			if ( ! is_admin() ) {
				return;
			}

			global $pagenow;

			// Init `cherry-js-core` module only on `Widgets` page.
			if ( null !== $pagenow && 'widgets.php' === $pagenow ) {
				$this->get_core()->init_module( 'cherry-js-core' );
			}
		}

		/**
		 * Register and enqueue public-facing stylesheet.
		 *
		 * @since 1.0.0
		 */
		public function public_assets() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_style(
				'font-awesome',
				CHERRY_TREND_POSTS_URI . 'assets/css/font-awesome.min.css', array(), '4.7.0'
			);

			wp_enqueue_style(
				'cherry-trending-posts',
				CHERRY_TREND_POSTS_URI . 'assets/css/style.css', array( 'font-awesome' ), CHERRY_TREND_POSTS_VERSION
			);

			wp_register_script(
				'cherry-trending-posts',
				CHERRY_TREND_POSTS_URI . "assets/js/script{$suffix}.js", array( 'jquery' ), CHERRY_TREND_POSTS_VERSION, true
			);
		}

		/**
		 * Enqueue required assets only on call.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_assets() {

			/**
			 * Check before printing JavaScript variable via `wp_localize_script()`.
			 */
			if ( did_action( 'cherry_trend_posts_assets' ) > 1 ) {
				return;
			}

			wp_enqueue_script( 'cherry-trending-posts' );
			wp_localize_script(
				'cherry-trending-posts',
				'cherryTrendPosts',
				array(
					'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
					'nonce'   => wp_create_nonce( 'cherry_trend_posts' ),
					'cache'   => apply_filters( 'cherry_trend_posts_cache_fix', 0 ),
				)
			);
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

Cherry_Trending_Posts::get_instance();
