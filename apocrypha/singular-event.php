<?php 
/**
 * Apocrypha Theme Single Event Template
 * Andrew Clayton
 * Version 1.0
 * 7-25-2013
 */
 
/* Who is it? */
$user_id 	= get_current_user_id();

/* Get some information about the event */
global $post;
$post_id = $post->ID;

/* Get some information on the calendars for which this event is posted */
$calendars = wp_get_post_terms( $post_id , 'calendar' );

/* Find the first calendar to which the user has access */
$can_view = false;
foreach ( $calendars as $calendar ) {
	
	/* Is it a group calendar? */
	$term_id 	= $calendar->term_id;
	$slug		= $calendar->slug;
	if ( is_group_calendar( $term_id ) ) :
		$group_id	= groups_get_id( $slug );
		$can_view 	= groups_is_user_member( $user_id , $group_id ) ? true : false;
	else :
		$can_view = true;
	endif;
	
	/* If we find a calendar where the user is authorized, stop */
	if( true == $can_view ) :
		global $allowed_calendar;
		$header 	= ( 'entropy-rising' == $slug ) ? 'entropy_rising_header' 	: 'get_header';
		$sidebar 	= ( 'entropy-rising' == $slug ) ? 'entropy_rising_sidebar' 	: 'apoc_primary_sidebar';
		$allowed_calendar = $calendar;
		break;
	endif;
}

/* If the user is not authorized to view any of the calendars, redirect them */
if ( !$can_view ) :
	$default_slug = $calendars[0]->slug;
	if ( 'entropy-rising' == $slug ) $redirect	= SITEURL . '/entropy-rising/';
	else 	$redirect	= SITEURL . '/groups/' . trailingslashit( $slug );

	bp_core_add_message( 'You cannot access events on this calendar.' , 'error' );
	bp_core_redirect( $redirect );	
endif;



/* OK, now we're in the clear to display the event */
$action_url = get_permalink();
$title 		= $post->post_title;
$content 	= $post->post_content;
$capacity	= get_post_meta( $post_id , 'event_capacity' , true );
$capacity	= ( '' == $capacity ) ? 9999 : $capacity;
$cap_label	= ( 9999 == $capacity ) ? '&infin;' : $capacity;
$req_rsvp	= get_post_meta( $post_id , 'event_require_rsvp' , true );
$req_role	= get_post_meta( $post_id , 'event_require_role' , true );
$url		= get_post_permalink();

/* Get the RSVPS and sort them alphabetically */
$rsvps		= get_post_meta( $post_id , 'event_rsvps' , true );
if( !empty( $rsvps ) ) :
	$names = array();
	foreach ($rsvps as $uid => $info) {
		$name = bp_core_get_user_displayname( $uid );
		$link = bp_core_get_userlink( $uid );
		$rsvps[$uid]['name'] = $name;
		$rsvps[$uid]['link'] = $link;
		$names[$uid] = strtolower( $name );
	}
	array_multisort($names, SORT_STRING, $rsvps);
else : $rsvps = array();
endif;

/* Lastly check if the RSVP was submitted, and verify the nonce */
if ( isset ( $_POST['submit'] ) && wp_verify_nonce( $_POST['event_rsvp_nonce'] , 'event-rsvp' ) ) :
	
	$new_rsvp = array();
	
	/* Clean each field individually */
	if( !isset ( $_POST['attendance'] ) )
		$error = 'You must select your expected attendance!';
	else
		$new_rsvp['rsvp'] = $_POST['attendance'];
		
	if( $req_role && empty( $_POST['rsvp-role'] ) && $_POST['attendance'] != 'no' )
		$error = 'You must select your preferred role!';
	elseif( $req_role )
		$new_rsvp['role'] = $_POST['rsvp-role'];
		
	if( isset ( $_POST['rsvp-comment'] ) )
		$new_rsvp['comment'] = sanitize_text_field( $_POST['rsvp-comment'] );
		
		
	/* If there are no errors, we can save the stuff */
	if ( !$error ) :
		
		/* Update the postmeta */
		$rsvps[$user_id] = $new_rsvp;
		update_post_meta( $post_id, 'event_rsvps' , $rsvps );
		
		
		/* Flush the post cache */
		if ( function_exists( 'w3tc_pgcache_flush_post' ) ) w3tc_pgcache_flush_post( $post_id );
		
		/* Say Thanks */
		bp_core_add_message( 'Thank you for responding!' );

		/* Clear notifications on each group calendar */
		foreach ( $calendars as $calendar ) {
			if ( is_group_calendar( $calendar->term_id ) ) :
				global $bp;
				$group_id	= groups_get_id( $calendar->slug );
				bp_core_delete_notifications_by_item_id( $user_id , $group_id , $bp->groups->id , 'new_calendar_event' , $post_id );
			endif;
		}
	
	/* Otherwise, throw the error message */
	else :
		bp_core_add_message( $error	, 'error' );
	endif;
endif;

/* Count Attendance */
$confirmed 	= 0;
$maybe 		= 0;
$declined 	= 0;
foreach ( $rsvps as $response ) {
	if ( 'yes' == $response['rsvp'] )
		$confirmed++;
	elseif ( 'maybe' == $response['rsvp'] )
		$maybe++;
	elseif ( 'no' == $response['rsvp'] )
		$declined++;
}

/* User Response */
if ( isset( $rsvps[$user_ID] ) ) :
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
	}
else :
	$rsvp = "RSVP";
endif;

/* Date */
$event_date	= get_post_meta( $post_id , 'event_date' , true );
$fulldate	= date('l F j, Y', strtotime( $event_date ) );

/* Time */
$start		= get_post_meta( $post_id , 'event_start' , true );
$end		= get_post_meta( $post_id , 'event_end' , true );
$fulltime 	= date('g:i', strtotime( $start ) ) . ' - ' . date('g:ia' , strtotime( $end ) ) . ' EST';

/* Is the Event Over? */
$end_time	= strtotime( $end ) > strtotime( $start ) ? strtotime( $end ) : strtotime ( $end . ' tomorrow' );
$is_past	= ( $end_time < strtotime( '-5 hours' ) ) ? true : false;


$header(); // Load the header contextually ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header class="entry-header <?php post_header_class(); ?>">
			<h1 class="entry-title"><?php entry_header_title(); ?></h1>
			<p class="entry-byline">This event is scheduled on the <?php echo $allowed_calendar->name; ?> calendar.</p>
		</header>

		<div class="entry-content">
			<div class="event-details">
				<h2 class="event-time double-border bottom">
					<?php echo $fulldate . ' from ' . $fulltime; ?>
				</h2>
				<div class="event-meta">
					<span class="event-attendance"><?php echo $confirmed . '/' . $cap_label; ?></span>
					<div class="event-response <?php echo $rsvps[$user_id]['rsvp']; ?>">
						<span class="event-rsvp"><?php echo $rsvp; ?></span>
					</div>
				</div>
				<div class="event-content">
					<div class="event-description"><?php echo wpautop( $content ); ?></div>
				</div>
			</div>

		
			<div class="event-respondents">
				<?php if ( $confirmed > 0 ) : ?>
				<h2 class="calendar-header">Attending (<?php echo $confirmed; ?>)</h2>
				<ul class="respondent-list attending">
					<?php foreach ( $rsvps as $uid => $response ) :
						if ( 'yes' == $response['rsvp'] ) :
							$role = isset( $response['role'] ) ? "(" . $response['role'] . ") " : "";							
							echo '<li class="event-respondent">' . implode( ' - ' , array( $response['link']  , $role .  stripslashes( $response['comment'] ) ) ) . '</li>';
						endif;
					endforeach; ?>
				</ul>
				<?php endif; ?>
				
				<?php if ( $maybe > 0 ) : ?>
				<h2 class="calendar-header">Maybe (<?php echo $maybe; ?>)</h2>
				<ul class="respondent-list attending">
					<?php foreach ( $rsvps as $responder => $response ) :
						if ( 'maybe' == $response['rsvp'] ) :
							$role = isset( $response['role'] ) ? "(" . $response['role'] . ") " : "";			
							echo '<li class="event-respondent">' . implode( ' - ' , array( $response['link']  , $role .  stripslashes( $response['comment'] ) ) ) . '</li>';
						endif;
					endforeach; ?>
				</ul>
				<?php endif; ?>
				
				<?php if ( $declined > 0 ) : ?>
				<h2 class="calendar-header">Not Attending (<?php echo $declined; ?>)</h2>
				<ul class="respondent-list attending">
					<?php foreach ( $rsvps as $responder => $response ) :
						if ( 'no' == $response['rsvp'] ) :
							echo '<li class="event-respondent">' . implode( ' - ' , array( $response['link']  , stripslashes( $response['comment'] ) ) ) . '</li>';
						endif;
					endforeach; ?>
				</ul>
				<?php endif; ?>
				
				<?php if ( 0 == count( $rsvps ) ) : ?>
				<h2 class="calendar-header">No Responses Yet!</h2>
				<?php endif; ?>
			</div>
		</div>
			
		<?php if ( !$is_past ) : ?>	
		<form action="<?php echo $action_url; ?>" name="calendar-rsvp-form" id="calendar-rsvp-form" method="post">
			<?php do_action( 'template_notices' ); ?>
			<h2 class="calendar-header">Respond to this Event</h2>

			<ol class="form">
				<li class="form-field radio">
					<label for="attendance">Expected Attendance : &#9734;</label>
					<ul class="radio-options-list">
						<?php if ( $confirmed < $capacity || ( isset( $rsvps[$user_ID] ) && 'yes' == $rsvps[$user_ID]['rsvp'] ) ) : ?>
						<li><input type="radio" name="attendance" value="yes" <?php if ( isset( $rsvps[$user_ID] ) ) checked( $rsvps[$user_ID]['rsvp'] , 'yes' ); ?>/><label for="attendance">Yes</label></li>
						<li><input type="radio" name="attendance" value="no" <?php if ( isset( $rsvps[$user_ID] ) ) checked( $rsvps[$user_ID]['rsvp'] , 'no' ); ?>/><label for="playstyle">No</label></li>
						<li><input type="radio" name="attendance" value="maybe" <?php if ( isset( $rsvps[$user_ID] ) ) checked( $rsvps[$user_ID]['rsvp'] , 'maybe' ); ?>/><label for="attendance">Maybe</label></li>
						<?php else : ?>
						<li><input type="radio" name="attendance" value="maybe" <?php if ( isset( $rsvps[$user_ID] ) ) checked( $rsvps[$user_ID]['rsvp'] , 'maybe' ); ?>/><label for="attendance">Standby</label></li>
						<li><input type="radio" name="attendance" value="no" <?php if ( isset( $rsvps[$user_ID] ) ) checked( $rsvps[$user_ID]['rsvp'] , 'no' ); ?>/><label for="playstyle">No</label></li>
						<?php endif; ?>
					</ul>
				</li>
				<?php if ( $req_role ) : ?>
				<li class="form-field select">
					<label for="rsvp-role">Preferred Role : &#9734;</label>
					<select name="rsvp-role">
						<option></option>
						<option value="tank" <?php if ( isset( $rsvps[$user_ID] ) ) selected( $rsvps[$user_ID]['role'] , 'tank' ); ?>>Tank</option>
						<option value="healer" <?php if ( isset( $rsvps[$user_ID] ) ) selected( $rsvps[$user_ID]['role'] , 'healer' ); ?>>Healer</option>
						<option value="dps" <?php if ( isset( $rsvps[$user_ID] ) ) selected( $rsvps[$user_ID]['role'] , 'dps' ); ?>>DPS</option>
						<option value="control" <?php if ( isset( $rsvps[$user_ID] ) ) selected( $rsvps[$user_ID]['role'] , 'control' ); ?>>Control</option>
					</select>
				</li>
				<?php endif; ?>
				<li class="form-field textarea">
					<label for="rsvp-comment">Comment:</label><br/>
					<textarea type="textarea" name="rsvp-comment" value="" rows="2" ><?php if ( isset( $rsvps[$user_ID] ) ) echo stripslashes( $rsvps[$user_ID]['comment'] ); ?></textarea>
				</li>		
				<li class="form-field submit">
					<?php wp_nonce_field( 'event-rsvp' , 'event_rsvp_nonce' ) ?>
					<input type="submit" name="submit" value="Submit">
				</li>
			</ol>
		</form>
		<?php endif; ?>
	</div><!-- #content -->
	
	<?php $sidebar(); // Load the sidebar contextually ?>
<?php get_footer(); // Load the footer