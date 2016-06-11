<?php get_header(); ?>

<div class="page-title-wrap">
  <div class="container clearfix">
    <h1 class="page-title">Events</h1>
  </div>
</div>

<section class="container">

  <?php $event = array(
        	'post_type' => 'event',
        	'orderby' => 'date',
        	'order' => 'DESC',
        	);

$wp_query = new WP_Query($event);
  if( $wp_query->have_posts() ) :
    while( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

      <h3 class="sponsor"><span><?php the_title();?></span></h3>

        <div class="row">
          <div class="col-md-4">
            <?php $pics = get_posts(array(
              'post_type' => 'event',
              'meta_query' => array(
                array(
                  'key' => 'event_sponsor', // name of custom field
                  'value' => '"' . get_the_ID() . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
                  'compare' => 'LIKE'
                )
              )
            )); ?>
            <?php $pics = get_field('event_sponsor');
              if ($pics): ?>
                  <?php foreach ($pics as $post): ?>
                  <?php setup_postdata($post); ?>
                  <h2>Brought to you by:</h2>
                  &nbsp;
                      <a href="<?php the_permalink(); ?>">
                          <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
                      </a>
                  <?php endforeach; ?>
              <?php endif; ?>
              <?php wp_reset_postdata(); ?>
          </div>

          <div class="col-md-8">
              <p><?php echo the_content(); ?></p>
          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

</section>

<?php wp_reset_postdata(); ?>

<?php get_footer(); ?>
