/*! Tabbed Containers */
$('div.tab-content').not('div.active').hide();
$('ul.tabs li a').click(function(){
	if( !$(this).parent().hasClass('current') && !$(this).parent().hasClass('disabled')) {
		var target = $(this).attr('href');
		$('ul.tabs li').removeClass('current');	
		$(this).parent().addClass('current');
		$('div.tab-content').hide();
		$(target).fadeIn('slow');
	}
	return false;
});