<?php get_header(); ?>

<?php if(have_posts()): while(have_posts()): the_post(); ?>

  <section class="container">
    <?php the_content(); ?>
  </section>

<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
