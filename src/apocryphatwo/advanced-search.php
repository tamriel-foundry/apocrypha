<?php
/**
 * Apocrypha Theme Category Archive Template
 * Andrew Clayton
 * Version 1.0
 * 8-8-2013
 * Template Name: Advanced Search
 */
?>

<?php get_header(); ?>

	<div id="content" role="main">

		<header id="archive-header">
			<h1 id="archive-title" class="double-border bottom"><?php printf( 'Advanced Search' ); ?></h1>
				<div id="archive-description">
					<p>Search Tamriel Foundry using advanced search options.</p>
				</div>
		</header>
		<form id="advanced-search" action="#" method="post">
			<fieldset>
				<legend>Advanced Search Options</legend>
				<p>
					<label for="search-for">Search For:</label>
					<select name="search-for" id="search-for">
						<option value="articles" selected>Articles</option>
						<option value="topics">Topics</option>
						<option value="members">Members</option>
						<option value="guilds">Guilds</option>
					</select>
				</p>
				<div id="if-articles" class="dynamic-form-section">
					<p>
						<label for="search-query">Search Articles:</label>
						<input type="search" name="search-query" id="search-query" required>
					</p>
					<p>
						<label for="search-title-only">Search Title Only:</label>
						<input type="checkbox" name="search-title-only" id="search-title-only">
					</p>
					<p>
						<label for="search-by-author">Author:</label>
						<select name="search-by-author" id="search-by-author">
							<option value="ph1">This dropdown will be</option>
							<option value="ph2">Populated from DB</option>
							<option value="ph2">Very soon</option>
						</select>
					</p>
					<p>
						<label for="search-by-category">Category:</label>
						<select name="search-by-category" id="search-by-category">
							<option value="ph1">This dropdown will be</option>
							<option value="ph2">Populated from DB</option>
							<option value="ph2">Very soon</option>
						</select>
					</p>
				</div><!-- #if-articles -->
				<div id="if-topics" class="dynamic-form-section">
					<p>
						<label for="search-query">Search Topics:</label>
						<input type="search" name="search-query" id="search-query" required>
					</p>
					<p>
						<label for="search-title-only">Search Replies?</label>
						<input type="checkbox" name="search-replies" id="search-replies">
					</p>
					<p>
						<label for="search-in-forum">In Forum:</label>
						<select name="search-in-forum" id="search-in-forum">
							<option value="ph1">This dropdown will be</option>
							<option value="ph2">Populated from DB</option>
							<option value="ph2">Very soon</option>
						</select>
					</p>
					<p>
						<label for="search-by-author">Author:</label>
						<select name="search-by-author" id="search-by-author">
							<option value="ph1">This dropdown will be</option>
							<option value="ph2">Populated from DB</option>
							<option value="ph2">Very soon</option>
						</select>
					</p>
				</div><!-- #if-topics -->
				<div id="if-members" class="dynamic-form-section">
					<p>
						<label for="search-query">Username:</label>
						<input type="search" name="search-query" id="search-query">
					</p>
					<p>
						<label for="search-in-alliance">Alliance:</label>
						<select name="search-in-alliance" id="search-in-alliance">
							<option value="dc">Daggerfall Covenant</option>
							<option value="ep">Ebonheart Pact</option>
							<option value="ad">Aldmeri Dominion</option>
						</select>
					</p>
					<p>
						<label for="search-by-platform">Platform:</label>
						<select name="search-by-platform" id="search-by-platform">
							<option value="pcmac" selected>PC/Mac</option>
							<option value="xbox">Xbox One</option>
							<option value="ps4">PS4</option>
						</select>
					</p>
					<p>
						<label for="search-by-region">Region:</label>
						<select name="search-by-region" id="search-by-region">
							<option value="ph1">This dropdown will be</option>
							<option value="ph2">Populated from DB</option>
							<option value="ph2">Very soon</option>
						</select>
					</p>
					<p>
						<label for="search-title-only">Show "Looking For Guild"?</label>
						<input type="checkbox" name="show-lfg" id="show-lfg">
					</p>
				</div><!-- #if-members -->
				<div id="if-guilds" class="dynamic-form-section">
					<p>
						<label for="search-query">Guild Name:</label>
						<input type="search" name="search-query" id="search-query">
					</p>
					<p>
						<label for="search-in-alliance">Alliance:</label>
						<select name="search-in-alliance" id="search-in-alliance">
							<option value="dc">Daggerfall Covenant</option>
							<option value="ep">Ebonheart Pact</option>
							<option value="ad">Aldmeri Dominion</option>
						</select>
					</p>
					<p>
						<label for="search-by-platform">Platform:</label>
						<select name="search-by-platform" id="search-by-platform">
							<option value="pcmac" selected>PC/Mac</option>
							<option value="xbox">Xbox One</option>
							<option value="ps4">PS4</option>
						</select>
					</p>
					<p>
						<label for="search-by-region">Region:</label>
						<select name="search-by-region" id="search-by-region">
							<option value="ph1">This dropdown will be</option>
							<option value="ph2">Populated from DB</option>
							<option value="ph2">Very soon</option>
						</select>
					</p>
					<p>
						<label for="mentality">Mentality:</label>
						<input type="radio" name="mentality" value="opt1"> Option 1
						<input type="radio" name="mentality" value="opt2"> Option 2
						<input type="radio" name="mentality" value="opt3"> Option 3
					</p>
					<p>
						<label for="style">Style:</label>
						<input type="radio" name="style" value="opt1"> Option 1
						<input type="radio" name="style" value="opt2"> Option 2
						<input type="radio" name="style" value="opt3"> Option 3
					</p>
					<p>
						<label for="only-recruiting">Only show guilds that are recruiting:</label>
						<input type="checkbox" name="only-recruiting" id="only-recruiting">
					</p>
				</div><!-- #if-guilds -->
				<input type="submit" name="submit" value="Search">
			</fieldset>
		</form>
		<div id="search-results"></div><!-- #search-results -->
	</div><!-- #content -->

	<?php apoc_primary_sidebar(); ?>
<?php get_footer(); ?>