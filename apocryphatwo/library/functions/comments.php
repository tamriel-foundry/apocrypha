<?php
/**
 * Apocrypha Comments Functions
 * Andrew Clayton
 * Version 2.0
 * 8-3-2013
 */

/**
 * Generate a number sensitive link to article comments
 * @since 2.0
 */
function apoc_comments_link() {
	$comments_link = '';
	$number = doubleval( get_comments_number() );
	$comments_link = '<a class="comments-link button" href="' . get_comments_link() . '" title="Article Comments">';
	if( $number == 0 ) :
		$comments_link .= 'Leave a Comment';
	elseif ( $number > 0 ) :
		$comments_link .= 'Comments <span class="comments-link-count activity-count">' . $number . '</span>';
	endif;
	$comments_link .= '</a>';
	if( $comments_link ) echo $comments_link;
}