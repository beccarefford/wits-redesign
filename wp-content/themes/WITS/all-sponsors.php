<?php

/** Template Name: All Sponsors */

get_header(); ?>

<section class="container banner">
<div class="landing" style="background-image:
  url('/wp-content/uploads/2017/01/Screen-Shot-2017-01-02-at-8.58.13-AM.png')">
    <div class="landing-home">
      <h1>All Sponsors</h1>
    </div>
</div>
</section>

<?php /* SUMMIT SERIES */ ?>

<section class="container headline">
  <div style="margin-top:150px;" class="row">
    <div class="col-md-12">
      <h1><center>
        Summit Series
      </center></h1>
    </div>
  </div>

<?php
  $current_post = 1; ?>

  <?php $query = array(
        	'post_type' => 'summitseries_sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	);

$myquery = new WP_Query($query);
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







<section class="container headline">
  <div style="margin-top:150px;" class="row">
    <div class="col-md-12">
      <h1><center>
        Philadelphia Sponsors
      </center></h1>
    </div>
  </div>

  <?php $query = array(
        	'post_type' => 'philly_sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'philly_sponsor_level',
        	'meta_value' => 'PremierCitySponsor',
        	);

$myquery = new WP_Query($query);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
        $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Premier City Sponsor</span></h3>
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

<?php $query = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'CitySponsor',
        );

$myquery = new WP_Query($query);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>City Sponsor</span></h3>
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


<?php $query = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'Friends',
        );

$myquery = new WP_Query($query);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Friends</span></h3>
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

<?php $query = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'MediaAndPartners',
        );

$myquery = new WP_Query($query);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Media & Partners</span></h3>
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

<?php $query = array(
        'post_type' => 'philly_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'philly_sponsor_level',
        'meta_value' => 'Snack',
        );

$myquery = new WP_Query($query);
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







<section class="container headline">
  <div style="margin-top:150px;" class="row">
    <div class="col-md-12">
      <h1><center>
        Raleigh-Durham Sponsors
      </center></h1>
    </div>
  </div>

<?php $headline = array(
        'post_type' => 'raleigh_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'raleigh_sponsor_level',
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
          <a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
          </a>
        </div>

        <div class="col-md-6 col-xs-12">
          <h3><?php the_title(); ?></h3>
          <br />
            <ul>
              <li><a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
              <?php echo the_field('raleigh_sponsor_url'); ?></a></li>
            <li><?php if( get_field('raleigh_sponsor_twitter') ): ?>
            <a href="<?php the_field('raleigh_sponsor_twitter');?>">
            <?php echo the_field('raleigh_sponsor_twitter'); ?></a></li>
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
      'post_type' => 'raleigh_sponsor',
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_key' => 'raleigh_sponsor_level',
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
        <a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
          <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
        </a>
      </div>

      <div class="col-md-6 col-xs-12">
        <h3><?php the_title(); ?></h3>
        <br />
          <ul>
            <li><a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
            <?php echo the_field('raleigh_sponsor_url'); ?></a></li>
          <li><?php if( get_field('raleigh_sponsor_twitter') ): ?>
          <a href="<?php the_field('raleigh_sponsor_twitter');?>">
          <?php echo the_field('raleigh_sponsor_twitter'); ?></a></li>
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
      'post_type' => 'raleigh_sponsor',
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_key' => 'raleigh_sponsor_level',
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
        <a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
          <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
        </a>
      </div>

      <div class="col-md-6 col-xs-12">
        <h3><?php the_title(); ?></h3>
        <br />
          <ul>
            <li><a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
            <?php echo the_field('raleigh_sponsor_url'); ?></a></li>
          <li><?php if( get_field('raleigh_sponsor_twitter') ): ?>
          <a href="<?php the_field('raleigh_sponsor_twitter');?>">
          <?php echo the_field('raleigh_sponsor_twitter'); ?></a></li>
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
      'post_type' => 'raleigh_sponsor',
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_key' => 'raleigh_sponsor_level',
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
        <a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
          <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
        </a>
      </div>

      <div class="col-md-6 col-xs-12">
        <h3><?php the_title(); ?></h3>
        <br />
          <ul>
            <li><a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
            <?php echo the_field('raleigh_sponsor_url'); ?></a></li>
          <li><?php if( get_field('raleigh_sponsor_twitter') ): ?>
          <a href="<?php the_field('raleigh_sponsor_twitter');?>">
          <?php echo the_field('raleigh_sponsor_twitter'); ?></a></li>
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
      'post_type' => 'raleigh_sponsor',
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_key' => 'raleigh_sponsor_level',
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
        <a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
          <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
        </a>
      </div>

      <div class="col-md-6 col-xs-12">
        <h3><?php the_title(); ?></h3>
        <br />
          <ul>
            <li><a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
            <?php echo the_field('raleigh_sponsor_url'); ?></a></li>
          <li><?php if( get_field('raleigh_sponsor_twitter') ): ?>
          <a href="<?php the_field('raleigh_sponsor_twitter');?>">
          <?php echo the_field('raleigh_sponsor_twitter'); ?></a></li>
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
      'post_type' => 'raleigh_sponsor',
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_key' => 'raleigh_sponsor_level',
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
        <a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
          <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
        </a>
      </div>

      <div class="col-md-6 col-xs-12">
        <h3><?php the_title(); ?></h3>
        <br />
          <ul>
            <li><a target="_blank" href="<?php the_field('raleigh_sponsor_url')?>">
            <?php echo the_field('raleigh_sponsor_url'); ?></a></li>
          <li><?php if( get_field('raleigh_sponsor_twitter') ): ?>
          <a href="<?php the_field('raleigh_sponsor_twitter');?>">
          <?php echo the_field('raleigh_sponsor_twitter'); ?></a></li>
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









<section class="container headline">
  <div style="margin-top:150px;" class="row">
    <div class="col-md-12">
      <h1><center>
        Washington D.C. Sponsors
      </center></h1>
    </div>
  </div>


<?php $query = array(
        'post_type' => 'washington_sponsor',
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_key' => 'washington_sponsor_level',
        'meta_value' => 'PremierCitySponsor',
        );

$myquery = new WP_Query($query);
if( $myquery->have_posts() ) :
  while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Premier City Sponsor</span></h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-6 col-xs-12">
          <a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
            <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
          </a>
        </div>

        <div class="col-md-6 col-xs-12">
          <h3><?php the_title(); ?></h3>
          <br />
            <ul>
              <li><a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
              <?php echo the_field('washington_sponsor_url'); ?></a></li>
            <li><?php if( get_field('washington_sponsor_twitter') ): ?>
            <a href="<?php the_field('washington_sponsor_twitter');?>">
            <?php echo the_field('washington_sponsor_twitter'); ?></a></li>
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

<?php $query = array(
      'post_type' => 'washington_sponsor',
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_key' => 'washington_sponsor_level',
      'meta_value' => 'CitySponsor',
      );

$myquery = new WP_Query($query);
if( $myquery->have_posts() ) :
while( $myquery->have_posts() ) : $myquery->the_post();
    $current_post = $myquery->current_post + 1; ?>

    <?php if($current_post == 1) { ?>
      <h3 class="sponsor"><span>City Sponsor</span></h3>
    <?php } ?>
    <div class="row">
      <div class="col-md-6 col-xs-12">
        <a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
          <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
        </a>
      </div>

      <div class="col-md-6 col-xs-12">
        <h3><?php the_title(); ?></h3>
        <br />
          <ul>
            <li><a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
            <?php echo the_field('washington_sponsor_url'); ?></a></li>
          <li><?php if( get_field('washington_sponsor_twitter') ): ?>
          <a href="<?php the_field('washington_sponsor_twitter');?>">
          <?php echo the_field('washington_sponsor_twitter'); ?></a></li>
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


<?php $query = array(
      'post_type' => 'washington_sponsor',
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_key' => 'washington_sponsor_level',
      'meta_value' => 'Friends',
      );

$myquery = new WP_Query($query);
if( $myquery->have_posts() ) :
while( $myquery->have_posts() ) : $myquery->the_post();
    $current_post = $myquery->current_post + 1; ?>

    <?php if($current_post == 1) { ?>
      <h3 class="sponsor"><span>Friends</span></h3>
    <?php } ?>
    <div class="row">
      <div class="col-md-6 col-xs-12">
        <a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
          <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
        </a>
      </div>

      <div class="col-md-6 col-xs-12">
        <h3><?php the_title(); ?></h3>
        <br />
          <ul>
            <li><a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
            <?php echo the_field('washington_sponsor_url'); ?></a></li>
          <li><?php if( get_field('washington_sponsor_twitter') ): ?>
          <a href="<?php the_field('washington_sponsor_twitter');?>">
          <?php echo the_field('washington_sponsor_twitter'); ?></a></li>
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

<?php $query = array(
      'post_type' => 'washington_sponsor',
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_key' => 'washington_sponsor_level',
      'meta_value' => 'MediaAndPartners',
      );

$myquery = new WP_Query($query);
if( $myquery->have_posts() ) :
while( $myquery->have_posts() ) : $myquery->the_post();
    $current_post = $myquery->current_post + 1; ?>

    <?php if($current_post == 1) { ?>
      <h3 class="sponsor"><span>Media & Partners</span></h3>
    <?php } ?>
    <div class="row">
      <div class="col-md-6 col-xs-12">
        <a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
          <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
        </a>
      </div>

      <div class="col-md-6 col-xs-12">
        <h3><?php the_title(); ?></h3>
        <br />
          <ul>
            <li><a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
            <?php echo the_field('washington_sponsor_url'); ?></a></li>
          <li><?php if( get_field('washington_sponsor_twitter') ): ?>
          <a href="<?php the_field('washington_sponsor_twitter');?>">
          <?php echo the_field('washington_sponsor_twitter'); ?></a></li>
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

<?php $query = array(
      'post_type' => 'washington_sponsor',
      'orderby' => 'date',
      'order' => 'ASC',
      'meta_key' => 'washington_sponsor_level',
      'meta_value' => 'Snack',
      );

$myquery = new WP_Query($query);
if( $myquery->have_posts() ) :
while( $myquery->have_posts() ) : $myquery->the_post();
    $current_post = $myquery->current_post + 1; ?>

    <?php if($current_post == 1) { ?>
      <h3 class="sponsor"><span>Snack</span></h3>
    <?php } ?>
    <div class="row">
      <div class="col-md-6 col-xs-12">
        <a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
          <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
        </a>
      </div>

      <div class="col-md-6 col-xs-12">
        <h3><?php the_title(); ?></h3>
        <br />
          <ul>
            <li><a target="_blank" href="<?php the_field('washington_sponsor_url')?>">
            <?php echo the_field('washington_sponsor_url'); ?></a></li>
          <li><?php if( get_field('washington_sponsor_twitter') ): ?>
          <a href="<?php the_field('washington_sponsor_twitter');?>">
          <?php echo the_field('washington_sponsor_twitter'); ?></a></li>
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

</section>

<?php get_footer(); ?>
