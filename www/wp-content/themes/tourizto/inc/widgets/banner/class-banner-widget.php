<?php
/*
Widget Name: Banner widget
Description: This widget is used to display a banner in your sidebar.
Settings:
 Title - Widget's text title
 Source - You can choose an image
 Image size - Choose the image size
 Link - Specify a banner link
 Opens in - Choose where the link will be opened in
*/

/**
 * @package Tourizto
 */

if ( ! class_exists( 'Tourizto_Banner_Widget' ) ) {

	/**
	 * Class Tourizto_Banner_Widget.
	 */
	class Tourizto_Banner_Widget extends Cherry_Abstract_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'widget-banner';
			$this->widget_description = esc_html__( 'Display a banner in your sidebar.', 'tourizto' );
			$this->widget_id          = 'tourizto_widget_banner';
			$this->widget_name        = esc_html__( 'Banner', 'tourizto' );
			$this->settings           = array(
				'title'  => array(
					'type'  => 'text',
					'value' => '',
					'label' => esc_html__( 'Title:', 'tourizto' ),
				),
				'media_id' => array(
					'type'               => 'media',
					'multi_upload'       => false,
					'library_type'       => 'image',
					'upload_button_text' => esc_html__( 'Upload', 'tourizto' ),
					'value'              => '',
					'label'              => esc_html__( 'Source:', 'tourizto' ),
				),
				'media_size' => array(
					'type'             => 'select',
					'value'            => 'full',
					'options_callback' => array( $this, 'get_image_sizes' ),
					'options'          => false,
					'label'            => esc_html__( 'Select image size', 'tourizto' ),
					'placeholder'      => esc_html__( 'Select image size', 'tourizto' ),
				),
				'link' => array(
					'type'        => 'text',
					'placeholder' => esc_html__( 'Type a banner`s link', 'tourizto' ),
					'value'       => esc_url( home_url( '/' ) ),
					'label'       => esc_html__( 'Link:', 'tourizto' ),
				),
				'target' => array(
					'type'    => 'select',
					'options' => array(
						'_blank' => esc_html__( 'A new window or tab', 'tourizto' ),
						'_self'  => esc_html__( 'The same frame as it was clicked', 'tourizto' ),
					),
					'value' => '_blank',
					'label' => esc_html__( 'Opens in:', 'tourizto' ),
				),
			);

			parent::__construct();
		}

		/**
		 * Widget function.
		 *
		 * @see   WP_Widget
		 * @since 1.0.1
		 * @param array $args     Widget arguments.
		 * @param array $instance Instance.
		 */
		public function widget( $args, $instance ) {

			if ( empty( $instance['media_id'] ) ) {
				return;
			}

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}

			$template = tourizto_get_locate_template( 'inc/widgets/banner/views/banner.php' );

			if ( empty( $template ) ) {
				return;
			}

			ob_start();

			$this->setup_widget_data( $args, $instance );
			$this->widget_start( $args, $instance );

			$title      = ! empty( $instance['title'] ) ? $instance['title'] : $this->settings['title']['value'];
			$link       = $this->use_wpml_translate( 'link' );
			$media_id   = absint( $instance['media_id'] );
			$media_size = $instance['media_size'];
			$src        = wp_get_attachment_image_src( $media_id, $media_size );
			$target     = ! empty( $instance['target'] ) && in_array( $instance['target'], array( '_blank', '_self' ) ) ? $instance['target'] : $this->settings['target']['value'];

			include $template;

			$this->widget_end( $args );
			$this->reset_widget_data();

			echo $this->cache_widget( $args, ob_get_clean() );
		}

		/**
		 * Get register image sizes.
		 *
		 * @return array
		 */
		public function get_image_sizes() {

			global $_wp_additional_image_sizes;

			$sizes  = get_intermediate_image_sizes();
			$result = array();

			foreach ( $sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
					$result[ $size ] = ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) );
				} else {
					$result[ $size ] = sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					);
				}
			}

			$result = array_merge( $result, array( 'full' => esc_html__( 'Full', 'tourizto' ) ) );

			return $result;
		}
	}
}

add_action( 'widgets_init', 'tourizto_register_banner_widget' );
/**
 * Register banner widget.
 */
function tourizto_register_banner_widget() {
	register_widget( 'Tourizto_Banner_Widget' );
}
