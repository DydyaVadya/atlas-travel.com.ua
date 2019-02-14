<?php
/**
 * Import remap hooks
 */

add_filter( 'cherry_data_import_home_regex_replace', 'tourizto_remap_shortcodes' );

/**
 * Remap terms in shortocdes
 *
 * @param  array $regex Shortcode data for regex.
 * @return array
 */
function tourizto_remap_shortcodes( $regex ) {

	$regex[] = array(
		'shortcode' => 'tm_pb_cherry_testi',
		'attr'      => 'ids',
		'delimiter' => ' ',
	);

	$regex[] = array(
		'shortcode' => 'tm_pb_cherry_testi',
		'attr'      => 'category',
		'delimiter' => ' ',
	);

	return $regex;
}
