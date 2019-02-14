<?php
/**
 * TM mega menu walker class
 *
 * @package   tm_mega_menu
 * @author    TemplateMonster
 * @license   GPL-2.0+
 */

if( ! class_exists( 'tm_mega_menu_walker' ) ) :

/**
 * @package WordPress
 * @since 1.0.0
 * @uses Walker
 */
class tm_mega_menu_walker extends Walker_Nav_Menu {

	/**
	 * Check if sub grouped to cols
	 * @var boolean
	 */
	private $child_columns = false;

	/**
	 * Mega submenu trigger
	 * @var boolean
	 */
	private $is_mega_sub = false;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {

		$indent  = str_repeat( "\t", $depth );
		$classes = 'tm-mega-menu-sub sub-menu level-' . $depth . ' effect-' . get_option( 'tm-mega-menu-effect', 'slide-top' );

		$classes .= $this->is_mega_sub ? ' mega-sub' : ' simple-sub';
		$output  .= "$indent<ul class=\"$classes\">\n";

	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent        = str_repeat( "\t", $depth );
		$mega_settings = isset( $item->megamenu_settings ) ? $item->megamenu_settings : array();

		if ( ! isset( $item->description ) ) {
			$item->description = false;
		}

		$mega_settings = wp_parse_args( $mega_settings, array(
			'type'                  => '',
			'item-hide-text'        => '',
			'item-hide-arrow'       => '',
			'item-icon'             => '',
			'item-arrow'            => '',
			'sub-items-to-cols'     => '',
			'sub-cols-num'          => '12',
			'item-submenu-position' => 'fullwidth',
			'item-width-fullscreen' => '100%',
			'item-width-desktop'    => '100%',
			'item-width-tablet'     => '100%',
			'item-hide-mobile'      => '',
			'duration'              => 300
		) );

		$classes   = empty( $item->classes ) ? array() : ( array ) $item->classes;

		$classes[] = 'menu-item-' . $item->ID;

		$meta_atts = '';

		if ( 0 === $depth ) {

			$this->child_columns = 'sub-items-to-cols' == $mega_settings[ 'sub-items-to-cols' ] ? $mega_settings[ 'sub-cols-num' ] : '';

			$classes[] = 'tm-mega-menu-top-item item-submenu-position-' . $mega_settings[ 'item-submenu-position' ];

			if ( $this->has_children ) {
				$meta_atts = array(
					'data-sub-position'     => $mega_settings[ 'item-submenu-position' ],
					'data-sub-type'         => 'megamenu' == $mega_settings[ 'type' ] ? 'megamenu' : 'standard',
					'data-width-fullscreen' => tm_mega_menu_sanitize_width( $mega_settings[ 'item-width-fullscreen' ] ),
					'data-width-desktop'    => tm_mega_menu_sanitize_width( $mega_settings[ 'item-width-desktop' ] ),
					'data-width-tablet'     => tm_mega_menu_sanitize_width( $mega_settings[ 'item-width-tablet' ] )
				);
			}
		} else {
			$classes[] = 'tm-mega-menu-sub-item';
		}
		if ( $this->has_children ) {

			$classes[] = 'tm-mega-menu-has-children';

			if ( 0 === $depth && 'megamenu' == $mega_settings[ 'type' ] ) {

				$this->is_mega_sub = true;
			}
			if ( 'megamenu' != $mega_settings[ 'type' ] ) {

				$this->is_mega_sub = false;
			}
		}
		if ( 0 < $depth ) {

			$classes[] = 'item-nested-sub item-nested-sub-' . $depth;

			if ( $this->child_columns ) {

				if ( 1 < $depth ) {

					$classes[] = 'sub-column-item';
				}
				if ( 1 === $depth ) {

					$classes[] = 'sub-column-title menu-columns-' . round( 12 / $this->child_columns, 0 );
				}
			}
		}

		if ( isset( $item->type ) && 'widget' == $item->type ) {
			$classes[] = 'menu-item-widget';
		}

		if ( '' != $mega_settings[ 'item-hide-mobile' ] ) {
			$classes[] = 'item-hide-mobile';
		}

		if ( in_array( 'current_page_item', $classes ) || in_array( 'current-menu-item', $classes ) ) {
			wp_cache_set( 'tm_mega_menu_has_active', 'has', 'tm-mega-menu' );
		}

		/**
		 * Default WP filter
		 *
		 * Filter the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Default WP filter
		 *
		 * Filter the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		/**
		 * Filter additional attributes for mega menu item
		 *
		 * @since 1.0.0
		 *
		 * @param array  $meta_atts default attributes
		 * @param object $item      The current menu item.
		 * @param array  $args      An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth     Depth of menu item. Used for padding.
		 */
		$meta_atts = tm_mega_menu_parse_atts( apply_filters( 'tm_mega_menu_additional_item_attributes', $meta_atts, $item, $args, $depth ) );

		$output   .= $indent . "<li" . $id . $class_names . $meta_atts . ">\n";

		// output the widgets
		if ( $item->content ) {

			$item_output = $item->content;

			/**
			 * Default WP filter
			 *
			 * Filter a menu item's starting output.
			 *
			 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
			 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
			 * no filter for modifying the opening and closing `<li>` for a menu item.
			 *
			 * @since 3.0.0
			 *
			 * @param string $item_output The menu item's starting HTML output.
			 * @param object $item        Menu item data object.
			 * @param int    $depth       Depth of menu item. Used for padding.
			 * @param array  $args        An array of {@see wp_nav_menu()} arguments.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

			return;
		}

		/** This filter is documented in wp-includes/post-template.php */
		$link_title       = apply_filters( 'the_title', $item->title, $item->ID );
		$atts             = array();
		$atts[ 'title' ]  = empty( $item->attr_title ) ? '' : $item->attr_title;
		$atts[ 'target' ] = empty( $item->target )     ? '' : $item->target;
		$atts[ 'rel' ]    = empty( $item->xfn )        ? '' : $item->xfn;
		$atts[ 'href' ]   = empty( $item->url )        ? '' : $item->url;

		/**
		 * Default WP filter
		 *
		 * Filter the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item  The current menu item.
		 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
		 * @param int    $depth Depth of menu item. Used for padding.
		 */
		$atts       = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		$attributes = '';

		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before . '<a' . $attributes . '>';

		$link_before = $args->link_before;

		if ( $mega_settings[ 'item-icon' ] ) {
			/**
			 * Filter menu item icon HTML format
			 *
			 * @since  1.0.0
			 *
			 * @param  string  default FontAwesome icon format
			 */
			$icon_format  = apply_filters( 'tm_mega_menu_icon_format', '<i class="fa %1$s mega-menu-icon"></i>' );
			$link_before .= sprintf( $icon_format, esc_attr( $mega_settings[ 'item-icon' ] ) );
		}

		/**
		 * Filter HTML outputed before link text. By default appends menu icon, if exist
		 *
		 * @since  1.0.0
		 *
		 * @param string  $link_before  default HTML markup before link text
		 * @param object  $item         The current menu item.
		 * @param array   $args         An array of {@see wp_nav_menu()} arguments.
		 * @param int     $depth        Depth of menu item. Used for padding.
		 */
		$item_output .= apply_filters( 'tm_mega_menu_before_link_text', $link_before, $item, $args, $depth );

		if ( ! $mega_settings[ 'item-hide-text' ] ) {
			$item_output .= $link_title;
		}

		$link_after  = $args->link_after;

		$arrow_level = 0 < $depth ? 'sub-arrow' : 'top-level-arrow';

		$toggle_mobile = '';

		if ( $this->has_children ) {
			if ( ! $mega_settings[ 'item-hide-arrow' ] ) {

				/**
				 * Filter menu item arrow HTML format
				 *
				 * @since  1.0.0
				 *
				 * @param  string  default FontAwesome icon format
				 */
				$icon_format = apply_filters( 'tm_mega_menu_arrow_format', '<i class="fa %1$s mega-menu-arrow %2$s"></i>' );
				$arrow       = 0 < $depth ? 'fa-angle-right' : 'fa-angle-down';
				$arrow       = '' !== $mega_settings[ 'item-arrow' ] ? $mega_settings[ 'item-arrow' ] : $arrow;
				$link_after  = sprintf( $icon_format, esc_attr( $arrow ), $arrow_level ) . $link_after;
			}
			if( 'item-hide-mobile' !== $mega_settings['item-hide-mobile'] ) {

				$link_after   .= sprintf( apply_filters( 'tm_mega_menu_arrow_mobile_format', '<label for="tm-megamenu-toggle-' . $item->ID . '" class="mega-menu-mobile-arrow"><i class="fa fa-angle-down %1$s"></i></label>' ), $arrow_level );

				$toggle_mobile = '<input type="checkbox" name="tm-megamenu-toggle-' . $item->ID . '" id="tm-megamenu-toggle-' . $item->ID . '" class="mega-menu-mobile-toggle" value="1">';
			}
		}

		/**
		 * Filter HTML outputed before link text. By default appends menu icon, if exist
		 *
		 * @since  1.0.0
		 *
		 * @param string  $link_after   default HTML markup after link text
		 * @param object  $item         The current menu item.
		 * @param array   $args         An array of {@see wp_nav_menu()} arguments.
		 * @param int     $depth        Depth of menu item. Used for padding.
		 */
		$item_output .= apply_filters( 'tm_mega_menu_after_link_text', $link_after, $item, $args, $depth ) . "</a>\n" . $args->after . $toggle_mobile;

		/**
		 * Default WP filter
		 *
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item        Menu item data object.
		 * @param int    $depth       Depth of menu item. Used for padding.
		 * @param array  $args        An array of {@see wp_nav_menu()} arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

}

endif;