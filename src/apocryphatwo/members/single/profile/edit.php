<?php 
/**
 * Apocrypha Theme Profile Characters Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-6-2013
 */
 
// Setup the edit profile form
global $bp;
$user_id	= $bp->displayed_user->id;
$action_url = $bp->displayed_user->domain . $bp->profile->slug . '/edit/';

global $user;
$user 		= new Edit_Profile( $user_id , 'profile' );
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>
		
		<div id="profile-body">
			<nav class="directory-subheader no-ajax" id="subnav" >
				<ul id="profile-tabs" class="tabs" role="navigation">
					<?php bp_get_options_nav(); ?>
				</ul>
			</nav><!-- #subnav -->
			<?php do_action( 'template_notices' ); ?>
			
			<div id="user-profile" role="main">
				<form method="post" id="edit-profile-form" action="<?php echo $action_url; ?>">
				
					<?php // Character Information ?>
					<div class="instructions">	
						<h3 class="double-border bottom">Character Information</h3>
						<ul>
							<li>Share some information about your main character in The Elder Scrolls Online.</li>
							<li>These fields are displayed as part of your publicly visible character sheet on your user profile.</li>
						</ul>
					</div>
					<ol id="character-info">
						<li class="text">
							<i class="icon-book icon-fixed-width"></i><label for="first-name">Character Name:</label>
							<input name="first-name" type="text" id="first-name" value="<?php echo $user->first_name; ?>" size="30" />
							<input name="last-name" type="text" id="last-name" value="<?php echo $user->last_name; ?>" size="30" />
						</li>
					
						<li class="select">
							<i class="icon-flag icon-fixed-width"></i><label for="faction">Choose Your Alliance:</label>
							<select name="faction" id="faction" onchange="updateRaceDropdown('faction')">
								<option value="undecided" <?php selected( $user->faction 	, 'undecided'	, true ); ?>></option>
								<option value="aldmeri" <?php selected( $user->faction	 	, 'aldmeri' 	, true ); ?>>Aldmeri Dominion</option>
								<option value="daggerfall" <?php selected( $user->faction	, 'daggerfall' 	, true ); ?>>Daggerfall Covenant</option>
								<option value="ebonheart" <?php selected( $user->faction 	, 'ebonheart' 	, true ); ?>>Ebonheart Pact</option>
							</select>
						</li>
						
						<li class="select">
							<i class="icon-user icon-fixed-width"></i><label for="race">Choose Your Race:</label>
							<select name="race" id="race" onchange="updateRaceDropdown('race')">
								<option value="norace" <?php selected( $user->race	, 'norace' 	, true ); ?>></option>
								<option value="altmer" <?php selected( $user->race	, 'altmer' 	, true ); ?> <?php disabled( $faction , 'daggerfall' , true ); ?> <?php disabled( $faction , 'ebonheart' , true ); ?>>Altmer</option>
								<option value="bosmer" <?php selected( $user->race	, 'bosmer' 	, true ); ?> <?php disabled( $faction , 'daggerfall' , true ); ?> <?php disabled( $faction , 'ebonheart' , true ); ?>>Bosmer</option>
								<option value="khajiit" <?php selected( $user->race	, 'khajiit' , true ); ?> <?php disabled( $faction , 'daggerfall' , true ); ?> <?php disabled( $faction , 'ebonheart' , true ); ?>>Khajiit</option>
								<option value="breton" <?php selected( $user->race	, 'breton' 	, true ); ?> <?php disabled( $faction , 'aldmeri' , true ); ?> <?php disabled( $faction , 'ebonheart' , true ); ?>>Breton</option>
								<option value="orc" <?php selected( $user->race	, 'orc'		, true ); ?> <?php disabled( $faction , 'aldmeri' , true ); ?> <?php disabled( $faction , 'ebonheart' , true ); ?>>Orc</option>
								<option value="redguard" <?php selected( $user->race, 'redguard', true ); ?> <?php disabled( $faction , 'aldmeri' , true ); ?> <?php disabled( $faction , 'ebonheart' , true ); ?>>Redguard</option>
								<option value="argonian" <?php selected( $user->race, 'argonian' , true ); ?> <?php disabled( $faction , 'aldmeri' , true ); ?> <?php disabled( $faction , 'daggerfall' , true ); ?>>Argonian</option>
								<option value="dunmer" <?php selected( $user->race	, 'dunmer' 	, true ); ?> <?php disabled( $faction , 'aldmeri' , true ); ?> <?php disabled( $faction , 'daggerfall' , true ); ?>>Dunmer</option>
								<option value="nord" <?php selected( $user->race	, 'nord' 	, true ); ?> <?php disabled( $faction , 'aldmeri' , true ); ?> <?php disabled( $faction , 'daggerfall' , true ); ?>>Nord</option>
							</select>
						</li>
						
						<li class="select">
							<i class="icon-gear icon-fixed-width"></i><label for="playerclass">Choose Your Class:</label>
							<select name="playerclass" id="playerclass">
								<option value="undecided" <?php selected( $user->class 	, 'undecided' 	, true ); ?>></option>
								<option value="dragonknight" <?php selected( $user->class 	, 'dragonknight' , true ); ?>>Dragonknight</option>
								<option value="nightblade" <?php selected( $user->class 	, 'nightblade' 	, true ); ?>>Nightblade</option>
								<option value="sorcerer" <?php selected( $user->class 		, 'sorcerer' 	, true ); ?>>Sorcerer</option>
								<option value="templar" <?php selected( $user->class 		, 'templar' 	, true ); ?>>Templar</option>
							</select>
						</li>
						
						<li class="select">
							<i class="icon-shield icon-fixed-width"></i><label for="playerclass">Preferred Role:</label>
							<select name="prefrole" id="prefrole">
								<option value="any" <?php selected( $user->prefrole 	, 'any' 	, true ); ?>></option>
								<option value="tank" <?php selected( $user->prefrole 	, 'tank' 	, true ); ?>>Tank</option>
								<option value="heal" <?php selected( $user->prefrole	, 'heal' 	, true ); ?>>Healer</option>
								<option value="dps" <?php selected( $user->prefrole 	, 'dps' 	, true ); ?>>Damage</option>
								<option value="support" <?php selected( $user->prefrole , 'support' , true ); ?>>Support</option>
							</select>
						</li>
						
						<li class="select">
							<i class="icon-group icon-fixed-width"></i><label for="guild">Primary Guild:</label>
							<select name="guild" id="guild">
								<option value="none" <?php selected( $user->guild 	, '' 	, true ); ?>></option>
								<?php if ( bp_has_groups( array(	'type' => 'alphabetical', 'user_id'	=> $user_id ) ) ) : while ( bp_groups() ) : bp_the_group(); ?>
								<option value="<?php bp_group_slug(); ?>" <?php selected( $user->guild , bp_get_group_slug() , true ); ?>><?php bp_group_name();?></option>
								<?php endwhile; endif; ?>
							</select>
						</li>
					</ol>

					<?php // Biography and Signature ?>
					<div class="instructions">	
						<h3 class="double-border bottom">Biography and Signature</h3>
						<ul>
							<li>Your biography is a detailed description of yourself as a gamer and individual.</li>
							<li>It can describe your character's backstory or personality, or your personal tastes in gaming.</li>
							<li>Your signature is a shorter tagline which is displayed beneath forum posts and article comments.</li>
							<li>Signature text and/or images must be less than 150 pixels in height, otherwise their contents will be truncated.</li>
						</ul>
					</div>
					<ol id="biography-signature">					
						<li class="textarea">
							<i class="icon-pencil icon-fixed-width"></i><label for="description">Personal or Character Biography:</label>
							<?php wp_editor( htmlspecialchars_decode( $user->bio , ENT_QUOTES ) , 'description' , array(
								'media_buttons' => false,
								'wpautop'		=> false,
								'editor_class'  => 'description',
								'quicktags'		=> true,
								'teeny'			=> false,
							) ); ?>
						</li>
						
						<li class="textarea">
							<i class="icon-quote-left icon-fixed-width"></i><label for="signature">Forum Signature:</label>
							<?php wp_editor( htmlspecialchars_decode( $user->sig , ENT_QUOTES ), 'signature', array(
								'media_buttons' => false,
								'wpautop'		=> false,
								'editor_class'  => 'signature',
								'quicktags'		=> true,
								'teeny'			=> false,
							) ); ?>
						</li>
					</ol>
					
					<?php // Contact Methods ?>
					<div class="instructions">	
						<h3 class="double-border bottom">Contact Methods</h3>
						<ul>
							<li>Specify some ways that you can be reached throughout the social gaming community.</li>
							<li>These contact methods will be listed publicly on your user profile.</li>
						</ul>
					</div>
					<ol id="contact-methods">						
						<li class="text">
							<label for="url">Your Website:</label>
							<input class="text-input" name="url" type="url" id="url" value="<?php echo $user->contacts['url']; ?>" size="60" />
						</li>
						
						<li class="text">
							<label for="facebook">Facebook:</label>
							<span class="contact-url-prefix">facebook.com/</span>
							<input type="text" name="facebook" id="facebook" value="<?php echo $user->contacts['facebook']; ?>" class="regular-text user-contact-method" size="43">
						</li>
						
						<li class="text">
							<label for="twitter">Twitter:</label>
							<span class="contact-url-prefix">twitter.com/</span>
							<input type="text" name="twitter" id="twitter" value="<?php echo $user->contacts['twitter']; ?>" class="regular-text user-contact-method" size="46">
						</li>
						
						<li class="text">
							<label for="youtube">YouTube:</label>
							<span class="contact-url-prefix">youtube.com/</span>
							<input type="text" name="youtube" id="youtube" value="<?php echo $user->contacts['youtube']; ?>" class="regular-text user-contact-method" size="44">
						</li>
						
						<li class="text">
							<label for="steam">Steam:</label>
							<span class="contact-url-prefix">steamcommunity.com/id/</span>
							<input type="text" name="steam" id="steam" value="<?php echo $user->contacts['steam']; ?>" class="regular-text user-contact-method" size="30">
						</li>
						
						<li class="text">
							<label for="twitch">TwitchTV:</label>
							<span class="contact-url-prefix">twitch.tv/</span>
							<input type="text" name="twitch" id="twitch" value="<?php echo $user->contacts['twitch']; ?>" class="regular-text user-contact-method" size="49">
						</li>
					
						<li class="text">
							<label for="bethforums">Bethesda Forums:</label>
							<span class="contact-url-prefix">forums.bethsoft.com/user/</span>
							<input type="text" name="bethforums" id="bethforums" value="<?php echo $user->contacts['bethforums']; ?>" class="regular-text user-contact-method" size="30">
						</li>
					</ol>
					
					<?php // Allow plugins to link in
					do_action( 'show_user_profile' , $userid );
					do_action( 'edit_user_profile' , $userid ); ?>
					
					<?php // Submit the edit profile form ?>
					<ul class="edit-submit">
						<li class="submit">
							<input name="action" type="hidden" id="action" value="update-user" />
							<?php wp_nonce_field( 'update-user' , 'edit_user_nonce' ) ?>
							<button type="submit" name="updateuser" id="updateuser" class="submit button"><i class="icon-pencil"></i>Update Profile</button>	
						</li>
					</ul>		
				</form>				
			</div>	
		</div>
		
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>