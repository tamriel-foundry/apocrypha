<?php 
/** 
 * Apocrypha Theme Footer Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-4-2012
 */ 
?>

	</div><!-- #main-container -->

	<div id="footer-container">
		<div id="footer-divider">
			<div id="footer-anvil">
				<a id="anvil-link" class="backtotop" href="#top"></a>
			</div>
		</div><!-- #footer-divider -->
		
		<nav id="footer-navigation">
			<a class="footer-nav-item" href="<?php echo SITEURL . '/about-us/'; ?>" title="Learn more about Tamriel Foundry">
				<img id="footer-about" class="footer-nav-image" src="<?php echo THEME_URI; ?>/images/backgrounds/about-us.png" alt="About Tamriel Foundry"/>
				<h3 class="double-border top">About Us</h3>
			</a>
			<a class="footer-nav-item" href="<?php echo SITEURL . '/activity/'; ?>" title="Browse recent Tamriel Foundry activity">
				<img id="footer-guides" class="footer-nav-image" src="<?php echo THEME_URI; ?>/images/backgrounds/guides.png" alt="Elder Scrolls Online Guides"/>
				<h3 class="double-border top">Activity</h3>
			</a>
			<a class="footer-nav-item" href="<?php echo SITEURL . '/forums/'; ?>" title="Browse the forums">
				<img id="footer-forums" class="footer-nav-image" src="<?php echo THEME_URI; ?>/images/backgrounds/forums.png" alt="Tamriel Foundry Forums"/>
				<h3 class="double-border top">Forums</h3>

			</a>
			<a class="footer-nav-item" href="<?php echo SITEURL . '/groups/' ?>" title="Browse groups and guilds">
				<img id="footer-guilds" class="footer-nav-image" src="<?php echo THEME_URI; ?>/images/backgrounds/guilds.png" alt="Guilds and Groups Directory"/>
				<h3 class="double-border top">Guilds</h3>
			</a>
		</nav><!-- #footer-navigation -->
		
		<div id="footer">
			<p>Copyright &copy; 2013 <a href="<?php echo SITEURL; ?>" title="Tamriel Foundry" rel="home">Tamriel Foundry</a>. Questions, comments, or concerns? <a href="<?php echo SITEURL; ?>/contact-us/" title="Contact Tamriel Foundry">Contact Us</a>.<br>Tamriel Foundry was created using content and materials from <a href="http://elderscrollsonline.com/" title="Official Game Website" target="_blank">The Elder Scrolls Online</a> &copy; ZeniMax Online Studios, LLC or its licensors.</p>
		</div><!-- #footer -->	
	</div><!-- #footer-container -->

<?php wp_footer(); ?>
<!-- <?php echo get_num_queries(); ?> queries in <?php timer_stop(1); ?> seconds. -->
<!-- <?php echo round ( memory_get_peak_usage() / 1048576 , 2 ) . 'megabytes used.'; ?> -->
</body>
</html>