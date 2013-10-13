<?php
/**
 * Apocrypha Theme Core Shortcodes
 * Andrew Clayton
 * Version 1.0.0
 * 8-13-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
1.0 - APOC_SHORTCODES CLASS
----------------------------------------------*/

/**
 * Registers shortcodes used in forum replies and article comments.
 * Provides methods for processing said shortcodes when detected.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
class Apoc_Shortcodes {

	/**
	 * Declare used shortcodes
	 * Format as 'name' => 'tag'
	 */
	public $shortcodes = array(
		'image'		=> 'img',
		'quote'		=> 'quote',
		'spoiler'	=> 'spoiler',
	);

	/**
	 * Registers shortcode hooks and actions
	 */
	function __construct() {
	
		// Register the list of shortcodes
		$this->register();
	
		// Enable shortcodes in site elements
		$this->enable();
	}
	
	/**
	 * Enable shortcodes in additional elements
	 */
	private function register() {
		
		// Loop through each shortcode, registering both lowercase and uppercase versions
		foreach( $this->shortcodes as $name => $tag ) {
			add_shortcode( $tag 			, array( __CLASS__ , $name ) );
			add_shortcode( strtoupper($tag) , array( __CLASS__ , $name ) );		
		}
	}
	
	/**
	 * Enable shortcodes in additional elements
	 */
	private function enable() {	
		
		// Add shortcode filters
		add_filter( 'get_comment_text'			, 'do_shortcode' );
		add_filter( 'bbp_get_reply_content'		, 'do_shortcode' );
		add_filter( 'bp_get_group_description'	, 'do_shortcode' );		
	}
		
	/**
	 * Process image shortcode
	 * Format - [img alt="alttext"]http://source.com/image.jpg[/img]
	 */
	static function image( $atts, $content = NULL ) {
	
		// If the user submitted an image source wrapped in a link, strip out the link
		$content 	= preg_replace( array( '#<a(.*)">#' , '#</a>#' ) , '' , $content );
		$src		= esc_url( $content );
		
		// Check that it's a valid image file
		$info		= pathinfo( $src );
		if ( !in_array( $info['extension'] , array( 'jpeg' , 'jpg' , 'png' , 'gif' ) ) )
			return false;
			
		// Otherwise, extract shortcode attributes
		extract( shortcode_atts( array(	'alt' => '', ), $atts ) );
		
		// Return the formatted image
		return '<img src="' . $src . '" alt="'. esc_attr($alt) .'" title="'. esc_attr($alt) .'" class="shortcode-image" />';
	}

	
	/**
	 * Process spoiler shortcode
	 * Format - [spoiler title="spoiler title"]spoiler content[/spoiler]
	 */		
	static function spoiler( $atts, $content = NULL ) {
	
		// Make sure there's something inside the tags
		if ( NULL == $content ) return '';
		
		// Look for a title attribute
		extract( shortcode_atts( array( 'title' => '' ), $atts ) );
		
		// Wrap the spoiler
		$thespoiler = '<div class="spoiler">';
		$thespoiler .= '<p class="spoiler-title double-border bottom"><strong>SPOILER:</strong> ' . esc_attr($title) . '</p>';
		$thespoiler .= $content . '</div>';
		
		// Check for other shortcodes inside it, and return
		return do_shortcode($thespoiler);
	}
			
	/**
	 * Process quote shortcode
	 * Format - [quote author="name|postid|date"]content[/quote]
	 */		
	static function quote( $atts, $content = NULL ) {
	
		// If there are no attributes, just give it a generic blockquote
		if( !$atts ) 
			$thequote = '<blockquote>' . $content . '</blockquote>';
		
		// Otherwise, make it a formatted or nested discussion quote
		else {
		
			// Get the passed attributes
			extract( shortcode_atts( array(	'meta' => '', ), $atts ) );
			
			// Get info on the quoted user
			$author = explode( '|', $atts['author']);
			$author_name = $author[0];
			$author_slug = str_replace( " ", "-", $author_name);
			
			// Generate the link back to the parent post
			$quote_link = '<a href="#' . $author[1] . '">' . $author[2] . '</a>';
			
			// Format the quote
			$thequote = '<div class="quote"><p class="quote-author double-border bottom">';
			$thequote .= '<strong>'.$author_slug.'</strong> said on '.$quote_link.' :</p>';
			$thequote .= $content . '</div>';
		}
		
		// Return the quote
		return $thequote; 
	}
}
?>