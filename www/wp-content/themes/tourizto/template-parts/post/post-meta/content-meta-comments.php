<?php
/**
 * Template part for displaying post comments.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tourizto
 */
$utility = tourizto_utility()->utility;

if ( 'post' === get_post_type() ) :

	$comment_visible = ( is_single() ) ? tourizto_is_meta_visible( 'single_post_comments', 'single' ) : tourizto_is_meta_visible( 'blog_post_comments', 'loop' );

	$utility->meta_data->get_comment_count( array(
		'visible' => $comment_visible,
		'icon'    => '<i class="nc-icon-mini ui-2_chat-round-content"></i>',
		'html'    => '<span class="post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
		'sufix'   => '%s',
		'class'   => 'post__comments-link',
		'echo'    => true,
	) );

endif;
