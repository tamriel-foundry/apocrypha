<?php
/**
 * Apocrypha Content Slider
 * Andrew Clayton
 * Version 1.0.0
 * 8-3-2013
 */
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
 
 
/*---------------------------------------------
1.0 - APOC_SLIDES CLASS
----------------------------------------------*/

/**
 * Registers the "Slide" custom post type.
 * Slides are used with the taxonomy "Slideshow" to display images for Flexslider.
 *
 * @author Andrew Clayton
 * @version 1.0.0
 */
class Apoc_Slides {

	// Register slide dimensions
	public $height 	= 360;
	public $width 	= 720;

	/**
	 * Register the custom post and taxonomy with WordPress on init
	 * @since 0.1
	 */
	function __construct() {
	
		// Add universal actions
		add_action( 'init', array( $this , 'register_slides' 		) );
		add_action( 'init', array( $this , 'register_slideshows' 	) );
		add_action( 'init', array( $this , 'register_slide_size' 	) );

		// Admin-only methods
		if ( is_admin() ) {
			
			// Admin Actions
			add_action( 'do_meta_boxes'				, array( $this , 'slide_image_box' 			) );	
			add_action( 'admin_menu'				, array( $this , 'change_slide_link' 		) );
			add_action( 'save_post'					, array( $this , 'save_meta' 				) );
			add_action( 'add_attachment'			, array( $this , 'save_meta' 				) );
			add_action( 'edit_attachment'			, array( $this , 'save_meta' 				) );
			add_action( 'manage_posts_custom_column', array( $this , 'slides_custom_columns'	) );
			
			// Admin Filters
			add_filter( 'post_updated_messages'		, array( $this , 'slide_updated_messages' 	) );
			add_filter( 'manage_edit-slide_columns'	, array( $this , 'slides_edit_columns'		) );
		}
	}
	
	/**
	 * Add a featured image size for the slides
	 * @since 0.1
	 */
	function register_slide_size() {
		
		// Register the image size
		add_image_size( 'featured-slide' , $this->width , $this->height , true );
	}

	/**
	 * Register a custom post type for Slides
	 * @version 1.0.0
	 */
	function register_slides() {

		// Labels for the backend slide publisher 
		$slide_labels = array(
			'name'					=> 'Slides',
			'singular_name'			=> 'Slide',
			'add_new'				=> 'Add New',
			'add_new_item'			=> 'Add New Slide',
			'edit_item'				=> 'Edit Slide',
			'new_item'				=> 'New Slide',
			'view_item'				=> 'View Slide',
			'search_items'			=> 'Search Slides',
			'not_found'				=> 'No slides found',
			'not_found_in_trash'	=> 'No slides found in Trash', 
			'parent_item_colon'		=> '',
			'menu_name'				=> 'Slides',
		);
		
		$slide_capabilities = array(
			'edit_post'				=> 'edit_post',
			'edit_posts'			=> 'edit_posts',
			'edit_others_posts'		=> 'edit_others_posts',
			'publish_posts'			=> 'publish_posts',
			'read_post'				=> 'read_post',
			'read_private_posts'	=> 'read_private_posts',
			'delete_post'			=> 'delete_post'
		);			
			
		// Construct the arguments for our custom slide post type 
		$slide_args = array(
			'labels'				=> $slide_labels,
			'description'			=> 'Front page showcase slides',
			'public'				=> true,
			'publicly_queryable'	=> false,
			'exclude_from_search'	=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'		=> false,
			'menu_icon'				=> THEME_URI . '/images/icons/slide-icon-20.png',
			'capabilities'			=> $slide_capabilities,
			'map_meta_cap'			=> true,
			'hierarchical'			=> false,
			'supports'				=> array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'			=> array( 'slideshow' ),
			'has_archive'			=> false,
			'rewrite'				=> false,
			'query_var'				=> true,
			'can_export'			=> true,
		);
		
		// Register the Slide post type! 
		register_post_type( 'slide', $slide_args );
	}

	/**
	 * Register a custom post taxonomy for Slideshows
	 * @since 0.1
	 */
	function register_slideshows() {
		
		$slideshow_tax_labels = array(			
			'name'							=> 'Slideshows',
			'singular_name'					=> 'Slideshow',
			'search_items'					=> 'Search Slideshows',
			'popular_items'					=> 'Popular Slideshows',
			'all_items'						=> 'All Slideshows',
			'edit_item'						=> 'Edit Slideshow',
			'update_item'					=> 'Update Slideshow',
			'add_new_item'					=> 'Add New Slideshow',
			'new_item_name'					=> 'New Slideshow Name',
			'menu_name'						=> 'Slideshows',
			'separate_items_with_commas'	=> 'Separate slideshows with commas',
			'choose_from_most_used'			=> 'Choose from the most used slideshows',
		);
		
		$slideshow_tax_caps = array(
			'manage_terms'	=> 'manage_categories',
			'edit_terms'	=> 'manage_categories',
			'delete_terms'	=> 'manage_categories',
			'assign_terms'	=> 'edit_posts'
		);
		
		$slideshow_tax_args = array(
			'labels'				=> $slideshow_tax_labels,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> false,
			'show_tagcloud'			=> false,
			'hierarchical'			=> true,
			'rewrite'				=> array( 'slug' => 'slideshow' ),
			'capabilities'    	  	=> $slideshow_tax_caps,
		);

		// Register the Slideshow post taxonomy! 
		register_taxonomy( 'slideshow', 'slide', $slideshow_tax_args );	
	}
	
	/**
	 * Place the "featured image" box in the main listing, since it's the key element here.
	 * @since 0.1
	 */
	function slide_image_box() {	
		$slide_image_title = 'Set featured slide image (' . $this->width . 'x' . $this->height . ')';
		remove_meta_box( 'postimagediv', 'slide', 'side' );
		add_meta_box( 'postimagediv', $slide_image_title, 'post_thumbnail_meta_box', 'slide', 'normal', 'high' );
	}
	
	/**
	 * Get rid of the "slug" box, show our permalink box instead.
	 * @since 0.1
	 */
	function change_slide_link() {
		remove_meta_box( 'slugdiv', 'slide', 'core' );
		add_meta_box( 'slide-settings', 'Slide Settings', 'slide_settings_box', 'slide', 'normal', 'high' );
	}
	
	/**
	 * Display inputs for our custom slide meta fields
	 * @since 0.1
	 */
	function slide_settings_box( $object , $box ) {
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
	 * Save the slide meta fields
	 * @version 1.0.0
	 */
	function save_meta( $post_id ) {
		
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
	 * Customize backend messages when a slide is updated
	 * @since 0.1
	 */
	function slide_updated_messages( $slide_messages ) {
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
	 * Adds the slide featured image and link to the slides page
	 * @since 0.1
	 */
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
	
	/**
	 * Adds content to the custom column format
	 * @since 0.1
	 */
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

}


/*---------------------------------------------
2.0 - STANDALONE FUNCTIONS
----------------------------------------------*/

/**
 * Function to load the slideshow in a theme template
 * @since 0.1
 */
function get_slideshow( $slideshow = '' , $number = 5 ) {	
	include( THEME_DIR . '/library/templates/slideshow.php' );	
} 
?>