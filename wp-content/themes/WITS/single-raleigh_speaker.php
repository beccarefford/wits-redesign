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
          <div class="speaker-job-title"><?php the_field('raleigh_job_title'); ?></div>
          <?php
          $excerpt = get_the_excerpt();
          $permalink = get_permalink();
          $talks = get_field('raleigh_session_relationship');
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
            <br /><?php if( get_field('raleigh_website') ): ?>
            <a target="_blank" href="<?php the_field('raleigh_website');?>"><i class="icon-globe icon-2x"></i></a>
            <?php endif; ?>

            <?php if( get_field('raleigh_twitter') ): ?>
              <a target="_blank" href="<?php the_field('raleigh_twitter');?>"><i class="icon-twitter icon-2x"></i></a>
            <?php endif; ?>

        </div>
      </div>

<?php endwhile; ?>
<?php endif; ?>

</section>

<?php get_footer(); ?>
