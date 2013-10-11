<?php 
/** 
 * Apocrypha Theme Entropy Rising Sidebar
 * Andrew Clayton
 * Version 1.0.0
 * 10-9-2013
 */ 
?>

<div id="primary-sidebar" class="sidebar">
	<div class="welcome-text">Follow what Entropy Rising members are doing within the ESO community!</div>
	<div class="social-media-widget widget">
		<header class="widget-header"><h3 class="widget-title">Get Connected</h3></header>
		<div class="social-icons">
			<a class="social-icon youtube" href="http://youtube.com/tamrielfoundry" title="Check out our YouTube videos." target="_blank"></a>
			<a class="social-icon twitter" href="http://twitter.com/tamrielfoundry" title="Follow us on Twitter." target="_blank"></a>
			<a class="social-icon facebook" href="http://facebook.com/tamrielfoundry" title="Follow us on Facebook." target="_blank"></a>
			<a class="social-icon feed" href="<?php echo SITEURL; ?>/feed/" title="Subscribe to RSS Feed." target="_blank"></a>
		</div>
	</div>
	
	<div class="er-streams-widget widget">
		<header class="widget-header"><h3 class="widget-title">ER Twitch Streams</h3></header>
		<div class="er-streams">
			<?php guild_twitch_streams(); ?>
		</div>
	</div>

</div><!-- #primary-sidebar -->