<?php 
/**
 * Apocrypha Theme Delete Account Screen
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
			
			<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/delete-account'; ?>" name="account-delete-form" id="account-delete-form" class="standard-form" method="post">
			
				<div class="instructions">	
					<h3 class="double-border bottom">Permanently Delete Account</h3>
					<ul>
						<?php if ( bp_is_my_profile() ) : ?>
						<li>Deleting your account will delete all of the content you have created. It will be completely irrecoverable.</li>
						<?php else : ?>
						<li>Deleting this account will delete all of the content it has created. It will be completely irrecoverable.</li>
						<?php endif; ?>
						<li>Do not submit this form unless you absolutely understand the consequences of this action.</li>
					</ul>
				</div>
				
				<ol id="account-delete-list">
					<li class="checkbox">
						<input type="checkbox" name="delete-account-understand" id="delete-account-understand" value="1" onclick="if(this.checked) { document.getElementById('delete-account-button').disabled = ''; } else { document.getElementById('delete-account-button').disabled = 'disabled'; }" />
						<label><?php _e( 'I understand the consequences.', 'buddypress' ); ?></label>
					</li>
					
					<li class="submit">
						<button type="submit" disabled="disabled" id="delete-account-button" name="delete-account-button">
							<i class="icon-trash"></i>Permanently Delete Account
						</button>
					</li>

					<li class="hidden">
						<?php wp_nonce_field( 'delete-account' ); ?>
					</li>
				</ol>
				
			</form><!-- #account-delete-form -->
		</div><!-- #profile-body -->
	</div><!-- #content -->
<?php get_footer(); // Loads the footer.php template. ?>	