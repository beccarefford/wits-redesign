<?php
/**
 * Conductor Customizer Content Layouts Control (Theme Customizer functionality)
 *
 * @class Conductor_Customizer_Content_Layouts_Control
 * @author Slocum Studio
 * @version 1.1.0
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

// Make sure the Customize Image Control class exists
if ( ! class_exists( 'WP_Customize_Control' ) )
	exit;

if( ! class_exists( 'Conductor_Customizer_Content_Layouts_Control' ) ) {
	final class Conductor_Customizer_Content_Layouts_Control extends WP_Customize_Control {
		/**
		 * @var string
		 */
		public $version = '1.1.0';

		/**
		 * This function sets up all of the actions and filters on instance. It also loads (includes)
		 * the required files and assets.
		 */
		function __construct( $manager, $id, $args = array() ) {
			// Hooks
			add_action( 'customize_controls_print_styles', array( $this, 'customize_controls_print_styles' ) ); // Output styles on Theme Customizer

			add_filter( 'conductor_widget_admin_localize', array( $this, 'conductor_widget_admin_localize' ) ); // Conductor Widget Admin Localize

			// Call the parent constructor here
			parent::__construct( $manager, $id, $args );
		}

		/**
		 * TODO: Is this function necessary?
		 */
		public function to_json() {
			// Just calling the parent to_json method here
			parent::to_json();
		}

		/**
		 * This function enqueues scripts and styles
		 */
		public function enqueue() {
			global $wp_scripts;

			// Stylesheets
			wp_enqueue_style( 'conductor-admin', Conductor::plugin_url() . '/assets/css/conductor-admin.css', false, Conductor::$version );
			wp_enqueue_style( 'conductor-customizer', Conductor::plugin_url() . '/assets/css/conductor-customizer.css', false, Conductor::$version );

			// Content Layouts Scripts
			wp_enqueue_script( 'conductor-content-layouts', Conductor::plugin_url() . '/assets/js/conductor-options-admin.js', array( 'jquery', 'wp-backbone' ), Conductor::$version );
			wp_enqueue_script( 'jquery-formparams', Conductor::plugin_url() . '/assets/js/jquery.formparams.min.js', array( 'jquery' ), Conductor::$version );
			wp_enqueue_script( 'conductor-content-layouts-customizer-control', Conductor::plugin_url() . '/assets/js/conductor-content-layouts-customizer-control.js', array( 'customize-widgets', 'jquery-formparams', 'conductor-content-layouts' ), Conductor::$version );
			$wp_scripts->add_data( 'conductor-content-layouts-customizer-control', 'data', str_replace( '_wpCustomizeWidgetsSettings', 'conductorCustomizeWidgetsSettings', $wp_scripts->get_data( 'customize-widgets', 'data' ) ) );


			// Call the parent enqueue method here
			parent::enqueue();
		}

		/**
		 * This function renders the control's content.
		 */
		public function render_content() {
		?>
			<div class="customize-conductor customize-conductor-content-layouts">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

				<?php include( 'views/html-conductor-customizer-content-layouts-field.php' ); // Conductor Customizer Content Layouts Field ?>
			</div>
		<?php
		}

		/**
		 * This function prints styles on the Theme Customizer only.
		 */
		function customize_controls_print_styles() {
			global $_wp_admin_css_colors;

			// Output styles to match selected admin color scheme
			if ( ( $user_admin_color = get_user_option( 'admin_color' ) ) && isset( $_wp_admin_css_colors[$user_admin_color] ) && Conductor::wp_version_compare( '3.8' ) ) :
		?>
				<style type="text/css" scoped>
					/* Content Layouts */
					.conductor-content-layout:hover .conductor-content-layout-preview,
					.conductor-content-layout input[type=radio]:checked + .conductor-content-layout-preview {
						border: 1px solid <?php echo $_wp_admin_css_colors[$user_admin_color]->colors[2]; ?>;
					}

					.conductor-content-layout:hover .conductor-content-layout-preview  .col,
					.conductor-content-layout input[type=radio]:checked + .conductor-content-layout-preview .col {
						color: #fff;
						background: <?php echo $_wp_admin_css_colors[$user_admin_color]->colors[2]; ?>;
					}

					.conductor-content-layout:hover .conductor-content-layout-preview  .col-sidebar,
					.conductor-content-layout input[type=radio]:checked + .conductor-content-layout-preview .col-sidebar {
						color: #fff;
						background: <?php echo $_wp_admin_css_colors[$user_admin_color]->colors[3]; ?>;
					}
				</style>
		<?php
			endif;
		}

		/**
		 * This function filters the localized data on the Conductor Widget script to add customizer information.
		 * It adds the conductor_refresh parameter when on the Customizer and Conductor has had to refresh to ensure
		 * newly added sidebars would appear properly.
		 */
		// TODO: Update function documentation
		function conductor_widget_admin_localize( $data ) {
			global $wp_version;

			// Validate that the Conductor Customizer transient is set
			// TODO: Remove (refresh)
			if ( $conductor_customizer = get_transient( 'conductor_customizer' ) ) {
				// Set the customizer flag if not already set
				if ( ! isset( $data['customizer'] ) )
					$data['customizer'] = array();

				// Store the refresh flag
				$data['customizer']['conductor_refresh'] = true;

				// Store the refresh data
				$data['customizer']['refresh_params'] = $conductor_customizer;
			}

			// Add the current WordPress version to the 'customizer' key
			if ( ! isset( $data['customizer'] ) )
				$data['customizer'] = array();

			$data['customizer']['wp_version'] = $wp_version;

			// Add the WordPress customize URL
			if ( ! isset( $data['customize_url'] ) )
				$data['customize_url'] = add_query_arg( array( 'url' => '' ), wp_customize_url() );

			// If less than 4.0 we need to include the save alert message for the Customizer
			if ( Conductor::wp_version_compare( '4.0', '<' ) ) {
				$data['customizer']['l10n'] = array(
					'saveAlert' => __( 'The changes you made will be lost if you navigate away from this page.' )
				);
			}

			return $data;
		}
	}
}