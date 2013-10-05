<?php 
/**
 * Apocrypha Theme Search Template
 * Andrew Clayton
 * Version 1.0
 * 10-4-2013
 * Template Name: Advanced Search
 */
 
// Get the theme global
$apoc = apocrypha();

// Determine the search context
$context 	= isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : 'posts';
$search		= trim( $_REQUEST['s'] );

// Was a search string provided OR was the form submitted?
if ( !empty( $search ) || $_POST['submitted'] ) :

	// Get the data by context
	switch ( $context ) {
		
		case 'posts' :
			
			// Get the fields
			$author_id		= ( $_POST['search-author'] != -1 ) ? $_POST['search-author'] : NULL;
			$category_id	= ( $_POST['search-category'] != -1 ) ? $_POST['search-category'] : NULL;
		
			// Construct a query
			$args = array( 
				'post_type'			=> 'post',
				's'					=> $search,
				'paged'				=> 1,
				'author'			=> $author_id,
				'cat' 				=> $category_id,	
			);
			$query = new WP_Query( $args );
			$submitted		= true;
			$query_posts 	= true;
			break;
		
		case 'pages' :
		
			// Construct a query
			$args = array( 
				'post_type'			=> 'page',
				's'					=> $search,
				'paged'				=> 1,
			);
			$query = new WP_Query( $args );
			$submitted		= true;
			$query_pages 	= true;
			break;
			
		case 'topics' :
		
			// Get arguments
			$forum_id		= ( $_POST['search-forum'] != '' ) ? $_POST['search-forum'] : 'any';

			// Construct a query		
			$topic_args = array(
				'post_type'			=> 'topic',
				'post_parent'		=> $forum_id,
				'meta_key'       	=> '_bbp_last_active_time', 
				'orderby'       	=> 'meta_value',
				'order'				=> 'DESC',
				'posts_per_page'	=> 12,
				'paged' 			=> 1,
				's'					=> $search,
				'show_stickies'		=> false,
				'max_num_pages'		=> false,
			);
					
			// Get the topics
			$submitted		= true;
			$query_topics 	= true;
			break;
			
		case 'members' :
		
			// Get arguments
			$member_faction		= $_POST['member-faction'];
		
			// Construct a query			
			$members_args = array(
				'type'				=> 'active',
				'page' 				=> 1,
				'per_page'			=> 12,
				'search_terms'		=> $search,
				'meta_key'			=> 'faction',
				'meta_value'		=> $member_faction,		
			);

			// Get the members
			$submitted 		= true;
			$query_members	= true;
			break;
			
		case 'groups' :
		
			// Get arguments
			$group_faction		= $_POST['group-faction'];
			
			// Construct a query			
			$groups_args = array(
				'type'				=> 'active',
				'page' 				=> 1,
				'per_page'			=> 12,
				'search_terms'		=> $search,	
			);
			
			// If we are targetting a specific faction, apply the meta filter
			if ( in_array( $group_faction , array( 'aldmeri' , 'daggerfall' , 'ebonheart' )))
			$meta_filter = new BP_Groups_Meta_Filter( 'group_faction', $group_faction );

			// Get the groups
			$submitted 		= true;
			$query_groups	= true;
			break;
	}
endif;
?>

<?php get_header(); // Load the header ?>
	
	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header class="entry-header <?php post_header_class(); ?>">
			<h1 class="entry-title">Advanced Search</h1>
			<p class="entry-byline">Search all Tamriel Foundry content using customizable search options.</p>
		</header>
		
		<form id="advanced-search" action="<?php echo SITEURL . '/advsearch/'; ?>" method="post">
		
			<div class="instructions">
				<h3 class="double-border bottom">Sitewide Search Form</h3>
				<ul>
					<li>You can use this form to search for articles, pages, forum topics, members, or guilds.</li>
					<li>Use the following form to narrow your search to just what you are seeking.</li>
				</ul>			
			</div>
			
			<?php // Select the search CONTEXT ?>
			<ol id="adv-search-context">
				<li class="select">
					<label for="type"><i class="icon-bookmark icon-fixed-width"></i>Search In: </label>
					<select name="type" id="search-for">
						<option value="posts" <?php selected( $context , 'posts' ); ?>>Articles</option>
						<option value="pages" <?php selected( $context , 'pages' ); ?>>Pages</option>
						<option value="topics" <?php selected( $context , 'topics' ); ?>>Topics</option>
						<option value="members" <?php selected( $context , 'members' ); ?>>Members</option>
						<option value="groups" <?php selected( $context , 'groups' ); ?>>Guilds</option>
					</select>
				</li>
				
				<li class="text">
					<label for="s"><i class="icon-quote-left icon-fixed-width"></i>Search For: </label>
					<input type="text" name="s" id="s" size="50" value="<?php echo $search; ?>">
				</li>
			</ol>
	
			<?php // Searching for ARTICLES ?>
			<ol id="adv-search-posts" class="adv-search-fields <?php if ( $context == 'posts' ) echo 'active'; ?>">
			
				<li class="select">
					<label for="search-author"><i class="icon-user icon-fixed-width"></i>By Author: </label>
					<?php wp_dropdown_users( $args = array(
						'show_option_none'		=> 'Any',
						'orderby'				=> 'display_name',
						'order'					=> 'ASC',
						'show'					=> 'display_name',
						'echo'					=> true,
						'name'					=> 'search-author',
						'who'					=> 'authors',
						'selected'				=> $author_id,				
					) ); ?>
				</li>

				<li class="select">
					<label for="search-category"><i class="icon-tag icon-fixed-width"></i>In Category: </label>
					<?php wp_dropdown_categories( $args = array(
						'show_option_none'		=> 'Any',
						'orderby'				=> 'NAME',
						'order'					=> 'ASC',
						'exclude'				=> get_cat_ID( 'entropy rising' ) . ',' . get_cat_ID( 'guild news' ),
						'echo'					=> true,
						'name'					=> 'search-category',
						'selected'				=> $category_id,
					) ); ?>
				</li>					
			</ol>
			
			<?php // Searching for PAGES  - unused ?>
			
			<?php // Searching for TOPICS ?>
			<ol id="adv-search-topics" class="adv-search-fields <?php if ( $context == 'topics' ) echo 'active'; ?>">
			
				<li class="select">
					<label for="search-forum"><i class="icon-list icon-fixed-width"></i>Search in Forum: </label>
					<?php bbp_dropdown( $args = array(
						'post_type'				=> 'forum',
						'show_none'          	=> 'Any Forum',
						'selected'				=> $forum_id,
						'select_id'          	=> 'search-forum',
					) ); ?>
				</li>
			</ol>
			
			<?php // Searching for MEMBERS ?>
			<ol id="adv-search-members" class="adv-search-fields <?php if ( $context == 'members' ) echo 'active'; ?>">
				<li class="select">
					<label for="member-faction"><i class="icon-flag icon-fixed-width"></i>User Alliance:</label>
					<select name="member-faction">
						<option value="">Any Alliance</option>
						<option value="aldmeri" class="aldmeri" <?php selected( $member_faction , 'aldmeri' , true ); ?>>Aldmeri Dominion</option>
						<option value="daggerfall" class="daggerfall" <?php selected( $member_faction , 'daggerfall' , true ); ?>>Daggerfall Covenant</option>
						<option value="ebonheart" class="ebonheart" <?php selected( $member_faction , 'ebonheart' , true ); ?>>Ebonheart Pact</option>
					</select>
				</li>			
			</ol>
			
			
			<?php // Searching for GROUPS ?>
			<ol id="adv-search-groups" class="adv-search-fields <?php if ( $context == 'groups' ) echo 'active'; ?>">
				<li class="select">
					<label for="group-faction"><i class="icon-flag icon-fixed-width"></i>Guild Alliance:</label>
					<select name="group-faction">
						<option value="">Any Alliance</option>
						<option value="aldmeri" class="aldmeri" <?php selected( $group_faction , 'aldmeri' , true ); ?>>Aldmeri Dominion</option>
						<option value="daggerfall" class="daggerfall" <?php selected( $group_faction , 'daggerfall' , true ); ?>>Daggerfall Covenant</option>
						<option value="ebonheart" class="ebonheart" <?php selected( $group_faction , 'ebonheart' , true ); ?>>Ebonheart Pact</option>
					</select>
				</li>			
			</ol>
			
			
			<?php // Search submission ?>
			<ol id="adv-search-submit">
				<li class="submit">
					<button type="submit" id="submit"><i class="icon-search"></i>Submit Search</button>
				</li>
				
				<li class="hidden">
					<input type="hidden" name="submitted" value="true" />
					<?php wp_nonce_field( 'apoc_adv_search' ); ?>
				</li>			
			</ol>
		</form><!-- #advanced-search -->
		
		<?php // Search results container
		if ( $submitted ) : ?>
		<div id="search-results" role="main">
		<?php endif; ?>
		
		<?php // Posts and Pages Results
		if ( $query_posts || $query_pages ) : ?>
		<div id="posts" class="archive">
			<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
				apoc_display_post();
			endwhile; else : ?>
			<p class="no-results">No articles were found that match this search.</p>
			<?php endif; ?>
		</div>
		
		<?php // Topics Results
		elseif ( $query_topics ) : ?>
		<div id="forums">
			<?php if ( bbp_has_topics( $topic_args ) ) :
			bbp_get_template_part( 'loop', 'topics' ); ?>
			
			<nav class="forum-pagination pagination">
				<div class="pagination-count">
					<?php bbp_forum_pagination_count(); ?>
				</div>
				<div class="pagination-links">
					<?php bbp_forum_pagination_links(); ?>
				</div>
			</nav>
			<?php else : ?>
			<p class="no-results">No topics were found that match this search.</p>
			<?php endif; ?>
		</div>
		
		<?php // Members Results
		elseif ( $query_members ) : ?>
		<header class="discussion-header" id="subnav" role="navigation">
				<div class="directory-member">Member</div>
				<div class="directory-content">Current Status</div>
		</header><!-- #subnav -->
		<div id="members-dir-list" class="members dir-list">
		<?php if ( bp_has_members( $members_args ) ) : ?>
			<ul id="members-list" class="directory-list" role="main">
				
				<?php // Loop through all members
				while ( bp_members() ) : bp_the_member(); 
				$user = new Apoc_User( bp_get_member_user_id() , 'directory' );	?>
				<li class="member directory-entry">

					<div class="directory-member">
						<?php echo $user->block; ?>
					</div>
					
					<div class="directory-content">
						<span class="activity"><?php bp_member_last_active(); ?></span>
						<div class="actions">
							<?php do_action( 'bp_directory_members_actions' ); ?>
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

			<?php else: ?>
			<p class="no-results"><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
			<?php endif; ?>
		</div>				
		
		
		<?php // Groups Results
		elseif ( $query_groups ) : ?>
		<header class="discussion-header" id="subnav" role="navigation">
			<div class="directory-member">Guild</div>
			<div class="directory-content">Description</div>
		</header><!-- #subnav -->
		<div id="groups-dir-list" class="groups dir-list">
			<?php  if ( bp_has_groups( $groups_args ) ) : ?>
			<ul id="groups-list" class="directory-list" role="main">
			<?php // Loop through all groups
				while ( bp_groups() ) : bp_the_group();
				$group = new Apoc_Group( bp_get_group_id() , 'directory' );	?>
				
				<li class="group directory-entry">
					<div class="directory-member">
						<?php echo $group->block; ?>
					</div>
					
					<div class="directory-content">
						<span class="activity"><?php bp_group_last_active(); ?></span>
						<div class="actions">
							<?php do_action( 'bp_directory_groups_actions' ); ?>
						</div>
						<div class="guild-description">
							<?php bp_group_description_excerpt(); ?>
						</div>
					</div>
				</li>
			<?php endwhile; ?>
			</ul><!-- #groups-list -->

			<nav id="pag-bottom" class="pagination">
				<div id="group-dir-count-bottom" class="pagination-count">
					<?php bp_groups_pagination_count(); ?>
				</div>
				<div id="group-dir-pag-bottom" class="pagination-links">
					<?php bp_groups_pagination_links(); ?>
				</div>
			</nav>

			<?php else: ?>
				<p class="no-results">Sorry, no guilds were found.</p>
			<?php endif; ?>
		</div>		
		<?php endif; ?>
		
		<?php if ( $submitted ) : ?>
		</div><!-- #search-results -->
		<?php endif; ?>
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>