<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Jet_Elements_Templates_Source extends Elementor\TemplateLibrary\Source_Base {

	/**
	 * JetImpex templates API server
	 *
	 * @var string
	 */
	protected $api_server = 'http://jetelements.zemez.io/';

	/**
	 * JetImpex templates API route
	 *
	 * @var string
	 */
	protected $api_route = 'wp-json/jet/v1';

	/**
	 * Template prefix
	 *
	 * @var string
	 */
	protected $template_prefix = 'jet_';

	/**
	 * Return JetImpex templates prefix
	 *
	 * @return [type] [description]
	 */
	public function get_prefix() {
		return $this->template_prefix;
	}

	public function get_id() {
		return 'jet-templates';
	}

	public function get_title() {
		return __( 'Jet Templates', 'jet-elements' );
	}

	public function register_data() {}

	public function get_items( $args = array() ) {

		$url            = $this->api_server . $this->api_route . '/templates/';
		$response       = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body           = wp_remote_retrieve_body( $response );
		$body           = json_decode( $body, true );
		$templates_data = ! empty( $body['data'] ) ? $body['data'] : false;
		$templates      = array();

		if ( ! empty( $templates_data ) ) {
			foreach ( $templates_data as $template_data ) {
				$templates[] = $this->get_item( $template_data );
			}
		}

		if ( ! empty( $args ) ) {
			$templates = wp_list_filter( $templates, $args );
		}

		return $templates;
	}

	/**
	 * @param array $template_data
	 *
	 * @return array
	 */
	public function get_item( $template_data ) {
		return array(
			'id'                => $this->template_prefix . $template_data['id'],
			'source'            => $this->get_id(),
			'title'             => $template_data['title'],
			'thumbnail'         => $template_data['thumbnail'],
			'tmpl_created'      => date( get_option( 'date_format' ), $template_data['tmpl_created'] ),
			'author'            => $template_data['author'],
			'categories'        => array(),
			'keywords'          => array(),
			'is_pro'            => ( '1' === $template_data['is_pro'] ),
			'has_page_settings' => ( '1' === $template_data['has_page_settings'] ),
			'url'               => $template_data['url'],
		);
	}

	public function save_item( $template_data ) {
		return false;
	}

	public function update_item( $new_data ) {
		return false;
	}

	public function delete_template( $template_id ) {
		return false;
	}

	public function export_template( $template_id ) {
		return false;
	}

	public function get_data( array $args, $context = 'display' ) {

		$id       = str_replace( $this->template_prefix, '', $args['template_id'] );
		$url      = $this->api_server . $this->api_route . '/template/' . $id;
		$response = wp_remote_get( $url, array( 'timeout' => 60 ) );
		$body     = wp_remote_retrieve_body( $response );
		$body     = json_decode( $body, true );
		$data     = ! empty( $body['data'] ) ? $body['data'] : false;

		$result = array();

		$result['content']       = $this->replace_elements_ids( $data );
		$result['content']       = $this->process_export_import_content( $result['content'], 'on_import' );
		$result['page_settings'] = array();

		return $result;
	}
}
