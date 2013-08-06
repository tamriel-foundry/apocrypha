<?php
/**
 * Apocrypha Content Slider Admin Functions
 * Andrew Clayton
 * Version 1.0
 * 8-3-2013
 */

/**
 * Customize backend messages when a slide is updated
 * @since 0.1
 */
add_filter( 'post_updated_messages', 'slideshow_updated_messages' );
function slideshow_updated_messages( $slide_messages ) {
	global $post, $post_ID;
	
	// Set some simple messages for editing slides, no post previews needed. 
	$slide_messages['slide'] = array( 
		0	=> '',
		1	=> 'Slide updated.',
		2	=> 'Custom field updated.',
		2	=> 'Custom field deleted.',
		4	=> 'Slide updated.',
		5	=> isset($_GET['revision']) ? sprintf( 'Slide restored to revision from %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6	=> 'Slide published.',
		7	=> 'Slide saved.',
		8	=> 'Slide submitted.',
		9	=> sprintf( 'Slide scheduled for: <strong>%1$s</strong>.' , strtotime( $post->post_date ) ),
		10	=> 'Slide draft updated.',
	);
	return $slide_messages;
}

/**
 * Let's move the "featured image" box to the main column, since it's the key element here.
 * @since 0.1
 */
add_action( 'do_meta_boxes', 'slideshow_image_box' );
function slideshow_image_box() {	
	$slide_dimensions = slideshow_slide_dimensions();
	$slide_image_title = 'Set featured slide image (' . $slide_dimensions['width'] . 'x' . $slide_dimensions['height'] . ')';
	remove_meta_box( 'postimagediv', 'slide', 'side' );
	add_meta_box( 'postimagediv', $slide_image_title, 'post_thumbnail_meta_box', 'slide', 'normal', 'high' );
}

/**
 * Get rid of the "slug" box, show our permalink box instead.
 * @since 0.1
 */
add_action( 'admin_menu', 'slideshow_change_slide_link' );
function slideshow_change_slide_link() {
	remove_meta_box( 'slugdiv', 'slide', 'core' );
	add_meta_box( 'slide-settings', 'Slide Settings', 'slideshow_settings_box', 'slide', 'normal', 'high' );
}

/**
 * Set up a nice "permalink" box for the destination of the slide link 
 * @since 0.1
 */
function slideshow_settings_box( $object , $box ) {
	wp_nonce_field( basename( __FILE__ ), 'slideshow-settings-box' ); ?>
	<p>
		<label for="slide-tabtitle">Slide Tab Text</label>
		<br />
		<input type="text" name="slide-tabtitle" id="slide-tabtitle" value="<?php echo esc_attr( get_post_meta( $object->ID, 'TabTitle', true ) ); ?>" size="55" tabindex="10" style="width: 99%;" />
	</p>
	
	<p>
		<label for="slide-permalink">Set Slide Permalink</label>
		<br />
		<input type="text" name="slide-permalink" id="slide-permalink" value="<?php echo esc_attr( get_post_meta( $object->ID, 'Permalink', true ) ); ?>" size="55" tabindex="10" style="width: 99%;" />
	</p><?php 
}

/**
 * Save the slide settings as postmeta.
 * @since 1.0
 */
// Save the post SEO meta box data on the 'save_post' hook 
add_action( 'save_post', 'slideshow_settings_save_meta', 10, 2 );
add_action( 'add_attachment', 'slideshow_settings_save_meta' );
add_action( 'edit_attachment', 'slideshow_settings_save_meta' );
function slideshow_settings_save_meta( $post_id, $post = '' ) {
	
	// Verify the nonce before proceeding. 
	if ( !isset( $_POST['slideshow-settings-box'] ) || !wp_verify_nonce( $_POST['slideshow-settings-box'], basename( __FILE__ ) ) )
		return $post_id;
	
	// Assign names for the slide metadata 
	$meta = array(
		'TabTitle' => 	$_POST['slide-tabtitle'],
		'Permalink' => 	$_POST['slide-permalink'],
	);
	
	foreach ( $meta as $meta_key => $new_meta_value ) {
	
		// Get the meta value of the custom field key. 
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		// If there is no new meta value but an old value exists, delete it. 
		if ( current_user_can( 'delete_post_meta', $post_id, $meta_key ) && '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );

		// If a new meta value was added and there was no previous value, add it. 
		elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		// If the new meta value does not match the old value, update it. 
		elseif ( current_user_can( 'edit_post_meta', $post_id, $meta_key ) && $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );
	}
}

/**
 * Adds the slide featured image and link to the slides page
 * @since 0.1
 */
add_filter( 'manage_edit-slide_columns', 'slides_edit_columns' );
function slides_edit_columns( $columns ) {
	$columns = array(		
		'cb'			=> '<input type="checkbox" />',
		'slide'			=> 'Slide Image',
		'title'			=> 'Slide Title',
		'show'			=> 'Slideshow',
		'slide-link'	=> 'Slide Link',
		'date'			=> 'Date',
	);
	return $columns; 
}
add_action( 'manage_posts_custom_column', 'slides_custom_columns' );
function slides_custom_columns( $columns ) {
	global $post;
	switch ( $columns ) {
		case 'slide' :
			echo get_the_post_thumbnail( $post->ID , 'medium');
		break;
		
		case 'show' :
			echo get_the_term_list( $post->ID , 'slideshow' );
		break;
		
		case 'slide-link' :	
			if ( get_post_meta($post->ID, "Permalink", $single = true) != "" ) {
				echo "<a href='" . get_post_meta($post->ID, "Permalink", $single = true) . "'>" . get_post_meta($post->ID, "Permalink", $single = true) . "</a>";
			} else {
				'No Link';
			}	
		break;
	}
}
?>