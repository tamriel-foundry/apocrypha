<?php
/**
 * Apocrypha Theme Functions
 * Andrew Clayton
 * Version 2.0
 * 8-1-2013
 
----------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.0 - Load Core Framework	
	___________________________
	
2.0 - Head Functions
3.0 - WordPress Functions
	3.1 - Posts and Pages
	3.2 - Comments
4.0 - BBPress Functions
5.0 - BuddyPress Functions
6.0 - User Functions
	6.1 - Signature, Biography, Contact Fields
7.0 - Entropy Rising
--------------------------------------------------------------*/

/*--------------------------------------------------------------
1.0 - LOAD CORE FRAMEWORK
--------------------------------------------------------------*/

/* 
 * This function initializes the Apocrypha theme framework.
 * It runs immediately after WordPress loads the theme files.
 * @since 2.0
 */
add_action( 'after_setup_theme' , 'apocrypha_theme_setup' , 1 );
function apocrypha_theme_setup() {

	/* Load the Apocrypha class */
	require_once( trailingslashit( TEMPLATEPATH ) . 'library/apocrypha.php' );

	/* Set it up */
	$apocrypha = new Apocrypha();
}
?>


