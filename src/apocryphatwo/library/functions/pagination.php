<?php
/**
 * Apocrypha Theme Pagination Functions
 * Andrew Clayton
 * Version 1.0.1
 * 1-26-2014
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
1.0 - APOC_PAGINATION CLASS
----------------------------------------------*/

/**
 * Generates pagination links for WordPress loops.
 * Adapts to refresh pagination from AJAX requests.
 * Paginates article comments.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
class Apoc_Pagination {

	// The pagination arguments
	public 	$args;
	
	// The resulting links
	public	$links;

	/**
	 * The pagination constructor accepts three optional parameters
	 * Args provides formatting preferences
	 * Baseurl specifies the originating page (for AJAX requests)
	 * Query gives the name of the query object which contains pagination data
	 */
	function __construct( $args = array() , $baseurl = '' , $query = 'wp_query' ) {
	
		// Setup default arguments
		$defaults 				= $this->default_args();
		$this->args				= wp_parse_args( $args , $defaults );
		
		// Validate page number
		$this->args['current']	= $this->get_paged( $query );
		
		// Validate maximum pages
		$this->args['total']	= $this->get_total( $query );
		
		// Get the base url for the pagination
		$this->args['base']		= $this->get_url( $baseurl , $this->args['context'] );
		
		// Generate the links
		$this->links			= $this->create_links( $this->args );
	}
	
	/**
	 * Set up the default arguments for the pagination links
	 */	
	private function default_args() {
	
		$defaults = array(
			'context'		=> 'post',
			'base'			=> '',
			'format'		=> '',
			'total'			=> NULL,
			'current'		=> NULL,
			'show_all'		=> false,
			'end_size'		=> 1,
			'mid_size'		=> 1,
			'prev_next'		=> true,
			'prev_text'		=> '&larr;',
			'next_text'		=> '&rarr;',
			'add_fragment'	=> '',
			'type'			=> 'plain',
			'add_args'		=> false,		
			'before'		=> '<div class="pagination-links">',
			'after'			=> '</div>',
		);
			
		return $defaults;
	}
	
	/**
	 * Get the displayed page number from the query
	 */	
	function get_paged( $query ) {
	
		// If a page number was passed directly, use it
		if ( isset( $this->args['current'] ) )
			return $this->args['current'];
			
		// Otherwise, get the current page from the query object
		else {
			global ${$query};
			$pagevar = ( 'comment' == $this->args['context'] ) ? 'cpage' : 'paged';
			$paged = isset( ${$query}->query_vars[$pagevar] ) ? absint( ${$query}->query_vars[$pagevar]  ) : 1 ;
			return max( $paged , 1 );
		}		
	}
	
	
	/**
	 * Get the total pages from the query
	 */	
	function get_total( $query ) {
	
		// If a page number was passed directly, use it
		if ( isset( $this->args['total'] ) )
			return $this->args['total'];
			
		// Otherwise, get the current page from the query object
		else {
			global ${$query};
			$total = ( 'comment' == $this->args['context'] ) ? intval( ${$query}->max_num_comment_pages ) : intval( ${$query}->max_num_pages );
			return $total;
		}		
	}
	
	/**
	 * Format the URL in using the necessary %#% tag
	 */	
	function get_url( $baseurl , $context ) {
	
		// If a url was not passed, get the current page
		if ( empty( $baseurl ) )
			$baseurl = get_current_url();
			
		// Format the URL differently depending on context
		switch ( $context ) {
			case 'post' :
			
				// Get the pagination base
				global $wp_rewrite;
				$pagination_base 	= $wp_rewrite->pagination_base;
					
				// Parse any existing page numbers out of the URL
				$remove 			= '/\/' . $pagination_base . '\/(.*)/';
				$baseurl 			= preg_replace( $remove , "" , $baseurl );
				$baseurl			= trailingslashit( $baseurl ) . $pagination_base . '/%#%/';
				break;
			
			case 'comment' :
			
				// Get the pagination base
				$pagination_base 	= 'comment-page-';
				
				// Parse existing page numbers out of the URL
				$remove 			= '/' . $pagination_base . '[0-9]*\/(.*)/';
				$baseurl 			= preg_replace( $remove , "" , $baseurl );
				$baseurl			= trailingslashit( $baseurl ) . $pagination_base . '%#%/#comments';
				break;

			case 'search' :
			
				// Set the pagination base
				$pagination_base	= '?page=';	
				$baseurl			= SITEURL . '/advsearch' . $pagination_base . '%#%';
				break;
		}				
			
		// Return the formatted URL
		return $baseurl;
	}
		
		
	/**
	 * Handles creation of pagination links
	 */
	function create_links( $args ) {
		
		// Only create links if there is more than 1 page
		if ( 1 >= $args['total'] )
			return false;

		// Get the paginated links.
		$page_links = paginate_links( $args );
		
		// Get the pagination base
		global $wp_rewrite;
		$pagination_base 	= $wp_rewrite->pagination_base;

		// Remove 'page/1' from the entire output for readability
		if ( 'post' == $args['context'] ) :
			$page_links = str_replace( array( "/{$pagination_base}/1'"	, "/{$pagination_base}/1/'" )	, '\'' , $page_links );
			$page_links = str_replace( array( "/{$pagination_base}/1\""	, "/{$pagination_base}/1/\"" )	, '\"' , $page_links );
		elseif ( 'comment' == $args['context'] ) :
			$page_links	= str_replace( "comment-page-1/" , "" , $page_links );
		endif;

		// Wrap the paginated links with the $before and $after elements.
		$page_links = $args['before'] . $page_links . $args['after'];

		// Echo or return the links
		return $page_links;
	}
}


/*---------------------------------------------
2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/

/**
 * Helper function that displays the pagination in templates.
 * @version 1.0.0
 */
function apoc_pagination( $args = array() , $baseurl = '' , $query = 'wp_query' ) {
	$nav = new Apoc_Pagination( $args , $baseurl , $query );
	echo $nav->links;
}

?>