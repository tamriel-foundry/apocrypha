<?php 
/**
 * Apocrypha User Activation Template
 * Andrew Clayton
 * Version 1.0.0
 * 10-3-2013
 */
?>

<?php get_header(); ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header id="registration-header" class="entry-header <?php post_header_class(); ?>">
			<h1 class="entry-title">New User Activation</h1>
			<p class="entry-byline">Activate a new user account on Tamriel Foundry.</p>
		</header>
		
		<?php if ( bp_account_was_activated() ) : ?>
			<div class="updated">Your account was activated successfully! Please log in with the username and password you provided when you signed up. Welcome to the Tamriel Foundry community!</div>
			<div class="instructions">
				<h3 class="double-border bottom">Registration Completed</h3>
				<p>Thank you for completing your user registration at Tamriel Foundry. We look forward to welcoming you to our community! Here are some helpful links to get you started:</p>
				<ul>
					<li><a href="http://tamrielfoundry.com/topic/welcome-to-the-tamriel-foundry-forums/" title="Read welcome thread!" target="_blank">Welcome to the Tamriel Foundry Forums</a></li>
					<li><a href="http://tamrielfoundry.com/topic/introductions/" title="Read welcome thread!" target="_blank">Introduce Yourself</a></li>
					<li>Update Your User Profile</li>
				</ul>
			</div>
		<?php else : ?>	
			<div class="instructions">
				<h3 class="double-border bottom">Activate an Account</h3>
				<p>If you already followed the activation link from your email, your account has been activated and you can now log in with the username and password you provided at registration. Otherwise you can manually activate a pending account using a valid activation key.</p>
			</div>
			
			<form action="<?php the_permalink(); ?>" method="get" class="standard-form" id="activation-form">
				<ol id="activation-list">
					<li class="text">
						<label for="key"><?php _e( 'Activation Key:', 'buddypress' ); ?></label>
						<input type="text" name="key" id="key" value="" size="50" />
					</li>
					<li class="submit">
						<button type="submit" name="submit"><i class="icon-ok"></i>Activate Account</button>
					</li>
				</ol>
			</form>
		<?php endif; ?>
		
	</div><!-- #content -->
	<?php apoc_primary_sidebar(); // Load the community sidebar ?>
<?php get_footer(); ?>