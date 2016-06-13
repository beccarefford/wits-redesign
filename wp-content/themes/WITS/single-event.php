<?php get_header(); ?>

<?php if(have_posts()): while(have_posts()): the_post(); ?>

<section class="container">
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

      <?php //Display Location Category ?>
      &nbsp;
        <?php
        $terms = get_the_terms( $post->ID, 'location' );
        if ( $terms && ! is_wp_error( $terms ) ) :
          $locations = array();
            foreach ( $terms as $term ) {
              $locations[] = $term->name;
            }
        ?>

        <?php
        $location = join( ", ", $locations ); ?>
        Location: <?php echo $location; ?>
        <br />
        <?php endif; ?>

      <?php // Display formatted date ?>

      <?php $format = "F jS, Y";?>
      <?php if( $datestamp = get_field('date') ): ?>
        <?php echo date_i18n( $format, $datestamp ); ?>
        <br />
        <?php endif; ?>

      <?php // Display formatted time ?>
      <?php $timeformat = "g:i A"; ?>
      <?php if( $timestamp = get_field('date') ): ?>
        <?php echo date_i18n( $timeformat, $timestamp ); ?>
        <br />
      <?php endif; ?>

      </div>




    <div class="col-md-8">

      <?php echo the_content(); ?>

    </div>
  </div>
</section>

<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
