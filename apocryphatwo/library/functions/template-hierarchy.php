<?php
/**
 * Apocrypha Theme Core Template Hierarchy
 * Andrew Clayton
 * Version 1.0
 * 8-1-2013
 */


/**
 * Controls template selection for author archive pages
 * The hierarchy is: author-{nicename}.php, author.php, archive.php
 * @since 1.0
 */
add_filter( 'author_template', 'apoc_author_template' );
function apoc_author_template( $template ) {
	
	// Get the author's name
	$name = get_the_author_meta( 'user_nicename', get_query_var( 'author' ) );

	// Add the user nicename template
	$templates = array();
	$templates[] = "author-{$name}.php";

	// Add a basic author template
	$templates[] = 'author.php';

	// Fall back to the basic archive template
	$templates[] = 'archive.php';

	// Return the found template.
	return locate_template( $templates );
}

/**
 * Controls template selection for category, tag, and taxonomy pages
 * The hierarchy is: taxonomy-{taxonomy}.php, taxonomy.php, archive.php
 * @since 1.0
 */
add_filter( 'tag_template'		, 'apoc_taxonomy_template' );
add_filter( 'category_template'	, 'apoc_taxonomy_template' );
add_filter( 'taxonomy_template'	, 'apoc_taxonomy_template' );
function apoc_taxonomy_template( $template ) {

	// Get the queried term object.
	$term = get_queried_object();

	// Return the available templates.
	return locate_template( array( "taxonomy-{$term->taxonomy}.php", 'taxonomy.php', 'archive.php' ) );
}

/**
 * Controls template selection for posts and custom post types
 * The hierarchy is: {custom-post-template}.php, singular-{post_type}.php, singular.php
 * @since 1.0
 */
add_filter( 'single_template' , 'apoc_singular_template' );
function apoc_singular_template( $template ) {
	
	// Get the queried post.
	$templates 	= array();
	$post 		= get_queried_object();
	
	// Check for a custom post template using the custom meta field key '_wp_post_template'.
	$custom = get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true );
	if ( $custom )
		$templates[] = $custom;

	// Add a template based off the post type
	$templates[] = "singular-{$post->post_type}.php";
	
	// Add a general template of singular.php.
	$templates[] = "singular.php";
	
	// Return the found template.
	return locate_template( $templates );
}

/**
 * Controls template selection for comments on posts
 * The hierarchy is: {comments_template_function_arg}.php, comments-{post_type}.php, comments.php
 * @since 1.0
 */
add_filter( 'comments_template'	, 'apoc_comments_template' );
function apoc_comments_template( $template ) {
	
	// If a custom template was entered in the comments_template( $file ) function
	$templates = array();
	$template = str_replace( trailingslashit( THEME_DIR ), '', $template );
	if ( 'comments.php' !== $template )
		$templates[] = $template;
	
	// Comment templates by post type
	$templates[] = 'comments-' . get_post_type() . '.php';	

	// Add the default comments template
	$templates[] = 'comments.php';
	
	// Return the found template
	return locate_template( $templates );
}
?>