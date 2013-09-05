<?php 
/**
 * Apocrypha Theme User Profile Component
 * Andrew Clayton
 * Version 1.0.0
 * 9-4-2013
 */
global $user;
$charsheet_class = 'neutral';
if ( '' != $user->race ) $charsheet_class = $user->race;
elseif ( '' != $user->faction ) $charsheet_class = $user->faction;
?>

<nav class="directory-subheader no-ajax" id="subnav" >
	<ul id="profile-tabs" class="tabs" role="navigation">
		<?php bp_get_options_nav(); ?>
	</ul>
</nav><!-- #subnav -->
<?php do_action( 'template_notices' ); ?>


<?php // Secondary template options
if ( bp_is_current_action( 'change-avatar' ) ) : 
	locate_template( array( 'members/single/profile/change-avatar.php' ), true );
else : ?>

<div id="user-profile" role="main">

	<div id="user-character" class="widget">
		<header class="widget-header">
			<h3 class="widget-title">Character Sheet</h3>
		</header>
		<div id="character-sheet" class="<?php echo $charsheet_class; ?>">
			<ul>
				<li><i class="icon-book icon-fixed-width"></i><span>Name:</span><?php echo $user->charname; ?></li>
				<li><i class="icon-flag icon-fixed-width"></i><span>Race:</span><?php echo ucfirst( $user->race ); ?></li>
				<li><i class="icon-gear icon-fixed-width"></i><span>Class:</span><?php echo ucfirst( $user->class ); ?></li>
				<li><i class="icon-shield icon-fixed-width"></i><span>Role:</span></li>
				<li><i class="icon-group icon-fixed-width"></i><span>Guild:</span></li>
			</ul>
		</div>
	</div>	

	<div id="user-biography">
		<?php echo $user->bio; ?>
	</div>
	

</div><!-- #user-profile -->
<?php endif; ?>