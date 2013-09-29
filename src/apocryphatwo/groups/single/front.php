<?php 
/**
 * Apocrypha Theme Guild Front Page
 * Andrew Clayton
 * Version 1.0.0
 * 9-28-2013
 */
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<li class="current"><span>Guild Description</span></li>
	</ul>
</nav><!-- #subnav -->

<div id="user-profile" role="main">

	<div id="user-biography">
		<?php bp_group_description(); ?>
	</div>

</div><!-- #user-profile -->