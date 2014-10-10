<?php 
/**
 * Apocrypha Theme 404 Page
 * Andrew Clayton
 * Version 1.0.0
 * 10-11-2013
 */
?>

<?php get_header(); ?>
	
	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header class="entry-header <?php post_header_class(); ?>">
			<h1 class="entry-title">Error - Page Not Found</h1>
			<p class="entry-byline">Sorry, but this page does not exist, or is not accessible at this time.</p>
		</header>
		
		<div id="error-404">
			<p id="tagline-404">You have ventured into an uninhabitable land; but all is not lost. You may still search for what you seek!</p>
			
			<form role="search" method="get" class="search-form" id="search-404" action="<?php echo SITEURL . '/advsearch/'; ?>">
				<select name="type" id="search-for">
					<option value="posts">Articles</option>
					<option value="pages">Pages</option>
					<option value="topics">Topics</option>
					<option value="members">Members</option>
					<option value="groups">Guilds</option>
				</select>
				<input class="search-text" type="text" name="s" value="Search Tamriel Foundry" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
		</div>
		
	</div><!-- #content -->
	
<?php get_footer(); // Load the footer ?>