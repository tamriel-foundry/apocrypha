<?php 
/**
 * Apocrypha Theme Calendar Archive Template
 * Andrew Clayton
 * Version 1.0
 * 7-25-2013
 */
 
/* Who is it? */
$user_id 	= get_current_user_id();

/* Get some information about the calendar */
$apoc 		= apocrypha();
$calendar 	= $apoc->queried_object;
$term_id 	= $calendar->term_id;
$slug 		= $calendar->slug;

/* Does it belong to a specific group? */
$is_group	= is_group_calendar( $term_id );

/* Set the default context */
$header 	= 'get_header';
$sidebar 	= 'apoc_primary_sidebar';

/* If it's a group calendar, switch the header accordingly */
if ( $is_group ) :
	
	/* Check if the user is a member */ 
	$group_id	= groups_get_id( $slug );
	$is_member 	= groups_is_user_member( $user_id , $group_id );
		
	if ( 'entropy-rising' == $slug ) :
		$header 	= 'entropy_rising_header';
		$sidebar 	= 'entropy_rising_sidebar';
		$redirect	= SITEURL . '/entropy-rising/';
	else :
		$redirect	= SITEURL . '/groups/' . trailingslashit( $slug );
	endif;
	
	/* If not a member, redirect */
	if ( !$is_member ) {
		bp_core_add_message( 'You cannot access this calendar.'	, 'error' );
		bp_core_redirect( $redirect );
	}
endif;

$header(); // Load the header contextually ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header class="entry-header <?php post_header_class(); ?>">
			<h1 class="entry-title"><?php echo $calendar->name; ?> Calendar</h1>
			<p class="entry-byline"><?php echo $calendar->description; ?></p>
		</header>
			
		<h2 class="calendar-header">Upcoming Events</h2>

		<?php $upcoming_events = apoc_calendar_upcoming_events( $slug );
			
		/* If there are upcoming events */
		if ( $upcoming_events->have_posts() ) : 
			echo '<ol id="calendar-upcoming-list" class="event-list">';	
			while( $upcoming_events->have_posts() ) : $upcoming_events->the_post();
				include( THEME_DIR . '/library/templates/loop-single-event.php' );		
			endwhile; 
			echo '</ol>';

		/* If there are no events */	
		else :
			echo '<p class="no-results">No upcoming events found.</p>';
		endif;

		/* Now check for past events */ ?>
		<h2 class="calendar-header">Past Events</h2>
		<?php wp_reset_query();
		$past_events = apoc_calendar_past_events( $slug, $number );

		/* If there are past events */
		if ( $past_events->have_posts() ) : 
			echo '<ol id="calendar-past-list" class="event-list">';	
			while( $past_events->have_posts() ) : $past_events->the_post();
				include( THEME_DIR . '/library/templates/loop-single-event.php' );			
			endwhile; 
			echo '</ol>';


		/* If there are no events */	
		else :
			echo '<p class="no-results">No past events found.</p>';
		endif;  ?>
				
	</div><!-- #content -->
	
	<?php $sidebar(); // Load the sidebar contextually ?>
<?php get_footer(); // Load the footer ?>

