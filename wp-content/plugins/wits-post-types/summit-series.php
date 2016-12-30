<?php

/*
Plugin Name: Summit Series Sponsors
Description: National Sponsors for the Women In Tech Summit
*/

function register_summitseries_sponsor_post_type()
{
    $labels = array(
        "name" => "Summit Series Sponsors",
        "singular_name" => "Summit Series Sponsor",
        "menu_name" => "Summit Series Sponsors",
        "all_items" => "All Summit Series Sponsors",
        "add_new" => "Add New",
        "add_new_item" => "Add New Summit Series Sponsor",
        "edit" => "Edit",
        "edit_item" => "Edit Summit Series Sponsor",
        "new_item" => "New Summit Series Sponsor",
        "view" => "View",
        "view_item" => "View Summit Series Sponsor",
        "search_items" => "Search Summit Series Sponsors",
        "not_found" => "No Summit Series Sponsors Found",
        "not_found_in_trash" => "No Summit Series Sponsors found in Trash",
    );

    $args = Array(
        "labels" => $labels,
        "description" => "A WITS Summit Series Sponsor",
        "public" => true,
        "show_ui" => true,
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array("slug" => "summitseries_sponsor", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type("summitseries_sponsor", $args);
}
add_action('init', 'register_summitseries_sponsor_post_type');

?>

<?php /* CUSTOM FIELDS */ ?>
<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_5865bdefe5c5a',
	'title' => 'Summit Series Sponsors',
	'fields' => array (
		array (
			'key' => 'field_5865bede1156b',
			'label' => 'Summit Series Sponsor URL',
			'name' => 'summit_series_sponsor_url',
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
		array (
			'key' => 'field_5865befb1156c',
			'label' => 'Summit Series Sponsor Twitter',
			'name' => 'summit_series_sponsor_twitter',
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
				'value' => 'summitseries_sponsor',
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
