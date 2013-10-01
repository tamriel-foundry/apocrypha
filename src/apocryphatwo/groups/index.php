<?php 
/**
 * Apocrypha Theme Groups Directory Template
 * Andrew Clayton
 * Version 1.0
 * 9-19-2013
 */
 
// Get the current user info
$user 		= apocrypha()->user->data;
$user_id	= $user->ID;
?>

<?php get_header(); ?>

	<div id="content" class="no-sidebar">
		<?php apoc_breadcrumbs(); ?>
		
		<form action="<?php the_permalink(); ?>" method="post" id="groups-directory-form" class="dir-form">
		
			<header id="directory-header" class="entry-header <?php page_header_class(); ?>">
				<h1 class="entry-title"><?php entry_header_title( false ); ?></h1>
				<p class="entry-byline">A directory listing of active guilds within the Tamriel Foundry community.</p>
				<div id="groups-search" class="directory-search" role="search">
					<?php apoc_groups_search_form(); ?>
				</div>
				<?php if ( is_user_logged_in() && bp_user_can_create_groups() ) : ?>
					<a id="create-guild-button" class="button" href="<?php echo SITEURL . '/' . bp_get_groups_root_slug() . '/create/'; ?>">Create New Guild</a>
				<?php elseif ( is_user_logged_in() ) : ?>
					<a id="create-guild-button" class="button" href="<?php echo SITEURL . '/submit-guild/'; ?>">Submit New Guild</a>
				<?php endif; ?>
			</header>
			
			<nav id="directory-nav" role="navigation">
				<ul id="directory-actions" class="directory-tabs">
					<li class="selected" id="groups-all"><a href="<?php echo trailingslashit( SITEURL . '/' . bp_get_groups_root_slug() ); ?>">All Guilds<span><?php echo bp_get_total_group_count(); ?></span></a></li>
					<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( $user_id ) ) : ?>
					<li id="groups-personal"><a href="<?php echo trailingslashit( bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups' ); ?>">My Guilds<span><?php echo bp_get_total_group_count_for_user(); ?></span></a></li>
					<?php endif; ?>
					<li id="groups-aldmeri"><a href="?faction=aldmeri">Aldmeri<span><?php echo count_groups_by_meta( 'group_faction' , 'aldmeri' ); ?></span></a></li>
					<li id="groups-daggerfall"><a href="?faction=daggerfall">Daggerfall<span><?php echo count_groups_by_meta( 'group_faction' , 'daggerfall' ); ?></span></a></li>
					<li id="groups-ebonheart"><a href="?faction=ebonheart">Ebonheart<span><?php echo count_groups_by_meta( 'group_faction' , 'ebonheart' ); ?></span></a></li>
				</ul>
			</nav><!-- #directory-header -->
			
			<header class="discussion-header" id="subnav" role="navigation">
				<div class="directory-member">Guild</div>
				<div class="directory-content">Description
					<div id="members-order-select" class="filter">
						<select id="groups-order-by">
							<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
							<option value="popular"><?php _e( 'Most Members', 'buddypress' ); ?></option>
							<option value="newest"><?php _e( 'Newly Created', 'buddypress' ); ?></option>
							<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
						</select>
					</div>
				</div>
			</header><!-- #subnav -->
			
			<div id="groups-dir-list" class="groups dir-list">
				<?php locate_template( array( 'groups/groups-loop.php' ), true ); ?>
			</div><!-- #groups-dir-list -->
			<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

		</form>		
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>