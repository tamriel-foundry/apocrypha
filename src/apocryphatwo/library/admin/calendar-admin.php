<?php
/**
 * Apocrypha Content Calendar Admin Functions
 * Andrew Clayton
 * Version 0.2
 * 7-24-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;




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