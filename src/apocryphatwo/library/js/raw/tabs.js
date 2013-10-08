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

/*! Collapsing FAQ Containers */
$('div.faq-section header').append('<button class="faq-collapse"><i class="icon-expand"></i>Expand Section</button>');
$('div.faq-section ul , div.faq-references ol').hide();
$('button.faq-collapse').click(function() {
	var oldtext = newtext = '';
	$(this).parent().siblings('ul'|'ol').slideToggle(500,"swing");
	oldtext = $(this).text();
	newtext = ( oldtext == "Expand Section" ) ? '<i class="icon-collapse"></i>Collapse Section' : '<i class="icon-expand"></i>Expand Section';
	$(this).text(newtext);
});