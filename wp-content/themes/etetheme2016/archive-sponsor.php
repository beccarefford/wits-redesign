<?php get_header(); ?>

<div class="page-title-wrap">
  <div class="container clearfix">
    <h1 class="page-title">Sponsors</h1>
  </div>
</div>

<section class="container">

<?php
  $current_post = 1; ?>

  <?php // sponsor_type queries
        // begin with 'Headline'?>

  <?php $headline = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Headline',
        	);

$myquery = new WP_Query($headline);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
        $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Headline / Sold Out</span></h3>
        <?php } ?>
        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>

          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php // video query ?>

  <?php $video = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Video',
        	);

$myquery = new WP_Query($video);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Video / Sold Out</span></h3>
        <?php } ?>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>

          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php // breakfast query ?>

  <?php $breakfast = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Breakfast',
        	);

$myquery = new WP_Query($breakfast);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Breakfast</span></h3>
        <?php } ?>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>

          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php // lunch query ?>

  <?php $lunch = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Lunch',
        	);

$myquery = new WP_Query($lunch);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Lunch / Sold Out</span></h3>
      <?php } ?>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>

          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php // happy hour query ?>

  <?php $happyhour = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Happy Hour',
        	);

$myquery = new WP_Query($happyhour);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

      <?php if($current_post == 1) { ?>
        <h3 class="sponsor"><span>Happy Hour</span></h3>
      <?php } ?>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>

          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php // lanyard query ?>

  <?php $lanyard = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Lanyard',
        	);

$myquery = new WP_Query($lanyard);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Lanyard / Sold Out</span></h3>
        <?php } ?>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>

          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php // coffee query ?>

  <?php $coffee = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Coffee',
        	);

$myquery = new WP_Query($coffee);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Coffee</span></h3>
        <?php } ?>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>

          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php // pre-party query ?>

  <?php $preparty = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Pre-Party',
        	);

$myquery = new WP_Query($preparty);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Pre-Party</span></h3>
        <?php } ?>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>

          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php // friend query ?>

  <?php $friend = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Friend',
        	);

$myquery = new WP_Query($friend);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Friend</span></h3>
        <?php } ?>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>

          </div>
        </div>
      <hr />
    <?php
  endwhile;
endif;
?>

<?php wp_reset_postdata(); ?>

<?php // media query ?>

  <?php $media = array(
        	'post_type' => 'sponsor',
        	'orderby' => 'date',
        	'order' => 'ASC',
        	'meta_key' => 'sponsor_type',
        	'meta_value' => 'Media',
        	);

$myquery = new WP_Query($media);
  if( $myquery->have_posts() ) :
    while( $myquery->have_posts() ) : $myquery->the_post();
      $current_post = $myquery->current_post + 1; ?>

        <?php if($current_post == 1) { ?>
          <h3 class="sponsor"><span>Media</span></h3>
        <?php } ?>

        <div class="row">
          <div class="col-md-6 col-xs-12">
            <a target="_blank" href="<?php the_field('sponsor_url')?>">
              <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
            </a>
          </div>

          <div class="col-md-6 col-xs-12">
            <h3><?php the_title(); ?></h3>
              <?php echo the_content(); ?>
              
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
