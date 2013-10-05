<?php 
/**
 * Apocrypha Theme Class Page Template
 * Andrew Clayton
 * Template Name: Class Page
 * Version 1.0.0
 * 9-30-2013
 */

// Determine context 
$apoc = apocrypha();
$context = $apoc->queried_object->post_name;
$classname	= ucfirst( $context );
?>
 
<?php get_header(); ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="<?php apoc_entry_class(); ?>">
		
			<header class="entry-header <?php post_header_class(); ?>">
				<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
				<p class="entry-byline"><?php entry_header_description(); ?></p>
			</header>
			
			<ul class="tabs js">
				<li class="current"><a href="#description">Description</a></li>
				<li><a href="#skills">Class Skills</a></li>
				<li><a href="#resources">Resources</a></li>
			</ul>
			
			<div id="description" class="tab-content active entry-content">
				<h2><?php echo $classname; ?> Class Description</h2>
				<?php the_content(); ?>
			</div>
			
			<div id="skills" class="tab-content entry-content">
				<?php locate_template( array( 'pages/classes/' . $context . '.php' ), true ); ?>
			</div>
			
			<div id="resources" class="tab-content entry-content">
				<h2>Useful <?php echo $classname; ?> Resources</h2>
				<ul id="class-resources">
					<li><a href="<?php echo SITEURL . '/category/' . $context; ?>" title="Read articles tagged as <?php echo $classname; ?>" target="_blank"><?php echo $classname; ?> Articles</a></li>
					<li><a href="<?php echo SITEURL . '/classes/' . $context; ?>" title="Visit the class forum" target="_blank"><?php echo $classname; ?> Class Forum</a></li>
				</ul>
			</div>

		</div><!-- #post-<?php the_ID(); ?> -->
		<?php endwhile; endif; ?>
		
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>