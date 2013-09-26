<?php 
/**
 * Apocrypha Theme Create Group Template
 * Andrew Clayton
 * Version 1.0
 * 1-28-2013
 */
 
// Get the current user info
$user 		= apocrypha()->user->data;
$user_id	= $user->ID;
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar">
		<?php apoc_breadcrumbs(); ?>
		
		<header id="directory-header" class="entry-header <?php page_header_class(); ?>">
			<h1 class="entry-title">Create New Guild</h1>
			<p class="entry-byline">Add a new guild to the Tamriel Foundry guilds directory.</p>
			<a class="button" href="<?php echo SITEURL . '/' . bp_get_groups_root_slug(); ?>">Back to Guilds</a>	
		</header>
		
		<form action="<?php bp_group_creation_form_action(); ?>" method="post" id="create-group-form" class="standard-form" enctype="multipart/form-data">
		
			<nav class="directory-subheader no-ajax" id="subnav">
				<ul id="profile-tabs" class="tabs no-ajax">
					<?php bp_group_creation_tabs(); ?>
				</ul>
			</nav>	
			<?php do_action( 'template_notices' ); ?>
		
			<?php // STEP 1 - Basic Group Details
			if ( bp_is_group_creation_step( 'group-details' ) ) : ?>
			
				<div class="instructions">
					<h3 class="double-border bottom">Step 1 - Basic Guild Details</h3>
					<ul>
						<li>Enter the guild name, description, and basic details.</li>
						<li>Please ensure to strip unnecessary html formatting out of guild descriptions before finalizing them.</li>
						<li>Fields denoted with a star (&#9734;) are required.</li>
					</ul>
				</div>
				
				<ol id="group-create-list">
					<li class="text">
						<label for="group-name"><i class="icon-bookmark"></i> Guild Name (&#9734;) :</label>
						<input type="text" name="group-name" id="group-name" aria-required="true" value="<?php bp_new_group_name(); ?>" size="100" />
					</li>
					
					<li class="textarea">
						<label for="group-desc"><i class="icon-edit"></i>Guild Description (&#9734;) :</label><br>
						<?php // Load the TinyMCE Editor
						$thecontent = bp_get_new_group_description();
						wp_editor( htmlspecialchars_decode( $thecontent, ENT_QUOTES ), 'group-desc', array(
							'media_buttons' => false,
							'wpautop'		=> true,
							'editor_class'  => 'group-description',
							'quicktags'		=> true,
							'teeny'			=> false,
						) ); ?>		
					</li>
					
					<?php // @TODO - ADD CUSTOM GROUP FIELDS ?>
					<li class="extra">					
						<?php do_action( 'bp_after_group_details_creation_step' ); ?>
						<?php wp_nonce_field( 'groups_create_save_group-details' ); ?>
					</li>
			<?php endif; ?>	
		
			<?php // STEP 2 - Visibility Settings
			if ( bp_is_group_creation_step( 'group-settings' ) ) : ?>
				<div class="instructions">
					<h3 class="double-border bottom">Step 2 - Guild Visibility Settings</h3>
					<ul>
						<li>Choose the desired privacy and visibility settings for this guild.</li>
						<li>Select which types of group members are allowed to invite others to join.</li>
						<li>Specify whether to set up a private guild forum.</li>
					</ul>
				</div>
				
				<ol id="group-create-list">
					<li class="checkbox">
						<h3>Privacy Settings</h3>
						<input type="radio" name="group-status" value="public"<?php if ( 'public' == bp_get_new_group_status() || !bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> /><label for="group-status">Public Guild - Any site member can freely join this guild. This guild will show up in the directory and search results. Guild activity will be visible to any site members.</label><br>
						<input type="radio" name="group-status" value="private"<?php if ( 'private' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> /><label for="group-status">Private Guild - Only users who request membership and are accepted can join the group. This group will be listed in the groups directory and in search results. Group content and activity will only be visible to members of the group.</label><br>
						<input type="radio" name="group-status" value="hidden"<?php if ( 'hidden' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> /><label for="group-status">Hidden Guild - Only users who are invited can join the group. This group will not be listed in the groups directory or search results. Group content and activity will only be visible to members of the group.</label>
					</li>
					
					<li class="checkbox">
						<h3>Invitation Permissions</h3>
						<input type="radio" name="group-invite-status" value="members" /><label for="group-status">All guild members.</label><br>
						<input type="radio" name="group-invite-status" value="mods" /><label for="group-status">Guild leaders and officers only.</label><br>
						<input type="radio" name="group-invite-status" value="admins" checked="checked" /><label for="group-status">Guild leaders only.</label>
					</li>
		
					<li class="extra">
						<?php do_action( 'bp_after_group_settings_creation_step' ); ?>
						<?php wp_nonce_field( 'groups_create_save_group-settings' ); ?>
					</li>
			<?php endif; ?>
			
			<?php // STEP 3 - bbPress Forum Settings, see bbpress/includes/extend/buddypress/groups.php ?>
			
			<?php // STEP 4 - Avatar Uploads	
			if ( bp_is_group_creation_step( 'group-avatar' ) ) : ?>
				<div class="instructions">
					<h3 class="double-border bottom">Step 4 - Upload Guild Avatar</h2>
					<ul>
						<li>Upload an image to use as the guild avatar.</li>
						<li>The image will be shown on the main group page, and in search results.</li>
						<li>Avatars are automatically resized to 200 pixel jpegs after cropping.</li>
						<li>You may skip the avatar upload process by hitting the "Next Step" button.</li>
					</ul>
				</div>
			
				<ol id="group-create-list">			
				<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>
					<li class="file">
						<?php bp_new_group_avatar( $args = array(
								'type' => 'full',
								'width' => 200,
								'height' => 200,
								'no_grav' => true,
								) ); ?>
						<input type="file" name="file" id="file" />
						<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
						<button type="submit" name="upload" id="upload" class="button">
							<i class="icon-upload-alt"></i>Upload New Avatar				
						</button>
					</li>
				<?php elseif ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>
					<li class="file">
						<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Avatar to crop', 'buddypress' ); ?>" />
						<div id="avatar-crop-pane">
							<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php _e( 'Avatar preview', 'buddypress' ); ?>" />
						</div>
					</li>
					
					<li class="submit">
						<button type="submit" name="avatar-crop-submit" id="avatar-crop-submit">
							<i class="icon-crop"></i>Crop Image</i>
						</button>
					</li>
					
					<li class="hidden">
						<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
						<input type="hidden" name="upload" id="upload" />
						<input type="hidden" id="x" name="x" />
						<input type="hidden" id="y" name="y" />
						<input type="hidden" id="w" name="w" />
						<input type="hidden" id="h" name="h" />
					</li>
				<?php endif; ?>
				
				<li class="extra">
					<?php do_action( 'bp_after_group_avatar_creation_step' ); ?>
					<?php wp_nonce_field( 'groups_create_save_group-avatar' ); ?>
				</li>		
			<?php endif; ?>
			
			<?php // STEP 5 - Invite Friends
			if ( bp_is_group_creation_step( 'group-invites' ) ) : ?>
				<div class="instructions">
					<h3 class="double-border bottom">Step 5 - Invite Friends to Group</h3>
					<ul>
						<li>Invite friends to participate in this new guild.</li>
						<li>Members may always be added later on.</li>
						<li>To directly invite new members they must first be on your friends list.</li>
					</ul>
				</div>
				
				<ol id="group-create-list">	
			
			
			
			<?php // STEP ? - Plugin Steps ?>
			<?php do_action( 'groups_custom_create_steps' ); ?>		
			
			<?php // Shared stuff for every step ?>
				<?php if ( 'crop-image' != bp_get_avatar_admin_step() ) : ?>
					<li id="previous-next" class="submit" >

						<?php // Previous Button ?>
						<?php if ( !bp_is_first_group_creation_step() ) : ?>
							<input type="button" value="<?php _e( 'Back to Previous Step', 'buddypress' ); ?>" id="group-creation-previous" name="previous" onclick="location.href='<?php bp_group_creation_previous_link(); ?>'" />
						<?php endif; ?>

						<?php // Next Button ?>
						<?php if ( !bp_is_last_group_creation_step() && !bp_is_first_group_creation_step() ) : ?>
							<input type="submit" value="<?php _e( 'Next Step', 'buddypress' ); ?>" id="group-creation-next" name="save" />
						<?php endif;?>

						<?php // Create Button (on Step 1) ?>
						<?php if ( bp_is_first_group_creation_step() ) : ?>
							<input type="submit" value="<?php _e( 'Create Group and Continue', 'buddypress' ); ?>" id="group-creation-create" name="save" />
						<?php endif; ?>

						<?php // Finish Button ?>
						<?php if ( bp_is_last_group_creation_step() ) : ?>
							<input type="submit" value="<?php _e( 'Finish', 'buddypress' ); ?>" id="group-creation-finish" name="save" />
						<?php endif; ?>
					</li>
				<?php endif; ?>
					
					<li class="hidden">
						<input type="hidden" name="group_id" id="group_id" value="<?php bp_new_group_id(); ?>" />
					</li>
				</ol><!-- #create-group-list -->			
			
		</form><!-- #create-group-form -->
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>

