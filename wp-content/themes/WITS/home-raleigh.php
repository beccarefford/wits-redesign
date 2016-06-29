<?php
/** Template Name: Raleigh Home */
?>

<?php get_header(); ?>

	<section class="container banner">
	<div class="landing" style="background-image:
    url('/wp-content/uploads/2015/11/Philadelphia-Skyline-ETE.jpg')">
			<h1>Raleigh</h1>
			<div class="row">
				<div class="col-md-12">
					<div class="box">
						<div>
							<!--<span class="sub">WHEN</span>-->
							<span class="title"></span> <span class="desc">April 9, 2016</span>
						</div>
						<div>
							<!--<span class="sub">WHERE</span>-->
							<span class="title">Raleigh, NC</span> <span class="desc"></span>
						</div>
						<div>
							<a href="/session/" class="btn btn-primary btn-header">View the Sessions</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="container headline">
		<div class="row">
			<div class="col-md-7">
				<h2>Inspire. Educate. Connect.</h2>
				<p>The Women in Tech Summit inspires, educates and connects
					women in the technology industry. Join us for a unique combination of deep-dive, hands-on tech
				workshops; information and discussions about careers in tech and
				how to pursue them; and connection and networking opportunities with
				other women in various aspects and careers in technology.</p>
			</div>
			<div class="col-md-5">
				<a target="_blank" href="http://chariotsolutions.com">
				<img class="left" src="<?php echo get_stylesheet_directory_uri(); ?>/images/chariot-presented-by.jpg"
			alt="Presented by Chariot Solutions" /></a>
			</div>
		</div>
        </section>

	<section class="container keynote">
        <h2>Keynote Speakers</h2>
	<div class="row">
		<?php
wp_reset_postdata();
$keynote = array(
			'post_type' => 'raleigh_speaker',
			'orderby' => 'date',
			'order' => 'DESC',
			'key' => 'keynote',
			'posts_per_page' => -1,
			'value' => '1',
			'compare' => '=='
		);

$myquery = new WP_Query($keynote);
	if( $myquery->have_posts() ) :
		while( $myquery->have_posts() ) : $myquery->the_post();
			if (get_field('raleigh_keynote')) { ?>
		<div class="col-md-6">
			<div class="col-md-5">
				<center>
					<div class="circular-image-keynote">
						<a href="<?php the_permalink(); ?>"> <?php echo get_the_post_thumbnail($post->ID, 'medium'); ?>
					</div>
					<h3><?php echo the_title(); ?></h3>
					</a>
				</center>
			</div>
			<div class="col-md-7">
				<?php $excerpt = get_the_excerpt();
			            $permalink = get_permalink(); ?>
				<div class="speaker-job-title"><?php echo the_field( 'job_title' ); ?></div>
				<p><?php echo wpse_custom_excerpts($excerpt, 35, $permalink); ?></p>
			</div>
		</div>
		<?php	}
endwhile;
endif;
wp_reset_postdata(); ?>
	</div>
</section>


<!--Start Speaker Carousel-->
<section class="jumbotron widget">
<div class="container">
<?php
    $loop_speakers = new WP_Query( array(
    'post_type' => 'raleigh_speaker',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'DESC'
    ) );
    ?>
	<div class="container">
		<h2><a href="/speaker">Our Speakers</a></h2>
		<div class="speakers carousel slide" data-ride="carousel" data-interval="false" id="speakers-carousel">
			<a class="left carousel-control" href="#myCarousel" data-target="#speakers-carousel" role="button" data-slide="prev"> <span class="icon-arrow-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a class="right carousel-control" href="#myCarousel" data-target="#speakers-carousel" role="button" data-slide="next"> <span class="icon-arrow-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> <!-- Indicators -->

			<?php $speaker_rows = ceil( $loop_speakers->found_posts / 3 ); ?>

			<!-- Indicators -->
			<ol class="carousel-indicators">
				<?php for( $i=0; $i<$speaker_rows; $i++ ): ?>

					<li data-target="#speakers-carousel" data-slide-to="<?php echo($i); ?>" class="<?php if( $i == 0 ): ?>active<?php endif; ?>"></li>

				<? endfor; ?>
			</ol>
			<div class="carousel-inner">
			<?php
				$line_count = 0;
				while ( $loop_speakers->have_posts() ) : $loop_speakers->the_post();
			?>
				<?php if ( $line_count == 0 ): ?>
					<div class="item <?php if($loop_speakers->current_post == 0) { echo "active"; } ?>">
				<? endif; ?>
					<div class="speaker">
						<a class="speaker-inner" href="<?php the_permalink() ?>"> <span class="photo"><?php echo get_the_post_thumbnail($post->ID, 'medium'); ?> </span><span class="name"><span class="text-fit"><span class="text-fit-inner"><?php echo the_title(); ?></span></span></span><span class="description"><?php the_field( 'job_title' ); ?></span></a>
					</div>

				<?php $line_count++; ?>

				<?php if ( $line_count == 3 ): $line_count = 0; ?>
					</div>
				<?php endif; ?>

			<?php endwhile; ?>

			<?php if ( $line_count > 0 ): ?>
				</div>
			<?php endif; ?>

			</div>
		</div>
	</div>
   </div>
</section>
<!--End Speaker Carousel-->



<!-- Start Sponsors -->
<div id="tile_sponsors" class="container widget">
	<h2><a href="/sponsor">Sponsors</a></h2>
	<a href="/sponsor" class="btn btn-primary btn-header pull-right hidden-xs"> View All Sponsors </a> <br/>
	<h3 class="sponsor"><span>Headline</span></h3>
			<div class="row">
				<div class="col-md-6 col-xs-12">
					<center>
						<a href="http://www.emoneyadvisor.com/" title="eMoney Advisor" target="_blank"><img width="250" height="111" src="/wp-content/uploads/2015/11/eMoney_Logo.jpeg" class="attachment-full wp-post-image" alt="Hortonworks"></a>
					</center>
				</div>

				<div class="col-md-6 col-xs-12">
					<center>
						<a href="http://www.chariotsolutions.com" title="Chariot Solutions" target="_blank"><img width="250" height="112" src="/wp-content/uploads/2015/11/chariot-presented-by.jpg" class="attachment-full wp-post-image" alt="Chariot Solutions"></a>
					</center>
				</div>
			</div>

	<!-- .sponsors .sponsors-lg -->
	<div class="text-center visible-xs">
		<a href="/sponsors" class="btn btn-primary btn-header"> View All Sponsors </a>
	</div>
</div>
<!-- End Sponsors -->

<?php get_footer(); ?>
