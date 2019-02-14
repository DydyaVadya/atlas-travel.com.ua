<?php

/**
 * Loop item meta
 */

if ( 'yes' !== $this->get_attr( 'show_meta' ) ) {
	return;
}

echo '<div class="post-meta">';

	jet_elements()->utility()->meta_data->get_author( array(
		'class'   => 'posted-by__author',
		'prefix'  => esc_html__( 'By ', 'starpus' ),
		'html'    => '<span class="posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
		'echo'    => true,
	) );

	jet_elements()->utility()->meta_data->get_date( array(
		'class'   => 'post__date-link',
		'html'    => '<span class="post__date">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s" title="%5$s">%6$s%7$s</time></a></span>',
		'echo'    => true,
	) );

	jet_elements()->utility()->meta_data->get_comment_count( array(
		'class'   => 'post__comments-link',
		'html'    => '<span class="post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
		'echo'    => true,
	) );

echo '</div>';
