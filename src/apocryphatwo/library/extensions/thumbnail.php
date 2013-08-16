<?php
/**
 * Apocrypha Theme Post Thumbnails
 * Andrew Clayton
 * Version 1.0.0
 * 8-16-2013
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
1.0 - APOC_THUMBNAIL CLASS
----------------------------------------------*/

/**
 * Generates the thumbnail image for posts within the loop.
 * First looks for any images that are directly flagged as "Featured Images" for the post.
 * Next it scans through images attached to the post and picks the first one.
 * If there are no images attached to the post, it shows a default logo.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
class Apoc_Thumbnail {

	// The image arguments
	public 	$args;
	
	// The resulting image
	public	$thumb;
	
	/**
	 * The thumbnail constructor.
	 * Args provides formatting preferences.
	 */
	function __construct( $args = array() ) {
	
		// Construct arguments
		$defaults 		= $this->default_args();		
		$this->args		= wp_parse_args( $args , $defaults );		
		
		// Get the correct image to use
		$image			= $this->get_image();
		
		// Build the thumbnail and return it
		$this->thumb	= $this->create_thumb( $image );	
	}
	
	/**
	 * Set up the default arguments for the pagination links
	 */
	private function default_args() {
	
		// Set the default arguments
		$defaults = array(
			'post_id'			=> get_the_ID(),
			'size'				=> 'thumbnail',
			'height'			=> 150,
			'width'				=> 150,
			'default_image'		=> true,
			'link_to_post'		=> true,
			'image_class'		=> false,
			'thumbnail_id_save'	=> false,
		);
		return $defaults;
	}
	
	/**
	 * Determine the correct image to use for the post
	 */
	function get_image() {
	
		// Get the arguments
		$args 		= $this->args;
		$image 		= array();
		extract( $args );
				
		// First use the "Featured Image" thumbnail if set
		$thumb_id = get_post_thumbnail_id( $post_id );
		if ( !empty( $thumb_id ) ) :
			$thumb_id 	= get_post_thumbnail_id( $post_id );
			$src 		= wp_get_attachment_image_src( $thumb_id , $size );
			$img		= $src[0];
			
		// Next try scanning the post for attachments
		else :
			$att_args = array(
				'numberposts' 		=> 1,
				'post_type' 		=> 'attachment',
				'post_parent' 		=> $post_id,
				'post_mime_type'	=> 'image',
				'post_status' 		=> null,
			);
			$attachments = get_children( $att_args );
			
			// If attachments were found, grab the first one
			if ( $attachments ) {
				foreach ( $attachments as $att ) {
					$src = wp_get_attachment_image_src( $att->ID, $size );
					$img = $src[0];
				}
			}
			
		// If we still have nothing, grab a default
		if( empty( $img ) ) 
			$img = THEME_URI . '/images/avatars/foundry-thumb.jpg';
		endif;
		
		// Return the image
		return $img;
	}

	/**
	 * Determine the correct image to use for the post
	 */
	function create_thumb( $src ) {
		
		// Make sure there's an image
		if ( empty( $src ) )
			return false;
			
		// Extract arguments
		extract( $this->args );
			
		// Get the post title for alt-text
		$alt = get_the_title( $post_id );

		// Build the html
		$html = '<img src="' . $src . '" alt="' . esc_attr( strip_tags( $alt ) ) . '" class="' . esc_attr( $size ) . '" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" />';
		
		// Maybe wrap the image in a link
		if ( $link_to_post )
			$html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( $alt ) . '">' . $html . '</a>';	
		
		return $html;			
	}
}

/*---------------------------------------------
2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/

/**
 * Helper function that displays the thumbnail in templates
 * @version 1.0.0
 */
function apoc_thumbnail( $args = array() ) {
	$thumb = new Apoc_Thumbnail( $args );
	echo $thumb->thumb;
}