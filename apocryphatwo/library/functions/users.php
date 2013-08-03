<?php
/**
 * Apocrypha Theme User Functions
 * Andrew Clayton
 * Version 2.0
 * 8-1-2013
 */
 

/** 
 * Get a user's avatar without using gravatar, uses custom defaults
 * @since 2.0
 */
function apoc_fetch_avatar( $user_id , $type='thumb' , $size=100 ) {
	
	if ( $user_id > 0 ) {
		$avatar	= bp_core_fetch_avatar( $args = array (
			'item_id' 		=> $user_id,
			'type'			=> $type,
			'height'		=> $size,
			'width'			=> $size,
			'no_grav'		=> true,
			));
		if ( strrpos( $avatar , 'mystery-man.jpg' ) ) 
			$avatar = apoc_guest_avatar( $type , $size ); 
	}
	else $avatar = apoc_guest_avatar( $type , $size ); 
	return $avatar;
}

/**
 * Randomly selects and returns a guest avatar from the available choices
 * @since 2.0
 */
function apoc_guest_avatar( $type ='thumb' , $size = 100 ) {
	$avsize = ( 'full' == $type ) ? '-large' : '' ;
	$avatars = array( 'aldmeri' , 'daggerfall' , 'ebonheart' , 'undecided' );
	$avatar = $avatars[array_rand($avatars)];
    $src = trailingslashit( THEME_URI ) . "images/avatars/{$avatar}{$avsize}.jpg";
	$guest_avatar = '<img src="' . $src . '" alt="Avatar Image" class="avatar" width="' . $size . '" height="' . $size . '">';
    return $guest_avatar;
}
