<?php 
/** 
 * Apocrypha Theme Header Template
 * Andrew Clayton
 * Version 1.0.0
 * 10-9-2013
 */
?>
<!doctype html>
<!-- Tamriel Foundry - an ESO fansite and forum dedicated to discussing mechanics, theorycrafting, and guides for The Elder Scrolls Online. -->
<html dir="ltr" lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php apoc_document_title(); ?></title>
	<meta name="description" content="<?php apoc_meta_description(); ?>" />
	<link rel="SHORTCUT ICON" href="<?php echo THEME_URI . '/images/icons/favicon.ico'; ?>">
	<link rel="alternate" type="application/rss+xml" title="Tamriel Foundry RSS Feed" href="<?php echo SITEURL; ?>/feed">
	<?php wp_head(); ?>
</head>

<body class="<?php apoc_body_class(); ?>">	
	
	<div id="header-container">	
		<header id="site-header" role="banner">
		
			<nav id="admin-bar" role="navigation">
				<?php apoc_admin_bar(); ?>
			</nav><!-- #admin-bar -->	
			
			<a id="main-banner" href="<?php echo SITEURL; ?>"></a>
			
			<div id="azk60495"></div><!-- QuantCast -->
			
		</header>
	</div><!-- #header-container -->
	
	<nav id="primary-menu" role="navigation">
		<?php apoc_primary_menu(); ?>
	</nav><!-- #primary-menu -->
	
	<div id="main-container">