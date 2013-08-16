<?php
/**
 * Apocrypha Theme Admin Framework Functions
 * Andrew Clayton
 * Version 0.1
 * 1-11-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
// Add the admin setup function to the 'admin_menu' hook.
add_action( 'admin_menu', 'setup_apocrypha_admin' ); 
 
/**
 * Set up the adminstration functionality for the framework
 * @since 0.1
 */
function setup_apocrypha_admin() {

	$apoc = apocrypha();

	// Load the SEO post meta box
	require_once( trailingslashit( $apoc->admin_dir ) . 'post-meta-boxes.php' );

	// Load the post template meta box.
	require_once( trailingslashit( $apoc->admin_dir ) . 'post-meta-template.php' );
}

/**
 * Function for grabbing the available templates with a specific header.
 * @since 0.1
 */
function apoc_get_post_templates( $post_type = 'post' ) {

	// Set up an empty post templates array.
	$post_templates = array();

	// Get the post type object.
	$post_type_object = get_post_type_object( $post_type );

	// Get the theme (parent theme if using a child theme) object.
	$theme = wp_get_theme( get_template(), get_theme_root( get_template_directory() ) );

	// Get the theme PHP files one level deep.
	$files = (array) $theme->get_files( 'php', 1 );

	// If a child theme is active, get its files and merge with the parent theme files.
	if ( is_child_theme() ) {
		$child = wp_get_theme( get_stylesheet(), get_theme_root( get_stylesheet_directory() ) );
		$child_files = (array) $child->get_files( 'php', 1 );
		$files = array_merge( $files, $child_files );
	}

	// Loop through each of the PHP files and check if they are post templates.
	foreach ( $files as $file => $path ) {

		// Get file data based on the post type singular name (e.g., "Post Template", "Book Template", etc.).
		$headers = get_file_data(
			$path,
			array( 
				"{$post_type_object->name} Template" => "{$post_type_object->name} Template",
			)
		);

		// Continue loop if the header is empty.
		if ( empty( $headers["{$post_type_object->name} Template"] ) )
			continue;

		// Add the PHP filename and template name to the array.
		$post_templates[ $file ] = $headers["{$post_type_object->name} Template"];
	}

	// Return array of post templates.
	return array_flip( $post_templates );
}

?>