<?php 
/** 
 * Apocrypha Primary Navigation Menu
 * Andrew Clayton
 * Version 1.0.0
 * 8-2-2012
 */

// Get some info on the current user
$user_id 	= apocrypha()->user->ID;
$loggedin	= ( $user_id > 0 ) ? true : false;
?>

<div id="menu-container">
	<ul id="top-menu">
		<li id="home" class="top nodrop"><a href="<?php echo SITEURL; ?>">Home</a></li>
	
		<li id="gameinfo" class="top drop"><a href="#">Game Info<i class="drop-icon icon-angle-down"></i></a>
			<div class="dropdown col4">
				<div class="sub col">
					<h3>Factions</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/groups/aldmeri-dominion/' ?>">Aldmeri Dominion</a></li>
						<li><a href="<?php echo SITEURL . '/groups/daggerfall-covenant/' ?>">Daggerfall Covenant</a></li>
						<li><a href="<?php echo SITEURL . '/groups/ebonheart-pact/' ?>">Ebonheart Pact</a></li>
						<?php /*
						<li><a href="#">Racial Comparison</a></li>
						*/ ?>
					</ul>
				</div>
				<div class="sub col">
					<h3>Classes</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/dragonknight/' ?>">Dragonknight</a></li>
						<li><a href="<?php echo SITEURL . '/templar/' ?>">Templar</a></li>
						<li><a href="<?php echo SITEURL . '/sorcerer/' ?>">Sorcerer</a></li>
						<li><a href="<?php echo SITEURL . '/nightblade/' ?>">Nightblade</a></li>
					</ul>
				</div>
				<div class="sub col">
					<h3>Skill Lines</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/races/' ?>">Racial Bonuses</a></li>
						<li><a href="<?php echo SITEURL . '/weapons/' ?>">Weapon Skills</a></li>
						<li><a href="<?php echo SITEURL . '/armors/' ?>">Armor Types</a></li>
						<?php /*
						<li><a href="#">NPC Guilds</a></li>
						<li><a href="#">Other Skills</a></li>
						*/ ?>
					</ul>
				</div>	
				<div class="sub col">
					<h3>Resources</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/development-faq/' ?>">Development FAQ</a></li>
						<li><a href="<?php echo SITEURL . '/map/' ?>">Interactive Map</a></li>
						<?php /*
						<li><a href="#">Guides</a></li>
						<li><a href="#">Maps</a></li>
						<li><a href="#">Utilities</a></li>
						*/ ?>
					</ul>
				</div>		
			</div>
		</li>	

		<li id="community" class="top drop"><a href="#">Community<i class="drop-icon icon-angle-down"></i></a>
			
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
						<li><a href="<?php echo SITEURL . '/advsearch/' ?>">Advanced Search</a></li>
					</ul>
				</div>
				
				<div class="sub col">
					<h3>The Foundry Team</h3>
					<ul class="submenu">
						<li><a href="<?php echo SITEURL . '/about-us/' ?>">About Tamriel Foundry</a></li>
						<li><a href="<?php echo SITEURL . '/contact-us/' ?>">Contact Us</a></li>
						<li><a href="<?php echo SITEURL . '/entropy-rising/' ?>">Entropy Rising</a></li>
						<?php /*
						<li><a href="#">Partnerships</a></li>
						*/ ?>
					</ul>
				</div>
			
				<?php if ( $loggedin ) : ?>
				<div class="sub col">
					<h3>Your Account</h3>
					<ul class="submenu">						
						<li><a href="<?php echo bp_loggedin_user_link(); ?>">Your Profile</a></li>
						<li><a href="<?php echo bp_loggedin_user_link() . 'profile/edit/' ?>">Edit Profile</a></li>
						<li><a href="<?php echo bp_loggedin_user_link() . 'messages/' ?>">Private Messages</a></li>
						<li><a href="<?php echo bp_loggedin_user_link() . 'settings/' ?>">Account Settings</a></li>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</li>	
		
		<li id="menu-forums" class="top drop right"><a href="<?php echo SITEURL . '/forums/' ?>">Forums<i class="drop-icon icon-angle-down"></i></a>
			<div class="dropdown col1">
				<div class="sub col">
					<ul class="submenu noheader">
						<li><a href="<?php echo SITEURL . '/forums/' ?>">Forums Home</a></li>
						<li><a href="<?php echo SITEURL . '/topics/' ?>">Recent Topics</a></li>
						<li><a href="<?php echo SITEURL . '/best-of/' ?>">Best Weekly Topics</a></li>
					<?php if ( $loggedin ) : ?>
						<li><a href="<?php echo bp_loggedin_user_link() . 'forums/subscriptions/' ?>">Your Subscribed Topics</a></li>
					<?php endif; ?>
					</ul>
				</div>	
			</div>
		</li>
		
	</ul>
</div><!-- #menu-container -->
