<?php
/**
 * Views post meta.
 *
 * @package   Cherry_Trending_Posts
 * @author    Template Monster
 * @license   GPL-3.0+
 * @copyright 2012 - 2016, Template Monster
 */

// If class 'Cherry_Trending_Posts_Callback_Views' not exists.
if ( ! class_exists( 'Cherry_Trending_Posts_Callback_Views' ) ) {

	/**
	 * Add rating system and callback.
	 *
	 * @since 1.0.0
	 */
	class Cherry_Trending_Posts_Callback_Views {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Meta field name for rating storing.
		 *
		 * @since 1.0.0
		 * @var   string
		 */
		public $meta_key = 'cherry_trend_views';

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'wp', array( $this, 'save_views' ) );

			// Hooks a function on to display/return post `Views` meta.
			add_action( 'cherry_trend_posts_display_views', array( $this, 'the_views' ) );
			add_filter( 'cherry_trend_posts_return_views',  array( $this, 'get_the_views' ) );

			// Handlers for AJAX request.
			add_action( 'wp_ajax_cherry_trend_posts_check_views',        array( $this, 'ajax_cache_fix' ) );
			add_action( 'wp_ajax_nopriv_cherry_trend_posts_check_views', array( $this, 'ajax_cache_fix' ) );
		}

		/**
		 * Get clean views output.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_the_views() {
			global $post;

			$result = $this->get_views_html( $post->ID );

			return '<div class="cherry-trend-views">' . $result . '</div>';
		}

		/**
		 * Display views.
		 *
		 * @since 1.0.0
		 */
		public function the_views() {
			echo $this->get_the_views();
		}

		/**
		 * Get post View HTML.
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_views_html( $post_id ) {

			/**
			 * Fires this action to enqueue assets.
			 *
			 * @since 1.0.0
			 */
			do_action( 'cherry_trend_posts_assets' );

			$views = get_post_meta( $post_id, $this->meta_key, true );
			$views = absint( $views );

			$format = apply_filters( 'cherry_trend_posts_views_format',
				'<span class="cherry-trend-views__count" data-id="%2$s">%1$s</span>',
				$views,
				$post_id
			);

			return sprintf( $format, $views, $post_id );
		}

		/**
		 * Handle view.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function save_views() {
			global $post;

			if ( ! is_object( $post ) ) {
				return;
			}

			if ( ! is_singular() ) {
				return;
			}

			$views = array();

			if ( isset( $_COOKIE[ $this->meta_key ] ) ) {
				$views = maybe_unserialize( $_COOKIE[ $this->meta_key ] );

				if ( isset( $views[ $post->ID ] ) ) {
					return;
				}
			}

			$views[ $post->ID ] = $post->ID;

			setcookie( $this->meta_key, maybe_serialize( $views ), time() + CHERRY_TREND_POSTS_CACHE_TIME, COOKIEPATH, COOKIE_DOMAIN );

			$views = get_post_meta( $post->ID, $this->meta_key, true );
			$views = absint( $views );
			$views++;
			update_post_meta( $post->ID, $this->meta_key, $views );
		}

		/**
		 * Ajax handler for fix cached results.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function ajax_cache_fix() {
			$post_ids = ( ! empty( $_POST['trend_data'] ) ) ? $_POST['trend_data'] : false;
			$post_ids = array_map( 'esc_attr', $post_ids );

			if ( ! is_array( $post_ids ) ) {
				wp_send_json_error();
			}

			$post_views = array();

			foreach ( $post_ids as $key => $id ) {
				$view              = get_post_meta( $id, $this->meta_key, true );
				$post_views[ $id ] = ! empty( $view ) ? $view : '0';
			}

			if ( empty( $post_views ) ) {
				wp_send_json_error();
			}

			wp_send_json_success( $post_views );
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

Cherry_Trending_Posts_Callback_Views::get_instance();
