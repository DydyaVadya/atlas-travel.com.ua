<?php
/**
 * TM mega menu core functions
 *
 * @package   tm_mega_menu
 * @author    TemplateMonster
 * @license   GPL-2.0+
 */

/**
 * Get option by name from theme options
 *
 * @since  1.0.0
 *
 * @param  string  $name    option name to get
 * @param  mixed   $default default option value
 * @return mixed            option value
 */
function tm_mega_menu_get_option( $name, $default = false ) {

	return $default;
}

/**
 * Parse HTML tag attributes array into formatted string
 *
 * @since  1.0.0
 *
 * @param  array  $atts arry of attributes
 * @return string       parsed string
 */
function tm_mega_menu_parse_atts( $atts = array() ) {

	if ( !is_array( $atts ) ) {
		return;
	}

	$str_atts = '';

	foreach ( $atts as $att_name => $att_value ) {
		$str_atts .= sprintf( ' %1$s="%2$s"', esc_attr( $att_name ), esc_attr( $att_value ) );
	}

	return $str_atts;
}

/**
 * Sanitize width value for % or 'px' format
 *
 * @since  1.0.0
 *
 * @param  string  $value  input value
 * @return string          sanitized value
 */
function tm_mega_menu_sanitize_width( $value ) {

	$new_value = absint( filter_var( $value, FILTER_SANITIZE_NUMBER_INT ) );

	if ( strpos( $value, '%' ) ) {

		$new_value = $new_value > 100 ? 100 : $new_value;

		return $new_value . '%';
	}
	return $new_value . 'px';
}