<?php
get_header(); ?>

	<section class="content-container cf">
		<?php if ( ! symphony_is_fixed_width() ) : ?>
			<div class="in">
		<?php endif; ?>
		<!--Post Loop -->
		<article class="content">
<div class="city-nav-container">
		<?php if ( has_term ('Baltimore', 'cities') ){
		wp_nav_menu( array( 'menu' => 'baltimore-event', 'items_wrap' => '<ul class="citymenu">%3$s</ul>'  ) );	}
	elseif ( has_term ('Raleigh', 'cities') ){
		wp_nav_menu( array( 'menu' => 'raleigh-event', 'items_wrap' => '<ul class="citymenu">%3$s</ul>' ) );	}
	elseif ( has_term ('Philadelphia', 'cities') ) {
		wp_nav_menu( array( 'menu' => 'philadelphia-event', 'items_wrap' => '<ul class="citymenu">%3$s</ul>' ) );	}
	elseif ( has_term ('Washington', 'cities') ){
		wp_nav_menu( array( 'menu' => 'washington-event','items_wrap' => '<ul class="citymenu">%3$s</ul>'  ) );	}
	else {echo "<p></p>";
	}
?></div>

			<?php get_template_part( 'loop', 'single' ); ?>

			<?php comments_template(); ?>

		</article>

		<!-- Sidebar -->
		<?php get_sidebar(); ?>

		<?php if ( ! symphony_is_fixed_width() ) : ?>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
	</section>

<?php get_footer(); ?>
