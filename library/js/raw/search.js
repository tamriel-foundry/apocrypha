/*! Advanced Search Fields */
$( 'ol.adv-search-fields' ).not( 'ol.active' ).hide();
$( 'select#search-for' ).bind( 'change', function() {

	// Hide old search results
	$( '#search-results' ).slideUp();
	
	// Get the new field set
	var type 		= $( 'select#search-for' ).val();
	var target		= $( 'ol#adv-search-' + type );
	
	// Hide the unused fields
	$( 'ol.adv-search-fields' ).removeClass( 'active' ).hide();
	
	// Show the relevant fields
	target.addClass( 'active' ).fadeIn();
});