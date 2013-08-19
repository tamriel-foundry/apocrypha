<?php 
/**
 * Apocrypha Theme Profile Header
 * Andrew Clayton
 * Version 1.0
 * 8-18-2013
 */

// Get the user info block
$user 	= new Apoc_User( bp_displayed_user_id() , 'profile' );
?>	

<header id="profile-header" class="entry-header <?php echo $user->faction; ?>">
	<h1 id="profile-title" class="entry-title">User Profile - <?php echo $user->fullname; ?></h1>
	<p class="entry-byline <?php echo $user->faction; ?>"><?php echo $user->byline; ?></p>		
	<?php if ( bbp_is_user_home() ) : ?>
		<a class="button" href="<?php echo $user->domain; ?>profile/edit" title="Edit your user profile">Edit Profile</a>
	<?php endif; ?>
</header><!-- #profile-header -->

<div id="profile-sidebar" role="complementary">
	<div id="profile-user-info">
		<?php echo $user->block; ?>	
	</div>
	
	<nav id="profile-menu" class="no-ajax">
		<ul id="profile-actions" role="navigation">
			<?php bp_get_displayed_user_nav(); ?>
		</ul>
	</nav>
</div><!-- #profile-sidebar -->

<div id="profile-action-block">
	<span class="activity"><?php echo $user->status['content']; ?></span>
	<div id="profile-action-buttons">
		<?php do_action( 'bp_member_header_actions' ); ?>
	</div>
</div><!-- #profile-action-block -->