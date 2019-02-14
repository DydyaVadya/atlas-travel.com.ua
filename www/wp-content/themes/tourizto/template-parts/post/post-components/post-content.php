<?php
/**
 * Template part for displaying post content.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Tourizto
 */

$utility             = tourizto_utility()->utility;
$blog_content        = get_theme_mod( 'blog_posts_content', tourizto_theme()->customizer->get_default( 'blog_posts_content' ) );
$blog_content_length = get_theme_mod( 'blog_posts_content_length', tourizto_theme()->customizer->get_default( 'blog_posts_content_length' ) );
$length              = ( 'full' === $blog_content ) ? - 1 : $blog_content_length;
$content_visible     = ( 'none' !== $blog_content ) ? true : false;
$content_type        = ( 'full' !== $blog_content ) ? 'post_excerpt' : 'post_content';

$utility->attributes->get_content( array(
	'visible'      => $content_visible,
	'length'       => $length,
	'content_type' => $content_type,
	'echo'         => true,
) );
