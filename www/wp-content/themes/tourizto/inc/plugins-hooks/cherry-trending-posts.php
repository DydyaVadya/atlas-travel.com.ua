<?php
/**
 * Cherry Trending Posts hooks.
 *
 * @package Tourizto
 */

// Enable cherry_trend_posts cache fix.
add_filter( 'cherry_trend_posts_cache_fix', '__return_true' );

// Customization cherry_trend_posts widgets.
add_filter( 'cherry_trend_posts_date_args', 'tourizto_cherry_trend_posts_date_args' );
add_filter( 'cherry_trend_posts_comments_args', 'tourizto_cherry_trend_posts_comments_args' );
add_filter( 'cherry_trend_posts_author_args', 'tourizto_cherry_trend_posts_author_args' );
add_filter( 'cherry_trend_posts_category_args', 'tourizto_cherry_trend_posts_category_args' );
add_filter( 'cherry_trend_posts_tag_args', 'tourizto_cherry_trend_posts_tag_args' );
add_filter( 'cherry_trend_posts_button_args', 'tourizto_cherry_trend_posts_button_args' );

/**
 * Customization date arguments.
 *
 * @param array $args Date arguments.
 *
 * @return array
 */
function tourizto_cherry_trend_posts_date_args( $args = array() ) {
	$args['html']   = '<span class="post__date">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s">%6$s%7$s</time></a></span>';

	return $args;
}

/**
 * Customization comments arguments.
 *
 * @param array $args Comments arguments.
 *
 * @return array
 */
function tourizto_cherry_trend_posts_comments_args( $args = array() ) {

	$theme_args = array(
		'sufix' => '%s',
		'icon'  => '<i class="nc-icon-mini ui-2_chat-round-content"></i>',
		'html'  => '<span class="post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
	);

	$args = wp_parse_args( $theme_args, $args );

	return $args;
}

/**
 * Customization author arguments.
 *
 * @param array $args Author arguments.
 *
 * @return array
 */
function tourizto_cherry_trend_posts_author_args( $args = array() ) {
	$args['prefix'] = esc_html__( 'by ', 'tourizto' );
	$args['html']   = '<span class="posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>';

	return $args;
}

/**
 * Customization category arguments.
 *
 * @param array $args Category arguments.
 *
 * @return array
 */
function tourizto_cherry_trend_posts_category_args( $args = array() ) {

	$theme_args = array(
		'before'    => '<span class="cherry-trend-post__meta-cats post__cats">',
		'after'     => '</span>',
		'prefix'    => esc_html__( 'in ', 'tourizto' ),
		'delimiter' => ' ',
	);

	$args = wp_parse_args( $theme_args, $args );

	return $args;
}

/**
 * Customization tags arguments.
 *
 * @param array $args Tags arguments.
 *
 * @return array
 */
function tourizto_cherry_trend_posts_tag_args( $args = array() ) {

	$theme_args = array(
		'before' => '<span class="cherry-trend-post__meta-tags post__tags">',
		'after'  => '</span>',
		'prefix' => esc_html__( 'Tags: ', 'tourizto' ),
	);

	$args = wp_parse_args( $theme_args, $args );

	return $args;
}

/**
 * Customization button arguments.
 *
 * @param array $args Button arguments.
 *
 * @return array
 */
function tourizto_cherry_trend_posts_button_args( $args = array() ) {

	$args['class'] = 'btn-link cherry-trend-post__btn';

	return $args;
}
