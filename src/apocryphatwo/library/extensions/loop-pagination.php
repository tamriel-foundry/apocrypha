<?php
/**
 * Loop pagination function for paginating loops with multiple posts. 
 * Code modified from original copyright (c) Justin Tadlock
 *
 * @since 0.1.0
 * @access public
 * @uses paginate_links() Creates a string of paginated links based on the arguments given.
 * @param array $args Arguments to customize how the page links are output.
 * @return string $page_links
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function loop_pagination( $args = array() ) {
	global $wp_rewrite, $wp_query;

	// If there's not more than one page, return nothing.
	if ( 1 >= $wp_query->max_num_pages )
		return;

	// Get the current page.
	$current = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );

	// Get the max number of pages.
	$max_num_pages = intval( $wp_query->max_num_pages );

	// Get the pagination base.
	$pagination_base = $wp_rewrite->pagination_base;

	// Set up some default arguments for the paginate_links() function.
	$defaults = array(
		'base'         => add_query_arg( 'paged', '%#%' ),
		'format'       => '',
		'total'        => $max_num_pages,
		'current'      => $current,
		'prev_next'    => true,
		'show_all'     => false,
		'end_size'     => 1,
		'mid_size'     => 1,
		'add_fragment' => '',
		'type'         => 'plain',

		// Begin loop_pagination() arguments.
		'before'       => '<div class="pagination-links">',
		'after'        => '</div>',
		'echo'         => true,
	);

	// Add the $base argument to the array if the user is using permalinks.
	if ( $wp_rewrite->using_permalinks() && !is_search() )
		$defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . "{$pagination_base}/%#%" );

	// Merge the arguments input with the defaults.
	$args = wp_parse_args( $args, $defaults );

	// Don't allow the user to set this to an array.
	if ( 'array' == $args['type'] )
		$args['type'] = 'plain';

	// Get the paginated links.
	$page_links = paginate_links( $args );

	// Remove 'page/1' from the entire output since it's not needed.
	$page_links = str_replace( array( "?paged=1'", "&#038;paged=1'", "/{$pagination_base}/1'", "/{$pagination_base}/1/'" ), '\'', $page_links );
	$page_links = str_replace( array( '?paged=1"', '&#038;paged=1"', "/{$pagination_base}/1\"", "/{$pagination_base}/1/\"" ), '\"', $page_links );

	// Wrap the paginated links with the $before and $after elements.
	$page_links = $args['before'] . $page_links . $args['after'];

	// Allow devs to completely overwrite the output.
	$page_links = apply_filters( 'loop_pagination', $page_links );

	// Return the paginated links for use in themes.
	if ( $args['echo'] )
		echo $page_links;
	else
		return $page_links;
}

/**
 * Handles the re-creation of pagination links when posts are loaded with AJAX
 * @version 1.0.0
 */
function ajax_pagination( $ajax_query , $args = array() , $url = '' ) {
	
	global $wp_rewrite;
	
	// Get the max number of pages.
	$max_pages = intval( $ajax_query->max_num_pages );
	
	// Get the current page.
	$current = ( isset( $ajax_query->query_vars['paged'] ) ) ? absint( $ajax_query->query_vars['paged']  ) : 1 ;
	
	// Get the cleaned base url
	$pagination_base 	= $wp_rewrite->pagination_base;
	$remove 			= '/\/' . $pagination_base . '\/(.*)/';
	$baseurl 			= preg_replace( $remove , "" , $url );
	$format				= $pagination_base . '/%#%/';
	
	// Set up some default arguments for the paginate_links() function.
	$defaults = array(
		'base'         => trailingslashit( $baseurl ) . $format,
		'format'       => '',
		'total'        => $max_pages,
		'current'      => $current,
		'prev_next'    => true,
		'show_all'     => false,
		'end_size'     => 1,
		'mid_size'     => 1,
		'add_fragment' => '',
		'type'         => 'plain',

		// Extra ajax_pagination() arguments.
		'before'       => '<div class="pagination-links">',
		'after'        => '</div>',
	);
	
	// Merge the arguments input with the defaults.
	$args = wp_parse_args( $args, $defaults );

	// Get the paginated links.
	$page_links = paginate_links( $args );
	
	// Remove 'page/1' from the entire output since it's not needed.
	$page_links = str_replace( array( "/{$pagination_base}/1'"	, "/{$pagination_base}/1/'" )	, '\''	, $page_links );
	$page_links = str_replace( array( "/{$pagination_base}/1\""	, "/{$pagination_base}/1/\"" )	, '\"'	, $page_links );

	// Wrap the paginated links with the $before and $after elements.
	$page_links = $args['before'] . $page_links . $args['after'];

	// Allow devs to completely overwrite the output.
	$page_links = apply_filters( 'loop_pagination', $page_links );

	// Return the paginated links to the AJAX handler
	echo $page_links;
}

/**
 * Handles the re-creation of comment pagination when new comments are loaded with AJAX
 * @version 1.0.0
 */
function ajax_comment_pagination( $args = array() , $url = '' , $paged , $max_pages = 2 ) {
	
	global $wp_rewrite, $wp_query;

	// Get the cleaned base url
	$remove 			= '/\/' . $pagination_base . '\/(.*)/';
	$baseurl 			= preg_replace( $remove , "" , $url );
	$format				= $pagination_base . '/%#%/';
	
	// Get the cleaned base url
	$pagination_base 	= 'comment-page-';
	$remove 			= '/' . $pagination_base . '[0-9]*\/(.*)/';
	$baseurl 			= preg_replace( $remove , "" , $url );
	$format				= $pagination_base . '%#%/';
	
	// Set up some default arguments for the paginate_links() function.
	$defaults = array(
		'base'         => trailingslashit( $baseurl ) . $format,
		'format'       => '',
		'total'        => $max_pages,
		'current'      => $paged,
		'prev_next'    => true,
		'show_all'     => false,
		'end_size'     => 1,
		'mid_size'     => 1,
		'add_fragment' => '',
		'type'         => 'plain',

		// Extra ajax_pagination() arguments.
		'before'       => '<div class="pagination-links">',
		'after'        => '</div>',
	);
	
	// Merge the arguments input with the defaults.
	$args = wp_parse_args( $args, $defaults );

	// Get the paginated links.
	$page_links = paginate_links( $args );
	
	// Remove 'comment-page-1' from the entire output since it's not needed.
	$page_links	= str_replace( "comment-page-1/" , "" , $page_links );

	// Wrap the paginated links with the $before and $after elements.
	$page_links = $args['before'] . $page_links . $args['after'];

	// Allow devs to completely overwrite the output.
	$page_links = apply_filters( 'loop_pagination', $page_links );

	// Return the paginated links to the AJAX handler
	echo $page_links;
}

?>