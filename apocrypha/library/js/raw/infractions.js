/*! Clear Infraction */
$("a.clear-infraction").click( function() {
		
	// Get some info about what we are doing
	var button	= $(this);
	var nonce	= get_url_var( button.attr('href') , '_wpnonce' );
	var id 		= get_url_var( button.attr('href') , 'id' );
	
	// Disable the button
	button.html('<i class="icon-spinner icon-spin"></i>Deleting').attr("disabled","disabled");

	// Submit the POST AJAX
	$.post( apoc_ajax, { 
			'action': 'apoc_clear_infraction',
			'_wpnonce': nonce,
			'id' : id,
		},
		function( resp ) {
			if( resp == 1 ) {
				$( "li#infraction-" + id ).slideUp();
			}
		}
	);
		
	// Prevent the default pageload
	return false;
});

/*! Clear Mod Notes */
$("a.clear-mod-note").click( function() {
		
	// Get some info about what we are doing
	var button	= $(this);
	var nonce	= get_var_in_url( button.attr('href') , '_wpnonce' );
	var id 		= get_var_in_url( button.attr('href') , 'id' );
	
	// Disable the button
	button.html('<i class="icon-spinner icon-spin"></i>Deleting').attr("disabled","disabled");

	// Submit the POST AJAX
	$.post( apoc_ajax, { 
			'action': 'apoc_clear_mod_note',
			'_wpnonce': nonce,
			'id' : id,
		},
		function( resp ) {
			if( resp == 1 ) {
				$( "li#modnote-" + id ).slideUp();
			}
		}
	);
		
	// Prevent the default pageload
	return false;
});

