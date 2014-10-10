<?php 
/**
 * Apocrypha Theme Entropy Rising Application Form
 * Andrew Clayton
 * Version 1.3
 * 10-10-2014
 */

 
// Gather information about the current user
$user 				= apocrypha()->user;
$userid 			= $user->ID;
$username 			= $user->display_name;
$username_link 		= '<a href="http://tamrielfoundry.com/members/'.$username.'" target="_blank">'.$username.'</a>';
$user_email 		= $user->user_email;

// Populate default form values and sanitize form submissions
$account_name 		= isset( $_POST['account-name'] ) 		? trim($_POST['account-name']) : '';
$character_class 	= isset( $_POST['character-class'] ) 	? $_POST['character-class'] : '';
$character_level 	= isset( $_POST['character-level'] ) 	? trim($_POST['character-level']) : '';
$your_age 			= isset( $_POST['your-age'] ) 			? trim($_POST['your-age']) : '';
$preferred_role 	= isset( $_POST['preferred-role'] ) 	? $_POST['preferred-role'] : '';
$playstyle 			= isset( $_POST['playstyle'] )			? $_POST['playstyle'] : '';
$experience 		= isset( $_POST['experience'] ) 		? wpautop( stripslashes( trim( $_POST['experience'] ) ) ) : '';
$youoffer 			= isset( $_POST['youoffer'] ) 			? wpautop( stripslashes( trim( $_POST['youoffer'] ) ) ) : '';
$mainguild 			= isset( $_POST['mainguild'] ) 			? $_POST['mainguild'] : '';
$otherguildurl 		= isset( $_POST['otherguildurl'] )		? $_POST['otherguildurl'] : '';
$voicechat 			= isset( $_POST['voicechat'] ) 			? $_POST['voicechat'] : '';


// Was the form submitted?
if( isset( $_POST['submitted']) ) {

	// Check nonce
	if ( !wp_verify_nonce( $_POST['_wpnonce'] , 'er_guild_application' ) )
		die( 'Security Failure' );

	// Check honeypot
	if( trim( $_POST['checking'] ) !== '' ) 
		die( 'No Bots Allowed' );

	// Account Name
	if( '' === $account_name ) {
		$nameError = 'Please enter your character name.';
		$hasError = true;
	}
	
	// Character Class
	if( '' === $character_class ) {
		$classError = 'Please specify your primary character class.';
		$hasError = true;
	}

	// Current Level
	if( '' === $character_level ) {
		$levelError = 'Please enter the current level of your main character.';
		$hasError = true;
	}

	// Your Age
	if( '' === $your_age ) {
		$ageError = 'Please share your age (in years).';
		$hasError = true;
	}

	// Preferred Role
	if ( '' === $preferred_role ) {
		$roleError = 'Please express your preferred character role in group PvE situations.';
		$hasError = true;
	} 
	
	// Typical Playstyle
	if ( '' === $playstyle ) {
		$playstyleError = 'Please indicate your typical availability and playstyle.';
		$hasError = true;
	}

	// Gaming Experience
	if( '' === $experience ) {
		$experienceError = 'Please describe your MMO experience.';
		$hasError = true;
	}

	// What You Offer
	if( '' === $youoffer ) {
		$youofferError = 'Please explain the characteristics you have to offer Entropy Rising.';
		$hasError = true;
	}
	
	// Primary Guild
	if( '' === $mainguild ) {
		$mainguildError = 'Please indicate whether you are interested in joining Entropy Rising as your primary guild?';
		$hasError = true;
	}

	// Main Guild URL
	if ( 'no' === $mainguild && '' === $otherguildurl ) {
		$guildurlError = 'Please provide link your primary guild website.';
		$hasError = true;
	}
	
	// Voice Chat
	if( '' === $voicechat ) {
		$voicechatError = 'Please describe your ability to use voice communication.';
		$hasError = true;
	}
	
	// Confirm Charter
	if ( !isset($_POST['readrules'] ) ) {
		$readrulesError = 'Please confirm your understanding of our guild charter.';
		$hasError = true;
	}

	// Send email if no errors!
	if( !isset($hasError) ) {
		
		// Email Headers
		$emailto 	= "admin@tamrielfoundry.com";
		$subject 	= "Entropy Rising Guild Application From $username";
		$headers[] 	= "From: $username <$user_email>";
		$headers[] 	= "Content-type: text/html";

		// Basic Information
		$body = "<h3>Basic Information</h3>";
		$body .= "<ul>";
			$body .= "<li>Applicant: $username_link";
			$body .= "<li>Email: $user_email</li>";
			$body .= "<li>Age: $your_age</li>";
			$body .= "<li>Account Name: $account_name</li>";
			$body .= "<li>Character Class: $character_class</li>";
			$body .= "<li>Character Level: $character_level</li>";
			$body .= "<li>Preferred Role: $preferred_role</li>";
			$body .= "<li>Playstyle and Availability: $playstyle</li>";
			$body .= "<li>ER as Primary Guild: $mainguild";
			$body .= "<li>Other Guild Website: $otherguildurl</li>";
			$body .= "<li>Voicechat: $voicechat</li>";
		$body .= "</ul>";
		
		// MMO Experience
		$body .= "<h3>MMO Experience</h3>";
		$body .= "<div>$experience</div>";
		
		// Interest in ER
		$body .= "<h3>Interest in Entropy Rising</h3>";
		$body .= "<div>$youoffer</div>";
	
		// Send mail!
		wp_mail( $emailto , $subject , $body, $headers);
		$emailSent = true;
	}
} ?>

<?php if ( isset( $emailSent ) ) : ?>
<div class="updated">
	Congratulations <?php echo $username; ?>, your application was successfully submitted! We will attempt to review it within the next several days. You will be contacted via the email address registered on your Tamriel Foundry user account once we have processed your application. Thank you for taking the time to apply to Entropy Rising.
</div>
<?php elseif ( isset($hasError) ) : ?>
	<p class="error">Sorry <?php echo $username; ?>, there was an error with your application, please correct any errors before resubmitting this form.</p>
<?php else : ?>
	<div class="instructions">
	<?php if ( guild_recruitment_status() == 'closed' ) : ?>
		<h4 style="color:darkred"><u>Disclaimer:</u></h4>
		<p>Hello, <?php echo $username; ?>. Recruitment for Entropy Rising is currently closed. However, we are always interested in recruiting truly exceptional players to join our tight-knit team. If you feel that you possess the exact qualities which we are looking for in Entropy Rising members, please feel free to submit an application. We will save your application, and review it when and if we decide to add new members. If you apply while recruitment is closed, do not expect to hear back from us until recruitment is reopened, at which time your application will be revisited.</p>
	<?php else : ?>
		<h3>Hello, <?php echo $username; ?></h3>
		<p>Please carefully fill out the following form. We receive a large volume of guild applications to join Entropy Rising for a limited number of guild openings. In order to give yourself the best possible chance of being accepted it is important that you answer the following questions carefully and clearly so that we can evaluate how well you would fit in with our guild.</p>
	<?php endif; ?>
	</div>
<?php endif; ?>


<?php if ( !isset( $emailSent ) ) : ?>
<form action="<?php the_permalink(); ?>" id="guild-application-form" method="post">
	<ol id="guild-application-list">	
		<li class="text">
			<label for="account-name"><i class="icon-bookmark icon-fixed-width"></i>ESO Username:</label>
			<input type="text" name="account-name" value="<?php echo $account_name; ?>" size="30" placeholder="@username" />
			<?php if( isset( $nameError ) && $nameError ) echo '<div class="error">' . $nameError . '</div>'; ?> 
		</li>
	
		<li class="select">
			<label for="character-class"><i class="icon-gear icon-fixed-width"></i>Main Class:</label>
			<select name="character-class">
			<?php $classes = array ( '' , 'dragonknight', 'templar', 'sorcerer', 'nightblade' ); 
				foreach ( $classes as $key => $class ) :
				echo '<option value="' . $class . '" ' . selected( $character_class , $class , false ) . '>' . ucfirst($class) . '</option>';
				endforeach; ?>
			</select>
			<?php if( isset( $classError ) && $classError ) echo '<div class="error">' . $classError . '</div>'; ?> 
		</li>
		
		<li class="text">
			<label for="character-level"><i class="icon-bookmark icon-fixed-width"></i>Veteran Rank:</label>
			<select name="character-level">
				<option value=""></option>
				<option value="max" <?php selected( $character_level , 'max' ); ?>>VR14</option>
				<option value="near" <?php selected( $character_level , 'near' ); ?>>VR10-13</option>
				<option value="low" <?php selected( $character_level , 'low' ); ?>>&lt; VR10</option>
			</select>
			<?php if( isset( $levelError ) && $levelError ) echo '<div class="error">' . $levelError . '</div>'; ?> 
		</li>
		
		<li class="text">		
			<label for="preferred-role"><i class="icon-shield icon-fixed-width"></i>Preferred Role (group PvE):</label>
			<select name="preferred-role">
				<option value=""></option>
				<option value="dps" <?php selected( $preferred_role , 'dps' ); ?>>Damage</option>
				<option value="heal" <?php selected( $preferred_role , 'heal' ); ?>>Healing</option>
				<option value="tank" <?php selected( $preferred_role , 'tank' ); ?>>Tanking</option>			
			</select>
			<?php if( isset( $roleError ) && $roleError ) echo '<div class="error">' . $roleError . '</div>'; ?> 
		</li>
		
		<li class="form-field text">
			<label for="playstyle"><i class="icon-calendar icon-fixed-width"></i>How Old Are You?:</label>
			<input type="text" name="your-age" value="<?php echo $your_age; ?>" size="4" placeholder="years"/>
			<?php if( isset( $ageError ) && $ageError ) echo '<div class="error">' . $ageError . '</div>'; ?> 
		</li>
		
		<li class="checkbox">
			<p><i class="icon-time icon-fixed-width"></i>Typical Availability:</p>
			<ul class="radio-options-list">
				<li><input type="radio" name="playstyle" value="casual" <?php checked( $playstyle , 'casual' ); ?>/><label for="playstyle">0-13 Hours/Week</label></li>
				<li><input type="radio" name="playstyle" value="moderate" <?php checked( $playstyle , 'moderate' ); ?>/><label for="playstyle">14-27 Hours/Week</label></li>
				<li><input type="radio" name="playstyle" value="hardcore" <?php checked( $playstyle , 'hardcore' ); ?>/><label for="playstyle">28+ Hours/Week</label></li>
			</ul>
			<?php if( isset( $playstyleError ) && $playstyleError ) echo '<div class="error">' . $playstyleError . '</div>'; ?> 
		</li>
		
		<li class="wp-editor">
			<p><i class="icon-pencil icon-fixed-width"></i>Please describe your prior MMO experience, placing emphasis on past competitive raiding or PvP experience. Describe your experience with <em>ESO</em> in detail. Some specific items to mention include:</p>
			<ul>
				<li>Describe your main character(s) and their builds.</li>
				<li>What is your preferred playstyle and role within a group?</li>
				<li>What endgame PvE content have you completed?</li>
				<li>What item sets do you typically use?</li>
				<li>Are you interested in PvP? How do you adapt your character build to be successful in PvP?</li>
			</ul>
			<?php if( isset( $experienceError ) && $experienceError ) echo '<div class="error">' . $experienceError . '</div>'; ?> 
			
			<?php // Load the TinyMCE Editor
			wp_editor( $experience, 'experience', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'experience-field',
				'quicktags'		=> false,
				'teeny'			=> true,
			) ); ?>
		</li>
		
		<li class="wp-editor">
			<p><i class="icon-pencil icon-fixed-width"></i>Why are you interested in joining Entropy Rising? What do you have to offer Entropy Rising as a player and as a community member? Are you interested in contributing to the Tamriel Foundry community? Do you have any experience creating content to share with other gamers in the past like YouTube videos, Twitch streams, written guides, or artwork? Is there anything else you would like to share about yourself as a gamer or as a person?</p>	
			<?php if( isset( $youofferError ) && $youofferError ) echo '<div class="error">' . $youofferError . '</div>'; ?>
			
			<?php // Load the TinyMCE Editor
			wp_editor( $youoffer, 'youoffer', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'youoffer-field',
				'quicktags'		=> false,
				'teeny'			=> true,
			) ); ?>
		</li>
		
		<li class="checkbox">
			<label for="mainguild"><i class="icon-group icon-fixed-width"></i>Are you applying to join Entropy Rising as your primary guild?</label>
			<input type="radio" name="mainguild" value="yes" <?php checked( $mainguild , 'yes' ); ?>/><label for="mainguild">Yes</label>
			<input type="radio" name="mainguild" value="no" <?php checked( $mainguild , 'no' ); ?>/><label for="mainguild">No</label>
			<?php if( isset( $mainguildError ) && $mainguildError ) echo '<div class="error">' . $mainguildError . '</div>'; ?> 
		</li>
		
		<li class="text">		
			<label for="otherguildurl"><i class="icon-globe icon-fixed-width"></i>(If NO) Main guild website:</label>
			<input type="url" name="otherguildurl" id="otherguildurl" value="<?php echo $otherguildurl; ?>" size="75">
			<?php if( isset( $guildurlError ) && $guildurlError ) echo '<div class="error">' . $guildurlError . '</div>'; ?> 
		</li>
		
		<li class="checkbox">
			<label for="voicechat"><i class="icon-microphone icon-fixed-width"></i>Do you own a working microphone and are you comfortable using TeamSpeak3 while playing?</label>
			<input type="radio" name="voicechat" value="yes" <?php checked( $voicechat , 'yes' ); ?>/><label for="voicechat">Yes</label>
			<input type="radio" name="voicechat" value="no" <?php checked( $voicechat , 'no' ); ?>/><label for="voicechat">No</label>
			<?php if( isset( $voicechatError ) && $voicechatError ) echo '<div class="error">' . $voicechatError . '</div>'; ?> 
		</li>
		
		<li class="checkbox">
			<input type="checkbox" name="readrules" value="read"><label for="readrules">I have read the Entropy Rising <a href="http://tamrielfoundry.com/entropy-rising/charter" title="Read the charter" target="_blank">guild charter</a>, and understand what will be expected of me if my application is approved.</label></li>
			<?php if( isset( $readrulesError ) && $readrulesError ) echo '<div class="error">' . $readrulesError . '</div>'; ?> 
		</li>
		
		<li class="honeypot">
			<label for="checking">If you want to submit this form, do not enter anything in this field</label>
			<input type="text" name="checking" id="checking" value=""/>
		</li>
		 
		<li class="submit">
			<button type="submit"><i class="icon-envelope-alt"></i>Submit Application</button>
		</li>
		
		<li class="hidden">
			<?php wp_nonce_field( 'er_guild_application' ); ?>
			<input type="hidden" name="submitted" id="submitted" value="true" />
		</li>
	</ol>
</form>
<?php endif; ?>
