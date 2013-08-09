<?php
/**
 * Apocrypha Theme Admin Post Meta Boxes
 * Andrew Clayton
 * Version 0.1
 * 1-11-2013
 */

// Add the post SEO meta box on the 'add_meta_boxes' hook
add_action( 'add_meta_boxes', 'apoc_add_meta_box_seo', 10, 2 );

// Save the post SEO meta box data on the 'save_post' hook
add_action( 'save_post', 'apoc_meta_box_save_seo', 10, 2 );
add_action( 'add_attachment', 'apoc_meta_box_save_seo' );
add_action( 'edit_attachment', 'apoc_meta_box_save_seo' );

/**
 * Start out by adding the SEO boxes to all post types
 * @since 0.1
 */
function apoc_add_meta_box_seo( $post_type, $post ) {

	// Define Excluded Post Types
	$exclusions = array( 'slide' , 'event' , 'topic' , 'reply' );
	
	// If it's not an excluded type, register SEO meta-boxes
	if ( in_array ( $post_type , $exclusions ) ) 
		return false;

	// Only add meta box if current user can edit, add, or delete meta for the post.
	$post_type_object = get_post_type_object( $post_type );
	if ( ( true === $post_type_object->public ) && ( current_user_can( 'edit_post_meta', $post->ID ) || current_user_can( 'add_post_meta', $post->ID ) || current_user_can( 'delete_post_meta', $post->ID ) ) )
		add_meta_box( 'apoc-post-seo' , 'SEO' , 'apoc_meta_box_display_seo', $post_type, 'normal', 'high' );
}

/**
 * Displays the post SEO meta box
 * @since 0.1
 */
function apoc_meta_box_display_seo( $object, $box ) {
	wp_nonce_field( basename( __FILE__ ), 'apoc-post-seo' ); ?>
	
	<p>
		<label for="apoc-document-title">Document Title</label>
		<br />
		<input type="text" name="apoc-document-title" id="apoc-document-title" value="<?php echo esc_attr( get_post_meta( $object->ID, 'Title', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
	</p>

	<p>
		<label for="apoc-meta-description">Meta Description</label>
		<br />
		<textarea name="apoc-meta-description" id="apoc-meta-description" cols="60" rows="2" tabindex="30" style="width: 99%;"><?php echo esc_textarea( get_post_meta( $object->ID, 'Description', true ) ); ?></textarea>
	</p>
<?php }

/**
 * Save the post SEO meta box settings as post metadata.
 * @since 0.1
 */
function apoc_meta_box_save_seo( $post_id, $post = '' ) {
	
	// Fix for attachment save issue in WordPress 3.5. @link http://core.trac.wordpress.org/ticket/21963
	if ( !is_object( $post ) )
		$post = get_post();
	
	// Verify the nonce before proceeding.
	if ( !isset( $_POST['apoc-post-seo'] ) || !wp_verify_nonce( $_POST['apoc-post-seo'], basename( __FILE__ ) ) )
		return $post_id;
	
	$meta = array(
		'Title' => 	$_POST['apoc-document-title'],
		'Description' => 	$_POST['apoc-meta-description'],
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
?>