<?php
/**
 * Apocrypha Theme SEO Functions
 * Andrew Clayton
 * Version 1.0
 * 8-1-2013
 */

/**
 * Sets a logical browser title for SEO. 
 * Adapts to handle WordPress/bbPress/BuddyPress pages
 * @since 1.0
 */
remove_action( 'wp_title' , 'bbp_title' );
function display_document_title() {
	
	/* Start with some default variables */
	global $wp_query, $apocrypha;
	$doctitle = '';
	$separator = ' &bull; ';
	$sitename = SITENAME;
	
	/* Homepage */
	if ( is_home() )
		$doctitle = $sitename . $separator . get_bloginfo( 'description' );
	
	/* bbPress Forums */
	elseif ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
	
		/* Main Forum Archive */
		if ( bbp_is_forum_archive() )
			$doctitle = "{$sitename} Forums";
			
		/* Recent Topics */
		elseif ( bbp_is_topic_archive() )
			$doctitle = "Recent Topics in {$sitename} Forums";
			
		/* Single Forum */
		elseif ( bbp_is_single_forum() )
			$doctitle = bbp_get_forum_title( get_queried_object_id() );	
			
		/* Single Topic */
		elseif ( bbp_is_single_topic() )
			$doctitle = bbp_get_topic_title( get_queried_object_id() );	
	}
	
	/* Singular Post */
	elseif ( is_singular() ) {
		$doctitle = get_post_meta( get_queried_object_id(), 'Title', true );
		if ( empty ( $doctitle ) ) 
			$doctitle = $sitename . $separator . get_bloginfo( 'description' );
	}
	
	/* Archive view */
	elseif ( is_archive() ) {
		
		/* Taxonomy */
		if ( is_category() || is_tax() ) 
			$doctitle = 'Category Archive' . $separator . single_term_title( '', false );
		
		/* Author */
		elseif ( is_author() )
			$doctitle = 'Author Archive' . $separator . get_user_meta( get_query_var( 'author' ), 'nickname', true );
		
		/* Anything Else */
		else $doctitle = 'Archives';
	}
	
	/* Search Results */
	elseif ( is_search() )
		$doctitle = sprintf( 'Search results for &quot;%1$s&quot;' , esc_attr( get_search_query() ) );
	
	/* Error 404 */
	elseif ( is_404() )
		$doctitle = '404 Page Not Found';
	
	/* If it's a paged result */
	if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 )
		$doctitle = sprintf( '%1$s Page %2$s', $doctitle . $separator, $page );
	
	/* Apply the wp_title filters to work with plugins */
	$doctitle = apply_filters( 'wp_title', $doctitle, $separator, '' );	
	
	/* Convert characters and trim separator/space from beginning and end in case a plugin adds it */
	$doctitle	= html_entity_decode( $doctitle );
	$doctitle 	= trim( $doctitle, "{$separator} " );	
	
	/* Print the title */
	echo $doctitle ;	
}
 
 /**
 * Sets a SEO friendly meta description tag
 * @since 1.0
 */
function display_meta_description() {
	$description = '';
	$sitename = SITENAME;

	/* Home Page */
	if ( is_home() ) 
		$description = get_bloginfo( 'description' );
	
	/* bbPress Forums */
	elseif ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
		
		/* Main Forum Archive */
		if ( bbp_is_forum_archive() )
			$description = "Get involved in the community on the {$sitename} forums.";
		
		/* Single Forum */
		elseif ( bbp_is_single_forum() ) {
			$forum = get_post( get_queried_object_id() );
			$description = $forum->post_content;
		}
		
		/* Recent Topics */
		elseif ( bbp_is_topic_archive() ) 
			$description = "Browse a list of the most recent forum topics on {$sitename}.";
			
		/* Single Topic */
		elseif ( bbp_is_single_topic() )
			$description = bbp_get_topic_excerpt( get_queried_object_id() );	
	}
	
	/* BuddyPress */
	elseif ( function_exists( 'is_buddypress' ) && is_buddypress() ) {

		/* Directories */
		if ( bp_is_directory() ) 
			$description = get_post_meta( get_queried_object_id() , 'Description' , true );
			
		elseif ( bp_is_user() )
			$description = $sitename . ' user profile for ' . bp_get_displayed_user_fullname();
		
		elseif ( bp_is_group() )
			$description = $sitename . ' group profile for ' . bp_get_current_group_name();
			
		elseif ( bp_is_register_page() || bp_is_activation_page() )
			$description = get_post_field( 'post_excerpt' , get_queried_object_id() );
	}
	
	/* Singular Post */
	elseif ( is_singular() ) {
		
		/* Get the description from post meta */
		$description = get_post_meta( get_queried_object_id() , 'Description' , true );
		
		/* If nothing is found, give a post excerpt */
		if ( empty ( $description ) )
			$description = get_post_field( 'post_excerpt' , get_queried_object_id() );
	}
	
	/* Archive Page */
	elseif ( is_archive() ) {

		/* Author */
		if ( is_author() )
			$description = 'An archive listing of articles written by '. get_user_meta( get_query_var( 'author' ), 'nickname', true );
		
		/* Taxonomy */
		elseif ( is_category() || is_tax() )  {
			$description = category_description( get_query_var( 'cat' ) );
			$description = trim( strip_tags($description) );
		}
		
		/* Anything Else */
		else $description = $sitename . ' archives.';
	}

	echo esc_attr( $description );
}
?>