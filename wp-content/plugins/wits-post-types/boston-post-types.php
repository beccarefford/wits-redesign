<?php

/*
Plugin Name: Boston Custom Post Types
Description: Sessions, speakers, and sponsors for Boston.
*/

function register_boston_session_post_type()
{
    $labels = array(
        "name" => "Boston Sessions",
        "singular_name" => "Boston Session",
        "menu_name" => "Boston Sessions",
        "all_items" => "All Boston Sessions",
        "add_new" => "Add New",
        "add_new_item" => "Add New Boston Session",
        "edit" => "Edit",
        "edit_item" => "Edit Boston Session",
        "new_item" => "New Boston Session",
        "view" => "View",
        "view_item" => "View Boston Session",
        "search_items" => "Search Boston Session",
        "not_found" => "No Sessions Found",
        "not_found_in_trash" => "No Sessions found in Trash",
    );

    $args = Array(
        "labels" => $labels,
        "description" => "A WITS Session",
        "public" => true,
        "show_ui" => true,
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array("slug" => "Boston_session", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author", "tags"),
    );
    register_post_type("boston_session", $args);
}
add_action('init', 'register_boston_session_post_type');





/* REGISTER SPEAKERS POST TYPE */
function register_boston_speaker_post_type()
{
    $labels = array(
        "name" => "Boston Speakers",
        "singular_name" => "Boston Speaker",
        "menu_name" => "Boston Speakers",
        "all_items" => "All Boston Speakers",
        "add_new" => "Add New",
        "add_new_item" => "Add New Boston Speaker",
        "edit" => "Edit",
        "edit_item" => "Edit Boston Speaker",
        "new_item" => "New Boston Speaker",
        "view" => "View",
        "view_item" => "View Boston Speaker",
        "search_items" => "Search Boston Speakers",
        "not_found" => "No Speakers Found",
        "not_found_in_trash" => "No Speakers found in Trash",
    );

    $args = Array(
        "labels" => $labels,
        "description" => "A WITS Speaker",
        "public" => true,
        "show_ui" => true,
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array("slug" => "Boston_speaker", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type("boston_speaker", $args);
}
add_action('init', 'register_boston_speaker_post_type');

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_boston-speaker-field-group',
		'title' => 'Boston Speaker Field Group',
		'fields' => array (
			array (
				'key' => 'field_5773a670c6c68',
				'label' => 'Keynote',
				'name' => 'boston_keynote',
				'type' => 'true_false',
				'message' => '',
				'default_value' => 0,
			),
			array (
				'key' => 'field_5773a67ec6c69',
				'label' => 'Job Title',
				'name' => 'boston_job_title',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5773a689c6c6a',
				'label' => 'Twitter',
				'name' => 'boston_twitter',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5773a692c6c6b',
				'label' => 'Website',
				'name' => 'boston_website',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'boston_speaker',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_boston-session-relationship',
		'title' => 'Boston Session Relationship',
		'fields' => array (
			array (
				'key' => 'field_5773b089dcb13',
				'label' => 'Boston Session Relationship',
				'name' => 'boston_session_relationship',
				'type' => 'relationship',
				'return_format' => 'object',
				'post_type' => array (
					0 => 'Boston_session',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'filters' => array (
					0 => 'search',
				),
				'result_elements' => array (
					0 => 'post_type',
					1 => 'post_title',
				),
				'max' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'boston_speaker',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_577fe863893a3',
	'title' => 'Date and Time Picker',
	'fields' => array (
		array (
			'key' => 'field_577fe86dbfe6e',
			'label' => 'Boston Date',
			'name' => 'boston_date',
			'type' => 'date_time_picker',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'show_date' => 'true',
			'date_format' => 'yymmdd',
			'time_format' => 'h:mm tt',
			'show_week_number' => 'false',
			'picker' => 'slider',
			'save_as_timestamp' => 'true',
			'get_as_timestamp' => 'true',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'boston_session',
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
	'key' => 'group_577fead43283c',
	'title' => 'Boston Speaker Photo',
	'fields' => array (
		array (
			'key' => 'field_577feb62f9ddb',
			'label' => 'Boston Speaker Photo',
			'name' => 'boston_speaker_photo',
			'type' => 'relationship',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array (
				0 => 'boston_speaker',
			),
			'taxonomy' => array (
			),
			'filters' => '',
			'elements' => '',
			'min' => '',
			'max' => '',
			'return_format' => 'object',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'boston_session',
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

function register_boston_sponsor_post_type()
{
    $labels = array(
        "name" => "Boston Sponsors",
        "singular_name" => "Boston Sponsor",
        "menu_name" => "Boston Sponsors",
        "all_items" => "All Boston Sponsors",
        "add_new" => "Add New",
        "add_new_item" => "Add New Boston Sponsor",
        "edit" => "Edit",
        "edit_item" => "Edit Boston Sponsor",
        "new_item" => "New Boston Sponsor",
        "view" => "View",
        "view_item" => "View Boston Sponsor",
        "search_items" => "Search Boston Sponsors",
        "not_found" => "No Sponsors Found",
        "not_found_in_trash" => "No Sponsors found in Trash",
    );

    $args = Array(
        "labels" => $labels,
        "description" => "A WITS Sponsor",
        "public" => true,
        "show_ui" => true,
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array("slug" => "boston_sponsor", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type("boston_sponsor", $args);
}
add_action('init', 'register_boston_sponsor_post_type'); ?>
