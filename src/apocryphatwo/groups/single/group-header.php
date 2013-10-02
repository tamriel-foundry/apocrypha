<?php 
/**
 * Apocrypha Theme Group Profile Header
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
 
// Retrieve the group info block
global $guild;
?>


<header id="profile-header" class="entry-header <?php echo $guild->alliance; ?>">
	<h1 id="profile-title" class="entry-title">Guild Profile - <?php echo $guild->fullname; ?></h1>
	<p class="entry-byline <?php echo $guild->faction; ?>"><?php echo $guild->byline; ?></p>		
	<div id="profile-actions">
		<span id="guild-activity" class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span>
		<?php do_action( 'bp_group_header_actions' ); ?>
	</div>
</header><!-- #profile-header -->

<div id="profile-sidebar" role="complementary">
	<div id="profile-user" class="user-block">
		<?php echo $guild->block; ?>	
	</div>
</div><!-- #profile-sidebar -->

<div id="profile-content" class="group-profile-content">
	<nav id="directory-nav" class="no-ajax">
		<ul id="directory-actions" role="navigation">
			<?php bp_get_options_nav(); ?>
		</ul>
	</nav>
	
	<div id="profile-widgets" class="guild-profile-widgets">
		<div id="group-administrators" class="widget profile-widget guild-leaders">
			<?php $header = ( $guild->guild == 1 ) ? 'Guild Leaders' : 'Group Admins'; ?>
			<header class="widget-header">
				<h3 class="widget-title"><?php echo $header; ?></h3>
			</header>
			<?php echo $guild->admins; ?>
		</div>
			
		<?php if (bp_group_has_moderators() ) : ?>
		<div id="group-moderators" class="widget profile-widget guild-leaders">
			<?php $header = ( $guild->guild == 1 ) ? 'Guild Officers' : 'Group Moderators'; ?>
			<header class="widget-header">
				<h3 class="widget-title"><?php echo $header; ?></h3>
			</header>
			<?php echo $guild->mods; ?>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'template_notices' ); ?>