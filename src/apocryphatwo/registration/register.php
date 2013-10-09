<?php 
/**
 * Apocrypha User Registration Template
 * Andrew Clayton
 * Version 1.0.0
 * 10-3-2013
 */
?>

<?php get_header(); ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header id="registration-header" class="entry-header <?php post_header_class(); ?>">
			<h1 class="entry-title">New User Registration</h1>
			<p class="entry-byline">Register a new user account on Tamriel Foundry.</p>
		</header>
		
		<form action="<?php the_permalink(); ?>" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">
			<?php do_action( 'template_notices' ); ?>
			
			<?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
				<div class="error">User registration is currently disabled. This is typically a temporary change to allow for periodic site maintenance or other administrative processes. Please check back again soon!</div>
			<?php endif; ?>
			
			<?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>
				<div id="registration-instructions" class="instructions">
					<h3 class="double-border bottom">Important Registration Instructions</h3>
					<ul>
						<li>Please carefully review the following form, paying special attention to the registration instructions. All fields are required.</li>
						<li>Your username is how you will be known throughout the community. Choose the name that you wish to be associated with your user profile on Tamriel Foundry. Spaces and certain special characters are permitted.</li>
						<li>Please do not register a user account for your guild. Guilds are collections of individual users, not users themselves.</li>
						<li>A valid email address is required in order to activate your account, any site notifications which you elect to recieve or correspondence from Tamriel Foundry administrators will be sent to this address.</li>		
						<li>If you have any trouble completing the registration process, please contact <a href="mailto:admin@tamrielfoundry.com?Subject=Registration%20Trouble" target="_blank">admin@tamrielfoundry.com</a> for assistance.</p>
					</ul>
				</div>
				
				<h3 class="registration-header double-border bottom">Basic Account Details</h3>
				<ol id="registration-account-details">
					
					<li class="text">
						<?php do_action( 'bp_signup_username_errors' ); ?>
						<label class="settings-field-label" for="signup_username"><i class="icon-user icon-fixed-width"></i>Desired Username:</label>
						<input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" size="40" />					
					</li>
					
					<li class="text">
						<?php do_action( 'bp_signup_email_errors' ); ?>
						<label class="settings-field-label" for="signup_email"><i class="icon-envelope icon-fixed-width"></i>Your Email Address:</label>
						<input type="text" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" size="40" />
					</li>
					
					<li class="text">
						<?php do_action( 'bp_signup_password_errors' ); ?>	
						<label class="settings-field-label" for="signup_password"><i class="icon-key icon-fixed-width"></i>Choose A Password:</label>
						<input type="password" name="signup_password" id="signup_password" value="" autocomplete="off" size="40" />
					</li>
					
					<li class="text">
						<?php do_action( 'bp_signup_password_confirm_errors' ); ?>	
						<label class="settings-field-label" for="signup_password_confirm"><i class="icon-ok icon-fixed-width"></i>Confirm Password:</label>
						<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" autocomplete="off" size="40" />
					</li>
				</ol>
				<?php do_action( 'bp_after_account_details_fields' ); ?>
				
				<div id="registration-terms" class="instructions">
					<h3 class="double-border bottom">Terms of Registration</h3>
					<p>By registering for Tamriel Foundry, you agree to respect our sitewide <a href="http://tamrielfoundry.com/topic/tamriel-foundry-code-of-conduct" title="Tamriel Foundry Code of Conduct" target="_blank">Code of Conduct</a>. Violations of these rules may result in immediate warning, suspension, or removal.</p>
					<ul id="registration-terms-left">
						<li style="list-style:none;"><h4><u>Tamriel Foundry IS:</u></h4></li>
						<li>A community of ESO enthusiasts.</li>
						<li>A resource for useful articles, guides, and tools.</li>
						<li>A forum for discussing gameplay mechanics and strategies.</li>
					</ul>
					<ul id="registration-terms-right">
						<li style="list-style:none;"><h4><u>Tamriel Foundry IS NOT:</u></h4></li>
						<li>A general purpose gaming forum.</li>		
						<li>A lore compendium or library.</li>
						<li>A dedicated roleplaying platform.</li>		
						<li>A guild recruitment listing service.</li>		
					</ul>
				</div>
				
				<h3 class="registration-header double-border bottom">Membership Agreement</h3>
				<ol id="registration-use-terms">
				
					<li class="checkbox">
						<input type="checkbox" name="confirm_tos_box" id="confirm_tos_box" value="confirmed" autocomplete="off" />
						<label for="confirm_tos_box">I understand the distinction regarding the nature and purpose of Tamriel Foundry.</label>
						<?php do_action( 'bp_confirm_tos_box_errors' ); ?>
					</li>
					
					<li class="checkbox">
						<input type="checkbox" name="confirm_coc_box" id="confirm_coc_box" value="confirmed" autocomplete="off" />
						<label for="confirm_coc_box">I agree to comply by the terms of the <a href="http://tamrielfoundry.com/topic/tamriel-foundry-code-of-conduct/" target="_blank" title="Read the Code of Conduct">Code of Conduct</a> when participating in Tamriel Foundry.</label>
						<?php do_action( 'bp_confirm_coc_box_errors' ); ?>
					</li>
				</ol>
				
				<h3 class="registration-header double-border bottom">Confirm Your Humanity</h3>
				<div id="humanity-section">
					<img id="humanity-image" class="avatar" src="<?php echo THEME_URI; ?>/images/avatars/argonian.png" alt="HINT: This is an Argonian!" title="HINT: This is an Argonian!" width="220" height="250" />
					<div id="humanity-fields">		
						<?php do_action( 'bp_confirm_humanity_errors' ); ?>
						<label for="confirm_humanity"><i class="icon-search"></i>Identify which iconic Elder Scrolls race is shown in the image to the left:</label><br><br>
						<input type="text" name="confirm_humanity" id="confirm_humanity" value="" autocomplete="off" size="40" placeholder="&larr; What is that thing???"  />
					</div>
				</div>
				
				<div id="xprofile-section">
				<?php if ( bp_is_active( 'xprofile' ) ) : ?>
					<?php if ( bp_has_profile( 'profile_group_id=1' ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
						<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
							<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>
							<?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
							<input type="hidden" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" />
							<?php endif; ?>
						<?php endwhile; ?>
						<input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_group_field_ids(); ?>" />
					<?php endwhile; endif; ?>
				<?php endif; ?>
				</div>

				<ol id="registration-complete">

					<li id="registration-submit" class="submit">
						<button type="submit" name="signup_submit" id="signup_submit" ><i class="icon-ok"></i>Complete Account Creation</button>
					</li>
					
					<li class="hidden">
						<?php do_action( 'bp_after_signup_profile_fields' ); ?>
						<?php do_action( 'bp_before_registration_submit_buttons' ); ?>				
						<?php wp_nonce_field( 'bp_new_signup' ); ?>
					</li>				
				</ol>
				
			<?php elseif ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>
				<div class="updated">Thank you for registering!</div>
				<div id="registration-instructions" class="instructions">
				<?php if ( bp_registration_needs_activation() ) : ?>
					<h3 class="double-border bottom">Signup Almost Complete!</h3>
					<p>You have successfully created your account! However, before you can begin using your account, you must finalize your Tamriel Foundry registration by confirming your account via the email we have just sent to your given email address. This email will contain an activation link, following it will automatically complete the activation process. If you have trouble locating this email, please check your <u>SPAM</u> folder, <em>just in case</em>. Thanks for registering!</p>	
				<?php else : ?>
					<h3 class="double-border bottom">Congratulations, Signup Complete!</h3>
					<p>You have successfully created your account! You may now log in using the username and password you have just created. Thanks for registering!</p>	
				<?php endif; ?>
				</div>			
			<?php endif; ?>		
			<?php do_action( 'bp_custom_signup_steps' ); ?>
			
		</form><!-- #signup_form -->	
	</div><!-- #content -->
	<?php apoc_primary_sidebar(); // Load the community sidebar ?>
<?php get_footer(); ?>