/**
* Toggles 
*/
var Toggle = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.activeBtn = '';
	plugin.activeBlock = '';

	plugin.selectors = {
		toggleBtn : '[data-toggle]',
		toggleBlock : '[data-toggle-id]'
	}

	plugin.bindEvents = function()
	{
		$(document).on('click', plugin.selectors.toggleBtn, function(e){
			e.preventDefault();
			plugin.activeBtn = $(this);
			plugin.toggle();
		});
	}

	plugin.toggle = function()
	{
		var target = $(plugin.activeBtn).attr('data-toggle');
		var all_targets = $(plugin.selectors.toggleBlock);

		if ( $('*[data-toggle-id=' + target + ']').is(':visible') ){
			$('*[data-toggle-id=' + target + ']').hide();
			plugin.toggleArrow(true);
			$(document).trigger('element-toggled-hidden', [plugin.activeBtn, $(target)]);
			return;
		}

		$('*[data-toggle-id=' + target + ']').show();
		plugin.toggleArrow(false);
		$(document).trigger('element-toggled-shown', [plugin.activeBtn, $(target)]);
	}

	plugin.toggleArrow = function(closed)
	{
		if ( closed ){
			$(plugin.activeBtn).find('.icon-arrow_up').removeClass('icon-arrow_up').addClass('icon-arrow_down');
			return;
		}
		$(plugin.activeBtn).find('.icon-arrow_down').removeClass('icon-arrow_down').addClass('icon-arrow_up');
	}

	return plugin.bindEvents();
}