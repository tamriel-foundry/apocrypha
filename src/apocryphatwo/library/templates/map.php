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
				<?php if ( $error ) echo'<p id="warning">Make sure to fill in all required fields!</p>'; ?>
				<h2>Modify Markers</h2>
				<ol id="controls-list">
					<li class="select">
						<label for="zone">Select Zone:</label>
						<select name="zone" id="zone-select" class="ebonheart" onchange="get_markers()">
							<option value=""></option>
							<optgroup label="Aldmeri Dominion">
								<option value="roost" <?php if ( $zone == 'roost' ) echo "selected"; ?>>Khenarthi's Roost</option>
								<option value="auridon" <?php if ( $zone == 'auridon' ) echo "selected"; ?>>Auridon</option>
								<option value="grahtwood" <?php if ( $zone == 'grahtwood' ) echo "selected"; ?>>Grahtwood</option>
								<option value="greenshade" <?php if ( $zone == 'greenshade' ) echo "selected"; ?>>Greenshade</option>
								<option value="malabal" <?php if ( $zone == 'malabal' ) echo "selected"; ?>>Malabal Tor</option>
								<option value="reapers" <?php if ( $zone == 'reapers' ) echo "selected"; ?>>Reaper's March</option>
							</optgroup>
							<optgroup label="Daggerfall Covenant">
								<option value="stros" <?php if ( $zone == 'stros' ) echo "selected"; ?>>Stros M'Kai</option>
								<option value="betnikh" <?php if ( $zone == 'betnikh' ) echo "selected"; ?>>Betnikh</option>
								<option value="glenumbra" <?php if ( $zone == 'glenumbra' ) echo "selected"; ?>>Glenumbra</option>
								<option value="stormhaven" <?php if ( $zone == 'stormhaven' ) echo "selected"; ?>>Stormhaven</option>
								<option value="rivenspire" <?php if ( $zone == 'rivenspire' ) echo "selected"; ?>>Rivenspire</option>
								<option value="bangkorai" <?php if ( $zone == 'bangkorai' ) echo "selected"; ?>>Bangkorai</option>
								<option value="alikr" <?php if ( $zone == 'alikr' ) echo "selected"; ?>>Alik'r Desert</option>
							</optgroup>
							<optgroup label="Ebonheart Pact">
								<option value="bleakrock" <?php if ( $zone == 'bleakrock' ) echo "selected"; ?>>Bleakrock Isle</option>
								<option value="balfoyen" <?php if ( $zone == 'balfoyen' ) echo "selected"; ?>>Bal Foyen</option>
								<option value="stonefalls" <?php if ( $zone == 'stonefalls' ) echo "selected"; ?>>Stonefalls</option>
								<option value="deshaan" <?php if ( $zone == 'deshaan' ) echo "selected"; ?>>Deshaan</option>
								<option value="shadowfen" <?php if ( $zone == 'shadowfen' ) echo "selected"; ?>>Shadowfen</option>
								<option value="therift" <?php if ( $zone == 'therift' ) echo "selected"; ?>>The Rift</option>
								<option value="eastmarch" <?php if ( $zone == 'eastmarch' ) echo "selected"; ?>>Eastmarch</option>
							</optgroup>
							<optgroup label="Cyrodiil">
								<option value="cyrodiil" <?php if ( $zone == 'cyrodiil' ) echo "selected"; ?>>Cyrodiil</option>
							</optgroup>
							<optgroup label="Random">
								<option value="crafting" <?php if ( $zone == 'crafting' ) echo "selected"; ?>>Rudrias' Crafting Layer</option>
								<option value="tactics" <?php if ( $zone == 'tactics' ) echo "selected"; ?>>Pinglong's Tactics Layer</option>
							</optgroup>
						</select>
					</li>
					
					<li class="text">
						<label for="lat">Latitude:</label><input type="text" name="lat" id="latFld">
					</li>
					
					<li class="text">
						<label for="lng">Longitude:</label><input type="text" name="lng" id="lngFld">
					</li>
					
					<li class="text">
						<label for="name">Name:</label><input type="text" name="name" id="nameFld">
					</li>
					
					<li class="select">
						<label for="type">Type:</label>
						<select name="type" id="typeFld">
							<option value=""></option>
							<optgroup label="Buildings">
								<option value="camp">Camp</option>
								<option value="farm">Farm</option>
								<option value="fort">Fort</option>
								<option value="lighthouse">Lighthouse</option>
								<option value="mill">Lumber Mill</option>
								<option value="mine">Mine</option>
								<option value="tent">Outpost</option>
								<option value="ruin">Ruin</option>
								<option value="tower">Tower</option>
								<option value="town">Town</option>
							</optgroup>
							
							<optgroup label="Cities">
								<option value="bank">Bank</option>
								<option value="castle">Castle</option>
								<option value="fightersguild">Fighters Guild</option>
								<option value="magesguild">Mages Guild</option>
								<option value="undaunted">Undaunted Tavern</option>
								<option value="temple">Temple</option>
								<option value="dock">Dockyard</option>
							</optgroup>

							<optgroup label="Collectibles">
								<option value="treasure">Buried Treasure</option>	
								<option value="crafting">Crafting Camp</option>	
								<option value="boss">Rare Monster</option>
								<option value="skyshard">Skyshard</option>
								<option value="mundus">Mundus Stone</option>						
								<option value="lorebook">Lorebook</option>
								<option value="landmark">Landmark</option>
							</optgroup>

							<optgroup label="Dungeons">
								<option value="barrow">Barrow</option>		
								<option value="cave">Cave</option>		
								<option value="pubdungeon">Public Dungeon</option>
								<option value="instance">Instanced Dungeon</option>						
								<option value="dwemer">Dwemer Ruin</option>
								<option value="ayleid">Ayleid Ruin</option>
								<option value="daedric">Daedric Ruin</option>
								<option value="tomb">Tomb</option>						
							</optgroup>
							
							<optgroup label="Landmarks">				
								<option value="dolmen">Dolmen</option>
								<option value="mountain">Mountain</option>
								<option value="wayshrine">Wayshrine</option>						
								<option value="tree">Tree</option>		
								<option value="battle">Battle</option>						
							</optgroup>	
						</select>
					</li>
					
					<li class="textarea">
						<label for="description">Description:</label>
						<textarea name="description" id="descFld" rows="5"></textarea>
					</li>
					
					<li class="submit">
						<button type="button" id="clear" name="clear" onclick="ClearMarker()">New</button>
						<input type="submit" id="submit" name="submitted" value="Submit" />				
					</li>
					
					<li class="hidden">
						<input type="hidden" id="context" name="context" value="new" />
						<input type="hidden" id="editid" name="editid" value="0" />
					</li>
				</div>
			</form>
		</div>
		
	</div><!-- #content -->
<?php get_footer(); ?>