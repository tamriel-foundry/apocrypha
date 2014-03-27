/*!	Quantcast Tag - Just before the </head> Tag
========================================================================== */
var _qevents = _qevents || [];
(function() {
	var elem = document.createElement('script');
	elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
	elem.async = true;
	elem.type = "text/javascript";
	var scpt = document.getElementsByTagName('script')[0];
	scpt.parentNode.insertBefore(elem, scpt);  
})();

/*!	AdTag - In The Head Tag
========================================================================== */
var p="http",d="static";if(document.location.protocol=="https:"){p+="s";d="engine";}var z=document.createElement("script");z.type="text/javascript";z.async=true;z.src=p+"://"+d+".adzerk.net/ados.js";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(z,s);
var ados = ados || {};
ados.run = ados.run || [];
ados.run.push(function() {
	/* load placement for account: LMN, site: Tamriel Foundry, size: 728x90 - Leaderboard*/
	ados_add_placement(8074, 58682, "azk60495", 4);
	ados_load();
});