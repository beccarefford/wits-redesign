<?php
/**
 * Displays the index section of the theme.
 *
 * @package Theme Horse
 * @subpackage Interface
 * @since Interface 1.0
 */
?>
<?php get_header(); ?>

	<section class="container banner-home">
	<div class="landing" style="background-image:
		url('/wp-content/uploads/2016/07/Screen-Shot-2016-07-24-at-2.26.42-PM.png')">
			<div class="landing-home">
				<h1>The Women In Tech Summit</h1>
				<p>Inspire. Educate. Connect.</p>
			</div>
	</div>
	</section>

<div class="home">
	<div class="row">
	<div class="col-md-3">
		<a href="/washington">
		<div style="padding: 50px; margin-top: -10px; color: rgb(255, 255, 255);
		min-height: 260px; max-height: 260px;background-color: #3C323B;" class="panel-widget-style"
		onmouseover="this.style.background='#9E1B42';"
		onmouseout="this.style.background='#3C323B';">
			<center>
				<div class="state">y</div>
				<h2><font style="color:#fff;">Washington DC</font></h2>
				<p>
					March 24, 2017
				</p>
			</center>
		</div>
		</a>
	</div>

	<div class="col-md-3">
		<a href="/philadelphia">
		<div style="padding: 50px; margin-top: -10px; color: rgb(255, 255, 255);
		min-height: 260px;max-height: 260px;background-color: #1D181C;" class="panel-widget-style"
		onmouseover="this.style.background='#9E1B42';"
		onmouseout="this.style.background='#1D181C';">
			<center>
				<div class="state">l</div>
				<h2><font style="color:#fff;">Philadelphia</font></h2>
				<p>
					April 21 & 22, 2017
				</p>
			</center>
		</div>
		</a>
	</div>

	<div class="col-md-3">
		<a href="/boston_home">
		<div style="padding: 50px; margin-top: -10px; color: rgb(255, 255, 255);
		min-height: 260px; max-height: 260px; background-color: #2A2329;" class="panel-widget-style"
		onmouseover="this.style.background='#9E1B42';"
		onmouseout="this.style.background='#2A2329';">
			<center>
				<div class="state">s</div>
				<h2><font style="color:#fff;">Boston</font></h2>
				<p>
					July 22, 2017
				</p>
			</center>
		</div>
	</a>
	</div>

	<div class="col-md-3">
		<a href="/#">
		<div style="padding: 50px; margin-top: -10px; color: rgb(255, 255, 255);
		min-height: 260px; max-height: 260px;background-color: #493D48;" class="panel-widget-style"
		onmouseover="this.style.background='#9E1B42';"
		onmouseout="this.style.background='#493D48';">
			<center>
				<i class="fa fa-gift fa-5x" aria-hidden="true"></i>
				<h2><font style="color:#fff;">All Cities</font></h2>
				<p>
					5+ events
				</p>
			</center>
		</div>
		</a>
	</div>
</div>
</div>

<?php /*

	<section class="container keynote">
        <h2>Keynote Speakers</h2>
	<div class="row">
		<?php
wp_reset_postdata();
$keynote = array(
			'post_type' => 'speaker',
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
			if (get_field('keynote')) { ?>
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
    'post_type' => 'speaker',
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
<!-- End Sponsors --> */ ?>

<section class="container headline">
	<div class="row">
		<div class="col-md-7">
			<h2>Inspire. Educate. Connect.</h2>
			<p>The Women in Tech Summit inspires, educates and connects
				women in the technology industry. Join us for a unique combination of deep-dive, hands-on tech
			workshops; information and discussions about careers in tech and
			how to pursue them; and connection and networking opportunities with
			other women in various aspects and careers in technology.</p>

			<p>All profits generated
			from each event support the non-profit <strong><a target="_blank" href="http://www.techgirlz.org">TechGirlz.org</a></strong>
			and its ongoing mission to inspire middle school girls
			to explore the possibilities of technology to empower their
			future careers.</p>
		</div>
		<div class="col-md-5">
		<center>
			<h2>Call For Papers is now open!</h2>
			<a target="_blank" href="https://goo.gl/forms/VpqSiN6c8NTYU9mA3">
				<div class="btn-primary">
					Submit A Talk
				</div>
			</a>
		</center>
		&nbsp;
		&nbsp;
			<!-- Begin MailChimp Signup Form -->
		<center>
			<strong>Stay up-to-date with future events.</strong>
				<a target="_blank" href="http://womenintechsummit.us4.list-manage.com/subscribe?u=cd7267f1d913ce62c1b8f5b7b&id=d193e8ea2c">
					<div class="btn-primary">
						Sign Up for our mailing list
					</div>
				</a>
			</center>
		</div>
	</div>
</section>

<?php get_footer(); ?>
