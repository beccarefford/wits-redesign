<?php get_header(); ?>

<div class="page-title-wrap">
  <div class="container clearfix">
  </div>
</div>

<section class="container">

<?php if(have_posts()): while(have_posts()): the_post(); ?>

        <div class="row">
          <div class="col-md-4">
            <a href="<?php the_field('sponsor_url'); ?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-8">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>
              <a href="<?php the_field('sponsor_url'); ?>"><?php the_field('sponsor_url'); ?></a>
          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

</section>
<?php get_footer(); ?>
