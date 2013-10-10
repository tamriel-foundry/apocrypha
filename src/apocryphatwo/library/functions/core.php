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

// Admin Bar
function apoc_admin_bar() {
	include( THEME_DIR . '/library/templates/admin-bar.php' );
}

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
	if ( '' != $search_type )
		apocrypha()->search_type = $search_type;
	include( THEME_DIR . '/library/templates/searchform.php' );
}

// Posts Loop
function apoc_display_post() {
	include( THEME_DIR . '/library/templates/loop-single-post.php' );
}


// Load the Entropy Rising guild header, menu, and sidebar
function entropy_rising_header() {
	locate_template( array( 'guild/guild-header.php' ), true );
}
function entropy_rising_menu() {
	locate_template( array( 'guild/guild-menu.php' ), true );
}
function entropy_rising_sidebar() {
	locate_template( array( 'guild/guild-sidebar.php' ), true );
}


/*---------------------------------------------
2.0 - FILTER TEMPLATE HIERARCHY
----------------------------------------------*/

/**
 * Controls template selection for category, tag, and taxonomy pages
 * The hierarchy is: taxonomy-{taxonomy}.php, taxonomy.php, archive.php
 * @version 1.0.0
 */
add_filter( 'tag_template'		, 'apoc_taxonomy_template' );
add_filter( 'category_template'	, 'apoc_taxonomy_template' );
add_filter( 'taxonomy_template'	, 'apoc_taxonomy_template' );
function apoc_taxonomy_template( $template ) {

	// Get the queried term object.
	$term = apocrypha()->queried_object;

	// Return the available templates.
	return locate_template( array( "{$term->taxonomy}.php" , "taxonomy-{$term->taxonomy}.php" , 'taxonomy.php', 'archive.php' ) );
}

/**
 * Controls template selection for posts and custom post types
 * The hierarchy is: {custom-post-template}.php, singular-{post_type}.php, singular.php
 * @version 1.0.0
 */
add_filter( 'single_template' , 'apoc_singular_template' );
function apoc_singular_template( $template ) {
	
	// Get the queried post
	$post = apocrypha()->queried_object;
		
	// Check for a custom post template using the custom meta field key '_wp_post_template'.
	$custom = get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true );
	
	// Return the found template.
	return locate_template( array( $custom , "singular-{$post->post_type}.php" , "singular.php" )  );
}


/*---------------------------------------------
3.0 - QUERY POSTS
----------------------------------------------*/
/**
 * Tamriel Foundry homepage have_posts query
 * @version 1.0.0
 */
function homepage_have_posts() {

	$apoc 			= apocrypha();
	$ppp 			= $apoc->counts->ppp;
	$paged 			= $apoc->counts->paged;
	$offset 		= ( $ppp * $paged ) - $ppp;
	$guild_cats 	= '-'.get_cat_ID( 'entropy rising' ) . ',-' . get_cat_ID( 'guild news' );
	
	$args = array( 
		'paged'=> $paged, 
		'posts_per_page'=> $ppp,
		'offset' => $offset,
		'cat' => $guild_cats,
		);
		
	query_posts( $args );
}

/* 
 * Load the Entropy Rising guild posts loop
 */
function entropy_rising_have_posts() {
	$posts_per_page = 6;
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$offset = ( $posts_per_page * $paged ) - $posts_per_page;
	$guild_public = get_cat_ID( 'entropy rising' );
	$guild_private = get_cat_ID( 'guild news' );
	if ( is_user_guild_member() ) $guild_cats = $guild_public . ',' . $guild_private;
	else $guild_cats = $guild_public;

	$args = array( 
		'paged'=> $paged, 
		'posts_per_page'=> $posts_per_page,
		'offset' => $offset,
		'cat' => $guild_cats,
		);
		
	query_posts( $args );
}

/* 
 * My own default filtering set
 * @since 0.3
 */
function apoc_custom_kses( $content ) {
	$content = wp_filter_post_kses( $content );
	$content = wptexturize( $content );
	$content = wpautop( $content );
	$content = convert_chars( $content );
	$content = force_balance_tags( $content );
	return $content;
	}

?>