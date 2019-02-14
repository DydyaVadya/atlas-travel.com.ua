<?php
/**
 * Include public script and CSS, additional functions and definitions
 *
 * @package   tm_mega_menu
 * @author    TemplateMonster
 * @license   GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

if ( ! class_exists( 'tm_mega_menu_public_manager' ) ) {

	/**
	 * Menu items manager
	 */
	class tm_mega_menu_public_manager {

		public static $mobile_button;
		public static $processed = array();

		/**
		 * Class instance holder
		 * @var object
		 */
		private static $instance;

		/**
		 * include necessary files. Run actions
		 */
		public function __construct() {

			self::$mobile_button = ( string ) apply_filters( 'tm_mega_menu_mobile_button', false );

			add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );
			add_filter( 'wp_nav_menu_args', array( $this, 'add_walker_to_nav_menu' ), 999 );
			add_filter( 'wp_nav_menu_objects', array( $this, 'add_menu_objects' ), 10, 2 );
			add_filter( 'wp_nav_menu', array( $this, 'add_menu_mobile_label' ), 10, 2 );

			require_once ( 'class-tm-mega-menu-walker.php' );
			require_once ( TM_MEGA_MENU_DIR . '/core/includes/class-tm-mega-menu-widget-manager.php' );

		}

		/**
		 * Include assets
		 *
		 * @since  1.0.0
		 */
		public function assets() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '4.4.0' );

			wp_enqueue_style( 'tm-mega-menu', TM_MEGA_MENU_URI . 'public/assets/css/style.css', array(), TM_MEGA_MENU_VERSION );

			$duration = get_option( 'tm-mega-menu-duration', '300' );
			$parent   = get_option( 'tm-mega-menu-parent-container', '.tm-mega-menu' );
			$css      = '.sub-menu {transition-duration: ' . $duration . 'ms;}';

			wp_add_inline_style( 'tm-mega-menu', $css );

			wp_enqueue_script( 'tm-mega-menu', TM_MEGA_MENU_URI . 'public/assets/js/script' . $suffix . '.js',
				array( 'jquery' ), TM_MEGA_MENU_VERSION, true );
		}

		/**
		 * Apply mega menu walker for main theme menu
		 *
		 * @since  1.0.0
		 *
		 * @param  array  $args  default nav menu args
		 * @return array         modified args with mega menu walker
		 */
		public function add_walker_to_nav_menu( $args ) {

			/*if( !isset( $args['theme_location'] ) || ( wp_cache_get('tm_mega_menu_location') && wp_cache_get( 'tm_mega_menu_location' ) !== $args['theme_location'] ) ) {
				return $args;
			}*/

			$mega_menu_location = get_option( 'tm-mega-menu-location', array( 'main' ) );
			$mega_menu_location = ( array ) $mega_menu_location;

			if ( ! in_array( $args['theme_location'], $mega_menu_location ) ) {
				return $args;
			}

			if ( ! in_array( $mega_menu_location, self::$processed ) ) {
				self::$processed[] = $mega_menu_location;
			} else {
				return $args;
			}

			if ( is_array( $args ) && isset( $args['container_class'] ) && 'handheld-navigation' === $args['container_class'] ) {
				return $args;
			}

			//wp_cache_set( 'tm_mega_menu_location', $args['theme_location'] );

			$direction = 'horizontal';

			global $tm_mega_menu_total_columns;

			$wrapper_atts = array(
				'id'                   => '%1$s',
				'class'                => '%2$s tm-mega-no-js tm-mega-menu mega-menu-direction-' . $direction . ' total-columns-' . $tm_mega_menu_total_columns,
				'data-effect'          => get_option( 'tm-mega-menu-effect', 'slide-top' ),
				'data-direction'       => $direction,
				'data-parent-selector' => get_option( 'tm-mega-menu-parent-container', '.tm-mega-menu' ),
				'data-mobile-trigger'  => get_option( 'tm-mega-menu-mobile-trigger', 768 ),
				'data-duration'        => get_option( 'tm-mega-menu-duration', 300 ),
				'data-mobile-button'   => self::$mobile_button
			);

			/**
			 * Filter megamenu wrapper attributes
			 *
			 * @since  1.0.0
			 *
			 * @var    array  filtered attributes
			 * @param  array  $wrapper_atts default attributes
			 * @param  array  $args         default nav menu arguments
			 */
			$atts = tm_mega_menu_parse_atts( apply_filters( 'tm_mega_menu_wrapper_atts', $wrapper_atts, $args ) );

			$new_args = array(
				'container'  => 'ul',
				'menu_class' => 'tm-mega-menu menu',
				'items_wrap' => '<ul' . $atts . '>%3$s</ul>',
				'walker'     => new tm_mega_menu_walker()
			);

			$args = wp_parse_args( $new_args, $args );

			return $args;
		}

		/**
		 * Add mobile menu label before menu content
		 *
		 * @since  1.0.0
		 *
		 * @param  string  $menu  menu content
		 * @param  array   $args  menu args
		 * @return string         menu content with mobile label
		 */
		function add_menu_mobile_label( $menu, $args ) {

			// make sure we're working with a Mega Menu
			if ( !is_a( $args->walker, 'tm_mega_menu_walker' ) ) {
				return $menu;
			}

			$custom_menu_class = null;
			$checked           = null;
			$before_menu_label = null;
			$after_menu_label  = null;

			$is_custom_menu = get_theme_support( 'tm-custom-mobile-menu' );

			if ( $is_custom_menu ) {
				$custom_menu_class = 'custom-menu';
				$checked           = 'checked';
			}

			$before_menu_label = sprintf(
				'<input class="tm-mega-menu-mobile-trigger-box %2$s" id="trigger-%1$s" type="checkbox" %3$s>',
				$args->menu_id,
				$custom_menu_class,
				$checked
			);

			if ( ! $is_custom_menu ) {
				if( ! self::$mobile_button ) {
					$before_menu_label .= '<label class="tm-mega-menu-mobile-trigger" for="trigger-' . $args->menu_id . '"></label>';
				}
				$after_menu_label  = '<label class="tm-mega-menu-mobile-close" for="trigger-' . $args->menu_id . '"></label>';
			}

			return $before_menu_label . $menu . $after_menu_label;
		}

		/**
		 * Append the widget objects to the menu array before the
		 * menu is processed by the walker.
		 *
		 * @since 1.0.0
		 *
		 * @param  array  $items  all menu item objects
		 * @param  object $args
		 * @return array          menu objects including widgets
		 */
		public function add_menu_objects( $items, $args ) {

			// make sure we're working with a Mega Menu
			if ( !is_a( $args->walker, 'tm_mega_menu_walker' ) ) {
				return $items;
			}

			$widget_manager = new tm_mega_menu_widget_manager();
			$duration       = get_option( 'tm-mega-menu-duration', 300 );

			foreach ( $items as $item ) {

				if ( ( false !== $key = array_search( 'menu-item-has-children', $item->classes ) ) ) {
					unset( $item->classes[ $key ] );
				}

				if ( class_exists( 'SitePress' ) ) {
					$item_id = apply_filters( 'wpml_element_trid', false, $item->ID, 'post_nav_menu_item' );
				} else {
					$item_id = $item->ID;
				}

				$saved_settings = array_filter( (array) get_post_meta( $item_id, '_tm_mega_menu', true ) );

				$item->megamenu_settings = wp_parse_args( $saved_settings, array(
					'type'     => '',
					'duration' => $duration
				) );

				// only look for widgets on top level items
				if ( 0 != $item->menu_item_parent || 'megamenu' != $item->megamenu_settings[ 'type' ] ) {
					continue;
				}

				$panel_widgets = $widget_manager->get_widgets_for_menu_id( $item_id );

				if ( empty( $panel_widgets ) ) {
					continue;
				}

				$cols = 0;

				$item_appended = $this->append_widgets( $panel_widgets, $item, $widget_manager );

				$items = array_merge( $items, $item_appended );
			}

			return $items;
		}

		/**
		 * Append saved widgets HTML to menu object
		 *
		 * @since  1.0.0
		 *
		 * @param  array  $widgets item widgets
		 * @return array           item data
		 */
		function append_widgets( $widgets = array(), $item, $widget_manager ) {

			if ( !is_array( $widgets ) ) {
				return false;
			}

			$result = array();

			foreach ( $widgets as $widget ) {

				$cols = $widget[ 'mega_columns' ];

				$menu_item = array(
					'type'             => 'widget',
					'title'            => '',
					'content'          => $widget_manager->show_widget( $widget[ 'widget_id' ] ),
					'menu_item_parent' => $item->ID,
					'db_id'            => 0, // This menu item does not have any childen
					'ID'               => $widget[ 'widget_id' ],
					'classes'          => array(
						'menu-item',
						'menu-item-type-widget',
						'menu-columns-' . $cols
					)
				);

				$result[] = (object) $menu_item;
			}

			return $result;

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

add_action( 'init', array( 'tm_mega_menu_public_manager', 'get_instance' ) );
