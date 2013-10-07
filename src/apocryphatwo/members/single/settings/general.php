<?php 
/**
 * Apocrypha Theme User Settings General Screen
 * Andrew Clayton
 * Version 1.0.0
 * 10-6-2013
 */
 
// Get the currently displayed user
global $user;
$user 	= new Apoc_User( bp_displayed_user_id() , 'profile' );
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
			
			<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/general'; ?>" method="post" class="standard-form" id="settings-form">
				
				<div class="instructions">	
					<h3 class="double-border bottom">Modify Account Settings</h3>
					<ul>
						<li>You can use this area to change details regarding your Tamriel Foundry user account.</li>
						<li>Your current password required to change your account email or password.</li>
						<li>You are not allowed to change your account username. In exceptional cases, Tamriel Foundry administrators may grant name changes. If you wish to request your username be changed, please email <a href="mailto:admin@tamrielfoundry.com?Subject=Requested%20Username%20Change" title="Email Us">admin@tamrielfoundry.com</a> with the reason for your request.</li>
						<li>You may change your account password, or leave these fields blank for your password to remain unchanged.</li>
					</ul>
				</div>
			
				<ol id="profile-settings-form">
					<?php if ( !is_super_admin() ) : ?>
					<li class="text">
						<label for="pwd"><i class="icon-lock icon-fixed-width"></i>Current Password:</label>
						<input type="password" name="pwd" id="pwd" size="30" value="" />
						<a href="<?php echo site_url( add_query_arg( array( 'action' => 'lostpassword' ), 'wp-login.php' ), 'login' ); ?>" title="<?php _e( 'Password Lost and Found', 'buddypress' ); ?>" class="button">Lost your password?</a>
					</li>
					<?php endif; ?>
					
					<li class="text">
						<label for="login"><i class="icon-user icon-fixed-width"></i>Username:</label>
						<input type="text" name="login" id="login" size="30" value="<?php bp_displayed_user_username(); ?>" disabled />
					</li>
					
					<li class="text">
						<label for="email"><i class="icon-envelope icon-fixed-width"></i>Change Account Email:</label>
						<input type="text" name="email" id="email" value="<?php echo bp_get_displayed_user_email(); ?>" class="settings-input" size="30"/>
					</li>

					<li class="password">
						<label for="pass1"><i class="icon-key icon-fixed-width"></i>New Password:</label>
						<input type="password" name="pass1" id="pass1" size="30" value="" class="settings-input small" />
					</li>
					
					<li class="password">
						<label for="pass2"><i class="icon-ok icon-fixed-width"></i>Confirm Password:</label>
						<input type="password" name="pass2" id="pass2" size="30" value="" class="settings-input small" />
					</li>					
				
					<li class="submit">
						<button type="submit" name="submit"id="submit" class="auto"><i class="icon-ok"></i>Save Changes</button>
					</li>
				
					<li class="hidden">
						<?php wp_nonce_field( 'bp_settings_general' ); ?>
					</li>
				</ol>
			</form><!-- #settings-form -->	
		</div><!-- #profile-body -->
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>