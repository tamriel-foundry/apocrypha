<?php 
/**
 * Apocrypha Theme Guild Submission Form
 * Andrew Clayton
 * Template Name: Submit Guild
 * Version 1.0.0
 * 9-30-2013
 */
 
// Set guild submission status
$enabled 	= true;
$minposts 	= 25;
$regtime	= '2 weeks';

// Get the current user
$user_id = get_current_user_id();
if ( $user_id > 0 ) {
	$user = new Apoc_User( $user_id , 'reply' );
	$regdate = apocrypha()->user->data->user_registered;
	$regdate = strtotime( $regdate );
	}

// Can the user submit a guild
$canreg = ( $regdate <= strtotime( '-'.$regtime ) && $user->posts['total'] > $minposts ) ? true : false;

// Process the form if it was submitted
if( 0 < $user_id && isset( $_POST['submitted'] ) ) {

	// Verify the nonce
	if ( !wp_verify_nonce( $_POST['_wpnonce'] , 'submit_guild_form' ) )
		die( 'Security Failure' );
	
	// Validate guild name and make sure it name isn't already in use
	$slug = sanitize_title( esc_attr( $_POST['group-name'] ) );
	if( trim( $_POST['group-name'] === '' )) {
		$name_error 	= 'You must enter a guild name.';
		$has_error 		= true;
	}
	elseif ( BP_Groups_Group::check_slug( $slug ) ) {
		$name_error		= 'This guild name is already in use!';
		$has_error 		= true;
	}
	$group_name = trim( $_POST['group-name']);
	
	// Validate recruitment status
	if ( $_POST['group-recruitment'] === NULL )	{
		$recruit_error = 'Please select your current recruitment status.';
		$has_error = true;
	}
	$group_recruit = ucfirst( $_POST['group-recruitment']);

	// Validate platform choice
	if ( $_POST['group-platform'] === '' )	{
		$platform_error = 'Please select your platform choice.';
		$has_error = true;
	}
	$group_platform = $_POST['group-platform'];

	// Validate faction choice
	if ( $_POST['group-faction'] === '' )	{
		$faction_error = 'Please select a faction affiliation.';
		$has_error = true;
	} 
	$group_faction = ucfirst( $_POST['group-faction']);
	
	// Validate group region
	if ( $_POST['group-region'] === '' )	{
		$region_error = 'Please specify your primary geographic region.';
		$has_error = true;
	}	
	$group_region = $_POST['group-region'];
	
	// Validate group interests
	$group_gameplay = $_POST['group-gameplay'];

	// Validate website
	$group_website = esc_url( $_POST['group-website'] );
	
	// Validate interests
	if ( empty( $_POST['group-interests'] ) ) {
		$interest_error = 'Please specify your guild interests.';
		$has_error = true;
		$group_interests = '';
	} else {
		$group_interests = implode(', ', $_POST['group-interests']);
	}
	
	// Validate description
	if ( $_POST['group-description'] === '' )	{
		$description_error = 'Please submit a description of this guild.';
		$has_error = true;
	} 
	$group_description = stripslashes( trim( $_POST['group-description'] ) );	
	
	// Make sure we haven't thrown an error
	if ( !$has_error ) {
	
		// Get some user info
		$user 		= apocrypha()->user->data;
		$login 		= $user->user_nicename;
		$user_email	= $user->user_email;
		$username	= $user->display_name;
		
		// Set the email headers
		$emailto 	= 'admin@tamrielfoundry.com';
		$subject 	= "Guild Creation Request From $username";
		$headers[] 	= "From: $username <$user_email>\r\n";
		$headers[] 	= "Content-Type: text/html; charset=UTF-8";
		
		// Construct the message
		$body 		= '<p><b>From:</b> <a href="http://tamrielfoundry.com/members/'.$login.'" title="view profile" target="_blank">'.$username.'</a></p>';
		$body 		.= "<p><b>Email:</b> $user_email</p>";
		$body 		.= "<p><b>Guild Name:</b> $group_name</p>";
		$body 		.= "<p><b>Guild Description:</b></p> $group_description";
		$body 		.= "<p><b>Guild Website:</b> $group_website</p>";
		$body 		.= "<p><b>Guild Platform:</b> $group_platform</p>";
		$body 		.= "<p><b>Guild Faction:</b> $group_faction</p>";
		$body 		.= "<p><b>Region:</b> $group_region</p>";
		$body 		.= "<p><b>Gameplay Style:</b> $group_gameplay</p>";
		$body 		.= "<p><b>Guild Interests:</b> $group_interests</p>";
		$body		.= "<p><b>Recruitment Status:</b> $group_recruit</p>";
		
		// Send it
		wp_mail( $emailto , $subject , $body , $headers );
		$email_sent = true;
	}
} ?>

<?php get_header(); ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="<?php apoc_entry_class(); ?>">
		
			<header class="entry-header <?php post_header_class(); ?>">
				<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
				<p class="entry-byline"><?php entry_header_description(); ?></p>
			</header>
			
			<div class="entry-content">
				<?php the_content(); ?>
			</div>

		</div><!-- #post-<?php the_ID(); ?> -->
		<?php endwhile; endif; ?>
		
		
		<?php // If the submission was successful, say thank you instead
		if ( $email_sent ) : ?>
		<div class="updated">Thank you for submitting your guild, <?php echo $user->display_name; ?>. Your request was successfully sent. We will review it and respond as soon as possible. If your request is approved, you will be added to your group, and promoted to guild leader. We will contact you via email regarding your guild request once it has been processed. Thank you for contributing to Tamriel Foundry!</div>
			
		<?php // Make sure it's a registered user
		elseif ( 0 == $user_id ) : ?>
		<div class="warning">You must be a registered Tamriel Foundry member to submit a guild!</div>
				
		<?php // Make sure guild submission is currently allowed
		elseif ( !$enabled ) : ?>
		<div class="warning">Guild creation is temporarily disabled while we clear backlogged applications. Sorry for the inconvenience, please check back in a few days.</div>
				
		<?php // Make sure the user has enough posts to submit
		elseif ( !$canreg ) : ?>
		<div class="warning">Guild submission is only available to Tamriel Foundry members who have been a site member for longer than <?php echo $regtime; ?> and contributed more than <?php echo $minposts; ?> posts to the community. This is to prevent the submission of guilds which are only seeking to use Tamriel Foundry as a recruitment or advertisment tool with no intention to participate within the community. Acceptable posts contribute to the discussion within the Tamriel Foundry community while conforming to our site's Code of Conduct, as such, the spam creation of topics or replies will be punished accordingly.</div>
			
		<?php // Otherwise, give them the form! 
		else : ?>
		<form action="<?php the_permalink(); ?>" id="guild-submit-form" method="post">		
			<h2>Guild Submission Form</h2>
			
			<?php // If something went wrong, give an error
			if ( $has_error ) : ?>
			<div class="error">There was an error submitting the guild request, please double check the required fields.</div>
			<?php endif; ?>
			
			<ol id="group-create-list">
				<li class="text">
					<label for="group-name"><i class="icon-bookmark"></i> Guild Name &#9734; :</label>
					<input type="text" name="group-name" id="group-name" aria-required="true" value="<?php if(isset($_POST['group-name'])) echo trim($_POST['group-name']);?>" maxlength="100" size="100"/>
					<?php if( $name_error ) echo '<div class="error">' . $name_error . '</div>'; ?>
				</li>
				
				<li class="radio">
					<p><i class="icon-legal icon-fixed-width"></i><strong>Recruitment Status &#9734; : </strong></p>
					<ul class="radio-options-list">
						<li>
							<input type="radio" name="group-recruitment" value="public" <?php checked( $_POST['group-recruitment'] , 'public' ); ?>>
							<label for="group-recruitment">Public Guild - Anyone may join, application is not required.</label></li>
						<li>
							<input type="radio" name="group-recruitment" value="private" <?php checked( $_POST['group-recruitment'] , 'private' ); ?>>
							<label for="group-recruitment">Private Guild - Users must request membership to join.</label>
						</li>
					</ul>
					<?php if( $recruit_error ) echo '<div class="error">' . $recruit_error . '</div>'; ?>
				</li>
				
				<li class="select">
					<label for="group-platform"><i class="icon-desktop icon-fixed-width"></i><strong>Platform &#9734; :</strong></label>
					<select name="group-platform" id="group-platform">
						<option value=""></option>
						<option value="pc" <?php selected( $_POST['group-platform'] , 'pc' ); ?>>PC/Mac</option>
						<option value="xbox" <?php selected( $_POST['group-platform'] , 'xbox' ); ?>>Xbox One</option>
						<option value="playstation" <?php selected( $_POST['group-platform'] , 'playstation' ); ?>>Playstation 4</option>
					</select>
					<?php if( $platform_error ) echo '<div class="error">' . $platform_error . '</div>'; ?>
				</li>
				
				<li class="select">
					<label for="group-faction"><i class="icon-flag icon-fixed-width"></i><strong>Faction Allegiance &#9734; :</strong></label>
					<select name="group-faction" id="group-faction">
						<option value=""></option>
						<option value="aldmeri" <?php selected( $_POST['group-faction'] , 'aldmeri' ); ?>>Aldmeri Dominion</option>
						<option value="daggerfall" <?php selected( $_POST['group-faction'] , 'daggerfall' ); ?>>Daggerfall Covenant</option>
						<option value="ebonheart" <?php selected( $_POST['group-faction'] , 'ebonheart' ); ?>>Ebonheart Pact</option>
					</select>
					<?php if( $faction_error ) echo '<div class="error">' . $faction_error . '</div>'; ?>
				</li>
				
				<li class="select">
					<label for="group-region"><i class="icon-globe icon-fixed-width"></i><strong>Region &#9734; :</strong></label>
					<select name="group-region" id="group-region">
						<option value=""></option>
						<option value="NA" <?php selected( $_POST['group-region'] , 'NA' ); ?>>North America</option>
						<option value="EU" <?php selected( $_POST['group-region'] , 'EU' ); ?>>Europe</option>
						<option value="OC" <?php selected( $_POST['group-region'] , 'OC' ); ?>>Oceania</option>
					</select>
					<?php if( $region_error ) echo '<div class="error">' . $region_error . '</div>'; ?>
				</li>
				
				<li class="select">
					<label for="group-style"><i class="icon-shield icon-fixed-width"></i><strong>Guild Playstyle:</strong></label>
					<select name="group-gameplay" id="group-gameplay">
						<option value=""></option>		
						<option value="casual" <?php selected( $_POST['group-gameplay'] , 'casual' ); ?>>Casual</option>
						<option value="moderate" <?php selected( $_POST['group-gameplay'] , 'moderate' ); ?>>Moderate</option>
						<option value="hardcore" <?php selected( $_POST['group-gameplay'] , 'hardcore' ); ?>>Hardcore</option>
					</select>
				</li>
					
				<li class="checkbox">
					<?php if ( '' == $_POST['group-interests'] ) $_POST['group-interests'] = array(); ?>
					<p><i class="icon-gear icon-fixed-width"></i><strong>Group Interests &#9734; :</strong></p>
					<ul id="group-interests-list" class="radio-options-list">
						<li>
							<input type="checkbox" name="group-interests[]" value="pve" <?php checked( in_array( 'pve' , $_POST['group-interests'] ) , 1 ); ?>>
							<label for="group-interests[]">Player vs. Environment (PvE)</label>
						</li>
						<li>
							<input type="checkbox" name="group-interests[]" value="pvp" <?php checked( in_array( 'pvp' , $_POST['group-interests'] ) , 1 ); ?>>
							<label for="group-interests[]">Player vs. Player (PvP)</label>
						</li>
						<li>
							<input type="checkbox" name="group-interests[]" value="roleplay" <?php checked( in_array( 'roleplay' , $_POST['group-interests'] ) , 1 ); ?>>
							<label for="group-interests[]">Roleplaying (RP)</label>
						</li>
						<li>
							<input type="checkbox" name="group-interests[]" value="crafting" <?php checked( in_array( 'crafting' , $_POST['group-interests'] ) , 1 ); ?>>
							<label for="group-interests[]">Crafting</label><br>
						</li>
					</ul>
					<?php if( $interest_error ) echo '<div class="error">' . $interest_error . '</div>'; ?>
				</li>
				
				
				<li class="textarea">
					<p><i class="icon-edit icon-fixed-width"></i><strong>Guild Description &#9734; :</strong></p>
					<?php // Load the TinyMCE Editor
					$description = stripslashes($_POST['group-description']);
					wp_editor( $description, 'group-description', array(
						'media_buttons' => false,
						'wpautop'		=> false,
						'editor_class'  => 'guild-request-description',
						'quicktags'		=> false,
						'teeny'			=> true,
						) ); ?>
					<?php if( $description_error ) echo '<div class="error">' . $description_error . '</div>'; ?>
				</li>
				
				<li class="text">
					<label class="settings-field-label"><i class="icon-home icon-fixed-width"></i><strong>Guild Website: </strong></label>
					<input type="url" name="group-website" id="group-website" value="<?php if(isset($_POST['group-website'])) echo trim($_POST['group-website']); ?>" size="50"/>
				</li>
				
				<?php if( '' == $_POST['group-website'] ) : ?>
				<div class="instructions">	
					<h3 class="double-border bottom">Guild Website</h3>
					<p>Don't have a guild website yet? Tamriel Foundry would like to encourage you to check out <a href="http://www.guildlaunch.com/" title="Get hooked up with Guild Launch!" target="_blank">Guild Launch</a> for a great range of fully-featured and affordable guild hosting solutions.</p>
					<p>Having a website is critically important for the long-run success of your guild in The Elder Scrolls Online, and Guild Launch has loads of pre-configured ESO-themed guild solutions to choose from.</p>
				</div>
				<?php endif; ?>
				
				<li class="submit">
					<button type="submit" id="submit-guild-button" name="submit-guild-button"><i class="icon-group"></i>Submit Guild</button>
				</li>
				
				<li class="hidden">
					<input type="hidden" name="submitted" id="submitted" value="true" />
					<?php wp_nonce_field( 'submit_guild_form' ); ?>
				</li>
			</ol>		
		<?php endif; ?>
		</form>
		
	</div><!-- #content -->
	
	<?php apoc_primary_sidebar(); // Load the community sidebar ?>
<?php get_footer(); // Load the footer ?>