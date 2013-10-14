<?php 
/** 
 * Apocrypha Theme Entropy Rising Roster Template
 * Template Name: Entropy Rising Roster
 * Andrew Clayton
 * Version 1.0.0
 * 10-9-2013
 */ 
?>

<?php entropy_rising_header(); ?>

	<div id="content" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<div id="entropy-rising-roster">
			<header id="er-header" class="entry-header">
				<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
				<p class="entry-byline"><?php entry_header_description(); ?></p>
			</header>
		
			<header class="discussion-header" id="subnav" role="navigation">
				<div class="directory-member">Member</div>
				<div class="directory-content">Current Status
				</div>
			</header><!-- #subnav -->
		
		
			<?php if ( bp_group_has_members( $args = array( 'group_id' => 1, 'exclude_admins_mods' => false ) ) ) : ?>		
			<ul id="members-list" class="directory-list" role="main">
				<?php while ( bp_members() ) : bp_the_member(); 
				$user = new Apoc_User( bp_get_member_user_id() , 'directory' );	?>
				<li class="member directory-entry">
				
					<div class="directory-member">
						<?php echo $user->block; ?>
					</div>
					
					<div class="directory-content">
						<span class="activity"><?php bp_group_member_joined_since(); ?></span>
						
						<div id="guild-rank-block" class="actions">
							<?php display_guild_member_rank( bp_get_group_member_id() ); ?>
						</div>
						
						<?php if ( $user->status['content'] ) : ?>
						<blockquote class="user-status">
							<p><?php echo $user->status['content']; ?></p>
						</blockquote>
						<?php endif; ?>
					</div>
				</li>
				<?php endwhile; ?>
			</ul><!-- #members-list -->
			
			<nav id="pag-bottom" class="pagination directory-pagination">
				<div id="member-dir-count-bottom" class="pagination-count" >
					<?php bp_members_pagination_count(); ?>
				</div>
				<div id="member-dir-pag-bottom" class="pagination-links" >
					<?php bp_members_pagination_links(); ?>
				</div>
			</nav>
			<?php endif; ?>
		</div>
			
	</div><!-- #content -->
	<?php entropy_rising_sidebar(); // Load the guild sidebar ?>
<?php get_footer(); // Load the footer ?>