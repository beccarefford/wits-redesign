<?php

/*
Plugin Name: Washington Custom Post Types
Description: Sessions, speakers, and sponsors for Washington.
*/


function register_washington_session_post_type()
{
    $labels = array(
        "name" => "Washington Sessions",
        "singular_name" => "Washington Session",
        "menu_name" => "Washington Sessions",
        "all_items" => "All Washington Sessions",
        "add_new" => "Add New",
        "add_new_item" => "Add New Washington Session",
        "edit" => "Edit",
        "edit_item" => "Edit Washington Session",
        "new_item" => "New Washington Session",
        "view" => "View",
        "view_item" => "View Washington Session",
        "search_items" => "Search Washington Session",
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
        "rewrite" => array("slug" => "washington_session", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author", "tags"),
    );
    register_post_type("washington_session", $args);
}
add_action('init', 'register_washington_session_post_type');





/* REGISTER SPEAKERS POST TYPE */
function register_washington_speaker_post_type()
{
    $labels = array(
        "name" => "Washington Speakers",
        "singular_name" => "Washington Speaker",
        "menu_name" => "Washington Speakers",
        "all_items" => "All Washington Speakers",
        "add_new" => "Add New",
        "add_new_item" => "Add New Washington Speaker",
        "edit" => "Edit",
        "edit_item" => "Edit Washington Speaker",
        "new_item" => "New Washington Speaker",
        "view" => "View",
        "view_item" => "View Washington Speaker",
        "search_items" => "Search Washington Speakers",
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
        "rewrite" => array("slug" => "washington_speaker", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type("washington_speaker", $args);
}
add_action('init', 'register_washington_speaker_post_type');

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_washington-speaker-field-group',
		'title' => 'Washington Speaker Field Group',
		'fields' => array (
			array (
				'key' => 'field_5773a670c6c68',
				'label' => 'Keynote',
				'name' => 'washington_keynote',
				'type' => 'true_false',
				'message' => '',
				'default_value' => 0,
			),
			array (
				'key' => 'field_5773a67ec6c69',
				'label' => 'Job Title',
				'name' => 'washington_job_title',
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
				'name' => 'washington_twitter',
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
				'name' => 'washington_website',
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
					'value' => 'washington_speaker',
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
		'id' => 'acf_washington-session-relationship',
		'title' => 'Washington Session Relationship',
		'fields' => array (
			array (
				'key' => 'field_5773b089dcb13',
				'label' => 'Washington Session Relationship',
				'name' => 'washington_session_relationship',
				'type' => 'relationship',
				'return_format' => 'object',
				'post_type' => array (
					0 => 'washington_session',
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
					'value' => 'washington_speaker',
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
	'key' => 'group_577fead43283c',
	'title' => 'Washington Speaker Photo',
	'fields' => array (
		array (
			'key' => 'field_577feb62f9ddb',
			'label' => 'Washington Speaker Photo',
			'name' => 'washington_speaker_photo',
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
				0 => 'washington_speaker',
			),
			'taxonomy' => array (
			),
      'filters' => array (
        0 => 'search',
      ),
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
				'value' => 'washington_session',
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

function register_washington_sponsor_post_type()
{
    $labels = array(
        "name" => "Washington Sponsors",
        "singular_name" => "Washington Sponsor",
        "menu_name" => "Washington Sponsors",
        "all_items" => "All Washington Sponsors",
        "add_new" => "Add New",
        "add_new_item" => "Add New Washington Sponsor",
        "edit" => "Edit",
        "edit_item" => "Edit Washington Sponsor",
        "new_item" => "New Washington Sponsor",
        "view" => "View",
        "view_item" => "View Washington Sponsor",
        "search_items" => "Search Washington Sponsors",
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
        "rewrite" => array("slug" => "washington_sponsor", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type("washington_sponsor", $args);
}
add_action('init', 'register_washington_sponsor_post_type'); ?>
