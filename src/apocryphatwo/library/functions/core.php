<?php
/**
 * Apocrypha Theme Core Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-1-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
/*---------------------------------------------
1.0 - INCLUDE TEMPLATE ELEMENTS
----------------------------------------------*/

// Navigation
function apoc_primary_menu() {
	include( THEME_DIR . '/library/templates/menu-primary.php' );
}

// Sidebar
function apoc_primary_sidebar() {
	include( THEME_DIR . '/library/templates/sidebar-primary.php' );
}

// Comment Respond Form
function apoc_comment_form() {
	include( THEME_DIR . '/library/templates/respond.php' );
}
 
// Search Form
function apoc_get_search_form( $search_type = '' ) {
	include( THEME_DIR . '/library/templates/searchform.php' );
}

// Posts Loop
function apoc_display_post() {
	include( THEME_DIR . '/library/templates/loop-single-post.php' );
}


/*---------------------------------------------
2.0 - QUERY POSTS
----------------------------------------------*/
/**
 * Tamriel Foundry homepage have_posts query
 * @version 1.0.0
 */
function homepage_have_posts() {

	$apoc = apocrypha();
	
	$posts_per_page = $apoc->posts_per_page;
	$paged 			= $apoc->paged;
	$offset 		= ( $posts_per_page * $paged ) - $posts_per_page;
	$guild_cats 	= '-'.get_cat_ID( 'entropy rising' ) . ',-' . get_cat_ID( 'guild news' );
	
	$args = array( 
		'paged'=> $paged, 
		'posts_per_page'=> $posts_per_page,
		'offset' => $offset,
		'cat' => $guild_cats,
		);
		
	query_posts( $args );
}

?>