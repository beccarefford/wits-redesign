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


/* REGISTER SPONSOR POST TYPE */
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
add_action('init', 'register_raleigh_sponsor_post_type');
