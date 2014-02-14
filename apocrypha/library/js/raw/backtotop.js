/*! Back To Top Link Scrolling */
$('a.backtotop').click(function () {
	$('html, body').animate({scrollTop: 0 }, 600);
	return false;
});

/*! Scroll To Bottom */
$('a.downtobottom').click(function () {
	$('html, body').animate({scrollTop: $(document).height() }, 600);
	return false;
});