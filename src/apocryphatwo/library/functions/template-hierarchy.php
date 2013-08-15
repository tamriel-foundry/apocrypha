<?php
/**
 * Apocrypha Theme Core Template Hierarchy
 * Andrew Clayton
 * Version 1.0.0
 * 8-1-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

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
?>