<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Tourizto
 */
while ( have_posts() ) : the_post();

	tourizto_get_template_part( 'template-parts/post/content-single', get_post_format() );

	tourizto_post_author_bio();

	tourizto_get_template_part( 'template-parts/content', 'post-navigation' );

	tourizto_related_posts();

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

endwhile; // End of the loop.
