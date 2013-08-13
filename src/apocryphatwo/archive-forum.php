<?php 
/**
 * Apocrypha Theme Forum Homepage
 * Andrew Clayton
 * Version 1.0
 * 8-10-2013
 */
?>

<?php get_header(); ?>
	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header id="forum-header" class="entry-header <?php page_header_class(); ?>">
			<h1 class="entry-title"><?php echo SITENAME; ?> Forums</h1>
			<p class="entry-byline">Discussion forums for The Elder Scrolls Online with a specific focus on development news, game mechanics, and theorycrafting.</p>
		</header>		
		
		<div id="forums">
			<?php do_action( 'bbp_template_notices' ); ?>
			
			<?php if ( bbp_has_forums() ) : while ( bbp_forums() ) : bbp_the_forum(); ?>
				<?php bbp_get_template_part( 'loop', 'single-forum' ); ?>
			<?php endwhile;	else : ?>
				<p class="notice warning">Sorry, but no forums were found here.</p>
			<?php endif; ?>	

		</div><!-- #forums -->	
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>