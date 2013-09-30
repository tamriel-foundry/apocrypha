<?php 
/**
 * Apocrypha Theme Group Admin Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
 
// Retrieve the guild object
global $guild;
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_group_admin_tabs(); ?>
	</ul>
</nav><!-- #subnav -->
 
<form action="<?php bp_group_admin_form_action(); ?>" name="group-settings-form" id="group-settings-form" class="standard-form" method="post" enctype="multipart/form-data" role="main">
	
	<?php // Edit Group Details
	if ( bp_is_group_admin_screen( 'edit-details' ) ) : ?>
		<div class="instructions">
			<h3 class="double-border bottom">Basic Guild Description</h3>
			<ul>
				<li>You can fine tune many aspects of your guild's presentation on Tamriel Foundry below.</li>
				<li>Provide some information about your guild's role within <em>The Elder Scrolls Online</em>.</li>
				<li>Listing information such as your playstyle, interests, region, and recruitment status will assist prospective members in evaluating the appeal of your guild.</li>
				<li>Fields denoted with a star (&#9734;) are required, however, there are many optional fields included which can help refine your guild's categorization within the community.</li>
				<li>Including a link to a permanent guild website is also encouraged. Tamriel Foundry is happy to provide tools for existing guilds to interact within the ESO community, but we are not a substitute for a full guild website and the tools which one can provide!</li>
			</ul>
		</div>
		<ol class="group-edit-list">
			<li class="text">
				<label for="group-name"><i class="icon-bookmark icon-fixed-width"></i>Group Name &#9734; :</label>
				<input type="text" name="group-name" id="group-name" value="<?php bp_group_name(); ?>" aria-required="true" size="50" />
			</li>
			
			<li class="textarea">
				<label for="group-desc"><i class="icon-edit icon-fixed-width"></i>Group Description &#9734; :</label>
				<?php // Load the TinyMCE Editor
				$thecontent = bp_get_group_description();
				wp_editor( htmlspecialchars_decode( $thecontent, ENT_QUOTES ), 'group-desc', array(
					'media_buttons' => false,
					'wpautop'		=> true,
					'editor_class'  => 'group-description',
					'quicktags'		=> true,
					'teeny'			=> false,
					)
				);	
				/*<textarea name="group-desc" id="group-desc" aria-required="true"><?php bp_group_description_editable(); ?></textarea>*/ ?>
			</li>
			
			<li class="text form-left">
				<label for="group-website"><i class="icon-home icon-fixed-width"></i>Group Website: </label>
				<input type="url" name="group-website" id="group-website" value="<?php echo $guild->website; ?>" size="50" />
			</li>

			<?php if ( current_user_can( 'edit_posts' ) ) : ?>
			<li class="checkbox form-right">
				<label for="group-type"><i class="icon-group icon-fixed-width"></i>Group Type &#9734; :</label>
				<input type="radio" name="group-type" value="group" <?php checked( $guild->guild, 0 , true ) ?>><label for="group-type">Group</label>
				<input type="radio" name="group-type" value="guild" <?php checked( $guild->guild, 1 , true ) ?>><label for="group-type">Guild</label>
			</li>
			<?php endif; ?>
			
			<li class="select form-left">
				<label for="group-platform"><i class="icon-desktop icon-fixed-width"></i>Platform:</label>
				<select name="group-platform" id="group-platform">
					<option value="blank" <?php selected( $guild->platform, 'blank' ); ?>></option>
					<option value="pcmac" <?php selected( $guild->platform, 'pcmac' ); ?>>PC / Mac</option>
					<option value="xbox" <?php selected( $guild->platform, 'xbox' ); ?>>Xbox One</option>
					<option value="playstation" <?php selected( $guild->platform, 'playstation' ); ?>>PlayStation 4</option>
				</select>
			</li>

			<li class="select form-right">
				<label for="group-faction"><i class="icon-flag icon-fixed-width"></i>Faction Allegiance (&#9734;) :</label>
				<select name="group-faction" id="group-faction">
					<option value="neutral" <?php selected( $guild->alliance, 'neutral' ); ?>>Undeclared</option>
					<option value="aldmeri" <?php selected( $guild->alliance, 'aldmeri' ); ?>>Aldmeri Dominion</option>
					<option value="daggerfall" <?php selected( $guild->alliance, 'daggerfall' ); ?>>Daggerfall Covenant</option>
					<option value="ebonheart" <?php selected( $guild->alliance, 'ebonheart' ); ?>>Ebonheart Pact</option>
				</select>
			</li>
			
			<li class="select form-left">
				<label for="group-region"><i class="icon-globe icon-fixed-width"></i>Region (&#9734;) :</label>
				<select name="group-region" id="group-region">
					<option value="blank" <?php selected( $guild->region, 'blank' ); ?>></option>
					<option value="NA" <?php selected( $guild->region, 'NA' ); ?>>North America</option>
					<option value="EU" <?php selected( $guild->region, 'EU' ); ?>>Europe</option>
					<option value="OC" <?php selected( $guild->region, 'OC' ); ?>>Oceania</option>
				</select>
			</li>
			
			<li class="select form-right">
				<label for="group-style"><i class="icon-shield icon-fixed-width"></i>Guild Playstyle:</label>
				<select name="group-style" id="group-style">
					<option value="blank" <?php selected( $guild->style, 'blank' ); ?>></option>
					<option value="casual" <?php selected( $guild->style, 'casual' ); ?>>Casual</option>
					<option value="moderate" <?php selected( $guild->style, 'moderate' ); ?>>Moderate</option>
					<option value="hardcore" <?php selected( $guild->style, 'hardcore' ); ?>>Hardcore</option>
				</select>
			</li>
			
			<li class="checkboxes">
				<label for="group-interests"><i class="icon-gear icon-fixed-width"></i>Group Interests (&#9734;) :</label><br>
				<ul id="group-interests-list" class="radio-options-list">
					<li><input type="checkbox" name="group-interests[]" value="pve" <?php checked( in_array( 'pve' , $guild->interests ) , 1 ); ?>><label for="group-interests">Player vs. Environment (PvE)</label></li>
					<li><input type="checkbox" name="group-interests[]" value="pvp" <?php checked( in_array( 'pvp' , $guild->interests ) , 1 ); ?>><label for="group-interests">Player vs. Player (PvP)</label></li>
					<li><input type="checkbox" name="group-interests[]" value="rp" <?php checked( in_array( 'rp' , $guild->interests ) , 1 ); ?>><label for="group-interests">Roleplaying (RP)</label></li>
					<li><input type="checkbox" name="group-interests[]" value="crafting" <?php checked( in_array( 'crafting' , $guild->interests ) , 1 ); ?>><label for="group-interests">Crafting</label></li>
				</ul>
			</li>	
							
			<?php // Allow plugins to hook in 
			do_action( 'groups_custom_group_fields_editable' ); ?>
		
			<li class="checkbox">
				<label for="group-notifiy-members"><i class="icon-envelope icon-fixed-width"></i>Send a notification to group members of these changes?</label><input type="radio" name="group-notify-members" value="1" /><label for ="group-notify-members">Yes</label><input type="radio" name="group-notify-members" value="0" checked="checked" /><label for ="group-notify-members">No</label>
			</li>
			
			<li class="submit">
				<button type="submit" id="save" name="save">
					<i class="icon-pencil"></i>Update Group
				</button>
			</li>
			<li class="hidden">
				<?php wp_nonce_field( 'groups_edit_group_details' ); ?>
			</li>
		</ol>
		
	<?php // Manage Group Settings
	elseif ( bp_is_group_admin_screen( 'group-settings' ) ) : ?>
		<div class="instructions">	
			<h3 class="double-border bottom">Guild Privacy Settings</h3>
			<ul>
				<li>You can fine tune your guild's privacy settings below.</li>
				<li>Please also specify which members of this guild should be allowed to invite others to join.</li>
				<li>Please note that the settings you choose will affect the Tamriel Foundry community's ability to interact with your guild!</li>
			</ul>
		</div>
		<ol class="group-edit-list">
			<li class="checkbox">
				<input type="radio" name="group-status" value="public"<?php bp_group_show_status_setting( 'public' ); ?> /><label><i class="icon-unlock icon-fixed-width"></i><strong>This is a public guild.</strong></label>
				<ul class="radio-options-list">
					<li>Any Tamriel Foundry member can join this guild.</li>
					<li>This guild will be listed in the guilds directory and will appear in search results.</li>
					<li>Guild content and activity will be visible to all site members and guests.</li>
				</ul>
			</li>
			<li class="checkbox">				
				<input type="radio" name="group-status" value="private"<?php bp_group_show_status_setting( 'private' ); ?> /><label><i class="icon-lock icon-fixed-width"></i><strong>This is a private guild.</strong></label>
				<ul class="radio-options-list">
					<li>Only users who request membership and are accepted can join this guild.</li>
					<li>This guild will be listed in the guilds directory and will appear in search results.</li>
					<li>Guild content and activity will only be visible to guild members.</li>
				</ul>
			</li>
				
			<?php if ( current_user_can( 'manage_options') ) : // only admins can set hidden groups ?>
			<li class="checkbox">			
				<input type="radio" name="group-status" value="hidden"<?php bp_group_show_status_setting( 'hidden' ); ?> /><label><i class="icon-eye-close icon-fixed-width"></i><strong>This is a hidden guild.</strong></label>
				<ul class="radio-options-list">
					<li>Only users who are invited can join this guild</li>
					<li>This guild will not be listed in the guilds directory or search results.</li>
					<li>Guild content and activity will only be visible to guild members</li>
				</ul>
			</li>
			<?php endif; ?>
			
			<li class="checkbox">
				<p><i class="icon-legal icon-fixed-width"></i><strong>Set guild invitation permissions</strong></p>
				<ul class="radio-options-list">
					<input type="radio" name="group-invite-status" value="members" <?php bp_group_show_invite_status_setting( 'members' ); ?> />
					<label>All guild members can invite others.</label><br>
					<input type="radio" name="group-invite-status" value="mods" <?php bp_group_show_invite_status_setting( 'mods' ); ?> />
					<label>Guild leaders and officers can invite others.</label><br>	
					<input type="radio" name="group-invite-status" value="admins" <?php bp_group_show_invite_status_setting( 'admins' ); ?> />
					<label>Only guild leaders can invite others.</label>
				</ul>
			</li>
			
			<li class="submit">
				<button type="submit" id="save" name="save"><i class="icon-pencil"></i>Update Group</button>
			</li>
			
			<li class="hidden">
				<?php wp_nonce_field( 'groups_edit_group_settings' ); ?>
			</li>
		</ol>

	<?php // Group Avatar Settings
	elseif ( bp_is_group_admin_screen( 'group-avatar' ) ) : ?>
		<div class="instructions">
			<h3 class="double-border-bottom">Upload Guild Avatar</h3>
			<ul>
				<li>Upload an image to use as the guild avatar.</li>
				<li>The image will be shown on the main group page, and in search results.</li>
				<li>Avatars are automatically resized to 200 pixel jpegs after cropping.</li>
				<li>If you'd like to remove the existing avatar without uploading a new one, please use the delete avatar button.</li>
			</ul>
		</div>
		<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>
		<ol class="group-edit-list">
			<li class="file">
				<?php bp_new_group_avatar( $args = array(
							'type' => 'full',
							'width' => 200,
							'height' => 200,
							'no_grav' => true,
							) ); ?>
				<input type="file" name="file" id="file" />
				<?php if ( bp_get_group_has_avatar() ) : ?>
					<?php bp_button( array( 'id' => 'delete_group_avatar', 'component' => 'groups', 'wrapper_id' => 'delete-group-avatar-button', 'link_class' => 'edit button', 'link_href' => bp_get_group_avatar_delete_link(), 'link_title' => __( 'Delete Avatar', 'buddypress' ), 'link_text' => '<i class="icon-remove"></i>Delete Avatar' , 'wrapper' => false ) ); ?>
				<?php endif; ?>	
			</li>
			<li class="submit">
				<button type="submit" name="upload" id="upload"><i class="icon-upload"></i>Upload Image</button>
			</li>
			<li class="hidden">
				<?php wp_nonce_field( 'bp_avatar_upload' ); ?>
				<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
			</li>
		</ol>
		
		<?php elseif ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>
		<ol class="group-edit-list">
			<li>
				<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php _e( 'Avatar to crop', 'buddypress' ); ?>" />
				<div id="avatar-crop-pane">
					<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php _e( 'Avatar preview', 'buddypress' ); ?>" />
				</div>	
			</li>	
			<li class="submit">
				<button type="submit" name="avatar-crop-submit" id="avatar-crop-submit" class="button"><i class="icon-crop"></i>Crop Image</button>
			</li>		
			<li class="hidden">
				<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
				<input type="hidden" id="x" name="x" />
				<input type="hidden" id="y" name="y" />
				<input type="hidden" id="w" name="w" />
				<input type="hidden" id="h" name="h" />
				<?php wp_nonce_field( 'bp_avatar_cropstore' ); ?>
			</li>
		</ol>		
		<?php endif; ?>
	
	<?php // Manage Group Members
	elseif ( bp_is_group_admin_screen( 'manage-members' ) ) : ?>	
		<div class="instructions">
			<h3 class="double-border bottom">Manage Guild Members</h3>
			<ul>
				<li>From this panel you can manage the current members of this guild.</li>
				<li>Guilds are allowed three member ranks: member, officer, and leader.</li>
			</ul>
		</div>
		
		<?php if ( bp_has_members( '&include='. bp_group_admin_ids() ) ) : ?>
		<h2>Group Leaders</h2>
		<ul id="members-list" class="directory-list" role="main">
			<?php while ( bp_members() ) : bp_the_member();
			$user = new Apoc_User( bp_get_member_user_id() , 'directory' );	?>
			<li class="member directory-entry">
			
				<div class="directory-member">
					<?php echo $user->block; ?>
				</div>
				
				<div class="directory-content">
					<span class="activity"><?php bp_member_last_active(); ?></span>
					<div class="actions">
						<?php if ( count( bp_group_admin_ids( false, 'array' ) ) > 1 ) : ?>
						<a class="button confirm admin-demote-to-member" href="<?php bp_group_member_demote_link( bp_get_member_user_id() ); ?>"><i class="icon-level-down"></i>Demote to Member</a>
						<?php endif; ?>
					</div>
					<?php if ( $user->status['content'] ) : ?>
					<blockquote class="user-status">
						<p><?php echo $user->status['content']; ?></p>
					</blockquote>
					<?php endif; ?>
				</div>
			</li>
			<?php endwhile; ?>
		</ul>
		<?php endif; ?>
		
		<?php if ( bp_group_has_moderators() ) : if ( bp_has_members( '&include=' . bp_group_mod_ids() ) ) : ?>
		<h2>Group Officers</h2>	
		<ul id="members-list" class="directory-list" role="main">
			<?php while ( bp_members() ) : bp_the_member();
			$user = new Apoc_User( bp_get_member_user_id() , 'directory' );	?>
			<li class="member directory-entry">
			
				<div class="directory-member">
					<?php echo $user->block; ?>
				</div>
				
				<div class="directory-content">
					<span class="activity"><?php bp_member_last_active(); ?></span>
					<div class="actions">
						<a href="<?php bp_group_member_promote_admin_link( array( 'user_id' => bp_get_member_user_id() ) ); ?>" class="button confirm mod-promote-to-admin" title="Promote to Leader"><i class="icon-level-up"></i>Promote to Leader</a>
						<a class="button confirm mod-demote-to-member" href="<?php bp_group_member_demote_link( bp_get_member_user_id() ); ?>"><i class="icon-level-down"></i>Demote to Member</a>
					</div>
					<?php if ( $user->status['content'] ) : ?>
					<blockquote class="user-status">
						<p><?php echo $user->status['content']; ?></p>
					</blockquote>
					<?php endif; ?>
				</div>
			</li>
			<?php endwhile; ?>
		</ul>			
		<?php endif; endif; ?>
		
		<h2>Group Members</h2>
		<?php if ( bp_group_has_members( 'per_page=15&exclude_banned=false' ) ) : ?>
		<ul id="members-list" class="directory-list" role="main">
			<?php while ( bp_members() ) : bp_the_member();
			$user = new Apoc_User( bp_get_member_user_id() , 'directory' );	?>
			<li class="member directory-entry">
			
				<div class="directory-member">
					<?php echo $user->block; ?>
				</div>
				
				<div class="directory-content">
					<span class="activity"><?php bp_member_last_active(); ?></span>
					<div class="actions">
						<?php if ( bp_get_group_member_is_banned() ) : ?>
							<a href="<?php bp_group_member_unban_link(); ?>" class="button confirm member-unban" title="<?php _e( 'Unban this member', 'buddypress' ); ?>"><i class="icon-ok"></i>Unban User</a>
						<?php else : ?>
							<a href="<?php bp_group_member_ban_link(); ?>" class="button confirm member-ban" title="<?php _e( 'Kick and ban this member', 'buddypress' ); ?>"><i class="icon-remove-circle"></i>Kick and Ban</a>
							<a href="<?php bp_group_member_promote_mod_link(); ?>" class="button confirm member-promote-to-mod" title="Promote to Officer"><i class="icon-level-up"></i>Promote to Officer</a>
							<a href="<?php bp_group_member_promote_admin_link(); ?>" class="button confirm member-promote-to-admin" title="Promote to Leader"><i class="icon-level-up"></i>Promote to Leader</a>
						<?php endif; ?>
						<a href="<?php bp_group_member_remove_link(); ?>" class="button confirm" title="<?php _e( 'Remove this member', 'buddypress' ); ?>"><i class="icon-remove"></i>Remove from Guild</a>
					</div>
					<?php if ( $user->status['content'] ) : ?>
					<blockquote class="user-status">
						<p><?php echo $user->status['content']; ?></p>
					</blockquote>
					<?php endif; ?>
				</div>
			</li>
			<?php endwhile; ?>
		</ul>
	
		<?php else : ?>
			<p class="no-results"><?php _e( 'This group has no members.', 'buddypress' ); ?></p>
		<?php endif; ?>
		
		<?php if ( bp_group_member_needs_pagination() ) : ?>
		<nav class="pagination no-ajax">
			<div id="member-count" class="pagination-count">
				<?php bp_group_member_pagination_count(); ?>
			</div>
			<div id="member-admin-pagination" class="pagination-links">
				<?php bp_group_member_admin_pagination(); ?>
			</div>
		</nav>
		<?php endif; ?>
		
	<?php // Manage Membership Requests
	elseif ( bp_is_group_admin_screen( 'membership-requests' ) ) : ?>
		<?php if ( bp_group_has_membership_requests() ) : ?>
		<h2>Pending Membership Requests</h2>
		<ul id="members-list" class="directory-list">
			<?php while ( bp_group_membership_requests() ) : bp_group_the_membership_request();
			global $requests_template; 
			$user = new Apoc_User( $requests_template->request->user_id , 'directory' ); ?>
			<li class="member directory-entry">
				<div class="directory-member">
					<?php echo $user->block; ?>
				</div>
				
				<div class="directory-content">
					<span class="activity"><?php bp_group_request_time_since_requested(); ?></span>
					<div class="actions">
						<?php bp_button( array( 'id' => 'group_membership_accept', 'component' => 'groups', 'wrapper_class' => 'accept', 'link_href' => bp_get_group_request_accept_link(), 'link_title' => __( 'Accept', 'buddypress' ), 'link_class' => 'button' , 'link_text' => '<i class="icon-ok"></i>Accept' , 'wrapper' => false ) ); ?>
						<?php bp_button( array( 'id' => 'group_membership_reject', 'component' => 'groups', 'wrapper_class' => 'reject', 'link_href' => bp_get_group_request_reject_link(), 'link_title' => __( 'Reject', 'buddypress' ), 'link_class' => 'button' , 'link_text' => '<i class="icon-remove"></i>Reject' , 'wrapper' => false ) ); ?>
					</div>
					<blockquote class="user-status">
						<?php bp_group_request_comment(); ?>
					</blockquote>
				</div>
			</li>		
			<?php endwhile ?>
		</ul>
		
		<?php else: ?>
		<p class="no-results"><?php _e( 'There are no pending membership requests.', 'buddypress' ); ?></p>
		<?php endif; ?>
		
	<?php // Delete Group Option
	elseif ( bp_is_group_admin_screen( 'delete-group' ) ) : ?>
		<div class="instructions">
			<h3 class="double-border bottom">Delete Group</h3>
			<ul>
				<li>If you so choose, you may delete this group/guild and remove it from the Tamriel Foundry directory.</li>
				<li>WARNING: Deleting this group will completely remove ALL content associated with it. There is no way back, please be careful with this option.</li>
			</ul>
		</div>
		
		<ol class="group-edit-list">
			<li class="checkbox">
				<input type="checkbox" name="delete-group-understand" id="delete-group-understand" value="1" onclick="if(this.checked) { document.getElementById('delete-group-button').disabled = ''; } else { document.getElementById('delete-group-button').disabled = 'disabled'; }" /><label>I understand the consequences of deleting this guild.</label>
			</li>
			
			<li class="submit">
				<button type="submit" disabled="disabled" id="delete-group-button" name="delete-group-button"><i class="icon-remove"></i>Delete This Group</button>
			</li>
			
			<li class="hidden">
				<?php wp_nonce_field( 'groups_delete_group' ); ?>			
			</li>
		</ol>
	<?php endif; ?>
		
	<?php // Allow plugins to add custom group edit screens
	do_action( 'groups_custom_edit_steps' ); ?>

	<?php // Important hidden group ID field ?>
	<input type="hidden" name="group-id" id="group-id" value="<?php bp_group_id(); ?>" />

</form><!-- #group-settings-form -->