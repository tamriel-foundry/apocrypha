<?php
/**
 * Apocrypha Theme User Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-1-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/*--------------------------------------------------------------
1.0 - APOCRYPHA USER CLASS
--------------------------------------------------------------*/
class Apoc_User {

	// The context in which this user is being displayed
	public $context;
	
	// The HTML member block
	public $block;
	
	/**
	 * Constructs relevant information regarding a TF user 
	 * The scope of information that is added depends on the context supplied
	 */	
	function __construct( $user_id = 0 , $context = 'reply' ) {
	
		// Set the context
		$this->context = $context;
		
		// Get data for the user
		$this->get_data( $user_id );
		
		// Format data depending on the context
		$this->format_data( $context );
	}
	
	/**
	 * Gets user data for a forum reply or article comment
	 */	
	function get_data( $user_id ) {
	
		// Get the user domain
		$this->domain	= bp_core_get_user_domain( $user_id );
		
		// Get all meta entries for a user
		$meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $user_id ) );
		
		// Add meta to the class
		$this->id		= $user_id;
		$this->fullname = $meta['nickname'];
		$this->roles	= array_keys( maybe_unserialize( $meta['wp_capabilities'] ) );
		$this->status	= maybe_unserialize( $meta['bp_latest_update'] );
		$this->faction	= $meta['faction'];
		$this->race		= $meta['race'];
		$this->class	= $meta['playerclass'];
		$this->posts	= maybe_unserialize( $meta['post_count'] );
		$this->guild	= $meta['guild'];
		$this->bio		= do_shortcode( $meta['description'] );
		$this->sig		= $meta['signature'];
		
		// If the post count is not yet in the database, build it
		if ( $user_id > 0 && empty( $this->posts ) )
			update_user_post_count( $user_id );
		
		// Get some derived data
		$this->rank		= $this->user_rank( $this->posts );
		$this->title	= $this->user_title( $user_id );
		
		// Get additional data and the byline on profile pages
		if ( 'profile' == $this->context ) {
			$user				= get_userdata( $this->id );
			$this->nicename 	= $user->user_nicename;
			$this->regdate 		= strtotime( $user->user_registered );
			$this->byline		= $this->byline();
			$this->contacts		= array(
				'url' 			=> $user->user_url,
				'twitter' 		=> $meta['twitter'],
				'facebook' 		=> $meta['facebook'],
				'steam' 		=> $meta['steam'],
				'youtube' 		=> $meta['youtube'],
				'twitch' 		=> $meta['twitch'],
				'bethforums' 	=> $meta['bethforums'],
			);
			$this->badges		= $this->badges();
			$this->first_name	= $meta['first_name'];
			$this->last_name	= $meta['last_name'];
			$this->charname		= implode( ' ' , array( $meta['first_name'] , $meta['last_name'] ) );
			$this->prefrole		= $meta['prefrole'];
		}
	}
	
	/** 
	 * Assign default ranks based on total post count
	 */
	function user_rank( $posts ) {
		
		// Make sure it's a valid user
		if ( 0 == $this->id ) return false;
		
		// Set up the array of ranks
		$ranks = array(
			0 => array(	'min_posts' => 0 	, 'next_rank' => 10 	, 'title' => 'Scamp' 		),
			1 => array(	'min_posts' => 10 	, 'next_rank' => 25 	, 'title' => 'Novice' 		),
			2 => array(	'min_posts' => 25	, 'next_rank' => 50 	, 'title' => 'Apprentice' 	),
			3 => array(	'min_posts' => 50	, 'next_rank' => 100	, 'title' => 'Journeyman' 	),	
			4 => array(	'min_posts' => 100	, 'next_rank' => 250	, 'title' => 'Adept' 		),
			5 => array(	'min_posts' => 250	, 'next_rank' => 500	, 'title' => 'Expert'		),
			6 => array( 'min_posts' => 500	, 'next_rank' => 1000	, 'title' => 'Master' 		),
			7 => array( 'min_posts' => 1000	, 'next_rank' => 10000	, 'title' => 'Grandmaster' 	),
		);
		
		// Iterate through the ranks, determining where the user's postcount falls
		$rank = $ranks[$i=0];
		while ( $posts['total'] >= $rank['next_rank'] ) { 
			$i++; 
			$rank = $ranks[$i];
		}
		$user_rank = array(
			'current_rank' 	=> $rank['min_posts'],
			'next_rank' 	=> $rank['next_rank'],
			'rank_title'	=> $rank['title']
		);
		
		// Return it
		return $user_rank;
	}
	
	/** 
	 * Display user site title
	 */
	function user_title( $user_id ) {
				
		// If not a guest, get site title
		if ( 0 < $user_id ) :
		
			// Get the user's site roles
			$site_role 	= $this->roles[0];
			$forum_role = $this->roles[1];
			
			// Assign special (non-changeable) titles
			if ( 'administrator' == $site_role ) :
				$title = 'Daedric Prince';
			elseif ( 'bbp_moderator' == $forum_role || 'bbp_keymaster' == $forum_role ) :
				$title = 'Moderator'; 
			elseif ( 'banned' == $site_role ) :
				$title = 'Banned';
			
			// Otherwise, the user can set a custom title
			else :
				// Default title
				$title = $this->rank['rank_title'];
				// Special prefixes
				// $prefix = get_prefix();
			endif;
			
			// Construct the full title
			if ( isset( $prefix ) ) 
				$display_title = $prefix . ', ' . $title;
			else 
				$display_title = $title;
		
		// Otherwise it must be a guest
		else :
			$title = 'Guest';
		endif;
		
		// Display the title
		$role_class = strtolower( str_replace( " " , "-" , $title ) );
		return '<p class="user-title ' . $role_class . '">' . $display_title . '</p>';
	}
	
	/* 
	 * Get a user's declared race and class
	 * @since 0.4
	 */
	function allegiance() {
	
		// Set it up
		$separator	= '';
		$faction	= $this->faction;
		$race 		= $this->race;
		$class 		= $this->class;
	
		// Make sure we have info to use
		if ( '' == $race && '' == $class && '' == $faction )
			return false;
	
		// Otherwise, display what we have		
		if ( '' == $race ) $race = $faction;
		if ( $race != '' ) $separator = ' ';
		$allegiance = '<p class="user-allegiance ' . $faction . '">' . ucfirst( $race ) . $separator . ucfirst( $class ) . '</p>';
		return $allegiance;
	}
	
	/**
	 * Display user post experience bar
	 */
	function expbar() {
	
		// Get the counts
		$current	= $this->rank['current_rank'];
		$next		= $this->rank['next_rank'];
		$total		= $this->posts['total'];
		
		// Calculate the exp
		$percent 	= ( $total - $current ) / ( $next - $current );
		$percent 	= round( $percent , 2) * 100;
		$to_ding 	= $next - $total;
		$tip 		= $to_ding . ' more until next rank!';		

		// Display the bar
		$bar = '<div class="user-exp-container" title="' . $tip . '"><div class="user-exp-bar" style="width:' . $percent . '%;"></div></div>';
		return $bar;
	}
	
	/**
	 * Display user signature
	 */
	function signature() {
		if ( '' != $this->sig )
			echo '<div class="user-signature"><div class="signature-content">' . do_shortcode( $this->sig ) . '</div></div>';
	}
	
	/* 
	 * Generate a byline for the user profile with their allegiance information
	 */
	function byline() {
	
		// Get the data
		$faction 	= $this->faction;
		$race 		= $this->race;
		$class		= ucfirst( $this->class );
		$name		= $this->fullname;

		// Obey proper grammar
		if ( '' == $race ) 
			$grammar 	= 'a sworn ';
		elseif ( in_array( $race , array('altmer','orc','argonian' ) ) )
			$grammar 	= 'an ' . ucfirst($race);
		else $grammar 	= 'a ' 	. ucfirst($race);
			
		// Generate the byline
		switch( $faction ) {
			case 'aldmeri' :
				if ( $class == '' ) $class = 'champion';
				$byline = $name . ' is ' . $grammar . ' ' . $class . ' of the Aldmeri Dominion.';
				break;
			case 'daggerfall' :
				if ( $class == '' ) $class = 'protector';
				$byline = $name . ' is ' . $grammar . ' ' . $class . ' of the Daggerfall Covenant.';
				break;
			case 'ebonheart' :
				if ( $class == '' ) $class = 'vanguard';
				$byline = $name . ' is ' . $grammar . ' ' . $class . ' of the Ebonheart Pact.';
				break;
			default : 
				$class = 'mercenary';
				$byline = $name . ' is a ' . $class . ' with no political allegiance.';
				break;
		}
		
		// Return the byline
		return $byline;
	}
	
	/* 
	 * Display the user's contact information
	 * @since 0.1
	 */
	function contacts() {
	
		// Get the data
		$contacts = array_filter( $this->contacts );

		// Display the list
		echo '<ul class="user-contact-list">' ;
		if ( empty( $contacts ) ) {
			echo '<li><i class="icon-eye-close icon-fixed-width"></i>No contact information shared</li>';
			return;
		}
		if ( $contacts['url'] != '' )
			echo '<li><i class="icon-globe icon-fixed-width"></i><span>Website:</span><a href="' . $contacts['url'] . '" target="_blank">' . $contacts['url'] . '</a></li>' ;
		if ( $contacts['twitter'] != '' )
			echo '<li><i class="icon-twitter icon-fixed-width"></i><span>Twitter:</span><a href="http://twitter.com/' . $contacts['twitter'] . '" target="_blank">' . $contacts['twitter'] . '</a></li>' ;
		if ( $contacts['facebook'] != '' )
			echo '<li><i class="icon-facebook icon-fixed-width"></i><span>Facebook:</span><a href="http://facebook.com/' . $contacts['facebook'] . '" target="_blank">' . $contacts['facebook'] . '</a></li>' ;
		if ( $contacts['steam'] != '' )
			echo '<li><i class="icon-wrench icon-fixed-width"></i><span>Steam ID:</span><a href="http://steamcommunity.com/id/' . $contacts['steam'] . '" target="_blank">' . $contacts['steam'] . '</a></li>' ;
		if ( $contacts['youtube'] != '' )
			echo '<li><i class="icon-youtube icon-fixed-width"></i><span>YouTube:</span><a href="http://www.youtube.com/user/' . $contacts['youtube'] . '" target="_blank">' . $contacts['youtube'] . '</a></li>' ;
		if ( $contacts['twitch'] != '' )
			echo '<li><i class="icon-desktop icon-fixed-width"></i><span>TwitchTV:</span><a href="http://www.twitch.tv/' . $contacts['twitch'] . '" target="_blank">' . $contacts['twitch'] . '</a></li>' ;
		if ( $contacts['bethforums'] != '' ) {
			$bethforums_name = preg_replace( '#(.*)[0-9]+(-{1})#' , '' , $contacts['bethforums'] );
			$bethforums_name = preg_replace( '#-{1}|/{1}#' , ' ' , $bethforums_name );
			echo '<li><i class="icon-sign-blank icon-fixed-width"></i><span>Bethesda:</span><a href="http://forums.bethsoft.com/user/' . $contacts['bethforums'] . '" target="_blank">' . ucwords( $bethforums_name ) . '</a></li>' ;
			}
		echo '</ul>' ;
	}
	
	/* 
	 * Get a users earned forum badges
	 * @since 0.1
	 */
	function badges() {
	
		// Setup array
		$badges = array();
		
		// Veterancy Badges
		if( $this->regdate <= strtotime( '11/12/2012' ) )
			$badges['founder']	= 'Founder';
		if( $this->regdate <= strtotime( '-1 year' ) )
			$badges['yearone']	= 'One Year Veteran';
		
		// Posting Badges
		if( 'Scamp' != $this->rank['rank_title'] )
			$badges[str_replace( ' ' , '-' , strtolower($this->rank['rank_title']) )] = $this->rank['rank_title'];
		
		// Social Badges
		if ( 1 <= bp_get_total_group_count_for_user( $this->id ) )
			$badges['group']	= "It's Dangerous To Go Alone...";
			
		// In-Game Badges
		
		return $badges;
	}

	
	/**
	 * Format the output user block
	 */	
	function format_data( $context ) {
	
		// Setup the basic info block
		$block		= '<a class="member-name" href="' . $this->domain . '" title="View ' . $this->fullname . '&apos;s Profile">' . $this->fullname . '</a>';
		$block		.= $this->title;	
		$block		.= $this->allegiance();
		$block		.= ( '' != $this->guild ) ? '<p class="user-guild ' . strtolower( str_replace( ' ' , '-' , $this->guild ) ) . '">' . $this->guild . '</p>' : '' ;
		

	
		// Do some things differently depending on context
		$avatar_args = array( 'user_id' => $this->id , 'alliance' => $this->faction , 'race' => $this->race );
		switch( $context ) {
		
			case 'directory' :
				$block 					= '<div class="member-meta user-block">' . $block . '</div>';
				break;
		
			case 'reply' :
				$block					.= '<p class="user-post-count">Total Posts: ' . $this->posts['total'] . '</p>';
				$block					.= $this->expbar();
				break;
					
			case 'profile' :
				$avatar_args['type'] 	= 'full';
				$avatar_args['size']	= 200;
				$block					.= '<p class="user-post-count">Total Posts: ' . $this->posts['total'] . '</p>';
				$block					.= $this->expbar();
				$regdate				= date("F j, Y", $this->regdate );
				$block					.= '<p class="user-join-date">Joined ' . $regdate . '</p>';
				break;
		}
		
		// Prepend the avatar
		$avatar			= new Apoc_Avatar( $avatar_args );
		$avatar			= '<a class="member-avatar" href="' . $this->domain . '" title="View ' . $this->fullname . '&apos;s Profile">' . $avatar->avatar . '</a>';
		$this->avatar 	= $avatar;
		$block			= $avatar . $block;
		
		// Add the html to the object
		$this->block 	= $block;
	}
}

/*--------------------------------------------------------------
2.0 - EDIT PROFILE CLASS
--------------------------------------------------------------*/
class Edit_Profile extends Apoc_User {

	/** 
	 * Constructor function for Edit Profile class
	 * Inherits the arguments $user_id and $context from the Apoc_User class
	 * Checks to see if the edit form has been submitted, if so, update the form
	 */
	function __construct( $user_id = 0 ) {
	
		// Construct the user
		parent::__construct( $user_id , 'profile' );
	
		// Was the form submitted?
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' )
			$this->save( $user_id );
			
	}
	
	function save( $user_id ) {
		
		// Check the nonce
		if ( !wp_verify_nonce( $_POST['edit_user_nonce'] , 'update-user' ) )
			exit;
			
		// Get the original values so we can tell whether they were updated
		$originals 	= array(
			'first-name'	=> $this->first_name,
			'last-name'		=> $this->last_name,
			'faction'		=> $this->faction,
			'race'			=> $this->race,
			'playerclass'	=> $this->class,
			'prefrole'		=> $this->prefrole,
			'guild'			=> $this->guild,
			'description'	=> $this->bio,
			'signature'		=> $this->sig,
			'url'			=> $this->contacts['url'],
			'twitter'		=> $this->contacts['twitter'],
			'facebook'		=> $this->contacts['facebook'],
			'youtube'		=> $this->contacts['facebook'],
			'steam'			=> $this->contacts['steam'],
			'twitch'		=> $this->contacts['twitch'],
			'bethforums'	=> $this->contacts['bethforums']
		);
			
		// Group meta fields by their sanitization treatment
		$updates = array(
			'escattr'	=> array( 'first-name' , 'last-name' , 'guild' , 'url' , 'facebook' , 'twitter' , 'youtube' , 'steam' , 'twitch' , 'bethforums' ),
			'escurl'	=> array( 'url' ),
			'kses'		=> array( 'description' , 'signature' ),
			'noesc'		=> array( 'faction' , 'race' , 'playerclass', 'prefrole' )
		);
		
		foreach ( $updates as $treatment => $fields ) {
				
			// Configure the sanitization treatments
			switch ( $treatment ) {
				
				case 'escattr' :
					$treat = 'esc_attr';
					break;
				
				case 'escurl' :
					$treat = 'esc_url';
					break;
					
				case 'kses' :
					$treat	= 'apoc_custom_kses';
					break;
				
				case 'noesc' :
					$treat = 'trim';
					break;
			}
			
			// Loop through fields
			foreach ( $fields as $field ) {
					
					// There is a new value to save
					if ( !empty( $_POST[$field] ) && $_POST[$field] != $originals[$field] )
						update_user_meta( $user_id	, $field , call_user_func( $treat , $_POST[$field] ) );
						
					// The value was removed
					elseif ( empty( $_POST[$field] ) )
						delete_user_meta( $user_id	, $field  )	;	
			}			
		}
		
		// Let plugins save their stuff
		do_action('edit_user_profile_update', $user_id );
		
		// Add a success message
		bp_core_add_message( 'User profile successfully updated!' );
		
		// Redirect back to the profile
		global $bp;
		wp_redirect( $bp->displayed_user->domain );
	}
}
 
/*--------------------------------------------------------------
3.0 - APOCRYPHA AVATAR CLASS
--------------------------------------------------------------*/
class Apoc_Avatar {

	// Declare the avatar property
	public $avatar;

	/** 
	 * Constructor function for Apoc Avatar class
	 * Optionally accepts arguments to avoid unnecessary DB interaction
	 */
	function __construct( $args = array() ) {
	
		// Setup default arguments
		$defaults = array(
			'user_id'		=> 0,
			'alliance'		=> '',
			'race'			=> '',
			'type'			=> 'thumb',
			'size'			=> 100,
			'link'			=> false,
			'url'			=> '',
			);
		
		// Parse with supplied params
		$args = wp_parse_args( $args , $defaults );
		
		// Set class attributes
		foreach ( $args as $prop => $attr ) {
			$this->$prop = $attr;
		}
		
		// If no url was supplied, get it
		if ( $this->link == true && '' == $this->url && 0 < $this->user_id )
			$this->url = bp_core_get_user_domain( $this->user_id );		
			
		// Get the avatar
		$this->get_avatar();
	}
	
	function get_avatar() {
		
		// Get the avatar from BuddyPress
		if ( $this->user_id > 0 ) {
			$avatar	= bp_core_fetch_avatar( $args = array (
				'item_id' 		=> $this->user_id,
				'type'			=> $this->type,
				'height'		=> $this->size,
				'width'			=> $this->size,
				'no_grav'		=> true,
				));
				
			// If the user has not uploaded an avatar, get the default
			if ( strrpos( $avatar , 'mystery-man.jpg' ) ) 
				$avatar = $this->guest_avatar();
		}
		else 
			$avatar = $this->guest_avatar();
			
		// Wrap the avatar in a profile link?
		if ( true == $this->link && $this->user_id > 0 ) 
			$avatar	= '<a class="member-avatar" href="' . $this->url . '" title="View User Profile">' . $avatar . '</a>';
		
		// Set the avatar to the class object
		$this->avatar = $avatar;	
	}
	
	function guest_avatar() {
	
		// Get needed descriptors
		$race 			= $this->race;
		$alliance		= $this->alliance;
		
		// If nothing was passed, try race first
		if ( '' == $race && '' == $alliance ) {
			$race 		= get_user_meta( $this->user_id , 'race' , true );
			$this->race = $race;
		}
			
		// If it's still unset, try alliance next
		if ( '' == $race && '' == $alliance ) {
			$alliance 	= get_user_meta( $this->user_id , 'faction' , true );
			$this->alliance	= $alliance;
		}
		
		// See if anything stuck
		$type = ( '' != $race ) ? $race : $alliance;
			
		// If nothing has worked, use neutral
		if ( '' == $type )
			$type 	= 'neutral';
			
		// Return the appropriate guest avatar
		$avsize		= ( 'thumb' == $this->type ) ? 100 : 200;
		$src 		= trailingslashit( THEME_URI ) . "images/avatars/{$type}-{$avsize}.jpg";
		$avatar 	= '<img src="' . $src . '" alt="Member Avatar" class="avatar" width="' . $this->size . '" height="' . $this->size . '">';
		return $avatar;
	}
}

/*--------------------------------------------------------------
3.0 - STANDALONE FUNCTIONS
--------------------------------------------------------------*/

/** 
 * Update a user's total post count
 * @version 1.0.0
 */
function update_user_post_count( $user_id , $type = 'all' ) {

	// Only do this for registered users
	if ( 0 >= $user_id ) 
		return;
	
	// Get existing post count
	$posts = get_user_meta( $user_id , 'post_count' , true );
	if ( empty( $posts ) ) 
		$type == 'all';

	// Update the counts
	if ( 'all' == $type || 'articles' == $type )
		$posts['articles']	= get_user_article_count( $user_id );
	
	if ( 'all' == $type || 'forums' == $type ) {
		$posts['topics'] 	= bbp_get_user_topic_count_raw( $user_id ) ;
		$posts['replies'] 	= bbp_get_user_reply_count_raw( $user_id ) ;
	}
	
	if ( 'all' == $type || 'comments' == $type ) 
		$posts['comments'] 	= get_user_comment_count( $user_id );
		
	$posts['total'] = $posts['articles'] + $posts['topics'] + $posts['replies'] + $posts['comments'];
	
	// Save it
	update_user_meta( $user_id , 'post_count' , $posts );
}

/** 
 * Update the user's post count when a front-page article is published
 * @version 1.0.0
 */
add_action( 'save_post'			, 'update_author_post_count' , 10 , 2 );
function update_author_post_count( $post_ID , $post ) {
	if ( 'post' != $post->post_type )
		return;
	update_user_post_count( $post->post_author , $type = 'articles' );
}
 
/** 
 * Update the user's post count after a topic or reply is trashed or untrashed
 * @version 1.0.0
 */
add_action( 'bbp_new_topic' 	, 'update_bbpress_post_count' );
add_action( 'bbp_new_reply' 	, 'update_bbpress_post_count' );
add_action( 'bbp_trash_reply' 	, 'update_bbpress_post_count' );
add_action( 'bbp_trash_topic' 	, 'update_bbpress_post_count' );
add_action( 'bbp_untrash_reply' , 'update_bbpress_post_count' );
add_action( 'bbp_untrash_topic' , 'update_bbpress_post_count' );
function update_bbpress_post_count( $post_id ) {
	$post 		= get_post( $post_id );
	$user_id 	= $post->post_author;
	update_user_post_count( $user_id , $type = 'forums' );
}

/** 
 * Update the user's post count after they submit a new comment
 * @version 1.0.0
 */
add_action( 'comment_post' 		, 'new_comment_post_count' );
function new_comment_post_count( $comment_ID ) {
	$comment	= get_comment( $comment_ID );
	$user_id 	= $comment->user_id;
	update_user_post_count( $user_id , $type = 'comments' );
}

/** 
 * Update the user's post count after a comment is trashed or untrashed
 * @version 1.0.0
 */
add_action( 'trashed_comment' 		, 'trash_comment_post_count' );
add_action( 'untrashed_comment' 	, 'trash_comment_post_count' );
function trash_comment_post_count( $comment_id ) {
	$comment	= get_comment( $comment_id );
	$user_id 	= $comment->user_id;
	update_user_post_count( $user_id );
}

/** 
 * Count a user's total comments
 * @since 0.1
 */
function get_user_comment_count( $user_id ) {
	global $wpdb;
    $count = $wpdb->get_var('SELECT COUNT(comment_ID) FROM ' . $wpdb->comments . ' WHERE user_id = ' . $user_id . ' AND comment_approved = 1' );
    return $count;
}

/** 
 * Count a user's total articles
 * @since 0.1
 */
function get_user_article_count( $user_id ) {
	global $wpdb;
    $count = $wpdb->get_var('SELECT COUNT(ID) FROM ' . $wpdb->posts . ' WHERE post_type = "post" AND post_author = ' . $user_id . ' AND post_status = "publish"' );
    return $count;
}


/*--------------------------------------------------------------
3.0 - MISCELLANEOUS
--------------------------------------------------------------*/
/** 
 * Get a user's current warning level
 * @since 0.5
 */
function get_user_warning_level( $userid ) {
	$level = intval( get_user_meta( $userid , 'warning_level' , true ) );
	return $level;
}

/**
 * Count users by a specific meta key
 * @since 0.1
 */
function count_users_by_meta( $meta_key , $meta_value ) {
	global $wpdb;
	$user_meta_query = $wpdb->get_var( 
		$wpdb->prepare(
			"SELECT COUNT(*) 
			FROM $wpdb->usermeta 
			WHERE meta_key = %d 
			AND meta_value = %s" , 
			$meta_key , $meta_value 
			) 
		);
	return intval($user_meta_query);
}

