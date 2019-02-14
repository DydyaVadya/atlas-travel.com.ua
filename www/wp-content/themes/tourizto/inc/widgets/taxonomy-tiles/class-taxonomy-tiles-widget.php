<?php
/*
Widget Name: Taxonomy Tiles widget
Description: This widget is used to display images from a taxonomy
Settings:
 Title - Widget's text title
 Choose taxonomy type - Choose the posts source type
 Select cateogory / tag - Choose the posts source
 Description words length - Limit the description length
 Show post count - Toggle whether to show the post count
 Choose layout type - Switch between grid and tiles layouts
 Columns number - Choose a number of columns
 Items padding - Customize the padding between items
*/

/**
 * @package Tourizto
 */

if ( ! class_exists( 'Tourizto_Taxonomy_Tiles_Widget' ) ) {

	/**
	 * Taxonomy Tiles Widget
	 */
	class Tourizto_Taxonomy_Tiles_Widget extends Cherry_Abstract_Widget {

		public $tiles_matrix = array(
			array( 'tile-xl-x', 'tile-xl-y' ),
			array( 'tile-md-x', 'tile-md-y' ),
			array( 'tile-md-x', 'tile-md-y' ),
			array( 'tile-md-x', 'tile-md-y' ),
			array( 'tile-md-x', 'tile-md-y' ),
			array( 'tile-md-x', 'tile-md-y' ),
			array( 'tile-md-x', 'tile-md-y' ),
			array( 'tile-md-x', 'tile-md-y' ),
			array( 'tile-md-x', 'tile-md-y' ),
		);

		/**
		 * Contain utility module from Cherry framework
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private $utility = null;

		/**
		 * Taxonomy Tiles widget constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->widget_name        = esc_html__( 'Taxonomy Tiles', 'tourizto' );
			$this->widget_description = esc_html__( 'This widget displays images from taxonomy.', 'tourizto' );
			$this->widget_id          = 'tourizto-widget-taxonomy-tiles';
			$this->widget_cssclass    = 'widget-taxonomy-tiles';
			$this->utility            = tourizto_utility()->utility;
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'value' => 'Taxonomy Tiles Widget',
					'label' => esc_html__( 'Widget title', 'tourizto' ),
				),
				'terms_type' => array(
					'type'    => 'radio',
					'value'   => 'category',
					'options' => array(
						'category' => array(
							'label' => esc_html__( 'Category', 'tourizto' ),
							'slave' => 'terms_type_post_category',
						),
						'post_tag' => array(
							'label' => esc_html__( 'Tag', 'tourizto' ),
							'slave' => 'terms_type_post_tag',
						),
					),
					'label'   => esc_html__( 'Choose taxonomy type', 'tourizto' ),
				),
				'category' => array(
					'type'             => 'select',
					'multiple'         => true,
					'value'            => '',
					'options'          => false,
					'options_callback' => array( $this->utility->satellite, 'get_terms_array', array( 'category', 'term_id' ) ),
					'label'            => esc_html__( 'Select category to show', 'tourizto' ),
					'master'           => 'terms_type_post_category',
				),
				'post_tag' => array(
					'type'             => 'select',
					'multiple'         => true,
					'value'            => '',
					'options'          => false,
					'options_callback' => array( $this->utility->satellite, 'get_terms_array', array( 'post_tag', 'term_id' ) ),
					'label'            => esc_html__( 'Select tags to show', 'tourizto' ),
					'master'           => 'terms_type_post_tag',
				),
				'description_length' => array(
					'type'       => 'stepper',
					'value'      => '0',
					'max_value'  => '500',
					'min_value'  => '0',
					'step_value' => '1',
					'label'      => esc_html__( 'Description words length ( Set 0 to hide description. )', 'tourizto' ),
				),
				'show_post_count' => array(
					'type'    => 'checkbox',
					'value'   => array(
						'show_post_count_check' => 'true',
					),
					'options' => array(
						'show_post_count_check' => esc_html__( 'Show post count', 'tourizto' ),
					),
				),
				'layout_type' => array(
					'type'    => 'radio',
					'value'   => 'grid',
					'options' => array(
						'grid'  => array(
							'label' => esc_html__( 'Grid', 'tourizto' ),
							'slave' => 'layout_type_grid',
						),
						'tiles' => array(
							'label' => esc_html__( 'Tiles', 'tourizto' ),
							'slave' => 'layout_type_tiles',
						),
					),
					'label'   => esc_html__( 'Choose Layout Type', 'tourizto' ),
				),
				'columns_number' => array(
					'type'       => 'stepper',
					'value'      => '2',
					'max_value'  => '4',
					'min_value'  => '1',
					'step_value' => '1',
					'label'      => esc_html__( 'Columns number ( layout type grid )', 'tourizto' ),
					'master'     => 'layout_type_grid',
				),
				'tiles_type' => array(
					'type'    => 'radio',
					'value'   => 'tiles-layout-2',
					'options' => array(
						'tiles-layout-1' => array(
							'label' => esc_html__( 'Layout 1', 'tourizto' ),
						),
						'tiles-layout-2' => array(
							'label' => esc_html__( 'Layout 2', 'tourizto' ),
						),
					),
					'master'           => 'layout_type_tiles',
				),
				'items_padding' => array(
					'type'       => 'stepper',
					'value'      => '5',
					'max_value'  => '50',
					'min_value'  => '0',
					'step_value' => '1',
					'label'      => esc_html__( 'Items padding ( size in pixels )', 'tourizto' ),
				),
			);

			parent::__construct();
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

			$template = tourizto_get_locate_template( 'inc/widgets/taxonomy-tiles/views/taxonomy-tiles-view.php' );

			if ( empty( $template ) ) {
				return;
			}

			ob_start();

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$terms_type         = $instance['terms_type'];
			$description_length = $instance['description_length'];
			$show_post_count    = $instance['show_post_count'];
			$layout_type        = $instance['layout_type'];
			$columns_number     = $instance['columns_number'];
			$items_padding      = $instance['items_padding'];
			$tiles_type         = $instance['tiles_type'];

			if ( array_key_exists( $terms_type, $instance ) ) {

				$taxonomy = $instance[ $terms_type ];

				if ( $taxonomy ) {
					$terms = get_terms( $terms_type, array('include' => $taxonomy, 'hide_empty' => false ) );
				}
			}

			if ( isset( $terms ) && $terms ) {

				$grid_class_array = apply_filters( 'tourizto_taxonomy_tiles_widget_grid_class', array(
					'1' => 'col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12',
					'2' => 'col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6',
					'3' => 'col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4',
					'4' => 'col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3',
				) );

				$columns_class = $grid_class_array[ $columns_number ];
				$inline_style  = array();
				$counter       = 0;

				if ( 'grid' === $layout_type ) {
					$class = $columns_class;
					$inline_style = array(
						'margin' => '0 0 ' . $items_padding . 'px ' . $items_padding . 'px',
					);

				} elseif ( 'tiles' === $layout_type && 'tiles-layout-2' == $tiles_type ) {
					$inline_style = array(
						'width'  => 'calc(100% - ' . $items_padding . 'px)',
						'height' => 'calc(100% - ' . $items_padding . 'px)',
						'margin' => '0 0 ' . $items_padding . 'px ' . $items_padding . 'px',
					);
				} else {
					$inline_style = array(
						'width'  => 'calc(100% - ' . $items_padding . 'px)',
						'margin' => '0 0 ' . $items_padding . 'px ' . $items_padding . 'px',
					);
				}

				echo apply_filters( 'tourizto_taxonomy_tiles_widget_before',
					'<div class="row grid ' . $layout_type . '-columns columns-number-' . $columns_number . ' ' . $tiles_type . '">',
					$instance
				);

				tourizto_theme()->dynamic_css->add_style(
					$this->add_selector( '.widget-taxonomy-tiles__inner' ),
					$inline_style
				);

				tourizto_theme()->dynamic_css->add_style(
					$this->add_selector( '.row' ),
					array( 'margin' => '0 0 -' . $items_padding . 'px -' . $items_padding . 'px' )
				);

				foreach ( $terms as $term_key => $term ) {

					$title = $this->utility->attributes->get_title( array(
						'html'  => '<h6 %1$s><a href="%2$s" %3$s>%4$s</a></h6>',
						'class' => 'widget-taxonomy-tiles__title',
					), 'term', $term->term_id );

					$description_visible = ( '0' === $description_length ) ? false : true;
					$description = $this->utility->attributes->get_content( array(
						'visible'      => $description_visible,
						'length'       => $description_length,
						'content_type' => 'term_description',
						'class'        => 'widget-taxonomy-tiles__desc',
					), 'term', $term->term_id );

					$count = $this->utility->meta_data->get_post_count_in_term( array(
						'visible' => $show_post_count['show_post_count_check'],
						'sufix'   => _n_noop( '%s post', '%s posts', 'tourizto' ),
						'html'    => '%1$s<span %4$s>%5$s%6$s</span>',
						'class'   => 'widget-taxonomy-tiles__post-count',
					), $term->term_id );

					$permalink = $this->utility->attributes->get_term_permalink( $term->term_id );

					if ( 'grid' === $layout_type ) {
						$html = '<img %2$s src="%3$s" alt="%4$s" %5$s >';
					} else {
						$html  = '<span %2$s></span>';

						if ( 'tiles-layout-1' == $tiles_type ) {
							$class = '';
						} elseif ( 'tiles-layout-2' == $tiles_type ) {
							$class = $this->tiles_matrix[$counter][0] . ' ' . $this->tiles_matrix[$counter][1];
						}

						$tiles_img_css_selector = sprintf( '.term-%s .term-img', $term->term_id );

						$bg_image_url = $this->utility->media->get_image( array(
							'html'        => '%3$s',
							'size'        => 'tourizto-thumb-760-571',
							'mobile_size' => 'tourizto-thumb-760-571',
						), 'term', $term->term_id );

						tourizto_theme()->dynamic_css->add_style(
							$this->add_selector( $tiles_img_css_selector ),
							array( 'background-image' => 'url(' . esc_url( $bg_image_url ) . ')' )
						);
					}

					$image = $this->utility->media->get_image( array(
						'html'        => $html,
						'class'       => 'term-img',
						'size'        => 'tourizto-thumb-760-571',
						'mobile_size' => 'tourizto-thumb-760-571',
					), 'term', $term->term_id );

					$button = $this->utility->attributes->get_button( array(
						'visible' => true,
						'text'    => '',
						'class'   => 'carousel__more-btn btn-link',
					), 'term', $term->term_id );

					include $template;

					if ( isset( $this->tiles_matrix[$counter + 1] ) ) {
						$counter ++;
					} else {
						$counter = 0;
					}
				}

				echo apply_filters( 'tourizto_taxonomy_tiles_widget_after', '</div>', $instance );
			}

			$this->widget_end( $args );
			$this->reset_widget_data();

			echo $this->cache_widget( $args, ob_get_clean() );
		}
	}

	add_action( 'widgets_init', 'tourizto_register_taxonomy_tiles_widget' );
	/**
	 * Register taxonomy tiles widget.
	 */
	function tourizto_register_taxonomy_tiles_widget() {
		register_widget( 'Tourizto_Taxonomy_Tiles_Widget' );
	}
}
