<?php
/**
 * Apocrypha Calendar Event Template
 * Andrew Clayton
 * Version 0.1
 * 7-25-2013
 */
 	
/* Define some variables */	
global 		$post;
$title 		= $post->post_title;
$content 	= $post->post_content;
$capacity	= get_post_meta( $post->ID , 'event_capacity' , true );
			if ( '' == $capacity ) $capacity = '&infin;';
$req_rsvp	= get_post_meta( $post->ID , 'event_require_rsvp' , true );
$req_role	= get_post_meta( $post->ID , 'event_require_role' , true );
$rsvps		= get_post_meta( $post->ID , 'event_rsvps' , true );
			if ( empty( $rsvps ) ) $rsvps = array();
$url		= get_post_permalink();

/* Date */
$event_date	= get_post_meta( $post->ID , 'event_date' , true );
$weekday	= date('l', strtotime( $event_date ) );
$monthday	= date('M j', strtotime( $event_date ) );

/* Time */
$start		= get_post_meta( $post->ID , 'event_start' , true );
$end		= get_post_meta( $post->ID , 'event_end' , true );
$eventtime 	= date('g:ia', strtotime( $start ) ) . ' EST';

/* Has it passed? */
$datetime	= $event_date . ' ' . $start;
$is_past	= ( strtotime( $datetime ) < time() ? true : false );

/* Attendance */
$confirmed = 0;
foreach ( $rsvps as $response ) {
	if ( 'yes' == $response['rsvp'] )
	$confirmed++;
}

/* User Response */
switch ( $rsvps[$user_id]['rsvp'] ) {
	case "yes" :
		$rsvp = "Attending";
		break;
	case "no" :
		$rsvp = "Absent";
		break;
	case "maybe" :
		$rsvp = "Maybe";
		break;
	default :
		$rsvp = "RSVP";
		break;
}

/* Display the event */ ?>
<li class="event">
	<div class="event-datetime">
		<span class="event-weekday"><?php echo $weekday; ?></span>
		<span class="event-monthday"><?php echo $monthday; ?></span>
		<span class="event-time"><?php echo $eventtime; ?></span>
	</div>
	<div class="event-content">
		<h3 class="double-border bottom"><a href="<?php echo $url; ?>" title="View Event" ><?php echo $title; ?></a></h3>
		<div class="event-description entry-content"><?php echo wpautop( $content ); ?></div>
	</div>
	<div class="event-meta">
		<span class="event-attendance"><?php echo $confirmed . '/' . $capacity; ?></span>
		<?php if ( !$is_past ) : ?>
		<div class="event-response <?php echo $rsvps[$user_id]['rsvp']; ?>">
			<span class="event-rsvp"><?php echo $rsvp; ?></span>
		</div>
		<?php endif; ?>
	</div>
</li>