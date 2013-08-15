<?php
/**
 * Apocrypha Content Slider
 * Andrew Clayton
 * Version 1.0.0
 * 8-3-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
/**
* Set slideshow slide dimensions
* @version 1.0.0
*/
function slideshow_slide_dimensions() {
	$dimensions = array(
		'height'	=> 360,
		'width'		=> 720,
	);
	return $dimensions;
}

/**
 * Register a custom post type for Slides
 * @version 1.0.0
 */
add_action( 'init', 'register_content_slides' );
function register_content_slides() {

	// Labels for the backend slide publisher 
	$slide_labels = array(
		'name'					=> 'Slides',
		'singular_name'			=> 'Slide',
		'add_new'				=> 'Add New',
		'add_new_item'			=> 'Add New Slide',
		'edit_item'				=> 'Edit Slide',
		'new_item'				=> 'New Slide',
		'view_item'				=> 'View Slide',
		'search_items'			=> 'Search Slides',
		'not_found'				=> 'No slides found',
		'not_found_in_trash'	=> 'No slides found in Trash', 
		'parent_item_colon'		=> '',
		'menu_name'				=> 'Slides',
	);
	
	$slide_capabilities = array(
		'edit_post'				=> 'edit_post',
		'edit_posts'			=> 'edit_posts',
		'edit_others_posts'		=> 'edit_others_posts',
		'publish_posts'			=> 'publish_posts',
		'read_post'				=> 'read_post',
		'read_private_posts'	=> 'read_private_posts',
		'delete_post'			=> 'delete_post'
	);			
		
	// Construct the arguments for our custom slide post type 
	$slide_args = array(
		'labels'				=> $slide_labels,
		'description'			=> 'Front page showcase slides',
		'public'				=> true,
		'publicly_queryable'	=> false,
		'exclude_from_search'	=> true,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'show_in_nav_menus'		=> false,
		'menu_icon'				=> THEME_URI . '/images/icons/slide-icon-20.png',
		'capabilities'			=> $slide_capabilities,
		'map_meta_cap'			=> true,
		'hierarchical'			=> false,
		'supports'				=> array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'			=> array( 'slideshow' ),
		'has_archive'			=> false,
		'rewrite'				=> false,
		'query_var'				=> true,
		'can_export'			=> true,
	);
	
	// Register the Slide post type! 
	register_post_type( 'slide', $slide_args );
}

/**
 * Register a custom post taxonomy for Slideshows
 * @since 0.1
 */
add_action( 'init', 'register_slideshow_taxonomy' );
function register_slideshow_taxonomy() {
	
	$slideshow_tax_labels = array(			
		'name'							=> 'Slideshows',
		'singular_name'					=> 'Slideshow',
		'search_items'					=> 'Search Slideshows',
		'popular_items'					=> 'Popular Slideshows',
		'all_items'						=> 'All Slideshows',
		'edit_item'						=> 'Edit Slideshow',
		'update_item'					=> 'Update Slideshow',
		'add_new_item'					=> 'Add New Slideshow',
		'new_item_name'					=> 'New Slideshow Name',
		'menu_name'						=> 'Slideshows',
		'separate_items_with_commas'	=> 'Separate slideshows with commas',
		'choose_from_most_used'			=> 'Choose from the most used slideshows',
	);
	
	$slideshow_tax_caps = array(
		'manage_terms'	=> 'manage_categories',
		'edit_terms'	=> 'manage_categories',
		'delete_terms'	=> 'manage_categories',
		'assign_terms'	=> 'edit_posts'
	);
	
	$slideshow_tax_args = array(
		'labels'				=> $slideshow_tax_labels,
		'public'				=> true,
		'show_ui'				=> true,
		'show_in_nav_menus'		=> false,
		'show_tagcloud'			=> false,
		'hierarchical'			=> true,
		'rewrite'				=> array( 'slug' => 'slideshow' ),
		'capabilities'    	  	=> $slideshow_tax_caps,
	);

	// Register the Slideshow post taxonomy! 
	register_taxonomy( 'slideshow', 'slide', $slideshow_tax_args );	
}

/**
 * Add a featured image size for the slideshow
 * @since 0.1
 */
$slide_dimensions = slideshow_slide_dimensions();
add_image_size( 'featured-slide' , $slide_dimensions['width'] , $slide_dimensions['height'] , true );

/**
 * Function to load the slideshow in a theme template
 * @since 0.1
 */
function get_slideshow( $slideshow = '' , $number = 5 ) {	
	include( THEME_DIR . '/library/templates/slideshow.php' );	
} 
?>