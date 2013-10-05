<?php
/**
 * Apocrypha Search Functions
 * Andrew Clayton
 * Version 1.0.0
 * 10-5-2013
*/

/*---------------------------------------------
1.0 - SEARCH CLASS
----------------------------------------------*/
 
/**
 * Frontend Article Comment Editing Class
 * @version 1.0.0
 */
class Apoc_Search {

	// Construct the class
	function __construct() {
	
		// Add the necessary rewrite rules
		add_action( 'init', array( &$this, 'generate_rewrite_rules' ) ); 
		add_action( 'template_redirect', array( &$this , 'comment_edit_template' ) );
	}
	
	// Define the rule for identifying comment edits
	function generate_rewrite_rules() {
		$rule = 'advsearch/?$';
		$query	= 'index.php?name=advsearch';
		add_rewrite_rule( $rule , $query , 'top' );
	}
	
	// Redirect the template to use comment edit
	function comment_edit_template() {
		
		global $wp_query;
		
		if ( $wp_query->query['name'] == 'advsearch' ) {
		
			// Grab the search template
			include ( THEME_DIR . '/library/templates/adv-search.php' );
			exit();
		}
	}
}
$apoc_search = new Apoc_Search;

/**
 * Context function for detecting whether we are on advanced search
 * @version 1.0.0
 */
function is_adv_search() {
	global $wp_query;
	if ( $wp_query->query['name'] == 'advsearch' )
		return true;
	else return false;
}
