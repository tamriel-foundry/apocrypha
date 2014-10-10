<?php 
/**
 * Apocrypha Theme Entropy Rising Guild Application Template
 * Template Name: Entropy Rising Application
 * Andrew Clayton
 * Version 1.2
 * 10-10-2014
 */
?>

<?php entropy_rising_header(); // Load the header ?>

	<div id="content" class="no-sidebar" role="main">
		
		<div id="showcase-container" class="er-application-showcase">
			<div id="showcase">
				<div id="recruitment-video">
				<header class="widget-header"><h3 class="widget-title">Recruitment Video</h3></header>
					<?php $autoplay = !isset($_POST['submitted']) ? 'autoplay=0' : '';
					$source = 'http://www.youtube.com/embed/yXW3E8pBw4M';
					$options = '?' . $autoplay . '&vq=hd720'; ?>
					<iframe width="640" height="360" src="<?php echo $source . $options; ?>" frameborder="0" allowfullscreen></iframe>
				</div>
			</div><!-- #showcase --> 
			
			<div id="showcase-sidebar">
				<?php apoc_breadcrumbs(); ?>
				<div class="guild-status widget">				
					<header class="widget-header"><h3 class="widget-title">Recruitment Status</h3></header>
					<div class="instructions">
					<?php if ( guild_recruitment_status() == 'closed' ) : ?>
						<h4>Recruitment Status:<span class="guild-status-entry closed">Closed</span></h4>
						<p class="recruitment-message">Thank you for your interest in Entropy Rising. Unfortunately, we are not accepting further applications at this time. Guild recruitment is invite-only until further notice. Please continue to check back with us for further openings.</p>		
					<?php else : ?>
						<h4>Recruitment Status:<span class="guild-status-entry open">Selective</span></h4>
						<p class="recruitment-message">Thank you for your interest in Entropy Rising. We are currently looking for exceptional individuals to join our team. Before applying, please read our charter for details regarding our structure, recruitment objectives, and member requirements.</p>		
					<?php endif; ?>
					</div>
				</div>
				<div class="guild-status widget">	
					<header class="widget-header"><h3 class="widget-title">Class Priorities</h3></header>
					<ul class="guild-status-list">
						<?php foreach ( get_class_recruitment_status() as $class => $status ) {
						echo '<li class="guild-status-item">' . ucfirst($class) . ':<span class="guild-status-entry ' . $status . '">' . ucfirst($status) . '</span></li>';
						} ?>
					</ul>	
				</div>

			</div><!-- #showcase-sidebar --> 
		</div><!-- #showcase-container -->
		
		<div id="er-application">
			<header id="home-posts-header" class="double-border top">
				<h1 id="home-posts-title">Application Form</h1>
			</header>
			
			<?php if ( is_user_logged_in() && guild_recruitment_status() != 'closed' ) : ?> 
				<?php locate_template( array( 'guild/application-form.php' ), true ); ?>
			<?php elseif ( guild_recruitment_status() == 'closed' ) : ?>
				<div class="error">Entropy Rising guild recruitment is CLOSED at this time. We are not planning to add any further members for the next several weeks. If you are interested in joining and have exceptional MMO experience both in <em>ESO</em> as well as past games please check back periodically for any recruitment openings that we may have. Thank you for your interest!</div>
			<?php else : ?>
				<div class="warning">You must be a registered member of Tamriel Foundry in order to apply to join Entropy Rising.</div>
			<?php endif; ?>				
		</div><!-- #er-application -->
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>