/**
* Federal/State locale switch on search form
*/
var LocalSwitch = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.selectors = {
		switch : 'data-locale-select'
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', '[' + plugin.selectors.switch + '] a', function(e){
			e.preventDefault();
			plugin.switch($(this));
		});
	}

	/**
	* Update the locale
	*/
	plugin.switch = function(button)
	{
		var current = $(button).attr('href');
		$(current).prop('checked', true);	
		$('[' + plugin.selectors.switch + '] a').removeClass('active');
		$(button).addClass('active');

		if ( current == "#state" ){
			$('.switch').addClass('state');
			return;
		}
		$('.switch').removeClass('state');
	}

	return plugin.bindEvents();
}