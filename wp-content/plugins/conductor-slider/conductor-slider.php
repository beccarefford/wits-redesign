<?php
/**
 * Plugin Name: Conductor - Slider Add-On
 * Plugin URI: https://www.conductorplugin.com/
 * Description: Display your content in a slider with different display types.
 * Version: 1.0.0
 * Author: Slocum Studio
 * Author URI: http://www.slocumstudio.com/
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'Conductor_Slider_Addon' ) ) {
	final class Conductor_Slider_Addon {
		/**
		 * @var string
		 */
		public static $version = '1.0.0';

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
		function __construct( ) {
			// Hooks
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) ); // Plugins Loaded
			add_action( 'widgets_init', array( $this, 'widgets_init' ) ); // Widgets Init
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		private function includes() {
			// All
			include_once 'includes/class-conductor-slider.php'; // Conductor Slider Class
			include_once 'includes/class-conductor-slider-customizer.php'; // Conductor Slider Customizer Class

			// Admin Only
			if ( is_admin() )
				include_once 'includes/admin/class-conductor-admin-slider.php'; // Conductor Slider Admin Class

			// Front-End Only
			//if ( ! is_admin() )
		}

		/**
		 * Allow add-on updates.
		 */
		private function updates() {
			// Create a new instance of the Conductor Updates class for this add-on
			if ( class_exists( 'Conductor_Updates' ) )
				$this->updater = new Conductor_Updates( array(
					'version' => Conductor_Slider_Addon::$version,
					'name' => 'Slider Add-On',
					'plugin_file' => Conductor_Slider_Addon::plugin_file()
				) );
		}

		/**
		 * This function checks to see if Conductor is enabled.
		 */
		function plugins_loaded() {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			// If Conductor is not active
			if ( ! class_exists( 'Conductor' ) ) {
				// De-activate this plugin
				if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
					deactivate_plugins( plugin_basename( __FILE__ ) );
					unset( $_GET[ 'activate' ] );

					// Show admin notice
					add_action( 'admin_notices', array( $this, 'admin_notices' ) );
				}

				return false;
			}

			/*
			 * Conductor exists
			 */

			// Load required assets
			$this->includes();

			// Updates
			$this->updates();
		}

		/**
		 * This function checks to see if Conductor is enabled when widgets are initialized.
		 */
		function widgets_init() {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			// If Conductor is not the right version
			if ( ! $this->conductor_has_flexbox_display( true ) ) {
				// De-activate this plugin
				if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
					deactivate_plugins( plugin_basename( __FILE__ ) );
					unset( $_GET[ 'activate' ] );

					// Show admin notice
					add_action( 'admin_notices', array( $this, 'admin_notices' ) );
				}

				return false;
			}
		}

		/**
		 * This function outputs an admin notice if Conductor is not active or is not the right version.
		 */
		function admin_notices() {
		?>
			<div class="updated error">
				<p><?php printf( __( 'Conductor Slider Add-On requires at least Conductor v1.3.0. Please install &amp; activate Conductor v.1.3.0 (or above) and try again.', 'conductor-slider' ) ); ?></p>
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


		/**********************
		 * Internal Functions *
		 **********************/

		/**
		 * This function checks to see if Conductor has the new flexbox display.
		 */
		public function conductor_has_flexbox_display( $include_conductor_widget = false, $conductor_widget = false ) {
			// Only if Conductor exists
			if ( $include_conductor_widget && class_exists( 'Conductor' ) && ! function_exists( 'Conduct_Widget' ) && file_exists( Conductor::plugin_dir() . '/includes/widgets/class-conductor-widget.php' ) )
				// Conductor Widget
				include_once Conductor::plugin_dir() . '/includes/widgets/class-conductor-widget.php';

			// If we don't have a Conductor Widget reference, grab one now
			$conductor_widget = ( ! $conductor_widget && function_exists( 'Conduct_Widget' ) ) ? Conduct_Widget() : $conductor_widget;

			// If Conductor is greater than 1.2.9 or Conductor Widget instance has the "displays" property, we can check to see if the custom display exists
			return ( $conductor_widget && ( version_compare( Conductor::$version, '1.2.9', '>' ) || property_exists( $conductor_widget, 'displays' ) ) && isset( $conductor_widget->displays['flexbox'] ) );
		}
	}
}

/**
 * Create an instance of the Conductor_Slider_Addon class.
 */
function Conduct_Slider_Addon() {
	return Conductor_Slider_Addon::instance();
}

Conduct_Slider_Addon(); // Conduct your content!