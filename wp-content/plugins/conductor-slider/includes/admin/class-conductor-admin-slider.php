<?php
/**
 * Conductor Slider Admin
 *
 * @class Conductor_Admin_Slider
 * @author Slocum Studio
 * @version 1.0.0
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if( ! class_exists( 'Conductor_Admin_Slider' ) ) {
	class Conductor_Admin_Slider {
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
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); // Enqueue admin scripts

			// Displays
			add_action( 'conductor_widget_settings_display_preview_slider-testimonials', array( $this,  'conductor_widget_settings_display_preview_slider_testimonials' ), 10, 2 ); // Slider - Testimonials
			add_action( 'conductor_widget_settings_display_preview_slider-hero', array( $this,  'conductor_widget_settings_display_preview_slider_hero' ), 10, 2 ); // Slider - Hero
			add_action( 'conductor_widget_settings_display_preview_slider-news', array( $this,  'conductor_widget_settings_display_preview_slider_news' ), 10, 2 ); // Slider - News
		}


		/**
		 * This function enqueues the necessary scripts and styles associated with this widget on admin.
		 */
		function admin_enqueue_scripts( $hook ) {
			// Only on Widgets Admin Page
			if ( $hook === 'widgets.php' )
				// Conductor Slider Admin Stylesheet
				wp_enqueue_style( 'conductor-slider-admin', Conductor_Slider_Addon::plugin_url() . '/assets/css/conductor-slider-admin.css', array( 'conductor-widget-admin' ) );
		}

		/**
		 * This function outputs the markup for the Slider Testimonials Conductor Widget size/display.
		 */
		function conductor_widget_settings_display_preview_slider_testimonials( $instance, $widget ) {
		?>
			<div class="posts posts-slider-testimonials">
				<div class="post post-slider-testimonials">
					<div class="slider-testimonials-users dashicons dashicons-admin-users"></div>
					<div class="slider-testimonials-quote dashicons dashicons-editor-quote"></div>
				</div>
			</div>
		<?php
		}

		/**
		 * This function outputs the markup for the Slider Hero Conductor Widget size/display.
		 */
		function conductor_widget_settings_display_preview_slider_hero( $instance, $widget ) {
		?>
			<div class="posts posts-slider-hero">
				<div class="post post-slider-hero">
					<div class="slider-hero-featured-image featured-image post-featured-image"></div>
					<div class="slider-hero-title slider-hero-post-title title post-title dashicons dashicons-minus"></div>
					<div class="slider-hero-content slider-hero-post-content content post-content dashicons dashicons-text"></div>
				</div>
			</div>
		<?php
		}

		/**
		 * This function outputs the markup for the Slider News Conductor Widget size/display.
		 */
		function conductor_widget_settings_display_preview_slider_news( $instance, $widget ) {
		?>
			<div class="posts posts-slider-news">
				<div class="post post-slider-news">
					<div class="slider-news-slides dashicons dashicons-slides"></div>
				</div>
			</div>
		<?php
		}
	}
}

/**
 * Create an instance of the Conductor_Admin_Slider class.
 */
function Conduct_Admin_Slider() {
	return Conductor_Admin_Slider::instance();
}

Conduct_Admin_Slider(); // Conduct your content!