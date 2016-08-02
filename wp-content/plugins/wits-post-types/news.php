<?php

/*
Plugin Name: WITS News
Description: All press related to the Women in Tech Summit.
*/

function register_wits_news()
{
    $labels = array(
        "name" => "News",
        "singular_name" => "News",
        "menu_name" => "News",
        "all_items" => "All News",
        "add_new" => "Add New",
        "add_new_item" => "Add News",
        "edit" => "Edit",
        "edit_item" => "Edit News",
        "new_item" => "New News",
        "view" => "View",
        "view_item" => "View News",
        "search_items" => "Search News",
        "not_found" => "No Sessions Found",
        "not_found_in_trash" => "No Sessions found in Trash",
    );

    $args = Array(
        "labels" => $labels,
        "description" => "WITS News",
        "public" => true,
        "show_ui" => true,
        "has_archive" => true,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array("slug" => "news", "with_front" => true),
        "query_var" => true,
        "supports" => array("title", "editor", "excerpt", "revisions", "thumbnail", "author", "tags"),
    );
    register_post_type("news", $args);
}
add_action('init', 'register_wits_news');
