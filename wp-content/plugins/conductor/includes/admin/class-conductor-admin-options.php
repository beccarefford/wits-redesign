<?php
/**
 * Conductor Admin Options
 *
 * @class Conductor_Admin_Options
 * @author Slocum Studio
 * @version 1.0.0
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if( ! class_exists( 'Conductor_Admin_Options' ) ) {
	final class Conductor_Admin_Options {
		/**
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 * @var string
		 */
		public static $menu_page = 'conductor';

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
			add_action( 'admin_menu', array( $this, 'admin_menu' ) ); // Set up admin menu item
			add_action( 'admin_menu', array( $this, 'admin_menu_sub_menu' ), 9999 ); // Adjust the main Conductor sub-menu item
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); // Load CSS/JavaScript
			add_filter( 'wp_redirect', array( $this, 'wp_redirect' ) ); // Add "hash" (tab) to URL before re-direct
			add_action( 'admin_init', array( $this, 'admin_init' ) ); // Register setting
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		private function includes() {
			include_once( 'class-conductor-admin-options-views.php' ); // Conductor Admin Options View Controller
		}

		/**
		 * This function creates the admin menu item for Conductor admin functionality
		 */
		public function admin_menu() {
			// Conductor Admin Page (directly after "Settings" which is located at position 80)
			self::$menu_page = add_menu_page( __( 'Conductor', 'conductor' ), __( 'Conductor', 'conductor' ), 'manage_options', 'conductor', array( $this, 'render' ), '', '80.01000011' );
		}

		/**
		 * This function adjusts the label on the first Conductor sub-menu item in the admin menu.
		 */
		public function admin_menu_sub_menu() {
			global $submenu;

			// Adjust the first sub-menu item label
			if ( isset( $submenu['conductor'] ) )
				$submenu['conductor'][0][0] = __( 'Options', 'conductor' );
		}

		/**
		 * This function enqueues CSS/JavaScript on the Conductor Options Page.
		 */
		public function admin_enqueue_scripts( $hook ) {
			// Bail if we're not on the conductor page
			if ( $hook !== Conductor_Admin_Options::$menu_page )
				return;

			$protocol = is_ssl() ? 'https' : 'http';

			// Stylesheets
			wp_enqueue_style( 'conductor-admin', Conductor::plugin_url() . '/assets/css/conductor-admin.css', false, Conductor::$version );

			// Scripts
			wp_enqueue_script( 'conductor-general-admin', Conductor::plugin_url() . '/assets/js/conductor-general-admin.js', array( 'jquery' ), Conductor::$version );

			wp_enqueue_script( 'conductor-content-layouts', Conductor::plugin_url() . '/assets/js/conductor-options-admin.js', array( 'jquery', 'wp-backbone' ), Conductor::$version );
			$conductor_admin_options_localize = array(
				'customize_url' => add_query_arg( array( 'url' => '' ), wp_customize_url() )
			);
			$conductor_admin_options_localize = apply_filters( 'conductor_admin_options_localize', $conductor_admin_options_localize, $hook, $protocol );
			wp_localize_script( 'conductor-content-layouts', 'conductor', $conductor_admin_options_localize );
		}

		/*
		 * This function appends the hash for the current tab based on POST data.
		 */
		function wp_redirect( $location ) {
			// Append tab "hash" to end of URL
			if ( strpos( $location, Conductor_Options::$option_name ) !== false && isset( $_POST['conductor_options_tab'] ) && $_POST['conductor_options_tab'] )
				$location .= esc_url( $_POST['conductor_options_tab'] );

			return $location;
		}

		/**
		 * This function registers a setting for Conductor and adds setting sections and setting fields.
		 */
		public function admin_init() {
			// Register Setting
			register_setting( Conductor_Options::$option_name, Conductor_Options::$option_name, array( $this, 'sanitize_option' ) );

			// Enable Conductor
			add_settings_section( 'conductor_enable_section', __( 'Enable Conductor', 'conductor' ), array( $this, 'conductor_enable_section' ), Conductor_Options::$option_name . '_general' );
			add_settings_field( 'conductor_enable_field', __( 'Enable Conductor', 'conductor' ), array( $this, 'conductor_enable_field' ), Conductor_Options::$option_name . '_general', 'conductor_enable_section' );

			// Content Layouts
			add_settings_section( 'conductor_content_layouts_section', __( 'Conductor Layouts', 'conductor' ), array( $this, 'conductor_content_layouts_section' ), Conductor_Options::$option_name . '_general' );
			add_settings_field( 'conductor_content_layouts_field', __( 'Select a Layout For:', 'conductor' ), array( $this, 'conductor_content_layouts_field' ), Conductor_Options::$option_name . '_general', 'conductor_content_layouts_section' );

			// Conductor Uninstall
			add_settings_section( 'conductor_uninstall_section', __( 'Uninstall', 'conductor' ), array( $this, 'conductor_uninstall_section' ), Conductor_Options::$option_name . '_advanced' );
			add_settings_field( 'conductor_uninstall_data_field', __( 'Uninstall Data', 'conductor' ), array( $this, 'conductor_uninstall_data_field' ), Conductor_Options::$option_name . '_advanced', 'conductor_uninstall_section' );
		}

		/**
		 * This function renders the Conductor Enable Settings Section.
		 */
		public function conductor_enable_section() {
			Conductor_Admin_Options_Views::conductor_enable_section();
		}

		/**
		 * This function renders the Conductor Enable Settings Field.
		 */
		public function conductor_enable_field() {
			Conductor_Admin_Options_Views::conductor_enable_field();
		}

		/**
		 * This function renders the Conductor Content Layouts Settings Section.
		 */
		public function conductor_content_layouts_section() {
			Conductor_Admin_Options_Views::conductor_content_layouts_section();
		}

		/**
		 * This function renders the Conductor Content Layouts Settings Field.
		 */
		public function conductor_content_layouts_field() {
			Conductor_Admin_Options_Views::conductor_content_layouts_field();
		}

		/**
		 * This function renders the Conductor Uninstall Settings Section.
		 */
		public function conductor_uninstall_section() {
			Conductor_Admin_Options_Views::conductor_uninstall_section();
		}

		/**
		 * This function renders the Conductor Uninstall Data Settings Field.
		 */
		public function conductor_uninstall_data_field() {
			Conductor_Admin_Options_Views::conductor_uninstall_data_field();
		}

		/**
		 * This function renders the Conductor options page.
		 */
		public function render() {
			// Render the main view
			Conductor_Admin_Options_Views::render();
		}

		/**
		 * This function sanitizes the option values before they are stored in the database.
		 */
		public static function sanitize_option( $input ) {
			// Reset to Defaults
			if ( isset( $input['reset'] ) )
				return Conductor_Options::get_option_defaults();

			// Store the raw input values from the user which will be used in certain validation checks
			$raw_input = $input;

			// Parse arguments, replacing defaults with user input
			$input = wp_parse_args( $input, Conductor_Options::get_option_defaults() );

			// Enable Conductor
			$input['enabled'] = ( isset( $raw_input['enabled'] ) && $input['enabled'] ) ? true : false;

			// Content Layouts
			if ( ! empty( $input['content_layouts'] ) && is_array( $input['content_layouts'] ) ) {
				foreach ( $input['content_layouts'] as $id => &$value ) {
					$temp_value = array(); // Store the data temporarily

					// Verify that the content layout data is an array of data
					if ( is_array( $value ) ) {
						$content_type = key( $value ); // Content Type is stored in first key
						$field_name = key( $value[$content_type] ); // Field name is stored in the first key of the $content_type array

						// Content Type
						// TODO: i18n on built-in content types, sidebar name prefixes, and labels
						switch ( $content_type ) {
							// Built-In
							case 'built-in':
								$built_in_content_types = apply_filters( 'conductor_sanitize_option_built_in_content_types', array( 'front_page' => 'Front Page', 'home' => 'Blog' ) );

								// Make sure the content type is valid and modify the $temp_value
								if ( array_key_exists( $field_name, $built_in_content_types ) )
									$temp_value = array(
										'field_label' => $built_in_content_types[$field_name],
										'sidebar_name_prefix' => $built_in_content_types[$field_name],

									);
								// Content type is not valid
								else
									unset( $input['content_layouts'][$id] );
							break;
							// Category
							case 'category':
								// Make sure the content type is valid and modify the $temp_value
								if ( $category = get_category( $field_name ) )
									$temp_value = array(
										'field_label' => 'Category - ' . $category->name,
										'sidebar_name_prefix' => $category->name . ' - Category',
									);
								// Remove this entry as it's not valid
								else
									unset( $input['content_layouts'][$id] );
							break;
							// Post Type
							case 'post-type':
								$post_type = sanitize_text_field( $field_name );
								$public_post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' ); // Public Custom Post Types

								// Make sure the content type is valid and modify the $temp_value
								if ( array_key_exists( $post_type , $public_post_types ) )
									$temp_value = array(
										'field_label' => 'Post Type - ' . $public_post_types[$post_type]->labels->name,
										'sidebar_name_prefix' => $public_post_types[$post_type]->labels->name . ' - Post Type',
									);
								// Remove this entry as it's not valid
								else
									unset( $input['content_layouts'][$id] );
							break;
							// Post
							case 'post':
								// Make sure the content type is valid and modify the $temp_value
								if ( $post = get_post( $field_name ) )
									$temp_value = array(
										'field_label' => 'Post - ' . $post->post_title,
										'sidebar_name_prefix' => $post->post_title . ' - Post',
									);
								// Remove this entry as it's not valid
								else
									unset( $input['content_layouts'][$id] );
								break;
							// Page
							case 'page':
								// Make sure the content type is valid and modify the $temp_value
								if ( $post = get_post( $field_name ) )
									$temp_value = array(
										'field_label' => 'Page - ' . $post->post_title,
										'sidebar_name_prefix' => $post->post_title . ' - Page',
									);
								// Remove this entry as it's not valid
								else
									unset( $input['content_layouts'][$id] );
							break;
							// All other content types
							default:
								/*
								 * Field Type, Field ID, and value will be set and/or sanitized below.
								 *
								 * $temp_value should look similar to the following after validation (see above validation for reference):
								 *
								 * $temp_value = array(
								 * 		'field_label' => 'Content Type - Content Title',
								 * 		'sidebar_name_prefix' => 'Content Title - Content Type',
								 * );
								 */
								$temp_value = apply_filters( 'conductor_sanitize_option_other_content_types', $temp_value, $id, $value, $content_type, $field_name, $input, $raw_input );
							break;
						}

						// Append the rest of the content layout data
						// TODO: Sanitize these values as well?
						$temp_value['field_type'] = $content_type;
						$temp_value['field_id'] = $field_name;

						// This value is used in options panel output to determine selected content layout
						$temp_value['value'] = ( $value[$content_type][$field_name] !== 'default' ) ? sanitize_text_field( $value[$content_type][$field_name] ) : false;

						// Assign the $temp_value to the $value
						$value = apply_filters( 'conductor_sanitize_option_content_layout_value', $temp_value, $id, $value, $content_type, $field_name, $input, $raw_input );
					}
					// Content type is not valid
					else
						unset( $input['content_layouts'][$id] );
				}

				// Reset the array keys
				$input['content_layouts'] = array_values( $input['content_layouts'] );
			}
			// Invalid content_layouts passed
			else {
				$input['content_layouts'] = false;
			}

			// Conductor Uninstall
			$input['uninstall']['data'] = ( isset( $raw_input['uninstall']['data'] ) && $input['uninstall']['data'] ) ? true : false; // Remove Conductor data on uninstall (checking isset() here due to the nested arrays)

			return $input;
		}
	}
}

/**
 * Create an instance of the Conductor_Admin_Options class.
 */
function Conduct_Admin_Options() {
	return Conductor_Admin_Options::instance();
}

Conduct_Admin_Options(); // Conduct your content!