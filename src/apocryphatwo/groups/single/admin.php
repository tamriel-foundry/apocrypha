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
			
			<li class="text">
				<label for="group-website"><i class="icon-home icon-fixed-width"></i>Group Website: </label>
				<input type="url" name="group-website" id="group-website" value="<?php echo $guild->website; ?>" size="50" />
			</li>

			<?php if ( current_user_can( 'edit_posts' ) ) : ?>
			<li class="checkbox">
				<label for="group-type"><i class="icon-group icon-fixed-width"></i>Group Type &#9734; :</label>
				<input type="radio" name="group-type" value="group" <?php checked( $guild->guild, 0 , true ) ?>><label for="group-type">Group</label>
				<input type="radio" name="group-type" value="guild" <?php checked( $guild->guild, 1 , true ) ?>><label for="group-type">Guild</label>
			</li>
			<?php endif; ?>
			
			<li class="select">
				<label for="group-platform"><i class="icon-desktop icon-fixed-width"></i>Platform:</label>
				<select name="group-platform" id="group-platform">
					<option value="blank" <?php selected( $guild->platform, 'blank' ); ?>></option>
					<option value="pcmac" <?php selected( $guild->platform, 'pcmac' ); ?>>PC / Mac</option>
					<option value="xbox" <?php selected( $guild->platform, 'xbox' ); ?>>Xbox One</option>
					<option value="playstation" <?php selected( $guild->platform, 'playstation' ); ?>>PlayStation 4</option>
				</select>
			</li>

			<li class="select">
				<label for="group-faction"><i class="icon-flag icon-fixed-width"></i>Faction Allegiance (&#9734;) :</label>
				<select name="group-faction" id="group-faction">
					<option value="neutral" <?php selected( $guild->alliance, 'neutral' ); ?>>Undeclared</option>
					<option value="aldmeri" <?php selected( $guild->alliance, 'aldmeri' ); ?>>Aldmeri Dominion</option>
					<option value="daggerfall" <?php selected( $guild->alliance, 'daggerfall' ); ?>>Daggerfall Covenant</option>
					<option value="ebonheart" <?php selected( $guild->alliance, 'ebonheart' ); ?>>Ebonheart Pact</option>
				</select>
			</li>
			
			<li class="select">
				<label for="group-region"><i class="icon-globe icon-fixed-width"></i>Region (&#9734;) :</label>
				<select name="group-region" id="group-region">
					<option value="blank" <?php selected( $guild->region, 'blank' ); ?>></option>
					<option value="NA" <?php selected( $guild->region, 'NA' ); ?>>North America</option>
					<option value="EU" <?php selected( $guild->region, 'EU' ); ?>>Europe</option>
					<option value="OC" <?php selected( $guild->region, 'OC' ); ?>>Oceania</option>
				</select>
			</li>
			
			<li class="select">
				<label for="group-style"><i class="icon-shield icon-fixed-width"></i>Guild Playstyle:</label>
				<select name="group-style" id="group-style">
					<option value="blank" <?php selected( $guild->style, 'blank' ); ?>></option>
					<option value="casual" <?php selected( $guild->style, 'casual' ); ?>>Casual</option>
					<option value="moderate" <?php selected( $guild->style, 'moderate' ); ?>>Moderate</option>
					<option value="hardcore" <?php selected( $guild->style, 'hardcore' ); ?>>Hardcore</option>
				</select>
			</li>
			
			<li class="radio">
				<label for="group-interests"><i class="icon-gear icon-fixed-width"></i>Group Interests (&#9734;) :</label><br>
				<ul id="group-interests-list">
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
		Group management screen

	<?php // Group Avatar Settings
	elseif ( bp_is_group_admin_screen( 'group-avatar' ) ) : ?>
		Group avatar screen
	
	<?php // Manage Group Members
	elseif ( bp_is_group_admin_screen( 'manage-members' ) ) : ?>	
		Member management screen
		
	<?php // Manage Membership Requests
	elseif ( bp_is_group_admin_screen( 'membership-requests' ) ) : ?>
		Membership requests screen
		
	<?php // Delete Group Option
	elseif ( bp_is_group_admin_screen( 'delete-group' ) ) : ?>
		Group deletion screen
	<?php endif; ?>
		
	<?php // Allow plugins to add custom group edit screens
	do_action( 'groups_custom_edit_steps' ); ?>

	<?php // Important hidden group ID field ?>
	<input type="hidden" name="group-id" id="group-id" value="<?php bp_group_id(); ?>" />

</form><!-- #group-settings-form -->