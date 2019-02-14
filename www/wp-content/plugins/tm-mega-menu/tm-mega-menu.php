<?php
/**
 * Plugin Name: TM Mega Menu
 * Description: A megamenu management plugin for WordPress.
 * Version:     1.1.4
 * Author:      TemplateMonster
 * Author URL:  http://www.templatemonster.com/wordpress-themes.php
 * Text Domain: tm-mega-menu
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

if ( !class_exists( 'tm_mega_menu' ) ) {
	/**
	 * Main plugin class
	 */
	final class tm_mega_menu {

		/**
		 * @var   string
		 * @since 1.0.0
		 */
		public $version = '1.1.4';

		/**
		 * @var   string
		 * @since 1.0.0
		 */
		public $slug = 'tm-mega-menu';

		/**
		 * Constructor
		 */
		public function __construct() {

			// Internationalize the text strings used.
			add_action( 'plugins_loaded', array( $this, 'lang' ), 2 );

			add_action( 'tm_mega_menu_after_widget_add', array( $this, 'clear_caches' ) );
			add_action( 'tm_mega_menu_after_widget_save', array( $this, 'clear_caches' ) );
			add_action( 'tm_mega_menu_after_widget_delete', array( $this, 'clear_caches' ) );

			// Set the constants needed by the plugin.
			$this->constants();

			$GLOBALS[ 'tm_mega_menu_total_columns' ] = apply_filters( 'tm_mega_menu_total_columns', 12 );

			$this->includes();

			// init menu caching class
			add_action( 'init', array( 'tm_mega_menu_cache', 'get_instance' ) );

			if ( is_admin() ) {
				$this->_admin();
			} else {
				$this->_public();
			}
		}

		// action function for above hook
		public function tm_megamenu_add_pages() {

			// Add a new top-level menu (ill-advised):
			global $m_megamenu_admin_page;
			$m_megamenu_admin_page = add_theme_page( 'TM Megamenu', 'TM Megamenu', 'administrator', 'tm-megamenu', array( $this, 'tm_megamenu_settings_page' ) );
		}

		public function tm_megamenu_settings_page() { ?>
			<form method="POST" action="options.php">
			<?php
				wp_nonce_field( 'update-options' );

				settings_fields( 'tm-megamenu-main-settings' );

				do_settings_sections( 'tm-megamenu' );

				submit_button();
			?>
			</form>
			<?php
		}

		public function tm_megamenu_settings_api_init() {

			$mega_menu_options = new tm_mega_menu_options;

			$mega_menu_setings = $mega_menu_options->megamenu_options();

			foreach ( $mega_menu_setings as $id => $setting ) {

				if ( empty( $setting[ 'type' ] ) ) {
					continue;
				}

				if ( 'section' === $setting[ 'type' ] ) {
					$this->add_section( $id, $setting );
				}

				if ( 'field' === $setting[ 'type' ] ) {
					if ( empty( $setting[ 'section' ] ) ) {
						continue;
					}
					$this->add_field( $id, $setting );
				}
			}
		}

		public function add_section( $id, $args ) {

			$title		= isset( $args[ 'title' ] ) ? esc_attr( $args[ 'title' ] ) : '';
			$callback	= isset( $args[ 'callback' ] ) ? esc_attr( $args[ 'callback' ] ) : '';
			$page		= 'tm-megamenu';

			add_settings_section( $id, $title, $callback, $page );
		}

		public function add_field( $id, $args ) {

			$title			= isset( $args[ 'title' ] ) ? esc_attr( $args[ 'title' ] ) : '';
			$field_type 	= isset( $args[ 'field' ] ) ? esc_attr( $args[ 'field' ] ) : 'text';
			$callback		= isset( $args[ 'callback' ] ) ? esc_attr( $args[ 'callback' ] ) : array( $this, 'megamenu_field_callback_' . $field_type );
			$callback		= is_callable( $callback ) ? $callback : array( $this, 'megamenu_field_callback_text' );
			$page			= 'tm-megamenu';
			$section		= $args[ 'section' ];
			$callback_args	= array(
				'id' => $id
			);
			$callback_args	= isset( $args[ 'callback_args' ] ) ? array_merge( $callback_args, $args[ 'callback_args' ] ) : $callback_args;

			add_settings_field( $id, $title, $callback, $page, $section, $callback_args );

			register_setting( $section, $id );
		}

		function megamenu_field_callback_text( $args ) {

			$value = isset( $args[ 'value' ] ) ? esc_attr( $args[ 'value' ] ) : '';

			$value = null != get_option( $args[ 'id' ] ) ? get_option( $args[ 'id' ] ) : $value;

			echo '<input name="' . $args[ 'id' ] . '" id="' . $args[ 'id' ] . '" type="text" value="' . $value . '">';
			if ( isset( $args[ 'label' ] ) ) {
				echo '<p class="description">' . $args[ 'label' ] . '</p>';
			}
		}

		function megamenu_field_callback_checkbox( $args ) {

			echo '<input name="' . $args[ 'id' ] . '" id="' . $args[ 'id' ] . '" type="checkbox" value="1" class="code" ' . checked( 1, get_option( $args[ 'id' ] ), false ) . '>';
			if ( isset( $args[ 'label' ] ) ) {
				echo '<label for="' . $args[ 'id' ] . '">' . $args[ 'label' ] . '</label>';
			}
		}

		function megamenu_field_callback_select( $args ) {

			$class = isset( $args[ 'class' ] ) ? ' class="' . esc_attr( $args[ 'class' ] ) . '"' : '';
			$multiple = isset( $args[ 'multiple' ] ) && $args[ 'multiple' ] ? ' multiple' : '';

			echo '<select name="' . $args[ 'id' ] . '" id="' . $args[ 'id' ] . '"' . $class . $multiple . '>';

			if( isset( $args[ 'options' ] ) && is_array( $args[ 'options' ] ) ) {

				foreach ( $args[ 'options' ] as $value => $option ) {
					if( null != get_option( $args[ 'id' ] ) ) {
						$selected = $value == get_option( $args[ 'id' ] ) ? ' selected' : '';
					} else {
						$selected = ( isset( $args[ 'value' ] ) && $value == $args[ 'value' ] ) ? ' selected' : '';
					}
					echo '<option' . $selected . ' value="' . $value . '">' . $option . '</option>';
				}
			}

			echo '</select>';
			if ( isset( $args[ 'label' ] ) ) {
				echo '<p class="description">' . $args[ 'label' ] . '</p>';
			}
		}

		function megamenu_field_callback_slider( $args ) {

			$range = isset( $args[ 'range' ] )           ? ' data-range="' . esc_attr( $args[ 'range' ] ) . '"' : '';
			$min   = isset( $args[ 'min' ] )             ? ' data-min="' . esc_attr( $args[ 'min' ] ) . '"'     : '';
			$max   = isset( $args[ 'max' ] )             ? ' data-max="' . esc_attr( $args[ 'max' ] ) . '"'     : '';
			$value = isset( $args[ 'value' ] )           ? esc_attr( $args[ 'value' ] )                         : '';
			$value = null != get_option( $args[ 'id' ] ) ? get_option( $args[ 'id' ] )                          : $value;

			echo '<input name="' . $args[ 'id' ] . '" id="' . $args[ 'id' ] . '" type="number" value="' . $value . '"><div class="jquery-ui-slider" data-id="' . $args[ 'id' ] . '"' . $range . $min . $max . '></div>';
			if ( isset( $args[ 'label' ] ) ) {
				echo '<p class="description">' . $args[ 'label' ] . '</p>';
			}
		}

		function tm_megamenu_admin_scripts( $hook ) {
				global $m_megamenu_admin_page;
				if ($hook != $m_megamenu_admin_page) {
					return;
				}

				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				wp_enqueue_style( 'jquery-ui', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css', array(), '1.11.4', 'all' );

				wp_enqueue_style( 'tm-megamenu-settings-fields', TM_MEGA_MENU_URI . 'admin/assets/css/settings.css', array(), TM_MEGA_MENU_VERSION, 'all' );

				wp_enqueue_script( 'tm-megamenu-settings-fields', TM_MEGA_MENU_URI . 'admin/assets/js/settings-fields' . $suffix . '.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-slider' ), TM_MEGA_MENU_VERSION, true );
		}

		/**
		 * Initialise translations
		 *
		 * @since 1.0.0
		 */
		public function lang() {
			load_plugin_textdomain( $this->slug, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Defines constants for the plugin.
		 *
		 * @since 1.0.0
		 */
		function constants() {

			/**
			 * Set the version number of the plugin.
			 *
			 * @since 1.0.0
			 */
			define( 'TM_MEGA_MENU_VERSION', $this->version );

			/**
			 * Set the slug of the plugin.
			 *
			 * @since 1.0.0
			 */
			define( 'TM_MEGA_MENU_SLUG', basename( dirname( __FILE__ ) ) );

			/**
			 * Set constant path to the plugin directory.
			 *
			 * @since 1.0.0
			 */
			define( 'TM_MEGA_MENU_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

			/**
			 * Set constant path to the plugin URI.
			 *
			 * @since 1.0.0
			 */
			define( 'TM_MEGA_MENU_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}

		/**
		 * Include core files for both: admin and public
		 *
		 * @since 1.0.0
		 */
		function includes() {
			require_once( 'core/includes/tm-mega-menu-core-functions.php' );
			require_once( 'core/includes/class-tm-mega-menu-cache.php' );
		}

		/**
		 * Include files and assign actions and filters for admin
		 *
		 * @since 1.0.0
		 */
		private function _admin() {

			require_once( 'admin/includes/class-tm-mega-menu-options.php' );
			require_once( 'admin/includes/class-tm-mega-menu-item-manager.php' );
			require_once( 'admin/includes/class-tm-mega-menu-walker.php' );
			require_once( 'admin/includes/class-cherry-update/class-cherry-plugin-update.php' );

			new tm_mega_menu_item_manager();
			new tm_mega_menu_widget_manager();

			add_action( 'admin_menu', array( $this, 'tm_megamenu_add_pages' ) );
			add_action( 'admin_init', array( $this, 'tm_megamenu_settings_api_init' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'tm_megamenu_admin_scripts' ) );
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'megamenu_edit_nav_menu_walker' ), 10, 2 );

			$updater = new Cherry_Plugin_Update();
			$updater->init( array(
				'version'         => TM_MEGA_MENU_VERSION,
				'slug'            => TM_MEGA_MENU_SLUG,
				'repository_name' => TM_MEGA_MENU_SLUG,
			) );
		}

		/**
		 * Include files and assign actions and filters for public
		 *
		 * @since 1.0.0
		 */
		private function _public() {
			require_once( 'public/includes/class-tm-mega-menu-public-manager.php' );
		}

		public function megamenu_edit_nav_menu_walker( $walker, $menu_id ) {

			$menu_locations = get_nav_menu_locations();

			$allowed_menus = get_registered_nav_menus();
			$allowed_menus = array_keys( $allowed_menus );

			$allowed_locations = array();

			foreach ( $allowed_menus as $menu ) {
				if ( isset( $menu_locations[ $menu ] ) ) {
					$allowed_locations[ $menu ] = $menu_locations[ $menu ];
				}
			}

			if( is_array( $allowed_locations ) && $menu_location = array_search( $menu_id, $allowed_locations ) ) {

				$mega_menu_location = ( array ) get_option( 'tm-mega-menu-location', array() );

				if( in_array( $menu_location, $mega_menu_location ) ) {
					return 'Tm_Mega_Menu_Walker_Edit';
				}
				return $walker;
			}
			return $walker;
		}

		/**
		 * Checks this WordPress installation is v3.8 or above.
		 * 3.8 is needed for dashicons.
		 *
		 * @since 1.0.0
		 */
		public function is_compatible_wordpress_version() {
			global $wp_version;

			return $wp_version >= 4.5;
		}

		/**
		 * Clear the cache when the tm mega menu is updated.
		 *
		 * @since 1.0.0
		 */
		public function clear_caches() {
			// https://wordpress.org/plugins/widget-output-cache/
			if ( function_exists( 'menu_output_cache_bump' ) ) {
				menu_output_cache_bump();
			}

			// https://wordpress.org/plugins/widget-output-cache/
			if ( function_exists( 'widget_output_cache_bump' ) ) {
				widget_output_cache_bump();
			}

			// https://wordpress.org/plugins/wp-super-cache/
			if ( function_exists( 'wp_cache_clear_cache' ) ) {
				global $wpdb;
				wp_cache_clear_cache( $wpdb->blogid );
			}
		}
	}
	new tm_mega_menu();
}