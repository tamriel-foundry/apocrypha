<?php
/**
 * Apocrypha Theme bbPress Functions
 * Andrew Clayton
 * Version 1.0.4
 * 8-12-2014

----------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.0 - Apoc BBPress Class
2.0 - Forum Archives
3.0 - Single Topics
4.0 - Best-Of Topics
--------------------------------------------------------------*/
 
 /*---------------------------------------------
1.0 - APOC BBPRESS CLASS
----------------------------------------------*/ 
class Apoc_bbPress {

	/**
	 * Construct the bbPress Class
	 * @version 1.0.0
	 */
	function __construct() {
	
		// Add actions
		$this->actions();
		
		// Register filters
		$this->filters();
	}

	/**
	 * Custom bbPress actions
	 * @version 1.0.0
	 */	
	function actions() {
	
		// Modify bbPress header contents
		add_action( 'bbp_theme_compat_actions'	, array( $this , 'remove_head' ) );
	
		// Increment Favorite Counts
		add_action( 'bbp_add_user_favorite' 	, array( $this , 'fav_count_plus' )	, 10 , 2 );
		add_action( 'bbp_remove_user_favorite' 	, array( $this , 'fav_count_minus' ), 10 , 2 );
	}
	
	
	/**
	 * Custom bbPress filters
	 * @version 1.0.0
	 */
	function filters() {
	
		// IMPORTANT - Don't let bbPress do theme compatibility
		remove_filter( 'bbp_template_include',   'bbp_template_include_theme_compat',   4, 2 );
		
		// Remove Editor JavaScript
		add_filter( 'bbp_default_scripts'								, array( $this , 'remove_scripts' ) );
		
		// Topic Title Length
		add_filter( 'bbp_get_title_max_length'							, array( $this , 'title_length' ) );
		
		// Reply CSS Class
		add_filter( 'bbp_get_reply_class'								, array( $this , 'reply_class' ) );
		
		// Subscribe and Favorite Buttons
		add_filter( 'bbp_before_get_user_favorites_link_parse_args' 	, array( $this , 'favorite_button' ) );
		add_filter( 'bbp_before_get_user_subscribe_link_parse_args' 	, array( $this , 'subscribe_button' ) );
		add_filter( 'bbp_is_subscriptions'								, array( $this , 'subscriptions_component' ) );
		
		// Prevent Self-Favoriting
		add_filter( 'bbp_get_user_favorites_link' 						, array( $this , 'disallow_self_favorite' ) , 10 , 4 );
		
		// Revision Logs
		add_filter( 'bbp_get_reply_revision_log'						, array( $this , 'revision_log' ) );
		add_filter( 'bbp_get_topic_revision_log'						, array( $this , 'revision_log' ) );
		
		// Allow additional formatting options
		add_filter( 'bbp_kses_allowed_tags'								, array( $this , 'allowed_kses' ) );
		
		// Quote Mentions
		add_filter( 'bbp_activity_reply_create_excerpt' 				, array( $this , 'quote_mention' ) );
		
		// Block topic spam
		add_filter( 'bbp_new_topic_pre_title' 	, array( $this , 'block_spam' ) );
	}
	
	/**
	 * Prevent bbPress from including scripts and styles in the header
	 * @version 1.0.2
	 */	
	function remove_head( $admin ) {
		remove_action( 'bbp_enqueue_scripts' 	, array( $admin, 'enqueue_styles'  	) );
		remove_action( 'bbp_head'				, array( $admin, 'head_scripts' 	) );
	}
	
	/**
	 * Prevent bbPress from including editor javascript on every page
	 * @version 1.0.2
	 */
	function remove_scripts( $scripts ) {
		unset( $scripts['bbpress-editor'] );
		return $scripts;
	}
	
	
	/**
	 * Set an intelligent maximum topic title length
	 * @version 1.0.0
	 */
	function title_length( $length ) {
		return 70;
	}
	
	/**
	 * Filter the element class list for topics to only say replies
	 * @version 1.0.0
	 */
	function reply_class( $classes ) {
		$classes[1] = 'reply';
		return $classes;
	}
	
	/** 
	 * Apply custom styling to favorite and subscribe buttons
	 * @version 1.0.0
	 */
	function favorite_button( $r ) {
		$r = array (
			'favorite'		=> '<i class="icon-thumbs-up"></i>This Thread Rocks',
			'favorited'		=> '<i class="icon-thumbs-down"></i>This Got Ugly',
			'before'    	=> '',
			'after'     	=> '',
		);
		return $r;
	}
	function subscribe_button( $r ) {
		$r = array(
				'subscribe'		=> '<i class="icon-bookmark"></i>Subscribe',
				'unsubscribe'	=> '<i class="icon-remove"></i>Unsubscribe',
				'before'    	=> '',
				'after'     	=> '',
			);
		return $r;
	}
	function subscriptions_component() {
		if ( bp_is_user() ) return true;
		else return false;
	}

	/**
	 * Prepend an icon to the revision log
	 * @version 1.0.0
	 */

	function revision_log( $revision ) {
		$revision = str_replace( 'revision-log">' , 'revision-log icons-ul double-border top">' , $revision );
		$revision = str_replace( 'revision-log-item">' , 'revision-log-item"><i class="icon-li icon-edit"></i>' , $revision );
		return $revision;
	}

	/**
	 * Increment Best-Of Favorite Counts
	 * @version 1.0.0
	 */
	function fav_count_plus( $user_id , $topic_id ) {
		
		// Get the favorite count, converting missing to zero
		$count = (int) get_post_meta( $topic_id , 'topic_fav_count' , true );
		
		// Save the incremented value
		update_post_meta( $topic_id , 'topic_fav_count' , ++$count );
	}
	function fav_count_minus( $user_id , $topic_id ) {
		
		// Get the favorite count, converting missing to zero
		$count = (int) get_post_meta( $topic_id , 'topic_fav_count' , true );
		
		// Don't let the count go below zero
		$count = max( $count , 1 );
		
			// Save the decremented value
		if ( $count > 1 )
			update_post_meta( $topic_id , 'topic_fav_count' , --$count );
			
		// If the count would be going to zero, just delete the postmeta entirely
		else
			delete_post_meta( $topic_id , 'topic_fav_count' );
	}
	
	/**
	 * Prevent users from favoriting their own posts
	 * @version 1.0.0
	 */
	
	function disallow_self_favorite( $html, $r, $user_id, $topic_id ) {

		// Prevent a topic author from favoriting him/herself
		if ( $user_id == bbp_get_topic_author_id() )
			return false;
		
		// Otherwise, allow the link
		else return $html;
	}
	
	/**
	 * Block certain recurring spam topics
	 * @version 1.0.4
	 */	
	function block_spam( $topic_title ) {
	
		// Set up an array of (lowercase) bad words and their point value
		$illegals = array(
			'vashikaran',
			'baba ji',
			'love problem',
			'marriage problem',
			'+91',
			'+91',
			'+O99',
			'91-85',
			'91-99',
			'919914',
		);
		
		// Get the all-lowercase title
		$spam_title = strtolower( $topic_title );
		
		// Check for any of the illegals in the title
		foreach ( $illegals as $illegal ) {
			if ( strpos( $spam_title , $illegal ) !== false ) {
			
				// If the topic matches as spam, let's ban the user
				$user = new WP_User( get_current_user_id() );
				$user->set_role('banned');	
				
				// Send an email letting me know
				$headers 	= "From: Foundry Discipline Bot <noreply@tamrielfoundry.com>\r\n";
				$headers	.= "Content-Type: text/html; charset=UTF-8";
				$subject 	= 'User ' . $user->user_login . ' banned for spamming.';
				$body 		= 'The user ' . bp_core_get_userlink( $user->ID ) . ' was banned for attempting to post the topic: "' . $topic_title . '".';
				wp_mail( 'atropos@tamrielfoundry.com' , $subject , $body , $headers );
			
				// Trigger an error, preventing the topic from posting
				bbp_add_error( 'apoc_topic_spam' , '<strong>ERROR</strong>: Die, filthy spammer!' );
				
				// Log the user out
				wp_logout();
				break;
			}
		}
		
		return $topic_title;
	}

	
	/**
	 * Special bbPress allowed KSES
	 * @version 1.0.0
	 */
	function allowed_kses( $allowed ) {
		$allowed['div']['class']	= array();
		$allowed['div']['style']	= array();
		$allowed['p']['class']		= array();
		$allowed['p']['style']		= array();
		$allowed['h1']['style']		= array();
		$allowed['h2']['style']		= array();
		$allowed['h3']['style']		= array();
		$allowed['h4']['style']		= array();
		$allowed['h5']['style']		= array();
		$allowed['h6']['style']		= array();
		$allowed['span']['style']	= array();
		return $allowed;
	}

	/** 
	 * Modify reply content when it is passed to the activity stream
	 * Includes quote mentions before stripping quotes
	 * @version 1.0.0
	 */
	function quote_mention( $reply_content ) {
		
		// Match the pattern for quote shortcodes
		$thequote = '#\[quote(.*)\](.*)\[\/quote\]#is';
		if ( preg_match( $thequote , $reply_content ) ) :
		
			// If there are quotes found, match the quoted usernames
			$author_pattern = '#(?<=\[quote author=")(.+?)(?=\|)#i';
			preg_match_all( $author_pattern , $reply_content , $authors );
			
			// For each username, turn it into a mention
			if ( isset( $authors ) ) :
				$authors = array_unique( $authors[0] );
				count( $authors ) > 1 ? $grammar = ' were quoted:' : $grammar = ' was quoted:';
				$mentions = implode( ",@" , $authors );
				$mentions = str_replace( " ", "-", $mentions );
				$mentions = str_replace( ".", "-", $mentions );
				$mentions = '<p><span class="activity-quote-mention">@'. $mentions . $grammar . '</span></p>';
			endif;
			
			// Add the mentions to the content and register them with BuddyPress
			$reply_content = $mentions . $reply_content ;
			$reply_content = strip_shortcodes( $reply_content );
			$reply_content = bp_activity_at_name_filter( $reply_content );
		endif;
		
		// Return the excerpt
		return $reply_content;
	}	
}

/*---------------------------------------------
2.0 - FORUM ARCHIVE
----------------------------------------------*/ 
/**
 * Display forums hierarchically instead of the bbPress default
 * Parent categories are seperated with child subforums
 * @version 1.0.3
 */
function apoc_list_subforums( $args = array() ) {

	// Loop through forums and create a list
	$subforums = bbp_forum_get_subforums();
	if ( !empty( $subforums ) ) {
	
		// Total count (for separator)
		$total = count( $subforums );
		
		// Count evens and odds
		$i = 1;
		
		// Loop through subforums and store output in a buffer
		ob_start();
		foreach ( $subforums as $subforum ) :
			
			// Get forum details
			$sub_id			= $subforum->ID;
			$title			= $subforum->post_title;
			$desc			= $subforum->post_content;
			$permalink		= bbp_get_forum_permalink( $sub_id );
			
			// Get topic counts
			$topics	 		= bbp_get_forum_topic_count( $sub_id , false );
			
			// Get the most recent reply
			$reply_id		= bbp_get_forum_last_reply_id( $sub_id );
			$topic_id		= bbp_is_reply( $reply_id ) ? bbp_get_reply_topic_id( $reply_id ) : $reply_id;
			$topic_title	= bbp_get_topic_title( $topic_id );
			$link 			= bbp_get_reply_url( $reply_id );
			
			// Build the html class
			$class = ( $i % 2 ) ? "sub-forum odd " : "sub-forum even ";
			$class .= bbp_get_forum_status( $sub_id );
			
			// Build output ?>
			<li id="forum-<?php echo $sub_id ?>" class="<?php echo $class; ?>">
				<div class="forum-content">
					<h3 class="forum-title"><a href="<?php echo $permalink; ?>" title="Browse <?php echo $title; ?>"><?php echo $title; ?></a></h3>
					<p class="forum-description"><?php echo $desc; ?></p>
				</div>

				<div class="forum-count">
					<?php echo $topics; ?>
				</div>

				<div class="forum-freshness">
					<?php bbp_author_link( array( 'post_id' => $reply_id, 'type' => 'avatar' , 'size' => 50 ) ); ?>
					<div class="freshest-meta">
						<a class="freshest-title" href="<?php echo $link; ?>" title="<?php echo $topic_title; ?>"><?php echo $topic_title; ?></a>
						<span class="freshest-author">By <?php bbp_author_link( array( 'post_id' => $reply_id, 'type' => 'name' ) ); ?></span>
						<span class="freshest-time"><?php bbp_topic_last_active_time( $topic_id ); ?></span>
					</div>
				</div>
			</li>
		<?php endforeach;
		
		// Output the list
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
	}
}

/*---------------------------------------------
3.0 - SINGLE TOPICS
----------------------------------------------*/
function apoc_topic_header_class( $topic_id = 0 ) {
	$topic_id = bbp_get_topic_id( $topic_id );
	
	// Generate some classes
	$classes = array();
	$classes[] = 'page-header-' . rand(1,6);
	$classes[] = bbp_is_topic_sticky( $topic_id, false ) ? 'sticky'       : '';
	$classes[] = bbp_is_topic_super_sticky( $topic_id  ) ? 'super-sticky' : '';
	$classes[] = 'status-' . get_post_status( $topic_id );
	
	// Output it
	echo join( ' ', $classes );
}

/* 
 * Display a custom freshness block for subforums
 * @since 0.1
 */
function apoc_topic_description( $args = '' ) {

	// Default arguments
	$defaults = array (
		'topic_id'  => 0,
		'before'    => '<p class="entry-byline">',
		'after'     => '</p>',
		'size'		=> 50,
		'echo'		=> true,
	);
	$args = wp_parse_args( $args, $defaults );

	// Validate topic_id
	$topic_id = bbp_get_topic_id( $args['topic_id'] );

	// Build the topic description
	$voice_count	= bbp_get_topic_voice_count ( $topic_id );
	$reply_count	= bbp_get_topic_reply_count ( $topic_id , true ) + 1;
	$time_since  	= bbp_get_topic_freshness_link ( $topic_id );
	$author			= bbp_get_author_link( array( 'post_id' => $topic_id , 'size' => $args['size'] ) );

	// Singular/Plural
	$reply_count = sprintf( _n( '%d posts' , '%d posts', $reply_count ) 	, $reply_count );
	$voice_count = sprintf( _n( '%s member', '%s members', $voice_count	) 	, $voice_count );

	// Topic has replies
	$last_reply = bbp_get_topic_last_active_id( $topic_id );
	if ( !empty( $last_reply ) ) :
		$last_updated_by = bbp_get_author_link( array( 'post_id' => $last_reply, 'type' => 'name' ) );
		$retstr = sprintf( 'This topic by%1$s contains %2$s by %3$s, and was last updated by %4$s, %5$s.', $author, $reply_count, $voice_count, $last_updated_by, $time_since );

	// Topic has no replies
	elseif ( ! empty( $voice_count ) && ! empty( $reply_count ) ) :
		$retstr = sprintf( 'This topic contains %1$s by %2$s.', $reply_count, $voice_count );

	// Topic has no replies and no voices
	elseif ( empty( $voice_count ) && empty( $reply_count ) ) :
		$retstr = sprintf( 'This topic has no replies yet.' );
	endif;

	// Combine the elements together
	$retstr = $args['before'] . $retstr . $args['after'];

	// Return filtered result
	if ( true == $args['echo'] )
		echo $retstr;
	else
		return $retstr;
}


/**
 * Display a warning notice on forums which have special posting rules
 * @version 1.0.0
 */
function apoc_forum_rules() {

	// Determine context, and get the correct forum ID
	if ( bbp_is_single_forum() ) :
		$forum_id = bbp_get_forum_id();
	elseif ( bbp_is_single_topic() || bbp_is_topic_edit() || bbp_is_reply_edit() ) :
		$forum_id = bbp_get_topic_forum_id();
	endif;
	
	// Check whether the forum has special rules
	$rules = get_post_meta( $forum_id , 'forum-rules' , true );
	if ( '' != $rules ) {
		echo '<div class="warning">' . $rules . '</div>';
	}
}

/**
 * Output custom bbPress admin links
 * @version 1.0.0
 */
function apoc_reply_admin_links( $reply_id ) {
	
	// Make sure it's a logged-in user
	if ( !is_user_logged_in() ) return false;
		
	// Get post id and setup desired links
	$links = array();
	
	// Add common quote and reply links
	$links['quote'] 		= apoc_quote_button( 'reply' , $reply_id );
	$links['reply']			= '<a class="reply-link button button-dark" href="#new-post" title="Quick Reply"><i class="icon-reply"></i>Reply</a>';
	
	// Topic admin links
	if( bbp_is_topic( $reply_id ) ) :
		$links['edit'] 		= bbp_get_topic_edit_link  ( array( 
								'id'			=> $reply_id,
								'edit_text' 	=> '<i class="icon-edit"></i>Edit' ) );
		$links['close']		= bbp_get_topic_close_link ( array( 
								'id'			=> $reply_id,
								'close_text'	=> '<i class="icon-lock"></i>Close',
								'open_text'		=> '<i class="icon-unlock"></i>Open',		
								) );
		$links['stick']		= bbp_get_topic_stick_link ( array(
								'id'			=> $reply_id,
								'stick_text' 	=> '<i class="icon-pushpin"></i>Stick',
								'unstick_text' 	=> '<i class="icon-level-down"></i>Unstick',
								'super_text' 	=> '<i class="icon-paper-clip"></i>Notice', ) );
		$links['merge']		= bbp_get_topic_merge_link ( array( 'merge_text'=> '<i class="icon-code-fork"></i>Merge') );
		$links['trash']		= bbp_get_topic_trash_link ( array(
								'id'			=> $reply_id,
								'trash_text' 	=> '<i class="icon-trash"></i>Trash',
								'restore_text' 	=> '<i class="icon-undo"></i>Restore',
								'delete_text' 	=> '<i class="icon-remove"></i>Delete',
								'sep'			=> '',
								) );
									
	// Reply admin links
	else :
		$links['edit'] 		= bbp_get_reply_edit_link (	array( 
								'id'			=> $reply_id,
								'edit_text'  	=> '<i class="icon-edit"></i>Edit' ) );
		$links['move'] 		= bbp_get_reply_move_link (	array( 
								'id'			=> $reply_id,
								'split_text' 	=> '<i class="icon-move"></i>Move' ) );
		$links['split'] 	= bbp_get_topic_split_link( array( 
								'id'			=> $reply_id,
								'split_text' 	=> '<i class="icon-code-fork"></i>Split' ) );
		$links['trash'] 	= bbp_get_reply_trash_link( array( 
								'id'			=> $reply_id,
								'trash_text' 	=> '<i class="icon-trash"></i>Trash',
								'restore_text' 	=> '<i class="icon-undo"></i>Restore',
								'delete_text' 	=> '<i class="icon-remove"></i>Delete',
								'sep'			=> '',
								) );
	endif;
	
	// Get the admin links!
	bbp_reply_admin_links( array(
		'id'		=> $reply_id,
		'before'	=> '',
		'after'		=> '',
		'sep'		=> '',
		'links'		=> $links,
	));
}

/*---------------------------------------------
4.0 - BEST-OF TOPICS
----------------------------------------------*/

/**
 * Display the total number of favorites a topic has recieved
 * @version 1.0.0
 */
function apoc_total_favs( $topic_id = 0 , $echo = true ) {

	// If a topic ID wasn't given, grab it from inside the loop
	if( empty( $topic_id ) )
		$topic_id = bbp_get_topic_id();
		
	// Get the number of favorites
	$favs = get_post_meta( $topic_id , 'topic_fav_count' , true );
	
	// Maybe we are just returning the raw number
	if ( !$echo ) 
		return $favs;
		
	// Otherwise, determine whether the topic gets an award
	$class = false;
	if ( $favs >= 250 )		$class = 'legendary';
	elseif ( $favs >= 50 ) 	$class = 'epic';
	elseif ( $favs >= 1 ) 	$class = 'heroic';
	
	// Echo the icon
	if ( $class ) echo '<span class="total-fav-count ' . $class . '" title="' . $favs . ' votes"></span>';
}
	

/**
 * Get the most favorited topics in the last 7 days
 * @version 1.0.0
 */
function bestof_has_topics() {

	// Setup query arguments
	$args = array(
		'post_type'			=> 'topic',
		'post_parent'		=> 'any',
		'posts_per_page'	=> 10,
		'meta_key'			=> 'topic_fav_count',
		'meta_value_num'	=> '0',
		'meta_compare'		=> '>',		
		'orderby' 			=> 'meta_value_num',
		'order'				=> 'DESC',
		'max_num_pages'		=> 1,
		'show_stickies'		=> false,
	);
	
	// Filter for just the past 7 days
	function filter_bestof_topics( $where = '' ) {
		$where .= " AND post_date > '" . date( 'Y-m-d' , strtotime( '-7 days' )) . "'";
		return $where;
	}	
	
	// Apply the filter, pass our arguments, and get topics
	add_filter( 'posts_where' , 'filter_bestof_topics' );
	$topics = bbp_has_topics( $args );
	remove_filter( 'posts_where' , 'filter_bestof_topics' );
	
	return $topics;
}


/**
 * Store the bbPress group forum pages in a buffer to put my own headers
 * @version 1.0.0
 */
add_action( 'bbp_before_group_forum_display' , 'apoc_group_forum_ob_start' );
function apoc_group_forum_ob_start() {
	ob_start();
}
add_action( 'bbp_after_group_forum_display' , 'apoc_group_forum_header' );
function apoc_group_forum_header() {

	// Get the buffered content
	$content = ob_get_contents();
	ob_end_clean();
	
	// Print the group forum header 
	if ( !bbp_is_single_forum() ) :
	
		// Get the title prefix
		$prefix = "";
		if ( bbp_is_topic_edit() || bbp_is_reply_edit() ) $prefix = "Edit: ";
		elseif ( bbp_is_topic_merge() ) $prefix = "Merge: ";
		elseif ( bbp_is_topic_split() ) $prefix = "Split: "; ?>
		
		<header id="forum-header" class="entry-header <?php apoc_topic_header_class(); ?>">
			<h1 class="entry-title"><?php echo $prefix . bbp_get_topic_title(); ?></h1>
			<?php apoc_topic_description(); ?>
			<div class="forum-actions">
				<?php apoc_get_search_form( 'topic' ); ?>
			</div>
		</header>
		<?php endif;

	// Echo the buffered content
	echo $content;
}


?>