<?php
/**
 * Conductor Slider Customizer
 *
 * @class Conductor_Slider_Customizer
 * @author Slocum Studio
 * @version 1.0.0
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'Conductor_Slider_Customizer' ) ) {
	class Conductor_Slider_Customizer {
		/**
		 * @var string
		 */
		public $version = '1.0.0';

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
		function __construct( $args = array() ) {
			// Hooks
			add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) ); // Customize Preview Init
		}


		/**
		 * This function fires on the initialization of the Customizer Previewer. We add actions that pertain to the
		 * Customizer preview window here. The actions added here are fired only in the Customizer Previewer.
		 */
		public function customize_preview_init() {
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) ); // Enqueue Scripts & Styles
		}

		/**
		 * This function enqueues scripts & styles on the front-end for Conductor Widget Displays.
		 */
		public function wp_enqueue_scripts() {
			// Grab the Conductor Widget instance
			$conductor_widget = Conduct_Widget();

			// Unslider (only enqueue script if at least one Conductor Widget is active)
			if ( is_active_widget( false, false, $conductor_widget->id_base, true ) ) {
				// Unslider.js
				wp_enqueue_script( 'conductor-slider-customizer-preview', Conductor_Slider_Addon::plugin_url() . '/assets/js/conductor-slider-customizer-preview.js', array( 'customize-preview-widgets' ), Conductor_Slider_Addon::$version );
			}
		}
	}
}

/**
 * Create an instance of the Conductor_Slider_Customizer class.
 */
function Conduct_Slider_Customizer() {
	return Conductor_Slider_Customizer::instance();
}

Conduct_Slider_Customizer(); // Conduct your content!