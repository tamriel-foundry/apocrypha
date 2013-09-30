<?php 
/**
 * Apocrypha Theme Guild Submission Form
 * Andrew Clayton
 * Template Name: Submit Guild
 * Version 1.0.0
 * 9-30-2013
 */
 
// Set guild submission status
$enabled = true;
$minposts = 25;

// Get the current user
$user_id = get_current_user_id();
if ( $user_id > 0 )
	$user = new Apoc_User( $user_id , 'reply' );

// Process the form if it was submitted
if( 0 < $user_id && isset( $_POST['submitted'] ) ) {

	// validate
	
	// send email
}
?>

<?php get_header(); ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="<?php apoc_entry_class(); ?>">
		
			<header class="entry-header <?php post_header_class(); ?>">
				<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
				<p class="entry-byline"><?php entry_header_description(); ?></p>
			</header>
			
			<div class="entry-content">
				<?php the_content(); ?>
			</div>

		</div><!-- #post-<?php the_ID(); ?> -->
		<?php endwhile; endif; ?>
		
		<form action="<?php the_permalink(); ?>" id="guild-submit-form" method="post">
			<?php // If the submission was successful, say thank you instead
			if ( $email_sent ) : ?>
			<div class="updated">Thank you for submitting your guild, <?php echo $user->display_name; ?>. Your request was successfully sent. We will review it and respond as soon as possible. If your request is approved, you will be added to your group, and promoted to guild leader. We will contact you via email regarding your guild request once it has been processed. Thank you for contributing to Tamriel Foundry!</div>
			
			<?php // If something went wrong, give an error
			elseif ( $has_error ) : ?>
				<div class="error">There was an error submitting the guild request, please double check the required fields.</div>
			
			<?php // Make sure it's a registered user
			elseif ( 0 == $user_id ) : ?>
				<div class="warning">You must be a registered Tamriel Foundry member to submit a guild!</div>
				
			<?php // Make sure guild submission is currently allowed
			elseif ( !$enabled ) : ?>
				<div class="warning">Guild creation is temporarily disabled while we clear backlogged applications. Sorry for the inconvenience, please check back in a few days.</div>
				
			<?php // Make sure the user has enough posts to submit
			elseif ( $user->posts['total'] < $minposts ) : ?>
				<div class="warning">Guild submission is only available to Tamriel Foundry members who have contributed more than <?php echo $minposts; ?> posts to the community. This is to prevent the submission of guilds which are only seeking to use Tamriel Foundry as a recruitment or advertisment tool with no intention to participate within the community. Acceptable posts contribute to the discussion within the Tamriel Foundry community while conforming to our site's Code of Conduct, as such, the spam creation of topics or replies will be punished accordingly.</div>
			
			<?php // Otherwise, give them the form! 
			else : ?>
				THE FORM
			
			
			
			<?php endif; ?>
		</form>
		
	</div><!-- #content -->
	
	<?php apoc_primary_sidebar(); // Load the community sidebar ?>
<?php get_footer(); // Load the footer ?>