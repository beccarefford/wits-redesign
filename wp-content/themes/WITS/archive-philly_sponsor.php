<?php

/** Template Name: Philly Sponsors */

get_header(); ?>

<section class="container">

<?php
  $current_post = 1; ?>

  <?php // sponsor_type queries
        // begin with 'Headline'?>

  <?php $headline = array(
        	'post_type' => 'philly_sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'philly_sponsor_level',
        	'meta_value' => 'Headline',
        	);

$myquery = new WP_Query($headline);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
        $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Headline</span></h3>
        <?php } ?>
        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
            <br />
              <ul>
                <li><a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
                <?php echo the_field('philly_sponsor_url'); ?></a></li>
              <li><?php if( get_field('philly_sponsor_twitter') ): ?>
              <a href="<?php the_field('philly_sponsor_twitter');?>">
              <?php echo the_field('philly_sponsor_twitter'); ?></a></li>
            </ul>
            <?php endif; ?>
          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php $headline = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'Petabyte',
        );

$myquery = new WP_Query($headline);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Petabyte</span></h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-6 col-xs-12">
          <a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
          </a>
        </div>

        <div class="col-md-6 col-xs-12">
          <h3><?php the_title(); ?></h3>
          <br />
            <ul>
              <li><a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
              <?php echo the_field('philly_sponsor_url'); ?></a></li>
            <li><?php if( get_field('philly_sponsor_twitter') ): ?>
            <a href="<?php the_field('philly_sponsor_twitter');?>">
            <?php echo the_field('philly_sponsor_twitter'); ?></a></li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    <hr />
  <?php
endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>


<?php $headline = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'Terabyte',
        );

$myquery = new WP_Query($headline);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Terabyte</span></h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-6 col-xs-12">
          <a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
          </a>
        </div>

        <div class="col-md-6 col-xs-12">
          <h3><?php the_title(); ?></h3>
          <br />
            <ul>
              <li><a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
              <?php echo the_field('philly_sponsor_url'); ?></a></li>
            <li><?php if( get_field('philly_sponsor_twitter') ): ?>
            <a href="<?php the_field('philly_sponsor_twitter');?>">
            <?php echo the_field('philly_sponsor_twitter'); ?></a></li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    <hr />
  <?php
endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php $headline = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'Gigabyte',
        );

$myquery = new WP_Query($headline);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Gigabyte</span></h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-6 col-xs-12">
          <a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
          </a>
        </div>

        <div class="col-md-6 col-xs-12">
          <h3><?php the_title(); ?></h3>
          <br />
            <ul>
              <li><a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
              <?php echo the_field('philly_sponsor_url'); ?></a></li>
            <li><?php if( get_field('philly_sponsor_twitter') ): ?>
            <a href="<?php the_field('philly_sponsor_twitter');?>">
            <?php echo the_field('philly_sponsor_twitter'); ?></a></li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    <hr />
  <?php
endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php $headline = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'Lanyard',
        );

$myquery = new WP_Query($headline);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Lanyard</span></h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-6 col-xs-12">
          <a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
          </a>
        </div>

        <div class="col-md-6 col-xs-12">
          <h3><?php the_title(); ?></h3>
          <br />
            <ul>
              <li><a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
              <?php echo the_field('philly_sponsor_url'); ?></a></li>
            <li><?php if( get_field('philly_sponsor_twitter') ): ?>
            <a href="<?php the_field('philly_sponsor_twitter');?>">
            <?php echo the_field('philly_sponsor_twitter'); ?></a></li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    <hr />
  <?php
endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php $headline = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'Megabyte',
        );

$myquery = new WP_Query($headline);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Megabyte</span></h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-6 col-xs-12">
          <a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
          </a>
        </div>

        <div class="col-md-6 col-xs-12">
          <h3><?php the_title(); ?></h3>
          <br />
            <ul>
              <li><a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
              <?php echo the_field('philly_sponsor_url'); ?></a></li>
            <li><?php if( get_field('philly_sponsor_twitter') ): ?>
            <a href="<?php the_field('philly_sponsor_twitter');?>">
            <?php echo the_field('philly_sponsor_twitter'); ?></a></li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    <hr />
  <?php
endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php $headline = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'Snack',
        );

$myquery = new WP_Query($headline);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Snack</span></h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-6 col-xs-12">
          <a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
          </a>
        </div>

        <div class="col-md-6 col-xs-12">
          <h3><?php the_title(); ?></h3>
          <br />
            <ul>
              <li><a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
              <?php echo the_field('philly_sponsor_url'); ?></a></li>
            <li><?php if( get_field('philly_sponsor_twitter') ): ?>
            <a href="<?php the_field('philly_sponsor_twitter');?>">
            <?php echo the_field('philly_sponsor_twitter'); ?></a></li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    <hr />
  <?php
endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php $headline = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'post_status' => 'published',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'Media',
        );

$myquery = new WP_Query($headline);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Media</span></h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-6 col-xs-12">
          <a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
          </a>
        </div>

        <div class="col-md-6 col-xs-12">
          <h3><?php the_title(); ?></h3>
          <br />
            <ul>
              <li><a target="_blank" href="<?php the_field('philly_sponsor_url')?>">
              <?php echo the_field('philly_sponsor_url'); ?></a></li>
            <li><?php if( get_field('philly_sponsor_twitter') ): ?>
            <a href="<?php the_field('philly_sponsor_twitter');?>">
            <?php echo the_field('philly_sponsor_twitter'); ?></a></li>
          </ul>
          <?php endif; ?>
        </div>
      </div>
    <hr />
  <?php
endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>


<?php get_footer(); ?>
