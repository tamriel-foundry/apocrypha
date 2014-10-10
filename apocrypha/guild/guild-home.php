<?php 
/** 
 * Apocrypha Theme Entropy Rising Guild Homepage
 * Template Name: Entropy Rising Home
 * Andrew Clayton
 * Version 1.0.0
 * 10-9-2013
 */
 
// Load up the ER group
$group = groups_get_group( array( 'group_id' => 1 , 'populate_extras' => true ) ); ?>

<?php entropy_rising_header(); ?>

	<div id="showcase-container">
		<div id="showcase">
			<?php get_slideshow( $slideshow = 'er-showcase' , $number = 5 ); ?>
		</div><!-- #showcase --> 
		
		<div id="showcase-sidebar">
			<?php apoc_breadcrumbs(); ?>
			
			<div id="er-welcome" class="welcome-text widget">
				<span class="er-name">Entropy Rising</span> is a competitive progression guild, and the official arm of Tamriel Foundry on the PC megaserver. We pride ourselves on unrivaled excellence in PvE, RvR, and community involvement.
			</div>
			
			<div class="guild-status widget">
				<header class="widget-header"><h3 class="widget-title">Guild Status</h3></header>
				<ul class="guild-status-list">
					<li class="guild-status-item"><span class="er-name">Alliance:</span><span class="guild-status-entry aldmeri">Aldmeri Dominion</span></li>
					<li class="guild-status-item"><span class="er-name">Region/Platform:</span><span class="guild-status-entry">North America - PC</li>
					<li class="guild-status-item"><span class="er-name">Playstyle:</span><span class="guild-status-entry">Hardcore PvE/PvP</li>
					<li class="guild-status-item"><span class="er-name">Members:</span><span class="guild-status-entry"><?php echo $group->total_member_count; ?></li>
					<li class="guild-status-item recruitment-status-item"><span class="er-name">Recruitment:</span>
						<?php if ( guild_recruitment_status() == 'closed' ) : ?>
						<span class="guild-status-entry closed">Closed</span>
						<div class="recruitment-status-popup error">
							We are not currently soliciting further applications, recruitment is conducted on an invite-only basis until further notice. You may still apply if you wish, and we will hold your application until recruitment is reopened.
						</div>
						<?php else : ?>
						<span class="guild-status-entry open">Selective</span>
						<div class="recruitment-status-popup warning">
							We are currently seeking members of exceptional quality, if you think you would be a good fit for Entropy Rising, we encourage you to read our guild charter and visit our recruitment page for more details.
						</div>
						<?php endif; ?>
					</li>
				</ul>	
			</div>
		</div><!-- #showcase-sidebar --> 
	</div><!-- #showcase-container -->
	
	<div id="content" role="main">
	
		<header id="home-posts-header" class="double-border top">
			<h1 id="home-posts-title">Entropy Rising News</h1>
		</header>
		
		<div id="posts">	
			<?php entropy_rising_have_posts(); ?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php apoc_display_post(); ?>
			<?php endwhile; endif; ?>
			
			<nav class="pagination ajaxed" data-type="erhome">
				<?php apoc_pagination( array() , $baseurl = SITEURL . '/entropy-rising' ); ?>
			</nav>
		</div>
		
	</div><!-- #content -->
	<?php entropy_rising_sidebar(); ?>
<?php get_footer(); ?>