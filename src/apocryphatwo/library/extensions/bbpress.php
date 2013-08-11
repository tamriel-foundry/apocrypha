<?php
/**
 * Apocrypha Theme bbPress Functions
 * Andrew Clayton
 * Version 1.0
 * 8-10-2013
 */


/*---------------------------------------------
1.0 - FORUM ARCHIVE
----------------------------------------------*/ 
/**
 * Display forums hierarchically instead of the bbPress default
 * Parent categories are seperated with child subforums
 * @since 1.0
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

/* Display a custom freshness block for subforums
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
X.X - NEW POSTS
----------------------------------------------*/ 
 
/**
 * Special bbPress allowed KSES
 * @since 1.0
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
 * @since 1.0
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
 * @since 1.0
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