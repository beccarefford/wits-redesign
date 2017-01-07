<?php

/*
Plugin Name: Philly Custom Post Types
Description: Sessions, speakers, and sponsors for Philadelphia.
*/

function register_philly_session_post_type()
{
    $labels = array(
        "name" => "Philly Sessions",
        "singular_name" => "Philly Session",
        "menu_name" => "Philly Sessions",
        "all_items" => "All Philly Sessions",
        "add_new" => "Add New",
        "add_new_item" => "Add New Philly Session",
        "edit" => "Edit",
        "edit_item" => "Edit Philly Session",
        "new_item" => "New Philly Session",
        "view" => "View",
        "view_item" => "View Philly Session",
        "search_items" => "Search Philly Session",
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
        "rewrite" => array("slug" => "philly_session", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author", "tags"),
    );
    register_post_type("philly_session", $args);
}
add_action('init', 'register_philly_session_post_type');





/* REGISTER SPEAKERS POST TYPE */
function register_philly_speaker_post_type()
{
    $labels = array(
        "name" => "Philly Speakers",
        "singular_name" => "Philly Speaker",
        "menu_name" => "Philly Speakers",
        "all_items" => "All Philly Speakers",
        "add_new" => "Add New",
        "add_new_item" => "Add New Philly Speaker",
        "edit" => "Edit",
        "edit_item" => "Edit Philly Speaker",
        "new_item" => "New Philly Speaker",
        "view" => "View",
        "view_item" => "View Philly Speaker",
        "search_items" => "Search Philly Speakers",
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
        "rewrite" => array("slug" => "philly_speaker", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type("philly_speaker", $args);
}
add_action('init', 'register_philly_speaker_post_type');

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_philly-speaker-fields',
		'title' => 'Philly Speaker Fields',
		'fields' => array (
			array (
				'key' => 'field_5769a17681553',
				'label' => 'Keynote',
				'name' => 'philly_keynote',
				'type' => 'true_false',
				'message' => '',
				'default_value' => 0,
			),
			array (
				'key' => 'field_5769a1df81554',
				'label' => 'Job Title',
				'name' => 'philly_job_title',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5769a22d81555',
				'label' => 'Twitter',
				'name' => 'philly_twitter',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5769a24581556',
				'label' => 'Website',
				'name' => 'philly_website',
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
					'value' => 'philly_speaker',
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

/* REGISTER RELATIONSHIP BETWEEN SPEAKER AND SESSION TITLE */

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_philly-relationship-field',
		'title' => 'Philly Relationship Field',
		'fields' => array (
			array (
				'key' => 'field_5773ad572e919',
				'label' => 'Sessions',
				'name' => 'philly_session_relationship',
				'type' => 'relationship',
				'return_format' => 'object',
				'post_type' => array (
					0 => 'philly_session',
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
			array (
				'key' => 'field_5773adde2e91a',
				'label' => '',
				'name' => '',
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
					'value' => 'philly_speaker',
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

/* REGISTER RELATIONSHIP BETWEEN SESSION AND SPEAKER PHOTO */

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_philly-speaker-photo',
		'title' => 'Philly Speaker Photo',
		'fields' => array (
			array (
				'key' => 'field_5773b268321f4',
				'label' => 'Philly Speaker Photo',
				'name' => 'philly_speaker_photo',
				'type' => 'relationship',
				'return_format' => 'object',
				'post_type' => array (
					0 => 'philly_speaker',
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
					'value' => 'philly_session',
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

function register_philly_sponsor_post_type()
{
    $labels = array(
        "name" => "Philly Sponsors",
        "singular_name" => "Philly Sponsor",
        "menu_name" => "Philly Sponsors",
        "all_items" => "All Philly Sponsors",
        "add_new" => "Add New",
        "add_new_item" => "Add New Philly Sponsor",
        "edit" => "Edit",
        "edit_item" => "Edit Philly Sponsor",
        "new_item" => "New Philly Sponsor",
        "view" => "View",
        "view_item" => "View Philly Sponsor",
        "search_items" => "Search Philly Sponsors",
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
        "rewrite" => array("slug" => "philly_sponsor", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type("philly_sponsor", $args);
}
add_action('init', 'register_philly_sponsor_post_type');
