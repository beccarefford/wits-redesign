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


/* REGISTER SPONSOR POST TYPE
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
add_action('init', 'register_washington_sponsor_post_type');

*/
