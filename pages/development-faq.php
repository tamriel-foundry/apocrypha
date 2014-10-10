<?php 
/**
 * Apocrypha Theme Development FAQ Template
 * Template Name: Development FAQ
 * Andrew Clayton
 * Version 1.0
 * 10-8-2013
 */
?>

<?php get_header(); // Load the header ?>
	
	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div id="dev-faq" class="<?php apoc_entry_class(); ?>">
		
			<header class="entry-header <?php post_header_class(); ?>">
				<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
				<p class="entry-byline"><?php entry_header_description(); ?></p>
			</header>
				
			<?php // Get the FAQ last modified time
			$time = filemtime( THEME_DIR . '/pages/faq/references.php' );
			$faq_date = date( 'F j, Y' , $time );
			?>
			
			<p><em>The Tamriel Foundry development FAQ was last updated on <?php echo $faq_date; ?>. If you notice any inacurracies or missing information, please send a message to <a href="http://tamrielfoundry.com/members/atropos/" title="Visit Atropos' User Profile">@Atropos</a>.</em></p>
			<div id="faq-contents">
				<ul class="faq-tabs tabs" id="tabs">
					<li class="current"><a href="#overview">Overview</a></li>
					<li><a href="#setting">Setting</a></li>
					<li><a href="#alliances">Alliances</a></li>
					<li><a href="#gameplay">Gameplay</a></li>
					<li><a href="#classes">Classes</a></li>
					<li><a href="#pve">PvE</a></li>
					<li><a href="#pvp">PvP</a></li>
					<li><a href="#social">Social</a></li>
					<li><a href="#misc">Misc.</a></li>
				</ul>
			</div>
			
			<?php locate_template( array( 'pages/faq/overview.php' ), true ); ?>
			<?php locate_template( array( 'pages/faq/setting.php' ), true ); ?>
			<?php locate_template( array( 'pages/faq/alliances.php' ), true ); ?>
			<?php locate_template( array( 'pages/faq/gameplay.php' ), true ); ?>
			<?php locate_template( array( 'pages/faq/classes.php' ), true ); ?>
			<?php locate_template( array( 'pages/faq/pve.php' ), true ); ?>
			<?php locate_template( array( 'pages/faq/pvp.php' ), true ); ?>
			<?php locate_template( array( 'pages/faq/social.php' ), true ); ?>
			<?php locate_template( array( 'pages/faq/misc.php' ), true ); ?>
			<?php locate_template( array( 'pages/faq/references.php' ), true ); ?>
		
		</div><!-- #dev-faq> -->
		<?php endwhile; endif; ?>
				
	</div><!-- #content -->
	
<?php get_footer(); // Load the footer ?>