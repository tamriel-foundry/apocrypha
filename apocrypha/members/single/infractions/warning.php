<?php 
/**
 * Apocrypha Theme User Infractions Warning Form
 * Andrew Clayton
 * Version 1.0.0
 * 10-2-2013
 */
 
// Get the currently displayed user
global $user;
$user 		= new Apoc_User( bp_displayed_user_id() , 'profile' );
$user_id 	= $user->id;
$level		= $user->warnings['level'];
$points		= 1;

global $bp;
$action_url = $bp->displayed_user->domain . 'infractions';

// Process new warnings
if ( isset( $_POST['issue_warning_nonce'] ) && wp_verify_nonce( $_POST['issue_warning_nonce'] , 'issue-warning' ) ) {
	
	// Flush any cached stuff
	if ( function_exists( 'w3tc_pgcache_flush' ) ) w3tc_pgcache_flush();
	if ( function_exists( 'w3tc_objectcache_flush' ) ) w3tc_objectcache_flush();
	if ( function_exists( 'apc_clear_cache' ) ) apc_clear_cache('user');
	
	// Validate data
	$warnings 		= ( $level > 0 ) ? $user->warnings['history'] : array();
	$current_user 	= apocrypha()->user->data;
	$moderator		= $current_user->display_name;
	
	// Warning points
	$points = $_POST['warning-points'];
	
	// Warning reason
	if ( empty( $_POST['warning-reason'] ) )
		$error = 'You must supply a reason for issuing this warning.';
	else
		$reason = wpautop( $_POST['warning-reason'] );
		
	// Warning date
	if ( '' != $_POST['warning-date'] )
		$date = date( 'M j, Y' , strtotime( $_POST['warning-date'] , current_time( 'timestamp' ) ) );
	else
		$date = date('M j, Y', current_time( 'timestamp' ) );

	// Add the new warning to the array
	$warnings[] = array(
		'points' 	=> $points,
		'reason' 	=> trim( $reason ),
		'moderator' => $moderator,
		'date' 		=> $date,
	);
	
	// Make sure there was no error
	if ( $error ) {
		bp_core_add_message( $error , 'error' );
	} else {
	
		// Update user meta
		update_user_meta( $user_id , 'infraction_history' , $warnings );
		bp_core_add_message( 'Warning successfully issued!' , 'success' );
		
		// Maybe ban the user
		if ( $level + $points == 5 ) {
			$u = new WP_User( $user_id );
			$u->set_role('banned');	
		}
		
		// Flag success
		$success = true;
		
		// Email people
		if ( $_POST['email-user'] || $_POST['email-mods'] ) {
		
			// Set the email headers
			$name		= bp_get_displayed_user_username();
			$subject 	= "[Tamriel Foundry] Warning Issued to $name";
			$headers 	= "From: Foundry Discipline Bot <noreply@tamrielfoundry.com>\r\n";
			$headers	.= "Content-Type: text/html; charset=UTF-8";
			
			// Construct the message
			$body = '<p>Your user account, ' . $name . ', has been issued a warning for ' . $points . ' point(s) by the moderation team at Tamriel Foundry for the following reason:</p>';
			$body .= '&nbsp;';
			$body .= '<p><strong>' . $reason . '</strong></p>';
			$body .= '&nbsp;';
			$body .= '<p>Please review the Tamriel Foundry <a href="http://tamrielfoundry.com/topic/tamriel-foundry-code-of-conduct/" title="Read the Code of Conduct" target="_blank">Code of Conduct</a> to better understand the expectations we have of our users.';
			$body .= 'You may review your current infractions on your user profile using the following link:</p>';
			$body .= '<p><a href="' . $bp->displayed_user->domain . 'infractions/' . '" title="View Your Infractions" target="_blank">' . $bp->displayed_user->domain . 'infractions/</a>';
			
			// Send the message
			if ( $_POST['email-user'] ) {
				$emailto = bp_get_displayed_user_email();
				wp_mail( $emailto , $subject , $body , $headers );
			}
			if ( $_POST['email-mods'] ) {
				$emailto = get_moderator_emails();
				wp_mail( $emailto , $subject , $body , $headers );
			}				
		}
		
		// Redirect
		wp_redirect( $action_url , 302 );
	}
}
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>	
		
		<div id="profile-body">
			<?php do_action( 'template_notices' ); ?>
			<nav class="directory-subheader no-ajax" id="subnav" >
				<ul id="profile-tabs" class="tabs" role="navigation">
					<?php bp_get_options_nav(); ?>
				</ul>
			</nav><!-- #subnav -->

			<div id="user-infractions">
				<h3>Send <?php echo bp_displayed_user_username(); ?> A Warning!</h3>
				<div class="warning">The current warning level for <?php bp_displayed_user_username(); ?> is <?php echo $level; ?> points.</div>
			</div>
			
			<?php if ( user_can( $user_id , 'moderate' ) ) : ?>
				<div class="error">You cannot issue a warning to another moderator.</div>
				
			<?php elseif ( $error ) : ?>
				<div class="error"><?php echo $error; ?></div>
			
			<?php elseif ( $level < 5 ) : ?>
			<form action="<?php echo $action_url ?>/warning" name="send-warning-form" id="send-warning-form" class="standard-form" method="post">
				<ol id="send-warning">
					<li class="select form-left">
						<label for="warning-points"><i class="icon-warning-sign"></i> How Many Points: </label>
						<select name="warning-points">
						<?php while ( 5 - $level > 0 ) :
							echo '<option>' . $points . '</option>';
							$level++;
							$points++;
						endwhile; ?>
						</select>
					</li>
					
					<li class="text form-right">
						<label for="warning-date"><i class="icon-calendar"></i> Infraction Date (optional): </label>
						<input type="text" name="warning-date" id="warning-date" />
					</li>
					
					<li class="textarea">
						<label for="warning-reason"><i class="icon-pencil"></i>Reason: </label>
						<textarea name="warning-reason" id="warning-reason" rows="5"></textarea>
					</li>
					
					<li class="checkbox form-left">
						<ul class="radio-options-list">
							<li>
								<input type="checkbox" name="email-user" id="email-user" checked="checked"/>
								<label for="email-user">Notify user?</label>
							</li>
							<li>
								<input type="checkbox" name="email-mods" id="email-mods" checked="checked"/>
								<label for="email-user">Notify moderators?</label>
							</li>
						</ul>
					</li>
					
					<li class="submit form-right">
						<button type="submit" name="issuewarning">
							<i class="icon-exclamation"></i>Issue Warning
						</button>
					</li>
					
					<li class="hidden">
						<?php wp_nonce_field( 'issue-warning' , 'issue_warning_nonce' ) ?>
						<input name="action" type="hidden" id="action" value="issue-warning" />
					</li>
				</ol>
			</form>
			<?php endif; ?>
		</div>		
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>