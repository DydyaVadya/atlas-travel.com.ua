<?php
/*
Widget Name: News Smart Box Widget
Description:
Settings:
*/

/**
 * @package Tourizto
 */

/**
 * Class Tourizto_News_Smart_Box_Widget
 */
class Tourizto_News_Smart_Box_Widget extends Cherry_Abstract_Widget {

	/**
	 * Contain utility module from Cherry framework
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private $utility = null;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'widget-news-smart-box';
		$this->widget_id          = 'tourizto_widget_news_smart_box';
		$this->widget_name        = esc_html__( 'News Smart Box', 'tourizto' );
		$this->widget_description = esc_html__( 'News Smart Box', 'tourizto' );
		$this->utility            = tourizto_utility()->utility;
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'value' => esc_html__( 'News Smart Box', 'tourizto' ),
				'label' => esc_html__( 'Title', 'tourizto' ),
			),
			'layout_type' => array(
				'type'    => 'radio',
				'value'   => 'layout_type_1',
				'options' => array(
					'layout_type_1' => array(
						'label'   => esc_html__( 'Layout type 1', 'tourizto' ),
						'img_src' => TOURIZTO_THEME_URI . '/assets/images/admin/widgets/news-smart-box/layout-1.svg',
					),
					'layout_type_2' => array(
						'label'   => esc_html__( 'Layout type 2', 'tourizto' ),
						'img_src' => TOURIZTO_THEME_URI . '/assets/images/admin/widgets/news-smart-box/layout-2.svg',
					),
					'layout_type_3' => array(
						'label'   => esc_html__( 'Layout type 3', 'tourizto' ),
						'img_src' => TOURIZTO_THEME_URI . '/assets/images/admin/widgets/news-smart-box/layout-3.svg',
					),
				),
				'label' => esc_html__( 'Choose layout type', 'tourizto' ),
			),
			'terms_type' => array(
				'type'    => 'radio',
				'value'   => 'category',
				'options' => array(
					'category' => array(
						'label' => esc_html__( 'Category', 'tourizto' ),
						'slave' => 'terms_type_category',
					),
					'post_tag' => array(
						'label' => esc_html__( 'Tag', 'tourizto' ),
						'slave' => 'terms_type_post_tag',
					),
				),
				'label' => esc_html__( 'Choose taxonomy type', 'tourizto' ),
			),
			'current_category' => array(
				'type'             => 'select',
				'value'            => '',
				'options_callback' => array( $this, 'get_terms_list',
					array( 'category', 'slug', ) ),
				'options'          => false,
				'label'            => esc_html__( 'Select category', 'tourizto' ),
				'placeholder'      => esc_html__( 'Select category', 'tourizto' ),
				'master'           => 'terms_type_category',
			),
			'categories' => array(
				'type'             => 'select',
				'size'             => 1,
				'value'            => '',
				'options_callback' => array( $this, 'get_terms_list', array(
					'category', 'slug', ) ),
				'options'          => false,
				'label'            => esc_html__( 'Select secondary categories', 'tourizto' ),
				'multiple'         => true,
				'placeholder'      => esc_html__( 'Select categories', 'tourizto' ),
				'master'           => 'terms_type_category',
			),
			'current_tag' => array(
				'type'             => 'select',
				'value'            => '',
				'options_callback' => array( $this, 'get_terms_list', array(
					'post_tag', 'slug', ) ),
				'options'          => false,
				'label'            => esc_html__( 'Select tag', 'tourizto' ),
				'placeholder'      => esc_html__( 'Select tag', 'tourizto' ),
				'master'           => 'terms_type_post_tag',
			),
			'tags' => array(
				'type'             => 'select',
				'size'             => 1,
				'value'            => '',
				'options_callback' => array( $this, 'get_terms_list', array(
					'post_tag', 'slug', ) ),
				'options'          => false,
				'label'            => esc_html__( 'Select secondary tags', 'tourizto' ),
				'multiple'         => true,
				'placeholder'      => esc_html__( 'Select tags', 'tourizto' ),
				'master'           => 'terms_type_post_tag',
			),
			'posts_per_page' => array(
				'type'      => 'stepper',
				'value'     => 6,
				'max_value' => 20,
				'min_value' => 1,
				'label'     => esc_html__( 'Posts count', 'tourizto' ),
			),
			'trim_title_chars' => array(
				'type'       => 'slider',
				'value'      => 25,
				'max_value'  => 50,
				'min_value'  => 0,
				'step_value' => 1,
				'label'      => esc_html__( 'Title chars trimmed count', 'tourizto' ),
			),
			'trim_content_words' => array(
				'type'       => 'slider',
				'value'      => 15,
				'max_value'  => 55,
				'min_value'  => 0,
				'step_value' => 1,
				'label'      => esc_html__( 'Content words trimmed count', 'tourizto' ),
			),
			'current_title_visibility' => array(
				'type'  => 'switcher',
				'value' => 'true',
				'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
				'label' => esc_html__( 'Display current navigation title', 'tourizto' ),
			),
			'date_visibility' => array(
				'type'  => 'switcher',
				'value' => 'true',
				'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
				'label' => esc_html__( 'Display date', 'tourizto' ),
			),
			'author_visibility' => array(
				'type'  => 'switcher',
				'value' => 'true',
				'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
				'label' => esc_html__( 'Display author', 'tourizto' ),
			),
			'comment_visibility' => array(
				'type'  => 'switcher',
				'value' => 'true',
				'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
				'label' => esc_html__( 'Display comments', 'tourizto' ),
			),
			'category_visibility' => array(
				'type'  => 'switcher',
				'value' => 'true',
				'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
				'label' => esc_html__( 'Display categories', 'tourizto' ),
			),
			'tags_visibility' => array(
				'type'  => 'switcher',
				'value' => 'true',
				'style' => ( wp_is_mobile() ) ? 'normal' : 'small',
				'label' => esc_html__( 'Display tags', 'tourizto' ),
			),
			'more_button_visibility' => array(
				'type'   => 'switcher',
				'value'  => 'true',
				'style'  => ( wp_is_mobile() ) ? 'normal' : 'small',
				'label'  => esc_html__( 'Display more button', 'tourizto' ),
				'toggle' => array(
					'true_toggle'  => esc_html__( 'On', 'tourizto' ),
					'false_toggle' => esc_html__( 'Off', 'tourizto' ),
					'true_slave'   => 'more_button_attr',
					'false_slave'  => '',
				),
			),
			'more_button_text' => array(
				'type'   => 'text',
				'value'  => esc_html__( 'Read more', 'tourizto' ),
				'label'  => esc_html__( 'More button text', 'tourizto' ),
				'master' => 'more_button_attr',
			),
		);

		parent::__construct();

		add_filter( 'tourizto_theme_script_variables', array( $this, 'add_new_smart_box_nonce_var' ) );

		add_action( 'wp_ajax_new_smart_box_instance', array( $this, 'new_smart_box_instance' ) );
		add_action( 'wp_ajax_nopriv_new_smart_box_instance', array( $this, 'new_smart_box_instance' ) );
	}

	/**
	 * Add widget nonce variables.
	 *
	 * @param array $vars Variables.
	 *
	 * @return array
	 */
	public function add_new_smart_box_nonce_var( $vars = array() ) {
		$vars['new_smart_box_nonce'] = wp_create_nonce( 'tourizto-new-smart-box-nonce' );

		return $vars;
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

		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		$loop_template = tourizto_get_locate_template( 'inc/widgets/news-smart-box/views/loop-view.php' );

		if ( '' === $loop_template ) {
			return;
		}

		$this->setup_widget_data( $args, $instance );
		$this->widget_start( $args, $instance );

		$cats_array = ( isset( $this->instance['categories'] ) ) ? $this->instance['categories'] : array();
		$tags_array = ( isset( $this->instance['tags'] ) ) ? $this->instance['tags'] : array();

		switch ( $this->instance['terms_type'] ) {
			case 'category':
				$current_term_slug   = $this->instance['current_category'];
				$alt_terms_slug_list = $cats_array;
				break;

			case 'post_tag':
				$current_term_slug   = $this->instance['current_tag'];
				$alt_terms_slug_list = $tags_array;
				break;
		}

		$instance          = uniqid();
		$instance_settings = json_encode( $this->instance );

		$data_attr_line = 'class="news-smart-box__instance ' . esc_attr( $this->instance['layout_type'] ) . '"';
		$data_attr_line .= ' data-uniq-id="news-smart-box-' . esc_attr( $instance ) . '"';
		$data_attr_line .= " data-instance-settings='" . $instance_settings . "'";

		ob_start();
		include $loop_template;

		$this->widget_end( $args );
		$this->reset_widget_data();
		wp_reset_postdata();

		echo $this->cache_widget( $args, ob_get_clean() );
	}

	/**
	 * Get new instance.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function get_instance( $current_term_slug ) {
		$tax_query           = array();
		$alt_terms_slug_list = array();

		$posts_query = $this->get_query_items( array(
			'posts_per_page' => $this->instance['posts_per_page'],
			'tax_query'      => array(
				array(
					'taxonomy' => $this->instance['terms_type'],
					'field'    => 'slug',
					'terms'    => array( $current_term_slug ),
				),
			),
		) );

		ob_start();

		if ( $posts_query ) {
			echo $this->get_smart_box_loop( $posts_query );
			echo '<div class="clear"></div>';
		}

		return ob_get_clean();
	}

	/**
	 * Retrieve a data (posts) for widget.
	 *
	 * @since  1.0.0
	 * @param  array|string $args Arguments to be passed to the query.
	 * @return array|bool         Array if true, boolean if false.
	 */
	public function get_query_items( $query_args = array() ) {

		$defaults_query_args = apply_filters( 'tourizto_news_smart_box_default_query_args', array(
			'post_type'      => 'post',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'posts_per_page' => 10,
			'tax_query'      => array(),
		) );

		$query_args = wp_parse_args( $query_args, $defaults_query_args );

		// The Query.
		$posts_query = new WP_Query( $query_args );

		if ( ! is_wp_error( $posts_query ) ) {
			return $posts_query;
		} else {
			return false;
		}
	}

	/**
	 * Retrieve a widget data items.
	 *
	 * @since  1.0.0
	 * @param  array  $posts_query List of WP_Post objects.
	 * @return string
	 */
	public function get_smart_box_loop( $posts_query ) {
		$full_view_template = tourizto_get_locate_template( 'inc/widgets/news-smart-box/views/full-view.php' );
		$mini_view_template = tourizto_get_locate_template( 'inc/widgets/news-smart-box/views/mini-view.php' );

		if ( in_array( '', array( $full_view_template, $mini_view_template ), true ) ) {
			return;
		}

		if ( ! $posts_query->have_posts() ) {
			return '<div class="container"><h4>' . esc_html__( 'Posts not found', 'tourizto' ) . '</h4></div>';
		}

		$title_length   = (int) $this->instance['trim_title_chars'];
		$excerpt_length = (int) $this->instance['trim_content_words'];
		$title          = $excerpt = '';

		ob_start();

		while ( $posts_query->have_posts() ) : $posts_query->the_post();

			$date = $this->utility->meta_data->get_date( array(
				'visible' => $this->instance['date_visibility'],
				'html'    => '<span class="news-smart-box__item-date post__date">%1$s<a href="%2$s" %3$s %4$s ><time datetime="%5$s">%6$s%7$s</time></a></span>',
				'prefix'  => '',
			) );

			$author = $this->utility->meta_data->get_author(array(
				'visible' => $this->instance['author_visibility'],
				'prefix'  => esc_html__( 'by ', 'tourizto' ),
				'html'    => '<span class="news-smart-box__item-author posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
			) );

			$comments = $this->utility->meta_data->get_comment_count(array(
				'visible' => $this->instance['comment_visibility'],
				'icon'    => '<i class="nc-icon-mini ui-2_chat-round-content"></i>',
				'sufix'   => '%s',
				'html'    => '<span class="news-smart-box__item-comments post__comments">%1$s<a href="%2$s" %3$s %4$s>%5$s%6$s</a></span>',
			) );

			if ( 0 !== $excerpt_length ) {

				$excerpt = $this->utility->attributes->get_content( array(
					'length'  => $excerpt_length,
					'class'   => 'news-smart-box__item-excerpt',
				) );
			}

			$terms = $this->utility->meta_data->get_terms( array(
				'type'   => $this->instance['terms_type'],
				'before' => '<div class="news-smart-box__item-terms post__terms">',
				'after'  => '</div>',
			) );

			$category = $this->utility->meta_data->get_terms( array(
				'type'    => 'category',
				'visible' => $this->instance['category_visibility'],
				'before'  => '<span class="news-smart-box__item-cats post__cats">',
				'after'   => '</span>',
				'prefix'  => esc_html__( 'in ', 'tourizto' ),
			) );

			$tags = $this->utility->meta_data->get_terms( array(
				'visible'   => $this->instance['tags_visibility'],
				'type'      => 'post_tag',
				'delimiter' => ', ',
				'prefix'    => esc_html__( 'Tags: ', 'tourizto' ),
				'before'    => '<span class="news-smart-box__item-tags post__tags">',
				'after'     => '</span>',
			) );

			$more_btn = $this->utility->attributes->get_button( array(
				'visible' => $this->instance['more_button_visibility'],
				'class'   => 'news-smart-box__item-more-btn btn-link',
				'text'    => $this->instance['more_button_text'],
				'html'    => '<a href="%1$s" %3$s><span class="btn btn-accent-1">%4$s</span>%5$s</a>',
			) );

			$item_classes   = array();
			$item_classes[] = 'news-smart-box__item';
			$item_classes[] = 'news-smart-box__item-' . $posts_query->current_post;

			$view_template   = $full_view_template;
			$grid_class_line = 'col-xs-12 col-sm-6';
			$type_class      = 'full-type';
			$image_size      = 'tourizto-thumb-560-315';
			$title_tag       = 'h4';

			switch ( $this->instance['layout_type'] ) {
				case 'layout_type_1':
					if ( 1 <= $posts_query->current_post ) {
						$view_template   = $mini_view_template;
						$type_class      = 'mini-type';
						$image_size      = 'tourizto-thumb-260-147';
						$title_tag       = 'h5';
					} else {
						$grid_class_line = 'col-xs-12 col-sm-12 col-xl-6';
					}
					break;

				case 'layout_type_2':
					if ( 1 < $posts_query->current_post  ) {
						$view_template   = $mini_view_template;
						$type_class      = 'mini-type';
						$image_size      = 'tourizto-thumb-260-147';
						$title_tag       = 'h5';
					} else {
						$grid_class_line = 'col-xs-12 col-sm-12 col-lg-6';
					}
					break;

				case 'layout_type_3':
					if ( 1 <= $posts_query->current_post ) {
						$view_template   = $mini_view_template;
						$type_class      = 'mini-type';
						$grid_class_line = 'col-xs-12 col-sm-6';
						$image_size      = 'tourizto-thumb-260-147';
						$title_tag       = 'h5';
					} else {
						$grid_class_line = 'col-xs-12 col-sm-12';
					}
					break;

				default:
					break;
			}

			$image = $this->utility->media->get_image( array(
				'size'              => $image_size,
				'mobile_size'       => $image_size,
				'html'              => '<a href="%1$s" %2$s><img class="news-smart-box__item-thumb-img" src="%3$s" alt="%4$s" %5$s></a>',
				'class'             => 'news-smart-box__item-thumb-link',
				'placeholder_title' => get_bloginfo( 'name' ),
			) );

			if ( 0 !== $title_length ) {
				$title = $this->utility->attributes->get_title( array(
					'class'        => 'news-smart-box__item-title',
					'html'         => '<' . $title_tag . ' %1$s><a href="%2$s" %3$s>%4$s</a></' . $title_tag . '>',
					'trimmed_type' => 'char',
					'length'       => $title_length,
				) );
			}

			$item_classes[] = $grid_class_line;
			$item_classes[] = $type_class;

			echo '<div class="' . join( ' ', $item_classes ) . '">';
				include $view_template;
			echo '</div>';

		endwhile;

		return ob_get_clean();
	}

	/**
	 * Get navigation box.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_navigation_box( $current_term_slug, $alt_terms_slug_list = array() ) {
		$html              = '';
		$current_term_name = $this->get_term_name_by_slug( $current_term_slug );

		if ( false === $current_term_name ) {
			return $html;
		}

		$html .= '<div class="news-smart-box__navigation">';

			$html .= '<div class="news-smart-box__navigation-current-term">';
				if ( 'true' === $this->instance['current_title_visibility'] ) {
					$html .= '<span class="news-smart-box__navigation-title h4-style">' . $current_term_name . '</span>';
				}
				$html .= '<div class="nsb-spinner"><div class="double-bounce-1"></div><div class="double-bounce-2"></div></div>';
			$html .= '</div>';

			if ( ! empty( $alt_terms_slug_list ) ) {
				$html .= '<div class="news-smart-box__navigation-wrapper">';
					$html .= '<div class="news-smart-box__navigation-terms-list">';
						foreach ( $alt_terms_slug_list as $key => $term_slug ) {
							$name = $this->get_term_name_by_slug( $term_slug );

							if ( $name ) {
								$html .= '<div class="news-smart-box__navigation-terms-list-item" data-slug=' . $term_slug . '>' . $this->get_term_name_by_slug( $term_slug ) . '</div>';
							}
						}
					$html .= '</div>';
				$html .= '</div>';
			}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Ajax get instance.
	 *
	 * @since 1.0.0
	 */
	public function new_smart_box_instance(){
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'tourizto-new-smart-box-nonce' ) ) {
			exit();
		}

		if ( ! empty( $_GET )
			&& array_key_exists( 'value_slug', $_GET )
			&& array_key_exists( 'instance_settings', $_GET ) ) {

			$value_slug        = sanitize_text_field( $_GET['value_slug'] );
			$instance_settings = $_GET['instance_settings'];
			$this->instance    = $instance_settings;

			echo $this->get_instance( $value_slug );
		}

		exit();
	}

	/**
	 * Get terms list array.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_terms_list( $tax = 'category', $key = 'slug' ) {
		$all_terms = ( array ) get_terms( $tax, array( 'hide_empty' => false ) );

		foreach ( $all_terms as $term ) {
			$terms[ $term->$key ] = $term->name;
		}

		return $terms;
	}

	/**
	 * Get term's name by slug value.
	 *
	 * @param  string $slug Slug value
	 * @return string|bool
	 */
	public function get_term_name_by_slug( $slug ) {
		$all_terms = (array) get_terms( array( 'post_tag', 'category' ), array( 'hide_empty' => false ) );

		foreach ( $all_terms as $key => $term_info ) {
			if ( $term_info->slug == $slug ) {
				return $term_info->name;
			}
		}

		return false;
	}
}

add_action( 'widgets_init', 'tourizto_register_news_smart_box_widgets' );
/**
 * Register news-smart-box widget.
 */
function tourizto_register_news_smart_box_widgets() {
	register_widget( 'Tourizto_News_Smart_Box_Widget' );
}
