<?php
/**
 * Apocrypha Theme Core Shortcodes
 * Andrew Clayton
 * Version 1.0
 * 8-13-2013
 */

/* Initialize Shortcodes */
add_shortcode( 'img' , 'shortcode_image' );
add_shortcode( 'IMG' , 'shortcode_image' );
add_shortcode( 'quote' , 'shortcode_quote' );
add_shortcode( 'QUOTE' , 'shortcode_quote' );
add_shortcode( 'spoiler' , 'shortcode_spoiler' );
add_shortcode( 'SPOILER' , 'shortcode_spoiler' );

/* Enable Shortcodes */
add_filter( 'get_comment_text', 'do_shortcode' );
add_filter( 'bbp_get_reply_content', 'do_shortcode' );
add_filter( 'bp_get_group_description', 'do_shortcode' );

/* Images [img alt="alttext"][/img] */
function shortcode_image( $atts, $content = NULL ) {
	if ( NULL == $content ) return '';
	$content = preg_replace( '#<a(.*)">#' , '' , $content );
	$content = preg_replace( '#</a>#' , '' , $content );
	$src = esc_url( $content );
	extract( shortcode_atts( array(
		'alt' => '',
	), $atts ) );
	return '<img src=' . $src . ' alt="'. esc_attr($alt) .'" title="'. esc_attr($alt) .'" class="shortcode-image" />';
}

/* Quotes [quote author="name|postid|date"]content[/quote] */
function shortcode_quote( $atts, $content = NULL ) {
	if ( NULL == $content ) return '';
	
	// Old [quote]content[/quote] format
	if( !$atts ) 
	$thequote = '<div class="quote">' . $content . '</div>';
	else {
		extract( shortcode_atts( array(
			'meta' => '',
		), $atts ) );
		$author = explode( '|', $atts['author']);
		$author_name = $author[0];
		$author_slug = str_replace( " ", "-", $author_name);
		$quote_link = '<a href="#' . $author[1] . '">' . $author[2] . '</a>';
		$thequote = '<div class="quote"><p class="quote-author double-border bottom">';
		$thequote .= '<strong>'.$author_slug.'</strong> said on '.$quote_link.' :</p>';
		$thequote .= $content . '</div>';
	}
	return $thequote; 
}

/* Spoliers [spoiler title="spoiler title"]spoiler content[/spoiler] */
function shortcode_spoiler( $atts, $content = NULL ) {
	if ( NULL == $content ) return '';
	extract( shortcode_atts( array(
		'title' => '',
	), $atts ) );
	$thespoiler = '<div class="spoiler"><p class="spoiler-title">';
	$thespoiler .= '<span><strong>SPOILER:</strong> ' . esc_attr($title) . '</span></p>';
	$thespoiler .= '<p>' . $content . '</p></div>';
	return do_shortcode($thespoiler);
}

?>