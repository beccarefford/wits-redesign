<?php global $wpdb; ?>
<div class="conductor-content-layouts-controls">
	<div id="setting-error-conductor-content-layouts" class="settings-error"></div>

	<select id="conductor_content_types" name="conductor_content_types" class="conductor-content-types conductor-content-types-select conductor-select">
		<option value=""><?php _e( 'Select a Content Type', 'conductor' ); ?></option>

		<?php do_action( 'conductor_content_types_before' ); ?>

		<optgroup label="<?php esc_attr_e( 'Built-In', 'conductor' ); ?>">
			<option value="front_page" data-content-type="built-in" data-permalink="<?php echo esc_attr( trailingslashit( home_url() ) ); ?>"><?php _e( 'Front Page', 'conductor' ); ?></option>
			<option value="home" data-content-type="built-in" data-permalink="<?php echo esc_attr( ( $page_for_posts = get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : trailingslashit( home_url() ) ); ?>"><?php _e( 'Blog', 'conductor' ); ?></option>
			<?php do_action( 'conductor_content_types_built_in' ); ?>
		</optgroup>

		<optgroup label="<?php esc_attr_e( 'Category Archive', 'conductor' ); ?>">
			<?php
				$category_args = array(
					'orderby' => 'id', // TODO: Change this to name
					'order' => 'ASC',
					'show_count' => false,
					'hide_empty'  => false,
					'child_of' => false,
					'selected' => false,
					'hierarchical' => true,
					'depth' => 0,
					'hide_if_empty' => false,
					'walker' => new Walker_ConductorCategoryDropdown
				);

				// Fetch the categories (custom Walker adds the data-content-type attribute)
				echo walk_category_dropdown_tree( get_categories( $category_args ), $category_args['depth'], $category_args );
			?>
		</optgroup>

		<?php
			// Public Custom Post Types (further filtered to remove those without archives or rewrite rules)
			$public_post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
			$public_post_types = wp_list_filter( $public_post_types, array( 'has_archive', false, 'rewrite' => false ), 'NOT' );

			if ( ! empty( $public_post_types ) ) :
		?>
			<optgroup label="<?php esc_attr_e( 'Post Type Archive', 'conductor' ); ?>">
				<?php foreach ( $public_post_types as $public_post_type ) : ?>
					<option value="<?php echo esc_attr( $public_post_type->name ); ?>" data-content-type="post-type" data-permalink="<?php echo esc_attr( get_post_type_archive_link( $public_post_type->name ) ); ?>"><?php echo $public_post_type->labels->name; ?></option>
				<?php endforeach; ?>
			</optgroup>
		<?php
			endif;
		?>

		<optgroup label="<?php esc_attr_e( 'Single Post', 'conductor' ); ?>">
			<?php
				$posts = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT SQL_CALC_FOUND_ROWS $wpdb->posts.ID, $wpdb->posts.post_title FROM $wpdb->posts WHERE 1=1 AND $wpdb->posts.post_type = 'post' AND $wpdb->posts.post_status = 'publish' ORDER BY $wpdb->posts.post_title ASC LIMIT 0, %d", wp_count_posts( 'post' )->publish
					)
				);

				if ( ! empty( $posts ) )
					foreach( $posts as $post ) :
				?>
					<option value="<?php echo $post->ID; ?>" data-content-type="post" data-permalink="<?php echo esc_attr( trailingslashit( home_url() ) . '?p=' . $post->ID ); ?>"><?php echo ( $post->post_title === '' ) ? sprintf( __( '#%d (no title)' ), $post->ID ) : $post->post_title; ?></option>
				<?php
					endforeach;
			?>
		</optgroup>

		<optgroup label="<?php esc_attr_e( 'Single Page', 'conductor' ); ?>">
			<?php
			$pages = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT SQL_CALC_FOUND_ROWS $wpdb->posts.ID, $wpdb->posts.post_title FROM $wpdb->posts WHERE 1=1 AND $wpdb->posts.post_type = 'page' AND $wpdb->posts.post_status = 'publish' ORDER BY $wpdb->posts.post_title ASC LIMIT 0, %d", wp_count_posts( 'page' )->publish
				)
			);

			if ( ! empty( $pages ) )
				foreach( $pages as $page ) :
				?>
					<option value="<?php echo $page->ID; ?>" data-content-type="page" data-permalink="<?php echo esc_attr( trailingslashit( home_url() ) . '?page_id=' . $page->ID ); ?>"><?php echo ( $page->post_title === '' ) ? sprintf( __( '#%d (no title)' ), $page->ID ) : $page->post_title; ?></option>
				<?php
				endforeach;
			?>
		</optgroup>
		<?php do_action( 'conductor_content_types_after' ); ?>
	</select>

	<br />

	<input type="button" id="conductor_content_layouts_add" class="button-primary conductor-content-layouts-add" name="conductor[content_layouts][add]"  value="<?php esc_attr_e( 'Conduct this Content Type', 'conductor' ); ?>" />
	<img src="<?php echo admin_url( '/images/spinner.gif' ); ?>" class="conductor-spinner" alt="<?php esc_attr_e( 'Loading...', 'conductor' ); ?>" title="<?php esc_attr_e( 'Loading...', 'conductor' ); ?>" style="<?php echo ( ! is_customize_preview() ) ? 'display: none;' : false; ?>" />
</div>