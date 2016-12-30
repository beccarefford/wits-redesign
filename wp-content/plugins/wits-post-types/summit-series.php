<?php

/*
Plugin Name: Summit Series Sponsors
Description: National Sponsors for the Women In Tech Summit
*/

function register_summit_series_sponsor_post_type()
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
        "not_found" => "No Sponsors Found",
        "not_found_in_trash" => "No Sponsors found in Trash",
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
        "rewrite" => array("slug" => "summit_series_sponsor", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author"),
    );
    register_post_type('summit_series_sponsor', $args);
}
add_action('init', 'register_summit_series_sponsor_post_type');

?>
