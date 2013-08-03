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
	global $apocrypha;
	$apocrypha->search = $search_type;
	get_search_form();
}
?>