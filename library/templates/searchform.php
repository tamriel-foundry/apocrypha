<?php 
/** 
 * Apocrypha Theme Search Form Template
 * Andrew Clayton
 * Version 0.1
 * 1-18-2012
 */ 

// If we are viewing search results, grab what was queried
if ( is_search() )
	$search_text = get_search_query(); 
	
// Check the type of search we are doing
$search_type = apocrypha()->search_type;

// Get the search form depending on the type
if ( 'topic' == $search_type ) :
	$search_text = 'Search Forums...';
	$search_class = 'topics'; ?>
<form role="search" method="get" class="search-form forum-search"  action="<?php echo SITEURL . '/advsearch/'; ?>">
							<input type="hidden" name="type" value="topics" />
							<input class="search-text <?php echo $search_class; ?>" type="text" name="s" value="<?php echo $search_text; ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
						</form><!-- .search-form -->
<?php else :
	$search_text = 'Search Articles...';
	$search_class = 'posts'; ?>
<form role="search" method="get" class="search-form"  action="<?php echo SITEURL . '/advsearch/'; ?>">
							<input type="hidden" name="type" value="posts" />
							<label class="search-label double-border bottom"><i class="icon-search"></i>Search Tamriel Foundry:</label>
							<input class="search-text <?php echo $search_class; ?>" type="text" name="s" value="<?php echo $search_text; ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
						</form><!-- .search-form -->
<?php endif; ?>