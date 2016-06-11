<?php
/**
 * These functions will add registered custom taxonomies/terms (ignoring _builtin taxonomies)
 * to the Conductor Widget (when querying "many" pieces of content) in a multi-select drop-down.
 * Please customize the following code to fit your needs. Conductor will support this
 * functionality in a future version.
 *
 * TODO: Need to indent child terms in <select>
 */


/**
 * This function adds 'terms' to the list of Conductor Widget defaults.
 */
add_filter( 'conductor_widget_defaults', 'my_conductor_widget_defaults' );

function my_conductor_widget_defaults( $defaults ) {
	// Add 'terms' to defaults if it doesn't already exist
	if ( ! isset( $defaults['terms'] ) )
		$defaults['terms'] = array();

	// Add 'terms_relation' to defaults if it doesn't already exist
	if ( ! isset( $defaults['terms'] ) )
		$defaults['terms_relation'] = 'AND';

	return $defaults;
}

/**
 * This function adds all terms within registered taxonomies to Conductor Widgets.
 */
add_action( 'conductor_widget_settings_content_section_after', 'my_conductor_widget_settings_content_section_after', 10, 2 );

function my_conductor_widget_settings_content_section_after( $instance, $widget ) {
	global $wpdb;

	// Get all registered (non built-in) taxonomies
	if ( ! $taxonomies = wp_cache_get( 'taxonomies', 'conductor-widget' ) ) {
		$taxonomies = get_taxonomies( array( '_builtin' => false ), 'objects' );
		wp_cache_add( 'taxonomies', $taxonomies, 'conductor-widget' ); // Store cache
	}

	// Loop through taxonomies
	if ( ! empty( $taxonomies ) ) :
	?>
		<p class="conductor-taxonomies-terms conductor-feature-many <?php echo ( ! $instance['feature_many'] ) ? 'conductor-hidden' : false; ?>">
			<?php // Terms (terms within registered taxonomies) ?>
			<label for="<?php echo $widget->get_field_id( 'terms' ); ?>">
				<strong><?php _e( 'Terms' ); ?></strong>
			</label>
			<br />
			<select class="conductor-select-taxonomy-term conductor-select" id="<?php echo $widget->get_field_id( 'terms' ); ?>" name="<?php echo $widget->get_field_name( 'terms][' ); ?>" multiple="true" style="min-height: 150px;">
				<?php
					foreach ( $taxonomies as $taxonomy_id => $taxonomy ) :
						// Term Data
						if ( ! $terms = wp_cache_get( $taxonomy_id . '_data', 'conductor-widget' ) ) {
							// Custom SQL query used to fetch term data
							$terms = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT t.term_id, t.name, t.slug FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s') ORDER BY t.name ASC", $taxonomy_id
								)
							);
							wp_cache_add( $taxonomy_id . '_data', $terms, 'conductor-widget' ); // Store cache
						}

						// If we have terms, output them
						if ( ! empty( $terms ) ) :
						?>
							<optgroup label="<?php echo esc_attr( $taxonomy->labels->name ); ?>">
								<?php
									foreach ( $terms as $term ) :
										$value = $taxonomy_id . ':' . $term->term_id;
								?>
									<option value="<?php echo esc_attr( $value ); ?>" <?php selected( ( isset( $instance['terms'] ) && in_array( $value, $instance['terms'] ) ) ); ?>>
										<?php echo ( $term->name === '' ) ? sprintf( __( '#%d (no title)' ), $term->term_id ) : $term->name; ?>
									</option>
								<?php
									endforeach;
								?>
							</optgroup>
						<?php
						endif;
					endforeach;
				?>
			</select>
			<small class="description conductor-description"><?php _e( 'Hold down the control or shift keys to select multiple terms.' ); ?></small>
		</p>
 
		<p class="conductor-taxonomies-terms-relation conductor-feature-many <?php echo ( ! $instance['feature_many'] ) ? 'conductor-hidden' : false; ?>">
			<?php // Terms Relation(relationship between selected terms; AND or OR) ?>
			<label for="<?php echo $widget->get_field_id( 'terms_relation' ); ?>">
				<strong><?php _e( 'Term Relationship' ); ?></strong>
			</label>
			<br />
			<select class="conductor-select-taxonomy-term-relation conductor-select" id="<?php echo $widget->get_field_id( 'terms_relation' ); ?>" name="<?php echo $widget->get_field_name( 'terms_relation' ); ?>">
				<option value="AND" <?php selected( ( isset( $instance['terms_relation'] ) && $instance['terms_relation'] === 'AND' ) ); ?>><?php _e( 'Content pieces exist in ALL term(s)' ); ?></option>
				<option value="OR" <?php selected( ( isset( $instance['terms_relation'] ) && $instance['terms_relation'] === 'OR' ) ); ?>><?php _e( 'Content pieces exist in ANY term(s)' ); ?></option>
			</select>
		</p>
	<?php
	endif;
}

/**
 * This function validates the terms selected by the user upon saving of a Conductor Widget.
 */
add_filter( 'conductor_widget_update', 'my_conductor_widget_update', 10, 3 );

function my_conductor_widget_update( $new_instance, $old_instance, $widget ) {
	// Validate the terms
	if ( isset( $new_instance['terms'] ) && ! empty( $new_instance['terms'] ) ) {
		// Setup the tax_query data
		$new_instance['tax_query'] = array();

		// Loop through terms
		foreach ( $new_instance['terms'] as $key => &$tt ) {
			// Separate taxonomy and term ID
			$tt = explode( ':', $tt );

			// Parse the exploded values
			$tt = array(
				'taxonomy' => $tt[0], // $tt[0] contains taxonomy ID
				'term' => $tt[1] // $tt[1] contains term ID
			);

			// Set taxonomy
			$tt['taxonomy'] = sanitize_text_field( $tt['taxonomy'] ); 

			// Set term to absolute integer value
			$tt['term'] = absint( $tt['term'] );

			// Check to see if term exists, if not, remove it from data
			if ( ! term_exists( $tt['term'], $tt['taxonomy'] ) )
				unset( $new_instance['terms'][$key] );
			// Otherwise add it to the tax_query query_args data
			else {
				$new_instance['tax_query'][] = array(
					'taxonomy' => $tt['taxonomy'],
					'terms' => $tt['term']
				);

				// Implode the term
				$tt = implode( ':', $tt );
			}
		}
	}
	else
		$new_instance['terms'] = array();

	// Validate the terms_relation
	if ( isset( $new_instance['terms_relation'] ) && ! empty( $new_instance['terms_relation'] ) ) {
		if ( ! in_array( $new_instance['terms_relation'], array( 'AND', 'OR' ) ) )
			$new_instance['terms_relation'] = 'AND';

		// If we have multiple terms selected, setup the relation parameter for the tax_query
		if ( $new_instance['terms_relation'] === 'OR' && isset( $new_instance['terms'] ) && count( $new_instance['terms'] ) > 1 )
			$new_instance['tax_query']['relation'] = $new_instance['terms_relation'];
	}

	return $new_instance;
}

/**
 * This function checks to see if a term was selected by the user and modifies the query on
 * Conductor Widgets accordingly.
 */
add_filter( 'conductor_query_args', 'my_conductor_query_args', 10, 4 );

function my_conductor_query_args( $query_args, $type, $widget_instance, $query ) {
	// Add term to query if specified
	if ( isset( $widget_instance['tax_query'] ) && ! empty( $widget_instance['tax_query'] ) ) {
		$tax_query = $widget_instance['tax_query'];

		// If a taxonomy query is already set, merge the two, otherwise set the taxonomy query
		$query_args['tax_query'] = ( isset( $query_args['tax_query'] ) ) ? array_merge( $query_args['tax_query'], $tax_query ) : $tax_query;
	}

	return $query_args;
}
?>
<?php
//start woocommerce modifications for seat reservations
add_filter( 'woocommerce_variable_free_price_html',  'hide_free_price_notice' );
 
add_filter( 'woocommerce_free_price_html',           'hide_free_price_notice' );
 
add_filter( 'woocommerce_variation_free_price_html', 'hide_free_price_notice' );
 
/*changes text of free price notice*/
function hide_free_price_notice( $price, $product ) { 
return 'Workshop is included in Attendee Ticket';
 }
 /*Hides the 'Free!' price notice
function hide_free_price_notice( $price ) {  return '';}
*/
?>
<?php
 
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
 
function custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
    unset($fields['order']['order_comments']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_company']);
    //unset($fields['billing']['billing_email']);
    unset($fields['billing']['billing_city']);
    return $fields;
}
?>
<?php
// Change the description tab title to product name
add_filter( 'woocommerce_product_tabs', 'wc_change_product_description_tab_title', 10, 1 );
function wc_change_product_description_tab_title( $tabs ) {
  global $post;
	if ( isset( $tabs['description']['title'] ) )
		$tabs['description']['title'] = $post->post_title;
	return $tabs;
}
// Change the description tab heading to product name
add_filter( 'woocommerce_product_description_heading', 'wc_change_product_description_tab_heading', 10, 1 );
function wc_change_product_description_tab_heading( $title ) {
	global $post;
	return $post->post_title;
}
?>
<?php
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
?>



