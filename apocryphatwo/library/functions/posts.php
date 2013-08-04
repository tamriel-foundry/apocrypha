<?php
/**
 * Apocrypha Posts Functions
 * Andrew Clayton
 * Version 2.0
 * 8-3-2013
 */
 
/*---------------------------------------------
1.0 - SUPPORTS
----------------------------------------------*/ 
 
/**
 * Add extra support for post types within the Apocrypha theme.
 * @since 2.0
 */
add_action( 'init', 'apoc_post_type_support' );
function apoc_post_type_support() {

	// Add support for excerpts to pages
	add_post_type_support( 'page', array( 'excerpt' ) );
}

/**
 * Registers custom metadata keys that are used for SEO
 * @since 2.0
 */
add_action( 'init', 'apoc_register_postmeta' );
function apoc_register_postmeta() {
	
	// Register 'Title' and 'Description' meta for posts 
	register_meta( 'post' , 'Title'			, 'apoc_sanitize_meta' );
	register_meta( 'post' , 'Description' 	, 'apoc_sanitize_meta' );
	
	// Register custom post templates 
	$post_types = get_post_types( array( 'public' => true ) );
	foreach ( $post_types as $post_type ) {
		if ( 'page' !== $post_type )
			register_meta( 'post' , "_wp_{$post_type}_template" , 'apoc_santize_meta' );
		}
}

/**
 * Sanitizes meta data that is passed to custom post meta fields
 * @since 0.1
 */
function apoc_sanitize_meta( $meta_value , $meta_key , $meta_type ) {
	return strip_tags( $meta_value );
}


/*---------------------------------------------
2.0 - DISPLAYED ELEMENTS
----------------------------------------------*/

/**
 * Titles a post within the loop
 * @since 2.0
 */
function entry_header_title() {
	$title = '<a href="' . get_permalink() . '" title="Read Post, ' . get_the_title() . '">' . get_the_title() . '</a>';
	echo $title;
	}
 
/**
 * Describes a post within the loop
 * @since 2.0
 */
function entry_header_description() {
	global $post;
	$post_ID = $post->ID;
	$author_ID = $post->post_author;
	$type = $post->post_type;
	$description = '';
	
	// Posts 
	if ( $type == 'post' ) :
		
		// Get some info 
		$author = '<a class="post-author" href="' . get_author_posts_url( $author_ID ) . '" title="All posts by ' . get_the_author_meta( 'display_name' ) . '">' . get_the_author_meta( 'display_name' ) . '</a>';
		$published = '<time class="post-date" datetime="'. get_the_time( 'Y-m-d' ) . '">' . get_the_time( 'F j, Y' ) . '</time>';
		if ( current_user_can( 'edit_post' , $post_ID ) )
			$edit_link = '<a class="post-edit-link" href="' . get_edit_post_link( $post_ID ) . '" title="Edit this post" target="_blank">Edit</a>';
		
		// Keep it simple on the homepage 
		if ( is_home() ) :
			$description = 'By ' . $author . ' on ' . $published . $edit_link;
		else : 
			$description = 'By ' . $author . ' on ' . $published . $edit_link;
		endif;
			
	// Pages 
	elseif ( $type == 'page' ) :
		$description = get_post_meta( $post_ID , 'Description' , true );
		if ( current_user_can( 'edit_post' , $post_ID ) )
			$description = $description . '<a class="post-edit-link" href="' . get_edit_post_link( $post_ID ) . '" title="Edit this post" target="_blank">Edit</a>';
	
	endif;
	
	// Echo the post description 
	echo $description;
}

/**
 * Add a continue reading link to custom excerpts
 * First remove the default [...] text, then add a link
 * Next, make default excerpts a bit shorter for the home page
 * @since 0.1
 */
add_filter( 'excerpt_more', 'remove_excerpt_more' );
add_filter( 'get_the_excerpt', 'add_excerpt_more');
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
function remove_excerpt_more( $more ) {
	return '';
}
function add_excerpt_more( $excerpt ) {
	$more = '<a class="excerpt-more" href="'. get_permalink() . '" title="Continue Reading">[...]</a>';
	return $excerpt . ' ' . $more;
}
function custom_excerpt_length( $length ) {
	if ( is_home() )
		return 45;
	else
		return $length;
}

?>