<?php
/**
 * `Cherry Trending Posts` widget.
 *
 * @package   Cherry_Trending_Posts
 * @author    Template Monster
 * @license   GPL-3.0+
 * @copyright 2012 - 2016, Template Monster
 */

if ( ! class_exists( 'Cherry_Trending_Posts_Widget' ) ) {

	/**
	 * PHP-class for `Cherry Trending Posts` widget.
	 */
	class Cherry_Trending_Posts_Widget extends Cherry_Abstract_Widget {

		/**
		 * A reference to an instance of `Cherry_Trending_Posts_Callback_Views` class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $viewer = null;

		/**
		 * A reference to an instance of `Cherry_Trending_Posts_Callback_Rating` class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $ratinger = null;

		/**
		 * Contain utility module.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $utility = null;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'cherry-trend-widget-list';
			$this->widget_description = esc_html__( 'Displays the most popular/trending posts on your blog.', 'cherry-trending-posts' );
			$this->widget_id          = 'cherry_widget_trending_posts';
			$this->widget_name        = esc_html__( 'Cherry Trending Posts', 'cherry-trending-posts' );
			$this->settings           = array(
				'title'  => array(
					'type'  => 'text',
					'value' => esc_html__( 'Trending Posts', 'cherry-trending-posts' ),
					'label' => esc_html__( 'Title', 'cherry-trending-posts' ),
				),
				'title_length' => array(
					'type'       => 'stepper',
					'value'      => 25,
					'max_value'  => 100,
					'min_value'  => -1,
					'step_value' => 1,
					'label'      => esc_html__( 'Title length in characters (0 &mdash; hide, -1 &mdash; full)', 'cherry-trending-posts' ),
				),
				'filter' => array(
					'type'    => 'radio',
					'value'   => 'comments',
					'options' => array(
						'view' => array(
							'label' => esc_html__( 'Views', 'cherry-trending-posts' ),
						),
						'rating' => array(
							'label' => esc_html__( 'Rating', 'cherry-trending-posts' ),
							'slave' => 'get_rating_type',
						),
						'comments' => array(
							'label' => esc_html__( 'Comments', 'cherry-trending-posts' ),
						),
					),
					'label' => esc_html__( 'Filter by', 'cherry-trending-posts' ),
				),
				'rating_type' => array(
					'type'    => 'select',
					'size'    => 1,
					'value'   => 'most_rated',
					'options' => array(
						'most_rated'    => esc_html__( 'Most Rated', 'cherry-trending-posts' ),
						'highest_rated' => esc_html__( 'Highest Rated', 'cherry-trending-posts' ),
					),
					'multiple' => false,
					'master'   => 'get_rating_type',
					'label'    => esc_html__( 'Select rating type', 'cherry-trending-posts' ),
				),
				'terms_type' => array(
					'type'    => 'radio',
					'value'   => 'category_name',
					'options' => array(
						'category_name' => array(
							'label' => esc_html__( 'Category', 'cherry-trending-posts' ),
							'slave' => 'terms_type_post_category',
						),
						'tag' => array(
							'label' => esc_html__( 'Tag', 'cherry-trending-posts' ),
							'slave' => 'terms_type_post_tag',
						),
					),
					'label' => esc_html__( 'Show from', 'cherry-trending-posts' ),
				),
				'category_name' => array(
					'type'             => 'select',
					'size'             => 1,
					'value'            => '',
					'options_callback' => array( $this, 'get_terms_list', array( 'category', 'slug' ) ),
					'options'          => false,
					'multiple'         => true,
					'master'           => 'terms_type_post_category',
					'label'            => esc_html__( 'Select category', 'cherry-trending-posts' ),
				),
				'tag' => array(
					'type'             => 'select',
					'size'             => 1,
					'value'            => '',
					'options_callback' => array( $this, 'get_terms_list', array( 'post_tag', 'slug' ) ),
					'options'          => false,
					'multiple'         => true,
					'master'           => 'terms_type_post_tag',
					'label'            => esc_html__( 'Select tags', 'cherry-trending-posts' ),
				),
				'posts_per_page' => array(
					'type'       => 'stepper',
					'value'      => 3,
					'max_value'  => 100,
					'min_value'  => -1,
					'step_value' => 1,
					'label'      => esc_html__( 'Number of post to show (Use -1 to show all posts)', 'cherry-trending-posts' ),
				),
				'offset' => array(
					'type'       => 'stepper',
					'value'      => 0,
					'max_value'  => 1000,
					'min_value'  => 0,
					'step_value' => 1,
					'label'      => esc_html__( 'Offset (ignored when `posts_per_page`=>-1 (show all posts) is used)', 'cherry-trending-posts' ),
				),
				'excerpt_length' => array(
					'type'       => 'stepper',
					'value'      => 15,
					'max_value'  => 100,
					'min_value'  => -1,
					'step_value' => 1,
					'label'      => esc_html__( 'Excerpt length in words (0 &mdash; hide, -1 &mdash; all)', 'cherry-trending-posts' ),
				),
				'meta_data' => array(
					'type'  => 'checkbox',
					'value' => array(
						'date'        => 'false',
						'author'      => 'false',
						'view'        => 'false',
						'rating'      => 'false',
						'comments'    => 'false',
						'category'    => 'false',
						'post_tag'    => 'false',
						'more_button' => 'false',
					),
					'options' => array(
						'date'        => esc_html__( 'Date', 'cherry-trending-posts' ),
						'author'      => esc_html__( 'Author', 'cherry-trending-posts' ),
						'view'        => esc_html__( 'View', 'cherry-trending-posts' ),
						'rating'      => esc_html__( 'Rating', 'cherry-trending-posts' ),
						'comments'    => esc_html__( 'Comments', 'cherry-trending-posts' ),
						'category'    => esc_html__( 'Category', 'cherry-trending-posts' ),
						'post_tag'    => esc_html__( 'Tag', 'cherry-trending-posts' ),
						'more_button' => esc_html__( 'Read More', 'cherry-trending-posts' ),
					),
					'label' => esc_html__( 'Display meta', 'cherry-trending-posts' ),
				),
				'button_text' => array(
					'type'  => 'text',
					'value' => esc_html__( 'Read More', 'cherry-trending-posts' ),
					'label' => esc_html__( 'Button text', 'cherry-trending-posts' ),
				),
			);

			$this->viewer   = Cherry_Trending_Posts_Callback_Views::get_instance();
			$this->ratinger = Cherry_Trending_Posts_Callback_Rating::get_instance();
			$this->utility  = Cherry_Trending_Posts::get_instance()->get_core()->modules['cherry-utility']->utility;

			parent::__construct();
		}

		/**
		 * The widget's HTML output.
		 *
		 * @see   WP_Widget::widget()
		 * @since 1.0.0
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		public function widget( $args, $instance ) {

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			$posts_per_page = isset( $instance['posts_per_page'] ) ? esc_attr( $instance['posts_per_page'] ) : $this->settings['posts_per_page']['value'];

			if ( 0 == $posts_per_page ) {
				return;
			}

			$filter      = ! empty( $instance['filter'] ) ? esc_attr( $instance['filter'] ) : $this->settings['filter']['value'];
			$rating_type = ! empty( $instance['rating_type'] ) ? esc_attr( $instance['rating_type'] ) : $this->settings['rating_type']['value'];
			$terms_type  = ! empty( $instance['terms_type'] ) ? esc_attr( $instance['terms_type'] ) : $this->settings['terms_type']['value'];
			$offset      = ! empty( $instance['offset'] ) ? absint( $instance['offset'] ) : $this->settings['offset']['value'];

			// Query arguments.
			$data_args = array(
				'posts_per_page' => $posts_per_page,
				'offset'         => $offset,
				'post_status'    => 'publish',
				'has_password'   => false,
			);

			switch ( $filter ) {
				case 'view':
					$data_args['meta_query'] = array(
						'view_clause' => array(
							'key'     => $this->viewer->meta_key,
							'value'   => 0,
							'compare' => '>',
							'type'    => 'NUMERIC',
						),
					);
					$data_args['orderby'] = 'view_clause';
					break;

				case 'rating':
					$data_args['meta_query'] = array(
						'relation'    => 'AND',
						'rate_clause' => array(
							'key'     => 'cherry_trend_rating_rate',
							'value'   => 0,
							'compare' => '>',
						),
						'votes_clause' => array(
							'key'     => 'cherry_trend_rating_votes',
							'value'   => 0,
							'compare' => '>',
						),
					);
					$data_args['orderby'] = ( 'most_rated' == $rating_type ) ? array( 'votes_clause' => 'DESC' ) : array( 'rate_clause' => 'DESC' );

					break;

				default:
					$data_args['orderby'] = 'comment_count';
					break;
			}

			if ( ! empty( $instance[ $terms_type ] ) ) {
				$data_args[ $terms_type ] = implode( ',', $instance[ $terms_type ] );
			}

			$data_args = apply_filters( 'cherry_trend_posts_args', $data_args, $args, $instance );

			// Prepare data.
			$data        = Cherry_Trending_Posts_Data::get_instance();
			$trend_posts = $data->query( $data_args, 'widget' );

			if ( ! $trend_posts ) {
				return;
			}

			$template = $this->locate_template( 'widget.php', false, false );

			if ( false === $template ) {
				return;
			}

			$title_length   = isset( $instance['title_length'] ) ? esc_attr( $instance['title_length'] ) : $this->settings['title_length']['value'];
			$excerpt_length = isset( $instance['excerpt_length'] ) ? esc_attr( $instance['excerpt_length'] ) : $this->settings['excerpt_length']['value'];
			$excerpt_length = intval( $excerpt_length );

			if ( ! empty( $instance['meta_data'] ) ) {
				$meta_data = $instance['meta_data'];
			} else {
				$meta_data = $this->settings['meta_data']['value'];
			}

			ob_start();

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$title_trimmed_more = apply_filters( 'cherry_trend_posts_title_trimmed_more',
				'&hellip;',
				$args, $instance
			);

			$title_format = ( 0 == $title_length ) ? '' : '<a href="%s">%s</a>';
			$title_format = apply_filters( 'cherry_trend_posts_title_format',
				$title_format,
				$args, $instance
			);

			$image_args = apply_filters( 'cherry_trend_posts_image_args', array(
				'size'        => 'medium',
				'mobile_size' => 'thumbnail',
				'html'        => '<figure class="cherry-trend-post__thumbnail"><a href="%1$s" %2$s><img class="cherry-trend-post__img" src="%3$s" alt="%4$s" %5$s></a></figure>',
				'class'       => 'cherry-trend-post__thumbnail-link',
			), $args, $instance );

			$excerpt_args = array(
				'visible'      => true,
				'length'       => $excerpt_length,
				'content_type' => 'post_content',
			);

			if ( 0 == $excerpt_length ) {
				$excerpt_args['visible'] = false;
			}

			$excerpt_args = apply_filters( 'cherry_trend_posts_excerpt_args', $excerpt_args, $args, $instance );

			$date_args = apply_filters( 'cherry_trend_posts_date_args', array(
				'visible' => $meta_data['date'],
				'class'   => 'cherry-trend-post__meta-date',
				'icon'    => '',
				'prefix'  => '',
			), $args, $instance );

			$comments_args = apply_filters( 'cherry_trend_posts_comments_args', array(
				'visible' => $meta_data['comments'],
				'class'   => 'cherry-trend-post__meta-comments',
				'sufix'   => _n_noop( '%s comment', '%s comments', 'cherry-trending-posts' ),
			), $args, $instance );

			$author_args = apply_filters( 'cherry_trend_posts_author_args', array(
				'visible' => $meta_data['author'],
				'class'   => 'cherry-trend-post__meta-author',
				'icon'    => '',
				'prefix'  => '',
			), $args, $instance );

			$category_args = apply_filters( 'cherry_trend_posts_category_args', array(
				'visible'   => $meta_data['category'],
				'before'    => '<div class="cherry-trend-post__meta-cats">',
				'after'     => '</div>',
				'type'      => 'category',
				'delimiter' => ', ',
				'icon'      => '',
			), $args, $instance );

			$tag_args = apply_filters( 'cherry_trend_posts_tag_args', array(
				'visible'   => $meta_data['post_tag'],
				'before'    => '<div class="cherry-trend-post__meta-tags">',
				'after'     => '</div>',
				'type'      => 'post_tag',
				'delimiter' => ', ',
				'icon'      => '',
			), $args, $instance );

			$button_args = apply_filters( 'cherry_trend_posts_button_args', array(
				'visible' => $meta_data['more_button'],
				'text'    => $this->use_wpml_translate( 'button_text' ),
				'class'   => 'btn cherry-trend-post__btn',
				'icon'    => '',
			), $args, $instance );

			while ( $trend_posts->have_posts() ) : $trend_posts->the_post();

				if ( 'comments' == $filter && 0 == $trend_posts->post->comment_count ) {
					break;
				}

				$image_args = wp_parse_args( array(
					'visible' => has_post_thumbnail(),
				), $image_args );

				$image = $this->utility->media->get_image( $image_args );

				$title = get_the_title();

				if ( $title_length > 0 ) {
					$title = wp_html_excerpt( $title, $title_length, $title_trimmed_more );
				}

				$title    = sprintf( $title_format, esc_url( get_permalink() ), $title );
				$excerpt  = $this->utility->attributes->get_content( $excerpt_args );
				$date     = $this->utility->meta_data->get_date( $date_args );
				$comments = $this->utility->meta_data->get_comment_count( $comments_args );
				$author   = $this->utility->meta_data->get_author( $author_args );
				$category = $this->utility->meta_data->get_terms( $category_args );
				$tag      = $this->utility->meta_data->get_terms( $tag_args );
				$button   = $this->utility->attributes->get_button( $button_args );

				$view = $rating = '';

				if ( isset( $instance['meta_data']['view'] ) && ( 'true' == $instance['meta_data']['view'] ) ) {
					$view = $this->viewer->get_the_views();
				}

				if ( isset( $instance['meta_data']['rating'] ) && ( 'true' == $instance['meta_data']['rating'] ) ) {
					$rating = $this->ratinger->get_the_rating( array(
						'format'      => esc_html__( '%1$s (%2$s)', 'cherry-trending-posts' ),
						'is_disabled' => true,
					) );
				}

				include $template;

			endwhile;

			wp_reset_postdata();

			$this->widget_end( $args );
			$this->reset_widget_data();

			echo $this->cache_widget( $args, ob_get_clean() );
		}

		/**
		 * Retrieve a terms list.
		 *
		 * @since  1.0.0
		 * @param  string $tax - category, post_tag, etc.
		 * @param  string $key - slug or term_id.
		 * @return array
		 */
		public function get_terms_list( $tax = 'category', $key = 'slug' ) {
			$terms     = array();
			$all_terms = ( array ) get_terms( $tax, array( 'hide_empty' => false ) );

			foreach ( $all_terms as $term ) {
				$terms[ $term->$key ] = $term->name;
			}

			return $terms;
		}

		/**
		 * Retrieve a path to the view-tempate.
		 *
		 * @since 1.0.0
		 * @param string $template_name File name.
		 * @return bool|string
		 */
		public function locate_template( $template_name ) {
			$check_dirs = apply_filters( 'cherry_trend_posts_template_dirs', array(
				trailingslashit( get_stylesheet_directory() ) . 'inc/widgets/cherry-trending-posts/',
				trailingslashit( get_stylesheet_directory() ) . 'cherry-trending-posts-',
				trailingslashit( get_template_directory() ) . 'inc/widgets/cherry-trending-posts/',
				trailingslashit( get_template_directory() ) . 'cherry-trending-posts-',
				trailingslashit( CHERRY_TREND_POSTS_DIR ) . 'inc/views/',
			), $template_name );

			foreach ( $check_dirs as $dir ) {
				if ( file_exists( $dir . $template_name ) ) {
					return $dir . $template_name;
				}
			}

			return false;
		}
	}

	add_action( 'widgets_init', 'cherry_trend_posts_register_widget' );
	/**
	 * Register the new widget.
	 *
	 * @since 1.0.0
	 */
	function cherry_trend_posts_register_widget() {
		register_widget( 'Cherry_Trending_Posts_Widget' );
	}
}
