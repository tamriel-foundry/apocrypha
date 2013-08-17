<?php
/**
 * Apocrypha Theme Admin Posts Functions
 * Andrew Clayton
 * Version 1.0.0
 * 8-17-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*---------------------------------------------
1.0 - ADMIN_POSTMETA CLASS
----------------------------------------------*/

/**
 * Generates and displays custom meta-boxes for posts in the admin section.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
class Admin_Postmeta {


	/**
	 * Registers admin actions and filters for post-related tasks
	 */
	function __construct() {
	
		// Add hooks to admin actions
		$this->actions();
	}
	

	/**
	 * Add action hooks
	 */	
	private function actions() {
	
		// Add boxes to posts
		add_action( 'add_meta_boxes'	, array( $this , 'add_meta' ) , 10, 2 );
		
		// Save postmeta
		add_action( 'save_post'			, array( $this , 'save_meta' ) , 10, 2 );
		add_action( 'add_attachment'	, array( $this , 'save_meta' ) );
		add_action( 'edit_attachment'	, array( $this , 'save_meta' ) );
	
	}
	
	/**
	 * Add meta boxes to edit screen
	 */
	function add_meta( $post_type , $post ) {
	
		// First make sure the current user can use the meta boxes
		if ( !current_user_can( 'edit_post_meta' , $post->ID ) && !current_user_can( 'add_post_meta' , $post->ID ) && !current_user_can( 'delete_post_meta' , $post->ID ) )
			return false;
		
		// Switch through different post types and add meta boxes when appropriate
		switch ( $post_type ) {
		
			case 'post' :
				add_meta_box( 'apoc-post-seo' , 'SEO' , array( $this , 'display_seo' ) , $post_type , 'normal', 'high' );
				add_meta_box( 'apoc-post-template', 'Template' , array( $this , 'display_template' ) , $post_type, 'side', 'default' );
				break;
				
			case 'page' :
				add_meta_box( 'apoc-post-seo' , 'SEO' , array( $this , 'display_seo' ) , $post_type , 'normal', 'high' );
				break;
		}
	}
		
	/**
	 * Displays the SEO meta box
	 */
	function display_seo( $post , $box ) {
	
		// Add a nonce
		wp_nonce_field( basename( __FILE__ ), 'apoc-post-seo' ); 
		
		// Display the field ?>
		<p>
			<label for="apoc-meta-description">Meta Description</label>
			<br />
			<textarea name="apoc-meta-description" id="apoc-meta-description" cols="60" rows="1" tabindex="30" style="width: 99%;"><?php echo esc_textarea( get_post_meta( $post->ID, 'description', true ) ); ?></textarea>
		</p><?php 
	}
	
	/**
	 * Displays the custom post template meta box
	 */
	function display_template( $post , $box ) {
	
		// Get the post type object.
		$post_type_object = get_post_type_object( $post->post_type );
	
		// Get the possible templates
		$templates = $this->get_post_templates( $post->post_type );
		
		// Add a nonce
		wp_nonce_field( basename( __FILE__ ), 'apoc-post-template-nonce' );
		
		// Display the field
		echo '<p>';
		if ( $templates ) : ?>
		<select name="apoc-post-template" id="apoc-post-template" class="widefat">
			<option value=""></option>
			<?php foreach ( $templates as $label => $template ) { ?>
				<option value="<?php echo esc_attr( $template ); ?>" <?php selected( esc_attr( get_post_meta( $post->ID, "_wp_{$post_type_object->name}_template", true ) ), esc_attr( $template ) ); ?>><?php echo esc_html( $label ); ?></option>
			<?php } ?>
		</select>
		<?php else :
			echo 'No templates exist for this post type.';
		endif;
		echo '</p>';	
	}
	
	/**
	 * Gets available custom post templates
	 */	
	function get_post_templates( $post_type ) {
	
		// Get the post type object.
		$post_type_object = get_post_type_object( $post_type );
		
		// Get the current theme files one level deep
		$templates 	= array();
		$files 		= wp_get_theme()->get_files( 'php', 1 );
		
		// Loop through each of the PHP files and check if they are post templates.
		foreach ( $files as $file => $path ) {

			// Get file data based on the post type singular name
			$headers = get_file_data( $path, array(	"{$post_type_object->name} Template" => "{$post_type_object->name} Template", ) );

			// Continue loop if the header is empty.
			if ( empty( $headers["{$post_type_object->name} Template"] ) )
				continue;

			// Add the PHP filename and template name to the array.
			$templates[ $file ] = $headers["{$post_type_object->name} Template"];
		}
	
		// Return array of post templates.
		return array_flip( $templates );
	}
	
	/**
	 * Save postmeta to database
	 */	
	function save_meta( $post_id , $post = '' ) {
	
		// Setup an empty array of meta key => values to save
		$meta = array();
	
		// SEO Description
		if ( isset( $_POST['apoc-meta-description'] ) && wp_verify_nonce( $_POST['apoc-post-seo'] , basename( __FILE__ ) ) )
			$meta['description'] = $_POST['apoc-meta-description'];
			
		// Custom post template
		if ( isset( $_POST['apoc-post-template'] ) && wp_verify_nonce( $_POST['apoc-post-template-nonce'] , basename( __FILE__ ) ) )
			$meta["_wp_{$post->post_type}_template"] = $_POST['apoc-post-template'];
		
		// Loop through the registered meta, saving each value
		foreach ( $meta as $meta_key => $new_meta_value ) {
	
			// Get the current meta value for the meta key
			$meta_value = get_post_meta( $post_id, $meta_key, true );

			// If there is no new meta value but an old value exists, delete it
			if ( current_user_can( 'delete_post_meta' ) && '' == $new_meta_value && $meta_value )
				delete_post_meta( $post_id, $meta_key );

			// Otherwise update or add the new meta value
			elseif ( current_user_can( 'edit_post_meta' ) && $new_meta_value  )
				update_post_meta( $post_id, $meta_key , $new_meta_value , $meta_value );
		}
	}
	
}

// Initialize the class
new Admin_Postmeta();
?>