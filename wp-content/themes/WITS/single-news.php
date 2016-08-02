<?php get_header(); ?>

<?php if(have_posts()): while(have_posts()): the_post(); ?>

<section class="container">

  <a href="/news"><strong>< Back to All Press</strong></a>

  <div class="row">
    <div class="col-md-12">
      <a href="<?php the_permalink(); ?>"><h2 style="padding-top:30px;"><?php echo the_title(); ?></h2></a>

  <p>
    <em><?php $pfx_date = get_the_date( $format, $post_id ); echo $pfx_date; ?></em>
  </p>

<p><?php echo the_content(); ?></p>

</section>
<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
