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
$context = isset( $_POST['search-for'] ) ? $_POST['search-for'] : 'posts';

// Was the form submitted?
if ( isset( $_POST['submitted'] ) ) :

	// Get the search query
	$search = trim( $_POST['search-query'] );

	// Get the data by context
	switch ( $context ) {
		
		case 'posts' :
			
			// Get the fields
			$title_only 	= isset( $_POST['search-title-only'] ) ? true : false;
			$author_id		= ( $_POST['search-author'] != -1 ) ? $_POST['search-author'] : NULL;
			$category_id	= ( $_POST['search-category'] != -1 ) ? $_POST['search-category'] : NULL;
		
			// Construct a query
			$args = array( 
				'post_type'			=> 'post',
				's'					=> $search,
				'posts_per_page'	=> 10,
				'author'			=> $author_id,
				'cat' 				=> $category_id,	
			);
			$query = new WP_Query( $args );
			//print_r( $query );
		break;
	}
endif;
?>

<?php get_header(); // Load the header ?>
	
	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header class="entry-header <?php post_header_class(); ?>">
			<h1 class="entry-title">Advanced Search</h1>
			<p class="entry-byline"><?php entry_header_description(); ?></p>
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
					<label for="search-context"><i class="icon-bookmark icon-fixed-width"></i>Search In: </label>
					<select name="search-for" id="search-for">
						<option value="posts" <?php selected( $context , 'posts' ); ?>>Articles</option>
						<option value="pages" <?php selected( $context , 'pages' ); ?>>Pages</option>
						<option value="topics" <?php selected( $context , 'topics' ); ?>>Topics</option>
						<option value="members" <?php selected( $context , 'members' ); ?>>Members</option>
						<option value="guilds" <?php selected( $context , 'guilds' ); ?>>Guilds</option>
					</select>
				</li>
				
				<li class="text">
					<label for="search-query"><i class="icon-quote-left icon-fixed-width"></i>Search For: </label>
					<input type="text" name="search-query" id="search-query" size="50" value="<?php echo $search; ?>">
				</li>
			</ol>
	
			<?php // Searching for ARTICLES ?>
			<ol id="adv-search-articles">
			
				<li class="checkbox">
					<label><i class="icon-book icon-fixed-width"></i>In Title Only? </label>
					<input type="checkbox" name="search-title-only" id="search-title-only" <?php checked( $title_only , true ); ?>>
					<label for="search-title-only">Yes</label>
				</li>
			
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
			
			<?php // Searching for PAGES ?>
			
			<?php // Searching for TOPICS ?>
			
			<?php // Searching for MEMBERS ?>
			
			<?php // Searching for GROUPS ?>
			
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
		</form>
		
		
		<div id="seach-results">



		</div>
		
	</div><!-- #content -->
<?php get_footer(); // Load the footer ?>