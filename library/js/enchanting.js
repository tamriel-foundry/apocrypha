//description prefix
var add = "Adds ";
var deal = "Deals ";
var reduce = "Reduce ";
var increase = "Increase ";

//checkbox quick selection
var pot = "input.potency-rune";
var ess = "input.essence-rune";
var asp = "input.aspect-rune";

//effect value
var x = $('.effect-value');

//array index numbers
var table = 0;
var quality = 0;
var level = 0;
var craftExp = 0;
var craftExpQuality = 0;

var MyArray = [];
//Default values 0
MyArray[0] = [
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"],
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"],
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"],
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"],
	["?","?","?","?","?","?","?","?","?","?","?","?","?","?"]
];
//Add Magicka/Stamina 2
MyArray[1] = [
	[7,12,17,29,35,41,47,52,58,64,66,70,74,86],
	[9,14,19,31,37,43,49,54,60,66,68,72,76,88],
	[12,17,22,34,40,44,52,57,63,69,71,75,79,91],
	[15,20,25,37,43,47,55,60,66,72,74,78,82,94],
	[18,23,28,40,46,50,58,63,69,75,78,81,85,97]
];
//Add Health 1
MyArray[2] = [
	[10,18,25,43,52,61,70,78,87,96,99,105,111,129],
	[13,21,28,46,55,64,73,81,90,99,102,108,114,132],
	[18,25,33,51,60,66,78,85,94,103,106,112,118,136],
	[22,30,37,55,64,70,82,90,99,108,111,117,123,141],
	[27,34,42,60,69,75,87,94,103,112,115,121,127,145]
];
//Deal type of damage 5
MyArray[3] = [
	[3,4,7,10,12,15,18,21,24,30,31,32,33,40],
	[4,6,9,12,14,17,20,23,26,32,33,34,35,42],
	[5,8,11,14,16,19,22,25,28,34,35,36,37,44],
	[9,12,15,18,20,23,26,29,32,38,39,40,41,48],
	[14,17,20,23,25,28,31,34,37,43,44,45,46,53]
];
//Increase and reduce power 2
MyArray[4] = [
	[1,1,2,2,3,3,4,4,5,6,6,7,8,10],
	[2,2,3,3,4,4,5,5,6,7,7,8,9,11],
	[3,3,4,4,5,5,6,6,7,8,8,9,10,12],
	[4,4,5,5,6,6,7,7,8,9,9,10,11,13],
	[5,5,6,6,7,7,8,8,9,10,10,11,12,14]
];
//reduce armor, adds armor, adds spell resist 3
MyArray[5] = [
	[20,30,40,50,60,70,80,90,100,110,114,118,122,134],
	[30,50,70,90,110,130,150,170,190,210,218,226,234,258],
	[40,70,100,130,160,190,220,250,280,310,322,334,346,382],
	[50,90,130,170,210,250,290,330,370,410,426,442,458,506],
	[60,110,160,210,260,310,360,410,460,510,530,550,570,630]
];
//Deal magicka damage + unresistable damage 4
MyArray[6] = [
	[2,3,5,7,9,11,13,15,18,22,23,24,24,30],
	[3,4,6,9,10,12,15,17,19,24,24,25,26,31],
	[3,6,8,10,12,14,16,18,21,25,26,27,27,33],
	[6,9,11,13,15,17,19,21,24,28,29,30,30,36],
	[10,12,15,17,18,21,23,25,27,32,32,33,34,39]
];
//Spell and weapon damage 2
MyArray[7] = [
	[1,1,2,2,3,3,4,4,5,6,7,8,9,10],
	[2,2,3,3,4,4,5,5,6,7,8,9,10,10],
	[3,3,4,4,5,5,6,6,7,8,9,10,11,12],
	[4,4,5,5,6,6,7,7,8,9,10,11,12,13],
	[5,5,6,6,7,7,8,8,9,10,11,12,13,14]
];
//Health, stamina, magicka recovery 3
MyArray[8] = [
	[1,2,3,4,5,6,7,8,9,10,11,12,13,15],
	[2,3,4,5,6,7,8,9,10,11,12,13,14,16],
	[3,4,5,6,7,8,9,10,11,12,13,14,15,17],
	[4,5,6,7,8,9,10,11,12,13,14,15,16,18],
	[5,6,7,8,9,10,11,12,13,14,15,16,17,19]
];
//bash damage 1
MyArray[9] = [
	[2,5,7,10,12,15,17,20,22,25,26,27,28,31],
	[3,5,8,10,13,15,18,20,23,25,26,27,28,31],
	[3,6,8,11,13,16,18,21,23,26,27,28,29,32],
	[4,6,9,11,14,16,19,21,24,26,27,28,29,32],
	[4,7,9,12,14,17,19,22,24,27,28,29,30,33]
];
//potion boost 1
MyArray[10] = [
	[10,20,30,40,50,60,70,80,90,100,104,108,112,124],
	[12,22,32,42,52,62,72,82,92,102,106,110,114,126],
	[14,24,34,44,54,64,74,84,94,104,108,112,116,128],
	[16,26,36,46,56,66,76,86,96,106,110,114,118,130],
	[18,28,38,48,58,68,78,88,98,108,112,116,120,132]
];
//Type resist 5
MyArray[11] = [
	[25,50,75,100,125,150,175,200,225,250,260,270,280,310],
	[50,100,150,200,250,300,350,400,450,500,520,540,560,620],
	[75,150,225,300,375,450,525,600,675,750,780,810,840,930],
	[100,200,300,400,500,600,700,800,900,1000,1040,1080,1120,1240],
	[125,250,375,500,625,750,875,1000,1125,1250,1300,1350,1400,1550]
];
//reduce feat and spell cost 2
MyArray[12] = [
	[3,5,6,7,8,9,10,12,13,14,15,15,15,17],
	[4,6,7,8,9,10,11,13,14,15,16,16,16,18],
	[5,7,8,9,10,11,12,14,15,16,17,17,17,19],
	[6,8,9,10,11,12,13,15,16,17,18,18,18,20],
	[7,9,10,11,12,13,14,16,17,18,19,19,19,21]
];
//reduce bash/block cost 1
MyArray[13] = [
	[4,7,9,10,12,13,15,18,19,21,22,22,22,25],
	[6,9,10,12,13,15,16,19,21,22,24,24,24,27],
	[7,10,12,13,15,16,18,21,22,24,25,25,25,28],
	[9,12,13,15,16,18,19,22,24,25,27,27,27,30],
	[10,13,15,16,18,19,21,24,25,27,28,28,28,31]
];
//recovery from magicka damage (3)
MyArray[14] = [
	[1,1,2,3,4,5,6,7,8,10,10,10,11,13],
	[1,2,3,4,4,5,6,7,8,10,11,11,11,14],
	[1,2,3,4,5,6,7,8,9,11,11,12,12,14],
	[3,4,5,6,6,7,8,9,10,12,13,13,13,16],
	[4,5,6,7,8,9,10,11,12,14,14,15,15,18]
];
//damage shield
MyArray[15] = [
	[6,9,13,16,19,23,26,29,33,36,37,38,40,44],
	[9,16,23,29,36,42,49,56,62,69,71,74,77,85],
	[13,23,33,42,52,62,72,82,92,102,106,110,114,126],
	[16,29,42,56,69,82,85,102,122,135,140,145,151,166],
	[17,36,52,69,85,102,118,135,151,168,174,181,188,207]
];
//second cooldown
MyArray[16] = [
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5],
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5],
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5],
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5],
	[5,5,5,5,5,5,5,5,5,5,5,5,5,5]
];

var craftExpArray = [];
craftExpArray[0] = [165,329,548,987,2632];
craftExpArray[1] = [232,464,773,1392,3715];
craftExpArray[2] = [307,614,1023,1842,4912];
craftExpArray[3] = [403,806,1343,2418,6448];
craftExpArray[4] = [487,974,1623,2922,7792];
craftExpArray[5] = [592,1184,1973,3552,9472];
craftExpArray[6] = [692,1384,2307,4152,11072];
craftExpArray[7] = [814,1628,2713,4884,13024];
craftExpArray[8] = [969,1938,3230,5814,15504];
craftExpArray[9] = [1200,2400,4000,7200,19200];
craftExpArray[10] = [1281,2562,4270,7686,20496];
craftExpArray[11] = [1362,2724,4540,8172,21792];
craftExpArray[12] = [1483,2966,4943,8898,23728];
craftExpArray[13] = [1605,3210,5350,9630,25680];
craftExpArray[14] = ["Crafting Exp"];

var extractExpArray = [];
extractExpArray[0] = [313,625,1042,1875,2500];
extractExpArray[1] = [441,882,1470,2646,3528];
extractExpArray[2] = [586,1172,1953,3516,4688];
extractExpArray[3] = [768,1536,2560,4608,6144];
extractExpArray[4] = [928,1856,3093,5568,7424];
extractExpArray[5] = [1127,2254,3757,6762,9016];
extractExpArray[6] = [1318,2636,4393,7908,10544];
extractExpArray[7] = [1552,3104,5173,9312,12416];
extractExpArray[8] = [1845,3690,6150,11070,14760];
extractExpArray[9] = [2284,4568,7613,13704,18272];
extractExpArray[10] = [2437,4874,8123,14622,19496];
extractExpArray[11] = [2591,5182,8637,15546,20728];
extractExpArray[12] = [2744,5488,9147,16464,21952];
extractExpArray[13] = [3282,6564,10940,19692,26256];
extractExpArray[14] = ["Extraction Exp"];

//selection additive/subtractive + reset
$('#selector').change(function() {
	$('.hideornot').hide();
	$('.' + $(this).val()).show();
	$('.prefix').text('(select) ');
	$('.glyph-lvl').text('Level 0 - 0');
	$('.glyph-type').text('Type');
	$('.suffix').text(' (select)');
	$('.glyph-name').css({'color':"#ccc"});
	$('.desc1').text('');
	$('.desc2').text('');
	$('.desc3').text('');
	$('.effect-value2').text('');	
	$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/noglyph.png)");
	$(pot).prop('checked', false);
	$(ess).prop('checked', false);
	$(asp).prop('checked', false);
	table = 0;
	quality = 0;
	level = 0;
	craftExp = 14;
	craftExpQuality = 0;
});

//potency rune prefix, glyph level, level array
$(pot).click(function() {
	if(this.checked) {
		$('.prefix').text($(this).attr('data-prefix'));
		$('.glyph-lvl').text($(this).attr('data-level'));
		level = parseInt($(this).val(), 10);
		craftExp = parseInt($(this).val(), 10);
	}
});

//aspect rune change color, get quality array
$(asp).click(function() {
	if(this.checked) {
		$('.glyph-name').css({'color':$(this).attr('data-quality-color')});
		quality = parseInt($(this).val(), 10);
		craftExpQuality = parseInt($(this).val(), 10);
	};
});

//essence rune add suffix, get table, glyph image
$(ess).click(function () {
	if(this.checked) {
		$('.suffix').text($(this).attr('data-suffix'));
		table = parseInt($(this).val(), 10);
	};
	//recipes
	if($(this).attr('data-suffix') === " Health Regen" || $(this).attr('data-suffix') === " Stamina Regen" || $(this).attr('data-suffix') === " Magicka Regen") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(add);
		//$('.effect-value').text('x');
		$('.desc2').text($(this).attr('data-desc') + " Recovery");
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Increase Magical Harm" || $(this).attr('data-suffix') === " Increase Physical Harm") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(add);
		$('.desc2').text($(this).attr('data-desc') + " Damage");
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Potion Boost" || $(this).attr('data-suffix') === " Bashing") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(increase + $(this).attr('data-desc'));
		$('.desc2').text('');
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Frost" || $(this).attr('data-suffix') === " Foulness" || $(this).attr('data-suffix') === " Poison" || $(this).attr('data-suffix') === " Shock" || $(this).attr('data-suffix') === " Decrease Health" || $(this).attr('data-suffix') === " Flame") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text(deal);
		$('.desc2').text($(this).attr('data-desc'));
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Frost Resist" || $(this).attr('data-suffix') === " Disease Resist" || $(this).attr('data-suffix') === " Poison Resist" || $(this).attr('data-suffix') === " Decrease Spell Harm" || $(this).attr('data-suffix') === " Shock Resist" || $(this).attr('data-suffix') === " Fire Resist" || $(this).attr('data-suffix') === " Decrease Physical Harm") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(add);
		$('.desc2').text($(this).attr('data-desc'));
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Reduce Spell Cost" || $(this).attr('data-suffix') === " Reduce Feat Cost") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text('');
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Crushing" || $(this).attr('data-suffix') === " Weakening") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text(' for ');
		$('.effect-value2').text('5');
		$('.desc3').text(' seconds');
	} else if ($(this).attr('data-suffix') === " Potion Speed") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text(' seconds');
		$('.effect-value2').text('');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Shielding") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/jewelryglyph.png)");
		$('.glyph-type').text('(Jewelry)');
		$('.desc1').text(reduce + $(this).attr('data-desc'));
		$('.desc2').text('');
		$('.effect-value2').text('5');
		$('.desc3').text('');
	} else if ($(this).attr('data-suffix') === " Rage") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text(increase + $(this).attr('data-desc'));
		$('.desc2').text(' for ');
		$('.effect-value2').text('5');
		$('.desc3').text(' seconds');
	} else if ($(this).attr('data-suffix') === " Hardening") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text('Grants a ');
		$('.desc2').text(' point Damage Shield for ');
		$('.effect-value2').text('5');
		$('.desc3').text(' seconds');
	} else if($(this).attr('data-suffix') === " Absorb Health" || $(this).attr('data-suffix') === " Absorb Magicka" || $(this).attr('data-suffix') === " Absorb Stamina") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/weaponglyph.png)");
		$('.glyph-type').text('(Weapon)');
		$('.desc1').text(deal);
		$('.desc2').text(' Magic Damage and recovers ');
		$('.effect-value2').text(MyArray[14][quality][level]);
		$('.desc3').text($(this).attr('data-desc'));
	}
	//Armor glyphs
	if($(this).attr('data-suffix') === "Stamina" || $(this).attr('data-suffix') === "Magicka") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.glyph-type').text('(Armor)');
		$('.desc1').text(add);
		$('.desc2').text(' Max ' + $(this).attr('data-suffix'));
	} else if($(this).attr('data-suffix') === " Health") {
		$('.glyph-img').css("background-image", "url(http://tamrielfoundry.com/wp-content/uploads/2014/10/armorglyph.png)");
		$('.glyph-type').text('(Armor)');
		$('.desc1').text(add);
		$('.desc2').text(' Max ' + $(this).attr('data-suffix'));
	}
});
//global click update important
$(document).click(function() {
    $('.effect-value').text(MyArray[table][quality][level]);
    $('.glyph-crafting-exp').text(craftExpArray[craftExp][craftExpQuality]);
    $('.glyph-extraction-exp').text(extractExpArray[craftExp][craftExpQuality]);
});