<?php
/**
 * This is the content wrapper template used for displaying the opening content wrapper element.
 *
 * Conductor will look for your-theme/conductor/content-before.php and load that file first if it exists.
 *
 * @author Slocum Studio
 * @version 1.0.0
 * @since 1.0.0
 */

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;
?>
<?php if ( ! is_front_page() ) {?>
<section class="breadcrumbs"><?php if ( function_exists('yoast_breadcrumb') ) {
yoast_breadcrumb('<p id="breadcrumbs">','</p>');
} ?></section>
<?php } ?>
<div class="city-nav-container"><?php if ( is_page (array(38,75,76,77,78)) ){ 
		wp_nav_menu( array( 'menu' => 'baltimore-event', 'items_wrap' => '<ul class="citymenu">%3$s</ul>'  ) );	}
	elseif ( 
is_page (array(260, 296, 278, 276, 277, 279)) ){ 
		wp_nav_menu( array( 'menu' => 'raleigh-event', 'items_wrap' => '<ul class="citymenu">%3$s</ul>' ) );	}
	elseif ( is_page (array(40,104,103,102,101,234)) ){ 
		wp_nav_menu( array( 'menu' => 'philadelphia-event', 'items_wrap' => '<ul class="citymenu">%3$s</ul>' ) );	}
	elseif ( is_page (array(41,297,274,272,273,275)) ){ 
		wp_nav_menu( array( 'menu' => 'washington-event','items_wrap' => '<ul class="citymenu">%3$s</ul>'  ) );	}	
	else {echo "<p></p>";
	}
?>
</div>

<?php echo apply_filters( 'conductor_content_element_before', '<div class="conductor-content conductor-cf ' . Conductor::get_conductor_content_layout_sidebar_id( 'content' ) . '" data-sidebar-id="' . Conductor::get_conductor_content_layout_sidebar_id( 'content' ) . '"><div class="conductor-inner conductor-cf">' ); ?>