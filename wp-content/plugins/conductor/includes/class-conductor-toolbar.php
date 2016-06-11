<?php
/**
 * Conductor Toolbar (Admin Bar)
 *
 * @class Conductor_Toolbar
 * @author Slocum Studio
 * @version 1.0.0
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if( ! class_exists( 'Conductor_Toolbar' ) ) {
	final class Conductor_Toolbar {
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
		function __construct( ) {
			// Hooks
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 9999 ); // Admin Bar Menu
			add_action( 'wp_before_admin_bar_render', array( $this, 'wp_before_admin_bar_render' ) ); // Before Admin Bar Render
		}

		/**
		 * This function runs when the admin bar is initialized and adds a Conductor node.
		 */
		public function admin_bar_menu( $wp_admin_bar ) {
			global $wp;

			// Administrators only
			if ( current_user_can( 'switch_themes' ) || current_user_can( 'edit_theme_options' ) ) {
				$conductor_menu_page = str_replace( 'toplevel_page_', '', Conductor_Admin_Options::$menu_page );

				// TODO: We only want to trailing slash if permalinks are enabled

				// Conduct your content/conduct this page menu
				$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

				// Conductor parent menu
				$wp_admin_bar->add_menu( array(
					'id' => $conductor_menu_page,
					'title' => __( '<span class="ab-icon dashicons dashicons-admin-generic conductor-icon"></span> Conductor', 'conductor' ),
					'href' => ( is_admin() ) ? wp_customize_url() : add_query_arg( 'url', urlencode( $current_url ), wp_customize_url() ),
					'meta' => array(
						'class' => 'conductor conductor-parent'
					)
				) );


				$wp_admin_bar->add_menu( array(
					'id' => 'conductor-conduct',
					'parent' => $conductor_menu_page,
					'title' => ( is_admin() ) ? __( 'Conduct Your Content', 'conductor' ) : __( 'Conduct This Page', 'conductor' ),
					'href' => ( is_admin() ) ? wp_customize_url() : add_query_arg( 'url', urlencode( $current_url ), wp_customize_url() ),
					'meta' => array(
						'class' => 'conductor conductor-child conductor-conduct hide-if-no-customize'
					)
				) );

				// Options menu
				$wp_admin_bar->add_menu( array(
					'id' => 'conductor-options',
					'parent' => $conductor_menu_page,
					'title' => __( 'Options', 'conductor' ),
					'href' => admin_url( add_query_arg( 'page', str_replace( 'toplevel_page_', '', Conductor_Admin_Options::$menu_page ), 'admin.php' ) ),
					'meta' => array(
						'class' => 'conductor conductor-child conductor-options'
					)
				) );

				// Addons menu
				$wp_admin_bar->add_menu( array(
					'id' => 'conductor-addons',
					'parent' => $conductor_menu_page,
					'title' => __( 'Add-ons', 'conductor' ),
					'href' => admin_url( add_query_arg( 'page', str_replace( 'conductor_page_', '', Conductor_Admin_Addons::$sub_menu_page ), 'admin.php' ) ),
					'meta' => array(
						'class' => 'conductor conductor-child conductor-addons'
					)
				) );

				// Help menu
				$wp_admin_bar->add_menu( array(
					'id' => 'conductor-help',
					'parent' => $conductor_menu_page,
					'title' => __( 'Help', 'conductor' ),
					'href' => admin_url( add_query_arg( 'page', str_replace( 'conductor_page_', '', Conductor_Admin_Help::$sub_menu_page ), 'admin.php' ) ),
					'meta' => array(
						'class' => 'conductor conductor-child conductor-help'
					)
				) );
			}
		}

		/**
		 * This function runs before the admin bar is rendered and outputs styles for the Conductor item.
		 */
		public function wp_before_admin_bar_render() {
			?>
			<style type="text/css">
				#wpadminbar .conductor-parent > a {
					padding: 0 0.5em;
					font-size: 14px;
				}
			</style>
		<?php
		}

	}
}

/**
 * Create an instance of the Conductor_Toolbar class.
 */
function Conduct_Toolbar() {
	return Conductor_Toolbar::instance();
}

Conduct_Toolbar(); // Conduct your content!