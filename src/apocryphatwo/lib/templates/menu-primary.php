<?php 
/** 
 * Apocrypha Primary Navigation Menu
 * Andrew Clayton
 * Version 1.0
 * 8-2-2012
 */

// Get some info on the current user
global $apocrypha;
$user_id 	= $apocrypha->user->data->ID;
$loggedin	= ( $user_id > 0 ) ? true : false;
?>

<div id="menu-container">
	<ul id="top-menu">
		<li id="home" class="top nodrop"><a href="<?php echo SITEURL; ?>">Home</a></li>
	
		<li id="gameinfo" class="top drop"><a href="#">Game Info<span class="drop-icon"></span></a>
			<div class="dropdown col4">
				<div class="sub col">
					<h3>Factions</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/groups/aldmeri-dominion/' ?>">Aldmeri Dominion</a></li>
						<li><a href="<?php echo SITEURL . '/groups/daggerfall-covenant/' ?>">Daggerfall Covenant</a></li>
						<li><a href="<?php echo SITEURL . '/groups/ebonheart-pact/' ?>">Ebonheart Pact</a></li>
						<li><a href="#">Racial Comparison</a></li>
					</ul>
				</div>
				<div class="sub col">
					<h3>Classes</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/player-classes/dragonknight' ?>">Dragonknight</a></li>
						<li><a href="<?php echo SITEURL . '/player-classes/templar' ?>">Templar</a></li>
						<li><a href="<?php echo SITEURL . '/player-classes/sorcerer' ?>">Sorcerer</a></li>
						<li><a href="<?php echo SITEURL . '/player-classes/nightblade' ?>">Nightblade</a></li>
					</ul>
				</div>
				<div class="sub col">
					<h3>Skill Lines</h3>
					<ul class="submenu">
						<li><a href="#">Weapon Skills</a></li>
						<li><a href="#">Armor Types</a></li>
						<li><a href="#">NPC Guilds</a></li>
						<li><a href="#">Other Skills</a></li>
					</ul>
				</div>	
				<div class="sub col">
					<h3>Resources</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/development-faq/' ?>">Development FAQ</a></li>
						<li><a href="#">Guides</a></li>
						<li><a href="#">Maps</a></li>
						<li><a href="#">Utilities</a></li>
					</ul>
				</div>		
			</div>
		</li>	

		<li id="community" class="top drop"><a href="#">Community<span class="drop-icon"></span></a>
			
			<?php if ( $loggedin ) : ?>
			<div class="dropdown col3">
			<?php else : ?>
			<div class="dropdown col2">
			<?php endif; ?>
				<div class="sub col">
					<h3>Directories</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/activity/' ?>">Activity Feed</a></li>
						<li><a href="<?php echo SITEURL . '/members/' ?>">Members</a></li>
						<li><a href="<?php echo SITEURL . '/groups/' ?>">Guild Listing</a></li>
					</ul>
				</div>
				
				<div class="sub col">
					<h3>The Foundry Team</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/join-us/' ?>">About Tamriel Foundry</a></li>
						<li><a href="<?php echo SITEURL . '/contact-us/' ?>">Contact Us</a></li>
						<li><a href="<?php echo SITEURL . '/entropy-rising/' ?>">Entropy Rising</a></li>
						<li><a href="#">Partnerships</a></li>
					</ul>
				</div>
			
				<?php if ( $loggedin ) : ?>
				<div class="sub col">
					<h3>Your Account</h3>
					<ul class="submenu">						
						<li><a href="<?php echo bp_loggedinuser_link(); ?>">Your Profile</a></li>
						<li><a href="<?php echo bp_loggedinuser_link() . 'profile/edit/' ?>">Edit Profile</a></li>
						<li><a href="<?php echo bp_loggedinuser_link() . 'messages/' ?>">Private Messages</a></li>
						<li><a href="<?php echo bp_loggedinuser_link() . 'settings/' ?>">Account Settings</a></li>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</li>	
		
		<li id="menu-forums" class="top drop right"><a href="<?php echo SITEURL . '/forums/' ?>">Forums<span class="drop-icon"></span></a>
			<div class="dropdown col1">
				<div class="sub col">
					<ul class="submenu noheader">
						<li><a href="<?php echo SITEURL . '/forums/' ?>">Forums Home</a></li>
						<li><a href="<?php echo SITEURL . '/topics/' ?>">Recent Topics</a></li>
					<?php if ( $loggedin ) : ?>
						<li><a href="<?php echo bp_loggedinuser_link() . '/forums/favorites/' ?>">Favorites</a></li>
						<li><a href="<?php echo bp_loggedinuser_link() . '/forums/subscriptions/' ?>">Subscriptions</a></li>
					<?php endif; ?>
					</ul>
				</div>	
			</div>
		</li>
		
	</ul>
</div><!-- #menu-container -->