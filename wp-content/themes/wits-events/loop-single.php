<?php // Loop through posts
	global $multipage;

	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
?>
		<article id="<?php the_ID(); ?>" <?php post_class( 'post-content cf' ); ?>>
			<?php sds_featured_image(); ?>

			<header class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>

				<p class="latest-post-date">
					<?php
					printf( 
					__( 'Posted on %1$s', 'symphony' ) ,
					get_the_time( get_option( 'date_format' ) )
					); 
					?>
				</p>			
				</header>
			<?php the_content(); ?>
			
<?php the_meta(); ?>
			<div class="clear"></div>
<?php if ( has_term( 'Philadelphia', 'cities' ) ) {
						 echo '<a href="http://womenintechsummit.net/philadelphia-schedule/">Return to Philadelphia Schedule</a>';				}
	 elseif( has_term( 'Washington', 'cities' ) ) {
						 echo '<a href="http://womenintechsummit.net/washington-schedule/">Return to Washington Schedule</a>';				}
	 elseif ( has_term( 'Raleigh', 'cities' ) ) {
						 echo '<a href="http://womenintechsummit.net/raleigh-schedule/">Return to Raleigh Schedule</a>';				}
	 else{}
	 ?>
			<?php edit_post_link( __( 'Edit Post', 'symphony' ) ); // Allow logged in users to edit ?>

			<div class="clear"></div>

			<section class="post-meta">
				<?php sds_post_meta(); ?>

			</section>

			<?php if ( $multipage ) : ?>
				<section class="single-post-navigation single-post-pagination wp-link-pages">
					<?php wp_link_pages(); ?>
				</section>
			<?php endif; ?>

			<?php get_template_part( 'post', 'author' ); // Author Details ?>
		</article>

		<section class="clear"></section>

		<section class="after-posts-widgets <?php echo ( is_active_sidebar( 'after-posts-sidebar' ) ) ? 'after-posts-widgets-active cf widgets' : 'no-widgets'; ?>">
			<?php sds_after_posts_sidebar(); ?>
		</section>

		<!-- Pagination -->
		

			<div class="clear"></div>
<?php
		endwhile;
	endif;
?>