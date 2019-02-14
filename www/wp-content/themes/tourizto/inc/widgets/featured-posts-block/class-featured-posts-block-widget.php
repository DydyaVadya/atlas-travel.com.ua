<?php
/*
Widget Name: Featured Posts Block widget
Description:
Settings:
*/

/**
 * @package Tourizto
 */

if ( ! class_exists( 'Tourizto_Featured_Posts_Block_Widget' ) ) {

	/**
	 * Featured Posts Block Widget.
	 *
	 * @since 1.0.0
	 */
	class Tourizto_Featured_Posts_Block_Widget extends Cherry_Abstract_Widget {

		/**
		 * Default layout.
		 *
		 * @since 1.0.0
		 * @var   string
		 */
		private $_default_layout = 'layout-1';

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->widget_name        = esc_html__( 'Featured Posts Block', 'tourizto' );
			$this->widget_description = esc_html__( 'This widget displays latest posts', 'tourizto' );
			$this->widget_id          = 'tourizto_widget_featured_posts_block';
			$this->widget_cssclass    = 'widget-fpblock';
			$this->utility            = tourizto_utility()->utility;

			$layouts        = $this->get_layouts();
			$layout_options = array();

			foreach( $layouts as $id => $layout ) {
				$layout_options[ $id ] = array(
					'label'   => $layout['name'],
					'img_src' => TOURIZTO_THEME_URI . '/assets/images/admin/widgets/featured-posts-block/' . $id . '.svg',
				);
			}

			$this->settings = array(
				'title'  => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Title:', 'tourizto' ),
				),
				'layout' => array(
					'type'    => 'radio',
					'value'   => $this->_default_layout,
					'label'   => esc_html__( 'Layout', 'tourizto' ),
					'options' => $layout_options,
				),
				'posts_ids' => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Posts IDs (Optional)', 'tourizto' ),
				),
				'checkboxes'     => array(
					'type'  => 'checkbox',
					'label' => esc_html__( 'Post Meta', 'tourizto' ),
					'value' => array(
						'title'      => 'true',
						'excerpt'    => 'true',
						'categories' => 'true',
						'tags'       => 'true',
						'author'     => 'true',
						'date'       => 'true',
					),
					'options' => array(
						'title'      => esc_html__( 'Show title', 'tourizto' ),
						'excerpt'    => esc_html__( 'Show excerpt', 'tourizto' ),
						'categories' => esc_html__( 'Show categories', 'tourizto' ),
						'tags'       => esc_html__( 'Show tags', 'tourizto' ),
						'author'     => esc_html__( 'Show author', 'tourizto' ),
						'date'       => esc_html__( 'Show date', 'tourizto' ),
					),
				),
				'title_length' => array(
					'type'      => 'stepper',
					'value'     => 12,
					'min_value' => 1,
					'label'     => esc_html__( 'Title length (chars)', 'tourizto' ),
				),
				'excerpt_length' => array(
					'type'      => 'stepper',
					'value'     => 15,
					'min_value' => 1,
					'label'     => esc_html__( 'Excerpt length (words)', 'tourizto' ),
				),
			);

			parent::__construct();
		}

		/**
		 * Widget function.
		 *
		 * @see   WP_Widget
		 *
		 * @since 1.0.0
		 * @param array $args     Arguments.
		 * @param array $instance Instance.
		 */
		public function widget( $args, $instance ) {

			if ( true === $this->get_cached_widget( $args ) ) {
				return;
			}

			$layout = $this->_default_layout;

			if ( $this->_validate_layout( $this->instance['layout'] ) ) {
				$layout = $this->instance['layout'];
			}

			ob_start();

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$template = tourizto_get_locate_template( 'inc/widgets/featured-posts-block/views/widget.php' );

			if ( ! empty( $template ) ) {
				include $template;
			}

			$this->widget_end( $args );
			$this->reset_widget_data();

			echo $this->cache_widget( $args, ob_get_clean() );
		}

		/**
		 * Render layout.
		 *
		 * @since  1.0.0
		 * @param  array $options
		 * @return string|boolean
		 */
		public function render_layout( $options = array() ) {
			$defaults = array(
				'layout'    => $this->_default_layout,
				'posts_ids' => '',
				'wrapper'   => '<div class="%1$s">%2$s</div>',
			);

			$settings = wp_parse_args( $options, $defaults );
			$layouts  = $this->get_layouts();

			if ( empty( $layouts[ $settings['layout'] ] ) ) {
				return false;
			}

			$layout        = $layouts[ $settings['layout'] ];
			$item_template = tourizto_get_locate_template( 'inc/widgets/featured-posts-block/views/item.php' );

			if ( '' === $item_template ) {
				return false;
			}

			global $post;

			$query = array(
				'posts_per_page' => $layout['amount'],
				'orderby'        => 'date',
				'order'          => 'DESC',
			);

			if ( isset( $this->instance['posts_ids'] ) && ! empty( $this->instance['posts_ids'] ) ) {
				$query['include'] = $this->instance['posts_ids'];
			}

			/**
			 * Filters the set of arguments for query.
			 *
			 * @since 1.0.0
			 * @param array $query    Query arguments
			 * @param array $instance Widget instance.
			 */
			$query = apply_filters( 'tourizto_featured_posts_block_query', $query, $this->instance );

			// Retrieve posts.
			$posts = get_posts( $query );
			$data  = array();

			if ( sizeof( $posts ) > 0 ) {

				foreach( $posts as $key => $post ) {

					ob_start();

					setup_postdata( $post );

					$image = $this->utility->media->get_image( array(
						'size'                   => 'tourizto-thumb-l',
						'mobile_size'            => 'tourizto-thumb-l',
						'html'                   => '%3$s',
						'placeholder_background' => '000',
						'placeholder_foreground' => 'fff',
						'placeholder_title'      => get_bloginfo( 'name' ),
					) );

					$title = $this->utility->attributes->get_title( array(
						'visible'      => $this->instance['checkboxes']['title'],
						'class'        => 'widget-fpblock__item-title',
						'html'         => '<h4 %1$s><a href="%2$s" %3$s>%4$s</a></h4>',
						'trimmed_type' => 'char',
						'length'       => (int) $this->instance['title_length'],
					) );

					$date = $this->utility->meta_data->get_date( array(
						'visible' => $this->instance['checkboxes']['date'],
						'class'   => 'widget-fpblock__item-date post__date',
					) );

					$author = $this->utility->meta_data->get_author( array(
						'visible' => $this->instance['checkboxes']['author'],
						'class'   => 'widget-fpblock__item-author-link',
						'prefix'  => esc_html__( 'by ', 'tourizto' ),
						'html'    => '<span class="widget-fpblock__item-author posted-by">%1$s<a href="%2$s" %3$s %4$s rel="author">%5$s%6$s</a></span>',
					) );

					$content = $this->utility->attributes->get_content( array(
						'visible' => $this->instance['checkboxes']['excerpt'],
						'length'  => (int) $this->instance['excerpt_length'],
						'class'   => 'widget-fpblock__item-content',
					) );

					$tags = $this->utility->meta_data->get_terms( array(
						'visible'   => $this->instance['checkboxes']['tags'],
						'type'      => 'post_tag',
						'delimiter' => ', ',
						'prefix'    => esc_html__( 'Tags: ', 'tourizto' ),
						'before'    => '<div class="widget-fpblock__item-tags post__tags">',
						'after'     => '</div>',
					) );

					$cats = $this->utility->meta_data->get_terms( array(
						'visible'   => $this->instance['checkboxes']['categories'],
						'type'      => 'category',
						'prefix'    => esc_html__( 'in ', 'tourizto' ),
						'before'    => '<span class="widget-fpblock__item-cats post__cats">',
						'after'     => '</span>',
					) );

					$special_class = ( 0 === $key ) ? 'featured' : 'simple';

					$fpblock_item_css_selector = sprintf( '.widget-fpblock__item.widget-fpblock__item-%1$s.post-%2$s', $key, get_the_ID() );

					tourizto_theme()->dynamic_css->add_style(
						$this->add_selector( $fpblock_item_css_selector ),
						array( 'background-image' => 'url(' . esc_url( $image ) . ')' )
					);

					include $item_template;

					$data[] = ob_get_clean();
				}
			}

			wp_reset_postdata();

			if ( 0 < sizeof( $posts ) ) {
				return sprintf(
					$settings['wrapper'],
					$settings['layout'],
					$this->prepare_data( $data, $layout )
				);
			}

			return false;
		}

		/**
		 * Prepare contenmt to output (wrap to container's).
		 *
		 * @since  1.0.0
		 * @param  array $data   Set of HTML-formatted items.
		 * @param  array $layout Layout configuration.
		 * @return string
		 */
		public function prepare_data( $data, $layout ) {
			$result = '';

			if ( empty( $data ) ) {
				return $result;
			}

			if ( empty( $layout['markup'] ) ) {
				return join( '', $data );
			}

			$container_template = tourizto_get_locate_template( 'inc/widgets/featured-posts-block/views/container.php' );

			if ( '' === $container_template ) {
				return $result;
			}

			$elements = $layout['markup'];
			$counter  = 0;

			foreach ( $elements as $k => $element ) {

				if ( empty( $data[ $k ] ) ) {
					break;
				}

				if ( 'container' == $element['type'] ) {

					$classes = ! empty( $element['class'] ) ? $element['class'] : '';
					$childs  = ! empty( $element['childs'] ) ? $element['childs'] : 1;
					$_data   = array_slice( $data, $counter, $childs );
					$counter += $childs;

					ob_start();
					include $container_template;
					$result .= ob_get_clean();

				} else {
					$result .= $data[ $k ];
					$counter++;
				}
			}

			return $result;
		}

		/**
		 * Check if given layout exists and is valid.
		 *
		 * @since  1.0.0
		 * @param  string $layout Layout option value.
		 * @return bool
		 */
		private function _validate_layout( $layout ) {

			if ( ! empty( $layout ) ) {
				$layouts = $this->get_layouts();
				$keys    = array_keys( $layouts );

				return in_array( $layout, $keys );
			}

			return false;
		}

		/**
		 * Get available layouts.
		 *
		 * @since  1.0.0
		 * @return array
		 */
		public function get_layouts() {
			return array(
				'layout-1' => array(
					'name'   => esc_html__( 'Type #1', 'tourizto' ),
					'amount' => 5,
					'markup' => array(
						array(
							'type'  => 'item',
							'class' => 'widget-fpblock__item-medium',
						),
						array(
							'type'   => 'container',
							'class'  => '',
							'childs' => 2,
						),
						array(
							'type'   => 'container',
							'class'  => '',
							'childs' => 2,
						),
					),
				),
				'layout-2' => array(
					'name'   => esc_html__( 'Type #2', 'tourizto' ),
					'amount' => 5,
					'markup' => array(
						array(
							'type'  => 'item',
							'class' => 'widget-fpblock__item-medium',
						),
						array(
							'type'   => 'container',
							'class'  => '',
							'childs' => 2,
						),
						array(
							'type'   => 'container',
							'class'  => '',
							'childs' => 2,
						),
					),
				),
				'layout-3' => array(
					'name'   => esc_html__( 'Type #3', 'tourizto' ),
					'amount' => 4,
					'markup' => array(
						array(
							'type'  => 'item',
							'class' => 'widget-fpblock__item-medium',
						),
						array(
							'type'   => 'container',
							'class'  => '',
							'childs' => 3,
						),
					),
				),
				'layout-4' => array(
					'name'   => esc_html__( 'Type #4', 'tourizto' ),
					'amount' => 4,
					'markup' => array(
						array(
							'type'  => 'item',
							'class' => 'widget-fpblock__item-medium',
						),
						array(
							'type'  => 'item',
							'class' => '',
						),
						array(
							'type'   => 'container',
							'class'  => '',
							'childs' => 2,
						),
					),
				),
				'layout-5' => array(
					'name'   => esc_html__( 'Type #5', 'tourizto' ),
					'amount' => 3,
					'markup' => array(
						array(
							'type'  => 'item',
							'class' => 'widget-fpblock__item-large',
						),
						array(
							'type'   => 'container',
							'class'  => '',
							'childs' => 2,
						),
					),
				),
			);
		}
	}

	add_action( 'widgets_init', 'tourizto_register_featured_posts_block_widget' );

	if ( false === function_exists( 'tourizto_register_featured_posts_block_widget' ) ) {
		/**
		 * Register featured posts block widget.
		 */
		function tourizto_register_featured_posts_block_widget() {
			register_widget( 'Tourizto_Featured_Posts_Block_Widget' );
		}
	}
}
