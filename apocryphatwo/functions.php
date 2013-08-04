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
2.0 - Head Functions
	2.1 - Stylesheets
	2.2 - JavaScript
	___________________________
	
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
	
	// Disable WordPress admin bar
	if( function_exists( 'show_admin_bar' ) )
		show_admin_bar(false);
}


/*--------------------------------------------------------------
2.0 - HEAD FUNCTIONS
--------------------------------------------------------------*/

/**
 * Remove default WordPress head entries
 * @since 2.0
 */
remove_action( 'wp_head' 	, 	'wp_generator' 								);
remove_action( 'wp_head' 	, 	'feed_links'						, 2		); 
remove_action( 'wp_head' 	, 	'feed_links_extra'					, 3 	); 
remove_action( 'wp_head' 	, 	'rsd_link' 									);
remove_action( 'wp_head' 	, 	'wlwmanifest_link' 							);
remove_action( 'wp_head' 	, 	'rel_canonical'								);
remove_action( 'wp_head' 	, 	'wp_shortlink_wp_head'						);
remove_action( 'wp_head' 	, 	'adjacent_posts_rel_link_wp_head'	, 10, 0 );

/**
 * Remove default BuddyPress head entries
 * @since 2.0
 */
remove_action( 'wp_head'	, 	'bp_core_add_ajax_url_js' 					);
remove_action( 'wp_head'	, 	'bp_core_confirmation_js'			, 100 	);
remove_action( 'bp_actions'	, 	'messages_add_autocomplete_js' 				);
remove_action( 'wp_head'	, 	'messages_add_autocomplete_css' 			);

/**
 * Remove default bbPress head entries
 * @since 2.0
 */
add_action( 'bbp_theme_compat_actions' , 'remove_bbpress_head' );
function remove_bbpress_head( $admin ) {
    remove_action( 'bbp_enqueue_scripts' 	, array( $admin, 'enqueue_styles'  	) );
    remove_action( 'bbp_enqueue_scripts' 	, array( $admin, 'enqueue_scripts' 	) );
	remove_action( 'bbp_head'				, array( $admin, 'head_scripts' 	) );
}

/*---------------------------------------------
	2.1 - Stylesheets
----------------------------------------------*/

/**
 * Load stylesheets based on context
 * @since 2.0
 */
add_action( 'wp_enqueue_scripts' , 'apoc_enqueue_styles' );
function apoc_enqueue_styles() {

	/* Register first */
	wp_register_style( 'primary' , THEME_URI . '/style.css' , false , $ver=filemtime( THEME_DIR . "/style.css" ) );
	wp_register_style( 'fonts' , 'http://fonts.googleapis.com/css?family=Cinzel|Bitter|Open+Sans' , false );
	
	/* Then enqueue - some styles are only needed on specific pages */
	wp_enqueue_style( 'fonts' );
	wp_enqueue_style( 'primary' );
}

/**
 * Set a tinymce editor stylesheet version number to defeat caching
 * @since 2.0
 */
function tinymce_editor_style_version() {
	$version = "?ver=1.0.0";
	echo $version;
}

/*---------------------------------------------
	2.2 - JavaScript
----------------------------------------------*/

/**
 * Include the primary theme JavaScript
 * @since 2.0
 */
add_action( 'wp_enqueue_scripts' , 'apoc_enqueue_scripts' );
function apoc_enqueue_scripts() {

	/* Register first */
	wp_register_script( 'foundry' 		, APOC_JS . '/foundry.js' 			,'jquery' , $ver='0.1' 	);
	wp_register_script( 'flexslider' 	, APOC_JS . '/flexslider.min.js' 	,'jquery' , $ver='0.1' 	);	
	wp_register_script( 'buddypress'	, APOC_JS . '/buddypress.js' 		,'jquery' , $ver='0.1' 	);	
	
	/* Then enqueue */
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'buddypress' );
	
	/* Some scripts are only needed on specific pages */
	if ( is_home() || is_page_template( 'guild/guild-home.php' ) ) 
		wp_enqueue_script( 'flexslider' );
	
	/* My JS file comes last */
	wp_enqueue_script( 'foundry' );
}
/* 
 * Display the google analytics tracking code for Tamriel Foundry
 * @since 2.0
 */
function google_analytics_js() {
	
	echo '<script type="text/javascript">var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-33555290-2"]);_gaq.push(["_trackPageview"]);(function(){var b=document.createElement("script");b.type="text/javascript";b.async=true;b.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(b,a)})();</script>' . "\n";
}



function dump_apoc() {
	global $apocrypha; 
	echo '<pre>';
	print_r($apocrypha);
	echo '</pre>';
}
?>


