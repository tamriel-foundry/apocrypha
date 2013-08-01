<?php
/**
 * Apocrypha Theme Core Functions
 * Andrew Clayton
 * Version 2.0
 * 8-1-2013
 */
 
/*-------------------------------------------
1.0 - SEARCH FORM
-------------------------------------------*/
 
/**
 * Apocrypha Theme get searchform by post type
 * @since 0.1
 */
function apoc_get_search_form( $search_type = '' ) {
	global $search_query_type;
	$search_query_type = $search_type;
	get_search_form();
}

/**
 * Apocrypha Theme display the header search form
 * @since 0.1
 */
function apoc_header_search() { ?>
	<div id="header-search">
		<div id="search-dropdown">';
		<?php apoc_get_search_form( 'posts' ); ?>
		</div>
	</div><?php
}
?>