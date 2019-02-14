<?php
/**
 * Rating post meta.
 *
 * @package   Cherry_Trending_Posts
 * @author    Template Monster
 * @license   GPL-3.0+
 * @copyright 2012 - 2016, Template Monster
 */

// If class 'Cherry_Trending_Posts_Callback_Rating' not exists.
if ( ! class_exists( 'Cherry_Trending_Posts_Callback_Rating' ) ) {

	/**
	 * Add rating system and callback.
	 *
	 * @since 1.0.0
	 */
	class Cherry_Trending_Posts_Callback_Rating {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Unique name for storing rating meta fields and cookie.
		 *
		 * @since 1.0.0
		 * @var   string
		 */
		public $meta_key = 'cherry_trend_rating';

		/**
		 * Rating meta components.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		public $meta_components = array(
			'rate'  => 0,
			'total' => 5,
			'votes' => 0,
		);

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Hooks a function on to display/return post `Rating` meta.
			add_action( 'cherry_trend_posts_display_rating', array( $this, 'the_rating' ) );
			add_filter( 'cherry_trend_posts_return_rating', array( $this, 'get_the_rating' ) );

			// Handlers for AJAX request.
			add_action( 'wp_ajax_cherry_trend_posts_handle_rating',        array( $this, 'ajax_handle' ) );
			add_action( 'wp_ajax_nopriv_cherry_trend_posts_handle_rating', array( $this, 'ajax_handle' ) );
			add_action( 'wp_ajax_cherry_trend_posts_check_rating',         array( $this, 'ajax_cache_fix' ) );
			add_action( 'wp_ajax_nopriv_cherry_trend_posts_check_rating',  array( $this, 'ajax_cache_fix' ) );
		}

		/**
		 * Get clean rating output.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_the_rating( $args = array() ) {
			global $post;

			$result = $this->get_rating_html( $args );

			return '<div class="cherry-trend-rating">' . $result . '</div>';
		}

		/**
		 * Display rating.
		 *
		 * @since  1.0.0
		 */
		public function the_rating( $args = array() ) {
			echo $this->get_the_rating( $args );
		}

		/**
		 * Retrieve a post rating HTML.
		 *
		 * @since  1.0.0
		 * @param  array $args Arguments.
		 * @return string
		 */
		public function get_rating_html( $args = array() ) {
			/**
			 * Filter a default arguments.
			 *
			 * @since 1.0.0
			 * @param array $defaults
			 */
			$defaults = apply_filters( 'cherry_trend_posts_rating_defaults', array(
				'post_id'     => get_the_ID(),
				'format'      => esc_html__( '%1$s (%2$s. Average %3$s of %4$s)', 'cherry-trending-posts' ),
				'is_disabled' => false,
			) );

			$args = wp_parse_args( $args, $defaults );

			/**
			 * Filter arguments.
			 *
			 * @since 1.0.0
			 * @param array $args
			 */
			$args = apply_filters( 'cherry_trend_posts_rating_args', $args );

			/**
			 * Fires this action to enqueue assets.
			 *
			 * @since 1.0.0
			 */
			do_action( 'cherry_trend_posts_assets' );

			$rating_meta = $this->get_post_meta( $args['post_id'] );

			if ( ! $rating_meta ) {
				$rating_meta = array();
			}

			$rating_meta = wp_parse_args( $rating_meta, $this->meta_components );
			$rating_meta = array_map( 'esc_attr', $rating_meta );

			$_votes = sprintf( _n( '%s vote', '%s votes', $rating_meta['votes'], 'cherry-trending-posts' ), $rating_meta['votes'] );
			$votes  = '<span class="cherry-trend-rating__votes">' . $_votes . '</span>';
			$rating = '<span class="cherry-trend-rating__val">' . $rating_meta['rate'] . '</span>';

			$star_rating = $this->get_stars( $rating_meta['rate'], $rating_meta['total'], $args );

			$format = apply_filters(
				'cherry_trend_posts_rating_format',
				$args['format'],
				$star_rating, $votes, $rating, $rating_meta['total'], $args['post_id']
			);

			return sprintf( $format, $star_rating, $votes, $rating, $rating_meta['total'] );
		}

		/**
		 * Get stars markup for star rating.
		 *
		 * @since  1.0.0
		 * @param  float $current Current rating value.
		 * @param  int   $total   Total rating steps count.
		 * @return string
		 */
		public function get_stars( $current = 0, $total = 5, $args ) {
			$star  = '<span class="cherry-trend-stars__item cherry-trend-star%2$s%3$s" data-rate="%1$s">%1$s</span>';
			$stars = '';

			$current = floatval( $current );

			for ( $i = $total; $i >= 1; $i-- ) {

				$active  = '';
				$is_half = '';

				if ( ( $i + 0.2 ) >= $current && $current >= $i ) {
					$active = ' cherry-trend-star--active';
				}

				if ( ( $i - 0.2 ) >= $current && ( $i - 0.8 ) < $current ) {
					$active  = ' cherry-trend-star--active';
					$is_half = ' cherry-trend-star--is-half';
				}

				if ( $i > $current && ( $i - 0.2 ) < $current ) {
					$active  = ' cherry-trend-star--active';
				}

				$stars .= sprintf( $star, $i, $active, $is_half );
			}

			$disabled = ! empty( $args['is_disabled'] ) && true == $args['is_disabled'] ? ' cherry-trend-stars--rate-disabled' : '';

			if ( isset( $_COOKIE[ $this->meta_key ] ) ) {
				$rates = maybe_unserialize( $_COOKIE[ $this->meta_key ] );

				if ( in_array( $args['post_id'], $rates ) ) {
					$disabled = ' cherry-trend-stars--rate-disabled';
				}
			}

			return sprintf(
				'<div class="cherry-trend-rating__stars cherry-trend-stars%3$s" data-post="%2$s" data-format="%4$s">%1$s</div>',
				$stars, $args['post_id'], $disabled, esc_attr( $args['format'] )
			);
		}

		/**
		 * Ajax handler for rating processing.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function ajax_handle() {

			if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'cherry_trend_posts' ) ) {
				die();
			}

			$rate    = ( ! empty( $_REQUEST['rate'] ) ) ? absint( $_REQUEST['rate'] ) : 0;
			$post_id = ( ! empty( $_REQUEST['post'] ) ) ? absint( $_REQUEST['post'] ) : false;
			$format  = ( ! empty( $_REQUEST['format'] ) ) ? esc_attr( $_REQUEST['format'] ) : '';

			if ( ! $post_id ) {
				die();
			}

			$rates = array();

			if ( isset( $_COOKIE[ $this->meta_key ] ) ) {
				$rates = maybe_unserialize( $_COOKIE[ $this->meta_key ] );

				if ( in_array( $post_id, $rates ) ) {
					die();
				}
			}

			$rates[ $post_id ] = $post_id;

			setcookie( $this->meta_key, maybe_serialize( $rates ), time() + CHERRY_TREND_POSTS_CACHE_TIME, COOKIEPATH, COOKIE_DOMAIN );

			$current_rate = $this->get_post_meta( $post_id );

			if ( ! $current_rate ) {
				$current_rate = array();
			}

			$current_rate = wp_parse_args( $current_rate, $this->meta_components );

			$votes      = intval( $current_rate['votes'] );
			$curr_count = floatval( $current_rate['rate'] );
			$total      = intval( $current_rate['total'] );

			$new_count = ( ceil( $curr_count * $votes ) + $rate ) / ( $votes + 1 );

			$new_rate = array(
				'rate'  => round( $new_count , 2 ),
				'total' => 5,
				'votes' => $votes + 1,
			);

			$this->update_post_meta( $post_id, $new_rate );

			echo $this->get_rating_html( array(
				'post_id'     => $post_id,
				'format'      => $format,
				'is_disabled' => true,
			) );

			do_action( 'cherry_trend_posts_rating_ajax_handle', $post_id );

			die();
		}

		/**
		 * Ajax handler for fix cached results.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function ajax_cache_fix() {

			if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'cherry_trend_posts' ) ) {
				die();
			}

			$post_ids = ( ! empty( $_POST['trend_data'] ) ) ? $_POST['trend_data'] : false;
			$formats  = ( ! empty( $_POST['trend_format'] ) ) ? array_map( 'esc_attr', $_POST['trend_format'] ) : false;

			if ( ! is_array( $post_ids ) || ! is_array( $formats ) ) {
				wp_send_json_error();
			}

			$post_ratings = array();

			foreach ( $post_ids as $key => $ids ) {

				$format = sanitize_key( $key );

				if ( ! isset( $formats[ $format ] ) ) {
					continue;
				}

				$_ids    = array_map( 'esc_attr', $ids );
				$_format = esc_attr( $formats[ $format ] );

				$post_ratings[ $format ] = $this->_get_post_ratings_by_id( $_ids, $_format );
			}

			if ( empty( $post_ratings ) ) {
				wp_send_json_error();
			}

			wp_send_json_success( $post_ratings );
		}

		/**
		 * Retrieve a set of rating HTML-result grouped by post ID.
		 *
		 * @since  1.0.0
		 * @param  array  $ids    Posts IDs.
		 * @param  string $format Result string format.
		 * @return array
		 */
		public function _get_post_ratings_by_id( $ids, $format ) {
			$ratings = array();

			foreach ( $ids as $key => $id ) {

				$rating_meta = $this->get_post_meta( $id );

				if ( ! $rating_meta ) {
					$rating_meta = array();
				}

				$rating_meta = wp_parse_args( $rating_meta, $this->meta_components );
				$rating_meta = array_map( 'esc_attr', $rating_meta );

				$_votes = sprintf( _n( '%s vote', '%s votes', $rating_meta['votes'], 'cherry-trending-posts' ), $rating_meta['votes'] );
				$votes  = '<span class="cherry-trend-rating__votes">' . $_votes . '</span>';
				$rating = '<span class="cherry-trend-rating__val">' . $rating_meta['rate'] . '</span>';

				$args = array(
					'post_id' => $id,
					'format'  => $format,
				);
				$star_rating = $this->get_stars( $rating_meta['rate'], $rating_meta['total'], $args );

				$rating = sprintf(
					$format, $star_rating, $votes, $rating, $rating_meta['total']
				);

				$ratings[ $id ] = ! empty( $rating ) ? $rating : '0';
			}

			return $ratings;
		}

		/**
		 * Retrieve a full rating meta.
		 *
		 * @since  1.0.0
		 * @param  int $post_id Post ID.
		 * @return array
		 */
		public function get_post_meta( $post_id ) {
			$meta = array();

			foreach ( $this->meta_components as $key => $value ) {
				$_meta = get_post_meta( $post_id, $this->meta_key . '_' . $key, true );

				if ( ! empty( $_meta ) ) {
					$meta[ $key ] = $_meta;
				}
			}

			return $meta;
		}

		/**
		 * Update a full rating meta.
		 *
		 * @since  1.0.0
		 * @param  int   $post_id  Post ID.
		 * @param  array $new_rate New rating meta values.
		 * @return void
		 */
		public function update_post_meta( $post_id, $new_rate ) {

			if ( empty( $new_rate ) ) {
				return;
			}

			foreach ( (array) $new_rate as $key => $value ) {
				update_post_meta( $post_id, $this->meta_key . '_' . $key, $value );
			}
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

Cherry_Trending_Posts_Callback_Rating::get_instance();
