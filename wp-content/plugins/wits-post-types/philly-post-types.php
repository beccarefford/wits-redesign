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


/* REGISTER SPONSOR POST TYPE */
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
