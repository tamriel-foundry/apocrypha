<?php 
/** 
 * Apocrypha Theme Entropy Rising Menu
 * Andrew Clayton
 * Version 1.0.0
 * 10-9-2013
 */
?>

<div id="menu-container">
	<ul id="top-menu">
		<li id="home" class="top nodrop"><a href="<?php echo SITEURL; ?>">Home</a></li>		
		
		<li id="er-home" class="top nodrop"><a href="<?php echo SITEURL; ?>/entropy-rising/">Guild Home</a></li>
		
		<li id="er-recruitment" class="top drop right"><a href="<?php echo SITEURL; ?>/entropy-rising/application/">Recruitment<i class="drop-icon icon-angle-down"></i></a>
			<div class="dropdown col1">
				<div class="sub col">
					<ul class="submenu noheader">
						<li><a href="<?php echo SITEURL; ?>/entropy-rising/roster/">Roster</a></li>
						<li><a href="<?php echo SITEURL; ?>/entropy-rising/charter/">Guild Charter</a></li>
						<li><a href="<?php echo SITEURL; ?>/entropy-rising/application/">Application Form</a></li>
					</ul>
				</div>	
			</div>
		</li>
		
		<li id="er-private" class="top drop right"><a href="<?php echo SITEURL; ?>/groups/entropy-rising/forum/">Sanctum<i class="drop-icon icon-angle-down"></i></a>
			<div class="dropdown col1">
				<div class="sub col">
					<ul class="submenu noheader">
						<li><a href="<?php echo SITEURL; ?>/groups/entropy-rising/activity/">Guild Activity</a></li>
						<li><a href="<?php echo SITEURL; ?>/calendar/entropy-rising/">Calendar</a></li>
						<li><a href="#">DKP Tracker</a></li>
						<li><a href="<?php echo SITEURL; ?>/groups/entropy-rising/forum/">Guild Forum</a></li>
						<li><a href="<?php echo SITEURL; ?>/forums/">Main Forums</a></li>
					</ul>
				</div>
			</div>
		</li>
	</ul>
</div>