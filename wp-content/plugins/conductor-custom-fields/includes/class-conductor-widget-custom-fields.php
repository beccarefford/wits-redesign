<?php
/**
 * Conductor Widget Custom Fields - Functionality used to enhance/modify the Conductor Widget.
 *
 * @class Conductor_Widget_Custom_Fields
 * @author Slocum Studio
 * @version 1.0.4
 * @since 0.0.1
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'Conductor_Widget_Custom_Fields' ) ) {
	class Conductor_Widget_Custom_Fields {
		/**
		 * @var string
		 */
		public $version = '1.0.4';

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
			add_action( 'conductor_widget_includes', array( $this, 'conductor_widget_includes' ) ); // Conductor Widget Includes
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); // Enqueue admin scripts
			add_filter( 'conductor_widget_defaults', array( $this, 'conductor_widget_defaults' ) ); // Filter widget defaults
			add_filter( 'conductor_widget_admin_localize', array( $this, 'conductor_widget_admin_localize' ) ); // Filter the localized data to include custom fields
			add_action( 'conductor_widget_output_list_before', array( $this, 'conductor_widget_output_list_before' ), 10, 2 ); // Output custom fields input box
			add_filter( 'conductor_widget_update', array( $this, 'conductor_widget_update' ), 10, 3 );
		}


		/**
		 * This function includes the Conductor Widget Custom Fields Query Class on the Conductor Widget.
		 */
		function conductor_widget_includes( $widget ) {
			include_once( Conductor_Custom_Fields_Addon::plugin_dir() . '/includes/class-conductor-widget-custom-fields-query.php' ); // Conductor Widget Custom Fields Query Class
		}

		/**
		 * This function enqueues the necessary scripts and styles associated with this widget on admin.
		 */
		function admin_enqueue_scripts( $hook ) {
			// Only on Widgets Admin Page
			if ( $hook === 'widgets.php' ) {
				wp_enqueue_script( 'conductor-widget-custom-fields-admin', Conductor_Custom_Fields_Addon::plugin_url() . '/assets/js/conductor-widget-custom-fields-admin.js', array( 'jquery', 'conductor-widget-admin', 'jquery-ui-autocomplete' ) );

				wp_enqueue_style( 'conductor-widget-custom-fields-admin', Conductor_Custom_Fields_Addon::plugin_url() . '/assets/css/conductor-widget-custom-fields-admin.css', array( 'conductor-widget-admin' ) );
			}
		}

		/**
		 * This function filters Conductor Widget defaults to add output feature support for custom fields.
		 */
		function conductor_widget_defaults( $defaults ) {
			// Custom Field output element type
			$defaults['output_features']['custom_field'] = array(
				'edit_label' => array( 'default' => '' ), // Edit label, if default is empty, Conductor will use the element id
				'remove' => true
			);

			return $defaults;
		}

		/**
		 * This function filters Conductor Widget admin localized script data to include a list of custom fields.
		 */
		function conductor_widget_admin_localize( $data ) {
			// Get all distinct custom field/meta keys
			$custom_fields = $this->add_custom_fields_to_cache();

			// Store the meta info in the localized data
			$data['widgets']['conductor']['custom_fields'] = $custom_fields;

			return $data;
		}

		/**
		 * This function outputs the UI for the custom fields input/autocomplete box.
		 */
		function conductor_widget_output_list_before( $instance, $widget ) {
			// Get all distinct (unique) custom field/meta keys
			$custom_fields = $this->add_custom_fields_to_cache();

			// Placeholder text
			$placeholder = ( ! empty( $custom_fields ) ) ? __( 'Start typing to add a custom field...', 'conductor-cf' ) : __( 'No custom fields exist...', 'conductor-cf' );
		?>
			<input type="text" class="conductor-input conductor-widget-custom-fields" id="<?php echo $widget->get_field_id( 'custom_fields' ); ?>" name="<?php echo $widget->get_field_name( 'custom_fields' ); ?>" value="" placeholder="<?php echo esc_attr( $placeholder ); ?>" />
		<?php
		}

		/**
		 * This function sanitizes custom field elements on Conductor Widget update.
		 */
		function conductor_widget_update( $new_instance, $old_instance, $widget ) {
			// Adjust the callback on custom fields
			foreach( $new_instance['output'] as $priority => &$element )
				if ( $element['type'] === 'custom_field' )
					// Static callback
					$element['callback'] = array( 'Conductor_Widget_Custom_Fields_Query', 'conductor_widget_custom_field' );

			return $new_instance;
		}

		/**
		 * This function adds custom fields to Conductor Widget cache.
		 */
		function add_custom_fields_to_cache() {
			global $wpdb, $wp_version;

			if ( ! $custom_fields = wp_cache_get( 'custom_fields', 'conductor-widget' ) ) {
				$limit = apply_filters( 'postmeta_form_limit', apply_filters( 'conductor_widget_postmeta_form_limit', 9999, $this ) ); // Find all custom fields
				$sql = "SELECT meta_key FROM $wpdb->postmeta GROUP BY meta_key HAVING meta_key NOT LIKE %s ORDER BY meta_key LIMIT %d";

				// WordPress 4.0 and above
				if ( version_compare( $wp_version, '4.0', '>=' ) )
					$custom_fields = $wpdb->get_col( $wpdb->prepare( $sql, $wpdb->esc_like( '_' ) . '%', $limit ) );
				// WordPress versions below 4.0
				else
					$custom_fields = $wpdb->get_col( $wpdb->prepare( $sql, '\_%', $limit ) );

				// Sort the keys by "natural order" if we have them
				//if ( ! empty( $custom_fields ) )
				//	natcasesort( $custom_fields );

				// Remove protected meta keys if they exist
				foreach ( $custom_fields as $id => $meta_key )
					if ( is_protected_meta( $meta_key, 'post' ) )
						unset( $custom_fields[$id] );

				// Reset the array keys
				$custom_fields = array_values( $custom_fields );

				// Make sure the custom field data is set up with the correct schema
				foreach ( $custom_fields as &$custom_field )
					if ( ! is_array( $custom_field ) )
						$custom_field = array(
							'label' => $custom_field,
							'value' => $custom_field
						);

				// Sort the keys by "natural order" if we have them
				if ( ! empty( $custom_fields ) )
					usort( $custom_fields, array( $this, 'usort_by_custom_field_label' ) );

				wp_cache_add( 'custom_fields', $custom_fields, 'conductor-widget' ); // Store cache
			}

			return $custom_fields;
		}

		/**
		 * This function is used by usort to order custom fields by label.
		 */
		function usort_by_custom_field_label( $a, $b ) {
			return strcasecmp( $a['label'], $b['label'] );
		}
	}
}

/**
 * Create an instance of the Conductor_Widget_Custom_Fields class.
 */
function Conduct_Widget_Custom_Fields() {
	return Conductor_Widget_Custom_Fields::instance();
}

Conduct_Widget_Custom_Fields(); // Conduct your content!