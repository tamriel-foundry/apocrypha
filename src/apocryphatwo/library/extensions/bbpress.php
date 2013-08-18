<?php
/**
 * Apocrypha Theme bbPress Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-10-2013
 */


/*---------------------------------------------
1.0 - FORUM ARCHIVE
----------------------------------------------*/ 
/**
 * Display forums hierarchically instead of the bbPress default
 * Parent categories are seperated with child subforums
 * @version 1.0.0
 */
function apoc_list_subforums( $args = array() ) {

	// Defaults arguments
	$defaults = array (
		'before'            => '',
		'after'             => '',
		'count_before'      => '<p class="topic-count">Topics: <span class="post-count">',
		'count_after'       => '</span></p>',
		'count_sep'         => '</span></p><p class="reply-count">Replies: <span class="post-count">',
		'separator'         => '',
		'forum_id'          => '',
		'show_topic_count'  => true,
		'show_reply_count'  => true,
		'show_freshness_link'  => true,
	);				
	$args = wp_parse_args( $args, $defaults );
				
	// Loop through forums and create a list
	$subforums = bbp_forum_get_subforums();
	if ( !empty( $subforums ) ) {
	
		// Total count (for separator)
		$total = count( $subforums );
		
		// Count evens and odds
		$i = 1;
		
		// Loop through subforums and display them in full
		foreach ( $subforums as $subforum ) {
			
			// Get forum details
			$sub_id		= $subforum->ID;
			$title    	= $subforum->post_title;
			$desc		= $subforum->post_content;
			$permalink 	= bbp_get_forum_permalink( $sub_id );
			
			// Get topic and reply counts
			$topics	 	= bbp_get_forum_topic_count( $sub_id , false );
			$replies 	= bbp_get_forum_reply_count( $sub_id , false );
			$total		= $topics + $replies;
			
			// Build the html class
			$class = ( $i % 2 ) ? "sub-forum odd " : "sub-forum even ";
			$class .= bbp_get_forum_status( $sub_id );
			
			// Build output
			ob_start(); ?>
			<li id="forum-<?php echo $sub_id ?>" class="<?php echo $class; ?>">
				<div class="forum-content">
					<h3 class="forum-title"><a href="<?php echo $permalink; ?>" title="Browse <?php echo $title; ?>"><?php echo $title; ?></a></h3>
					<p class="forum-description"><?php echo $desc; ?></p>
				</div>

				<div class="forum-count">
					<?php echo $total; ?>
				</div>

				<div class="forum-freshness">
					<?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id( $sub_id ), 'type' => 'avatar' , 'size' => 50 ) ); ?>
					<div class="freshest-meta">
						<a class="freshest-title" href="<?php bbp_forum_last_reply_url( $sub_id ); ?>" title="<?php bbp_forum_last_topic_title( $sub_id ); ?>"><?php bbp_forum_last_topic_title( $sub_id ); ?></a>
						<span class="freshest-author">By <?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id( $sub_id ), 'type' => 'name' ) ); ?></span>
						<span class="freshest-time"><?php bbp_forum_last_active_time( $sub_id ); ?></span>
					</div>
				</div>
			</li><?php 
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		
		// Output the list
		echo $before . $output . $after;
	}
}

/* 
 * Display a custom freshness block for subforums
 * @since 0.1
 */
function apoc_subforum_freshness( $subforum_id = '' ) {			
	$output = '';
	if ( !empty( $subforum_id ) ) {
		$author_id = bbp_get_forum_last_reply_author_id( $subforum_id );
		$output .= '<a class="freshest-topic-title" href="' . bbp_get_forum_last_reply_url( $subforum_id ) . '">' . bbp_get_forum_last_topic_title( $subforum_id ) . '</a>';
		$output .= '<div class="freshest-topic-meta">';
		$output .= '<span class="freshest-author">By: ';
		$output .= bbp_get_author_link( array( 'post_id' => bbp_get_forum_last_reply_id( $subforum_id ) , 'type' => 'name' ) ) . ' ';
		$output .= bbp_get_author_link( array( 'post_id' => bbp_get_forum_last_reply_id( $subforum_id ) , 'type' => 'avatar' , 'size' => 20 ) );
		$output .= '</span> ';
		$output .= '<span class="freshness-time">';
		$output .= bbp_get_forum_last_active_time( $subforum_id );
		$output .= '</span></div>';
	}
	return $output;
} 

/*---------------------------------------------
2.0 - SINGLE TOPICS
----------------------------------------------*/
function apoc_topic_header_class( $topic_id = 0 ) {
	$topic_id = bbp_get_topic_id( $topic_id );
	
	// Generate some classes
	$classes = array();
	$classes[] = 'page-header-' . rand(1,6);
	$classes[] = bbp_is_topic_sticky( $topic_id, false ) ? 'sticky'       : '';
	$classes[] = bbp_is_topic_super_sticky( $topic_id  ) ? 'super-sticky' : '';
	$classes[] = 'status-' . get_post_status( $ID );
	
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
	$reply_count = sprintf( _n( '%d posts' , '%d posts', $reply_count ) , $reply_count );
	$voice_count = sprintf( _n( '%s member', '%s members', $voice_count	) , $voice_count );

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
 * Filter the element class list for topics to only say replies
 * @version 1.0.0
 */
add_filter( 'bbp_get_reply_class', 'apoc_reply_class' );
function apoc_reply_class( $classes ) {
	$classes[1] = 'reply';
	return $classes;
}


/**
 * Output custom bbPress admin links
 * @version 1.0.0
 */
function apoc_reply_admin_links( $id ) {
	
	// Make sure it's a logged-in user
	if ( !is_user_logged_in() ) return false;
		
	// Get post id and setup desired links
	$links = array();
	
	// Add common quote and reply links
	$links['quote'] 		= apoc_quote_button( 'reply' );
	$links['reply']			= '<a class="reply-link button button-dark" href="#new-post" title="Quick Reply"><i class="icon-reply"></i>Reply</a>';
	
	// NOTE: Icons and labels that are commented out are because bbPress runs annoying esc_html() on the input arguments.
	// I submitted a ticket to the bbPress trac, but in the meantime I'll have to either core hack it, or wait for patch.
	
	// Topic admin links
	if( bbp_is_topic( $id ) ) :
		$links['edit'] 		= bbp_get_topic_edit_link  ( array( 'edit_text' => '<i class="icon-edit"></i>Edit' ) );
		$links['close']		= bbp_get_topic_close_link ( array( 
								'close_text'	=> '<i class="icon-lock"></i>Close',
								'open_text'		=> '<i class="icon-unlock"></i>Open',		
								) );
		$links['stick']		= bbp_get_topic_stick_link ( array(
								'stick_text' 	=> '<i class="icon-pushpin"></i>Stick',
								'unstick_text' 	=> '<i class="icon-level-down"></i>Unstick',
								'super_text' 	=> '<i class="icon-paper-clip"></i>Notice', ) );
		$links['merge']		= bbp_get_topic_merge_link ( array( 'merge_text'=> '<i class="icon-code-fork"></i>Merge') );
		$links['trash']		= bbp_get_topic_trash_link ( array(
								'trash_text' 	=> '<i class="icon-trash"></i>Trash',
								'restore_text' 	=> '<i class="icon-undo"></i>Restore',
								'delete_text' 	=> '<i class="icon-remove"></i>Delete',
								'sep'			=> '',
								) );
									
	// Reply admin links
	else :
		$links['edit'] 		= bbp_get_reply_edit_link (	array( 'edit_text'  => '<i class="icon-edit"></i>Edit' ) );
		$links['move'] 		= bbp_get_reply_move_link (	array( 'split_text' => '<i class="icon-move"></i>Move' ) );
		$links['split'] 	= bbp_get_topic_split_link( array( 'split_text' => '<i class="icon-code-fork"></i>Split' ) );
		$links['trash'] 	= bbp_get_reply_trash_link( array( 
								'trash_text' 	=> '<i class="icon-trash"></i>Trash',
								'restore_text' 	=> '<i class="icon-undo"></i>Restore',
								'delete_text' 	=> '<i class="icon-remove"></i>Delete',
								'sep'			=> '',
								) );
	endif;
	
	// Get the admin links!
	bbp_reply_admin_links( $args = array(
		'id'		=> $id,
		'before'	=> '',
		'after'		=> '',
		'sep'		=> '',
		'links'		=> $links,
	));
}


/**
 * Prepend an icon to the revision log
 * @version 1.0.0
 */
add_filter( 'bbp_get_reply_revision_log', 'apoc_custom_revision_log' );
add_filter( 'bbp_get_topic_revision_log', 'apoc_custom_revision_log' );
function apoc_custom_revision_log( $revision ) {
	$revision = str_replace( 'revision-log">' , 'revision-log icons-ul double-border top">' , $revision );
	$revision = str_replace( 'revision-log-item">' , 'revision-log-item"><i class="icon-li icon-edit"></i>' , $revision );
	return $revision;
}

/**
 * Count the total number of times a topic has been favorited
 * @version 1.0.0
 */
add_action( 'bbp_add_user_favorite' 	, 'apoc_favorite_count_plus' 	, 10 , 2 );
add_action( 'bbp_remove_user_favorite' 	, 'apoc_favorite_count_minus' 	, 10 , 2 );
function apoc_favorite_count_plus( $user_id , $topic_id ) {
	
	// Get the favorite count, converting missing to zero
	$count = (int) get_post_meta( $topic_id , 'topic_fav_count' , true );
	
	// Save the incremented value
	update_post_meta( $topic_id , 'topic_fav_count' , ++$count );
}
function apoc_favorite_count_minus( $user_id , $topic_id ) {
	
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
 * Display the total number of favorites a topic has recieved
 * @version 1.0.0
 */
function apoc_total_favs( $topic_id = 0 , $echo = true ) {

	// If a topic ID wasn't given, grab it from inside the loop
	if( empty( $topic_id ) )
		$topic_id = bbp_get_topic_id();
		
	// Get the number of favorites
	$favs = get_post_meta( $topic_id , 'topic_fav_count' , true );
	
	// Display it, if positive
	if ( 0 < $favs && $echo )
		echo '<span class="total-fav-count"><i class="icon-trophy"></i>' . $favs . '</span>';
	else
		return $favs;
}
	
/**
 * Prevent users from favoriting their own posts
 * @version 1.0.0
 */
add_filter( 'bbp_get_user_favorites_link' , 'apoc_disallow_author_favorite' , 10 , 4 );
function apoc_disallow_author_favorite( $html, $r, $user_id, $topic_id ) {

	// Prevent a topic author from favoriting him/herself
	if ( $user_id == bbp_get_topic_author_id() )
		return false;
	
	// Otherwise, allow the link
	else return $html;
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
 
 

/*---------------------------------------------
X.X - NEW POSTS
----------------------------------------------*/ 
 
/**
 * Special bbPress allowed KSES
 * @version 1.0.0
 */
add_filter( 'bbp_kses_allowed_tags', 'apoc_bbp_allowed_kses' );
function apoc_bbp_allowed_kses( $allowed ) {
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
 * Prevent bbpress from escaping topic and reply content in the editor.
 * @version 1.0.0
 */
remove_filter( 'bbp_get_form_forum_content', 'esc_textarea' );
remove_filter( 'bbp_get_form_topic_content', 'esc_textarea' );
remove_filter( 'bbp_get_form_reply_content', 'esc_textarea' );


/*---------------------------------------------
X.X - BUDDYPRESS INTEGRATION
----------------------------------------------*/
/** 
 * Modify reply content when it is passed to the activity stream
 * Includes quote mentions before stripping quotes
 * @version 1.0.0
 */
add_filter( 'bbp_activity_reply_create_excerpt' , 'apoc_activity_replace_quote' );
function apoc_activity_replace_quote( $reply_content ) {
	
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
		$reply_content = bp_activity_at_name_filter( $reply_content );
		$reply_content = strip_shortcodes( $reply_content );
	endif;
	
	// Return the excerpt
	return $reply_content;
}


?>