<?php get_header(); // Load the header ?>

	<div id="content" class="no-sidebar" role="main">
		<?php apoc_breadcrumbs(); ?>
		
		<header class="entry-header <?php post_header_class(); ?>">
			<h1 class="entry-title"><?php echo apocrypha()->seo->title; ?></h1>
			<p class="entry-byline"><?php echo apocrypha()->seo->description; ?></p>
		</header>
		
		<div id="map-container">
			<div id="map-canvas"></div>
		</div>
		
		<div id="map-sidebar">
			<form method="post" id="map-controls">
				<ol id="controls-list">
					<li class="select">
						<h2>Select Zone</h2>
						<select name="zone" id="zone-select" class="ebonheart" onchange="get_markers()">
							<option value=""></option>
							<optgroup label="Aldmeri Dominion">
								<option value="roost">Khenarthi's Roost</option>
								<option value="auridon">Auridon</option>
								<option value="grahtwood">Grahtwood</option>
								<option value="greenshade">Greenshade</option>
								<option value="malabal">Malabal Tor</option>
								<option value="reapers">Reaper's March</option>
							</optgroup>
							<optgroup label="Daggerfall Covenant">
								<option value="stros">Stros M'Kai</option>
								<option value="betnikh">Betnikh</option>
								<option value="glenumbra">Glenumbra</option>
								<option value="stormhaven">Stormhaven</option>
								<option value="rivenspire">Rivenspire</option>
								<option value="alikr">Alik'r Desert</option>
								<option value="bangkorai">Bangkorai</option>
							</optgroup>
							<optgroup label="Ebonheart Pact">
								<option value="bleakrock">Bleakrock Isle</option>
								<option value="balfoyen">Bal Foyen</option>
								<option value="stonefalls">Stonefalls</option>
								<option value="deshaan">Deshaan</option>
								<option value="shadowfen">Shadowfen</option>
								<option value="eastmarch">Eastmarch</option>
								<option value="therift">The Rift</option>
							</optgroup>
							<optgroup label="Planes of Oblivion">
								<option value="coldharbour">Coldharbour</option>
							</optgroup>
							<optgroup label="Cyrodiil">
								<option value="cyrodiil">Cyrodiil</option>
							</optgroup>
						</select>
					</li>
					
					<li class="checkbox">
						<h2>Filter Markers</h2>
						<ul id="marker-filters" class="radio-options-list">
							<li><input type="checkbox" name="filters" value="locales" checked="checked" onclick="get_markers()"/><label for="playstyle">Locales</label></li>
							<li><input type="checkbox" name="filters" value="skyshard" checked="checked" onclick="get_markers()"/><label for="playstyle">Skyshards</label></li>
							<li><input type="checkbox" name="filters" value="lorebook" checked="checked" onclick="get_markers()"/><label for="playstyle">Lorebooks</label></li>
							<li><input type="checkbox" name="filters" value="boss" checked="checked" onclick="get_markers()"/><label for="playstyle">Bosses</label></li>
							<li><input type="checkbox" name="filters" value="treasure" checked="checked" onclick="get_markers()"/><label for="playstyle">Treasure</label></li>
						</ul>
					</li>
				</ol>
			</form>
			<h2>About the Map</h2>
			<p>Welcome to the Tamriel Foundry interactive map. This map allows you to interact with the entire continent of Tamriel to view important locations and share coordinates with your friends.</p>
			<p>For now, most map marker layers are disabled in order to avoid extensive spoilers. We'll be rolling out additional layers of the map in stages in order to avoid ruining the hard work of the ZeniMax Online developers. Be sure to check back periodically for these additions as well as a number of planned improvements to the map itself!</p>
		</div>
		
	</div><!-- #content -->
<?php get_footer(); ?>