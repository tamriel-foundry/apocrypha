<?php
/**
 * Apocrypha Group Events
 * Andrew Clayton
 * Version 1.0.0
 * 10-8-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
1.0 - APOC_EVENTS CLASS
----------------------------------------------*/

/**
 * Registers the "Event" custom post type.
 * Events are used with the taxonomy "Calendar" to display a list of upcoming events.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
 
class Apoc_Events {

	/**
	 * Register the custom post and taxonomy with WordPress on init
	 * @since 0.1
	 */
	function __construct() {
	
		// Add universal actions
		add_action( 'init'						, array( $this , 'register_events' 	) );
		add_action( 'init'						, array( $this , 'register_calendar' 	) );

		// Admin-only methods
		if ( is_admin() ) {
		
			// Admin Actions
			add_action( 'admin_menu'			, array( $this , 'meta_boxes' ) );
			add_action( 'save_post'				, array( $this , 'save_event' )	, 10, 2 );

			
			// Admin Filters
			add_filter( 'post_updated_messages'	, array( $this , 'update_messages') );
		

		}
	}
	
	/**
	 * Register a custom post type for Events
	 * @version 1.0.0
	 */
	function register_events() {

		// Labels for the backend Event publisher
		$event_labels = array(
			'name'					=> 'Events',
			'singular_name'			=> 'Event',
			'add_new'				=> 'New Event',
			'add_new_item'			=> 'Schedule Event',
			'edit_item'				=> 'Edit Event',
			'new_item'				=> 'New Event',
			'view_item'				=> 'View Event',
			'search_items'			=> 'Search Events',
			'not_found'				=> 'No events found',
			'not_found_in_trash'	=> 'No events found in Trash', 
			'parent_item_colon'		=> '',
			'menu_name'				=> 'Events',
		);
		
		$event_capabilities = array(
			'edit_post'				=> 'edit_post',
			'edit_posts'			=> 'edit_posts',
			'edit_others_posts'		=> 'edit_others_posts',
			'publish_posts'			=> 'publish_posts',
			'read_post'				=> 'read_post',
			'read_private_posts'	=> 'read_private_posts',
			'delete_post'			=> 'delete_post'
		);			
			
		// Construct the arguments for our custom slide post type
		$event_args = array(
			'labels'				=> $event_labels,
			'description'			=> 'Scheduled calendar events',
			'public'				=> true,
			'publicly_queryable'	=> true,
			'exclude_from_search'	=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'		=> false,
			'menu_icon'				=> THEME_URI . '/images/icons/calendar-icon-20.png',
			'capabilities'			=> $event_capabilities,
			'map_meta_cap'			=> true,
			'hierarchical'			=> false,
			'supports'				=> array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'			=> array( 'calendar' , 'occurence' ),
			'has_archive'			=> false,
			'rewrite'				=> array(
										'slug' 	=> 'event',
										'feeds'	=> false,
										'pages'	=> false,
										),
			'query_var'				=> true,
			'can_export'			=> true,
		);

		
		// Register the Event post type!
		register_post_type( 'event', $event_args );
	}
		

	/**
	 * Register a Calendar taxonomy for Events
	 * @since 0.1
	 */
	function register_calendar() {
		
		/* Calendar */
		$calendar_tax_labels = array(			
			'name'							=> 'Calendars',
			'singular_name'					=> 'Calendar',
			'search_items'					=> 'Search Calendars',
			'popular_items'					=> 'Popular Calendars',
			'all_items'						=> 'All Calendars',
			'edit_item'						=> 'Edit Calendar',
			'update_item'					=> 'Update Calendar',
			'add_new_item'					=> 'Add New Calendar',
			'new_item_name'					=> 'New Calendar Name',
			'menu_name'						=> 'Calendars',
			'separate_items_with_commas'	=> 'Separate calendars with commas',
			'choose_from_most_used'			=> 'Choose from the most used calendars',
		);
		
		$calendar_tax_caps = array(
			'manage_terms'	=> 'manage_categories',
			'edit_terms'	=> 'manage_categories',
			'delete_terms'	=> 'manage_categories',
			'assign_terms'	=> 'edit_posts'
		);
		
		$calendar_tax_args = array(
			'labels'				=> $calendar_tax_labels,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> false,
			'show_tagcloud'			=> false,
			'hierarchical'			=> true,
			'rewrite'				=> array( 'slug' => 'calendar' ),
			'capabilities'    	  	=> $calendar_tax_caps,
		);		

		/* Register the Calendar post taxonomy! */
		register_taxonomy( 'calendar', 'event', $calendar_tax_args );
	}
	
	/**
	 * Customize backend messages when an event is updated.
	 * @since 0.1
	 */
	function update_messages( $event_messages ) {
		global $post, $post_ID;
		
		/* Set some simple messages for editing slides, no post previews needed. */
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
	function meta_boxes() {
		add_meta_box( 'event-details', 'Event Details', array( $this , 'details_box' ), 'event', 'normal', 'high' );
		add_meta_box( 'event-recurrence', 'Event Recurrence', array( $this , 'recurrence_box' ), 'event', 'normal', 'high' );
	}
	

	/**
	 * Display custom event details box.
	 * @since 0.1
	 */
	function details_box( $object , $box ) {
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
	function recurrence_box( $object , $box ) {

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
	 * Save or update a new event
	 * @since 0.1
	 */
	function save_event( $post_id , $post = '' ) {
	
		// Don't do anything if it's not an event
		if ( $post->post_type != 'event' ) return;
	
		/* SAVE META INFORMATION 
		------------------------------------*/
		
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
		
		// Loop through each meta, saving it to the database
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
		
		
		/* REGISTER BUDDYPRESS NOTIFICATION 
		------------------------------------*/
		
		// Get event data
		global $bp, $wpdb;
		$post_id = $post_id;
		if ( !$user_id )
			$user_id = $post->post_author;

		// Figure out which calendars this event belongs to
		$calendars = wp_get_post_terms( $post_id , 'calendar' );
		$group_slugs = array();
		
		// For each calendar, check if it's a group calendar
		foreach ( $calendars as $calendar ) {
			$term_id 	= $calendar->term_id;
			$slug		= $calendar->slug;
			if ( is_group_calendar( $term_id ) )
				$groups[] = $calendar;
		}	
		
		// If this event does not belong to a group, we can stop here
		if ( empty( $groups ) ) return;
		
		// As long as the post is published, we can register the activity
		if ( 'publish' != $post->post_status ) return;
		
		// Loop through each group, adding an activity entry for each one
		foreach ( $groups as $group ) {
		
			// Get the group data
			$group_id 	= groups_get_id( $group->slug );
			$group_name	= $group->name;

			// Configure the activity entry
			$post_permalink 	= get_permalink( $post_id );
			$activity_action 	= sprintf( '%1$s added the event %2$s to the %3$s.' , bp_core_get_userlink( $post->post_author ), '<a href="' . $post_permalink . '">' . $post->post_title . '</a>' , $group_name . ' <a href="' . SITEURL . '/calendar/' . $group->slug .'">group calendar</a>' );
			$activity_content 	= $post->post_content;

			// Check for existing entry
			$activity_id = bp_activity_get_activity_id( array(
				'user_id'           => $user_id,
				'component'         => $bp->groups->id,
				'type'              => 'new_calendar_event',
				'item_id'           => $group_id,
				'secondary_item_id' => $post_id,
			) );
			
			// Record the entry
			groups_record_activity( array(
				'id'				=> $activity_id,
				'user_id' 			=> $user_id,
				'action' 			=> $activity_action,
				'content' 			=> $activity_content,
				'primary_link' 		=> $post_permalink,
				'type' 				=> 'new_calendar_event',
				'item_id' 			=> $group_id,
				'secondary_item_id' => $post_id,
			));
			
			// Update the group's last activity meta
			groups_update_groupmeta( $group_id, 'last_activity' , bp_core_current_time() );
			
			// Maybe notify every group member
			$require_rsvp = $_POST['event-require-rsvp'];
			if ( $require_rsvp ) :
				if ( bp_group_has_members( $args = array( 'group_id' => $group_id, 'exclude_admins_mods' => false , 'per_page' => 99999 ) ) ) :	while ( bp_members() ) : bp_the_member();
						
					// Remove any existing notifications ( userid , itemid , component, action , secondary )
					bp_core_delete_notifications_by_item_id( bp_get_group_member_id() , $group_id , $bp->groups->id , 'new_calendar_event' , $post_id );
			
					// Send a notification ( itemid , groupid , component, action , secondary )
					bp_core_add_notification( $group_id , bp_get_group_member_id() , $bp->groups->id , 'new_calendar_event' , $post_id  );
						
				endwhile; endif;
			endif;
		}
	}
}

/*---------------------------------------------
2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/

/**
 * Display calendar events
 * @since 0.1
*/
function apoc_get_calendar( $calendar = '' , $number = 10 ) {
	include( THEME_DIR . '/library/templates/calendar.php' );	
} 

/**
 * Helper function to set context on calendar and event pages
 * @since 0.1
*/
function is_calendar() {
	global $wp_query;
	if ( 'calendar' == $wp_query->queried_object->taxonomy )
		return true;
}

/**
 * Helper function to check if a calendar belongs to a BuddyPress group
 * @since 0.1
 */
function is_group_calendar( $term_id ) {
	$term_meta 	= get_option( "taxonomy_$term_id" );
	if ( $term_meta['is_group_calendar'] )
		return true;
}