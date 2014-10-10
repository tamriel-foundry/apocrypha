<?php 
/** 
 * Apocrypha Theme Entropy Rising Header
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
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_URI . '/library/css/entropy-rising.css'; ?>?v=<?php echo filemtime( THEME_DIR . '/library/css/entropy-rising.css'); ?>" />	
</head>

<body class="<?php apoc_body_class(); ?> entropy-rising">
	
	<div id="header-container" class="erheader">	
		<header id="site-header" role="banner">
		
			<nav id="admin-bar" role="navigation">
				<?php apoc_admin_bar(); ?>
			</nav><!-- #admin-bar -->	
			
			<a id="main-banner" class="erheader" href="<?php echo SITEURL . '/entropy-rising/'; ?>"></a>
					
		</header>
	</div><!-- #header-container -->
	
	<nav id="primary-menu" role="navigation">
		<?php entropy_rising_menu(); ?>
	</nav><!-- #primary-menu -->
	
	<div id="main-container">	