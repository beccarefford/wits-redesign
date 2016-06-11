<?php
/**
 * Conductor Admin License Options
 *
 * @class Conductor_Admin_License_Options
 * @author Slocum Studio
 * @version 1.3.2
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if( ! class_exists( 'Conductor_Admin_License_Options' ) ) {
	final class Conductor_Admin_License_Options {
		/**
		 * @var string
		 */
		public $version = '1.3.2';

		/**
		 * @var string
		 */
		public static $sub_menu_page = '';

		/**
		 * @var Conductor, Instance of the class
		 */
		protected static $_instance;

		/**
		 * Function used to create instance of class.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) )
				self::$_instance = new self();

			return self::$_instance;
		}


		/**
		 * This function sets up all of the actions and filters on instance. It also loads (includes)
		 * the required files and assets.
		 */
		function __construct() {
			// Load required assets
			$this->includes();

			// Hooks
			add_action( 'admin_menu', array( $this, 'admin_menu' ) ); // Set up admin sub-menu item
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); // Load CSS/JavaScript
			add_action( 'admin_init', array( $this, 'admin_init' ) ); // Register setting
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		private function includes() {
			include_once( 'class-conductor-admin-license-options-views.php' ); // Conductor Admin License Options View Controller
		}

		/**
		 * This function creates the admin menu item for Conductor admin licensing functionality
		 */
		public function admin_menu() {
			$conductor_admin_options_menu_page = str_replace( 'toplevel_page_', '', Conductor_Admin_Options::$menu_page );

			// Conductor Admin License Options Page
			self::$sub_menu_page = add_submenu_page( $conductor_admin_options_menu_page, __( 'License', 'conductor' ), __( 'License', 'conductor' ), 'manage_options', 'conductor-license', array( $this, 'render' ) );

			return self::$sub_menu_page;
		}

		/**
		 * This function enqueues CSS/JavaScript on the Conductor Options Page.
		 */
		function admin_enqueue_scripts( $hook ) {
			// Bail if we're not on the conductor-license page
			if ( strpos( $hook, 'conductor-license' ) === false )
				return false;

			$protocol = is_ssl() ? 'https' : 'http';

			// Stylesheets
			wp_enqueue_style( 'conductor-admin', Conductor::plugin_url() . '/assets/css/conductor-admin.css', false, Conductor::$version );

			// Scripts
			wp_enqueue_script( 'conductor-general-admin', Conductor::plugin_url() . '/assets/js/conductor-general-admin.js', array( 'jquery' ), Conductor::$version );
		}

		/**
		 * This function registers a setting for Conductor and adds setting sections and setting fields.
		 */
		public function admin_init() {
			// Register Setting
			register_setting( Conductor_Options::$option_name . '_license', Conductor_Options::$option_name .'_license', array( $this, 'sanitize_option' ) );

			// License
			add_settings_section( 'conductor_license_section', __( 'Conductor License', 'conductor' ), array( $this, 'conductor_license_section' ), Conductor_Options::$option_name . '_license' );
			add_settings_field( 'conductor_license_field', __( 'Conductor License Key', 'conductor' ), array( $this, 'conductor_license_field' ), Conductor_Options::$option_name . '_license', 'conductor_license_section' );
		}

		/**
		 * This function renders the Conductor License Settings Section.
		 */
		public function conductor_license_section() {
			Conductor_Admin_License_Options_Views::conductor_license_section();
		}

		/**
		 * This function renders the Conductor License Settings Field.
		 */
		function conductor_license_field() {
			Conductor_Admin_License_Options_Views::conductor_license_field();
		}


		/**
		 * This function renders the Conductor options page.
		 */
		public function render() {
			// Render the main view
			Conductor_Admin_License_Options_Views::render();
		}

		/**
		 * This function sanitizes the option values before they are stored in the database.
		 */
		public static function sanitize_option( $input ) {
			// Fetch current options
			$conductor_license_options = Conductor_Options::get_options( Conductor_Options::$option_name . '_license' );

			// Conductor Updates instance
			$conductor_updates = Conduct_Updates();

			// Deactivate License
			if ( isset( $input['deactivate'] ) && ! empty( $conductor_license_options['key'] ) ) {
				// Deactivation arguments
				$api_params = array(
					'edd_action'=> 'deactivate_license',
					'license' 	=> $conductor_license_options['key'],
					'item_name' => urlencode( $conductor_updates->get_name() ),
					'url'       => home_url()
				);

				// Call the custom API.
				$response = wp_remote_get( add_query_arg( $api_params, $conductor_updates->get_url() ), array( 'timeout' => 15, 'sslverify' => false ) );

				// Make sure we have a valid response
				if ( ! is_wp_error( $response ) && ( $license_data = json_decode( wp_remote_retrieve_body( $response ) ) ) )
					// Validate that the request was successful and we have a valid license
					if ( $license_data->license === 'deactivated' ) {
						// Clear the add-ons transient
						delete_transient( Conductor_Options::$option_name . '_addons_list' );

						return Conductor_Options::get_option_defaults( Conductor_Options::$option_name . '_license' );
					}
			}

			// Parse arguments, replacing defaults with user input
			$input = wp_parse_args( $input, Conductor_Options::get_option_defaults( Conductor_Options::$option_name . '_license' ) );

			// License Key
			if ( isset( $input['key'] ) )
				$input['key'] = trim( sanitize_text_field( $input['key'] ) );

			// Status
			if ( ( ! empty( $input['key'] ) && $input['key'] !== $conductor_license_options['key'] ) || $input['status'] === 'invalid' ) {
				$input['status'] = false;

				// Activation arguments
				$api_args = array(
					'edd_action'=> 'activate_license',
					'license' 	=> $input['key'],
					'item_name' => urlencode( $conductor_updates->get_name() ),
					'url'       => home_url()
				);

				// Call the custom API.
				$response = wp_remote_get( add_query_arg( $api_args, $conductor_updates->get_url() ), array( 'timeout' => 15, 'sslverify' => false ) );

				// Make sure we have a valid response
				if ( ! is_wp_error( $response ) && ( $license_data = json_decode( wp_remote_retrieve_body( $response ) ) ) )
					// Validate that the request was successful and we have a valid license
					if ( $license_data->success && $license_data->license === 'valid' ) {
						// Clear the add-ons transient
						delete_transient( Conductor_Options::$option_name . '_addons_list' );

						$input['status'] = 'valid';
					}
					else
						$input['status'] = 'invalid';
				// Otherwise we do not have a valid response
				else
					$input['status'] = 'invalid';
			}

			// If we don't have a new license status but a previous license status existed
			if ( ( ! isset( $input['status'] ) || empty( $input['status'] ) ) && ! empty( $conductor_license_options['status'] ) )
				$input['status'] = $conductor_license_options['status'];

			return $input;
		}
	}
}

/**
 * Create an instance of the Conductor_Admin_License_Options class.
 */
function Conduct_Admin_License_Options() {
	return Conductor_Admin_License_Options::instance();
}

Conduct_Admin_License_Options(); // Conduct your content!