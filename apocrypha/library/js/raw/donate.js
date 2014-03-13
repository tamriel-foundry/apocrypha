/*! Donation Widget Verification */
$('form#donation-form').submit(function(){

	var user_id = $(this).children('input[name=user_id]').val();
	if ( parseInt( user_id ) === 0 ) { 
		if ( confirm( 'If you wish to recieve credit for your donation, please log in first. You may continue and donate anonymously if you wish!' ) ) return true; 
		else return false;
	}
	return true;
});
