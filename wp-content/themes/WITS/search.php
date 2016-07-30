<?php get_header(); ?>
<section class="container">
  <div class="row">

    <?php if ( have_posts() ) : ?>

      <header class="page-header">
        <h2><?php printf( __( 'Search Results for: %s', 'twentyfifteen' ), get_search_query() ); ?></h2>
      </header><!-- .page-header -->

      <?php
      // Start the loop.
      while ( have_posts() ) : the_post(); ?>

        <?php
        /*
         * Run the loop for the search to output the results.
         * If you want to overload this in a child theme then include a file
         * called content-search.php and that will be used instead.
         */
        ?>
        <br /><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <br /><p><?php the_content(); ?></p>

    <?php  // End the loop.
    endwhile; ?>

  <?php endif; ?>


</div>
</section>
<?php get_footer(); ?>
