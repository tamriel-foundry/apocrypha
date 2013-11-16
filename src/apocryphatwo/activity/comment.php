<?php 
/**
 * Apocrypha Theme Activity Comment Template
 * Andrew Clayton
 * Version 1.0.0
 * 10-6-2013
 */
$avatar = new Apoc_Avatar( $args = array(
	'user_id' 		=> bp_get_activity_comment_user_id(),
	'type'			=> 'thumb',
	'size'			=> 50,
	'link'			=> true,
	) );
global $acomment_type;
if ( $acomment_type == 'even' )
	$acomment_type = 'odd';
elseif ( $acomment_type == 'odd' )
	$acomment_type = 'even';
else
	$acomment_type = 'odd';
?>

<li id="acomment-<?php bp_activity_comment_id(); ?>" class="activity-comment recent-discussion <?php echo $acomment_type; ?>">
	<div class="discussion-avatar">
		<?php echo $avatar->avatar; ?>
	</div>

	<div class="recent-discussion-content">
		<span class="recent-discussion-title"><?php printf( __( '<a href="%1$s">%2$s</a> replied <a href="%3$s" class="activity-time-since"><span class="time-since">%4$s</span></a>', 'buddypress' ), bp_get_activity_comment_user_link(), bp_get_activity_comment_name(), bp_get_activity_thread_permalink(), bp_get_activity_comment_date_recorded() ); ?></span>
	
		<div class="acomment-options">
		<?php if ( bp_activity_user_can_delete() ) : ?>
			<a href="<?php bp_activity_comment_delete_link(); ?>" class="delete acomment-delete confirm bp-secondary-action button button-dark" rel="nofollow"><i class="icon-trash"></i>Delete</a>
		<?php endif; ?>
		</div>
	
		<div class="acomment-content">
			<?php bp_activity_comment_content(); ?>
		</div>
	</div>	
	<?php //bp_activity_recurse_comments( bp_activity_current_comment() ); ?>
</li>
 