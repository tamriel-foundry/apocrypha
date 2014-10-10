<?php 
/**
 * Apocrypha Theme User Capabilities Screen
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
			
			<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/capabilities/'; ?>" name="account-capabilities-form" id="account-capabilities-form" class="standard-form" method="post">	
				<div class="instructions">	
					<h3 class="double-border bottom">Restrict User Capabilities</h3>
					<ul>
						<li>Marking this user as a spammer will delete all their activity entries and move all forum posts into the spam folder.</li>
						<li>The activity entries are not recoverable, but the forum posts will be automatically restored if this action is reversed.</li>
					</ul>
				</div>
				
				<ol id="account-capabilities-list">
					<li class="checkbox">
						<input type="checkbox" name="user-spammer" id="user-spammer" value="1" <?php checked( bp_is_user_spammer( bp_displayed_user_id() ) ); ?> />
						<label><?php _e( 'This user is a spammer.', 'buddypress' ); ?></label>
					</li>
			
					<li class="submit">
						<button type="submit" id="capabilities-submit" name="capabilities-submit"><i class="icon-warning-sign"></i>Mark User</button>
					</li>
				
					<li class="hidden">
						<?php wp_nonce_field( 'capabilities' ); ?>
					</li>
				</ol>			
			</form><!-- #account-capabilities-form -->
			
			
		</div><!-- #profile-body -->
	</div><!-- #content -->
<?php get_footer(); // Loads the footer.php template. ?>		