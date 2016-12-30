<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function prefix_enqueue_awesome() {
	wp_enqueue_style( 'prefix-font-awesome', '/wp-content/themes/font-awesome-4.6.3/css/font-awesome.min.css', array(), '4.6.3' );
}
add_action( 'wp_enqueue_scripts', 'prefix_enqueue_awesome' );


function wpbootstrap_scripts_with_jquery()
{
    // Register the script like this for a theme
    // wp_enqueue_script( 'bootstrap-jquery', get_stylesheet_directory_uri() . '/jquery.js' );
    // TODO: We are using stylesheet directory because that seems to work on active
    // child theme. Research proper way to do this.

    wp_register_script( 'bootstrap-script', get_stylesheet_directory_uri() .
                        '/bootstrap.js', array(), true );
    wp_register_script( 'carousel-script', get_stylesheet_directory_uri() .
        '/carousel-scripts.js', array(), true );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bootstrap-script' );
    wp_enqueue_script( 'carousel-script' );
}
add_action( 'wp_enqueue_scripts', 'wpbootstrap_scripts_with_jquery' );

function phillyete_responsive (){
  wp_dequeue_style( 'interface-responsive' );
  wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', 'style');
}
add_action('wp_enqueue_scripts','phillyete_responsive', 999);

function wpse_custom_excerpts($content, $limit, $permalink) {
    return wp_trim_words($content, $limit, '<a href="'. esc_url( $permalink ) .
          '">' . '&hellip;' . __( '<br />Read more &nbsp;&raquo;', 'wpse' ) . '</a>');
}

function pete_force_json_update($post_id) {
     delete_transient('appjson');
     delete_transient('appjsontime');
//     wp_cache_delete('appjsontime', $group = '');
}
add_action('save_post', 'pete_force_json_update');

?>


<?php if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_57c399a4c4430',
	'title' => 'Philly Home',
	'fields' => array (
		array (
			'key' => 'field_57c399ae8b259',
			'label' => 'Philly Schedule Button URL',
			'name' => 'philly_schedule_button_url',
			'type' => 'url',
			'instructions' => 'The URL of the PDF schedule.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '40',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_57c3965631ec1',
	'title' => 'Philly Sponsors',
	'fields' => array (
		array (
			'key' => 'field_57c39662d096e',
			'label' => 'Philly Sponsor Level',
			'name' => 'philly_sponsor_level',
			'type' => 'select',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'Headline' => 'Headline',
				'Petabyte' => 'Petabyte',
				'Terabyte' => 'Terabyte',
				'Gigabyte' => 'Gigabyte',
				'Lanyard' => 'Lanyard',
				'Megabyte' => 'Megabyte',
				'Snack' => 'Snack',
				'Media' => 'Media',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_57c39695d096f',
			'label' => 'Philly Sponsor URL',
			'name' => 'philly_sponsor_url',
			'type' => 'url',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_57c396b3d0970',
			'label' => 'Philly Sponsor Twitter',
			'name' => 'philly_sponsor_twitter',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'philly_sponsor',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_57c399588cd66',
	'title' => 'Raleigh Home',
	'fields' => array (
		array (
			'key' => 'field_57c3997146db9',
			'label' => 'Raleigh Schedule Button URL',
			'name' => 'raleigh_schedule_button_url',
			'type' => 'url',
			'instructions' => 'The link to the PDF of the schedule page.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '260',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_57c396da12372',
	'title' => 'Raleigh Sponsors',
	'fields' => array (
		array (
			'key' => 'field_57c396e26c915',
			'label' => 'Raleigh Sponsor Level',
			'name' => 'raleigh_sponsor_level',
			'type' => 'select',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'Headline' => 'Headline',
				'Petabyte' => 'Petabyte',
				'Terabyte' => 'Terabyte',
				'Gigabyte' => 'Gigabyte',
				'Lanyard' => 'Lanyard',
				'Megabyte' => 'Megabyte',
				'Snack' => 'Snack',
				'Media' => 'Media',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_57c397086c916',
			'label' => 'Raleigh Sponsor URL',
			'name' => 'raleigh_sponsor_url',
			'type' => 'url',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_57c3971a6c917',
			'label' => 'Raleigh Sponsor Twitter',
			'name' => 'raleigh_sponsor_twitter',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'raleigh_sponsor',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_57c39bf46b089',
	'title' => 'Sessions',
	'fields' => array (
		array (
			'key' => 'field_55b8d424f2b09',
			'label' => 'Room',
			'name' => 'room',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'to be determined',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_55b8fbdfda4f9',
			'label' => 'Presenters',
			'name' => 'presenters',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'toolbar' => 'basic',
			'media_upload' => 0,
			'tabs' => 'all',
		),
		array (
			'key' => 'field_55b8fc03da4fa',
			'label' => 'Laptop',
			'name' => 'laptop',
			'type' => 'radio',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'No need to bring your own device' => 'No need to bring your own device',
				'Recommended to bring your own device' => 'Recommended to bring your own device',
				'Required to bring your own device' => 'Required to bring your own device',
			),
			'other_choice' => 0,
			'save_other_choice' => 0,
			'default_value' => '',
			'layout' => 'vertical',
		),
		array (
			'key' => 'field_55b8fc56da4fb',
			'label' => 'time',
			'name' => 'time',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_55b8fc86f080c',
			'label' => 'track',
			'name' => 'track',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'session',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
	),
	'active' => 1,
	'description' => '',
	'old_ID' => 134,
));

acf_add_local_field_group(array (
	'key' => 'group_57c39bf4881e8',
	'title' => 'Speakers',
	'fields' => array (
		array (
			'key' => 'field_55b83a0d02d64',
			'label' => 'Company',
			'name' => 'company',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_55b83a1cfc666',
			'label' => 'Twitter',
			'name' => 'twitter',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'toolbar' => 'basic',
			'media_upload' => 0,
			'tabs' => 'all',
		),
		array (
			'key' => 'field_55b83a46fc667',
			'label' => 'Linkedin',
			'name' => 'linkedin',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'toolbar' => 'basic',
			'media_upload' => 0,
			'tabs' => 'all',
		),
		array (
			'key' => 'field_55b83a63433ca',
			'label' => 'Session',
			'name' => 'session',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'to be determined',
			'toolbar' => 'basic',
			'media_upload' => 0,
			'tabs' => 'all',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'speaker',
			),
		),
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'team',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array (
	),
	'active' => 1,
	'description' => '',
	'old_ID' => 146,
));

acf_add_local_field_group(array (
	'key' => 'group_57c399ec96bc9',
	'title' => 'Washington Home',
	'fields' => array (
		array (
			'key' => 'field_57c39a0744263',
			'label' => 'Washington Schedule Button URL',
			'name' => 'washington_schedule_button_url',
			'type' => 'url',
			'instructions' => 'The URL of the schedule PDF goes here.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page',
				'operator' => '==',
				'value' => '41',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_57c3974603ae6',
	'title' => 'Washington Sponsors',
	'fields' => array (
		array (
			'key' => 'field_57c397531ffe3',
			'label' => 'Washington Sponsor Level',
			'name' => 'washington_sponsor_level',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'Headline' => 'Headline',
				'Petabyte' => 'Petabyte',
				'Terabyte' => 'Terabyte',
				'Gigabyte' => 'Gigabyte',
				'Lanyard' => 'Lanyard',
				'Megabyte' => 'Megabyte',
				'Snack' => 'Snack',
				'Media' => 'Media',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
		array (
			'key' => 'field_57c3976f1ffe4',
			'label' => 'Washington Sponsor URL',
			'name' => 'washington_sponsor_url',
			'type' => 'url',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_57c3977e1ffe5',
			'label' => 'Washington Sponsor Twitter',
			'name' => 'washington_sponsor_twitter',
			'type' => 'url',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'washington_sponsor',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif; 

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_5865d3209ef7d',
	'title' => 'Updated Sponsors',
	'fields' => array (
		array (
			'key' => 'field_5865d3ab4e061',
			'label' => 'New Sponsor Types',
			'name' => 'new_sponsor_types',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array (
				'Headline' => 'Headline',
				'Petabyte' => 'Petabyte',
				'Terabyte' => 'Terabyte',
				'Gigabyte' => 'Gigabyte',
				'Lanyard' => 'Lanyard',
				'Megabyte' => 'Megabyte',
				'Snack' => 'Snack',
				'PremierCitySponsor' => 'PremierCitySponsor',
				'CitySponsor' => 'CitySponsor',
				'Friends' => 'Friends',
				'MediaAndPartners' => 'MediaAndPartners',
			),
			'default_value' => array (
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'disabled' => 0,
			'readonly' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'boston_sponsor',
			),
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'philly_sponsor',
			),
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'raleigh_sponsor',
			),
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'washington_sponsor',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif; ?>
