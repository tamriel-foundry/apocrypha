<?php 
/** 
 * Apocrypha Theme Primary Sidebar Template
 * Andrew Clayton
 * Version 1.0.0
 * 10-9-2013
 */ 
?>

<div id="primary-sidebar" class="sidebar">
	<div class="welcome-text">Welcome to Tamriel Foundry - Your home for theorycrafting, strategies, discussion, and guides for The Elder Scrolls Online.</div>
	<div class="social-media-widget widget">
		<header class="widget-header"><h3 class="widget-title">Get Connected</h3></header>
		<div class="social-icons">
			<a class="social-icon youtube" href="http://youtube.com/tamrielfoundry" title="Check out our YouTube videos." target="_blank"></a>
			<a class="social-icon twitter" href="http://twitter.com/tamrielfoundry" title="Follow us on Twitter." target="_blank"></a>
			<a class="social-icon facebook" href="http://facebook.com/tamrielfoundry" title="Follow us on Facebook." target="_blank"></a>
			<a class="social-icon feed" href="<?php echo SITEURL; ?>/feed/" title="Subscribe to RSS Feed." target="_blank"></a>
		</div>
		<?php if ( is_user_logged_in() ) : ?><p class="tf-teamspeak">TF Teamspeak - ts.tamrielfoundry.com</p><?php endif; ?>
	</div>
	<?php community_online_widget(); ?>
	<?php // twitch_streams_widget(); ?>
	<?php paypal_donate_box(); ?>
	<?php featured_guild_box(); ?>
	<?php community_stat_counter(); ?>
</div><!-- #primary-sidebar -->