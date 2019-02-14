<?php
/**
 * Template part for displaying post publish date.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tourizto
 */

$utility = tourizto_utility()->utility;

if ( 'post' === get_post_type() ) :

	$date_visible = ( is_single() ) ? tourizto_is_meta_visible( 'single_post_publish_date', 'single' ) : tourizto_is_meta_visible( 'blog_post_publish_date', 'loop' );

	$utility->meta_data->get_date( array(
		'visible'     => $date_visible,
		'html'        => '<div class="post__date post__date-circle"><a href="%2$s" %3$s %4$s ><time datetime="%5$s">',
		'class'       => 'post__date-link',
		'echo'        => true,
	) );

	$utility->meta_data->get_date( array(
		'visible'     => $date_visible,
		'date_format' => 'd',
		'html'        => '<span class="post__date-day">%6$s%7$s</span>',
		'class'       => 'post__date-link',
		'echo'        => true,
	) );

	$utility->meta_data->get_date( array(
		'visible'     => $date_visible,
		'date_format' => 'M',
		'html'        => '<span class="post__date-month">%6$s%7$s</span>',
		'class'       => 'post__date-link',
		'echo'        => true,
	) );

	$utility->meta_data->get_date( array(
		'visible'     => $date_visible,
		'html'        => '</time></a></div>',
		'class'       => 'post__date-link',
		'echo'        => true,
	) );

endif;
