<?php
/**
 * Apocrypha Theme Core Functions
 * Andrew Clayton
 * Version 2.0
 * 8-1-2013
 */
 
/*---------------------------------------------
1.0 - INCLUDE TEMPLATE ELEMENTS
----------------------------------------------*/

// Navigation
function apoc_primary_menu() {
	locate_template( array( 'library/templates/menu-primary.php' ), true );
}

// Sidebar
function apoc_primary_sidebar() {
	locate_template( array( 'library/templates/sidebar-primary.php' ), true );
}

// Comment Respond Form
function apoc_comment_form() {
	locate_template( array( 'library/templates/respond.php' ), true );
}
 
// Search Form
function apoc_get_search_form( $search_type = '' ) {
	global $apocrypha;
	$apocrypha->search = $search_type;
	locate_template( array( 'library/templates/searchform.php' ), true );
}
?>