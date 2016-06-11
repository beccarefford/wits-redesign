<?php
/**
 * Conductor Admin Addons
 *
 * @class Conductor_Admin_Addons
 * @author Slocum Studio
 * @version 1.1.0
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if( ! class_exists( 'Conductor_Admin_Addons' ) ) {
	final class Conductor_Admin_Addons {
		/**
		 * @var string
		 */
		public $version = '1.1.0';

		/**
		 * @var string
		 */
		public static $sub_menu_page = 'conductor_page_conductor-addons';

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
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		private function includes() {
			include_once( 'class-conductor-admin-addons-views.php' ); // Conductor Admin Addons View Controller
		}

		/**
		 * This function creates the admin menu item for Conductor admin addons functionality
		 */
		public function admin_menu() {
			$conductor_admin_options_menu_page = str_replace( 'toplevel_page_', '', Conductor_Admin_Options::$menu_page );

			// Conductor Admin License Options Page
			self::$sub_menu_page = add_submenu_page( $conductor_admin_options_menu_page, __( 'Add-ons', 'conductor' ), __( 'Add-ons', 'conductor' ), 'manage_options', 'conductor-addons', array( $this, 'render' ) );

			return self::$sub_menu_page;
		}

		/**
		 * This function enqueues CSS/JavaScript on the Conductor Options Page.
		 */
		function admin_enqueue_scripts( $hook ) {
			// Bail if we're not on the conductor-addons page
			if ( strpos( $hook, 'conductor-addons' ) === false )
				return false;

			$protocol = is_ssl() ? 'https' : 'http';

			// Stylesheet
			wp_enqueue_style( 'conductor-admin', Conductor::plugin_url() . '/assets/css/conductor-admin.css', false, Conductor::$version );

			// Scripts
			wp_enqueue_script( 'conductor-general-admin', Conductor::plugin_url() . '/assets/js/conductor-general-admin.js', array( 'jquery' ), Conductor::$version );
		}


		/**
		 * This function renders the Conductor options page.
		 */
		public function render() {
			// Render the main view
			Conductor_Admin_Addons_Views::render();
		}

		/**
		 * This function fetches a list of add-ons from http://conductorplugin.com.
		 *
		 * @uses Conductor_Updates
		 */
		public static function fetch_addons_list() {
			// Check the transient data first
			if ( ! $addons = get_transient( Conductor_Options::$option_name . '_addons_list' ) ) {
				// Fetch the license
				$license = Conductor_Options::get_options( Conductor_Options::$option_name . '_license' );

				// Conductor Updates instance
				$conductor_updates = Conduct_Updates();

				// Fetch the data
				$args = array(
					'feed' => 'add-ons',
					'license' => $license['key'],
					'url' => home_url()
				);

				$feed = wp_remote_post( $conductor_updates->get_url() . '/edd-api/products/', array( 'timeout' => 15, 'sslverify' => false, 'body' => $args ) );

				// Validate the data
				if ( ! is_wp_error( $feed ) ) {
					$addons = wp_remote_retrieve_body( $feed );
					set_transient( Conductor_Options::$option_name . '_addons_list', $addons, 3600 );
				}
				else
					$addons = '<div class="error"><p>' . __( 'There was an error retrieving the add-ons list. Please try again later.', 'conductor' ) . '</p></div>';
			}

			return $addons;
		}
	}
}

/**
 * Create an instance of the Conductor_Admin_Addons class.
 */
function Conduct_Admin_Addons() {
	return Conductor_Admin_Addons::instance();
}

Conduct_Admin_Addons(); // Conduct your content!