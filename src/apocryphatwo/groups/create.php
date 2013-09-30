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
				
				<li class="text form-left">
					<label for="group-website"><i class="icon-home icon-fixed-width"></i>Group Website: </label>
					<input type="url" name="group-website" id="group-website" value="<?php echo $guild->website; ?>" size="50" />
				</li>
						
				<li class="select form-right">
					<label for="group-platform"><i class="icon-desktop icon-fixed-width"></i>Platform:</label>
					<select name="group-platform" id="group-platform">
						<option value="blank"></option>
						<option value="pcmac">PC / Mac</option>
						<option value="xbox">Xbox One</option>
						<option value="playstation">PlayStation 4</option>
					</select>
				</li>

				<?php if ( current_user_can( 'edit_posts' ) ) : ?>
				<li class="checkbox form-left">
					<label for="group-type"><i class="icon-group icon-fixed-width"></i>Group Type &#9734; :</label>
					<input type="radio" name="group-type" value="group"><label for="group-type">Group</label>
					<input type="radio" name="group-type" value="guild"><label for="group-type">Guild</label>
				</li>
				<?php endif; ?>

				<li class="select form-right">
					<label for="group-faction"><i class="icon-flag icon-fixed-width"></i>Faction Allegiance (&#9734;) :</label>
					<select name="group-faction" id="group-faction">
						<option value="neutral">Undeclared</option>
						<option value="aldmeri">Aldmeri Dominion</option>
						<option value="daggerfall">Daggerfall Covenant</option>
						<option value="ebonheart">Ebonheart Pact</option>
					</select>
				</li>
				
				<li class="checkboxes form-left">
					<label for="group-interests"><i class="icon-gear icon-fixed-width"></i>Group Interests (&#9734;) :</label><br>
					<ul id="group-interests-list" class="radio-options-list">
						<li><input type="checkbox" name="group-interests[]" value="pve"><label for="group-interests">Player vs. Environment (PvE)</label></li>
						<li><input type="checkbox" name="group-interests[]" value="pvp"><label for="group-interests">Player vs. Player (PvP)</label></li>
						<li><input type="checkbox" name="group-interests[]" value="rp"><label for="group-interests">Roleplaying (RP)</label></li>
						<li><input type="checkbox" name="group-interests[]" value="crafting"><label for="group-interests">Crafting</label></li>
					</ul>
				</li>	
				
				<li class="select form-right">
					<label for="group-region"><i class="icon-globe icon-fixed-width"></i>Region (&#9734;) :</label>
					<select name="group-region" id="group-region">
						<option value="blank"></option>
						<option value="NA">North America</option>
						<option value="EU">Europe</option>
						<option value="OC">Oceania</option>
					</select>
				</li>
				
				<li class="select form-right">
					<label for="group-style"><i class="icon-shield icon-fixed-width"></i>Guild Playstyle:</label>
					<select name="group-style" id="group-style">
						<option value="blank"></option>
						<option value="casual">Casual</option>
						<option value="moderate">Moderate</option>
						<option value="hardcore">Hardcore</option>
					</select>
				</li>

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
					<input type="radio" name="group-status" value="public"<?php if ( 'public' == bp_get_new_group_status() || !bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
					<label><i class="icon-unlock icon-fixed-width"></i><strong>This is a public guild.</strong></label>
					<ul class="radio-options-list">
						<li>Any Tamriel Foundry member can join this guild.</li>
						<li>This guild will be listed in the guilds directory and will appear in search results.</li>
						<li>Guild content and activity will be visible to all site members and guests.</li>
					</ul>
				</li>

				<li class="checkbox">				
					<input type="radio" name="group-status" value="private"<?php if ( 'private' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
					<label><i class="icon-lock icon-fixed-width"></i><strong>This is a private guild.</strong></label>
					<ul class="radio-options-list">
						<li>Only users who request membership and are accepted can join this guild.</li>
						<li>This guild will be listed in the guilds directory and will appear in search results.</li>
						<li>Guild content and activity will only be visible to guild members.</li>
					</ul>
				</li>
					
				<li class="checkbox">			
					<input type="radio" name="group-status" value="hidden"<?php if ( 'hidden' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> />
					<label><i class="icon-eye-close icon-fixed-width"></i><strong>This is a hidden guild.</strong></label>
					<ul class="radio-options-list">
						<li>Only users who are invited can join this guild</li>
						<li>This guild will not be listed in the guilds directory or search results.</li>
						<li>Guild content and activity will only be visible to guild members</li>
					</ul>
				</li>
									
				<li class="radio">
					<p><i class="icon-legal icon-fixed-width"></i><strong>Set guild invitation permissions</strong></p>
					<ul class="radio-options-list">
						<li><input type="radio" name="group-invite-status" value="members" /><label for="group-status">All guild members.</label></li>
						<li><input type="radio" name="group-invite-status" value="mods" /><label for="group-status">Guild leaders and officers only.</label></li>
						<li><input type="radio" name="group-invite-status" value="admins" checked="checked" /><label for="group-status">Guild leaders only.</label></li>
					</ul>
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
				
			<?php if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
				<div id="invite-list" class="group-invites">
					<h3 class="double-border bottom">Your Friends</h3>
					<ul id="group-invite-list">
						<?php apoc_group_invite_friend_list(); ?>
					</ul>
					
				</div>
				
				<div id="invited-list" class="group-invites">
					<h3 class="double-border bottom">Selected Friends</h3>
					<?php /* The ID 'friend-list' is important for AJAX support. */ ?>
					<ul id="friend-list" class="directory-list">
					<?php if ( bp_group_has_invites() ) : ?>
						<?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>
							<li id="<?php bp_group_invite_item_id(); ?>" class="member directory-entry">
								<?php global $invites_template;
								$userid = $invites_template->invite->user->id;
								$user = new Apoc_User( $userid , 'directory' );	?>
								<div class="directory-member">
									<?php echo $user->block; ?>
								</div>
								<div class="directory-content">
									<span class="activity"><?php bp_group_invite_user_last_active(); ?></span>
									<div class="actions">
										<a class="button remove" href="<?php bp_group_invite_user_remove_invite_url(); ?>" id="<?php bp_group_invite_item_id(); ?>"><i class="icon-remove"></i>Remove Invite</a>
									</div>
									<?php if ( $user->status['content'] ) : ?>
									<blockquote class="user-status">
										<p><?php echo $user->status['content']; ?></p>
									</blockquote>
									<?php endif; ?>
								</div>
							</li>
						<?php endwhile; ?>
					<?php endif; ?>
					</ul>
				</div>					
			<?php else : ?>
				<div class="instructions">
					<h3 class="double-border bottom">Invite Friends to Join This <?php echo ucfirst( $guild->type ); ?></h3>
					<p><?php _e( 'Once you have built up friend connections you will be able to invite others to your group. You can send invites any time in the future by selecting the "Send Invites" option when viewing your new group.', 'buddypress' ); ?></p>
				</div>
			<?php endif; ?>
			<ol id="group-create-list">
				<li class="hidden">
					<?php wp_nonce_field( 'groups_send_invites', '_wpnonce_send_invites' ); ?>
					<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); ?>
					<?php wp_nonce_field( 'groups_create_save_group-invites' ); ?>
				</li>
			<?php endif; ?>
				
			<?php // STEP ? - Plugin Steps ?>
			<?php do_action( 'groups_custom_create_steps' ); ?>
			
			<?php // Shared stuff for every step ?>
				<?php if ( 'crop-image' != bp_get_avatar_admin_step() ) : ?>
					<li id="next" class="submit form-right" >

						<?php // Next Button
						if ( !bp_is_last_group_creation_step() && !bp_is_first_group_creation_step() ) : ?>
							<button type="submit" id="group-creation-next" name="save"><i class="icon-forward"></i>Next Step</button>
						<?php endif;?>

						<?php // Create Button (on Step 1)
						if ( bp_is_first_group_creation_step() ) : ?>
							<button type="submit" id="group-creation-create" name="save"><i class="icon-group"></i>Create Group and Continue</button>
						<?php endif; ?>

						<?php // Finish Button
						if ( bp_is_last_group_creation_step() ) : ?>
							<button type="submit" id="group-creation-finish" name="save"><i class="icon-ok"></i>Finish</button>
						<?php endif; ?>
					</li>
					
					<li id="previous" class="submit form-left">
						<?php // Previous Button
						if ( !bp_is_first_group_creation_step() ) : ?>
							<button id="group-creation-previous" name="previous" onclick="location.href='<?php bp_group_creation_previous_link(); ?>'"><i class="icon-backward"></i>Back to Previous Step</button>
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
