<?php
/**
 * Conductor Slider
 *
 * @class Conductor_Slider
 * @author Slocum Studio
 * @version 1.0.0
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'Conductor_Slider' ) ) {
	class Conductor_Slider {
		/**
		 * @var string
		 */
		public $version = '1.0.0';

		/**
		 * @var array
		 */
		public $conductor_widget_displays = array();

		/**
		 * @var array
		 */
		public $current_widget_args = array();

		/**
		 * @var Boolean
		 */
		public $is_last_post = false;

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
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) ); // Enqueue Scripts & Styles
			add_filter( 'dynamic_sidebar_params', array( $this, 'dynamic_sidebar_params' ), 9999 ); // Dynamic Sidebar Parameters (late)
			add_action( 'wp_footer', array( $this, 'wp_footer' ) );
			add_filter( 'conductor_widget_displays', array( $this, 'conductor_widget_displays' ), 10, 3 );
			add_filter( 'conductor_widget_update', array( $this, 'conductor_widget_update' ), 10, 3 );
			add_filter( 'conductor_widget_instance', array( $this, 'conductor_widget_instance' ), 9999, 3 ); // Adjust callback functions upon Conductor Widget display (late)
			add_filter( 'conductor_widget_content_wrapper_css_classes', array( $this, 'conductor_widget_content_wrapper_css_classes' ), 9999, 5 ); // Adjust the CSS classes for the content wrapper HTML element on Conductor Widgets (late)
			add_action( 'conductor_widget_display_content', array( $this, 'conductor_widget_display_content' ), 10, 4 ); // Conductor Widget output
			add_action( 'conductor_widget_after', array( $this, 'conductor_widget_after' ) );

			// Conductor Widget Output
			add_action( 'conductor_widget_author_byline_before', array( $this, 'conductor_widget_author_byline_before' ), 10, 2 ); // Author Byline before
			add_action( 'conductor_widget_author_byline_after', array( $this, 'conductor_widget_author_byline_after' ), 10, 2 ); // Author Byline after
			add_action( 'conductor_widget_read_more_before', array( $this, 'conductor_widget_read_more_before' ), 10, 2 ); // Read More before
			add_action( 'conductor_widget_read_more_after', array( $this, 'conductor_widget_read_more_after' ), 10, 2 ); // Read More after
		}


		/**
		 * This function enqueues scripts & styles on the front-end for Conductor Widget Displays.
		 */
		public function wp_enqueue_scripts() {
			// Grab the Conductor Widget instance
			$conductor_widget = Conduct_Widget();

			// Unslider (only enqueue script if at least one Conductor Widget is active)
			if ( is_active_widget( false, false, $conductor_widget->id_base, true ) ) {
				// Determine current protocol
				$protocol = is_ssl() ? 'https' : 'http';

				// Conductor Slider
				wp_enqueue_style( 'conductor-slider', Conductor_Slider_Addon::plugin_url() . '/assets/css/conductor-slider.css', array( 'conductor-widget' ), Conductor_Slider_Addon::$version );

				// FontAwesome
				wp_enqueue_style( 'conductor-slider-fontawesome', Conductor_Slider_Addon::plugin_url() . '/assets/css/font-awesome.min.css', false, Conductor_Slider_Addon::$version );

				// Web Fonts
				wp_enqueue_style( 'conductor-slider-google-web-fonts', $protocol . '://fonts.googleapis.com/css?family=Lato:400,700,900', false, Conductor_Slider_Addon::$version );

				// Unslider.js
				wp_enqueue_script( 'conductor-slider-unslider', Conductor_Slider_Addon::plugin_url() . '/assets/js/unslider.min.js', array( 'jquery' ), Conductor_Slider_Addon::$version );

				/*
				 * Unslider.js config
				 *
				 * @see https://github.com/idiot/unslider
				 *
				 * The following is a list of default configuration parameters:
				 *
				 * speed: 500,     // animation speed, false for no transition (integer or boolean)
				 * delay: 3000,    // delay between slides, false for no autoplay (integer or boolean)
				 * init: 0,        // init delay, false for no delay (integer or boolean)
				 * pause: !f,      // pause on hover (boolean)
				 * loop: !f,       // infinitely looping (boolean)
				 * keys: f,        // keyboard shortcuts (boolean)
				 * dots: f,        // display dots pagination (boolean)
				 * arrows: f,      // display prev/next arrows (boolean)
				 * prev: '&larr;', // text or html inside prev button (string)
				 * next: '&rarr;', // same as for prev option
				 * fluid: f,       // is it a percentage width? (boolean)
				 * starting: f,    // invoke before animation (function with argument)
				 * complete: f,    // invoke after animation (function with argument)
				 * items: '>ul',   // slides container selector
				 * item: '>li',    // slidable items selector
				 * column_item: false, // if columns exist within item
				 * row_item: false, // if rows exist within item (or if false, the row item defaults to item)
				 * use_item_width_until: 768, // use the item width until this value, then use the column_item width
				 * easing: 'swing',// easing function to use for animation
				 * autoplay: true,  // enable autoplay on initialisation
				 * resize_timeout: 50 // timeout for the window resize event
				 * reinit: !f, // slider will re-initialize at use_item_width_until (boolean)
				 * max_slide_height: f // slideshow will inherit the maximum height of the tallest slide across all slides (boolean)
				 */
				wp_localize_script( 'conductor-slider-unslider', 'conductor_slider_config', apply_filters( 'conductor_slider_unslider_config', array(
						'displays' => array(
							// Slider - Testimonials
							'slider-testimonials' => array(
								'delay' => false,
								'arrows' => true,
								'prev' => '<span class="fa fa-chevron-left"></span>',
								'next' => '<span class="fa fa-chevron-right"></span>',
								'fluid' => true,
								'autoplay' => false,
								'items' => '.conductor-rows',
								'item' => '.conductor-row',
								'column_item' => '.conductor-col',
								'max_slide_height' => true
							),
							// Slider - Hero
							'slider-hero' => array(
								'delay' => false,
								'arrows' => true,
								'prev' => '<span class="fa fa-chevron-left"></span>',
								'next' => '<span class="fa fa-chevron-right"></span>',
								'fluid' => true,
								'autoplay' => false,
								'items' => '.conductor-rows',
								'item' => '.conductor-row',
								'reinit' => false
							),
							// Slider - News
							'slider-news' => array(
								'delay' => false,
								'dots' => true,
								'fluid' => true,
								'autoplay' => false,
								'items' => '.conductor-rows',
								'item' => '.conductor-row',
								'column_item' => '.conductor-col',
								'max_slide_height' => true
							),
						)
					) )
				);
			}
		}

		/**
		 * This function adjusts the parameters for widgets within the Conductor content sidebar.
		 */
		public function dynamic_sidebar_params( $params ) {
			// Bail if we're not on the front-end
			if ( is_admin() )
				return $params;

			// Grab the Conductor Widget instance
			$conductor_widget = Conduct_Widget();

			// Conductor Widgets only
			if ( $conductor_widget->id_base === _get_widget_id_base( $params[0]['widget_id'] ) ) {
				// Store a reference to the widget settings (all Conductor Widgets)
				$conductor_widget_settings = $conductor_widget->get_settings();

				// Determine if this is a valid Conductor widget
				if ( array_key_exists( $params[1]['number'], $conductor_widget_settings ) ) {
					// Grab this widget's settings
					$instance = $conductor_widget_settings[$params[1]['number']];

					// Conductor Slider displays
					if ( $this->is_conductor_slider_display( $instance['widget_size'] ) ) {
						// If we don't already have the row element data
						if ( ! isset( $this->current_widget_args['before_widget_el_name'] ) ) {
							$this->current_widget_args = $params[0];

							// Grab the row (before_widget) element
							preg_match( '/<([a-zA-Z]+)/', $this->current_widget_args['before_widget'], $before_widget_pieces );
							//$this->current_widget_args['before_widget_el'] = $before_widget_pieces[0]; // [0] is the first HTML element
							$this->current_widget_args['before_widget_el_name'] = $before_widget_pieces[1]; // [1] is the first HTML element name, no markup

							// Grab the "row" (before_widget) attributes
							preg_match_all( '/(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?/', $this->current_widget_args['before_widget'], $before_widget_attributes );
							$this->current_widget_args['before_widget_attributes'] = $before_widget_attributes;
						}

						// Base Conductor Slider CSS classes
						$css_classes = array(
							'conductor-widget', // Conductor
							'conductor-widget-wrap', // Conductor
							'conductor-widget-slider-wrap',
							'conductor-' . $instance['widget_size'] . '-wrap',
							'conductor-' . $instance['widget_size'] . '-widget-wrap',
							'conductor-' . $instance['widget_size'] . '-slider-wrap',
							'conductor-' . $instance['widget_size'] . '-widget-slider-wrap',
							'conductor-flex' // Conductor Flexbox
						);

						// Add the instance CSS class
						if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
							$instance_css_classes = explode( ' ', $instance['css_class'] );

							// Loop through instance CSS classes
							foreach ( $instance_css_classes as $instance_css_class )
								// Add this class to the list of classes (adding "slider-wrap" suffix)
								$css_classes[] = str_replace( '.', '', $instance_css_class ) . $instance['widget_size'] . '-slider-wrap';
						}

						// Sanitize CSS classes
						$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

						// Allow filtering of CSS classes
						$css_classes = apply_filters( 'conductor_slider_widget_before_widget_css_classes', $css_classes, $params, $instance, $conductor_widget_settings, $conductor_widget );
						$css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_before_widget_css_classes', $css_classes, $params, $instance, $conductor_widget_settings, $conductor_widget );

						// Add wrapper element
						$params[0]['before_widget'] = '<div class="' . esc_attr( implode( ' ', $css_classes ) ) . '">'; // . $params[0]['before_widget'];

						// Add closing wrapper closing element
						$params[0]['after_widget'] = '</div>';

						/*
						 * Since this logic runs after the Conductor core Customizer logic, we have to
						 * ensure hidden <input> elements are prepended to the after_widget element so
						 * that the Conductor UI logic will function properly.
						 */

						// If we're on a Conductor content template in the Customizer (front-end)
						if ( is_customize_preview() && ! is_admin() && function_exists( 'Conduct_Customizer' ) ) {
							// Grab the Conductor Customizer instance
							$conductor_customizer = Conduct_Customizer();

							// Send the parameters through Conductor_Customizer::dynamic_sidebar_params() to add the hidden input elements
							$params = $conductor_customizer->dynamic_sidebar_params( $params );
						}
					}
				}
			}

			return $params;
		}

		/**
		 * This function outputs the slider initialization script for Conductor Widgets with Conductor Slider displays.
		 */
		public function wp_footer() {
		?>
			<script type="text/javascript">
				// <![CDATA[
				jQuery( function( $ ) {
					var $conductor_slider = $( '.conductor-slider' );

					// If we have Conductor Widgets with Conductor Slider displays
					if ( $conductor_slider.length ) {
						// Loop through each slider
						$conductor_slider.each( function () {
							var $this = $( this ),
								type = $this.data( 'conductor-slider-type' ),
								feature_many = $this.data( 'conductor-slider-feature-many' );

							// If we have a Conductor Slider type
							if ( feature_many && type && conductor_slider_config.displays.hasOwnProperty( type ) ) {
								// Create the unslider instance
								$this.unslider( conductor_slider_config.displays[type] );
							}
						} );
					}
				} );
				// ]]>
			</script>
		<?php
		}

		/**
		 * This function adds to the list of Conductor widget layouts displays. It also stores references
		 * of all Conductor Slider widget sizes (displays) in Conductor_Slider::$conductor_widget_displays.
		 */
		public function conductor_widget_displays( $conductor_widget_displays, $widget_instance, $widget ) {
			// Slider - Testimonials
			$conductor_widget_displays['slider-testimonials'] = array(
				'label' => __( 'Slider: Testimonials', 'conductor-slider' ),
				'customize' => array(
					//'columns' => true, // Columns
					'columns' => array(
						'single' => false, // Single content piece query
						'many' => true, // Many content pieces query
					)
				),
				'defaults' => array(
					'columns' => 2,
					'output' => array(
						'author_byline' => array(
							'visible' => false
						),
						'read_more' => array(
							'visible' => false
						)
					),
					'post_thumbnails_size' => 'thumbnail'
				)
			);

			// Slider - Hero
			$conductor_widget_displays['slider-hero'] = array(
				'label' => __( 'Slider: Hero', 'conductor-slider' ),
				'defaults' => array(
					'output' => array(
						'featured_image' => array(
							'link' => false
						)
					),
					'post_thumbnails_size' => 'large'
				)
			);

			// Slider - News
			$conductor_widget_displays['slider-news'] = array(
				'label' => __( 'Slider: News', 'conductor-slider' ),
				'customize' => array(
					//'columns' => true, // Columns
					'columns' => array(
						'single' => false, // Single content piece query
						'many' => true, // Many content pieces query
					)
				),
				'defaults' => array(
					'columns' => 3,
					'output' => array(
						'author_byline' => array(
							'visible' => false
						),
						'post_content' => array(
							'visible' => false
						),
						'read_more' => array(
							'visible' => false
						)
					),
					'post_thumbnails_size' => 'large'
				)
			);

			// Store references of the slider displays on this class
			$this->conductor_widget_displays['slider-testimonials'] = $conductor_widget_displays['slider-testimonials'];
			$this->conductor_widget_displays['slider-hero'] = $conductor_widget_displays['slider-hero'];
			$this->conductor_widget_displays['slider-news'] = $conductor_widget_displays['slider-news'];

			// Conductor Slider Widget Displays
			$this->conductor_widget_displays = apply_filters( 'conductor_slider_widget_displays', $this->conductor_widget_displays, $this );

			return $conductor_widget_displays;
		}

		/**
		 * This function adjusts various settings upon save of a Conductor Widget with Conductor Slider displays.
		 */
		public function conductor_widget_update( $new_instance, $old_instance, $widget ) {
			// Bail if this isn't a Conductor Slider display
			if ( ! $this->is_conductor_slider_display( $new_instance['widget_size'] ) )
				return $new_instance;

			// Remove pagination
			$new_instance['posts_per_page'] = $widget->defaults['query_args']['posts_per_page'];
			$new_instance['query_args']['posts_per_page'] = $widget->defaults['query_args']['posts_per_page'];

			return $new_instance;
		}



		/**
		 * This function adjusts the callback functions for various output elements.
		 */
		public function conductor_widget_instance( $instance, $args, $widget ) {
			// Bail if this isn't a Conductor Slider display
			if ( ! $this->is_conductor_slider_display( $instance['widget_size'] ) )
				return $instance;

			// Adjust the callback on the output elements
			foreach ( $instance['output'] as $priority => &$element )
				// If a callback function exists on this class that matches conductor_widget_$element['id'], change the callback
				if ( method_exists( $this, 'conductor_widget_' . $element['id'] ) && is_callable( array( $this, 'conductor_widget_' . $element['id'] ) ) )
					$element['callback'] = array( $this, 'conductor_widget_' . $element['id'] );

			return $instance;
		}

		/**
		 * This function adjusts the CSS classes on the content wrapper element.
		 */
		public function conductor_widget_content_wrapper_css_classes( $css_classes, $post, $instance, $widget, $query ) {
			// Bail if this isn't a Conductor Slider display
			if ( ! $this->is_conductor_slider_display( $instance['widget_size'] ) )
				return $css_classes;

			// Base Conductor Slider CSS classes
			$css_classes = array(
				'conductor-slider-content',
				'conductor-widget-slider-content',
				'conductor-' . $instance['widget_size'],
				'conductor-' . $instance['widget_size'] . '-content',
				'conductor-' . $instance['widget_size'] . '-slider-content',
				'conductor-widget-' . $instance['widget_size'] . '-content',
				'conductor-widget-' . $instance['widget_size'] . '-slider-content',
				'conductor-cf' // Conductor
			);

			// Add the instance CSS class
			if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
				$instance_css_classes = explode( ' ', $instance['css_class'] );

				// Loop through instance CSS classes
				foreach ( $instance_css_classes as $instance_css_class )
					// Add this class to the list of classes (adding "wrap" suffix)
					$css_classes[] = str_replace( '.', '', $instance_css_class ) . '-single-' . $instance['widget_size'] . '-wrap';
			}

			// Sanitize CSS classes
			$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

			// Allow filtering of CSS classes
			$css_classes = apply_filters( 'conductor_slider_widget_content_wrapper_css_classes', $css_classes, $instance, $post, $query, $widget );
			$css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_content_wrapper_css_classes', $css_classes, $instance, $post, $query, $widget );

			$css_classes = implode( ' ', $css_classes );

			return $css_classes;
		}

		/**
		 * This function outputs row HTML elements for Conductor Widgets with Conductor Slider displays.
		 */
		public function conductor_widget_display_content( $post, $instance, $widget, $conductor_widget_query ) {
			// Bail if this isn't a Conductor Slider display
			if ( ! $this->is_conductor_slider_display( $instance['widget_size'] ) )
				return;

			// Grab the widget display config
			$widget_display_config = $this->conductor_widget_displays[$instance['widget_size']];

			// Grab the query reference
			$widget_query = $conductor_widget_query->get_query();

			/*
			 * Logic to determine row data
			 */
			$current_post = ( property_exists( $widget_query, 'current_post' ) ) ? $widget_query->current_post : -1;
			$total_posts = ( property_exists( $widget_query, 'post_count' ) ) ? $widget_query->post_count : -1;

			// If the current widget size (display) supports columns
			if ( $widget->widget_display_supports_customize_property( $widget_display_config, 'columns' ) ) {
				$columns = $instance['flexbox']['columns'];
				$total_rows = ceil( $instance['max_num_posts'] / $columns );
				$current_row = ( $current_post !== -1 ) ? ceil( ( $current_post + 1 ) / $columns ) : 1; // Default to row 1

				// Single query
				if ( ! $instance['feature_many'] )
					$is_new_row = true; // Flag to determine if there should be a new row (always in this case)
				// Many query
				else
					$is_new_row = ( ( $total_rows > 1 ) && $current_post !== -1 && ( ( $current_post % $columns ) === 0 || $current_post === 0 ) ) ? true : false; // Flag to determine if there should be a new row
			}
			// Otherwise, this widget size (display) doesn't support customization of columns
			else {
				$current_row = ( $current_post !== -1 ) ? $current_post + 1 : 1; // Default to row 1

				$is_new_row = true; // Flag to determine if there should be a new row (always in this case)
			}

			// Last post (on single posts this is always the last row)
			if ( ( $current_post + 1 ) === $total_posts || ! $instance['feature_many'] )
				// Close the row (set a flag for conductor_after_widget)
				$this->is_last_post = true;

			/*
			 * Conductor Widgets automatically output one row element for widget displays that support
			 * column adjustments. We're outputting row HTML markup after each "row" (current post vs columns)
			 * has been output.
			 */

			// If there should be a new row output here
			if ( $is_new_row ) :
				$before_widget_atts = array();

				// Loop through attribute names ($before_widget_attributes[1] is an array of attribute names)
				// TODO: Edge case: class attribute doesn't exist on original wrapper element
				foreach ( $this->current_widget_args['before_widget_attributes'][1] as $index => $attribute_name )
					// Switch based on name of attribute
					switch ( strtolower( $attribute_name ) ) {
						// ID
						case 'id':
							// $before_widget_attributes[2] is an array of the attribute values (corresponding with $before_widget_attributes[2])
							$before_widget_atts[$attribute_name] = $this->current_widget_args['before_widget_attributes'][2][$index] . '-row-' . $current_row;
						break;

						// Class
						case 'class':
							// $before_widget_attributes[2] is an array of the attribute values (corresponding with $before_widget_attributes[2])
							$before_widget_atts[$attribute_name] = explode( ' ', $this->current_widget_args['before_widget_attributes'][2][$index] );

							// Base Conductor Slider CSS classes (merge with existing CSS classes)
							$css_classes = array_merge( $before_widget_atts[$attribute_name], array(
								'conductor-slider-row',
								'conductor-widget-slider-row'
							) );

							// Add the "conductor-row" CSS class if it doesn't exist
							if ( ! in_array( 'conductor-row', $css_classes ) )
								$css_classes[] = 'conductor-row';

							// Add the "conductor-widget-row" CSS class if it doesn't exist
							if ( ! in_array( 'conductor-widget-row', $css_classes ) )
								$css_classes[] = 'conductor-widget-row';

							// Add the instance CSS class
							if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
								$instance_css_classes = explode( ' ', $instance['css_class'] );

								// Loop through instance CSS classes
								foreach ( $instance_css_classes as $instance_css_class )
									// Add this class to the list of classes (adding "slider-row" suffix)
									$css_classes[] = str_replace( '.', '', $instance_css_class ) . '-slider-row';
							}

							// Sanitize CSS classes
							$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

							// Allow filtering of CSS classes
							$css_classes = apply_filters( 'conductor_slider_widget_row_css_classes', $css_classes, $instance, $widget, $conductor_widget_query );
							$css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_row_css_classes', $css_classes, $instance, $widget, $conductor_widget_query );

							// Implode the CSS classes and store them for future use
							$before_widget_atts[$attribute_name] = implode( ' ', $css_classes );
						break;

						// Default
						default:
							// $before_widget_attributes[2] is an array of the attribute values (corresponding with $before_widget_attributes[2])
							$before_widget_atts[$attribute_name] = $this->current_widget_args['before_widget_attributes'][2][$index];
						break;
					}

				// Store attributes in current widget arguments
				$this->current_widget_args['before_widget_atts'] = $before_widget_atts;

				// Switch based on current row
				switch ( $current_row ) :
					// First
					case 1:
						// Open wrapper elements and a new row

						// Base slider CSS classes
						$slider_wrap_css_classes = array(
							'conductor-slider',
							'conductor-widget-slider',
							'conductor-slider-wrap',
							'conductor-widget-slider-wrap',
							'conductor-' . $instance['widget_size'] .'-slider',
							'conductor-widget-' . $instance['widget_size'] .'-slider',
							'conductor-' . $instance['widget_size'] .'-slider-wrap',
							'conductor-widget-' . $instance['widget_size'] .'-slider-wrap'
						);

						// Base rows CSS classes
						$rows_wrap_css_classes = array(
							'conductor-rows',
							'conductor-widget-rows',
							'conductor-rows-wrap',
							'conductor-widget-rows-wrap',
							'conductor-slider-rows',
							'conductor-widget-slider-rows',
							'conductor-slider-rows-wrap',
							'conductor-widget-slider-rows-wrap',
							'conductor-' . $instance['widget_size'] .'-rows',
							'conductor-widget-' . $instance['widget_size'] .'-rows',
							'conductor-' . $instance['widget_size'] .'-slider-rows',
							'conductor-widget-' . $instance['widget_size'] .'-slider-rows'
						);

						// Single slider/row CSS classes
						if ( ! $instance['feature_many'] ) {
							// Slider
							$slider_wrap_css_classes[] = 'conductor-slider-single';
							$slider_wrap_css_classes[] = 'conductor-widget-slider-single';
							$slider_wrap_css_classes[] = 'conductor-slider-single-wrap';
							$slider_wrap_css_classes[] = 'conductor-widget-slider-single-wrap';
							$slider_wrap_css_classes[] = 'conductor-' . $instance['widget_size'] .'-single-slider';
							$slider_wrap_css_classes[] = 'conductor-widget-' . $instance['widget_size'] .'-single-slider';
							$slider_wrap_css_classes[] = 'conductor-' . $instance['widget_size'] .'-single-slider-wrap';
							$slider_wrap_css_classes[] = 'conductor-widget-' . $instance['widget_size'] .'-single-slider-wrap';

							// Row
							$rows_wrap_css_classes[] = 'conductor-row-single';
							$rows_wrap_css_classes[] = 'conductor-widget-row-single';
							$rows_wrap_css_classes[] = 'conductor-row-single-wrap';
							$rows_wrap_css_classes[] = 'conductor-widget-row-single-wrap';
							$rows_wrap_css_classes[] = 'conductor-' . $instance['widget_size'] .'-single-rows';
							$rows_wrap_css_classes[] = 'conductor-widget-' . $instance['widget_size'] .'-single-row';
							$rows_wrap_css_classes[] = 'conductor-' . $instance['widget_size'] .'-single-rows-wrap';
							$rows_wrap_css_classes[] = 'conductor-widget-' . $instance['widget_size'] .'-single-row-wrap';
						}


						// Add the instance CSS class
						if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
							$instance_css_classes = explode( ' ', $instance['css_class'] );

							// Loop through instance CSS classes
							foreach ( $instance_css_classes as $instance_css_class )
								// Add this class to the list of classes (adding "slider-wrap" suffix)
								$slider_wrap_css_classes[] = str_replace( '.', '', $instance_css_class ) . $instance['widget_size'] . '-slider-wrap';
						}

						// Sanitize CSS classes
						$slider_wrap_css_classes = array_filter( $slider_wrap_css_classes, 'sanitize_html_class' );

						// Allow filtering of CSS classes
						$slider_wrap_css_classes = apply_filters( 'conductor_slider_widget_slider_wrapper_css_classes', $slider_wrap_css_classes, $instance, $widget, $conductor_widget_query );
						$slider_wrap_css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_slider_wrapper_css_classes', $slider_wrap_css_classes, $instance, $widget, $conductor_widget_query );


						// Add the instance CSS class
						if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
							$instance_css_classes = explode( ' ', $instance['css_class'] );

							// Loop through instance CSS classes
							foreach ( $instance_css_classes as $instance_css_class )
								// Add this class to the list of classes (adding "slider-rows-wrap" suffix)
								$rows_wrap_css_classes[] = str_replace( '.', '', $instance_css_class ) . $instance['widget_size'] . '-slider-rows-wrap';
						}

						// Sanitize CSS classes
						$rows_wrap_css_classes = array_filter( $rows_wrap_css_classes, 'sanitize_html_class' );

						// Allow filtering of CSS classes
						$rows_wrap_css_classes = apply_filters( 'conductor_slider_widget_rows_wrapper_css_classes', $rows_wrap_css_classes, $instance, $widget, $conductor_widget_query );
						$rows_wrap_css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_rows_wrapper_css_classes', $rows_wrap_css_classes, $instance, $widget, $conductor_widget_query );
					?>
						<div class="<?php echo esc_attr( implode( ' ', $slider_wrap_css_classes ) ) ?>" data-conductor-slider-type="<?php echo esc_attr( $instance['widget_size'] ); ?>"  data-conductor-slider-feature-many="<?php echo esc_attr( ( $instance['feature_many'] ) ? 'true' : 'false' ); ?>">
							<div class="<?php echo esc_attr( implode( ' ', $rows_wrap_css_classes ) ); ?>">
								<<?php echo $this->current_widget_args['before_widget_el_name']; ?> <?php echo $this->prepare_attributes( $this->current_widget_args['before_widget_atts'] ); ?>>
					<?php
					break;

					// Default
					default:
								// Close the row first
					?>
								</<?php echo $this->current_widget_args['before_widget_el_name']; ?>>
					<?php
								// Open a new row
					?>
								<<?php echo $this->current_widget_args['before_widget_el_name']; ?> <?php echo $this->prepare_attributes( $this->current_widget_args['before_widget_atts'] ); ?>>
					<?php
					break;
				endswitch;
			endif;
		}

		/**
		 * This function resets the reference of the current Conductor Widget arguments (i.e. before_sidebar).
		 */
		public function conductor_widget_after( $instance ) {
			// Bail if this isn't a Conductor Slider display
			if ( ! $this->is_conductor_slider_display( $instance['widget_size'] ) )
				return;

			// Last post (close row and wrapper elements)
			if ( $this->is_last_post ) :
			?>
						</<?php echo $this->current_widget_args['before_widget_el_name']; ?>>
					</div>
				</div>
			<?php
			endif;

			// Reset last post flag
			$this->is_last_post = false;

			// Reset arguments reference
			$this->current_widget_args = array();
		}


		/***************************
		 * Conductor Widget Output *
		 ***************************/

		/**
		 * This function outputs the featured image for Conductor Widgets.
		 */
		public function conductor_widget_featured_image( $post, $instance, $widget, $query ) {
			// Find the featured image output element data
			$priority = $instance['output_elements']['featured_image'];
			$output = $instance['output'][$priority];

			if ( has_post_thumbnail( $post->ID ) ) :
				do_action( 'conductor_widget_featured_image_before', $post, $instance );

				// Output desired featured image size
				if ( ! empty( $instance['post_thumbnails_size'] ) )
					$conductor_thumbnail_size = $instance['post_thumbnails_size'];
				else
					$conductor_thumbnail_size = ( $instance['widget_size'] !== 'small' ) ? $instance['widget_size'] : 'thumbnail';

				$conductor_thumbnail_size = apply_filters( 'conductor_widget_featured_image_size', $conductor_thumbnail_size, $instance, $post );

				// Thumbnail CSS classes
				$css_classes = array(
					'conductor-slider-thumbnail',
					'conductor-widget-slider-thumbnail',
					'conductor-slider-thumbnail-wrap',
					'conductor-widget-slider-thumbnail-wrap',
					'conductor-slider-featured-image',
					'conductor-widget-slider-featured-image',
					'conductor-slider-featured-image-wrap',
					'conductor-widget-slider-featured-image-wrap',
					'conductor-' . $instance['widget_size'] .'-thumbnail',
					'conductor-' . $instance['widget_size'] .'-slider-thumbnail',
					'conductor-widget-' . $instance['widget_size'] .'-thumbnail',
					'conductor-widget-' . $instance['widget_size'] .'-slider-thumbnail',
					'conductor-' . $instance['widget_size'] .'-thumbnail-wrap',
					'conductor-' . $instance['widget_size'] .'-slider-thumbnail-wrap',
					'conductor-widget-' . $instance['widget_size'] .'-thumbnail-wrap',
					'conductor-widget-' . $instance['widget_size'] .'--sliderthumbnail-wrap',
					'conductor-' . $instance['widget_size'] .'-featured-image',
					'conductor-' . $instance['widget_size'] .'-slider-featured-image',
					'conductor-widget-' . $instance['widget_size'] .'-featured-image',
					'conductor-widget-' . $instance['widget_size'] .'-slider-featured-image',
					'conductor-' . $instance['widget_size'] .'-featured-image-wrap',
					'conductor-' . $instance['widget_size'] .'-slider-featured-image-wrap',
					'conductor-widget-' . $instance['widget_size'] .'-featured-image-wrap',
					'conductor-widget-' . $instance['widget_size'] .'-slider-featured-image-wrap'
				);

				// Add the instance CSS class
				if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
					$instance_css_classes = explode( ' ', $instance['css_class'] );

					// Loop through instance CSS classes
					foreach ( $instance_css_classes as $instance_css_class )
						// Add this class to the list of classes (adding "slider-thumbnail-wrap" suffix)
						$css_classes[] = str_replace( '.', '', $instance_css_class ) . $instance['widget_size'] . '-slider-thumbnail-wrap';
				}

				// Sanitize CSS classes
				$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

				// Allow filtering of CSS classes
				$css_classes = apply_filters( 'conductor_slider_widget_thumbnail_wrapper_css_classes', $css_classes, $instance, $widget, $query );
				$css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_thumbnail_wrapper_css_classes', $css_classes, $instance, $widget, $query );
			?>
				<div class="<?php echo esc_attr( implode( ' ', $css_classes ) ) ?>">
					<?php
						// Link featured image to post
						if ( $output['link'] ) :
					?>
							<a href="<?php echo get_permalink( $post->ID ); ?>">
								<?php echo get_the_post_thumbnail( $post->ID, $conductor_thumbnail_size ); ?>
							</a>
					<?php
						// Just output the featured image
						else:
							echo get_the_post_thumbnail( $post->ID, $conductor_thumbnail_size );
						endif;
					?>
				</div>
			<?php
				do_action( 'conductor_widget_featured_image_after', $post, $instance );
			endif;
		}

		/**
		 * This function outputs the post title for Conductor Widgets.
		 */
		public function conductor_widget_post_title( $post, $instance, $widget, $query ) {
			// Find the post title output element data
			$priority = $instance['output_elements']['post_title'];
			$output = $instance['output'][$priority];

			do_action( 'conductor_widget_post_title_before', $post, $instance );

			// Title CSS classes
			$css_classes = array(
				'conductor-slider-title',
				'conductor-widget-slider-title',
				'conductor-slider-post-title',
				'conductor-widget-slider-post-title',
				'conductor-slider-entry-title',
				'conductor-widget-slider-entry-title',
				'conductor-' . $instance['widget_size'] .'-title',
				'conductor-' . $instance['widget_size'] .'-slider-title',
				'conductor-widget-' . $instance['widget_size'] .'-slider-title',
				'conductor-' . $instance['widget_size'] .'-post-title',
				'conductor-' . $instance['widget_size'] .'-slider-post-title',
				'conductor-widget-' . $instance['widget_size'] .'-slider-post-title',
				'conductor-' . $instance['widget_size'] .'-entry-title',
				'conductor-' . $instance['widget_size'] .'-slider-entry-title',
				'conductor-widget-' . $instance['widget_size'] .'-slider-entry-title'
			);

			// Add the instance CSS class
			if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
				$instance_css_classes = explode( ' ', $instance['css_class'] );

				// Loop through instance CSS classes
				foreach ( $instance_css_classes as $instance_css_class )
					// Add this class to the list of classes (adding "slider-post-title" suffix)
					$css_classes[] = str_replace( '.', '', $instance_css_class ) . $instance['widget_size'] . '-slider-post-title';
			}

			// Sanitize CSS classes
			$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

			// Allow filtering of CSS classes
			$css_classes = apply_filters( 'conductor_slider_widget_post_title_css_classes', $css_classes, $instance, $widget, $query );
			$css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_post_title_css_classes', $css_classes, $instance, $widget, $query );
		?>
			<h3 class="<?php echo esc_attr( implode( ' ', $css_classes ) ) ?>">
				<?php
					// Link post title to post
					if ( $output['link'] ) :
				?>
						<a href="<?php echo get_permalink( $post->ID ); ?>">
							<?php echo get_the_title( $post->ID ); ?>
						</a>
				<?php
					// Just output the post title
					else:
						echo get_the_title( $post->ID );
					endif;
				?>
			</h3>
		<?php
			do_action( 'conductor_widget_post_title_after', $post, $instance );
		}

		/**
		 * This function outputs the post content for Conductor Widgets.
		 */
		public function conductor_widget_post_content( $post, $instance, $widget, $query ) {
			do_action( 'conductor_widget_post_content_before', $post, $instance );

			// Title CSS classes
			$css_classes = array(
				'conductor-slider-content-wrap',
				'conductor-widget-slider-content-wrap',
				'conductor-' . $instance['widget_size'] .'-content-wrap',
				'conductor-' . $instance['widget_size'] .'-slider-content-wrap',
				'conductor-widget-' . $instance['widget_size'] .'-slider-content-wrap',
			);

			// Add the instance CSS class
			if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
				$instance_css_classes = explode( ' ', $instance['css_class'] );

				// Loop through instance CSS classes
				foreach ( $instance_css_classes as $instance_css_class )
					// Add this class to the list of classes (adding "slider-post-content-wrap" suffix)
					$css_classes[] = str_replace( '.', '', $instance_css_class ) . $instance['widget_size'] . '-slider-post-content-wrap';
			}

			// Sanitize CSS classes
			$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

			// Allow filtering of CSS classes
			$css_classes = apply_filters( 'conductor_slider_widget_post_content_wrapper_css_classes', $css_classes, $instance, $widget, $query );
			$css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_post_content_wrapper_css_classes', $css_classes, $instance, $widget, $query );
		?>
			<div class="<?php echo esc_attr( implode( ' ', $css_classes ) ) ?>">
				<?php
					// Determine which type of content to output
					switch ( $instance['content_display_type'] ) {
						// Excerpt - the_excerpt()
						case 'excerpt':
							echo $query->get_excerpt_by_id( $post, $instance['excerpt_length'] );
						break;

						// the_content()
						case 'content':
						default:
							echo $query->get_content_by_id( $post );
						break;
					}
				?>
			</div>
		<?php
			do_action( 'conductor_widget_post_content_after', $post, $instance );
		}

		/**
		 * This function outputs an opening wrapper element before the author byline output element.
		 */
		public function conductor_widget_author_byline_before( $post, $instance ) {
			// Bail if this isn't a Conductor Slider display
			if ( ! $this->is_conductor_slider_display( $instance['widget_size'] ) )
				return;

			// Author Byline wrapper CSS classes
			$css_classes = array(
				'conductor-slider-author-byline',
				'conductor-widget-slider-author-byline',
				'conductor-slider-author-byline-wrap',
				'conductor-widget-slider-author-byline-wrap',
				'conductor-' . $instance['widget_size'] .'-author-byline',
				'conductor-' . $instance['widget_size'] .'-slider-author-byline',
				'conductor-widget-' . $instance['widget_size'] .'-slider-author-byline',
				'conductor-' . $instance['widget_size'] .'-author-byline-wrap',
				'conductor-' . $instance['widget_size'] .'-slider-author-byline-wrap',
				'conductor-widget-' . $instance['widget_size'] .'-slider-author-byline-wrap'
			);

			// Add the instance CSS class
			if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
				$instance_css_classes = explode( ' ', $instance['css_class'] );

				// Loop through instance CSS classes
				foreach ( $instance_css_classes as $instance_css_class )
					// Add this class to the list of classes (adding "slider-author-byline-wrap" suffix)
					$css_classes[] = str_replace( '.', '', $instance_css_class ) . $instance['widget_size'] . '-slider-author-byline-wrap';
			}

			// Sanitize CSS classes
			$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

			// Allow filtering of CSS classes
			$css_classes = apply_filters( 'conductor_slider_widget_author_byline_wrapper_css_classes', $css_classes, $instance );
			$css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_author_byline_wrapper_css_classes', $css_classes, $instance );

			?>
			<div class="<?php echo esc_attr( implode( ' ', $css_classes ) ) ?>">
		<?php
		}

		/**
		 * This function outputs an closing wrapper element after the author byline output element.
		 */
		public function conductor_widget_author_byline_after( $post, $instance ) {
			// Bail if this isn't a Conductor Slider display
			if ( ! $this->is_conductor_slider_display( $instance['widget_size'] ) )
				return;
			?>
			</div>
		<?php
		}

		/**
		 * This function outputs an opening wrapper element before the read more output element.
		 */
		public function conductor_widget_read_more_before( $post, $instance ) {
			// Bail if this isn't a Conductor Slider display
			if ( ! $this->is_conductor_slider_display( $instance['widget_size'] ) )
				return;

			// Read More wrapper CSS classes
			$css_classes = array(
				'conductor-slider-read-more',
				'conductor-widget-slider-read-more',
				'conductor-slider-read-more-wrap',
				'conductor-widget-slider-read-more-wrap',
				'conductor-' . $instance['widget_size'] .'-read-more',
				'conductor-' . $instance['widget_size'] .'-slider-read-more',
				'conductor-widget-' . $instance['widget_size'] .'-slider-read-more',
				'conductor-' . $instance['widget_size'] .'-read-more-wrap',
				'conductor-' . $instance['widget_size'] .'-slider-read-more-wrap',
				'conductor-widget-' . $instance['widget_size'] .'-slider-read-more-wrap'
			);

			// Add the instance CSS class
			if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
				$instance_css_classes = explode( ' ', $instance['css_class'] );

				// Loop through instance CSS classes
				foreach ( $instance_css_classes as $instance_css_class )
					// Add this class to the list of classes (adding "slider-read-more-wrap" suffix)
					$css_classes[] = str_replace( '.', '', $instance_css_class ) . $instance['widget_size'] . '-slider-read-more-wrap';
			}

			// Sanitize CSS classes
			$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

			// Allow filtering of CSS classes
			$css_classes = apply_filters( 'conductor_slider_widget_read_more_wrapper_css_classes', $css_classes, $instance );
			$css_classes = apply_filters( 'conductor_slider_widget_' . $instance['widget_size'] . '_read_more_wrapper_css_classes', $css_classes, $instance );

		?>
			<div class="<?php echo esc_attr( implode( ' ', $css_classes ) ) ?>">
		<?php
		}

		/**
		 * This function outputs an closing wrapper element after the read more output element.
		 */
		public function conductor_widget_read_more_after( $post, $instance ) {
			// Bail if this isn't a Conductor Slider display
			if ( ! $this->is_conductor_slider_display( $instance['widget_size'] ) )
				return;
		?>
			</div>
		<?php
		}


		/**********************
		 * Internal Functions *
		 **********************/

		/**
		 * This function prepares an array of data for use as HTML5 data attributes.
		 */
		public function prepare_attributes( $attrs ) {
			$the_attrs = '';

			// Loop through data attributes
			foreach ( $attrs as $key => &$value ) {
				// If we have a boolean value, change it to a string
				if ( is_bool( $value ) )
					$value = ( $value ) ? 'true' : 'false';

				$the_attrs .= sanitize_text_field( $key ) . '="' . esc_attr( ( string ) $value ) . '" ';
			}

			return $the_attrs;
		}

		/**
		 * This function determines if the passed in Conductor Widget size (display) is a Conductor
		 * Slider display.
		 */
		public function is_conductor_slider_display( $display ) {
			return array_key_exists( $display, $this->conductor_widget_displays );
		}
	}
}

/**
 * Create an instance of the Conductor_Slider class.
 */
function Conduct_Slider() {
	return Conductor_Slider::instance();
}

Conduct_Slider(); // Conduct your content!