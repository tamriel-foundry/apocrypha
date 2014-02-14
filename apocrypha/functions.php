<?php
/**
 * Apocrypha Theme Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-1-2013
 
----------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.0 - Load Core Framework	
2.0 - Head Functions
	2.1 - Stylesheets
	2.2 - JavaScript
3.0 - TinyMCE
4.0 - Entropy Rising
--------------------------------------------------------------*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*--------------------------------------------------------------
1.0 - LOAD CORE FRAMEWORK
--------------------------------------------------------------*/

/**
 * This function initializes the Apocrypha theme framework.
 * It runs immediately after WordPress loads core libraries.
 * Some other "run once per pageload" things are done here also.
 *
 * @see Apocrypha
 * @version 1.0.0
 */
add_action( 'after_setup_theme' , 'apocrypha_theme_setup' , 1 );
function apocrypha_theme_setup() {

	// Load the Apocrypha class
	require_once( trailingslashit( TEMPLATEPATH ) . 'library/apocrypha.php' );

	// Set it up
	apocrypha();
}

/*--------------------------------------------------------------
2.0 - HEAD FUNCTIONS
--------------------------------------------------------------*/

/**
 * Remove default WordPress head entries
 * @version 1.0.0
 */
remove_action( 'wp_head'	,	'wp_generator'								);
remove_action( 'wp_head'	,	'feed_links'						, 2		); 
remove_action( 'wp_head'	,	'feed_links_extra'					, 3		); 
remove_action( 'wp_head'	,	'rsd_link'									);
remove_action( 'wp_head'	,	'wlwmanifest_link'							);
remove_action( 'wp_head'	,	'rel_canonical'								);
remove_action( 'wp_head'	,	'wp_shortlink_wp_head'						);
remove_action( 'wp_head'	,	'adjacent_posts_rel_link_wp_head'	, 10, 0 );

/**
 * Remove default BuddyPress head entries
 * @version 1.0.0
 */
remove_action( 'wp_head'	, 	'bp_core_add_ajax_url_js'					);
remove_action( 'wp_head'	,	'bp_core_confirmation_js'			, 100	);
remove_action( 'bp_actions'	,	'messages_add_autocomplete_js'				);
remove_action( 'wp_head'	,	'messages_add_autocomplete_css'				);

/**
 * Remove default bbPress head entries
 * @version 1.0.0
 */
add_action( 'bbp_theme_compat_actions' , 'remove_bbpress_head' );
function remove_bbpress_head( $admin ) {
    remove_action( 'bbp_enqueue_scripts' 	, array( $admin, 'enqueue_styles'  	) );
	remove_action( 'bbp_head'				, array( $admin, 'head_scripts' 	) );
}

/*---------------------------------------------
	2.1 - Stylesheets
----------------------------------------------*/

/**
 * Load stylesheets based on context
 * @version 1.0.0
 */
add_action( 'wp_enqueue_scripts' , 'apoc_enqueue_styles' );
function apoc_enqueue_styles() {

	/* Register first */
	wp_register_style( 'primary' , THEME_URI . '/style.css' , false , $ver=filemtime( THEME_DIR . "/style.css" ) );
	wp_register_style( 'google-fonts' , 'http://fonts.googleapis.com/css?family=Cinzel|PT+Serif|Open+Sans' , false );
	wp_register_style( 'font-awesome' , 'http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css' , false );
	
	/* Then enqueue - some styles are only needed on specific pages */
	wp_enqueue_style( 'google-fonts' );
	wp_enqueue_style( 'font-awesome' );
	wp_enqueue_style( 'primary' );
}

/*---------------------------------------------
	2.2 - JavaScript
----------------------------------------------*/

/**
 * Include the primary theme JavaScript
 * @version 1.0.0
 */
add_action( 'wp_enqueue_scripts' , 'apoc_enqueue_scripts' );
function apoc_enqueue_scripts() {

	// Register first
	wp_register_script( 'foundry' 		, THEME_URI . '/library/js/foundry.js' 			, 'jquery' , $ver='0.51' , true	);
	wp_register_script( 'flexslider' 	, THEME_URI . '/library/js/flexslider.min.js' 	, 'jquery' , $ver='0.1' , true  );
	wp_register_script( 'buddypress'	, THEME_URI . '/library/js/buddypress.js' 		, 'jquery' , $ver='0.33' , true 	);	
	wp_register_script( 'colorbox' 		, THEME_URI . '/library/js/colorbox.min.js' 	, 'jquery' , $ver='1.0' , true	);
	
	// Deregister WordPress default jQuery and get from Google
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery' 		, '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js' ,'jquery' , $ver ='1.8.3' , true );
	
	// Then enqueue
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'buddypress' );
	wp_enqueue_script( 'colorbox' );
	
	// Some scripts are only needed on specific pages
	if ( is_home() || is_page_template( 'guild/guild-home.php' ) ) 
		wp_enqueue_script( 'flexslider' );
	if ( is_page( 'contact-us' ) ) {
		wp_register_script( 'contactform' 	, THEME_URI . '/library/js/contactform.js' 	, 'jquery' , $ver='0.1' , true 	);
		wp_enqueue_script( 'contactform' );
	}
	
	// My JS file comes last
	wp_enqueue_script( 'foundry' );
}
/* 
 * Display the google analytics tracking code for Tamriel Foundry
 * @version 1.0.0
 */
function google_analytics_js() {
	echo '<script type="text/javascript">var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-33555290-2"]);_gaq.push(["_trackPageview"]);(function(){var b=document.createElement("script");b.type="text/javascript";b.async=true;b.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(b,a)})();</script>' . "\n";
}


/*--------------------------------------------------------------
3.0 - TINYMCE CUSTOMIZATION
--------------------------------------------------------------*/
/**
 * Set some TinyMCE options
 * @version 1.0.0
 */
add_filter( 'tiny_mce_before_init' , 'apoc_mce_options' );
add_filter( 'teeny_mce_before_init' , 'apoc_mce_options' );
function apoc_mce_options( $init ) {
    
	// Get the proper URL format
	$stylesheet = substr( THEME_URI . '/library/css/' , strpos( THEME_URI . '/library/css/' , '/' , 7 ) );
	
	// TinyMce initialization options
	if( !is_admin() )
			$init['content_css']				= $stylesheet . '/editor-content.css?v=1.0.0';
	$init['wordpress_adv_hidden'] 				= false;
	$init['height']								= 250;
	$init['theme_advanced_resizing_use_cookie'] = false;
    return $init;
}

add_filter( 'mce_buttons' , 'apoc_mce_buttons' );
add_filter( 'mce_buttons_2' , 'apoc_mce_buttons_2' );
function apoc_mce_buttons( $buttons ) {

	// Add buttons
	array_splice($buttons, 2, 0, 'underline');
	
	// Only remove buttons for frontend users
	if ( is_admin() ) return $buttons;
	
	// Remove buttons and return
	$remove = array('wp_more','wp_adv','fullscreen');
	return array_diff($buttons,$remove);

}
function apoc_mce_buttons_2( $buttons ) {

	// Only remove buttons for frontend users
	if ( is_admin() ) return $buttons;
	
	// Remove buttons and return
	$remove = array('wp_help','underline','pasteword','pastetext');
	return array_diff($buttons,$remove);
}

/*--------------------------------------------------------------
4.0 - ENTROPY RISING
--------------------------------------------------------------*/
/*
 * Set ER Recruitment Status and Class Needs
 */
function guild_recruitment_status() {
	$status = 'closed';
	return $status;
}
function get_class_recruitment_status() {
	$classes = ( guild_recruitment_status() == 'closed' ) ? array (
		'dragonknight' => 'low',
		'templar' => 'low',
		'sorcerer' => 'low',
		'nightblade' => 'low',
	) : array (
		'dragonknight' => 'medium',
		'templar' => 'medium',
		'sorcerer' => 'medium',
		'nightblade' => 'medium',
	);
	return $classes;
}

/*
 * Check if user is an ER member
 * Defaults to the current user, but accepts an argument for any userid
 */
function is_user_guild_member( $userid = '' ) {	
	
	if ( '' == $userid )
		$userid = get_current_user_id();
		
	$is_member = groups_is_user_member( $userid , 1 );
	return $is_member;
}

/* 
 * Get an Entropy Rising member's guild rank
 */
function display_guild_member_rank( $userid ) {
	$rank = get_guild_member_rank( $userid );
	echo '<span class="guild-rank activity">' . $rank . '</span>';
	}
	
function get_guild_member_rank( $userid ) {
	$user = get_userdata( $userid );
	$user_role = array_shift( $user->roles );
	
	if ( $user_role == 'guildinitiate' ) $guild_rank = '<i class="icon-angle-up"></i>Initiate';
	elseif ( $user_role == 'editor' ) $guild_rank = '<i class="icon-shield"></i>Officer';
	elseif ( $user_role == 'administrator' ) $guild_rank = '<i class="icon-star"></i>Guildmaster';
	else $guild_rank = '<i class="icon-double-angle-up"></i>Member';
	return $guild_rank;
}
?>