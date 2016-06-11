<?php
/**
 * Conductor Widget
 *
 * @class Conductor_Widget
 * @author Slocum Studio
 * @version 1.3.1
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'Conductor_Widget' ) ) {
	final class Conductor_Widget extends WP_Widget {
		/**
		 * @var string
		 */
		public $version = '1.3.1';

		/**
		 * @var array, Conductor Widget defaults
		 */
		public $defaults = array();

		/**
		 * @var array, Conductor Widget displays (formally widget sizes)
		 */
		public $displays = array();

		/**
		 * @var Conductor_Widget_Query, Instance of the Conductor Query class
		 */
		public $conductor_widget_query;

		/**
		 * @var int, Maximum number of Flexbox columns
		 */
		public $max_columns = 6;

		/**
		 * @var string
		 */
		public $current_sidebar_id = false;

		/**
		 * @var array
		 */
		public $current_sidebar_widgets = array();

		/**
		 * @var array, Array of arrays of Conductor Widgets
		 */
		public $sidebar_conductor_widgets = array();

		/**
		 * @var int
		 */
		public $sidebar_conductor_widgets_index = -1;

		/**
		 * @var Conductor_Widget, Instance of the class
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
		 * This function sets up all of the actions and filters on instance. It also initializes widget options
		 * including class name, description, width/height, and creates an instance of the widget
		 */
		function __construct() {
			// Load required assets
			$this->includes();


			// Maximum number of columns
			$this->max_columns = ( int ) apply_filters( 'conductor_widget_max_columns', $this->max_columns, $this );

			// Widget displays
			// TODO: Introduce a filter that is executed in the widget() function for specific widget displays (by widget number)
			$this->displays = apply_filters( 'conductor_widget_displays', array(
				'small' => __( 'Small', 'conductor' ),
				'medium' => __( 'Medium', 'conductor' ),
				'large' => __( 'Large', 'conductor' ),
				'flexbox' => array(
					'label' => __( 'Custom', 'conductor' ),
					'customize' => array(
						'columns' => true // Columns
					)
				)
			), array(), $this ); // TODO: Empty array is for the $instance (old reference) which will likely be removed in the future (some add-ons may reference this value in their callback functions, but do not actually use it in logic)

			// Set up the default widget settings
			$this->defaults = apply_filters( 'conductor_widget_defaults', array(
				'title' => false,
				'feature_many' => false, // Feature one content piece
				'post_id' => false, // Post ID used if featuring only one (above)
				'content_type' => 'post',
				// Query arguments if featuring many
				'query_args' => array(
					'post_type' => 'post',
					'orderby' => 'date',
					'order' => 'DESC',
					'max_num_posts' => get_option( 'posts_per_page' ),
					'posts_per_page' => '',
					'offset' => 1,
					'cat' => false,
					'post__in' => false,
					'post__not_in' => false
				),
				'widget_size' => 'large',
				'hide_title' => false,
				'post_thumbnails_size' => false,
				'excerpt_length' => apply_filters( 'excerpt_length', 55 ),
				'content_display_type' => 'content',
				'css_class' => false,
				/*
				 * Sortable order of output elements on front-end
				 *
				 * Format: array(
				 * 		10 (priority) => array(
				 * 			'id' => 'featured_image', // id of output element
				 *			'label' =>  __( 'Featured Image', 'conductor' ), // label of output element
				 *			'type' => 'built-in', // type of output element
				 *			'visible' => true, // visible flag, boolean
				 *			'link' => true // link flag, boolean (optional)
				 * 		)
				 * )
				 */
				'output' => array(
					// Featured Image
					10 => array(
						'id' => 'featured_image',
						'label' =>  __( 'Featured Image', 'conductor' ),
						'type' => 'built-in',
						'visible' => true,
						'link' => true
					),
					// Post Title
					20 => array(
						'id' => 'post_title',
						'label' =>  __( 'Title', 'conductor' ),
						'type' => 'post_title',
						'visible' => true,
						'link' => true
					),
					// Author Byline
					30 => array(
						'id' => 'author_byline',
						'label' =>  __( 'Author Byline', 'conductor' ),
						'type' => 'built-in',
						'visible' => true
					),
					// Post Content
					40 => array(
						'id' => 'post_content',
						'label' =>  __( 'Content', 'conductor' ),
						'type' => 'content', // TODO: This should maybe be set to post_content (but we'd need to consider backwards compatibility)
						'visible' => true,
						'value' => 'content'
					),
					// Read More
					50 => array(
						'id' => 'read_more',
						'label' => __( 'Read More', 'conductor' ),
						'type' => 'read_more',
						'visible' => true,
						'link' =>  true
					)
				),
				'output_elements' => array(), // Upon saving will contain an array of output elements ('id' => 'priority'); used in Conductor_Widget_Query
				/*
				 * Features that various output elements or element types support
				 *
				 * Key is either id of output element or type of output element (widget will check id first, then type)
				 */
				'output_features' => array(
					// Featured Image
					'featured_image' => array(
						'link' => true // Enable/disable linking
					),
					// Post Title
					'post_title' => array(
						'link' => true // Enable/disable linking
					),
					// Post Content
					'post_content' => array(
						'edit_content_type' => true // Content type (special case)
					),
					// Read More
					'read_more' => array(
						'link' => true, // Enable/disable linking
						'edit_label' => array( 'default' => __( 'Read More', 'conductor' ) ) // Edit label (pass a default)
					)
				),
				// Flexbox
				'flexbox' => array(
					// Columns
					'columns' => 1 // Default to 1 column
				)
			), $this );


			// Widget/Control options
			$widget_options = array(
				'classname' => 'conductor-widget',
				'description' => __( 'Display one or many content pieces.', 'conductor' )
			);
			$widget_options = apply_filters( 'conductor_widget_widget_options', $widget_options, $this );

			$control_options = apply_filters( 'conductor_widget_control_options', array( 'id_base' => 'conductor-widget' ), $this );

			// Call the parent constructor
			parent::__construct( 'conductor-widget', __( 'Conductor Widget', 'conductor' ), $widget_options, $control_options );

			// Hooks
			// TODO: Hooks are currently run multiple times due to each widget instance, we should either verify if the hook has been added, move these to another file, or set callback functions to static
			add_action( 'admin_enqueue_scripts', array( 'Conductor_Widget', 'admin_enqueue_scripts' ) ); // Enqueue admin scripts
			add_action( 'dynamic_sidebar_before', array( 'Conductor_Widget', 'dynamic_sidebar_before' ), 10, 2 ); // Dynamic sidebar before
			add_filter( 'dynamic_sidebar_params', array( 'Conductor_Widget', 'dynamic_sidebar_params' ) ); // Dynamic sidebar parameters
			add_action( 'dynamic_sidebar_after', array( 'Conductor_Widget', 'dynamic_sidebar_after' ), 10, 2 ); // Dynamic sidebar after
			add_filter( 'conductor_widget_wrapper_css_classes', array( 'Conductor_Widget', 'conductor_widget_wrapper_css_classes' ), 10, 5 ); // Conductor Widget Wrapper CSS Classes
			add_filter( 'conductor_widget_featured_image_size', array( 'Conductor_Widget', 'conductor_widget_featured_image_size' ), 10, 3 ); // Conductor Widget Featured Image Size
			//add_filter( 'template_include', array( $this, 'template_include' ), 20 ); // Template Include
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		private function includes() {
			include_once( Conductor::plugin_dir() . '/includes/class-conductor-widget-query.php' ); // Conductor Widget Query Class
			include_once( Conductor::plugin_dir() . '/includes/class-conductor-widget-default-query.php' ); // Conductor Widget Default Query Class

			do_action( 'conductor_widget_includes', $this );
		}


		/**
		 * This function configures the form on the Widgets Admin Page.
		 */
		public function form( $instance ) {
			global $wpdb, $_wp_additional_image_sizes;

			$instance = wp_parse_args( ( array ) $instance, $this->defaults ); // Parse any saved arguments into defaults

			// Get all public post types and format the list for display in drop down
			if ( ! $public_post_types = wp_cache_get( 'public_post_types', 'conductor-widget' ) ) {
				$public_post_types = get_post_types( array( 'public' => true ) ); // Public Post Types
				unset( $public_post_types['attachment'] ); // Remove attachments
				wp_cache_add( 'public_post_types', $public_post_types, 'conductor-widget' ); // Store cache
			}
		?>
			<?php do_action( 'conductor_widget_settings_before', $instance, $this ); ?>

			<div class="conductor-widget-setting conductor-widget-title">
				<?php do_action( 'conductor_widget_settings_title_before', $instance, $this ); ?>

				<?php // Widget Title ?>
				<label for="<?php echo $this->get_field_id( 'title' ) ; ?>"><strong><?php _e( 'Title', 'conductor' ); ?></strong></label>
				<br />

				<div class="conductor-widget-title-container">
					<input type="text" class="conductor-input" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
					<span class="conductor-hide-widget-title">
						<?php // Hide Widget Title ?>
						<input id="<?php echo $this->get_field_id( 'hide_title' ); ?>" name="<?php echo $this->get_field_name( 'hide_title' ); ?>" type="checkbox" <?php checked( $instance['hide_title'], true ); ?> />
						<label for="<?php echo $this->get_field_id( 'hide_title' ) ; ?>"><span class="dashicons dashicons-visibility"></span></label>
					</span>
				</div>

				<?php do_action( 'conductor_widget_settings_title_after', $instance, $this ); ?>
			</div>


			<div class="conductor-section conductor-section-top conductor-section-general conductor-accordion-section open" data-conductor-section="conductor-section-general">
				<div class="conductor-section-title conductor-accordion-section-title">
					<h3><?php _e( 'Content Settings', 'conductor' ); ?></h3>
				</div>

				<div class="conductor-section-inner">
					<div class="conductor-accordion-section-content">
						<?php do_action( 'conductor_widget_settings_content_section_before', $instance, $this ); ?>

						<p class="conductor-feature-content-pieces">
							<?php do_action( 'conductor_widget_settings_feature_content_pieces_before', $instance, $this ); ?>

							<?php // Feature Many (feature one or many pieces of content) ?>
							<label for="<?php echo $this->get_field_id( 'feature_many' ); ?>">
								<strong>
									<?php _ex( 'Select', 'Beginning of feature many label; before <select> elements', 'conductor' ); ?>
									<select class="conductor-select-feature-type conductor-select conductor-inline-select" id="<?php echo $this->get_field_id( 'feature_many' ); ?>" name="<?php echo $this->get_field_name( 'feature_many' ); ?>">
										<option value="" <?php selected( $instance['feature_many'], false ); ?>><?php _e( 'one', 'conductor' ); ?></option>
										<option value="true" <?php selected( $instance['feature_many'], true ); ?>><?php _e( 'many', 'conductor' ); ?></option>
										<?php do_action( 'conductor_widget_settings_feature_type', $instance, $this ); ?>
									</select>
								</strong>
							</label>

							<label for="<?php echo $this->get_field_id( 'post_type' ); ?>">
								<strong>
									<select class="conductor-select-content-type conductor-select conductor-inline-select" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
										<?php
											// Loop through public post types
											if ( ! empty( $public_post_types ) )
												foreach ( $public_post_types as $public_post_type ) :
													// Post Type Object
													if ( ! $public_post_type_object = wp_cache_get( 'public_post_type_' . $public_post_type . '_object', 'conductor-widget' ) ) {
														$public_post_type_object = get_post_type_object( $public_post_type );
														wp_cache_add( 'public_post_type_' . $public_post_type . '_object', $public_post_type_object, 'conductor-widget' ); // Store cache
													}

													// Post Count
													if ( ! $post_count = wp_cache_get( $public_post_type . '_post_type_count', 'conductor-widget' ) ) {
														$post_count = wp_count_posts( $public_post_type )->publish;
														wp_cache_add( $public_post_type . '_post_type_count', $post_count, 'conductor-widget' ); // Store cache
													}
													?>

													<?php if ( $post_count ) : ?>
														<option value="<?php echo esc_attr( $public_post_type_object->name ); ?>" <?php selected( $instance['content_type'], $public_post_type ); ?> data-type="<?php echo $public_post_type; ?>"><?php echo ( $public_post_type_object->labels->singular_name !== $public_post_type_object->labels->name ) ? sprintf( _x( '%1$s(s)', 'Possible plural value of post type singular name.', 'conductor' ), $public_post_type_object->labels->singular_name ) : $public_post_type_object->labels->name; ?></option>
													<?php endif; ?>
												<?php
												endforeach;
										?>
										<?php do_action( 'conductor_widget_settings_post_type', $instance, $this ); ?>
									</select>
									<?php _ex( 'to display.', 'End of feature many label; after <select> elements', 'conductor' ); ?>
								</strong>
							</label>

							<?php // Content Type ?>
							<input type="hidden" class="conductor-input conductor-content-type" id="<?php echo $this->get_field_id( 'content_type' ); ?>" name="<?php echo $this->get_field_name( 'content_type' ); ?>" value="<?php echo esc_attr( $instance['content_type'] ); ?>" />

							<?php do_action( 'conductor_widget_settings_feature_content_pieces_after', $instance, $this ); ?>
						</p>

						<div class="conductor-widget-setting conductor-select-feature-one conductor-feature-one <?php echo ( $instance['feature_many'] ) ? 'conductor-hidden' : false; ?>">
							<?php do_action( 'conductor_widget_settings_feature_one_before', $instance, $this ); ?>

							<?php
								// Loop through public post types
								if ( ! empty( $public_post_types ) )
									foreach ( $public_post_types as $public_post_type ) :
										// Post Count
										if ( ! $post_count = wp_cache_get( $public_post_type . '_post_type_count', 'conductor-widget' ) ) {
											$post_count = wp_count_posts( $public_post_type )->publish;
											wp_cache_add( $public_post_type . '_post_type_count', $post_count, 'conductor-widget' ); // Store cache
										}

										// Post Data
										if ( ! $posts = wp_cache_get( $public_post_type . '_data', 'conductor-widget' ) ) {
											$posts = $wpdb->get_results(
												$wpdb->prepare(
													"SELECT SQL_CALC_FOUND_ROWS $wpdb->posts.ID, $wpdb->posts.post_title FROM $wpdb->posts WHERE 1=1 AND $wpdb->posts.post_type = %s AND $wpdb->posts.post_status = 'publish' ORDER BY $wpdb->posts.post_title ASC LIMIT 0, %d", $public_post_type, $post_count
												)
											);
											wp_cache_add( $public_post_type . '_data', $posts, 'conductor-widget' ); // Store cache
										}

										// Display posts
										if ( ! empty( $posts ) ) :
											// Post Type Object
											if ( ! $public_post_type_object = wp_cache_get( 'public_post_type_' . $public_post_type . '_object', 'conductor-widget' ) ) {
												$public_post_type_object = get_post_type_object( $public_post_type );
												wp_cache_add( 'public_post_type_' . $public_post_type . '_object', $public_post_type_object, 'conductor-widget' ); // Store cache
											}
										?>
											<div class="conductor-feature-one conductor-content-type-field conductor-content-type-<?php echo esc_attr( $public_post_type ); ?> <?php echo ( $instance['feature_many'] || ( $instance['content_type'] !== $public_post_type ) ) ? 'conductor-hidden' : false; ?>">
												<label for="<?php echo $this->get_field_id( 'post_id_' . $public_post_type ); ?>">
													<strong>
														<?php printf( __( 'Select a %1$s', 'conductor' ), ( $public_post_type_object->labels->singular_name !== $public_post_type_object->labels->name ) ? $public_post_type_object->labels->singular_name : $public_post_type_object->labels->name ); ?>
													</strong>
												</label>
												<br />
												<select class="featured-one-select conductor-select" id="<?php echo $this->get_field_id( 'post_id_' . $public_post_type ); ?>">
													<option value=""><?php _e( '&mdash; Select &mdash;', 'conductor' ); ?></option>
													<?php foreach( $posts as $post ) : ?>
														<option value="<?php echo $post->ID; ?>" <?php selected( ( $instance['content_type'] === $public_post_type ) ? $instance['post_id'] : false, $post->ID ); ?> data-type="<?php echo $public_post_type; ?>"><?php echo ( $post->post_title === '' ) ? sprintf( __( '#%d (no title)' ), $post->ID ) : $post->post_title; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										<?php
										endif;
									endforeach;
							?>

							<?php do_action( 'conductor_widget_settings_feature_one', $instance, $this ); ?>

							<?php // Post ID ?>
							<input type="hidden" class="conductor-input conductor-post-id" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>" value="<?php echo esc_attr( $instance['post_id'] ); ?>" />

							<?php do_action( 'conductor_widget_settings_feature_one_after', $instance, $this ); ?>
						</div>

						<p class="conductor-select-cat conductor-feature-many conductor-content-type-field conductor-content-type-post <?php echo ( ! $instance['feature_many'] || $instance['content_type'] !== 'post' ) ? 'conductor-hidden' : false; ?>">
							<?php do_action( 'conductor_widget_settings_category_before', $instance, $this ); ?>

							<?php // Category ?>
							<label for="<?php echo $this->get_field_id( 'cat' ); ?>"><strong><?php _e( 'Category', 'conductor' ); ?></strong></label>
							<br />
							<?php
								// Show a list of categories
								wp_dropdown_categories( array(
									'name' => $this->get_field_name( 'cat' ),
									'selected' => $instance['query_args']['cat'],
									'catby' => 'Name', // TODO: Remove, not a valid argument
									'hierarchical' => true,
									'show_option_all' => __( 'All Categories', 'conductor' ),
									'hide_empty'  => false,
									'class' => 'conductor-select'
								) );
							?>
							<br />

							<?php do_action( 'conductor_widget_settings_category_after', $instance, $this ); ?>
						</p>

						<p class="conductor-select-orderby conductor-feature-many <?php echo ( ! $instance['feature_many'] ) ? 'conductor-hidden' : false; ?>">
							<?php do_action( 'conductor_widget_settings_orderby_before', $instance, $this ); ?>

							<?php // Orderby ?>
							<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><strong><?php _e( 'Order By', 'conductor' ); ?></strong></label>
							<br />
							<select name="<?php echo $this->get_field_name( 'orderby' ); ?>" id="<?php echo $this->get_field_id( 'orderby' ); ?>" class="conductor-orderby conductor-select">
								<option value=""><?php _e( '&mdash; Select &mdash;', 'conductor' ); ?></option>
								<option value="author" <?php selected( $instance['query_args']['orderby'], 'author' ); ?> data-type="built-in"><?php _e( 'Author', 'conductor' ); ?></option>
								<option value="comment_count" <?php selected( $instance['query_args']['orderby'], 'comment_count' ); ?> data-type="built-in"><?php _e( 'Comment Count', 'conductor' ); ?></option>
								<option value="date" <?php selected( $instance['query_args']['orderby'], 'date' ); ?> data-type="built-in"><?php _e( 'Date', 'conductor' ); ?></option>
								<option value="ID" <?php selected( $instance['query_args']['orderby'], 'ID' ); ?> data-type="built-in"><?php _e( 'ID', 'conductor' ); ?></option>
								<option value="parent" <?php selected( $instance['query_args']['orderby'], 'parent' ); ?> data-type="built-in"><?php _e( 'Parent', 'conductor' ); ?></option>
								<option value="name" <?php selected( $instance['query_args']['orderby'], 'name' ); ?> data-type="built-in"><?php _e( 'Post Slug', 'conductor' ); ?></option>
								<option value="title" <?php selected( $instance['query_args']['orderby'], 'title' ); ?> data-type="built-in"><?php _e( 'Title', 'conductor' ); ?></option>
								<option value="rand" <?php selected( $instance['query_args']['orderby'], 'rand' ); ?> data-type="built-in"><?php _e( 'Random', 'conductor' ); ?></option>
								<?php do_action( 'conductor_widget_settings_orderby', $instance, $this ); ?>
							</select>

							<?php do_action( 'conductor_widget_settings_orderby_after', $instance, $this ); ?>
						</p>

						<p class="conductor-select-order conductor-feature-many <?php echo ( ! $instance['feature_many'] ) ? 'conductor-hidden' : false; ?>">
							<?php do_action( 'conductor_widget_settings_order_before', $instance, $this ); ?>

							<?php // Order ?>
							<label for="<?php echo $this->get_field_id( 'order' ); ?>"><strong><?php _e( 'Order', 'conductor' ); ?></strong></label>
							<br />
							<select name="<?php echo $this->get_field_name( 'order' ); ?>" id="<?php echo $this->get_field_id( 'order' ); ?>" class="conductor-order conductor-select">
								<option value=""><?php _e( '&mdash; Select &mdash;', 'conductor' ); ?></option>
								<option value="ASC" <?php selected( $instance['query_args']['order'], 'ASC' ); ?>><?php _e( 'Ascending (1, 2, 3)', 'conductor' ); ?></option>
								<option value="DESC" <?php selected( $instance['query_args']['order'], 'DESC' ); ?>><?php _e( 'Descending (3, 2, 1)', 'conductor' ); ?></option>
							</select>

							<?php do_action( 'conductor_widget_settings_order_after', $instance, $this ); ?>
						</p>

						<p class="conductor-max-num-posts conductor-feature-many <?php echo ( ! $instance['feature_many'] ) ? 'conductor-hidden' : false; ?>">
							<?php do_action( 'conductor_widget_settings_max_num_posts_before', $instance, $this ); ?>

							<?php // Number of Posts to Display ?>
							<label for="<?php echo $this->get_field_id( 'max_num_posts' ); ?>">
								<strong><?php _ex( 'Show', 'Beginning of maximum number of posts to display label; before <input> element', 'conductor' ); ?>
									<input type="text" class="conductor-input conductor-inline-input conductor-number" id="<?php echo $this->get_field_id( 'max_num_posts' ); ?>" name="<?php echo $this->get_field_name( 'max_num_posts' ); ?>" value="<?php echo esc_attr( $instance['query_args']['max_num_posts'] ); ?>" placeholder="<?php _ex( '#', 'placeholder for number input elements', 'conductor' ); ?>" />
									<?php _ex( 'posts', 'End of number of maximum posts to display label; after <input> element', 'conductor' ); ?>
								</strong>
							</label>
							<?php // Number of Posts to Display ?>
							<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>">
								<strong><?php _ex( 'with', 'Beginning of number of posts per page to display label; before <input> element', 'conductor' ); ?>
									<input type="text" class="conductor-input conductor-inline-input conductor-number" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php echo esc_attr( $instance['query_args']['posts_per_page'] ); ?>" placeholder="<?php _ex( '#', 'placeholder for number input elements', 'conductor' ); ?>" />
									<?php _ex( 'per page.', 'End of number of posts per page to display label; after <input> element', 'conductor' ); ?>
								</strong>
							</label>
							<br />
							<small class="description conductor-description"><?php printf( __( 'Leave blank to show all. <a href="%1$s" target="_blank">Learn more about pagination</a>.', 'conductor' ), esc_url( 'http://codex.wordpress.org/Pagination' ) ); ?></small>

							<?php do_action( 'conductor_widget_settings_max_num_posts_after', $instance, $this ); ?>
						</p>

						<p class="conductor-offset conductor-feature-many <?php echo ( ! $instance['feature_many'] ) ? 'conductor-hidden' : false; ?>">
							<?php do_action( 'conductor_widget_settings_offset_before', $instance, $this ); ?>

							<?php // Offset (Number of post to offset by) ?>
							<label for="<?php echo $this->get_field_id( 'offset' ); ?>">
								<strong>
									<?php _ex( 'Start at post #', 'Beginning of post offset label; before <input> element', 'conductor' ); ?>
									<input type="text" class="conductor-input conductor-inline-input conductor-number" id="<?php echo $this->get_field_id( 'offset' ); ?>" name="<?php echo $this->get_field_name( 'offset' ); ?>" value="<?php echo esc_attr( $instance['query_args']['offset'] ); ?>" />
									<?php _ex( '.', 'End of post offset label; after <input> element', 'conductor' ); ?>
								</strong>
							</label>

							<?php do_action( 'conductor_widget_settings_offset_after', $instance, $this ); ?>
						</p>

						<?php do_action( 'conductor_widget_settings_content_section_after', $instance, $this ); ?>
					</div>
				</div>
			</div>

			<div class="conductor-section conductor-section-display conductor-accordion-section" data-conductor-section="conductor-section-display">
				<div class="conductor-section-title conductor-accordion-section-title">
					<h3><?php _e( 'Display Settings', 'conductor' ); ?></h3>
				</div>

				<div class="conductor-section-inner">
					<div class="conductor-accordion-section-content">
						<?php do_action( 'conductor_widget_settings_display_section_before', $instance, $this ); ?>

						<div class="conductor-select-widget-size">
							<?php do_action( 'conductor_widget_settings_widget_size_before', $instance, $this ); ?>

							<p>
								<?php // Widget Size (size of the widget on the front end of the site) ?>
								<label for="<?php echo $this->get_field_id( 'widget_size' ); ?>"><strong><?php _e( 'Select a Widget Display', 'conductor' ); ?></strong></label>
								<br />
								<small class="description conductor-description"><?php _e( 'Change the display of the content displayed on the front end.', 'conductor' ); ?></small>
							</p>
							<div class="conductor-widget-size-wrap">
								<?php
									// Reference to the widget display configuration
									$widget_display_config = false;

									// Output widget displays (as of 1.3.0 $config can now be an array with configuration data; $config used to be referenced as $label which just contained a translated label string)
									foreach ( $this->displays as $size => $config ) :
										// Store a reference to the widget display_config
										$widget_display_config = ( $size === $instance['widget_size'] ) ? $config : $widget_display_config;

										// Data attributes
										$data_attrs = '';

										// Only if we have a configuration array
										if ( is_array( $config ) ) {
											// Data Attributes
											// TODO: Document attribute naming convention
											// TODO: Eventually loop through all 'customize' properties
											$data_attrs = array(
												'data-conductor-customize-columns' => $this->widget_display_supports_customize_property( $config, 'columns' ), // Reference $config and not $widget_display_config
												'data-conductor-customize-single-columns' => ( $this->widget_display_supports_customize_property( $config, 'columns' ) && is_array( $config['customize']['columns'] ) && $this->widget_display_customize_property_exists( $config['customize']['columns'], 'single', true ) ) ? $this->widget_display_get_customize_property_value( $config['customize']['columns'], 'single', true ) : '', // Reference $config and not $widget_display_config
												'data-conductor-customize-many-columns' => ( $this->widget_display_supports_customize_property( $config, 'columns' ) && is_array( $config['customize']['columns'] ) && $this->widget_display_customize_property_exists( $config['customize']['columns'], 'many', true ) ) ? $this->widget_display_get_customize_property_value( $config['customize']['columns'], 'many', true ) : '' // Reference $config and not $widget_display_config
											);
											$data_attrs = apply_filters( 'conductor_widget_display_data_attributes', $data_attrs, $config, $size, $instance, $this );

											$data_attrs = $this->prepare_data_attributes( $data_attrs );
										}
								?>
										<div class="conductor-widget-size conductor-widget-size-<?php echo esc_attr( $size ); ?>">
											<label>
												<input type="radio" class="conductor-widget-size-value" name="<?php echo $this->get_field_name( 'widget_size' ); ?>" id="<?php echo $this->get_field_id( 'widget_size_' . $size ); ?>" value="<?php echo esc_attr( $size ); ?>" <?php checked( $instance['widget_size'], $size ); ?> <?php echo $data_attrs; ?> />

												<div class="conductor-widget-size-preview">
													<?php
														// Output correct markup for $size
														switch ( $size ) :
															// Small
															case 'small':
															?>
																<div class="posts posts-small">
																	<div class="post post-small">
																		<div class="featured-image post-featured-image"></div>
																		<div class="title post-title dashicons dashicons-minus"></div>
																		<div class="content post-content dashicons dashicons-text"></div>
																	</div>
																	<div class="post post-small">
																		<div class="featured-image post-featured-image"></div>
																		<div class="title post-title dashicons dashicons-minus"></div>
																		<div class="content post-content dashicons dashicons-text"></div>
																	</div>
																	<div class="post post-small">
																		<div class="featured-image post-featured-image"></div>
																		<div class="title post-title dashicons dashicons-minus"></div>
																		<div class="content post-content dashicons dashicons-text"></div>
																	</div>
																	<div class="post post-small">
																		<div class="featured-image post-featured-image"></div>
																		<div class="title post-title dashicons dashicons-minus"></div>
																		<div class="content post-content dashicons dashicons-text"></div>
																	</div>
																</div>
															<?php
															break;
															// Medium
															case 'medium':
															?>
																<div class="posts posts-medium">
																	<div class="post post-medium">
																		<div class="featured-image post-featured-image"></div>
																		<div class="title post-title dashicons dashicons-minus"></div>
																		<div class="content post-content dashicons dashicons-text"></div>
																	</div>
																	<div class="post post-medium">
																		<div class="featured-image post-featured-image"></div>
																		<div class="title post-title dashicons dashicons-minus"></div>
																		<div class="content post-content dashicons dashicons-text"></div>
																	</div>
																</div>
															<?php
															break;
															// Large
															case 'large':
															?>
																<div class="posts posts-large">
																	<div class="post post-large">
																		<div class="featured-image post-featured-image"></div>
																		<div class="title post-title dashicons dashicons-minus"></div>
																		<div class="content post-content dashicons dashicons-text"></div>
																	</div>
																</div>
															<?php
															break;
															// Flexbox
															case 'flexbox':
															?>
																<div class="posts posts-flexbox">
																	<div class="post post-flexbox">
																		<div class="flexbox dashicons dashicons-admin-tools"></div>
																	</div>
																</div>
															<?php
															break;
															// Other
															default:
																do_action( 'conductor_widget_settings_display_preview', $size, $instance, $this );
																do_action( 'conductor_widget_settings_display_preview_' . $size, $instance, $this );
															break;
														endswitch;
													?>
												</div>
											</label>

											<span class="conductor-widget-size-label widget-size-label label"><?php echo ( is_array( $config ) ) ? $config['label'] : $config; ?></span>
										</div>
								<?php
									endforeach;
								?>
							</div>

							<div class="conductor-columns conductor-flexbox-columns conductor-customize-columns <?php echo ( ! $this->widget_display_customize_property_supported_for_query_type( $widget_display_config, 'columns', $instance['feature_many'] ) ) ? 'conductor-hidden' : false; ?>">
								<?php // Flexbox Columns ?>
								<label for="<?php echo $this->get_field_id( 'flexbox_columns' ); ?>"><strong><?php _e( 'Number of Columns', 'conductor' ); ?></strong></label>
								<br />
								<input type="range" min="1" max="<?php echo esc_attr( $this->max_columns ); ?>" class="conductor-input conductor-flexbox-columns-range" id="<?php echo $this->get_field_id( 'flexbox_columns' ); ?>" name="<?php echo $this->get_field_name( 'flexbox_columns' ); ?>" value="<?php echo esc_attr( $instance['flexbox']['columns'] ); ?>" />
								<span class="conductor-flexbox-columns-value"><?php echo $instance['flexbox']['columns']; ?></span>
								<br />
								<small class="description conductor-description"><?php _e( 'Specify the number of columns used when outputting widget content.', 'conductor' ); ?></small>
							</div>

							<?php do_action( 'conductor_widget_settings_widget_size_after', $instance, $this ); ?>
						</div>

						<div class="conductor-widget-setting conductor-output">
							<?php do_action( 'conductor_widget_output_before', $instance, $this ); // TODO: Depreciate in a future version ?>
							<?php do_action( 'conductor_widget_settings_output_before', $instance, $this ); ?>

							<?php // Output (order of elements output on the front-end) ?>
							<label for="<?php echo $this->get_field_id( 'output' ); ?>"><strong><?php _e( 'Adjust Output Elements', 'conductor' ); ?></strong></label>
							<br />

							<?php do_action( 'conductor_widget_output_list_before', $instance, $this ); // TODO: Depreciate in a future version ?>
							<?php do_action( 'conductor_widget_settings_output_list_before', $instance, $this ); ?>

							<br />
							<ul class="conductor-widget-output-list conductor-output-list" data-widget-id-base="<?php echo esc_attr( $this->id_base ); ?>" data-widget-number="<?php echo esc_attr( $this->number ); ?>">
								<?php
								// Will be in order based on priority
								if ( ! empty( $instance['output'] ) )
									foreach ( $instance['output'] as $priority => $element ) {
										$id = $element['id'];
										$type = $element['type'];

										// Determine the features this element supports (first by id)
										$supports = ( isset( $this->defaults['output_features'][$id] ) ) ? $this->defaults['output_features'][$id] : array();

										// Find support by type if not found by id
										if ( empty( $supports ) )
											$supports = ( isset( $this->defaults['output_features'][$type] ) ) ? $this->defaults['output_features'][$type] : array();

										// Allow add-ons/developers to create their own output
										$output = apply_filters( 'conductor_widget_output_element_output', '', $element, $priority, $id, $supports, $instance, $this->defaults, $this );

										// If the output isn't empty, use it instead of expected output
										if ( ! empty( $output ) ) {
											echo $output;
											break;
										}

										// Generate CSS Classes
										$css_classes = array(
											'ui-state-default',
											'conductor-widget-output-element',
											'conductor-widget-output-element-' . $element['id']
										);

										// Visible CSS Class
										if ( isset( $element['visible'] ) && $element['visible'] )
											$css_classes[] = 'visible';

										// Link CSS Class
										if ( $supports && array_key_exists( 'link', $supports ) && isset( $element['link'] ) && $element['link'] )
											$css_classes[] = 'link';

										// TODO: array_filter/sanitize_html_class for CSS classes

										$output = '<li class="' . implode( ' ', $css_classes ) . '"'; // Start the element
											$output .= ' data-priority="' . esc_attr( $priority ) . '"'; // Priority
											$output .= ' data-id="' . esc_attr( $element['id'] ) . '"'; // ID
											$output .= ' data-label="' . esc_attr( $element['label'] ) . '"'; // Label
											$output .= ' data-type="' . esc_attr( $element['type'] ) . '"'; // Type
											$output .= ( isset( $element['visible'] ) && $element['visible'] ) ? ' data-visible="true"' : ' data-visible="false"'; // Visible
											$output .= ( $supports && array_key_exists( 'link', $supports ) && isset( $element['link'] ) && $element['link'] ) ? ' data-link="true"' : ' data-link="false"'; // Link
											$output .= ( $supports && array_key_exists( 'edit_content_type', $supports ) ) ? ' data-value="' . $element['value'] .'"' : false; // Edit Content Type (special case; content/excerpt only)
										$output .= '>'; // End the element
										$output .= ' <div class="dashicons dashicons-sort"></div>'; // Sort handle

										// Label Editing TODO: This logic should check if value is a Boolean "true" in the future and adjust output accordingly, currently it is expected to be an array with a default label passed in
										if ( $supports && array_key_exists( 'edit_label', $supports ) ) {
											$output .= ' <span class="conductor-widget-output-element-label conductor-widget-output-element-label-editable editable-input">';
												$output .= '<span class="label">' . $element['label'] . '</span>';
												$output .= ' <div class="dashicons dashicons-edit"></div>';
												$output .= '<div class="conductor-widget-output-element-label-input">';
													$output .= '<input value="';
														$output .= ( ( ! empty( $supports['edit_label']['default'] ) && $element['label'] !== $supports['edit_label']['default'] ) || ( empty( $supports['edit_label']['default'] ) && $element['label'] !== $element['id'] ) ) ? $element['label'] .'"' : '"';
														$output .= 'placeholder="' . __( 'Enter custom label...', 'conductor' ) .'"';
														$output .= ( ! empty( $supports['edit_label']['default'] ) ) ? 'data-original="' . $supports['edit_label']['default'] . '"' : 'data-original="' . $element['id'] . '"';
														$output .= ' data-current="" />';
													$output .= '<div class="dashicons dashicons-no-alt conductor-widget-discard"></div>';
													$output .= '<div class="dashicons dashicons-yes conductor-widget-save"></div>';
												$output .= '</div>';
											$output .= '</span>';
										}
										// Content Type Editing (special case)
										else if ( $supports && array_key_exists( 'edit_content_type', $supports ) ) {
											$output .= ' <span class="conductor-widget-output-element-label conductor-widget-output-element-label-editable editable-select">';
												$output .= '<span class="label">' . $element['label'] . '</span>';
												$output .= ' <div class="dashicons dashicons-edit"></div>';
												$output .= '<div class="conductor-widget-output-element-label-input conductor-widget-output-element-label-select">';
													$output .= '<select data-current="';
													$output .= ( isset( $element['value'] ) && ! empty( $element['value'] ) ) ? $element['value'] .'">' : '">';
														$output .= '<option value="content" data-label="' .__( 'Content', 'conductor' ) . '"' . selected( $element['value'], 'content', false ) . '>' . __( 'Content', 'conductor' ) . '</option>';
														$output .= '<option value="excerpt" data-label="' .__( 'Excerpt', 'conductor' ) . '"' . selected( $element['value'], 'excerpt', false ) . '>' . __( 'Excerpt', 'conductor' ) . '</option>';
													$output .= '</select>';
													$output .= '<div class="dashicons dashicons-no-alt conductor-widget-discard"></div>';
													$output .= '<div class="dashicons dashicons-yes conductor-widget-save"></div>';
												$output .= '</div>';
											$output .= '</span>';
										}
										// Regular Label
										else
											$output .= ' <span class="conductor-widget-output-element-label">' . $element['label'] . '</span>';


										// Controls
										$output .= ' <span class="conductor-widget-output-element-controls">';
											// Link
											if ( $supports && array_key_exists( 'link', $supports ) )
												$output .= ' <div class="dashicons dashicons-admin-links conductor-widget-link"></div>';

											// Removal
											if ( $supports && array_key_exists( 'remove', $supports ) )
												$output .= ' <div class="dashicons dashicons-no-alt conductor-widget-remove"></div>';

											// Visibility
											$output .= ' <div class="dashicons dashicons-visibility conductor-widget-visibility"></div>';
										$output .= '</span>';
										$output .= '</li>';

										// Output
										echo $output;
									}
								?>
							</ul>

							<?php
								// Remove the callback parameter from the instance (for serialized data in hidden input element)
								$output_without_callbacks = array();

								if ( ! empty( $instance['output'] ) ) {
									$output_without_callbacks = $instance['output'];

									// Remove the callback parameter
									foreach( $output_without_callbacks as &$element )
										unset( $element['callback'] );
								}
							?>
							<input type="hidden" class="conductor-input conductor-output-data" id="<?php echo $this->get_field_id( 'output' ); ?>" name="<?php echo $this->get_field_name( 'output' ); ?>" value="<?php echo esc_attr( json_encode( $output_without_callbacks ) ); ?>" />
							<small class="description conductor-description"><?php _e( 'Adjust the order of elements output on the front-end display.', 'conductor' ); ?></small> <?php // TODO: Adjust description ?>

							<?php do_action( 'conductor_widget_output_after', $instance, $this ); // TODO: Depreciate in a future version ?>
							<?php do_action( 'conductor_widget_settings_output_after', $instance, $this ); ?>
						</div>

						<?php do_action( 'conductor_widget_settings_display_section_after', $instance, $this ); ?>
					</div>
				</div>
			</div>

			<div class="conductor-section conductor-section-advanced conductor-accordion-section" data-conductor-section="conductor-section-advanced">
				<div class="conductor-section-title conductor-accordion-section-title">
					<h3><?php _e( 'Advanced Settings', 'conductor' ); ?></h3>
				</div>

				<div class="conductor-section-inner">
					<div class="conductor-accordion-section-content">
						<?php do_action( 'conductor_widget_settings_advanced_section_before', $instance, $this ); ?>

						<p class="conductor-post-thumbnails-size">
							<?php do_action( 'conductor_widget_settings_post_thumbnails_size_before', $instance, $this ); ?>

							<?php // Featured Image Size ?>
							<label for="<?php echo $this->get_field_id( 'post_thumbnails_size' ); ?>"><strong><?php _e( 'Featured Image Size', 'conductor' ); ?></strong></label>
							<br />
							<select name="<?php echo $this->get_field_name( 'post_thumbnails_size' ); ?>" id="<?php echo $this->get_field_id( 'post_thumbnails_size' ); ?>" class="conductor-select">
								<option value=""><?php _e( '&mdash; Select &mdash;', 'conductor' ); ?></option>
								<?php
								// Get all of the available image sizes
								if ( ! $avail_image_sizes = wp_cache_get( 'avail_image_sizes', 'conductor-widget' ) ) {
									$avail_image_sizes = array();
									foreach ( get_intermediate_image_sizes() as $size ) {
										$avail_image_sizes[$size] = array(
											'label' => '',
											'width' => 0,
											'height' => 0
										);

										// Built-in Image Sizes
										if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
											$avail_image_sizes[$size]['label'] = $size;
											$avail_image_sizes[$size]['width'] = get_option( $size . '_size_w' );
											$avail_image_sizes[$size]['height'] = get_option( $size . '_size_h' );
										}
										// Additional Image Sizes
										else if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[$size] ) ) {
											$avail_image_sizes[$size]['label'] = $size;
											$avail_image_sizes[$size]['width'] = $_wp_additional_image_sizes[$size]['width'];
											$avail_image_sizes[$size]['height'] = $_wp_additional_image_sizes[$size]['height'];
										}

										// If any of the dimension values happen to be zero, make them 9999 (i.e. crop ratio or infinite)
										if ( ( int ) $avail_image_sizes[$size]['width'] === 0 )
											$avail_image_sizes[$size]['width'] = 9999;

										if ( ( int ) $avail_image_sizes[$size]['height'] === 0 )
											$avail_image_sizes[$size]['height'] = 9999;
									}

									$avail_image_sizes = apply_filters( 'conductor_widget_available_image_sizes', $avail_image_sizes, $instance, $this );

									wp_cache_add( 'avail_image_sizes', $avail_image_sizes, 'conductor-widget' ); // Store cache
								}

								foreach ( $avail_image_sizes as $size => $atts ) :
									$dimensions = array( $atts['width'], $atts['height'] );
								?>
									<option value="<?php echo esc_attr( $size ); ?>" <?php selected( $instance['post_thumbnails_size'], $size ); ?>><?php echo $atts['label'] . ' (' . implode( ' x ', $dimensions ) . ')'; ?></option>
								<?php
								endforeach;
								?>
							</select>
							<small class="description conductor-description"><?php printf( __( 'Featured Images are displayed based on the Widget Size in the Display Settings. <a href="%1$s" target="_blank">Learn more about Featured Images</a>.', 'conductor' ), esc_url( 'http://codex.wordpress.org/Post_Thumbnails' ) ); ?></small>

							<?php do_action( 'conductor_widget_settings_post_thumbnails_size_after', $instance, $this ); ?>
						</p>

						<p class="conductor-excerpt-length conductor-display-content-type-field conductor-display-content-type-excerpt <?php echo ( $instance['content_display_type'] !== 'excerpt' ) ? 'conductor-hidden' : false; ?>">
							<?php do_action( 'conductor_widget_settings_excerpt_length_before', $instance, $this ); ?>

							<?php // Excerpt Length ?>
							<label for="<?php echo $this->get_field_id( 'excerpt_length' ); ?>">
								<strong>
									<?php _ex( 'Limit excerpt to', 'Beginning of content limit label; before <input> element', 'conductor' ); ?>
									<input type="text" class="conductor-input conductor-inline-input conductor-number" id="<?php echo $this->get_field_id( 'excerpt_length' ); ?>" name="<?php echo $this->get_field_name( 'excerpt_length' ); ?>" value="<?php echo esc_attr( $instance['excerpt_length'] ); ?>" placeholder="<?php _ex( '#', 'placeholder for number input elements', 'conductor' ); ?>" />
									<?php _ex( 'words.', 'End of content limit label; after <input> element', 'conductor' ); ?>
								</strong>
							</label>

							<?php do_action( 'conductor_widget_settings_excerpt_length_after', $instance, $this ); ?>
						</p>

						<p class="conductor-css-class">
							<?php do_action( 'conductor_widget_settings_css_class_before', $instance, $this ); ?>

							<?php // CSS Class ?>
							<label for="<?php echo $this->get_field_id( 'css_class' ); ?>"><strong><?php _e( 'CSS Class(es)', 'conductor' ); ?></strong></label>
							<br />
							<input type="text" class="conductor-input" id="<?php echo $this->get_field_id( 'css_class' ); ?>" name="<?php echo $this->get_field_name( 'css_class' ); ?>" value="<?php echo esc_attr( $instance['css_class'] ); ?>" />
							<br />
							<small class="description conductor-description"><?php printf( __( 'Target this widget on the front-end (e.g. my-custom-conductor-widget content-bold). <a href="%1$s" target="_blank">Learn more about CSS</a>.', 'conductor' ),esc_url( 'http://codex.wordpress.org/CSS' ) ); ?></small>

							<?php do_action( 'conductor_widget_settings_css_class_after', $instance, $this ); ?>
						</p>

						<?php // TODO: Finish this logic ?>
						<!--p class="conductor-css-id">
							<?php // CSS ID ?>
							<label for="<?php echo $this->get_field_id( 'css_id' ); ?>"><strong><?php _e( 'CSS ID', 'conductor' ); ?></strong></label>
							<br />
							<input type="text" class="conductor-input conductor-css-id" id="<?php echo $this->get_field_id( 'css_id' ); ?>" name="<?php echo $this->get_field_name( 'css_id' ); ?>" value="" />
						</p-->

						<p class="conductor-post-in conductor-feature-many <?php echo ( ! $instance['feature_many'] ) ? 'conductor-hidden' : false; ?>">
							<?php do_action( 'conductor_widget_settings_post__in_before', $instance, $this ); ?>

							<?php // Post In (posts to specifically include) ?>
							<label for="<?php echo $this->get_field_id( 'post__in' ); ?>"><strong><?php _e( 'Include Only These Posts', 'conductor' ); ?></strong></label>
							<br />
							<input type="text" class="conductor-input" id="<?php echo $this->get_field_id( 'post__in' ); ?>" name="<?php echo $this->get_field_name( 'post__in' ); ?>" value="<?php echo esc_attr( $instance['query_args']['post__in'] ); ?>" />
							<br />
							<small class="description conductor-description"><?php printf( __( 'Comma separated list of post IDs. Only these posts will be displayed. Some settings above will be ignored. <a href="%1$s" target="_blank">How do I find an ID?</a>', 'conductor' ), esc_url( 'http://codex.wordpress.org/FAQ_Working_with_WordPress#How_do_I_determine_a_Post.2C_Page.2C_Category.2C_Tag.2C_Link.2C_Link_Category.2C_or_User_ID.3F' ) ); ?></small>

							<?php do_action( 'conductor_widget_settings_post__in_after', $instance, $this ); ?>
						</p>

						<p class="conductor-post-not-in conductor-feature-many <?php echo ( ! $instance['feature_many'] ) ? 'conductor-hidden' : false; ?>">
							<?php do_action( 'conductor_widget_settings_post__not_in_before', $instance, $this ); ?>

							<?php // Post Not In (posts to specifically exclude) ?>
							<label for="<?php echo $this->get_field_id( 'post__not_in' ); ?>"><strong><?php _e( 'Exclude These Posts', 'conductor' ); ?></strong></label>
							<br />
							<input type="text" class="conductor-input" id="<?php echo $this->get_field_id( 'post__not_in' ); ?>" name="<?php echo $this->get_field_name( 'post__not_in' ); ?>" value="<?php echo esc_attr( $instance['query_args']['post__not_in'] ); ?>" />
							<br />
							<small class="description conductor-description"><?php printf( __( 'Comma separated list of post IDs. Will display all posts based on settings above, except those in this list. <a href="%1$s" target="_blank">How do I find an ID?</a>', 'conductor' ), esc_url( 'http://codex.wordpress.org/FAQ_Working_with_WordPress#How_do_I_determine_a_Post.2C_Page.2C_Category.2C_Tag.2C_Link.2C_Link_Category.2C_or_User_ID.3F' ) ); ?></small>

							<?php do_action( 'conductor_widget_settings_post__not_in_after', $instance, $this ); ?>
						</p>

						<?php do_action( 'conductor_widget_settings_advanced_section_after', $instance, $this ); ?>
					</div>
				</div>
			</div>

			<?php do_action( 'conductor_widget_settings_after', $instance, $this ); ?>

			<div class="clear"></div>

			<p class="conductor-widget-slug">
				<?php // TODO Adjust content and URL ?>
				<?php printf( __( 'Content management brought to you by <a href="%1$s" target="_blank">Conductor</a>','conductor' ), esc_url( 'https://conductorplugin.com/?utm_source=conductor&utm_medium=link&utm_content=conductor-widget-branding&utm_campaign=conductor' ) ); ?>
			</p>
		<?php
		}

		/**
		 * This function handles updating (saving) widget options
		 * TODO: Unset all settings that are not used in widget (i.e. max_num_posts)
		 * TODO: Reference default values here during sanitization
		 */
		public function update( $new_instance, $old_instance ) {
			// Sanitize all input data
			$new_instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : false; // Widget Title
			$new_instance['widget_size'] = ( ! empty( $new_instance['widget_size'] ) ) ? sanitize_text_field( $new_instance['widget_size'] ) : 'large'; // Widget Size (default to large)

			// Flexbox Columns
			$new_instance['flexbox']['columns'] = ( isset( $new_instance['flexbox_columns'] ) ) ? ( int ) $new_instance['flexbox_columns'] : 1;

			// General
			$new_instance['feature_many'] = ( ! empty( $new_instance['feature_many'] ) ) ? true : false; // Feature Many

			// Content Type
			$new_instance['content_type'] = ( ! empty( $new_instance['content_type'] ) ) ? sanitize_text_field( $new_instance['content_type'] ) : false; // Content Type

			// Feature One
			$new_instance['post_id'] = ( ! $new_instance['feature_many'] && ! empty( $new_instance['post_id'] ) ) ? abs( ( int ) $new_instance['post_id'] ) : false; // Post ID (feature one)

			// Feature Many
			$new_instance['query_args']['post_type'] = ( $new_instance['feature_many'] && ! empty( $new_instance['post_type'] ) ) ? sanitize_text_field( $new_instance['post_type'] ) : 'post'; // Post Type
			$new_instance['query_args']['cat'] = ( $new_instance['feature_many'] && ! empty( $new_instance['cat'] ) ) ? abs( ( int ) $new_instance['cat'] ) : false; // Category ID
			$new_instance['query_args']['orderby'] = ( $new_instance['orderby'] && ! empty( $new_instance['orderby'] ) ) ? sanitize_text_field( $new_instance['orderby'] ) : 'date'; // Order By
			$new_instance['query_args']['order'] = ( $new_instance['orderby'] && ! empty( $new_instance['order'] ) ) ? sanitize_text_field( $new_instance['order'] ) : 'DESC'; // Order
			$new_instance['query_args']['max_num_posts'] = ( $new_instance['feature_many'] && ! empty( $new_instance['max_num_posts'] ) && ( int ) $new_instance['max_num_posts'] > 0 ) ? abs( ( int ) $new_instance['max_num_posts'] ) : ''; // Maximum Number of Posts
			$new_instance['query_args']['offset'] = ( $new_instance['feature_many'] && ! empty( $new_instance['offset'] ) ) ? abs( ( int ) $new_instance['offset'] ) : 1; // Offset
			$new_instance['query_args']['posts_per_page'] = ( $new_instance['feature_many'] && ! empty( $new_instance['posts_per_page'] ) ) ? abs( ( int ) $new_instance['posts_per_page'] ) : ''; // Number of Posts Per Page

			// Maximum Number of Posts (further sanitization)
			if ( ! $new_instance['feature_many'] )
				$new_instance['query_args']['max_num_posts'] = get_option( 'posts_per_page' );

			// Posts Per Page (further sanitization)
			if ( $new_instance['query_args']['max_num_posts'] !== '' && ! empty( $new_instance['query_args']['posts_per_page'] ) && $new_instance['query_args']['posts_per_page'] > $new_instance['query_args']['max_num_posts'] )
				$new_instance['query_args']['posts_per_page'] = $new_instance['query_args']['max_num_posts'];

			// Post In
			if ( $new_instance['feature_many'] && ! empty( $new_instance['post__in'] ) ) {
				// Keep only digits and commas
				preg_match_all( '/\d+(?:,\d+)*/', $new_instance['post__in'], $new_instance['query_args']['post__in'] );
				$new_instance['query_args']['post__in'] = implode( ',', $new_instance['query_args']['post__in'][0] );
			}
			else
				$new_instance['query_args']['post__in'] = false;

			// Post Not In
			if ( $new_instance['feature_many'] && ! empty( $new_instance['post__not_in'] ) ) {
				// Keep only digits and commas
				preg_match_all( '/\d+(?:,\d+)*/', $new_instance['post__not_in'], $new_instance['query_args']['post__not_in'] );
				$new_instance['query_args']['post__not_in'] = implode( ',', $new_instance['query_args']['post__not_in'][0] );
			}
			else
				$new_instance['query_args']['post__not_in'] = false;

			// Display
			$new_instance['hide_title'] = ( isset( $new_instance['hide_title'] ) ) ? true : false; // Hide Widget Title
			$new_instance['hide_post_title'] = ( isset( $new_instance['hide_post_title'] ) ) ? true : false; // Hide Post Title
			$new_instance['hide_author_byline'] = ( isset( $new_instance['hide_author_byline'] ) ) ? true : false; // Hide Author Byline
			$new_instance['hide_post_thumbnails'] = ( isset( $new_instance['hide_post_thumbnails'] ) ) ? true : false; // Featured Images
			$new_instance['hide_read_more'] = ( isset( $new_instance['hide_read_more'] ) ) ? true : false; // Hide Read More Link
			$new_instance['post_thumbnails_size'] = ( ! empty( $new_instance['post_thumbnails_size'] ) ) ? sanitize_text_field( $new_instance['post_thumbnails_size'] ) : false; // Post Thumbnails Size
			$new_instance['excerpt_length'] = abs( ( int ) $new_instance['excerpt_length'] ); // Excerpt Length

			/*
			 * Display - Output
			 *
			 * The Conductor_Widget_Query class will check for method_exists() and/or is_callable() before calling
			 * add_action() to ensure there are no errors/warnings.
			 */
			$output_elements = ( ! empty( $new_instance['output'] ) ) ? json_decode( $new_instance['output'], true ) : false;

			if ( ! empty( $output_elements ) ) {
				$callback_prefix = apply_filters( 'conductor_widget_query_callback_prefix', 'conductor_widget_', $new_instance, $old_instance ); // Callback function prefix

				// Reset the output array first
				$new_instance['output'] = $new_instance['output_elements'] = array();

				// Loop through each output element
				foreach ( $output_elements as $priority => $element ) {
					$the_priority = ( int ) $priority;
					$id = sanitize_text_field( $element['id'] );

					// Create a sanitized array of data
					$new_instance['output'][$the_priority] = array(
						'id' => $id,
						'priority' => $the_priority,
						'label' => sanitize_text_field( $element['label'] ),
						'type' => sanitize_text_field( $element['type'] ),
						'visible' => ( $element['visible'] ),
						'callback' => $callback_prefix . $id
					);

					// Link
					if ( isset( $element['link'] ) )
						$new_instance['output'][$the_priority]['link'] = ( $element['link'] ) ? true: false;

					// Content
					if ( $element['type'] === 'content' ) {
						$new_instance['output'][$the_priority]['value'] = ( isset( $element['value'] ) && ! empty( $element['value'] ) ) ? sanitize_text_field( $element['value'] ) : 'content'; // Default to content
						$new_instance['content_display_type'] = $new_instance['output'][$the_priority]['value'];
					}

					// Store a reference of this element in the 'output_elements' key
					$new_instance['output_elements'][$id] = $the_priority;
				}
			}

			// Advanced
			if ( ! empty( $new_instance['css_class'] ) ) {
				// Split classes
				$new_instance['css_class'] = explode( ' ', $new_instance['css_class'] );

				foreach ( $new_instance['css_class'] as &$css_class )
					$css_class = sanitize_html_class( $css_class );

				$new_instance['css_class'] = implode( ' ', $new_instance['css_class'] );
			}
			else
				$new_instance['css_class'] = false;

			return apply_filters( 'conductor_widget_update', $new_instance, $old_instance, $this );
		}

		/**
		 * This function controls the display of the widget on the website
		 */
		public function widget( $args, $instance ) {
			// Instance filter
			$instance = apply_filters( 'conductor_widget_instance', $instance, $args, $this );

			extract( $args ); // $before_widget, $after_widget, $before_title, $after_title

			// Start of widget output
			echo $before_widget;

			do_action( 'conductor_widget_before', $instance, $args, $this );

			do_action( 'conductor_widget_title_before', $instance, $args, $this );
			$this->get_widget_title( $before_title, $after_title, $instance );
			do_action( 'conductor_widget_title_after', $instance, $args, $this );

			// Create a new query only if we have an instance and this is not a newly added widget
			if ( $this->is_valid_instance( $instance ) ) {
				// Conductor Query
				$conductor_widget_query_args = array(
					'widget' => $this,
					'widget_instance' => $instance,
					'query_type' => ( isset( $instance['feature_many'] ) && ! $instance['feature_many'] ) ? 'single' : 'many',
					'display_content_args_count' => 4 // Current number of arguments on the display_content() function, used in sortable output
				);
				$conductor_widget_query_args = apply_filters( 'conductor_widget_query_args', $conductor_widget_query_args, $instance, $args, $this );

				// Default to Conductor Query
				if ( ! ( $this->conductor_widget_query = apply_filters( 'conductor_widget_query', false, $conductor_widget_query_args, $instance, $args, $this ) ) )
					$this->conductor_widget_query = new Conductor_Widget_Default_Query( $conductor_widget_query_args );

				// Return the query (should contain results)
				$conductor_widget_query_results = $this->conductor_widget_query->get_query();

				// Single Content Piece
				// TODO: Template files for widget output
				if ( $this->is_single_content_piece( $instance ) && ! empty( $conductor_widget_query_results ) ) {
					// Fetch the post (global $post data is setup upon Conductor_Widget_Query::query() which is called in instantiation)
					$post = $this->conductor_widget_query->get_current_post( true );

					// Display the post
					$this->display_content( $post, $instance );

					// Reset global $post data now that we've finished the loop
					$this->conductor_widget_query->reset_global_post();
				}
				// Many Content Pieces
				else if ( ! $this->is_single_content_piece( $instance ) && $this->conductor_widget_query->have_posts() ) {
					while ( $this->conductor_widget_query->have_posts() ) : $this->conductor_widget_query->the_post();
						$this->display_content( $this->conductor_widget_query->get_current_post(), $instance );
					endwhile;

					// Pagination
					// TODO: Allow this function to be filtered to adjust output
					if ( $this->conductor_widget_query->has_pagination() ) {
						do_action( 'conductor_widget_pagination_before', $instance, $args, $conductor_widget_query_results, $this );
						$this->conductor_widget_query->get_pagination_links();
						do_action( 'conductor_widget_pagination_after', $instance, $args, $conductor_widget_query_results, $this );
					}

					// Reset global $post
					wp_reset_postdata();
				}
				// Other Content Pieces (or no results)
				else
					do_action( 'conductor_widget_content_pieces_other', $conductor_widget_query_results, $instance, $args, $this );
			}

			do_action( 'conductor_widget_after', $instance, $args, $this );

			// End of widget output
			echo $after_widget;
		}

		/**
		 * This function enqueues the necessary styles associated with this widget on admin.
		 */
		public static function admin_enqueue_scripts( $hook ) {
			global $wpdb;

			// Only on Widgets Admin Page
			if ( $hook === 'widgets.php' ) {
				global $wp_version, $wp_scripts;

				// Grab the Conductor Widget instance
				$conductor_widget = Conduct_Widget();

				// WordPress version is less than (<) 3.9
				// TODO: Remove this backwards compatibility script
				if ( Conductor::wp_version_compare( '3.9', '<' ) )
					wp_enqueue_script( 'jquery-unserialize', Conductor::plugin_url() . '/assets/js/jquery.unserialize.min.js', array( 'jquery' ) );

				// On the Customizer, we need to make sure the widgets admin script is not a dependency
				wp_enqueue_script( 'conductor-widget-admin', Conductor::plugin_url() . '/assets/js/widgets/conductor-widget-admin.js', ( ! did_action( 'customize_controls_init' ) ) ? array( 'backbone', 'admin-widgets' ) : array( 'backbone', 'customize-widgets' ) );

				// Localize the Conductor Widget admin script information
				wp_localize_script( 'conductor-widget-admin', 'conductor', apply_filters( 'conductor_widget_admin_localize', array(
					'widgets' =>
						array(
							// Conductor Widget
							'conductor' => array(
								'wp_version' => $wp_version,
								'output' => array(
									'priority_step_size' => 10
								),
								'displays' => $conductor_widget->displays
							)
						),
					'is_customizer' => ( did_action( 'customize_controls_init' ) ) ? true : false
					) )
				);

				wp_enqueue_style( 'conductor-widget-admin', Conductor::plugin_url() . '/assets/css/widgets/conductor-widget-admin.css', array( 'dashicons' ) );
			}
		}

		/**
		 * This function stores a reference to the current sidebar ID, current sidebars widgets,
		 * and Conductor widgets within the current sidebar.
		 */
		public static function dynamic_sidebar_before( $index, $has_widgets ) {
			// Bail if we're not on the front-end
			if ( is_admin() )
				return;

			// Grab the Conductor Widget instance
			$conductor_widget = Conduct_Widget();

			// Grab the sidebars widgets
			$sidebars_widgets = wp_get_sidebars_widgets();

			// If we have widgets
			if ( $has_widgets ) {
				// Store a reference to the widget settings (all Conductor Widgets)
				$conductor_widget_settings = $conductor_widget->get_settings();

				// Grab the current sidebar ID
				$conductor_widget->current_sidebar_id = $index;

				// Grab the current sidebar widgets
				$conductor_widget->current_sidebar_widgets = $sidebars_widgets[$index];

				$conductor_widgets_index = 0;
				$current_conductor_widget = false;
				$sidebar_conductor_widgets = array();

				// Loop through sidebar widgets
				foreach ( $conductor_widget->current_sidebar_widgets as $id => $widget_id ) {
					// Conductor Widgets only
					if ( $conductor_widget->id_base === _get_widget_id_base( $widget_id ) ) {
						// Find the widget number
						$widget_number = str_replace( $conductor_widget->id_base . '-', '', $widget_id );

						// Determine if this is a valid Conductor widget
						if ( array_key_exists( $widget_number, $conductor_widget_settings ) ) {
							// Grab this widget's settings
							$instance = $conductor_widget_settings[$widget_number];

							// Grab this widget's display configuration
							$widget_display_config = ( isset( $instance['widget_size'] ) && isset( $conductor_widget->displays[$instance['widget_size']] ) ) ? $conductor_widget->displays[$instance['widget_size']] : false;

							// Determine if this is a "single" Conductor widget that supports columns
							if ( ( ! isset( $instance['feature_many'] ) || ! $instance['feature_many'] ) && ! empty( $widget_display_config ) && $conductor_widget->widget_display_customize_property_supported_for_query_type( $widget_display_config, 'columns', $instance['feature_many'] ) ) {
								// If this widget doesn't match the previous number of columns, store the last widget ID, increase the index
								if ( isset( $sidebar_conductor_widgets[$conductor_widgets_index] ) && $instance['flexbox']['columns'] !== $sidebar_conductor_widgets[$conductor_widgets_index]['columns'] ) {
									// The last current Conductor Widget is the "last" widget
									$sidebar_conductor_widgets[$conductor_widgets_index]['last'] = $current_conductor_widget;

									$conductor_widgets_index++;
								}

								// Create the array if it doesn't already exist
								if ( ! isset( $sidebar_conductor_widgets[$conductor_widgets_index] ) ) {
									$sidebar_conductor_widgets[$conductor_widgets_index] = array(
										'first' => false,
										'last' => false,
										'count' => 0,
										'current' => 0,
										'columns' => $instance['flexbox']['columns']
									);

									// Since were creating this array, we know this is the first widget
									$sidebar_conductor_widgets[$conductor_widgets_index]['first'] = $widget_id;
								}

								// Increase the count
								$sidebar_conductor_widgets[$conductor_widgets_index]['count']++;

								// Store a reference to the current Conductor Widget
								$current_conductor_widget = $widget_id;
							}
							// Otherwise, this is not a "single" flexbox Conductor Widget
							else {
								// If we have a Conductor Widget in our reference
								if ( ! empty( $current_conductor_widget ) ) {
									// The last current Conductor Widget is the "last" widget
									$sidebar_conductor_widgets[$conductor_widgets_index]['last'] = $current_conductor_widget;

									// Reset the current Conductor Widget reference
									$current_conductor_widget = false;

									// Increase Conductor Widgets index (only if we already have Conductor Widgets)
									if ( ! empty( $sidebar_conductor_widgets ) )
										$conductor_widgets_index++;
								}
							}
						}
					}
					// Otherwise, this is not a Conductor Widget
					else {
						// If we have a Conductor Widget in our reference
						if ( ! empty( $current_conductor_widget ) ) {
							// The last current Conductor Widget is the "last" widget
							$sidebar_conductor_widgets[$conductor_widgets_index]['last'] = $current_conductor_widget;

							// Reset the current Conductor Widget reference
							$current_conductor_widget = false;

							// Increase Conductor Widgets index (only if we already have Conductor Widgets)
							if ( ! empty( $sidebar_conductor_widgets ) )
								$conductor_widgets_index++;
						}
					}
				}

				// Populate the last "last" Conductor Widget
				if ( isset( $sidebar_conductor_widgets[$conductor_widgets_index] ) && ! $sidebar_conductor_widgets[$conductor_widgets_index]['last'] )
					$sidebar_conductor_widgets[$conductor_widgets_index]['last'] = $current_conductor_widget;

				// Set the current sidebar Conductor Widgets
				$conductor_widget->sidebar_conductor_widgets = $sidebar_conductor_widgets;
			}
		}

		/**
		 * This function adjusts the parameters for widgets within the Conductor content sidebar.
		 */
		public static function dynamic_sidebar_params( $params ) {
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

					// CSS Classes
					$css_classes = $core_css_classes = array();

					// Determine if this is a "single" Conductor widget
					if ( ! isset( $instance['feature_many'] ) || ! $instance['feature_many'] )
						// Add "conductor-widget-single-wrap" CSS class
						$core_css_classes[] = 'conductor-widget-single-wrap';
					// Otherwise determine if this is a "many" Conductor widget
					else
						$core_css_classes[] = 'conductor-widget-wrap';

					/*
					 * Flexbox
					 */

					// Grab this widget's display configuration
					$widget_display_config = ( isset( $instance['widget_size'] ) && isset( $conductor_widget->displays[$instance['widget_size']] ) ) ? $conductor_widget->displays[$instance['widget_size']] : false;

					// Verify that the widget size supports columns
					if ( ! empty( $widget_display_config ) && $conductor_widget->widget_display_customize_property_supported_for_query_type( $widget_display_config, 'columns', $instance['feature_many'] ) ) {
						// Number of columns for this widget
						$columns = 1;

						// Base Flexbox CSS Classes
						$css_classes = array_merge( $css_classes, array(
							'conductor-row',
							'conductor-widget-row',
							'conductor-flex',
							'conductor-widget-flex',
						) );

						// Legacy widgets (widget_size)
						if ( ! isset( $instance['flexbox']['columns'] ) || empty( $instance['flexbox']['columns'] ) )
							// Switch based on widget_size
							switch ( $instance['widget_size'] ) {
								// Medium
								case 'medium':
									$columns = 2;
									break;
								// Small
								case 'small':
									$columns = 4;
								break;
							}
						// Flexbox widgets
						else
							$columns = $instance['flexbox']['columns'];

						// Add the specific column CSS classes
						$css_classes[] = 'conductor-row-' . $columns . '-columns';
						$css_classes[] = 'conductor-widget-row-' . $columns . '-columns';
						$css_classes[] = 'conductor-flex-' . $columns . '-columns';
						$css_classes[] = 'conductor-widget-flex-' . $columns . '-columns';
						$css_classes[] = 'conductor-' . $columns . '-columns';
						$css_classes[] = 'conductor-widget-' . $columns . '-columns';
					}
					// Other widget sizes
					else
						$core_css_classes[] = 'conductor-' . sanitize_html_class( $instance['widget_size'] ) . '-wrap';

					// TODO: Move this below instance since we're adding '-wrap' suffix
					// Sanitize CSS classes (instance CSS classes are sanitized upon Conductor Widget update)
					$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

					// Add the instance CSS class
					if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
						$instance_css_classes = explode( ' ', $instance['css_class'] );

						// Loop through instance CSS classes
						foreach ( $instance_css_classes as $instance_css_class )
							// Add this class to the list of classes (replacing . with '' and with suffix of -wrap)
							$core_css_classes[] = str_replace( '.', '', $instance_css_class ) . '-wrap';
					}

					// Determine if this is a "single" Conductor widget
					if ( ! isset( $instance['feature_many'] ) || ! $instance['feature_many'] ) {
						// Setup the sidebar index
						if ( $conductor_widget->sidebar_conductor_widgets_index === -1 )
							$conductor_widget->sidebar_conductor_widgets_index = key( $conductor_widget->sidebar_conductor_widgets );

						// Setup the Conductor Widget reference
						$sidebar_conductor_widgets = &$conductor_widget->sidebar_conductor_widgets[$conductor_widget->sidebar_conductor_widgets_index];

						// Only if we have single flexbox Conductor Widgets
						if ( ! empty( $sidebar_conductor_widgets ) && isset( $sidebar_conductor_widgets['count'] ) && $sidebar_conductor_widgets['count'] ) {
							// Increase the current widget count
							$sidebar_conductor_widgets['current']++;

							// Add conductor-col CSS classes
							$core_css_classes[] = 'conductor-col';
							$core_css_classes[] = 'conductor-col-' . $sidebar_conductor_widgets['current'];

							// Allow filtering of CSS classes
							$core_css_classes = apply_filters( 'conductor_widget_before_widget_core_css_classes', $core_css_classes, $params, $instance, $conductor_widget_settings, $conductor_widget );

							// Adjust widget wrapper CSS classes (only replacing once to ensure only the outer most wrapper element gets the CSS class adjustment)
							$params[0]['before_widget'] = preg_replace( '/class="/', 'class="' . esc_attr( implode( ' ', $core_css_classes ) ) . ' ', $params[0]['before_widget'], 1 );

							// Determine if this widget is the first in a set of single Conductor Widgets
							if ( $sidebar_conductor_widgets['first'] === $params[0]['widget_id'] ) {
								// Add single flexbox wrap CSS class
								$css_classes[] = 'conductor-widget-single-flexbox-wrap';

								// Add the instance CSS class
								if ( isset( $instance['css_class'] ) && ! empty( $instance['css_class'] ) ) {
									$instance_css_classes = explode( ' ', $instance['css_class'] );

									// Loop through instance CSS classes
									foreach ( $instance_css_classes as $instance_css_class )
										// Add this class to the list of classes (replacing . with '' and with suffix of -wrap)
										$css_classes[] = str_replace( '.', '', $instance_css_class ) . '-single-flexbox-wrap';
								}

								// Allow filtering of CSS classes
								$css_classes = apply_filters( 'conductor_widget_before_widget_css_classes', $css_classes, $params, $instance, $conductor_widget_settings, $conductor_widget );

								// Add single widget flexbox wrapper element
								$params[0]['before_widget'] = '<div class="' . esc_attr( implode( ' ', $css_classes ) ) . '">' . $params[0]['before_widget'];
							}

							// Determine if this widget is the last in a set of single Conductor Widgets
							if ( $sidebar_conductor_widgets['last'] === $params[0]['widget_id'] ) {
								// Add single widget flexbox wrapper closing element
								$params[0]['after_widget'] .= '</div>';

								// Increase the index value
								$conductor_widget->sidebar_conductor_widgets_index++;
							}
						}
						// Otherwise, just adjust the widget wrapper CSS classes
						else {
							// Merge core CSS classes
							$css_classes = array_merge( $core_css_classes, $css_classes );

							// Allow filtering of CSS classes
							$css_classes = apply_filters( 'conductor_widget_before_widget_css_classes', $css_classes, $params, $instance, $conductor_widget_settings, $conductor_widget );

							// Adjust widget wrapper CSS classes (only replacing once to ensure only the outer most wrapper element gets the CSS class adjustment)
							$params[0]['before_widget'] = preg_replace( '/class="/', 'class="' . esc_attr( implode( ' ', $css_classes ) ) . ' ', $params[0]['before_widget'], 1 );
						}
					}
					// Otherwise, just adjust the widget wrapper CSS classes
					else {
						// Merge core CSS classes
						$css_classes = array_merge( $core_css_classes, $css_classes );

						// Allow filtering of CSS classes
						$css_classes = apply_filters( 'conductor_widget_before_widget_css_classes', $css_classes, $params, $instance, $conductor_widget_settings, $conductor_widget );

						// Adjust widget wrapper CSS classes (only replacing once to ensure only the outer most wrapper element gets the CSS class adjustment)
						$params[0]['before_widget'] = preg_replace( '/class="/', 'class="' . esc_attr( implode( ' ', $css_classes ) ) . ' ', $params[0]['before_widget'], 1 );
					}
				}
			}

			return $params;
		}

		/**
		 * This function resets the references to the current sidebar ID, current sidebars widgets,
		 * and Conductor widgets within the current sidebar.
		 */
		public static function dynamic_sidebar_after( $index, $has_widgets ) {
			// Bail if we're not on the front-end
			if ( is_admin() )
				return;

			// Grab the Conductor Widget instance
			$conductor_widget = Conduct_Widget();

			// If we have widgets
			if ( $has_widgets ) {
				// Reset the current sidebar ID
				$conductor_widget->current_sidebar_id = false;

				// Reset the current sidebar widgets
				$conductor_widget->current_sidebar_widgets = array();

				// Reset the current sidebar Conductor Widgets
				$conductor_widget->sidebar_conductor_widgets = array();
			}
		}

		/**
		 * This function adjusts Conductor Widget CSS classes on Flexbox layouts.
		 */
		public static function conductor_widget_wrapper_css_classes( $css_classes, $post, $instance, $widget, $conductor_query ) {
			// Grab the Conductor Widget instance
			$conductor_widget = Conduct_Widget();

			// Grab this widget's display configuration
			$widget_display_config = ( isset( $instance['widget_size'] ) && isset( $conductor_widget->displays[$instance['widget_size']] ) ) ? $conductor_widget->displays[$instance['widget_size']] : false;

			// Bail if this is not a "many" flexbox widget
			if ( ( empty( $widget_display_config ) || ! $conductor_widget->widget_display_customize_property_supported_for_query_type( $widget_display_config, 'columns', $instance['feature_many'] ) ) || ( ! isset( $instance['feature_many'] ) || ! $instance['feature_many'] ) )
				return $css_classes;

			// Break CSS classes into an array
			$css_classes = explode( ' ', $css_classes );

			// Grab the query
			$query = $conductor_query->get_query();

			// CSS Classes
			$new_css_classes = array(
				'conductor-col',
				'conductor-col-' . ( $query->current_post + 1 ) // WP_Query returns posts in a zero-index array
			);

			// Add the Conductor Flexbox CSS classes
			$css_classes = array_merge( $css_classes, $new_css_classes );

			// Sanitize CSS classes (instance CSS classes are sanitized upon Conductor Widget update)
			$css_classes = array_filter( $css_classes, 'sanitize_html_class' );

			// Implode the CSS classes
			$css_classes = implode( ' ', $css_classes );

			return $css_classes;
		}

		/**
		 * This function adjusts Conductor Widget featured image size on Flexbox layouts.
		 */
		public static function conductor_widget_featured_image_size( $conductor_thumbnail_size, $instance, $post ) {
			// Bail if the featured image size is set or it's not flexbox
			if ( isset( $instance['post_thumbnails_size'] ) && ! empty( $instance['post_thumbnails_size'] ) || $conductor_thumbnail_size !== 'flexbox' )
				return $conductor_thumbnail_size;

			// Flexbox Columns
			$columns = 1;

			// Legacy widgets (widget_size)
			if ( ! isset( $instance['flexbox']['columns'] ) || empty( $instance['flexbox']['columns'] ) )
				// Switch based on widget_size
				switch ( $instance['widget_size'] ) {
					// Medium
					case 'medium':
						$columns = 2;
						break;
					// Small
					case 'small':
						$columns = 4;
						break;
				}
			// Flexbox widgets
			else
				$columns = $instance['flexbox']['columns'];

			// Switch based on the number of columns
			switch ( $columns ) {
				// "Large"
				case 1:
					$conductor_thumbnail_size = 'large';
				break;
				// "Medium"
				case 2:
				case 3:
					$conductor_thumbnail_size = 'medium';
				break;
				// "Small"
				case 4:
				case 5:
				case 6:
					$conductor_thumbnail_size = 'thumbnail';
				break;
			}

			return $conductor_thumbnail_size;
		}

		/**
		 * This function determines whether or not the 404 template should be loaded based on query arguments
		 * on this widget instance. It determines whether or not a Conductor page is being loaded first and then
		 * determines if the current query arguments (paged) should result in a 404.
		 */
		public function template_include( $template ) {
			global $wp_query, $wp_registered_sidebars, $wp_registered_widgets;

			// Verify that a Conductor page is being requested
			if ( Conductor::is_conductor() && $template !== get_404_template() ) {
				$instance = $this->get_settings();
				$sidebar_widgets = wp_get_sidebars_widgets();
				$conductor_widgets = array(); // Conductor widget instances on this page
				$conductor_sidebars = array( 'content' => sanitize_title( Conductor::get_conductor_content_layout_sidebar_id( 'content' ) ) );

				// Primary Sidebar
				if ( conductor_content_layout_has_sidebar( 'primary' ) )
					$conductor_sidebars['primary'] = sanitize_title( Conductor::get_conductor_content_layout_sidebar_id( 'primary' ) );

				// Secondary Sidebar
				if ( conductor_content_layout_has_sidebar( 'secondary' ) )
					$conductor_sidebars['secondary'] = sanitize_title( Conductor::get_conductor_content_layout_sidebar_id( 'secondary' ) );

				// First find the sidebars/widgets for this layout
				foreach ( $conductor_sidebars as $sidebar )
					if ( isset( $wp_registered_sidebars[$sidebar] ) && ! empty( $sidebar_widgets[$sidebar] ) )
						foreach( ( array ) $sidebar_widgets[$sidebar] as $id )
							if ( isset( $wp_registered_widgets[$id] ) && $wp_registered_widgets[$id]['name'] === 'Conductor' )
								if ( ( $number = $wp_registered_widgets[$id]['params'][0]['number'] ) && isset( $instance[$number] ) )
									// Found a Conductor widget on this page
									$conductor_widgets[] = $instance[$number];

				// Next check to see if any Conductor widgets exist on this page and if we need to return a 404 error due to pagination
				// TODO: Check the has_pagination() function of the query class here
				// TODO: Add a pagination function to query that can determine if is_paged, or is_pagination()
				if ( ! empty( $conductor_widgets ) )
					foreach( $conductor_widgets as $instance )
						// Feature Many only
						if ( isset( $instance['feature_many'] ) && $instance['query_args']['posts_per_page'] && $instance['query_args']['max_num_posts'] ) {
							if ( $instance['query_args']['posts_per_page'] !== $instance['query_args']['max_num_posts'] ) {
								$max_num_pages = ceil( $instance['query_args']['max_num_posts'] / $instance['query_args']['posts_per_page'] );

								// Get the "true" paged query variable from the main query (defaulting to 1)
								$paged = ( int ) get_query_var( 'paged' );
								if ( empty( $paged ) && isset( $wp_query->query['paged'] ) )
									$paged = ( int )$wp_query->query['paged'];
								else if ( empty( $paged ) )
									$paged = 1;

								// 404 (set global query is_404 as well)
								if ( $paged > $max_num_pages ) {
									$wp_query->set_404();
									$template = get_404_template();
								}
							}
						}
			}

			return $template;
		}


		/**********************
		 * Internal Functions *
		 **********************/

		/**
		 * This function outputs the content for this widget instance.
		 */
		public function display_content( $post, $instance ) {
			// TODO: Eventually change order of arguments (if possible) in a future release (conductor widget query should come before $this; have to think about backwards/forwards compatibility)
			do_action( 'conductor_widget_display_content', $post, $instance, $this, $this->conductor_widget_query );
			do_action( 'conductor_widget_display_content_' . $this->number, $post, $instance, $this, $this->conductor_widget_query );
		}

		/**
		 * This function generates CSS classes for widget output.
		 */
		public function get_css_classes( $instance ) {
			$conductor_widget_query = $this->conductor_widget_query->get_query();
			$classes = array( 'conductor-widget', $instance['widget_size'] );

			// Single Content Piece
			if ( isset( $instance['feature_many'] ) && ! $instance['feature_many'] ) {
				$classes[] = 'conductor-widget-single';
				$classes[] = 'conductor-widget-single-' . $instance['widget_size'];
			}
			// Many Content Pieces
			else {
				$classes[] = 'conductor-widget-' . $instance['widget_size'];

				// Even or odd
				if ( property_exists( $conductor_widget_query, 'current_post' ) ) {
					// Even
					if ( ( $conductor_widget_query->current_post + 1 ) % 2 === 0 ) {
						$classes[] = 'conductor-widget-even';
						$classes[] = 'conductor-widget-' . $instance['widget_size'] . '-even';
					}
					// Odd
					else {
						$classes[] = 'conductor-widget-odd';
						$classes[] = 'conductor-widget-' . $instance['widget_size'] . '-odd';
					}
				}
			}

			// Custom CSS Classes
			if ( ! empty( $instance['css_class'] ) )
				$classes[] = str_replace( '.', '', $instance['css_class'] );

			$classes = apply_filters( 'conductor_widget_css_classes', $classes, $instance, $this );

			return implode( ' ', $classes );
		}

		/**
		 * This function generates CSS classes for widget title output.
		 */
		public function get_widget_title_css_classes( $instance ) {
			$classes = array( 'conductor-widget-title' );

			// Custom CSS Classes
			if ( ! empty( $instance['css_class'] ) )
				$classes[] = str_replace( '.', '', $instance['css_class'] );

			$classes = apply_filters( 'conductor_widget_title_css_classes', $classes, $instance, $this );

			return implode( ' ', $classes );
		}

		/**
		 * This function gets the widget title.
		 */
		public function get_widget_title( $before_title, $after_title, $instance ) {
			// TODO: Only replace 1 time in case other HTML elements exist with a class attribute

			$before_title = str_replace( 'class="', 'class="' . $this->get_widget_title_css_classes( $instance ) . ' ', $before_title );

			// Widget Title
			if ( ! empty( $instance['title'] ) && ( ! isset( $instance['hide_title'] ) || ( isset( $instance['hide_title'] ) && ! $instance['hide_title'] ) ) )
				echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base, $this ) . $after_title;
		}

		/**
		 * This function determines if this particular widget instance is configured to display a single piece of content.
		 */
		public function is_single_content_piece( $instance ) {
			return ! isset( $instance['feature_many'] ) || ! $instance['feature_many'];
		}

		/**
		 * This function determines if the current instance is valid (i.e. a query should be made based on settings).
		 */
		public function is_valid_instance( $instance ) {
			return ! empty( $instance ) && isset( $instance['feature_many'] ) && isset( $instance['post_id'] ) && ( $instance['feature_many'] || ( ! $instance['feature_many'] && $instance['post_id'] ) );
		}

		/**
		 * This function prepares an array of data for use as HTML5 data attributes.
		 */
		public function prepare_data_attributes( $data_attrs ) {
			$the_data_attrs = '';

			// Loop through data attributes
			foreach ( $data_attrs as $key => &$value ) {
				// If we have a boolean value, change it to a string
				if ( is_bool( $value ) )
					$value = ( $value ) ? 'true' : 'false';

				$the_data_attrs .= sanitize_text_field( $key ) . '="' . esc_attr( ( string ) $value ) . '" ';
			}

			return $the_data_attrs;
		}

		/**
		 * This function determines if a specific widget display supports 'customize' properties based on arguments.
		 * The $sub_property flag (Boolean) can be set if it is known that customizer property is supported but that property is an
		 * array of data instead of just a Boolean value. Sub-property checks aren't as strict and rely on the developer
		 * to check that the original config property is an array of data, etc....
		 */
		public function widget_display_supports_customize_property( $config, $property, $sub_property = false ) {
			// Sub-Property
			if ( $sub_property )
				// Just checking for a "value" (anything that will return not false)
				return ( $this->widget_display_customize_property_exists( $config, $property, $sub_property ) && $config[$property] );
			// Regular property
			else
				return ( $this->widget_display_customize_property_exists( $config, $property, $sub_property ) && ( ( is_array( $config['customize'][$property] ) && ! empty( $config['customize'][$property] ) ) || $config['customize'][$property] ) );
		}

		/**
		 * This function determines if a specific widget display 'customize' properties exist based on arguments.
		 * The $sub_property flag (Boolean) can be set if it is known that customizer property exists but that property is an
		 * array of data instead of just a Boolean value. Sub-property checks aren't as strict and rely on the developer
		 * to check that the original config property is an array of data, etc....
		 */
		public function widget_display_customize_property_exists( $config, $property, $sub_property = false ) {
			// Sub-Property
			if ( $sub_property )
				// Just checking for a "value" (anything that will return not false)
				return ( is_array( $config ) && isset( $config[$property] ) );
			// Regular property
			else
				return ( is_array( $config ) && isset( $config['customize'] ) && isset( $config['customize'][$property] ) );
		}

		/**
		 * This function returns a specific widget display 'customize' property value based on arguments.
		 * The sub-property flag can be set if it is known that customizer property is supported but that property is an
		 * array of data instead of just a Boolean value. Sub-property checks aren't as strict and rely on the developer
		 * to check that the original config property is an array of data, etc....
		 *
		 * If a value isn't set, an empty string is returned
		 *
		 * TODO: Introduce a "return flag" to allow developers/logic to return different types of data (i.e. Boolean instead of and empty string)
		 */
		public function widget_display_get_customize_property_value( $config, $property, $sub_property = false ) {
			// Sub-Property
			if ( $sub_property )
				return ( is_array( $config ) && isset( $config[$property] ) ) ? $config[$property] : '';
			// Regular property
			else
				return ( is_array( $config ) && isset( $config['customize'] ) && isset( $config['customize'][$property] ) ) ? $config['customize'][$property] : '';
		}

		/**
		 * TODO: Comment
		 */
		public function widget_display_customize_property_supported_for_query_type( $config, $property, $query_type ) {
			// Find the "correct" widget size value
			if ( ! $query_type || $query_type === 'true' )
				// Switch based on widget size
				switch ( $query_type ) {
					// Single
					case '':
						$query_type = 'single';
					break;

					// Many
					case 'true':
						$query_type = 'many';
					break;
				}

			// If the property is supported
			if ( $this->widget_display_supports_customize_property( $config, $property ) ) {
				// If a specific widget size config parameter exists
				if ( $this->widget_display_customize_property_exists( $config['customize'][$property], $query_type, true ) ) {
					$property_value = $this->widget_display_get_customize_property_value( $config['customize'][$property], $property, $query_type );

					// If we have a value
					if ( $property_value )
						return true;
					// Otherwise, the value either is false or it's empty
					else
						return false;
				}

				return true;
			}

			// No support for the customize property, this element shouldn't be visible
			return false;
		}
	}
}

/**
 * Create an instance of the Conductor_Widget class.
 */
function Conduct_Widget() {
	return Conductor_Widget::instance();
}

//Conduct_Widget(); // Conduct your content!

register_widget( 'Conductor_Widget' );