<?php 
/** 
 * Apocrypha Theme Primary Sidebar Template
 * Andrew Clayton
 * Version 0.1
 * 1-19-2012
 */ 
?>

<div id="primary-sidebar" class="sidebar">
	<div class="welcome-text">Welcome to Tamriel Foundry - Your home for theorycrafting, strategies, discussion, and guides for The Elder Scrolls Online.</div>
	<div class="social-media-widget widget">
		<header class="widget-header"><h3 class="widget-title">Get Connected</h3></header>
		<div class="social-icons">
			<a class="social-icon facebook" href="http://facebook.com/tamrielfoundry" title="Follow us on Facebook." target="_blank"></a>
			<a class="social-icon feed" href="http://tamrielfoundry.com/feed/" title="Subscribe to RSS Feed." target="_blank"></a>
			<a class="social-icon youtube" href="http://youtube.com/tamrielfoundry" title="Check out our YouTube videos." target="_blank"></a>
			<a class="social-icon twitter" href="http://twitter.com/tamrielfoundry" title="Follow us on Twitter." target="_blank"></a>
		</div>
	</div>
	<?php community_online_widget(); ?>
	<?php featured_guild_box(); ?>
	<?php community_stat_counter(); ?>
</div><!-- #primary-sidebar -->