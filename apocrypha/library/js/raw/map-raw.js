// ESO Interactive Map Control

/* Define Global Variables
--------------------------------------------------*/
var $ 				= jQuery;
var esomap;

// Locations
var siteurl			= "http://tamrielfoundry.com/";
var assets			= "http://tamrielfoundry.com/wp-content/themes/apocrypha/library/map/";

// Markers
var zone;
var locations 		= [];
var activeMarkers 	= [];

// Boundaries
var zoneCoords;
var allowedBounds;

// Tooltips
var iconver 		= 0.72;
var tilever 		= 0.3;
var infowindow;

// Initialization
$(document).ready( function(){ interactiveMap( zone ); } );

/* Initiate the Map API
--------------------------------------------------*/
function interactiveMap( zone ) {
	
	// Define the Tamriel map layer
	var tamrielTiles	= "http://tamriel.objects.dreamhost.com/";
	var tamrielOptions 	= {
		name: "Tamriel",
		mapTypeId: "tamriel",
		getTileUrl: function(coord,zoom) {
			var maxTile = Math.pow(2,zoom);
			coord.x		= ( Math.abs(coord.x) < maxTile ) ? Math.abs(coord.x) : maxTile - 1;
			coord.y		= ( Math.abs(coord.y) < maxTile ) ? Math.abs(coord.y) : maxTile - 1;
			return 		tamrielTiles + zoom + "_" + coord.x + "_" + coord.y + ".jpg?ver=" + tilever;
		},
		tileSize: new google.maps.Size(256, 256),
		maxZoom: 7,
		minZoom: 2,
		opacity: 1,
	};
	var tamrielMapType = new google.maps.ImageMapType( tamrielOptions );
	
	// Define the Coldharbour map layer
	var coldharbourTiles	= "http://coldharbour.objects.dreamhost.com/";
	var coldharbourOptions 	= {
		name: "Coldharbour",
		mapTypeId: "coldharbour",
		getTileUrl: function(coord,zoom) {
			var maxTile = Math.pow(2,zoom);
			coord.x		= ( Math.abs(coord.x) < maxTile ) ? Math.abs(coord.x) : maxTile - 1;
			coord.y		= ( Math.abs(coord.y) < maxTile ) ? Math.abs(coord.y) : maxTile - 1;
			return 		coldharbourTiles + zoom + "_" + coord.x + "_" + coord.y + ".jpg?ver=" + tilever;
		},
		tileSize: new google.maps.Size(256, 256),
		maxZoom: 4,
		minZoom: 2,
		opacity: 1,
	};
	var coldharbourMapType = new google.maps.ImageMapType( coldharbourOptions );
	
	// Setup map options
	var mapOptions = {
		center: new google.maps.LatLng(0,0),
		zoom: 2,
		streetViewControl: false,
		scaleControl: false,
		panControl: false,
		zoomControl: true,
		mapTypeControl: true,
		mapTypeControlOptions: { mapTypeIds: [ 'tamriel' , 'coldharbour' ] },
		backgroundColor: "black",
		draggableCursor: "crosshair",
	};
	esomap = new google.maps.Map( document.getElementById('map-canvas') , mapOptions );
	   
	// Set the current map type
	esomap.mapTypes.set( 'tamriel' , tamrielMapType );
	esomap.mapTypes.set( 'coldharbour' , coldharbourMapType );
	esomap.setMapTypeId( 'tamriel' );
	
	// Limit panning to legal bounds depending on the zoom level
	allowedBounds = set_bounds( 'new' );
	google.maps.event.addListener( esomap , 'zoom_changed' , function() { 
		set_bounds();
		maybe_hide_markers();
	});
	
	// Add a movement listener to enforce the boundaries
	var lastValidCenter = esomap.getCenter();
	google.maps.event.addListener(esomap, 'center_changed', function() {   
		if (allowedBounds.contains(esomap.getCenter())) {
			lastValidCenter = esomap.getCenter();
			return; 
		} 	
    	esomap.panTo(lastValidCenter);
	});
	
	// Add a mapTypeID listener to re-adjust zoom
	google.maps.event.addListener( esomap , 'maptypeid_changed' , function() {
		
		// Get the new map type id
		var map = esomap.getMapTypeId();
		
		// Get the currently requested zone
		var zone = $( 'select#zone-select :selected' ).attr('value');

		// Clear the zone dropdown
		$( 'select#zone-select' ).val("");
	
		// Clear existing markers
		clear_markers();
			
		// Pan to the center of the new map
		coords = get_zone_coords( map );
		zoneCoords = new google.maps.LatLng( coords[0] , coords[1] );
		esomap.setZoom( coords[2] );
		esomap.panTo( zoneCoords );	
	});
		
	
	// Add an info window
	infowindow = new google.maps.InfoWindow();
	
	// If arguments were passed during setup, use them
	if ( '' !== zone )
		get_markers();

}

/* Set the boundaries given the zoom level
--------------------------------------------------*/
function set_bounds() {

	// Get the new zoom level
	var zoom 	= esomap.getZoom();
	var center 	= esomap.getCenter();
	
	// Define the allowed boundaries [SW,NE]
	var zoomBounds = [
		[-35,-20,35,20],
		[-70,-100,70,100],
		[-75,-120,75,120],
		[-80,-130,80,130],
		[-85,-135,85,135],
		[-87,-140,87,140],
	];
	var swlat = zoomBounds[zoom-2][0];
	var swlng = zoomBounds[zoom-2][1];
	var nelat = zoomBounds[zoom-2][2];
	var nelng = zoomBounds[zoom-2][3];
	
	// Set the new bounds
	allowedBounds = new google.maps.LatLngBounds(
		new google.maps.LatLng( swlat , swlng ),   //SouthWest Corner
		new google.maps.LatLng( nelat , nelng )    //NorthEast Corner
	);
	
	// Helper function for checking the sign of a variable
	function sign( x ) { return x > 0 ? 1 : x < 0 ? -1 : 0; }
		
	// If changing the zoom has put us out of bounds, move
	if ( !allowedBounds.contains( center ) ) {
		
		// Figure out which dimension is out of bounds
		if ( Math.abs( center.lat() ) > Math.abs( swlat ) ) {
			goodLat = sign( center.lat() ) * ( Math.abs( swlat ) - 0.01 );
		} else {
			goodLat = center.lat();
		}
		if ( Math.abs( center.lng() ) > Math.abs( swlng ) ) {
			goodLng = sign( center.lng() ) * ( Math.abs( swlng ) - 0.01 );
		} else {
			goodLng = center.lng();
		}
		
		// Set some new good bounds
		var goodBounds = new google.maps.LatLng( goodLat , goodLng );
		esomap.panTo( goodBounds );
	}
	
	return allowedBounds;
}

/* Remove markers from the map
--------------------------------------------------*/
function clear_markers() {
	for (var i = 0; i < activeMarkers.length; i++ ) {
		activeMarkers[i].setMap(null);
	}
	activeMarkers = [];
}

/* Hide or display markers depending on the zoom level
--------------------------------------------------*/
function maybe_hide_markers() {
	
	// Get the minimum allowed zoom for marker visibility
	var visibility	= true;
	var minZoom 	= ( esomap.getMapTypeId() == 'tamriel' ) ? 3 : 1;
	
	// Hide markers for big picture
	if ( esomap.getZoom() <= minZoom )
		visibility = false; 
		
	// Set the visibility status for the zoom level
	for (var i = 0; i < activeMarkers.length; i++ ) {
		activeMarkers[i].setVisible(visibility);
	}
}

/* Get zone coordinates to pan
--------------------------------------------------*/
function get_zone_coords( zone ) {

	// Make cyrodiil the default
	var defaultZone = ( esomap.getMapTypeId() == 'tamriel' ) ? 'tamriel' : 'coldharbour';
	zone = ( zone === "" ) ? defaultZone : zone;

	// Supply the central coordinates for the requested zone [lat,lng,zoom]
	var coords			= {};
	
	// Tamriel coordinates
	coords.tamriel		= [0.00,0.00,2];
	coords.roost 		= [-68.00,1.20,6];
	coords.auridon 		= [-48.93,-76.40,5];
	coords.grahtwood 	= [-55.42,-18.81,5];
	coords.greenshade 	= [-48.70,-46.90,5];
	coords.malabal 		= [-33.40,-39.50,5];
	coords.reapers 		= [-29.88,-12.00,5];

	coords.stros 		= [-12.43,-96.52,6];
	coords.betnikh 		= [16.06,-120.61,6];
	coords.glenumbra	= [37.04,-121.27,5];
	coords.stormhaven	= [46.85,-80.66,5];
	coords.rivenspire	= [60.13,-92.49,5];
	coords.bangkorai	= [42.76,-59.81,5];
	coords.alikr		= [29.71,-90.13,5];
	
	coords.bleakrock 	= [56.20,53.80,7];
	coords.balfoyen 	= [16.10,95.10,7];
	coords.stonefalls 	= [15.15,74.00,5];
	coords.deshaan 		= [-3.95,85.33,5];
	coords.shadowfen 	= [-22.25,77.75,5];
	coords.therift 		= [35.00,38.00,5];
	coords.eastmarch 	= [50.60,38.75,5];

	coords.cyrodiil 	= [0.52,16.82,4];
	coords.tactics 		= [0,0,4];
	coords.crafting 	= [0,0,4];
	
	// Coldharbour coordinates
	coords.coldharbour 	= [0.00,0.00,2];
	
	// Return the coordinates
	return coords[zone];
}

/* Get the currently applied filters
--------------------------------------------------*/
function get_filtered_types() {
	
	var filters = [ 'locales' , 'skyshard' , 'lorebook' , 'boss' , 'treasure' ];
	$( '#marker-filters :checked' ).each( function() {
		for( i = filters.length; i >= 0; i-- ) {
			if ( $(this).val() == filters[i] )	filters.splice( i , 1 );
		}
     });
	 return filters;
}

/* Place markers onto the map
--------------------------------------------------*/
function get_markers() {

	// First clear any existing markers
	clear_markers();
	
	// Get the current map and requested zone
	var map		= esomap.getMapTypeId();
	var zone 	= $( 'select#zone-select :selected' ).attr('value');
	
	// Change the map to Coldharbour if it is requested
	if ( zone === 'coldharbour' ) {
		if ( map !== 'coldharbour' ) {
			esomap.setMapTypeId( 'coldharbour' );
			return;
		}
	}
		
	// Otherwise we are in Tamriel
	else if ( zone !== '' ) {
		if ( map !== 'tamriel' ) {
			esomap.setMapTypeId( 'tamriel' );
			return;
		}
	}
	
	// Check what is filtered
	var filters = get_filtered_types();
	var filterTypes = [ 'skyshard' , 'lorebook' , 'boss' , 'treasure' ];
	
	// Pan to the selected zone and set an appropriate zoom level
	coords = get_zone_coords( zone );
	zoneCoords = new google.maps.LatLng( coords[0] , coords[1] );
	esomap.setZoom( coords[2] );
	esomap.panTo( zoneCoords );
		
	// Get markers for that zone
	$.getScript( assets + 'zones/' + zone + '.js' , function() {

		// Loop through each marker - [ 'name' , 'description' , 'type' , 'zone' , 'lat' , 'lng' ]
		for (i = 0; i < locations.length; i++) {
		
			// Make sure the marker is appropriately filtered
			var toFilter = locations[i][1];
			if ( filterTypes.indexOf(toFilter) === -1 ) toFilter = "locales";
			
			var shown = true;
			for ( j = 0; j < filters.length; j++ ) {
				if ( toFilter == filters[j] ) {
					shown = false;
				}
			}
			
			// Skip the marker if it's not in the filters
			if ( shown === false ) {
				continue;
			}
			
			// Choose an icon
			var image = {
				url: 		assets + "icons/" + locations[i][1] + ".png?ver=" + iconver,
				size:		new google.maps.Size(24,24),
				origin:		new google.maps.Point(0,0),
				anchor: 	new google.maps.Point(12,12)
			};
			
			// Define the marker
			marker = new google.maps.Marker({
				position: 	new google.maps.LatLng(locations[i][3], locations[i][4]),
				map: 		esomap,
				id: 		i,
				title: 		$('<div/>').html(locations[i][0]).text(),
				desc: 		locations[i][2],
				type: 		locations[i][1],
				icon: 		image,
			});
			
			// Add it to the active markers array
			activeMarkers.push(marker);
			
			// Set up a click listener for it
			google.maps.event.addListener( marker, 'click', ( function() {
			
				// Use a jQuery trick to include HTML
				var desc = $('<div />').html(this.desc).text();
				var title = $('<div />').html(this.title).text();
			
				// Set up some HTML for the info window
				var marker_content = '<div class="marker-window">'+
				'<h3 class="marker-title">' + title + '</h3>'+
				'<p class="marker-content">' + desc + '</p>'+
				'</div>';	
			
				// Show the tooltip
				infowindow.setContent( marker_content );
				infowindow.open( esomap, this );
			}));
		}
	});
}
