/*! Update Profile Race Dropdown */
function updateRaceDropdown( context ) {	
	
	if ( 'faction' == context ) {
		factionid 	= jQuery( 'select#faction :selected' ).val();
		jQuery( 'select#race option' ).not( '.'+factionid ).attr('disabled','disabled').removeAttr('selected');
		jQuery( 'select#race option.' + factionid ).removeAttr('disabled');
		jQuery( 'select#race option:first-child' ).removeAttr('disabled');
	} 
	
	else if ( 'race' == context ) {
		raceid	 = jQuery( 'select#race :selected' ).attr('class');
		if ( undefined != raceid ) {
			jQuery( 'select#faction option' ).removeAttr('selected');
			jQuery( 'select#faction option.' + raceid ).attr('selected','selected');
		}
	}
}