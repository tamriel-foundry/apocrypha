<?php
/**
 * Apocrypha Slideshow Template
 * Andrew Clayton
 * Version 1.0.0
 * 8-1-2013
 */

// In case we are already inside a post, save the $post global so it doesn't get overwritten 
global $post;
$temporary_post = $post;
$slide_count = 1;
 
// Use the specified $slideshow and $number to query slides 
$slide_loop = new WP_Query( array(
	'post_type'			=> 'slide',
	'slideshow'			=> $slideshow,
	'posts_per_page'	=> $number,
	) );
	
// Find some slides! 
if ( $slide_loop->have_posts() ) : 

	// If we have slides, let's pull all the data we need at once, then build the slideshow 
	$slides = array();
	$total_slides = $slide_loop->found_posts;
	while ( $slide_loop->have_posts() ) : $slide_loop->the_post();
		
		$slides[] = array(
			'number'		=> $slide_count,
			'title' 		=> $post->post_title,
			'tab'			=> get_post_meta( $post->ID , 'TabTitle' , $single = true ),
			'link'			=> get_post_meta( $post->ID , 'Permalink' , $single = true ),
			'content'		=> $post->post_content,
			'image'			=> get_the_post_thumbnail( $post->ID, 'featured-slide' ),
		);
		$slide_count++;
	endwhile; 
	
	// Ok, now lets build the slideshow html  ?>
	<div id="<?php echo $slideshow . '-slider'; ?>" class="flexslider">
		
		<ol class="slideshow-tabs">
		<?php for($i = 0; $i < $total_slides; $i++) : ?>
			<li class="slideshow-tab" style="width:<?php echo round(100/$total_slides , 3 ); ?>%;">
				<span class="slideshow-tab-title"><?php echo $slides[$i]['tab']; ?></span>
			</li>
		<?php endfor; ?>
		</ol>
			
		<ul class="slides">
		<?php for($i = 0; $i < $total_slides; $i++) : ?>
			<li class="slideshow-slide">
				<a href="<?php echo $slides[$i]['link']; ?>" title="<?php echo $slides[$i]['title']; ?>" target="_blank" ><?php echo $slides[$i]['image']; ?></a>
				<div class="flex-caption">
					<h2 class="slide-title"><?php echo $slides[$i]['title']; ?></h2>
					<p class="slide-content double-border top">
						<?php echo $slides[$i]['content']; ?>
						<a href="<?php echo $slides[$i]['link']; ?>" title="<?php echo $slides[$i]['title']; ?>" target="_blank" >[...]</a>
					</p>
				</div>	
			</li>
		<?php endfor; ?>
		</ul>
		
	</div><!-- <?php echo $slideshow . '-slider'; ?> -->
	
<?php // Reset the post data back to before the slideshow 
	$post = $temporary_post;	
	wp_reset_postdata();
endif; ?>