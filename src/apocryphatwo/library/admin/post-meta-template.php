<?php
/**
 * Apocrypha Theme Admin Meta Box Template
 * Andrew Clayton
 * Version 0.1
 * 1-11-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
// Add the post template meta box on the 'add_meta_boxes' hook.
add_action( 'add_meta_boxes', 'apoc_meta_box_add_template', 10, 2 );

// Save the post template meta box data on the 'save_post' hook.
add_action( 'save_post', 'apoc_meta_box_save_template', 10, 2 );
add_action( 'add_attachment', 'apoc_meta_box_save_template' );
add_action( 'edit_attachment', 'apoc_meta_box_save_template' );

/**
 * Add the post template meta box for all public post types except pages.
 * @since 0.1
 */
function apoc_meta_box_add_template( $post_type, $post ) {

	// Define Excluded Post Types
	$exclusions = array( 'slide' , 'event' , 'topic' , 'reply' , 'page' );
	
	// If it's not an excluded type, register post template dropdown
	if ( in_array ( $post_type , $exclusions ) ) 
		return false;
	
	// Only add meta box if current user can edit, add, or delete meta for the post.
	$post_type_object = get_post_type_object( $post_type );
	if ( ( true === $post_type_object->public ) && ( current_user_can( 'edit_post_meta', $post->ID ) || current_user_can( 'add_post_meta', $post->ID ) || current_user_can( 'delete_post_meta', $post->ID ) ) )
		add_meta_box( 'apoc-post-template', 'Template' , 'apoc_display_post_template', $post_type, 'side', 'default' );

}

/**
 * Display the post template meta box
 * @since 0.1
 */
function apoc_display_post_template( $object, $box ) {
	// Get the post type object.
	$post_type_object = get_post_type_object( $object->post_type );

	// Get a list of available custom templates for the post type.
	$templates = apoc_get_post_templates( $object->post_type );

	wp_nonce_field( basename( __FILE__ ), 'apoc-post-meta-box-template' ); ?>

	<p>
		<?php if ( 0 != count( $templates ) ) { ?>
		<select name="apoc-post-template" id="apoc-post-template" class="widefat">
			<option value=""></option>
			<?php foreach ( $templates as $label => $template ) { ?>
				<option value="<?php echo esc_attr( $template ); ?>" <?php selected( esc_attr( get_post_meta( $object->ID, "_wp_{$post_type_object->name}_template", true ) ), esc_attr( $template ) ); ?>><?php echo esc_html( $label ); ?></option>
			<?php } ?>
		</select>
		<?php } else { ?>
			No templates exist for this post type.
		<?php } ?>
	</p>
<?php
}

/**
 * Save the post template meta box as metadata
 * @since 0.1
 */
function apoc_meta_box_save_template( $post_id, $post = '' ) {
	// Fix for attachment save issue in WordPress 3.5. @link http://core.trac.wordpress.org/ticket/21963
	if ( !is_object( $post ) )
		$post = get_post();

	// Verify the nonce before proceeding.
	if ( !isset( $_POST['apoc-post-meta-box-template'] ) || !wp_verify_nonce( $_POST['apoc-post-meta-box-template'], basename( __FILE__ ) ) )
		return $post_id;

	// Return here if the template is not set. There's a chance it won't be if the post type doesn't have any templates.
	if ( !isset( $_POST['apoc-post-template'] ) )
		return $post_id;

	// Get the posted meta value.
	$new_meta_value = $_POST['apoc-post-template'];

	// Set the $meta_key variable based off the post type name.
	$meta_key = "_wp_{$post->post_type}_template";

	// Get the meta value of the meta key.
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	// If there is no new meta value but an old value exists, delete it.
	if ( current_user_can( 'delete_post_meta', $post_id ) && '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );

	// If a new meta value was added and there was no previous value, add it.
	elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	// If the new meta value does not match the old value, update it.
	elseif ( current_user_can( 'edit_post_meta', $post_id ) && $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );
}

?>