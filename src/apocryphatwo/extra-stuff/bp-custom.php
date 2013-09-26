<?php 
/**
 * Apocrypha Theme Custom Buddypress Functions
 * Andrew Clayton
 * Version 1.0
 * 2-7-2013
 */


/* Avatar Upload Size */
if ( !defined( 'BP_AVATAR_THUMB_WIDTH' ) )
	define( 'BP_AVATAR_THUMB_WIDTH', 100 );
if ( !defined( 'BP_AVATAR_THUMB_HEIGHT' ) )
	define( 'BP_AVATAR_THUMB_HEIGHT', 100 );
if ( !defined( 'BP_AVATAR_FULL_WIDTH' ) )
	define( 'BP_AVATAR_FULL_WIDTH', 200 ); 
if ( !defined( 'BP_AVATAR_FULL_HEIGHT' ) )
	define( 'BP_AVATAR_FULL_HEIGHT', 200 ); 

define( 'BP_AVATAR_DEFAULT', 'http://tamrielfoundry.com/wp-content/themes/apocrypha/images/avatars/neutral-200.jpg' );
define( 'BP_AVATAR_DEFAULT_THUMB', 'http://tamrielfoundry.com/wp-content/themes/apocrypha/images/avatars/neutral.jpg' );

/* Modify Navigation Tabs */
function foundry_profile_tabs() {
	global $bp;
		
	// Main navigation
	$bp->bp_nav['profile']['position'] 	= 1;
	$bp->bp_nav['activity']['position'] = 2;
	$bp->bp_nav['forums']['position'] 	= 3;
	$bp->bp_nav['friends']['position'] 	= 4;
	$bp->bp_nav['groups']['position'] 	= 5;
	$bp->bp_nav['messages']['position'] = 6;
	$bp->bp_nav['settings']['position'] = 100;
	
	// Profile sub-navigation
	$bp->bp_options_nav['activity']['just-me']['name'] 			= 'All Activity';
	$bp->bp_options_nav['profile']['public']['name'] 			= 'Player Biography';
	$bp->bp_options_nav['profile']['change-avatar']['link'] 	= $bp->displayed_user->domain . 'profile/change-avatar';
	if ( !bp_is_my_profile() && !current_user_can( 'edit_users' ) )
	$bp->bp_options_nav['profile']['change-avatar']				= false;
	$bp->bp_options_nav['forums']['replies']['name'] 			= 'Recent Post Tracker';
	if ( !current_user_can( 'moderate_comments' ) )
	$bp->bp_options_nav['forums']['replies']					= false;
	$bp->bp_options_nav['forums']['favorites']['name'] 			= 'Favorite Topics';
	$bp->bp_options_nav['forums']['subscriptions']['name']	 	= 'Subscribed Topics';
	$bp->bp_options_nav['settings']['general']['name'] 			= 'Edit Account Info';
	$bp->bp_options_nav['settings']['notifications']['name'] 	= 'Notification Preferences';
	
	$bp->bp_options_nav['settings']['capabilities']['parent_slug']		= 'infractions';
	$bp->bp_options_nav['settings']['capabilities']['parent_url']		= $bp->displayed_user->domain . 'infractions/';
	
	// Notifications on user profile
	if ( bp_is_my_profile() ) {
	
		$friend_requests = bp_friend_get_total_requests_count();
		if ( $friend_requests ) {
			$friend_plus = ' <span class="activity-count">+' . $friend_requests . '</span>';
			$bp->bp_nav['friends']['name'] .= $friend_plus;
			$bp->bp_options_nav['friends']['requests']['name'] .= $friend_plus;
		}
					
		$unread_messages 	= bp_get_total_unread_messages_count();
		if ( $unread_messages ) {
			$message_plus = ' <span class="activity-count">+' . $unread_messages . '</span>';
			$bp->bp_options_nav['messages']['inbox']['name'] .= $message_plus;
		}
		
		$group_invites		= groups_get_invites_for_user( bp_loggedin_user_id() );
		$group_invites		= $group_invites['total'];
		if ( $group_invites ) {
			$guild_plus = ' <span class="activity-count">+' . $group_invites . '</span>';
			$bp->bp_options_nav['groups']['invites']['name'] .= $guild_plus;
		}
	}

	// Add custom edit profile screen
	bp_core_remove_subnav_item( 'profile' , 'edit' );
	if ( bp_is_my_profile() || current_user_can( 'edit_users' ) ) {
		
		bp_core_new_subnav_item( array(
			'name' 				=> 'Edit Profile',
			'slug' 				=> 'edit',
			'parent_url' 		=> $bp->displayed_user->domain . $bp->profile->slug . '/',
			'parent_slug' 		=> $bp->profile->slug,
			'screen_function' 	=> 'apoc_edit_profile_screen',
			'position' 			=> 20 ) );
	}		
	
	// Add guild activity screen
	if( bp_is_group() ) {
		bp_core_new_subnav_item( array( 
			'name' 				=> 'Activity', 
			'slug' 				=> 'activity', 
			'parent_slug' 		=> $bp->groups->current_group->slug, 
			'parent_url' 		=> bp_get_group_permalink( $bp->groups->current_group ), 
			'screen_function' 	=> 'apoc_guild_activity_screen',
			'position' 			=> 20,  ) );
	}
	
	// Add moderation and infraction management panel
	if ( bp_is_my_profile() || current_user_can( 'moderate' ) ) {
		
		if ( function_exists( 'get_user_warning_level' ) ) {
			$level = get_user_warning_level( bp_displayed_user_id() );
			bp_core_new_nav_item( array(
				'name' 					=> 'Infractions <span>' . $level . '</span>',
				'slug' 					=> 'infractions',
				'position' 				=> 99, 
				'screen_function' 		=> 'apoc_user_infrations_screen',
				'default_subnav_slug' 	=> 'status',
				'item_css_id' 			=> 'infractions', ) );
		
			// Add infraction overview screen
			bp_core_new_subnav_item( array( 
				'name' 				=> 'Status',
				'slug' 				=> 'status',
				'parent_url' 		=> $bp->displayed_user->domain . 'infractions/',
				'parent_slug' 		=> 'infractions',
				'screen_function' 	=> 'apoc_user_infrations_screen',
				'position' 			=> 10 ) );
				
			// Add send warning screen
			if ( current_user_can( 'moderate' ) ) {	
				bp_core_new_subnav_item( array( 
					'name' 				=> 'Issue Warning',
					'slug' 				=> 'warning',
					'parent_url' 		=> $bp->displayed_user->domain . 'infractions/',
					'parent_slug' 		=> 'infractions',
					'screen_function' 	=> 'apoc_issue_warning_screen',
					'position' 			=> 20 ) );
			
				// Add moderator notes screen
				bp_core_new_subnav_item( array( 
					'name' 				=> 'Moderator Notes',
					'slug' 				=> 'notes',
					'parent_url' 		=> $bp->displayed_user->domain . 'infractions/',
					'parent_slug' 		=> 'infractions',
					'screen_function' 	=> 'apoc_moderator_notes_screen',
					'position' 			=> 30 ) );
			}
		}
	}	
}
add_action( 'bp_setup_nav', 'foundry_profile_tabs', 99 );
define( 'BP_DEFAULT_COMPONENT' , 'profile' );

/*
 * Create custom edit profile screen
 * @since 0.1
 */
function apoc_edit_profile_screen() {
	bp_core_load_template( apply_filters( 'apoc_edit_profile_template', 'members/single/profile/edit' ) );
}

/*
 * Create custom user infractions screen
 * @since 0.3
 */
function apoc_user_infrations_screen() {
	bp_core_load_template( apply_filters( 'apoc_user_infractions_template', 'members/single/infractions' ) );
}

/*
 * Create the issue warning screen
 * @since 0.3
 */
function apoc_issue_warning_screen() {
	bp_core_load_template( apply_filters( 'apoc_issue_warning_template', 'members/single/infractions/warning' ) );
}

/*
 * Create the moderator notes screen
 * @since 0.3
 */
function apoc_moderator_notes_screen() {
	bp_core_load_template( apply_filters( 'apoc_moderator_notes_template', 'members/single/infractions/notes' ) );
}

/*
 * Create guild activity screen
 * @since 0.1
 */
function apoc_guild_activity_screen() {
	bp_core_load_template( apply_filters( 'apoc_guild_activity_template', 'groups/single/activity' ) );
}




/* GROUPS
------------------------------------------------*/
/*
 * This class allows a group query to be cross referenced using group_meta values
 * @since 0.1
 */
class BP_Groups_Meta_Filter {
	protected $key;
	protected $value;
	protected $group_ids = array();

	function __construct( $key, $value ) {
		$this->key   = $key;
		$this->value = $value;
		$this->setup_group_ids();
		add_filter( 'bp_groups_get_paged_groups_sql', array( &$this, 'filter_sql' ) );
		add_filter( 'bp_groups_get_total_groups_sql', array( &$this, 'filter_sql' ) );
	}

	function setup_group_ids() {
		global $wpdb, $bp;
		$sql = $wpdb->prepare( "SELECT group_id FROM {$bp->groups->table_name_groupmeta} WHERE meta_key = %s AND meta_value = %s", $this->key, $this->value );
		$this->group_ids = wp_parse_id_list( $wpdb->get_col( $sql ) );
	}

	function get_group_ids() {
		return $this->group_ids;
	}

	function filter_sql( $sql ) {
		$group_ids = $this->get_group_ids();
		if ( empty( $group_ids ) ) {
			return $sql;
		}

		$sql_a = explode( 'WHERE', $sql );
		$new_sql = $sql_a[0] . 'WHERE g.id IN (' . implode( ',', $group_ids ) . ') AND ' . $sql_a[1];
		return $new_sql;
	}

	function remove_filters() {
		remove_filter( 'bp_groups_get_paged_groups_sql', array( &$this, 'filter_sql' ) );
		remove_filter( 'bp_groups_get_total_groups_sql', array( &$this, 'filter_sql' ) );
	}
}
?>