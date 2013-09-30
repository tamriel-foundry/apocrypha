<?php
/**
 * Apocrypha Theme SEO Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-15-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
1.0 - APOC_SEO CLASS
----------------------------------------------*/

/**
 * Sets a logical browser title for SEO. 
 * Also generates a <meta> description tag.
 * Adapts to handle WordPress, bbPress, and BuddyPress pages.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
class Apoc_SEO {

	// Declare variables
	public $title;
	public $description;

	// Construct it
	function __construct() {
		
		// Populate the seo
		$this->get_document_seo();
	}
	
	
	/**
	 * Generates the document title and description
	 * @version 1.0.0
	 */
	function get_document_seo() {
		
		// Start with some default variables
		$apoc		= apocrypha();
		$separator 	= ' &bull; ';
		$sitename 	= SITENAME;
		$id			= $apoc->queried_id;
		$object		= $apoc->queried_object;
				
		// Homepage
		if ( is_home() ) {
			$description		= get_bloginfo( 'description' );
			$doctitle 			= $sitename . $separator . $description;
		}

		// BuddyPress Pages
		elseif ( class_exists( 'BuddyPress' ) && is_buddypress() ) {
					
			// Profiles
			if ( bp_is_user() ) :
				$doctitle		= bp_get_displayed_user_fullname() . $separator . 'User Profile';
				$description 	= $sitename . ' user profile for ' . bp_get_displayed_user_fullname();
			
			elseif ( bp_is_group_create() ) :
				$doctitle		= 'Create New Group';
				$description 	= 'Create a new group for the ' . $sitename . ' groups directory.';
			
			// Groups
			elseif ( bp_is_group() ) :
				$doctitle		= bp_get_current_group_name() . $separator . 'Guild Profile';
				$description 	= $sitename . ' group profile for ' . bp_get_current_group_name();
			
			// Registration and activation
			elseif ( bp_is_register_page() || bp_is_activation_page() ) :
				$description 	= get_post_field( 'post_excerpt' , get_queried_object_id() );
			endif;
			
			// Have some fallbacks just in case
			if ( !$doctitle )
				$doctitle		= $object->post_title;
			if ( !$description )
				$description 	= $object->post_content;
		}
		
		// bbPress Forums
		elseif ( class_exists( 'bbPress' ) && is_bbpress() ) {
		
			// Main Forum Archive
			if ( bbp_is_forum_archive() ) :
				$doctitle 		= "{$sitename} Forums";
				$description 	= "Get involved in the community on the {$sitename} forums.";
				
			// Recent Topics
			elseif ( bbp_is_topic_archive() ) :
				$doctitle 		= "Recent Topics in the {$sitename} Forums";
				$description 	= "Browse a list of the most recent forum topics on {$sitename}.";
				
			// Single Forum
			elseif ( bbp_is_single_forum() ) :
				$doctitle 		= $object->post_title;
				$description 	= $object->post_content;
				
			// Single Topic
			elseif ( bbp_is_single_topic() ) :
				$doctitle 		= $object->post_title;
				$description	= bbp_get_topic_excerpt( $id );				
				
			// Edit Topic
			elseif ( bbp_is_topic_edit() ) :
				$doctitle 		= 'Edit Topic' . $separator . $object->post_title;
				$description	= bbp_get_topic_excerpt( $id );				
			
			// Edit Reply
			elseif ( bbp_is_reply_edit() ) :
				$doctitle 		= str_replace( 'To: ' , $separator , 'Edit ' . $object->post_title );
				$description	= bbp_get_reply_excerpt( $id );				
			endif;
		}
		
		// Singular Post
		elseif ( is_singular() ) {
			$doctitle 			= $object->post_title;
			$description 		= get_post_meta( $id , 'description' , true );
			
			// If nothing is found, give a post excerpt
			if ( empty ( $description ) )
				$description 	= get_post_field( 'post_excerpt' , get_queried_object_id() );
				
			if ( empty ( $description ) )
				$description 	= $object->post_title;
		}

		// Archive view
		elseif ( is_archive() ) {
			
			// Taxonomy
			if ( is_category() || is_tax() ) :
				$doctitle 		= 'Category Archive' . $separator . $object->name;
				$description	= $object->description;
			
			// Author
			elseif ( is_author() ) :
				$doctitle 		= 'Author Archive' . $separator . $object->display_name;
				$description 	= 'An archive listing of all articles written by '. $object->display_name;
			
			// Anything Else
			else :
				$doctitle 		= 'Archives';
				$description 	= $sitename . ' archives.';
			endif;
		}
		
		// Search Results
		elseif ( is_search() ) {
			$doctitle 			= sprintf( 'Search results for &quot;%1$s&quot;' , esc_attr( $apoc->search->query ) );
			$description		= "Browsing search results.";
		}
		
		// Error 404
		elseif ( is_404() ) {
			$doctitle 			= '404 Page Not Found';
			$description		= "Sorry, but nothing exists here.";
		}
	
		// If nothing else catches, apply the wp_title filter for plugins
		else $doctitle 	= apply_filters( 'wp_title', $doctitle, $separator, '' );
			
		// Return the SEO fields
		$this->title 		= html_entity_decode( $doctitle );
		$this->description	= str_replace( array("\n", "\r"), ' ' , html_entity_decode( $description ) );
	}
}

/*---------------------------------------------
2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/
function apoc_document_title() {
	echo apocrypha()->seo->title;
	}
function apoc_meta_description() {
	echo apocrypha()->seo->description;
	}

?>