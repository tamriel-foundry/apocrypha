<?php 
/**
 * Apocrypha Theme Profile Header
 * Andrew Clayton
 * Version 1.0
 * 8-18-2013
 */

// Get the user info block
global $user;
?>	

<header id="profile-header" class="entry-header <?php echo $user->faction; ?>">
	<h1 id="profile-title" class="entry-title">User Profile - <?php echo $user->fullname; ?></h1>
	<p class="entry-byline <?php echo $user->faction; ?>"><?php echo $user->byline; ?></p>		
	<div id="profile-actions">
		<?php if ( bbp_is_user_home() ) : ?>
		<a class="button" href="<?php echo $user->domain; ?>profile/edit" title="Edit your user profile"><i class="icon-edit"></i>Edit Profile</a>
		<?php else : ?>
		<?php do_action( 'bp_member_header_actions' ); ?>
		<?php endif; ?>
	</div>
</header><!-- #profile-header -->

<div id="profile-sidebar" role="complementary">
	<div id="profile-user" class="user-block">
		<?php echo $user->block; ?>	
	</div>
</div><!-- #profile-sidebar -->

<div id="profile-content">
	<nav id="directory-nav" class="no-ajax">
		<ul id="directory-actions" role="navigation">
			<?php bp_get_displayed_user_nav(); ?>
		</ul>
	</nav>
	
	<blockquote id="profile-status" class="user-status">
		<p><?php echo '@' . $user->nicename . ' &rarr; ' . bp_get_activity_latest_update( $user->ID ); ?></p>
		<?php if ( bp_is_my_profile() ) : ?>
			<a class="update-status-button button"><i class="icon-pencil"></i>What's New?</a>
		<?php else : ?>
			<span class="activity"><?php bp_last_activity( $user->ID ); ?></span>
		<?php endif; ?>
	</blockquote>
	
	<div id="profile-widgets">		
		<div id="detail-post-count" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title">Post Details</h3>
			</header>
			<ul id="detail-post-count">
				<?php $posts = $user->posts; 
				if ( $posts['articles'] > 0 ) : ?>
					<li><i class="icon-tag icon-fixed-width"></i>Articles <span class="activity-count"><?php echo $posts['articles']; ?></span></li>
				<?php endif; ?>
				<li><i class="icon-comment icon-fixed-width"></i>Comments <span class="activity-count"><?php echo $posts['comments']; ?></span></li>
				<li><i class="icon-bookmark icon-fixed-width"></i>Topics <span class="activity-count"><?php echo $posts['topics']; ?></span></li>
				<li><i class="icon-reply icon-fixed-width"></i>Replies <span class="activity-count"><?php echo $posts['replies']; ?></span></li>
				<li class="post-count-total"><i class="icon-star icon-fixed-width"></i>Total <span class="activity-count"><?php echo $posts['total']; ?></span></li>
			</ul>
		</div>
		
		<div id="profile-badges" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title">User Badges</h3>
			</header>
			<ul class="user-badges">
				<?php if ( !empty( $user->badges ) ) :
				foreach ( $user->badges as $badge => $name ) : ?>
					<li class="user-badge <?php echo $badge; ?>" title="<?php echo $name; ?>"></li>
				<?php endforeach;
				else : ?>
					<li>No badges earned yet!</li>
				<?php endif; ?>
			</ul>
		</div>
		
		<div id="profile-contacts" class="widget profile-widget">
			<header class="widget-header">
				<h3 class="widget-title">Contact Info</h3>
			</header>
			<?php $user->contacts(); ?>
		</div>
	</div>
	
</div>