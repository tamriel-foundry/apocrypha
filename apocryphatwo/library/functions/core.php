<?php
/**
 * Apocrypha Theme Core Functions
 * Andrew Clayton
 * Version 1.0
 * 8-1-2013
 */
 
/*---------------------------------------------
1.0 - INCLUDE TEMPLATE ELEMENTS
----------------------------------------------*/

// Navigation
function apoc_primary_menu() {
	locate_template( array( 'library/templates/menu-primary.php' ), true );
}

// Sidebar
function apoc_primary_sidebar() {
	locate_template( array( 'library/templates/sidebar-primary.php' ), true );
}

// Comment Respond Form
function apoc_comment_form() {
	locate_template( array( 'library/templates/respond.php' ), true );
}
 
// Search Form
function apoc_get_search_form( $search_type = '' ) {
	global $apocrypha;
	$apocrypha->search = $search_type;
	locate_template( array( 'library/templates/searchform.php' ), true );
}


/*---------------------------------------------
2.0 - QUERY POSTS
----------------------------------------------*/
/**
 * Tamriel Foundry homepage have_posts query
 * @since 1.0
 */
function homepage_have_posts() {
	$posts_per_page = 6;
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$offset = ( $posts_per_page * $paged ) - $posts_per_page;
	$guild_cats = '-'.get_cat_ID( 'entropy rising' ) . ',-' . get_cat_ID( 'guild news' );
	
	$args = array( 
		'paged'=> $paged, 
		'posts_per_page'=> $posts_per_page,
		'offset' => $offset,
		'cat' => $guild_cats,
		);
		
	query_posts( $args );
}

?>