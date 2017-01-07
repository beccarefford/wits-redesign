<?php

/** Template Name: Boston Sponsors */

get_header(); ?>

<section class="container">

<?php
  $current_post = 1; ?>

  <?php // sponsor_type queries
        // begin with 'Headline'?>

        <?php $headline = array(
                'post_type' => 'summitseries_sponsor',
                'orderby' => 'date',
                'order' => 'ASC',
                );

      $myquery = new WP_Query($headline);
        if( $myquery->have_posts() ) :
          while( $myquery->have_posts() ) : $myquery->the_post();
              $current_post = $myquery->current_post + 1; ?>

              <?php if($current_post == 1) { ?>
                <h3 class="sponsor"><span>Summit Series</span></h3>
              <?php } ?>
              <div class="row">
                <div class="col-md-6 col-xs-12">
                  <a target="_blank" href="<?php the_field('summit_series_sponsor_url')?>">
                    <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
                  </a>
                </div>

                <div class="col-md-6 col-xs-12">
                  <h3><?php the_title(); ?></h3>
                  <br />
                    <ul>
                      <li><a target="_blank" href="<?php the_field('summit_series_sponsor_url')?>">
                      <?php echo the_field('summit_series_sponsor_url'); ?></a></li>
                    <li><?php if( get_field('summit_series_sponsor_twitter') ): ?>
                    <a href="<?php the_field('summit_series_sponsor_twitter');?>">
                    <?php echo the_field('summit_series_sponsor_twitter'); ?></a></li>
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
              'post_type' => 'boston_sponsor',
              'orderby' => 'date',
              'order' => 'ASC',
              'meta_key' => 'boston_sponsor_level',
              'meta_value' => 'PremierCitySponsor',
              );

      $myquery = new WP_Query($headline);
      if( $myquery->have_posts() ) :
        while( $myquery->have_posts() ) : $myquery->the_post();
            $current_post = $myquery->current_post + 1; ?>

            <?php if($current_post == 1) { ?>
              <h3 class="sponsor"><span>Premier City Sponsor</span></h3>
            <?php } ?>
            <div class="row">
              <div class="col-md-6 col-xs-12">
                <a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                  <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
                </a>
              </div>

              <div class="col-md-6 col-xs-12">
                <h3><?php the_title(); ?></h3>
                <br />
                  <ul>
                    <li><a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                    <?php echo the_field('boston_sponsor_url'); ?></a></li>
                  <li><?php if( get_field('boston_sponsor_twitter') ): ?>
                  <a href="<?php the_field('boston_sponsor_twitter');?>">
                  <?php echo the_field('boston_sponsor_twitter'); ?></a></li>
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
            'post_type' => 'boston_sponsor',
            'orderby' => 'date',
            'order' => 'ASC',
            'meta_key' => 'boston_sponsor_level',
            'meta_value' => 'CitySponsor',
            );

      $myquery = new WP_Query($headline);
      if( $myquery->have_posts() ) :
      while( $myquery->have_posts() ) : $myquery->the_post();
          $current_post = $myquery->current_post + 1; ?>

          <?php if($current_post == 1) { ?>
            <h3 class="sponsor"><span>City Sponsor</span></h3>
          <?php } ?>
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
              </a>
            </div>

            <div class="col-md-6 col-xs-12">
              <h3><?php the_title(); ?></h3>
              <br />
                <ul>
                  <li><a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                  <?php echo the_field('boston_sponsor_url'); ?></a></li>
                <li><?php if( get_field('boston_sponsor_twitter') ): ?>
                <a href="<?php the_field('boston_sponsor_twitter');?>">
                <?php echo the_field('boston_sponsor_twitter'); ?></a></li>
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
            'post_type' => 'boston_sponsor',
            'orderby' => 'date',
            'order' => 'ASC',
            'meta_key' => 'boston_sponsor_level',
            'meta_value' => 'Friends',
            );

      $myquery = new WP_Query($headline);
      if( $myquery->have_posts() ) :
      while( $myquery->have_posts() ) : $myquery->the_post();
          $current_post = $myquery->current_post + 1; ?>

          <?php if($current_post == 1) { ?>
            <h3 class="sponsor"><span>Friends</span></h3>
          <?php } ?>
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
              </a>
            </div>

            <div class="col-md-6 col-xs-12">
              <h3><?php the_title(); ?></h3>
              <br />
                <ul>
                  <li><a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                  <?php echo the_field('boston_sponsor_url'); ?></a></li>
                <li><?php if( get_field('boston_sponsor_twitter') ): ?>
                <a href="<?php the_field('boston_sponsor_twitter');?>">
                <?php echo the_field('boston_sponsor_twitter'); ?></a></li>
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
            'post_type' => 'boston_sponsor',
            'orderby' => 'date',
            'order' => 'ASC',
            'meta_key' => 'boston_sponsor_level',
            'meta_value' => 'MediaAndPartners',
            );

      $myquery = new WP_Query($headline);
      if( $myquery->have_posts() ) :
      while( $myquery->have_posts() ) : $myquery->the_post();
          $current_post = $myquery->current_post + 1; ?>

          <?php if($current_post == 1) { ?>
            <h3 class="sponsor"><span>Media & Partners</span></h3>
          <?php } ?>
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
              </a>
            </div>

            <div class="col-md-6 col-xs-12">
              <h3><?php the_title(); ?></h3>
              <br />
                <ul>
                  <li><a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                  <?php echo the_field('boston_sponsor_url'); ?></a></li>
                <li><?php if( get_field('boston_sponsor_twitter') ): ?>
                <a href="<?php the_field('boston_sponsor_twitter');?>">
                <?php echo the_field('boston_sponsor_twitter'); ?></a></li>
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
            'post_type' => 'boston_sponsor',
            'orderby' => 'date',
            'order' => 'ASC',
            'meta_key' => 'boston_sponsor_level',
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
              <a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
              </a>
            </div>

            <div class="col-md-6 col-xs-12">
              <h3><?php the_title(); ?></h3>
              <br />
                <ul>
                  <li><a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                  <?php echo the_field('boston_sponsor_url'); ?></a></li>
                <li><?php if( get_field('boston_sponsor_twitter') ): ?>
                <a href="<?php the_field('boston_sponsor_twitter');?>">
                <?php echo the_field('boston_sponsor_twitter'); ?></a></li>
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
            'post_type' => 'boston_sponsor',
            'orderby' => 'date',
            'order' => 'ASC',
            'meta_key' => 'boston_sponsor_level',
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
              <a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
              </a>
            </div>

            <div class="col-md-6 col-xs-12">
              <h3><?php the_title(); ?></h3>
              <br />
                <ul>
                  <li><a target="_blank" href="<?php the_field('boston_sponsor_url')?>">
                  <?php echo the_field('boston_sponsor_url'); ?></a></li>
                <li><?php if( get_field('boston_sponsor_twitter') ): ?>
                <a href="<?php the_field('boston_sponsor_twitter');?>">
                <?php echo the_field('boston_sponsor_twitter'); ?></a></li>
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
