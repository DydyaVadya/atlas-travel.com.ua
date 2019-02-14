<?php
/**
 * Template part for posts pagination.
 *
 * @package Tourizto
 */

the_posts_pagination(
	array(
		'prev_text' => sprintf( '%s %s', '<i class="nc-icon-mini arrows-1_minimal-left"></i>', esc_html__( 'PREV', 'tourizto' ) ),
		'next_text' => sprintf( '%s %s', esc_html__( 'NEXT', 'tourizto' ), '<i class="nc-icon-mini arrows-1_minimal-right"></i>' ),
	)
);
