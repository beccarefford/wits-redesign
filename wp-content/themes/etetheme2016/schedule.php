<?php
/*
Template Name: Schedule
*/
?>

<?php get_header(); ?>

<section class="container">

<?php
// collection keyed by speaker id, currently holds title only
$speakers_by_id = get_speakers_by_id();

// setup the key lookups and the timeslot buckets
$salon_lookup_table = generate_salon_lookups();
$time_print_buckets = generate_buckets($salon_lookup_table);


$prior_time_slot = 0;
$first_iteration = true;
$loop_sessions = new WP_Query(array(
    $schedule_entry = $wp_query->get_queried_object(),
    'meta_key' => 'date',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'post_type' => 'session',
    'posts_per_page' => -1
));

while ($loop_sessions->have_posts()) : $loop_sessions->the_post();

    $current_time_slot = get_field('date');

    if ($first_iteration == true) {
        $first_iteration = false;
        $prior_time_slot = $current_time_slot;
    }

    // Do we need to reset the buckets?
    if ($prior_time_slot !== $current_time_slot) {

        print_salons($prior_time_slot, $salon_lookup_table, $time_print_buckets);
        reset_slots($time_print_buckets);
        // now overwrite
        $prior_time_slot = $current_time_slot;
    }


    // TODO : if there is no location (exactly 1) we skip this for the calendar...  Maybe warn
    $room_key = get_the_terms(get_the_ID(), 'location')[0]->term_id;

    // PHP WEIRDNESS HERE
    // Apparently this makes a copy of the array to give to us - because
    // without re-setting the copy as the value for the room key, we don't
    // get the changes back to the original collection.  THIS IS WHY KEN
    // HATES PHP!  Non-obvious side-effects.
    $current_print_bucket =& $time_print_buckets[$room_key];

    $current_print_bucket['session_title'] = $post->post_title; //get_the_title();

    $speakers = array();

    // get the speakers
    $speaker_ids = get_field('speaker_photo', false, false);
    foreach($speaker_ids as $speaker_id) {
        array_push($speakers, $speakers_by_id[strval($speaker_id)]);
    }

    $current_print_bucket['speakers'] = $speakers;
    $current_print_bucket['tags'] = get_the_terms($post, 'tags');
    $current_print_bucket['session_permalink'] = get_permalink($post);
    $timestamp = get_field('date');
    $timestamp_plus60 = get_field('date') + 3600;
    $current_print_bucket['time'] = date('g:i A', $timestamp);
    $current_print_bucket['endtime'] = date('g:i A', $timestamp_plus60);
    $current_print_bucket['keynote'] = get_field('keynote');

    // todo - fetch other non-session post data
    $current_print_bucket['is_empty'] = 0;
    /* print_r($current_print_bucket); */

endwhile;

print_salons($prior_time_slot, $salon_lookup_table, $time_print_buckets);

// emit the last row
//print_salons($last_time_slot, $salons);


function print_salons($time, $salon_lookup_table, $salon_buckets)
{ ?>

<div class="sessions-list">
<div class="followWrap" style="height: 60px;">
  <div class="day-floating">
    <?php $time_plus60 = $time + 3600; ?>
      <span><?php echo(gmdate('F d, Y', $time)); ?> &nbsp; &bull; &nbsp;
      <?php echo(date('g:i A', $time)) ?> - <?php echo(date('g:i A', $time_plus60)); ?></span>
  </div>
</div>
</div>

    <?php foreach ($salon_lookup_table as $key => $lookup) {
        print_salon($salon_buckets[$key]);
    } ?>
<?php }

function print_salon($salon)
{ ?>

<?php if ($salon['is_empty'] === 0) { ?>
<div class="session-entry">
  <div class="row">
    <div class="col-md-12">
    <h3><?php echo($salon['salon']) ?> -
    <a href="<?php echo($salon['session_permalink'])?>">
      <?php echo($salon['session_title']); ?></a><br />
    </h3>
    <?php foreach($salon['speakers'] as $speaker) { ?>
      <div class="speaker_thumbs">
        <a href="<?php echo($speaker['speaker_permalink']) ?>">
          <div class="circular-image-schedule">
            <?php echo($speaker['speaker_photo']); ?></a>
            <?php echo($speaker['speaker_name']); ?>
          </div>
      </div>
      <?php } ?>
      <?php if($salon['tags'] !== false) { ?>
        <?php foreach($salon['tags'] as $tag) { ?>
          <a href="<?php echo get_tag_link($tag); ?>"><div class="btn"><?php echo($tag->name); ?></div></a>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
    <?php foreach($salon['speakers'] as $speaker) { ?>
      <?php if($speaker['keynote'] === true) { ?>
       <?php } else { ?>
          <hr />
         <?php } ?>
    <?php } ?>
  <?php } ?>
<?php } ?>

<?php

function generate_salon_lookups() {
    $taxonomy = 'location';
    // first, grab all salons in ascending order, and hold for matching with sessions
    $terms = get_terms($taxonomy, array(
        // hide_empty queries ALL terms, even those that are not attached to any posts
        "hide_empty" => 0,
        "orderby" => "name",
        "search" => "Salon",
        "order" => "ASC"));

    $salon_lookup_table = array();

    $id = 0;
    foreach ($terms as $term) {
        $salon_lookup_table[strval($term->term_id)] =
            array(
                'term_idx' => $id,
                'term_name' => $term->name);
        $id = $id + 1;
    }
    return $salon_lookup_table;
}

// todo - extract all of the config into a structure in config files
function generate_buckets(&$salon_lookup_table) {
    // create lookup collection that holds
    $salon_print_buckets = array();
    foreach ($salon_lookup_table as $term_key => $term) {
        $salon_info = array(
            'salon_idx' => $term['term_idx'],
            'salon' => $term['term_name'],
            'is_empty' => 1);

        $salon_print_buckets[strval($term_key)] = $salon_info;
    }
    return $salon_print_buckets;
}

// clear the buckets when time switches
function reset_slots(&$salon_buckets)
{
        foreach ($salon_buckets as &$salon) {
            // add back the salon, etc...
            unset($salon['speakers']);
            unset($salon['tags']);
            unset($salon['session_permalink']);
            unset($salon['time']);
            unset($salon['endtime']);
            /* echo('<pre>');
            var_dump($salon);
            echo('</pre>'); */
            $salon['is_empty'] = 1;
        }
}

function get_speakers_by_id() {

    $options = array(
        'post_type' => 'speaker',
        'posts_per_page' => -1
    );

    $speakers_query = new WP_Query($options);
    $speakers_by_id = array();
    while ($speakers_query->have_posts()): $speakers_query->the_post();
        $speaker_data = array(
            'speaker_name' => get_the_title(),
            'speaker_permalink' => get_the_permalink(),
            'speaker_photo' => get_the_post_thumbnail(get_the_ID(), 'thumbnail'),
            'keynote' => get_field('keynote')
        );
        $speaker_id = strval(get_the_ID());
        if($speaker_id) {
            $speakers_by_id[$speaker_id] = $speaker_data;
        }
    endwhile;
  return $speakers_by_id;
}

?>
</section>
<?php get_footer(); ?>
