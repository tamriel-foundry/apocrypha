<?php
/**
 * Apocrypha Theme Admin Notifications Template
 * Andrew Clayton
 * Version 1.0.1
 * 2-17-2014
 */
 
// Get the notifications
$user 			= apocrypha()->user;
$user_id		= $user->ID;
$link			= trailingslashit( SITEURL ) . trailingslashit( BP_MEMBERS_SLUG ) . $user->data->user_nicename;
$notifications 	= apoc_user_notifications( $user_id );
?>
 
<ul id="notifications-menu">
	<li id="notifications-activity" class="notification-type">
	<?php if ( !empty( $notifications['activity'] ) ) :?>
		<span class="notifications-number"><?php echo $notifications['counts']['activity']; ?></span>
	<?php endif; ?>		
		<div class="admin-bar-dropdown">
			<ul class="notification-list icons-ul">
			
			<?php if ( !empty( $notifications['activity'] ) ) : for( $i = 0; $i < count( $notifications['activity'] ); $i++ ) : ?>
				<li id="notification-<?php echo $notifications['activity'][$i]->id; ?>" class="notification-entry"><i class="icon-li icon-chevron-right"></i>
					<?php $id = ( $notifications['activity'][$i]->component_action == "new_at_mention" ) ? $user_id : $notifications['activity'][$i]->item_id; ?>
					<a class="clear-notification" data-type="<?php echo $notifications['activity'][$i]->component_action; ?>" data-id="<?php echo $id; ?>" data-count="<?php echo $notifications['activity'][$i]->counts; ?>"><i class="icon-remove"></i></a>
					<?php echo $notifications['activity'][$i]->desc; ?>
				</li>
			<?php endfor; else: ?>
				<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new mentions.</li>
			<?php endif; ?>	
			</ul>
			<ul class="notification-links">
				<li><a class="button" href="<?php echo SITEURL . '/activity/'; ?>" title="The sitewide activity feed"><i class="icon-gears"></i>Site Feed</a></li>
				<li><a class="button" href="<?php echo $link . '/activity/mentions/'; ?>" title="Your mentions in the community"><i class="icon-comments-alt"></i>Your Mentions</a></li>
			</ul>					
		</div>
	</li>
	
	<li id="notifications-messages" class="notification-type">
	<?php if ( !empty( $notifications['messages'] ) ) :?>
		<span class="notifications-number"><?php echo count( $notifications['messages'] ); ?></span>
	<?php endif; ?>		
		<div class="admin-bar-dropdown">
			<ul class="notification-list icons-ul">
			<?php if ( !empty( $notifications['messages'] ) ) : for( $i = 0; $i < count( $notifications['messages'] ); $i++ ) : ?>
				<li id="notification-<?php echo $notifications['messages'][$i]->id; ?>" class="notification-entry"><i class="icon-li icon-chevron-right"></i>
					<a class="clear-notification" data-type="<?php echo $notifications['messages'][$i]->component_action; ?>" data-id="<?php echo $notifications['messages'][$i]->id; ?>"><i class="icon-remove"></i></a>
					<?php echo $notifications['messages'][$i]->desc; ?>
				</li>
			<?php endfor; else: ?>
				<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new messages.</li>
			<?php endif; ?>	
			</ul>
			<ul class="notification-links">
				<li><a class="button" href="<?php echo $link . '/messages/'; ?>" title="Go to your inbox"><i class="icon-inbox"></i>Inbox</a></li>
				<li><a class="button" href="<?php echo $link . '/messages/sentbox/'; ?>" title="Browse your sent messages"><i class="icon-envelope-alt"></i>Outbox</a></li>
				<li><a class="button" href="<?php echo $link . '/messages/compose/'; ?>" title="Send a new message"><i class="icon-edit"></i>New Message</a></li>
			</ul>					
		</div>
	</li>

	<li id="notifications-friends" class="notification-type">
	<?php if ( !empty( $notifications['friends'] ) ) :?>
		<span class="notifications-number"><?php echo count( $notifications['friends'] ); ?></span>
	<?php endif; ?>		
		<div class="admin-bar-dropdown">
			<ul class="notification-list icons-ul">
			<?php if ( !empty( $notifications['friends'] ) ) : for( $i = 0; $i < count( $notifications['friends'] ); $i++ ) : ?>
				<li id="notification-<?php echo $notifications['friends'][$i]->id; ?>" class="notification-entry"><i class="icon-li icon-chevron-right"></i>
					<a class="clear-notification" data-type="<?php echo $notifications['friends'][$i]->component_action; ?>" data-id="<?php echo $notifications['friends'][$i]->id; ?>"><i class="icon-remove"></i></a>
					<?php echo $notifications['friends'][$i]->desc; ?>
				</li>
			<?php endfor; else: ?>
				<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new friend requests.</li>
			<?php endif; ?>	
			</ul>
			<ul class="notification-links">
				<li><a class="button" href="<?php echo $link . '/friends/'; ?>" title="View your friends list"><i class="icon-user"></i>Your Friends</a></li>
				<li><a class="button" href="<?php echo $link . '/activity/friends'; ?>" title="Recent activity by your friends"><i class="icon-gears"></i>Friend Activity</a></li>
			</ul>					
		</div>
	</li>
	
	<li id="notifications-groups" class="notification-type">
	<?php if ( !empty( $notifications['groups'] ) ) :?>
		<span class="notifications-number"><?php echo count( $notifications['groups'] ); ?></span>
	<?php endif; ?>		
		<div class="admin-bar-dropdown">
			<ul class="notification-list icons-ul">
			<?php if ( !empty( $notifications['groups'] ) ) : for( $i = 0; $i < count( $notifications['groups'] ); $i++ ) : ?>
				<li id="notification-<?php echo $notifications['groups'][$i]->id; ?>" class="notification-entry"><i class="icon-li icon-chevron-right"></i>
					<a class="clear-notification" data-type="<?php echo $notifications['groups'][$i]->component_action; ?>" data-id="<?php echo $notifications['groups'][$i]->id; ?>"><i class="icon-remove"></i></a>
					<?php echo $notifications['groups'][$i]->desc; ?>
				</li>
			<?php endfor; else: ?>
				<li class="notification-entry"><i class="icon-li icon-chevron-right"></i>You have no new group notifications.</li>
			<?php endif; ?>	
			</ul>
			<ul class="notification-links">
				<li><a class="button" href="<?php echo SITEURL . '/groups/'; ?>" title="View the sitewide guild listing"><i class="icon-globe"></i>Guilds</a></li>
				<li><a class="button" href="<?php echo $link . '/groups/'; ?>" title="View your groups listing"><i class="icon-group"></i>Your Guilds</a></li>
				<li><a class="button" href="<?php echo $link . '/activity/groups/'; ?>" title="View recent activity within your groups"><i class="icon-gears"></i>Guild Feed</a></li>
			</ul>					
		</div>
	</li>
</ul>