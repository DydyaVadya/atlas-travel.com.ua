<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Elements_Templates_Manager' ) ) {

	/**
	 * Define Jet_Elements_Templates_Manager class
	 */
	class Jet_Elements_Templates_Manager {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Template option name
		 * @var string
		 */
		protected $option = 'jet_elements_templates';

		/**
		 * Source instance
		 *
		 * @var Elementor\TemplateLibrary\Source_Base
		 */
		protected $source = null;

		/**
		 * Constructor for the class
		 */
		public function init() {

			add_action( 'elementor/init', array( $this, 'register_templates_source' ) );
			// Get remote JetImpex templates list
			add_action( 'admin_init', array( $this, 'maybe_update_templates_list' ) );
			// Add JetImpex templates to Elementor templates list
			add_filter( 'option_elementor_remote_info_templates_data', array( $this, 'prepend_templates' ) );
			// Process JetImpext template request
			add_action( 'wp_ajax_elementor_get_template_data', array( $this, 'get_jet_template_data' ), 0 );

		}

		/**
		 * Register
		 *
		 * @return [type] [description]
		 */
		public function register_templates_source() {
			require jet_elements()->plugin_path( 'includes/template-library/class-jet-elements-templates-source.php' );
			$this->source = new Jet_Elements_Templates_Source();
		}

		/**
		 * Return transient key
		 *
		 * @return [type] [description]
		 */
		public function transient_key() {
			return $this->option . '_' . jet_elements()->get_version();
		}

		/**
		 * Process templates list updating if required
		 *
		 * @return [type] [description]
		 */
		public function maybe_update_templates_list() {

			$cached = get_transient( $this->transient_key() );

			if ( $cached ) {
				return;
			}

			if ( ! is_object( $this->source ) ) {
				return;
			}

			$templates = $this->source->get_items();

			if ( ! $templates ) {
				return;
			}

			update_option( $this->option, $templates, 'no' );

			set_transient( $this->transient_key(), true, 2 * WEEK_IN_SECONDS );

		}

		/**
		 * Add JetImpex templates to Elementor templates list
		 *
		 * @param  [type] $templates [description]
		 * @return [type]            [description]
		 */
		public function prepend_templates( $templates ) {

			$jet_templates = get_option( $this->option );

			if ( empty( $jet_templates ) ) {
				return $templates;
			} else {
				return array_merge( $jet_templates, $templates );
			}

		}

		/**
		 * Return jet template data insted of elementor template.
		 *
		 * @return [type] [description]
		 */
		public function get_jet_template_data() {

			if ( empty( $_REQUEST['template_id'] ) ) {
				return;
			}

			if ( false === strpos( $_REQUEST['template_id'], $this->source->get_prefix() ) ) {
				return;
			}

			wp_send_json_success( $this->source->get_data( $_REQUEST ) );

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

/**
 * Returns instance of Jet_Elements_Templates_Manager
 *
 * @return object
 */
function jet_elements_templates_manager() {
	return Jet_Elements_Templates_Manager::get_instance();
}
