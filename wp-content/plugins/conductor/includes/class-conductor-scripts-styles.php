<?php
/**
 * Conductor Scripts & Styles
 *
 * @class Conductor_Options
 * @author Slocum Studio
 * @version 1.1.2
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if( ! class_exists( 'Conductor_Scripts_Styles' ) ) {
	final class Conductor_Scripts_Styles {
		/**
		 * @var string
		 */
		public $version = '1.1.2';

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
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) ); // Enqueue Scripts & Styles (front-end)
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); // Enqueue Scripts & Styles (admin)
		}


		/**
		 * This function enqueues scripts & styles on the front-end for Conductor.
		 */
		// TODO: Minify/consolidate all scripts
		public function wp_enqueue_scripts() {
			// Conductor Content Layout Styles
			if ( Conductor::is_conductor() )
				wp_enqueue_style( 'conductor', Conductor::plugin_url() . '/assets/css/conductor.css', false, Conductor::$version );

			// Conductor Widget Styles (only enqueue styles if this widget is active)
			if ( function_exists( 'Conduct_Widget' ) ) {
				// Grab the Conductor Widget instance
				$conductor_widget = Conduct_Widget();

				if ( is_active_widget( false, false, $conductor_widget->id_base, true ) ) {
					// Conductor Flexbox
					wp_enqueue_style( 'conductor-flexbox', Conductor::plugin_url() . '/assets/css/conductor-flexbox.css', false, Conductor::$version );

					// Conductor Widget
					wp_enqueue_style( 'conductor-widget', Conductor::plugin_url() . '/assets/css/widgets/conductor-widget.css', array( 'conductor-flexbox' ), Conductor::$version );
				}
			}

			// WordPress 3.7 and lower
			// TODO: Remove/depreciate
			if ( Conductor::wp_version_compare( '3.7', '<=' ) ) {
				// Dashicons (bundled)
				wp_enqueue_style( 'dashicons', Conductor::plugin_url() . '/assets/css/dashicons.css' );

				wp_enqueue_style( 'conductor-wp-backwards-compat', Conductor::plugin_url() . '/assets/css/conductor-wp-compat.css' );
			}
		}

		/**
		 * This function enqueues scripts & styles on the admin for Conductor.
		 */
		public function admin_enqueue_scripts() {
			// WordPress 3.7 and lower
			if ( Conductor::wp_version_compare( '3.7', '<=' ) ) {
				// Dashicons (bundled)
				wp_enqueue_style( 'dashicons', Conductor::plugin_url() . '/assets/css/dashicons.css' );

				wp_enqueue_style( 'conductor-wp-backwards-compat', Conductor::plugin_url() . '/assets/css/conductor-wp-compat.css' );
			}
		}
	}
}

/**
 * Create an instance of the Conductor_Scripts_Styles class.
 */
function Conduct_Scripts_Styles() {
	return Conductor_Scripts_Styles::instance();
}

Conduct_Scripts_Styles(); // Conduct your content!