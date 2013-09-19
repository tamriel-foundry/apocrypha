<?php 
/**
 * Apocrypha Theme Groups Loop Template
 * Andrew Clayton
 * Version 1.0
 * 9-19-2013
 */
 
// Parse the querystring to allow for faction filtering
$groupquery		= bp_ajax_querystring( 'groups' );
$splitquery		= explode( '&' , $groupquery);
$grouptype		= preg_grep( '#scope=#' , $splitquery);
if ( $grouptype != '' )
	$faction	= array_pop( explode( '=' , implode( $grouptype ) ));
	
/* If we are targetting a specific faction, apply the meta filter */
if ( in_array( $faction , array( 'aldmeri' , 'daggerfall' , 'ebonheart' )))
	$meta_filter = new BP_Groups_Meta_Filter( 'group_faction', $faction );
?>

<?php  if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>
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
	<p class="notice">Sorry, no guilds were found.</p>
<?php endif; ?>

<?php if ( $meta_filter ) $meta_filter->remove_filters(); ?>