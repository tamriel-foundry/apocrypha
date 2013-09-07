<?php
/**
 * Apocrypha Theme Change Avatar Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
 
// Setup the edit profile form
global $bp;
$user_id	= $bp->displayed_user->id;
$action_url = $bp->displayed_user->domain . $bp->profile->slug . '/change-avatar/';
?>

<div id="user-profile" role="main">

	<form method="post" id="edit-profile-form" action="<?php echo $action_url; ?>" enctype="multipart/form-data">

		<div class="instructions">
			<h3 class="double-border bottom">Upload Avatar</h3>
			<ul>
				<li>Upload an image to use as your personal avatar</li>
				<li>This image will be used to identify you throughout the site.</li>
				<li>Avatars are automatically resized to 200 pixel jpeg files after cropping.</li>
				<li>Most images will distort less as a result of cropping if you resize them manually before uploading.</li>
				<li>If you'd like to delete your current avatar without uploading a new one, please use the delete avatar button.</li>
			</ul>
		</div>
		
		<?php // Upload Image Step
		if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>
			<fieldset>
				<legend>Upload or Delete Image</legend>
				<input type="file" name="file" id="file" />
				
				<?php wp_nonce_field( 'bp_avatar_upload' ); ?>
				<input type="hidden" name="action" id="action" value="bp_avatar_upload" />		

				<button type="submit" name="upload" id="upload" class="button">
					<i class="icon-upload-alt"></i>Upload New Avatar				
				</button>
				
				<?php if ( bp_get_user_has_avatar() ) : ?>
					<a class="button" href="<?php bp_avatar_delete_link(); ?>" title="Delete Current Avatar"><i class="icon-remove"></i>Delete Current Avatar</a>
				<?php endif; ?>
			</fieldset>
		
		<?php // Crop Image Step
		elseif ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>
		<fieldset>
				<legend>Crop Image</legend>
				
				<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Avatar to crop', 'buddypress' ); ?>" />
				
				<div id="avatar-crop-pane">
					<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php _e( 'Avatar preview', 'buddypress' ); ?>" />
				</div>
				
				<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
				<input type="hidden" id="x" name="x" />
				<input type="hidden" id="y" name="y" />
				<input type="hidden" id="w" name="w" />
				<input type="hidden" id="h" name="h" />
				<?php wp_nonce_field( 'bp_avatar_cropstore' ); ?>
				
				<button type="submit" name="avatar-crop-submit" id="avatar-crop-submit" class="button">
					<i class="icon-picture"></i>Crop Image				
				</button>
					
		</fieldset>
		<?php endif; ?>
	
	</form>
</div><!-- #user-profile -->