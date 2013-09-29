<?php 
/**
 * Apocrypha Theme Guild Activity Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<li class="current"><span>Guild Activity</span></li>
	</ul>
	<div id="activity-filter-select" class="filter">
		<select id="activity-filter-by">
		<option value="-1">All Activity</option>
		<option value="activity_update">Status Updates</option>
		<option value="joined_group">Guild Memberships</option>
		</select>
	</div>
</nav><!-- #subnav -->

<div id="activity-directory" class="activity" role="main">
	<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>
</div><!-- #activity-directory -->