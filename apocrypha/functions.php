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
 * Most "run once per pageload" things are done here also.
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
2.0 - SCRIPTS AND STYLES
--------------------------------------------------------------*/

/**
 * Load stylesheets and JavaScript based on context
 * @version 1.0.0
 */
add_action( 'wp_enqueue_scripts' , 'apoc_enqueue_scripts' );
function apoc_enqueue_scripts() {

	// Deregister Styles
	add_filter( 'use_default_gallery_style' , '__return_false' );
	
	// Register Styles
	wp_register_style( 'primary' 		, THEME_URI . '/style.css' , false , $ver=filemtime( THEME_DIR . "/style.css" ) );
	wp_register_style( 'font-awesome' 	, 'http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css' , false );
	
	// Enqueue Styles
	wp_enqueue_style( 'font-awesome' );
	wp_enqueue_style( 'primary' );

	// Deregister Scripts
	wp_deregister_script( 'jquery' );
	
	// Register Scripts
	wp_register_script( 'jquery' 		, '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' ,'jquery' , $ver ='1.10.2' , true );
	wp_register_script( 'foundry' 		, THEME_URI.'/library/js/foundry.js' 		, 'jquery' 	, $ver='0.60' 	, true	);
	wp_register_script( 'buddypress'	, THEME_URI.'/library/js/buddypress.js' 	, 'jquery' 	, $ver='0.36' 	, true 	);	
	wp_register_script( 'analytics'		, THEME_URI.'/library/js/ga.js' 			, false 	, $ver='0.1' 	, false	);	

	// Enqueue Scripts
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'buddypress' );
	wp_enqueue_script( 'foundry' );
	wp_enqueue_script( 'analytics' );

	
	// QuantCast
	if ( !apoc_is_donor() ) {
		wp_register_script( 'quantcast'		, THEME_URI.'/library/js/qc.js' 			, false 	, $ver='0.1' 	, false	);	
		wp_enqueue_script( 'quantcast' );
	}
	
	// FlexSlider
	if ( is_home() || is_page_template( 'guild/guild-home.php' ) ) {
		wp_register_script( 'flexslider' 	, THEME_URI.'/library/js/flexslider.min.js' , 'jquery' 	, $ver='0.1' 	, true  );
		wp_enqueue_script( 'flexslider' );
	}
	
	// Contact Form
	elseif ( is_page( 'contact-us' ) ) {
		wp_register_script( 'contactform'	, THEME_URI.'/library/js/contactform.js' 	, 'jquery' 	, $ver='0.1' 	, true 	);
		wp_enqueue_script( 'contactform' );
	}
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