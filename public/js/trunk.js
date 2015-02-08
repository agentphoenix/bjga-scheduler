$(function() {

	var items = $('.slideRight, .slideLeft');
	var content = $('.content');
	
	var open = function()
	{
		$(items).removeClass('trunkClose').addClass('trunkOpen');
	}
	var close = function()
	{ 
		$(items).removeClass('trunkOpen').addClass('trunkClose');
	}

	$('#navToggle').click(function()
	{
		if (content.hasClass('trunkOpen')) {$(close)}
		else {$(open)}
	});
	
	content.click(function()
	{
		if (content.hasClass('trunkOpen')) {$(close)}
	});

});