/*!	Setup Googletag Ads
========================================================================== */
var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
var gads = document.createElement('script');
gads.async = true;
gads.type = 'text/javascript';
var useSSL = 'https:' == document.location.protocol;
gads.src = (useSSL ? 'https:' : 'http:') + 
'//www.googletagservices.com/tag/js/gpt.js';
var node = document.getElementsByTagName('script')[0];
node.parentNode.insertBefore(gads, node);
})();

/*! Initialize ads
========================================================================== */
$(document).ready(function(){
	googletag.cmd.push(function() {
	googletag.defineSlot('/1045124/_TF_Leaderboard', [728, 90], 'div-gpt-ad-1414005680010-0').addService(googletag.pubads());
	// googletag.defineSlot('/1045124/_TF_Sidebar', [300, 250], 'div-gpt-ad-1414005680010-1').addService(googletag.pubads());
	googletag.pubads().enableSingleRequest();
	googletag.enableServices();
	});
});