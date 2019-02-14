<?php
/**
 * `Trending Posts` data.
 *
 * @package   Cherry_Trending_Posts
 * @author    Template Monster
 * @license   GPL-3.0+
 * @copyright 2012 - 2016, Template Monster
 */

if ( ! class_exists( 'Cherry_Trending_Posts_Data' ) ) {
	/**
	 * Class for Cherry Trending Post data.
	 *
	 * @since 1.0.0
	 */
	class Cherry_Trending_Posts_Data {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Sets up our actions/filters.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {}

		/**
		 * Retrieve the `WP_Query` object.
		 *
		 * @since  1.0.0
		 * @param  array|string $args Arguments to be passed to the query.
		 * @return object|bool        Object if true, boolean if false.
		 */
		public function query( $args = array(), $context = '' ) {

			$defaults = array(
				'post_type'           => 'post',
				'posts_per_page'      => 3,
				'ignore_sticky_posts' => true,
			);

			$args = wp_parse_args( $args, $defaults );

			/**
			 * Filter the array of arguments.
			 *
			 * @since 1.0.0
			 * @param array Arguments to be passed to the query.
			 */
			$args = apply_filters( 'cherry_trend_posts_wp_query_args', $args, $context );

			// The Query.
			$query = new WP_Query( $args );

			wp_reset_postdata();

			if ( ! $query->have_posts() ) {
				return false;
			}

			return $query;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}
