<?php 
/** 
 * Apocrypha Theme Header Template
 * Andrew Clayton
 * Version 0.1
 * 1-11-2012
 */ 
?>
<!doctype html>
<!-- Tamriel Foundry - an ESO fansite and forum dedicated to discussing mechanics, theorycrafting, and guides for The Elder Scrolls Online. -->
<html dir="ltr" lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php // display_document_title(); ?></title>
	<meta name="description" content="<?php // display_meta_description(); ?>" />
	<link rel="SHORTCUT ICON" href="<?php echo THEME_URI . '/images/favicon.ico'; ?>">
	<link rel="alternate" type="application/rss+xml" title="Tamriel Foundry RSS Feed" href="<?php echo SITEURL; ?>/feed">
	<?php apoc_primary_stylesheet(); ?>
	<?php wp_head(); ?>
	<?php google_analytics_js(); ?>
</head>

<body class="<?php display_body_class(); ?>">
	
	<div id="header-container">
			
		<header id="site-header" role="banner">
			
			<nav id="admin-bar" role="navigation">
				<?php apoc_header_search(); ?>
				<?php //apoc_header_login(); ?>
				<?php //apoc_notifications_menu(); ?>
			</nav><!-- #admin-bar -->
			
			<a id="main-banner" href="<?php echo SITEURL; ?>"></a>
					
		</header><!-- #header -->
		
	</div><!-- #header-container -->
	
	<nav id="primary-menu" role="navigation">
		<?php //apoc_primary_menu(); ?>
	</nav><!-- #primary-menu -->
	
	<div id="main-container">	
	
<?php global $apocrypha;
print_r( $apocrypha ); ?>