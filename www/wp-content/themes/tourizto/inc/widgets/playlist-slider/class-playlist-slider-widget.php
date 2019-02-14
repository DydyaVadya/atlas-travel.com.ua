<?php
/*
Widget Name: Playlist Slider Widget
Description:
Settings:
*/

/**
 * @package Tourizto
 */

if ( ! class_exists( 'Tourizto_Playlist_Slider_Widget' ) ) {

	/**
	 * Class Tourizto_Playlist_Slider_Widget.
	 */
	class Tourizto_Playlist_Slider_Widget extends Cherry_Abstract_Widget {

		private $posts           = null;
		public $instance         = null;
		private $layout_settings = array();

		/**
		 * Contain utility module from Cherry framework
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $utility = null;

		/**
		 * Constructor.
		 *
		 * @since	1.0.0
		 */
		public function __construct() {
			$this->widget_name        = esc_html__( 'Playlist Slider', 'tourizto' );
			$this->widget_description = esc_html__( 'Display playlist slider on your site.', 'tourizto' );
			$this->widget_id          = apply_filters( 'tourizto_playlist_slider_widget_ID', 'tourizto_widget_playlist_slider' );
			$this->widget_cssclass    = apply_filters( 'tourizto_playlist_slider_widget_cssclass', 'widget-playlist-slider' );
			$this->utility            = tourizto_utility()->utility;
			$this->settings = array(
				'title' => array(
					'type'  => 'text',
					'value' => esc_html__( 'Playlist Slider', 'tourizto' ),
					'label' => esc_html__( 'Title', 'tourizto' ),
				),
				'terms_type' => array(
					'type'    => 'radio',
					'value'   => 'category_name',
					'options' => array(
						'category_name' => array(
							'label' => esc_html__( 'Category', 'tourizto' ),
							'slave' => 'terms_type_post_category',
						),
						'tag' => array(
							'label' => esc_html__( 'Tag', 'tourizto' ),
							'slave' => 'terms_type_post_tag',
						),
					),
					'label' => esc_html__( 'Choose taxonomy type', 'tourizto' ),
				),
				'category_name' => array(
					'type'             => 'select',
					'size'             => 1,
					'value'            => '',
					'options_callback' => array( $this->utility->satellite, 'get_terms_array', array( 'category', 'slug' ) ),
					'options'          => false,
					'label'            => esc_html__( 'Select category', 'tourizto' ),
					'multiple'         => true,
					'placeholder'      => esc_html__( 'Select category', 'tourizto' ),
					'master'           => 'terms_type_post_category',
				),
				'tag' => array(
					'type'             => 'select',
					'size'             => 1,
					'value'            => '',
					'options_callback' => array( $this->utility->satellite, 'get_terms_array', array( 'post_tag', 'slug' ) ),
					'options'          => false,
					'label'            => esc_html__( 'Select tags', 'tourizto' ),
					'multiple'         => true,
					'placeholder'      => esc_html__( 'Select tags', 'tourizto' ),
					'master'           => 'terms_type_post_tag',
				),
				'posts_per_page' => array(
					'type'      => 'stepper',
					'value'     => 10,
					'max_value' => 50,
					'min_value' => 0,
					'label'     => esc_html__( 'Posts count ( Set 0 to show all. )', 'tourizto' ),
				),
				'width' => array(
					'type'  => 'text',
					'value' => '100%',
					'label' => esc_html__( 'Slider width ( px, %, rem )', 'tourizto' ),
				),
				'height' => array(
					'type' => 'text',
					'value' => '525',
					'label' => esc_html__( 'Slider height ( px, rem )', 'tourizto' ),
				),
				'thumbnail_controls' => array(
					'type'    => 'select',
					'size'    => 1,
					'value'   => true,
					'options' => array(
						false => esc_html__( 'Hide', 'tourizto' ),
						true  => esc_html__( 'Buttons', 'tourizto' ),
					),
					'label'       => esc_html__( 'Select Thumbnail Controls', 'tourizto' ),
					'placeholder' => esc_html__( 'Select Thumbnail Controls', 'tourizto' ),
				),
				'slider_controls' => array(
					'type'    => 'select',
					'size'    => 1,
					'value'   => true,
					'options' => array(
						false => esc_html__( 'Hide', 'tourizto' ),
						true  => esc_html__( 'Buttons', 'tourizto' ),
					),
					'label'       => esc_html__( 'Select Slider Controls', 'tourizto' ),
					'placeholder' => esc_html__( 'Select Thumbnail Controls', 'tourizto' ),
				),
				'title_length' => array(
					'type'       => 'stepper',
					'value'      => '10',
					'max_value'  => '500',
					'min_value'  => '0',
					'step_value' => '1',
					'label'      => esc_html__( 'Title words length ( Set 0 to hide title. )', 'tourizto' ),
				),
				'thumb_title_length' => array(
					'type'       => 'stepper',
					'value'      => '5',
					'max_value'  => '500',
					'min_value'  => '0',
					'step_value' => '1',
					'label'      => esc_html__( 'Thumbnail title words length ( Set 0 to hide title. )', 'tourizto' ),
				),
				'mate_data' => array(
					'type'  => 'checkbox',
					'value' => array(
						'date'     => 'true',
						'author'   => 'false',
						'comments' => 'false',
						'category' => 'false',
						'tag'      => 'false',
					),
					'options' => array(
						'date'     => esc_html__( 'Date', 'tourizto' ),
						'author'   => esc_html__( 'Author', 'tourizto' ),
						'comments' => esc_html__( 'Comment count', 'tourizto' ),
						'category' => esc_html__( 'Category', 'tourizto' ),
						'post_tag' => esc_html__( 'Tag', 'tourizto' ),
					),
					'label' => esc_html__( 'Display post meta data', 'tourizto' ),
				),
				'thumb_mate_data' => array(
					'type'  => 'checkbox',
					'value' => array(
						'date'     => 'true',
						'author'   => 'false',
						'comments' => 'false',
					),
					'options' => array(
						'date'     => esc_html__( 'Date', 'tourizto' ),
						'author'   => esc_html__( 'Author', 'tourizto' ),
						'comments' => esc_html__( 'Comment count', 'tourizto' ),
					),
					'label' => esc_html__( 'Display thumbnail post meta data', 'tourizto' ),
				),
			);

			parent::__construct();

			// default
			$widget_area_settings_0 = array(
				'thumbnailWidth'     => 460,
				'thumbnailHeight'    => 'auto',
				'thumbnailsPosition' => 'right',
				'breakpoints'        => array(
					'1199' => array(
						'thumbnailWidth'     => 140,
						'thumbnailHeight'    => 165,
						'thumbnailsPosition' => 'bottom',
					),
					'991' => array(
						'thumbnailWidth'     => 165,
						'thumbnailHeight'    => 165,
						'thumbnailsPosition' => 'bottom',
					),
					'767' => array(
						'thumbnailWidth'     => 172,
						'thumbnailHeight'    => 172,
						'thumbnailsPosition' => 'bottom',
					),
					'543' => array(
						'thumbnailWidth'     => 156,
						'thumbnailHeight'    => 156,
						'thumbnailsPosition' => 'bottom',
					),
				),
			);

			// sidebar
			$widget_area_settings_1 = array(
				'thumbnailWidth'     => 105,
				'thumbnailHeight'    => 105,
				'thumbnailsPosition' => 'bottom',
				'breakpoints'        => array(
					'1199' => array(
						'thumbnailWidth'     => 149,
						'thumbnailHeight'    => 149,
						'thumbnailsPosition' => 'bottom',
					),
					'991' => array(
						'thumbnailWidth'     => 130,
						'thumbnailHeight'    => 130,
						'thumbnailsPosition' => 'bottom',
					),
					'767' => array(
						'thumbnailWidth'     => 172,
						'thumbnailHeight'    => 172,
						'thumbnailsPosition' => 'bottom',
					),
					'543' => array(
						'thumbnailWidth'     => 156,
						'thumbnailHeight'    => 156,
						'thumbnailsPosition' => 'bottom',
					),
				),
			);

			// before-loop-area, after-loop-area
			$widget_area_settings_2 = array(
				'thumbnailWidth'     => 176,
				'thumbnailHeight'    => 176,
				'thumbnailsPosition' => 'bottom',
				'breakpoints'        => array(
					'1199' => array(
						'thumbnailWidth'     => 125,
						'thumbnailHeight'    => 125,
						'thumbnailsPosition' => 'bottom',
					),
					'991' => array(
						'thumbnailWidth'     => 153,
						'thumbnailHeight'    => 153,
						'thumbnailsPosition' => 'bottom',
					),
					'767' => array(
						'thumbnailWidth'     => 172,
						'thumbnailHeight'    => 172,
						'thumbnailsPosition' => 'bottom',
					),
					'543' => array(
						'thumbnailWidth'     => 156,
						'thumbnailHeight'    => 156,
						'thumbnailsPosition' => 'bottom',
					),
				),
			);

			// footer-area
			$widget_area_settings_3 = array(
				'thumbnailWidth'     => 121,
				'thumbnailHeight'    => 121,
				'thumbnailsPosition' => 'bottom',
				'breakpoints'        => array(
					'1199' => array(
						'thumbnailWidth'     => 140,
						'thumbnailHeight'    => 140,
						'thumbnailsPosition' => 'bottom',
					),
					'991' => array(
						'thumbnailWidth'     => 103,
						'thumbnailHeight'    => 103,
						'thumbnailsPosition' => 'bottom',
					),
					'767' => array(
						'thumbnailWidth'     => 80,
						'thumbnailHeight'    => 80,
						'thumbnailsPosition' => 'bottom',
					),
					'543' => array(
						'thumbnailWidth'     => 163,
						'thumbnailHeight'    => 163,
						'thumbnailsPosition' => 'bottom',
					),
				),
			);

			$this->layout_settings = apply_filters( 'tourizto_playlist_slider_size', array(
					'default'           => $widget_area_settings_0,
					'sidebar'           => $widget_area_settings_1,
					'before-loop-area'  => $widget_area_settings_2,
					'after-loop-area'   => $widget_area_settings_2,
					'footer-area'       => $widget_area_settings_3,
				)
			);

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 9 );
		}

		/**
		 * Echo thumbnail view.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function the_thumbnail_view() {
			$thumbnails_view_dir = tourizto_get_locate_template( 'inc/widgets/playlist-slider/views/playlist-thumbnails-view.php' );

			if ( ! $thumbnails_view_dir ) {
				return;
			}

			global $post;

			foreach ( $this->posts as $post ) {

				setup_postdata( $post );

				$title = $this->utility->attributes->get_title( array(
					'visible'      => $this->instance['thumb_title_length'] > 0 ? true : false,
					'class'        => 'sp-thumbnail-title',
					'length'       => (int) $this->instance['thumb_title_length'],
					'trimmed_type' => 'word',
					'ending'       => '&hellip;',
					'html'         => '<p %1$s>%4$s</p>',
				), 'post', $post );

				$image = '<div class="playlist-img playlist--thumbnail"></div>';

				$thumb_css_selector = sprintf( '.sp-thumbnail.post-%s .playlist-img.playlist--thumbnail', get_the_ID() );
				$thumb_img_url      = $this->utility->media->get_image( array(
					'size'              => 'tourizto-slider-thumb',
					'mobile_size'       => 'tourizto-slider-thumb',
					'html'              => '%3$s',
					'placeholder_title' => get_bloginfo( 'name' ),
				), 'post', $post );

				tourizto_theme()->dynamic_css->add_style(
					$this->add_selector( $thumb_css_selector ),
					array( 'background-image' => 'url(' . esc_url( $thumb_img_url ) . ')' )
				);

				$date = $this->utility->meta_data->get_date( array(
					'visible' => $this->instance['thumb_mate_data']['date'],
					'html'    => '<span class="post__date">%1$s<time datetime="%5$s">%6$s%7$s</time></span>',
				) );

				$author = $this->utility->meta_data->get_author( array(
					'visible' => $this->instance['thumb_mate_data']['author'],
					'prefix'  => esc_html__( 'by ', 'tourizto' ),
					'html'    => '<span class="posted-by">%1$s%5$s%6$s</span>',
				) );

				$comments = $this->utility->meta_data->get_comment_count( array(
					'visible' => $this->instance['thumb_mate_data']['comments'],
					'html'    => '<span class="post__comments">%1$s%5$s%6$s</span>',
					'icon'    => '<i class="nc-icon-outline ui-2_chat-round"></i>',
				) );

				include $thumbnails_view_dir;
			}

			wp_reset_postdata();
		}

		/**
		 * Echo slider view.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function the_slides_view() {
			$slides_view_dir = tourizto_get_locate_template( 'inc/widgets/playlist-slider/views/playlist-slides-view.php' );

			if ( ! $slides_view_dir ) {
				return;
			}

			global $post;

			foreach ( $this->posts as $post) {
				setup_postdata( $post );

				$title = '';

				if ( $this->instance['title_length'] > 0 ) {
					$title = $this->utility->attributes->get_title( array(
						'length' => (int) $this->instance['title_length'],
						'class'  => 'title',
						'html'   => '<h4 %1$s><a href="%2$s" %3$s>%4$s</a></h4>',
					) );
				}

				$date = $this->utility->meta_data->get_date( array(
					'visible' => $this->instance['mate_data']['date'],
					'html'    => '<span class="post__date">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s">%6$s%7$s</time></a></span>',
					'class'   => 'post__date-link',
				) );

				$author = $this->utility->meta_data->get_author( array(
					'visible' => $this->instance['mate_data']['author'],
					'class'   => 'posted-by__author',
					'prefix'  => esc_html__( 'by ', 'tourizto' ),
					'html'    => '<span class="posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
				) );

				$category = $this->utility->meta_data->get_terms( array(
					'type'    => 'category',
					'class'   => 'post_term',
					'visible' => $this->instance['mate_data']['category'],
					'prefix'  => esc_html__( 'in ', 'tourizto' ),
					'before'  => '<span class="post__cats">',
					'after'   => '</span>',
				) );

				$tag = $this->utility->meta_data->get_terms( array(
					'type'      => 'post_tag',
					'class'     => 'post_term',
					'delimiter' => ', ',
					'prefix'    => esc_html__( 'Tags: ', 'tourizto' ),
					'visible'   => $this->instance['mate_data']['post_tag'],
					'before'    => '<span class="post__tags">',
					'after'     => '</span>',
				) );

				$comments = $this->utility->meta_data->get_comment_count( array(
					'visible' => $this->instance['mate_data']['comments'],
					'html'    => '<span class="post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
					'icon'    => '<i class="nc-icon-outline ui-2_chat-round"></i>',
				) );

				$permalink   = $this->utility->attributes->get_post_permalink();
				$post_format = get_post_format( $post->ID ) ? get_post_format( $post->ID ) : 'standard';
				$is_invert   = has_post_thumbnail( $post->ID ) ? 'has-thumbnail invert' : 'no-thumbnail invert';
				$visible_content  = ( $this->instance['title_length'] > 0
									|| 'true' === $this->instance['mate_data']['date']
									|| 'true' === $this->instance['mate_data']['comments']
									|| 'true' === $this->instance['mate_data']['post_tag']
									|| 'true' === $this->instance['mate_data']['category']
									|| 'true' === $this->instance['mate_data']['author'] ) ? 'content-visible' : 'content-disable';

				switch ( $post_format ) {

					case 'video':
						$slide = $this->utility->media->get_video( array(
							'size'        => 'tourizto-thumb-xl',
							'mobile_size' => 'tourizto-thumb-l',
						) );
					break;

					default:
						$slide = '<div class="playlist-img playlist--slide"></div>';

						$slide_css_selector = sprintf( '.sp-slide.post-%s .playlist-img.playlist--slide', get_the_ID() );
						$slide_img_url      = $this->utility->media->get_image( array(
							'size'              => 'tourizto-thumb-xl',
							'mobile_size'       => 'tourizto-thumb-l',
							'html'              => '%3$s',
							'placeholder_title' => get_bloginfo( 'name' ),
						) );

						tourizto_theme()->dynamic_css->add_style(
							$this->add_selector( $slide_css_selector ),
							array( 'background-image' => 'url(' . esc_url( $slide_img_url ) . ')' )
						);
					break;

				}

				include $slides_view_dir;
			}

			wp_reset_postdata();
		}

		/**
		 * Widget function.
		 *
		 * @see WP_Widget
		 *
		 * @since 1.0.0
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {

			if ( empty( $instance['terms_type'] ) ) {
				return;
			}

			$terms_type = $instance['terms_type'];

			if ( ! isset( $instance[ $terms_type ] ) || empty( $instance[ $terms_type ] ) ) {
				return;
			}

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			ob_start();

			$this->instance = $instance;

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$height             = $instance['height'];
			$width              = $instance['width'];
			$posts_per_page     = $instance['posts_per_page'];
			$slider_controls    = $instance['slider_controls'];
			$thumbnail_controls = $instance['thumbnail_controls'];

			if ( ! isset( $instance[ $terms_type ] ) || ! $instance[ $terms_type ] ) {
				return;
			}

			$args['id'] = ( isset( $args['id'] ) ) ? ( isset( $args['id'] ) ) : 'custom-widget-area';

			$layout_settings = isset( $this->layout_settings[ $args['id'] ] ) ? $this->layout_settings[ $args['id'] ] : $this->layout_settings[ 'default' ] ;
			$layout_settings['breakpoints']['991']['height'] = ( int ) $height * 0.75;
			$layout_settings['breakpoints']['767']['height'] = ( int ) $height * 0.5;
			$layout_settings['breakpoints']['543']['height'] = ( int ) $height * 0.5;

			$posts_per_page = ( '0' === $posts_per_page ) ? -1 : ( int ) $posts_per_page;
			$post_args      = array(
				'post_type'   => 'post',
				'numberposts' => $posts_per_page,
			);
			$post_args[ $terms_type ] = implode( ',', $instance[ $terms_type ] );

			$this->posts = get_posts( $post_args );

			if ( $this->posts ) {
				$slider_settings = array(
					'width'              => $width,
					'height'             => $height,
					'arrows'             => ( boolean ) $slider_controls,
					'buttons'            => apply_filters( 'tourizto_playlist_buttons', false ),
					'thumbnailArrows'    => ( boolean ) $thumbnail_controls,
					'thumbnailsPosition' => $layout_settings['thumbnailsPosition'],
					'thumbnailWidth'     => $layout_settings['thumbnailWidth'],
					'thumbnailHeight'    => $layout_settings['thumbnailHeight'],
					'breakpoints'        => json_encode( $layout_settings['breakpoints'] ),
				);
				$slider_settings = json_encode( $slider_settings );

				$holder_view_dir = tourizto_get_locate_template( 'inc/widgets/playlist-slider/views/playlist-holder-view.php' );

				if ( $holder_view_dir ) {

					echo '<div class="playlist-slider slider-pro" data-settings=\'' . $slider_settings . '\'>';
						include $holder_view_dir;
					echo '</div>';
				}
			}

			$this->widget_end( $args );
			$this->reset_widget_data();

			echo $this->cache_widget( $args, ob_get_clean() );
		}

		/**
		 * Enqueue javascript and stylesheet.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_assets() {
			if ( is_active_widget( false, false, $this->id_base, true ) ) {
				wp_enqueue_script( 'jquery-slider-pro' );
				wp_enqueue_style( 'jquery-slider-pro' );
			}
		}
	}
}

add_action( 'widgets_init', 'tourizto_register_playlist_slider_widget' );
/**
 * Register playlist-slider widget.
 */
function tourizto_register_playlist_slider_widget() {
	register_widget( 'Tourizto_Playlist_Slider_Widget' );
}
