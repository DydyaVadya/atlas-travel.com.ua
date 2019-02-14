<?php
/**
 * Menu Template Functions.
 *
 * @package Tourizto
 */

/**
 * Get main menu.
 *
 * @since  1.0.0
 * @return string
 */
function tourizto_get_main_menu() {
	$args = apply_filters( 'tourizto_main_menu_args', array(
		'theme_location'   => 'main',
		'container'        => '',
		'menu_id'          => 'main-menu',
		'echo'             => false,
		'fallback_cb'      => 'tourizto_set_nav_menu',
		'fallback_message' => esc_html__( 'Set main menu', 'tourizto' ),
	) );

	return wp_nav_menu( $args );
}

/**
 * Show main menu.
 *
 * @since  1.0.0
 * @return void
 */
function tourizto_main_menu() {

	$main_menu  = tourizto_get_main_menu();
	$menu_style = get_theme_mod( 'header_menu_style', tourizto_theme()->customizer->get_default( 'header_menu_style' ) );

	$menu_style = 'main-menu-' . $menu_style;

	printf( '<nav id="site-navigation" class="main-navigation %s" role="navigation">%s</nav><!-- #site-navigation -->', $menu_style, $main_menu );
}

/**
 * Show vertical main menu.
 *
 * @param string $slide Slide type: left or right.
 * @param array  $args  Arguments.
 *
 * @since  1.0.0
 * @return void
 */
function tourizto_vertical_main_menu( $slide = 'left', $args = array() ) {

	$default_args = apply_filters( 'tourizto_vertical_menu_args', array(
		'container-class'         => 'vertical-menu',
		'navigation-buttons-html' => '<div class="main-navigation-buttons"><span class="navigation-button back hide">%1$s</span><span class="navigation-button close">%2$s</span></div>',
		'button-back-inner-html'  => '<i class="nc-icon-mini arrows-1_bold-left"></i><span class="navigation-button__text">' . esc_html__( 'Back', 'tourizto' ) . '</span>',
		'button-close-inner-html' => '<i class="nc-icon-mini ui-1_bold-remove"></i><span class="navigation-button__text">' . esc_html__( 'Close', 'tourizto' ) . '</span>',
	) );

	$args        = wp_parse_args( $args, $default_args );
	$menu        = tourizto_get_main_menu();
	$slide_class = sprintf( 'slide--%s', sanitize_html_class( $slide ) );
	$nav_btns    = sprintf( $args['navigation-buttons-html'], $args['button-back-inner-html'], $args['button-close-inner-html'] );

	printf( '<nav id="site-navigation" class="main-navigation %1$s %2$s" role="navigation">%3$s%4$s</nav>', $args['container-class'], $slide_class, $nav_btns, $menu  );
}

/**
 * Show footer menu.
 *
 * @since  1.0.0
 * @return void
 */
function tourizto_footer_menu() {
	if ( ! get_theme_mod( 'footer_menu_visibility', tourizto_theme()->customizer->get_default( 'footer_menu_visibility' ) ) ) {
		return;
	}

	$args = apply_filters( 'tourizto_footer_menu_args', array(
		'theme_location'   => 'footer',
		'container'        => '',
		'menu_id'          => 'footer-menu-items',
		'menu_class'       => 'footer-menu__items',
		'depth'            => 1,
		'echo'             => false,
		'fallback_cb'      => 'tourizto_set_nav_menu',
		'fallback_message' => esc_html__( 'Set footer menu', 'tourizto' ),
	) );

	printf('<nav id="footer-navigation" class="footer-menu" role="navigation">%s</nav><!-- #footer-navigation -->', wp_nav_menu( $args ) );
}

/**
 * Show top page menu if active.
 *
 * @since  1.0.0
 * @return void
 */
function tourizto_top_menu() {

	if ( ! tourizto_is_top_menu_visible() ) {
		return;
	}

	wp_nav_menu( apply_filters( 'tourizto_top_menu_args', array(
		'theme_location'  => 'top',
		'container'       => 'div',
		'container_class' => 'top-panel__menu',
		'menu_class'      => 'top-panel__menu-list inline-list',
		'depth'           => 1,
	) ) );
}

/**
 * Check visibility top menu.
 *
 * @return bool
 */
function tourizto_is_top_menu_visible() {

	$is_visible = false;

	if ( has_nav_menu( 'top' ) && get_theme_mod( 'top_menu_visibility', tourizto_theme()->customizer->get_default( 'top_menu_visibility' ) ) ) {
		$is_visible = true;
	}

	return $is_visible;
}

/**
 * Show login link if active.
 *
 * @since  1.0.0
 * @return void
 */
function tourizto_login_link() {

	if ( ! tourizto_is_login_link_visible() ) {
		return;
	}

	do_action('cherry_popups_login_logout_link');
}

/**
 * Check visibility login link.
 *
 * @return bool
 */
function tourizto_is_login_link_visible() {

	$is_visible = false;

	if ( has_nav_menu( 'top' ) && get_theme_mod( 'login_link_visibility', tourizto_theme()->customizer->get_default( 'login_link_visibility' ) ) ) {
		$is_visible = true;
	}

	return $is_visible;
}

/**
 * Get social nav menu.
 *
 * @since  1.0.0
 * @since  1.0.0  Added new param - $item.
 * @since  1.0.1  Added arguments to the filter.
 * @param  string $context Current post context - 'single' or 'loop'.
 * @param  string $type    Content type - icon, text or both.
 * @return string
 */
function tourizto_get_social_list( $context, $type = 'icon' ) {
	static $instance = 0;
	$instance++;

	$container_class = array( 'social-list' );

	if ( ! empty( $context ) ) {
		$container_class[] = sprintf( 'social-list--%s', sanitize_html_class( $context ) );
	}

	$container_class[] = sprintf( 'social-list--%s', sanitize_html_class( $type ) );

	$args = apply_filters( 'tourizto_social_list_args', array(
		'theme_location'   => 'social',
		'container'        => 'div',
		'container_class'  => join( ' ', $container_class ),
		'menu_id'          => "social-list-{$instance}",
		'menu_class'       => 'social-list__items inline-list',
		'depth'            => 1,
		'link_before'      => ( 'icon' == $type ) ? '<span class="screen-reader-text">' : '',
		'link_after'       => ( 'icon' == $type ) ? '</span>' : '',
		'echo'             => false,
		'fallback_cb'      => 'tourizto_set_nav_menu',
		'fallback_message' => esc_html__( 'Set social menu', 'tourizto' ),
	), $context, $type );

	return wp_nav_menu( $args );
}

/**
 * Set fallback callback for nav menu.
 *
 * @param  array $args Nav menu arguments.
 * @return null|string
 */
function tourizto_set_nav_menu( $args ) {

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return null;
	}

	$format = '<div class="set-menu %3$s"><a href="%2$s" target="_blank" class="set-menu_link">%1$s</a></div>';
	$label  = $args['fallback_message'];
	$url    = esc_url( admin_url( 'nav-menus.php' ) );

	return sprintf( $format, $label, $url, $args['container_class'] );
}

/**
 * Show menu button.
 *
 * @since  1.1.0
 * @param  string $menu_id Menu ID.
 * @return void
 */
function tourizto_menu_toggle( $menu_id ) {
	$format = apply_filters(
		'tourizto_menu_toggle_html',
		'<button class="main-menu-toggle menu-toggle" aria-controls="%s" aria-expanded="false"><span class="menu-toggle-box"><span class="menu-toggle-inner"></span></span></button>'
	);

	printf( $format, $menu_id );
}

/**
 * Show vertical menu button.
 *
 * @since  1.1.0
 * @param  string $menu_id Menu ID.
 * @return void
 */
function tourizto_vertical_menu_toggle( $menu_id ) {
	$format = apply_filters(
		'tourizto_vertical_menu_toggle_html',
		'<button class="vertical-menu-toggle menu-toggle" aria-controls="%s" aria-expanded="false"><span class="menu-toggle-box"><span class="menu-toggle-inner"></span></span></button>'
	);

	printf( $format, $menu_id );
}
