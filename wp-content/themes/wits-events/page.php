<?php
/*
 * This template is used for the display of single pages.
 */
get_header();
?>
	<!-- Home Content -->
	<section class="content-container cf">
		<?php if ( ! symphony_is_fixed_width() ) : ?>
			<div class="in">
		<?php endif; ?>

		<article class="content">
		<?php get_template_part( 'yoast', 'breadcrumbs' ); // Yoast Breadcrumbs ?>
		<div class="city-nav-container">
		<?php if ( is_page (array(38,75,76,77,78)) ){ 
		wp_nav_menu( array( 'menu' => 'baltimore-event', 'items_wrap' => '<ul class="citymenu">%3$s</ul>'  ) );	}
	elseif ( is_page (array(260,276,277,278,279,296)) ){ 
		wp_nav_menu( array( 'menu' => 'raleigh-event', 'items_wrap' => '<ul class="citymenu">%3$s</ul>' ) );	}
	elseif ( is_page (array(40,103,102,101,234,294)) ){ 
		wp_nav_menu( array( 'menu' => 'philadelphia-event', 'items_wrap' => '<ul class="citymenu">%3$s</ul>' ) );	}
	elseif ( is_page (array(41,272,273,274,275,297)) ){ 
		wp_nav_menu( array( 'menu' => 'washington-event','items_wrap' => '<ul class="citymenu">%3$s</ul>'  ) );	}
		elseif ( is_page (array(829,840,841,842)) ){ 
		wp_nav_menu( array( 'menu' => 'pick','items_wrap' => '<ul class="citymenu">%3$s</ul>'  ) );	}	
	else {echo "<p></p>";
	}
?></div>
		
		
			<?php get_template_part( 'loop', 'page' ); ?>


			<?php comments_template(); ?>
 
		</article>

		<?php get_sidebar(); ?>

		<?php if ( ! symphony_is_fixed_width() ) : ?>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
	</section>
<?php get_footer(); ?>