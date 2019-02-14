<?php
/**
 * Cherry-testi hooks.
 *
 * @package Tourizto
 */

// Customization cherry-testimonials pagination args.
add_filter( 'tm_testimonials_pagination_args', 'tourizto_tm_testimonials_pagination_args', 10, 2 );

// Add template to tm-testimonials templates list.
add_filter( 'tm_testimonials_templates_list', 'tourizto_add_template_to_tm_testimonials_templates_list' );

// Change testimonials archive page template.
add_filter( 'tm_testimonials_archive_template_args', 'tourizto_tm_testimonials_archive_template_args' );

// Add custom class at title wrap if invert
add_filter( 'tm_testimonials_title_format', 'tourizto_tm_testimonials_add_invert_class_at_title_wrap', 10, 3 );

/**
 * Customization cherry-testimonials pagination args.
 *
 * @return array
 */
function tourizto_tm_testimonials_pagination_args( $pagination_args, $args ) {

	$pagination_args = array(
		'prev_text' => '<i class="nc-icon-outline arrows-1_tail-triangle-left"></i>',
		'next_text' => '<i class="nc-icon-outline arrows-1_tail-triangle-right"></i>',
	);

	return $pagination_args;
}

/**
 * Add template to tm-testimonials templates list.
 *
 * @param array $tmpl_list Templates list.
 *
 * @return array
 */
function tourizto_add_template_to_tm_testimonials_templates_list( $tmpl_list ) {
	$tmpl_list['default-white.tmpl'] = 'default-white.tmpl';
	$tmpl_list['default-invert.tmpl'] = 'default-invert.tmpl';
	$tmpl_list['default-2.tmpl'] = 'default-2.tmpl';
	$tmpl_list['default-3.tmpl'] = 'default-3.tmpl';
	$tmpl_list['default-4.tmpl'] = 'default-4.tmpl';
	$tmpl_list['default-5.tmpl'] = 'default-5.tmpl';

	unset( $tmpl_list['boxed.tmpl'] );
	unset( $tmpl_list['speech-bubble.tmpl'] );

	return $tmpl_list;
}

/**
 * Change testimonials archive page template.
 *
 * @param array $args Testimonials archive template args.
 *
 * @return array
 */
function tourizto_tm_testimonials_archive_template_args ( $args = array() ) {

	$args['template'] = 'default-without-icon.tmpl';

	return $args;
}

/**
 * Add custom class at title wrap if invert.
 *
 * @param string $wrap Testimonials title wrap.
 *
 * @return string
 */
function tourizto_tm_testimonials_add_invert_class_at_title_wrap( $wrap, $inner, $args ) {

	if ( strpos( $args['custom_class'], 'invert' ) !== false ) {
		return '<div class="tm-testi__title invert">%s</div>';
	}

	return $wrap;
}
