<?php get_header(); ?>

<?php if(have_posts()): while(have_posts()): the_post(); ?>

  <section class="container">

    <div class="row">
      <div class="col-md-2 col-xs-3">
            <a href="<?php the_permalink(); ?>">
              <div class="circular-image-archive">
                <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
              </div>
      </div>

      <div class="col-md-10 col-xs-9">
        <a href="<?php the_permalink() ?>">
          <h3 class="name"><?php the_title(); ?></h3></a>
          <div class="speaker-job-title"><?php the_field('job_title'); ?></div>
          <?php
          $excerpt = get_the_excerpt();
          $permalink = get_permalink();
          $talks = get_field('session');
            if ($talks): ?>
                <?php foreach ($talks as $post): ?>
                  <?php setup_postdata($post); ?>
                    <a href="<?php echo the_permalink(); ?>"><?php the_title(); ?><br /></a>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 col-xs-12">
          <?php echo the_content(); ?>

          <center>
            <br /><?php if( get_field('website') ): ?>
            <a href="<?php the_field('website');?>"><i class="icon-globe icon-2x"></i></a>
            <?php endif; ?>

            <?php if( get_field('twitter') ): ?>
              <a href="<?php the_field('twitter');?>"><i class="icon-twitter icon-2x"></i></a>
            <?php endif; ?>

            <?php if( get_field('facebook') ): ?>
              <a href="<?php the_field('facebook');?>"><i class="icon-facebook icon-2x"></i></a>
            <?php endif; ?>
        </div>
      </div>





        <?php /*

        // displays 'Other Talks' repeater field

        if( have_rows('other_talks') ):

            while ( have_rows('other_talks') ) : the_row(); ?>

            <h3>Other Talks:</h3>

                <a href="<?php the_sub_field('talk_url'); ?>">

                  <?php echo the_sub_field('talk_title'); ?><br />

                </a>

        <?php

                endwhile;

                else :

                endif;

                ?>



      <div class="col-md-9 col-xs-8">

        <br />

        <?php the_content(); */ ?>


<?php endwhile; ?>
<?php endif; ?>

</section>

<?php get_footer(); ?>
