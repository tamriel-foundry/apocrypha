<?php 
/**
 * Apocrypha Theme Members Directory Template
 * Andrew Clayton
 * Version 1.0.0
 * 9-7-2013
 */
 
// Get the current user info
$user 		= apocrypha()->user->data;
$user_id	= $user->ID;
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar">
		<?php apoc_breadcrumbs(); ?>
		
		<form action="<?php the_permalink(); ?>" method="post" id="members-directory-form" class="dir-form">
		
			<header id="directory-header" class="entry-header <?php page_header_class(); ?>">
				<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
				<p class="entry-byline">A directory listing of all Tamriel Foundry members.</p>
				<div id="members-search" class="directory-search" role="search">
					<?php apoc_members_search_form(); ?>
				</div>
			</header>
		
			<nav id="directory-nav" class="dir-list-tabs" role="navigation">
				<ul id="directory-actions" class="directory-tabs">
					<li class="selected" id="members-all"><a href="<?php echo trailingslashit( SITEURL . '/' . bp_get_members_root_slug() ); ?>">All Members<span><?php echo bp_get_total_member_count(); ?></span></a></li>
					<?php if ( $user_id > 0 ) : ?>
					<li id="members-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ?>">My Friends<span><?php echo bp_get_total_friend_count( $user_id ); ?></span></a></li>
					<?php endif; ?>
				</ul>
			</nav><!-- #directory-header -->
			
			<header class="discussion-header" id="subnav" role="navigation">
				<div class="directory-member">Member</div>
				<div class="directory-content">Current Status
					<div id="members-order-select" class="filter">
						<select id="members-order-by">
							<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
							<option value="newest"><?php _e( 'Newest Registered', 'buddypress' ); ?></option>
							<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
						</select>
					</div>
				</div>
			</header><!-- #subnav -->
			
			<div id="members-dir-list" class="members dir-list">
				<?php locate_template( array( 'members/members-loop.php' ), true ); ?>
			</div><!-- #members-dir-list -->		
			<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>
				
		</form>
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>