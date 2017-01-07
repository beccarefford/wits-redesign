<?php

/*
Plugin Name: Raleigh Custom Post Types
Description: Sessions, speakers, and sponsors for Raleigh.
*/

function register_raleigh_session_post_type()
{
    $labels = array(
        "name" => "Raleigh Sessions",
        "singular_name" => "Raleigh Session",
        "menu_name" => "Raleigh Sessions",
        "all_items" => "All Raleigh Sessions",
        "add_new" => "Add New",
        "add_new_item" => "Add New Raleigh Session",
        "edit" => "Edit",
        "edit_item" => "Edit Raleigh Session",
        "new_item" => "New Raleigh Session",
        "view" => "View",
        "view_item" => "View Raleigh Session",
        "search_items" => "Search Raleigh Session",
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
        "rewrite" => array("slug" => "raleigh_session", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author", "tags"),
    );
    register_post_type("raleigh_session", $args);
}
add_action('init', 'register_raleigh_session_post_type');





/* REGISTER SPEAKERS POST TYPE */
function register_raleigh_speaker_post_type()
{
    $labels = array(
        "name" => "Raleigh Speakers",
        "singular_name" => "Raleigh Speaker",
        "menu_name" => "Raleigh Speakers",
        "all_items" => "All Raleigh Speakers",
        "add_new" => "Add New",
        "add_new_item" => "Add New Raleigh Speaker",
        "edit" => "Edit",
        "edit_item" => "Edit Raleigh Speaker",
        "new_item" => "New Raleigh Speaker",
        "view" => "View",
        "view_item" => "View Raleigh Speaker",
        "search_items" => "Search Raleigh Speakers",
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
        "rewrite" => array("slug" => "raleigh_speaker", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type("raleigh_speaker", $args);
}
add_action('init', 'register_raleigh_speaker_post_type');

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_raleigh-field-group',
		'title' => 'Raleigh Field Group',
		'fields' => array (
			array (
				'key' => 'field_5769a6fa24adf',
				'label' => 'Keynote',
				'name' => 'raleigh_keynote',
				'type' => 'true_false',
				'message' => '',
				'default_value' => 0,
			),
			array (
				'key' => 'field_5769a6e224ade',
				'label' => 'Job Title',
				'name' => 'raleigh_job_title',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5769a71624ae0',
				'label' => 'Twitter',
				'name' => 'raleigh_twitter',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5769a71e24ae1',
				'label' => 'Website',
				'name' => 'raleigh_website',
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
					'value' => 'raleigh_speaker',
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
		'id' => 'acf_raleigh-session-relationship',
		'title' => 'Raleigh Session Relationship',
		'fields' => array (
			array (
				'key' => 'field_5773af4ffcf6e',
				'label' => 'Raleigh Session Relationship',
				'name' => 'raleigh_session_relationship',
				'type' => 'relationship',
				'return_format' => 'object',
				'post_type' => array (
					0 => 'raleigh_session',
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
					'value' => 'raleigh_speaker',
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
		'id' => 'acf_raleigh-speaker-photo',
		'title' => 'Raleigh Speaker Photo',
		'fields' => array (
			array (
				'key' => 'field_5773b3aa8986e',
				'label' => 'Raleigh Speaker Photo',
				'name' => 'raleigh_speaker_photo',
				'type' => 'relationship',
				'return_format' => 'object',
				'post_type' => array (
					0 => 'raleigh_speaker',
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
					'value' => 'raleigh_session',
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

function register_raleigh_sponsor_post_type()
{
    $labels = array(
        "name" => "Raleigh Sponsors",
        "singular_name" => "Raleigh Sponsor",
        "menu_name" => "Raleigh Sponsors",
        "all_items" => "All Raleigh Sponsors",
        "add_new" => "Add New",
        "add_new_item" => "Add New Raleigh Sponsor",
        "edit" => "Edit",
        "edit_item" => "Edit Raleigh Sponsor",
        "new_item" => "New Raleigh Sponsor",
        "view" => "View",
        "view_item" => "View Raleigh Sponsor",
        "search_items" => "Search Raleigh Sponsors",
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
        "rewrite" => array("slug" => "raleigh_sponsor", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type("raleigh_sponsor", $args);
}
add_action('init', 'register_raleigh_sponsor_post_type'); ?>
