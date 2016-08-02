<?php get_header(); ?>

<section class="container banner-home">
<div class="landing" style="background-image:
  url('/wp-content/uploads/2016/07/Screen-Shot-2016-07-24-at-2.26.42-PM.png')">
    <div class="landing-home">
      <h1>Press</h1>
      <p>The Women In Tech Summit in the News</p>
    </div>
</div>
</section>

<section class="container">
			<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>
			<?php
	    $wp_query = new WP_Query(array(
		    'post_type' => 'news',
				'order' => 'DESC',
		    'orderby' => 'date',
				'paged' => $paged
	    ));
	    ?>

			<?php
      while ($wp_query->have_posts()) : $wp_query->the_post();
			?>

			<div class="row">
			     <div class="col-md-12">
			          <a href="<?php the_permalink(); ?>"><h2 style="padding-top:30px;"><?php echo the_title(); ?></h2></a>
			<p>
				<em><?php $pfx_date = get_the_date( $format, $post_id ); echo $pfx_date; ?></em>
			</p>
      <?php $excerpt = get_the_content();
                    $permalink = get_permalink($post->ID); ?>
              <p><?php echo wpse_custom_excerpts($excerpt, 100, $permalink); ?></p>
			<a href="<?php the_permalink(); ?>"><div class="button">Read More</div></a>


		      </div>
	    </div>
	<hr />

<?php endwhile; ?>

<div class="row">
	<div class="col-md-12">
		<div class="pagination-forward">
		<?php next_posts_link(); ?>
		</div>

		<div class="pagination-backward">
		<?php previous_posts_link(); ?>
		</div>
	</div>
</div>

</section>

<?php get_footer(); ?>
