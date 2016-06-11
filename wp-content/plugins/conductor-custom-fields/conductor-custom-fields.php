<?php
/**
 * Plugin Name: Conductor - Custom Fields Add-On
 * Plugin URI: http://www.conductorplugin.com/
 * Description: Add custom fields to Conductor widget output with ease.
 * Version: 1.0.4
 * Author: Slocum Studio
 * Author URI: http://www.slocumstudio.com/
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'Conductor_Custom_Fields_Addon' ) ) {
	final class Conductor_Custom_Fields_Addon {
		/**
		 * @var string
		 */
		public static $version = '1.0.4';

		/**
		 * @var Conductor_Updates, Instance of the Conductor Updates class
		 */
		protected $updater;

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
			// Hooks
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) ); // Plugins Loaded
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		private function includes() {
			// All
			include_once( 'includes/class-conductor-widget-custom-fields.php' ); // Conductor Widget Custom Fields Class

			// Admin Only
			if ( is_admin() ) {}

			// Front-End Only
			if ( ! is_admin() ) {}
		}

		/**
		 * Allow add-on updates.
		 */
		private function updates() {
			// Create a new instance of the Conductor Updates class for this add-on
			if ( class_exists( 'Conductor_Updates' ) )
				$this->updater = new Conductor_Updates( array(
					'version' => Conductor_Custom_Fields_Addon::$version,
					'name' => 'Custom Fields Add-On',
					'plugin_file' => Conductor_Custom_Fields_Addon::plugin_file()
				) );
		}

		/**
		 * This function checks to see if Conductor is enabled.
		 */
		function plugins_loaded() {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			// If Conductor is not active
			if ( ! class_exists( 'Conductor' ) ) {
				// De-activate this plugin
				if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
					deactivate_plugins( plugin_basename( __FILE__ ) );
					unset( $_GET[ 'activate' ] );

					// Show admin notice
					add_action( 'admin_notices', array( $this, 'admin_notices' ) );
				}

			}

			// Load required assets
			$this->includes();

			// Updates
			$this->updates();
		}

		/**
		 * This function outputs an admin notice if Conductor is not active
		 */
		function admin_notices() {
		?>
			<div class="updated error">
				<p><?php printf( __( 'Conductor Custom Fields Add-on requires Conductor. Please install &amp; activate Conductor and try again.', 'conductor-cf' ) ); ?></p>
			</div>
		<?php
		}


		/********************
		 * Helper Functions *
		 ********************/

		/**
		 * This function returns the plugin url for Conductor without a trailing slash.
		 *
		 * @return string, URL for the Conductor plugin
		 */
		public static function plugin_url() {
			return untrailingslashit( plugins_url( '', __FILE__ ) );
		}

		/**
		 * This function returns the plugin directory for Conductor without a trailing slash.
		 *
		 * @return string, Directory for the Conductor plugin
		 */
		public static function plugin_dir() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * This function returns a reference to this Conductor class file.
		 *
		 * @return string
		 */
		public static function plugin_file() {
			return __FILE__;
		}
	}
}

/**
 * Create an instance of the Conductor_Custom_Fields_Addon class.
 */
function Conduct_Custom_Fields_Addon() {
	return Conductor_Custom_Fields_Addon::instance();
}

Conduct_Custom_Fields_Addon(); // Conduct your content!