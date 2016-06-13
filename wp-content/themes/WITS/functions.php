<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


function wpbootstrap_scripts_with_jquery()
{
    // Register the script like this for a theme
    // wp_enqueue_script( 'bootstrap-jquery', get_stylesheet_directory_uri() . '/jquery.js' );
    // TODO: We are using stylesheet directory because that seems to work on active
    // child theme. Research proper way to do this.

    wp_register_script( 'bootstrap-script', get_stylesheet_directory_uri() .
                        '/bootstrap.js', array(), true );
    wp_register_script( 'carousel-script', get_stylesheet_directory_uri() .
        '/carousel-scripts.js', array(), true );

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bootstrap-script' );
    wp_enqueue_script( 'carousel-script' );
}
add_action( 'wp_enqueue_scripts', 'wpbootstrap_scripts_with_jquery' );

function phillyete_responsive (){
  wp_dequeue_style( 'interface-responsive' );
  wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css', 'style');
}
add_action('wp_enqueue_scripts','phillyete_responsive', 999);

function wpse_custom_excerpts($content, $limit, $permalink) {
    return wp_trim_words($content, $limit, '<a href="'. esc_url( $permalink ) .
          '">' . '&hellip;' . __( '<br />Read more &nbsp;&raquo;', 'wpse' ) . '</a>');
}

function pete_force_json_update($post_id) {
     delete_transient('appjson');
     delete_transient('appjsontime');
//     wp_cache_delete('appjsontime', $group = '');
}
add_action('save_post', 'pete_force_json_update');


?>
