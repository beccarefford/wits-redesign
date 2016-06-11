<?php
/**
 * Conductor Widget Custom Fields Query - Helper class used for querying/displaying custom fields  within Conductor.
 *
 * @class Conductor_Widget_Custom_Fields_Query
 * @author Slocum Studio
 * @version 1.0.3
 * @since 0.0.1
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'Conductor_Widget_Custom_Fields_Query' ) ) {
	class Conductor_Widget_Custom_Fields_Query extends Conductor_Widget_Query {
		/**
		 * @var string
		 */
		public $version = '1.0.3';

		/**
		 * @var array, An array of custom fields used within this query
		 */
		public static $custom_fields = array();

		/**
		 * @var integer, Current custom field being output
		 */
		public static $current_custom_field = 0;

		/**
		 * @var integer, Current widget number
		 */
		public static $current_widget_number = 0;

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
			add_action( 'conductor_widget_query_add_display_content', array( get_class(), 'conductor_widget_query_add_display_content' ),10, 5 );
		}

		/**
		 * This function adds an action to reset the current custom field index value after
		 * each post within a Conductor Widget
		 */
		public static function conductor_widget_query_add_display_content( $element, $priority, $widget_number, $display_content_args_count, $query ) {
			// Only on custom fields
			if ( $element['type'] === 'custom_field' ) {
				// If the callback is uses this class to output data
				// TODO: Check is_callable() here?
				if ( is_array( $element['callback'] ) && $element['callback'][0] === get_class() && method_exists( $element['callback'][0], $element['callback'][1] ) ) {
					// Set the widget number and reset custom fields array
					if ( self::$current_widget_number !== $widget_number ) {
						self::$current_widget_number = $widget_number;
						self::$custom_fields = array();
					}

					// Add this custom field to the array
					self::$custom_fields[] = $element;
				}

				// Reset current custom field value at the end of post output
				if ( ! has_action( 'conductor_widget_display_content_' . $widget_number, array( get_class(), 'conductor_widget_reset_current_custom_field' ) ) ) {
					add_action( 'conductor_widget_display_content_' . $widget_number, array( get_class(), 'conductor_widget_reset_current_custom_field' ), 99999, $display_content_args_count );
					$query->hooks['conductor_widget_display_content_' . $widget_number] += array(
						99999 => array( get_class(), 'conductor_widget_reset_current_custom_field' ), // Static callback
					);
				}
			}
		}

		/**
		 * This function outputs the custom fields for Conductor Widgets. The constructor adds the necessary hooks to
		 * ensure custom fields are output. Custom fields are stored in an array on this class in the order based on
		 * priority in the Conductor Widget.
		 */
		// TODO: do_action for specific custom field name
		public static function conductor_widget_custom_field( $post, $instance, $widget, $query ) {
			// Make sure this is a valid custom field
			if ( isset( self::$custom_fields[self::$current_custom_field] ) ) :
				$custom_field_object = self::$custom_fields[self::$current_custom_field];
				$custom_field = get_post_meta( $post->ID, $custom_field_object['id'], true );

				do_action( 'conductor_widget_custom_field_before', $post, $instance, $custom_field_object );
				?>
				<p class="custom-field custom-field-<?php echo esc_attr( $custom_field_object['id'] ); ?>">
					<?php if ( $custom_field_object['label'] !== $custom_field_object['id'] ) : // Custom Field Label ?>
						<span class="custom-field-label"><?php echo $custom_field_object['label']; ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $custom_field ) ) : // Custom Field Value ?>
						<span class="custom-field-val"><?php echo ( is_array( $custom_field ) ) ? implode( ', ', $custom_field ) : $custom_field; ?></span>
					<?php endif; ?>
				</p>
				<?php
				do_action( 'conductor_widget_custom_field_after', $post, $instance, $custom_field_object );

				// Move the current custom field index to the next field
				self::$current_custom_field++;
			endif;
		}

		/**
		 * This function resets the current custom field index to 0 for output on the next post within this widget query.
		 */
		public static function conductor_widget_reset_current_custom_field( $post, $instance, $widget, $query ) {
			self::$current_custom_field = 0;
		}
	}
}

/**
 * Create an instance of the Conductor_Widget_Custom_Fields_Query class.
 */
function Conduct_Widget_Custom_Fields_Query() {
	return Conductor_Widget_Custom_Fields_Query::instance();
}

Conduct_Widget_Custom_Fields_Query(); // Conduct your content!