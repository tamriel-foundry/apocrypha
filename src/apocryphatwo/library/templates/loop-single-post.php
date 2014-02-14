<?php 
/**
 * Apocrypha Loop Single Post Template
 * Andrew Clayton
 * Version 1.0.1
 * 2-6-2013
 */
?>
	
<div id="post-<?php the_ID(); ?>" class="<?php apoc_entry_class(); ?>">
	
	<header class="entry-header <?php home_header_class(); ?>">
		<h2 class="entry-title"><?php entry_header_title(); ?></h2>
		<p class="entry-byline"><?php entry_header_description(); ?></p>
	</header>
	
	<div class="entry-content">
		<?php apoc_thumbnail(); ?>
		<div class="entry-excerpt">
			<?php the_excerpt(); ?>
		</div>
	</div>
	
	<footer class="entry-footer">
	<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<i class="icon-tags"></i>
			<?php echo get_the_term_list( get_the_ID() , 'category', 'Posted In: ', ', ', '' ); ?> 
		</div>
		<?php apoc_comments_link(); ?>
	<?php endif; ?>
	</footer>
</div>