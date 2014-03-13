<?php 
/**
 * Apocrypha Theme Entropy Rising Application Form
 * Andrew Clayton
 * Version 1.1
 * 1-21-2014
 */

 
// Get some data about the current user
$user 			= apocrypha()->user;
$userid 		= $user->ID;
$username 		= $user->display_name;
$username_link 	= '<a href="http://tamrielfoundry.com/members/'.$username.'" target="_blank">'.$username.'</a>';
$user_email 	= $user->user_email;

// If the form was submitted...
if( isset( $_POST['submitted']) ) {

	// Check nonce
	if ( !wp_verify_nonce( $_POST['_wpnonce'] , 'er_guild_application' ) )
		die( 'Security Failure' );

	// Check captcha and nonce
	if(trim($_POST['checking']) !== '') {
		$captchaError = true;
	} else {
	
	//Check fields
		if(trim($_POST['character-name']) === '') {
			$nameError = 'Please enter your character name.';
			$hasError = true;
		} else $character_name = trim($_POST['character-name']);
		
		$character_class = $_POST['character-class'];

		if(trim($_POST['your-age']) === '') {
			$ageError = 'Please state your age.';
			$hasError = true;
		} else $your_age = trim($_POST['your-age']);
		
		if ( $_POST['character-build'] === '' ) {
			$buildError = 'Please share your group PvE character build.';
			$hasError = true;
		} else $character_build = $_POST['character-build'];
		
		if( !isset( $_POST['playstyle'] ) ) {
			$playstyleError = 'Please indicate your usual playstyle.';
			$hasError = true;
		} else $playstyle = $_POST['playstyle'];
		
		if( trim($_POST['experience']) === '') {
			$experienceError = 'Please describe your MMO experience.';
			$hasError = true;
		} else {
			$experience = stripslashes(trim($_POST['experience']));
			$experience = wpautop( $experience );
		}
	
		if( trim($_POST['youoffer']) === '') {
			$youofferError = 'Please explain what you offer to Entropy Rising.';
			$hasError = true;
		} else {
			$youoffer = stripslashes(trim($_POST['youoffer']));
			$youoffer = wpautop( $youoffer );
		}
		
		if( !isset( $_POST['otherguild'] ) ) {
			$otherguildError = 'Are you a current member of another guild?';
			$hasError = true;
		} else $otherguild = $_POST['otherguild'];
		
		if ( $otherguild == 'yes' && $_POST['otherguildurl'] === '' ) {
			$guildurlError = 'Please link your guild website.';
			$hasError = true;
		} else $otherguildurl = $_POST['otherguildurl'];
		
		if ( !isset( $_POST['voicechat'] ) ) {
			$voicechatError = 'Please describe your ability to use voice communication.';
			$hasError = true;
		} else $voicechat = $_POST['voicechat'];
		
		$extrainfo = stripslashes(trim($_POST['extrainfo']));
		$extrainfo = wpautop ( $extrainfo );
		
		if ( !isset($_POST['readrules']) ) {
			$readrulesError = 'Please confirm your understanding of our guild charter.';
			$hasError = true;
		}
	
		if( !isset($hasError) ) { // Send the email!
			$emailto = "admin@tamrielfoundry.com";
			$subject = "Entropy Rising Guild Application From $username";
			
			/* Headers */
			$headers[] = "From: $username <$user_email>";
			$headers[] = "Content-type: text/html";

			/* Body */
			$body = "<h3>Basic Information</h3>";
			$body .= "<ul>";
			$body .= "<li>Applicant: $username_link";
			$body .= "<li>Email: $user_email</li>";
			$body .= "<li>Age: $your_age</li>";
			$body .= "<li>Character Name: $character_name</li>";
			$body .= "<li>Character Class: $character_class</li>";
			$body .= "<li>Character Build: $character_build</li>";
			$body .= "<li>Playstyle: $playstyle</li>";
			$body .= "<li>Member of Another Guild: $otherguild, $otherguildurl</li>";
			$body .= "<li>Voicechat: $voicechat</li>";
			$body .= "</ul>";
			$body .= "<h3>MMO Experience</h3>";
			$body .= "<div>$experience</div>";
			$body .= "<h3>Interest in Entropy Rising</h3>";
			$body .= "<div>$youoffer</div>";
			$body .= "<h3>Extra Info</h3>";
			$body .= "<div>$extrainfo</div>";
		
			wp_mail( $emailto , $subject , $body, $headers);
			$emailSent = true;
		}
	}
} ?>

<?php if ( isset( $emailSent ) ) : ?>
<div class="updated">
	Congratulations <?php echo $username; ?>, your application was successfully submitted! We will attempt to review it within the next several days. You will be contacted via the email address registered on your Tamriel Foundry user account once we have processed your application. Thank you for taking the time to apply to Entropy Rising.
</div>
<?php elseif ( isset($hasError) ) : ?>
	<p class="warning">Sorry <?php echo $username; ?>, there was an error with your application, please correct any errors before resubmitting this form.</p>
<?php else : ?>
	<div class="instructions">
	<?php if ( guild_recruitment_status() == 'closed' ) : ?>
		<h4 style="color:darkred"><u>Disclaimer:</u></h4>
		Hello, <?php echo $username; ?>. Recruitment for Entropy Rising is currently closed. However, we are always interested in recruiting truly exceptional players to join our tight-knit team. If you feel that you possess the exact qualities which we are looking for in Entropy Rising members, please feel free to submit an application. We will save your application, and review it when and if we decide to add new members. If you apply while recruitment is closed, do not expect to hear back from us until recruitment is reopened, at which time your application will be revisited.
	<?php else : ?>
		Hello, <?php echo $username; ?>. Please carefully fill out the following form.
	<?php endif; ?>
	</div>
<?php endif; ?>


<?php if ( !isset( $emailSent ) ) : ?>
<form action="<?php the_permalink(); ?>" id="guild-application-form" method="post">
	<ol id="guild-application-list">	
		<li class="text">
			<label for="character-name"><i class="icon-bookmark icon-fixed-width"></i>Character Name (intended):</label>
			<input type="text" name="character-name" value="<?php if(isset($_POST['character-name'])) echo $_POST['character-name'];?>" size="50" />
			<?php if( isset( $nameError ) && $nameError ) echo '<div class="error">' . $nameError . '</div>'; ?> 
		</li>
	
		<li class="select">
			<label for="character-class"><i class="icon-gear icon-fixed-width"></i>Character Class (intended):</label>
			<select name="character-class">
			<?php $classes = array ( 'dragonknight', 'templar', 'sorcerer', 'nightblade' ); 
				foreach ( $classes as $key => $class ) :
				echo '<option value="' . $class . '" ' . selected( $_POST['character-class'] , $class , true ) . '>' . ucfirst($class) . '</option>';
				endforeach; ?>
			</select>
		</li>
		
		<li class="text">		
			<label for="character-build"><i class="icon-shield icon-fixed-width"></i>Character Build (group PvE):</label>
			<input type="url" name="character-build" id="character-build" value="<?php if(isset($_POST['character-build'])) echo $_POST['character-build'];?>" size="75">
			<?php if( isset( $buildError ) && $buildError ) echo '<div class="error">' . $buildError . '</div>'; ?> 
		</li>
		
		<li class="form-field text">
			<label for="playstyle"><i class="icon-calendar icon-fixed-width"></i>How Old Are You?:</label>
			<input type="text" name="your-age" value="<?php if(isset($_POST['your-age'])) echo $_POST['your-age'];?>" size="2"/>
			<?php if( isset( $ageError ) && $ageError ) echo '<div class="error">' . $ageError . '</div>'; ?> 
		</li>
		
		<li class="checkbox">
			<p><i class="icon-time icon-fixed-width"></i>Typical Playstyle:</p>
			<?php if( isset($_POST['playstyle']) ) $playstyle = $_POST['playstyle']; ?>
			<ul class="radio-options-list">
				<li><input type="radio" name="playstyle" value="casual" <?php if ( isset( $_POST['playstyle'] ) ) checked( $_POST['playstyle'] , 'casual' ); ?>/><label for="playstyle">0-13 Hours/Week</label></li>
				<li><input type="radio" name="playstyle" value="moderate" <?php if ( isset( $_POST['playstyle'] ) ) checked( $_POST['playstyle'] , 'moderate' ); ?>/><label for="playstyle">14-27 Hours/Week</label></li>
				<li><input type="radio" name="playstyle" value="hardcore" <?php if ( isset( $_POST['playstyle'] ) ) checked( $_POST['playstyle'] , 'hardcore' ); ?>/><label for="playstyle">28+ Hours/Week</label></li>
			</ul>
			<?php if( isset( $playstyleError ) && $playstyleError ) echo '<div class="error">' . $playstyleError . '</div>'; ?> 
		</li>
		
		<li class="wp-editor">
			<p><i class="icon-pencil icon-fixed-width"></i>Please describe your prior MMO experience in detail. Particular emphasis should be placed on competitive raiding or PvP experience.</p>
			<?php if( isset( $experienceError ) && $experienceError ) echo '<div class="error">' . $experienceError . '</div>'; ?> 
			<?php // Load the TinyMCE Editor
			$thecontent = wpautop( isset( $_POST['experience'] ) ? stripslashes($_POST['experience']) : "" );
			wp_editor( $thecontent, 'experience', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'experience-field',
				'quicktags'		=> false,
				'teeny'			=> true,
				) ); ?>
		</li>
		
		<li class="wp-editor">
			<p><i class="icon-pencil icon-fixed-width"></i>Why are you interested in joining, and what do you have to offer Entropy Rising? What do you have to offer Tamriel Foundry?</p>
			<?php if( isset( $youofferError ) && $youofferError ) echo '<div class="error">' . $youofferError . '</div>'; ?> 
			<?php // Load the TinyMCE Editor
			$thecontent = wpautop( isset( $_POST['youoffer'] ) ? stripslashes($_POST['youoffer']) : "" );
			wp_editor( $thecontent, 'youoffer', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'youoffer-field',
				'quicktags'		=> false,
				'teeny'			=> true,
				) ); ?>
		</li>
		
		<li class="checkbox">
			<label for="otherguild"><i class="icon-group icon-fixed-width"></i>Are you currently a member of another guild?</label>
			<input type="radio" name="otherguild" value="yes" <?php if( isset( $_POST['otherguild'] ) ) checked( $_POST['otherguild'] , 'yes' ); ?>/><label for="otherguild">Yes</label>
			<input type="radio" name="otherguild" value="no" <?php if( isset( $_POST['otherguild'] ) ) checked( $_POST['otherguild'] , 'no' ); ?>/><label for="otherguild">No</label>
			<?php if( isset( $otherguildError ) && $otherguildError ) echo '<div class="error">' . $otherguildError . '</div>'; ?> 
		</li>
		
		<li class="text">		
			<label for="otherguildurl"><i class="icon-globe icon-fixed-width"></i>(If yes) Current guild website:</label>
			<input type="url" name="otherguildurl" id="otherguildurl" value="<?php if(isset($_POST['otherguildurl'])) echo $_POST['otherguildurl'];?>" size="75">
			<?php if( isset( $guildurlError ) && $guildurlError ) echo '<div class="error">' . $guildurlError . '</div>'; ?> 
		</li>
		
		<li class="checkbox">
			<label for="voicechat"><i class="icon-microphone icon-fixed-width"></i>Do you own a working microphone and are you willing to download and use TeamSpeak3 while online?</label>
			<input type="radio" name="voicechat" value="yes" <?php if( isset( $_POST['voicechat'] ) ) checked( $_POST['voicechat'] , 'yes' ); ?>/><label for="voicechat">Yes</label>
			<input type="radio" name="voicechat" value="no" <?php if( isset( $_POST['voicechat'] ) ) checked( $_POST['voicechat'] , 'no' ); ?>/><label for="voicechat">No</label>
			<?php if( isset( $voicechatError ) && $voicechatError ) echo '<div class="error">' . $voicechatError . '</div>'; ?> 
		</li>
			
		<li class="wp-editor">
			<p><i class="icon-pencil icon-fixed-width"></i>Is there anything else you would like to share about yourself as a gamer or as a person?</p>
			<?php // Load the TinyMCE Editor
			$thecontent = wpautop ( isset( $_POST['extrainfo'] ) ? stripslashes($_POST['extrainfo']) : "" );
			wp_editor( $thecontent, 'extrainfo', array(
				'media_buttons' => false,
				'wpautop'		=> true,
				'editor_class'  => 'extrainfo-field',
				'quicktags'		=> false,
				'teeny'			=> true,
				) ); ?>
		</li>
		
		<li class="checkbox">
			<input type="checkbox" name="readrules" value="read"><label for="readrules">I have read the Entropy Rising <a href="http://tamrielfoundry.com/entropy-rising/charter" title="Read the charter" target="_blank">guild charter</a>, and understand what will be expected of me if my application is approved.</label></li>
			<?php if( isset( $readrulesError ) && $readrulesError ) echo '<div class="error">' . $readrulesError . '</div>'; ?> 
		</li>
		
		<li class="honeypot">
			<label for="checking" class="screenReader">If you want to submit this form, do not enter anything in this field</label>
			<input type="text" name="checking" id="checking" class="screenReader" value="<?php if(isset($_POST['checking'])) echo $_POST['checking'];?>"/>
			<?php if( isset( $captchaError ) && $captchaError ) echo '<div class="error">DIE ROBOT!</div>'; ?> 
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
