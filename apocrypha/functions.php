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

/**
 * This function initializes the Apocrypha theme framework.
 * It runs immediately after WordPress loads the theme files.
 * @since 2.0
 */
add_action( 'after_setup_theme' , 'apocrypha_theme_setup' , 1 );
function apocrypha_theme_setup() {

	// Load the Apocrypha class
	require_once( trailingslashit( TEMPLATEPATH ) . 'library/apocrypha.php' );

	// Set it up
	global $apocrypha;
	$apocrypha = new Apocrypha();
}


/**
 * Include the primary theme CSS stylesheet
 * @since 2.0
 */
function apoc_primary_stylesheet() {
	echo '<link rel="stylesheet" type="text/css" media="all" href="' . THEME_URI . '/style.css?v=' . filemtime( THEME_DIR . "/style.css" ) .'" />' . "\n" ;
}

/* 
 * Display the google analytics tracking code for Tamriel Foundry
 * @since 2.0
 */
function google_analytics_js() {
	
	?><script type="text/javascript">
		var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-33555290-2"]);_gaq.push(["_trackPageview"]);(function(){var b=document.createElement("script");b.type="text/javascript";b.async=true;b.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(b,a)})();
	</script><?php
}
?>


