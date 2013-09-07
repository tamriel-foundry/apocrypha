<?php
/**
 * Apocrypha Theme Breadcrumb Trail.
 * Andrew Clayton
 * Version 1.0.0
 * 8-16-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
/*---------------------------------------------
1.0 - APOC_BREADCRUMBS CLASS
----------------------------------------------*/

/**
 * Generates breadcrumb links for Tamriel Foundry pages.
 * Gives users a handy reference for where they are within the site.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
class Apoc_Breadcrumbs {

	// The breadcrumb arguments
	public 	$args;
	
	// The resulting links
	public	$crumbs;
	
	/**
	 * The breadcrumb constructor function.
	 * Args provides formatting preferences.
	 */
	function __construct( $args = array() ) {
	
		// Setup default arguments
		$defaults 				= $this->default_args();
		$this->args				= wp_parse_args( $args , $defaults );
				
		// Generate the links
		$this->crumbs			= $this->create_crumbs();
	}
	
	
	/**
	 * Set up the default arguments for the pagination links
	 */
	private function default_args() {
	
		$defaults = array(
			'container' 	=> 'nav',
			'separator' 	=> '&raquo;',
			'before' 		=> 'Viewing:',
			'home' 			=> 'Home',
		);
		return $defaults;
	}
	
	/**
	 * Create the breadcrumb links
	 */	
	function create_crumbs() {
	
		$breadcrumb = '';
		$args = $this->args;

		// Get the items based on page context 
		$trail = $this->get_trail();

		// If items are found, build the trail 
		if ( !empty( $trail ) && is_array( $trail ) ) {

			// Wrap the trail and add the 'Before' element
			$breadcrumb = '<'.$args['container']. ' class="breadcrumb-trail breadcrumbs">';
			$breadcrumb .= ( !empty( $args['before'] ) ? '<span class="trail-before">' . $args['before'] . '</span> ' : '' );
			
			// Adds the 'trail-end' class around last item 
			array_push( $trail, '<span class="trail-end">' . array_pop( $trail ) . '</span>' );
			
			// Format the separator 
			$separator = ( !empty( $args['separator'] ) ? $args['separator'] : '' );

			// Join the individual trail items into a single string 
			$breadcrumb .= join( " {$separator} ", $trail );

			// Close the breadcrumb trail containers 
			$breadcrumb .= '</' .  $args['container'] . '>';
		}

		// Return the formatted breadcrumbs
		return $breadcrumb;
	}
	
	/**
	 * Combine the breadcrumb items
	 */		
	function get_trail() {
		
		// Setup empty trail
		$trail = array();
		
		// Start with a link to the home page 
		$trail[] = '<a href="' . SITEURL . '" title="' . SITENAME . '" rel="home" class="trail-home">' . $this->args['home'] . '</a>';	
		
		// If BuddyPress is installed and we're on a BuddyPress page 
		if ( class_exists( 'BuddyPress' ) && is_buddypress() ) :
			$trail = array_merge( $trail , $this->buddypress_crumbs() );
		
		// If bbPress is installed and we're on a bbPress page
		elseif ( class_exists( 'bbPress' ) && is_bbpress() ) :
			$trail = array_merge( $trail , $this->bbpress_crumbs() );
		
		// Otherwise, it's a basic WordPress component
		else :
			$trail = array_merge( $trail , $this->wordpress_crumbs() );
		endif;
		
		// Return the trail for use
		return $trail;
	}
		
	/**
	 * Get WordPress breadcrumb items
	 */		
	function wordpress_crumbs() {
		
		// Setup empty trail
		$trail 	= array();
		
		// Get some info
		$apoc 		= apocrypha();
		$post		= $apoc->queried_object;
		$post_id	= $apoc->queried_object_id;
		$post_type 	= $post->post_type;
		$parent 	= absint( $post->post_parent );

		// Singular Views
		if ( is_singular() ) :
			switch( $post_type ) {
			
				// Single Posts 
				case 'post' :
				
					// Is the post in a category?
					$categories = get_the_category( $post_id );
					if ( $categories ) :
					
						// Just do the first category
						$term = $categories[0];
				
						// If the category has a parent, add it to the trail. 
						if ( 0 != $term->parent ) 
							$trail = array_merge( $trail, $this->trail_parents( $term->parent, 'category' ) );

						// Add the category archive link to the trail. 
						$trail[] = '<a href="' . get_term_link( $term ) . '" title="' . esc_attr( $term->name ) . '">' . $term->name . '</a>';
					endif;
					
					// Does the post have an ancestor?
					if ( 0 != $parent  ) 
						$trail = array_merge( $trail, $this->trail_parents( $parent ) );
					
					// Editing a comment on this post
					if ( is_comment_edit() ) :
						$trail[] = '<a href="' . get_permalink() . '" title="Return to article">' . $post->post_title . '</a>';
						$trail[] = 'Edit Comment';
					
					// Reading the post
					else :
						$trail[] = $post->post_title;
					endif; 
				break;
								
				// Single Pages 
				case 'page' :
					
					// If the page has ancestor pages
					if ( 0 != $parent  ) 
						$trail = array_merge( $trail, $this->trail_parents( $parent ) );
					
					// Otherwise, viewing the page
					$trail[] = $post->post_title;
				break;
				
				// Single Calendar Event 
				case 'event' :
					
					// I need to rewrite this
					global $allowed_calendar;
					if ( is_group_calendar( $allowed_calendar->term_id ) ) :
						$group_url = ( 'entropy-rising' == $allowed_calendar->slug ) ? SITEURL . '/entropy-rising/' : SITEURL . '/groups/' . trailingslashit( $slug );
						$trail[] = '<a href="'. $group_url .'" title="'. $allowed_calendar->name . ' Guild Page">'. $allowed_calendar->name .'</a>';
					endif;
									
					$trail[] = '<a href="'. SITEURL . '/calendar/' . $allowed_calendar->slug . '/" title="'. $allowed_calendar->name . ' Calendar">Calendar</a>';
					$trail[] = $post->post_title;
				break;
			}
			
		// Archive Pages
		elseif ( is_archive() ) :
		
			// Category Archives
			if ( is_category() ) :
				$trail[] = 'Categories';

				// If the category has a parent, add it to the trail. 
				if ( $post->parent != 0 ) 
					$trail = array_merge( $trail, $this->trail_parents( $post->parent ) );
				
				// Finish up with the term name
				$trail[] = $post->name;
			
			// Author Archive 
			elseif ( is_author() ) :
				$trail[] = 'Authors';
				$trail[] = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
			
			
			// Calendar Archive 
			elseif ( is_calendar() ) :
			
				// Is it a group calendar? 
				$term_id 	= $post->term_id;
				if( is_group_calendar( $term_id ) ) :

					// Get the group
					$slug		= $post->slug;
					$group_id	= groups_get_id( $slug );		
					if ( 'entropy-rising' == $slug ) :
						$trail[] = '<a href="'. home_url() . '/entropy-rising/">Entropy Rising</a>';
					else :
						$group = groups_get_group( array( 'group_id' => $group_id ) );
						$trail[] = '<a href="'. bp_get_groups_directory_permalink() .'">Guilds</a>';
						$trail[] = '<a href="'. bp_get_groups_directory_permalink() . $slug .'" title="'. $group->name . '">'. $group->name . '</a>';
					endif;
				endif;
				
				// Otherwise just a standalone calendar
				$trail[] = 'Calendar';
			endif;
		
		// Search Results
		elseif ( is_search() ) :
			$trail[] = sprintf( 'Pages and articles containing &quot;%1$s&quot;', esc_attr( get_search_query() ) );
			
		// Page Not Found
		elseif ( is_404() ) : 
			$trail[] = '404 Page Not Found';		
		endif;
					
		// Return the trail
		return $trail;		
	}
	
	/**
	 * Get bbPress breadcrumb items
	 */		
	function bbpress_crumbs() {
		
		// Setup the trail
		$bbp_trail = array();
		
		// If it is the forum root, just display "Forums"
		if ( bbp_is_forum_archive() ) {
			$bbp_trail[] = 'Forums';
			return $bbp_trail;
		}
					
		// Otherwise link to the root forums
		$bbp_trail[] = '<a href="' . get_post_type_archive_link( 'forum' ) . '">Forums</a>';
		
		// Recent topics page 
		if ( bbp_is_topic_archive() ) :
			$bbp_trail[] = 'Recent Topics';
		
		// Topic tag archives 
		elseif ( bbp_is_topic_tag() ) :
			$bbp_trail[] = bbp_get_topic_tag_name();
		
		// Editing a topic tag
		elseif ( bbp_is_topic_tag_edit() ) :
			$bbp_trail[] = '<a href="' . bbp_get_topic_tag_link() . '">' . bbp_get_topic_tag_name() . '</a>';
			$bbp_trail[] = 'Edit';
		
		// Single topic 
		elseif ( bbp_is_single_topic() ) :	
			$topic_id = get_queried_object_id();
			$bbp_trail = array_merge( $bbp_trail, $this->trail_parents( bbp_get_topic_forum_id( $topic_id ) ) );
			$bbp_trail[] = bbp_get_topic_title( $topic_id );
				
		// If it's a split, merge, or edit, link back to the topic 
		elseif ( bbp_is_topic_split() || bbp_is_topic_merge() || bbp_is_topic_edit() ) :
			$topic_id = get_queried_object_id();
			$bbp_trail = array_merge( $bbp_trail, $this->trail_parents( $topic_id ) );
		
			if ( bbp_is_topic_split() ) 		: $bbp_trail[] = 'Split Topic';
			elseif ( bbp_is_topic_merge() ) 	: $bbp_trail[] = 'Merge Topic';
			elseif ( bbp_is_topic_edit() ) 		: $bbp_trail[] = 'Edit Topic';
			endif;
			
		// Single reply 
		elseif ( bbp_is_single_reply() ) :
			$reply_id = get_queried_object_id();
			$bbp_trail = array_merge( $bbp_trail, $this->trail_parents( bbp_get_reply_topic_id( $reply_id ) ) );
			$bbp_trail[] = bbp_get_reply_title( $reply_id );
		
		// Single reply edit 
		elseif ( bbp_is_reply_edit() ) :
			$reply_id = get_queried_object_id();
			$bbp_trail = array_merge( $bbp_trail, $this->trail_parents( bbp_get_reply_topic_id( $reply_id ) ) );
			$bbp_trail[] = 'Edit Reply';
			
		// Single forum 
		elseif ( bbp_is_single_forum() ) :
		
			// Get the queried forum ID and its parent forum ID. 
			$forum_id 			= get_queried_object_id();
			$forum_parent_id 	= bbp_get_forum_parent_id( $forum_id );
			
			// Get the forum parents
			if ( 0 != $forum_parent_id) 
				$bbp_trail = array_merge( $bbp_trail, $this->trail_parents( $forum_parent_id ) );
				
			// Give the forum title
			$bbp_trail[] = bbp_get_forum_title( $forum_id );
		
		endif;
		
		// Return the bbPress trail 
		return $bbp_trail;
	}

	/**
	 * Get BuddyPress breadcrumb items
	 */	
	function buddypress_crumbs() {
				
	// User Profile
	if ( bp_is_user() ) : 
	
		// Your own profile
		if ( bp_is_home() ) :
			 $bp_trail[] = '<a href="'.bp_displayed_user_domain().'" title="Your Profile">Your Profile</a>';
			 
		// Someone else's profile
		else :
			$bp_trail[] = '<a href="'. bp_get_members_directory_permalink() .'" title="Members Directory">Members</a>';
			$bp_trail[] = '<a href="'.bp_displayed_user_domain().'" title="'.bp_get_displayed_user_fullname(). '">' . bp_get_displayed_user_fullname() . '</a>';
		endif;

		// Display the current component
		$bp_trail[] = ucfirst( bp_current_component() );
		
	// Backup Placeholder
	else :
		$bp_trail = 'buddypress placeholder';
	endif;
		
	// Return the BuddyPress trail
	return $bp_trail;
	}
	
	function trail_parents( $post_id ) {
	
		// Setup the parent trail
		$parent_trail = array();
		$parents = array();

		// Verify we have something to work with 
		if ( empty( $post_id ) ) return $parent_trail;
		
		// Loop through post IDs until we run out of parents 
		while ( $post_id ) {
			$page 		= get_page( $post_id );
			$parents[]  = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . get_the_title( $post_id ) . '</a>';
			
			// Load the grandparent page if there is one 
			$post_id	 = $page->post_parent;
		}
	
		// If parents were found, reverse their order
		if ( !empty( $parents ) ) 
			$parent_trail = array_reverse( $parents );
			
		// Return the trail
		return $parent_trail;
	}
	
}



/*---------------------------------------------
TO DO 
----------------------------------------------*/

/* Get BuddyPress breadcrumb items
-----------------------------------------------------*/
function apoc_get_buddypress_breadcrumbs( $args = array() ) {
	$bp_trail = array();
	
	// Directories 
	if ( bp_is_directory() ) :
		if ( bp_is_activity_component() ) : $bp_trail[] = 'Recent Activity';
		elseif ( bp_is_members_component() ) : $bp_trail[] = 'Members Directory';
		elseif ( bp_is_groups_component() ) : $bp_trail[] = 'Groups and Guilds Directory';
		else : $bp_trail[] = ucfirst( bp_current_component() );
		endif;
	
	// Single Member 
	elseif ( bp_is_user() ) : 
		$bp_trail[] = '<a href="'. bp_get_members_directory_permalink() .'" title="Members Directory">Members</a>';
		
		// Get the displayed user 
		if ( bp_is_home() ) : $bp_trail[] = '<a href="'.bp_displayed_user_domain().'" title="Your Profile">Your Profile</a>';
		else : $bp_trail[] = '<a href="'.bp_displayed_user_domain().'" title="'.bp_get_displayed_user_fullname(). '">' . bp_get_displayed_user_fullname() . '</a>';
		endif;

		// Display the current component 
		$bp_trail[] = ucfirst( bp_current_component() );
		
	// Single Group 
	elseif ( bp_is_group() ) :
		
		// Get the displayed group 
		if ( bp_get_current_group_id() == 1 ) :
			$bp_trail[] = '<a href="'. home_url() . '/entropy-rising/">Entropy Rising</a>';
		else :
			$bp_trail[] = '<a href="'. bp_get_groups_directory_permalink() .'">Guilds</a>';
			$bp_trail[] = '<a href="'. bp_get_group_permalink() .'" title="'.bp_get_current_group_name().'">'.bp_get_current_group_name().'</a>';
		endif;
		
		// Display the current group action for everything except forums 
		if ( 'home' == bp_current_action() ) : $bp_trail[] = 'Profile';
		elseif ( bp_current_action() != 'forum' ) : $bp_trail[] = ucfirst( bp_current_action() );
		elseif ( bp_current_action() == 'forum' ) : 
			$bp_trail = array_merge( $bp_trail, apoc_get_group_forum_breadcrumbs() );
		endif;
	
	// User Registration 
	elseif ( bp_is_register_page() ) :
		$bp_trail[] = 'New User Registration';
	
	// User Activation 
	elseif ( bp_is_activation_page() ) :
		$bp_trail[] = 'User Account Activation';
	
	// New Group Creation 
	elseif ( bp_is_group_create() ) :
		$bp_trail[] = 'Create New Group';

 	endif;
	return $bp_trail;
}

/* Get group forum breadcrumbs
-----------------------------------------------------*/
function apoc_get_group_forum_breadcrumbs() {
	$bp_trail = array();
	
	// Group Forum Root 
	if ( NULL == bp_action_variable() ) :
		$bp_trail[] = 'Forum';
	
	// Topic 
	else :
		$bp_trail[] = '<a href="'. bp_get_group_permalink() .'forum/" title="Group Forum">Forum</a>';
		
		// Single Topic
		if ( bp_is_action_variable( 'topic' , 0 ) ) :
			$topic_info = apoc_get_group_topic_info();
					
			// Edit Topic
			if ( bp_is_action_variable( 'edit' , 2 ) ) :
				$bp_trail[] = '<a href="'. bp_get_group_permalink() .'forum/topic/' . $topic_info->url . '" title="' . $topic_info->title . '">' . $topic_info->title . '</a>';
				$bp_trail[] = 'Edit Topic';
				
			else :
				$bp_trail[] = $topic_info->title;
			endif; 
			
		// Edit Reply
		elseif ( bp_is_action_variable( 'reply' , 0 ) ) :
			$topic_info = apoc_get_group_reply_info();
			
			if ( bp_is_action_variable( 'edit' , 2 ) ) :
				$bp_trail[] = '<a href="'. bp_get_group_permalink() .'forum/topic/' . $topic_info->url . '" title="' . $topic_info->title . '">' . $topic_info->title . '</a>';
				$bp_trail[] = 'Edit Reply';
			endif;
		endif;
		
	endif;
	
	return $bp_trail;
}

// Get info about a group forums topic from its slug 
function apoc_get_group_topic_info() {

	global $bp;
	$slug = $bp->action_variables[1];
	
	
	global $wpdb;
	$topic = $wpdb->get_row( 
		$wpdb->prepare( 
			"SELECT post_title AS title, post_name AS url
			FROM $wpdb->posts 
			WHERE post_name = %s",
			$slug )
		);
		
	return $topic;
}

// Get info about a group forums reply from its slug 
function apoc_get_group_reply_info() {

	global $bp;
	$slug = 'reply-to-here-is-a-post-in-the-private-roleplaying-forum';
	
	global $wpdb;
	$topic = $wpdb->get_row( 
		$wpdb->prepare( 
			"SELECT post_title AS title, post_name AS url
			FROM $wpdb->posts 
			WHERE ID = ( 
				SELECT post_parent
				FROM $wpdb->posts
				WHERE post_name = %s )",
			$slug )
		);
		
	return( $topic );
}


/*---------------------------------------------
2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/

/**
 * Helper function that displays the breadcrumbs in templates
 * @version 1.0.0
 */
function apoc_breadcrumbs( $args = array() ) {
	$crumbs = new Apoc_Breadcrumbs( $args );
	echo $crumbs->crumbs;
}
