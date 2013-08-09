<?php
/**
 * Apocrypha Content Calendar Admin Functions
 * Andrew Clayton
 * Version 0.2
 * 7-24-2013
 */

 
/**
 * Customize backend messages when an event is updated.
 * @since 0.1
 */
add_filter( 'post_updated_messages', 'event_updated_messages' );
function event_updated_messages( $event_messages ) {
	global $post, $post_ID;
	
	// Set some simple messages for editing slides, no post previews needed.
	$event_messages['event'] = array( 
		0	=> '',
		1	=> 'Event updated.',
		2	=> 'Custom field updated.',
		2	=> 'Custom field deleted.',
		4	=> 'Event updated.',
		5	=> isset($_GET['revision']) ? sprintf( 'Event restored to revision from %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6	=> 'Event added to calendar.',
		7	=> 'Event saved.',
		8	=> 'Event added to calendar.',
		9	=> sprintf( 'Event scheduled for: <strong>%1$s</strong>.' , strtotime( $post->post_date ) ),
		10	=> 'Event draft updated.',
	);
	return $event_messages;
}

 /**
 * Add custom event meta boxes.
 * @since 0.1
 */
add_action( 'admin_menu', 'add_event_meta_boxes' );
function add_event_meta_boxes() {
	add_meta_box( 'event-details', 'Event Details', 'event_details_box', 'event', 'normal', 'high' );
	add_meta_box( 'event-recurrence', 'Event Recurrence', 'event_recurrence_box', 'event', 'normal', 'high' );
}

 /**
 * Display custom event details box.
 * @since 0.1
 */
function event_details_box( $object , $box ) {
	wp_nonce_field( basename( __FILE__ ) , 'event-details-box' ); ?>
	<p>
		<label for="event-date">Event Date</label><br/>
		<input type="date" name="event-date" value="<?php echo get_post_meta( $object->ID , 'event_date' , true ); ?>" tabindex="10" />
	</p>
	<p>
		<label for="event-start">Start Time</label><br/>
		<input type="time" name="event-start" value="<?php echo get_post_meta( $object->ID , 'event_start' , true ); ?>" tabindex="10" />
	</p>
	<p>
		<label for="event-end">End Time</label><br/>
		<input type="time" name="event-end" value="<?php echo get_post_meta( $object->ID , 'event_end' , true ); ?>" tabindex="10" />
	</p>
	<p>
		<label for="event-capacity">Event Capacity?</label>
		<input type="number" name="event-capacity" value="<?php echo get_post_meta( $object->ID , 'event_capacity' , true ); ?>" tabindex="10" />
	</p>
	<p>
		<input type="checkbox" name="event-require-rsvp" value="true" <?php checked( get_post_meta( $object->ID , 'event_require_rsvp' , true ) , 'true' ); ?> tabindex="10" />
		<label for="event-require-rsvp">Require RSVP?</label>
	</p>
	<p>
		<input type="checkbox" name="event-require-role" value="true" <?php checked( get_post_meta( $object->ID , 'event_require_role' , true ) , 'true' ); ?> tabindex="10" />
		<label for="event-require-role">Request preferred role?</label>
	</p>
<?php 	
}

 /**
 * Display custom event recurrence settings.
 * @since 0.1
 */
function event_recurrence_box( $object , $box ) {

	// Don't allow recurrence settings to be changed for clones
	$is_clone = has_term( 'clone' , 'recurrence' , $object->ID ); 
	if ( $is_clone ) : ?>
		<p>This is a recurring event clone, you can only change the recurrence settings on the base event which spawned it.</p>
	<?php else :
		$event_recur_days = get_post_meta( $object->ID , 'event_recurrence' , true );
		if ( '' == $event_recur_days ) $event_recur_days = array(); ?>
		<p>
			<label for="event-require-rsvp">Repeat Event Weekly On:</label><br/><br/>
			<input type="checkbox" name="event-recurrence[]" value="sun" <?php checked( in_array( 'sun' , $event_recur_days ) , 1 ); ?> tabindex="20" /> Sunday<br/>
			<input type="checkbox" name="event-recurrence[]" value="mon" <?php checked( in_array( 'mon' , $event_recur_days ) , 1 ); ?> tabindex="20" /> Monday<br/>
			<input type="checkbox" name="event-recurrence[]" value="tue" <?php checked( in_array( 'tue' , $event_recur_days ) , 1 ); ?> tabindex="20" /> Tuesday<br/>
			<input type="checkbox" name="event-recurrence[]" value="wed" <?php checked( in_array( 'wed' , $event_recur_days ) , 1 ); ?> tabindex="20" /> Wednesday<br/>
			<input type="checkbox" name="event-recurrence[]" value="thr" <?php checked( in_array( 'thr' , $event_recur_days ) , 1 ); ?> tabindex="20" /> Thursday<br/>
			<input type="checkbox" name="event-recurrence[]" value="fri" <?php checked( in_array( 'fri' , $event_recur_days ) , 1 ); ?> tabindex="20" /> Friday<br/>
			<input type="checkbox" name="event-recurrence[]" value="sat" <?php checked( in_array( 'sat' , $event_recur_days ) , 1 ); ?> tabindex="20" /> Saturday<br/>
		</p>
	<?php endif; ?>
<?php 	
}

/**
 * Save the event settings as postmeta.
 * @since 0.1
 */
// Save the post SEO meta box data on the 'save_post' hook
add_action( 'save_post'			, 'save_event_details_meta'	, 10, 2 );
add_action( 'add_attachment'	, 'save_event_details_meta' );
add_action( 'edit_attachment'	, 'save_event_details_meta' );
function save_event_details_meta( $post_id , $post = '' ) {
	
	// Verify the nonce before proceeding.
	if ( !isset( $_POST['event-details-box'] ) || !wp_verify_nonce( $_POST['event-details-box'], basename( __FILE__ ) ) )
		return $post_id;
		
	// Define the meta to look for
	$meta = array(
		'event_date'			=> $_POST['event-date'],
		'event_start'			=> $_POST['event-start'],
		'event_end'				=> $_POST['event-end'],
		'event_require_rsvp'	=> $_POST['event-require-rsvp'],
		'event_require_role'	=> $_POST['event-require-role'],
		'event_recurrence'		=> $_POST['event-recurrence'],
		'event_capacity'		=> $_POST['event-capacity'],
	);
	
	foreach ( $meta as $meta_key => $new_meta_value ) {
	
		// Get the meta value of the custom field key.
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		// If there is no new meta value but an old value exists, delete it.
		if ( current_user_can( 'delete_post_meta', $post_id, $meta_key ) && '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );

		// If a new meta value was added and there was no previous value, add it.
		elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		// If the new meta value does not match the old value, update it.
		elseif ( current_user_can( 'edit_post_meta', $post_id, $meta_key ) && $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );
	}
}

/**
 * Add group association to calendar taxonomies.
 * @since 0.2
 */
function new_calendar_meta_box() { ?>

	<div>
		<label for="is-group-calendar"><input type="checkbox" name="is-group-calendar" value="true"> This calendar is for a BuddyPress group.</label>
		<p class="description">A calendar may be associated with a BuddyPress group by assigning it the same slug as the slug of the group.</p>	
	</div>
	
<?php }
add_action( 'calendar_add_form_fields', 'new_calendar_meta_box', 10, 2 );

/**
 * Edit calendar meta box
 * @since 0.2
 */
function edit_calendar_meta_box( $term ) { 

		
	// Get any existing value
	$term_meta = get_option( 'taxonomy_' . $term->term_id ); ?>
	
	<tr>
		<th scope="row" valign="top"><label for="is-group-calendar">This calendar is associated with a BuddyPress group.</label></th>
		<td>
			<input type="checkbox" name="is-group-calendar" value="true" <?php checked( $term_meta['is_group_calendar'] , 'true' ); ?>>
			<p class="description">A calendar may be associated with a BuddyPress group by assigning it the same slug as the slug of the group.</p>	
		</td>
	</tr>
	
<?php }
add_action( 'calendar_edit_form_fields', 'edit_calendar_meta_box' , 10 , 2  );

/**
 * Save custom calendar taxonomy.
 * @since 0.2
 */
function save_calendar_taxonomy_meta( $term_id ) {
	
	$term_meta 	= get_option( "taxonomy_$term_id" );
	
	// If it has a value, update the option
	if ( isset( $_POST['is-group-calendar'] ) ) {
		$term_meta['is_group_calendar'] = $_POST['is-group-calendar'];
		update_option( "taxonomy_$term_id", $term_meta );
	}
	
	// Otherwise, if it had a value, remove it
	elseif ( !empty( $term_meta ) )
		delete_option( "taxonomy_$term_id" );
} 
add_action( 'edited_calendar', 'save_calendar_taxonomy_meta', 10, 2 );  
add_action( 'create_calendar', 'save_calendar_taxonomy_meta', 10, 2 );


/**
 * Define event taxonomies when the event is saved
 * @since 0.1
 */
add_action( 'save_post'	, 'set_event_taxonomies' , 11, 2 );
function set_event_taxonomies( $post_id , $post ) {
	
	// Only worry about this for events
	if ( 'event' != $post->post_type )
		return;
	
	// Figure out what we are working with
	$is_single 		= empty( $_POST['event-recurrence'] );
	$is_clone		= has_term( 'clone' , 'recurrence' , $post_id );
	
	// Categorize the target term
	if ( $is_clone ) :
		$term = term_exists( 'clone' , 'recurrence' );
	elseif ( $is_single ) :
		$term = term_exists( 'single' , 'recurrence' );
	else :
		$term = term_exists( 'recurring' , 'recurrence' );
	endif;

	// Make sure the term is set, and overwrite any previous setting
	wp_set_post_terms( $post_id, $term['term_id'] , 'recurrence' , false );
}

 
/**
 * Configure the display of events page
 * @since 0.1
 */
add_filter( 'manage_edit-event_columns', 'events_edit_columns' );
function events_edit_columns( $columns ) {
	$columns = array(		
		'cb'			=> '<input type="checkbox" />',
		'title'			=> 'Event Name',
		'calendar'		=> 'Calendar',
		'recur'			=> 'Recurrence',
		'event_date'	=> 'Date',
		'event_time'	=> 'Time',
	);
	return $columns; 
}
add_action( 'manage_event_posts_custom_column', 'events_custom_columns' );
function events_custom_columns( $columns ) {
	global $post;
	switch ( $columns ) {		
		case 'calendar' :
			echo get_the_term_list( $post->ID , 'calendar' );
		break;
		
		case 'recur' :	
			echo get_the_term_list( $post->ID , 'recurrence' );
		break;
		
		case 'event_date' :	
			$meta_date 		= get_post_meta( $post->ID , 'event_date' , true );
			$display_date 	= date('l, F j', strtotime( $meta_date ) );
			echo $display_date;
		break;
		
		case 'event_time' :	
			$meta_time 		= get_post_meta( $post->ID , 'event_start' , true );
			$display_time 	= date('g:i a', strtotime( $meta_time ) );
			echo $display_time;
		break;
	}
}
 

?>